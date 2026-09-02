<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

/*
| Meneruskan segmen layanan (mis. konsultasi/pbg, konsultasi/slf) sebagai
| argumen ke Konsultasi::index() alih-alih ditafsirkan sebagai nama method.
*/
$route['konsultasi/(:any)'] = 'konsultasi/index/$1';

/* Detail bangunan publik: /bangunan/720 -> Bangunan::index(720) */
$route['bangunan/(:num)'] = 'bangunan/index/$1';

/* Modul PBG seragam dengan SI DAI TERBANG; input dan kendali tahap oleh PU. */
$route['pengajuan-pbg'] = 'pbg_pu/index';
$route['pengajuan-pbg/tambah'] = 'pbg_pu/tambah';
$route['pengajuan-pbg/edit/(:num)'] = 'pbg_pu/edit/$1';
$route['pengajuan-pbg/tahap/(:num)'] = 'pbg_pu/tahap/$1';
$route['pengajuan-pbg/tahap/(:num)/(:num)'] = 'pbg_pu/tahap/$1/$2';
$route['pengajuan-pbg/ubah-tahap/(:num)'] = 'pbg_pu/ubah_tahap/$1';
$route['pengajuan-pbg/hapus/(:num)'] = 'pbg_pu/hapus/$1';
