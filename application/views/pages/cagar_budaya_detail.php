<?php
$kat      = (string) $cb['kategori'];
$k_warna  = isset($warna_kat[$kat]) ? $warna_kat[$kat] : '#8A94A6';
$ada_titik = ($cb['latitude'] !== NULL && $cb['longitude'] !== NULL);
$lat      = $ada_titik ? (float) $cb['latitude']  : NULL;
$lng      = $ada_titik ? (float) $cb['longitude'] : NULL;
$judul    = $cb['nama'];
$e        = function ($v) { return htmlspecialchars(($v === null || $v === '') ? '—' : (string) $v, ENT_QUOTES, 'UTF-8'); };
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($judul, ENT_QUOTES, 'UTF-8'); ?> — Cagar Budaya · SIP Gatutkaca</title>
<link rel="icon" type="image/png" href="<?php echo base_url('assets/img/icon.png'); ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<style>
:root{--gold-500:#C9A24B;--gold-300:#E4C87B;--gold-100:#F3E3B8;--display:'Marcellus',serif;--body:'Plus Jakarta Sans',system-ui,sans-serif}
html[data-theme="dark"]{--bg:#081826;--bg-alt:#0C2236;--surface:#0C2236;--surface-hi:#123249;--text:#F8F4EA;--muted:#B9C7D2;--line:rgba(201,162,75,.28);--head-bg:rgba(8,24,38,.94);--head-grad:rgba(8,24,38,.85);--foot:#050F19;--input:#0F2A40;--shadow:rgba(0,0,0,.5);--label:#8FA9E8}
html[data-theme="light"]{--bg:#FDFBF5;--bg-alt:#F6F1E3;--surface:#FFFFFF;--surface-hi:#FAF5E8;--text:#152A3B;--muted:#4E6070;--line:rgba(160,124,45,.35);--head-bg:rgba(253,251,245,.94);--head-grad:rgba(253,251,245,.85);--foot:#122536;--input:#FFFFFF;--shadow:rgba(21,42,59,.18);--gold-500:#A57E2C;--gold-300:#8F6C1F;--gold-100:#6E5314;--label:#3D5A99}
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
body{font-family:var(--body);background:var(--bg);color:var(--text);line-height:1.7;font-weight:300}
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
.btn{display:inline-block;padding:12px 24px;font-size:.74rem;letter-spacing:.2em;text-transform:uppercase;transition:.3s;cursor:pointer;border:none;font-family:var(--body)}
.btn-gold{background:linear-gradient(135deg,#C9A24B,#E4C87B);color:#081826;font-weight:600}
.btn-gold:hover{filter:brightness(1.08)}
.btn-ghost{border:1px solid var(--line);color:var(--text);background:transparent}
.btn-ghost:hover{border-color:#C9A24B;color:#E4C87B}
.btn-sm{padding:9px 18px;font-size:.68rem;letter-spacing:.14em}

main{padding-top:110px;padding-bottom:90px}
.page-breadcrumb{font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:14px}
.page-breadcrumb a{color:var(--gold-300)}
.page-breadcrumb a:hover{text-decoration:underline}
h1{font-family:var(--display);font-weight:400;font-size:clamp(1.6rem,3vw,2.3rem);line-height:1.2;color:var(--text);margin-bottom:4px}
.subttl{color:var(--muted);font-size:.9rem;margin-bottom:26px}
.subttl .kat{display:inline-block;padding:2px 12px;border-radius:999px;font-size:.72rem;font-weight:600;color:#fff;letter-spacing:.04em;vertical-align:1px}

.hero-foto{position:relative;border:1px solid var(--line);border-radius:16px;overflow:hidden;box-shadow:0 12px 34px var(--shadow);margin-bottom:34px;background:var(--surface-hi)}
.hero-foto img{width:100%;height:clamp(240px,42vw,440px);object-fit:cover}
.hero-foto figcaption{padding:10px 16px;font-size:.72rem;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);background:var(--surface)}

.detail-grid{display:grid;grid-template-columns:minmax(320px,440px) 1fr;gap:34px;align-items:start}
@media(max-width:880px){.detail-grid{grid-template-columns:1fr}}
.map-card{position:relative;border:1px solid var(--line);border-radius:16px;overflow:hidden;box-shadow:0 12px 34px var(--shadow)}
#cbdMap{height:420px;width:100%;background:#e9efe9;z-index:1}
.map-actions{padding:14px;display:flex;gap:10px;flex-wrap:wrap;background:var(--surface);border-top:1px solid var(--line)}
.map-none{border:1px dashed var(--line);border-radius:16px;padding:34px 24px;text-align:center;color:var(--muted);font-size:.9rem;background:var(--surface)}
.leaflet-control-layers{border-radius:4px;box-shadow:0 2px 10px rgba(0,0,0,.28);color:#223}
.leaflet-control-layers-expanded{padding:10px 12px;min-width:164px;font-size:.78rem}
.leaflet-control-layers-list{line-height:1.35;padding:0;margin:0}
.leaflet-control-layers-base,.leaflet-control-layers-overlays{padding:0;margin:0}
.leaflet-control-layers label{margin:1px 0;font-weight:400;line-height:1.35;white-space:nowrap;display:flex;align-items:center}
.leaflet-control-layers label>span{display:flex;align-items:center}
.leaflet-control-layers-selector{accent-color:#A57E2C;margin:0 6px 0 0;width:13px;height:13px}
.leaflet-control-layers-separator{border-top-color:#e6e6e6}
html[data-theme="dark"] .leaflet-tile{filter:brightness(.82) contrast(1.06) saturate(.85)}

.panel{background:var(--surface);border:1px solid var(--line);border-radius:16px;box-shadow:0 12px 34px var(--shadow);overflow:hidden}
.tabs{display:flex;gap:4px;border-bottom:1px solid var(--line);padding:6px 14px 0;overflow-x:auto}
.tab-btn{background:none;border:none;font-family:var(--body);font-size:.8rem;letter-spacing:.04em;color:var(--muted);padding:12px 14px;cursor:pointer;border-bottom:2px solid transparent;white-space:nowrap;transition:.2s}
.tab-btn:hover{color:var(--text)}
.tab-btn.active{color:var(--label);border-bottom-color:var(--label);font-weight:600}
.tab-pane{display:none;padding:22px 28px}
.tab-pane.active{display:block}
.row{display:grid;grid-template-columns:170px 1fr;gap:18px;padding:15px 0;border-bottom:1px solid var(--line)}
.row:last-child{border-bottom:none}
.row dt{font-weight:600;color:var(--label);font-size:.9rem}
.row dd{color:var(--text);font-size:.94rem}
@media(max-width:560px){.row{grid-template-columns:1fr;gap:4px}}
.badge{display:inline-flex;align-items:center;gap:7px;padding:5px 14px;border-radius:999px;font-size:.78rem;font-weight:600;color:#fff}
.desc{color:var(--text);font-size:.94rem;line-height:1.75}

footer{background:var(--foot);color:#F8F4EA;padding:60px 0 30px;border-top:1px solid var(--line);margin-top:20px}
.foot-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr;gap:50px}
.foot-grid h4{font-family:var(--display);font-weight:400;letter-spacing:.14em;color:#E4C87B;margin-bottom:18px;font-size:1rem}
.foot-grid p,.foot-grid li{font-size:.88rem;color:#B9C7D2}
.foot-grid ul{list-style:none;display:grid;gap:10px}
.foot-grid a:hover{color:#E4C87B}
.credit{margin-top:44px;padding-top:22px;border-top:1px solid rgba(185,199,210,.15);display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap;font-size:.72rem;color:rgba(185,199,210,.6)}
@media(max-width:900px){.foot-grid{grid-template-columns:1fr}}
</style>
</head>
<body>

<header id="topbar">
  <div class="wrap nav">
    <a class="brand" href="<?php echo base_url(); ?>" aria-label="Beranda SIP Gatutkaca">
      <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Seal_of_Cilacap_Regency.svg?width=120" alt="Lambang Kabupaten Cilacap">
      <span><span class="brand-name">SIP GATUTKACA</span><br><span class="brand-sub">Kabupaten Cilacap</span></span>
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
            <a href="<?php echo base_url('pengaturan'); ?>" role="menuitem">Pengaturan</a>
            <a href="<?php echo base_url('login/keluar'); ?>" role="menuitem" class="logout">Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a class="btn btn-ghost btn-sm" href="<?php echo base_url('login?from=admin'); ?>">Masuk</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<main>
  <div class="wrap">
    <div class="page-breadcrumb">
      <a href="<?php echo base_url(); ?>">Beranda</a><span> / </span><a href="<?php echo base_url('cagar-budaya'); ?>">Cagar Budaya</a><span> / </span>Detail
    </div>
    <h1><?php echo htmlspecialchars($judul, ENT_QUOTES, 'UTF-8'); ?></h1>
    <p class="subttl">
      <span class="kat" style="background:<?php echo $k_warna; ?>"><?php echo $e($cb['kategori']); ?></span>
      &nbsp; <?php echo $e($cb['kecamatan']); ?><?php echo $cb['kelurahan'] ? ' · ' . $e($cb['kelurahan']) : ''; ?>
    </p>

    <?php if (! empty($foto_url)): ?>
      <figure class="hero-foto">
        <img src="<?php echo htmlspecialchars($foto_url, ENT_QUOTES, 'UTF-8'); ?>" alt="Foto <?php echo htmlspecialchars($judul, ENT_QUOTES, 'UTF-8'); ?>"
             referrerpolicy="no-referrer"
             onerror="this.closest('.hero-foto').style.display='none'">
        <figcaption>Foto: <?php echo htmlspecialchars($cb['sumber'] ?: 'sumber publik', ENT_QUOTES, 'UTF-8'); ?></figcaption>
      </figure>
    <?php endif; ?>

    <div class="detail-grid">
      <div>
        <?php if ($ada_titik): ?>
          <div class="map-card">
            <div id="cbdMap"></div>
            <div class="map-actions">
              <a class="btn btn-gold btn-sm" target="_blank" rel="noopener noreferrer"
                 href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $lat; ?>,<?php echo $lng; ?>">Menuju Lokasi →</a>
            </div>
          </div>
        <?php else: ?>
          <div class="map-none">Titik koordinat objek ini belum tersedia. Data akan dilengkapi setelah verifikasi Tim Ahli Cagar Budaya (TACB).</div>
        <?php endif; ?>
      </div>

      <div class="panel">
        <div class="tabs" role="tablist">
          <button class="tab-btn active" data-tab="data" role="tab">Data</button>
          <?php if ($ada_titik): ?><button class="tab-btn" data-tab="geospasial" role="tab">Geospasial</button><?php endif; ?>
        </div>

        <dl class="tab-pane active" id="tab-data">
          <div class="row"><dt>Nama Objek</dt><dd><?php echo $e($cb['nama']); ?></dd></div>
          <div class="row"><dt>Kategori</dt><dd><?php echo $e($cb['kategori']); ?></dd></div>
          <div class="row"><dt>Kecamatan / Kelurahan</dt><dd><?php echo $e($cb['kecamatan']); ?> / <?php echo $e($cb['kelurahan']); ?></dd></div>
          <div class="row"><dt>Alamat</dt><dd><?php echo $e($cb['alamat']); ?></dd></div>
          <div class="row"><dt>Tahun / Periode</dt><dd><?php echo $e($cb['tahun']); ?></dd></div>
          <div class="row"><dt>Status</dt><dd><span class="badge" style="background:<?php echo $k_warna; ?>"><?php echo $e($cb['status']); ?></span></dd></div>
          <?php if (! empty($cb['no_sk'])): ?><div class="row"><dt>Nomor SK</dt><dd><?php echo $e($cb['no_sk']); ?></dd></div><?php endif; ?>
          <?php if (! empty($cb['deskripsi'])): ?><div class="row"><dt>Deskripsi</dt><dd class="desc"><?php echo $e($cb['deskripsi']); ?></dd></div><?php endif; ?>
          <?php if (! empty($cb['sumber'])): ?><div class="row"><dt>Sumber Data</dt><dd><?php echo $e($cb['sumber']); ?></dd></div><?php endif; ?>
        </dl>

        <?php if ($ada_titik): ?>
        <dl class="tab-pane" id="tab-geospasial">
          <div class="row"><dt>Latitude</dt><dd><?php echo $lat; ?></dd></div>
          <div class="row"><dt>Longitude</dt><dd><?php echo $lng; ?></dd></div>
          <div class="row"><dt>Koordinat</dt><dd><?php echo $lat; ?>, <?php echo $lng; ?></dd></div>
          <div class="row"><dt>Peta</dt><dd><a target="_blank" rel="noopener noreferrer" style="color:var(--gold-300);text-decoration:underline" href="https://www.google.com/maps/search/?api=1&query=<?php echo $lat; ?>,<?php echo $lng; ?>">Buka di Google Maps →</a></dd></div>
        </dl>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <div class="brand" style="margin-bottom:18px">
          <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Seal_of_Cilacap_Regency.svg?width=120" alt="" style="height:56px">
          <span><span class="brand-name" style="font-size:1.05rem">SIP GATUTKACA</span><br><span class="brand-sub">Sistem Informasi Penataan Ruang</span></span>
        </div>
        <p>Dinas Pekerjaan Umum dan Penataan Ruang Kabupaten Cilacap.</p>
      </div>
      <div>
        <h4>Layanan</h4>
        <ul>
          <li><a href="<?php echo base_url('cagar-budaya'); ?>">Cagar Budaya</a></li>
          <li><a href="<?php echo base_url('analisa-kerusakan'); ?>">Analisa Kerusakan</a></li>
          <li><a href="<?php echo base_url('spasial'); ?>">Peta Spasial</a></li>
        </ul>
      </div>
      <div>
        <h4>Kontak</h4>
        <ul>
          <li>Jl. MT. Haryono, Cilacap, Jawa Tengah</li>
          <li>Senin–Jumat · 08.00–15.30 WIB</li>
        </ul>
      </div>
    </div>
    <div class="credit"><span>© 2026 Pemerintah Kabupaten Cilacap</span></div>
  </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
(function(){
  var p=new URLSearchParams(location.search);
  document.documentElement.setAttribute('data-theme', p.get('theme')==='dark'?'dark':'light');

  var bar=document.getElementById("topbar");
  addEventListener("scroll",function(){ bar.classList.toggle("scrolled",scrollY>40); },{passive:true});
  var ub=document.getElementById("userMenuBtn"), up=document.getElementById("userMenuPanel");
  if(ub){ ub.addEventListener("click",function(){ var o=up.classList.toggle("open"); ub.setAttribute("aria-expanded",o); });
    document.addEventListener("click",function(e){ if(!up.contains(e.target)&&e.target!==ub&&!ub.contains(e.target))up.classList.remove("open"); }); }

<?php if ($ada_titik): ?>
  var lat=<?php echo json_encode($lat); ?>, lng=<?php echo json_encode($lng); ?>;
  var _gt="&x={x}&y={y}&z={z}",_go={maxZoom:20,subdomains:["mt0","mt1","mt2","mt3"],attribution:"&copy; Google"};
  var baseGmap    =L.tileLayer("https://{s}.google.com/vt/lyrs=m"+_gt,_go),
      baseOsm     =L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png",{maxZoom:19,attribution:"&copy; OpenStreetMap"}),
      baseGhybrid =L.tileLayer("https://{s}.google.com/vt/lyrs=y"+_gt,_go),
      baseGsat    =L.tileLayer("https://{s}.google.com/vt/lyrs=s"+_gt,_go),
      baseGterrain=L.tileLayer("https://{s}.google.com/vt/lyrs=p"+_gt,_go);
  var map=L.map("cbdMap",{layers:[baseGmap],scrollWheelZoom:true}).setView([lat,lng],16);
  var titik=L.circleMarker([lat,lng],{radius:8,color:"#fff",weight:2,fillColor:<?php echo json_encode($k_warna); ?>,fillOpacity:1})
    .addTo(map).bindPopup(<?php echo json_encode($judul); ?>);
  L.control.scale({imperial:false}).addTo(map);
  L.control.layers(
    {"Google Maps":baseGmap,"OpenStreetMap":baseOsm,"Google Hybrid":baseGhybrid,"Google Satelite":baseGsat,"Google Terrain":baseGterrain},
    {"Titik Objek":titik},
    {position:"bottomleft",collapsed:true}
  ).addTo(map);
  setTimeout(function(){ map.invalidateSize(); }, 250);

  document.querySelectorAll(".tab-btn").forEach(function(btn){
    btn.addEventListener("click",function(){
      document.querySelectorAll(".tab-btn").forEach(function(b){ b.classList.remove("active"); });
      document.querySelectorAll(".tab-pane").forEach(function(pp){ pp.classList.remove("active"); });
      btn.classList.add("active");
      var el=document.getElementById("tab-"+btn.dataset.tab);
      if(el){ el.classList.add("active"); setTimeout(function(){ map.invalidateSize(); },50); }
    });
  });
<?php endif; ?>
})();
</script>
</body>
</html>
