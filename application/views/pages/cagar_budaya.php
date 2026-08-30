<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cagar Budaya — SIP Gatutkaca · Kabupaten Cilacap</title>
<link rel="icon" type="image/png" href="<?php echo base_url('assets/img/icon.png'); ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<style>
:root{
  --gold-500:#C9A24B;--gold-300:#E4C87B;--gold-100:#F3E3B8;
  --display:'Marcellus',serif;--body:'Plus Jakarta Sans',system-ui,sans-serif;
}
html[data-theme="dark"]{
  --bg:#081826;--bg-alt:#0C2236;--surface:#0C2236;--surface-hi:#123249;
  --text:#F8F4EA;--muted:#B9C7D2;--line:rgba(201,162,75,.28);
  --head-bg:rgba(8,24,38,.94);--head-grad:rgba(8,24,38,.85);
  --hero-1:rgba(8,24,38,.95);--hero-2:rgba(8,24,38,.78);--hero-3:rgba(8,24,38,.28);
  --foot:#050F19;--input:#0F2A40;--shadow:rgba(0,0,0,.5);
}
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
.page-breadcrumb{margin-bottom:26px;font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)}
.page-breadcrumb a{color:var(--gold-300)}
.page-breadcrumb a:hover{text-decoration:underline}
.page-breadcrumb .sep{margin:0 8px;color:var(--line)}

.hero{position:relative;display:flex;align-items:center;overflow:hidden}
.hero.page{min-height:56vh}
.hero-bg{position:absolute;inset:0;background-position:center 60%;background-size:cover;transform:scale(1.06);animation:slowzoom 26s ease-out forwards}
@keyframes slowzoom{to{transform:scale(1)}}
.hero::after{content:"";position:absolute;inset:0;background:
  linear-gradient(105deg,var(--hero-1) 0%,var(--hero-2) 42%,var(--hero-3) 78%),
  linear-gradient(0deg,var(--bg) 0%,transparent 30%)}
