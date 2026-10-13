<?php
$edit_id = isset($edit_id) ? (int)$edit_id : 0;
$v=function($field,$default='')use($old){return htmlspecialchars(isset($old[$field])?$old[$field]:$default,ENT_QUOTES,'UTF-8');};
$jenis = isset($old['jenis_pemohon']) && $old['jenis_pemohon']==='perusahaan' ? 'perusahaan' : 'perorangan';
$status_tanah_opsi = array('SHM'=>'Sertifikat Hak Milik (SHM)','SHGU'=>'Sertifikat Hak Guna Usaha (SHGU)','SHGB'=>'Sertifikat Hak Guna Bangunan (SHGB)','SHP'=>'Sertifikat Hak Pakai (SHP)','Girik'=>'Surat Girik (Letter C)','Notaris'=>'Surat Keterangan Notaris');
$air_opsi = array('Sumur Dangkal'=>'Air Sumur Dangkal','Sumur Dalam'=>'Air Sumur Dalam','PDAM'=>'PDAM','Lain-lain'=>'Lain-lain');
$perijinan_opsi = array('SPPL'=>'SPPL','PBG'=>'PBG','SIUJK'=>'SIUJK','SIUP'=>'SIUP','Perijinan Usaha Baru'=>'Perijinan Usaha Baru','Lain-Lain'=>'Lain-Lain');
$titik_lama = json_decode((string)($old['titik_koordinat']??''),TRUE); if(!is_array($titik_lama)) $titik_lama=array();
$wilayah_cilacap = wilayah_cilacap();
?>
<p class="eyebrow">Portal Pemohon ITR</p><h2><?= $edit_id?'Edit Pengajuan ITR':'Formulir Pengajuan ITR' ?></h2><p class="section-lead">Mengikuti format resmi Surat Permohonan Informasi Tata Ruang 2021. Lengkapi data pemohon, lokasi, rencana kegiatan, titik koordinat, dan seluruh lampiran sebelum mengirim pengajuan.</p>
<?php if($error): ?><div class="notice" role="alert"><?= htmlspecialchars($error,ENT_QUOTES,'UTF-8') ?></div><?php endif; ?>
<?= form_open($edit_id?'pemohon/perbarui_itr/'.$edit_id:'pemohon/simpan_itr',array('class'=>'info-card itr-application','id'=>'itrForm')) ?>
<input type="hidden" name="itr_token" value="<?= htmlspecialchars($this->session->userdata('itr_form_token'),ENT_QUOTES,'UTF-8') ?>">
<input type="hidden" name="titik_koordinat" id="itr-titik" value="<?= htmlspecialchars(json_encode($titik_lama),ENT_QUOTES,'UTF-8') ?>">

<h3>Jenis Pemohon</h3>
<div class="itr-jenis-toggle">
  <label class="itr-radio"><input type="radio" name="jenis_pemohon" value="perorangan" <?= $jenis==='perorangan'?'checked':'' ?>> Perorangan</label>
  <label class="itr-radio"><input type="radio" name="jenis_pemohon" value="perusahaan" <?= $jenis==='perusahaan'?'checked':'' ?>> Perusahaan / Badan Usaha</label>
</div>

