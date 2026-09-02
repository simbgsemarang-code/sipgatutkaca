<?php
$ubah = ($row !== NULL);
$v = function ($k, $def = '') use ($row, $old) {
	if (!empty($old) && array_key_exists($k, $old)) return (string) $old[$k];
	if ($row !== NULL && array_key_exists($k, $row) && $row[$k] !== NULL) return (string) $row[$k];
	return $def;
};
$foto_lama = ($row !== NULL && !empty($row['foto'])) ? $row['foto'] : NULL;
$foto_url  = $foto_lama === NULL ? NULL
	: (preg_match('#^https?://#i', $foto_lama) ? $foto_lama : base_url('assets/foto-cagar-budaya/' . $foto_lama));
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $ubah ? 'Ubah' : 'Tambah'; ?> Cagar Budaya — Panel Admin · SIP Gatutkaca</title>
<link rel="icon" type="image/png" href="<?php echo base_url('assets/img/icon.png'); ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<style>
:root{--gold-500:#C9A24B;--gold-300:#E4C87B;--display:'Marcellus',serif;--body:'Plus Jakarta Sans',system-ui,sans-serif}
html[data-theme="dark"]{--bg:#081826;--bg-alt:#0C2236;--surface:#0C2236;--surface-hi:#123249;--text:#F8F4EA;--muted:#B9C7D2;--line:rgba(201,162,75,.28);--head-bg:rgba(8,24,38,.94);--head-grad:rgba(8,24,38,.85);--foot:#050F19;--input:#0F2A40;--shadow:rgba(0,0,0,.5)}
html[data-theme="light"]{--bg:#FDFBF5;--bg-alt:#F6F1E3;--surface:#FFFFFF;--surface-hi:#FAF5E8;--text:#152A3B;--muted:#4E6070;--line:rgba(160,124,45,.35);--head-bg:rgba(253,251,245,.94);--head-grad:rgba(253,251,245,.85);--foot:#122536;--input:#FFFFFF;--shadow:rgba(21,42,59,.18);--gold-500:#A57E2C;--gold-300:#8F6C1F}
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:var(--body);background:var(--bg);color:var(--text);line-height:1.7;font-weight:300}
a{color:inherit;text-decoration:none}
.wrap{max-width:1180px;margin:0 auto;padding:0 28px}
header{position:fixed;inset:0 0 auto 0;z-index:60;transition:.4s;background:linear-gradient(180deg,var(--head-grad),transparent)}
header.scrolled{background:var(--head-bg);backdrop-filter:blur(12px);box-shadow:0 1px 0 var(--line)}
.nav{display:flex;align-items:center;justify-content:space-between;height:84px;gap:18px}
.brand{display:flex;align-items:center;gap:13px}
.brand img{height:50px;width:auto}
.brand-name{font-family:var(--display);font-size:1.2rem;letter-spacing:.13em;color:var(--gold-300)}
.brand-sub{font-size:.6rem;letter-spacing:.3em;text-transform:uppercase;color:var(--muted)}
.dash-layout{display:flex;padding-top:84px}
.dash-sidebar{width:240px;flex:0 0 240px;height:calc(100vh - 84px);position:sticky;top:84px;background:var(--surface);border-right:1px solid var(--line);padding:40px 0;display:flex;flex-direction:column;justify-content:space-between;overflow-y:auto}
.dash-sidebar nav{display:flex;flex-direction:column;gap:4px}
.dash-sidebar a{display:flex;align-items:center;gap:12px;padding:14px 28px;font-size:.78rem;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);border-left:3px solid transparent;transition:.25s}
.dash-sidebar a:hover{color:var(--text);background:var(--surface-hi)}
.dash-sidebar a.active{color:var(--gold-300);border-left-color:var(--gold-500);background:var(--surface-hi)}
.dash-main{flex:1;min-width:0}
.dash-wrap{max-width:1000px;margin:0;padding:0 44px}
@media(max-width:860px){.dash-layout{flex-direction:column}.dash-sidebar{width:100%;flex:0 0 auto;height:auto;flex-direction:row;flex-wrap:wrap;border-right:none;border-bottom:1px solid var(--line);padding:0}.dash-sidebar nav{flex-direction:row;flex-wrap:wrap}.dash-sidebar a{padding:14px 20px;border-left:none;border-bottom:3px solid transparent}.dash-wrap{padding:0 24px}}
.btn{display:inline-block;padding:15px 34px;font-size:.78rem;letter-spacing:.26em;text-transform:uppercase;cursor:pointer;border:none;font-family:var(--body)}
.btn-ghost{border:1px solid var(--line);color:var(--text);background:transparent}
.btn-ghost:hover{border-color:#C9A24B;color:#E4C87B}
.btn-gold{background:linear-gradient(135deg,#C9A24B,#E4C87B);color:#081826;font-weight:600}
.btn-sm{padding:11px 26px;font-size:.72rem;letter-spacing:.2em}
section{padding:60px 0 100px}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.7rem,3vw,2.3rem);line-height:1.2}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:22px;margin-top:36px}
.grid .full{grid-column:1/-1}
@media(max-width:720px){.grid{grid-template-columns:1fr}}
.field label{display:block;font-size:.7rem;letter-spacing:.2em;text-transform:uppercase;color:var(--muted);margin-bottom:6px}
.field input,.field select,.field textarea{width:100%;background:var(--input);border:1px solid var(--line);color:var(--text);padding:11px 14px;font-family:var(--body);font-size:.9rem}
.field textarea{min-height:100px;resize:vertical}
.field input:focus,.field select:focus,.field textarea:focus{outline:1px solid var(--gold-500);border-color:var(--gold-500)}
.hint{font-size:.72rem;color:var(--muted);margin-top:6px}
#pickMap{height:320px;width:100%;border:1px solid var(--line);margin-top:6px;background:#dfeee2}
.foto-prev img{max-width:280px;border:1px solid var(--line);margin-top:8px}
</style>
</head>
<body>

<header id="topbar">
  <div class="wrap nav">
    <a class="brand" href="<?php echo base_url(); ?>">
      <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Seal_of_Cilacap_Regency.svg?width=120" alt="Lambang Kabupaten Cilacap">
      <span><span class="brand-name">SIP GATUTKACA</span><br><span class="brand-sub">Kabupaten Cilacap</span></span>
    </a>
    <a class="btn btn-ghost btn-sm" href="<?php echo base_url('admin/cagar-budaya'); ?>">&larr; Kembali</a>
  </div>
</header>

<div class="dash-layout">
  <aside class="dash-sidebar">
    <nav>
      <a href="<?php echo base_url('admin'); ?>">Dashboard</a>
      <a href="<?php echo base_url('admin/pengguna'); ?>">Kelola Pengguna</a>
      <a href="<?php echo base_url('admin/pengajuan'); ?>">Pengajuan PBG</a>
      <a href="<?php echo base_url('admin/bangunan'); ?>">Sebaran Bangunan</a>
      <a href="<?php echo base_url('admin/cagar-budaya'); ?>" class="active">Kelola Cagar Budaya</a>
      <a href="<?php echo base_url('admin/saran'); ?>">Saran &amp; FAQ</a>
    </nav>
    <nav><a href="<?php echo base_url('login/keluar'); ?>">Logout</a></nav>
  </aside>
  <div class="dash-main">
<section style="padding-top:20px">
  <div class="dash-wrap">
    <p class="eyebrow">Panel Admin</p>
    <h2><?php echo $ubah ? 'Ubah Objek Cagar Budaya' : 'Tambah Objek Cagar Budaya'; ?></h2>

    <?php if (!empty($error)): ?>
      <div style="margin-top:24px;padding:14px 18px;border:1px solid #E0526B;background:rgba(224,82,107,.12);color:#E0526B;font-size:.88rem"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data"
          action="<?php echo base_url('admin/cagar-budaya-simpan' . ($ubah ? '/' . (int) $row['id'] : '')); ?>">
      <div class="grid">
        <div class="field full">
          <label for="f-nama">Nama Objek *</label>
          <input type="text" id="f-nama" name="nama" required value="<?php echo htmlspecialchars($v('nama'), ENT_QUOTES, 'UTF-8'); ?>" placeholder="mis. Benteng Pendem">
        </div>

        <div class="field">
          <label for="f-kategori">Kategori *</label>
          <select id="f-kategori" name="kategori" required>
            <?php foreach ($cb_kategori as $k): ?>
              <option <?php echo $v('kategori', 'Bangunan') === $k ? 'selected' : ''; ?>><?php echo $k; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label for="f-status">Status *</label>
          <select id="f-status" name="status" required>
            <?php foreach ($cb_status as $s): ?>
              <option <?php echo $v('status', 'Objek Diduga Cagar Budaya') === $s ? 'selected' : ''; ?>><?php echo $s; ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field">
          <label for="f-kec">Kecamatan</label>
          <input type="text" id="f-kec" name="kecamatan" value="<?php echo htmlspecialchars($v('kecamatan'), ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="field">
          <label for="f-kel">Kelurahan / Desa</label>
          <input type="text" id="f-kel" name="kelurahan" value="<?php echo htmlspecialchars($v('kelurahan'), ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <div class="field full">
          <label for="f-alamat">Alamat</label>
          <input type="text" id="f-alamat" name="alamat" value="<?php echo htmlspecialchars($v('alamat'), ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <div class="field">
          <label for="f-tahun">Tahun / Periode</label>
          <input type="text" id="f-tahun" name="tahun" value="<?php echo htmlspecialchars($v('tahun'), ENT_QUOTES, 'UTF-8'); ?>" placeholder="mis. 1861-1879 atau abad ke-19">
        </div>
        <div class="field">
          <label for="f-sk">Nomor SK Penetapan</label>
          <input type="text" id="f-sk" name="no_sk" value="<?php echo htmlspecialchars($v('no_sk'), ENT_QUOTES, 'UTF-8'); ?>" placeholder="mis. SK Bupati No. 556/204/15 Tahun 2019">
        </div>

        <div class="field">
          <label for="f-lat">Latitude</label>
          <input type="text" id="f-lat" name="latitude" value="<?php echo htmlspecialchars($v('latitude'), ENT_QUOTES, 'UTF-8'); ?>" placeholder="-7.749206">
          <p class="hint">Kosongkan keduanya jika titik belum diketahui.</p>
        </div>
        <div class="field">
          <label for="f-lng">Longitude</label>
          <input type="text" id="f-lng" name="longitude" value="<?php echo htmlspecialchars($v('longitude'), ENT_QUOTES, 'UTF-8'); ?>" placeholder="109.017086">
        </div>

        <div class="field full">
          <label>Pilih Titik di Peta</label>
          <div id="pickMap"></div>
          <p class="hint">Klik peta atau geser penanda untuk mengisi koordinat otomatis.</p>
        </div>

        <div class="field full">
          <label for="f-desk">Deskripsi</label>
          <textarea id="f-desk" name="deskripsi"><?php echo htmlspecialchars($v('deskripsi'), ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>

        <div class="field full">
          <label for="f-sumber">Sumber Data / Rujukan</label>
          <input type="text" id="f-sumber" name="sumber" value="<?php echo htmlspecialchars($v('sumber'), ENT_QUOTES, 'UTF-8'); ?>" placeholder="mis. Registrasi Nasional Cagar Budaya; BPCB Jawa Tengah">
        </div>

        <div class="field full">
          <label for="f-foto">Foto Objek</label>
          <input type="file" id="f-foto" name="foto_file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
          <p class="hint">JPG / PNG / WebP, maksimal 5 MB.</p>
          <?php if ($foto_url): ?>
            <div class="foto-prev">
              <img src="<?php echo htmlspecialchars($foto_url, ENT_QUOTES, 'UTF-8'); ?>" alt="Foto saat ini">
              <label style="display:flex;align-items:center;gap:8px;margin-top:10px;text-transform:none;letter-spacing:0;font-size:.84rem;color:var(--text)">
                <input type="checkbox" name="hapus_foto" value="1" style="width:auto"> Hapus foto ini
              </label>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div style="display:flex;gap:12px;margin-top:34px;flex-wrap:wrap">
        <button type="submit" class="btn btn-gold btn-sm"><?php echo $ubah ? 'Simpan Perubahan' : 'Simpan Objek'; ?></button>
        <a href="<?php echo base_url('admin/cagar-budaya'); ?>" class="btn btn-ghost btn-sm">Batal</a>
      </div>
    </form>
  </div>
</section>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
(function(){
  var p=new URLSearchParams(location.search);
  document.documentElement.setAttribute('data-theme', p.get('theme')==='dark'?'dark':'light');
  var bar=document.getElementById('topbar');
  addEventListener('scroll',function(){bar.classList.toggle('scrolled',scrollY>40)},{passive:true});

  var latEl=document.getElementById('f-lat'), lngEl=document.getElementById('f-lng');
  function val(el,fb){ var n=parseFloat(el.value); return isFinite(n)?n:fb; }
  var lat=val(latEl,-7.53), lng=val(lngEl,108.99);
  var map=L.map('pickMap').setView([lat,lng], (latEl.value&&lngEl.value)?15:10);
  L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{maxZoom:20,subdomains:['mt0','mt1','mt2','mt3'],attribution:'&copy; Google Maps'}).addTo(map);
  var marker=L.marker([lat,lng],{draggable:true}).addTo(map);
  function setFields(ll){ latEl.value=ll.lat.toFixed(6); lngEl.value=ll.lng.toFixed(6); }
  marker.on('dragend',function(){ setFields(marker.getLatLng()); });
  map.on('click',function(e){ marker.setLatLng(e.latlng); setFields(e.latlng); });
  function sync(){
    var la=parseFloat(latEl.value), ln=parseFloat(lngEl.value);
    if(isFinite(la)&&isFinite(ln)){ marker.setLatLng([la,ln]); map.panTo([la,ln]); }
  }
  latEl.addEventListener('change',sync);
  lngEl.addEventListener('change',sync);
  setTimeout(function(){ map.invalidateSize(); },200);
})();
</script>
</body>
</html>
