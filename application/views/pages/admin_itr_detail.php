<?php $perusahaan=($r['jenis_pemohon']??'perorangan')==='perusahaan'; ?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Pengajuan ITR — Panel Admin · SIP Gatutkaca</title>
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
  --foot:#050F19;--input:#0F2A40;--shadow:rgba(0,0,0,.5);
}
html[data-theme="light"]{
  --bg:#FDFBF5;--bg-alt:#F6F1E3;--surface:#FFFFFF;--surface-hi:#FAF5E8;
  --text:#152A3B;--muted:#4E6070;--line:rgba(160,124,45,.35);
  --head-bg:rgba(253,251,245,.94);--head-grad:rgba(253,251,245,.85);
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

.user-menu{position:relative}
.user-menu-btn{display:flex;align-items:center;gap:8px;background:none;border:none;font-family:var(--body);font-size:.78rem;letter-spacing:.08em;color:var(--gold-300);font-weight:600;cursor:pointer;padding:8px 2px}
.user-menu-btn svg{transition:transform .25s}
.user-menu-btn[aria-expanded="true"] svg{transform:rotate(180deg)}
.user-menu-panel{position:absolute;top:calc(100% + 12px);right:0;min-width:190px;background:var(--surface);border:1px solid var(--line);box-shadow:0 16px 42px var(--shadow);padding:8px;opacity:0;transform:translateY(8px);pointer-events:none;transition:.25s;z-index:70}
.user-menu-panel.open{opacity:1;transform:none;pointer-events:auto}
.user-menu-panel a{display:flex;align-items:center;gap:10px;padding:11px 14px;font-size:.76rem;letter-spacing:.04em;color:var(--text)}
.user-menu-panel a:hover{background:var(--surface-hi);color:var(--gold-300)}
.user-menu-panel a.logout:hover{color:#E0526B;background:rgba(224,82,107,.08)}

.dash-layout{display:flex;padding-top:84px}
.dash-sidebar{width:240px;flex:0 0 240px;height:calc(100vh - 84px);position:sticky;top:84px;background:var(--surface);border-right:1px solid var(--line);padding:40px 0;display:flex;flex-direction:column;justify-content:space-between;overflow-y:auto}
.dash-sidebar nav{display:flex;flex-direction:column;gap:4px}
.dash-sidebar a{display:flex;align-items:center;gap:12px;padding:14px 28px;font-size:.78rem;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);border-left:3px solid transparent;transition:.25s}
.dash-sidebar a:hover{color:var(--text);background:var(--surface-hi)}
.dash-sidebar a.active{color:var(--gold-300);border-left-color:var(--gold-500);background:var(--surface-hi)}
.dash-sidebar a.logout:hover{color:#E0526B;background:rgba(224,82,107,.08)}
.dash-main{flex:1;min-width:0}
.dash-wrap{max-width:980px;margin:0;padding:0 44px}
@media(max-width:860px){
  .dash-layout{flex-direction:column}
  .dash-sidebar{width:100%;flex:0 0 auto;height:auto;min-height:0;flex-direction:row;justify-content:space-between;align-items:center;border-right:none;border-bottom:1px solid var(--line);padding:0}
  .dash-sidebar nav{flex-direction:row;justify-content:center;flex-wrap:wrap}
  .dash-sidebar a{padding:14px 20px;border-left:none;border-bottom:3px solid transparent}
  .dash-sidebar a.active{border-left-color:transparent;border-bottom-color:var(--gold-500)}
  .dash-wrap{padding:0 24px}
}

.btn{display:inline-block;padding:15px 34px;font-size:.78rem;letter-spacing:.26em;text-transform:uppercase;transition:.3s;cursor:pointer;border:none;font-family:var(--body)}
.btn-gold{background:linear-gradient(135deg,#C9A24B,#E4C87B);color:#081826;font-weight:600}
.btn-ghost{border:1px solid var(--line);color:var(--text);background:transparent}
.btn-ghost:hover{border-color:#C9A24B;color:#E4C87B}
.btn-danger{background:#E0526B;color:#fff}
.btn-sm{padding:11px 26px;font-size:.72rem;letter-spacing:.2em}

section{padding:60px 0 100px}
.eyebrow{font-size:.7rem;letter-spacing:.38em;text-transform:uppercase;color:var(--gold-500);margin-bottom:14px}
h2{font-family:var(--display);font-weight:400;font-size:clamp(1.6rem,3vw,2.2rem);line-height:1.2}
.alert{padding:16px 20px;margin:26px 0 0;font-size:.88rem;border:1px solid}
.alert-ok{background:rgba(46,168,79,.12);border-color:#2EA84F;color:#1c7a37}
.alert-err{background:rgba(224,82,107,.12);border-color:#E0526B;color:#a3283d}
html[data-theme="dark"] .alert-ok{color:#8CE0A6}
html[data-theme="dark"] .alert-err{color:#F3AEB9}
.tag{display:inline-block;border:1px solid var(--line);padding:4px 14px;font-size:.68rem;letter-spacing:.16em;text-transform:uppercase;color:var(--gold-300);margin-top:14px}
.tag-diajukan{color:#F0A048;border-color:#B4573B}
.tag-sedang_diverifikasi{color:#5FC2E0;border-color:#1E86A3}
.tag-perlu_perbaikan{color:#E0526B;border-color:#E0526B}
.tag-disetujui{color:#6FCF97;border-color:#2EA84F}
.tag-ditolak{color:#E0526B;border-color:#E0526B}
.tag-diterima{color:#6FCF97;border-color:#2EA84F;margin-top:0}
.tag-menunggu{color:#5FC2E0;border-color:#1E86A3;margin-top:0}
.tag-kosong{color:var(--muted);margin-top:0}

.card{background:var(--surface);border:1px solid var(--line);padding:32px 36px;margin-top:26px}
.card h4{font-family:var(--display);font-weight:400;font-size:1.05rem;color:var(--gold-300);margin-bottom:20px;letter-spacing:.04em}
.kv-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px 30px}
.kv{border-bottom:1px solid var(--line);padding-bottom:12px}
.kv span{display:block;font-size:.68rem;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);margin-bottom:5px}
.kv b{font-weight:500;font-size:.92rem;white-space:pre-line}
.kv.full{grid-column:1 / -1}
@media(max-width:640px){.kv-grid{grid-template-columns:1fr}}

.itr-review-row{padding:16px 18px;border:1px solid var(--line);margin-top:14px}
.itr-review-row:first-child{margin-top:0}
.itr-review-row.itr-review-diterima{background:rgba(46,168,79,.06);border-color:#2EA84F}
.itr-review-row.itr-review-ditolak{background:rgba(224,82,107,.08);border-color:#E0526B}
.itr-review-row.itr-review-kosong{display:flex;align-items:center;justify-content:space-between;gap:12px;opacity:.7}
.itr-review-head{display:flex;align-items:center;gap:12px;flex-wrap:wrap}
.itr-review-head b{font-size:.92rem}
.itr-review-catatan{margin:8px 0 0;color:#E0526B;font-size:.82rem}
.itr-review-form{display:flex;gap:12px;align-items:flex-start;margin-top:12px;flex-wrap:wrap}
.itr-review-form textarea{flex:1;min-width:220px;min-height:44px;padding:10px;border:1px solid var(--line);background:var(--surface);color:var(--text);font-family:var(--body)}
.itr-review-actions{display:flex;align-items:center;gap:8px;flex-shrink:0}
.itr-review-pesan{font-size:.76rem;font-weight:600}
.itr-review-pesan.ok{color:#2EA84F}
.itr-review-pesan.err{color:#E0526B}
.itr-review-actions .btn{padding:10px 20px;font-size:.68rem;letter-spacing:.14em}
label{display:block;font-size:.78rem;margin-bottom:7px;color:var(--muted)}
select,textarea,input[type=file]{width:100%;padding:13px;border:1px solid var(--line);background:var(--surface);color:var(--text);font-family:var(--body)}
textarea{resize:vertical}
.riwayat-item{padding:14px 0;border-bottom:1px solid var(--line);font-size:.85rem}
.riwayat-item:last-child{border-bottom:none}
.riwayat-item small{display:block;margin-top:6px;color:var(--muted)}

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
@media(max-width:980px){.foot-grid{grid-template-columns:1fr}}
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
      <a class="btn btn-ghost btn-sm" href="<?php echo base_url(); ?>">Beranda</a>
      <div class="user-menu">
        <button class="user-menu-btn" id="userMenuBtn" type="button" aria-expanded="false" aria-controls="userMenuPanel">
          <?php echo htmlspecialchars($nama_pengguna, ENT_QUOTES, 'UTF-8'); ?>
          <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2.5 4.5L6 8l3.5-3.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="user-menu-panel" id="userMenuPanel" role="menu">
          <a href="<?php echo base_url('pengaturan'); ?>" role="menuitem">Pengaturan</a>
          <a href="<?php echo base_url('login/keluar'); ?>" role="menuitem" class="logout">Logout</a>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="dash-layout">
  <aside class="dash-sidebar">
    <nav>
      <a href="<?php echo base_url('admin'); ?>">Dashboard</a>
      <a href="<?php echo base_url('admin/pengguna'); ?>">Kelola Pengguna</a>
      <a href="<?php echo base_url('admin/pengajuan'); ?>">Pengajuan PBG</a>
      <a href="<?= base_url('admin_itr') ?>" class="active">Pengajuan ITR</a>
      <a href="<?php echo base_url('admin/bangunan'); ?>">Sebaran Bangunan</a>
      <a href="<?php echo base_url('admin/cagar-budaya'); ?>">Kelola Cagar Budaya</a>
      <a href="<?php echo base_url('admin/aturan'); ?>">Kelola Aturan</a>
      <a href="<?php echo base_url('admin/saran'); ?>">Saran &amp; FAQ</a>
    </nav>
    <nav>
      <a href="<?php echo base_url('login/keluar'); ?>" class="logout">Logout</a>
    </nav>
  </aside>
  <div class="dash-main">
<section style="padding-top:100px">
  <div class="dash-wrap">
    <p class="eyebrow"><a href="<?= base_url('admin_itr') ?>" style="color:var(--gold-500);text-decoration:underline">&larr; Kembali ke Daftar Pengajuan ITR</a></p>
    <h2><?= htmlspecialchars($r['no_permohonan']) ?></h2>
    <span id="statusTag" class="tag tag-<?= htmlspecialchars($r['status'],ENT_QUOTES,'UTF-8') ?>"><?= ucwords(str_replace('_',' ',$r['status'])) ?></span>

    <?php if($this->session->flashdata('sukses')): ?><div class="alert alert-ok"><?= htmlspecialchars($this->session->flashdata('sukses')) ?></div><?php endif; ?>
    <?php if($this->session->flashdata('error')): ?><div class="alert alert-err"><?= htmlspecialchars($this->session->flashdata('error')) ?></div><?php endif; ?>

    <div class="card">
      <h4>Biodata Pemohon</h4>
      <div class="kv-grid">
        <div class="kv"><span><?= $perusahaan?'Nama Direktur':'Nama Pemilik' ?></span><b><?= htmlspecialchars($r['nama_pemohon']) ?></b></div>
        <div class="kv"><span>Jenis Pemohon</span><b><?= $perusahaan?'Perusahaan':'Perorangan' ?></b></div>
        <div class="kv"><span>Email</span><b><?= htmlspecialchars($r['email']) ?></b></div>
        <div class="kv"><span>No. HP</span><b><?= htmlspecialchars($r['no_hp']) ?></b></div>
        <?php if($perusahaan): ?><div class="kv"><span>NIB</span><b><?= htmlspecialchars($r['nib']?:'—') ?></b></div><?php else: ?><div class="kv"><span>NIK</span><b><?= htmlspecialchars($r['nik']?:'—') ?></b></div><div class="kv"><span>Pekerjaan</span><b><?= htmlspecialchars($r['pekerjaan']?:'—') ?></b></div><?php endif; ?>
        <div class="kv"><span>No. NPWP</span><b><?= htmlspecialchars($r['no_npwp']?:'—') ?></b></div>
        <div class="kv full"><span><?= $perusahaan?'Alamat Perusahaan':'Alamat Pemohon' ?></span><b><?= htmlspecialchars($r['alamat_pemohon']?:'—') ?></b></div>
      </div>
    </div>

    <div class="card">
      <h4>Data Bangunan &amp; Lokasi</h4>
      <div class="kv-grid">
        <div class="kv"><span>Jenis Kegiatan/Usaha</span><b><?= htmlspecialchars($r['jenis_kegiatan']?:'—') ?></b></div>
        <div class="kv"><span>Fungsi Bangunan</span><b><?= htmlspecialchars($r['fungsi_bangunan']?:'—') ?></b></div>
        <div class="kv full"><span>Lokasi Kegiatan</span><b><?= htmlspecialchars(trim(($r['lokasi_jalan']?:'').', RT/RW '.($r['lokasi_rt_rw']?:'-').', '.($r['lokasi_desa_kel']?:'').', Kec. '.($r['lokasi_kecamatan']?:''),' ,')) ?></b></div>
        <div class="kv"><span>Luas Lahan</span><b><?= number_format($r['luas_lahan'],2,',','.') ?> m²</b></div>
        <div class="kv"><span>Luas Bangunan</span><b><?= $r['luas_bangunan']!==null?number_format($r['luas_bangunan'],2,',','.').' m²':'—' ?></b></div>
        <div class="kv"><span>Lantai Bangunan</span><b><?= htmlspecialchars($r['lantai_bangunan']?:'—') ?></b></div>
        <div class="kv"><span>Status Tanah</span><b><?= htmlspecialchars($r['status_tanah']?:'—') ?></b></div>
        <div class="kv"><span>Penggunaan Air Baku</span><b><?= htmlspecialchars($r['penggunaan_air']?:'—') ?></b></div>
        <div class="kv full"><span>Keterangan Perijinan</span><b><?= htmlspecialchars($r['keterangan_perijinan']?:'—') ?></b></div>
        <div class="kv full"><span>Titik Koordinat Poligon</span><b><?php $titik=json_decode((string)($r['titik_koordinat']??''),TRUE); if(is_array($titik)&&$titik): ?><?= implode(' · ',array_map(function($t){return number_format($t['lat'],6).', '.number_format($t['lng'],6);},$titik)) ?><?php else: ?><?= htmlspecialchars($r['latitude'].', '.$r['longitude']) ?> (pusat)<?php endif; ?></b></div>
      </div>
    </div>

    <div class="card">
      <h4>Berkas &amp; Tinjauan</h4>
      <?php $berkas=array('file_permohonan'=>'Surat Permohonan','file_ktp'=>'KTP','file_sertifikat'=>'Sertifikat','file_siteplan'=>'Site plan','file_denah_foto'=>'Denah &amp; Foto'); if($perusahaan) $berkas+=array('file_nib'=>'NIB','file_npwp'=>'NPWP','file_akta'=>'Akta Perusahaan'); foreach($berkas as $field=>$label): if(empty($r[$field])): ?>
      <div class="itr-review-row itr-review-kosong"><b><?= $label ?></b><span class="tag tag-kosong">Belum diunggah</span></div>
      <?php continue; endif; $st=$r['_status_berkas'][$field]??null; $status=$st['status']??'menunggu'; ?>
      <div class="itr-review-row itr-review-<?= $status ?>" data-field="<?= htmlspecialchars($field,ENT_QUOTES,'UTF-8') ?>">
        <div class="itr-review-head"><b><?= $label ?></b><span class="tag tag-<?= $status ?>" data-role="status-tag"><?= ucfirst($status) ?></span><a class="btn btn-ghost btn-sm" style="padding:8px 18px" target="_blank" href="<?= base_url('admin_itr/berkas/'.$r['id'].'/'.$field) ?>">Lihat Berkas</a></div>
        <p class="itr-review-catatan" data-role="catatan"<?= ($status==='ditolak'&&!empty($st['catatan']))?'':' style="display:none"' ?>>Alasan sebelumnya: <?= $status==='ditolak'?nl2br(htmlspecialchars($st['catatan']??'')):'' ?></p>
        <?= form_open('admin_itr/tinjau-berkas/'.$r['id'],array('class'=>'itr-review-form')) ?>
        <input type="hidden" name="itr_token" value="<?= htmlspecialchars($this->session->userdata('admin_itr_token'),ENT_QUOTES,'UTF-8') ?>">
        <input type="hidden" name="field" value="<?= htmlspecialchars($field,ENT_QUOTES,'UTF-8') ?>">
        <textarea name="catatan" placeholder="Alasan penolakan (wajib kalau memilih Tolak)"></textarea>
        <div class="itr-review-actions"><button type="submit" name="keputusan" value="diterima" class="btn btn-gold">Terima</button><button type="submit" name="keputusan" value="ditolak" class="btn btn-danger">Tolak</button><span class="itr-review-pesan" data-role="pesan"></span></div>
        <?= form_close() ?>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="card">
      <h4>Dokumen Hasil ITR</h4>
      <div id="hasilSiap" style="<?= $r['_semua_diterima']?'':'display:none' ?>">
        <?php if(!empty($r['file_hasil_itr'])): ?><div class="alert alert-ok">Sudah diterbitkan <?= !empty($r['hasil_diunggah_pada'])?('· '.date('d/m/Y H:i',strtotime($r['hasil_diunggah_pada']))):'' ?> — <a target="_blank" href="<?= base_url('admin_itr/hasil/'.$r['id']) ?>" style="text-decoration:underline">Lihat/Unduh</a>. Unggah file baru di bawah untuk menggantinya.</div><?php else: ?><div class="alert alert-ok">Semua berkas sudah diterima. Unggah dokumen resmi hasil ITR (PDF) supaya bisa diunduh pemohon.</div><?php endif; ?>
        <?= form_open_multipart('admin_itr/unggah-hasil/'.$r['id'],array('style'=>'margin-top:18px')) ?><input type="hidden" name="itr_token" value="<?= htmlspecialchars($this->session->userdata('admin_itr_token'),ENT_QUOTES,'UTF-8') ?>">
        <input type="file" name="file_hasil_itr" accept=".pdf" required><button class="btn btn-gold btn-sm" style="margin-top:14px"><?= empty($r['file_hasil_itr'])?'Unggah Hasil ITR':'Ganti Hasil ITR' ?></button><?= form_close() ?>
      </div>
      <p id="hasilBelum" style="color:var(--muted);font-size:.88rem<?= $r['_semua_diterima']?';display:none':'' ?>">Unggah dokumen hasil ITR tersedia setelah semua berkas di atas berstatus Diterima.</p>
    </div>

    <div class="card">
      <h4>Status &amp; Informasi Bebas</h4>
      <?= form_open('admin_itr/simpan/'.$r['id']) ?><input type="hidden" name="itr_token" value="<?= htmlspecialchars($this->session->userdata('admin_itr_token'),ENT_QUOTES,'UTF-8') ?>">
      <label for="statusSelect">Status Pengajuan (otomatis mengikuti tinjauan berkas, bisa ditimpa manual)</label>
      <select id="statusSelect" name="status"><?php foreach(array('diajukan'=>'Diajukan','sedang_diverifikasi'=>'Sedang diverifikasi','perlu_perbaikan'=>'Perlu perbaikan','disetujui'=>'Disetujui','ditolak'=>'Ditolak') as $code=>$label): ?><option value="<?= $code ?>" <?= $r['status']===$code?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select>
      <label for="pesan-<?= (int)$r['id'] ?>" style="margin-top:18px">Informasi untuk Pemohon</label>
      <textarea id="pesan-<?= (int)$r['id'] ?>" name="informasi" maxlength="10000" rows="3" placeholder="Tuliskan hasil pemeriksaan, permintaan perbaikan, atau informasi berikutnya."></textarea>
      <button class="btn btn-gold btn-sm" style="margin-top:16px">Simpan dan Kirim Informasi</button>
      <?= form_close() ?>
    </div>

    <div class="card">
      <h4>Riwayat Informasi</h4>
      <?php if(!$pesan): ?><p style="color:var(--muted);font-size:.88rem">Belum ada informasi terkirim.</p><?php else: foreach($pesan as $p): ?>
      <div class="riwayat-item"><strong><?= htmlspecialchars($p['nama_admin']?:'Administrator') ?></strong> · <?= date('d/m/Y H:i',strtotime($p['created_at'])) ?><p style="margin-top:6px"><?= nl2br(htmlspecialchars($p['isi'])) ?></p><small><?= $p['dibaca_pada']?'Sudah dibaca pemohon · '.date('d/m/Y H:i',strtotime($p['dibaca_pada'])):'Belum dibaca pemohon' ?></small></div>
      <?php endforeach; endif; ?>
    </div>
  </div>
</section>
  </div>
</div>

<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <div class="brand" style="margin-bottom:18px">
          <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Seal_of_Cilacap_Regency.svg?width=120" alt="" style="height:56px">
          <span>
            <span class="brand-name" style="font-size:1.05rem">SIP GATUTKACA</span><br>
            <span class="brand-sub">Sistem Informasi Pengelolaan Gedung</span>
          </span>
        </div>
        <p>Dinas Pekerjaan Umum dan Penataan Ruang Kabupaten Cilacap. Melayani dengan semangat <em>&ldquo;otot kawat, balung wesi&rdquo;</em> — kokoh dalam aturan, luwes dalam pelayanan.</p>
      </div>
      <div>
        <h4>Layanan</h4>
        <ul>
          <li><a href="<?php echo base_url('konsultasi'); ?>">Konsultasi Tata Ruang</a></li>
          <li><a href="<?php echo base_url('regulasi'); ?>">Pustaka Regulasi</a></li>
          <li><a href="<?php echo base_url('itr'); ?>">Informasi Tata Ruang</a></li>
          <li><a href="<?php echo base_url('tatacara'); ?>">Tata Cara KKPR</a></li>
          <li><a href="<?php echo base_url('spasial'); ?>">Peta Spasial</a></li>
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
    <button class="swatch sel" data-theme="light"><i class="sw-light"></i><span>Terang</span></button>
    <button class="swatch" data-theme="dark"><i class="sw-dark"></i><span>Gelap</span></button>
  </div>
</div>

<script>
(function(){
  var p=new URLSearchParams(location.search);
  var t=p.get('theme')==='dark'?'dark':'light';
  applyTheme(t);
  function applyTheme(theme){
    document.documentElement.setAttribute('data-theme',theme);
    document.querySelectorAll('.swatch').forEach(function(s){
      s.classList.toggle('sel',s.dataset.theme===theme);
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
  s.addEventListener('click',function(){ window.__applyTheme(s.dataset.theme); });
});
document.addEventListener('click',function(e){
  if(!panel.contains(e.target)&&e.target!==fab&&!fab.contains(e.target))panel.classList.remove('open');
});
var userBtn=document.getElementById('userMenuBtn'),userPanel=document.getElementById('userMenuPanel');
userBtn.addEventListener('click',function(){
  var open=userPanel.classList.toggle('open');
  userBtn.setAttribute('aria-expanded',open);
});
document.addEventListener('click',function(e){
  if(!userPanel.contains(e.target)&&e.target!==userBtn&&!userBtn.contains(e.target))userPanel.classList.remove('open');
});
var bar=document.getElementById('topbar');
addEventListener('scroll',function(){bar.classList.toggle('scrolled',scrollY>40)},{passive:true});

document.querySelectorAll('.itr-review-form').forEach(function(form){
  form.querySelectorAll('button[name="keputusan"]').forEach(function(tombol){
    tombol.addEventListener('click', function(e){
      e.preventDefault();
      var row=form.closest('.itr-review-row');
      var pesanEl=form.querySelector('[data-role="pesan"]');
      var semuaTombol=form.querySelectorAll('button[name="keputusan"]');
      var fd=new FormData(form);
      fd.set('keputusan', tombol.value);
      semuaTombol.forEach(function(b){ b.disabled=true; });
      pesanEl.className='itr-review-pesan'; pesanEl.textContent='';
      fetch(form.getAttribute('action'), {method:'POST', body:fd, headers:{'X-Requested-With':'XMLHttpRequest'}})
        .then(function(res){ return res.json(); })
        .then(function(data){
          semuaTombol.forEach(function(b){ b.disabled=false; });
          if(!data.ok){ pesanEl.className='itr-review-pesan err'; pesanEl.textContent=data.message||'Gagal menyimpan tinjauan.'; return; }
          row.className='itr-review-row itr-review-'+data.status;
          var tagBerkas=row.querySelector('[data-role="status-tag"]');
          tagBerkas.className='tag tag-'+data.status;
          tagBerkas.textContent=data.status.charAt(0).toUpperCase()+data.status.slice(1);
          var catatanEl=row.querySelector('[data-role="catatan"]');
          if(data.status==='ditolak'&&data.catatan){
            catatanEl.style.display=''; catatanEl.textContent='Alasan sebelumnya: '+data.catatan;
          } else {
            catatanEl.style.display='none'; catatanEl.textContent='';
          }
          form.querySelector('textarea[name="catatan"]').value='';
          var statusTag=document.getElementById('statusTag');
          statusTag.className='tag tag-'+data.status_pengajuan;
          statusTag.textContent=data.status_label;
          var statusSelect=document.getElementById('statusSelect');
          if(statusSelect) statusSelect.value=data.status_pengajuan;
          var hasilSiap=document.getElementById('hasilSiap'), hasilBelum=document.getElementById('hasilBelum');
          if(data.semua_diterima){ hasilSiap.style.display=''; hasilBelum.style.display='none'; }
          else { hasilSiap.style.display='none'; hasilBelum.style.display=''; }
          pesanEl.className='itr-review-pesan ok'; pesanEl.textContent=data.pesan||'Tersimpan.';
        })
        .catch(function(){
          semuaTombol.forEach(function(b){ b.disabled=false; });
          pesanEl.className='itr-review-pesan err'; pesanEl.textContent='Gagal menghubungi server, coba lagi.';
        });
    });
  });
});
</script>
</body>
</html>
