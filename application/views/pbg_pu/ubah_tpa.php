<?php
/*
 * Ubah TPA yang sudah ditugaskan SEBELUM TPA mengirim hasil review.
 * Hanya untuk penugasan putaran 1 (distribusi awal): TPA yang masih berstatus
 * "ditugaskan" boleh diganti/ditambah/dibatalkan; yang sudah mengirim hasil
 * terkunci. Putaran lanjutan sengaja tidak bisa diubah karena hasil perbaikan
 * wajib kembali ke TPA yang meminta perbaikan (lihat Pbg_pu::ajukan_konsultasi).
 * Dipanggil dari pbg_pu/tahap: butuh $konsultasi_terakhir, $tpa_per_bidang, $row.
 */
$nama_bidang = array('arsitektur'=>'Arsitektur & Tata Ruang','struktur'=>'Struktur','mep'=>'Mekanikal, Elektrikal & Perpipaan (MEP)');
$bisa_diubah = array();
foreach ($nama_bidang as $kode => $nama)
{
	$akhir = isset($konsultasi_terakhir[$kode]) ? $konsultasi_terakhir[$kode] : null;
	if (!$akhir || $akhir['status'] !== 'ditugaskan') continue;
	$awal = true;
	foreach ($akhir['anggota'] as $m) { if ($m['status'] === 'ditugaskan' && (int) $m['putaran'] !== 1) $awal = false; }
	if ($awal) $bisa_diubah[$kode] = $akhir;
}
?>
<?php if (empty($bisa_diubah)): ?>
<div class="notice"><b>Menunggu hasil review.</b> TPA yang ditugaskan belum mengirim seluruh hasil review.</div>
<?php else: ?>
<h2 class="section-title">Ubah TPA yang Ditugaskan</h2>
<p>TPA yang belum mengirim hasil review masih dapat diganti, ditambah, atau dibatalkan. TPA yang sudah mengirim hasil tidak dapat diubah.</p>
<?= form_open('pengajuan-pbg/ubah-tpa/'.$row['id']) ?>
<div class="tpa-grid">
<?php foreach ($bisa_diubah as $kode => $akhir):
	$menunggu = array(); $terkunci = array();
	foreach ($akhir['anggota'] as $m)
	{
		if ($m['status'] === 'ditugaskan') $menunggu[(int) $m['tpa_user_id']] = TRUE;
		else $terkunci[(int) $m['tpa_user_id']] = $m;
	}
?>
	<div>
		<label>TPA <?= htmlspecialchars($nama_bidang[$kode], ENT_QUOTES, 'UTF-8') ?></label>
		<div class="tpa-recipient-list">
		<?php foreach ($tpa_per_bidang[$kode] as $tpa): $uid = (int) $tpa['id']; ?>
			<?php if (isset($terkunci[$uid])): ?>
			<label class="tpa-recipient">
				<input type="checkbox" checked disabled>
				<span><strong><?= htmlspecialchars($tpa['nama'], ENT_QUOTES, 'UTF-8') ?></strong><small>Sudah mengirim hasil (<?= konsultasi_label_status($terkunci[$uid]['status']) ?>) — terkunci</small></span>
			</label>
			<?php else: ?>
			<label class="tpa-recipient">
				<input type="checkbox" name="tpa_<?= $kode ?>[]" value="<?= $uid ?>" <?= isset($menunggu[$uid]) ? 'checked' : '' ?>>
				<span><strong><?= htmlspecialchars($tpa['nama'], ENT_QUOTES, 'UTF-8') ?></strong><small><?= htmlspecialchars($tpa['email'], ENT_QUOTES, 'UTF-8') ?> · <?= isset($menunggu[$uid]) ? 'Menunggu hasil review' : 'Belum ditugaskan' ?></small></span>
			</label>
			<?php endif; ?>
		<?php endforeach; ?>
		</div>
	</div>
<?php endforeach; ?>
</div>
<button class="btn btn-primary" type="submit">Simpan Perubahan TPA</button>
<?= form_close() ?>
<?php endif; ?>
