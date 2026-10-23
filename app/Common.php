<?php

/**
 * Fungsi global yang dimuat paling awal oleh CodeIgniter 4.
 * Berisi fungsi-fungsi gaya CI3 yang masih dipakai kode aplikasi (get_instance,
 * show_404, show_error, ci_redirect, force_download, ...) supaya migrasi dari CI3
 * tidak perlu menulis ulang setiap pemanggilan.
 */

use App\Libraries\Legacy\Legacy;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Exceptions\RedirectException;

if (! function_exists('get_instance')) {
    /** Controller yang sedang berjalan (objek dengan ->db, ->session, ->input, ->load, ...). */
    function &get_instance()
    {
        $i = Legacy::instance();

        return $i;
    }
}

if (! function_exists('show_404')) {
    function show_404($page = '', $log_error = true): void
    {
        throw PageNotFoundException::forPageNotFound();
    }
}

if (! function_exists('show_error')) {
    /** Halaman galat sederhana dengan kode status (seperti CI3). Mengakhiri request. */
    function show_error($message, $status_code = 500, $heading = 'An Error Was Encountered'): void
    {
        $pesan = is_array($message) ? implode('</p><p>', array_map('esc', $message)) : esc((string) $message);
        $html  = '<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
            . '<title>' . esc($heading) . '</title><style>body{background:#fff;margin:40px;font:13px/20px normal Helvetica,Arial,sans-serif;color:#4F5155}'
            . '#container{margin:10px;border:1px solid #D0D0D0;box-shadow:0 0 8px #D0D0D0}h1{color:#444;background:transparent;border-bottom:1px solid #D0D0D0;font-size:19px;font-weight:normal;margin:0 0 14px;padding:14px 15px 10px}'
            . 'p{margin:12px 15px}</style></head><body><div id="container"><h1>' . esc($heading) . '</h1><p>' . $pesan . '</p></div></body></html>';
        $res = service('response');
        $res->setStatusCode((int) $status_code)->setContentType('text/html')->setBody($html)->send();
        exit;
    }
}

if (! function_exists('ci_redirect')) {
    /** Pengganti redirect() CI3: mengalihkan lalu menghentikan eksekusi controller. */
    function ci_redirect($uri = '', $method = 'auto', $code = null): void
    {
        $uri = (string) $uri;
        $url = preg_match('#^(\w+:)?//#i', $uri) ? $uri : site_url($uri);

        throw new RedirectException(service('response')->redirect($url, $method === 'refresh' ? 'refresh' : 'auto', $code));
    }
}

if (! function_exists('set_status_header')) {
    function set_status_header($code = 200, $text = ''): void
    {
        service('response')->setStatusCode((int) $code);
    }
}

if (! function_exists('config_item')) {
    function config_item($item)
    {
        return get_instance()->config->item($item);
    }
}

if (! function_exists('read_file')) {
    function read_file($file)
    {
        return is_file($file) ? file_get_contents($file) : false;
    }
}

if (! function_exists('force_download')) {
    /** CI3 download helper: kirim data/berkas sebagai unduhan lalu hentikan eksekusi. */
    function force_download($filename = '', $data = '', $set_mime = false)
    {
        if ($filename === '' || $data === '') {
            return;
        }
        if ($data === null && is_file($filename)) {
            $data     = file_get_contents($filename);
            $filename = basename($filename);
        }
        $ext  = strtolower((string) pathinfo($filename, PATHINFO_EXTENSION));
        $mime = 'application/octet-stream';
        if ($set_mime === true && $ext !== '') {
            $mimes = \Config\Mimes::$mimes[$ext] ?? null;
            if ($mimes !== null) {
                $mime = is_array($mimes) ? $mimes[0] : $mimes;
            }
        }
        $res = service('response');
        $res->setStatusCode(200)
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', 'attachment; filename="' . str_replace('"', '', basename($filename)) . '"')
            ->setHeader('Content-Length', (string) strlen((string) $data))
            ->setHeader('Cache-Control', 'private, no-transform, no-store, must-revalidate')
            ->setBody((string) $data)
            ->send();
        exit;
    }
}

if (! function_exists('validation_errors')) {
    /** Pesan galat form validation (CI3): validation_errors('<p>', '</p>'). */
    function validation_errors($open = '<p>', $close = '</p>'): string
    {
        $fv = Legacy::formValidation();

        return $fv ? $fv->error_string($open, $close) : '';
    }
}

if (! function_exists('set_value')) {
    /** Nilai POST sebelumnya untuk mengisi ulang form (CI3), atau nilai bawaan. */
    function set_value($field = '', $default = '', $html_escape = true)
    {
        $v = $_POST[$field] ?? $default;
        $v = is_array($v) ? '' : (string) $v;

        return $html_escape ? htmlspecialchars($v, ENT_QUOTES, 'UTF-8') : $v;
    }
}
