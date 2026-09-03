<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — Panel Admin · SIP Gatutkaca</title>
<link rel="icon" type="image/png" href="<?php echo base_url('assets/img/icon.png'); ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
<style>
:root{--gold-500:#C9A24B;--gold-300:#E4C87B;--gold-100:#F3E3B8;--display:'Marcellus',serif;--body:'Plus Jakarta Sans',system-ui,sans-serif}
html[data-theme="dark"]{--bg:#081826;--bg-alt:#0C2236;--surface:#0C2236;--surface-hi:#123249;--text:#F8F4EA;--muted:#B9C7D2;--line:rgba(201,162,75,.28);--head-bg:rgba(8,24,38,.94);--head-grad:rgba(8,24,38,.85);--foot:#050F19;--input:#0F2A40;--shadow:rgba(0,0,0,.5)}
html[data-theme="light"]{--bg:#FDFBF5;--bg-alt:#F6F1E3;--surface:#FFFFFF;--surface-hi:#FAF5E8;--text:#152A3B;--muted:#4E6070;--line:rgba(160,124,45,.35);--head-bg:rgba(253,251,245,.94);--head-grad:rgba(253,251,245,.85);--foot:#122536;--input:#FFFFFF;--shadow:rgba(21,42,59,.18);--gold-500:#A57E2C;--gold-300:#8F6C1F;--gold-100:#6E5314}
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:var(--body);background:var(--bg);color:var(--text);line-height:1.7;font-weight:300}
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
@media(max-width:860px){.dash-layout{flex-direction:column}.dash-sidebar{width:100%;flex:0 0 auto;height:auto;flex-direction:row;justify-content:space-between;align-items:center;border-right:none;border-bottom:1px solid var(--line);padding:0}.dash-sidebar nav{flex-direction:row;justify-content:center;flex-wrap:wrap}.dash-sidebar a{padding:14px 20px;border-left:none;border-bottom:3px solid transparent}.dash-sidebar a.active{border-left-color:transparent;border-bottom-color:var(--gold-500)}.dash-wrap{padding:0 24px}}
section{padding:52px 0 90px}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.7rem,3.2vw,2.4rem);line-height:1.2}
h3{font-family:var(--display);font-weight:400;font-size:1.4rem;color:var(--gold-300);letter-spacing:.04em;margin:0 0 6px}