<h3>Data Pemohon</h3><div class="itr-form-grid">
<div><label for="itr-nama" id="itr-label-nama">Nama Pemilik *</label><input id="itr-nama" name="nama_pemohon" type="text" value="<?= $v('nama_pemohon',$nama_pengguna) ?>" required></div>
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
<div><label for="itr-kecamatan">Kecamatan *</label><select id="itr-kecamatan" name="lokasi_kecamatan" required><option value="">— Pilih Kecamatan —</option><?php foreach(array_keys($wilayah_cilacap) as $kec): ?><option value="<?= htmlspecialchars($kec,ENT_QUOTES,'UTF-8') ?>" <?= ($old['lokasi_kecamatan']??'')===$kec?'selected':'' ?>><?= htmlspecialchars($kec,ENT_QUOTES,'UTF-8') ?></option><?php endforeach; ?></select></div>
<div><label for="itr-desa">Desa / Kelurahan *</label><select id="itr-desa" name="lokasi_desa_kel" required <?= empty($old['lokasi_kecamatan'])?'disabled':'' ?>><option value="">— Pilih Kecamatan dahulu —</option></select></div>
<div><label for="itr-luas-lahan">Luas Lahan (m²) *</label><input id="itr-luas-lahan" type="number" name="luas_lahan" min="0.01" step="0.01" value="<?= $v('luas_lahan') ?>" required></div>
<div><label for="itr-luas-bangunan">Luas Bangunan (m²)</label><input id="itr-luas-bangunan" type="number" name="luas_bangunan" min="0" step="0.01" value="<?= $v('luas_bangunan') ?>"></div>
<div><label for="itr-lantai">Lantai Bangunan</label><input id="itr-lantai" type="number" name="lantai_bangunan" min="0" step="1" value="<?= $v('lantai_bangunan') ?>"></div>
<div><label for="itr-status-tanah">Status Tanah *</label><select id="itr-status-tanah" name="status_tanah" required><option value="">— Pilih —</option><?php foreach($status_tanah_opsi as $val=>$label): ?><option value="<?= htmlspecialchars($val,ENT_QUOTES,'UTF-8') ?>" <?= ($old['status_tanah']??'')===$val?'selected':'' ?>><?= htmlspecialchars($label,ENT_QUOTES,'UTF-8') ?></option><?php endforeach; ?></select></div>
<div><label for="itr-air">Penggunaan Air Baku *</label><select id="itr-air" name="penggunaan_air" required><option value="">— Pilih —</option><?php foreach($air_opsi as $val=>$label): ?><option value="<?= htmlspecialchars($val,ENT_QUOTES,'UTF-8') ?>" <?= ($old['penggunaan_air']??'')===$val?'selected':'' ?>><?= htmlspecialchars($label,ENT_QUOTES,'UTF-8') ?></option><?php endforeach; ?></select></div>
<div><label for="itr-perijinan">Keterangan Perijinan</label><select id="itr-perijinan" name="keterangan_perijinan"><option value="">— Pilih —</option><?php foreach($perijinan_opsi as $val=>$label): ?><option value="<?= htmlspecialchars($val,ENT_QUOTES,'UTF-8') ?>" <?= ($old['keterangan_perijinan']??'')===$val?'selected':'' ?>><?= htmlspecialchars($label,ENT_QUOTES,'UTF-8') ?></option><?php endforeach; ?></select></div>
</div>

<h3>Titik Koordinat Lokasi</h3><p class="itr-map-hint">Klik pada peta, atau masukkan koordinat secara manual di bawah — minimal <b>4 titik</b> membentuk poligon. Klik titik yang sudah ada di peta/daftar untuk menghapusnya.</p>
<div class="itr-geo-bar">
  <label for="itr-file-geo" class="btn btn-ghost itr-geo-btn">&#11014; Unggah SHP / KML / GeoJSON</label>
  <input type="file" id="itr-file-geo" accept=".zip,.shp,.kml,.geojson,.json" hidden>
  <button type="button" id="itr-hapus-semua" class="btn btn-ghost">Hapus Semua Titik</button>
  <span id="itr-geo-status" class="itr-geo-status" aria-live="polite"></span>
</div>
<p class="itr-geo-hint">Format: <b>.geojson</b>, <b>.kml</b>, atau shapefile berupa <b>.zip</b> (berisi .shp, .shx, .dbf, dan .prj). Seluruh titik batas poligon dari berkas dimuat ke peta dan ikut tersimpan bersama permohonan.</p>
<div id="itrMap" class="itr-map"></div>
<div class="itr-titik-manual">
  <div><label for="itr-manual-lat">Latitude</label><input id="itr-manual-lat" type="number" step="any" min="-90" max="90" placeholder="-7.726700"></div>
  <div><label for="itr-manual-lng">Longitude</label><input id="itr-manual-lng" type="number" step="any" min="-180" max="180" placeholder="109.015400"></div>
  <button type="button" id="itr-manual-add" class="btn btn-ghost">+ Tambah Titik</button>
