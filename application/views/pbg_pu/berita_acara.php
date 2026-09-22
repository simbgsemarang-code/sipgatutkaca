<?php
$hari_id  = array('Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu');
$bulan_id = array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
$ts       = strtotime($tanggal_ba);
$ba_hari  = $hari_id[date('l', $ts)];
$ba_tgl   = date('j', $ts) . ' ' . $bulan_id[(int) date('n', $ts)] . ' ' . date('Y', $ts);
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Berita Acara Konsultasi TPA · <?= htmlspecialchars($row['no_permohonan']) ?></title>
<link rel="icon" type="image/png" href="<?= base_url('assets/img/icon.png') ?>">
<style>
:root{--gold:#a57e2c;--navy:#152f45;--line:#d4e0e5}
*{box-sizing:border-box}
body{margin:0;background:#eef2f4;color:#1a1a1a;font:400 15px/1.7 'Times New Roman',Georgia,serif}
.toolbar{position:sticky;top:0;z-index:5;display:flex;justify-content:center;gap:12px;padding:16px;background:#fff;border-bottom:1px solid var(--line);font-family:'Plus Jakarta Sans',sans-serif}
.toolbar a,.toolbar button{display:inline-flex;align-items:center;min-height:40px;padding:0 22px;border:1px solid var(--line);border-radius:24px;background:transparent;color:var(--navy);text-decoration:none;text-transform:uppercase;letter-spacing:.1em;font:500 11px 'Plus Jakarta Sans',sans-serif;cursor:pointer}
.toolbar .primary{background:linear-gradient(135deg,#c9a24b,#e4c87b);color:#102536;border:0}
.sheet{max-width:800px;margin:28px auto 60px;padding:56px 64px;background:#fff;box-shadow:0 12px 32px rgba(21,47,69,.12)}
.kop{text-align:center;margin-bottom:26px}
.kop img{width:70px;height:auto;margin-bottom:8px}
.kop .instansi{font:700 14px Arial,sans-serif;letter-spacing:.03em;margin:0}
.kop .daerah{font:700 14px Arial,sans-serif;margin:2px 0 14px}
.kop hr{border:0;border-top:3px double #000;margin:0}
h1.judul{text-align:center;font-size:16px;text-decoration:underline;letter-spacing:.02em;margin:22px 0 4px}
p.nomor{text-align:center;margin:0 0 24px}
p.pembuka{margin:0 0 18px;text-align:justify}
.field-row{display:flex;margin:2px 0}
.field-row .label{width:190px;flex:0 0 190px}
.field-row .sep{width:14px}
.field-row .val{flex:1;border-bottom:1px dotted #777;min-height:20px}
.saran-heading{margin:22px 0 8px;font-weight:700}
.bidang-heading{margin:16px 0 4px;font-weight:700}
.bidang-heading .nama-tpa{font-weight:400;font-style:italic}
ol{margin:4px 0 0;padding-left:24px}
ol li{margin-bottom:4px}
.kosong{color:#777;font-style:italic}
.penutup{margin-top:30px}
.ttd-wrap{margin-top:10px}
.ttd-tempat{margin-bottom:14px}
table.ttd{width:100%;border-collapse:collapse;margin-top:6px}
table.ttd td{padding:14px 16px;vertical-align:top}
table.ttd td.no{width:28px;text-align:center}
table.ttd td.garis{width:260px}
@media print{
  body{background:#fff}
  .toolbar{display:none}
  .sheet{box-shadow:none;margin:0;padding:0;max-width:none}
  @page{size:A4;margin:2cm}
}
</style></head>
<body>
<div class="toolbar">
<a href="<?= base_url('pengajuan-pbg/tahap/'.$row['id'].'/3') ?>">← Kembali</a>
<button class="primary" onclick="window.print()">Cetak / Unduh PDF</button>
</div>
<div class="sheet">
  <div class="kop">
    <img src="<?= base_url('assets/img/lambang-cilacap.webp') ?>" alt="Lambang Kabupaten Cilacap">
    <p class="instansi">PEMERINTAH KABUPATEN CILACAP</p>
    <p class="daerah">TIM PROFESI AHLI PERSETUJUAN BANGUNAN GEDUNG</p>
    <hr>
  </div>
  <h1 class="judul">BERITA ACARA KONSULTASI TPA</h1>
  <p class="nomor">NOMOR : <?= htmlspecialchars($nomor) ?></p>

  <p class="pembuka">Konsultasi TPA Kabupaten Cilacap yang memeriksa dokumen rencana teknis pada hari <?= htmlspecialchars($ba_hari) ?>,
  tanggal <?= htmlspecialchars($ba_tgl) ?>, Konsultasi ke-<?= (int) $putaran ?> untuk bidang: Arsitektur, Struktur dan MEP atas :</p>

  <div class="field-row"><div class="label">Nama Pemilik</div><div class="sep">:</div><div class="val"><?= htmlspecialchars($row['nama_pemohon']) ?></div></div>
  <div class="field-row"><div class="label">Nama Bangunan Gedung</div><div class="sep">:</div><div class="val"><?= htmlspecialchars($row['nama_bangunan'] ?: '—') ?></div></div>
  <div class="field-row"><div class="label">Lokasi Bangunan</div><div class="sep">:</div><div class="val"><?= htmlspecialchars($row['alamat_bangunan']) ?></div></div>
  <div class="field-row"><div class="label">Nomor Registrasi</div><div class="sep">:</div><div class="val"><?= htmlspecialchars($row['nik'] ?: '—') ?></div></div>

  <p class="saran-heading">Saran dan Masukan dari Tim Profesi Ahli <small style="font-weight:400">(review terakhir tiap bidang per saat BA ini terbit)</small></p>
  <?php foreach (array('arsitektur','struktur','mep') as $kode): $s = $snapshot[$kode] ?? null; ?>
    <p class="bidang-heading">TPA <?= $bidang_label[$kode] ?><?php if($s):?> <span class="nama-tpa">(<?= htmlspecialchars($s['nama_tpa'] ?: 'TPA') ?> — <?= date('d/m/Y H:i', strtotime($s['reviewed_at'])) ?>)</span><?php endif;?> :</p>
    <?php
      $poin = array();
      if ($s) {
        $teks = trim((string) $s['rekomendasi']);
        foreach (preg_split('/\r\n|\r|\n/', $teks) as $baris) { $baris = trim($baris); if ($baris !== '') $poin[] = $baris; }
      }
    ?>
    <?php if (!$s): ?>
      <p class="kosong">Belum ada review dari bidang ini.</p>
    <?php elseif (empty($poin)): ?>
      <p class="kosong">Tidak ada saran dan masukan tertulis.</p>
    <?php else: ?>
      <ol><?php foreach ($poin as $p): ?><li><?= htmlspecialchars($p) ?></li><?php endforeach; ?></ol>
    <?php endif; ?>
  <?php endforeach; ?>

  <p class="penutup">Demikian berita acara ini dibuat, untuk bisa digunakan sebagaimana mestinya.</p>
  <div class="ttd-wrap">
    <p class="ttd-tempat">Cilacap, <?= htmlspecialchars($ba_tgl) ?></p>
    <table class="ttd"><tbody>
    <?php $no = 1; foreach (array('arsitektur','struktur','mep') as $kode): $s = $snapshot[$kode] ?? null; ?>
      <tr>
        <td class="no"><?= $no++ ?></td>
        <td>TPA <?= $bidang_label[$kode] ?><?php if($s):?><br><small><?= htmlspecialchars($s['nama_tpa'] ?: 'TPA') ?></small><?php endif;?></td>
        <td class="garis">………………………..</td>
      </tr>
    <?php endforeach; ?>
    </tbody></table>
  </div>
</div>
</body></html>