.stat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(210px,1fr));gap:1px;background:var(--line);border:1px solid var(--line);margin-top:40px}
.stat{background:var(--surface);padding:26px 24px;transition:background .25s}
.stat:hover{background:var(--surface-hi)}
.stat .num{font-family:var(--display);font-size:2.4rem;line-height:1;color:var(--text)}
.stat .lbl{margin-top:10px;font-size:.72rem;letter-spacing:.16em;text-transform:uppercase;color:var(--muted)}
.stat .go{margin-top:6px;font-size:.72rem;color:var(--gold-300)}
.stat.hi .num{color:#E0526B}
.badge-baru{display:inline-block;margin-left:8px;background:#E0526B;color:#fff;font-size:.62rem;font-weight:600;letter-spacing:.06em;padding:1px 7px;border-radius:999px;vertical-align:2px}

.panel{background:var(--surface);border:1px solid var(--line);margin-top:44px;padding:30px 30px 12px}
.mini{display:flex;gap:16px;padding:16px 0;border-bottom:1px solid var(--line)}
.mini:last-child{border-bottom:none}
.mini .who{color:var(--text);font-weight:500;font-size:.9rem;min-width:150px;flex:0 0 auto}
.mini .txt{color:var(--muted);font-size:.85rem;flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.mini .tag{flex:0 0 auto;border:1px solid var(--line);padding:2px 10px;font-size:.62rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold-300)}
.tag.st-baru{color:#5FC2E0;border-color:#1E86A3}
.tag.st-ditinjau{color:#F0A048;border-color:#B4573B}
.tag.st-selesai{color:#6FCF97;border-color:#2EA84F}
.panel .more{display:inline-block;margin:16px 0;font-size:.74rem;letter-spacing:.14em;text-transform:uppercase;color:var(--gold-300)}
.panel-head{display:flex;justify-content:space-between;align-items:baseline;gap:16px;margin-bottom:6px}
.panel-head .more{margin:0}
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:44px}
.two-col .panel{margin-top:0}
@media(max-width:1080px){.two-col{grid-template-columns:1fr}}

.dist{margin-top:14px}
.dist-row{padding:14px 0}
.dist-row:not(:last-child){border-bottom:1px solid var(--line)}
.dist-top{display:flex;justify-content:space-between;align-items:baseline;gap:12px}
.dist-top .k{color:var(--text);font-size:.92rem}
.dist-top .v{font-family:var(--display);font-size:1.1rem;color:var(--text)}
.dist-bar{margin-top:8px;height:8px;border-radius:999px;background:var(--surface-hi);overflow:hidden}
.dist-bar i{display:block;height:100%;border-radius:999px;background:var(--gold-500);transition:width .3s}

.act{margin-top:8px}
.act-row{display:flex;gap:14px;padding:16px 0;align-items:flex-start}
.act-row:not(:last-child){border-bottom:1px solid var(--line)}
.act-av{flex:0 0 auto;width:40px;height:40px;border-radius:50%;background:var(--surface-hi);border:1px solid var(--line);display:grid;place-items:center;font-family:var(--display);color:var(--gold-300);font-size:1rem}
.act-body{min-width:0;flex:1}
.act-name{color:var(--text);font-weight:500;font-size:.9rem}
.act-name .reg{color:var(--muted);font-weight:300}
.act-line{margin-top:3px;font-size:.85rem;color:var(--muted)}
.act-badge{display:inline-block;background:var(--surface-hi);border:1px solid var(--line);color:var(--gold-300);font-size:.66rem;font-weight:600;letter-spacing:.06em;padding:2px 9px;border-radius:999px;text-transform:uppercase}
.act-time{margin-top:4px;font-size:.74rem;color:var(--muted)}

footer{background:var(--foot);color:#F8F4EA;padding:60px 0 30px;border-top:1px solid var(--line);margin-top:40px}
.foot-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr;gap:50px}
.foot-grid h4{font-family:var(--display);font-weight:400;letter-spacing:.14em;color:#E4C87B;margin-bottom:18px;font-size:1rem}
.foot-grid p,.foot-grid li{font-size:.88rem;color:#B9C7D2}
.foot-grid ul{list-style:none;display:grid;gap:10px}
.credit{margin-top:44px;padding-top:22px;border-top:1px solid rgba(185,199,210,.15);font-size:.72rem;color:rgba(185,199,210,.6)}
@media(max-width:980px){.foot-grid{grid-template-columns:1fr}}
</style>
</head>
<body>

<header id="topbar">
  <div class="wrap nav">
    <a class="brand" href="<?php echo base_url(); ?>" aria-label="Beranda SIP Gatutkaca">
      <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Seal_of_Cilacap_Regency.svg?width=120" alt="Lambang Kabupaten Cilacap">
      <span><span class="brand-name">SIP GATUTKACA</span><br><span class="brand-sub">Kabupaten Cilacap</span></span>
    </a>
    <div class="auth-actions">
      <a class="btn btn-ghost btn-sm" href="<?php echo base_url(); ?>" style="border:1px solid var(--line);padding:9px 18px;font-size:.68rem;letter-spacing:.14em;text-transform:uppercase;color:var(--text)">Beranda</a>
      <div class="user-menu">
        <button class="user-menu-btn" id="userMenuBtn" type="button" aria-expanded="false" aria-controls="userMenuPanel">
          <?php echo htmlspecialchars($nama_admin, ENT_QUOTES, 'UTF-8'); ?>
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
      <a href="<?php echo base_url('admin'); ?>" class="active">Dashboard</a>
      <a href="<?php echo base_url('admin/pengguna'); ?>">Kelola Pengguna</a>
      <a href="<?php echo base_url('admin/pengajuan'); ?>">Pengajuan PBG</a>
      <a href="<?php echo base_url('admin/bangunan'); ?>">Sebaran Bangunan</a>
      <a href="<?php echo base_url('admin/cagar-budaya'); ?>">Kelola Cagar Budaya</a>
      <a href="<?php echo base_url('admin/aturan'); ?>">Kelola Aturan</a>
      <a href="<?php echo base_url('admin/saran'); ?>">Saran &amp; FAQ</a>
    </nav>
    <nav>
      <a href="<?php echo base_url('login/keluar'); ?>" class="logout">Logout</a>
    </nav>
  </aside>
  <div class="dash-main">
<section style="padding-top:20px">
  <div class="dash-wrap">
    <p class="eyebrow">Panel Admin</p>
    <h2>Dashboard</h2>
    <p style="color:var(--muted);max-width:70ch;margin-top:14px">Ringkasan data SIP Gatutkaca. Klik kartu untuk membuka pengelolaannya.</p>

    <div class="stat-grid">
      <a class="stat" href="<?php echo base_url('admin/pengguna'); ?>">
        <div class="num"><?php echo number_format($stat['pengguna'], 0, ',', '.'); ?></div>
        <div class="lbl">Pengguna</div><div class="go">Kelola →</div>
      </a>
      <a class="stat" href="<?php echo base_url('admin/pengajuan'); ?>">
        <div class="num"><?php echo number_format($stat['pengajuan_pbg'], 0, ',', '.'); ?></div>
        <div class="lbl">Pengajuan PBG</div><div class="go">Lihat →</div>
      </a>
      <a class="stat" href="<?php echo base_url('admin/bangunan'); ?>">
        <div class="num"><?php echo number_format($stat['bangunan'], 0, ',', '.'); ?></div>
        <div class="lbl">Sebaran Bangunan</div><div class="go">Kelola →</div>
      </a>
      <a class="stat" href="<?php echo base_url('admin/cagar-budaya'); ?>">
        <div class="num"><?php echo number_format($stat['cagar_budaya'], 0, ',', '.'); ?></div>
        <div class="lbl">Cagar Budaya</div><div class="go">Kelola →</div>
      </a>
      <a class="stat" href="<?php echo base_url('admin/aturan'); ?>">
        <div class="num"><?php echo number_format($stat['regulasi'], 0, ',', '.'); ?></div>
        <div class="lbl">Pustaka Regulasi</div><div class="go">Kelola →</div>
      </a>
      <a class="stat <?php echo $saran_baru > 0 ? 'hi' : ''; ?>" href="<?php echo base_url('admin/saran'); ?>">
        <div class="num"><?php echo number_format($stat['saran'], 0, ',', '.'); ?><?php if ($saran_baru > 0): ?><span class="badge-baru"><?php echo (int) $saran_baru; ?> baru</span><?php endif; ?></div>
        <div class="lbl">Saran &amp; Masukan</div><div class="go">Kelola →</div>
      </a>
    </div>

    <?php $this->load->view('partials/dashboard_status_aktivitas', array(
      'status_label'         => $status_label,
      'distribusi'           => $distribusi,
      'aktivitas'            => $aktivitas,
      'aktivitas_more_url'   => base_url('admin/pengajuan'),
      'aktivitas_kosong_teks'=> 'Belum ada aktivitas permohonan.',
    )); ?>

    <div class="panel">
      <h3>Masukan Terbaru</h3>
      <?php if (empty($saran_terbaru)): ?>
        <p style="color:var(--muted);padding:14px 0">Belum ada masukan yang masuk.</p>
      <?php else: foreach ($saran_terbaru as $s): ?>
        <div class="mini">
          <span class="who"><?php echo htmlspecialchars($s['nama'], ENT_QUOTES, 'UTF-8'); ?></span>
          <span class="txt"><?php echo htmlspecialchars(($s['topik'] ? $s['topik'] . ' — ' : '') . $s['pesan'], ENT_QUOTES, 'UTF-8'); ?></span>
          <span class="tag st-<?php echo htmlspecialchars($s['status'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars(ucfirst($s['status']), ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
      <?php endforeach; endif; ?>
      <a class="more" href="<?php echo base_url('admin/saran'); ?>">Buka Kotak Masuk →</a>
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
          <span><span class="brand-name" style="font-size:1.05rem">SIP GATUTKACA</span><br><span class="brand-sub">Sistem Informasi Penataan Ruang</span></span>
        </div>
        <p>Dinas Pekerjaan Umum dan Penataan Ruang Kabupaten Cilacap.</p>
      </div>
      <div><h4>Pintasan</h4><ul>
        <li><a href="<?php echo base_url('admin/saran'); ?>">Saran &amp; FAQ</a></li>
        <li><a href="<?php echo base_url('admin/cagar-budaya'); ?>">Kelola Cagar Budaya</a></li>
        <li><a href="<?php echo base_url('admin/bangunan'); ?>">Sebaran Bangunan</a></li>
      </ul></div>
      <div><h4>Kontak</h4><ul>
        <li>Jl. MT. Haryono, Cilacap, Jawa Tengah</li>
        <li>Senin–Jumat · 08.00–15.30 WIB</li>
      </ul></div>
    </div>
    <div class="credit">© 2026 Pemerintah Kabupaten Cilacap</div>
  </div>
</footer>

<script>
(function(){
  var p=new URLSearchParams(location.search);
  document.documentElement.setAttribute('data-theme', p.get('theme')==='dark'?'dark':'light');
})();
var bar=document.getElementById('topbar');
addEventListener('scroll',function(){bar.classList.toggle('scrolled',scrollY>40)},{passive:true});
var ub=document.getElementById('userMenuBtn'),up=document.getElementById('userMenuPanel');
ub.addEventListener('click',function(){var o=up.classList.toggle('open');ub.setAttribute('aria-expanded',o);});
document.addEventListener('click',function(e){if(!up.contains(e.target)&&e.target!==ub&&!ub.contains(e.target))up.classList.remove('open');});
</script>
</body>
</html>
