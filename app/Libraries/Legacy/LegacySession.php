<?php

namespace App\Libraries\Legacy;

/** $this->session gaya CI3 di atas Session CI4 (dimulai malas saat pertama dipakai). */
class LegacySession
{
    private function s()
    {
        return session();
    }

    public function userdata($key = null)
    {
        if ($key === null) {
            return $this->s()->get();
        }

        return $this->s()->get($key);
    }

    public function has_userdata($key): bool
    {
        return $this->s()->has($key);
    }

    public function set_userdata($key, $value = null): void
    {
        $this->s()->set($key, $value);
    }

    public function unset_userdata($key): void
    {
        $this->s()->remove($key);
    }

    public function flashdata($key = null)
    {
        return $key === null ? $this->s()->getFlashdata() : $this->s()->getFlashdata($key);
    }

    public function set_flashdata($key, $value = null): void
    {
        $this->s()->setFlashdata($key, $value);
    }

    public function keep_flashdata($key): void
    {
        $this->s()->keepFlashdata($key);
    }

    public function sess_destroy(): void
    {
        $this->s()->destroy();
    }

    public function sess_regenerate(bool $destroy = false): void
    {
        $this->s()->regenerate($destroy);
    }
}
