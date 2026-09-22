<p class="eyebrow">Portal Pemohon ITR</p><h2>Unggah Berkas Persyaratan</h2>
<p class="section-lead">Pengajuan <b><?= htmlspecialchars($row['no_permohonan']) ?></b> — pilih berkas pada masing-masing bidang, berkas langsung diunggah setelah dipilih.</p>
<?php if($this->session->flashdata('sukses')): ?><div class="notice"><?= htmlspecialchars($this->session->flashdata('sukses'),ENT_QUOTES,'UTF-8') ?></div><?php endif; ?>
<?php foreach($errors as $e): ?><div class="notice upload-error"><?= htmlspecialchars($e,ENT_QUOTES,'UTF-8') ?></div><?php endforeach; ?>

<div class="itr-upload-list">
<?php foreach($files as $field=>$label): $ada=!empty($row[$field]); ?>
<form action="<?= base_url('pemohon/upload-berkas-itr/'.$row['id']) ?>" class="itr-upload-row <?= $ada?'is-ready':'is-missing' ?>" enctype="multipart/form-data" method="post">
  <div class="upload-state" aria-hidden="true"><?= $ada?'✓':'!' ?></div>
  <div class="upload-name"><b><?= htmlspecialchars($label,ENT_QUOTES,'UTF-8') ?></b><span>Wajib</span></div>
  <div class="upload-actions">
    <small class="upload-status"></small>
    <?php if($ada): ?><a class="view-upload" target="_blank" rel="noopener" href="<?= base_url('pemohon/berkas_itr/'.$row['id'].'/'.$field) ?>">Lihat Berkas</a><?php endif; ?>
    <label class="btn btn-upload"><span><?= $ada?'Upload Ulang':'Upload' ?></span><input class="instant-upload" type="file" name="<?= htmlspecialchars($field,ENT_QUOTES,'UTF-8') ?>" accept=".pdf,.jpg,.jpeg,.png" aria-label="Upload <?= htmlspecialchars($label,ENT_QUOTES,'UTF-8') ?>"></label>
  </div>
  <div class="upload-progress" aria-hidden="true"><div class="upload-progress-fill"></div></div>
</form>
<?php endforeach; ?>
</div>
<p class="itr-upload-help">PDF, JPG, atau PNG. Maksimum 100 MB per berkas.</p>
<div class="itr-form-actions"><a class="btn btn-gold" href="<?= base_url('pemohon') ?>">Selesai, Kembali ke Dashboard</a></div>

<style>
.itr-upload-list{display:grid;gap:12px;margin:24px 0 8px}
.itr-upload-row{position:relative;overflow:hidden;display:flex;align-items:center;gap:18px;padding:15px 18px;border:1px solid var(--line);border-radius:13px}
.itr-upload-row.is-missing{background:rgba(224,82,107,.06);border-color:rgba(224,82,107,.35)}
.itr-upload-row.is-ready{background:rgba(46,168,79,.06);border-color:rgba(46,168,79,.35)}
.itr-upload-row .upload-state{width:38px;height:38px;border-radius:50%;display:grid;place-items:center;flex:0 0 38px;color:#fff;font-size:23px;font-weight:700}
.itr-upload-row.is-missing .upload-state{background:#e0526b}
.itr-upload-row.is-ready .upload-state{background:#2ea84f}
.itr-upload-row .upload-name{flex:1;display:flex;align-items:center;gap:14px;min-width:0}
.itr-upload-row .upload-name b{font-size:16px;color:var(--text)}
.itr-upload-row .upload-name span{padding:4px 10px;border-radius:20px;background:rgba(224,82,107,.12);color:#e0526b;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em}
.itr-upload-row .upload-actions{display:flex;align-items:center;gap:16px;flex-wrap:wrap;justify-content:flex-end}
.itr-upload-row .upload-status{color:var(--gold-300);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em}
.itr-upload-row .view-upload{color:var(--gold-300);font-size:12px;font-weight:700;text-transform:uppercase;text-decoration:underline}
.itr-upload-row .btn-upload{background:var(--gold-300);color:#102536;border:0;cursor:pointer}
.itr-upload-row .btn-upload input{position:absolute;width:1px;height:1px;opacity:0;pointer-events:none}
.itr-upload-row .btn-upload.is-uploading{opacity:.65;pointer-events:none}
.itr-upload-row .upload-progress{position:absolute;left:0;right:0;bottom:0;height:4px;background:rgba(201,162,75,.15);display:none;overflow:hidden}
.itr-upload-row.is-uploading .upload-progress{display:block}
.itr-upload-row .upload-progress-fill{height:100%;width:0%;background:var(--gold-300);transition:width .15s linear}
.itr-upload-row .upload-progress-fill.indeterminate{width:30%!important;animation:itr-indeterminate 1.1s ease-in-out infinite}
@keyframes itr-indeterminate{0%{margin-left:-30%}100%{margin-left:100%}}
.itr-upload-help{margin:10px 0 0;color:var(--muted);font-size:13px}
.upload-error{background:rgba(224,82,107,.12)!important}
@media(max-width:700px){.itr-upload-row{align-items:flex-start;flex-wrap:wrap}.itr-upload-row .upload-name{align-items:flex-start;flex-direction:column;gap:7px}.itr-upload-row .upload-actions{width:100%;justify-content:flex-end}}
</style>
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
      label.textContent='Menyimpan...'; status.textContent='Menyimpan berkas…';
    });
    xhr.onload=function(){ document.open(); document.write(xhr.responseText); document.close(); };
    xhr.onerror=function(){
      label.textContent='Gagal, coba lagi'; status.textContent='';
      form.classList.remove('is-uploading'); button.classList.remove('is-uploading');
    };
    xhr.send(new FormData(form));
  });
});
</script>
