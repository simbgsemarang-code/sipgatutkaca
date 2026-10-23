<?php
/*
 * Tabel riwayat link Google Drive perbaikan per putaran. Dipakai langsung oleh
 * pbg_pu/link_perbaikan dan dirender ulang oleh Pbg_pu::kirim_link_perbaikan
 * untuk jawaban AJAX. Butuh: $link_perbaikan (urut putaran turun, id turun).
 */
if (empty($link_perbaikan)) return;
?>
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
