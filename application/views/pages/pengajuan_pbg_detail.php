<?php
// Dipakai bersama oleh Portal PU (Pengajuan_pbg::lihat) dan Panel Admin
// (Admin::pengajuan_lihat). Dalam admin_mode: sidebar + tautan berkas
// diarahkan ke rute admin, dan semua tombol aksi PU disembunyikan
// (admin cuma boleh melihat, bukan mengubah permohonan).
$admin_mode  = isset($admin_mode) ? (bool) $admin_mode : FALSE;
$berkas_base = $admin_mode ? 'admin/berkas/' : 'pengajuan-pbg/berkas/';
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
	'draf'                         => 'Draf',
	'verifikasi_dokumen'           => 'Verifikasi Kelengkapan Dokumen',
	'perbaikan_dokumen'            => 'Perbaikan Dokumen',
	'perbaikan_dokumen_konsultasi' => 'Perbaikan Dokumen Konsultasi',
	'menunggu_jadwal_konsultasi'   => 'Menunggu Jadwal Konsultasi',
	'disetujui_tpa'                => 'Disetujui Semua TPA',
);
$perlu_perbaikan = in_array($row['status'], array('perbaikan_dokumen', 'perbaikan_dokumen_konsultasi'), TRUE);
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
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Permohonan PBG — <?php echo $admin_mode ? 'Panel Admin' : 'Portal PU'; ?> · SIP Gatutkaca</title>
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
.btn-ghost{border:1px solid var(--line);color:var(--text);background:transparent}
.btn-ghost:hover{border-color:#C9A24B;color:#E4C87B}
.btn-sm{padding:11px 26px;font-size:.72rem;letter-spacing:.2em}

section{padding:60px 0 100px}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.6rem,3vw,2.2rem);line-height:1.2}
.alert{padding:16px 20px;margin:26px 0 0;font-size:.88rem;border:1px solid}
.alert-ok{background:rgba(46,168,79,.12);border-color:#2EA84F;color:#8CE0A6}
.alert-err{background:rgba(224,82,107,.12);border-color:#E0526B;color:#F3AEB9}
.tag{display:inline-block;border:1px solid var(--line);padding:4px 14px;font-size:.68rem;letter-spacing:.16em;text-transform:uppercase;color:var(--gold-300);margin-top:14px}
.tag-draf{color:#F0A048;border-color:#B4573B}
.tag-verifikasi_dokumen{color:#5FC2E0;border-color:#1E86A3}
.tag-perbaikan_dokumen{color:#E0526B;border-color:#E0526B}
.tag-perbaikan_dokumen_konsultasi{color:#E0526B;border-color:#E0526B}
.tag-menunggu_jadwal_konsultasi{color:#6FCF97;border-color:#2EA84F}
.tag-disetujui_tpa{color:#6FCF97;border-color:#2EA84F}
.tag-terunggah{color:#6FCF97;border-color:#2EA84F;margin-top:0}
.tag-ditolak{color:#E0526B;border-color:#E0526B;margin-top:0}

.card{background:var(--surface);border:1px solid var(--line);padding:32px 36px;margin-top:26px}
.card h4{font-family:var(--display);font-weight:400;font-size:1.05rem;color:var(--gold-300);margin-bottom:20px;letter-spacing:.04em}
.kv-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px 30px}
.kv{border-bottom:1px solid var(--line);padding-bottom:12px}
.kv span{display:block;font-size:.68rem;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);margin-bottom:5px}
.kv b{font-weight:500;font-size:.92rem;white-space:pre-line}
.kv.full{grid-column:1 / -1}
@media(max-width:640px){.kv-grid{grid-template-columns:1fr}}

.doc-item{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:12px 0;border-bottom:1px solid var(--line);font-size:.88rem}
.doc-item:last-child{border-bottom:none}
.doc-item a{color:var(--gold-300);text-decoration:underline}
.doc-empty{color:var(--muted);font-size:.88rem}

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
      <?php if ($admin_mode): ?>
      <a href="<?php echo base_url('admin/pengguna'); ?>">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><circle cx="6.5" cy="5.5" r="2.6" stroke="currentColor" stroke-width="1.4"/><path d="M1.8 15c0-2.6 2.1-4.4 4.7-4.4S11.2 12.4 11.2 15" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><path d="M12.4 4.2a2.3 2.3 0 010 4.4M13.6 14.8c0-2.1-1-3.7-2.6-4.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
        Kelola Pengguna
      </a>
      <a href="<?php echo base_url('admin/pengajuan'); ?>" class="active">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M4 1.5h7L14.5 5v11a1 1 0 01-1 1h-9a1 1 0 01-1-1v-13a1 1 0 011-1z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M11 1.5V5h3.5" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M5.5 9.5h7M5.5 12h7M5.5 7h3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
        Pengajuan
      </a>
      <?php else: ?>
      <a href="<?php echo base_url('pu'); ?>">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><rect x="1" y="1" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="10" y="1" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="1" y="10" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="10" y="10" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/></svg>
        Dashboard
      </a>
      <a href="<?php echo base_url('pengajuan-pbg'); ?>" class="active">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M4 1.5h7L14.5 5v11a1 1 0 01-1 1h-9a1 1 0 01-1-1v-13a1 1 0 011-1z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M11 1.5V5h3.5" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M5.5 9.5h7M5.5 12h7M5.5 7h3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
        Pengajuan PBG
      </a>
      <?php endif; ?>
    </nav>
    <nav>
      <a href="<?php echo base_url('login/keluar'); ?>" class="logout">Logout</a>
    </nav>
  </aside>
  <div class="dash-main">
<section style="padding-top:100px">
  <div class="dash-wrap">
    <p class="eyebrow"><a href="<?php echo base_url($admin_mode ? 'admin/pengajuan' : 'pengajuan-pbg'); ?>" style="color:var(--gold-500);text-decoration:underline">← Kembali ke Daftar <?php echo $admin_mode ? 'Pengajuan' : 'Permohonan'; ?></a></p>
    <h2><?php echo $t($row['nama_pemohon']); ?></h2>
    <span class="tag tag-<?php echo htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars(isset($label_status[$row['status']]) ? $label_status[$row['status']] : $row['status'], ENT_QUOTES, 'UTF-8'); ?></span>
    <?php if (! $admin_mode): ?>
      <?php if ($row['status'] === 'draf'): ?>
        <a href="<?php echo base_url('pengajuan-pbg/tambah/' . (int) $row['id']); ?>" class="btn btn-gold btn-sm" style="margin-top:20px">Lanjutkan Permohonan</a>
      <?php elseif ($perlu_perbaikan): ?>
        <a href="<?php echo base_url('pengajuan-pbg/perbaiki/' . (int) $row['id']); ?>" class="btn btn-gold btn-sm" style="margin-top:20px">Perbaiki Permohonan</a>
      <?php elseif ($row['status'] === 'verifikasi_dokumen'): ?>
        <a href="<?php echo base_url('pengajuan-pbg/perbaiki/' . (int) $row['id']); ?>" class="btn btn-ghost btn-sm" style="margin-top:20px">Edit Permohonan</a>
      <?php endif; ?>
      <?php if ($row['status'] !== 'draf'): ?>
        <a href="<?php echo base_url('pengajuan-pbg/reviewer/' . (int) $row['id']); ?>" class="btn btn-ghost btn-sm" style="margin-top:20px">Atur Reviewer TPA</a>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (!empty($sukses)): ?>
      <div class="alert alert-ok"><?php echo htmlspecialchars($sukses, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert alert-err"><?php echo nl2br(htmlspecialchars($error, ENT_QUOTES, 'UTF-8')); ?></div>
    <?php endif; ?>

    <?php if (! $admin_mode): ?>
    <p style="margin-top:16px"><a href="<?php echo base_url('pengajuan-pbg/checklist/' . (int) $row['id']); ?>" style="color:var(--gold-300);text-decoration:underline;font-size:.85rem">Lihat Checklist Kelengkapan Persyaratan →</a></p>
    <?php endif; ?>

    <?php if (!empty($persetujuan) || $perlu_perbaikan || $row['status'] === 'disetujui_tpa'): ?>
      <div class="card" style="border-color:<?php echo ($row['status'] === 'disetujui_tpa') ? '#2EA84F' : '#B4573B'; ?>;background:<?php echo ($row['status'] === 'disetujui_tpa') ? 'rgba(46,168,79,.06)' : 'rgba(240,160,72,.06)'; ?>">
        <h4>Status Persetujuan TPA per Bidang</h4>
        <p style="color:var(--muted);font-size:.82rem;margin-top:-12px;margin-bottom:16px">Permohonan berstatus Disetujui Semua TPA kalau ketiga bidang di bawah sudah menyetujui.</p>
        <div class="kv-grid">
          <?php foreach ($label_bidang as $kode_bidang => $nama_bidang): ?>
            <div class="kv full">
              <span><?php echo htmlspecialchars($nama_bidang, ENT_QUOTES, 'UTF-8'); ?><span style="display:block;color:var(--muted);font-size:.76rem;font-weight:400;margin-top:4px">Ditugaskan: <?php echo empty($nama_reviewer_bidang[$kode_bidang]) ? 'Belum ditugaskan (semua staf bidang ini boleh meninjau)' : $t($nama_reviewer_bidang[$kode_bidang]); ?></span></span>
              <?php if (isset($persetujuan[$kode_bidang])): $p = $persetujuan[$kode_bidang]; ?>
                <b>
                  <span class="tag tag-<?php echo ($p['status'] === 'disetujui') ? 'disetujui_tpa' : htmlspecialchars($p['status'], ENT_QUOTES, 'UTF-8'); ?>" style="margin-top:4px"><?php echo htmlspecialchars(isset($label_status_bidang[$p['status']]) ? $label_status_bidang[$p['status']] : $p['status'], ENT_QUOTES, 'UTF-8'); ?></span>
                  <span style="display:block;color:var(--muted);font-size:.78rem;margin-top:6px">Oleh <?php echo $t($p['nama_peninjau']); ?> — <?php echo $tgl_jam($p['ditinjau_pada']); ?></span>
                  <?php if (!empty($p['catatan'])): ?>
                    <span style="display:block;white-space:pre-line;margin-top:6px;font-weight:400"><?php echo $t($p['catatan']); ?></span>
                  <?php endif; ?>
                </b>
              <?php else: ?>
                <b style="color:var(--muted);font-weight:400">Menunggu ditinjau</b>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <div class="card">
      <h4>Data Pemohon &amp; Registrasi</h4>
      <div class="kv-grid">
        <div class="kv"><span>No. Registrasi</span><b><?php echo !empty($row['no_registrasi']) ? $t($row['no_registrasi']) : 'Belum Terdefinisi'; ?></b></div>
        <div class="kv"><span>Diinput Pada</span><b><?php echo $tgl($row['created_at']); ?></b></div>
        <?php if ($admin_mode): ?>
        <div class="kv"><span>Diinput Oleh (Staf PU)</span><b><?php echo isset($row['nama_pembuat']) ? $t($row['nama_pembuat']) : '—'; ?></b></div>
        <?php endif; ?>
        <div class="kv"><span>NIK Pemohon</span><b><?php echo $t($row['nik_pemohon']); ?></b></div>
        <div class="kv"><span>Kontak Pemohon</span><b><?php echo $t($row['kontak_pemohon']); ?></b></div>
      </div>
    </div>

    <div class="card">
      <h4>Intensitas Pemanfaatan Ruang</h4>
      <div class="kv-grid">
        <div class="kv"><span>Sudah Memiliki Data</span><b><?php echo $t($row['intensitas_ada'] === 'ya' ? 'Ya' : ($row['intensitas_ada'] === 'tidak' ? 'Tidak' : null)); ?></b></div>
        <div class="kv"><span>Nomor Dokumen</span><b><?php echo $t($row['intensitas_no_dokumen']); ?></b></div>
        <div class="kv"><span>GSB</span><b><?php echo $t($row['intensitas_gsb']); ?></b></div>
        <div class="kv"><span>KDB</span><b><?php echo $t($row['intensitas_kdb']); ?></b></div>
        <div class="kv"><span>KLB</span><b><?php echo $t($row['intensitas_klb']); ?></b></div>
        <div class="kv"><span>KDH</span><b><?php echo $t($row['intensitas_kdh']); ?></b></div>
      </div>
    </div>

    <div class="card">
      <h4>Lokasi &amp; Kepemilikan Bangunan</h4>
      <div class="kv-grid">
        <div class="kv full"><span>Alamat Lokasi Bangunan</span><b><?php echo $t($row['lokasi_alamat']); ?></b></div>
        <div class="kv"><span>Provinsi / Kab-Kota</span><b><?php echo $t(trim($row['lokasi_provinsi'] . ' / ' . $row['lokasi_kabupaten'], ' /')); ?></b></div>
        <div class="kv"><span>Kecamatan / Kelurahan</span><b><?php echo $t(trim($row['lokasi_kecamatan'] . ' / ' . $row['lokasi_kelurahan'], ' /')); ?></b></div>
        <div class="kv"><span>Jumlah Bukti Kepemilikan Tanah</span><b><?php echo $t($row['jumlah_bukti_tanah']); ?></b></div>
        <div class="kv"><span>Kepemilikan Bangunan</span><b><?php echo $t(isset($opsi_kepemilikan[$row['kepemilikan_bangunan']]) ? $opsi_kepemilikan[$row['kepemilikan_bangunan']] : null); ?></b></div>
        <div class="kv"><span>Kondisi Bangunan</span><b><?php echo $t(isset($opsi_kondisi[$row['kondisi_bangunan']]) ? $opsi_kondisi[$row['kondisi_bangunan']] : null); ?></b></div>
      </div>
    </div>

    <div class="card">
      <h4>Fungsi Bangunan</h4>
      <div class="kv-grid">
        <div class="kv full"><span>Fungsi &amp; Sub Fungsi Terpilih</span><b><?php echo $t($row['fungsi_bangunan']); ?></b></div>
      </div>
    </div>

    <div class="card">
      <h4>Data Bangunan</h4>
      <div class="kv-grid">
        <div class="kv"><span>Nama Bangunan</span><b><?php echo $t($row['bangunan_nama']); ?></b></div>
        <div class="kv"><span>Memiliki Basemen</span><b><?php echo $t($row['punya_basemen'] === 'ya' ? 'Ya' : ($row['punya_basemen'] === 'tidak' ? 'Tidak' : null)); ?></b></div>
        <div class="kv"><span>Luas Per Unit (Selain Basemen)</span><b><?php echo $t($row['bangunan_luas_per_unit']); ?> m²</b></div>
        <div class="kv"><span>Tinggi Bangunan</span><b><?php echo $t($row['bangunan_tinggi']); ?> m</b></div>
        <div class="kv"><span>Jumlah Lantai</span><b><?php echo $t($row['bangunan_jumlah_lantai']); ?></b></div>
        <div class="kv"><span>Luas / Jumlah Lapis Basemen</span><b><?php echo $t($row['bangunan_luas_basemen']); ?> m² / <?php echo $t($row['bangunan_jumlah_lapis_basemen']); ?></b></div>
        <div class="kv"><span>Jumlah Unit</span><b><?php echo $t($row['bangunan_jumlah_unit']); ?></b></div>
        <div class="kv"><span>Estimasi Jumlah Penghuni</span><b><?php echo $t($row['bangunan_estimasi_penghuni']); ?></b></div>
        <div class="kv"><span>Koordinat Bangunan</span><b><?php echo $t(trim($row['bangunan_latitude'] . ', ' . $row['bangunan_longitude'], ', ')); ?></b></div>
        <div class="kv"><span>Peta Lokasi Bangunan</span><b><?php echo !empty($row['bangunan_peta']) ? '<a href="' . base_url($berkas_base . 'bangunan_peta/' . (int) $row['id']) . '" target="_blank" rel="noopener noreferrer" style="color:var(--gold-300);text-decoration:underline">Lihat berkas</a>' : '—'; ?></b></div>
      </div>
    </div>

    <?php if ($row['pakai_prototipe'] === 'ya' || $row['masa_pemanfaatan'] !== null): ?>
    <div class="card">
      <h4>Desain Prototipe</h4>
      <div class="kv-grid">
        <div class="kv"><span>Pakai Desain Prototipe</span><b><?php echo $t($row['pakai_prototipe'] === 'ya' ? 'Ya' : ($row['pakai_prototipe'] === 'tidak' ? 'Tidak' : null)); ?></b></div>
        <div class="kv"><span>Jenis Prototipe</span><b><?php echo $t($row['prototipe_jenis']); ?></b></div>
        <div class="kv"><span>Jumlah Unit Dibangun</span><b><?php echo $t($row['prototipe_jumlah_unit']); ?></b></div>
        <div class="kv"><span>Masa Pemanfaatan</span><b><?php echo $t($row['masa_pemanfaatan'] === 'lebih_5_tahun' ? 'Lebih dari 5 tahun' : ($row['masa_pemanfaatan'] === 'kurang_5_tahun' ? 'Kurang dari 5 tahun' : null)); ?></b></div>
        <div class="kv"><span>Koordinat Prototipe</span><b><?php echo $t(trim($row['prototipe_latitude'] . ', ' . $row['prototipe_longitude'], ', ')); ?></b></div>
        <div class="kv"><span>Peta / Denah Prototipe</span><b><?php echo !empty($row['prototipe_peta']) ? '<a href="' . base_url($berkas_base . 'prototipe_peta/' . (int) $row['id']) . '" target="_blank" rel="noopener noreferrer" style="color:var(--gold-300);text-decoration:underline">Lihat berkas</a>' : '—'; ?></b></div>
      </div>
    </div>
    <?php endif; ?>

    <div class="card">
      <h4>Dokumen Tanah Bangunan</h4>
      <div class="kv-grid">
        <div class="kv"><span>Jenis Dokumen Kepemilikan</span><b><?php echo $t($row['tanah_jenis_dokumen']); ?></b></div>
        <div class="kv"><span>Nomor / Tanggal Terbit</span><b><?php echo $t($row['tanah_nomor_dokumen']); ?> — <?php echo $tgl($row['tanah_tanggal_terbit']); ?></b></div>
        <div class="kv"><span>Luas Tanah</span><b><?php echo $t($row['tanah_luas']); ?> m²</b></div>
        <div class="kv"><span>Hak Kepemilikan</span><b><?php echo $t($row['tanah_hak_kepemilikan']); ?></b></div>
        <div class="kv"><span>Nama Pemilik Hak Tanah</span><b><?php echo $t($row['tanah_nama_pemilik']); ?></b></div>
        <div class="kv"><span>Lampiran Dokumen</span><b><?php echo !empty($row['tanah_lampiran']) ? '<a href="' . base_url($berkas_base . 'tanah_lampiran/' . (int) $row['id']) . '" target="_blank" rel="noopener noreferrer" style="color:var(--gold-300);text-decoration:underline">Lihat berkas</a>' : '—'; ?></b></div>
        <div class="kv full"><span>Alamat Lokasi Tanah</span><b><?php echo $t($row['tanah_alamat']); ?></b></div>
        <div class="kv"><span>Pemilik Tanah = Pemilik Bangunan?</span><b><?php echo $t($row['tanah_pemilik_sama'] === 'sama' ? 'Sama' : ($row['tanah_pemilik_sama'] === 'tidak' ? 'Tidak' : null)); ?></b></div>
        <div class="kv"><span>Nomor / Tanggal Izin Pemanfaatan</span><b><?php echo $t($row['tanah_nomor_izin']); ?> — <?php echo $tgl($row['tanah_tanggal_izin']); ?></b></div>
      </div>
    </div>

    <div class="card">
      <h4>Dokumen Teknis Terunggah</h4>
      <?php if (empty($dokumen)): ?>
        <p class="doc-empty">Belum ada dokumen teknis yang diunggah.</p>
      <?php else: ?>
        <?php foreach ($dokumen as $d): ?>
          <div class="doc-item" style="flex-wrap:wrap;align-items:flex-start">
            <span>
              <?php echo htmlspecialchars($d['jenis_dokumen'], ENT_QUOTES, 'UTF-8'); ?> — <?php echo htmlspecialchars($d['nama_file_asli'], ENT_QUOTES, 'UTF-8'); ?>
              <?php if (isset($d['status']) && $d['status'] === 'ditolak'): ?>
                <br><span class="tag tag-ditolak">Ditolak</span>
                <?php if (!empty($d['catatan_penolakan'])): ?>
                  <br><span style="color:var(--muted);font-size:.82rem"><?php echo $t($d['catatan_penolakan']); ?></span>
                <?php endif; ?>
              <?php endif; ?>
            </span>
            <a href="<?php echo base_url($berkas_base . 'dokumen/' . (int) $d['id']); ?>" target="_blank" rel="noopener noreferrer">Lihat</a>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
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
