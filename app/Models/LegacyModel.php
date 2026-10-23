<?php

namespace App\Models;

use App\Libraries\Legacy\Legacy;

/**
 * Induk model gaya CI3: $this->db, $this->load, $this->session, ... diteruskan
 * ke controller yang sedang berjalan (seperti CI_Model::__get).
 */
#[\AllowDynamicProperties]
abstract class LegacyModel
{
    public function __get(string $name)
    {
        $ci = Legacy::instance();

        return $ci->{$name} ?? null;
    }
}
