<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — Halaman Tidak Ditemukan · SIP Gatutkaca</title>
    <style>
        *{box-sizing:border-box}
        body{margin:0;min-height:100vh;display:grid;place-items:center;background:#FDFBF5;color:#152A3B;font:300 16px/1.7 "Plus Jakarta Sans",system-ui,Helvetica,Arial,sans-serif}
        .kotak{max-width:520px;margin:24px;padding:46px 40px;background:#fff;border:1px solid rgba(160,124,45,.35);border-radius:18px;text-align:center;box-shadow:0 18px 50px rgba(21,42,59,.12)}
        .kode{font:400 64px/1 Marcellus,Georgia,serif;color:#A57E2C;letter-spacing:.06em}
        h1{margin:14px 0 8px;font:400 24px/1.3 Marcellus,Georgia,serif}
        p{margin:0 0 26px;color:#4E6070}
        a{display:inline-block;padding:12px 26px;border-radius:999px;background:linear-gradient(135deg,#C9A24B,#E4C87B);color:#081826;font-weight:600;font-size:13px;letter-spacing:.14em;text-transform:uppercase;text-decoration:none}
    </style>
</head>
<body>
    <div class="kotak">
        <div class="kode">404</div>
        <h1>Halaman tidak ditemukan</h1>
        <p><?= (ENVIRONMENT !== 'production' && isset($message) && $message !== '(null)') ? nl2br(esc($message)) : 'Alamat yang Anda buka tidak tersedia atau sudah dipindahkan.' ?></p>
        <a href="<?= base_url() ?>">Kembali ke Beranda</a>
    </div>
</body>
</html>
