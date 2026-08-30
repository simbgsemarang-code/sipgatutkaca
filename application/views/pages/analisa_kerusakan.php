<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Analisa Kerusakan — SIP Gatutkaca · Kabupaten Cilacap</title>
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
.user-menu{position:relative}
.user-menu-btn{display:flex;align-items:center;gap:8px;background:none;border:none;font-family:var(--body);font-size:.78rem;letter-spacing:.08em;color:var(--gold-300);font-weight:600;cursor:pointer;padding:8px 2px}
.user-menu-btn svg{transition:transform .25s}
.user-menu-btn[aria-expanded="true"] svg{transform:rotate(180deg)}
.user-menu-panel{position:absolute;top:calc(100% + 12px);right:0;min-width:190px;background:var(--surface);border:1px solid var(--line);box-shadow:0 16px 42px var(--shadow);padding:8px;opacity:0;transform:translateY(8px);pointer-events:none;transition:.25s;z-index:70}
.user-menu-panel.open{opacity:1;transform:none;pointer-events:auto}
.user-menu-panel a{display:flex;align-items:center;gap:10px;padding:11px 14px;font-size:.76rem;letter-spacing:.04em;color:var(--text)}
.user-menu-panel a:hover{background:var(--surface-hi);color:var(--gold-300)}
.user-menu-panel a.logout:hover{color:#E0526B;background:rgba(224,82,107,.08)}
.page-breadcrumb{margin-bottom:22px;font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)}
.page-breadcrumb a{color:var(--gold-300)}
.page-breadcrumb a:hover{text-decoration:underline}
.page-breadcrumb .sep{margin:0 8px;color:var(--line)}

