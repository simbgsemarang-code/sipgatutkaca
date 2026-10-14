<?php
/*
 * Daftar Berita Acara Distribusi Tugas per putaran + tempat mengubah tanggalnya.
 * Putaran yang belum punya BA (penugasan lama) bisa diterbitkan dari sini.
 * Butuh: $konsultasi, $ba_distribusi (kunci = putaran), $ba_aktif, $row.
 */
if (empty($ba_aktif) || empty($konsultasi)) return;
$putaran_ada = array_values(array_unique(array_map('intval', array_column($konsultasi, 'putaran'))));
rsort($putaran_ada);
?>
<style>.ba-tabel{min-width:0!important}.ba-tabel td,.ba-tabel th{padding:14px 12px}.ba-tabel .ba-no{display:block;margin-top:2px;font-size:12px;color:var(--muted);white-space:nowrap}.ba-tanggal-form{display:flex;align-items:center;gap:8px;margin:0;flex-wrap:nowrap}.ba-tanggal-form input[type=date]{width:auto;min-width:0;flex:0 1 150px;padding:8px 10px}.ba-tanggal-form .btn{padding:8px 16px;min-height:38px;white-space:nowrap}.ba-tabel .btn{white-space:nowrap}@media(max-width:700px){.ba-tanggal-form{flex-wrap:wrap}}</style>
<h2 class="section-title">Berita Acara Distribusi Tugas</h2>
<p>Berita acara terbit otomatis setiap kali tugas dibagikan ke TPA. Tanggalnya dapat diubah kapan saja.</p>
<div class="table-wrap"><table class="consultation-table ba-tabel"><thead><tr><th>Putaran</th><th>Tanggal Berita Acara</th><th>Aksi</th></tr></thead><tbody>
<?php foreach ($putaran_ada as $p): $b = isset($ba_distribusi[$p]) ? $ba_distribusi[$p] : null; ?>
<tr>
	<td><b><?= (int) $p ?></b><?php if ($b): ?><span class="ba-no"><?= htmlspecialchars($b['nomor'], ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?></td>
	<?php if ($b): ?>
	<td><?= form_open('pengajuan-pbg/ubah-tanggal-ba/'.$b['id'], array('class' => 'ba-tanggal-form')) ?><input type="date" name="tanggal_ba" value="<?= htmlspecialchars($b['tanggal_ba'], ENT_QUOTES, 'UTF-8') ?>" required><button class="btn" type="submit">Simpan</button><?= form_close() ?></td>
	<td><a class="btn" target="_blank" href="<?= base_url('pengajuan-pbg/berita-acara-distribusi/'.$b['id']) ?>">Lihat / Cetak BA</a></td>
	<?php else: ?>
	<td colspan="2"><?= form_open('pengajuan-pbg/terbitkan-ba/'.$row['id'], array('class' => 'ba-tanggal-form')) ?><input type="hidden" name="putaran" value="<?= (int) $p ?>"><span>Belum terbit —</span><input type="date" name="tanggal_ba" value="<?= date('Y-m-d') ?>" required><button class="btn btn-primary" type="submit">Terbitkan Berita Acara</button><?= form_close() ?></td>
	<?php endif; ?>
</tr>
<?php endforeach; ?>
</tbody></table></div>
