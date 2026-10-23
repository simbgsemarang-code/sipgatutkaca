<?php

namespace App\Libraries\Legacy;

/** $this->input gaya CI3 (post/get/method/is_ajax_request/...). */
class LegacyInput
{
    private array $post;
    private array $get;

    public function __construct()
    {
        $this->post = is_array($_POST ?? null) ? $_POST : [];
        $this->get  = is_array($_GET ?? null) ? $_GET : [];
    }

    /** CI3: tanpa argumen = seluruh array; kunci tidak ada = NULL. */
    public function post($key = null, $xss = null)
    {
        if ($key === null) {
            return $this->post;
        }
        if (is_array($key)) {
            $out = [];
            foreach ($key as $k) {
                $out[$k] = $this->post[$k] ?? null;
            }

            return $out;
        }

        return $this->post[$key] ?? null;
    }

    public function get($key = null, $xss = null)
    {
        if ($key === null) {
            return $this->get;
        }

        return $this->get[$key] ?? null;
    }

    public function post_get($key, $xss = null)
    {
        return $this->post[$key] ?? ($this->get[$key] ?? null);
    }

    public function get_post($key, $xss = null)
    {
        return $this->get[$key] ?? ($this->post[$key] ?? null);
    }

    /** Dipakai form validation untuk menulis balik nilai yang sudah di-trim. */
    public function set_post(string $key, $value): void
    {
        $this->post[$key] = $value;
        $_POST[$key]      = $value;
    }

    public function method(bool $upper = false): string
    {
        $m = (string) ($_SERVER['REQUEST_METHOD'] ?? 'GET');

        return $upper ? strtoupper($m) : strtolower($m);
    }

    public function is_ajax_request(): bool
    {
        return strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest';
    }

    public function server($key, $xss = null)
    {
        return $_SERVER[$key] ?? null;
    }

    public function cookie($key = null, $xss = null)
    {
        return $key === null ? $_COOKIE : ($_COOKIE[$key] ?? null);
    }

    public function ip_address(): string
    {
        return service('request')->getIPAddress();
    }

    public function get_request_header(string $name, $xss = null)
    {
        $h = service('request')->getHeaderLine($name);

        return $h === '' ? null : $h;
    }
}