.btn{display:inline-block;padding:15px 34px;font-size:.78rem;letter-spacing:.26em;text-transform:uppercase;transition:.3s;cursor:pointer;border:none;font-family:var(--body)}
.btn-gold{background:linear-gradient(135deg,#C9A24B,#E4C87B);color:#081826;font-weight:600}
.btn-gold:hover{filter:brightness(1.08);transform:translateY(-2px)}
.btn-ghost{border:1px solid var(--line);color:var(--text);background:transparent}
.btn-ghost:hover{border-color:#C9A24B;color:#E4C87B}
/* Tombol Dashboard/Masuk di kop transparan di atas hero sebelum discroll - kasih latar solid (sama seperti header.scrolled) supaya tetap kelihatan. */
.auth-actions .btn-ghost{background:var(--head-bg)}
.btn-sm{padding:11px 26px;font-size:.72rem;letter-spacing:.2em}

/* ===== SECTION ===== */
section{padding:100px 0}
section.alt{background:var(--bg-alt)}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.8rem,3.4vw,2.6rem);line-height:1.2;max-width:24ch}
.section-lead{color:var(--muted);max-width:66ch;margin-top:18px}
.field{margin-bottom:0}
label{display:block;font-size:.72rem;letter-spacing:.24em;text-transform:uppercase;color:var(--muted);margin-bottom:9px}
input,select,textarea{width:100%;background:var(--input);border:1px solid var(--line);color:var(--text);padding:13px 15px;font-family:var(--body);font-size:.92rem}
input:focus,select:focus,textarea:focus{outline:1px solid var(--gold-500);border-color:var(--gold-500)}
.note{font-size:.78rem;color:var(--muted);margin-top:16px}

/* ===== STATISTIK KONDISI (kartu ala dashboard) ===== */
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-top:52px}
.stat{background:var(--surface);padding:26px 24px;text-align:left;border-radius:14px;box-shadow:0 4px 18px var(--shadow);border-left:4px solid var(--gold-500)}
.stat:nth-child(1){border-left-color:#2EA84F}
.stat:nth-child(2){border-left-color:#F2C230}
.stat:nth-child(3){border-left-color:#D9822B}
.stat:nth-child(4){border-left-color:#C0392B}
.stat b{display:block;font-family:var(--display);font-weight:400;font-size:2.2rem;margin-bottom:6px;color:var(--gold-500)}
.stat span{font-size:.68rem;letter-spacing:.2em;text-transform:uppercase;color:var(--muted)}

/* ===== PETA ===== */
.map-filter{position:absolute;top:16px;left:16px;right:16px;z-index:20;display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:16px;align-items:end;
  background:rgba(255,255,255,.5);backdrop-filter:blur(16px) saturate(1.4);-webkit-backdrop-filter:blur(16px) saturate(1.4);
  border:1px solid rgba(255,255,255,.6);border-radius:16px;padding:16px 22px;box-shadow:0 12px 34px rgba(21,42,59,.20)}
.map-filter label{color:var(--gold-300)}
html[data-theme="dark"] .map-filter{background:rgba(10,26,40,.5);border-color:rgba(201,162,75,.35)}
#btnReset{padding:13px 30px}
/* z-index:0 mengurung seluruh isi peta (termasuk panel filter yang
   melayang) dalam satu stacking context di bawah navbar fixed (z-index
   60) — supaya panel tidak menembus/menimpa header saat halaman digulir. */
.map-shell{position:relative;z-index:0;width:94vw;margin-left:calc(50% - 47vw);margin-right:calc(50% - 47vw);border:1px solid var(--line);box-shadow:0 18px 50px var(--shadow);border-radius:22px;overflow:hidden}
#map{height:640px;width:100%;background:#dfeee2;z-index:1}
.legend{background:#fff;color:#223;padding:12px 16px;font-size:.78rem;line-height:2;box-shadow:0 2px 10px rgba(0,0,0,.25)}
.legend b{display:block;font-family:var(--display);font-weight:400;letter-spacing:.14em;text-transform:uppercase;font-size:.68rem;margin-bottom:4px;color:#8a6a1c}
/* Kontrol lapisan (topright) diturunkan supaya tidak tertimpa panel filter melayang */
.leaflet-top.leaflet-right{margin-top:104px}
@media(max-width:980px){.leaflet-top.leaflet-right{margin-top:170px}}
@media(max-width:820px){.leaflet-top.leaflet-right{margin-top:12px}}
.leaflet-control-layers{border-radius:4px;box-shadow:0 2px 10px rgba(0,0,0,.28);color:#223}
.leaflet-control-layers-expanded{padding:10px 12px;min-width:172px;font-size:.76rem}
.leaflet-control-layers-list{line-height:1.35;padding:0;margin:0}
.leaflet-control-layers-base,.leaflet-control-layers-overlays{padding:0;margin:0}
.leaflet-control-layers label{margin:1px 0;font-weight:400;line-height:1.35;white-space:nowrap;display:flex;align-items:center}
.leaflet-control-layers label>span{display:flex;align-items:center}
.leaflet-control-layers-selector{accent-color:#A57E2C;margin:0 6px 0 0;width:13px;height:13px}
.leaflet-control-layers-separator{margin:7px 0;border-top-color:#e6e6e6}
.dot{display:inline-block;width:12px;height:12px;border-radius:50%;margin-right:8px;vertical-align:-1px;border:1.5px solid #fff;box-shadow:0 0 0 1px rgba(0,0,0,.25)}
.map-count{margin-top:14px;font-size:.78rem;letter-spacing:.16em;text-transform:uppercase;color:var(--muted)}
.map-count b{color:var(--gold-300);font-family:var(--display);font-weight:400}
.leaflet-popup-content{font-family:var(--body);font-size:.85rem;line-height:1.6;color:#223;min-width:248px}
.leaflet-popup-content h6{font-family:var(--display);font-weight:400;font-size:1rem;letter-spacing:.05em;color:#8a6a1c;margin:0 0 6px}
.pp-row{display:flex;gap:8px}.pp-row span:first-child{min-width:104px;color:#889;flex:0 0 auto}
.pp-status{display:inline-block;margin-top:8px;padding:2px 12px;font-size:.66rem;letter-spacing:.18em;text-transform:uppercase;border:1px solid}
.leaflet-popup-content .pp-act{display:flex;gap:8px;margin-top:12px}
.leaflet-popup-content .pp-btn{flex:1;text-align:center;padding:8px 10px;font-size:.72rem;font-weight:600;letter-spacing:.02em;border:1px solid #c9a24b;border-radius:7px;color:#8a6a1c;text-decoration:none;white-space:nowrap;transition:.15s}
.leaflet-popup-content .pp-btn:hover{background:#f5ecd6}
.leaflet-popup-content .pp-btn-solid{background:#c9a24b;border-color:#c9a24b;color:#20140a}
.leaflet-popup-content .pp-btn-solid:hover{background:#b98f38}
html[data-theme="dark"] .leaflet-tile{filter:brightness(.82) contrast(1.06) saturate(.85)}
@media(max-width:980px){.map-filter{grid-template-columns:1fr 1fr}#btnReset{width:100%}}
@media(max-width:820px){
  /* Layar sempit: kembalikan filter jadi panel padat di atas peta (di
     dalam bingkai), bukan overlay — supaya tidak menutupi peta. */
  .map-filter{position:static;background:var(--surface);backdrop-filter:none;-webkit-backdrop-filter:none;border:none;border-bottom:1px solid var(--line);border-radius:0;box-shadow:none;padding:18px 20px}
}
@media(max-width:520px){.map-filter{grid-template-columns:1fr}#map{height:460px}}

/* ===== GALERI KONDISI ===== */
.gallery-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:52px}
.g-card{border:1px solid var(--line);background:var(--surface);transition:.3s;border-radius:14px;overflow:hidden;box-shadow:0 4px 16px var(--shadow)}
.g-card:hover{background:var(--surface-hi);transform:translateY(-3px);box-shadow:0 14px 30px var(--shadow)}
.g-photo{height:170px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;position:relative;overflow:hidden}
.g-photo img{width:100%;height:100%;object-fit:cover}
.g-photo .ph-label{font-size:.64rem;letter-spacing:.16em;text-transform:uppercase;color:rgba(255,255,255,.85)}
.g-body{padding:20px 22px 24px}
.g-body h4{font-family:var(--display);font-weight:400;font-size:1.02rem;letter-spacing:.03em;margin-bottom:8px;color:var(--gold-300);line-height:1.35}
.g-meta{font-size:.78rem;color:var(--muted);margin-bottom:4px}
.g-coord{font-size:.74rem;color:var(--muted);letter-spacing:.03em;margin-top:10px;font-family:monospace}
.g-status{display:inline-block;margin-top:12px;padding:3px 12px;font-size:.64rem;letter-spacing:.16em;text-transform:uppercase;border:1px solid}
.g-go{display:inline-block;margin-top:14px;font-size:.7rem;letter-spacing:.2em;text-transform:uppercase;color:var(--gold-300);cursor:pointer;border-bottom:1px solid transparent}
.g-go:hover{border-color:var(--gold-300)}
.g-empty{grid-column:1/-1;text-align:center;color:var(--muted);padding:60px 0;font-size:.92rem}
.pager{display:flex;align-items:center;justify-content:center;gap:18px;margin-top:44px}
.pager button{background:var(--surface);border:1px solid var(--line);color:var(--text);padding:11px 22px;font-size:.72rem;letter-spacing:.2em;text-transform:uppercase;cursor:pointer;transition:.25s}
.pager button:hover:not(:disabled){border-color:var(--gold-500);color:var(--gold-300)}
.pager button:disabled{opacity:.35;cursor:not-allowed}
.pager span{font-size:.78rem;color:var(--muted);letter-spacing:.08em}

/* ===== FOOTER ===== */
footer{background:var(--foot);color:#F8F4EA;padding:66px 0 32px;border-top:1px solid var(--line)}
.foot-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr;gap:50px}
.foot-grid h4{font-family:var(--display);font-weight:400;letter-spacing:.14em;color:#E4C87B;margin-bottom:18px;font-size:1rem}
.foot-grid p,.foot-grid li{font-size:.88rem;color:#B9C7D2}
.foot-grid ul{list-style:none;display:grid;gap:10px}
.foot-grid a:hover{color:#E4C87B}
.credit{margin-top:50px;padding-top:22px;border-top:1px solid rgba(185,199,210,.15);display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap;font-size:.72rem;color:rgba(185,199,210,.6);letter-spacing:.06em}

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

/* ===== REVEAL ===== */
.reveal{opacity:0;transform:translateY(28px);transition:opacity .8s ease,transform .8s ease}
.reveal.in{opacity:1;transform:none}
@media (prefers-reduced-motion:reduce){
  .reveal{opacity:1;transform:none;transition:none}
  html{scroll-behavior:auto}
}

@media(max-width:980px){
  .stats{grid-template-columns:repeat(2,1fr)}
  .gallery-grid{grid-template-columns:repeat(2,1fr)}
  .foot-grid{grid-template-columns:1fr}
}
@media(max-width:560px){
  .stats{grid-template-columns:1fr 1fr}
  .gallery-grid{grid-template-columns:1fr}
  section{padding:76px 0}
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">

<section style="padding-top:calc(84px + 30px)">
  <div class="wrap">
    <div class="page-breadcrumb reveal">
      <a href="<?php echo base_url(); ?>">Beranda</a><span class="sep">/</span>Analisa Kerusakan
    </div>
    <div class="reveal" style="max-width:760px;margin:0 auto;text-align:center">
      <p class="eyebrow">Analisa Kerusakan</p>
      <h2 style="margin:0 auto">ANALISIS KERUSAKAN</h2>
    </div>

    <div class="map-shell reveal" style="margin-top:52px">
      <div class="map-filter">
        <div class="field">
          <label for="f-cari">Cari Bangunan (Nama / Alamat / OPD)</label>
          <input id="f-cari" type="text" placeholder="Ketik nama, OPD, atau alamat" autocomplete="off">
        </div>
        <div class="field">
          <label for="f-kec">Kecamatan</label>
          <select id="f-kec"><option value="">— Semua —</option></select>
        </div>
        <div class="field">
          <label for="f-kondisi">Kondisi</label>
          <select id="f-kondisi">
            <option value="">— Semua —</option>
            <option value="1">Baik</option>
            <option value="2">Rusak Ringan</option>
            <option value="3">Rusak Sedang</option>
            <option value="4">Rusak Berat</option>
          </select>
        </div>
        <button class="btn btn-gold" id="btnReset" type="button">Reset</button>
      </div>
      <div id="map" role="application" aria-label="Peta kondisi bangunan Kabupaten Cilacap"></div>
    </div>
    <p class="map-count reveal">Menampilkan <b id="countShown">0</b> dari <b id="countAll">0</b> bangunan</p>

    <div class="stats reveal" id="statBox" style="margin-top:56px">
      <div class="stat"><b id="stBaik">0</b><span>Kondisi Baik</span></div>
      <div class="stat"><b id="stRingan">0</b><span>Rusak Ringan</span></div>
      <div class="stat"><b id="stSedang">0</b><span>Rusak Sedang</span></div>
      <div class="stat"><b id="stBerat">0</b><span>Rusak Berat</span></div>
    </div>
  </div>
</section>

<section class="alt" id="galeri">
  <div class="wrap">
    <div class="reveal" style="text-align:center;max-width:720px;margin:0 auto">
      <p class="eyebrow">Galeri &amp; Rincian</p>
      <h2 style="margin:0 auto">Foto, Kondisi, dan Titik Koordinat</h2>
      <p class="section-lead" style="margin-left:auto;margin-right:auto">Kartu berikut mengikuti hasil saringan pada peta di atas. Foto lapangan akan tampil otomatis begitu petugas mengunggahnya; sementara ini kartu tanpa foto ditandai sebagai <em>“Foto belum tersedia”</em>.</p>
    </div>
    <div class="gallery-grid reveal" id="galleryGrid"></div>
    <div class="pager reveal" id="pager">
      <button id="prevPage" type="button">&larr; Sebelumnya</button>
      <span id="pageInfo">Halaman 1</span>
      <button id="nextPage" type="button">Berikutnya &rarr;</button>
    </div>
  </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script src="gis-data.js"></script>
<script>
// Titik bangunan diambil dari endpoint yang dikelola admin
// (Admin::bangunan* -> tabel bangunan_gis). gis-data.js tetap dipakai
// untuk layer referensi (batas kecamatan/kabupaten/jalan). Kalau
// endpoint gagal, jatuh ke gisBangunan bawaan gis-data.js.
fetch("<?php echo base_url('gis/bangunan'); ?>", {headers:{"Accept":"application/json"}})
  .then(function(r){ return r.ok ? r.json() : null; })
  .then(function(gj){ if(gj && gj.features && gj.features.length) window.gisBangunan = gj; })
  .catch(function(){})
  .then(bootPetaAnalisa);

function bootPetaAnalisa(){
(function(){
  /* ============ KONDISI: LABEL & WARNA ============ */
  var KONDISI={
    "1":{label:"Baik",color:"#2EA84F"},
    "2":{label:"Rusak Ringan",color:"#F2C230"},
    "3":{label:"Rusak Sedang",color:"#D9822B"},
    "4":{label:"Rusak Berat",color:"#C0392B"}
  };
  var KONDISI_LAIN={label:"Tidak Diketahui",color:"#8A94A6"};
  function infoKondisi(k){ return KONDISI[k]||KONDISI_LAIN; }

  /* ============ DATA (dari gisBangunan) ============ */
  var DATA=(gisBangunan.features||[]).map(function(f,i){
    var p=f.properties||{},c=(f.geometry&&f.geometry.coordinates)||[null,null];
    return{
      id:p.idBangunan||(i+1),
      nama:p.namaBangunan||"(Tanpa nama)",
      opd:p.opd||p.institusi||p.unit||"-",
      alamat:p.alamat||"-",
      kec:p.kecamatan||"-",
      kel:p.kelurahan||"-",
      fungsi:p.fungsi||"-",
      kondisi:p.kondisi||"",
      foto:p.foto||null,
      lat:c[1],lng:c[0]
    };
  }).filter(function(d){return typeof d.lat==="number"&&typeof d.lng==="number";});

  /* ============ STATISTIK ============ */
  var counts={"1":0,"2":0,"3":0,"4":0};
  DATA.forEach(function(d){ if(counts[d.kondisi]!==undefined) counts[d.kondisi]++; });
  document.getElementById("stBaik").textContent=counts["1"];
  document.getElementById("stRingan").textContent=counts["2"];
  document.getElementById("stSedang").textContent=counts["3"];
  document.getElementById("stBerat").textContent=counts["4"];

  /* ============ PETA: LAPIS DASAR ============ */
  var _gt="&x={x}&y={y}&z={z}",_go={maxZoom:20,subdomains:["0","1","2","3"],attribution:"&copy; Google"};
  var baseGmap    =L.tileLayer("https://mt{s}.google.com/vt/lyrs=m"+_gt,_go),
      baseOsm     =L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png",{maxZoom:19,attribution:"&copy; OpenStreetMap"}),
      baseGhybrid =L.tileLayer("https://mt{s}.google.com/vt/lyrs=y"+_gt,_go),
      baseGsat    =L.tileLayer("https://mt{s}.google.com/vt/lyrs=s"+_gt,_go),
      baseGterrain=L.tileLayer("https://mt{s}.google.com/vt/lyrs=p"+_gt,_go);
  var map=L.map("map",{layers:[baseGmap],zoomControl:false,scrollWheelZoom:true}).setView([-7.53,108.99],10);
  // Kontrol zoom dipindah ke bawah supaya tidak tertutup panel filter
  // yang kini melayang penuh di sisi atas peta.
  L.control.zoom({position:"bottomright"}).addTo(map);
  L.control.scale({imperial:false,position:"bottomleft"}).addTo(map);

  /* ============ WARNA WILAYAH KECAMATAN ============ */
  var PALET_KEC=["#4E9F3D","#3E7CB1","#D9822B","#8E6FCE","#E0526B","#4FB0C6","#B5B53C","#C9A24B",
    "#5FBF8F","#C97CC9","#7C93C9","#E0A15A","#6FC2A0","#C96F6F","#8FBF5F","#B58AE0",
    "#5FA8D9","#D9C15F","#9C6FE0","#5FD9B0","#D95FA1","#7FD95F","#D98F5F","#5F8FD9"];
  var kecColorMap={};
  function kecStyle(f){
    var nm=(f.properties&&f.properties.namaKecamatan)||"?";
    if(!(nm in kecColorMap))kecColorMap[nm]=PALET_KEC[Object.keys(kecColorMap).length%PALET_KEC.length];
    var c=kecColorMap[nm];
    return{color:c,weight:1.6,opacity:.9,fillColor:c,fillOpacity:.28};
  }
  var kecLayer=null;
  if(window.gisKecamatan&&gisKecamatan.features){
    kecLayer=L.geoJSON(gisKecamatan,{
      style:kecStyle,
      onEachFeature:function(f,l){
        var nm=f.properties&&f.properties.namaKecamatan;
        if(nm)l.bindTooltip("Kecamatan "+nm,{sticky:true});
        l.on("mouseover",function(){ l.setStyle({fillOpacity:.42,weight:2.4}); });
        l.on("mouseout",function(){ l.setStyle(kecStyle(f)); });
      }
    }).addTo(map);
  }

  /* ============ LAPIS REFERENSI WILAYAH (dari gis-data.js) ============ */
  var kabLayer=(window.gisKabupaten&&gisKabupaten.features)
    ? L.geoJSON(gisKabupaten,{style:{color:"#C9A24B",weight:2.2,dashArray:"7 6",fill:false}}).bindTooltip("Batas Kabupaten Cilacap",{sticky:true})
    : null;
  var desaLayer=(window.gisDesa&&gisDesa.features)
    ? L.geoJSON(gisDesa,{style:{color:"#8FD3E8",weight:.8,opacity:.55,fillColor:"#8FD3E8",fillOpacity:.03},
        onEachFeature:function(f,l){var nm=f.properties&&f.properties.namaDesa;if(nm)l.bindTooltip(nm,{sticky:true});}})
    : null;
  var jalanLayer=(window.gisJalan&&gisJalan.features)
    ? L.geoJSON(gisJalan,{style:{color:"#E8B84B",weight:1.3,opacity:.6},
        onEachFeature:function(f,l){var nm=f.properties&&f.properties.namaJalan;if(nm)l.bindTooltip(nm,{sticky:true});}})
    : null;
  if(kabLayer)kabLayer.addTo(map);

  var legend=L.control({position:"bottomright"});
  legend.onAdd=function(){
    var d=L.DomUtil.create("div","legend");
    var html="<b>Kondisi Bangunan</b>";
    Object.keys(KONDISI).forEach(function(k){
      html+="<span class='dot' style='background:"+KONDISI[k].color+"'></span>"+KONDISI[k].label+"<br>";
    });
    d.innerHTML=html;
    return d;
  };
  legend.addTo(map);

  var group=L.layerGroup().addTo(map);
  var markerById={};

  /* ============ KONTROL LAPISAN ============ */
  var _base={"Google Maps":baseGmap,"OpenStreetMap":baseOsm,"Google Hybrid":baseGhybrid,"Google Satelit":baseGsat,"Google Terrain":baseGterrain};
  var _ovl={"Bangunan":group};
  if(kabLayer)  _ovl["Batas Kabupaten"]=kabLayer;
  if(kecLayer)  _ovl["Batas Kecamatan"]=kecLayer;
  if(desaLayer) _ovl["Batas Desa"]=desaLayer;
  if(jalanLayer)_ovl["Jaringan Jalan"]=jalanLayer;
  L.control.layers(_base,_ovl,{position:"topright",collapsed:false}).addTo(map);

  var URL_DETAIL="<?php echo base_url('bangunan'); ?>";
  function esc(s){ return String(s==null?"":s).replace(/[&<>\"]/g,function(c){return{"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;"}[c];}); }
  function popupHTML(d){
    var info=infoKondisi(d.kondisi);
    var dir="https://www.google.com/maps/dir/?api=1&destination="+d.lat+","+d.lng;
    return "<h6>("+d.id+") "+esc(d.nama)+"</h6>"+
      "<div class='pp-row'><span>OPD</span><span>"+esc(d.opd)+"</span></div>"+
      "<div class='pp-row'><span>Desa / Kelurahan</span><span>"+esc(d.kel)+"</span></div>"+
      "<div class='pp-row'><span>Kecamatan</span><span>"+esc(d.kec)+"</span></div>"+
      "<div class='pp-row'><span>Alamat</span><span>"+esc(d.alamat)+"</span></div>"+
      "<span class='pp-status' style='color:"+info.color+";border-color:"+info.color+"'>"+info.label+"</span>"+
      "<div class='pp-act'>"+
        "<a class='pp-btn pp-btn-solid' target='_blank' rel='noopener' href='"+URL_DETAIL+"/"+d.id+"'>Detail Bangunan</a>"+
        "<a class='pp-btn' target='_blank' rel='noopener' href='"+dir+"'>Menuju Lokasi</a>"+
      "</div>";
  }
  function makeMarker(d){
    var info=infoKondisi(d.kondisi);
    var m=L.circleMarker([d.lat,d.lng],{radius:6,color:"#ffffff",weight:1.4,fillColor:info.color,fillOpacity:.9});
    m.bindPopup(popupHTML(d));
    m._data=d;
    markerById[d.id]=m;
    return m;
  }

  /* ============ FILTER ============ */
  var elCari=document.getElementById("f-cari"),
      elKec=document.getElementById("f-kec"),
      elKondisi=document.getElementById("f-kondisi"),
      elReset=document.getElementById("btnReset"),
      elShown=document.getElementById("countShown"),
      elAll=document.getElementById("countAll");
  elAll.textContent=DATA.length;

  var KEC_NAMES=(gisKecamatan.features||[]).map(function(f){return f.properties&&f.properties.namaKecamatan;}).filter(Boolean).sort();
  KEC_NAMES.forEach(function(nm){
    var o=document.createElement("option");o.value=nm;o.textContent=nm;elKec.appendChild(o);
  });

  var PAGE_SIZE=12,curPage=1,curFiltered=[];

  function applyFilter(){
    var q=elCari.value.trim().toLowerCase(),kec=elKec.value,kon=elKondisi.value;
    return DATA.filter(function(d){
      if(kec&&d.kec!==kec)return false;
      if(kon&&d.kondisi!==kon)return false;
      if(q&&(d.nama+" "+d.opd+" "+d.alamat).toLowerCase().indexOf(q)<0)return false;
      return true;
    });
  }

  function renderMap(fit){
    group.clearLayers();
    var bounds=[];
    curFiltered.forEach(function(d){
      var m=makeMarker(d);group.addLayer(m);bounds.push([d.lat,d.lng]);
    });
    elShown.textContent=curFiltered.length;
    // maxZoom dibatasi supaya kalau hasil saringan tinggal 1 titik,
    // peta tidak melompat terlalu dekat.
    if(fit&&bounds.length)map.fitBounds(bounds,{padding:[55,55],maxZoom:13});
  }

  function photoBoxHTML(d){
    var info=infoKondisi(d.kondisi);
    if(d.foto){
      return '<div class="g-photo"><img src="'+d.foto+'" alt="Foto '+d.nama+'" loading="lazy"></div>';
    }
    return '<div class="g-photo" style="background:'+info.color+'22">'+
      '<svg width="34" height="34" viewBox="0 0 40 40" aria-hidden="true">'+
      '<rect x="5" y="9" width="30" height="24" rx="2" fill="none" stroke="'+info.color+'" stroke-width="1.8"/>'+
      '<circle cx="14" cy="17" r="3.4" fill="none" stroke="'+info.color+'" stroke-width="1.8"/>'+
      '<path d="M6 30l9-9 6 6 6-8 8 11" fill="none" stroke="'+info.color+'" stroke-width="1.8" stroke-linejoin="round" stroke-linecap="round"/>'+
      '</svg>'+
      '<span class="ph-label" style="color:'+info.color+'">Foto belum tersedia</span></div>';
  }

  function renderGallery(){
    var grid=document.getElementById("galleryGrid");
    var totalPages=Math.max(1,Math.ceil(curFiltered.length/PAGE_SIZE));
    if(curPage>totalPages)curPage=totalPages;
    var start=(curPage-1)*PAGE_SIZE,slice=curFiltered.slice(start,start+PAGE_SIZE);
    if(!slice.length){
      grid.innerHTML='<div class="g-empty">Tidak ada bangunan yang cocok dengan saringan saat ini.</div>';
    }else{
      grid.innerHTML=slice.map(function(d){
        var info=infoKondisi(d.kondisi);
        return '<div class="g-card">'+
          photoBoxHTML(d)+
          '<div class="g-body">'+
            '<h4>'+d.nama+'</h4>'+
            '<div class="g-meta">'+d.opd+'</div>'+
            '<div class="g-meta">'+d.kec+', '+d.kel+'</div>'+
            '<div class="g-coord">'+d.lat.toFixed(6)+', '+d.lng.toFixed(6)+'</div>'+
            '<span class="g-status" style="color:'+info.color+';border-color:'+info.color+'">'+info.label+'</span><br>'+
            '<span class="g-go" data-id="'+d.id+'">Lihat di Peta &rarr;</span>'+
          '</div>'+
        '</div>';
      }).join("");
    }
    document.getElementById("pageInfo").textContent="Halaman "+curPage+" dari "+totalPages;
    document.getElementById("prevPage").disabled=curPage<=1;
    document.getElementById("nextPage").disabled=curPage>=totalPages;
    grid.querySelectorAll(".g-go").forEach(function(el){
      el.addEventListener("click",function(){
        var m=markerById[el.dataset.id];
        if(!m)return;
        document.getElementById("map").scrollIntoView({behavior:"smooth",block:"center"});
        map.setView(m.getLatLng(),16);
        setTimeout(function(){m.openPopup();},400);
      });
    });
  }

  function render(fit){
    curFiltered=applyFilter();
    curPage=1;
    renderMap(fit);
    renderGallery();
  }

  elCari.addEventListener("input",function(){render(false)});
  elKec.addEventListener("change",function(){render(true)});
  elKondisi.addEventListener("change",function(){render(true)});
  elReset.addEventListener("click",function(){
    elCari.value="";elKec.value="";elKondisi.value="";
    render(true);
  });
  document.getElementById("prevPage").addEventListener("click",function(){if(curPage>1){curPage--;renderGallery();}});
  document.getElementById("nextPage").addEventListener("click",function(){curPage++;renderGallery();});

  // Isi marker + galeri dulu tanpa menggeser peta...
  render(false);
  // ...lalu saat peta siap, bingkai otomatis ke SEMUA titik bangunan
  // (fitBounds) supaya posisi & zoom awal langsung menampilkan seluruh
  // sebaran se-Kabupaten, bukan view [-7.53,108.99]/z10 yang statis.
  // invalidateSize() memastikan ukuran kontainer sudah benar (animasi
  // .reveal / layout) sebelum perhitungan fitBounds.
  function bingkaiSemua(){
    map.invalidateSize();
    if(curFiltered.length) map.fitBounds(curFiltered.map(function(d){return [d.lat,d.lng];}),{padding:[55,55],maxZoom:13});
  }
  map.whenReady(bingkaiSemua);
  setTimeout(bingkaiSemua,600);
})();
}
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
      <span>Data kondisi bersumber dari hasil pendataan bangunan gedung milik daerah</span>
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
// ===== TEMA (tanpa penyimpanan browser: dibawa lewat parameter URL antar halaman) =====
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
