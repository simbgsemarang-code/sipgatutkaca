<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cagar Budaya — SIP Gatutkaca · Kabupaten Cilacap</title>
<link rel="icon" type="image/png" href="<?php echo base_url('assets/img/icon.png'); ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
<style>
:root{
  --gold-500:#C9A24B;--gold-300:#E4C87B;--gold-100:#F3E3B8;
  --display:'Marcellus',serif;--body:'Plus Jakarta Sans',system-ui,sans-serif;
}
html[data-theme="dark"]{
  --bg:#081826;--bg-alt:#0C2236;--surface:#0C2236;--surface-hi:#123249;
  --text:#F8F4EA;--muted:#B9C7D2;--line:rgba(201,162,75,.28);
  --head-bg:rgba(8,24,38,.94);--head-grad:rgba(8,24,38,.85);
  --hero-1:rgba(8,24,38,.95);--hero-2:rgba(8,24,38,.78);--hero-3:rgba(8,24,38,.28);
  --foot:#050F19;--input:#0F2A40;--shadow:rgba(0,0,0,.5);
}
html[data-theme="light"]{
  --bg:#F8F4EA;--bg-alt:#EFE7D6;--surface:#FFFDF6;--surface-hi:#F5EDDA;
  --text:#152A3B;--muted:#4E6070;--line:rgba(160,124,45,.35);
  --head-bg:rgba(248,244,234,.94);--head-grad:rgba(248,244,234,.85);
  --hero-1:rgba(13,29,44,.88);--hero-2:rgba(13,29,44,.66);--hero-3:rgba(13,29,44,.22);
  --foot:#122536;--input:#FFFFFF;--shadow:rgba(21,42,59,.18);
  --gold-500:#A57E2C;--gold-300:#8F6C1F;--gold-100:#6E5314;
}
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
body{font-family:var(--body);background:var(--bg);color:var(--text);line-height:1.7;font-weight:300;transition:background .4s,color .4s}
img{display:block;max-width:100%}
a{color:inherit;text-decoration:none}
.wrap{max-width:1180px;margin:0 auto;padding:0 28px}

header{position:fixed;inset:0 0 auto 0;z-index:60;transition:.4s;background:linear-gradient(180deg,var(--head-grad),transparent)}
header.scrolled{background:var(--head-bg);backdrop-filter:blur(12px);box-shadow:0 1px 0 var(--line)}
.nav{display:flex;align-items:center;justify-content:space-between;height:84px;gap:18px}
.brand{display:flex;align-items:center;gap:13px;flex:0 0 auto}
.brand img{height:50px;width:auto;filter:drop-shadow(0 2px 6px var(--shadow))}
.brand-name{font-family:var(--display);font-size:1.2rem;letter-spacing:.13em;color:var(--gold-300)}
.brand-sub{font-size:.6rem;letter-spacing:.3em;text-transform:uppercase;color:var(--muted)}
.auth-actions{display:flex;align-items:center;gap:14px}

.hero{position:relative;display:flex;align-items:center;overflow:hidden}
.hero.page{min-height:56vh}
.hero-bg{position:absolute;inset:0;background-position:center 60%;background-size:cover;transform:scale(1.06);animation:slowzoom 26s ease-out forwards}
@keyframes slowzoom{to{transform:scale(1)}}
.hero::after{content:"";position:absolute;inset:0;background:
  linear-gradient(105deg,var(--hero-1) 0%,var(--hero-2) 42%,var(--hero-3) 78%),
  linear-gradient(0deg,var(--bg) 0%,transparent 30%)}
