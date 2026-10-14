<?php
/*
 * Perbaikan berkas lewat link Google Drive + riwayat per putaran.
 * Form hanya tampil saat ada TPA yang meminta perbaikan ($ada_perbaikan) dan
 * dikirim lewat AJAX (tanpa muat ulang halaman): riwayat diganti dengan HTML
 * dari server dan tombol "Ajukan Konsultasi Berikutnya" diaktifkan.
 * Tanpa tabel link_perbaikan_pbg ($link_aktif kosong) kembali ke alur lama
 * (unggah berkas pengganti). Butuh: $row, $ada_perbaikan, $link_aktif,
 * $link_perbaikan, $konsultasi_terakhir.
 */
if (empty($link_aktif))
{
	if (!empty($ada_perbaikan)): ?>
<div class="revision-alert"><div><b>Perbaikan diperlukan</b><p>Unggah berkas pengganti untuk bidang yang berstatus Perlu Perbaikan.</p></div><a class="btn btn-primary" href="<?= base_url('pengajuan-pbg/upload-berkas/'.$row['id']) ?>">Perbaikan Berkas</a></div>
	<?php endif;
	return;
}
$putaran_perbaikan = 0;
foreach ($konsultasi_terakhir as $k)
{
	foreach ($k['anggota'] as $m) { if ($m['status'] === 'perlu_perbaikan') $putaran_perbaikan = max($putaran_perbaikan, (int) $m['putaran']); }
}
?>
<style>.link-form{display:grid;grid-template-columns:2fr 1.4fr auto;gap:10px;align-items:center;margin:14px 0 6px}.link-form input{min-width:0}.link-hint{display:block;color:var(--muted);font-size:12px}.link-url{overflow-wrap:anywhere}.link-terbaru{display:inline-block;margin-left:6px}.link-status{margin:10px 0 0;padding:10px 14px;border-radius:10px;font-size:13px;border:1px solid transparent}.link-status.ok{background:rgba(46,168,79,.12);border-color:#2EA84F;color:#1c7a37}.link-status.err{background:rgba(224,82,107,.12);border-color:#E0526B;color:#a3283d}@media(max-width:900px){.link-form{grid-template-columns:1fr}}</style>
<?php if (!empty($ada_perbaikan)): ?>
<div class="revision-alert" style="display:block">
	<b>Perbaikan diperlukan</b>
	<p>Ada TPA yang meminta perbaikan pada putaran <?= (int) $putaran_perbaikan ?>. Kirim link Google Drive berisi berkas perbaikan. Setiap putaran memakai link baru.</p>
	<?= form_open('pengajuan-pbg/link-perbaikan/'.$row['id'], array('class' => 'link-form', 'id' => 'linkForm')) ?>
		<input type="url" name="link_drive" required maxlength="500" placeholder="https://drive.google.com/…" aria-label="Link Google Drive">
		<input type="text" name="keterangan" maxlength="255" placeholder="Keterangan (opsional)" aria-label="Keterangan">
		<button class="btn btn-primary" type="submit">Kirim Link Perbaikan</button>
	<?= form_close() ?>
	<div class="link-status" id="linkStatus" role="status" aria-live="polite" hidden></div>
	<small class="link-hint">Atur akses link ke &ldquo;Siapa saja yang memiliki link&rdquo; agar dapat dibuka TPA. Hanya link drive.google.com atau docs.google.com yang diterima.</small>
</div>
<script>
(function(){
  var f=document.getElementById('linkForm'); if(!f||!window.fetch)return;
  var st=document.getElementById('linkStatus'), btn=f.querySelector('button[type=submit]');
  function tampil(teks,jenis){ st.textContent=teks; st.className='link-status'+(jenis?' '+jenis:''); st.hidden=!teks; }
  f.addEventListener('submit',function(e){
    e.preventDefault();
    if(!f.reportValidity())return;
    var label=btn.textContent; btn.disabled=true; btn.textContent='Mengirim…'; tampil('','');
    fetch(f.action,{method:'POST',body:new FormData(f),credentials:'same-origin',headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}})
      .then(function(r){ return r.json().catch(function(){ return {ok:false,pesan:'Jawaban server tidak dikenali.'}; }); })
      .then(function(d){
        if(d.ok){
          f.reset();
          document.getElementById('linkRiwayat').innerHTML=d.riwayat_html;
          var b=document.querySelector('form[action*="pengajuan-pbg/konsultasi/"] button.btn-primary'); if(b)b.disabled=false;
          tampil(d.pesan,'ok');
        } else tampil(d.pesan||'Link gagal dikirim.','err');
      })
      .catch(function(){ tampil('Koneksi gagal. Coba kirim lagi.','err'); })
      .then(function(){ btn.disabled=false; btn.textContent=label; });
  });
})();
</script>
<?php endif; ?>
<div id="linkRiwayat"><?php $this->load->view('pbg_pu/link_riwayat', array('link_perbaikan' => $link_perbaikan)); ?></div>
