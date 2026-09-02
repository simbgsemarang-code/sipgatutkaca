<?php
$val = function ($kunci) use ($row) {
	if (!isset($row[$kunci]) || $row[$kunci] === null) { return ''; }
	return htmlspecialchars((string) $row[$kunci], ENT_QUOTES, 'UTF-8');
};
$opt = function ($kunci, $nilai) use ($row) {
	return (isset($row[$kunci]) && (string) $row[$kunci] === $nilai) ? 'selected' : '';
};

// Dokumen yang sudah terunggah, dikunci per label supaya loop
// checklist di bawah bisa menunjukkan status "Terunggah"/"Ditolak"
// per slug (bukan cuma daftar yang ditolak saja).
$dokumen_by_label = array();
foreach ($dokumen as $d) {
	$dokumen_by_label[$d['jenis_dokumen']] = $d;
}

// Sedang merespons tanda TPA (perbaikan_dokumen/...konsultasi), atau
// PU/pemohon mengedit sendiri tanpa diminta (masih verifikasi_dokumen)?
// Menentukan judul/teks halaman + kartu catatan TPA di bawah - lihat
// Pengajuan_pbg::_ambil_bisa_diedit().
$sedang_merespons_tpa = in_array($row['status'], array('perbaikan_dokumen', 'perbaikan_dokumen_konsultasi'), TRUE);

$label_status_lanjut = ($row['status'] === 'perbaikan_dokumen_konsultasi') ? 'Menunggu Jadwal Konsultasi' : 'Verifikasi Kelengkapan Dokumen';

// Bidang yang catatannya masih relevan ditampilkan di kartu "Catatan
// dari TPA" - cuma yang statusnya masih perbaikan_dokumen/...konsultasi
// (baris yang statusnya 'disetujui' bukan alasan permohonan ini perlu
// diperbaiki, jadi tidak ditampilkan di sini).
$label_bidang = array(
	'tpa_arsitek'  => 'Bidang Arsitektur & Tata Kota',
	'tpa_struktur' => 'Bidang Struktur & Sipil',
	'tpa_mep'      => 'Bidang Mekanikal, Elektrikal & Perpipaan (MEP)',
);
$catatan_blocking = array();
foreach ($persetujuan as $kode_bidang => $p) {
	if (in_array($p['status'], array('perbaikan_dokumen', 'perbaikan_dokumen_konsultasi'), TRUE) && !empty($p['catatan'])) {
		$catatan_blocking[$kode_bidang] = $p;
	}
}
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $sedang_merespons_tpa ? 'Perbaiki Permohonan PBG' : 'Edit Permohonan PBG'; ?> — Portal PU · SIP Gatutkaca</title>
<link rel="icon" type="image/png" href="<?php echo base_url('assets/img/icon.png'); ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
<style>
:root{
  --gold-500:#C9A24B;--gold-300:#E4C87B;--gold-100:#F3E3B8;
  --display:'Marcellus',serif;--body:'Plus Jakarta Sans',system-ui,sans-serif;
}
html[data-theme="dark"]{
  --bg:#081826;--bg-alt:#0C2236;--surface:#0C2236;--surface-hi:#123249;
  --text:#F8F4EA;--muted:#B9C7D2;--line:rgba(201,162,75,.28);
  --head-bg:rgba(8,24,38,.94);--head-grad:rgba(8,24,38,.85);
  --foot:#050F19;--input:#0F2A40;--shadow:rgba(0,0,0,.5);
}
html[data-theme="light"]{
  --bg:#FDFBF5;--bg-alt:#F6F1E3;--surface:#FFFFFF;--surface-hi:#FAF5E8;
  --text:#152A3B;--muted:#4E6070;--line:rgba(160,124,45,.35);
  --head-bg:rgba(253,251,245,.94);--head-grad:rgba(253,251,245,.85);
  --foot:#122536;--input:#FFFFFF;--shadow:rgba(21,42,59,.18);
  --gold-500:#A57E2C;--gold-300:#8F6C1F;--gold-100:#6E5314;
}
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
body{font-family:var(--body);background:var(--bg);color:var(--text);line-height:1.7;font-weight:300;transition:background .4s,color .4s}
img{display:block;max-width:100%}
a{color:inherit;text-decoration:none}
.wrap{max-width:1180px;margin:0 auto;padding:0 28px}

