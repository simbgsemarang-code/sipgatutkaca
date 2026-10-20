<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Massa Bangunan — Panel Admin · SIP Gatutkaca</title>
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

.toolbar{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-top:44px}
.toolbar input[type=search]{background:var(--input);border:1px solid var(--line);color:var(--text);padding:12px 16px;font-family:var(--body);font-size:.85rem;min-width:260px}
.toolbar input[type=search]:focus{outline:1px solid var(--gold-500);border-color:var(--gold-500)}

table{width:100%;border-collapse:collapse;margin-top:26px;font-size:.88rem}
th{font-family:var(--display);font-weight:400;letter-spacing:.1em;text-transform:uppercase;font-size:.72rem;color:var(--gold-300);text-align:left;padding:14px;border-bottom:1px solid var(--gold-500)}
td{padding:14px;border-bottom:1px solid var(--line);color:var(--muted);vertical-align:top}
td:first-child{color:var(--text);font-weight:500}
.tag{display:inline-block;border:1px solid var(--line);padding:3px 12px;font-size:.66rem;letter-spacing:.14em;text-transform:uppercase;color:var(--gold-300)}
.tag-draf{color:#F0A048;border-color:#B4573B}
.tag-verifikasi_dokumen{color:#5FC2E0;border-color:#1E86A3}
.tag-perbaikan_dokumen{color:#E0526B;border-color:#E0526B}
.tag-perbaikan_dokumen_konsultasi{color:#E0526B;border-color:#E0526B}
.tag-menunggu_jadwal_konsultasi{color:#6FCF97;border-color:#2EA84F}
.tag-disetujui_tpa{color:#6FCF97;border-color:#2EA84F}
.no-reg{font-size:.72rem;color:var(--muted)}
.bidang-progres{margin-top:6px;font-size:.68rem;letter-spacing:.02em;color:var(--muted)}

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
.btn-gold{background:linear-gradient(135deg,#C9A24B,#E4C87B);color:#081826;font-weight:600}
.btn-gold:hover{filter:brightness(1.08)}
.form-card{background:var(--surface);border:1px solid var(--line);padding:34px 38px;max-width:760px;margin-top:30px}
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:20px}
@media(max-width:640px){.grid2{grid-template-columns:1fr}}
.fld{margin-bottom:18px}
.fld label{display:block;font-size:.7rem;letter-spacing:.22em;text-transform:uppercase;color:var(--muted);margin-bottom:7px}
.fld input,.fld select,.fld textarea{width:100%;background:var(--input);border:1px solid var(--line);color:var(--text);padding:12px 14px;font-family:var(--body);font-size:.92rem}
.fld input:focus,.fld select:focus,.fld textarea:focus{outline:1px solid var(--gold-500);border-color:var(--gold-500)}
.fld .req{color:#E0526B}
#pickMap{height:300px;width:100%;border:1px solid var(--line);margin-top:6px;background:#dfeee2}
.alertbox{padding:14px 18px;margin-top:24px;font-size:.88rem;border:1px solid}
.massa-sub{margin-top:10px;color:var(--muted);font-size:.9rem;max-width:80ch}
.massa-sub strong{color:var(--text);font-weight:600}
.massa-stat{display:flex;gap:12px;flex-wrap:wrap;margin-top:22px}
.massa-stat .pill{border:1px solid var(--line);background:var(--surface);padding:10px 18px;font-size:.78rem;letter-spacing:.06em;color:var(--muted)}
.massa-stat .pill b{display:block;font-family:var(--display);font-weight:400;font-size:1.25rem;letter-spacing:.02em;color:var(--gold-300)}
.massa-tabel td.num,.massa-tabel th.num{text-align:right;white-space:nowrap}
.massa-tabel .aksi{display:flex;gap:8px;flex-wrap:wrap;align-items:flex-start}
.massa-kosong{margin-top:26px;border:1px dashed var(--line);padding:30px;text-align:center;color:var(--muted);font-size:.9rem}
[hidden]{display:none!important}
.massa-foto{width:84px;height:64px;object-fit:cover;border:1px solid var(--line);border-radius:8px;display:block;background:var(--surface-hi)}.massa-foto-kosong{width:84px;height:64px;border:1px dashed var(--line);border-radius:8px;display:grid;place-items:center;font-size:.62rem;letter-spacing:.1em;text-transform:uppercase;color:var(--muted)}
.foto-pratinjau{display:flex;gap:14px;align-items:center;flex-wrap:wrap;margin-bottom:10px}.foto-pratinjau img{height:92px;width:auto;max-width:160px;border:1px solid var(--line);border-radius:8px;object-fit:cover}
.form-card h3{font-family:var(--display);font-weight:400;font-size:1.25rem;margin-bottom:20px}
.form-card .fld .hint{font-size:.72rem;color:var(--muted);margin-top:5px}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
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
  <?php $this->load->view('partials/sidebar', array('role'=>'admin','aktif'=>'bangunan')); ?>
  <div class="dash-main">
<?php
$warna_kondisi = array('1' => '#2EA84F', '2' => '#F2C230', '3' => '#D9822B', '4' => '#C0392B');
$e = function ($t) { return htmlspecialchars((string) $t, ENT_QUOTES, 'UTF-8'); };
$o = is_array($old) ? $old : array();
$f_val = function ($k, $d = '') use ($o) { return isset($o[$k]) ? $o[$k] : $d; };
$total_luas = 0.0; $total_lantai = 0;
foreach ($daftar as $m) { $total_luas += (float) $m['luas_m2']; $total_lantai += (int) $m['jumlah_lantai']; }
$ada_old_edit = ((int) $f_val('massa_id', 0)) > 0;
$foto_src = function ($nilai) { $nilai = (string) $nilai; if ($nilai === '') return ''; return preg_match('#^https?://#i', $nilai) ? $nilai : base_url('assets/foto-bangunan/' . rawurlencode($nilai)); };
?>
<section style="padding-top:20px">
  <div class="dash-wrap">
    <p class="eyebrow"><a href="<?php echo base_url('admin/bangunan'); ?>" style="color:var(--gold-500);text-decoration:underline">← Kembali ke Daftar Sebaran Bangunan</a></p>
    <h2>Massa Bangunan</h2>
    <p class="massa-sub"><strong><?php echo $e($row['nama_bangunan']); ?></strong><?php echo !empty($row['opd']) ? ' · ' . $e($row['opd']) : ''; ?><?php echo !empty($row['alamat']) ? '<br>' . $e($row['alamat']) : ''; ?></p>

    <?php if (!empty($sukses)): ?>
      <div class="alertbox" style="border-color:#2EA84F;background:rgba(46,168,79,.12);color:#2EA84F"><?php echo $e($sukses); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alertbox" style="border-color:#E0526B;background:rgba(224,82,107,.12);color:#E0526B"><?php echo $e($error); ?></div>
    <?php endif; ?>

    <?php if (!$tabel_ok): ?>
      <div class="alertbox" style="border-color:#E0526B;background:rgba(224,82,107,.12);color:#E0526B">Tabel <code>bangunan_massa</code> belum ada dan tidak dapat dibuat otomatis. Jalankan <code>database/bangunan_massa.sql</code> di phpMyAdmin, lalu muat ulang halaman ini.</div>
    <?php else: ?>

    <div class="massa-stat">
      <div class="pill"><b><?php echo count($daftar); ?></b>massa</div>
      <div class="pill"><b><?php echo $total_luas > 0 ? $e(number_format($total_luas, 2, ',', '.')) : '—'; ?></b>total luas (m²)</div>
      <div class="pill"><b><?php echo $total_lantai > 0 ? (int) $total_lantai : '—'; ?></b>total lantai (semua massa)</div>
    </div>

    <?php if (empty($daftar)): ?>
      <div class="massa-kosong">Belum ada massa untuk bangunan ini. Tambahkan massa pertama lewat formulir di bawah.</div>
    <?php else: ?>
      <table class="massa-tabel">
        <thead><tr><th>Foto</th><th>Nama Massa</th><th class="num">Lantai</th><th class="num">Luas (m²)</th><th>Kondisi</th><th class="num">Tahun</th><th>Keterangan</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($daftar as $m): $wk = $warna_kondisi[$m['kondisi']]; ?>
          <?php $fs = $foto_src(isset($m['foto']) ? $m['foto'] : ''); ?>
          <tr>
            <td><?php if ($fs !== ''): ?><a href="<?php echo $e($fs); ?>" target="_blank" rel="noopener" title="Lihat foto"><img class="massa-foto" src="<?php echo $e($fs); ?>" alt="Foto <?php echo $e($m['nama_massa']); ?>" loading="lazy"></a><?php else: ?><span class="massa-foto-kosong">Tanpa foto</span><?php endif; ?></td>
            <td><?php echo $e($m['nama_massa']); ?></td>
            <td class="num"><?php echo $m['jumlah_lantai'] !== null ? (int) $m['jumlah_lantai'] : '—'; ?></td>
            <td class="num"><?php echo $m['luas_m2'] !== null ? $e(number_format((float) $m['luas_m2'], 2, ',', '.')) : '—'; ?></td>
            <td><span class="tag" style="color:<?php echo $wk; ?>;border-color:<?php echo $wk; ?>"><?php echo $e(isset($kondisi_label[$m['kondisi']]) ? $kondisi_label[$m['kondisi']] : $m['kondisi']); ?></span></td>
            <td class="num"><?php echo $m['tahun_dibangun'] !== null ? (int) $m['tahun_dibangun'] : '—'; ?></td>
            <td style="font-size:.82rem"><?php echo $m['keterangan'] !== null && $m['keterangan'] !== '' ? $e($m['keterangan']) : '—'; ?></td>
            <td>
              <div class="aksi">
                <button type="button" class="btn btn-ghost btn-xs" data-ubah="<?php echo $e(json_encode(array('id' => (int) $m['id'], 'nama_massa' => $m['nama_massa'], 'jumlah_lantai' => $m['jumlah_lantai'], 'luas_m2' => $m['luas_m2'], 'kondisi' => $m['kondisi'], 'tahun_dibangun' => $m['tahun_dibangun'], 'keterangan' => $m['keterangan'], 'foto_src' => $fs))); ?>">Ubah</button>
                <form action="<?php echo base_url('admin/bangunan-massa-hapus/' . (int) $row['id'] . '/' . (int) $m['id']); ?>" method="post" style="margin:0" onsubmit="return confirm('Hapus massa &quot;<?php echo $e(addslashes($m['nama_massa'])); ?>&quot;?');">
                  <button type="submit" class="btn btn-xs" style="border:1px solid #E0526B;color:#E0526B;background:transparent;cursor:pointer">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <form class="form-card" method="post" enctype="multipart/form-data" action="<?php echo base_url('admin/bangunan-massa-simpan/' . (int) $row['id']); ?>" id="fmassa">
      <h3 id="fmassaJudul"><?php echo $ada_old_edit ? 'Ubah Massa' : 'Tambah Massa'; ?></h3>
      <input type="hidden" name="massa_id" id="fMassaId" value="<?php echo (int) $f_val('massa_id', 0); ?>">
      <div class="grid2">
        <div class="fld"><label for="fNama">Nama Massa <span class="req">*</span></label><input type="text" name="nama_massa" id="fNama" maxlength="150" required placeholder="mis. Massa A — Ruang Kelas" value="<?php echo $e($f_val('nama_massa')); ?>"></div>
        <div class="fld"><label for="fKondisi">Kondisi <span class="req">*</span></label>
          <select name="kondisi" id="fKondisi" required>
            <?php foreach ($kondisi_label as $kk => $vv): ?>
              <option value="<?php echo $kk; ?>" <?php echo (string) $f_val('kondisi', '1') === (string) $kk ? 'selected' : ''; ?>><?php echo $e($vv); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="grid2">
        <div class="fld"><label for="fLantai">Jumlah Lantai</label><input type="number" name="jumlah_lantai" id="fLantai" min="0" max="200" step="1" placeholder="mis. 2" value="<?php echo $e($f_val('jumlah_lantai')); ?>"></div>
        <div class="fld"><label for="fLuas">Luas Bangunan (m²)</label><input type="text" inputmode="decimal" name="luas_m2" id="fLuas" placeholder="mis. 245,5" value="<?php echo $e($f_val('luas_m2')); ?>"></div>
      </div>
      <div class="grid2">
        <div class="fld"><label for="fTahun">Tahun Dibangun</label><input type="number" name="tahun_dibangun" id="fTahun" min="1800" max="<?php echo (int) date('Y') + 1; ?>" step="1" placeholder="mis. 1998" value="<?php echo $e($f_val('tahun_dibangun')); ?>"></div>
        <div class="fld"><label for="fKet">Keterangan</label><input type="text" name="keterangan" id="fKet" maxlength="255" placeholder="Fungsi atau catatan massa" value="<?php echo $e($f_val('keterangan')); ?>"></div>
      </div>
      <div class="fld">
        <label for="fFoto">Foto Massa</label>
        <div class="foto-pratinjau" id="fFotoSaatIni" hidden>
          <img src="" alt="Foto massa saat ini" id="fFotoImg">
          <label style="display:flex;align-items:center;gap:8px;font-size:.78rem;letter-spacing:0;text-transform:none;margin:0;color:var(--text)"><input type="checkbox" name="hapus_foto" value="1" id="fHapusFoto" style="width:auto"> Hapus foto ini</label>
        </div>
        <div class="foto-pratinjau" id="fFotoBaru" hidden><img src="" alt="Pratinjau foto baru" id="fFotoBaruImg"><span class="hint" style="margin:0">Pratinjau foto yang akan diunggah</span></div>
        <input type="file" name="foto_file" id="fFoto" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        <p class="hint" id="fFotoHint">JPG / PNG / WebP, maksimal 100 MB.</p>
      </div>
      <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:6px">
        <button type="submit" class="btn btn-gold btn-sm" id="fmassaSimpan"><?php echo $ada_old_edit ? 'Simpan Perubahan' : 'Tambah Massa'; ?></button>
        <button type="button" class="btn btn-ghost btn-sm" id="fmassaBatal" <?php echo $ada_old_edit ? '' : 'hidden'; ?>>Batal Ubah</button>
      </div>
    </form>
    <script>
    (function(){
      var f=document.getElementById('fmassa'); if(!f)return;
      var judul=document.getElementById('fmassaJudul'), simpan=document.getElementById('fmassaSimpan'), batal=document.getElementById('fmassaBatal');
      var fotoBox=document.getElementById('fFotoSaatIni'), fotoImg=document.getElementById('fFotoImg'), hapusFoto=document.getElementById('fHapusFoto'), fotoInput=document.getElementById('fFoto'), fotoBaru=document.getElementById('fFotoBaru'), fotoBaruImg=document.getElementById('fFotoBaruImg'), fotoHint=document.getElementById('fFotoHint');
      function setFoto(src){ hapusFoto.checked=false; fotoInput.value=''; fotoBaru.hidden=true; if(src){ fotoImg.src=src; fotoBox.hidden=false; fotoHint.textContent='JPG / PNG / WebP, maksimal 100 MB. Biarkan kosong kalau tidak ingin mengganti.'; } else { fotoImg.removeAttribute('src'); fotoBox.hidden=true; fotoHint.textContent='JPG / PNG / WebP, maksimal 100 MB.'; } }
      fotoInput.addEventListener('change',function(){ var fl=fotoInput.files&&fotoInput.files[0]; if(fl&&fl.type.indexOf('image/')===0){ fotoBaruImg.src=URL.createObjectURL(fl); fotoBaru.hidden=false; } else { fotoBaru.hidden=true; } });
      var F={id:document.getElementById('fMassaId'),nama:document.getElementById('fNama'),kondisi:document.getElementById('fKondisi'),lantai:document.getElementById('fLantai'),luas:document.getElementById('fLuas'),tahun:document.getElementById('fTahun'),ket:document.getElementById('fKet')};
      function mode(ubah){ judul.textContent=ubah?'Ubah Massa':'Tambah Massa'; simpan.textContent=ubah?'Simpan Perubahan':'Tambah Massa'; batal.hidden=!ubah; }
      function nol(v){ return v===null||v===undefined?'':v; }
      document.querySelectorAll('[data-ubah]').forEach(function(b){
        b.addEventListener('click',function(){
          var d=JSON.parse(b.getAttribute('data-ubah'));
          F.id.value=d.id; F.nama.value=d.nama_massa; F.kondisi.value=d.kondisi; F.lantai.value=nol(d.jumlah_lantai);
          F.luas.value=d.luas_m2===null?'':String(d.luas_m2).replace('.',','); F.tahun.value=nol(d.tahun_dibangun); F.ket.value=nol(d.keterangan);
          setFoto(d.foto_src||''); mode(true); f.scrollIntoView({behavior:'smooth',block:'center'}); F.nama.focus();
        });
      });
      batal.addEventListener('click',function(){ f.reset(); F.id.value='0'; F.nama.value=''; F.lantai.value=''; F.luas.value=''; F.tahun.value=''; F.ket.value=''; F.kondisi.value='1'; setFoto(''); mode(false); });
    })();
    </script>
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
</script>
</body>
</html>
