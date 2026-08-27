<?php
// Helper tampilan lokal (closure, bukan function biasa - aman kalau
// suatu saat view ini di-load lebih dari sekali dalam satu request).
$lama = isset($row) ? $row : null;
$val  = function ($kunci) use ($lama) {
	if ($lama === null || !isset($lama[$kunci]) || $lama[$kunci] === null) { return ''; }
	return htmlspecialchars((string) $lama[$kunci], ENT_QUOTES, 'UTF-8');
};
$radio = function ($kunci, $nilai) use ($lama) {
	return ($lama !== null && isset($lama[$kunci]) && (string) $lama[$kunci] === $nilai) ? 'checked' : '';
};
$opt = function ($kunci, $nilai) use ($lama) {
	return ($lama !== null && isset($lama[$kunci]) && (string) $lama[$kunci] === $nilai) ? 'selected' : '';
};
$berkas_ada = function ($kolom) use ($lama) {
	return ($lama !== null && !empty($lama[$kolom]));
};
$dokumen_terunggah = array();
if (!empty($dokumen_ada)) {
	foreach ($dokumen_ada as $d) { $dokumen_terunggah[$d['jenis_dokumen']] = $d; }
}
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $lama !== null ? 'Lanjutkan' : 'Tambah'; ?> Permohonan PBG — Portal PU · SIP Gatutkaca</title>
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
.btn[disabled]{opacity:.35;cursor:not-allowed}

section{padding:60px 0 100px}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.6rem,3vw,2.2rem);line-height:1.2}
.section-lead{color:var(--muted);max-width:66ch;margin-top:14px}

