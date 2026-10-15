<?php $heading='Pengajuan PBG'; $top_actions='<a class="btn btn-primary" href="'.base_url('pengajuan-pbg/tambah').'">+ Tambah Pengajuan</a>'; $this->load->view('pbg_pu/header',compact('heading','top_actions','nama_pengguna')); ?>
<style>
.pbg-list-card{padding:32px}.pbg-list-toolbar{display:flex;align-items:center;justify-content:space-between;gap:24px;margin-bottom:24px}.pbg-list-toolbar .eyebrow{margin:0}.pbg-list-toolbar .search{flex:0 1 390px}.pbg-list-table{table-layout:auto;width:100%}.pbg-list-table th,.pbg-list-table td{padding:18px 16px;vertical-align:middle}.pbg-list-table .aksi-select{width:100%;min-width:150px}.pbg-list-table .pl-no{display:block;font-weight:600;color:var(--text);white-space:nowrap}.pbg-list-table .pl-nama{display:block;font-weight:500;color:var(--text)}.pbg-list-table .pl-sub{display:block;white-space:nowrap;margin-top:3px;font-size:12px;color:var(--muted);letter-spacing:.02em;font-variant-numeric:tabular-nums}
@media(min-width:821px){.pbg-list-table{min-width:880px}.pbg-list-table th{white-space:nowrap}.pbg-list-table td:nth-child(2){min-width:190px}.pbg-list-table td:nth-child(3){min-width:200px;max-width:340px}.pbg-list-table td:nth-child(4){width:178px}.pbg-list-table td:nth-child(5){width:176px}}.pbg-list-table .aksi-select{padding:10px 12px;font-size:12px;border:1px solid var(--line);background:#fff;color:var(--text)}.pbg-list-table .locked{display:block;margin-top:6px;color:#8b9da8;font-size:12px}.pbg-list-empty{text-align:center;padding:46px!important}
@media(max-width:1250px){.pbg-list-table th,.pbg-list-table td{padding:15px 12px}}
@media(max-width:820px){.pbg-list-table{min-width:0}.pbg-list-table .aksi-select{min-width:0}.pbg-list-table .pl-no,.pbg-list-table .pl-sub{white-space:normal;overflow-wrap:anywhere}.pbg-list-card{padding:18px}.pbg-list-toolbar{align-items:stretch;flex-direction:column}.pbg-list-toolbar .search{flex:auto;width:100%}.pbg-list-table,.pbg-list-table tbody,.pbg-list-table tr,.pbg-list-table td{display:block;width:100%}.pbg-list-table thead{display:none}.pbg-list-table tr{padding:18px;border-bottom:1px solid var(--line)}.pbg-list-table td{display:grid;grid-template-columns:96px 1fr;gap:12px;padding:8px 0;border:0}.pbg-list-table td:before{content:attr(data-label);color:var(--gold);font:400 12px var(--display);letter-spacing:.05em;text-transform:uppercase}.pbg-list-table td:last-child{display:grid}.pbg-list-table td:last-child .aksi-select{grid-column:2}.pbg-list-empty{display:block!important}.pbg-list-empty:before{display:none}}
</style>
<?php if($this->session->flashdata('sukses')):?><div class="notice"><?= htmlspecialchars($this->session->flashdata('sukses')) ?></div><?php endif;?>
<div class="card pbg-list-card"><div class="pbg-list-toolbar"><p class="eyebrow">Daftar Permohonan</p><input class="search" id="q" placeholder="Cari nama, nomor registrasi, atau keterangan..."></div><div class="table-wrap"><table id="table" class="pbg-list-table"><thead><tr><th>No. Permohonan</th><th>Pemohon</th><th>Keterangan</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php if(empty($daftar)):?><tr><td class="pbg-list-empty" colspan="5">Belum ada pengajuan. Klik <b>+ Tambah Pengajuan</b> untuk mulai.</td></tr><?php endif;?>
<?php foreach($daftar as $r):?><tr><td data-label="No. Permohonan"><div><span class="pl-no"><?= htmlspecialchars($r['no_permohonan']) ?></span><small class="pl-sub"><?= date('d/m/Y',strtotime($r['created_at'])) ?></small></div></td><td data-label="Pemohon"><div><span class="pl-nama"><?= htmlspecialchars($r['nama_pemohon']) ?></span><small class="pl-sub">Reg. <?= htmlspecialchars($r['nik']?:'—') ?></small></div></td><td data-label="Keterangan"><?= htmlspecialchars($r['keterangan']?:'—') ?></td><td data-label="Status"><span class="badge <?= $r['status'] ?>"><?= pbg_status_label($r['status'], $r['tahap']) ?></span></td><td data-label="Aksi"><select class="aksi-select" onchange="pbgAksi(this)">
  <option value="">Pilih Aksi</option>
  <?php if(!($r['status']==='disetujui'||($r['status']==='diverifikasi'&&(int)$r['tahap']>=4))):?><option value="<?= base_url('pengajuan-pbg/tahap/'.$r['id']) ?>">Proses Pengajuan</option><?php endif;?>
  <?php if(!empty($ba_terbaru[$r['id']])):?><option value="<?= base_url('pengajuan-pbg/berita-acara/'.$ba_terbaru[$r['id']]) ?>" data-blank="1">Unduh BA Terbaru</option><?php endif;?>
  <?php if(!in_array($r['status'],array('disetujui','ditolak'),TRUE)):?><option value="<?= base_url('pengajuan-pbg/upload-berkas/'.$r['id']) ?>">Upload Berkas</option><?php endif;?>
  <?php if($r['status']==='diajukan'):?>
  <option value="<?= base_url('pengajuan-pbg/edit/'.$r['id']) ?>">Edit</option>
  <option value="<?= base_url('pengajuan-pbg/hapus/'.$r['id']) ?>" data-confirm="Hapus pengajuan ini?">Hapus</option>
  <?php endif;?>
</select><?php if($r['status']!=='diajukan'):?><span class="locked">Terkunci</span><?php endif;?></td></tr><?php endforeach;?></tbody></table></div></div>
<script>
q.oninput=()=>{let x=q.value.toLowerCase();document.querySelectorAll('#table tbody tr').forEach(r=>r.style.display=r.innerText.toLowerCase().includes(x)?'':'none')}
function pbgAksi(sel){
  var opt=sel.options[sel.selectedIndex];
  if(!opt.value) return;
  if(opt.dataset.confirm && !confirm(opt.dataset.confirm)){ sel.selectedIndex=0; return; }
  if(opt.dataset.blank){ window.open(opt.value,'_blank'); }
  else { location.href=opt.value; }
  sel.selectedIndex=0;
}
</script>
<?php $this->load->view('pbg_pu/footer'); ?>
