<?php

namespace App\Libraries\Legacy;

/**
 * Hasil query gaya CI3 (result_array, row_array, num_rows, ...) di atas hasil CI4.
 * Query tulis (INSERT/UPDATE) mengembalikan boolean di CI4; kasus itu ditangani
 * dengan mengembalikan hasil kosong supaya pemanggilan berantai tetap aman.
 */
class LegacyResult
{
    /** @var mixed */
    private $res;

    public function __construct($res)
    {
        $this->res = $res;
    }

    private function ok(): bool
    {
        return is_object($this->res);
    }

    public function result_array(): array
    {
        return $this->ok() ? $this->res->getResultArray() : [];
    }

    public function result(): array
    {
        return $this->ok() ? $this->res->getResult() : [];
    }

    public function result_object(): array
    {
        return $this->result();
    }

    public function row_array(int $n = 0): ?array
    {
        return $this->ok() ? $this->res->getRowArray($n) : null;
    }

    public function row(int $n = 0)
    {
        return $this->ok() ? $this->res->getRow($n) : null;
    }

    public function first_row(string $type = 'object')
    {
        return $type === 'array' ? $this->row_array(0) : $this->row(0);
    }

    public function num_rows(): int
    {
        return $this->ok() ? (int) $this->res->getNumRows() : 0;
    }

    public function num_fields(): int
    {
        return $this->ok() ? (int) $this->res->getFieldCount() : 0;
    }

    public function free_result(): void
    {
        if ($this->ok()) {
            $this->res->freeResult();
        }
    }

    /** Untuk penggunaan hasil mentah (mis. boolean sukses/gagal) bila perlu. */
    public function raw()
    {
        return $this->res;
    }
}
