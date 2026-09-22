<?php
$v=function($field,$default='')use($old){return htmlspecialchars(isset($old[$field])?$old[$field]:$default,ENT_QUOTES,'UTF-8');};
$jenis = isset($old['jenis_pemohon']) && $old['jenis_pemohon']==='perusahaan' ? 'perusahaan' : 'perorangan';
$status_tanah_opsi = array('SHM'=>'Sertifikat Hak Milik (SHM)','SHGU'=>'Sertifikat Hak Guna Usaha (SHGU)','SHGB'=>'Sertifikat Hak Guna Bangunan (SHGB)','SHP'=>'Sertifikat Hak Pakai (SHP)','Girik'=>'Surat Girik (Letter C)','Notaris'=>'Surat Keterangan Notaris');
$air_opsi = array('Sumur Dangkal'=>'Air Sumur Dangkal','Sumur Dalam'=>'Air Sumur Dalam','PDAM'=>'PDAM','Lain-lain'=>'Lain-lain');
$perijinan_opsi = array('SPPL'=>'SPPL','PBG'=>'PBG','SIUJK'=>'SIUJK','SIUP'=>'SIUP','Perijinan Usaha Baru'=>'Perijinan Usaha Baru','Lain-Lain'=>'Lain-Lain');
$titik_lama = json_decode((string)($old['titik_koordinat']??''),TRUE); if(!is_array($titik_lama)) $titik_lama=array();
?>
<p class="eyebrow">Portal Pemohon ITR</p><h2>Formulir Pengajuan ITR</h2><p class="section-lead">Mengikuti format resmi Surat Permohonan Informasi Tata Ruang 2021. Lengkapi data pemohon, lokasi, rencana kegiatan, titik koordinat, dan seluruh lampiran sebelum mengirim pengajuan.</p>
<?php if($error): ?><div class="notice" role="alert"><?= htmlspecialchars($error,ENT_QUOTES,'UTF-8') ?></div><?php endif; ?>
<?= form_open_multipart('pemohon/simpan_itr',array('class'=>'info-card itr-application','id'=>'itrForm')) ?>
<input type="hidden" name="itr_token" value="<?= htmlspecialchars($this->session->userdata('itr_form_token'),ENT_QUOTES,'UTF-8') ?>">
<input type="hidden" name="titik_koordinat" id="itr-titik" value="<?= htmlspecialchars(json_encode($titik_lama),ENT_QUOTES,'UTF-8') ?>">

<h3>Jenis Pemohon</h3>
<div class="itr-jenis-toggle">
  <label class="itr-radio"><input type="radio" name="jenis_pemohon" value="perorangan" <?= $jenis==='perorangan'?'checked':'' ?>> Perorangan</label>
  <label class="itr-radio"><input type="radio" name="jenis_pemohon" value="perusahaan" <?= $jenis==='perusahaan'?'checked':'' ?>> Perusahaan / Badan Usaha</label>
</div>

<h3>Data Pemohon</h3><div class="itr-form-grid">
<div><label for="itr-nama" id="itr-label-nama">Nama Pemohon *</label><input id="itr-nama" name="nama_pemohon" type="text" value="<?= $v('nama_pemohon',$nama_pengguna) ?>" required></div>
<div class="itr-perorangan"><label for="itr-nik">NIK *</label><input id="itr-nik" name="nik" type="text" pattern="[0-9]{16}" maxlength="16" inputmode="numeric" value="<?= $v('nik') ?>"></div>
<div class="itr-perorangan"><label for="itr-pekerjaan">Pekerjaan *</label><input id="itr-pekerjaan" name="pekerjaan" type="text" value="<?= $v('pekerjaan') ?>"></div>
<div class="itr-perusahaan"><label for="itr-nib">NIB *</label><input id="itr-nib" name="nib" type="text" value="<?= $v('nib') ?>"></div>
<div class="itr-full"><label for="itr-alamat-pemohon" id="itr-label-alamat">Alamat *</label><textarea id="itr-alamat-pemohon" name="alamat_pemohon" required><?= $v('alamat_pemohon') ?></textarea></div>

<div><label for="itr-hp">Nomor HP *</label><input id="itr-hp" name="no_hp" type="tel" value="<?= $v('no_hp') ?>" required></div>
<div><label for="itr-email">Email *</label><input id="itr-email" name="email" type="email" value="<?= $v('email',$email_pengguna) ?>" required></div>
<div><label for="itr-npwp">No. NPWP *</label><input id="itr-npwp" name="no_npwp" type="text" value="<?= $v('no_npwp') ?>" required></div>
</div>

