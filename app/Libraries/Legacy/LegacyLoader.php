<?php

namespace App\Libraries\Legacy;

/**
 * $this->load gaya CI3: view(), library(), helper(), model(), database().
 * Variabel view di-cache lintas pemanggilan (perilaku CI3: data yang dikirim ke
 * satu view tetap terlihat oleh view/partial berikutnya dalam request yang sama).
 */
class LegacyLoader
{
    private object $ctrl;
    private array $vars = [];
    private int $depth  = 0;

    /** Helper CI3 yang fungsinya sudah disediakan di app/Common.php (bukan berkas helper CI4). */
    private const HELPER_BAWAAN = ['download', 'file', 'string', 'text', 'security', 'html', 'array', 'date'];

    public function __construct(object $controller)
    {
        $this->ctrl = $controller;
    }

    // ---------------------------------------------------------------- view
    public function view(string $name, array $data = [], bool $return = false)
    {
        $this->vars = array_merge($this->vars, $data);

        $v = new LegacyView(config('View'), APPPATH . 'Views/', service('locator'), CI_DEBUG, service('logger'));
        $v->load    = $this;
        $v->session = $this->ctrl->session;
        $v->input   = $this->ctrl->input;
        $v->db      = $this->ctrl->db;
        $v->config  = $this->ctrl->config;
        $v->output  = $this->ctrl->output;
        $this->depth++;
        try {
            $html = $v->setData($this->vars, 'raw')->render($name, ['saveData' => false]);
        } finally {
            $this->depth--;
        }

        if ($return) {
            return $html;
        }
        // Tulis ke buffer keluaran: di dalam view lain (partial) tampil di tempat, di level controller
        // ditangkap CI4 sebagai isi respons (urutan antar-pemanggilan dan echo tetap terjaga, seperti CI3).
        echo $html;

        return $this;
    }

    public function vars($vars, $val = ''): void
    {
        $this->vars = array_merge($this->vars, is_array($vars) ? $vars : [$vars => $val]);
    }

    // ---------------------------------------------------------------- library
    public function library($library, $params = null, ?string $alias = null)
    {
        if (is_array($library)) {
            foreach ($library as $l) {
                $this->library($l);
            }

            return $this;
        }
        $lib = strtolower((string) $library);
        $ctrl = $this->ctrl;

        switch ($lib) {
            case 'session':
            case 'database':
                return $this;
            case 'form_validation':
                $ctrl->form_validation = $ctrl->form_validation ?? new LegacyFormValidation($ctrl->input);
                Legacy::setFormValidation($ctrl->form_validation);

                return $this;
            case 'upload':
                $ctrl->upload = $ctrl->upload ?? new LegacyUpload();

                return $this;
            case 'email':
                $ctrl->email = $ctrl->email ?? new LegacyEmail();

                return $this;
            case 'pagination':
                $ctrl->pagination = $ctrl->pagination ?? new LegacyPagination();

                return $this;
        }

        $property = $alias ?: $lib;
        if (! isset($ctrl->{$property})) {
            $class = '\\App\\Libraries\\' . ucfirst($lib);
            if (! class_exists($class)) {
                throw new \RuntimeException('Library tidak ditemukan: ' . $library);
            }
            $ctrl->{$property} = $params === null ? new $class() : new $class($params);
        }

        return $this;
    }

    // ---------------------------------------------------------------- helper
    public function helper($helpers)
    {
        foreach ((array) $helpers as $h) {
            $h = strtolower((string) $h);
            if (in_array($h, self::HELPER_BAWAAN, true)) {
                continue;
            }
            helper($h);
        }

        return $this;
    }

    // ---------------------------------------------------------------- model
    public function model($model, ?string $alias = null)
    {
        $name  = is_array($model) ? reset($model) : $model;
        $class = '\\App\\Models\\' . $name;
        $prop  = $alias ?: strtolower($name);
        if (! isset($this->ctrl->{$prop})) {
            $this->ctrl->{$prop} = new $class();
        }

        return $this;
    }

    public function database($params = '', bool $return = false, $queryBuilder = null)
    {
        return $return ? $this->ctrl->db : $this;
    }
}
