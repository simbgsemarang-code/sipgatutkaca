<?php $heading='Upload Berkas PBG'; $top_actions=''; $this->load->view('pbg_pu/header',compact('heading','top_actions','nama_pengguna')); ?>
<div class="card form-card">
<a href="<?= base_url('pengajuan-pbg') ?>">← Kembali ke Pengajuan PBG</a>
<h2 class="section-title">Upload Berkas</h2>
<div class="notice"><b><?= htmlspecialchars($row['nama_pemohon']) ?></b> · <?= htmlspecialchars($row['nik']?:'Nomor registrasi belum diisi') ?><br><small><?= htmlspecialchars($row['no_permohonan']) ?></small></div>
<?php foreach($errors as $e):?><div class="notice" style="background:#fce0e0"><?= htmlspecialchars($e) ?></div><?php endforeach;?>
<?= form_open_multipart(current_url()) ?>
<div class="grid"><?php foreach($files as $field=>$label):?><div class="upload"><label><?= htmlspecialchars($label) ?></label><?php if(!empty($row[$field])):?><small>Berkas sudah tersedia; unggah kembali untuk mengganti.</small><?php endif;?><input type="file" name="<?= htmlspecialchars($field) ?>" accept=".jpg,.jpeg,.png,.pdf"></div><?php endforeach;?></div>
<?php if(!empty($mode_perbaikan)):?><div style="margin-top:24px"><label>Catatan Perbaikan</label><textarea name="catatan_perbaikan" required placeholder="Jelaskan berkas yang telah diperbaiki."></textarea></div><?php endif;?>
<div style="margin-top:28px;display:flex;gap:10px"><button class="btn btn-primary"><?= !empty($mode_perbaikan)?'Kirim Perbaikan':'Upload Berkas' ?></button><a class="btn" href="<?= base_url('pengajuan-pbg') ?>">Batal</a></div>
<?= form_close() ?>
</div>
<?php $this->load->view('pbg_pu/footer'); ?>