<h3>Rencana Kegiatan</h3><div class="itr-form-grid">
<div><label for="itr-jenis-kegiatan">Jenis Kegiatan / Usaha *</label><input id="itr-jenis-kegiatan" name="jenis_kegiatan" type="text" placeholder="Sesuai KBLI OSS" value="<?= $v('jenis_kegiatan') ?>" required></div>
<div><label for="itr-fungsi-bangunan">Fungsi Bangunan *</label><input id="itr-fungsi-bangunan" name="fungsi_bangunan" type="text" value="<?= $v('fungsi_bangunan') ?>" required></div>
</div>

<h3>Lokasi Kegiatan</h3><div class="itr-form-grid">
<div><label for="itr-jalan">Jalan *</label><input id="itr-jalan" name="lokasi_jalan" type="text" value="<?= $v('lokasi_jalan') ?>" required></div>
<div><label for="itr-rtrw">RT/RW</label><input id="itr-rtrw" name="lokasi_rt_rw" type="text" placeholder="001/002" value="<?= $v('lokasi_rt_rw') ?>"></div>
<div><label for="itr-desa">Desa / Kelurahan *</label><input id="itr-desa" name="lokasi_desa_kel" type="text" value="<?= $v('lokasi_desa_kel') ?>" required></div>
<div><label for="itr-kecamatan">Kecamatan *</label><input id="itr-kecamatan" name="lokasi_kecamatan" type="text" value="<?= $v('lokasi_kecamatan') ?>" required></div>
<div><label for="itr-luas-lahan">Luas Lahan (m²) *</label><input id="itr-luas-lahan" type="number" name="luas_lahan" min="0.01" step="0.01" value="<?= $v('luas_lahan') ?>" required></div>
<div><label for="itr-luas-bangunan">Luas Bangunan (m²)</label><input id="itr-luas-bangunan" type="number" name="luas_bangunan" min="0" step="0.01" value="<?= $v('luas_bangunan') ?>"></div>
<div><label for="itr-lantai">Lantai Bangunan</label><input id="itr-lantai" type="number" name="lantai_bangunan" min="0" step="1" value="<?= $v('lantai_bangunan') ?>"></div>
<div><label for="itr-status-tanah">Status Tanah *</label><select id="itr-status-tanah" name="status_tanah" required><option value="">— Pilih —</option><?php foreach($status_tanah_opsi as $val=>$label): ?><option value="<?= htmlspecialchars($val,ENT_QUOTES,'UTF-8') ?>" <?= ($old['status_tanah']??'')===$val?'selected':'' ?>><?= htmlspecialchars($label,ENT_QUOTES,'UTF-8') ?></option><?php endforeach; ?></select></div>
<div><label for="itr-air">Penggunaan Air Baku *</label><select id="itr-air" name="penggunaan_air" required><option value="">— Pilih —</option><?php foreach($air_opsi as $val=>$label): ?><option value="<?= htmlspecialchars($val,ENT_QUOTES,'UTF-8') ?>" <?= ($old['penggunaan_air']??'')===$val?'selected':'' ?>><?= htmlspecialchars($label,ENT_QUOTES,'UTF-8') ?></option><?php endforeach; ?></select></div>
<div><label for="itr-perijinan">Keterangan Perijinan</label><select id="itr-perijinan" name="keterangan_perijinan"><option value="">— Pilih —</option><?php foreach($perijinan_opsi as $val=>$label): ?><option value="<?= htmlspecialchars($val,ENT_QUOTES,'UTF-8') ?>" <?= ($old['keterangan_perijinan']??'')===$val?'selected':'' ?>><?= htmlspecialchars($label,ENT_QUOTES,'UTF-8') ?></option><?php endforeach; ?></select></div>
</div>

<h3>Titik Koordinat Lokasi</h3><p class="itr-map-hint">Klik pada peta, atau masukkan koordinat secara manual di bawah — minimal <b>4 titik</b> membentuk poligon. Klik titik yang sudah ada di peta/daftar untuk menghapusnya.</p>
<div id="itrMap" class="itr-map"></div>
<div class="itr-titik-manual">
  <div><label for="itr-manual-lat">Latitude</label><input id="itr-manual-lat" type="number" step="any" min="-90" max="90" placeholder="-7.726700"></div>
  <div><label for="itr-manual-lng">Longitude</label><input id="itr-manual-lng" type="number" step="any" min="-180" max="180" placeholder="109.015400"></div>
  <button type="button" id="itr-manual-add" class="btn btn-ghost">+ Tambah Titik</button>