header{position:fixed;inset:0 0 auto 0;z-index:60;transition:.4s;background:linear-gradient(180deg,var(--head-grad),transparent)}
header.scrolled{background:var(--head-bg);backdrop-filter:blur(12px);box-shadow:0 1px 0 var(--line)}
.nav{display:flex;align-items:center;justify-content:space-between;height:84px;gap:18px}
.brand{display:flex;align-items:center;gap:13px;flex:0 0 auto}
.brand img{height:50px;width:auto;filter:drop-shadow(0 2px 6px var(--shadow))}
.brand-name{font-family:var(--display);font-size:1.2rem;letter-spacing:.13em;color:var(--gold-300)}
.brand-sub{font-size:.6rem;letter-spacing:.3em;text-transform:uppercase;color:var(--muted)}
.auth-actions{display:flex;align-items:center;gap:14px}

.user-menu{position:relative}
.user-menu-btn{display:flex;align-items:center;gap:8px;background:none;border:none;font-family:var(--body);font-size:.78rem;letter-spacing:.08em;color:var(--gold-300);font-weight:600;cursor:pointer;padding:8px 2px}
.user-menu-btn svg{transition:transform .25s}
.user-menu-btn[aria-expanded="true"] svg{transform:rotate(180deg)}
.user-menu-panel{position:absolute;top:calc(100% + 12px);right:0;min-width:190px;background:var(--surface);border:1px solid var(--line);box-shadow:0 16px 42px var(--shadow);padding:8px;opacity:0;transform:translateY(8px);pointer-events:none;transition:.25s;z-index:70}
.user-menu-panel.open{opacity:1;transform:none;pointer-events:auto}
.user-menu-panel a{display:flex;align-items:center;gap:10px;padding:11px 14px;font-size:.76rem;letter-spacing:.04em;color:var(--text)}
.user-menu-panel a:hover{background:var(--surface-hi);color:var(--gold-300)}
.user-menu-panel a.logout:hover{color:#E0526B;background:rgba(224,82,107,.08)}

.dash-layout{display:flex;padding-top:84px}
.dash-sidebar{width:240px;flex:0 0 240px;height:calc(100vh - 84px);position:sticky;top:84px;background:var(--surface);border-right:1px solid var(--line);padding:40px 0;display:flex;flex-direction:column;justify-content:space-between;overflow-y:auto}
.dash-sidebar nav{display:flex;flex-direction:column;gap:4px}
.dash-sidebar a{display:flex;align-items:center;gap:12px;padding:14px 28px;font-size:.78rem;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);border-left:3px solid transparent;transition:.25s}
.dash-sidebar a:hover{color:var(--text);background:var(--surface-hi)}
.dash-sidebar a.active{color:var(--gold-300);border-left-color:var(--gold-500);background:var(--surface-hi)}
.dash-sidebar a.logout:hover{color:#E0526B;background:rgba(224,82,107,.08)}
.dash-main{flex:1;min-width:0}
.dash-wrap{max-width:900px;margin:0;padding:0 44px}
@media(max-width:860px){
  .dash-layout{flex-direction:column}
  .dash-sidebar{width:100%;flex:0 0 auto;height:auto;min-height:0;flex-direction:row;justify-content:space-between;align-items:center;border-right:none;border-bottom:1px solid var(--line);padding:0}
  .dash-sidebar nav{flex-direction:row;justify-content:center;flex-wrap:wrap}
  .dash-sidebar a{padding:14px 20px;border-left:none;border-bottom:3px solid transparent}
  .dash-sidebar a.active{border-left-color:transparent;border-bottom-color:var(--gold-500)}
  .dash-wrap{padding:0 24px}
}

.btn{display:inline-block;padding:15px 34px;font-size:.78rem;letter-spacing:.26em;text-transform:uppercase;transition:.3s;cursor:pointer;border:none;font-family:var(--body)}
.btn-gold{background:linear-gradient(135deg,#C9A24B,#E4C87B);color:#081826;font-weight:600}
.btn-gold:hover{filter:brightness(1.08);transform:translateY(-2px)}
.btn-ghost{border:1px solid var(--line);color:var(--text);background:transparent}
.btn-ghost:hover{border-color:#C9A24B;color:#E4C87B}
.btn-sm{padding:11px 26px;font-size:.72rem;letter-spacing:.2em}

section{padding:60px 0 100px}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.6rem,3vw,2.2rem);line-height:1.2}
.section-lead{color:var(--muted);max-width:66ch;margin-top:14px}
.tag{display:inline-block;border:1px solid var(--line);padding:4px 14px;font-size:.68rem;letter-spacing:.16em;text-transform:uppercase;color:var(--gold-300);margin-top:14px}
.tag-verifikasi_dokumen{color:#5FC2E0;border-color:#1E86A3}
.tag-perbaikan_dokumen{color:#E0526B;border-color:#E0526B}
.tag-perbaikan_dokumen_konsultasi{color:#E0526B;border-color:#E0526B}
.tag-terunggah{color:#6FCF97;border-color:#2EA84F;margin-top:0;padding:3px 10px;font-size:.62rem}
.tag-ditolak{color:#E0526B;border-color:#E0526B;margin-top:0;padding:3px 10px;font-size:.62rem}
.doc-group-title{font-family:var(--display);font-size:1rem;color:var(--gold-300);margin:26px 0 12px}
.doc-group-title:first-child{margin-top:0}

.alert{padding:16px 20px;margin:26px 0 0;font-size:.88rem;border:1px solid}
.alert-ok{background:rgba(46,168,79,.12);border-color:#2EA84F;color:#8CE0A6}
.alert-err{background:rgba(224,82,107,.12);border-color:#E0526B;color:#F3AEB9}

.catatan-box{background:rgba(240,160,72,.1);border:1px solid #B4573B;padding:22px 26px;margin-top:26px}
.catatan-box p{font-size:.92rem;white-space:pre-line}
.catatan-box .meta{margin-top:10px;font-size:.78rem;color:var(--muted)}

.wiz-card{background:var(--surface);border:1px solid var(--line);padding:40px;margin-top:26px}
.wiz-card h3{font-family:var(--display);font-weight:400;font-size:1.2rem;margin-bottom:18px}
.field{margin-bottom:22px}
.field label{display:block;font-size:.72rem;letter-spacing:.16em;text-transform:uppercase;color:var(--muted);margin-bottom:9px}
.field input,.field select,.field textarea{width:100%;background:var(--input);border:1px solid var(--line);color:var(--text);padding:12px 14px;font-family:var(--body);font-size:.9rem}
.field input:focus,.field select:focus,.field textarea:focus{outline:1px solid var(--gold-500);border-color:var(--gold-500)}
.field textarea{min-height:80px;resize:vertical}
.field-row{display:grid;grid-template-columns:repeat(2,1fr);gap:20px}
@media(max-width:640px){.field-row{grid-template-columns:1fr}}

.dok-tolak-item{border:1px solid #B4573B;padding:18px 20px;margin-bottom:16px}
.dok-tolak-item .nama{font-weight:500;font-size:.92rem}
.dok-tolak-item .alasan{color:var(--muted);font-size:.85rem;margin-top:6px;background:var(--bg-alt);padding:8px 12px;border-left:2px solid #E0526B}
.dok-tolak-item input[type=file]{margin-top:12px;font-size:.82rem}

.wiz-nav{margin-top:34px}

.theme-fab{position:fixed;right:26px;bottom:26px;z-index:80;width:56px;height:56px;border-radius:50%;border:1px solid var(--gold-500);background:var(--surface);color:var(--gold-300);cursor:pointer;display:grid;place-items:center;box-shadow:0 8px 26px var(--shadow);transition:transform .3s}
.theme-fab:hover{transform:rotate(24deg)}
.theme-panel{position:fixed;right:26px;bottom:94px;z-index:80;background:var(--surface);border:1px solid var(--line);box-shadow:0 16px 42px var(--shadow);padding:22px;width:230px;opacity:0;transform:translateY(12px);pointer-events:none;transition:.3s}
.theme-panel.open{opacity:1;transform:none;pointer-events:auto}
.theme-panel h5{font-family:var(--display);font-weight:400;letter-spacing:.2em;text-transform:uppercase;font-size:.72rem;color:var(--gold-300);margin-bottom:16px}
.swatches{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.swatch{border:1px solid var(--line);background:none;cursor:pointer;padding:10px;display:grid;gap:8px;justify-items:center;transition:.25s}
.swatch:hover{border-color:var(--gold-500)}
.swatch.sel{border-color:var(--gold-500);box-shadow:0 0 0 1px var(--gold-500)}
.swatch i{width:100%;height:34px;display:block;border:1px solid var(--line)}
.swatch .sw-dark{background:linear-gradient(135deg,#081826,#123249)}
.swatch .sw-light{background:linear-gradient(135deg,#F8F4EA,#EFE7D6)}
.swatch span{font-size:.66rem;letter-spacing:.2em;text-transform:uppercase;color:var(--muted)}

footer{background:var(--foot);color:#F8F4EA;padding:66px 0 32px;border-top:1px solid var(--line)}
.foot-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr;gap:50px}
.foot-grid h4{font-family:var(--display);font-weight:400;letter-spacing:.14em;color:#E4C87B;margin-bottom:18px;font-size:1rem}
.foot-grid p,.foot-grid li{font-size:.88rem;color:#B9C7D2}
.foot-grid ul{list-style:none;display:grid;gap:10px}
.foot-grid a:hover{color:#E4C87B}
.credit{margin-top:50px;padding-top:22px;border-top:1px solid rgba(185,199,210,.15);display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap;font-size:.72rem;color:rgba(185,199,210,.6);letter-spacing:.06em}
@media(max-width:980px){.foot-grid{grid-template-columns:1fr}}
</style>
</head>
<body>

<header id="topbar">
  <div class="wrap nav">
    <a class="brand" href="<?php echo base_url(); ?>" aria-label="Beranda SIP Gatutkaca">
      <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Seal_of_Cilacap_Regency.svg?width=120" alt="Lambang Kabupaten Cilacap">
      <span>
        <span class="brand-name">SIP GATUTKACA</span><br>
        <span class="brand-sub">Kabupaten Cilacap</span>
      </span>
    </a>
    <div class="auth-actions">
      <a class="btn btn-ghost btn-sm" href="<?php echo base_url(); ?>">Beranda</a>
      <div class="user-menu">
        <button class="user-menu-btn" id="userMenuBtn" type="button" aria-expanded="false" aria-controls="userMenuPanel">
          <?php echo htmlspecialchars($nama_pengguna, ENT_QUOTES, 'UTF-8'); ?>
          <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2.5 4.5L6 8l3.5-3.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="user-menu-panel" id="userMenuPanel" role="menu">
          <a href="<?php echo base_url('pengaturan'); ?>" role="menuitem">Pengaturan</a>
          <a href="<?php echo base_url('login/keluar'); ?>" role="menuitem" class="logout">Logout</a>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="dash-layout">
  <aside class="dash-sidebar">
    <nav>
      <a href="<?php echo base_url('pu'); ?>">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><rect x="1" y="1" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="10" y="1" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="1" y="10" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="10" y="10" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/></svg>
        Dashboard
      </a>
      <a href="<?php echo base_url('pengajuan-pbg'); ?>" class="active">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M4 1.5h7L14.5 5v11a1 1 0 01-1 1h-9a1 1 0 01-1-1v-13a1 1 0 011-1z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M11 1.5V5h3.5" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M5.5 9.5h7M5.5 12h7M5.5 7h3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
        Pengajuan PBG
      </a>
      </nav>
    <nav>
      <a href="<?php echo base_url('login/keluar'); ?>" class="logout">Logout</a>
    </nav>
  </aside>
  <div class="dash-main">
<section style="padding-top:100px">
  <div class="dash-wrap">
    <p class="eyebrow"><a href="<?php echo base_url('pengajuan-pbg/lihat/' . (int) $row['id']); ?>" style="color:var(--gold-500);text-decoration:underline">← Kembali ke Detail Permohonan</a></p>
    <h2><?php echo $sedang_merespons_tpa ? 'Perbaiki Permohonan' : 'Edit Permohonan'; ?> — <?php echo htmlspecialchars($row['nama_pemohon'], ENT_QUOTES, 'UTF-8'); ?></h2>
    <span class="tag tag-<?php echo htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8'); ?>"><?php
      echo ($row['status'] === 'perbaikan_dokumen_konsultasi') ? 'Perbaikan Dokumen Konsultasi'
         : (($row['status'] === 'perbaikan_dokumen') ? 'Perbaikan Dokumen' : 'Verifikasi Kelengkapan Dokumen');
    ?></span>
    <?php if ($sedang_merespons_tpa): ?>
      <p class="section-lead">Lengkapi dokumen dan data yang diminta TPA di bawah ini, lalu kirim. Setelah dikirim, status permohonan berubah menjadi <strong><?php echo htmlspecialchars($label_status_lanjut, ENT_QUOTES, 'UTF-8'); ?></strong>.</p>
    <?php else: ?>
      <p class="section-lead">Ubah data atau unggah ulang dokumen mana pun di bawah ini, lalu kirim. Permohonan tetap berstatus Verifikasi Kelengkapan Dokumen - kalau ada bidang TPA yang sebelumnya sudah menyetujui, mereka akan diminta meninjau ulang.</p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div class="alert alert-err"><?php echo nl2br(htmlspecialchars($error, ENT_QUOTES, 'UTF-8')); ?></div>
    <?php endif; ?>

    <?php if (!empty($catatan_blocking)): ?>
      <div class="catatan-box">
        <p><strong>Catatan dari TPA:</strong></p>
        <?php foreach ($catatan_blocking as $kode_bidang => $p): ?>
          <p style="margin-top:12px"><strong><?php echo htmlspecialchars(isset($label_bidang[$kode_bidang]) ? $label_bidang[$kode_bidang] : $kode_bidang, ENT_QUOTES, 'UTF-8'); ?>:</strong><br><?php echo nl2br(htmlspecialchars($p['catatan'], ENT_QUOTES, 'UTF-8')); ?></p>
          <p class="meta">Oleh <?php echo htmlspecialchars($p['nama_peninjau'], ENT_QUOTES, 'UTF-8'); ?> — <?php echo !empty($p['ditinjau_pada']) ? htmlspecialchars(date('d M Y H:i', strtotime($p['ditinjau_pada'])), ENT_QUOTES, 'UTF-8') : '—'; ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form action="<?php echo base_url('pengajuan-pbg/kirim-perbaikan/' . (int) $row['id']); ?>" method="post" enctype="multipart/form-data">

      <div class="wiz-card">
        <h3>Dokumen Teknis</h3>
        <p style="color:var(--muted);font-size:.85rem;margin-top:-8px;margin-bottom:22px">Unggah ulang dokumen mana pun yang perlu diperbarui - berkas baru menggantikan yang lama. Dokumen bertanda "Ditolak" wajib diunggah ulang.</p>
        <?php foreach ($peta_dokumen as $judul_grup => $grup): ?>
          <p class="doc-group-title"><?php echo htmlspecialchars($judul_grup, ENT_QUOTES, 'UTF-8'); ?></p>
          <?php foreach ($grup['dokumen'] as $slug => $label): ?>
            <?php $d = isset($dokumen_by_label[$label]) ? $dokumen_by_label[$label] : null; ?>
            <div class="dok-tolak-item" style="<?php echo (!$d || $d['status'] !== 'ditolak') ? 'border-color:var(--line)' : ''; ?>">
              <div class="nama">
                <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                <?php if ($d !== null): ?>
                  <span class="tag tag-<?php echo ($d['status'] === 'ditolak') ? 'ditolak' : 'terunggah'; ?>"><?php echo ($d['status'] === 'ditolak') ? 'Ditolak' : 'Terunggah'; ?></span>
                  <a href="<?php echo base_url('pengajuan-pbg/berkas/dokumen/' . (int) $d['id']); ?>" target="_blank" rel="noopener noreferrer" style="font-size:.78rem;text-decoration:underline;color:var(--gold-300);margin-left:4px">lihat berkas saat ini</a>
                <?php endif; ?>
              </div>
              <?php if ($d !== null && $d['status'] === 'ditolak' && !empty($d['catatan_penolakan'])): ?>
                <div class="alasan"><?php echo htmlspecialchars($d['catatan_penolakan'], ENT_QUOTES, 'UTF-8'); ?></div>
              <?php endif; ?>
              <input type="file" name="dokumen[<?php echo $slug; ?>]" accept=".jpg,.jpeg,.png,.pdf">
            </div>
          <?php endforeach; ?>
        <?php endforeach; ?>
      </div>

      <div class="wiz-card">
        <h3>Lampiran Lain</h3>
        <p style="color:var(--muted);font-size:.85rem;margin-top:-8px;margin-bottom:22px">Peta prototipe dan peta lokasi bangunan bisa diunggah ulang di sini kalau perlu diganti - lampiran kepemilikan tanah ada di bagian "Ubah Data Tanah" di bawah.</p>
        <div class="field-row">
          <div class="field">
            <label>Peta / Denah Prototipe</label>
            <?php if (!empty($row['prototipe_peta'])): ?><p style="font-size:.8rem;color:var(--muted);margin-bottom:8px">Sudah diunggah — <a href="<?php echo base_url('pengajuan-pbg/berkas/prototipe_peta/' . (int) $row['id']); ?>" target="_blank" rel="noopener noreferrer" style="color:var(--gold-300);text-decoration:underline">lihat</a></p><?php endif; ?>
            <input type="file" name="prototipe_peta" accept=".jpg,.jpeg,.png,.pdf">
          </div>
          <div class="field">
            <label>Peta Lokasi Bangunan</label>
            <?php if (!empty($row['bangunan_peta'])): ?><p style="font-size:.8rem;color:var(--muted);margin-bottom:8px">Sudah diunggah — <a href="<?php echo base_url('pengajuan-pbg/berkas/bangunan_peta/' . (int) $row['id']); ?>" target="_blank" rel="noopener noreferrer" style="color:var(--gold-300);text-decoration:underline">lihat</a></p><?php endif; ?>
            <input type="file" name="bangunan_peta" accept=".jpg,.jpeg,.png,.pdf">
          </div>
        </div>
      </div>

      <div class="wiz-card">
        <h3>Ubah Data Tanah</h3>
        <div class="field">
          <label for="f-tn-jenis">Jenis Dokumen Kepemilikan</label>
          <select id="f-tn-jenis" name="tanah_jenis_dokumen">
            <option value="">— Pilih —</option>
            <?php foreach (array('Sertifikat Hak Milik','Sertifikat Hak Guna Bangunan','Sertifikat Hak Pakai','Girik/Letter C','Akta Jual Beli','Lainnya') as $jd): ?>
              <option value="<?php echo htmlspecialchars($jd, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $opt('tanah_jenis_dokumen', $jd); ?>><?php echo htmlspecialchars($jd, ENT_QUOTES, 'UTF-8'); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field-row">
          <div class="field"><label for="f-tn-nodok">Nomor Dokumen Tanah</label><input id="f-tn-nodok" name="tanah_nomor_dokumen" type="text" value="<?php echo $val('tanah_nomor_dokumen'); ?>"></div>
          <div class="field"><label for="f-tn-tgl">Tanggal Terbit Dokumen</label><input id="f-tn-tgl" name="tanah_tanggal_terbit" type="date" value="<?php echo $val('tanah_tanggal_terbit'); ?>"></div>
        </div>
        <div class="field-row">
          <div class="field"><label for="f-tn-luas">Luas Tanah di Dokumen (m²)</label><input id="f-tn-luas" name="tanah_luas" type="text" value="<?php echo $val('tanah_luas'); ?>"></div>
          <div class="field"><label for="f-tn-hak">Hak Kepemilikan atas Tanah</label><input id="f-tn-hak" name="tanah_hak_kepemilikan" type="text" value="<?php echo $val('tanah_hak_kepemilikan'); ?>"></div>
        </div>
        <div class="field">
          <label for="f-tn-pemilik">Nama Pemilik Hak Tanah</label>
          <input id="f-tn-pemilik" name="tanah_nama_pemilik" type="text" value="<?php echo $val('tanah_nama_pemilik'); ?>">
        </div>
        <div class="field">
          <label>Lampiran Dokumen Kepemilikan Tanah (jpg/png/pdf, maks 5MB)</label>
          <?php if (!empty($row['tanah_lampiran'])): ?><p style="font-size:.8rem;color:var(--muted);margin-bottom:8px">Sudah diunggah — <a href="<?php echo base_url('pengajuan-pbg/berkas/tanah_lampiran/' . (int) $row['id']); ?>" target="_blank" rel="noopener noreferrer" style="color:var(--gold-300);text-decoration:underline">lihat</a></p><?php endif; ?>
          <input type="file" name="tanah_lampiran" accept=".jpg,.jpeg,.png,.pdf">
        </div>

        <h3 style="margin-top:34px">Lokasi Tanah</h3>
        <div class="field-row">
          <div class="field"><label for="f-tnlok-prov">Provinsi</label><input id="f-tnlok-prov" name="tanah_provinsi" type="text" value="<?php echo $val('tanah_provinsi'); ?>"></div>
          <div class="field"><label for="f-tnlok-kab">Kabupaten/Kota</label><input id="f-tnlok-kab" name="tanah_kabupaten" type="text" value="<?php echo $val('tanah_kabupaten'); ?>"></div>
        </div>
        <div class="field-row">
          <div class="field"><label for="f-tnlok-kec">Kecamatan</label><input id="f-tnlok-kec" name="tanah_kecamatan" type="text" value="<?php echo $val('tanah_kecamatan'); ?>"></div>
          <div class="field"><label for="f-tnlok-kel">Desa/Kelurahan</label><input id="f-tnlok-kel" name="tanah_kelurahan" type="text" value="<?php echo $val('tanah_kelurahan'); ?>"></div>
        </div>
        <div class="field">
          <label for="f-tnlok-alamat">Alamat Lengkap</label>
          <textarea id="f-tnlok-alamat" name="tanah_alamat"><?php echo $val('tanah_alamat'); ?></textarea>
        </div>

        <h3 style="margin-top:34px">Izin Pemanfaatan Tanah</h3>
        <div class="field">
          <label for="f-tn-sama">Apakah pemilik tanah sama dengan pemilik bangunan gedung?</label>
          <select id="f-tn-sama" name="tanah_pemilik_sama">
            <option value="">— Pilih —</option>
            <option value="sama" <?php echo $opt('tanah_pemilik_sama', 'sama'); ?>>Sama</option>
            <option value="tidak" <?php echo $opt('tanah_pemilik_sama', 'tidak'); ?>>Tidak</option>
          </select>
        </div>
        <div class="field-row">
          <div class="field"><label for="f-tn-izin">Nomor Izin Pemanfaatan Tanah</label><input id="f-tn-izin" name="tanah_nomor_izin" type="text" value="<?php echo $val('tanah_nomor_izin'); ?>"></div>
          <div class="field"><label for="f-tn-tglizin">Tanggal Terbit Dokumen Pemanfaatan</label><input id="f-tn-tglizin" name="tanah_tanggal_izin" type="date" value="<?php echo $val('tanah_tanggal_izin'); ?>"></div>
        </div>
      </div>

      <div class="wiz-nav">
        <button type="submit" class="btn btn-gold btn-sm"><?php echo $sedang_merespons_tpa ? 'Kirim Perbaikan' : 'Simpan Perubahan'; ?></button>
      </div>
    </form>
  </div>
</section>
  </div>
</div>

<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <div class="brand" style="margin-bottom:18px">
          <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Seal_of_Cilacap_Regency.svg?width=120" alt="" style="height:56px">
          <span>
            <span class="brand-name" style="font-size:1.05rem">SIP GATUTKACA</span><br>
            <span class="brand-sub">Sistem Informasi Pengelolaan Gedung</span>
          </span>
        </div>
        <p>Dinas Pekerjaan Umum dan Penataan Ruang Kabupaten Cilacap. Melayani dengan semangat <em>“otot kawat, balung wesi”</em> — kokoh dalam aturan, luwes dalam pelayanan.</p>
      </div>
      <div>
        <h4>Layanan</h4>
        <ul>
          <li><a href="<?php echo base_url('konsultasi'); ?>">Konsultasi Tata Ruang</a></li>
          <li><a href="<?php echo base_url('regulasi'); ?>">Pustaka Regulasi</a></li>
          <li><a href="<?php echo base_url('itr'); ?>">Informasi Tata Ruang</a></li>
          <li><a href="<?php echo base_url('tatacara'); ?>">Tata Cara KKPR</a></li>
          <li><a href="<?php echo base_url('spasial'); ?>">Peta Spasial</a></li>
        </ul>
      </div>
      <div>
        <h4>Kontak</h4>
        <ul>
          <li>Jl. MT. Haryono, Cilacap, Jawa Tengah</li>
          <li>Senin–Jumat · 08.00–15.30 WIB</li>
          <li>siptaru@cilacapkab.go.id</li>
        </ul>
      </div>
    </div>
    <div class="credit">
      <span>© 2026 Pemerintah Kabupaten Cilacap · Jala Bhumi Wijayakusuma Cakti</span>
      <span>Foto: Wikimedia Commons (lisensi CC BY / CC BY-SA, kreator masing-masing)</span>
    </div>
  </div>
</footer>

<button class="theme-fab" id="themeFab" aria-expanded="false" aria-controls="themePanel" title="Pilih warna latar">
  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3a9 9 0 100 18c1.2 0 2-.9 2-2 0-.5-.2-1-.5-1.4-.3-.4-.5-.8-.5-1.3 0-1.1.9-2 2-2h2.3A4.7 4.7 0 0021 9.7C20.4 5.9 16.6 3 12 3z" stroke="currentColor" stroke-width="1.4"/><circle cx="7.5" cy="11" r="1.2" fill="currentColor"/><circle cx="10.5" cy="7.5" r="1.2" fill="currentColor"/><circle cx="15" cy="7.5" r="1.2" fill="currentColor"/></svg>
</button>
<div class="theme-panel" id="themePanel" role="dialog" aria-label="Pilih warna latar">
  <h5>Warna Latar</h5>
  <div class="swatches">
    <button class="swatch sel" data-theme="light"><i class="sw-light"></i><span>Terang</span></button>
    <button class="swatch" data-theme="dark"><i class="sw-dark"></i><span>Gelap</span></button>
  </div>
</div>

<script>
(function(){
  var p=new URLSearchParams(location.search);
  var t=p.get('theme')==='dark'?'dark':'light';
  applyTheme(t);
  function applyTheme(theme){
    document.documentElement.setAttribute('data-theme',theme);
    document.querySelectorAll('.swatch').forEach(function(s){
      s.classList.toggle('sel',s.dataset.theme===theme);
    });
  }
  window.__applyTheme=applyTheme;
})();
var fab=document.getElementById('themeFab'),panel=document.getElementById('themePanel');
fab.addEventListener('click',function(){
  var open=panel.classList.toggle('open');
  fab.setAttribute('aria-expanded',open);
});
document.querySelectorAll('.swatch').forEach(function(s){
  s.addEventListener('click',function(){ window.__applyTheme(s.dataset.theme); });
});
document.addEventListener('click',function(e){
  if(!panel.contains(e.target)&&e.target!==fab&&!fab.contains(e.target))panel.classList.remove('open');
});
var userBtn=document.getElementById('userMenuBtn'),userPanel=document.getElementById('userMenuPanel');
userBtn.addEventListener('click',function(){
  var open=userPanel.classList.toggle('open');
  userBtn.setAttribute('aria-expanded',open);
});
document.addEventListener('click',function(e){
  if(!userPanel.contains(e.target)&&e.target!==userBtn&&!userBtn.contains(e.target))userPanel.classList.remove('open');
});
var bar=document.getElementById('topbar');
addEventListener('scroll',function(){bar.classList.toggle('scrolled',scrollY>40)},{passive:true});
</script>
</body>
</html>