.alert{padding:16px 20px;margin-bottom:0;margin-top:30px;font-size:.88rem;border:1px solid}
.alert-ok{background:rgba(46,168,79,.12);border-color:#2EA84F;color:#8CE0A6}
.alert-err{background:rgba(224,82,107,.12);border-color:#E0526B;color:#F3AEB9}

/* ===== WIZARD ===== */
.wiz-progress{margin-top:34px;display:flex;align-items:center;gap:14px}
.wiz-progress .bar{flex:1;height:3px;background:var(--line);position:relative;overflow:hidden}
.wiz-progress .bar i{position:absolute;inset:0;width:0;background:linear-gradient(90deg,var(--gold-500),var(--gold-300));transition:width .35s}
.wiz-progress span{font-size:.68rem;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);white-space:nowrap}

.wiz-card{background:var(--surface);border:1px solid var(--line);padding:40px;margin-top:26px}
.wiz-step{display:none}
.wiz-step.current{display:block}
.wiz-step h3{font-family:var(--display);font-weight:400;font-size:1.3rem;margin-bottom:8px}
.wiz-step .wiz-desc{color:var(--muted);font-size:.88rem;margin-bottom:26px}

.field{margin-bottom:22px}
.field label{display:block;font-size:.72rem;letter-spacing:.16em;text-transform:uppercase;color:var(--muted);margin-bottom:9px}
.field input,.field select,.field textarea{width:100%;background:var(--input);border:1px solid var(--line);color:var(--text);padding:12px 14px;font-family:var(--body);font-size:.9rem}
.field input:focus,.field select:focus,.field textarea:focus{outline:1px solid var(--gold-500);border-color:var(--gold-500)}
.field textarea{min-height:80px;resize:vertical}
.field-row{display:grid;grid-template-columns:repeat(2,1fr);gap:20px}
.field-row-3{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
@media(max-width:640px){.field-row,.field-row-3{grid-template-columns:1fr}}
.hint{font-size:.78rem;color:var(--muted);margin-top:-12px;margin-bottom:22px}

.opt-list{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:22px}
.opt-list label{display:flex;align-items:center;gap:9px;border:1px solid var(--line);padding:11px 18px;font-size:.85rem;cursor:pointer;transition:.2s}
.opt-list label:hover{border-color:var(--gold-500)}
.opt-list input:checked + span,.opt-list label:has(input:checked){color:var(--gold-300);border-color:var(--gold-500)}
.opt-list input{margin:0}

.sub-block{border:1px dashed var(--line);padding:20px;margin-bottom:18px;display:none}
.sub-block.on{display:block}
.sub-block > p{font-size:.7rem;letter-spacing:.14em;text-transform:uppercase;color:var(--gold-300);margin-bottom:14px}

.cond{display:none}
.cond.on{display:block}

.file-row{display:flex;align-items:center;justify-content:space-between;gap:16px;border:1px solid var(--line);padding:14px 18px;margin-bottom:12px;flex-wrap:wrap}
.file-row .fr-label{font-size:.85rem}
.file-row .fr-status{font-size:.76rem;color:var(--muted)}
.file-row .fr-status a{color:var(--gold-300);text-decoration:underline}
.file-row input[type=file]{font-size:.78rem;max-width:220px}
.doc-group-title{font-family:var(--display);font-size:1rem;color:var(--gold-300);margin:26px 0 12px}
.doc-group-title:first-child{margin-top:0}

.wiz-nav{display:flex;justify-content:space-between;align-items:center;margin-top:34px;gap:14px;flex-wrap:wrap}
.wiz-nav .grp{display:flex;gap:10px;flex-wrap:wrap}

/* ===== PANEL WARNA ===== */
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

.reveal{opacity:0;transform:translateY(28px);transition:opacity .8s ease,transform .8s ease}
.reveal.in{opacity:1;transform:none}
@media (prefers-reduced-motion:reduce){.reveal{opacity:1;transform:none;transition:none}html{scroll-behavior:auto}}
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
    <p class="eyebrow">Portal PU — <a href="<?php echo base_url('pengajuan-pbg'); ?>" style="color:inherit;text-decoration:underline">Pengajuan PBG</a></p>
    <h2><?php echo $lama !== null ? 'Lanjutkan Permohonan' : 'Tambah Permohonan PBG'; ?></h2>
    <p class="section-lead">Isi formulir bertahap ini mewakili warga yang datang ke loket. Bisa disimpan sebagai draf dulu kapan saja, dan dilanjutkan lagi nanti.</p>

    <?php if (!empty($error)): ?>
      <div class="alert alert-err"><?php echo nl2br(htmlspecialchars($error, ENT_QUOTES, 'UTF-8')); ?></div>
    <?php endif; ?>

    <div class="wiz-progress">
      <span id="wizLabel">Langkah 1 dari 10</span>
      <div class="bar"><i id="wizBar"></i></div>
    </div>

    <form id="formPengajuan" action="<?php echo base_url('pengajuan-pbg/simpan' . ($lama !== null ? '/' . (int) $lama['id'] : '')); ?>" method="post" enctype="multipart/form-data">
      <input type="hidden" name="aksi" id="inputAksi" value="draf">
      <div class="wiz-card">

        <!-- LANGKAH 1: DATA PEMOHON -->
        <div class="wiz-step current" data-step="0">
          <h3>Data Pemohon</h3>
          <p class="wiz-desc">Identitas warga yang mengajukan permohonan ini (bukan akun staf PU yang sedang login).</p>
          <div class="field">
            <label for="f-nama-pemohon">Nama Pemohon *</label>
            <input id="f-nama-pemohon" name="nama_pemohon" type="text" required value="<?php echo $val('nama_pemohon'); ?>">
          </div>
          <div class="field-row">
            <div class="field">
              <label for="f-nik">NIK Pemohon</label>
              <input id="f-nik" name="nik_pemohon" type="text" maxlength="20" value="<?php echo $val('nik_pemohon'); ?>">
            </div>
            <div class="field">
              <label for="f-kontak">Kontak (No. HP / Email)</label>
              <input id="f-kontak" name="kontak_pemohon" type="text" value="<?php echo $val('kontak_pemohon'); ?>">
            </div>
          </div>
        </div>

        <!-- LANGKAH 2: INTENSITAS PEMANFAATAN RUANG -->
        <div class="wiz-step" data-step="1">
          <h3>Intensitas Pemanfaatan Ruang</h3>
          <p class="wiz-desc">Apakah pemohon sudah memiliki data Intensitas Pemanfaatan Ruang (KDB/KLB/KDH/GSB)?</p>
          <div class="opt-list">
            <label><input type="radio" name="intensitas_ada" value="ya" <?php echo $radio('intensitas_ada', 'ya'); ?> data-toggle="blokIntensitas"><span>Ya, sudah memiliki</span></label>
            <label><input type="radio" name="intensitas_ada" value="tidak" <?php echo $radio('intensitas_ada', 'tidak'); ?> data-toggle="blokIntensitas"><span>Tidak, belum memiliki</span></label>
          </div>
          <div class="sub-block" id="blokIntensitas">
            <p>Data Intensitas Pemanfaatan Ruang</p>
            <div class="field">
              <label for="f-int-no">Nomor Dokumen Izin Pemanfaatan Ruang</label>
              <input id="f-int-no" name="intensitas_no_dokumen" type="text" value="<?php echo $val('intensitas_no_dokumen'); ?>">
            </div>
            <div class="field-row-3">
              <div class="field"><label for="f-gsb">Garis Sempadan Bangunan / GSB (m)</label><input id="f-gsb" name="intensitas_gsb" type="text" value="<?php echo $val('intensitas_gsb'); ?>"></div>
              <div class="field"><label for="f-kdb">Koefisien Dasar Bangunan / KDB (%)</label><input id="f-kdb" name="intensitas_kdb" type="text" value="<?php echo $val('intensitas_kdb'); ?>"></div>
              <div class="field"><label for="f-klb">Koefisien Lantai Bangunan / KLB (%)</label><input id="f-klb" name="intensitas_klb" type="text" value="<?php echo $val('intensitas_klb'); ?>"></div>
            </div>
            <div class="field" style="max-width:calc(33.33% - 14px)">
              <label for="f-kdh">Koefisien Dasar Hijau / KDH (%)</label>
              <input id="f-kdh" name="intensitas_kdh" type="text" value="<?php echo $val('intensitas_kdh'); ?>">
            </div>
          </div>
        </div>

        <!-- LANGKAH 3: LOKASI BANGUNAN -->
        <div class="wiz-step" data-step="2">
          <h3>Lokasi Bangunan</h3>
          <p class="wiz-desc">Dimanakah lokasi bangunan yang akan diajukan?</p>
          <div class="field-row">
            <div class="field"><label for="f-lok-prov">Provinsi</label><input id="f-lok-prov" name="lokasi_provinsi" type="text" value="<?php echo $val('lokasi_provinsi'); ?>" placeholder="Jawa Tengah"></div>
            <div class="field"><label for="f-lok-kab">Kabupaten/Kota</label><input id="f-lok-kab" name="lokasi_kabupaten" type="text" value="<?php echo $val('lokasi_kabupaten'); ?>" placeholder="Cilacap"></div>
          </div>
          <div class="field-row">
            <div class="field"><label for="f-lok-kec">Kecamatan</label><input id="f-lok-kec" name="lokasi_kecamatan" type="text" value="<?php echo $val('lokasi_kecamatan'); ?>"></div>
            <div class="field"><label for="f-lok-kel">Desa/Kelurahan</label><input id="f-lok-kel" name="lokasi_kelurahan" type="text" value="<?php echo $val('lokasi_kelurahan'); ?>"></div>
          </div>
          <div class="field">
            <label for="f-lok-alamat">Alamat Lengkap *</label>
            <textarea id="f-lok-alamat" name="lokasi_alamat"><?php echo $val('lokasi_alamat'); ?></textarea>
          </div>
        </div>

        <!-- LANGKAH 4: KEPEMILIKAN TANAH & BANGUNAN -->
        <div class="wiz-step" data-step="3">
          <h3>Kepemilikan Tanah &amp; Bangunan</h3>
          <div class="field" style="max-width:320px">
            <label for="f-jml-bukti">Jumlah Bukti Kepemilikan Tanah</label>
            <input id="f-jml-bukti" name="jumlah_bukti_tanah" type="number" min="0" value="<?php echo $val('jumlah_bukti_tanah'); ?>">
          </div>
          <div class="field">
            <label for="f-kepemilikan">Bangunan gedung ini dimiliki oleh? *</label>
            <select id="f-kepemilikan" name="kepemilikan_bangunan">
              <option value="">— Pilih —</option>
              <?php foreach ($opsi_kepemilikan as $k => $l): ?>
                <option value="<?php echo $k; ?>" <?php echo $opt('kepemilikan_bangunan', $k); ?>><?php echo htmlspecialchars($l, ENT_QUOTES, 'UTF-8'); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field">
            <label for="f-kondisi">Bagaimana kondisi bangunan saat ini? *</label>
            <select id="f-kondisi" name="kondisi_bangunan">
              <option value="">— Pilih —</option>
              <?php foreach ($opsi_kondisi as $k => $l): ?>
                <option value="<?php echo $k; ?>" <?php echo $opt('kondisi_bangunan', $k); ?>><?php echo htmlspecialchars($l, ENT_QUOTES, 'UTF-8'); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <!-- LANGKAH 5: DESAIN PROTOTIPE -->
        <div class="wiz-step" data-step="4">
          <h3>Desain Prototipe</h3>
          <p class="wiz-desc">Apakah akan menggunakan desain prototipe yang disediakan?</p>
          <div class="opt-list">
            <label><input type="radio" name="pakai_prototipe" value="ya" <?php echo $radio('pakai_prototipe', 'ya'); ?> data-toggle="blokPrototipe"><span>Ya</span></label>
            <label><input type="radio" name="pakai_prototipe" value="tidak" <?php echo $radio('pakai_prototipe', 'tidak'); ?> data-toggle="blokMasaPakai"><span>Tidak</span></label>
          </div>

          <div class="sub-block" id="blokPrototipe">
            <p>Data Bangunan Prototipe</p>
            <div class="field-row">
              <div class="field"><label for="f-pr-unit">Jumlah Unit yang Dibangun</label><input id="f-pr-unit" name="prototipe_jumlah_unit" type="number" min="0" value="<?php echo $val('prototipe_jumlah_unit'); ?>"></div>
              <div class="field">
                <label for="f-pr-jenis">Jenis Desain Prototipe</label>
                <select id="f-pr-jenis" name="prototipe_jenis">
                  <option value="">— Pilih —</option>
                  <?php foreach (array('Rumah Tinggal Sederhana Tipe 36 (PP No. 16 Tahun 2021)','Rumah Tinggal Sederhana Tipe 54 (PP No. 16 Tahun 2021)','Rumah Tinggal Sederhana Tipe 72 (PP No. 16 Tahun 2021)','Rumah Tinggal Sederhana Tipe 22 Alternatif 1 (Kepmen PUPR No: 2947/KPTS/2024)','Rumah Tinggal Sederhana Tipe 30 Alternatif 1 (Kepmen PUPR No: 2947/KPTS/2024)') as $jp): ?>
                    <option value="<?php echo htmlspecialchars($jp, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $opt('prototipe_jenis', $jp); ?>><?php echo htmlspecialchars($jp, ENT_QUOTES, 'UTF-8'); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="field-row">
              <div class="field"><label for="f-pr-lat">Titik Koordinat Latitude Bangunan</label><input id="f-pr-lat" name="prototipe_latitude" type="text" value="<?php echo $val('prototipe_latitude'); ?>"></div>
              <div class="field"><label for="f-pr-lng">Titik Koordinat Longitude Bangunan</label><input id="f-pr-lng" name="prototipe_longitude" type="text" value="<?php echo $val('prototipe_longitude'); ?>"></div>
            </div>
            <div class="file-row">
              <span class="fr-label">Gambar Peta Lokasi Bangunan (jpg/png/pdf, maks 5MB)</span>
              <?php if ($berkas_ada('prototipe_peta')): ?><span class="fr-status">Sudah diunggah — <a href="<?php echo base_url('pengajuan-pbg/berkas/prototipe_peta/' . (int) $lama['id']); ?>" target="_blank" rel="noopener noreferrer">lihat</a></span><?php endif; ?>
              <input type="file" name="prototipe_peta" accept=".jpg,.jpeg,.png,.pdf">
            </div>
          </div>

          <div class="sub-block" id="blokMasaPakai">
            <p>Masa Waktu Pemanfaatan Bangunan</p>
            <div class="field">
              <label>Apakah bangunan akan digunakan lebih dari 5 tahun?</label>
              <div class="opt-list">
                <label><input type="radio" name="masa_pemanfaatan" value="lebih_5_tahun" <?php echo $radio('masa_pemanfaatan', 'lebih_5_tahun'); ?>><span>Ya, lebih dari 5 tahun</span></label>
                <label><input type="radio" name="masa_pemanfaatan" value="kurang_5_tahun" <?php echo $radio('masa_pemanfaatan', 'kurang_5_tahun'); ?>><span>Tidak, kurang dari 5 tahun</span></label>
              </div>
            </div>
          </div>
        </div>

        <!-- LANGKAH 6: FUNGSI BANGUNAN -->
        <div class="wiz-step" data-step="5">
          <h3>Fungsi Bangunan</h3>
          <p class="wiz-desc">Bangunan akan digunakan sebagai? (boleh pilih lebih dari satu)</p>
          <?php if ($lama !== null && !empty($lama['fungsi_bangunan'])): ?>
            <p class="hint">Pilihan tersimpan saat ini:<br><?php echo nl2br(htmlspecialchars($lama['fungsi_bangunan'], ENT_QUOTES, 'UTF-8')); ?><br>(Biarkan kosong di bawah untuk mempertahankan pilihan ini, atau centang ulang untuk menggantinya.)</p>
          <?php endif; ?>
          <div class="opt-list">
            <?php foreach ($peta_fungsi as $kunci => $f): ?>
              <label><input type="checkbox" name="fungsi[]" value="<?php echo $kunci; ?>" data-toggle-fungsi="sub-<?php echo $kunci; ?>"><span><?php echo htmlspecialchars($f['label'], ENT_QUOTES, 'UTF-8'); ?></span></label>
            <?php endforeach; ?>
          </div>
          <?php foreach ($peta_fungsi as $kunci => $f): ?>
            <div class="sub-block" id="sub-<?php echo $kunci; ?>">
              <p>Sub Fungsi — <?php echo htmlspecialchars($f['label'], ENT_QUOTES, 'UTF-8'); ?></p>
              <div class="opt-list">
                <?php foreach ($f['sub'] as $s): ?>
                  <label><input type="checkbox" name="sub_fungsi_<?php echo $kunci; ?>[]" value="<?php echo htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); ?>"><span><?php echo htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); ?></span></label>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- LANGKAH 7: DATA BANGUNAN -->
        <div class="wiz-step" data-step="6">
          <h3>Data Bangunan</h3>
          <p class="wiz-desc">Apakah bangunan memiliki basemen?</p>
          <div class="opt-list">
            <label><input type="radio" name="punya_basemen" value="ya" <?php echo $radio('punya_basemen', 'ya'); ?> data-toggle="blokBasemen"><span>Memiliki</span></label>
            <label><input type="radio" name="punya_basemen" value="tidak" <?php echo $radio('punya_basemen', 'tidak'); ?> data-toggle-off="blokBasemen"><span>Tidak Memiliki</span></label>
          </div>
          <div class="field">
            <label for="f-bg-nama">Nama Bangunan *</label>
            <input id="f-bg-nama" name="bangunan_nama" type="text" value="<?php echo $val('bangunan_nama'); ?>">
          </div>
          <div class="field-row-3">
            <div class="field"><label for="f-bg-luas">Luas Total Per Unit, Selain Basemen (m²)</label><input id="f-bg-luas" name="bangunan_luas_per_unit" type="text" value="<?php echo $val('bangunan_luas_per_unit'); ?>"></div>
            <div class="field"><label for="f-bg-tinggi">Tinggi Bangunan (m)</label><input id="f-bg-tinggi" name="bangunan_tinggi" type="text" value="<?php echo $val('bangunan_tinggi'); ?>"></div>
            <div class="field"><label for="f-bg-lantai">Jumlah Lantai</label><input id="f-bg-lantai" name="bangunan_jumlah_lantai" type="number" min="0" value="<?php echo $val('bangunan_jumlah_lantai'); ?>"></div>
          </div>
          <div class="field-row sub-block" id="blokBasemen">
            <div class="field"><label for="f-bg-luas-bsm">Luas Lapis Basemen (m²)</label><input id="f-bg-luas-bsm" name="bangunan_luas_basemen" type="text" value="<?php echo $val('bangunan_luas_basemen'); ?>"></div>
            <div class="field"><label for="f-bg-lapis-bsm">Jumlah Lapis Basemen</label><input id="f-bg-lapis-bsm" name="bangunan_jumlah_lapis_basemen" type="number" min="0" value="<?php echo $val('bangunan_jumlah_lapis_basemen'); ?>"></div>
          </div>
          <div class="field-row-3">
            <div class="field"><label for="f-bg-unit">Jumlah Unit</label><input id="f-bg-unit" name="bangunan_jumlah_unit" type="number" min="0" value="<?php echo $val('bangunan_jumlah_unit'); ?>"></div>
            <div class="field"><label for="f-bg-penghuni">Estimasi Jumlah Penghuni</label><input id="f-bg-penghuni" name="bangunan_estimasi_penghuni" type="number" min="0" value="<?php echo $val('bangunan_estimasi_penghuni'); ?>"></div>
          </div>
          <div class="field-row">
            <div class="field"><label for="f-bg-lat">Titik Koordinat Latitude Bangunan</label><input id="f-bg-lat" name="bangunan_latitude" type="text" value="<?php echo $val('bangunan_latitude'); ?>"></div>
            <div class="field"><label for="f-bg-lng">Titik Koordinat Longitude Bangunan</label><input id="f-bg-lng" name="bangunan_longitude" type="text" value="<?php echo $val('bangunan_longitude'); ?>"></div>
          </div>
          <div class="file-row">
            <span class="fr-label">Gambar Peta Lokasi Bangunan (jpg/png/pdf, maks 5MB)</span>
            <?php if ($berkas_ada('bangunan_peta')): ?><span class="fr-status">Sudah diunggah — <a href="<?php echo base_url('pengajuan-pbg/berkas/bangunan_peta/' . (int) $lama['id']); ?>" target="_blank" rel="noopener noreferrer">lihat</a></span><?php endif; ?>
            <input type="file" name="bangunan_peta" accept=".jpg,.jpeg,.png,.pdf">
          </div>
        </div>

        <!-- LANGKAH 8: DOKUMEN TANAH BANGUNAN -->
        <div class="wiz-step" data-step="7">
          <h3>Formulir Dokumen Tanah Bangunan</h3>
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
          <div class="file-row">
            <span class="fr-label">Lampiran Dokumen Kepemilikan Tanah (jpg/png/pdf, maks 5MB)</span>
            <?php if ($berkas_ada('tanah_lampiran')): ?><span class="fr-status">Sudah diunggah — <a href="<?php echo base_url('pengajuan-pbg/berkas/tanah_lampiran/' . (int) $lama['id']); ?>" target="_blank" rel="noopener noreferrer">lihat</a></span><?php endif; ?>
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

        <!-- LANGKAH 9: UNGGAH DOKUMEN TEKNIS -->
        <div class="wiz-step" data-step="8">
          <h3>Unggah Dokumen Teknis</h3>
          <p class="wiz-desc">Lengkapi data dengan mengunggah dokumen berikut (jpg/png/pdf, maks 5MB per berkas).</p>
          <?php foreach ($peta_dokumen as $judul_grup => $grup): ?>
            <p class="doc-group-title"><?php echo htmlspecialchars($judul_grup, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php foreach ($grup['dokumen'] as $slug => $label): ?>
              <div class="file-row">
                <span class="fr-label"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></span>
                <?php if (isset($dokumen_terunggah[$label])): ?>
                  <span class="fr-status">Sudah diunggah: <?php echo htmlspecialchars($dokumen_terunggah[$label]['nama_file_asli'], ENT_QUOTES, 'UTF-8'); ?> — <a href="<?php echo base_url('pengajuan-pbg/berkas/dokumen/' . (int) $dokumen_terunggah[$label]['id']); ?>" target="_blank" rel="noopener noreferrer">lihat</a></span>
                <?php endif; ?>
                <input type="file" name="dokumen[<?php echo $slug; ?>]" accept=".jpg,.jpeg,.png,.pdf">
              </div>
            <?php endforeach; ?>
          <?php endforeach; ?>
        </div>

        <!-- LANGKAH 10: RINGKASAN & KIRIM -->
        <div class="wiz-step" data-step="9">
          <h3>Siap Dikirim</h3>
          <p class="wiz-desc">Periksa kembali tiap langkah lewat tombol Kembali kalau masih ada yang perlu diubah. Setelah dikirim, status permohonan berubah menjadi <strong>Verifikasi Kelengkapan Dokumen</strong> dan nomor registrasi diterbitkan otomatis.</p>
          <p class="hint" style="margin-top:0">Belum yakin datanya sudah lengkap? Gunakan tombol <strong>Simpan Sebagai Draf</strong> di bawah - permohonan bisa dilanjutkan kapan saja lewat menu Pengajuan PBG.</p>
        </div>

      </div>

      <div class="wiz-nav">
        <div class="grp">
          <button type="button" class="btn btn-ghost btn-sm" id="btnKembali" disabled>← Kembali</button>
        </div>
        <div class="grp">
          <button type="submit" class="btn btn-ghost btn-sm" id="btnDraf" data-aksi="draf">Simpan Sebagai Draf</button>
          <button type="button" class="btn btn-gold btn-sm" id="btnSelanjutnya">Selanjutnya →</button>
          <button type="submit" class="btn btn-gold btn-sm" id="btnKirim" data-aksi="kirim" style="display:none">Kirim Permohonan</button>
        </div>
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

// ===== Mesin wizard (satu form, langkah ditampilkan/disembunyikan lewat JS - tetap terkirim sekali di akhir) =====
(function(){
  var steps = Array.prototype.slice.call(document.querySelectorAll('.wiz-step'));
  var total = steps.length;
  var current = 0;
  var label = document.getElementById('wizLabel');
  var bar = document.getElementById('wizBar');
  var btnKembali = document.getElementById('btnKembali');
  var btnSelanjutnya = document.getElementById('btnSelanjutnya');
  var btnKirim = document.getElementById('btnKirim');
  var btnDraf = document.getElementById('btnDraf');
  var inputAksi = document.getElementById('inputAksi');
  var form = document.getElementById('formPengajuan');

  function tampilkan(n, gulir){
    steps.forEach(function(s,i){ s.classList.toggle('current', i===n); });
    label.textContent = 'Langkah ' + (n+1) + ' dari ' + total;
    bar.style.width = (((n+1)/total)*100) + '%';
    btnKembali.disabled = (n===0);
    var akhir = (n === total-1);
    btnSelanjutnya.style.display = akhir ? 'none' : '';
    btnKirim.style.display = akhir ? '' : 'none';
    // Cuma digulir kalau benar-benar berpindah langkah (bukan saat
    // render pertama) - supaya sidebar tidak ikut tergulir ke luar
    // layar begitu halaman baru dibuka.
    if (gulir !== false){
      window.scrollTo({top: form.getBoundingClientRect().top + window.scrollY - 110, behavior:'smooth'});
    }
  }

  btnSelanjutnya.addEventListener('click', function(){
    if (current < total-1){ current++; tampilkan(current); }
  });
  btnKembali.addEventListener('click', function(){
    if (current > 0){ current--; tampilkan(current); }
  });

  // Tombol submit menandai aksi sebelum form benar-benar terkirim.
  btnDraf.addEventListener('click', function(){ inputAksi.value = 'draf'; });
  btnKirim.addEventListener('click', function(){ inputAksi.value = 'kirim'; });

  tampilkan(0, false);
})();

// ===== Blok kondisional Ya/Tidak (mis. intensitas ruang, prototipe, basemen) =====
document.querySelectorAll('[data-toggle]').forEach(function(r){
  r.addEventListener('change', function(){
    var target = document.getElementById(r.dataset.toggle);
    if (target){ target.classList.toggle('on', r.checked); }
  });
});
document.querySelectorAll('[data-toggle-off]').forEach(function(r){
  r.addEventListener('change', function(){
    var target = document.getElementById(r.dataset.toggleOff);
    if (target && r.checked){ target.classList.remove('on'); }
  });
});

// ===== Sub-fungsi bangunan: tampilkan blok sub-fungsi sesuai kategori yang dicentang =====
document.querySelectorAll('[data-toggle-fungsi]').forEach(function(c){
  c.addEventListener('change', function(){
    var target = document.getElementById(c.dataset.toggleFungsi);
    if (target){ target.classList.toggle('on', c.checked); }
  });
});
</script>
</body>
</html>
