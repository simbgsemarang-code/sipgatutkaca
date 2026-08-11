<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Spasial — SIP Gatutkaca · Kabupaten Cilacap</title>
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
nav ul{display:flex;align-items:center;gap:30px;list-style:none}
nav a{font-size:.78rem;letter-spacing:.2em;text-transform:uppercase;color:var(--text);position:relative;padding:6px 0;white-space:nowrap}
nav a::after{content:"";position:absolute;left:0;bottom:0;height:1px;width:0;background:var(--gold-500);transition:width .35s}
nav a:hover::after,nav a:focus-visible::after,nav a.active::after{width:100%}
nav a.active{color:var(--gold-300)}
nav a:focus-visible{outline:1px solid var(--gold-500);outline-offset:4px}
.nav-cta{border:1px solid var(--gold-500);color:var(--gold-300)!important;padding:10px 24px!important;letter-spacing:.24em;font-weight:600}
.nav-cta:hover{background:var(--gold-500);color:var(--bg)!important}
.nav-cta::after{display:none}
.burger{display:none;background:none;border:1px solid var(--line);color:var(--gold-300);font-size:1rem;padding:8px 14px;cursor:pointer}

/* ===== HERO / BANNER ===== */
.hero{position:relative;display:flex;align-items:center;overflow:hidden}
.hero.full{min-height:100vh}
.hero.page{min-height:56vh}
.hero-bg{position:absolute;inset:0;background-position:center 60%;background-size:cover;transform:scale(1.06);animation:slowzoom 26s ease-out forwards}
@keyframes slowzoom{to{transform:scale(1)}}
.hero::after{content:"";position:absolute;inset:0;background:
  linear-gradient(105deg,var(--hero-1) 0%,var(--hero-2) 42%,var(--hero-3) 78%),
  linear-gradient(0deg,var(--bg) 0%,transparent 30%)}
