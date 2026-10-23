<?php

namespace App\Libraries\Legacy;

/**
 * $this->config / config_item() gaya CI3. Berkas konfigurasi lama (mis. gdrive)
 * dimuat dari app/Config/legacy/<nama>.php yang mengisi array $config.
 */
class LegacyConfig
{
    private static array $items    = [];
    private static array $sections = [];

    public function load(string $file = '', bool $useSections = false, bool $failGracefully = false)
    {
        $path = APPPATH . 'Config/legacy/' . $file . '.php';
        if (! is_file($path)) {
            if ($failGracefully) {
                return false;
            }
            self::$sections[$file] = self::$sections[$file] ?? [];

            return true;   // CI3 melempar galat; di sini dibiarkan kosong agar fitur opsional (mis. Drive) otomatis nonaktif
        }
        $config = [];
        (static function () use ($path, &$config): void {
            include $path;
        })();
        if ($useSections) {
            self::$sections[$file] = $config;
        } else {
            self::$items = array_merge(self::$items, $config);
        }

        return true;
    }

    public function item(string $item, string $section = '')
    {
        if ($section !== '') {
            return self::$sections[$section][$item] ?? null;
        }
        if ($item === 'base_url') {
            return base_url();
        }

        return self::$items[$item] ?? null;
    }

    public function set_item(string $item, $value): void
    {
        self::$items[$item] = $value;
    }

    public function base_url(string $uri = ''): string
    {
        return base_url($uri);
    }

    public function site_url(string $uri = ''): string
    {
        return site_url($uri);
    }
}