</div>
<div id="itrTitikList" class="itr-titik-list"></div>

<?php if(!$edit_id): ?><div class="notice">Setelah data ini disimpan, Anda akan diarahkan ke halaman unggah berkas persyaratan (satu per satu, seperti pada Pengajuan PBG).</div><?php endif; ?>
<div class="itr-form-actions"><button type="submit" class="btn btn-gold"><?= $edit_id?'Simpan Perubahan':'Simpan &amp; Lanjut Unggah Berkas' ?></button><a class="btn btn-ghost" href="<?= base_url('pemohon') ?>">Kembali ke Dashboard</a></div><?= form_close() ?>

<style>
.notice{padding:16px 20px;border-radius:12px;border:1px solid var(--line);background:var(--surface-hi);margin:20px 0;font-size:14px}
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
.itr-geo-bar{display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:8px}
.itr-geo-bar .btn{cursor:pointer}
.itr-geo-status{font-size:13px;color:var(--muted)}
.itr-geo-status.galat{color:#e0526b}
.itr-geo-hint{font-size:12.5px;color:var(--muted);margin:0 0 12px}
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
  var labelNama=document.getElementById('itr-label-nama'), labelAlamat=document.getElementById('itr-label-alamat');
  function terapkanJenis(){
    var perusahaan = form.querySelector('input[name="jenis_pemohon"]:checked').value === 'perusahaan';
    form.classList.toggle('mode-perusahaan', perusahaan);
    form.classList.toggle('mode-perorangan', !perusahaan);
    form.querySelectorAll('.itr-perorangan input,.itr-perorangan textarea').forEach(function(el){ el.required=!perusahaan; el.disabled=perusahaan; });
    form.querySelectorAll('.itr-perusahaan input,.itr-perusahaan textarea').forEach(function(el){ el.required=perusahaan; el.disabled=!perusahaan; });
    labelNama.textContent = perusahaan ? 'Nama Direktur *' : 'Nama Pemilik *';
    labelAlamat.textContent = perusahaan ? 'Alamat Perusahaan *' : 'Alamat *';
  }
  radios.forEach(function(r){ r.addEventListener('change', terapkanJenis); });
  terapkanJenis();

  // ---- Kecamatan -> Desa/Kelurahan bertingkat ----
  var wilayah = <?= json_encode($wilayah_cilacap, JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) ?>;
  var selKec=document.getElementById('itr-kecamatan'), selDesa=document.getElementById('itr-desa');
  var desaAwal='<?= $v('lokasi_desa_kel') ?>';
  function isiDesa(){
    var daftar=wilayah[selKec.value]||[];
    selDesa.innerHTML='';
    if(!daftar.length){ selDesa.disabled=true; selDesa.innerHTML='<option value="">— Pilih Kecamatan dahulu —</option>'; return; }
    selDesa.disabled=false;
    var opt=document.createElement('option'); opt.value=''; opt.textContent='— Pilih Desa/Kelurahan —'; selDesa.appendChild(opt);
    daftar.forEach(function(d){
      var o=document.createElement('option'); o.value=d; o.textContent=d;
      if(d===desaAwal) o.selected=true;
      selDesa.appendChild(o);
    });
  }
  selKec.addEventListener('change', function(){ desaAwal=''; isiDesa(); });
  if(selKec.value) isiDesa();

  // ---- Peta poligon titik koordinat ----
  var titikInput=document.getElementById('itr-titik');
  var titik=[]; try{ titik=JSON.parse(titikInput.value)||[]; }catch(e){ titik=[]; }
  var map=L.map('itrMap').setView(titik.length?[titik[0].lat,titik[0].lng]:[-7.7267,109.0154], titik.length?15:11);
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19,attribution:'&copy; OpenStreetMap'}).addTo(map);
  var markers=[], polygon=null, BATAS_CHIP=30;
  var list=document.getElementById('itrTitikList');
  function render(){
    titikInput.value=JSON.stringify(titik);
    markers.forEach(function(m){ map.removeLayer(m); }); markers=[];
    if(polygon){ map.removeLayer(polygon); polygon=null; }
    var banyak=titik.length>100;
    titik.forEach(function(t,i){
      var m=banyak?L.circleMarker([t.lat,t.lng],{radius:4,color:'#c9a24b',weight:2,fillOpacity:.9}):L.marker([t.lat,t.lng]);
      m.addTo(map);
      m.on('click',function(){ titik.splice(i,1); render(); });
      markers.push(m);
    });
    if(titik.length>=3) polygon=L.polygon(titik.map(function(t){return [t.lat,t.lng];}),{color:'#c9a24b'}).addTo(map);
    list.innerHTML='';
    titik.forEach(function(t,i){
      if(i>=BATAS_CHIP) return;
      var chip=document.createElement('span'); chip.className='itr-titik-chip';
      chip.innerHTML='Titik '+(i+1)+': '+t.lat.toFixed(5)+', '+t.lng.toFixed(5)+' <button type="button">×</button>';
      chip.querySelector('button').addEventListener('click',function(){ titik.splice(i,1); render(); });
      list.appendChild(chip);
    });
    if(titik.length>BATAS_CHIP){
      var sisa=document.createElement('span'); sisa.className='itr-titik-chip';
      sisa.textContent='… dan '+(titik.length-BATAS_CHIP)+' titik lainnya';
      list.appendChild(sisa);
    }
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

  // ---- Hapus semua titik ----
  document.getElementById('itr-hapus-semua').addEventListener('click', function(){
    if(!titik.length) return;
    if(!confirm('Hapus seluruh '+titik.length+' titik koordinat?')) return;
    titik=[]; render(); pesan('');
  });

  // ---- Unggah berkas SHP / KML / GeoJSON ----
  // Seluruh titik batas poligon dimuat ke daftar `titik`, lalu ikut terkirim
  // lewat input tersembunyi #itr-titik dan disimpan di kolom titik_koordinat.
  var MAKS_TITIK=1000;
  var fileGeo=document.getElementById('itr-file-geo'), statusGeo=document.getElementById('itr-geo-status');
  function pesan(teks,galat){ statusGeo.textContent=teks; statusGeo.className='itr-geo-status'+(galat?' galat':''); }

  function muatShpjs(){
    if(window.shp) return Promise.resolve();
    return new Promise(function(ok,gagal){
      var s=document.createElement('script');
      s.src='https://cdn.jsdelivr.net/npm/shpjs@4.0.4/dist/shp.min.js';
      s.onload=ok;
      s.onerror=function(){ gagal(new Error('Pembaca shapefile gagal dimuat. Periksa koneksi internet lalu coba lagi.')); };
      document.head.appendChild(s);
    });
  }

  function kumpulGeom(x,out){
    if(!x) return out;
    if(Array.isArray(x)){ x.forEach(function(i){ kumpulGeom(i,out); }); return out; }
    switch(x.type){
      case 'FeatureCollection': (x.features||[]).forEach(function(f){ kumpulGeom(f,out); }); break;
      case 'Feature': kumpulGeom(x.geometry,out); break;
      case 'GeometryCollection': (x.geometries||[]).forEach(function(g){ kumpulGeom(g,out); }); break;
      case 'Polygon': case 'MultiPolygon': case 'LineString': case 'MultiLineString': case 'Point': case 'MultiPoint': out.push(x); break;
    }
    return out;
  }

  function parseKml(teks){
    var dok=new DOMParser().parseFromString(teks,'application/xml');
    if(dok.getElementsByTagName('parsererror').length) throw new Error('Berkas KML tidak valid (isi XML rusak).');
    function koordinat(el){
      var c=el.getElementsByTagName('coordinates')[0]; if(!c) return [];
      return c.textContent.trim().split(/\s+/).map(function(s){ var a=s.split(','); return [parseFloat(a[0]),parseFloat(a[1])]; })
        .filter(function(p){ return isFinite(p[0])&&isFinite(p[1]); });
    }
    var out=[];
    [].forEach.call(dok.getElementsByTagName('Polygon'),function(p){
      out.push({type:'Polygon',coordinates:[koordinat(p.getElementsByTagName('outerBoundaryIs')[0]||p)]});
    });
    [].forEach.call(dok.getElementsByTagName('LineString'),function(l){ out.push({type:'LineString',coordinates:koordinat(l)}); });
    [].forEach.call(dok.getElementsByTagName('Point'),function(p){ var k=koordinat(p); if(k.length) out.push({type:'Point',coordinates:k[0]}); });
    return out;
  }

  function luasRing(r){ var a=0; for(var i=0,j=r.length-1;i<r.length;j=i++){ a+=(r[j][0]+r[i][0])*(r[j][1]-r[i][1]); } return Math.abs(a/2); }
  function pilihTitik(geoms){
    var cincin=[],garis=[],pts=[];
    geoms.forEach(function(g){
      if(g.type==='Polygon') cincin.push(g.coordinates[0]);
      else if(g.type==='MultiPolygon') g.coordinates.forEach(function(p){ cincin.push(p[0]); });
      else if(g.type==='LineString') garis.push(g.coordinates);
      else if(g.type==='MultiLineString') g.coordinates.forEach(function(l){ garis.push(l); });
      else if(g.type==='Point') pts.push(g.coordinates);
      else if(g.type==='MultiPoint') g.coordinates.forEach(function(p){ pts.push(p); });
    });
    cincin=cincin.filter(function(r){ return r&&r.length>=3; });
    garis=garis.filter(function(l){ return l&&l.length>=2; });
    if(cincin.length){ cincin.sort(function(a,b){ return luasRing(b)-luasRing(a); }); return {jenis:'poligon',titik:cincin[0],bidang:cincin.length}; }
    if(garis.length){ garis.sort(function(a,b){ return b.length-a.length; }); return {jenis:'garis',titik:garis[0],bidang:garis.length}; }
    if(pts.length) return {jenis:'titik',titik:pts,bidang:pts.length};
    return null;
  }

  function bersihkan(mentah,jenis){
    var out=[];
    for(var i=0;i<mentah.length;i++){
      var lng=+mentah[i][0], lat=+mentah[i][1];
      if(!isFinite(lat)||!isFinite(lng)) continue;
      if(Math.abs(lat)>90||Math.abs(lng)>180) throw new Error('Koordinat berkas bukan lintang-bujur (WGS84). Untuk shapefile berkoordinat UTM, unggah .zip yang menyertakan berkas .prj agar dikonversi otomatis.');
      lat=Math.round(lat*1e7)/1e7; lng=Math.round(lng*1e7)/1e7;
      var p=out[out.length-1]; if(p&&p.lat===lat&&p.lng===lng) continue;
      out.push({lat:lat,lng:lng});
    }
    if(jenis==='poligon'&&out.length>1&&out[0].lat===out[out.length-1].lat&&out[0].lng===out[out.length-1].lng) out.pop();
    return out;
  }

  // Douglas-Peucker (iteratif) supaya data tetap muat di kolom basis data.
  function sederhanakan(pts,maks){
    function jarak2(p,a,b){
      var dx=b.lng-a.lng, dy=b.lat-a.lat, l=dx*dx+dy*dy;
      if(!l){ var ex=p.lng-a.lng, ey=p.lat-a.lat; return ex*ex+ey*ey; }
      var t=Math.max(0,Math.min(1,((p.lng-a.lng)*dx+(p.lat-a.lat)*dy)/l));
      var cx=a.lng+t*dx-p.lng, cy=a.lat+t*dy-p.lat; return cx*cx+cy*cy;
    }
    function dp(tol){
      var tol2=tol*tol, simpan=new Array(pts.length).fill(false), tumpuk=[[0,pts.length-1]];
      simpan[0]=simpan[pts.length-1]=true;
      while(tumpuk.length){
        var r=tumpuk.pop(), maksJ=0, idx=-1;
        for(var i=r[0]+1;i<r[1];i++){ var d=jarak2(pts[i],pts[r[0]],pts[r[1]]); if(d>maksJ){ maksJ=d; idx=i; } }
        if(idx>-1&&maksJ>tol2){ simpan[idx]=true; tumpuk.push([r[0],idx],[idx,r[1]]); }
      }
      return pts.filter(function(_,i){ return simpan[i]; });
    }
    var tol=1e-7, hasil=pts;
    while(hasil.length>maks&&tol<1){ tol*=2; hasil=dp(tol); }
    return hasil;
  }

  function terapkan(geoms,namaBerkas){
    var pilih=pilihTitik(geoms);
    if(!pilih) throw new Error('Tidak ditemukan poligon, garis, atau titik di dalam berkas.');
    var pts=bersihkan(pilih.titik,pilih.jenis), catatan=[];
    if(pilih.bidang>1) catatan.push('berkas berisi '+pilih.bidang+' objek, yang dipakai: '+(pilih.jenis==='poligon'?'poligon terluas':pilih.jenis==='garis'?'garis terpanjang':'seluruh titik'));
    if(pts.length>MAKS_TITIK){
      var asal=pts.length; pts=sederhanakan(pts,MAKS_TITIK);
      catatan.push('disederhanakan dari '+asal+' menjadi '+pts.length+' titik (batas '+MAKS_TITIK+' titik)');
    }
    if(!pts.length) throw new Error('Tidak ada koordinat valid di dalam berkas.');
    if(titik.length&&!confirm('Titik yang sudah ada ('+titik.length+') akan diganti dengan '+pts.length+' titik dari berkas ini. Lanjutkan?')){ pesan('Dibatalkan.'); return; }
    titik=pts; render();
    map.fitBounds(L.latLngBounds(titik.map(function(t){ return [t.lat,t.lng]; })),{padding:[30,30],maxZoom:19});
    var ket='\u2713 '+namaBerkas+': '+pts.length+' titik '+pilih.jenis+' dimuat ke peta'+(catatan.length?' ('+catatan.join('; ')+')':'')+'.';
    if(pts.length<4){ ket+=' Minimal 4 titik diperlukan — tambahkan titik lain di peta.'; pesan(ket,true); } else pesan(ket);
  }

  fileGeo.addEventListener('change',function(){
    var f=fileGeo.files[0]; if(!f) return;
    var nama=f.name.toLowerCase(), ext=nama.slice(nama.lastIndexOf('.')+1), baca;
    pesan('Membaca '+f.name+' …');
    if(ext==='geojson'||ext==='json'){
      baca=f.text().then(function(t){ try{ return kumpulGeom(JSON.parse(t),[]); }catch(e){ throw new Error('Berkas GeoJSON tidak valid.'); } });
    } else if(ext==='kml'){
      baca=f.text().then(parseKml);
    } else if(ext==='zip'||ext==='shp'){
      baca=Promise.all([f.arrayBuffer(),muatShpjs()]).then(function(h){
        return ext==='zip'?Promise.resolve(shp.parseZip(h[0])):shp.parseShp(h[0]);
      }).then(function(h){ return kumpulGeom(h,[]); });
    } else {
      baca=Promise.reject(new Error('Format .'+ext+' tidak didukung. Gunakan .zip (shapefile), .shp, .kml, atau .geojson.'));
    }
    baca.then(function(geoms){ terapkan(geoms,f.name); })
      .catch(function(e){ pesan(e&&e.message?e.message:'Berkas tidak dapat dibaca.',true); })
      .then(function(){ fileGeo.value=''; });
  });
})();
</script>
