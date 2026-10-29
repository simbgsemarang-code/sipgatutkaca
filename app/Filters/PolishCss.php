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

        // Pilihan tema (terang/gelap) disimpan di browser dan diterapkan di SEMUA halaman,
        // karena tiap halaman hanya membaca ?theme= dari URL (tautan sidebar tidak membawanya).
        if (strpos($output, 'sipTheme') === false) {
            $skrip  = self::skripTema();
            $output = preg_replace_callback('/<head[^>]*>/i', static fn ($m) => $m[0] . $skrip, $output, 1);
        }

        if (stripos($output, '<title>Beranda') === false) {
            $output = preg_replace('/<body(\s*)>/i', '<body$1 class="is-inner-page">', $output, 1);
        }

        $response->setBody($output);

        return $response;
    }

    /**
     * Skrip kecil di awal <head>: membaca pilihan tema dari localStorage (kunci sipTheme),
     * menerapkannya sebelum halaman digambar, mengembalikannya bila skrip halaman menimpanya
     * (mis. ke "light" karena tidak ada ?theme=), menyimpan pilihan saat tombol tema diklik,
     * dan menyamakan parameter ?theme= pada tautan internal. Tanpa localStorage (mode privat)
     * perilaku lama (?theme= di URL) tetap berjalan.
     */
    private static function skripTema(): string
    {
        return <<<'HTML'
<script>(function(){var K='sipTheme',d=document.documentElement,pref=null;
function ok(v){return v==='dark'||v==='light'?v:null}
try{pref=ok(localStorage.getItem(K))}catch(e){}
function put(v){pref=v;try{localStorage.setItem(K,v)}catch(e){}}
if(pref){d.setAttribute('data-theme',pref)}
try{new MutationObserver(function(){if(pref&&d.getAttribute('data-theme')!==pref)d.setAttribute('data-theme',pref)}).observe(d,{attributes:true,attributeFilter:['data-theme']})}catch(e){}
document.addEventListener('click',function(ev){var s=ev.target&&ev.target.closest&&ev.target.closest('.swatch[data-theme]');if(s&&ok(s.dataset.theme))put(s.dataset.theme)},true);
function sync(){if(!pref)return;d.setAttribute('data-theme',pref);
document.querySelectorAll('.swatch[data-theme]').forEach(function(s){s.classList.toggle('sel',s.dataset.theme===pref)});
document.querySelectorAll('a[href]').forEach(function(a){var h=a.getAttribute('href');if(!h||/^(https?:|mailto:|tel:|javascript:|#)/i.test(h)||h.indexOf('theme=')<0)return;
a.setAttribute('href',h.replace(/([?&]theme=)[a-z]*/i,'$1'+pref))})}
document.addEventListener('DOMContentLoaded',sync);window.addEventListener('load',sync);
window.sipTheme=function(){return pref}})();</script>
HTML;
    }
}