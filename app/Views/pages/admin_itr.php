<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pengajuan ITR — Panel Admin · SIP Gatutkaca</title>
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
.dash-wrap{max-width:1400px;margin:0;padding:0 44px}
@media(max-width:860px){
  .dash-layout{flex-direction:column}
  .dash-sidebar{width:100%;flex:0 0 auto;height:auto;min-height:0;flex-direction:row;justify-content:space-between;align-items:center;border-right:none;border-bottom:1px solid var(--line);padding:0}
  .dash-sidebar nav{flex-direction:row;justify-content:center;flex-wrap:wrap}
  .dash-sidebar a{padding:14px 20px;border-left:none;border-bottom:3px solid transparent}
  .dash-sidebar a.active{border-left-color:transparent;border-bottom-color:var(--gold-500)}
  .dash-wrap{padding:0 24px}
}

.btn{display:inline-block;padding:15px 34px;font-size:.78rem;letter-spacing:.26em;text-transform:uppercase;transition:.3s;cursor:pointer;border:none;font-family:var(--body)}
.btn-ghost{border:1px solid var(--line);color:var(--text);background:transparent}
.btn-ghost:hover{border-color:#C9A24B;color:#E4C87B}
.btn-sm{padding:11px 26px;font-size:.72rem;letter-spacing:.2em}
.btn-xs{padding:8px 16px;font-size:.68rem;letter-spacing:.12em}

section{padding:60px 0 100px}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.8rem,3.4vw,2.6rem);line-height:1.2;max-width:32ch}
.alert{padding:16px 20px;margin:26px 0 0;font-size:.88rem;border:1px solid}
.alert-ok{background:rgba(46,168,79,.12);border-color:#2EA84F;color:#1c7a37}
.alert-err{background:rgba(224,82,107,.12);border-color:#E0526B;color:#a3283d}
html[data-theme="dark"] .alert-ok{color:#8CE0A6}
html[data-theme="dark"] .alert-err{color:#F3AEB9}

table{width:100%;border-collapse:collapse;margin-top:26px;font-size:.88rem}
th{font-family:var(--display);font-weight:400;letter-spacing:.1em;text-transform:uppercase;font-size:.72rem;color:var(--gold-300);text-align:left;padding:14px;border-bottom:1px solid var(--gold-500)}
td{padding:14px;border-bottom:1px solid var(--line);color:var(--muted);vertical-align:top}
td:first-child{color:var(--text);font-weight:500}
td small{display:block;margin-top:2px;color:var(--muted)}
th small{display:block;margin-top:2px;font-weight:400;font-size:.62rem;letter-spacing:0;text-transform:none;opacity:.75}
.btn-gold{background:linear-gradient(135deg,#C9A24B,#E4C87B);color:#081826;font-weight:600}
.itr-filter{display:flex;flex-wrap:wrap;gap:14px 16px;align-items:flex-end;margin-top:26px;padding:18px 20px;border:1px solid var(--line);background:var(--surface)}
.itr-filter label{display:flex;flex-direction:column;gap:6px;font-size:.66rem;letter-spacing:.14em;text-transform:uppercase;color:var(--muted)}
.itr-filter select,.itr-filter input[type=date]{font:inherit;font-size:.84rem;letter-spacing:0;text-transform:none;padding:10px 12px;min-width:150px;background:var(--input,var(--surface));color:var(--text);border:1px solid var(--line)}
.itr-filter-cari{position:relative;flex:1 1 320px;min-width:240px}
.itr-filter-cari svg{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none}
.itr-filter-cari input{width:100%;font:inherit;font-size:.9rem;padding:12px 14px 12px 38px;background:var(--input,var(--surface));color:var(--text);border:1px solid var(--line)}
.itr-filter-cari input:focus,.itr-filter select:focus,.itr-filter input[type=date]:focus{outline:2px solid var(--gold-500);outline-offset:1px}
.itr-filter-aksi{display:flex;gap:10px}
.itr-bar{display:flex;flex-wrap:wrap;gap:12px 20px;align-items:center;justify-content:space-between;margin-top:18px}
.itr-hitung{font-size:.86rem;color:var(--muted)}
.itr-hitung b{color:var(--text)}
.itr-ekspor{display:flex;flex-wrap:wrap;gap:10px}
.tag{display:inline-block;border:1px solid var(--line);padding:3px 12px;font-size:.66rem;letter-spacing:.14em;text-transform:uppercase;color:var(--gold-300)}
.tag-diajukan{color:#F0A048;border-color:#B4573B}
.tag-sedang_diverifikasi{color:#5FC2E0;border-color:#1E86A3}
.tag-perlu_perbaikan{color:#E0526B;border-color:#E0526B}
.tag-disetujui{color:#6FCF97;border-color:#2EA84F}
.tag-ditolak{color:#E0526B;border-color:#E0526B}

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
@media(max-width:980px){
  .foot-grid{grid-template-columns:1fr}
  table{display:block;overflow-x:auto}
}

/* Tampilan panel admin modern, mengikuti gaya SIMBG */
:root{
  --panel-blue:#0738a8;
  --panel-blue-bright:#0797dc;
  --panel-bg:#f3f6fb;
  --panel-line:#dce4ef;
  --panel-text:#172b44;
  --panel-muted:#66788d;
}
body{background:var(--panel-bg);color:var(--panel-text);font-weight:400;font-size:15px}
body,button,input,select,textarea,.brand-name,.brand-sub,h2,th,.theme-panel h5{
  font-family:'Plus Jakarta Sans',system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif!important;
}
header,header.scrolled{
  height:64px;background:var(--panel-blue)!important;border:0;box-shadow:0 2px 12px rgba(13,42,98,.18);backdrop-filter:none
}
.nav{height:64px;max-width:none;padding:0 30px}
.brand{gap:10px}.brand img{height:42px}.brand-name{color:#fff;font-size:1rem;font-weight:700;letter-spacing:.06em}
.brand-sub{color:rgba(255,255,255,.72);font-size:.58rem;letter-spacing:.13em}
.auth-actions .btn-ghost{color:#fff;background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.48);border-radius:5px}
.auth-actions .btn-ghost:hover{color:var(--panel-blue);background:#fff;border-color:#fff}
.user-menu-btn{color:#fff;font-weight:600}
.dash-layout{padding-top:64px;min-height:100vh}
.dash-sidebar{
  top:64px;height:calc(100vh - 64px);width:224px;flex-basis:224px;padding:24px 12px;
  background:#fff;border-right:1px solid var(--panel-line);box-shadow:2px 0 10px rgba(31,61,101,.04)
}
.dash-sidebar nav{gap:5px}
.dash-sidebar a{
  padding:12px 15px;border:0!important;border-radius:6px;color:#617287;font-size:.76rem;font-weight:600;
  letter-spacing:.035em;text-transform:none
}
.dash-sidebar a:hover{color:var(--panel-blue);background:#edf5ff}
.dash-sidebar a.active{color:#fff;background:var(--panel-blue-bright);box-shadow:0 4px 11px rgba(7,151,220,.2)}
.dash-sidebar a.logout:hover{color:#d74444;background:#fff0f0}
.dash-main{background:var(--panel-bg)}
.dash-wrap{max-width:none;padding:0 30px}
section{padding-bottom:52px!important}
.eyebrow{margin:8px 0 6px;color:var(--panel-blue-bright);font-size:.69rem;font-weight:700;letter-spacing:.12em}
h2{max-width:none;color:var(--panel-text);font-size:1.65rem;font-weight:700;letter-spacing:-.025em}
h2+p{font-size:.9rem!important;color:var(--panel-muted)!important;margin-top:6px!important}
.itr-filter{
  gap:12px;margin-top:22px;padding:16px;background:#fff;border:1px solid var(--panel-line);border-radius:8px;
  box-shadow:0 3px 12px rgba(26,58,99,.05)
}
.itr-filter label{color:#50657e;font-size:.67rem;font-weight:700;letter-spacing:.06em}
.itr-filter select,.itr-filter input[type=date],.itr-filter-cari input{
  min-height:42px;background:#fff;border:1px solid #cfd9e6;border-radius:5px;color:var(--panel-text)
}
.itr-filter-cari input:focus,.itr-filter select:focus,.itr-filter input[type=date]:focus{
  outline:2px solid rgba(7,151,220,.24);border-color:var(--panel-blue-bright)
}
.btn{border-radius:5px;font-weight:700;letter-spacing:.06em}
.btn-gold{background:var(--panel-blue-bright);color:#fff;box-shadow:none}
.btn-gold:hover{background:#067fbc}
.btn-ghost{background:#fff;border-color:#b9c8d9;color:#27415d}
.btn-ghost:hover{color:#fff;background:var(--panel-blue-bright);border-color:var(--panel-blue-bright)}
.itr-bar{margin:16px 0 4px}.itr-hitung{color:var(--panel-muted)}
table{margin-top:14px;background:#fff;border:1px solid var(--panel-line);border-collapse:separate;border-spacing:0;border-radius:8px;overflow:hidden;box-shadow:0 3px 12px rgba(26,58,99,.05)}
th{padding:13px 12px;background:#edf2f8;border:0;border-bottom:1px solid #d7e0eb;color:#334a64;font-size:.68rem;font-weight:700;letter-spacing:.025em}
td{padding:13px 12px;border-color:#e3e9f0;color:#526a82;font-size:.82rem}
td:first-child{color:#20384f;font-weight:700}
.tag{border-radius:999px;padding:4px 10px;font-weight:700;letter-spacing:.04em;background:#fff}
.theme-fab{width:48px;height:48px;border-color:#cfd9e6;color:var(--panel-blue);box-shadow:0 5px 18px rgba(26,58,99,.15)}
@media(max-width:860px){
  .dash-sidebar{top:64px;width:100%;height:auto;padding:8px;background:#fff}
  .dash-sidebar a{padding:10px 14px}
  .dash-wrap{padding:0 18px}
}

/* Pengunci layout: mencegah aturan tema lama mengembalikan header putih dan ruang kosong */
html body #topbar,
html body #topbar.scrolled{
  inset:0 0 auto 0!important;
  height:64px!important;
  min-height:64px!important;
  background:#0738a8!important;
  background-image:none!important;
}
html body #topbar .nav{height:64px!important;min-height:64px!important}
html body #topbar .brand-name,
html body #topbar .brand-sub,
html body #topbar .user-menu-btn{color:#fff!important}
.dash-main>section{
  margin:0!important;
  padding:18px 0 42px!important;
  min-height:0!important;
}
.dash-main>section>.dash-wrap{margin:0!important;padding-top:0!important}
.dash-main .eyebrow{margin-top:0!important;margin-bottom:4px!important}
.dash-main h2{margin:0!important}
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
  <?php $this->load->view('partials/sidebar', array('role'=>'admin','aktif'=>'itr')); ?>
  <div class="dash-main">
<section style="padding-top:20px">
  <div class="dash-wrap">
    <div>
      <p class="eyebrow">Panel Admin</p>
      <h2>Pengajuan ITR</h2>
      <p style="color:var(--muted);max-width:64ch;margin-top:14px">Periksa dokumen, terima/tolak tiap berkas, dan unggah dokumen hasil ITR setelah semua berkas lengkap. Klik "Proses" untuk membuka detail satu pengajuan.</p>
    </div>

    <?php if($this->session->flashdata('sukses')): ?><div class="alert alert-ok"><?= htmlspecialchars($this->session->flashdata('sukses')) ?></div><?php endif; ?>
    <?php if($this->session->flashdata('error')): ?><div class="alert alert-err"><?= htmlspecialchars($this->session->flashdata('error')) ?></div><?php endif; ?>

    <?php if($total_semua>0): ?>
    <form class="itr-filter" method="get" action="<?= base_url('admin_itr') ?>" role="search">
      <div class="itr-filter-cari">
        <svg width="16" height="16" viewBox="0 0 18 18" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="5.5" stroke="currentColor" stroke-width="1.5"/><path d="M12.5 12.5L16 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        <input type="search" name="q" value="<?= htmlspecialchars($filter['q'],ENT_QUOTES,'UTF-8') ?>" placeholder="Cari no. permohonan, nama, NIK/NIB, alamat, lokasi, kegiatan…" aria-label="Cari pengajuan ITR" maxlength="100">
      </div>
      <label>Status
        <select name="status"><option value="">Semua status</option>
        <?php foreach($status_itr as $kode=>$label): ?><option value="<?= $kode ?>" <?= $filter['status']===$kode?'selected':'' ?>><?= htmlspecialchars($label) ?></option><?php endforeach; ?></select>
      </label>
      <label>Jenis Pemohon
        <select name="jenis"><option value="">Semua</option><option value="perorangan" <?= $filter['jenis']==='perorangan'?'selected':'' ?>>Perorangan</option><option value="perusahaan" <?= $filter['jenis']==='perusahaan'?'selected':'' ?>>Perusahaan</option></select>
      </label>
      <label>Kecamatan Lokasi
        <select name="kec"><option value="">Semua kecamatan</option>
        <?php foreach($daftar_kecamatan as $kc): ?><option value="<?= htmlspecialchars($kc,ENT_QUOTES,'UTF-8') ?>" <?= $filter['kec']===$kc?'selected':'' ?>><?= htmlspecialchars($kc) ?></option><?php endforeach; ?></select>
      </label>
      <label>Diajukan dari<input type="date" name="dari" value="<?= htmlspecialchars($filter['dari']) ?>"></label>
      <label>Sampai<input type="date" name="sampai" value="<?= htmlspecialchars($filter['sampai']) ?>"></label>
      <div class="itr-filter-aksi">
        <button type="submit" class="btn btn-gold btn-xs">Cari</button>
        <?php if($filter_aktif): ?><a class="btn btn-ghost btn-xs" href="<?= base_url('admin_itr') ?>">Reset</a><?php endif; ?>
      </div>
    </form>

    <div class="itr-bar">
      <p class="itr-hitung">
        <?php if($filter_aktif): ?>Menampilkan <b><?= count($daftar) ?></b> dari <?= (int)$total_semua ?> pengajuan (hasil pencarian/filter).<?php else: ?>Total <b><?= (int)$total_semua ?></b> pengajuan.<?php endif; ?>
      </p>
      <div class="itr-ekspor">
        <a class="btn btn-gold btn-xs" href="<?= base_url('admin_itr/ekspor'.($query_ekspor!==''?'?'.$query_ekspor:'')) ?>" title="<?= $filter_aktif?'Unduh .xlsx berisi hanya data hasil pencarian/filter yang sedang tampil':'Unduh .xlsx berisi seluruh pengajuan ITR' ?>">⬇ Ekspor Excel (<?= count($daftar) ?> data<?= $filter_aktif?' sesuai filter':'' ?>)</a>
      </div>
    </div>
    <?php endif; ?>

    <?php if($total_semua<1): ?>
      <p style="margin-top:26px;color:var(--muted)">Belum ada pengajuan ITR.</p>
    <?php elseif(!$daftar): ?>
      <p style="margin-top:26px;color:var(--muted)">Tidak ada pengajuan yang cocok dengan pencarian/filter. <a href="<?= base_url('admin_itr') ?>" style="color:var(--gold-300);text-decoration:underline">Reset filter</a></p>
    <?php else: ?>
    <table>
      <thead><tr><th>No. Permohonan</th><th>Nama Pemilik</th><th>Alamat Pemohon<small>tempat tinggal / kantor pemohon</small></th><th>Lokasi Kegiatan<small>persil yang dimohonkan ITR</small></th><th>Fungsi Bangunan</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php foreach($daftar as $r): $perusahaan=($r['jenis_pemohon']??'perorangan')==='perusahaan'; ?>
        <tr>
          <td><?= htmlspecialchars($r['no_permohonan']) ?><small><?= date('d/m/Y',strtotime($r['created_at'])) ?></small></td>
          <td><?= htmlspecialchars($r['nama_pemohon']) ?><small><?= $perusahaan?'Perusahaan':'Perorangan' ?></small></td>
          <td><?= htmlspecialchars($r['alamat_pemohon']?:'—') ?></td>
          <td><?= htmlspecialchars(($r['alamat_lokasi']??'')?:'—') ?><?php if(!empty($r['jenis_kegiatan'])): ?><small>Kegiatan/usaha: <?= htmlspecialchars($r['jenis_kegiatan']) ?></small><?php endif; ?></td>
          <td><?= htmlspecialchars($r['fungsi_bangunan']?:'—') ?></td>
          <td><span class="tag tag-<?= htmlspecialchars($r['status'],ENT_QUOTES,'UTF-8') ?>"><?= ucwords(str_replace('_',' ',$r['status'])) ?></span></td>
          <td><a class="btn btn-ghost btn-xs" href="<?= base_url('admin_itr/detail/'.$r['id']) ?>">Proses</a></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
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
            <span class="brand-sub">Sistem Informasi Pengelolaan Gedung <strong>ANDAL</strong> dan <strong>TERPERCAYA</strong> Untuk Kabupaten Cilacap</span>
          </span>
        </div>
        <p>Dinas Pekerjaan Umum dan Penataan Ruang Kabupaten Cilacap. Melayani dengan semangat <em>&ldquo;otot kawat, balung wesi&rdquo;</em> — kokoh dalam aturan, luwes dalam pelayanan.</p>
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
