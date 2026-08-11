<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $mode === 'edit' ? 'Ubah Pengajuan' : 'Tambah Pengajuan'; ?> — SIP Gatutkaca · Kabupaten Cilacap</title>
<link rel="icon" type="image/png" href="<?php echo base_url('assets/img/icon.png'); ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
<style>
:root{
  --gold-500:#C9A24B;--gold-300:#E4C87B;--gold-100:#F3E3B8;
  --display:'Marcellus',serif;--body:'Plus Jakarta Sans',system-ui,sans-serif;
}
/* ====== TEMA GELAP (bawaan) ====== */
html[data-theme="dark"]{
  --bg:#081826;--bg-alt:#0C2236;--surface:#0C2236;--surface-hi:#123249;
  --text:#F8F4EA;--muted:#B9C7D2;--line:rgba(201,162,75,.28);
  --head-bg:rgba(8,24,38,.94);--head-grad:rgba(8,24,38,.85);
  --hero-1:rgba(8,24,38,.95);--hero-2:rgba(8,24,38,.78);--hero-3:rgba(8,24,38,.28);
  --foot:#050F19;--input:#0F2A40;--shadow:rgba(0,0,0,.5);
}
/* ====== TEMA TERANG ====== */
html[data-theme="light"]{
  --bg:#F8F4EA;--bg-alt:#EFE7D6;--surface:#FFFDF6;--surface-hi:#F5EDDA;
  --text:#152A3B;--muted:#4E6070;--line:rgba(160,124,45,.35);
  --head-bg:rgba(248,244,234,.94);--head-grad:rgba(248,244,234,.85);
  --hero-1:rgba(13,29,44,.88);--hero-2:rgba(13,29,44,.66);--hero-3:rgba(13,29,44,.22);
  --foot:#122536;--input:#FFFFFF;--shadow:rgba(21,42,59,.18);
  --gold-500:#A57E2C;--gold-300:#8F6C1F;--gold-100:#6E5314;
}
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
body{font-family:var(--body);background:var(--bg);color:var(--text);line-height:1.7;font-weight:300;transition:background .4s,color .4s}
img{display:block;max-width:100%}
a{color:inherit;text-decoration:none}
.wrap{max-width:1180px;margin:0 auto;padding:0 28px}

/* ===== NAVBAR ===== */
header{position:fixed;inset:0 0 auto 0;z-index:60;transition:.4s;background:linear-gradient(180deg,var(--head-grad),transparent)}
header.scrolled{background:var(--head-bg);backdrop-filter:blur(12px);box-shadow:0 1px 0 var(--line)}
.nav{display:flex;align-items:center;justify-content:space-between;height:84px;gap:18px}
.brand{display:flex;align-items:center;gap:13px;flex:0 0 auto}
.brand img{height:50px;width:auto;filter:drop-shadow(0 2px 6px var(--shadow))}
.brand-name{font-family:var(--display);font-size:1.2rem;letter-spacing:.13em;color:var(--gold-300)}
.brand-sub{font-size:.6rem;letter-spacing:.3em;text-transform:uppercase;color:var(--muted)}
.auth-actions{display:flex;align-items:center;gap:14px}

/* ===== MENU PENGGUNA (dropdown navbar) ===== */
.user-menu{position:relative}
.user-menu-btn{display:flex;align-items:center;gap:8px;background:none;border:none;font-family:var(--body);font-size:.78rem;letter-spacing:.08em;color:var(--gold-300);font-weight:600;cursor:pointer;padding:8px 2px}
.user-menu-btn svg{transition:transform .25s}
.user-menu-btn[aria-expanded="true"] svg{transform:rotate(180deg)}
.user-menu-panel{position:absolute;top:calc(100% + 12px);right:0;min-width:190px;background:var(--surface);border:1px solid var(--line);box-shadow:0 16px 42px var(--shadow);padding:8px;opacity:0;transform:translateY(8px);pointer-events:none;transition:.25s;z-index:70}
.user-menu-panel.open{opacity:1;transform:none;pointer-events:auto}
.user-menu-panel a{display:flex;align-items:center;gap:10px;padding:11px 14px;font-size:.76rem;letter-spacing:.04em;color:var(--text)}
.user-menu-panel a:hover{background:var(--surface-hi);color:var(--gold-300)}
.user-menu-panel a.logout:hover{color:#E0526B;background:rgba(224,82,107,.08)}

/* ===== TATA LETAK DASHBOARD (sidebar) ===== */
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
  .dash-sidebar nav{flex-direction:row;justify-content:center}
  .dash-sidebar a{padding:14px 20px;border-left:none;border-bottom:3px solid transparent}
  .dash-sidebar a.active{border-left-color:transparent;border-bottom-color:var(--gold-500)}
  .dash-wrap{padding:0 24px}
}

