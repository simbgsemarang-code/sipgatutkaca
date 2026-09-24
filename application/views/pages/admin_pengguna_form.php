<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $editing ? 'Edit Pengguna' : 'Tambah Pengguna'; ?> — SIP Gatutkaca · Kabupaten Cilacap</title>
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

section{padding:60px 0 100px}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.8rem,3.4vw,2.6rem);line-height:1.2;max-width:24ch}

.form-card{background:var(--surface);border:1px solid var(--line);padding:46px;max-width:560px;margin-top:30px}
.field{margin-bottom:22px}
label{display:block;font-size:.72rem;letter-spacing:.24em;text-transform:uppercase;color:var(--muted);margin-bottom:9px}
input,select,textarea{width:100%;background:var(--input);border:1px solid var(--line);color:var(--text);padding:13px 15px;font-family:var(--body);font-size:.92rem}
input:focus,select:focus,textarea:focus{outline:1px solid var(--gold-500);border-color:var(--gold-500)}
input:disabled,select:disabled{opacity:.6;cursor:not-allowed}
.note{font-size:.78rem;color:var(--muted);margin-top:16px}
.alert{padding:16px 20px;margin-bottom:26px;font-size:.88rem;border:1px solid}
.alert-err{background:rgba(224,82,107,.12);border-color:#E0526B;color:#F3AEB9}
.form-actions{display:flex;gap:14px;align-items:center;margin-top:8px}

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
          <a href="<?php echo base_url('pengaturan'); ?>" role="menuitem">Pengaturan Akun</a>
          <a href="<?php echo base_url('login/keluar'); ?>" role="menuitem" class="logout">Logout</a>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="dash-layout">
  <aside class="dash-sidebar">
    <nav>
      <a href="<?php echo base_url('admin'); ?>">Dashboard</a>
      <a href="<?php echo base_url('admin/pengguna'); ?>" class="active">Kelola Pengguna</a>
      <a href="<?php echo base_url('admin/pengajuan'); ?>">Pengajuan PBG</a>
      <a href="<?= base_url('admin_itr') ?>">Pengajuan ITR</a>
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
    <p class="eyebrow"><a href="<?php echo base_url('admin/pengguna'); ?>" style="color:var(--gold-500);text-decoration:underline">&larr; Kembali ke Kelola Pengguna</a></p>
    <h2><?php echo $editing ? 'Edit Pengguna' : 'Tambah Pengguna'; ?></h2>

    <?php if (!empty($error)): ?>
      <div class="alert alert-err" style="margin-top:26px"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <?php
      $nilai = function ($kunci) use ($editing, $old) {
        if (isset($old[$kunci])) { return $old[$kunci]; }
        if ($editing && isset($editing[$kunci])) { return $editing[$kunci]; }
        return '';
      };
      $adalah_akun_sendiri = $editing && (int) $editing['id'] === (int) $this->session->userdata('user_id');
      $peran_terpilih = $nilai('role');
    ?>
    <form class="form-card" action="<?php echo current_url(); ?>" method="post">
      <input type="hidden" name="record_id" value="<?php echo $editing ? (int) $editing['id'] : 0; ?>">
      <div class="field">
        <label for="a-nama">Nama Lengkap</label>
        <input id="a-nama" name="nama" type="text" required value="<?php echo htmlspecialchars($nilai('nama'), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="field">
        <label for="a-email">Surel</label>
        <input id="a-email" name="email" type="email" required value="<?php echo htmlspecialchars($nilai('email'), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="field">
        <label for="a-nik">NIK <span style="text-transform:none;letter-spacing:0">(opsional)</span></label>
        <input id="a-nik" name="nik" type="text" value="<?php echo htmlspecialchars($nilai('nik'), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="field">
        <label for="a-no-hp">No. HP/WhatsApp <span style="text-transform:none;letter-spacing:0">(opsional, untuk kirim kredensial)</span></label>
        <input id="a-no-hp" name="no_hp" type="text" placeholder="08xxxxxxxxxx" value="<?php echo htmlspecialchars($nilai('no_hp'), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="field">
        <label for="a-password"><?php echo $editing ? 'Kata Sandi Baru (opsional)' : 'Kata Sandi Awal'; ?></label>
        <input id="a-password" name="password" type="password" <?php echo $editing ? '' : 'required'; ?> minlength="8" placeholder="<?php echo $editing ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter'; ?>" autocomplete="new-password">
      </div>
      <div class="field">
        <label for="a-role">Jenis Pengguna</label>
        <select id="a-role" name="role" required <?php echo $adalah_akun_sendiri ? 'disabled' : ''; ?>>
          <option value="" disabled <?php echo $peran_terpilih === '' ? 'selected' : ''; ?>>— Pilih jenis pengguna —</option>
          <option value="admin" <?php echo $peran_terpilih === 'admin' ? 'selected' : ''; ?>>Admin</option>
          <option value="pu" <?php echo $peran_terpilih === 'pu' ? 'selected' : ''; ?>>PU — Pekerjaan Umum</option>
          <option value="tpa_arsitek" <?php echo $peran_terpilih === 'tpa_arsitek' ? 'selected' : ''; ?>>TPA Arsitek — Tim Profesi Ahli Arsitektur</option>
          <option value="tpa_struktur" <?php echo $peran_terpilih === 'tpa_struktur' ? 'selected' : ''; ?>>TPA Struktur — Tim Profesi Ahli Struktur</option>
          <option value="tpa_mep" <?php echo $peran_terpilih === 'tpa_mep' ? 'selected' : ''; ?>>TPA MEP — Tim Profesi Ahli Mekanikal, Elektrikal, Plambing</option>
        </select>
        <?php if ($adalah_akun_sendiri): ?>
          <input type="hidden" name="role" value="admin">
          <div class="note" style="margin-top:8px">Akun admin yang sedang Anda gunakan tidak bisa diturunkan rolenya sendiri.</div>
        <?php endif; ?>
      </div>
      <div class="form-actions">
        <button class="btn btn-gold" type="submit"><?php echo $editing ? 'Simpan Perubahan' : 'Tambah Pengguna'; ?></button>
        <a class="btn btn-ghost" href="<?php echo base_url('admin/pengguna'); ?>">Batal</a>
      </div>
      <p class="note"><?php echo $editing ? 'Kosongkan kata sandi kalau tidak ingin menggantinya. Kalau diisi, kredensial baru akan disiapkan untuk dikirim ke pengguna lewat WA/Email setelah disimpan.' : 'Setelah akun dibuat, kredensial akan disiapkan untuk dikirim ke pengguna lewat WA/Email dari halaman Kelola Pengguna.'; ?></p>
    </form>
  </div>
</section>
  </div>
</div>

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