</div>
<div id="itrTitikList" class="itr-titik-list"></div>

<h3>Lampiran Persyaratan</h3><p>PDF, JPG, atau PNG. Maksimum 100 MB per berkas. Semua lampiran wajib diunggah.</p><div class="itr-form-grid">
<div class="itr-full"><label for="itr-file-permohonan">Surat Permohonan (bertanda tangan &amp; bermaterai) *</label><input id="itr-file-permohonan" type="file" name="file_permohonan" accept=".pdf,.jpg,.jpeg,.png" required></div>
<div class="itr-perusahaan"><label for="itr-file-nib">NIB *</label><input id="itr-file-nib" type="file" name="file_nib" accept=".pdf,.jpg,.jpeg,.png"></div>
<div><label for="itr-file-ktp" id="itr-label-ktp">KTP Pemohon *</label><input id="itr-file-ktp" type="file" name="file_ktp" accept=".pdf,.jpg,.jpeg,.png" required></div>
<div class="itr-perusahaan"><label for="itr-file-npwp">Fc NPWP *</label><input id="itr-file-npwp" type="file" name="file_npwp" accept=".pdf,.jpg,.jpeg,.png"></div>
<div class="itr-perusahaan"><label for="itr-file-akta">Fc Akta Pendirian Perusahaan *</label><input id="itr-file-akta" type="file" name="file_akta" accept=".pdf,.jpg,.jpeg,.png"></div>
<div><label for="itr-file-sertifikat">Sertifikat Tanah / Letter C &amp; Peta Blok Desa *</label><input id="itr-file-sertifikat" type="file" name="file_sertifikat" accept=".pdf,.jpg,.jpeg,.png" required></div>
<div><label for="itr-file-siteplan">Rencana Teknis Bangunan / Site Plan *</label><input id="itr-file-siteplan" type="file" name="file_siteplan" accept=".pdf,.jpg,.jpeg,.png" required></div>
<div><label for="itr-file-denah">Denah dan Foto Lokasi *</label><input id="itr-file-denah" type="file" name="file_denah_foto" accept=".pdf,.jpg,.jpeg,.png" required></div>
</div>
<div class="itr-form-actions"><button type="submit" class="btn btn-gold">Kirim Pengajuan ITR</button><a class="btn btn-ghost" href="<?= base_url('pemohon') ?>">Kembali ke Dashboard</a></div><?= form_close() ?>

