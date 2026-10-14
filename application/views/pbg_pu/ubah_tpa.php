<?php
/*
 * Ubah TPA yang sudah ditugaskan SEBELUM TPA mengirim hasil review, sekaligus
 * tanggal berita acara distribusi putaran yang sedang menunggu - satu form,
 * satu tombol simpan. Pergantian TPA hanya untuk penugasan putaran 1: TPA yang
 * masih "ditugaskan" boleh diganti/ditambah/dibatalkan, yang sudah mengirim hasil
 * terkunci. Putaran lanjutan tidak bisa diganti TPA-nya karena hasil perbaikan
 * wajib kembali ke TPA yang meminta perbaikan (lihat Pbg_pu::ajukan_konsultasi).
 * Dipanggil dari pbg_pu/tahap: butuh $konsultasi_terakhir, $tpa_per_bidang, $row,
 * $ba_distribusi (kunci = putaran) dan $ba_aktif.
 */
$nama_bidang = array('arsitektur'=>'Arsitektur & Tata Ruang','struktur'=>'Struktur','mep'=>'Mekanikal, Elektrikal & Perpipaan (MEP)');
$bisa_diubah = array();
$putaran_menunggu = 0;
foreach ($nama_bidang as $kode => $nama)
{
	$akhir = isset($konsultasi_terakhir[$kode]) ? $konsultasi_terakhir[$kode] : null;
	if (!$akhir || $akhir['status'] !== 'ditugaskan') continue;
	$awal = true;
	foreach ($akhir['anggota'] as $m)
	{
		if ($m['status'] !== 'ditugaskan') continue;
		$putaran_menunggu = max($putaran_menunggu, (int) $m['putaran']);
		if ((int) $m['putaran'] !== 1) $awal = false;
	}
	if ($awal) $bisa_diubah[$kode] = $akhir;
}
$ada_ba = !empty($ba_aktif) && $putaran_menunggu > 0;
$tanggal_ba = ($ada_ba && isset($ba_distribusi[$putaran_menunggu])) ? $ba_distribusi[$putaran_menunggu]['tanggal_ba'] : date('Y-m-d');
?>
<?php if (empty($bisa_diubah) && !$ada_ba): ?>
<div class="notice"><b>Menunggu hasil review.</b> TPA yang ditugaskan belum mengirim seluruh hasil review.</div>
<?php else: ?>
<h2 class="section-title"><?= !empty($bisa_diubah) ? 'Ubah TPA yang Ditugaskan' : 'Tanggal Berita Acara' ?></h2>
<?php if (!empty($bisa_diubah)): ?><p>TPA yang belum mengirim hasil review masih dapat diganti, ditambah, atau dibatalkan. TPA yang sudah mengirim hasil tidak dapat diubah.</p><?php endif; ?>
<?= form_open('pengajuan-pbg/ubah-tpa/'.$row['id']) ?>
<?php if (!empty($bisa_diubah)): ?>
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
<?php endif; ?>
<?php if ($ada_ba): ?>
<div class="consultation-fields"><div class="full">
	<label>Tanggal Berita Acara (Putaran <?= (int) $putaran_menunggu ?>)</label>
	<input type="hidden" name="putaran_ba" value="<?= (int) $putaran_menunggu ?>">
	<input type="date" name="tanggal_ba" value="<?= htmlspecialchars($tanggal_ba, ENT_QUOTES, 'UTF-8') ?>" required style="max-width:240px">
	<small class="multi-hint"><?= isset($ba_distribusi[$putaran_menunggu]) ? 'Nomor '.htmlspecialchars($ba_distribusi[$putaran_menunggu]['nomor'], ENT_QUOTES, 'UTF-8').'. ' : 'Berita acara putaran ini belum terbit dan akan diterbitkan saat disimpan. ' ?>Tanggal dapat diubah selama TPA belum mengirim hasil review.</small>
</div></div>
<?php endif; ?>
<button class="btn btn-primary" type="submit"><?= !empty($bisa_diubah) ? 'Simpan Perubahan TPA' : 'Simpan Tanggal Berita Acara' ?></button>
<?= form_close() ?>
<?php endif; ?>
