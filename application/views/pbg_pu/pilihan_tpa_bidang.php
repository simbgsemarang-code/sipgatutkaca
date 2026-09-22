<div>
  <label>TPA <?= htmlspecialchars($nama,ENT_QUOTES,'UTF-8') ?></label>
  <?php if($akhir && $akhir['status']==='direkomendasikan'): ?>
    <div class="tpa-locked"><span class="badge disetujui">✓ Direkomendasikan</span><small><?= htmlspecialchars($akhir['nama_tpa']?:'TPA bidang telah selesai',ENT_QUOTES,'UTF-8') ?> — tidak dipilih lagi pada putaran berikutnya.</small></div>
  <?php elseif($akhir): ?>
    <div class="tpa-recipient-list">
      <?php foreach($akhir['anggota'] as $anggota): ?>
        <label class="tpa-recipient">
          <input type="checkbox" name="tpa_<?= htmlspecialchars($kode,ENT_QUOTES,'UTF-8') ?>[]" value="<?= (int)$anggota['tpa_user_id'] ?>" <?= $anggota['status']!=='perlu_perbaikan'?'disabled':'' ?>>
          <span><strong><?= htmlspecialchars($anggota['nama_tpa']?:'TPA',ENT_QUOTES,'UTF-8') ?></strong><small><?= $anggota['status']==='direkomendasikan'?'Sudah merekomendasikan':($anggota['status']==='ditugaskan'?'Menunggu hasil review':'Perlu perbaikan') ?></small></span>
        </label>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <select multiple size="4" name="tpa_<?= htmlspecialchars($kode,ENT_QUOTES,'UTF-8') ?>[]" class="tpa-multiple" required>
      <?php foreach($tpa_per_bidang[$kode] as $tpa): ?><option value="<?= (int)$tpa['id'] ?>"><?= htmlspecialchars($tpa['nama'].' ('.$tpa['email'].')',ENT_QUOTES,'UTF-8') ?></option><?php endforeach; ?>
    </select>
    <small class="multi-hint">Pilih satu atau lebih TPA.</small>
  <?php endif; ?>
</div>
