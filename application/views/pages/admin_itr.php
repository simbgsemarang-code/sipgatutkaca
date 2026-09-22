<?php $heading='Kelola Pengajuan ITR';$top_actions='';$this->load->view('pbg_pu/header',compact('heading','top_actions','nama_pengguna')); ?>
<?php if($this->session->flashdata('sukses')): ?><div class="notice"><?= htmlspecialchars($this->session->flashdata('sukses')) ?></div><?php endif; ?>
<p>Periksa dokumen, perbarui status, dan kirim informasi kepada pemohon. Pesan tetap tersimpan dan dapat dipantau status bacanya.</p>
<?php if(!$daftar): ?><div class="notice">Belum ada pengajuan ITR.</div><?php endif; ?>
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
<div class="actions" style="margin:20px 0"><?php $berkas=array('file_permohonan'=>'Permohonan','file_ktp'=>'KTP','file_sertifikat'=>'Sertifikat','file_siteplan'=>'Site plan','file_denah_foto'=>'Denah &amp; Foto'); if($perusahaan) $berkas+=array('file_nib'=>'NIB','file_npwp'=>'NPWP','file_akta'=>'Akta Perusahaan'); foreach($berkas as $field=>$label): if(empty($r[$field]))continue; ?><a class="btn" href="<?= base_url('admin_itr/berkas/'.$r['id'].'/'.$field) ?>"><?= $label ?></a><?php endforeach; ?></div>
<?= form_open('admin_itr/simpan/'.$r['id']) ?><input type="hidden" name="itr_token" value="<?= htmlspecialchars($this->session->userdata('admin_itr_token'),ENT_QUOTES,'UTF-8') ?>">
<label for="status-<?= (int)$r['id'] ?>">Status Pengajuan</label><select id="status-<?= (int)$r['id'] ?>" name="status"><?php foreach(array('diajukan'=>'Diajukan','sedang_diverifikasi'=>'Sedang diverifikasi','perlu_perbaikan'=>'Perlu perbaikan','disetujui'=>'Disetujui','ditolak'=>'Ditolak') as $code=>$label): ?><option value="<?= $code ?>" <?= $r['status']===$code?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select>
<label for="pesan-<?= (int)$r['id'] ?>" style="margin-top:18px">Informasi untuk Pemohon</label><textarea id="pesan-<?= (int)$r['id'] ?>" name="informasi" maxlength="10000" placeholder="Tuliskan hasil pemeriksaan, permintaan perbaikan, atau informasi berikutnya."></textarea><button class="btn btn-primary" style="margin-top:16px">Simpan dan Kirim Informasi</button><?= form_close() ?>
<h3>Riwayat Informasi</h3><?php $ada=false;foreach($pesan as $p): if((int)$p['pengajuan_id']!==(int)$r['id'])continue;$ada=true; ?><div class="notice"><strong><?= htmlspecialchars($p['nama_admin']?:'Administrator') ?></strong> · <?= date('d/m/Y H:i',strtotime($p['created_at'])) ?><p><?= nl2br(htmlspecialchars($p['isi'])) ?></p><small><?= $p['dibaca_pada']?'Sudah dibaca pemohon · '.date('d/m/Y H:i',strtotime($p['dibaca_pada'])):'Belum dibaca pemohon' ?></small></div><?php endforeach;if(!$ada): ?><p>Belum ada informasi terkirim.</p><?php endif; ?></article><?php endforeach; ?>
<script>document.querySelector('.side nav').innerHTML=<?= json_encode('<a href="'.base_url('admin').'">▦ Dashboard</a><a class="active" href="'.base_url('admin_itr').'">▤ Pengajuan ITR</a><a href="'.base_url('admin/pengguna').'">Kelola Pengguna</a>',JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) ?>;document.querySelector('.top .eyebrow').textContent='Portal Administrator';</script>
<?php $this->load->view('pbg_pu/footer'); ?>
