<?php
$t = function ($v) {
	return ($v === null || $v === '') ? '—' : htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
};
$tgl = function ($v) {
	return ($v === null || $v === '') ? '—' : htmlspecialchars(date('d M Y', strtotime($v)), ENT_QUOTES, 'UTF-8');
};
$tgl_jam = function ($v) {
	return ($v === null || $v === '') ? '—' : htmlspecialchars(date('d M Y H:i', strtotime($v)), ENT_QUOTES, 'UTF-8');
};
$label_status = array(
	'verifikasi_dokumen'           => 'Verifikasi Kelengkapan Dokumen',
	'perbaikan_dokumen'            => 'Perbaikan Dokumen',
	'perbaikan_dokumen_konsultasi' => 'Perbaikan Dokumen Konsultasi',
	'menunggu_jadwal_konsultasi'   => 'Menunggu Jadwal Konsultasi',
	'disetujui_tpa'                => 'Disetujui Semua TPA',
);
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tinjau Permohonan PBG — Portal TPA · SIP Gatutkaca</title>
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
.dash-wrap{max-width:980px;margin:0;padding:0 44px}
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
.btn-xs{padding:8px 16px;font-size:.68rem;letter-spacing:.12em}

section{padding:60px 0 100px}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.6rem,3vw,2.2rem);line-height:1.2}
.tag{display:inline-block;border:1px solid var(--line);padding:4px 14px;font-size:.68rem;letter-spacing:.16em;text-transform:uppercase;color:var(--gold-300);margin-top:14px}
.tag-verifikasi_dokumen{color:#5FC2E0;border-color:#1E86A3}
.tag-perbaikan_dokumen{color:#F0A048;border-color:#B4573B}
.tag-perbaikan_dokumen_konsultasi{color:#F0A048;border-color:#B4573B}
.tag-menunggu_jadwal_konsultasi{color:#6FCF97;border-color:#2EA84F}
.tag-disetujui_tpa{color:#6FCF97;border-color:#2EA84F}
.tag-terunggah{color:#6FCF97;border-color:#2EA84F;margin-top:0}
.tag-ditolak{color:#E0526B;border-color:#E0526B;margin-top:0}

.alert{padding:16px 20px;margin:26px 0 0;font-size:.88rem;border:1px solid}
.alert-ok{background:rgba(46,168,79,.12);border-color:#2EA84F;color:#8CE0A6}
.alert-err{background:rgba(224,82,107,.12);border-color:#E0526B;color:#F3AEB9}

.card{background:var(--surface);border:1px solid var(--line);padding:32px 36px;margin-top:26px}
.card h4{font-family:var(--display);font-weight:400;font-size:1.05rem;color:var(--gold-300);margin-bottom:20px;letter-spacing:.04em}
.kv-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px 30px}
.kv{border-bottom:1px solid var(--line);padding-bottom:12px}
.kv span{display:block;font-size:.68rem;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);margin-bottom:5px}
.kv b{font-weight:500;font-size:.92rem;white-space:pre-line}
.kv.full{grid-column:1 / -1}
@media(max-width:640px){.kv-grid{grid-template-columns:1fr}}

.catatan-box{background:rgba(240,160,72,.1);border:1px solid #B4573B;padding:20px 24px;margin-top:26px}
.catatan-box p{font-size:.9rem;white-space:pre-line}
.catatan-box .meta{margin-top:10px;font-size:.74rem;color:var(--muted)}

.doc-review-item{padding:16px 0;border-bottom:1px solid var(--line)}
.doc-review-item:last-child{border-bottom:none}
.doc-review-main{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap}
.doc-review-main .doc-name{font-size:.9rem}
.doc-review-main .doc-right{display:flex;align-items:center;gap:12px}
.doc-review-main a{color:var(--gold-300);text-decoration:underline;font-size:.8rem}
.catatan-tolak{margin-top:10px;font-size:.85rem;color:var(--muted);background:var(--bg-alt);padding:10px 14px;border-left:2px solid #E0526B}
.doc-empty{color:var(--muted);font-size:.88rem}

details.tandai-form{margin-top:10px}
details.tandai-form summary{cursor:pointer;font-size:.72rem;letter-spacing:.12em;text-transform:uppercase;color:#E0526B;list-style:none}
details.tandai-form summary::-webkit-details-marker{display:none}
details.tandai-form summary:before{content:"⚠ "}
details.tandai-form .isi{margin-top:10px;display:flex;gap:10px;flex-wrap:wrap}
details.tandai-form textarea{flex:1;min-width:240px;background:var(--input);border:1px solid var(--line);color:var(--text);padding:10px 12px;font-family:var(--body);font-size:.85rem;min-height:60px}
details.tandai-form textarea:focus{outline:1px solid var(--gold-500);border-color:var(--gold-500)}

.keputusan-form{margin-top:20px}
.keputusan-form textarea{width:100%;background:var(--input);border:1px solid var(--line);color:var(--text);padding:13px 15px;font-family:var(--body);font-size:.9rem;min-height:110px;margin-top:8px}
.keputusan-form textarea:focus{outline:1px solid var(--gold-500);border-color:var(--gold-500)}
.opt-list{display:flex;flex-wrap:wrap;gap:12px;margin-top:16px}
.opt-list label{display:block;border:1px solid var(--line);padding:14px 18px;font-size:.85rem;cursor:pointer;transition:.2s;flex:1;min-width:240px}
.opt-list label:hover{border-color:var(--gold-500)}
.opt-list label:has(input:checked){border-color:var(--gold-500);background:var(--surface-hi)}
.opt-list .opt-title{display:flex;align-items:center;gap:8px;font-weight:500}
.opt-list .opt-hint{display:block;margin-top:6px;color:var(--muted);font-size:.8rem}

footer{background:var(--foot);color:#F8F4EA;padding:66px 0 32px;border-top:1px solid var(--line)}
.foot-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr;gap:50px}
.foot-grid h4{font-family:var(--display);font-weight:400;letter-spacing:.14em;color:#E4C87B;margin-bottom:18px;font-size:1rem}
.foot-grid p,.foot-grid li{font-size:.88rem;color:#B9C7D2}
.foot-grid ul{list-style:none;display:grid;gap:10px}
.foot-grid a:hover{color:#E4C87B}
.credit{margin-top:50px;padding-top:22px;border-top:1px solid rgba(185,199,210,.15);display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap;font-size:.72rem;color:rgba(185,199,210,.6);letter-spacing:.06em}

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
      <a href="<?php echo base_url('tpa'); ?>">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><rect x="1" y="1" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="10" y="1" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="1" y="10" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="10" y="10" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/></svg>
        Dashboard
      </a>
      <a href="<?php echo base_url('tpa-pengajuan-pbg'); ?>" class="active">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M4 1.5h7L14.5 5v11a1 1 0 01-1 1h-9a1 1 0 01-1-1v-13a1 1 0 011-1z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M11 1.5V5h3.5" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M5.5 9.5h7M5.5 12h7M5.5 7h3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
        Pengajuan PBG
      </a>
      <a href="<?php echo base_url('tpa-pengajuan-slf'); ?>">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M3.5 2.5h8L14.5 5.5V15a.5.5 0 01-.5.5H3.5a.5.5 0 01-.5-.5V3a.5.5 0 01.5-.5z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M6 8.6l1.7 1.7L11 6.9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Pengajuan SLF
      </a>
    </nav>
    <nav>
      <a href="<?php echo base_url('login/keluar'); ?>" class="logout">Logout</a>
    </nav>
  </aside>
  <div class="dash-main">
<section style="padding-top:100px">
  <div class="dash-wrap">
    <p class="eyebrow"><a href="<?php echo base_url('tpa-pengajuan-pbg'); ?>" style="color:var(--gold-500);text-decoration:underline">← Kembali ke Daftar Permohonan</a></p>
    <h2><?php echo $t($row['nama_pemohon']); ?></h2>
    <span class="tag tag-<?php echo htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars(isset($label_status[$row['status']]) ? $label_status[$row['status']] : $row['status'], ENT_QUOTES, 'UTF-8'); ?></span>

    <?php if (!empty($sukses)): ?>
      <div class="alert alert-ok"><?php echo htmlspecialchars($sukses, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert alert-err"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <p style="margin-top:16px"><a href="<?php echo base_url('tpa-pengajuan-pbg/checklist/' . (int) $row['id']); ?>" style="color:var(--gold-300);text-decoration:underline;font-size:.85rem">Lihat Checklist Kelengkapan Persyaratan →</a></p>

    <?php
    $label_bidang = array(
      'tpa_arsitek'  => 'Bidang Arsitektur & Tata Kota',
      'tpa_struktur' => 'Bidang Struktur & Sipil',
      'tpa_mep'      => 'Bidang Mekanikal, Elektrikal & Perpipaan (MEP)',
    );
    $label_status_bidang = array(
      'disetujui'                    => 'Disetujui',
      'perbaikan_dokumen'            => 'Perbaikan Dokumen',
      'perbaikan_dokumen_konsultasi' => 'Perbaikan Dokumen Konsultasi',
    );
    $nama_reviewer_bidang = array(
      'tpa_arsitek'  => isset($row['nama_reviewer_arsitek']) ? $row['nama_reviewer_arsitek'] : null,
      'tpa_struktur' => isset($row['nama_reviewer_struktur']) ? $row['nama_reviewer_struktur'] : null,
      'tpa_mep'      => isset($row['nama_reviewer_mep']) ? $row['nama_reviewer_mep'] : null,
    );
    ?>
    <div class="card">
      <h4>Status Persetujuan 3 Bidang TPA</h4>
      <p style="color:var(--muted);font-size:.82rem;margin-top:-12px;margin-bottom:16px">Permohonan baru berstatus Disetujui Semua TPA kalau ketiga bidang di bawah sudah menyetujui.</p>
      <div class="kv-grid">
        <?php foreach ($label_bidang as $kode_bidang => $nama_bidang): ?>
          <div class="kv full">
            <span><?php echo htmlspecialchars($nama_bidang, ENT_QUOTES, 'UTF-8'); ?><span style="display:block;color:var(--muted);font-size:.76rem;font-weight:400;text-transform:none;letter-spacing:normal;margin-top:4px">Ditugaskan: <?php echo empty($nama_reviewer_bidang[$kode_bidang]) ? 'Belum ditugaskan (semua staf bidang ini boleh meninjau)' : $t($nama_reviewer_bidang[$kode_bidang]); ?></span></span>
            <?php if (isset($persetujuan[$kode_bidang])): $p = $persetujuan[$kode_bidang]; ?>
              <b>
                <span class="tag tag-<?php echo ($p['status'] === 'disetujui') ? 'disetujui_tpa' : htmlspecialchars($p['status'], ENT_QUOTES, 'UTF-8'); ?>" style="margin-top:4px"><?php echo htmlspecialchars(isset($label_status_bidang[$p['status']]) ? $label_status_bidang[$p['status']] : $p['status'], ENT_QUOTES, 'UTF-8'); ?></span>
                <span style="display:block;color:var(--muted);font-size:.78rem;margin-top:6px;text-transform:none;letter-spacing:normal">Oleh <?php echo $t($p['nama_peninjau']); ?> — <?php echo $tgl_jam($p['ditinjau_pada']); ?></span>
                <?php if (!empty($p['catatan'])): ?>
                  <span style="display:block;white-space:pre-line;margin-top:6px;font-weight:400;text-transform:none;letter-spacing:normal"><?php echo $t($p['catatan']); ?></span>
                <?php endif; ?>
              </b>
            <?php else: ?>
              <b style="color:var(--muted);font-weight:400">Menunggu ditinjau</b>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="card">
      <h4>Data Pemohon &amp; Registrasi</h4>
      <div class="kv-grid">
        <div class="kv"><span>No. Registrasi</span><b><?php echo !empty($row['no_registrasi']) ? $t($row['no_registrasi']) : 'Belum Terdefinisi'; ?></b></div>
        <div class="kv"><span>Dikirim Pada</span><b><?php echo $tgl($row['created_at']); ?></b></div>
        <div class="kv"><span>NIK Pemohon</span><b><?php echo $t($row['nik_pemohon']); ?></b></div>
        <div class="kv"><span>Kontak Pemohon</span><b><?php echo $t($row['kontak_pemohon']); ?></b></div>
      </div>
    </div>

    <div class="card">
      <h4>Lokasi &amp; Kepemilikan Bangunan</h4>
      <div class="kv-grid">
        <div class="kv full"><span>Alamat Lokasi Bangunan</span><b><?php echo $t($row['lokasi_alamat']); ?></b></div>
        <div class="kv"><span>Provinsi / Kab-Kota</span><b><?php echo $t(trim($row['lokasi_provinsi'] . ' / ' . $row['lokasi_kabupaten'], ' /')); ?></b></div>
        <div class="kv"><span>Kepemilikan Bangunan</span><b><?php echo $t(isset($opsi_kepemilikan[$row['kepemilikan_bangunan']]) ? $opsi_kepemilikan[$row['kepemilikan_bangunan']] : null); ?></b></div>
        <div class="kv"><span>Kondisi Bangunan</span><b><?php echo $t(isset($opsi_kondisi[$row['kondisi_bangunan']]) ? $opsi_kondisi[$row['kondisi_bangunan']] : null); ?></b></div>
      </div>
    </div>

    <div class="card">
      <h4>Fungsi &amp; Data Bangunan</h4>
      <div class="kv-grid">
        <div class="kv full"><span>Fungsi &amp; Sub Fungsi Terpilih</span><b><?php echo $t($row['fungsi_bangunan']); ?></b></div>
        <div class="kv"><span>Nama Bangunan</span><b><?php echo $t($row['bangunan_nama']); ?></b></div>
        <div class="kv"><span>Jumlah Lantai</span><b><?php echo $t($row['bangunan_jumlah_lantai']); ?></b></div>
        <div class="kv"><span>Luas Per Unit</span><b><?php echo $t($row['bangunan_luas_per_unit']); ?> m²</b></div>
        <div class="kv"><span>Memiliki Basemen</span><b><?php echo $t($row['punya_basemen'] === 'ya' ? 'Ya' : ($row['punya_basemen'] === 'tidak' ? 'Tidak' : null)); ?></b></div>
      </div>
    </div>

    <div class="card">
      <h4>Dokumen Tanah Bangunan</h4>
      <div class="kv-grid">
        <div class="kv"><span>Jenis Dokumen Kepemilikan</span><b><?php echo $t($row['tanah_jenis_dokumen']); ?></b></div>
        <div class="kv"><span>Nomor / Tanggal Terbit</span><b><?php echo $t($row['tanah_nomor_dokumen']); ?> — <?php echo $tgl($row['tanah_tanggal_terbit']); ?></b></div>
        <div class="kv"><span>Luas Tanah</span><b><?php echo $t($row['tanah_luas']); ?> m²</b></div>
        <div class="kv"><span>Nama Pemilik Hak Tanah</span><b><?php echo $t($row['tanah_nama_pemilik']); ?></b></div>
        <div class="kv full"><span>Alamat Lokasi Tanah</span><b><?php echo $t($row['tanah_alamat']); ?></b></div>
      </div>
    </div>

    <?php if (empty($dokumen_kelompok)): ?>
      <div class="card">
        <h4>Dokumen Teknis Terunggah</h4>
        <p class="doc-empty">Tidak ada dokumen di bidang Anda untuk permohonan ini.</p>
      </div>
    <?php else: ?>
      <p style="color:var(--muted);font-size:.78rem;margin:26px 2px 0">Dokumen teknis terunggah — satu kartu per bidang, sesuai peninjauan Anda.</p>
      <?php foreach ($dokumen_kelompok as $judul_grup => $grup): ?>
        <div class="card">
          <h4><?php echo htmlspecialchars($judul_grup, ENT_QUOTES, 'UTF-8'); ?></h4>

          <?php if ($judul_grup === 'Bidang Struktur & Sipil' && !empty($row['tanah_lampiran'])): ?>
            <div class="doc-review-item">
              <div class="doc-review-main">
                <span class="doc-name">Sertifikat Kepemilikan Tanah</span>
                <div class="doc-right">
                  <a href="<?php echo base_url('tpa-pengajuan-pbg/berkas/tanah_lampiran/' . (int) $row['id']); ?>" target="_blank" rel="noopener noreferrer">Lihat</a>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <?php if (empty($grup['berkas'])): ?>
            <p class="doc-empty">Belum ada dokumen diunggah untuk bidang ini.</p>
          <?php endif; ?>

          <?php foreach ($grup['berkas'] as $d): ?>
            <div class="doc-review-item">
              <div class="doc-review-main">
                <span class="doc-name"><?php echo htmlspecialchars($d['jenis_dokumen'], ENT_QUOTES, 'UTF-8'); ?> — <?php echo htmlspecialchars($d['nama_file_asli'], ENT_QUOTES, 'UTF-8'); ?></span>
                <div class="doc-right">
                  <span class="tag tag-<?php echo htmlspecialchars($d['status'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo $d['status'] === 'ditolak' ? 'Ditolak' : 'Terunggah'; ?></span>
                  <a href="<?php echo base_url('tpa-pengajuan-pbg/berkas/dokumen/' . (int) $d['id']); ?>" target="_blank" rel="noopener noreferrer">Lihat</a>
                </div>
              </div>

              <?php if ($d['status'] === 'ditolak'): ?>
                <p class="catatan-tolak"><strong>Catatan:</strong> <?php echo $t($d['catatan_penolakan']); ?></p>
                <?php if (!empty($bisa_ditandai)): ?>
                  <form action="<?php echo base_url('tpa-pengajuan-pbg/tandai-dokumen/' . (int) $d['id']); ?>" method="post" style="margin-top:8px">
                    <input type="hidden" name="aksi" value="batal">
                    <button type="submit" class="btn btn-ghost btn-xs" style="cursor:pointer">Batalkan Tanda</button>
                  </form>
                <?php endif; ?>
              <?php elseif (!empty($bisa_ditandai)): ?>
                <details class="tandai-form">
                  <summary>Tandai Tidak Sesuai</summary>
                  <form action="<?php echo base_url('tpa-pengajuan-pbg/tandai-dokumen/' . (int) $d['id']); ?>" method="post">
                    <input type="hidden" name="aksi" value="tolak">
                    <div class="isi">
                      <textarea name="catatan" required placeholder="Alasan / apa yang perlu diperbaiki pada dokumen ini"></textarea>
                      <button type="submit" class="btn btn-ghost btn-xs" style="cursor:pointer;align-self:flex-start">Kirim Tanda</button>
                    </div>
                  </form>
                </details>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($bidang_boleh_menilai)): ?>
      <div class="card">
        <h4>Kirim Keputusan Peninjauan — Bidang Anda</h4>
        <p style="color:var(--muted);font-size:.88rem">Tandai dulu dokumen yang tidak sesuai di atas (kalau ada), lalu pilih keputusan BIDANG ANDA dan tulis catatan untuk pemohon/PU di bawah ini (catatan wajib diisi untuk 2 jenis perbaikan, opsional untuk "Dokumen Sesuai"). Bidang TPA lain meninjau &amp; memutuskan secara terpisah - permohonan baru berstatus Disetujui Semua TPA kalau ketiga bidang sudah menyetujui (lihat kartu status di atas).</p>
        <form class="keputusan-form" action="<?php echo base_url('tpa-pengajuan-pbg/kirim-catatan/' . (int) $row['id']); ?>" method="post">
          <div class="opt-list">
            <label>
              <span class="opt-title"><input type="radio" name="status_baru" value="disetujui" <?php echo (isset($old['status_baru']) && $old['status_baru'] === 'disetujui') ? 'checked' : ''; ?>> Dokumen Sesuai</span>
              <span class="opt-hint">Dokumen di bidang Anda sudah sesuai, tidak perlu perbaikan.</span>
            </label>
            <label>
              <span class="opt-title"><input type="radio" name="status_baru" value="perbaikan_dokumen" <?php echo (isset($old['status_baru']) && $old['status_baru'] === 'perbaikan_dokumen') ? 'checked' : ''; ?>> Perbaikan Dokumen</span>
              <span class="opt-hint">Ada dokumen yang perlu diunggah ulang / data yang perlu diperbaiki. Setelah diperbaiki, bidang Anda meninjau ulang (bidang lain yang sudah menyetujui tidak perlu meninjau ulang).</span>
            </label>
            <label>
              <span class="opt-title"><input type="radio" name="status_baru" value="perbaikan_dokumen_konsultasi" <?php echo (isset($old['status_baru']) && $old['status_baru'] === 'perbaikan_dokumen_konsultasi') ? 'checked' : ''; ?>> Perbaikan Dokumen Konsultasi</span>
              <span class="opt-hint">Perlu perbaikan terkait hasil konsultasi. Setelah diperbaiki, permohonan lanjut ke status Menunggu Jadwal Konsultasi (menggantikan sisa proses tinjau-ulang per bidang).</span>
            </label>
          </div>
          <label for="f-catatan-tpa" style="display:block;margin-top:20px;font-size:.72rem;letter-spacing:.16em;text-transform:uppercase;color:var(--muted)">Catatan untuk Pemohon/PU (opsional untuk "Dokumen Sesuai")</label>
          <textarea id="f-catatan-tpa" name="catatan_tpa" placeholder="Jelaskan apa yang perlu diperbaiki di bidang Anda - atau kosongkan kalau dokumen di bidang Anda sudah sesuai"><?php echo isset($old['catatan_tpa']) ? htmlspecialchars($old['catatan_tpa'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
          <button type="submit" class="btn btn-gold btn-sm" style="margin-top:18px">Kirim Keputusan</button>
        </form>
      </div>
    <?php elseif ($bidang_saya === null && !empty($bisa_ditandai)): ?>
      <div class="card">
        <h4>Kirim Keputusan Peninjauan</h4>
        <p style="color:var(--muted);font-size:.88rem">Akun peran TPA generik tidak berpartisipasi dalam persetujuan per bidang - gunakan salah satu akun spesialis (Arsitek/Struktur/MEP) untuk mengirim keputusan bidang. Anda tetap bisa menandai dokumen tidak sesuai per item di atas.</p>
      </div>
    <?php endif; ?>
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
