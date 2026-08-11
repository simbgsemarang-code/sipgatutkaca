<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Persyaratan SLF — SIP Gatutkaca · Kabupaten Cilacap</title>
<link rel="icon" type="image/png" href="<?php echo base_url('assets/img/icon.png'); ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
<style>
:root{
  --gold-500:#C9A24B;--gold-300:#E4C87B;--gold-100:#F3E3B8;
  --display:'Marcellus',serif;--body:'Plus Jakarta Sans',system-ui,sans-serif;
}
/* ====== TEMA GELAP (bawaan) ====== */
html[data-theme="dark"]{
  --bg:#081826;--bg-alt:#0C2236;--surface:#0C2236;--surface-hi:#123249;
  --text:#F8F4EA;--muted:#B9C7D2;--line:rgba(201,162,75,.28);
  --head-bg:rgba(8,24,38,.94);--head-grad:rgba(8,24,38,.85);
  --hero-1:rgba(8,24,38,.95);--hero-2:rgba(8,24,38,.78);--hero-3:rgba(8,24,38,.28);
  --foot:#050F19;--input:#0F2A40;--shadow:rgba(0,0,0,.5);
}
/* ====== TEMA TERANG ====== */
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

/* ===== NAVBAR ===== */
header{position:fixed;inset:0 0 auto 0;z-index:60;transition:.4s;background:linear-gradient(180deg,var(--head-grad),transparent)}
header.scrolled{background:var(--head-bg);backdrop-filter:blur(12px);box-shadow:0 1px 0 var(--line)}
.nav{display:flex;align-items:center;justify-content:space-between;height:84px;gap:18px}
.brand{display:flex;align-items:center;gap:13px;flex:0 0 auto}
.brand img{height:50px;width:auto;filter:drop-shadow(0 2px 6px var(--shadow))}
.brand-name{font-family:var(--display);font-size:1.2rem;letter-spacing:.13em;color:var(--gold-300)}
.brand-sub{font-size:.6rem;letter-spacing:.3em;text-transform:uppercase;color:var(--muted)}
nav ul{display:flex;align-items:center;gap:30px;list-style:none}
nav a{font-size:.78rem;letter-spacing:.2em;text-transform:uppercase;color:var(--text);position:relative;padding:6px 0;white-space:nowrap}
nav a::after{content:"";position:absolute;left:0;bottom:0;height:1px;width:0;background:var(--gold-500);transition:width .35s}
nav a:hover::after,nav a:focus-visible::after,nav a.active::after{width:100%}
nav a.active{color:var(--gold-300)}
nav a:focus-visible{outline:1px solid var(--gold-500);outline-offset:4px}
.nav-cta{border:1px solid var(--gold-500);color:var(--gold-300)!important;padding:10px 24px!important;letter-spacing:.24em;font-weight:600}
.nav-cta:hover{background:var(--gold-500);color:var(--bg)!important}
.nav-cta::after{display:none}
.burger{display:none;background:none;border:1px solid var(--line);color:var(--gold-300);font-size:1rem;padding:8px 14px;cursor:pointer}

/* ===== HERO / BANNER ===== */
.hero{position:relative;display:flex;align-items:center;overflow:hidden}
.hero.full{min-height:100vh}
.hero.page{min-height:56vh}
.hero-bg{position:absolute;inset:0;background-position:center 60%;background-size:cover;transform:scale(1.06);animation:slowzoom 26s ease-out forwards}
@keyframes slowzoom{to{transform:scale(1)}}
.hero::after{content:"";position:absolute;inset:0;background:
  linear-gradient(105deg,var(--hero-1) 0%,var(--hero-2) 42%,var(--hero-3) 78%),
  linear-gradient(0deg,var(--bg) 0%,transparent 30%)}
