<?php $heading='Kelola Pengajuan ITR';$top_actions='';$this->load->view('pbg_pu/header',compact('heading','top_actions','nama_pengguna')); ?>
<?php if($this->session->flashdata('sukses')): ?><div class="notice"><?= htmlspecialchars($this->session->flashdata('sukses')) ?></div><?php endif; ?>
<?php if($this->session->flashdata('error')): ?><div class="notice" style="background:#fce0e0"><?= htmlspecialchars($this->session->flashdata('error')) ?></div><?php endif; ?>
<p>Periksa dokumen, terima/tolak tiap berkas, dan unggah dokumen hasil ITR setelah semua berkas lengkap. Pesan tetap tersimpan dan dapat dipantau status bacanya.</p>
<?php if(!$daftar): ?><div class="notice">Belum ada pengajuan ITR.</div><?php endif; ?>
<style>
.itr-review-list{display:grid;gap:14px;margin:16px 0 28px}
.itr-review-row{padding:16px 18px;border:1px solid var(--line);border-radius:10px;background:#fbfdfd}
.itr-review-row.itr-review-diterima{background:#f2fbf5;border-color:#b7dfc3}
.itr-review-row.itr-review-ditolak{background:#fff3f4;border-color:#f0b7bf}
.itr-review-row.itr-review-kosong{display:flex;align-items:center;justify-content:space-between;gap:12px;opacity:.7}
.itr-review-head{display:flex;align-items:center;gap:12px;flex-wrap:wrap}
.itr-review-catatan{margin:8px 0 0;color:#c0384a;font-size:13px}
.itr-review-form{display:flex;gap:12px;align-items:flex-start;margin-top:12px;flex-wrap:wrap}
.itr-review-form textarea{flex:1;min-width:220px;min-height:44px;padding:10px;border:1px solid var(--line)}
.itr-review-actions{display:flex;gap:8px;flex-shrink:0}
</style>
<?php foreach($daftar as $r): $perusahaan=($r['jenis_pemohon']??'perorangan')==='perusahaan'; ?><article class="card" style="margin-bottom:24px">
<h2><?= htmlspecialchars($r['no_permohonan']) ?></h2><p><span class="badge <?= $perusahaan?'diverifikasi':'diajukan' ?>"><?= $perusahaan?'Perusahaan':'Perorangan' ?></span> <strong><?= htmlspecialchars($r['nama_pemohon']) ?></strong><?= $perusahaan?' (Direktur)':'' ?> · <?= htmlspecialchars($r['email']) ?> · <?= htmlspecialchars($r['no_hp']) ?></p>
<div class="detail-grid">
<?php if($perusahaan): ?><div><small>NIB</small><?= htmlspecialchars($r['nib']?:'—') ?></div><?php else: ?><div><small>NIK</small><?= htmlspecialchars($r['nik']?:'—') ?></div><div><small>Pekerjaan</small><?= htmlspecialchars($r['pekerjaan']?:'—') ?></div><?php endif; ?>
<div><small>No. NPWP</small><?= htmlspecialchars($r['no_npwp']?:'—') ?></div>
<div class="full"><small><?= $perusahaan?'Alamat Perusahaan':'Alamat Pemohon' ?></small><?= htmlspecialchars($r['alamat_pemohon']?:'—') ?></div>
<div><small>Jenis Kegiatan/Usaha</small><?= htmlspecialchars($r['jenis_kegiatan']?:'—') ?></div>
<div><small>Fungsi Bangunan</small><?= htmlspecialchars($r['fungsi_bangunan']?:'—') ?></div>
<div class="full"><small>Lokasi Kegiatan</small><?= htmlspecialchars(trim(($r['lokasi_jalan']?:'').', RT/RW '.($r['lokasi_rt_rw']?:'-').', '.($r['lokasi_desa_kel']?:'').', Kec. '.($r['lokasi_kecamatan']?:''),' ,')) ?></div>
<div><small>Luas Lahan</small><?= number_format($r['luas_lahan'],2,',','.') ?> m²</div>
<div><small>Luas Bangunan</small><?= $r['luas_bangunan']!==null?number_format($r['luas_bangunan'],2,',','.').' m²':'—' ?></div>
<div><small>Lantai Bangunan</small><?= htmlspecialchars($r['lantai_bangunan']?:'—') ?></div>
<div><small>Status Tanah</small><?= htmlspecialchars($r['status_tanah']?:'—') ?></div>
<div><small>Penggunaan Air Baku</small><?= htmlspecialchars($r['penggunaan_air']?:'—') ?></div>
<div><small>Keterangan Perijinan</small><?= htmlspecialchars($r['keterangan_perijinan']?:'—') ?></div>
<div class="full"><small>Titik Koordinat Poligon</small><?php $titik=json_decode((string)($r['titik_koordinat']??''),TRUE); if(is_array($titik)&&$titik): ?><?= implode(' · ',array_map(function($t){return number_format($t['lat'],6).', '.number_format($t['lng'],6);},$titik)) ?><?php else: ?><?= htmlspecialchars($r['latitude'].', '.$r['longitude']) ?> (pusat)<?php endif; ?></div>
</div>
<h3 class="section-title">Berkas &amp; Tinjauan</h3>
<div class="itr-review-list"><?php $berkas=array('file_permohonan'=>'Surat Permohonan','file_ktp'=>'KTP','file_sertifikat'=>'Sertifikat','file_siteplan'=>'Site plan','file_denah_foto'=>'Denah &amp; Foto'); if($perusahaan) $berkas+=array('file_nib'=>'NIB','file_npwp'=>'NPWP','file_akta'=>'Akta Perusahaan'); foreach($berkas as $field=>$label): if(empty($r[$field])): ?><div class="itr-review-row itr-review-kosong"><b><?= $label ?></b><span class="badge diajukan">Belum diunggah</span></div><?php continue; endif; $st=$r['_status_berkas'][$field]??null; $status=$st['status']??'menunggu'; ?>
<div class="itr-review-row itr-review-<?= $status ?>">
  <div class="itr-review-head"><b><?= $label ?></b><span class="badge <?= $status==='diterima'?'disetujui':($status==='ditolak'?'ditolak':'diverifikasi') ?>"><?= ucfirst($status) ?></span><a class="btn" target="_blank" href="<?= base_url('admin_itr/berkas/'.$r['id'].'/'.$field) ?>">Lihat Berkas</a></div>
  <?php if($status==='ditolak'&&!empty($st['catatan'])): ?><p class="itr-review-catatan">Alasan sebelumnya: <?= nl2br(htmlspecialchars($st['catatan'])) ?></p><?php endif; ?>
  <?= form_open('admin_itr/tinjau-berkas/'.$r['id'],array('class'=>'itr-review-form')) ?>
  <input type="hidden" name="itr_token" value="<?= htmlspecialchars($this->session->userdata('admin_itr_token'),ENT_QUOTES,'UTF-8') ?>">
  <input type="hidden" name="field" value="<?= htmlspecialchars($field,ENT_QUOTES,'UTF-8') ?>">
  <textarea name="catatan" placeholder="Alasan penolakan (wajib kalau memilih Tolak)"></textarea>
  <div class="itr-review-actions"><button type="submit" name="keputusan" value="diterima" class="btn btn-primary">Terima</button><button type="submit" name="keputusan" value="ditolak" class="btn btn-danger">Tolak</button></div>
  <?= form_close() ?>
</div>
<?php endforeach; ?></div>

<h3 class="section-title">Dokumen Hasil ITR</h3>
<?php if($r['_semua_diterima']): ?>
  <?php if(!empty($r['file_hasil_itr'])): ?><div class="notice">Sudah diterbitkan <?= !empty($r['hasil_diunggah_pada'])?('· '.date('d/m/Y H:i',strtotime($r['hasil_diunggah_pada']))):'' ?> — <a target="_blank" href="<?= base_url('admin_itr/hasil/'.$r['id']) ?>">Lihat/Unduh</a>. Unggah file baru di bawah untuk menggantinya.</div><?php else: ?><div class="notice">Semua berkas sudah diterima. Unggah dokumen resmi hasil ITR (PDF) supaya bisa diunduh pemohon.</div><?php endif; ?>
  <?= form_open_multipart('admin_itr/unggah-hasil/'.$r['id']) ?><input type="hidden" name="itr_token" value="<?= htmlspecialchars($this->session->userdata('admin_itr_token'),ENT_QUOTES,'UTF-8') ?>">
  <input type="file" name="file_hasil_itr" accept=".pdf" required><button class="btn btn-primary" style="margin-top:12px"><?= empty($r['file_hasil_itr'])?'Unggah Hasil ITR':'Ganti Hasil ITR' ?></button><?= form_close() ?>
<?php else: ?><div class="notice itr-review-kosong">Unggah dokumen hasil ITR tersedia setelah semua berkas di atas berstatus Diterima.</div><?php endif; ?>

<h3 class="section-title">Status &amp; Informasi Bebas</h3>
<?= form_open('admin_itr/simpan/'.$r['id']) ?><input type="hidden" name="itr_token" value="<?= htmlspecialchars($this->session->userdata('admin_itr_token'),ENT_QUOTES,'UTF-8') ?>">
<label for="status-<?= (int)$r['id'] ?>">Status Pengajuan (otomatis mengikuti tinjauan berkas, bisa ditimpa manual)</label><select id="status-<?= (int)$r['id'] ?>" name="status"><?php foreach(array('diajukan'=>'Diajukan','sedang_diverifikasi'=>'Sedang diverifikasi','perlu_perbaikan'=>'Perlu perbaikan','disetujui'=>'Disetujui','ditolak'=>'Ditolak') as $code=>$label): ?><option value="<?= $code ?>" <?= $r['status']===$code?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select>
<label for="pesan-<?= (int)$r['id'] ?>" style="margin-top:18px">Informasi untuk Pemohon</label><textarea id="pesan-<?= (int)$r['id'] ?>" name="informasi" maxlength="10000" placeholder="Tuliskan hasil pemeriksaan, permintaan perbaikan, atau informasi berikutnya."></textarea><button class="btn btn-primary" style="margin-top:16px">Simpan dan Kirim Informasi</button><?= form_close() ?>
<h3>Riwayat Informasi</h3><?php $ada=false;foreach($pesan as $p): if((int)$p['pengajuan_id']!==(int)$r['id'])continue;$ada=true; ?><div class="notice"><strong><?= htmlspecialchars($p['nama_admin']?:'Administrator') ?></strong> · <?= date('d/m/Y H:i',strtotime($p['created_at'])) ?><p><?= nl2br(htmlspecialchars($p['isi'])) ?></p><small><?= $p['dibaca_pada']?'Sudah dibaca pemohon · '.date('d/m/Y H:i',strtotime($p['dibaca_pada'])):'Belum dibaca pemohon' ?></small></div><?php endforeach;if(!$ada): ?><p>Belum ada informasi terkirim.</p><?php endif; ?></article><?php endforeach; ?>
<script>document.querySelector('.side nav').innerHTML=<?= json_encode('<a href="'.base_url('admin').'">▦ Dashboard</a><a class="active" href="'.base_url('admin_itr').'">▤ Pengajuan ITR</a><a href="'.base_url('admin/pengguna').'">Kelola Pengguna</a>',JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) ?>;document.querySelector('.top .eyebrow').textContent='Portal Administrator';</script>
<?php $this->load->view('pbg_pu/footer'); ?>
