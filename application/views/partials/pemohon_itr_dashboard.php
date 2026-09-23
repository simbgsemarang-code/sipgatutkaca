<p class="eyebrow">Portal Pemohon ITR</p><h2>Dashboard</h2>
<p class="section-lead">Selamat datang, <?= htmlspecialchars($nama_pengguna,ENT_QUOTES,'UTF-8') ?>. Pantau pengajuan dan aktivitas akun Anda di sini.</p>
<?php if($this->session->flashdata('sukses')): ?><div class="notice"><?= htmlspecialchars($this->session->flashdata('sukses'),ENT_QUOTES,'UTF-8') ?></div><?php endif; ?>
<div class="itr-summary"><article><strong><?= count($pengajuan_itr) ?></strong><span>Total Pengajuan</span></article><article><strong><?= count(array_filter($pengajuan_itr,function($r){return $r['status']==='diajukan';})) ?></strong><span>Diajukan</span></article></div>
<a class="btn btn-gold" href="<?= base_url('pemohon/pengajuan_itr') ?>">+ PENGAJUAN ITR</a>
<style>
.dash-wrap .info-card table{display:table;width:100%;max-width:none;table-layout:fixed;border-collapse:collapse}.dash-wrap .info-card th,.dash-wrap .info-card td{padding:18px 16px;text-align:left;vertical-align:middle;overflow-wrap:anywhere}.dash-wrap .info-card th:nth-child(1){width:15%}.dash-wrap .info-card th:nth-child(2){width:14%}.dash-wrap .info-card th:nth-child(3){width:10%}.dash-wrap .info-card th:nth-child(4){width:23%}.dash-wrap .info-card th:nth-child(5){width:13%}.dash-wrap .info-card th:nth-child(6){width:9%}.dash-wrap .info-card th:nth-child(7){width:16%}@media(max-width:900px){.dash-wrap .info-card table{min-width:960px}}
</style>
<div class="info-card"><h3>Riwayat Pengajuan ITR</h3><?php if(!$pengajuan_itr): ?><p>Belum ada pengajuan. Mulai melalui menu PENGAJUAN ITR.</p><?php else: ?><div style="overflow:auto"><table><thead><tr><th>Hasil ITR</th><th>No. Permohonan</th><th>Jenis</th><th>Lokasi</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php foreach($pengajuan_itr as $r): $perusahaan=($r['jenis_pemohon']??'perorangan')==='perusahaan'; $berkas=array('file_permohonan'=>'Permohonan','file_ktp'=>'KTP','file_sertifikat'=>'Sertifikat','file_siteplan'=>'Site plan','file_denah_foto'=>'Denah & Foto'); if($perusahaan)$berkas+=array('file_nib'=>'NIB','file_npwp'=>'NPWP','file_akta'=>'Akta'); $kurang=0; foreach($berkas as $field=>$label){ if(empty($r[$field])) $kurang++; } ?><tr><td><?php if(!empty($r['file_hasil_itr'])): ?><a class="btn btn-gold itr-btn-xs" href="<?= base_url('pemohon/hasil_itr/'.$r['id']) ?>" target="_blank">Unduh Hasil ITR</a><?php else: ?><span style="color:var(--muted)">Belum tersedia</span><?php endif; ?></td><td><?= htmlspecialchars($r['no_permohonan']) ?></td><td><?= $perusahaan?'Perusahaan':'Perorangan' ?></td><td><?= htmlspecialchars($r['alamat_lokasi']) ?></td><td><?= date('d/m/Y H:i',strtotime($r['created_at'])) ?></td><td><?= htmlspecialchars(ucfirst($r['status'])) ?></td><td><div class="itr-aksi-cell">
  <a class="btn btn-ghost itr-btn-xs" href="<?= base_url('pemohon/upload-berkas-itr/'.$r['id']) ?>"><?= $kurang?'Lengkapi Berkas ('.$kurang.' kurang)':'Kelola Berkas' ?></a>
  <?php if($r['status']==='diajukan'): ?>
  <select class="itr-aksi-select" onchange="itrAksi(this)">
    <option value="">Aksi Lain</option>
    <option value="<?= base_url('pemohon/edit-itr/'.$r['id']) ?>">Edit</option>
    <option value="<?= base_url('pemohon/hapus-itr/'.$r['id']) ?>" data-confirm="Hapus pengajuan <?= htmlspecialchars($r['no_permohonan'],ENT_QUOTES,'UTF-8') ?>?">Hapus</option>
  </select>
  <?php endif; ?>
</div></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></div>
<script>
function itrAksi(sel){
  var opt=sel.options[sel.selectedIndex];
  if(!opt.value) return;
  if(opt.dataset.confirm && !confirm(opt.dataset.confirm)){ sel.selectedIndex=0; return; }
  location.href=opt.value;
  sel.selectedIndex=0;
}
</script>
<?php $this->load->view('partials/pemohon_itr_pesan'); ?>
<div class="info-card"><h3>Aktivitas Anda</h3><?php if(!$aktivitas_itr): ?><p>Belum ada aktivitas pengajuan tercatat.</p><?php else: ?><ul class="itr-activity"><?php foreach($aktivitas_itr as $activity): ?><li><p><?= htmlspecialchars($activity['keterangan']) ?></p><small><?= date('d/m/Y H:i',strtotime($activity['created_at'])) ?></small></li><?php endforeach; ?></ul><?php endif; ?></div>
<style>.notice{padding:16px 20px;border-radius:12px;border:1px solid var(--line);background:var(--surface-hi);margin:20px 0;font-size:14px}
.itr-summary{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin:28px 0}.itr-summary article{padding:24px;border:1px solid var(--line);border-radius:16px;background:var(--surface)}.itr-summary strong{display:block;font:400 40px var(--display);color:var(--gold-300)}.itr-summary span{font-size:14px;color:var(--muted)}.itr-activity{list-style:none;padding:0}.itr-activity li{padding:16px 0;border-bottom:1px solid var(--line)}.itr-activity p{margin:0 0 4px}.itr-activity small{color:var(--muted)}.itr-aksi-select{padding:10px 12px;font-size:12px;border:1px solid var(--line);border-radius:8px;background:var(--surface);color:var(--text);max-width:100%}
.itr-aksi-cell{display:flex;flex-direction:column;align-items:flex-start;gap:8px}
.itr-btn-xs{padding:9px 16px!important;font-size:11px!important;letter-spacing:.12em!important;border-radius:8px;white-space:normal;text-align:center}@media(max-width:600px){.itr-summary{grid-template-columns:1fr}}</style>
