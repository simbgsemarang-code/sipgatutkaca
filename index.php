<?php

use CodeIgniter\Boot;
use Config\Paths;

/*
 *---------------------------------------------------------------
 * FRONT CONTROLLER CodeIgniter 4 (tata letak root)
 *---------------------------------------------------------------
 * Berbeda dari kerangka standar (folder public/), index.php berada di root
 * proyek seperti pada versi CodeIgniter 3 sebelumnya, sehingga alamat
 * assets/, gis-data.js, formulir, dll. tidak berubah. Folder internal
 * (app, vendor, writable, ...) ditutup lewat .htaccess.
 */

$minPhpVersion = '8.1'; // Jika diubah, ubah juga `spark`.
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Versi PHP minimal %s. Versi saat ini: %s',
        $minPhpVersion,
        PHP_VERSION,
    );

    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;

    exit(1);
}

// Folder front controller (root proyek)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Pastikan direktori kerja = folder front controller
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

require FCPATH . 'app/Config/Paths.php';

$paths = new Paths();

require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));
