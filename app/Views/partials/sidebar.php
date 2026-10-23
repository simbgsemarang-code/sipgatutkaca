<?php
/*
 * Sidebar portal SATU-SATUNYA untuk semua halaman pengguna (admin, PU, TPA, pemohon),
 * supaya menu, ikon, dan urutannya selalu sama di setiap halaman peran yang sama.
 *
 * Parameter ($this->load->view('partials/sidebar', array(...))):
 *   role  : 'admin' | 'pu' | 'tpa' (juga tpa_arsitek/tpa_struktur/tpa_mep) | 'pemohon'
 *   aktif : kunci menu yang disorot (lihat daftar di bawah), boleh kosong
 *   kelas : kelas <aside>: 'dash-sidebar' (default) atau 'side' (modul PU)
 */
$role  = isset($role) ? (string) $role : 'admin';
if (strpos($role, 'tpa') === 0) $role = 'tpa';
if (!in_array($role, array('admin', 'pu', 'tpa', 'pemohon'), TRUE)) $role = 'pemohon';
$aktif = isset($aktif) ? (string) $aktif : '';
$kelas = isset($kelas) ? (string) $kelas : 'dash-sidebar';

$svg = function ($isi) { return '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">' . $isi . '</svg>'; };
$ikon = array(
    'dashboard' => $svg('<rect x="2" y="2" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="10" y="2" width="6" height="4" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="10" y="8" width="6" height="8" rx="1" stroke="currentColor" stroke-width="1.4"/><rect x="2" y="10" width="6" height="6" rx="1" stroke="currentColor" stroke-width="1.4"/>'),
    'pengguna'  => $svg('<circle cx="6.5" cy="5.5" r="2.6" stroke="currentColor" stroke-width="1.4"/><path d="M1.8 15c0-2.6 2.1-4.4 4.7-4.4S11.2 12.4 11.2 15" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><path d="M12.4 4.2a2.3 2.3 0 010 4.4M13.6 14.8c0-2.1-1-3.7-2.6-4.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>'),
    'pbg'       => $svg('<path d="M4 1.5h7L14.5 5v11a1 1 0 01-1 1h-9a1 1 0 01-1-1v-13a1 1 0 011-1z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M11 1.5V5h3.5" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M5.5 9.5h7M5.5 12h7M5.5 7h3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>'),
    'itr'       => $svg('<path d="M2 4.5l4.5-2 5 2 4.5-2v11l-4.5 2-5-2-4.5 2v-11z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M6.5 2.5v11M11.5 4.5v11" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>'),
    'bangunan'  => $svg('<path d="M9 1.5c-2.9 0-5 2.1-5 4.9 0 3.4 5 10.1 5 10.1s5-6.7 5-10.1c0-2.8-2.1-4.9-5-4.9z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><circle cx="9" cy="6.4" r="1.9" stroke="currentColor" stroke-width="1.4"/>'),
    'cagar'     => $svg('<path d="M9 1.4l2.1 4.4 4.8.7-3.5 3.4.8 4.8L9 12.4l-4.2 2.3.8-4.8L2.1 6.5l4.8-.7L9 1.4z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/>'),
    'aturan'    => $svg('<path d="M3 3.2A1.7 1.7 0 014.7 1.5H14v11H4.7A1.7 1.7 0 003 14.2V3.2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M3 14.2a1.7 1.7 0 001.7 1.8H14v-3.5M6.5 5h4.5M6.5 7.5h4.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>'),
    'saran'     => $svg('<path d="M2.5 3.5h13a1 1 0 011 1v7a1 1 0 01-1 1H7l-3.5 3v-3H2.5a1 1 0 01-1-1v-7a1 1 0 011-1z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M5 6.5h8M5 9h5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>'),
    'kunci'     => $svg('<rect x="3" y="8" width="12" height="8" rx="1.5" stroke="currentColor" stroke-width="1.4"/><path d="M5.5 8V5.5a3.5 3.5 0 017 0V8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><circle cx="9" cy="12" r="1.2" fill="currentColor"/>'),
    'logout'    => $svg('<path d="M7 2H3a1 1 0 00-1 1v12a1 1 0 001 1h4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M11 12.5L15 9l-4-3.5M15 9H6.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>'),
);

// kunci => array(label, url, ikon)
$menu = array(
    'admin' => array(
        'dashboard' => array('Dashboard', 'admin', 'dashboard'),
        'pengguna'  => array('Kelola Pengguna', 'admin/pengguna', 'pengguna'),
        'pbg'       => array('Pengajuan PBG', 'admin/pengajuan', 'pbg'),
        'itr'       => array('Pengajuan ITR', 'admin_itr', 'itr'),
        'bangunan'  => array('Sebaran Bangunan', 'admin/bangunan', 'bangunan'),
        'cagar'     => array('Kelola Cagar Budaya', 'admin/cagar-budaya', 'cagar'),
        'aturan'    => array('Kelola Aturan', 'admin/aturan', 'aturan'),
        'saran'     => array('Saran & FAQ', 'admin/saran', 'saran'),
        'login'     => array('Pengaturan Login', 'admin/pengaturan-login', 'kunci'),
    ),
    'pu' => array(
        'dashboard' => array('Dashboard', 'pu', 'dashboard'),
        'pbg'       => array('Pengajuan PBG', 'pengajuan-pbg', 'pbg'),
    ),
    'tpa' => array(
        'dashboard' => array('Dashboard', 'tpa', 'dashboard'),
        'pbg'       => array('Pengajuan PBG', 'tpa-pengajuan-pbg', 'pbg'),
    ),
    'pemohon' => array(
        'dashboard' => array('Dashboard', 'pemohon', 'dashboard'),
        'itr'       => array('Pengajuan ITR', 'pemohon/pengajuan_itr', 'itr'),
    ),
);
?>
<aside class="<?php echo htmlspecialchars($kelas, ENT_QUOTES, 'UTF-8'); ?>">
  <nav>
<?php foreach ($menu[$role] as $kunci => $m): ?>
    <a href="<?php echo base_url($m[1]); ?>"<?php echo $kunci === $aktif ? ' class="active"' : ''; ?>>
      <?php echo $ikon[$m[2]]; ?>
      <?php echo htmlspecialchars($m[0], ENT_QUOTES, 'UTF-8'); ?>
    </a>
<?php endforeach; ?>
  </nav>
  <nav>
    <a href="<?php echo base_url('login/keluar'); ?>" class="logout">
      <?php echo $ikon['logout']; ?>
      Logout
    </a>
  </nav>
</aside>