.hero .wrap{position:relative;z-index:2;padding-top:120px;padding-bottom:80px;color:#F8F4EA}
.hero-eyebrow{display:inline-flex;align-items:center;gap:12px;font-size:.72rem;letter-spacing:.4em;text-transform:uppercase;color:#E4C87B;margin-bottom:24px}
.hero-eyebrow::before{content:"";width:44px;height:1px;background:#C9A24B}
h1{font-family:var(--display);font-weight:400;font-size:clamp(2.4rem,5.6vw,4.4rem);line-height:1.08;max-width:18ch;color:#F8F4EA}
h1 em{font-style:normal;color:#E4C87B}
.hero-lead{max-width:54ch;margin:26px 0 0;color:#CBD6DF;font-size:1rem}

.btn{display:inline-block;padding:15px 34px;font-size:.78rem;letter-spacing:.26em;text-transform:uppercase;transition:.3s;cursor:pointer;border:none;font-family:var(--body)}
.btn-gold{background:linear-gradient(135deg,#C9A24B,#E4C87B);color:#081826;font-weight:600}
.btn-gold:hover{filter:brightness(1.08);transform:translateY(-2px)}
.btn-ghost{border:1px solid var(--line);color:var(--text);background:transparent}
.btn-ghost:hover{border-color:#C9A24B;color:#E4C87B}
/* Tombol Dashboard/Masuk di kop transparan di atas hero sebelum discroll - kasih latar solid (sama seperti header.scrolled) supaya tetap kelihatan. */
.auth-actions .btn-ghost{background:var(--head-bg)}
.btn-sm{padding:11px 26px;font-size:.72rem;letter-spacing:.2em}

section{padding:100px 0}
section.alt{background:var(--bg-alt)}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.8rem,3.4vw,2.6rem);line-height:1.2;max-width:24ch}
.section-lead{color:var(--muted);max-width:66ch;margin-top:18px}

.cards{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--line);margin-top:60px;border:1px solid var(--line)}
.card{background:var(--surface);padding:42px 28px 38px;transition:background .35s}
.card:hover{background:var(--surface-hi)}
.card svg{margin-bottom:22px}
.card h3{font-family:var(--display);font-weight:400;font-size:1.24rem;letter-spacing:.06em;margin-bottom:12px;color:var(--gold-300)}
.card p{font-size:.9rem;color:var(--muted)}

.split{display:grid;grid-template-columns:1.05fr .95fr;gap:70px;align-items:center}
.split.rev{grid-template-columns:.95fr 1.05fr}
.photo-frame{position:relative}
.photo-frame img{width:100%;height:460px;object-fit:cover;filter:saturate(.92) contrast(1.04)}
.photo-frame::before{content:"";position:absolute;inset:18px -18px -18px 18px;border:1px solid var(--line);z-index:0;pointer-events:none}
.photo-frame img{position:relative;z-index:1}
.photo-cap{margin-top:14px;font-size:.7rem;letter-spacing:.18em;text-transform:uppercase;color:var(--muted)}

.list{margin-top:30px}
.list-item{display:flex;gap:20px;padding:20px 0;border-bottom:1px solid var(--line)}
.list-item:first-child{border-top:1px solid var(--line)}
.list-key{font-family:var(--display);color:var(--gold-300);letter-spacing:.06em;min-width:170px;font-size:.95rem;flex:0 0 auto}
.list-val{color:var(--muted);font-size:.92rem}

table{width:100%;border-collapse:collapse;margin-top:44px;font-size:.9rem}
th{font-family:var(--display);font-weight:400;letter-spacing:.12em;text-transform:uppercase;font-size:.74rem;color:var(--gold-300);text-align:left;padding:16px 14px;border-bottom:1px solid var(--gold-500)}
td{padding:16px 14px;border-bottom:1px solid var(--line);color:var(--muted);vertical-align:top}
td:first-child{color:var(--text);font-weight:500}
.tag{display:inline-block;border:1px solid var(--line);padding:3px 12px;font-size:.68rem;letter-spacing:.16em;text-transform:uppercase;color:var(--gold-300)}
.note{font-size:.78rem;color:var(--muted);margin-top:16px}
td .tag{white-space:nowrap}

/* ===== PETA CAGAR BUDAYA (pola sama halaman Analisa Kerusakan) ===== */
.cb-map-shell{position:relative;margin-top:44px;border:1px solid var(--line);box-shadow:0 18px 50px var(--shadow);border-radius:18px;overflow:hidden}
#cbMap{height:560px;width:100%;background:#dfeee2;z-index:1}
@media(max-width:560px){#cbMap{height:440px}}
.legend{background:#fff;color:#223;padding:12px 15px;font-size:.78rem;line-height:2;box-shadow:0 2px 10px rgba(0,0,0,.25)}
.legend b{display:block;font-family:var(--display);font-weight:400;letter-spacing:.14em;text-transform:uppercase;font-size:.68rem;margin-bottom:4px;color:#8a6a1c}
.legend .dot{display:inline-block;width:12px;height:12px;border-radius:50%;margin-right:8px;vertical-align:-1px;border:1.5px solid #fff;box-shadow:0 0 0 1px rgba(0,0,0,.25)}
.leaflet-popup-content{font-family:var(--body);font-size:.85rem;line-height:1.6;color:#223;min-width:230px}
.leaflet-popup-content h6{font-family:var(--display);font-weight:400;font-size:1rem;letter-spacing:.04em;color:#8a6a1c;margin:0 0 6px}
.leaflet-popup-content .pp-row{display:flex;gap:8px}
.leaflet-popup-content .pp-row span:first-child{min-width:82px;color:#889;flex:0 0 auto}
.leaflet-popup-content .pp-status{display:inline-block;margin-top:8px;padding:2px 12px;font-size:.66rem;letter-spacing:.14em;text-transform:uppercase;border:1px solid}
.leaflet-popup-content .pp-act{display:flex;gap:8px;margin-top:12px}
.leaflet-popup-content .pp-btn{flex:1;text-align:center;padding:8px 10px;font-size:.72rem;font-weight:600;border:1px solid #c9a24b;border-radius:7px;color:#8a6a1c;text-decoration:none;white-space:nowrap}
.leaflet-popup-content .pp-btn:hover{background:#f5ecd6}
.leaflet-control-layers{border-radius:4px;box-shadow:0 2px 10px rgba(0,0,0,.28);color:#223}
.leaflet-control-layers-expanded{padding:10px 12px;min-width:172px;font-size:.76rem}
.leaflet-control-layers-list{line-height:1.35;padding:0;margin:0}
.leaflet-control-layers-base,.leaflet-control-layers-overlays{padding:0;margin:0}
.leaflet-control-layers label{margin:1px 0;font-weight:400;line-height:1.35;white-space:nowrap;display:flex;align-items:center}
.leaflet-control-layers label>span{display:flex;align-items:center}
.leaflet-control-layers-selector{accent-color:#A57E2C;margin:0 6px 0 0;width:13px;height:13px}
.leaflet-control-layers-separator{margin:7px 0;border-top-color:#e6e6e6}
html[data-theme="dark"] .leaflet-tile{filter:brightness(.82) contrast(1.06) saturate(.85)}
.cb-toolbar{display:flex;gap:10px;flex-wrap:wrap;margin-top:34px}
.cb-toolbar input,.cb-toolbar select{background:var(--input);border:1px solid var(--line);color:var(--text);padding:10px 14px;font-family:var(--body);font-size:.85rem}
.cb-toolbar input:focus,.cb-toolbar select:focus{outline:1px solid var(--gold-500);border-color:var(--gold-500)}
.cb-count{margin-top:18px;font-size:.72rem;letter-spacing:.16em;text-transform:uppercase;color:var(--muted)}
td a.map-link{color:var(--gold-300);text-decoration:underline}
td .go-map{color:var(--gold-300);cursor:pointer;text-decoration:underline;font-size:.82rem}
@media(max-width:980px){table.cb-table{display:block;overflow-x:auto;white-space:nowrap}}

.steps{margin-top:60px;display:grid;gap:0}
.step{display:grid;grid-template-columns:90px 1fr;gap:30px;padding:34px 0;border-bottom:1px solid var(--line);align-items:start}
.step:first-child{border-top:1px solid var(--line)}
.step-num{font-family:var(--display);font-size:2.4rem;color:var(--gold-300);line-height:1}
.step h3{font-family:var(--display);font-weight:400;font-size:1.25rem;color:var(--text);margin-bottom:8px;letter-spacing:.04em}
.step p{color:var(--muted);font-size:.93rem;max-width:70ch}

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

.reveal{opacity:0;transform:translateY(28px);transition:opacity .8s ease,transform .8s ease}
.reveal.in{opacity:1;transform:none}
@media (prefers-reduced-motion:reduce){.reveal{opacity:1;transform:none;transition:none}.hero-bg{animation:none;transform:none}html{scroll-behavior:auto}}

@media(max-width:980px){
  .cards{grid-template-columns:repeat(2,1fr)}
  .split,.split.rev{grid-template-columns:1fr;gap:44px}
  .foot-grid{grid-template-columns:1fr}
}
@media(max-width:560px){
  .cards{grid-template-columns:1fr}
  .photo-frame img{height:320px}
  section{padding:76px 0}
  .step{grid-template-columns:56px 1fr;gap:18px}
  .step-num{font-size:1.7rem}
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
    <?php $sesi_nav = info_sesi_navbar(); ?>
    <div class="auth-actions">
      <?php if ($sesi_nav['masuk']): ?>
        <a class="btn btn-ghost btn-sm" href="<?php echo base_url($sesi_nav['tujuan_dashboard']); ?>">Dashboard</a>
        <div class="user-menu">
          <button class="user-menu-btn" id="userMenuBtn" type="button" aria-expanded="false" aria-controls="userMenuPanel">
            <?php echo htmlspecialchars($sesi_nav['nama'], ENT_QUOTES, 'UTF-8'); ?>
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
      <?php else: ?>
        <a class="btn btn-ghost btn-sm" href="<?php echo base_url('login?from=admin'); ?>">Masuk</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<section style="padding-top:calc(84px + 40px)">
  <div class="wrap">
    <div class="page-breadcrumb">
      <a href="<?php echo base_url(); ?>">Beranda</a><span class="sep">/</span>Cagar Budaya
    </div>
    <div class="reveal">
      <p class="eyebrow">Dasar Perlindungan</p>
      <h2>Apa yang Dimaksud Cagar Budaya</h2>
      <p class="section-lead">Berdasarkan Undang-Undang Nomor 11 Tahun 2010 tentang Cagar Budaya, benda, bangunan, struktur, lokasi, atau satuan ruang geografis dapat ditetapkan sebagai cagar budaya apabila memenuhi kriteria usia sekurang-kurangnya 50 tahun, mewakili gaya sezaman, memiliki nilai penting bagi sejarah, ilmu pengetahuan, pendidikan, agama, dan/atau kebudayaan, serta memiliki nilai budaya untuk memperkuat kepribadian bangsa.</p>
    </div>
    <div class="cards reveal">
      <div class="card">
        <svg width="36" height="36" viewBox="0 0 40 40" aria-hidden="true"><rect x="8" y="16" width="24" height="18" fill="#C9A24B" stroke="#0C2236" stroke-width="1.6"/><path d="M4 16L20 5l16 11H4z" fill="#C9A24B" stroke="#0C2236" stroke-width="1.6" stroke-linejoin="round"/><rect x="17" y="24" width="6" height="10" fill="#0C2236"/></svg>
        <h3>Bangunan Cagar Budaya</h3>
        <p>Susunan binaan berupa dinding, atap, lantai, dan struktur penopang lain yang menyatu dengan tanah, seperti gedung, rumah, atau tempat ibadah bersejarah.</p>
      </div>
      <div class="card">
        <svg width="36" height="36" viewBox="0 0 40 40" aria-hidden="true"><path d="M6 34l6-24h16l6 24H6z" fill="#C9A24B" stroke="#0C2236" stroke-width="1.6" stroke-linejoin="round"/><path d="M14 20h12M12 27h16" stroke="#0C2236" stroke-width="1.3"/></svg>
        <h3>Struktur Cagar Budaya</h3>
        <p>Susunan binaan yang menyatu dengan lokasinya dan sebagian atau seluruhnya berada di atas atau di dalam tanah/air, seperti benteng dan jembatan lama.</p>
      </div>
      <div class="card">
        <svg width="36" height="36" viewBox="0 0 40 40" aria-hidden="true"><circle cx="20" cy="20" r="14" fill="none" stroke="#C9A24B" stroke-width="1.8"/><circle cx="20" cy="20" r="4" fill="#B4573B"/></svg>
        <h3>Situs Cagar Budaya</h3>
        <p>Lokasi yang berada di darat dan/atau di air yang mengandung objek diduga cagar budaya, termasuk lingkungan di sekitarnya.</p>
      </div>
      <div class="card">
        <svg width="36" height="36" viewBox="0 0 40 40" aria-hidden="true"><path d="M5 30l8-14 6 9 5-11 11 16H5z" fill="#C9A24B" stroke="#0C2236" stroke-width="1.6" stroke-linejoin="round"/></svg>
        <h3>Kawasan Cagar Budaya</h3>
        <p>Satuan ruang geografis yang memiliki dua situs cagar budaya atau lebih yang letaknya berdekatan dan/atau memperlihatkan ciri tata ruang yang khas.</p>
      </div>
    </div>
  </div>
</section>

<section class="alt">
  <div class="wrap split">
    <figure class="photo-frame reveal">
      <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Barracks,_Benteng_Pendem,_Cilacap_2015-03-21.jpg?width=1200" alt="Barak Benteng Pendem Cilacap" loading="lazy">
      <figcaption class="photo-cap">Benteng Pendem — salah satu peninggalan sejarah di Kabupaten Cilacap</figcaption>
    </figure>
    <div class="reveal">
      <p class="eyebrow">Kriteria Penetapan</p>
      <h2>Nilai yang Dipertimbangkan</h2>
      <div class="list">
        <div class="list-item"><span class="list-key">Usia</span><span class="list-val">Berusia 50 tahun atau lebih, atau mewakili gaya arsitektur khas suatu masa.</span></div>
        <div class="list-item"><span class="list-key">Kesejarahan</span><span class="list-val">Memiliki keterkaitan dengan peristiwa atau tokoh penting dalam sejarah wilayah.</span></div>
        <div class="list-item"><span class="list-key">Keilmuan</span><span class="list-val">Bermanfaat bagi pengembangan ilmu pengetahuan, teknologi, dan pendidikan.</span></div>
        <div class="list-item"><span class="list-key">Kebudayaan</span><span class="list-val">Memperkuat kepribadian bangsa dan menjadi bagian dari identitas kolektif daerah.</span></div>
      </div>
    </div>
  </div>
</section>

<?php
$daftar     = isset($daftar) ? $daftar : array();
$total      = isset($total) ? (int) $total : count($daftar);
$total_peta = isset($total_peta) ? (int) $total_peta : 0;
$KAT_WARNA  = array(
  'Benda'    => '#8E6FCE',
  'Bangunan' => '#3E7CB1',
  'Struktur' => '#C0392B',
  'Situs'    => '#2EA84F',
  'Kawasan'  => '#D9822B',
);
?>
<section>
  <div class="wrap">
    <div class="reveal">
      <p class="eyebrow">Sebaran &amp; Daftar</p>
      <h2>Cagar Budaya Kabupaten Cilacap</h2>
      <p class="section-lead">Peta dan daftar objek cagar budaya serta objek diduga cagar budaya (ODCB) di Kabupaten Cilacap. Setiap rencana renovasi, pembongkaran, atau alih fungsi pada objek berikut memerlukan kajian Tim Ahli Cagar Budaya (TACB) dan izin khusus sebelum PBG diterbitkan.</p>
    </div>

    <div class="cb-map-shell">
      <div id="cbMap" role="application" aria-label="Peta sebaran cagar budaya Kabupaten Cilacap"></div>
    </div>

    <form class="cb-toolbar reveal" onsubmit="return false">
      <input type="search" id="cbCari" placeholder="Cari nama / kecamatan / alamat…" style="flex:1 1 240px">
      <select id="cbKategori">
        <option value="">— Semua kategori —</option>
        <option>Benda</option><option>Bangunan</option><option>Struktur</option><option>Situs</option><option>Kawasan</option>
      </select>
      <select id="cbStatus">
        <option value="">— Semua status —</option>
        <option>Ditetapkan</option>
        <option>Terdaftar Register Nasional</option>
        <option>Dalam Kajian</option>
        <option>Diusulkan</option>
        <option>Objek Diduga Cagar Budaya</option>
      </select>
    </form>
    <p class="cb-count reveal"><b id="cbShown"><?php echo $total; ?></b> objek ditampilkan · <?php echo $total_peta; ?> bertitik di peta · total <?php echo $total; ?> tercatat</p>

    <table class="cb-table reveal" id="cbTable">
      <thead><tr><th>Objek</th><th>Kategori</th><th>Kecamatan / Kelurahan</th><th>Tahun</th><th>Status</th><th>Peta</th></tr></thead>
      <tbody>
        <?php if (empty($daftar)): ?>
          <tr><td colspan="6">Belum ada data cagar budaya.</td></tr>
        <?php else: foreach ($daftar as $r):
          $warna = isset($KAT_WARNA[$r['kategori']]) ? $KAT_WARNA[$r['kategori']] : '#8A94A6';
          $lokasi = trim(($r['kecamatan'] ?: '—') . ' / ' . ($r['kelurahan'] ?: '—'), ' /');
          $ada_titik = ($r['latitude'] !== NULL && $r['longitude'] !== NULL);
        ?>
          <tr data-kategori="<?php echo htmlspecialchars($r['kategori'], ENT_QUOTES, 'UTF-8'); ?>"
              data-status="<?php echo htmlspecialchars($r['status'], ENT_QUOTES, 'UTF-8'); ?>"
              data-cari="<?php echo htmlspecialchars(mb_strtolower($r['nama'].' '.$r['kecamatan'].' '.$r['kelurahan'].' '.$r['alamat'], 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>"
              data-id="<?php echo (int) $r['id']; ?>">
            <td>
              <?php echo htmlspecialchars($r['nama'], ENT_QUOTES, 'UTF-8'); ?>
              <?php if (!empty($r['no_sk'])): ?><br><span class="note" style="margin:0;font-size:.72rem"><?php echo htmlspecialchars($r['no_sk'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
            </td>
            <td><span class="tag" style="color:<?php echo $warna; ?>;border-color:<?php echo $warna; ?>"><?php echo htmlspecialchars($r['kategori'], ENT_QUOTES, 'UTF-8'); ?></span></td>
            <td style="font-size:.84rem"><?php echo htmlspecialchars($lokasi, ENT_QUOTES, 'UTF-8'); ?></td>
            <td style="font-size:.84rem"><?php echo htmlspecialchars($r['tahun'] ?: '—', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><span class="tag"><?php echo htmlspecialchars($r['status'], ENT_QUOTES, 'UTF-8'); ?></span></td>
            <td>
              <?php if ($ada_titik): ?>
                <span class="go-map" data-id="<?php echo (int) $r['id']; ?>">Lihat di peta &rarr;</span>
              <?php else: ?>
                <span style="color:var(--muted);font-size:.82rem">—</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
    <p class="note">Data dihimpun dari sumber publik: Registrasi Nasional Cagar Budaya (Kemdikbud), BPCB Jawa Tengah, Wikipedia, dan pemberitaan resmi Pemkab Cilacap. Sebagian koordinat masih perkiraan dan status sebagian objek masih dalam kajian. Data resmi dan termutakhir mengikuti penetapan Tim Ahli Cagar Budaya (TACB) dan SK Bupati Kabupaten Cilacap.</p>
  </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script src="<?php echo base_url('assets/js/Leaflet.VectorGrid.bundled.js'); ?>?v=<?php echo @filemtime(FCPATH.'assets/js/Leaflet.VectorGrid.bundled.js'); ?>"></script>
<script src="<?php echo base_url('gis-data.js'); ?>?v=<?php echo @filemtime(FCPATH.'gis-data.js'); ?>"></script>
<script>
(function(){
  var DATA = <?php echo json_encode(array_map(function($r){
    return array(
      'id'        => (int) $r['id'],
      'nama'      => $r['nama'],
      'kategori'  => $r['kategori'],
      'kecamatan' => $r['kecamatan'] ?: '',
      'kelurahan' => $r['kelurahan'] ?: '',
      'alamat'    => $r['alamat'] ?: '',
      'tahun'     => $r['tahun'] ?: '',
      'status'    => $r['status'],
      'deskripsi' => $r['deskripsi'] ?: '',
      'lat'       => $r['latitude']  !== null ? (float) $r['latitude']  : null,
      'lng'       => $r['longitude'] !== null ? (float) $r['longitude'] : null,
    );
  }, $daftar), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); ?>;

  var KAT_WARNA = <?php echo json_encode($KAT_WARNA); ?>;
  function warnaKat(k){ return KAT_WARNA[k] || "#8A94A6"; }
  function esc(s){ return String(s==null?"":s).replace(/[&<>\"]/g,function(c){return {"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;"}[c];}); }

  /* ---- Peta: lapis dasar (sama dgn Analisa Kerusakan) ---- */
  var _G="https://{s}.google.com/vt/lyrs=",_Gt="&x={x}&y={y}&z={z}",
      _Go={maxZoom:20,subdomains:["mt0","mt1","mt2","mt3"]},
      _at="&copy; <b>A.S - Kab. Cilacap</b>";
  var baseGmap    =L.tileLayer(_G+"m"+_Gt,{maxZoom:20,subdomains:_Go.subdomains,attribution:"&copy; Google Maps | "+_at}),
      baseOsm     =L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png",{maxZoom:19,attribution:"&copy; OpenStreetMap | "+_at}),
      baseGhybrid =L.tileLayer(_G+"y"+_Gt,_Go),
      baseGsat    =L.tileLayer(_G+"s"+_Gt,_Go),
      baseGterrain=L.tileLayer(_G+"p"+_Gt,_Go);

  var map=L.map("cbMap",{layers:[baseGmap],zoomControl:false,scrollWheelZoom:true}).setView([-7.47356,108.985062],10);
  L.control.zoom({position:"bottomright"}).addTo(map);
  L.control.scale({imperial:false,position:"bottomleft"}).addTo(map);
  map.createPane("paneRTRW");     map.getPane("paneRTRW").style.zIndex=350;
  map.createPane("paneCagar");    map.getPane("paneCagar").style.zIndex=400;

  function _has(v){ return v&&v.features&&v.features.length; }
  function _warnaW(w){ return ({"1":"#FF1D1D","2":"#000CFF","3":"#FFB557","4":"#00B418"})[String(w)] || "#3388ff"; }
  function _polyOnly(f){
    var t=f.geometry&&f.geometry.type;
    return t==="Polygon"||t==="MultiPolygon"||(t==="GeometryCollection"&&f.geometry.geometries.some(function(g){return g.type==="Polygon"||g.type==="MultiPolygon";}));
  }

  var layerKabupaten=_has(window.gisKabupaten)?L.geoJSON(gisKabupaten,{
    onEachFeature:function(f,l){
      if(!(f.properties&&f.properties.idKabupaten))return;
      l.setStyle({color:"#1B2631",weight:1,dashArray:"5, 3"});
      l.bindTooltip("<b><i> Batas Adm. Kabupaten "+f.properties.namaKabupaten+"</i></b>");
    }
  }):L.layerGroup();
  var layerKecamatan=_has(window.gisKecamatan)?L.geoJSON(gisKecamatan,{
    filter:_polyOnly,
    style:function(f){return{color:_warnaW(f.properties.warna),weight:1,opacity:.7,fillOpacity:.08,dashArray:"5,3"};},
    onEachFeature:function(f,l){ if(f.properties&&f.properties.idKecamatan)l.bindPopup("Kec. "+f.properties.namaKecamatan); }
  }):L.layerGroup();
  var layerDesa=_has(window.gisDesa)?L.geoJSON(gisDesa,{
    filter:_polyOnly,
    style:function(f){return{color:_warnaW(f.properties.warna),weight:1,opacity:.6,fillOpacity:.04,dashArray:"5,3"};},
    onEachFeature:function(f,l){ if(f.properties&&f.properties.idDesa)l.bindPopup("Desa. "+f.properties.namaDesa); }
  }):L.layerGroup();

  var layerRTRW=L.layerGroup();
  if(L.vectorGrid&&L.vectorGrid.protobuf){
    var _rk={1:"#ffffff",2:"#9a99ff",3:"#e5d9ff",4:"#b3e6e7",5:"#9af2cc",6:"#cb9998",7:"#fee6fe",8:"#73b2ff",9:"#72dffe",10:"#ccff80",11:"#99ff99",12:"#ffd37f",13:"#ffaa00",14:"#e74cff",15:"#e6e6b2",16:"#b8ffc7",17:"#ffffbe",18:"#c02f1a",19:"#cdffcc",20:"#c3ffcc",21:"#e699ff"};
    L.vectorGrid.protobuf("https://api.maptiler.com/tiles/019714d6-e798-7829-a293-06f529070ecd/{z}/{x}/{y}.pbf?key=pP7Y0XrqMfIZ58PvGQHX",{
      pane:"paneRTRW",
      bounds:L.latLngBounds([[-7.784859999999999,108.55585],[-7.138700000000017,109.39394000000001]]),
      minZoom:0,maxZoom:20,minNativeZoom:10,maxNativeZoom:17,
      vectorTileLayerStyles:{ layerrtrw:function(p){ var c=(p&&_rk[p.KODE])||"#CCCCCC"; return {fill:true,fillColor:c,fillOpacity:.32,color:c,weight:1,opacity:.75}; } },
      interactive:true,getFeatureId:function(f){return f.properties.ID;}
    }).on("click",function(e){
      if(e.originalEvent)L.DomEvent.stopPropagation(e.originalEvent);
      var p=e.layer&&e.layer.properties;
      if(p&&p.NAMOBJ)L.popup().setLatLng(e.latlng).setContent("<b>Nama:</b> "+p.NAMOBJ+"<br><b>Kec:</b> "+(p.KEC||"-")).openOn(map);
    }).addTo(layerRTRW);
  }

  layerKabupaten.addTo(map);
  layerKecamatan.addTo(map);

  /* ---- Marker cagar budaya ---- */
  var group=L.layerGroup().addTo(map);
  var markerById={};
  function popupHTML(d){
    var w=warnaKat(d.kategori);
    var dir = (d.lat!=null&&d.lng!=null) ? "https://www.google.com/maps/dir/?api=1&destination="+d.lat+","+d.lng : null;
    var h="<h6>"+esc(d.nama)+"</h6>"+
      "<div class='pp-row'><span>Kategori</span><span>"+esc(d.kategori)+"</span></div>"+
      "<div class='pp-row'><span>Kecamatan</span><span>"+esc(d.kecamatan||"-")+"</span></div>"+
      "<div class='pp-row'><span>Kelurahan</span><span>"+esc(d.kelurahan||"-")+"</span></div>"+
      (d.tahun?"<div class='pp-row'><span>Tahun</span><span>"+esc(d.tahun)+"</span></div>":"")+
      "<span class='pp-status' style='color:"+w+";border-color:"+w+"'>"+esc(d.status)+"</span>";
    if(d.deskripsi) h+="<p style='margin:10px 0 0;font-size:.8rem;color:#556'>"+esc(d.deskripsi)+"</p>";
    if(dir) h+="<div class='pp-act'><a class='pp-btn' target='_blank' rel='noopener' href='"+dir+"'>Menuju Lokasi</a></div>";
    return h;
  }
  function makeMarker(d){
    var m=L.circleMarker([d.lat,d.lng],{pane:"paneCagar",radius:7,color:"#fff",weight:1.6,fillColor:warnaKat(d.kategori),fillOpacity:.95});
    m.bindPopup(popupHTML(d));
    markerById[d.id]=m;
    return m;
  }

  /* ---- Legenda ---- */
  var legend=L.control({position:"bottomright"});
  legend.onAdd=function(){
    var el=L.DomUtil.create("div","legend");
    var html="<b>Kategori Cagar Budaya</b>";
    Object.keys(KAT_WARNA).forEach(function(k){
      html+="<span class='dot' style='background:"+KAT_WARNA[k]+"'></span>"+k+"<br>";
    });
    el.innerHTML=html;
    return el;
  };
  legend.addTo(map);

  /* ---- Kontrol lapisan (pojok kiri-bawah, kuncup) ---- */
  L.control.layers(
    {"Google Maps":baseGmap,"OpenStreetMap":baseOsm,"Google Hybrid":baseGhybrid,"Google Satelite":baseGsat,"Google Terrain":baseGterrain},
    {"Cagar Budaya":group,"Adm. Kabupaten":layerKabupaten,"Adm. Kecamatan":layerKecamatan,"Adm. Desa":layerDesa,"RTRW":layerRTRW},
    {position:"bottomleft",collapsed:true}
  ).addTo(map);

  /* ---- Filter tabel + peta ---- */
  var elCari=document.getElementById("cbCari"),
      elKat=document.getElementById("cbKategori"),
      elStat=document.getElementById("cbStatus"),
      elShown=document.getElementById("cbShown"),
      rows=[].slice.call(document.querySelectorAll("#cbTable tbody tr[data-id]"));

  function lolos(d){
    var q=elCari.value.trim().toLowerCase(), k=elKat.value, s=elStat.value;
    if(k && d.kategori!==k) return false;
    if(s && d.status!==s) return false;
    if(q && (d.nama+" "+d.kecamatan+" "+d.kelurahan+" "+d.alamat).toLowerCase().indexOf(q)<0) return false;
    return true;
  }
  function terapkan(){
    var tampil=0, bounds=[];
    group.clearLayers();
    DATA.forEach(function(d){
      var ok=lolos(d);
      var tr=document.querySelector('#cbTable tbody tr[data-id="'+d.id+'"]');
      if(tr) tr.style.display = ok ? "" : "none";
      if(ok){
        tampil++;
        if(d.lat!=null && d.lng!=null){ group.addLayer(makeMarker(d)); bounds.push([d.lat,d.lng]); }
      }
    });
    elShown.textContent=tampil;
    if(bounds.length) map.fitBounds(bounds,{padding:[45,45],maxZoom:14});
  }
  elCari.addEventListener("input",terapkan);
  elKat.addEventListener("change",terapkan);
  elStat.addEventListener("change",terapkan);

  document.querySelectorAll("#cbTable .go-map").forEach(function(el){
    el.addEventListener("click",function(){
      var m=markerById[el.dataset.id];
      if(!m){ terapkan(); m=markerById[el.dataset.id]; }
      if(!m) return;
      document.getElementById("cbMap").scrollIntoView({behavior:"smooth",block:"center"});
      map.setView(m.getLatLng(),16);
      setTimeout(function(){ m.openPopup(); },400);
    });
  });

  function bingkai(){
    map.invalidateSize();
    var b=DATA.filter(function(d){return d.lat!=null&&d.lng!=null;}).map(function(d){return [d.lat,d.lng];});
    if(b.length) map.fitBounds(b,{padding:[45,45],maxZoom:14});
  }
  terapkan();
  map.whenReady(bingkai);
  setTimeout(bingkai,600);
})();
</script>

<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <div class="brand" style="margin-bottom:18px">
          <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Seal_of_Cilacap_Regency.svg?width=120" alt="" style="height:56px">
          <span>
            <span class="brand-name" style="font-size:1.05rem">SIP GATUTKACA</span><br>
            <span class="brand-sub">Sistem Informasi Penataan Ruang</span>
          </span>
        </div>
        <p>Dinas Pekerjaan Umum dan Penataan Ruang Kabupaten Cilacap. Melayani dengan semangat <em>“otot kawat, balung wesi”</em> — kokoh dalam aturan, luwes dalam pelayanan.</p>
      </div>
      <div>
        <h4>Layanan</h4>
        <ul>
          <li><a href="<?php echo base_url('regulasi'); ?>">Regulasi</a></li>
          <li><a href="<?php echo base_url('analisa-kerusakan'); ?>">Analisa Kerusakan</a></li>
          <li><a href="<?php echo base_url('pbg'); ?>">PBG</a></li>
          <li><a href="<?php echo base_url('slf'); ?>">SLF</a></li>
          <li><a href="<?php echo base_url('cagar-budaya'); ?>">Cagar Budaya</a></li>
          <li><a href="<?php echo base_url('saran-masukan'); ?>">Saran dan Masukan</a></li>
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

  function applyTheme(theme,rewrite){
    document.documentElement.setAttribute('data-theme',theme);
    document.querySelectorAll('.swatch').forEach(function(s){
      s.classList.toggle('sel',s.dataset.theme===theme);
    });
    document.querySelectorAll('a[href]').forEach(function(a){
      var h=a.getAttribute('href');
      if(!h||/^(https?:|mailto:|#)/.test(h))return;
      var hash='';var hi=h.indexOf('#');
      if(hi>=0){hash=h.slice(hi);h=h.slice(0,hi);}
      var qi=h.indexOf('?');var base=qi>=0?h.slice(0,qi):h;
      var qs=new URLSearchParams(qi>=0?h.slice(qi+1):'');
      qs.set('theme',theme);
      a.setAttribute('href',base+'?'+qs.toString()+hash);
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
  s.addEventListener('click',function(){ window.__applyTheme(s.dataset.theme,true); });
});
document.addEventListener('click',function(e){
  if(!panel.contains(e.target)&&e.target!==fab&&!fab.contains(e.target))panel.classList.remove('open');
});
var userBtn=document.getElementById('userMenuBtn'),userPanel=document.getElementById('userMenuPanel');
if(userBtn){
userBtn.addEventListener('click',function(){
  var open=userPanel.classList.toggle('open');
  userBtn.setAttribute('aria-expanded',open);
});
document.addEventListener('click',function(e){
  if(!userPanel.contains(e.target)&&e.target!==userBtn&&!userBtn.contains(e.target))userPanel.classList.remove('open');
});
}

var bar=document.getElementById('topbar');
addEventListener('scroll',function(){bar.classList.toggle('scrolled',scrollY>40)},{passive:true});

var io=new IntersectionObserver(function(es){es.forEach(function(e){
  if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}
})},{threshold:.12});
document.querySelectorAll('.reveal').forEach(function(el){io.observe(el)});
</script>
</body>
</html>