.hero .wrap{position:relative;z-index:2;padding-top:120px;padding-bottom:80px;color:#F8F4EA}
.hero-eyebrow{display:inline-flex;align-items:center;gap:12px;font-size:.72rem;letter-spacing:.4em;text-transform:uppercase;color:#E4C87B;margin-bottom:24px}
.hero-eyebrow::before{content:"";width:44px;height:1px;background:#C9A24B}
h1{font-family:var(--display);font-weight:400;font-size:clamp(2.4rem,5.6vw,4.4rem);line-height:1.08;max-width:18ch;color:#F8F4EA}
h1 em{font-style:normal;color:#E4C87B}
.hero-lead{max-width:54ch;margin:26px 0 0;color:#CBD6DF;font-size:1rem}

.btn{display:inline-block;padding:15px 34px;font-size:.78rem;letter-spacing:.26em;text-transform:uppercase;transition:.3s;cursor:pointer;border:none;font-family:var(--body)}
.btn-gold{background:linear-gradient(135deg,#C9A24B,#E4C87B);color:#081826;font-weight:600}
.btn-gold:hover{filter:brightness(1.08);transform:translateY(-2px)}
.btn-ghost{border:1px solid rgba(248,244,234,.45);color:#F8F4EA;background:transparent}
.btn-ghost:hover{border-color:#C9A24B;color:#E4C87B}
.btn-sm{padding:11px 26px;font-size:.72rem;letter-spacing:.2em}

section{padding:100px 0}
section.alt{background:var(--bg-alt)}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.8rem,3.4vw,2.6rem);line-height:1.2;max-width:24ch}
.section-lead{color:var(--muted);max-width:66ch;margin-top:18px}

.cards{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--line);margin-top:60px;border:1px solid var(--line)}
.card{background:var(--surface);padding:42px 28px 38px;transition:background .35s}
.card:hover{background:var(--surface-hi)}
.card svg{margin-bottom:22px}
.card h3{font-family:var(--display);font-weight:400;font-size:1.24rem;letter-spacing:.06em;margin-bottom:12px;color:var(--gold-300)}
.card p{font-size:.9rem;color:var(--muted)}

.split{display:grid;grid-template-columns:1.05fr .95fr;gap:70px;align-items:center}
.split.rev{grid-template-columns:.95fr 1.05fr}
.photo-frame{position:relative}
.photo-frame img{width:100%;height:460px;object-fit:cover;filter:saturate(.92) contrast(1.04)}
.photo-frame::before{content:"";position:absolute;inset:18px -18px -18px 18px;border:1px solid var(--line);z-index:0;pointer-events:none}
.photo-frame img{position:relative;z-index:1}
.photo-cap{margin-top:14px;font-size:.7rem;letter-spacing:.18em;text-transform:uppercase;color:var(--muted)}

.list{margin-top:30px}
.list-item{display:flex;gap:20px;padding:20px 0;border-bottom:1px solid var(--line)}
.list-item:first-child{border-top:1px solid var(--line)}
.list-key{font-family:var(--display);color:var(--gold-300);letter-spacing:.06em;min-width:170px;font-size:.95rem;flex:0 0 auto}
.list-val{color:var(--muted);font-size:.92rem}

table{width:100%;border-collapse:collapse;margin-top:44px;font-size:.9rem}
th{font-family:var(--display);font-weight:400;letter-spacing:.12em;text-transform:uppercase;font-size:.74rem;color:var(--gold-300);text-align:left;padding:16px 14px;border-bottom:1px solid var(--gold-500)}
td{padding:16px 14px;border-bottom:1px solid var(--line);color:var(--muted);vertical-align:top}
td:first-child{color:var(--text);font-weight:500}
.tag{display:inline-block;border:1px solid var(--line);padding:3px 12px;font-size:.68rem;letter-spacing:.16em;text-transform:uppercase;color:var(--gold-300)}
.note{font-size:.78rem;color:var(--muted);margin-top:16px}

.steps{margin-top:60px;display:grid;gap:0}
.step{display:grid;grid-template-columns:90px 1fr;gap:30px;padding:34px 0;border-bottom:1px solid var(--line);align-items:start}
.step:first-child{border-top:1px solid var(--line)}
.step-num{font-family:var(--display);font-size:2.4rem;color:var(--gold-300);line-height:1}
.step h3{font-family:var(--display);font-weight:400;font-size:1.25rem;color:var(--text);margin-bottom:8px;letter-spacing:.04em}
.step p{color:var(--muted);font-size:.93rem;max-width:70ch}

footer{background:var(--foot);color:#F8F4EA;padding:66px 0 32px;border-top:1px solid var(--line)}
.foot-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr;gap:50px}
.foot-grid h4{font-family:var(--display);font-weight:400;letter-spacing:.14em;color:#E4C87B;margin-bottom:18px;font-size:1rem}
.foot-grid p,.foot-grid li{font-size:.88rem;color:#B9C7D2}
.foot-grid ul{list-style:none;display:grid;gap:10px}
.foot-grid a:hover{color:#E4C87B}
.credit{margin-top:50px;padding-top:22px;border-top:1px solid rgba(185,199,210,.15);display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap;font-size:.72rem;color:rgba(185,199,210,.6);letter-spacing:.06em}

.theme-fab{position:fixed;right:26px;bottom:26px;z-index:80;width:56px;height:56px;border-radius:50%;border:1px solid var(--gold-500);background:var(--surface);color:var(--gold-300);cursor:pointer;display:grid;place-items:center;box-shadow:0 8px 26px var(--shadow);transition:transform .3s}
.theme-fab:hover{transform:rotate(24deg)}
.theme-panel{position:fixed;right:26px;bottom:94px;z-index:80;background:var(--surface);border:1px solid var(--line);box-shadow:0 16px 42px var(--shadow);padding:22px;width:230px;opacity:0;transform:translateY(12px);pointer-events:none;transition:.3s}
.theme-panel.open{opacity:1;transform:none;pointer-events:auto}
.theme-panel h5{font-family:var(--display);font-weight:400;letter-spacing:.2em;text-transform:uppercase;font-size:.72rem;color:var(--gold-300);margin-bottom:16px}
.swatches{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.swatch{border:1px solid var(--line);background:none;cursor:pointer;padding:10px;display:grid;gap:8px;justify-items:center;transition:.25s}
.swatch:hover{border-color:var(--gold-500)}
.swatch.sel{border-color:var(--gold-500);box-shadow:0 0 0 1px var(--gold-500)}
.swatch i{width:100%;height:34px;display:block;border:1px solid var(--line)}
.swatch .sw-dark{background:linear-gradient(135deg,#081826,#123249)}
.swatch .sw-light{background:linear-gradient(135deg,#F8F4EA,#EFE7D6)}
.swatch span{font-size:.66rem;letter-spacing:.2em;text-transform:uppercase;color:var(--muted)}

.reveal{opacity:0;transform:translateY(28px);transition:opacity .8s ease,transform .8s ease}
.reveal.in{opacity:1;transform:none}
@media (prefers-reduced-motion:reduce){.reveal{opacity:1;transform:none;transition:none}.hero-bg{animation:none;transform:none}html{scroll-behavior:auto}}

@media(max-width:980px){
  .cards{grid-template-columns:repeat(2,1fr)}
  .split,.split.rev{grid-template-columns:1fr;gap:44px}
  .foot-grid{grid-template-columns:1fr}
}
@media(max-width:560px){
  .cards{grid-template-columns:1fr}
  .photo-frame img{height:320px}
  section{padding:76px 0}
  .step{grid-template-columns:56px 1fr;gap:18px}
  .step-num{font-size:1.7rem}
}
</style>
</head>
<body>

<header id="topbar">
  <div class="wrap nav">
    <a class="brand" href="<?php echo base_url(); ?>" aria-label="Beranda SIP Gatutkaca">
      <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Seal_of_Cilacap_Regency.svg?width=120" alt="Lambang Kabupaten Cilacap">
      <span>
        <span class="brand-name">SIP GATUTKACA</span><br>
        <span class="brand-sub">Kabupaten Cilacap</span>
      </span>
    </a>
    <div class="auth-actions">
      <a class="btn btn-ghost btn-sm" href="<?php echo base_url('konsultasi'); ?>">Masuk</a>
      <a class="btn btn-gold btn-sm" href="<?php echo base_url('login'); ?>">Daftar</a>
    </div>
  </div>
</header>

<section class="hero page">
  <div class="hero-bg" style="background-image:url('hero-kantor-dpupr.jpg')" role="img" aria-label="Gedung Kantor DPUPR Kabupaten Cilacap"></div>
  <div class="wrap">
    <p class="hero-eyebrow">Pelestarian Warisan</p>
    <h1>Menjaga <em>Cagar Budaya</em> Kabupaten Cilacap</h1>
    <p class="hero-lead">Bangunan dan struktur bersejarah adalah bagian dari identitas ruang kabupaten. Setiap rencana pembangunan di sekitarnya wajib memperhatikan status pelindungan cagar budaya sebelum persetujuan diterbitkan.</p>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="reveal">
      <p class="eyebrow">Dasar Perlindungan</p>
      <h2>Apa yang Dimaksud Cagar Budaya</h2>
      <p class="section-lead">Berdasarkan Undang-Undang Nomor 11 Tahun 2010 tentang Cagar Budaya, benda, bangunan, struktur, lokasi, atau satuan ruang geografis dapat ditetapkan sebagai cagar budaya apabila memenuhi kriteria usia sekurang-kurangnya 50 tahun, mewakili gaya sezaman, memiliki nilai penting bagi sejarah, ilmu pengetahuan, pendidikan, agama, dan/atau kebudayaan, serta memiliki nilai budaya untuk memperkuat kepribadian bangsa.</p>
    </div>
    <div class="cards reveal">
      <div class="card">
        <svg width="36" height="36" viewBox="0 0 40 40" aria-hidden="true"><rect x="8" y="16" width="24" height="18" fill="#C9A24B" stroke="#0C2236" stroke-width="1.6"/><path d="M4 16L20 5l16 11H4z" fill="#C9A24B" stroke="#0C2236" stroke-width="1.6" stroke-linejoin="round"/><rect x="17" y="24" width="6" height="10" fill="#0C2236"/></svg>
        <h3>Bangunan Cagar Budaya</h3>
        <p>Susunan binaan berupa dinding, atap, lantai, dan struktur penopang lain yang menyatu dengan tanah, seperti gedung, rumah, atau tempat ibadah bersejarah.</p>
      </div>
      <div class="card">
        <svg width="36" height="36" viewBox="0 0 40 40" aria-hidden="true"><path d="M6 34l6-24h16l6 24H6z" fill="#C9A24B" stroke="#0C2236" stroke-width="1.6" stroke-linejoin="round"/><path d="M14 20h12M12 27h16" stroke="#0C2236" stroke-width="1.3"/></svg>
        <h3>Struktur Cagar Budaya</h3>
        <p>Susunan binaan yang menyatu dengan lokasinya dan sebagian atau seluruhnya berada di atas atau di dalam tanah/air, seperti benteng dan jembatan lama.</p>
      </div>
      <div class="card">
        <svg width="36" height="36" viewBox="0 0 40 40" aria-hidden="true"><circle cx="20" cy="20" r="14" fill="none" stroke="#C9A24B" stroke-width="1.8"/><circle cx="20" cy="20" r="4" fill="#B4573B"/></svg>
        <h3>Situs Cagar Budaya</h3>
        <p>Lokasi yang berada di darat dan/atau di air yang mengandung objek diduga cagar budaya, termasuk lingkungan di sekitarnya.</p>
      </div>
      <div class="card">
        <svg width="36" height="36" viewBox="0 0 40 40" aria-hidden="true"><path d="M5 30l8-14 6 9 5-11 11 16H5z" fill="#C9A24B" stroke="#0C2236" stroke-width="1.6" stroke-linejoin="round"/></svg>
        <h3>Kawasan Cagar Budaya</h3>
        <p>Satuan ruang geografis yang memiliki dua situs cagar budaya atau lebih yang letaknya berdekatan dan/atau memperlihatkan ciri tata ruang yang khas.</p>
      </div>
    </div>
  </div>
</section>

<section class="alt">
  <div class="wrap split">
    <figure class="photo-frame reveal">
      <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Barracks,_Benteng_Pendem,_Cilacap_2015-03-21.jpg?width=1200" alt="Barak Benteng Pendem Cilacap" loading="lazy">
      <figcaption class="photo-cap">Benteng Pendem — salah satu peninggalan sejarah di Kabupaten Cilacap</figcaption>
    </figure>
    <div class="reveal">
      <p class="eyebrow">Kriteria Penetapan</p>
      <h2>Nilai yang Dipertimbangkan</h2>
      <div class="list">
        <div class="list-item"><span class="list-key">Usia</span><span class="list-val">Berusia 50 tahun atau lebih, atau mewakili gaya arsitektur khas suatu masa.</span></div>
        <div class="list-item"><span class="list-key">Kesejarahan</span><span class="list-val">Memiliki keterkaitan dengan peristiwa atau tokoh penting dalam sejarah wilayah.</span></div>
        <div class="list-item"><span class="list-key">Keilmuan</span><span class="list-val">Bermanfaat bagi pengembangan ilmu pengetahuan, teknologi, dan pendidikan.</span></div>
        <div class="list-item"><span class="list-key">Kebudayaan</span><span class="list-val">Memperkuat kepribadian bangsa dan menjadi bagian dari identitas kolektif daerah.</span></div>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="reveal">
      <p class="eyebrow">Tata Cara</p>
      <h2>Alur Pengusulan Penetapan</h2>
      <p class="section-lead">Warga, komunitas, maupun instansi dapat mengusulkan suatu bangunan atau situs untuk dikaji dan ditetapkan sebagai cagar budaya melalui tahapan berikut.</p>
    </div>
    <div class="steps reveal">
      <div class="step"><span class="step-num">01</span><div><h3>Pendaftaran Objek Diduga Cagar Budaya</h3><p>Pemilik, masyarakat, atau instansi mendaftarkan objek melalui Tim Pendaftaran Cagar Budaya kabupaten disertai data awal dan foto.</p></div></div>
      <div class="step"><span class="step-num">02</span><div><h3>Kajian Tim Ahli Cagar Budaya</h3><p>Tim Ahli Cagar Budaya (TACB) melakukan kajian terhadap nilai penting objek berdasarkan kriteria usia, kesejarahan, keilmuan, dan kebudayaan.</p></div></div>
      <div class="step"><span class="step-num">03</span><div><h3>Rekomendasi Penetapan</h3><p>Hasil kajian yang memenuhi kriteria direkomendasikan kepada Bupati untuk ditetapkan sebagai cagar budaya.</p></div></div>
      <div class="step"><span class="step-num">04</span><div><h3>Penerbitan SK &amp; Pencatatan Register</h3><p>Objek yang ditetapkan dicatat dalam Register Nasional Cagar Budaya dan mendapat perlindungan hukum atas pemanfaatan maupun perubahannya.</p></div></div>
    </div>
  </div>
</section>

<section class="alt">
  <div class="wrap">
    <div class="reveal">
      <p class="eyebrow">Perlindungan &amp; Perizinan</p>
      <h2>Contoh Objek Diduga Cagar Budaya</h2>
      <p class="section-lead">Setiap rencana renovasi, pembongkaran, atau alih fungsi pada objek berikut memerlukan kajian dan izin khusus sebelum PBG dapat diproses.</p>
    </div>
    <table class="reveal">
      <thead><tr><th>Objek</th><th>Kategori</th><th>Lokasi</th><th>Status</th></tr></thead>
      <tbody>
        <tr><td>Benteng Pendem</td><td>Struktur Cagar Budaya</td><td>Cilacap Selatan</td><td><span class="tag">Terdaftar</span></td></tr>
        <tr><td>Stasiun Kereta Api Cilacap</td><td>Bangunan Cagar Budaya</td><td>Cilacap Tengah</td><td><span class="tag">Dalam Kajian</span></td></tr>
        <tr><td>Rumah Dinas Karesidenan Lama</td><td>Bangunan Cagar Budaya</td><td>Cilacap Tengah</td><td><span class="tag">Dalam Kajian</span></td></tr>
        <tr><td>Kawasan Pecinan Cilacap</td><td>Kawasan Cagar Budaya</td><td>Cilacap Selatan</td><td><span class="tag">Diusulkan</span></td></tr>
      </tbody>
    </table>
    <p class="note">Daftar bersifat contoh tampilan untuk ilustrasi alur layanan; rujuk Tim Ahli Cagar Budaya (TACB) dan Register Nasional Cagar Budaya untuk data resmi dan termutakhir.</p>
  </div>
</section>

<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <div class="brand" style="margin-bottom:18px">
          <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Seal_of_Cilacap_Regency.svg?width=120" alt="" style="height:56px">
          <span>
            <span class="brand-name" style="font-size:1.05rem">SIP GATUTKACA</span><br>
            <span class="brand-sub">Sistem Informasi Penataan Ruang</span>
          </span>
        </div>
        <p>Dinas Pekerjaan Umum dan Penataan Ruang Kabupaten Cilacap. Melayani dengan semangat <em>“otot kawat, balung wesi”</em> — kokoh dalam aturan, luwes dalam pelayanan.</p>
      </div>
      <div>
        <h4>Layanan</h4>
        <ul>
          <li><a href="<?php echo base_url('regulasi'); ?>">Regulasi</a></li>
          <li><a href="<?php echo base_url('analisa-kerusakan'); ?>">Analisa Kerusakan</a></li>
          <li><a href="<?php echo base_url('pbg'); ?>">PBG</a></li>
          <li><a href="<?php echo base_url('slf'); ?>">SLF</a></li>
          <li><a href="<?php echo base_url('cagar-budaya'); ?>">Cagar Budaya</a></li>
          <li><a href="<?php echo base_url('saran-masukan'); ?>">Saran dan Masukan</a></li>
        </ul>
      </div>
      <div>
        <h4>Kontak</h4>
        <ul>
          <li>Jl. MT. Haryono, Cilacap, Jawa Tengah</li>
          <li>Senin–Jumat · 08.00–15.30 WIB</li>
          <li>siptaru@cilacapkab.go.id</li>
        </ul>
      </div>
    </div>
    <div class="credit">
      <span>© 2026 Pemerintah Kabupaten Cilacap · Jala Bhumi Wijayakusuma Cakti</span>
      <span>Foto: Wikimedia Commons (lisensi CC BY / CC BY-SA, kreator masing-masing)</span>
    </div>
  </div>
</footer>

<button class="theme-fab" id="themeFab" aria-expanded="false" aria-controls="themePanel" title="Pilih warna latar">
  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3a9 9 0 100 18c1.2 0 2-.9 2-2 0-.5-.2-1-.5-1.4-.3-.4-.5-.8-.5-1.3 0-1.1.9-2 2-2h2.3A4.7 4.7 0 0021 9.7C20.4 5.9 16.6 3 12 3z" stroke="currentColor" stroke-width="1.4"/><circle cx="7.5" cy="11" r="1.2" fill="currentColor"/><circle cx="10.5" cy="7.5" r="1.2" fill="currentColor"/><circle cx="15" cy="7.5" r="1.2" fill="currentColor"/></svg>
</button>
<div class="theme-panel" id="themePanel" role="dialog" aria-label="Pilih warna latar">
  <h5>Warna Latar</h5>
  <div class="swatches">
    <button class="swatch" data-theme="light"><i class="sw-light"></i><span>Terang</span></button>
    <button class="swatch sel" data-theme="dark"><i class="sw-dark"></i><span>Gelap</span></button>
  </div>
</div>

<script>
(function(){
  var p=new URLSearchParams(location.search);
  var t=p.get('theme')==='light'?'light':'dark';
  applyTheme(t,false);

  function applyTheme(theme,rewrite){
    document.documentElement.setAttribute('data-theme',theme);
    document.querySelectorAll('.swatch').forEach(function(s){
      s.classList.toggle('sel',s.dataset.theme===theme);
    });
    document.querySelectorAll('a[href]').forEach(function(a){
      var h=a.getAttribute('href');
      if(!h||/^(https?:|mailto:|#)/.test(h))return;
      var parts=h.split('#');var base=parts[0].split('?')[0];
      a.setAttribute('href',base+'?theme='+theme+(parts[1]?'#'+parts[1]:''));
    });
  }
  window.__applyTheme=applyTheme;
})();

var fab=document.getElementById('themeFab'),panel=document.getElementById('themePanel');
fab.addEventListener('click',function(){
  var open=panel.classList.toggle('open');
  fab.setAttribute('aria-expanded',open);
});
document.querySelectorAll('.swatch').forEach(function(s){
  s.addEventListener('click',function(){ window.__applyTheme(s.dataset.theme,true); });
});
document.addEventListener('click',function(e){
  if(!panel.contains(e.target)&&e.target!==fab&&!fab.contains(e.target))panel.classList.remove('open');
});

var bar=document.getElementById('topbar');
addEventListener('scroll',function(){bar.classList.toggle('scrolled',scrollY>40)},{passive:true});

var io=new IntersectionObserver(function(es){es.forEach(function(e){
  if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}
})},{threshold:.12});
document.querySelectorAll('.reveal').forEach(function(el){io.observe(el)});
</script>
</body>
</html>
