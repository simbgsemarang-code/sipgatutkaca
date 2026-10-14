<?php
/*
 * Perbaikan berkas lewat link Google Drive + riwayat per putaran.
 * Form hanya tampil saat ada TPA yang meminta perbaikan ($ada_perbaikan).
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
<style>.link-form{display:grid;grid-template-columns:2fr 1.4fr auto;gap:10px;align-items:center;margin:14px 0 6px}.link-form input{min-width:0}.link-hint{display:block;color:var(--muted);font-size:12px}.link-url{overflow-wrap:anywhere}.link-terbaru{display:inline-block;margin-left:6px}@media(max-width:900px){.link-form{grid-template-columns:1fr}}</style>
<?php if (!empty($ada_perbaikan)): ?>
<div class="revision-alert" style="display:block">
	<b>Perbaikan diperlukan</b>
	<p>Ada TPA yang meminta perbaikan pada putaran <?= (int) $putaran_perbaikan ?>. Kirim link Google Drive berisi berkas perbaikan. Setiap putaran memakai link baru.</p>
	<?= form_open('pengajuan-pbg/link-perbaikan/'.$row['id'], array('class' => 'link-form')) ?>
		<input type="url" name="link_drive" required maxlength="500" placeholder="https://drive.google.com/…" aria-label="Link Google Drive">
		<input type="text" name="keterangan" maxlength="255" placeholder="Keterangan (opsional)" aria-label="Keterangan">
		<button class="btn btn-primary" type="submit">Kirim Link Perbaikan</button>
	<?= form_close() ?>
	<small class="link-hint">Atur akses link ke &ldquo;Siapa saja yang memiliki link&rdquo; agar dapat dibuka TPA. Hanya link drive.google.com atau docs.google.com yang diterima.</small>
</div>
<?php endif; ?>
<?php if (!empty($link_perbaikan)): ?>
<h2 class="section-title">Riwayat Link Google Drive Perbaikan</h2>
<div class="table-wrap"><table class="consultation-table" style="min-width:0"><thead><tr><th>Putaran</th><th>Link</th><th>Keterangan</th><th>Dikirim</th></tr></thead><tbody>
<?php $pertama_per_putaran = array(); foreach ($link_perbaikan as $l): $p = (int) $l['putaran']; $terbaru = !isset($pertama_per_putaran[$p]); $pertama_per_putaran[$p] = TRUE; ?>
<tr>
	<td><b><?= $p ?></b><?php if ($terbaru): ?><span class="badge disetujui link-terbaru">Terbaru</span><?php endif; ?></td>
	<td class="link-url"><a href="<?= htmlspecialchars($l['url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($l['url'], ENT_QUOTES, 'UTF-8') ?></a></td>
	<td><?= $l['keterangan'] !== null && $l['keterangan'] !== '' ? htmlspecialchars($l['keterangan'], ENT_QUOTES, 'UTF-8') : '—' ?></td>
	<td><?= date('d/m/Y H:i', strtotime($l['created_at'])) ?><?php if (!empty($l['nama_pengirim'])): ?><br><small><?= htmlspecialchars($l['nama_pengirim'], ENT_QUOTES, 'UTF-8') ?></small><?php endif; ?></td>
</tr>
<?php endforeach; ?>
</tbody></table></div>
<?php endif; ?>
