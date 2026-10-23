<?php

namespace App\Libraries\Legacy;

/**
 * Registri kecil: menyimpan controller yang sedang berjalan agar helper/library
 * lama yang memanggil get_instance() (gaya CI3) tetap mendapatkan $db, $session,
 * $input, $load, $config, dst.
 */
final class Legacy
{
    private static ?object $controller = null;
    private static ?object $formValidation = null;
    private static ?string $finalOutput = null;

    /** Isi respons yang ditetapkan lewat $this->output->set_output() (menggantikan semua keluaran lain). */
    public static function setFinalOutput(string $out): void
    {
        self::$finalOutput = $out;
    }

    public static function finalOutput(): ?string
    {
        return self::$finalOutput;
    }

    public static function setFormValidation(object $fv): void
    {
        self::$formValidation = $fv;
    }

    public static function formValidation(): ?object
    {
        return self::$formValidation;
    }

    public static function setController(object $c): void
    {
        self::$controller = $c;
    }

    public static function instance(): object
    {
        if (self::$controller === null) {
            throw new \RuntimeException('get_instance() dipanggil sebelum controller diinisialisasi.');
        }

        return self::$controller;
    }
}
