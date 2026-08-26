<!DOCTYPE html>
<html lang="id" data-theme="light">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda — SIP Gatutkaca · Kabupaten Cilacap</title>
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/img/icon.png'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Playfair+Display:ital,wght@1,500;1,600&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <style>
      :root {
        --gold-500: #C9A24B;
        --gold-300: #E4C87B;
        --gold-100: #F3E3B8;
        --display: 'Marcellus', serif;
        --body: 'Plus Jakarta Sans', system-ui, sans-serif;
      }

      /* ====== TEMA GELAP (bawaan) ====== */
      html[data-theme="dark"] {
        --bg: #081826;
        --bg-alt: #0C2236;
        --surface: #0C2236;
        --surface-hi: #123249;
        --text: #F8F4EA;
        --muted: #B9C7D2;
        --line: rgba(201, 162, 75, .28);
        --head-bg: rgba(8, 24, 38, .94);
        --head-grad: rgba(8, 24, 38, .85);
        --hero-1: rgba(8, 24, 38, .95);
        --hero-2: rgba(8, 24, 38, .78);
        --hero-3: rgba(8, 24, 38, .28);
        --foot: #050F19;
        --input: #0F2A40;
        --shadow: rgba(0, 0, 0, .5);
      }

      /* ====== TEMA TERANG ====== */
      html[data-theme="light"] {
        --bg: #FDFBF5;
        --bg-alt: #F6F1E3;
        --surface: #FFFFFF;
        --surface-hi: #FAF5E8;
        --text: #152A3B;
        --muted: #4E6070;
        --line: rgba(160, 124, 45, .35);
        --head-bg: rgba(253, 251, 245, .94);
        --head-grad: rgba(253, 251, 245, .85);
        --hero-1: rgba(13, 29, 44, .88);
        --hero-2: rgba(13, 29, 44, .66);
        --hero-3: rgba(13, 29, 44, .22);
        --foot: #122536;
        --input: #FFFFFF;
        --shadow: rgba(21, 42, 59, .18);
        --gold-500: #A57E2C;
        --gold-300: #8F6C1F;
        --gold-100: #6E5314;
      }

      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box
      }

      html {
        scroll-behavior: smooth
      }

      body {
        font-family: var(--body);
        background: var(--bg);
        color: var(--text);
        line-height: 1.7;
        font-weight: 300;
        transition: background .4s, color .4s;
        min-height: 100vh;
        display: flex;
        flex-direction: column
      }

      img {
        display: block;
        max-width: 100%
      }

      a {
        color: inherit;
        text-decoration: none
      }

      .wrap {
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 28px
      }

      /* ===== TOPBAR (tanpa navigasi) ===== */
      header {
        position: fixed;
        inset: 0 0 auto 0;
        z-index: 60;
        transition: .4s;
        background: linear-gradient(180deg, var(--head-grad), transparent)
      }

      header.scrolled {
        background: var(--head-bg);
        backdrop-filter: blur(12px);
        box-shadow: 0 1px 0 var(--line)
      }

      .nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 84px;
        gap: 18px
      }

      .brand {
        display: flex;
        align-items: center;
        gap: 13px;
        flex: 0 0 auto
      }

      .brand img {
        height: 50px;
        width: auto;
        filter: drop-shadow(0 2px 6px var(--shadow))
      }

      .brand-name {
        font-family: var(--display);
        font-size: 1.2rem;
        letter-spacing: .13em;
        color: var(--gold-300)
      }

      .brand-sub {
        font-size: .6rem;
        letter-spacing: .3em;
        text-transform: uppercase;
        color: var(--muted)
      }

      .auth-actions {
        display: flex;
        align-items: center;
        gap: 14px
      }

      .user-menu {
        position: relative
      }

      .user-menu-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background: none;
        border: none;
        font-family: var(--body);
        font-size: .78rem;
        letter-spacing: .08em;
        color: var(--gold-300);
        font-weight: 600;
        cursor: pointer;
        padding: 8px 2px
      }

      .user-menu-btn svg {
        transition: transform .25s
      }

      .user-menu-btn[aria-expanded="true"] svg {
        transform: rotate(180deg)
      }

      .user-menu-panel {
        position: absolute;
        top: calc(100% + 12px);
        right: 0;
        min-width: 190px;
        background: var(--surface);
        border: 1px solid var(--line);
        box-shadow: 0 16px 42px var(--shadow);
        padding: 8px;
        opacity: 0;
        transform: translateY(8px);
        pointer-events: none;
        transition: .25s;
        z-index: 70
      }

      .user-menu-panel.open {
        opacity: 1;
        transform: none;
        pointer-events: auto
      }

      .user-menu-panel a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 14px;
        font-size: .76rem;
        letter-spacing: .04em;
        color: var(--text)
      }

      .user-menu-panel a:hover {
        background: var(--surface-hi);
        color: var(--gold-300)
      }

      .user-menu-panel a.logout:hover {
        color: #E0526B;
        background: rgba(224, 82, 107, .08)
      }

      /* ===== TOMBOL ===== */
      .btn {
        display: inline-block;
        padding: 15px 34px;
        font-size: .78rem;
        letter-spacing: .26em;
        text-transform: uppercase;
        transition: .3s;
        cursor: pointer;
        border: none;
        font-family: var(--body)
      }

      .btn-gold {
        background: linear-gradient(135deg, #C9A24B, #E4C87B);
        color: #081826;
        font-weight: 600
      }

      .btn-gold:hover {
        filter: brightness(1.08);
        transform: translateY(-2px)
      }

      .btn-ghost {
        border: 1px solid var(--line);
        color: var(--text);
        background: transparent
      }

      .btn-ghost:hover {
        border-color: #C9A24B;
        color: #E4C87B
      }

      .btn-sm {
        padding: 11px 26px;
        font-size: .72rem;
        letter-spacing: .2em
      }

      /* ===== LANDING / HERO PENUH ===== */
      .hero {
        position: relative;
        display: flex;
        align-items: center;
        overflow: hidden
      }

      .hero.full {
        min-height: 100vh
      }

      .hero-bg {
        position: absolute;
        inset: 0;
        background-position: center 60%;
        background-size: cover;
        transform: scale(1.06);
        animation: slowzoom 26s ease-out forwards
      }

      @keyframes slowzoom {
        to {
          transform: scale(1)
        }
      }

      .hero::after {
        content: "";
        position: absolute;
        inset: 0;
        background:
          linear-gradient(105deg, var(--hero-1) 0%, var(--hero-2) 42%, var(--hero-3) 78%),
          linear-gradient(0deg, var(--bg) 0%, transparent 30%)
      }

      .hero .wrap {
        position: relative;
        z-index: 2;
        width: 100%;
        padding-top: 150px;
        padding-bottom: 80px;
        text-align: center
      }

      /* ===== EMBLEM ===== */
      .emblem {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 20px
      }

      .emblem-diamond-wrap {
        position: relative;
        display: inline-block;
        width: 340px;
        max-width: 82vw
      }

      .emblem-diamond {
        width: 100%;
        height: 190px;
        background: linear-gradient(135deg, #E2993C 0%, var(--gold-500) 55%, #E2993C 100%);
        clip-path: polygon(4% 50%, 26% 8%, 74% 8%, 96% 50%, 74% 92%, 26% 92%);
        filter: drop-shadow(0 18px 34px var(--shadow));
        animation: diamondPulse 3.8s ease-in-out infinite
      }

      @keyframes diamondPulse {
        0%, 100% {
          transform: scale(1) rotate(0deg);
          filter: drop-shadow(0 18px 34px var(--shadow)) brightness(1)
        }
        50% {
          transform: scale(1.02) rotate(1deg);
          filter: drop-shadow(0 22px 40px var(--shadow)) brightness(1.07)
        }
      }

      .emblem-mascot {
        position: absolute;
        left: 50%;
        bottom: 18px;
        transform: translateX(-50%);
        width: 76%;
        z-index: 3;
        filter: drop-shadow(0 12px 20px var(--shadow));
        animation: mascotFloat 4.5s ease-in-out infinite
      }

      @keyframes mascotFloat {
        0%, 100% {
          transform: translateX(-50%) translateY(0) rotate(0deg)
        }
        50% {
          transform: translateX(-50%) translateY(-11px) rotate(-1.5deg)
        }
      }

      .emblem-sub {
        display: block;
        max-width: 320px;
        margin: -16px auto 0;
        background: var(--gold-500);
        color: var(--bg);
        font-size: .76rem;
        font-weight: 600;
        letter-spacing: .03em;
        line-height: 1.5;
        text-align: center;
        padding: 14px 24px;
        border-radius: 20px;
        border: 3px solid var(--bg);
        position: relative;
        z-index: 2
      }

      /* ===== TAGLINE ===== */
      .tagline {
        font-family: 'Playfair Display', serif;
        font-style: italic;
        font-weight: 600;
        font-size: clamp(1.15rem, 3vw, 1.7rem);
        line-height: 1.4;
        max-width: 640px;
        margin: 40px auto 0;
        color: var(--gold-100);
        text-shadow: 0 2px 10px var(--shadow)
      }

      /* ===== GRID MENU ===== */
      .menu-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 44px 30px;
        max-width: 940px;
        margin: 64px auto 0;
        justify-items: center
      }

      .menu-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        max-width: 190px
      }

      .menu-icon-box {
        width: 100px;
        height: 100px;
        display: grid;
        place-items: center;
        background: linear-gradient(160deg, #1E86A3, #145E75);
        border: 4px solid #F8F4EA;
        position: relative;
        z-index: 2;
        box-shadow: 0 10px 20px var(--shadow);
        transition: transform .3s, box-shadow .3s
      }

      .menu-icon-box svg {
        width: 74px;
        height: 74px
      }

      .menu-card:hover .menu-icon-box {
        transform: translateY(-4px) scale(1.04);
        box-shadow: 0 14px 26px var(--shadow)
      }

      .menu-label-box {
        width: 88%;
        margin-top: -16px;
        background: #E9EEF1;
        border: 1px solid #C7D2D8;
        padding: 22px 10px 10px;
        text-align: center
      }

      .menu-label-box span {
        font-family: var(--display);
        font-size: .88rem;
        font-weight: 600;
        letter-spacing: .04em;
        color: #223842
      }

      /* ===== FOOTER SEDERHANA ===== */
      footer.footer-simple {
        margin-top: auto;
        background: var(--foot);
        color: var(--muted);
        text-align: center;
        padding: 20px 0;
        border-top: 1px solid var(--line);
        font-size: .78rem;
        letter-spacing: .03em
      }

      /* ===== PANEL WARNA ===== */
      .theme-fab {
        position: fixed;
        right: 26px;
        bottom: 26px;
        z-index: 80;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        border: 1px solid var(--gold-500);
        background: var(--surface);
        color: var(--gold-300);
        cursor: pointer;
        display: grid;
        place-items: center;
        box-shadow: 0 8px 26px var(--shadow);
        transition: transform .3s
      }

      .theme-fab:hover {
        transform: rotate(24deg)
      }

      .theme-panel {
        position: fixed;
        right: 26px;
        bottom: 94px;
        z-index: 80;
        background: var(--surface);
        border: 1px solid var(--line);
        box-shadow: 0 16px 42px var(--shadow);
        padding: 22px;
        width: 230px;
        opacity: 0;
        transform: translateY(12px);
        pointer-events: none;
        transition: .3s
      }

      .theme-panel.open {
        opacity: 1;
        transform: none;
        pointer-events: auto
      }

      .theme-panel h5 {
        font-family: var(--display);
        font-weight: 400;
        letter-spacing: .2em;
        text-transform: uppercase;
        font-size: .72rem;
        color: var(--gold-300);
        margin-bottom: 16px
      }

      .swatches {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px
      }

      .swatch {
        border: 1px solid var(--line);
        background: none;
        cursor: pointer;
        padding: 10px;
        display: grid;
        gap: 8px;
        justify-items: center;
        transition: .25s
      }

      .swatch:hover {
        border-color: var(--gold-500)
      }

      .swatch.sel {
        border-color: var(--gold-500);
        box-shadow: 0 0 0 1px var(--gold-500)
      }

      .swatch i {
        width: 100%;
        height: 34px;
        display: block;
        border: 1px solid var(--line)
      }

      .swatch .sw-dark {
        background: linear-gradient(135deg, #081826, #123249)
      }

      .swatch .sw-light {
        background: linear-gradient(135deg, #F8F4EA, #EFE7D6)
      }

      .swatch span {
        font-size: .66rem;
        letter-spacing: .2em;
        text-transform: uppercase;
        color: var(--muted)
      }

      /* ===== REVEAL ===== */
      .reveal {
        opacity: 0;
        transform: translateY(28px);
        transition: opacity .8s ease, transform .8s ease
      }

      .reveal.in {
        opacity: 1;
        transform: none
      }

      @media (prefers-reduced-motion:reduce) {
        .reveal {
          opacity: 1;
          transform: none;
          transition: none
        }

        .hero-bg {
          animation: none;
          transform: none
        }

        .emblem-diamond,
        .emblem-mascot {
          animation: none
        }

        html {
          scroll-behavior: auto
        }
      }

      /* ===== RESPONSIF ===== */
      @media(max-width:760px) {
        .menu-grid {
          grid-template-columns: repeat(2, 1fr)
        }

        .emblem-diamond-wrap {
          width: 280px;
        }

        .emblem-diamond {
          height: 156px
        }
      }

      @media(max-width:460px) {
        .menu-grid {
          grid-template-columns: 1fr;
          max-width: 220px
        }

        .auth-actions .btn {
          padding: 9px 16px;
          letter-spacing: .12em
        }
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
        <?php $sesi_nav = info_sesi_navbar(); ?>
        <div class="auth-actions">
          <?php if ($sesi_nav['masuk']): ?>
            <a class="btn btn-ghost btn-sm" href="<?php echo base_url($sesi_nav['tujuan_dashboard']); ?>">Dashboard</a>
            <div class="user-menu">
              <button class="user-menu-btn" id="userMenuBtn" type="button" aria-expanded="false" aria-controls="userMenuPanel">
                <?php echo htmlspecialchars($sesi_nav['nama'], ENT_QUOTES, 'UTF-8'); ?>
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2.5 4.5L6 8l3.5-3.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </button>
              <div class="user-menu-panel" id="userMenuPanel" role="menu">
                <a href="<?php echo base_url('pengaturan'); ?>" role="menuitem">
                  <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="2.3" stroke="currentColor" stroke-width="1.3"/><path d="M8 1.5v1.6M8 12.9v1.6M14.5 8h-1.6M3.1 8H1.5M12.4 3.6l-1.1 1.1M4.7 11.3l-1.1 1.1M12.4 12.4l-1.1-1.1M4.7 4.7L3.6 3.6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                  Pengaturan
                </a>
                <a href="<?php echo base_url('login/keluar'); ?>" role="menuitem" class="logout">
                  <svg width="16" height="16" viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M7 2H3a1 1 0 00-1 1v12a1 1 0 001 1h4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M11 12.5L15 9l-4-3.5M15 9H6.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  Logout
                </a>
              </div>
            </div>
          <?php else: ?>
            <a class="btn btn-ghost btn-sm" href="<?php echo base_url('konsultasi'); ?>">Masuk</a>
            <a class="btn btn-gold btn-sm" href="<?php echo base_url('daftar'); ?>">Daftar</a>
          <?php endif; ?>
        </div>
      </div>
    </header>

    <section class="hero full" style="--hero-1:rgba(8,24,38,.93);--hero-2:rgba(8,24,38,.87);--hero-3:rgba(8,24,38,.55)">
      <div class="hero-bg" style="background-image:url('hero-kantor-dpupr.jpg')" role="img" aria-label="Gedung Kantor DPUPR Kabupaten Cilacap"></div>
      <div class="wrap">
        <div class="emblem reveal">
          <div class="emblem-diamond-wrap">
            <div class="emblem-diamond"></div>
            <img class="emblem-mascot" src="<?php echo base_url('assets/img/gatutkaca-transparent.png'); ?>" alt="Maskot Gatutkaca">
          </div>
          <span class="emblem-sub">Sistem Informasi Pengelolaan Gedung <strong>ANDAL</strong> dan <strong>TERPERCAYA</strong> Untuk Kabupaten Cilacap</span>
        </div>

        <p class="tagline reveal">Mewujudkan Infrastruktur Gedung Andal dan Terencana di Kabupaten Cilacap</p>

        <div class="menu-grid reveal">
          <a class="menu-card" href="<?php echo base_url('regulasi'); ?>">
            <span class="menu-icon-box">
              <svg width="40" height="40" viewBox="0 0 40 40" aria-hidden="true">
                <path d="M6 8c3-1.6 7-1.6 10 0v22c-3-1.6-7-1.6-10 0V8z" fill="#F8F4EA" stroke="#123249" stroke-width="1.6" stroke-linejoin="round" />
                <path d="M34 8c-3-1.6-7-1.6-10 0v22c3-1.6 7-1.6 10 0V8z" fill="#F8F4EA" stroke="#123249" stroke-width="1.6" stroke-linejoin="round" />
                <path d="M20 8v22" stroke="#123249" stroke-width="1.4" />
                <rect x="25" y="6" width="5" height="11" fill="#E0673B" />
              </svg>
            </span>
            <span class="menu-label-box"><span>Regulasi</span></span>
          </a>
          <a class="menu-card" href="<?php echo base_url('analisa-kerusakan'); ?>">
            <span class="menu-icon-box">
              <svg width="40" height="40" viewBox="0 0 40 40" aria-hidden="true">
                <rect x="5" y="12" width="18" height="22" fill="#F8F4EA" stroke="#123249" stroke-width="1.6" />
                <path d="M10 12l4 7-4 4 4 9" stroke="#123249" stroke-width="1.3" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                <circle cx="27" cy="15" r="6" fill="none" stroke="#F0A048" stroke-width="2.4" />
                <path d="M31.2 19.2l4.3 4.3" stroke="#F0A048" stroke-width="2.6" stroke-linecap="round" />
              </svg>
            </span>
            <span class="menu-label-box"><span>Analisa Kerusakan</span></span>
          </a>
          <a class="menu-card" href="<?php echo base_url('pbg'); ?>">
            <span class="menu-icon-box">
              <svg width="40" height="40" viewBox="0 0 40 40" aria-hidden="true">
                <rect x="8" y="4" width="20" height="28" rx="2" fill="#F8F4EA" stroke="#123249" stroke-width="1.6" />
                <path d="M12 11h12M12 16h12M12 21h8" stroke="#123249" stroke-width="1.4" stroke-linecap="round" />
                <circle cx="28" cy="28" r="7.5" fill="#E0673B" stroke="#123249" stroke-width="1.4" />
                <path d="M24.3 28l2.4 2.4 4.6-5" stroke="#F8F4EA" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </span>
            <span class="menu-label-box"><span>PBG</span></span>
          </a>
          <a class="menu-card" href="<?php echo base_url('slf'); ?>">
            <span class="menu-icon-box">
              <svg width="40" height="40" viewBox="0 0 40 40" aria-hidden="true">
                <circle cx="20" cy="14" r="10" fill="#F0A048" stroke="#123249" stroke-width="1.6" />
                <path d="M14 22.5l-3.2 11.5 9.2-5 9.2 5-3.2-11.5" fill="#F8F4EA" stroke="#123249" stroke-width="1.6" stroke-linejoin="round" />
                <path d="M14.5 14l3.5 3.5 7-7.5" stroke="#F8F4EA" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </span>
            <span class="menu-label-box"><span>SLF</span></span>
          </a>
          <a class="menu-card" href="<?php echo base_url('cagar-budaya'); ?>">
            <span class="menu-icon-box">
              <svg width="40" height="40" viewBox="0 0 40 40" aria-hidden="true">
                <path d="M4 14L20 5l16 9H4z" fill="#F0A048" stroke="#123249" stroke-width="1.6" stroke-linejoin="round" />
                <rect x="7" y="16" width="3.5" height="14" fill="#F8F4EA" stroke="#123249" stroke-width="1.3" />
                <rect x="14.3" y="16" width="3.5" height="14" fill="#F8F4EA" stroke="#123249" stroke-width="1.3" />
                <rect x="22.2" y="16" width="3.5" height="14" fill="#F8F4EA" stroke="#123249" stroke-width="1.3" />
                <rect x="29.5" y="16" width="3.5" height="14" fill="#F8F4EA" stroke="#123249" stroke-width="1.3" />
                <rect x="4" y="31" width="32" height="4" fill="#E0673B" stroke="#123249" stroke-width="1.3" />
              </svg>
            </span>
            <span class="menu-label-box"><span>Cagar Budaya</span></span>
          </a>
          <a class="menu-card" href="<?php echo base_url('saran-masukan'); ?>">
            <span class="menu-icon-box">
              <svg width="40" height="40" viewBox="0 0 40 40" aria-hidden="true">
                <path d="M5 8a3 3 0 013-3h24a3 3 0 013 3v14a3 3 0 01-3 3H17l-8 7v-7H8a3 3 0 01-3-3V8z" fill="#F8F4EA" stroke="#123249" stroke-width="1.6" stroke-linejoin="round" />
                <path d="M13.5 18.5l1-4 8.5-8.5 3 3-8.5 8.5-4 1z" fill="#F0A048" stroke="#123249" stroke-width="1.3" stroke-linejoin="round" />
              </svg>
            </span>
            <span class="menu-label-box"><span>Saran dan Masukan</span></span>
          </a>
        </div>
      </div>
    </section>

    <footer class="footer-simple">
      <div class="wrap">
        <p>Copyright (c) 2026 Dinas Pekerjaan Umum dan Penataan Ruang Kabupaten Cilacap. All rights reserved.</p>
      </div>
    </footer>

    <button class="theme-fab" id="themeFab" aria-expanded="false" aria-controls="themePanel" title="Pilih warna latar">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M12 3a9 9 0 100 18c1.2 0 2-.9 2-2 0-.5-.2-1-.5-1.4-.3-.4-.5-.8-.5-1.3 0-1.1.9-2 2-2h2.3A4.7 4.7 0 0021 9.7C20.4 5.9 16.6 3 12 3z" stroke="currentColor" stroke-width="1.4" />
        <circle cx="7.5" cy="11" r="1.2" fill="currentColor" />
        <circle cx="10.5" cy="7.5" r="1.2" fill="currentColor" />
        <circle cx="15" cy="7.5" r="1.2" fill="currentColor" />
      </svg>
    </button>
    <div class="theme-panel" id="themePanel" role="dialog" aria-label="Pilih warna latar">
      <h5>Warna Latar</h5>
      <div class="swatches">
        <button class="swatch sel" data-theme="light"><i class="sw-light"></i><span>Terang</span></button>
        <button class="swatch" data-theme="dark"><i class="sw-dark"></i><span>Gelap</span></button>
      </div>
    </div>

    <script>
      // ===== TEMA (tanpa penyimpanan browser: dibawa lewat parameter URL antar halaman) =====
      (function () {
        var p = new URLSearchParams(location.search);
        var t = p.get('theme') === 'dark' ? 'dark' : 'light';
        applyTheme(t, false);

        function applyTheme(theme, rewrite) {
          document.documentElement.setAttribute('data-theme', theme);
          document.querySelectorAll('.swatch').forEach(function (s) {
            s.classList.toggle('sel', s.dataset.theme === theme);
          });
          // sisipkan tema ke seluruh tautan internal agar pilihan terbawa antar halaman
          document.querySelectorAll('a[href]').forEach(function (a) {
            var h = a.getAttribute('href');
            if (!h || /^(https?:|mailto:|#)/.test(h)) return;
            var parts = h.split('#'); var base = parts[0].split('?')[0];
            a.setAttribute('href', base + '?theme=' + theme + (parts[1] ? '#' + parts[1] : ''));
          });
        }
        window.__applyTheme = applyTheme;
      })();

      // ===== Panel warna =====
      var fab = document.getElementById('themeFab'), panel = document.getElementById('themePanel');
      fab.addEventListener('click', function () {
        var open = panel.classList.toggle('open');
        fab.setAttribute('aria-expanded', open);
      });
      document.querySelectorAll('.swatch').forEach(function (s) {
        s.addEventListener('click', function () {window.__applyTheme(s.dataset.theme, true);});
      });
      document.addEventListener('click', function (e) {
        if (!panel.contains(e.target) && e.target !== fab && !fab.contains(e.target)) panel.classList.remove('open');
      });
      var userBtn = document.getElementById('userMenuBtn'), userPanel = document.getElementById('userMenuPanel');
      if (userBtn) {
        userBtn.addEventListener('click', function () {
          var open = userPanel.classList.toggle('open');
          userBtn.setAttribute('aria-expanded', open);
        });
        document.addEventListener('click', function (e) {
          if (!userPanel.contains(e.target) && e.target !== userBtn && !userBtn.contains(e.target)) userPanel.classList.remove('open');
        });
      }

      // ===== Topbar saat scroll =====
      var bar = document.getElementById('topbar');
      addEventListener('scroll', function () {bar.classList.toggle('scrolled', scrollY > 40)}, {passive: true});

      // ===== Animasi muncul =====
      var io = new IntersectionObserver(function (es) {
        es.forEach(function (e) {
          if (e.isIntersecting) {e.target.classList.add('in'); io.unobserve(e.target)}
        })
      }, {threshold: .12});
      document.querySelectorAll('.reveal').forEach(function (el) {io.observe(el)});
    </script>
  </body>

</html>
