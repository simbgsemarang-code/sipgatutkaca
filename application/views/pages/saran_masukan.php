<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Saran dan Masukan — SIP Gatutkaca · Kabupaten Cilacap</title>
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

.btn{display:inline-block;padding:15px 34px;font-size:.78rem;letter-spacing:.26em;text-transform:uppercase;transition:.3s;cursor:pointer;border:none;font-family:var(--body)}
.btn-gold{background:linear-gradient(135deg,#C9A24B,#E4C87B);color:#081826;font-weight:600}
.btn-gold:hover{filter:brightness(1.08);transform:translateY(-2px)}
.btn-gold:disabled{opacity:.6;cursor:not-allowed}
.btn-ghost{border:1px solid rgba(248,244,234,.45);color:#F8F4EA;background:transparent}
.btn-ghost:hover{border-color:#C9A24B;color:#E4C87B}
.btn-sm{padding:11px 26px;font-size:.72rem;letter-spacing:.2em}

section{padding:100px 0}
section.alt{background:var(--bg-alt)}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.8rem,3.4vw,2.6rem);line-height:1.2;max-width:24ch}
.section-lead{color:var(--muted);max-width:66ch;margin-top:18px}

.split{display:grid;grid-template-columns:.9fr 1.1fr;gap:70px;align-items:start}

.list{margin-top:30px}
.list-item{display:flex;gap:20px;padding:20px 0;border-bottom:1px solid var(--line)}
.list-item:first-child{border-top:1px solid var(--line)}
.list-key{font-family:var(--display);color:var(--gold-300);letter-spacing:.06em;min-width:150px;font-size:.95rem;flex:0 0 auto}
.list-val{color:var(--muted);font-size:.92rem}

.form-card{background:var(--surface);border:1px solid var(--line);padding:46px}
.field{margin-bottom:22px}
.field-row{display:grid;grid-template-columns:1fr 1fr;gap:18px}
label{display:block;font-size:.72rem;letter-spacing:.24em;text-transform:uppercase;color:var(--muted);margin-bottom:9px}
input,select,textarea{width:100%;background:var(--input);border:1px solid var(--line);color:var(--text);padding:13px 15px;font-family:var(--body);font-size:.92rem}
input:focus,select:focus,textarea:focus{outline:1px solid var(--gold-500);border-color:var(--gold-500)}
textarea{resize:vertical;min-height:130px}
.note{font-size:.78rem;color:var(--muted);margin-top:16px}
.req{color:#E0526B}

.alert{padding:16px 20px;margin-bottom:26px;font-size:.88rem;border:1px solid}
.alert-ok{background:rgba(46,168,79,.12);border-color:#2EA84F;color:#8CE0A6}
.alert-err{background:rgba(224,82,107,.12);border-color:#E0526B;color:#F3AEB9}

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
@media (prefers-reduced-motion:reduce){.reveal{opacity:1;transform:none;transition:none}html{scroll-behavior:auto}}

@media(max-width:980px){
  .split{grid-template-columns:1fr;gap:44px}
  .foot-grid{grid-template-columns:1fr}
}
@media(max-width:560px){
  section{padding:76px 0}
  .field-row{grid-template-columns:1fr}
  .form-card{padding:30px 24px}
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
      <a class="btn btn-ghost btn-sm" href="<?php echo base_url('login'); ?>">Masuk</a>
      <a class="btn btn-gold btn-sm" href="<?php echo base_url('login'); ?>">Daftar</a>
    </div>
  </div>
</header>

<section style="padding-top:calc(84px + 100px)">
  <div class="wrap split">
    <div class="reveal">
      <p class="eyebrow">Kami Mendengarkan</p>
      <h2>Saran dan Masukan</h2>
      <p class="section-lead">Sampaikan saran, kritik, atau masukan Anda terkait layanan SIP Gatutkaca maupun penataan bangunan di Kabupaten Cilacap. Setiap masukan akan ditinjau oleh tim DPUPR Kabupaten Cilacap.</p>
      <div class="list">
        <div class="list-item"><span class="list-key">Respons</span><span class="list-val">Masukan ditinjau pada hari kerja, Senin–Jumat pukul 08.00–15.30 WIB.</span></div>
        <div class="list-item"><span class="list-key">Kerahasiaan</span><span class="list-val">Data kontak Anda hanya digunakan untuk menindaklanjuti masukan, tidak dipublikasikan.</span></div>
        <div class="list-item"><span class="list-key">Pengaduan Teknis</span><span class="list-val">Untuk permohonan PBG/SLF, gunakan menu Konsultasi agar diproses oleh tim yang sesuai.</span></div>
      </div>
    </div>

    <div class="reveal">
      <?php if (!empty($sukses)): ?>
        <div class="alert alert-ok"><?php echo htmlspecialchars($sukses, ENT_QUOTES, 'UTF-8'); ?></div>
      <?php endif; ?>
      <?php if (!empty($error)): ?>
        <div class="alert alert-err"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
      <?php endif; ?>

      <form class="form-card" action="<?php echo base_url('saran-masukan/kirim'); ?>" method="post">
        <div class="field-row">
          <div class="field">
            <label for="nama">Nama <span class="req">*</span></label>
            <input id="nama" name="nama" type="text" required value="<?php echo isset($old['nama']) ? htmlspecialchars($old['nama'], ENT_QUOTES, 'UTF-8') : ''; ?>">
          </div>
          <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="<?php echo isset($old['email']) ? htmlspecialchars($old['email'], ENT_QUOTES, 'UTF-8') : ''; ?>">
          </div>
        </div>
        <div class="field-row">
          <div class="field">
            <label for="no_hp">No. HP / WhatsApp</label>
            <input id="no_hp" name="no_hp" type="text" value="<?php echo isset($old['no_hp']) ? htmlspecialchars($old['no_hp'], ENT_QUOTES, 'UTF-8') : ''; ?>">
          </div>
          <div class="field">
            <label for="topik">Topik</label>
            <input id="topik" name="topik" type="text" placeholder="Mis. Layanan PBG, Tampilan Peta, dll" value="<?php echo isset($old['topik']) ? htmlspecialchars($old['topik'], ENT_QUOTES, 'UTF-8') : ''; ?>">
          </div>
        </div>
        <div class="field">
          <label for="pesan">Saran / Masukan <span class="req">*</span></label>
          <textarea id="pesan" name="pesan" required><?php echo isset($old['pesan']) ? htmlspecialchars($old['pesan'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
        </div>
        <button class="btn btn-gold" type="submit">Kirim Masukan</button>
        <p class="note">Kolom bertanda <span class="req">*</span> wajib diisi.</p>
      </form>
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
          <li><a href="<?php echo base_url('konsultasi/pbg'); ?>">PBG</a></li>
          <li><a href="<?php echo base_url('konsultasi/slf'); ?>">SLF</a></li>
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
