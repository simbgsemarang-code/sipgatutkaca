<?php if(!empty($konsultasi_terakhir)): ?>
<section class="tpa-review-summary" aria-label="Hasil review TPA">
  <h2 class="section-title">TPA Terpilih dan Hasil Review</h2>
  <div class="tpa-review-grid">
    <?php foreach(array('arsitektur'=>'Arsitektur & Tata Ruang','struktur'=>'Struktur','mep'=>'Mekanikal, Elektrikal & Perpipaan (MEP)') as $kode=>$nama): ?>
    <article class="tpa-review-field">
      <h3><?= htmlspecialchars($nama,ENT_QUOTES,'UTF-8') ?></h3>
      <?php if(empty($konsultasi_terakhir[$kode]['anggota'])): ?>
        <p>Belum ada TPA yang ditugaskan.</p>
      <?php else: foreach($konsultasi_terakhir[$kode]['anggota'] as $anggota): ?>
        <div class="tpa-review-member">
          <strong><?= htmlspecialchars($anggota['nama_tpa']?:'TPA',ENT_QUOTES,'UTF-8') ?></strong>
          <span class="badge <?= $anggota['status']==='direkomendasikan'?'disetujui':($anggota['status']==='perlu_perbaikan'?'ditolak':'diverifikasi') ?>"><?= konsultasi_label_status($anggota['status']) ?></span>
          <?php if($anggota['status']==='ditugaskan'): ?>
            <p>Belum mengirim hasil review.</p>
          <?php else: ?>
            <p><?= nl2br(htmlspecialchars($anggota['rekomendasi_tpa']?:($anggota['status']==='direkomendasikan'?'Sudah Sesuai.':'Tidak ada catatan review.'),ENT_QUOTES,'UTF-8')) ?></p>
            <?php if(!empty($anggota['file_rekomendasi'])): ?><a target="_blank" rel="noopener" href="<?= berkas_href($anggota['file_rekomendasi'],'assets/uploads/konsultasi_pbg/') ?>">Lihat lampiran rekomendasi</a><?php endif; ?>
          <?php endif; ?>
        </div>
      <?php endforeach; endif; ?>
    </article>
    <?php endforeach; ?>
  </div>
</section>
<?php $ba_putaran=array(); foreach((array)($konsultasi_riwayat??array()) as $k){if($k['status']!=='ditugaskan')$ba_putaran[(int)$k['putaran']]=$k;} if($ba_putaran):?>
<div class="tpa-review-ba"><small>Berita Acara permohonan:</small><?php foreach($ba_putaran as $putaran=>$k):?><button type="button" class="btn" data-ba-modal data-konsultasi="<?= (int)$k['id'] ?>" data-tanggal="<?= htmlspecialchars(isset($ba_tanggal[(int)$k['id']])?$ba_tanggal[(int)$k['id']]:substr((string)$k['reviewed_at'],0,10),ENT_QUOTES,'UTF-8') ?>">Unduh BA Putaran <?= (int)$putaran ?></button><?php endforeach;?></div>
<?php endif;?>
<?php endif; ?>
<?php if (!empty($permohonan_id)): ?>
<dialog id="baTanggalModal" style="margin:auto;border:1px solid #d9dee6;border-radius:16px;padding:26px 28px;max-width:420px;width:calc(100% - 32px);box-shadow:0 24px 60px rgba(15,30,50,.28)">
  <form method="post" target="_blank" id="baTanggalForm" action="<?= base_url('pengajuan-pbg/berita-acara-tanggal/'.(int) $permohonan_id) ?>">
    <h3 style="margin:0 0 6px">Tanggal Berita Acara</h3>
    <p style="margin:0 0 16px;font-size:.9rem;opacity:.8">Tanggal ini dipakai untuk berita acara pada <b>putaran yang dipilih</b>.</p>
    <input type="hidden" name="konsultasi_id" id="baKonsultasi">
    <label for="baTanggal" style="display:block;margin-bottom:6px;font-weight:600">Tanggal</label>
    <input type="date" name="tanggal_ba" id="baTanggal" required style="width:100%;box-sizing:border-box">
    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px">
      <button type="button" class="btn" id="baBatal">Batal</button>
      <button type="submit" class="btn btn-primary">Simpan &amp; Unduh BA</button>
    </div>
  </form>
</dialog>
<script>
(function(){
  var dlg=document.getElementById('baTanggalModal'); if(!dlg)return;
  var f=document.getElementById('baTanggalForm'), tg=document.getElementById('baTanggal'), kid=document.getElementById('baKonsultasi');
  function buka(){ if(dlg.showModal)dlg.showModal(); else dlg.setAttribute('open',''); }
  function tutup(){ if(dlg.close)dlg.close(); else dlg.removeAttribute('open'); }
  document.querySelectorAll('[data-ba-modal]').forEach(function(b){
    b.addEventListener('click',function(){ kid.value=b.getAttribute('data-konsultasi'); tg.value=b.getAttribute('data-tanggal')||''; buka(); });
  });
  document.getElementById('baBatal').addEventListener('click',tutup);
  dlg.addEventListener('click',function(e){ if(e.target===dlg)tutup(); });
  f.addEventListener('submit',function(){
    var t=tg.value; document.querySelectorAll('[data-ba-modal]').forEach(function(b){ b.setAttribute('data-tanggal',t); });
    setTimeout(tutup,150);
  });
})();
</script>
<?php endif; ?>
