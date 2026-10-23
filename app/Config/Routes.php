<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 *
 * Rute kustom hasil migrasi dari application/config/routes.php (CI3). Selain
 * rute di bawah, berlaku auto-route lama: /controller/metode/param (tanda '-'
 * pada URL dibaca sebagai '_', mis. admin/bangunan-massa/1 => Admin::bangunan_massa(1)).
 */
$routes->add('konsultasi/(:any)', 'Konsultasi::index/$1');
$routes->add('bangunan/(:num)', 'Bangunan::index/$1');

// Modul Pengajuan PBG (PU)
$routes->add('pengajuan-pbg', 'Pbg_pu::index');
$routes->add('pengajuan-pbg/tambah', 'Pbg_pu::tambah');
$routes->add('pengajuan-pbg/edit/(:num)', 'Pbg_pu::edit/$1');
$routes->add('pengajuan-pbg/upload-berkas/(:num)', 'Pbg_pu::dokumen/$1');
$routes->add('pengajuan-pbg/tahap/(:num)', 'Pbg_pu::tahap/$1');
$routes->add('pengajuan-pbg/tahap/(:num)/(:num)', 'Pbg_pu::tahap/$1/$2');
$routes->add('pengajuan-pbg/ubah-tahap/(:num)', 'Pbg_pu::ubah_tahap/$1');
$routes->add('pengajuan-pbg/konsultasi/(:num)', 'Pbg_pu::ajukan_konsultasi/$1');
$routes->add('pengajuan-pbg/ubah-tpa/(:num)', 'Pbg_pu::ubah_tpa/$1');
$routes->add('pengajuan-pbg/link-perbaikan/(:num)', 'Pbg_pu::kirim_link_perbaikan/$1');
$routes->add('pengajuan-pbg/hapus/(:num)', 'Pbg_pu::hapus/$1');

// Modul TPA
$routes->add('tpa-pengajuan-pbg', 'Pbg_tpa::index');
$routes->add('tpa-pengajuan-pbg/review/(:num)', 'Pbg_tpa::review/$1');

// Berita acara
$routes->add('pengajuan-pbg/berita-acara/(:num)', 'Berita_acara::pbg/$1');
$routes->add('pengajuan-pbg/berita-acara-distribusi/(:num)', 'Berita_acara::distribusi/$1');
$routes->add('pengajuan-pbg/berita-acara-tanggal/(:num)', 'Berita_acara::ubah_tanggal/$1');
