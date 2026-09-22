<?php $heading='Kelola Pengajuan ITR';$top_actions='';$this->load->view('pbg_pu/header',compact('heading','top_actions','nama_pengguna')); ?>
<?php if($this->session->flashdata('sukses')): ?><div class="notice"><?= htmlspecialchars($this->session->flashdata('sukses')) ?></div><?php endif; ?>
<?php if($this->session->flashdata('error')): ?><div class="notice" style="background:#fce0e0"><?= htmlspecialchars($this->session->flashdata('error')) ?></div><?php endif; ?>
<p>Daftar seluruh pengajuan ITR. Klik "Proses" untuk melihat biodata lengkap, meninjau tiap berkas, dan menerbitkan hasil ITR.</p>
<?php if(!$daftar): ?><div class="notice">Belum ada pengajuan ITR.</div><?php else: ?>
<style>
.itr-list-table{width:100%;border-collapse:collapse}
.itr-list-table th,.itr-list-table td{padding:14px 12px;text-align:left;border-bottom:1px solid var(--line);vertical-align:top}
.itr-list-table th{font-size:12px;text-transform:uppercase;letter-spacing:.05em;color:var(--muted)}
.itr-list-table td small{display:block;color:var(--muted);margin-top:2px}
</style>
<div class="card" style="overflow:auto">
<table class="itr-list-table">
<thead><tr><th>No. Permohonan</th><th>Nama Pemilik</th><th>Alamat</th><th>Fungsi Bangunan</th><th>Status</th><th>Aksi</th></tr></thead>
<tbody>
<?php foreach($daftar as $r): $perusahaan=($r['jenis_pemohon']??'perorangan')==='perusahaan'; ?>
<tr>
 <td><?= htmlspecialchars($r['no_permohonan']) ?><small><?= date('d/m/Y',strtotime($r['created_at'])) ?></small></td>
 <td><?= htmlspecialchars($r['nama_pemohon']) ?><small><?= $perusahaan?'Perusahaan':'Perorangan' ?></small></td>
 <td><?= htmlspecialchars($r['alamat_pemohon']?:'—') ?></td>
 <td><?= htmlspecialchars($r['fungsi_bangunan']?:'—') ?></td>
 <td><span class="badge <?= $r['status']==='disetujui'?'disetujui':($r['status']==='perlu_perbaikan'||$r['status']==='ditolak'?'ditolak':'diajukan') ?>"><?= ucwords(str_replace('_',' ',$r['status'])) ?></span></td>
 <td><a class="btn" href="<?= base_url('admin_itr/detail/'.$r['id']) ?>">Proses</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php endif; ?>
<script>document.querySelector('.side nav').innerHTML=<?= json_encode('<a href="'.base_url('admin').'">▦ Dashboard</a><a class="active" href="'.base_url('admin_itr').'">▤ Pengajuan ITR</a><a href="'.base_url('admin/pengguna').'">Kelola Pengguna</a>',JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) ?>;document.querySelector('.top .eyebrow').textContent='Portal Administrator';</script>
<?php $this->load->view('pbg_pu/footer'); ?>
