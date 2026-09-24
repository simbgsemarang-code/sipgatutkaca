<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Pengguna — SIP Gatutkaca · Kabupaten Cilacap</title>
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
  --bg:#FDFBF5;--bg-alt:#F6F1E3;--surface:#FFFFFF;--surface-hi:#FAF5E8;
  --text:#152A3B;--muted:#4E6070;--line:rgba(160,124,45,.35);
  --head-bg:rgba(253,251,245,.94);--head-grad:rgba(253,251,245,.85);
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
.btn-ghost{border:1px solid var(--line);color:var(--text);background:transparent}
.btn-ghost:hover{border-color:#C9A24B;color:#E4C87B}
.btn-sm{padding:11px 26px;font-size:.72rem;letter-spacing:.2em}

/* ===== SECTION ===== */
section{padding:60px 0 100px}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.8rem,3.4vw,2.6rem);line-height:1.2;max-width:24ch}
.section-lead{color:var(--muted);max-width:66ch;margin-top:18px}

/* ===== TABEL & FORM ===== */
table{width:100%;border-collapse:collapse;margin-top:44px;font-size:.9rem}
th{font-family:var(--display);font-weight:400;letter-spacing:.12em;text-transform:uppercase;font-size:.74rem;color:var(--gold-300);text-align:left;padding:16px 14px;border-bottom:1px solid var(--gold-500)}
td{padding:16px 14px;border-bottom:1px solid var(--line);color:var(--muted);vertical-align:top}
td:first-child{color:var(--text);font-weight:500}
.tag{display:inline-block;border:1px solid var(--line);padding:3px 12px;font-size:.68rem;letter-spacing:.16em;text-transform:uppercase;color:var(--gold-300)}
.tag-pu{color:#5FC2E0;border-color:#1E86A3}
.tag-tpa{color:#F0A048;border-color:#B4573B}
.tag-pemohon{color:var(--muted);border-color:var(--line)}
.dl{color:var(--gold-300);letter-spacing:.12em;font-size:.78rem;text-transform:uppercase;white-space:nowrap}
.dl:hover{text-decoration:underline}
.hapus-link{color:#E0526B;letter-spacing:.12em;font-size:.78rem;text-transform:uppercase;white-space:nowrap;background:none;border:none;cursor:pointer;font-family:var(--body)}
.hapus-link:hover{text-decoration:underline}

.form-card{background:var(--surface);border:1px solid var(--line);padding:46px;max-width:520px}
.field{margin-bottom:22px}
label{display:block;font-size:.72rem;letter-spacing:.24em;text-transform:uppercase;color:var(--muted);margin-bottom:9px}
input,select,textarea{width:100%;background:var(--input);border:1px solid var(--line);color:var(--text);padding:13px 15px;font-family:var(--body);font-size:.92rem}
input:focus,select:focus,textarea:focus{outline:1px solid var(--gold-500);border-color:var(--gold-500)}
.note{font-size:.78rem;color:var(--muted);margin-top:16px}
.alert{padding:16px 20px;margin-bottom:26px;font-size:.88rem;border:1px solid}
.alert-ok{background:rgba(46,168,79,.12);border-color:#2EA84F;color:#8CE0A6}
.alert-err{background:rgba(224,82,107,.12);border-color:#E0526B;color:#F3AEB9}

.list-head{display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-top:20px}
.search-form{display:flex;gap:10px;margin-top:26px;max-width:460px}
.search-form input{flex:1}
.search-form button{flex:0 0 auto;padding:0 22px;font-size:.78rem;letter-spacing:.18em;text-transform:uppercase;border:1px solid var(--gold-500);background:transparent;color:var(--gold-300);cursor:pointer;font-family:var(--body)}
.search-form button:hover{background:var(--gold-500);color:#081826}
.aksi-cell{white-space:nowrap;display:flex;gap:8px;flex-wrap:wrap}
.reset-btn{border:1px solid var(--line);color:var(--muted);background:transparent;padding:7px 14px;font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;font-family:var(--body)}
.reset-btn:hover{border-color:var(--gold-500);color:var(--gold-300)}
.pagination{display:flex;gap:6px;margin-top:30px;flex-wrap:wrap;align-items:center;font-size:.8rem}
.pagination a{display:inline-block;padding:8px 14px;border:1px solid var(--line);color:var(--muted)}
.pagination a:hover{border-color:var(--gold-500);color:var(--gold-300)}
.pagination span.current{display:inline-block;padding:8px 14px;border:1px solid var(--gold-500);background:var(--gold-500);color:#081826;font-weight:600}
.kredensial-panel p{margin:0 0 10px}
.kredensial-panel .kode{font-family:monospace;font-size:.86rem}
.kredensial-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:14px}

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
  .split-admin{grid-template-columns:1fr}
  .foot-grid{grid-template-columns:1fr}
  table{display:block;overflow-x:auto}
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
          <?php echo htmlspecialchars($nama_admin, ENT_QUOTES, 'UTF-8'); ?>
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
      <a href="<?php echo base_url('admin'); ?>">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><rect x="2" y="2" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="10" y="2" width="6" height="4" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="10" y="8" width="6" height="8" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="2" y="10" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.4"/></svg>
        Dashboard
      </a>
      <a href="<?php echo base_url('admin/pengguna'); ?>" class="active">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><circle cx="6.5" cy="5.5" r="2.6" stroke="currentColor" stroke-width="1.4"/><path d="M1.8 15c0-2.6 2.1-4.4 4.7-4.4S11.2 12.4 11.2 15" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><path d="M12.4 4.2a2.3 2.3 0 010 4.4M13.6 14.8c0-2.1-1-3.7-2.6-4.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
        Kelola Pengguna
      </a>
      <a href="<?php echo base_url('admin/pengajuan'); ?>">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M4 1.5h7L14.5 5v11a1 1 0 01-1 1h-9a1 1 0 01-1-1v-13a1 1 0 011-1z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M11 1.5V5h3.5" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M5.5 9.5h7M5.5 12h7M5.5 7h3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
        Pengajuan PBG
      </a>
      <a href="<?php echo base_url('admin/bangunan'); ?>">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M9 1.5c-2.9 0-5 2.1-5 4.9 0 3.4 5 10.1 5 10.1s5-6.7 5-10.1c0-2.8-2.1-4.9-5-4.9z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><circle cx="9" cy="6.4" r="1.9" stroke="currentColor" stroke-width="1.4"/></svg>
        Sebaran Bangunan
      </a>
      <a href="<?php echo base_url('admin/cagar-budaya'); ?>">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M9 1.4l2.1 4.4 4.8.7-3.5 3.4.8 4.8L9 12.4l-4.2 2.3.8-4.8L2.1 6.5l4.8-.7L9 1.4z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>
        Kelola Cagar Budaya
      </a>
      <a href="<?php echo base_url('admin/saran'); ?>">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M2.5 3.5h13a1 1 0 011 1v7a1 1 0 01-1 1H7l-3.5 3v-3H2.5a1 1 0 01-1-1v-7a1 1 0 011-1z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M5 6.5h8M5 9h5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
        Saran &amp; FAQ
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
<section style="padding-top:20px">
  <div class="dash-wrap">
    <div class="reveal list-head">
      <div>
        <p class="eyebrow">Panel Admin</p>
        <h2>Kelola Pengguna</h2>
        <p class="section-lead">Kelola akun staf Tim Profesi Ahli (Arsitek, Struktur, atau MEP), Pekerjaan Umum (PU), atau admin lain. Akun pemohon hanya bisa dibuat lewat pendaftaran mandiri di halaman <?php echo base_url('daftar'); ?>.</p>
      </div>
      <a class="btn btn-gold btn-sm" href="<?php echo base_url('admin/pengguna-form'); ?>">+ Tambah Pengguna</a>
    </div>

    <?php if (!empty($sukses)): ?>
      <div class="alert alert-ok reveal" style="margin-top:36px"><?php echo htmlspecialchars($sukses, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert alert-err reveal" style="margin-top:36px"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <?php if (!empty($kredensial)): ?>
      <div class="alert alert-ok reveal kredensial-panel" style="margin-top:36px">
        <p>Kata sandi untuk <strong><?php echo htmlspecialchars($kredensial['nama'], ENT_QUOTES, 'UTF-8'); ?></strong> berhasil disimpan. Kirim kredensial berikut ke pengguna:</p>
        <p class="kode">Email: <?php echo htmlspecialchars($kredensial['email'], ENT_QUOTES, 'UTF-8'); ?> &nbsp;|&nbsp; Kata Sandi: <strong><?php echo htmlspecialchars($kredensial['password'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
        <div class="kredensial-actions">
          <?php $wa_link = kredensial_wa_link($kredensial['no_hp'], $kredensial['nama'], $kredensial['email'], $kredensial['password']); ?>
          <?php if ($wa_link): ?>
            <a class="btn btn-gold btn-sm" target="_blank" rel="noopener" href="<?php echo htmlspecialchars($wa_link, ENT_QUOTES, 'UTF-8'); ?>">Kirim via WhatsApp</a>
          <?php else: ?>
            <span class="note" style="margin:0">No. HP belum diisi — lengkapi dulu untuk kirim via WhatsApp.</span>
          <?php endif; ?>
          <a class="btn btn-ghost btn-sm" href="<?php echo htmlspecialchars(kredensial_mailto_link($kredensial['email'], $kredensial['nama'], $kredensial['email'], $kredensial['password']), ENT_QUOTES, 'UTF-8'); ?>">Kirim via Email</a>
        </div>
      </div>
    <?php endif; ?>

    <?php echo form_open('admin/pengguna', array('method' => 'get', 'class' => 'search-form reveal')); ?>
      <input type="search" name="q" value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Cari nama, surel, NIK, atau No. HP">
      <button type="submit">Cari</button>
    <?php echo form_close(); ?>

    <div class="reveal">
      <p class="eyebrow" style="margin-top:30px;margin-bottom:8px">Daftar Pengguna</p>
      <h2 style="font-size:1.3rem"><?php echo (int) $total; ?> akun ditemukan</h2>
      <div style="overflow-x:auto">
      <table>
        <thead><tr><th>Aksi</th><th>Pengguna</th><th>No. HP</th><th>Jenis</th><th>Terdaftar</th></tr></thead>
        <tbody>
          <?php if (empty($daftar_user)): ?>
            <tr><td colspan="5">Pengguna tidak ditemukan.</td></tr>
          <?php else: ?>
            <?php foreach ($daftar_user as $u): ?>
              <tr>
                <td class="aksi-cell">
                  <a class="btn btn-ghost btn-sm" style="padding:9px 18px" href="<?php echo base_url('admin/pengguna-form/' . (int) $u['id']); ?>">Edit</a>
                  <form action="<?php echo base_url('admin/reset-password-pengguna/' . (int) $u['id']); ?>" method="post" onsubmit="return confirm('Kata sandi &quot;<?php echo htmlspecialchars(addslashes($u['nama']), ENT_QUOTES, 'UTF-8'); ?>&quot; akan diganti dengan kata sandi acak baru. Lanjutkan?');" style="margin:0">
                    <button type="submit" class="reset-btn" title="Buat kata sandi baru lalu siapkan link kirim ke WA/Email">Reset &amp; Kirim</button>
                  </form>
                  <form action="<?php echo base_url('admin/hapus-pengguna/' . (int) $u['id']); ?>" method="post" onsubmit="return confirm('Hapus pengguna &quot;<?php echo htmlspecialchars(addslashes($u['nama']), ENT_QUOTES, 'UTF-8'); ?>&quot;?');" style="margin:0">
                    <button type="submit" class="hapus-link">Hapus</button>
                  </form>
                </td>
                <td>
                  <?php echo htmlspecialchars($u['nama'], ENT_QUOTES, 'UTF-8'); ?><br>
                  <span style="font-size:.82rem"><?php echo htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8'); ?></span>
                  <?php if (!empty($u['nik'])): ?><br><span style="font-size:.82rem">NIK: <?php echo htmlspecialchars($u['nik'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                </td>
                <td><?php echo !empty($u['no_hp']) ? htmlspecialchars($u['no_hp'], ENT_QUOTES, 'UTF-8') : '—'; ?></td>
                <td>
                  <?php
                    // 'tpa'/'pemohon' generik dipertahankan di sini (bukan cuma
                    // 3 spesialisasi baru) karena akun lama bertipe itu masih
                    // bisa ada di tabel dan tetap harus tampil dengan tag yang
                    // benar, walau sudah tidak bisa dipilih lagi saat membuat
                    // pengguna baru.
                    $kelas_tag = array('admin' => 'tag', 'pu' => 'tag tag-pu', 'tpa' => 'tag tag-tpa', 'tpa_arsitek' => 'tag tag-tpa', 'tpa_struktur' => 'tag tag-tpa', 'tpa_mep' => 'tag tag-tpa', 'pemohon' => 'tag tag-pemohon');
                    $kelas = isset($kelas_tag[$u['role']]) ? $kelas_tag[$u['role']] : 'tag';
                    $label_peran = strtoupper(str_replace('_', ' ', $u['role']));
                  ?>
                  <span class="<?php echo $kelas; ?>"><?php echo htmlspecialchars($label_peran, ENT_QUOTES, 'UTF-8'); ?></span>
                </td>
                <td><?php echo htmlspecialchars(date('d M Y', strtotime($u['created_at'])), ENT_QUOTES, 'UTF-8'); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
      </div>
      <?php if (!empty($pagination_links)): ?><?php echo $pagination_links; ?><?php endif; ?>
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
