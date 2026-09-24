<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pengaturan Google Drive — Panel Admin · SIP Gatutkaca</title>
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
.dash-wrap{max-width:900px;margin:0;padding:0 44px}
@media(max-width:860px){
  .dash-layout{flex-direction:column}
  .dash-sidebar{width:100%;flex:0 0 auto;height:auto;min-height:0;flex-direction:row;justify-content:space-between;align-items:center;border-right:none;border-bottom:1px solid var(--line);padding:0}
  .dash-sidebar nav{flex-direction:row;justify-content:center;flex-wrap:wrap}
  .dash-sidebar a{padding:14px 20px;border-left:none;border-bottom:3px solid transparent}
  .dash-sidebar a.active{border-left-color:transparent;border-bottom-color:var(--gold-500)}
  .dash-wrap{padding:0 24px}
}

.btn{display:inline-block;padding:15px 34px;font-size:.78rem;letter-spacing:.26em;text-transform:uppercase;transition:.3s;cursor:pointer;border:none;font-family:var(--body)}
.btn-gold{background:linear-gradient(135deg,#C9A24B,#E4C87B);color:#081826;font-weight:600}
.btn-ghost{border:1px solid var(--line);color:var(--text);background:transparent}
.btn-danger{background:#E0526B;color:#fff}
.btn-sm{padding:11px 26px;font-size:.72rem;letter-spacing:.2em}

section{padding:60px 0 100px}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.6rem,3vw,2.2rem);line-height:1.2}
.alert{padding:16px 20px;margin:26px 0 0;font-size:.88rem;border:1px solid}
.alert-ok{background:rgba(46,168,79,.12);border-color:#2EA84F;color:#1c7a37}
.alert-err{background:rgba(224,82,107,.12);border-color:#E0526B;color:#a3283d}
html[data-theme="dark"] .alert-ok{color:#8CE0A6}
html[data-theme="dark"] .alert-err{color:#F3AEB9}

.card{background:var(--surface);border:1px solid var(--line);padding:32px 36px;margin-top:26px}
.card h4{font-family:var(--display);font-weight:400;font-size:1.05rem;color:var(--gold-300);margin-bottom:16px;letter-spacing:.04em}
.card p.help{color:var(--muted);font-size:.85rem;margin-top:-8px;margin-bottom:18px}

.status-row{display:flex;align-items:center;gap:14px;flex-wrap:wrap;margin-bottom:8px}
.badge{display:inline-block;padding:5px 14px;border-radius:20px;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em}
.badge-on{background:rgba(46,168,79,.15);color:#1c7a37}
.badge-off{background:rgba(224,82,107,.15);color:#a3283d}
html[data-theme="dark"] .badge-on{color:#8CE0A6}
html[data-theme="dark"] .badge-off{color:#F3AEB9}

.field{margin-bottom:20px}
.field label{display:block;font-size:.78rem;letter-spacing:.06em;color:var(--muted);margin-bottom:7px}
.field input[type=text],.field input[type=password]{width:100%;padding:13px;border:1px solid var(--line);background:var(--surface);color:var(--text);font-family:var(--body);font-size:.9rem}
.field input[type=file]{width:100%;padding:10px;border:1px solid var(--line);background:var(--surface);color:var(--text);font-family:var(--body);font-size:.85rem}
.field small{display:block;margin-top:6px;color:var(--muted);font-size:.78rem}
.readonly-copy{display:flex;border:1px solid var(--line);border-radius:10px;overflow:hidden;transition:border-color .2s}
.readonly-copy:focus-within{border-color:var(--gold-500)}
.readonly-copy input{flex:1;min-width:0;border:0!important;outline:none;background:var(--surface-hi)!important;color:var(--text);font-family:monospace;font-size:.82rem!important;padding:14px 16px}
.readonly-copy button{flex:0 0 auto;padding:0 22px;border:0;border-left:1px solid var(--line);background:linear-gradient(135deg,#C9A24B,#E4C87B);color:#081826;cursor:pointer;font-size:.72rem;font-weight:600;letter-spacing:.12em;text-transform:uppercase;transition:filter .2s}
.readonly-copy button:hover{filter:brightness(1.08)}

.mode-toggle{display:flex;gap:24px;margin-bottom:22px}
.mode-toggle label{display:flex!important;align-items:center;gap:8px;font-size:.86rem;color:var(--text)!important;cursor:pointer}
.mode-toggle input{width:auto!important}
.switch-row{display:flex;align-items:center;gap:12px;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--line)}
.switch-row label{font-size:.9rem;color:var(--text);cursor:pointer;display:flex;align-items:center;gap:10px}
.switch-row input{width:18px;height:18px}

ol.guide{margin:0 0 0 20px;color:var(--muted);font-size:.86rem}
ol.guide li{margin-bottom:10px}
ol.guide code{background:var(--surface-hi);padding:2px 6px;font-size:.82em;color:var(--text)}

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
      <a href="<?php echo base_url('admin/pengguna'); ?>">Kelola Pengguna</a>
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
    <p class="eyebrow"><a href="<?= base_url('admin') ?>" style="color:var(--gold-500);text-decoration:underline">&larr; Kembali ke Dashboard</a></p>
    <h2>Pengaturan Google Drive</h2>
    <p style="color:var(--muted);max-width:64ch;margin-top:14px">Atur akun Google Drive tempat berkas pengajuan disimpan. Berguna terutama kalau nanti domain atau akun Google-nya diganti - cukup diperbarui di sini, tidak perlu edit file lewat cPanel.</p>

    <?php if($this->session->flashdata('sukses')): ?><div class="alert alert-ok"><?= htmlspecialchars($this->session->flashdata('sukses')) ?></div><?php endif; ?>
    <?php if($this->session->flashdata('error')): ?><div class="alert alert-err"><?= htmlspecialchars($this->session->flashdata('error')) ?></div><?php endif; ?>

    <div class="card">
      <h4>Status Koneksi</h4>
      <div class="status-row">
        <span>Penyimpanan Drive:</span>
        <span class="badge <?= $pengaturan['enabled'] ? 'badge-on' : 'badge-off' ?>"><?= $pengaturan['enabled'] ? 'Aktif' : 'Nonaktif' ?></span>
      </div>
      <div class="status-row">
        <span>Koneksi akun Google:</span>
        <span class="badge <?= $terhubung ? 'badge-on' : 'badge-off' ?>"><?= $terhubung ? 'Terhubung' : 'Belum Terhubung' ?></span>
        <?php if(!empty($pengaturan['folder_id'])): ?><a class="btn btn-ghost btn-sm" style="padding:8px 16px" target="_blank" href="https://drive.google.com/drive/folders/<?= rawurlencode($pengaturan['folder_id']) ?>">Buka Folder Drive</a><?php endif; ?>
      </div>
      <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:18px">
        <?php if($pengaturan['auth_mode']==='oauth'): ?>
        <a class="btn btn-gold btn-sm" href="<?= base_url('admin/gdrive-oauth') ?>"><?= $terhubung ? 'Hubungkan Ulang / Ganti Akun' : 'Hubungkan ke Google Drive' ?></a>
        <?php if($terhubung): ?>
        <form method="post" action="<?= base_url('admin/putus-drive') ?>" onsubmit="return confirm('Putuskan koneksi Google Drive saat ini? Upload berkas baru akan gagal sampai dihubungkan kembali.');">
          <button type="submit" class="btn btn-ghost btn-sm">Putuskan Koneksi</button>
        </form>
        <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="card">
      <h4>Redirect URI untuk Google Cloud Console</h4>
      <p class="help">Isikan alamat persis di bawah ini ke kolom &#8220;Authorized redirect URIs&#8221; pada OAuth Client di Google Cloud Console. Alamat ini otomatis mengikuti domain aplikasi saat ini - kalau domain berpindah, cukup buka halaman ini lagi untuk melihat alamat yang baru.</p>
      <div class="readonly-copy">
        <input type="text" id="redirectUri" value="<?= htmlspecialchars($redirect_uri, ENT_QUOTES, 'UTF-8') ?>" readonly onclick="this.select()">
        <button type="button" onclick="salinRedirectUri()">Salin</button>
      </div>
    </div>

    <div class="card">
      <h4>Formulir Pengaturan</h4>
      <?= form_open_multipart('admin/simpan-pengaturan-drive', array('id'=>'formDrive')) ?>

      <div class="switch-row">
        <label><input type="checkbox" name="enabled" value="1" <?= $pengaturan['enabled']?'checked':'' ?>> Aktifkan penyimpanan berkas ke Google Drive</label>
      </div>

      <div class="mode-toggle">
        <label><input type="radio" name="auth_mode" value="oauth" <?= $pengaturan['auth_mode']==='oauth'?'checked':'' ?>> Akun Gmail biasa (OAuth)</label>
        <label><input type="radio" name="auth_mode" value="service_account" <?= $pengaturan['auth_mode']==='service_account'?'checked':'' ?>> Google Workspace (Service Account)</label>
      </div>

      <div class="field">
        <label for="folder_id">Folder ID Google Drive</label>
        <input type="text" id="folder_id" name="folder_id" value="<?= htmlspecialchars($pengaturan['folder_id'],ENT_QUOTES,'UTF-8') ?>" placeholder="mis. 1AbCDefGhIJKlmnoPQRstuVWxyz">
        <small>Buka folder tujuan di drive.google.com, salin bagian akhir URL-nya: <code>drive.google.com/drive/folders/<b>FOLDER_ID</b></code></small>
      </div>

      <div id="fieldsOauth">
        <div class="field">
          <label for="oauth_client_id">Client ID</label>
          <input type="text" id="oauth_client_id" name="oauth_client_id" value="<?= htmlspecialchars($pengaturan['oauth_client_id'],ENT_QUOTES,'UTF-8') ?>" placeholder="xxxxxxxxxx.apps.googleusercontent.com">
        </div>
        <div class="field">
          <label for="oauth_client_secret">Client Secret</label>
          <input type="password" id="oauth_client_secret" name="oauth_client_secret" placeholder="<?= !empty($pengaturan['oauth_client_secret']) ? 'Sudah tersimpan - kosongkan jika tidak diubah' : 'GOCSPX-...' ?>">
        </div>
        <p class="help" style="margin-top:-8px">Client ID &amp; Secret didapat dari Google Cloud Console &rarr; APIs &amp; Services &rarr; Credentials &rarr; OAuth Client ID (tipe Web application).</p>
      </div>

      <div id="fieldsServiceAccount" style="display:none">
        <div class="field">
          <label for="service_account_file">Berkas Kunci JSON Service Account</label>
          <input type="file" id="service_account_file" name="service_account_file" accept=".json">
          <small><?= !empty($pengaturan['service_account_json']) ? 'Sudah ada berkas tersimpan - pilih berkas baru hanya jika ingin menggantinya.' : 'Unduh dari Google Cloud Console → IAM & Admin → Service Accounts → Keys.' ?></small>
        </div>
      </div>

      <button type="submit" class="btn btn-gold">Simpan Pengaturan</button>
      <?= form_close() ?>
    </div>

    <div class="card">
      <h4>Panduan Singkat (Cara OAuth / Akun Gmail Biasa)</h4>
      <ol class="guide">
        <li>Buka <a href="https://console.cloud.google.com/" target="_blank" style="color:var(--gold-300);text-decoration:underline">Google Cloud Console</a>, buat/pilih project, aktifkan <b>Google Drive API</b>.</li>
        <li><b>OAuth consent screen</b> &rarr; User Type <b>External</b> &rarr; isi nama aplikasi bebas &rarr; di bagian Test users, tambahkan alamat Gmail yang akan jadi pemilik folder Drive.</li>
        <li><b>Credentials</b> &rarr; Create Credentials &rarr; <b>OAuth client ID</b> &rarr; Application type <b>Web application</b> &rarr; pada Authorized redirect URIs, tempel persis alamat di kartu &#8220;Redirect URI&#8221; di atas &rarr; Create.</li>
        <li>Salin <b>Client ID</b> dan <b>Client secret</b> yang muncul, isikan ke formulir di atas, isi juga Folder ID, centang Aktifkan, lalu <b>Simpan Pengaturan</b>.</li>
        <li>Klik tombol <b>Hubungkan ke Google Drive</b> di kartu Status Koneksi &rarr; login dengan akun Gmail pemilik folder &rarr; Allow. Selesai.</li>
      </ol>
    </div>

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

function salinRedirectUri(){
  var el=document.getElementById('redirectUri');
  el.select();
  navigator.clipboard && navigator.clipboard.writeText(el.value).catch(function(){ document.execCommand('copy'); });
}

var radios=document.querySelectorAll('input[name="auth_mode"]');
var fOauth=document.getElementById('fieldsOauth'), fSa=document.getElementById('fieldsServiceAccount');
function terapkanMode(){
  var pakaiSa = document.querySelector('input[name="auth_mode"]:checked').value === 'service_account';
  fOauth.style.display = pakaiSa ? 'none' : '';
  fSa.style.display = pakaiSa ? '' : 'none';
}
radios.forEach(function(r){ r.addEventListener('change', terapkanMode); });
terapkanMode();
</script>
</body>
</html>
