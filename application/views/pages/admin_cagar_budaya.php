<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Cagar Budaya — Panel Admin · SIP Gatutkaca</title>
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
.btn{display:inline-block;padding:15px 34px;font-size:.78rem;letter-spacing:.26em;text-transform:uppercase;transition:.3s;cursor:pointer;border:none;font-family:var(--body)}
.btn-ghost{border:1px solid var(--line);color:var(--text);background:transparent}
.btn-ghost:hover{border-color:#C9A24B;color:#E4C87B}
.btn-gold{background:linear-gradient(135deg,#C9A24B,#E4C87B);color:#081826;font-weight:600}
.btn-gold:hover{filter:brightness(1.08)}
.btn-sm{padding:11px 26px;font-size:.72rem;letter-spacing:.2em}
.btn-xs{padding:8px 16px;font-size:.68rem;letter-spacing:.12em}
.aksi-cell{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
section{padding:60px 0 100px}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.8rem,3.4vw,2.6rem);line-height:1.2;max-width:32ch}
.toolbar{display:flex;align-items:end;gap:16px;flex-wrap:wrap;margin-top:44px}
.toolbar input[type=search],.toolbar select{background:var(--input);border:1px solid var(--line);color:var(--text);padding:12px 16px;font-family:var(--body);font-size:.85rem}
.toolbar input[type=search]:focus,.toolbar select:focus{outline:1px solid var(--gold-500);border-color:var(--gold-500)}
.toolbar .lbl{display:block;font-size:.7rem;letter-spacing:.2em;text-transform:uppercase;color:var(--muted);margin-bottom:6px}
table{width:100%;border-collapse:collapse;margin-top:26px;font-size:.88rem}
th{font-family:var(--display);font-weight:400;letter-spacing:.1em;text-transform:uppercase;font-size:.72rem;color:var(--gold-300);text-align:left;padding:14px;border-bottom:1px solid var(--gold-500)}
td{padding:14px;border-bottom:1px solid var(--line);color:var(--muted);vertical-align:top}
td:first-child{color:var(--text);font-weight:500}
.tag{display:inline-block;border:1px solid var(--line);padding:3px 12px;font-size:.66rem;letter-spacing:.14em;text-transform:uppercase;color:var(--gold-300);white-space:nowrap}
.no-reg{font-size:.72rem;color:var(--muted)}
footer{background:var(--foot);color:#F8F4EA;padding:66px 0 32px;border-top:1px solid var(--line);margin-top:40px}
.foot-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr;gap:50px}
.foot-grid h4{font-family:var(--display);font-weight:400;letter-spacing:.14em;color:#E4C87B;margin-bottom:18px;font-size:1rem}
.foot-grid p,.foot-grid li{font-size:.88rem;color:#B9C7D2}
.foot-grid ul{list-style:none;display:grid;gap:10px}
.credit{margin-top:50px;padding-top:22px;border-top:1px solid rgba(185,199,210,.15);font-size:.72rem;color:rgba(185,199,210,.6);letter-spacing:.06em}
@media(max-width:980px){.foot-grid{grid-template-columns:1fr}table{display:block;overflow-x:auto}}
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
      <a class="btn btn-ghost btn-sm" href="<?php echo base_url(); ?>">Beranda</a>
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
      <a href="<?php echo base_url('admin/pengguna'); ?>">Kelola Pengguna</a>
      <a href="<?php echo base_url('admin/pengajuan'); ?>">Pengajuan PBG</a>
      <a href="<?php echo base_url('admin/pengajuan-slf'); ?>">Pengajuan SLF</a>
      <a href="<?php echo base_url('admin/bangunan'); ?>">Sebaran Bangunan</a>
      <a href="<?php echo base_url('admin/cagar-budaya'); ?>" class="active">Kelola Cagar Budaya</a>
      <a href="<?php echo base_url('admin/saran'); ?>">Saran &amp; FAQ</a>
    </nav>
    <nav>
      <a href="<?php echo base_url('login/keluar'); ?>" class="logout">Logout</a>
    </nav>
  </aside>
  <div class="dash-main">
<section style="padding-top:20px">
  <div class="dash-wrap">
    <div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap">
      <div>
        <p class="eyebrow">Panel Admin</p>
        <h2>Kelola Cagar Budaya</h2>
        <p style="color:var(--muted);max-width:74ch;margin-top:14px">Objek cagar budaya &amp; objek diduga cagar budaya yang tampil pada halaman publik <a href="<?php echo base_url('cagar-budaya'); ?>" style="color:var(--gold-300);text-decoration:underline">Cagar Budaya</a> (tabel + peta). Tambah, ubah, atau hapus di sini — perubahan langsung tampil.</p>
      </div>
      <a href="<?php echo base_url('admin/cagar-budaya-tambah'); ?>" class="btn btn-gold btn-sm" style="flex:0 0 auto">+ Tambah Objek</a>
    </div>

    <?php if (!empty($sukses)): ?>
      <div style="margin-top:28px;padding:14px 18px;border:1px solid #2EA84F;background:rgba(46,168,79,.12);color:#2EA84F;font-size:.88rem"><?php echo htmlspecialchars($sukses, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div style="margin-top:28px;padding:14px 18px;border:1px solid #E0526B;background:rgba(224,82,107,.12);color:#E0526B;font-size:.88rem"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <form method="get" action="<?php echo base_url('admin/cagar-budaya'); ?>" class="toolbar">
      <div style="flex:1 1 220px"><span class="lbl">Cari</span><input type="search" name="q" value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Nama, kecamatan, alamat…" style="width:100%"></div>
      <div><span class="lbl">Kategori</span>
        <select name="kategori" style="min-width:150px">
          <option value="">— Semua —</option>
          <?php foreach ($cb_kategori as $k): ?><option <?php echo $kategori === $k ? 'selected' : ''; ?>><?php echo $k; ?></option><?php endforeach; ?>
        </select>
      </div>
      <div><span class="lbl">Status</span>
        <select name="status" style="min-width:190px">
          <option value="">— Semua —</option>
          <?php foreach ($cb_status as $s): ?><option <?php echo $status === $s ? 'selected' : ''; ?>><?php echo $s; ?></option><?php endforeach; ?>
        </select>
      </div>
      <button class="btn btn-ghost btn-sm" type="submit">Terapkan</button>
      <?php if ($q !== '' || $kategori !== '' || $status !== ''): ?>
        <a class="btn btn-ghost btn-sm" href="<?php echo base_url('admin/cagar-budaya'); ?>">Reset</a>
      <?php endif; ?>
    </form>

    <p class="eyebrow" style="margin-top:20px;margin-bottom:0"><?php echo (int) $total; ?> objek<?php echo ($q !== '' || $kategori !== '' || $status !== '') ? ' (hasil saring)' : ''; ?> · halaman <?php echo (int) $page; ?>/<?php echo (int) $total_page; ?></p>

    <table>
      <thead><tr><th>Nama Objek</th><th>Kategori</th><th>Kecamatan / Kelurahan</th><th>Tahun</th><th>Status</th><th>Koordinat</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php if (empty($daftar)): ?>
          <tr><td colspan="7">Tidak ada objek yang cocok.</td></tr>
        <?php else: foreach ($daftar as $r):
          $ada = ($r['latitude'] !== NULL && $r['longitude'] !== NULL); ?>
          <tr>
            <td><?php echo htmlspecialchars($r['nama'], ENT_QUOTES, 'UTF-8'); ?><?php if (!empty($r['no_sk'])): ?><br><span class="no-reg"><?php echo htmlspecialchars($r['no_sk'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?></td>
            <td><span class="tag"><?php echo htmlspecialchars($r['kategori'], ENT_QUOTES, 'UTF-8'); ?></span></td>
            <td style="font-size:.82rem"><?php echo htmlspecialchars(trim(($r['kecamatan'] ?: '—') . ' / ' . ($r['kelurahan'] ?: '—'), ' /'), ENT_QUOTES, 'UTF-8'); ?></td>
            <td style="font-size:.82rem"><?php echo htmlspecialchars($r['tahun'] ?: '—', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><span class="tag"><?php echo htmlspecialchars($r['status'], ENT_QUOTES, 'UTF-8'); ?></span></td>
            <td class="no-reg"><?php echo $ada ? htmlspecialchars(round((float) $r['latitude'], 5) . ', ' . round((float) $r['longitude'], 5), ENT_QUOTES, 'UTF-8') : '—'; ?></td>
            <td class="aksi-cell">
              <a href="<?php echo base_url('admin/cagar-budaya-ubah/' . (int) $r['id']); ?>" class="btn btn-ghost btn-xs">Ubah</a>
              <form action="<?php echo base_url('admin/cagar-budaya-hapus/' . (int) $r['id']); ?>" method="post" style="margin:0" onsubmit="return confirm('Hapus objek &quot;<?php echo htmlspecialchars(addslashes($r['nama']), ENT_QUOTES, 'UTF-8'); ?>&quot;?');">
                <button type="submit" class="btn btn-xs" style="border:1px solid #E0526B;color:#E0526B;background:transparent;cursor:pointer">Hapus</button>
              </form>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>

    <?php if ($total_page > 1): ?>
      <?php
      $qs = function ($p) use ($q, $kategori, $status) {
        $par = array('hal' => $p);
        if ($q !== '')        $par['q'] = $q;
        if ($kategori !== '') $par['kategori'] = $kategori;
        if ($status !== '')   $par['status'] = $status;
        return '?' . http_build_query($par);
      };
      $start = max(1, $page - 3); $end = min($total_page, $page + 3);
      ?>
      <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:26px;align-items:center">
        <?php if ($page > 1): ?><a class="btn btn-ghost btn-xs" href="<?php echo base_url('admin/cagar-budaya') . $qs($page - 1); ?>">← Sebelumnya</a><?php endif; ?>
        <?php for ($i = $start; $i <= $end; $i++): ?>
          <a class="btn btn-xs <?php echo $i === $page ? 'btn-gold' : 'btn-ghost'; ?>" href="<?php echo base_url('admin/cagar-budaya') . $qs($i); ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
        <?php if ($page < $total_page): ?><a class="btn btn-ghost btn-xs" href="<?php echo base_url('admin/cagar-budaya') . $qs($page + 1); ?>">Berikutnya →</a><?php endif; ?>
      </div>
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
          <span><span class="brand-name" style="font-size:1.05rem">SIP GATUTKACA</span><br><span class="brand-sub">Sistem Informasi Penataan Ruang</span></span>
        </div>
        <p>Dinas Pekerjaan Umum dan Penataan Ruang Kabupaten Cilacap.</p>
      </div>
      <div><h4>Layanan</h4><ul>
        <li><a href="<?php echo base_url('cagar-budaya'); ?>">Cagar Budaya</a></li>
        <li><a href="<?php echo base_url('analisa-kerusakan'); ?>">Analisa Kerusakan</a></li>
        <li><a href="<?php echo base_url('spasial'); ?>">Peta Spasial</a></li>
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
