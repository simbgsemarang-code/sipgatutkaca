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
<style>.upload-page-card{padding:34px}.upload-page-heading{display:flex;justify-content:space-between;align-items:flex-start;gap:28px;margin:28px 0}.upload-page-heading h2{margin:6px 0 8px;font-size:36px}.upload-page-heading p{margin:0;color:var(--muted)}.upload-applicant{min-width:260px;padding:18px;border:1px solid var(--line);display:grid;gap:4px}.upload-applicant span,.upload-applicant small{color:var(--muted)}.upload-error{background:rgba(224,82,107,.12)}.document-upload-list{display:grid;gap:12px}.document-upload-row{position:relative;overflow:hidden;display:flex;align-items:center;gap:18px;padding:15px 18px;border:1px solid;border-radius:13px}
.upload-status{color:var(--gold);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;min-width:0}
.upload-status.ok{color:#2ea84f}
.upload-status.err{color:#e0526b}
.upload-progress{position:absolute;left:0;right:0;bottom:0;height:4px;background:rgba(201,162,75,.15);display:none;overflow:hidden}
.document-upload-row.is-uploading .upload-progress{display:block}
.upload-progress-fill{height:100%;width:0%;background:var(--gold2);transition:width .15s linear}
.upload-progress-fill.indeterminate{width:30%!important;animation:upload-indeterminate 1.1s ease-in-out infinite}
@keyframes upload-indeterminate{0%{margin-left:-30%}100%{margin-left:100%}}.document-upload-row.is-missing{background:rgba(224,82,107,.06);border-color:rgba(224,82,107,.35)}.document-upload-row.is-ready{background:rgba(46,168,79,.06);border-color:rgba(46,168,79,.35)}.upload-state{width:38px;height:38px;border-radius:50%;display:grid;place-items:center;flex:0 0 38px;color:#fff;font-size:23px;font-weight:700}.is-missing .upload-state{background:#e0526b}.is-ready .upload-state{background:#2ea84f}.upload-name{flex:1;display:flex;align-items:center;gap:14px;min-width:0}.upload-name b{font-size:17px}.upload-name span{padding:4px 10px;border-radius:20px;background:rgba(224,82,107,.12);color:#e0526b;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em}.upload-actions{display:flex;align-items:center;gap:18px}.view-upload{color:var(--gold);font-size:12px;font-weight:700;text-transform:uppercase;text-decoration:underline}.btn-upload{background:linear-gradient(135deg,#c9a24b,#e4c87b);color:#102536;border:0;cursor:pointer}.btn-upload input{position:absolute;width:1px;height:1px;opacity:0;pointer-events:none}.btn-upload.is-uploading{opacity:.65;pointer-events:none}.upload-help{margin:18px 0 0;color:var(--muted);font-size:13px}@media(max-width:720px){.upload-page-card{padding:20px}.upload-page-heading{display:block}.upload-applicant{margin-top:18px;min-width:0}.document-upload-row{align-items:flex-start;flex-wrap:wrap}.upload-name{align-items:flex-start;flex-direction:column;gap:7px}.upload-actions{width:100%;justify-content:flex-end}.upload-page-heading h2{font-size:29px}}</style>
<script>
document.querySelectorAll('.instant-upload').forEach(function(input){
  input.addEventListener('change', function(){
    if(!this.files.length) return;
    var form=this.closest('form'), button=form.querySelector('.btn-upload'), label=button.querySelector('span'),
        status=form.querySelector('.upload-status'), fill=form.querySelector('.upload-progress-fill');
    form.classList.add('is-uploading'); button.classList.add('is-uploading');
    fill.style.width='0%'; fill.classList.remove('indeterminate'); status.className='upload-status';

    var xhr=new XMLHttpRequest();
    xhr.open('POST', form.getAttribute('action'), true);
    xhr.setRequestHeader('X-Requested-With','XMLHttpRequest');
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
      form.classList.remove('is-uploading'); button.classList.remove('is-uploading');
      fill.classList.remove('indeterminate'); fill.style.width='0%';
      var res=null; try{ res=JSON.parse(xhr.responseText); }catch(e){}
      if(!res||!res.ok){
        label.textContent=form.classList.contains('is-ready')?'Upload Ulang':'Upload';
        status.className='upload-status err'; status.textContent=(res&&res.message)?res.message:'Gagal mengunggah berkas, coba lagi.';
        return;
      }
      form.classList.remove('is-missing'); form.classList.add('is-ready');
      form.querySelector('.upload-state').textContent='✓';
      label.textContent='Upload Ulang';
      if(res.href){
        var actions=form.querySelector('.upload-actions'), lihat=form.querySelector('.view-upload');
        if(!lihat){
          lihat=document.createElement('a'); lihat.className='view-upload'; lihat.target='_blank'; lihat.rel='noopener'; lihat.textContent='Lihat Berkas';
          actions.insertBefore(lihat, actions.querySelector('.btn-upload'));
        }
        lihat.href=res.href;
      }
      status.className='upload-status ok'; status.textContent=res.pesan||'Berkas berhasil diunggah.';
    };
    xhr.onerror=function(){
      label.textContent=form.classList.contains('is-ready')?'Upload Ulang':'Upload';
      form.classList.remove('is-uploading'); button.classList.remove('is-uploading');
      status.className='upload-status err'; status.textContent='Gagal mengunggah berkas, coba lagi.';
    };
    xhr.send(new FormData(form));
  });
});
</script>
<?php $this->load->view('pbg_pu/footer'); ?>
