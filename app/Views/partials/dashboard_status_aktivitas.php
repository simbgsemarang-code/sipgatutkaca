<?php
/* Partial: seksi "Distribusi Status" + "Aktivitas Terkini".
 * Dipakai di dashboard PU & TPA. Butuh: $status_label, $distribusi,
 * $aktivitas (baris ternormalisasi: ikon/judul/reg/baris/badge/meta).
 * Opsional: $aktivitas_more_url, $aktivitas_kosong_teks. */
$status_label = isset($status_label) ? $status_label : array();
$distribusi   = isset($distribusi) ? $distribusi : array();
$aktivitas    = isset($aktivitas) ? $aktivitas : array();
$more_url      = isset($aktivitas_more_url) ? $aktivitas_more_url : '';
$kosong_teks   = isset($aktivitas_kosong_teks) ? $aktivitas_kosong_teks : 'Belum ada aktivitas.';
$dsa_max      = 1;
foreach ($distribusi as $vv) { if ((int) $vv > $dsa_max) $dsa_max = (int) $vv; }
?>
<style>
.dsa-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:20px}
@media(max-width:900px){.dsa-grid{grid-template-columns:1fr}}
.dsa-panel{background:var(--surface);border:1px solid var(--line);padding:26px 26px 14px}
.dsa-panel h3{font-family:var(--display);font-weight:400;font-size:1.25rem;color:var(--gold-300);letter-spacing:.04em;margin:0}
.dsa-panel .dsa-sub{color:var(--muted);font-size:.8rem;margin:4px 0 6px}
.dsa-head{display:flex;justify-content:space-between;align-items:baseline;gap:14px}
.dsa-head a{font-size:.7rem;letter-spacing:.14em;text-transform:uppercase;color:var(--gold-300)}
.dsa-row{padding:13px 0}
.dsa-row:not(:last-child){border-bottom:1px solid var(--line)}
.dsa-top{display:flex;justify-content:space-between;align-items:baseline;gap:12px}
.dsa-top .k{color:var(--text);font-size:.9rem}
.dsa-top .v{font-family:var(--display);font-size:1.05rem;color:var(--text)}
.dsa-bar{margin-top:7px;height:8px;border-radius:999px;background:var(--surface-hi);overflow:hidden}
.dsa-bar i{display:block;height:100%;border-radius:999px;background:var(--gold-500)}
.dsa-act{display:flex;gap:13px;padding:14px 0;align-items:flex-start}
.dsa-act:not(:last-child){border-bottom:1px solid var(--line)}
.dsa-av{flex:0 0 auto;width:38px;height:38px;border-radius:50%;background:var(--surface-hi);border:1px solid var(--line);display:grid;place-items:center;font-family:var(--display);color:var(--gold-300);font-size:.95rem}
.dsa-body{min-width:0;flex:1}
.dsa-name{color:var(--text);font-weight:500;font-size:.88rem}
.dsa-name .reg{color:var(--muted);font-weight:300}
.dsa-line{margin-top:3px;font-size:.83rem;color:var(--muted)}
.dsa-badge{display:inline-block;background:var(--surface-hi);border:1px solid var(--line);color:var(--gold-300);font-size:.64rem;font-weight:600;letter-spacing:.06em;padding:2px 9px;border-radius:999px;text-transform:uppercase}
.dsa-time{margin-top:3px;font-size:.72rem;color:var(--muted)}
</style>

<div class="dsa-grid">
  <div class="dsa-panel">
    <h3>Distribusi Status</h3>
    <p class="dsa-sub">Permohonan PBG &amp; SLF berdasarkan status.</p>
    <?php foreach ($status_label as $k => $lbl): $n = isset($distribusi[$k]) ? (int) $distribusi[$k] : 0; ?>
      <div class="dsa-row">
        <div class="dsa-top">
          <span class="k"><?php echo htmlspecialchars($lbl, ENT_QUOTES, 'UTF-8'); ?></span>
          <span class="v"><?php echo $n; ?></span>
        </div>
        <div class="dsa-bar"><i style="width:<?php echo $n === 0 ? 0 : round($n / $dsa_max * 100); ?>%"></i></div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="dsa-panel">
    <div class="dsa-head">
      <h3>Aktivitas Terkini</h3>
      <?php if ($more_url !== ''): ?><a href="<?php echo $more_url; ?>">Lihat Semua</a><?php endif; ?>
    </div>
    <?php if (empty($aktivitas)): ?>
      <p class="dsa-sub" style="padding:12px 0"><?php echo htmlspecialchars($kosong_teks, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php else: foreach ($aktivitas as $a): ?>
      <div class="dsa-act">
        <div class="dsa-av"><?php echo htmlspecialchars($a['ikon'], ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="dsa-body">
          <div class="dsa-name"><?php echo htmlspecialchars($a['judul'], ENT_QUOTES, 'UTF-8'); ?> <span class="reg">(<?php echo htmlspecialchars($a['reg'], ENT_QUOTES, 'UTF-8'); ?>)</span></div>
          <div class="dsa-line"><?php echo htmlspecialchars($a['baris'], ENT_QUOTES, 'UTF-8'); ?> <span class="dsa-badge"><?php echo htmlspecialchars($a['badge'], ENT_QUOTES, 'UTF-8'); ?></span></div>
          <div class="dsa-time"><?php echo htmlspecialchars($a['meta'], ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
      </div>
    <?php endforeach; endif; ?>
  </div>
</div>
