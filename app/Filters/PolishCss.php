<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Menambahkan lapisan tampilan bersama ke respons HTML (pengganti MY_Output CI3):
 * site-polish.css di semua halaman, portal-polish.css di halaman portal (sidebar),
 * dan kelas is-inner-page pada <body> selain Beranda.
 */
class PolishCss implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        if (($final = \App\Libraries\Legacy\Legacy::finalOutput()) !== null) {
            $response->setBody($final);
        }

        $type = strtolower($response->getHeaderLine('Content-Type'));
        if ($type !== '' && strpos($type, 'text/html') === false) {
            return null;
        }
        $output = (string) $response->getBody();
        if ($output === '' || stripos($output, '</head>') === false) {
            return null;
        }

        $base = base_url();
        if (stripos($output, 'site-polish.css') === false) {
            $css  = FCPATH . 'assets/css/site-polish.css';
            $ver  = is_file($css) ? filemtime($css) : 1;
            $link = "\n<link rel=\"stylesheet\" href=\"" . htmlspecialchars($base . 'assets/css/site-polish.css?v=' . $ver, ENT_QUOTES, 'UTF-8') . "\">\n";
            $output = preg_replace('/<\/head>/i', $link . '</head>', $output, 1);
        }

        if (strpos($output, 'dash-sidebar') !== false || strpos($output, 'class="side"') !== false || strpos($output, 'class="sidebar"') !== false) {
            $css  = FCPATH . 'assets/css/portal-polish.css';
            $ver  = is_file($css) ? filemtime($css) : 1;
            $link = '<link rel="stylesheet" href="' . htmlspecialchars($base . 'assets/css/portal-polish.css?v=' . $ver, ENT_QUOTES, 'UTF-8') . '">';
            $output = preg_replace('/<\/head>/i', $link . '</head>', $output, 1);
        }

        if (stripos($output, '<title>Beranda') === false) {
            $output = preg_replace('/<body(\s*)>/i', '<body$1 class="is-inner-page">', $output, 1);
        }

        $response->setBody($output);

        return $response;
    }
}