.hero .wrap{position:relative;z-index:2;padding-top:120px;padding-bottom:80px;color:#F8F4EA}
.hero-eyebrow{display:inline-flex;align-items:center;gap:12px;font-size:.72rem;letter-spacing:.4em;text-transform:uppercase;color:#E4C87B;margin-bottom:24px}
.hero-eyebrow::before{content:"";width:44px;height:1px;background:#C9A24B}
h1{font-family:var(--display);font-weight:400;font-size:clamp(2.4rem,5.6vw,4.4rem);line-height:1.08;max-width:16ch;color:#F8F4EA}
h1 em{font-style:normal;color:#E4C87B}
.hero-lead{max-width:54ch;margin:26px 0 38px;color:#CBD6DF;font-size:1rem}
.hero-motto{margin-top:56px;font-family:var(--display);letter-spacing:.3em;font-size:.8rem;color:rgba(228,200,123,.8);text-transform:uppercase}

.btn{display:inline-block;padding:15px 34px;font-size:.78rem;letter-spacing:.26em;text-transform:uppercase;transition:.3s;cursor:pointer;border:none;font-family:var(--body)}
.btn-gold{background:linear-gradient(135deg,#C9A24B,#E4C87B);color:#081826;font-weight:600}
.btn-gold:hover{filter:brightness(1.08);transform:translateY(-2px)}
.btn-ghost{border:1px solid rgba(248,244,234,.45);color:#F8F4EA;background:transparent}
.btn-ghost:hover{border-color:#C9A24B;color:#E4C87B}
.hero-actions{display:flex;gap:18px;flex-wrap:wrap}

/* ===== SECTION ===== */
section{padding:100px 0}
section.alt{background:var(--bg-alt)}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.8rem,3.4vw,2.6rem);line-height:1.2;max-width:24ch}
.section-lead{color:var(--muted);max-width:66ch;margin-top:18px}

/* ===== DAFTAR / LIST BERNOMOR ===== */
.reg-list{margin-top:44px;list-style:none}
.reg-list li{display:flex;gap:22px;padding:20px 0;border-bottom:1px solid var(--line);align-items:baseline}
.reg-list li:first-child{border-top:1px solid var(--line)}
.reg-num{font-family:var(--display);color:var(--gold-300);font-size:1rem;min-width:32px;flex:0 0 auto}
.reg-text{color:var(--text);font-size:.95rem;line-height:1.6}

/* ===== KATEGORI PERSYARATAN SLF ===== */
.pbg-cat{margin-top:44px}
.pbg-cat h3{display:inline-block;font-family:var(--display);font-weight:400;font-size:1.05rem;letter-spacing:.05em;padding:11px 24px;margin-bottom:2px}
.pbg-cat-a h3{background:linear-gradient(135deg,#C9A24B,#E4C87B);color:#081826}
.pbg-cat-b h3{background:linear-gradient(160deg,#1E86A3,#3BAFC9);color:#F8F4EA}
.pbg-cat .reg-list{margin-top:16px}

/* ===== TIMELINE TATA CARA / DATA TEKNIS ===== */
.steps{margin-top:46px;display:grid;gap:0}
.step{display:grid;grid-template-columns:90px 1fr;gap:30px;padding:34px 0;border-bottom:1px solid var(--line);align-items:start}
.step:first-child{border-top:1px solid var(--line)}
.step-num{font-family:var(--display);font-size:2.4rem;color:var(--gold-300);line-height:1}
.step h3{font-family:var(--display);font-weight:400;font-size:1.25rem;color:var(--text);margin-bottom:8px;letter-spacing:.04em}
.step p{color:var(--muted);font-size:.93rem;max-width:70ch}
.step .tag{margin-top:12px}

.pbg-sub{list-style:none;margin-top:12px}
.pbg-sub li{display:flex;gap:14px;padding:7px 0;color:var(--muted);font-size:.9rem}
.pbg-letter{font-family:var(--display);color:var(--gold-300);min-width:20px;flex:0 0 auto}

/* ===== TABEL & FORM ===== */
table{width:100%;border-collapse:collapse;margin-top:44px;font-size:.9rem}
th{font-family:var(--display);font-weight:400;letter-spacing:.12em;text-transform:uppercase;font-size:.74rem;color:var(--gold-300);text-align:left;padding:16px 14px;border-bottom:1px solid var(--gold-500)}
td{padding:16px 14px;border-bottom:1px solid var(--line);color:var(--muted);vertical-align:top}
td:first-child{color:var(--text);font-weight:500}
.tag{display:inline-block;border:1px solid var(--line);padding:3px 12px;font-size:.68rem;letter-spacing:.16em;text-transform:uppercase;color:var(--gold-300)}
.dl{color:var(--gold-300);letter-spacing:.12em;font-size:.78rem;text-transform:uppercase;white-space:nowrap}
.dl:hover{text-decoration:underline}

.form-card{background:var(--surface);border:1px solid var(--line);padding:46px;max-width:520px}
.form-card.center{margin:0 auto}
.field{margin-bottom:22px}
label{display:block;font-size:.72rem;letter-spacing:.24em;text-transform:uppercase;color:var(--muted);margin-bottom:9px}
input,select,textarea{width:100%;background:var(--input);border:1px solid var(--line);color:var(--text);padding:13px 15px;font-family:var(--body);font-size:.92rem}
input:focus,select:focus,textarea:focus{outline:1px solid var(--gold-500);border-color:var(--gold-500)}
.note{font-size:.78rem;color:var(--muted);margin-top:16px}

/* ===== PANEL WARNA ===== */
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

/* ===== FOOTER ===== */
footer{background:var(--foot);color:#F8F4EA;padding:66px 0 32px;border-top:1px solid var(--line)}
.foot-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr;gap:50px}
.foot-grid h4{font-family:var(--display);font-weight:400;letter-spacing:.14em;color:#E4C87B;margin-bottom:18px;font-size:1rem}
.foot-grid p,.foot-grid li{font-size:.88rem;color:#B9C7D2}
.foot-grid ul{list-style:none;display:grid;gap:10px}
.foot-grid a:hover{color:#E4C87B}
.credit{margin-top:50px;padding-top:22px;border-top:1px solid rgba(185,199,210,.15);display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap;font-size:.72rem;color:rgba(185,199,210,.6);letter-spacing:.06em}

/* ===== REVEAL ===== */
.reveal{opacity:0;transform:translateY(28px);transition:opacity .8s ease,transform .8s ease}
.reveal.in{opacity:1;transform:none}
@media (prefers-reduced-motion:reduce){
  .reveal{opacity:1;transform:none;transition:none}
  .hero-bg{animation:none;transform:none}
  html{scroll-behavior:auto}
}

/* ===== RESPONSIF ===== */
@media(max-width:1060px){nav ul{gap:20px}}
@media(max-width:980px){
  .foot-grid{grid-template-columns:1fr}
  nav ul{position:fixed;inset:84px 0 auto 0;background:var(--head-bg);backdrop-filter:blur(12px);flex-direction:column;gap:0;padding:10px 28px 26px;display:none;border-bottom:1px solid var(--line);align-items:flex-start}
  nav ul.open{display:flex}
  nav li{width:100%}
  nav a{display:block;padding:15px 0}
  .burger{display:block}
}
@media(max-width:560px){
  section{padding:76px 0}
  .step{grid-template-columns:56px 1fr;gap:18px}
  .step-num{font-size:1.7rem}
}
.auth-actions{display:flex;align-items:center;gap:14px}
.btn-sm{padding:11px 26px;font-size:.72rem;letter-spacing:.2em}
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
          <a class="btn btn-ghost btn-sm" href="<?php echo base_url('login?from=admin'); ?>">Masuk</a>
          <a class="btn btn-gold btn-sm" href="<?php echo base_url('login'); ?>">Daftar</a>
        </div>
  </div>
</header>

<section style="padding-top:calc(84px + 100px)">
  <div class="wrap">
    <div class="reveal">
      <p class="eyebrow">Persyaratan Layanan</p>
      <h2>Persyaratan SLF (Sertifikat Laik Fungsi)</h2>
      <p class="section-lead">Siapkan kelengkapan data umum dan data teknis berikut sebelum mengajukan Sertifikat Laik Fungsi (SLF), mengikuti Peraturan Bupati Cilacap Nomor 52 Tahun 2023 tentang Tata Cara Penyelenggaraan Persetujuan Bangunan Gedung dan Sertifikat Laik Fungsi.</p>
    </div>

    <div class="reveal" style="margin-top:56px">
      <p class="eyebrow" style="margin-bottom:0">Data Umum</p>
    </div>

    <div class="pbg-cat pbg-cat-a reveal">
      <h3>Bangunan Hunian / Rumah Tinggal</h3>
      <ol class="reg-list">
        <li><span class="reg-num">01</span><span class="reg-text">Data Identitas Pemilik Bangunan (KTP/KITAS)</span></li>
        <li><span class="reg-num">02</span><span class="reg-text">Data Intensitas Bangunan (KKPR/KRK)/Informasi Tata Ruang (ITR)</span></li>
        <li><span class="reg-num">03</span><span class="reg-text">Bukti Kepemilikan Tanah (Sertifikat Tanah/Girik/Latter C) dan SPPT / Keterangan NOP</span></li>
        <li><span class="reg-num">04</span><span class="reg-text">Data Penyedia Jasa Perencana Konstruksi atau Arsitek berlisensi (SKK/STRA/STRI)</span></li>
      </ol>
    </div>

    <div class="pbg-cat pbg-cat-b reveal">
      <h3>Bangunan Umum Non Hunian &amp; Campuran</h3>
      <ol class="reg-list">
        <li><span class="reg-num">01</span><span class="reg-text">Data Identitas Pemilik Bangunan (KTP/KITAS) dan NIB</span></li>
        <li><span class="reg-num">02</span><span class="reg-text">Data Intensitas Bangunan (KKPR/KRK)/Informasi Tata Ruang (ITR)</span></li>
        <li><span class="reg-num">03</span><span class="reg-text">Dokumen lingkungan sesuai peraturan perundangan (AMDAL, UKL/UPL, SPPL) sesuai dengan penapisan amdalnet</span></li>
        <li><span class="reg-num">04</span><span class="reg-text">Bukti Kepemilikan Tanah (Sertifikat Tanah/Girik/Latter C) dan SPPT / Keterangan NOP</span></li>
        <li><span class="reg-num">05</span><span class="reg-text">Data Penyedia Jasa Perencana Konstruksi atau Arsitek berlisensi (SKK/STRA/STRI)</span></li>
        <li><span class="reg-num">06</span><span class="reg-text">Surat Kerukunan Umat Beragama (SKUB) — khusus bangunan agama</span></li>
      </ol>
    </div>

    <div class="reveal" style="margin-top:70px">
      <p class="eyebrow" style="margin-bottom:0">Data Teknis</p>
      <h2 style="font-size:clamp(1.5rem,2.6vw,2rem);margin-top:14px">Kelengkapan Dokumen Rencana Teknis</h2>
    </div>

    <div class="steps reveal">
      <div class="step">
        <div class="step-num">1</div>
        <div>
          <h3>Data Teknis Arsitektur</h3>
          <ol class="pbg-sub">
            <li><span class="pbg-letter">a.</span><span>Gambar As Build Drawing Arsitektur</span></li>
            <li><span class="pbg-letter">b.</span><span>Spesifikasi Teknis Arsitektur Bangunan</span></li>
          </ol>
        </div>
      </div>
      <div class="step">
        <div class="step-num">2</div>
        <div>
          <h3>Data Teknis Struktur</h3>
          <ol class="pbg-sub">
            <li><span class="pbg-letter">a.</span><span>Gambar As Build Drawing Struktur</span></li>
            <li><span class="pbg-letter">b.</span><span>Spesifikasi Teknis Struktur Bangunan</span></li>
            <li><span class="pbg-letter">c.</span><span>Perhitungan Teknis Struktur</span></li>
          </ol>
        </div>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <div>
          <h3>Data Teknis MEP</h3>
          <ol class="pbg-sub">
            <li><span class="pbg-letter">a.</span><span>Gambar As Build Drawing Mekanikal, Elektrikal, dan Plambing</span></li>
            <li><span class="pbg-letter">b.</span><span>Spesifikasi Teknis Mekanikal, Elektrikal, dan Plambing</span></li>
          </ol>
        </div>
      </div>
      <div class="step">
        <div class="step-num">4</div>
        <div>
          <h3>Dokumen Bangunan Eksisting</h3>
          <ol class="pbg-sub">
            <li><span class="pbg-letter">a.</span><span>Laporan Pemeriksaan Kelaikan Fungsi Bangunan</span></li>
            <li><span class="pbg-letter">b.</span><span>Surat Pernyataan Kelaikan Fungsi</span></li>
          </ol>
        </div>
      </div>
    </div>

    <div class="reveal" style="margin-top:56px;text-align:center;display:flex;gap:18px;justify-content:center;flex-wrap:wrap">
      <a class="btn btn-gold" href="<?php echo base_url('login?from=slf'); ?>">Ajukan SLF →</a>
      <a class="btn btn-ghost" href="<?php echo base_url(); ?>">← Beranda</a>
    </div>
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
// ===== TEMA (tanpa penyimpanan browser: dibawa lewat parameter URL antar halaman) =====
(function(){
  var p=new URLSearchParams(location.search);
  var t=p.get('theme')==='light'?'light':'dark';
  applyTheme(t,false);

  function applyTheme(theme,rewrite){
    document.documentElement.setAttribute('data-theme',theme);
    document.querySelectorAll('.swatch').forEach(function(s){
      s.classList.toggle('sel',s.dataset.theme===theme);
    });
    // sisipkan tema ke seluruh tautan internal agar pilihan terbawa antar halaman
    document.querySelectorAll('a[href]').forEach(function(a){
      var h=a.getAttribute('href');
      if(!h||/^(https?:|mailto:|#)/.test(h))return;
      var parts=h.split('#');var base=parts[0].split('?')[0];
      a.setAttribute('href',base+'?theme='+theme+(parts[1]?'#'+parts[1]:''));
    });
  }
  window.__applyTheme=applyTheme;
})();

// ===== Panel warna =====
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

// ===== Navbar & menu ponsel =====
var bar=document.getElementById('topbar');
addEventListener('scroll',function(){bar.classList.toggle('scrolled',scrollY>40)},{passive:true});

// ===== Animasi muncul =====
var io=new IntersectionObserver(function(es){es.forEach(function(e){
  if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}
})},{threshold:.12});
document.querySelectorAll('.reveal').forEach(function(el){io.observe(el)});
</script>
</body>
</html>