.hero .wrap{position:relative;z-index:2;padding-top:120px;padding-bottom:80px;color:#F8F4EA}
.hero-eyebrow{display:inline-flex;align-items:center;gap:12px;font-size:.72rem;letter-spacing:.4em;text-transform:uppercase;color:#E4C87B;margin-bottom:24px}
.hero-eyebrow::before{content:"";width:44px;height:1px;background:#C9A24B}
h1{font-family:var(--display);font-weight:400;font-size:clamp(2.4rem,5.6vw,4.4rem);line-height:1.08;max-width:16ch;color:#F8F4EA}
h1 em{font-style:normal;color:#E4C87B}
.hero-lead{max-width:54ch;margin:26px 0 38px;color:#CBD6DF;font-size:1rem}
.hero-motto{margin-top:56px;font-family:var(--display);letter-spacing:.3em;font-size:.8rem;color:rgba(228,200,123,.8);text-transform:uppercase}

.btn{display:inline-block;padding:15px 34px;font-size:.78rem;letter-spacing:.26em;text-transform:uppercase;transition:.3s;cursor:pointer;border:none;font-family:var(--body)}
.btn-gold{background:linear-gradient(135deg,#C9A24B,#E4C87B);color:#081826;font-weight:600}
.btn-gold:hover{filter:brightness(1.08);transform:translateY(-2px)}
.btn-ghost{border:1px solid rgba(248,244,234,.45);color:#F8F4EA;background:transparent}
.btn-ghost:hover{border-color:#C9A24B;color:#E4C87B}
.hero-actions{display:flex;gap:18px;flex-wrap:wrap}

/* ===== SECTION ===== */
section{padding:100px 0}
section.alt{background:var(--bg-alt)}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.8rem,3.4vw,2.6rem);line-height:1.2;max-width:24ch}
.section-lead{color:var(--muted);max-width:66ch;margin-top:18px}

/* ===== CARDS ===== */
.cards{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--line);margin-top:60px;border:1px solid var(--line)}
.card{background:var(--surface);padding:42px 28px 38px;transition:background .35s;display:block}
.card:hover{background:var(--surface-hi)}
.card svg{margin-bottom:22px}
.card h3{font-family:var(--display);font-weight:400;font-size:1.24rem;letter-spacing:.06em;margin-bottom:12px;color:var(--gold-300)}
.card p{font-size:.9rem;color:var(--muted)}
.card .go{display:inline-flex;align-items:center;gap:8px;margin-top:20px;font-size:.72rem;letter-spacing:.24em;text-transform:uppercase;color:var(--gold-300)}
.card .go span{transition:transform .3s}
.card:hover .go span{transform:translateX(6px)}

/* ===== SPLIT ===== */
.split{display:grid;grid-template-columns:1.05fr .95fr;gap:70px;align-items:center}
.split.rev{grid-template-columns:.95fr 1.05fr}
.photo-frame{position:relative}
.photo-frame img{width:100%;height:500px;object-fit:cover;filter:saturate(.92) contrast(1.04)}
.photo-frame::before{content:"";position:absolute;inset:18px -18px -18px 18px;border:1px solid var(--line);z-index:0;pointer-events:none}
.photo-frame img{position:relative;z-index:1}
.photo-cap{margin-top:14px;font-size:.7rem;letter-spacing:.18em;text-transform:uppercase;color:var(--muted)}

.list{margin-top:30px}
.list-item{display:flex;gap:20px;padding:20px 0;border-bottom:1px solid var(--line)}
.list-item:first-child{border-top:1px solid var(--line)}
.list-key{font-family:var(--display);color:var(--gold-300);letter-spacing:.06em;min-width:160px;font-size:.95rem;flex:0 0 auto}
.list-val{color:var(--muted);font-size:.92rem}

/* ===== STATISTIK ===== */
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:40px;margin-top:60px;text-align:center}
.stat b{display:block;font-family:var(--display);font-weight:400;font-size:2.5rem;color:var(--gold-300)}
.stat span{font-size:.7rem;letter-spacing:.24em;text-transform:uppercase;color:var(--muted)}

/* ===== GALERI ===== */
.gallery{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;margin-top:52px}
.g-item{position:relative;overflow:hidden;height:360px}
.g-item img{width:100%;height:100%;object-fit:cover;transition:transform .8s ease;filter:saturate(.9)}
.g-item:hover img{transform:scale(1.06)}
.g-item figcaption{position:absolute;inset:auto 0 0 0;padding:40px 22px 18px;background:linear-gradient(0deg,rgba(8,24,38,.92),transparent);font-family:var(--display);letter-spacing:.12em;color:#F3E3B8}

/* ===== TABEL & FORM ===== */
table{width:100%;border-collapse:collapse;margin-top:44px;font-size:.9rem}
th{font-family:var(--display);font-weight:400;letter-spacing:.12em;text-transform:uppercase;font-size:.74rem;color:var(--gold-300);text-align:left;padding:16px 14px;border-bottom:1px solid var(--gold-500)}
td{padding:16px 14px;border-bottom:1px solid var(--line);color:var(--muted);vertical-align:top}
td:first-child{color:var(--text);font-weight:500}
.tag{display:inline-block;border:1px solid var(--line);padding:3px 12px;font-size:.68rem;letter-spacing:.16em;text-transform:uppercase;color:var(--gold-300)}
.dl{color:var(--gold-300);letter-spacing:.12em;font-size:.78rem;text-transform:uppercase;white-space:nowrap}
.dl:hover{text-decoration:underline}

.form-card{background:var(--surface);border:1px solid var(--line);padding:46px;max-width:520px}
.form-card.center{margin:0 auto}
.field{margin-bottom:22px}
label{display:block;font-size:.72rem;letter-spacing:.24em;text-transform:uppercase;color:var(--muted);margin-bottom:9px}
input,select,textarea{width:100%;background:var(--input);border:1px solid var(--line);color:var(--text);padding:13px 15px;font-family:var(--body);font-size:.92rem}
input:focus,select:focus,textarea:focus{outline:1px solid var(--gold-500);border-color:var(--gold-500)}
.note{font-size:.78rem;color:var(--muted);margin-top:16px}

/* ===== TIMELINE TATA CARA ===== */
.steps{margin-top:60px;display:grid;gap:0}
.step{display:grid;grid-template-columns:90px 1fr;gap:30px;padding:34px 0;border-bottom:1px solid var(--line);align-items:start}
.step:first-child{border-top:1px solid var(--line)}
.step-num{font-family:var(--display);font-size:2.4rem;color:var(--gold-300);line-height:1}
.step h3{font-family:var(--display);font-weight:400;font-size:1.25rem;color:var(--text);margin-bottom:8px;letter-spacing:.04em}
.step p{color:var(--muted);font-size:.93rem;max-width:70ch}
.step .tag{margin-top:12px}

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
  .hero-bg{animation:none;transform:none}
  html{scroll-behavior:auto}
}

/* ===== RESPONSIF ===== */
@media(max-width:1060px){nav ul{gap:20px}}
@media(max-width:980px){
  .cards{grid-template-columns:repeat(2,1fr)}
  .split,.split.rev{grid-template-columns:1fr;gap:44px}
  .stats{grid-template-columns:repeat(2,1fr)}
  .gallery{grid-template-columns:1fr}
  .foot-grid{grid-template-columns:1fr}
  nav ul{position:fixed;inset:84px 0 auto 0;background:var(--head-bg);backdrop-filter:blur(12px);flex-direction:column;gap:0;padding:10px 28px 26px;display:none;border-bottom:1px solid var(--line);align-items:flex-start}
  nav ul.open{display:flex}
  nav li{width:100%}
  nav a{display:block;padding:15px 0}
  .burger{display:block}
}
@media(max-width:560px){
  .cards{grid-template-columns:1fr}
  .photo-frame img{height:340px}
  section{padding:76px 0}
  .step{grid-template-columns:56px 1fr;gap:18px}
  .step-num{font-size:1.7rem}
}
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
.btn-sm{padding:11px 26px;font-size:.72rem;letter-spacing:.2em}
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
          <a class="btn btn-ghost btn-sm" href="<?php echo base_url('konsultasi'); ?>">Masuk</a>
          <a class="btn btn-gold btn-sm" href="<?php echo base_url('daftar'); ?>">Daftar</a>
          <?php endif; ?>
        </div>
  </div>
</header>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-locatecontrol/0.85.1/L.Control.Locate.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/gokertanrisever/leaflet-ruler@master/src/leaflet-ruler.css">
<style>
/* ===== Panel filter peta ===== */
.map-filter{display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:20px;align-items:end;margin-bottom:22px;width:94vw;margin-left:calc(50% - 47vw);margin-right:calc(50% - 47vw)}
.map-filter .field{margin:0}
.map-filter label{color:var(--gold-300)}
#btnReset{padding:13px 30px}
.map-shell{position:relative;width:94vw;margin-left:calc(50% - 47vw);margin-right:calc(50% - 47vw);border:1px solid var(--line);box-shadow:0 18px 50px var(--shadow);border-radius:22px;overflow:hidden}
#map{height:620px;width:100%;background:#dfeee2;z-index:1}
/* Kotak cari alamat/koordinat mengambang di atas peta */
.map-geo{position:absolute;z-index:500;top:14px;left:50%;transform:translateX(-50%);display:flex;background:#fff;border:1px solid rgba(0,0,0,.15);box-shadow:0 4px 14px rgba(0,0,0,.2);width:min(420px,80%)}
.map-geo input{border:none;background:#fff;color:#223;padding:11px 14px;flex:1;font-size:.88rem}
.map-geo input:focus{outline:2px solid #C9A24B;outline-offset:-2px;border:none}
.map-geo button{border:none;background:#fff;color:#667;padding:0 14px;cursor:pointer;font-size:1rem}
.map-geo button:hover{color:#C9A24B}
/* Legenda & kontrol */
.legend{background:#fff;color:#223;padding:12px 16px;font-size:.78rem;line-height:2;box-shadow:0 2px 10px rgba(0,0,0,.25)}
.legend b{display:block;font-family:var(--display);font-weight:400;letter-spacing:.14em;text-transform:uppercase;font-size:.68rem;margin-bottom:4px;color:#8a6a1c}
.layerctl{background:#fff;color:#223;padding:14px 16px;font-size:.8rem;box-shadow:0 2px 10px rgba(0,0,0,.25);min-width:190px;border-radius:2px}
.layerctl h6{margin:0 0 8px;font-family:var(--display);font-weight:400;letter-spacing:.14em;text-transform:uppercase;font-size:.7rem;color:#8a6a1c;border-bottom:1px solid #eee;padding-bottom:6px}
.layerctl h6.sep{margin-top:12px}
.layerctl .row{display:flex;align-items:center;gap:8px;padding:4px 0;cursor:pointer;color:#223}
.layerctl .row input{accent-color:#A57E2C;width:14px;height:14px;cursor:pointer;margin:0}
.dot{display:inline-block;width:12px;height:12px;border-radius:50%;margin-right:8px;vertical-align:-1px;border:1.5px solid #fff;box-shadow:0 0 0 1px rgba(0,0,0,.25)}
.dot-g{background:#2EA84F}.dot-y{background:#F2C230}
.map-count{margin-top:14px;font-size:.78rem;letter-spacing:.16em;text-transform:uppercase;color:var(--muted)}
.map-count b{color:var(--gold-300);font-family:var(--display);font-weight:400}
/* Popup */
.leaflet-popup-content{font-family:var(--body);font-size:.85rem;line-height:1.6;color:#223}
.leaflet-popup-content h6{font-family:var(--display);font-weight:400;font-size:1rem;letter-spacing:.05em;color:#8a6a1c;margin:0 0 6px}
.pp-row{display:flex;gap:8px}.pp-row span:first-child{min-width:86px;color:#889}
.pp-status{display:inline-block;margin-top:8px;padding:2px 12px;font-size:.66rem;letter-spacing:.18em;text-transform:uppercase;border:1px solid}
.pp-g{color:#1d7a38;border-color:#2EA84F}.pp-y{color:#9c7a10;border-color:#F2C230}
/* Tile sedikit diredupkan pada tema gelap */
html[data-theme="dark"] .leaflet-tile{filter:brightness(.82) contrast(1.06) saturate(.85)}
@media(max-width:980px){.map-filter{grid-template-columns:1fr 1fr}#btnReset{width:100%}}
@media(max-width:560px){.map-filter{grid-template-columns:1fr}#map{height:480px}}
</style>

<section style="padding-top:calc(84px + 100px)">
  <div class="wrap">
    <div class="reveal" style="margin-bottom:40px;text-align:center">
      <p class="eyebrow">Peta Interaktif</p>
      <h2 style="margin:0 auto;max-width:none">Sebaran Bangunan Kabupaten Cilacap</h2>
    </div>

    <div class="map-filter reveal" style="margin-top:48px">
      <div class="field">
        <label for="f-cari">Cari Bangunan (Nama / Alamat / OPD)</label>
        <input id="f-cari" type="text" placeholder="Ketik nama, OPD, atau alamat" autocomplete="off">
      </div>
      <div class="field">
        <label for="f-kec">Kecamatan</label>
        <select id="f-kec"><option value="">— Semua —</option></select>
      </div>
      <div class="field">
        <label for="f-kel">Kelurahan / Desa</label>
        <select id="f-kel"><option value="">— Semua —</option></select>
      </div>
      <button class="btn btn-gold" id="btnReset" type="button">Reset</button>
    </div>

    <div class="map-shell reveal">
      <div class="map-geo">
        <input id="geoInput" type="text" placeholder="Cari Alamat / Koordinat (mis. -7.7267, 109.0154)" autocomplete="off">
        <button id="geoClear" title="Bersihkan" aria-label="Bersihkan pencarian">&times;</button>
      </div>
      <div id="map" role="application" aria-label="Peta sebaran bangunan Kabupaten Cilacap"></div>
    </div>
    <p class="map-count reveal">Menampilkan <b id="countShown">0</b> dari <b id="countAll">0</b> bangunan</p>
  </div>
</section>

<section class="alt">
  <div class="wrap">
    <div class="reveal">
      <p class="eyebrow">Kemampuan Peta</p>
      <h2>Apa yang Dapat Anda Lakukan</h2>
    </div>
    <div class="list reveal" style="max-width:820px">
      <div class="list-item"><span class="list-key">Saring &amp; Cari</span><span class="list-val">Gabungkan pencarian teks dengan saringan kecamatan dan kelurahan; peta menyesuaikan seketika.</span></div>
      <div class="list-item"><span class="list-key">Lompat Koordinat</span><span class="list-val">Tempel koordinat lintang-bujur pada kotak pencarian peta untuk menuju titik mana pun.</span></div>
      <div class="list-item"><span class="list-key">Ganti Lapisan</span><span class="list-val">Beralih antara peta jalan dan citra satelit melalui kontrol lapisan di pojok kanan atas peta.</span></div>
      <div class="list-item"><span class="list-key">Rincian Titik</span><span class="list-val">Klik penanda untuk melihat nama bangunan, OPD pengelola, fungsi, dan alamatnya.</span></div>
    </div>
  </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-locatecontrol/0.85.1/L.Control.Locate.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/gokertanrisever/leaflet-ruler@master/src/leaflet-ruler.js"></script>
<script src="gis-data.js"></script>
<script>
(function(){
  /* ============ KATEGORI FUNGSI BANGUNAN & WARNA ============ */
  var FUNGSI_WARNA={
    "Perkantoran":"#3E7CB1",
    "Pendidikan":"#2EA84F",
    "Sarana Pendukung":"#C9A24B",
    "Hunian":"#8E6FCE",
    "Keagamaan":"#D9822B",
    "Kesehatan":"#E0526B",
    "Sosial Budaya":"#4FB0C6",
    "Usaha":"#B5B53C"
  };
  var WARNA_LAIN="#8A94A6";
  function warnaFungsi(f){
    if(!f)return WARNA_LAIN;
    for(var k in FUNGSI_WARNA){if(f.indexOf(k)>=0)return FUNGSI_WARNA[k];}
    return WARNA_LAIN;
  }

  /* ============ DATA BANGUNAN (dari gisBangunan) ============ */
  var DATA=(gisBangunan.features||[]).map(function(f,i){
    var p=f.properties||{},c=(f.geometry&&f.geometry.coordinates)||[null,null];
    return{
      id:i+1,
      nama:p.namaBangunan||"(Tanpa nama)",
      opd:p.opd||p.institusi||p.unit||"-",
      alamat:p.alamat||"-",
      kec:p.kecamatan||"-",
      kel:p.kelurahan||"-",
      fungsi:p.fungsi||"-",
      lantai:p.jumlahLantai,
      lat:c[1],lng:c[0]
    };
  }).filter(function(d){return typeof d.lat==="number"&&typeof d.lng==="number";});

  /* ============ PETA ============ */
  var osm=L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png",{maxZoom:19,attribution:"&copy; OpenStreetMap"});
  var esri=L.tileLayer("https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",{maxZoom:19,attribution:"Tiles &copy; Esri"});
  var map=L.map("map",{layers:[osm],zoomControl:true,scrollWheelZoom:true}).setView([-7.53,108.99],10);
  L.control.scale({imperial:false,position:"bottomleft"}).addTo(map);

  /* Kontrol ukur jarak (polyline) — tampil di bawah tombol zoom out */
  if(L.control.ruler)L.control.ruler({position:"topleft"}).addTo(map);

  /* Batas Kabupaten (gisKabupaten) */
  var kabLayer=L.geoJSON(gisKabupaten,{
    style:{color:"#C9A24B",weight:2.2,dashArray:"7 6",fillOpacity:0}
  }).bindTooltip("Batas Kabupaten Cilacap",{sticky:true});

  /* Batas Kecamatan (gisKecamatan) — tiap kecamatan diberi warna berbeda */
  var PALET_KEC=["#4E9F3D","#3E7CB1","#D9822B","#8E6FCE","#E0526B","#4FB0C6","#B5B53C","#C9A24B",
    "#5FBF8F","#C97CC9","#7C93C9","#E0A15A","#6FC2A0","#C96F6F","#8FBF5F","#B58AE0",
    "#5FA8D9","#D9C15F","#9C6FE0","#5FD9B0","#D95FA1","#7FD95F","#D98F5F","#5F8FD9"];
  var kecColorMap={};
  var kecLayer=L.geoJSON(gisKecamatan,{
    style:function(f){
      var nm=(f.properties&&f.properties.namaKecamatan)||"?";
      if(!(nm in kecColorMap))kecColorMap[nm]=PALET_KEC[Object.keys(kecColorMap).length%PALET_KEC.length];
      var c=kecColorMap[nm];
      return{color:c,weight:1.4,opacity:.7,fillColor:c,fillOpacity:.3};
    },
    onEachFeature:function(f,l){
      var nm=f.properties&&f.properties.namaKecamatan;
      if(nm)l.bindTooltip(nm,{sticky:true});
    }
  });

  /* Batas Desa/Kelurahan (gisDesa) */
  var desaLayer=L.geoJSON(gisDesa,{
    style:{color:"#8FD3E8",weight:.8,opacity:.45,fillColor:"#8FD3E8",fillOpacity:.02},
    onEachFeature:function(f,l){
      var nm=f.properties&&f.properties.namaDesa;
      if(nm)l.bindTooltip(nm,{sticky:true});
    }
  });

  /* Jaringan Jalan (gisJalan) */
  var jalanLayer=L.geoJSON(gisJalan,{
    style:{color:"#E8B84B",weight:1.3,opacity:.55},
    onEachFeature:function(f,l){
      var nm=f.properties&&f.properties.namaJalan;
      if(nm)l.bindTooltip(nm,{sticky:true});
    }
  }).addTo(map);

  /* Layer aktif secara default: kabupaten & kecamatan; desa dimatikan (data padat) */
  kabLayer.addTo(map);
  kecLayer.addTo(map);

  /* Panel kontrol layer (kartu custom) */
  var layerCtl=L.control({position:"topright"});
  layerCtl.onAdd=function(){
    var d=L.DomUtil.create("div","layerctl");
    d.innerHTML=
      "<h6>Peta</h6>"+
      "<label class='row'><input type='checkbox' id='lc-kab' checked> Batas Kabupaten</label>"+
      "<label class='row'><input type='checkbox' id='lc-kec' checked> Batas Kecamatan</label>"+
      "<label class='row'><input type='checkbox' id='lc-desa'> Batas Desa/Kelurahan</label>"+
      "<label class='row'><input type='checkbox' id='lc-jalan' checked> Jaringan Jalan</label>"+
      "<h6 class='sep'>Jenis Tampilan</h6>"+
      "<label class='row'><input type='radio' name='lc-base' id='lc-osm' checked> Peta Jalan</label>"+
      "<label class='row'><input type='radio' name='lc-base' id='lc-esri'> Citra Satelit</label>";
    L.DomEvent.disableClickPropagation(d);
    return d;
  };
  layerCtl.addTo(map);

  /* Kontrol lokasi Anda — tampil di bawah panel layer */
  if(L.control.locate)L.control.locate({
    position:"topright",
    strings:{title:"Tampilkan lokasi Anda"},
    locateOptions:{enableHighAccuracy:true}
  }).addTo(map);

  function toggleLayer(id,layer){
    document.getElementById(id).addEventListener("change",function(e){
      if(e.target.checked)layer.addTo(map);else map.removeLayer(layer);
    });
  }
  toggleLayer("lc-kab",kabLayer);
  toggleLayer("lc-kec",kecLayer);
  toggleLayer("lc-desa",desaLayer);
  toggleLayer("lc-jalan",jalanLayer);
  document.getElementById("lc-osm").addEventListener("change",function(){if(this.checked){map.removeLayer(esri);map.addLayer(osm);}});
  document.getElementById("lc-esri").addEventListener("change",function(){if(this.checked){map.removeLayer(osm);map.addLayer(esri);}});

  /* Legenda */
  var legend=L.control({position:"bottomleft"});
  legend.onAdd=function(){
    var d=L.DomUtil.create("div","legend");
    var html="<b>Fungsi Bangunan</b>";
    Object.keys(FUNGSI_WARNA).forEach(function(k){
      html+="<span class='dot' style='background:"+FUNGSI_WARNA[k]+"'></span>"+k+"<br>";
    });
    html+="<span class='dot' style='background:"+WARNA_LAIN+"'></span>Lainnya";
    d.innerHTML=html;
    return d;
  };
  legend.addTo(map);

  /* Marker Bangunan */
  var group=L.layerGroup().addTo(map);
  function popupHTML(d){
    var warna=warnaFungsi(d.fungsi);
    return "<h6>"+d.nama+"</h6>"+
      "<div class='pp-row'><span>OPD</span><span>"+d.opd+"</span></div>"+
      "<div class='pp-row'><span>Fungsi</span><span>"+d.fungsi+"</span></div>"+
      "<div class='pp-row'><span>Alamat</span><span>"+d.alamat+"</span></div>"+
      "<div class='pp-row'><span>Kecamatan</span><span>"+d.kec+"</span></div>"+
      "<div class='pp-row'><span>Kelurahan</span><span>"+d.kel+"</span></div>"+
      "<span class='pp-status' style='color:"+warna+";border-color:"+warna+"'>"+d.fungsi+"</span>";
  }
  function makeMarker(d){
    var c=warnaFungsi(d.fungsi);
    var m=L.circleMarker([d.lat,d.lng],{radius:6,color:"#ffffff",weight:1.4,fillColor:c,fillOpacity:.9});
    m.bindPopup(popupHTML(d));
    m._data=d;
    return m;
  }

  /* ============ FILTER ============ */
  var elCari=document.getElementById("f-cari"),
      elKec=document.getElementById("f-kec"),
      elKel=document.getElementById("f-kel"),
      elReset=document.getElementById("btnReset"),
      elShown=document.getElementById("countShown"),
      elAll=document.getElementById("countAll");
  elAll.textContent=DATA.length;

  var KEC_NAMES=(gisKecamatan.features||[]).map(function(f){return f.properties&&f.properties.namaKecamatan;}).filter(Boolean).sort();
  KEC_NAMES.forEach(function(nm){
    var o=document.createElement("option");o.value=nm;o.textContent=nm;elKec.appendChild(o);
  });
  function fillKel(){
    var kec=elKec.value;elKel.innerHTML="<option value=''>— Semua —</option>";
    var kels=[];
    DATA.forEach(function(d){
      if(kec&&d.kec!==kec)return;
      if(d.kel&&kels.indexOf(d.kel)<0)kels.push(d.kel);
    });
    kels.sort().forEach(function(x){var o=document.createElement("option");o.value=x;o.textContent=x;elKel.appendChild(o);});
  }
  fillKel();

  function render(fit){
    var q=elCari.value.trim().toLowerCase(),kec=elKec.value,kel=elKel.value;
    group.clearLayers();
    var shown=[],bounds=[];
    DATA.forEach(function(d){
      if(kec&&d.kec!==kec)return;
      if(kel&&d.kel!==kel)return;
      if(q&&(d.nama+" "+d.opd+" "+d.alamat).toLowerCase().indexOf(q)<0)return;
      var m=makeMarker(d);group.addLayer(m);shown.push(m);bounds.push([d.lat,d.lng]);
    });
    elShown.textContent=shown.length;
    if(fit&&bounds.length)map.fitBounds(bounds,{padding:[46,46],maxZoom:14});
    return shown;
  }
  elCari.addEventListener("input",function(){render(false)});
  elKec.addEventListener("change",function(){fillKel();render(true)});
  elKel.addEventListener("change",function(){render(true)});
  elReset.addEventListener("click",function(){
    elCari.value="";elKec.value="";fillKel();elKel.value="";
    render(false);map.setView([-7.53,108.99],10);geoClearFn();
  });
  render(false);

  /* ============ CARI ALAMAT / KOORDINAT ============ */
  var geoInput=document.getElementById("geoInput"),geoBtn=document.getElementById("geoClear"),geoMarker=null;
  L.DomEvent.disableClickPropagation(document.querySelector(".map-geo"));
  function geoClearFn(){geoInput.value="";if(geoMarker){map.removeLayer(geoMarker);geoMarker=null;}}
  geoBtn.addEventListener("click",geoClearFn);
  geoInput.addEventListener("keydown",function(e){
    if(e.key!=="Enter")return;
    var v=geoInput.value.trim();if(!v)return;
    var m=v.match(/^\s*(-?\d+(?:[.,]\d+)?)\s*[,;\s]\s*(-?\d+(?:[.,]\d+)?)\s*$/);
    if(m){ /* koordinat */
      var lat=parseFloat(m[1].replace(",", ".")),lng=parseFloat(m[2].replace(",", "."));
      if(geoMarker)map.removeLayer(geoMarker);
      geoMarker=L.marker([lat,lng]).addTo(map).bindPopup("Titik: "+lat.toFixed(5)+", "+lng.toFixed(5)).openPopup();
      map.setView([lat,lng],15);
    }else{ /* cari nama pada data */
      var q=v.toLowerCase(),hit=null;
      group.eachLayer(function(l){if(!hit&&l._data&&(l._data.nama+" "+l._data.alamat).toLowerCase().indexOf(q)>=0)hit=l;});
      if(!hit){elCari.value=v;var s=render(true);if(s.length===1)s[0].openPopup();}
      else{map.setView(hit.getLatLng(),15);hit.openPopup();}
    }
  });
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
// ===== TEMA (tanpa penyimpanan browser: dibawa lewat parameter URL antar halaman) =====
(function(){
  var p=new URLSearchParams(location.search);
  var t=p.get('theme')==='light'?'light':'dark';
  applyTheme(t,false);

  function applyTheme(theme,rewrite){
    document.documentElement.setAttribute('data-theme',theme);
    document.querySelectorAll('.swatch').forEach(function(s){
      s.classList.toggle('sel',s.dataset.theme===theme);
    });
    // sisipkan tema ke seluruh tautan internal agar pilihan terbawa antar halaman
    document.querySelectorAll('a[href]').forEach(function(a){
      var h=a.getAttribute('href');
      if(!h||/^(https?:|mailto:|#)/.test(h))return;
      var parts=h.split('#');var base=parts[0].split('?')[0];
      a.setAttribute('href',base+'?theme='+theme+(parts[1]?'#'+parts[1]:''));
    });
  }
  window.__applyTheme=applyTheme;
})();

// ===== Panel warna =====
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

// ===== Navbar & menu ponsel =====
var bar=document.getElementById('topbar');
addEventListener('scroll',function(){bar.classList.toggle('scrolled',scrollY>40)},{passive:true});

// ===== Animasi muncul =====
var io=new IntersectionObserver(function(es){es.forEach(function(e){
  if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}
})},{threshold:.12});
document.querySelectorAll('.reveal').forEach(function(el){io.observe(el)});
</script>
</body>
</html>
