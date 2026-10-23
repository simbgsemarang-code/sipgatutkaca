<?php
/*
 * Daftar Berita Acara Distribusi Tugas per putaran (hanya baca). Tanggalnya
 * diubah lewat form "Ubah TPA yang Ditugaskan" (pbg_pu/ubah_tpa) atau saat
 * distribusi. Butuh: $konsultasi, $ba_distribusi (kunci = putaran), $ba_aktif.
 */
if (empty($ba_aktif) || empty($konsultasi)) return;
$putaran_ada = array_values(array_unique(array_map('intval', array_column($konsultasi, 'putaran'))));
rsort($putaran_ada);
?>
<style>.ba-tabel{min-width:0!important}.ba-tabel td,.ba-tabel th{padding:14px 12px}.ba-tabel .btn{white-space:nowrap}</style>
<h2 class="section-title">Berita Acara Distribusi Tugas</h2>
<p>Berita acara terbit otomatis setiap kali tugas dibagikan ke TPA. Tanggalnya diatur pada form distribusi dan dapat diubah selama TPA belum mengirim hasil review.</p>
<div class="table-wrap"><table class="consultation-table ba-tabel"><thead><tr><th>Putaran</th><th>Nomor</th><th>Tanggal</th><th>Aksi</th></tr></thead><tbody>
<?php foreach ($putaran_ada as $p): $b = isset($ba_distribusi[$p]) ? $ba_distribusi[$p] : null; ?>
<tr>
	<td><b><?= (int) $p ?></b></td>
	<?php if ($b): ?>
	<td><?= htmlspecialchars($b['nomor'], ENT_QUOTES, 'UTF-8') ?></td>
	<td><?= date('d/m/Y', strtotime($b['tanggal_ba'])) ?></td>
	<td><a class="btn" target="_blank" href="<?= base_url('pengajuan-pbg/berita-acara-distribusi/'.$b['id']) ?>">Lihat / Cetak BA</a></td>
	<?php else: ?>
	<td colspan="3">Belum terbit.</td>
	<?php endif; ?>
</tr>
<?php endforeach; ?>
</tbody></table></div>
