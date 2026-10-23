<?php

namespace App\Libraries\Legacy;

/**
 * Subset CI_Pagination (CI3) yang dipakai daftar pengguna: nomor halaman lewat
 * query string (page_query_string + use_page_numbers + reuse_query_string),
 * tautan awal/akhir/berikut/sebelum, dan tag pembungkus yang bisa diatur.
 */
class LegacyPagination
{
    private array $c = [];

    public function initialize(array $params = []): self
    {
        $this->c = $params + [
            'base_url' => '', 'total_rows' => 0, 'per_page' => 10, 'num_links' => 2,
            'use_page_numbers' => false, 'page_query_string' => false, 'query_string_segment' => 'per_page',
            'reuse_query_string' => false,
            'full_tag_open' => '', 'full_tag_close' => '',
            'first_link' => '&lsaquo; First', 'last_link' => 'Last &rsaquo;', 'next_link' => '&gt;', 'prev_link' => '&lt;',
            'first_tag_open' => '', 'first_tag_close' => '', 'last_tag_open' => '', 'last_tag_close' => '',
            'next_tag_open' => '', 'next_tag_close' => '', 'prev_tag_open' => '', 'prev_tag_close' => '',
            'cur_tag_open' => '<strong>', 'cur_tag_close' => '</strong>', 'num_tag_open' => '', 'num_tag_close' => '',
        ];

        return $this;
    }

    private function url(int $page): string
    {
        $c     = $this->c;
        $query = $c['reuse_query_string'] ? $_GET : [];
        unset($query[$c['query_string_segment']]);
        if ($page > 1) {
            $query[$c['query_string_segment']] = $page;
        }
        $base = (string) $c['base_url'];

        return $query === [] ? $base : $base . (strpos($base, '?') === false ? '?' : '&') . http_build_query($query);
    }

    private function a(int $page, string $teks, string $rel = ''): string
    {
        $relAttr = $rel !== '' ? ' rel="' . $rel . '"' : '';

        return '<a href="' . htmlspecialchars($this->url($page), ENT_QUOTES, 'UTF-8') . '" data-ci-pagination-page="' . $page . '"' . $relAttr . '>' . $teks . '</a>';
    }

    public function create_links(): string
    {
        $c     = $this->c;
        $total = (int) $c['total_rows'];
        $per   = max(1, (int) $c['per_page']);
        $pages = (int) ceil($total / $per);
        if ($total === 0 || $pages <= 1) {
            return '';
        }
        $seg  = $c['query_string_segment'];
        $cur  = max(1, min($pages, (int) ($_GET[$seg] ?? 1)));
        $n    = (int) $c['num_links'];
        $dari = max(1, $cur - $n);
        $ke   = min($pages, $cur + $n);

        $o = $c['full_tag_open'];
        if ($cur > $n + 1) {
            $o .= $c['first_tag_open'] . $this->a(1, $c['first_link']) . $c['first_tag_close'];
        }
        if ($cur > 1) {
            $o .= $c['prev_tag_open'] . $this->a($cur - 1, $c['prev_link'], 'prev') . $c['prev_tag_close'];
        }
        for ($i = $dari; $i <= $ke; $i++) {
            $o .= $i === $cur
                ? $c['cur_tag_open'] . $i . $c['cur_tag_close']
                : $c['num_tag_open'] . $this->a($i, (string) $i) . $c['num_tag_close'];
        }
        if ($cur < $pages) {
            $o .= $c['next_tag_open'] . $this->a($cur + 1, $c['next_link'], 'next') . $c['next_tag_close'];
        }
        if ($cur < $pages - $n) {
            $o .= $c['last_tag_open'] . $this->a($pages, $c['last_link']) . $c['last_tag_close'];
        }

        return $o . $c['full_tag_close'];
    }
}
