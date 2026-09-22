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
          <span class="badge <?= $anggota['status']==='direkomendasikan'?'disetujui':($anggota['status']==='perlu_perbaikan'?'ditolak':'diverifikasi') ?>"><?= ucwords(str_replace('_',' ',$anggota['status'])) ?></span>
          <?php if($anggota['status']==='ditugaskan'): ?>
            <p>Belum mengirim hasil review.</p>
          <?php else: ?>
            <p><?= nl2br(htmlspecialchars($anggota['rekomendasi_tpa']?:'Tidak ada catatan review.',ENT_QUOTES,'UTF-8')) ?></p>
            <?php if(!empty($anggota['file_rekomendasi'])): ?><a target="_blank" rel="noopener" href="<?= base_url('assets/uploads/konsultasi_pbg/'.rawurlencode($anggota['file_rekomendasi'])) ?>">Lihat lampiran rekomendasi</a><?php endif; ?>
          <?php endif; ?>
        </div>
      <?php endforeach; endif; ?>
    </article>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>