.btn{display:inline-block;padding:15px 34px;font-size:.78rem;letter-spacing:.26em;text-transform:uppercase;transition:.3s;cursor:pointer;border:none;font-family:var(--body)}
.btn-gold{background:linear-gradient(135deg,#C9A24B,#E4C87B);color:#081826;font-weight:600}
.btn-gold:hover{filter:brightness(1.08);transform:translateY(-2px)}
.btn-ghost{border:1px solid rgba(248,244,234,.45);color:#F8F4EA;background:transparent}
.btn-ghost:hover{border-color:#C9A24B;color:#E4C87B}
.btn-sm{padding:11px 26px;font-size:.72rem;letter-spacing:.2em}

/* ===== SECTION ===== */
section{padding:60px 0 100px}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.8rem,3.4vw,2.6rem);line-height:1.2;max-width:32ch}
.section-lead{color:var(--muted);max-width:66ch;margin-top:18px}

/* ===== FORM ===== */
.form-card{background:var(--surface);border:1px solid var(--line);padding:46px;max-width:760px;margin-top:44px}
.form-group{margin-bottom:36px}
.form-group:last-of-type{margin-bottom:0}
.form-group h3{font-family:var(--display);font-weight:400;font-size:1.02rem;letter-spacing:.04em;color:var(--gold-300);margin-bottom:20px;padding-bottom:10px;border-bottom:1px dashed var(--line)}
.field{margin-bottom:22px}
.field-row{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.field-row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:18px}
label{display:block;font-size:.72rem;letter-spacing:.24em;text-transform:uppercase;color:var(--muted);margin-bottom:9px}
label .opsional{text-transform:none;letter-spacing:0;color:var(--muted);opacity:.7}
input,select,textarea{width:100%;background:var(--input);border:1px solid var(--line);color:var(--text);padding:13px 15px;font-family:var(--body);font-size:.92rem}
input:focus,select:focus,textarea:focus{outline:1px solid var(--gold-500);border-color:var(--gold-500)}
textarea{resize:vertical;min-height:90px}
.form-actions{display:flex;gap:14px;margin-top:8px}
.form-actions .btn{flex:0 0 auto}
.alert{padding:16px 20px;margin-bottom:26px;font-size:.88rem;border:1px solid}
.alert-ok{background:rgba(46,168,79,.12);border-color:#2EA84F;color:#8CE0A6}
.alert-err{background:rgba(224,82,107,.12);border-color:#E0526B;color:#F3AEB9}

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

/* ===== FOOTER ===== */
footer{background:var(--foot);color:#F8F4EA;padding:66px 0 32px;border-top:1px solid var(--line)}
.foot-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr;gap:50px}
.foot-grid h4{font-family:var(--display);font-weight:400;letter-spacing:.14em;color:#E4C87B;margin-bottom:18px;font-size:1rem}
.foot-grid p,.foot-grid li{font-size:.88rem;color:#B9C7D2}
.foot-grid ul{list-style:none;display:grid;gap:10px}
.foot-grid a:hover{color:#E4C87B}
.credit{margin-top:50px;padding-top:22px;border-top:1px solid rgba(185,199,210,.15);display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap;font-size:.72rem;color:rgba(185,199,210,.6);letter-spacing:.06em}

/* ===== REVEAL ===== */
.reveal{opacity:0;transform:translateY(28px);transition:opacity .8s ease,transform .8s ease}
.reveal.in{opacity:1;transform:none}
@media (prefers-reduced-motion:reduce){
  .reveal{opacity:1;transform:none;transition:none}
  html{scroll-behavior:auto}
}

/* ===== RESPONSIF ===== */
@media(max-width:980px){
  .foot-grid{grid-template-columns:1fr}
}
@media(max-width:640px){
  .field-row,.field-row-3{grid-template-columns:1fr}
  .form-card{padding:30px 22px}
}
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
          <a href="<?php echo base_url('pengaturan'); ?>" role="menuitem">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="2.3" stroke="currentColor" stroke-width="1.3"/><path d="M8 1.5v1.6M8 12.9v1.6M14.5 8h-1.6M3.1 8H1.5M12.4 3.6l-1.1 1.1M4.7 11.3l-1.1 1.1M12.4 12.4l-1.1-1.1M4.7 4.7L3.6 3.6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
            Pengaturan
          </a>
          <a href="<?php echo base_url('login/keluar'); ?>" role="menuitem" class="logout">
            <svg width="16" height="16" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M7 2H3a1 1 0 00-1 1v12a1 1 0 001 1h4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M11 12.5L15 9l-4-3.5M15 9H6.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Logout
          </a>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="dash-layout">
  <aside class="dash-sidebar">
    <nav>
      <a href="<?php echo base_url('pemohon'); ?>">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><rect x="1" y="1" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="10" y="1" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="1" y="10" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="10" y="10" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.4"/></svg>
        Dashboard
      </a>
      <a href="<?php echo base_url('pengajuan'); ?>" class="active">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M5 2h5l3 3v11H5V2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M10 2v3h3" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M7 9.5h4M7 12.5h4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
        Pengajuan
      </a>
    </nav>
    <nav>
      <a href="<?php echo base_url('login/keluar'); ?>" class="logout">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M7 2H3a1 1 0 00-1 1v12a1 1 0 001 1h4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M11 12.5L15 9l-4-3.5M15 9H6.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Logout
      </a>
    </nav>
  </aside>
  <div class="dash-main">
<section style="padding-top:100px">
  <div class="dash-wrap">
    <div class="reveal">
      <p class="eyebrow"><a href="<?php echo base_url('pengajuan'); ?>" style="color:var(--gold-500)">Pengajuan Saya</a></p>
      <h2><?php echo $mode === 'edit' ? 'Ubah Pengajuan' : 'Tambah Pengajuan Baru'; ?></h2>
      <p class="section-lead">Lengkapi data umum sesuai persyaratan PBG/SLF (Peraturan Bupati Cilacap Nomor 52 Tahun 2023). Kolom bertanda opsional boleh dikosongkan kalau belum tersedia.</p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-err reveal" style="margin-top:36px;max-width:760px"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <?php
      $aksi = $mode === 'edit' ? base_url('pengajuan/perbarui/' . (int) $baris['id']) : base_url('pengajuan/simpan');
      $v = function ($nama) use ($baris) {
        return isset($baris[$nama]) ? htmlspecialchars($baris[$nama], ENT_QUOTES, 'UTF-8') : '';
      };
    ?>
    <form class="form-card reveal" action="<?php echo $aksi; ?>" method="post">

      <div class="form-group">
        <h3>Jenis Pengajuan</h3>
        <div class="field-row">
          <div class="field">
            <label for="f-layanan">Layanan</label>
            <select id="f-layanan" name="jenis_layanan" required>
              <option value="" disabled <?php echo $v('jenis_layanan') === '' ? 'selected' : ''; ?>>— Pilih layanan —</option>
              <option value="pbg" <?php echo $v('jenis_layanan') === 'pbg' ? 'selected' : ''; ?>>PBG — Persetujuan Bangunan Gedung</option>
              <option value="slf" <?php echo $v('jenis_layanan') === 'slf' ? 'selected' : ''; ?>>SLF — Sertifikat Laik Fungsi</option>
            </select>
          </div>
          <div class="field">
            <label for="f-jenis-bangunan">Jenis Bangunan</label>
            <select id="f-jenis-bangunan" name="jenis_bangunan" required>
              <option value="hunian" <?php echo $v('jenis_bangunan') === 'hunian' || $v('jenis_bangunan') === '' ? 'selected' : ''; ?>>Bangunan Hunian / Rumah Tinggal</option>
              <option value="non_hunian" <?php echo $v('jenis_bangunan') === 'non_hunian' ? 'selected' : ''; ?>>Bangunan Umum Non Hunian &amp; Campuran</option>
            </select>
          </div>
        </div>
      </div>

      <div class="form-group">
        <h3>Data Identitas Pemilik Bangunan</h3>
        <div class="field-row">
          <div class="field">
            <label for="f-nama">Nama Pemohon</label>
            <input id="f-nama" name="nama_pemohon" type="text" required value="<?php echo $v('nama_pemohon'); ?>">
          </div>
          <div class="field">
            <label for="f-nik">NIK / KTP / KITAS</label>
            <input id="f-nik" name="nik_ktp" type="text" required value="<?php echo $v('nik_ktp'); ?>">
          </div>
        </div>
        <div class="field">
          <label for="f-nib">NIB <span class="opsional">(wajib untuk bangunan non hunian &amp; campuran)</span></label>
          <input id="f-nib" name="nib" type="text" value="<?php echo $v('nib'); ?>">
        </div>
      </div>

      <div class="form-group">
        <h3>Data Bangunan</h3>
        <div class="field">
          <label for="f-alamat">Alamat / Lokasi Bangunan</label>
          <textarea id="f-alamat" name="alamat_bangunan" required><?php echo $v('alamat_bangunan'); ?></textarea>
        </div>
        <div class="field-row">
          <div class="field">
            <label for="f-luas">Luas Bangunan (m²) <span class="opsional">(opsional)</span></label>
            <input id="f-luas" name="luas_bangunan" type="text" inputmode="decimal" value="<?php echo $v('luas_bangunan'); ?>">
          </div>
          <div class="field">
            <label for="f-lantai">Jumlah Lantai <span class="opsional">(opsional)</span></label>
            <input id="f-lantai" name="jumlah_lantai" type="text" inputmode="numeric" value="<?php echo $v('jumlah_lantai'); ?>">
          </div>
        </div>
      </div>

      <div class="form-group">
        <h3>Data Intensitas Bangunan &amp; Kepemilikan Tanah <span class="opsional" style="text-transform:none;letter-spacing:0">(opsional)</span></h3>
        <div class="field">
          <label for="f-kkpr">No. KKPR/KRK atau Informasi Tata Ruang (ITR)</label>
          <input id="f-kkpr" name="no_kkpr_krk" type="text" value="<?php echo $v('no_kkpr_krk'); ?>">
        </div>
        <div class="field-row">
          <div class="field">
            <label for="f-tanah">Bukti Kepemilikan Tanah</label>
            <input id="f-tanah" name="bukti_tanah" type="text" placeholder="Sertifikat Tanah / Girik / Letter C" value="<?php echo $v('bukti_tanah'); ?>">
          </div>
          <div class="field">
            <label for="f-sppt">No. SPPT / Keterangan NOP</label>
            <input id="f-sppt" name="no_sppt_nop" type="text" value="<?php echo $v('no_sppt_nop'); ?>">
          </div>
        </div>
      </div>

      <div class="form-group">
        <h3>Data Penyedia Jasa Perencana <span class="opsional" style="text-transform:none;letter-spacing:0">(opsional)</span></h3>
        <div class="field-row">
          <div class="field">
            <label for="f-perencana">Nama Perencana Konstruksi / Arsitek</label>
            <input id="f-perencana" name="nama_perencana" type="text" value="<?php echo $v('nama_perencana'); ?>">
          </div>
          <div class="field">
            <label for="f-lisensi">No. Lisensi (SKK/STRA/STRI)</label>
            <input id="f-lisensi" name="no_lisensi_perencana" type="text" value="<?php echo $v('no_lisensi_perencana'); ?>">
          </div>
        </div>
      </div>

      <div class="form-actions">
        <button class="btn btn-gold" type="submit"><?php echo $mode === 'edit' ? 'Simpan Perubahan' : 'Simpan Pengajuan'; ?></button>
        <a class="btn btn-ghost" href="<?php echo base_url('pengajuan'); ?>">Batal</a>
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
    <button class="swatch" data-theme="light"><i class="sw-light"></i><span>Terang</span></button>
    <button class="swatch sel" data-theme="dark"><i class="sw-dark"></i><span>Gelap</span></button>
  </div>
</div>

<script>
(function(){
  var p=new URLSearchParams(location.search);
  var t=p.get('theme')==='light'?'light':'dark';
  applyTheme(t,false);
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
var io=new IntersectionObserver(function(es){es.forEach(function(e){
  if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}
})},{threshold:.12});
document.querySelectorAll('.reveal').forEach(function(el){io.observe(el)});
</script>
</body>
</html>