<style>
.itr-form-grid{display:grid;grid-template-columns:1fr 1fr;gap:22px;margin:22px 0 32px}
.itr-full{grid-column:1/-1}
.itr-application label{display:block;margin-bottom:8px;font-size:15px;color:var(--gold-300)}
.itr-application input:not([type=hidden]):not([type=radio]),.itr-application textarea,.itr-application select{width:100%;padding:14px;border:1px solid var(--line);border-radius:10px;background:var(--input);color:var(--text);font:400 15px var(--body)}
.itr-application textarea{min-height:90px}
.itr-form-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:28px}
.itr-jenis-toggle{display:flex;gap:24px;margin:18px 0 30px}
.itr-radio{display:flex!important;align-items:center;gap:10px;font-size:15px;color:var(--text)!important;cursor:pointer}
.itr-radio input{width:auto!important}
.itr-map-hint{color:var(--muted);margin:0 0 14px}
.itr-application.mode-perorangan .itr-perusahaan{display:none}
.itr-application.mode-perusahaan .itr-perorangan{display:none}
.itr-map{height:360px;border:1px solid var(--line);border-radius:12px;margin-bottom:10px}
.itr-titik-manual{display:flex;align-items:flex-end;gap:12px;margin-bottom:14px;flex-wrap:wrap}
.itr-titik-manual div{flex:1;min-width:140px}
.itr-titik-manual label{font-size:13px;margin-bottom:6px}
.itr-titik-manual input{width:100%;padding:12px;border:1px solid var(--line);border-radius:10px;background:var(--input);color:var(--text);font:400 14px var(--body)}
.itr-titik-manual button{flex:0 0 auto;white-space:nowrap}
.itr-titik-list{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:28px}
.itr-titik-chip{display:flex;align-items:center;gap:8px;padding:6px 12px;border:1px solid var(--line);border-radius:20px;font-size:12px;color:var(--muted)}
.itr-titik-chip button{border:0;background:none;color:#e0526b;cursor:pointer;font-weight:700;padding:0}
@media(max-width:700px){.itr-form-grid{grid-template-columns:1fr}.itr-full{grid-column:auto}}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
(function(){
  // ---- Toggle Perorangan / Perusahaan ----
  var form=document.getElementById('itrForm');
  var radios=form.querySelectorAll('input[name="jenis_pemohon"]');
  var labelNama=document.getElementById('itr-label-nama'), labelAlamat=document.getElementById('itr-label-alamat'), labelKtp=document.getElementById('itr-label-ktp');
  function terapkanJenis(){
    var perusahaan = form.querySelector('input[name="jenis_pemohon"]:checked').value === 'perusahaan';
    form.classList.toggle('mode-perusahaan', perusahaan);
    form.classList.toggle('mode-perorangan', !perusahaan);
    form.querySelectorAll('.itr-perorangan input,.itr-perorangan textarea').forEach(function(el){ el.required=!perusahaan; el.disabled=perusahaan; });
    form.querySelectorAll('.itr-perusahaan input,.itr-perusahaan textarea').forEach(function(el){ el.required=perusahaan; el.disabled=!perusahaan; });
    labelNama.textContent = perusahaan ? 'Nama Direktur *' : 'Nama Pemohon *';
    labelAlamat.textContent = perusahaan ? 'Alamat Perusahaan *' : 'Alamat *';
    labelKtp.textContent = perusahaan ? 'KTP Direktur *' : 'KTP Pemohon *';
  }
  radios.forEach(function(r){ r.addEventListener('change', terapkanJenis); });
  terapkanJenis();

  // ---- Peta poligon titik koordinat ----
  var titikInput=document.getElementById('itr-titik');
  var titik=[]; try{ titik=JSON.parse(titikInput.value)||[]; }catch(e){ titik=[]; }
  var map=L.map('itrMap').setView(titik.length?[titik[0].lat,titik[0].lng]:[-7.7267,109.0154], titik.length?15:11);
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19,attribution:'&copy; OpenStreetMap'}).addTo(map);
  var markers=[], polygon=null;
  var list=document.getElementById('itrTitikList');
  function render(){
    titikInput.value=JSON.stringify(titik);
    markers.forEach(function(m){ map.removeLayer(m); }); markers=[];
    if(polygon){ map.removeLayer(polygon); polygon=null; }
    titik.forEach(function(t,i){
      var m=L.marker([t.lat,t.lng]).addTo(map);
      m.on('click',function(){ titik.splice(i,1); render(); });
      markers.push(m);
    });
    if(titik.length>=3) polygon=L.polygon(titik.map(function(t){return [t.lat,t.lng];}),{color:'#c9a24b'}).addTo(map);
    list.innerHTML='';
    titik.forEach(function(t,i){
      var chip=document.createElement('span'); chip.className='itr-titik-chip';
      chip.innerHTML='Titik '+(i+1)+': '+t.lat.toFixed(5)+', '+t.lng.toFixed(5)+' <button type="button">×</button>';
      chip.querySelector('button').addEventListener('click',function(){ titik.splice(i,1); render(); });
      list.appendChild(chip);
    });
  }
  map.on('click',function(e){ titik.push({lat:e.latlng.lat,lng:e.latlng.lng}); render(); });
  render();
  setTimeout(function(){ map.invalidateSize(); },200);

  // ---- Input manual koordinat ----
  var manualLat=document.getElementById('itr-manual-lat'), manualLng=document.getElementById('itr-manual-lng');
  document.getElementById('itr-manual-add').addEventListener('click', function(){
    var lat=parseFloat(manualLat.value), lng=parseFloat(manualLng.value);
    if(!isFinite(lat)||lat<-90||lat>90){ manualLat.focus(); return; }
    if(!isFinite(lng)||lng<-180||lng>180){ manualLng.focus(); return; }
    titik.push({lat:lat,lng:lng}); render();
    map.panTo([lat,lng]);
    manualLat.value=''; manualLng.value=''; manualLat.focus();
  });
})();
</script>
