<?php $heading='Upload Berkas PBG'; $top_actions=''; $this->load->view('pbg_pu/header',compact('heading','top_actions','nama_pengguna')); ?>
<div class="card upload-page-card">
<a href="<?= base_url('pengajuan-pbg/tahap/'.$row['id']) ?>">← Kembali ke Detail Pengajuan</a>
<div class="upload-page-heading"><div><p class="eyebrow">Dokumen Persyaratan</p><h2>Upload Berkas</h2><p>Pilih berkas pada masing-masing bidang. Berkas langsung diunggah setelah dipilih.</p></div><div class="upload-applicant"><b><?= htmlspecialchars($row['nama_pemohon']) ?></b><span><?= htmlspecialchars($row['nik']?:'Nomor registrasi belum diisi') ?></span><small><?= htmlspecialchars($row['no_permohonan']) ?></small></div></div>
<?php if($this->session->flashdata('sukses')):?><div class="notice"><?= htmlspecialchars($this->session->flashdata('sukses')) ?></div><?php endif;?>
<?php if((int)$row['tahap']===3):?><div class="notice" style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap"><span>Setelah semua berkas perbaikan diunggah, lanjutkan ke halaman konsultasi untuk mengajukan putaran berikutnya.</span><a class="btn btn-primary" href="<?= base_url('pengajuan-pbg/tahap/'.$row['id'].'/3') ?>">Lanjut Ajukan Konsultasi →</a></div><?php endif;?>
<?php foreach($errors as $e):?><div class="notice upload-error"><?= htmlspecialchars($e) ?></div><?php endforeach;?>
<div class="document-upload-list"><?php foreach($files as $field=>$label): $ada=!empty($row[$field]); ?>
<?= form_open_multipart(current_url(),array('class'=>'document-upload-row '.($ada?'is-ready':'is-missing'))) ?><div class="upload-state" aria-hidden="true"><?= $ada?'✓':'!' ?></div><div class="upload-name"><b><?= htmlspecialchars($label) ?></b><span>Wajib</span></div><div class="upload-actions"><small class="upload-status"></small><?php if($ada):?><a class="view-upload" target="_blank" rel="noopener" href="<?= berkas_href($row[$field],'assets/uploads/pbg/') ?>">Lihat Berkas</a><?php endif;?><label class="btn btn-upload"><span><?= $ada?'Upload Ulang':'Upload' ?></span><input class="instant-upload" type="file" name="<?= htmlspecialchars($field) ?>" accept=".jpg,.jpeg,.png,.pdf" aria-label="Upload <?= htmlspecialchars($label) ?>"></label></div><div class="upload-progress" aria-hidden="true"><div class="upload-progress-fill"></div></div><?= form_close() ?>
<?php endforeach;?></div><p class="upload-help">Format JPG, PNG, atau PDF · maksimum 100 MB per berkas.</p>
</div>
<style>.upload-page-card{padding:34px}.upload-page-heading{display:flex;justify-content:space-between;align-items:flex-start;gap:28px;margin:28px 0}.upload-page-heading h2{margin:6px 0 8px;font-size:36px}.upload-page-heading p{margin:0;color:var(--muted)}.upload-applicant{min-width:260px;padding:18px;border:1px solid var(--line);display:grid;gap:4px}.upload-applicant span,.upload-applicant small{color:var(--muted)}.upload-error{background:#fce0e0}.document-upload-list{display:grid;gap:12px}.document-upload-row{position:relative;overflow:hidden;display:flex;align-items:center;gap:18px;padding:15px 18px;border:1px solid;border-radius:13px}
.upload-status{color:#137d86;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;min-width:0}
.upload-progress{position:absolute;left:0;right:0;bottom:0;height:4px;background:rgba(19,125,134,.12);display:none;overflow:hidden}
.document-upload-row.is-uploading .upload-progress{display:block}
.upload-progress-fill{height:100%;width:0%;background:#137d86;transition:width .15s linear}
.upload-progress-fill.indeterminate{width:30%!important;animation:upload-indeterminate 1.1s ease-in-out infinite}
@keyframes upload-indeterminate{0%{margin-left:-30%}100%{margin-left:100%}}.document-upload-row.is-missing{background:#fff7f7;border-color:#efbcbc}.document-upload-row.is-ready{background:#f2fbf5;border-color:#b7dfc3}.upload-state{width:38px;height:38px;border-radius:50%;display:grid;place-items:center;flex:0 0 38px;color:#fff;font-size:23px;font-weight:700}.is-missing .upload-state{background:#cf4141}.is-ready .upload-state{background:#18894d}.upload-name{flex:1;display:flex;align-items:center;gap:14px;min-width:0}.upload-name b{font-size:17px}.upload-name span{padding:4px 10px;border-radius:20px;background:#fde4e4;color:#b22222;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em}.upload-actions{display:flex;align-items:center;gap:18px}.view-upload{color:#075f69;font-size:12px;font-weight:700;text-transform:uppercase;text-decoration:underline}.btn-upload{background:#137d86;color:#fff;border-color:#137d86;cursor:pointer}.btn-upload input{position:absolute;width:1px;height:1px;opacity:0;pointer-events:none}.btn-upload.is-uploading{opacity:.65;pointer-events:none}.upload-help{margin:18px 0 0;color:var(--muted);font-size:13px}@media(max-width:720px){.upload-page-card{padding:20px}.upload-page-heading{display:block}.upload-applicant{margin-top:18px;min-width:0}.document-upload-row{align-items:flex-start;flex-wrap:wrap}.upload-name{align-items:flex-start;flex-direction:column;gap:7px}.upload-actions{width:100%;justify-content:flex-end}.upload-page-heading h2{font-size:29px}}</style>
<script>
document.querySelectorAll('.instant-upload').forEach(function(input){
  input.addEventListener('change', function(){
    if(!this.files.length) return;
    var form=this.closest('form'), button=form.querySelector('.btn-upload'), label=button.querySelector('span'),
        status=form.querySelector('.upload-status'), fill=form.querySelector('.upload-progress-fill');
    form.classList.add('is-uploading'); button.classList.add('is-uploading');
    fill.style.width='0%'; fill.classList.remove('indeterminate');

    var xhr=new XMLHttpRequest();
    xhr.open('POST', form.getAttribute('action'), true);
    xhr.upload.addEventListener('progress', function(e){
      if(!e.lengthComputable) return;
      var pct=Math.round(e.loaded/e.total*100);
      fill.style.width=pct+'%';
      label.textContent='Mengunggah '+pct+'%';
      status.textContent=pct+'%';
    });
    xhr.upload.addEventListener('load', function(){
      fill.style.width='100%'; fill.classList.add('indeterminate');
      label.textContent='Menyimpan...'; status.textContent='Menyimpan ke Google Drive…';
    });
    xhr.onload=function(){
      // xhr sudah otomatis mengikuti redirect sukses dari server, jadi
      // responseText di sini SELALU halaman akhir yang benar (sukses
      // ATAU form dengan pesan error kalau upload gagal di server) -
      // dipakai langsung, tanpa request GET kedua yang bisa kehilangan
      // pesan error (errornya cuma ada di respons POST ini, tidak
      // disimpan lewat flashdata).
      document.open(); document.write(xhr.responseText); document.close();
    };
    xhr.onerror=function(){
      label.textContent='Gagal, coba lagi'; status.textContent='';
      form.classList.remove('is-uploading'); button.classList.remove('is-uploading');
    };
    xhr.send(new FormData(form));
  });
});
</script>
<?php $this->load->view('pbg_pu/footer'); ?>
