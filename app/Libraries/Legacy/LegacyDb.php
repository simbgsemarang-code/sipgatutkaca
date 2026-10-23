<?php

namespace App\Libraries\Legacy;

use CodeIgniter\Database\BaseConnection;

/**
 * Pembungkus gaya CodeIgniter 3 ($this->db->where(...)->get('tabel')->row_array())
 * di atas Query Builder CodeIgniter 4. Pemanggilan rantai dicatat dulu (CI3 tidak
 * perlu tahu nama tabel di awal rantai) lalu diputar ulang pada builder CI4 saat
 * metode penutup (get, update, delete, ...) dipanggil.
 */
#[\AllowDynamicProperties]
class LegacyDb
{
    private ?BaseConnection $conn = null;

    /** @var list<array{0:string,1:array}> */
    private array $queue = [];

    /** @var list<string> */
    private array $tables = [];

    /** Pemetaan nama CI3 (snake_case) -> nama builder CI4 untuk metode berantai. */
    private const CHAIN = [
        'select' => 'select', 'select_max' => 'selectMax', 'select_min' => 'selectMin',
        'select_avg' => 'selectAvg', 'select_sum' => 'selectSum', 'distinct' => 'distinct',
        'join' => 'join',
        'where' => 'where', 'or_where' => 'orWhere', 'where_in' => 'whereIn', 'or_where_in' => 'orWhereIn',
        'where_not_in' => 'whereNotIn', 'or_where_not_in' => 'orWhereNotIn',
        'like' => 'like', 'or_like' => 'orLike', 'not_like' => 'notLike', 'or_not_like' => 'orNotLike',
        'group_start' => 'groupStart', 'or_group_start' => 'orGroupStart', 'not_group_start' => 'notGroupStart',
        'or_not_group_start' => 'orNotGroupStart', 'group_end' => 'groupEnd',
        'group_by' => 'groupBy', 'having' => 'having', 'or_having' => 'orHaving',
        'order_by' => 'orderBy', 'limit' => 'limit', 'offset' => 'offset', 'set' => 'set',
    ];

    public function conn(): BaseConnection
    {
        return $this->conn ??= \Config\Database::connect();
    }

    // ---------------------------------------------------------------- rantai
    public function __call(string $name, array $args)
    {
        if (isset(self::CHAIN[$name])) {
            $this->queue[] = [self::CHAIN[$name], $args];

            return $this;
        }

        throw new \BadMethodCallException('LegacyDb: metode tidak didukung: ' . $name);
    }

    public function from($table): self
    {
        foreach ((array) $table as $t) {
            $this->tables[] = (string) $t;
        }

        return $this;
    }

    private function reset(): void
    {
        $this->queue  = [];
        $this->tables = [];
    }

    /** Bangun builder CI4 dari catatan rantai (tabel pertama = utama, sisanya via from tambahan). */
    private function builder(string $table = '')
    {
        $tables = $this->tables;
        if ($table !== '') {
            array_unshift($tables, $table);
        }
        if ($tables === []) {
            $this->reset();

            throw new \LogicException('LegacyDb: nama tabel belum ditentukan.');
        }
        $main = array_shift($tables);
        $b    = $this->conn()->table($main);
        foreach ($tables as $extra) {
            $b->from($extra);
        }
        foreach ($this->queue as [$method, $args]) {
            $b->{$method}(...$args);
        }
        $this->reset();

        return $b;
    }

    // ---------------------------------------------------------------- eksekusi
    public function get(string $table = '', ?int $limit = null, int $offset = 0): LegacyResult
    {
        $b = $this->builder($table);
        if ($limit !== null) {
            $b->limit($limit, $offset);
        }

        return new LegacyResult($b->get());
    }

    public function get_where(string $table, $where = null, ?int $limit = null, int $offset = 0): LegacyResult
    {
        if ($where !== null) {
            $this->queue[] = ['where', [$where]];
        }

        return $this->get($table, $limit, $offset);
    }

    public function count_all_results(string $table = '', bool $reset = true): int
    {
        return (int) $this->builder($table)->countAllResults($reset);
    }

    public function count_all(string $table): int
    {
        return (int) $this->conn()->table($table)->countAll();
    }

    public function insert(string $table, ?array $data = null)
    {
        $b = $this->builder($table);

        return $b->insert($data) !== false;
    }

    public function insert_batch(string $table, ?array $data = null)
    {
        return $this->builder($table)->insertBatch($data);
    }

    public function update(string $table = '', ?array $data = null, $where = null, ?int $limit = null)
    {
        $b = $this->builder($table);

        return $b->update($data, $where, $limit);
    }

    public function delete(string $table = '', $where = '', ?int $limit = null)
    {
        $b = $this->builder($table);

        return $b->delete($where === '' ? [] : $where, $limit);
    }

    public function replace(string $table, ?array $data = null)
    {
        return $this->conn()->table($table)->replace($data);
    }

    public function empty_table(string $table)
    {
        return $this->conn()->table($table)->emptyTable();
    }

    public function query(string $sql, $binds = false): LegacyResult
    {
        return new LegacyResult($this->conn()->query($sql, $binds === false ? null : $binds));
    }

    // ---------------------------------------------------------------- info koneksi
    public function insert_id()
    {
        return $this->conn()->insertID();
    }

    public function affected_rows(): int
    {
        return (int) $this->conn()->affectedRows();
    }

    public function last_query(): string
    {
        return (string) $this->conn()->getLastQuery();
    }

    public function table_exists(string $table): bool
    {
        return $this->conn()->tableExists($table);
    }

    public function field_exists(string $field, string $table): bool
    {
        return $this->conn()->fieldExists($field, $table);
    }

    public function list_fields(string $table): array
    {
        return $this->conn()->getFieldNames($table);
    }

    public function list_tables(): array
    {
        return $this->conn()->listTables();
    }

    public function escape($v)
    {
        return $this->conn()->escape($v);
    }

    public function escape_str($v, bool $like = false)
    {
        return $this->conn()->escapeString($v, $like);
    }

    public function escape_like_str($v)
    {
        return $this->conn()->escapeLikeString($v);
    }

    // ---------------------------------------------------------------- transaksi
    public function trans_start(bool $testMode = false)
    {
        $this->conn()->transStart($testMode);
    }

    public function trans_complete()
    {
        return $this->conn()->transComplete();
    }

    public function trans_status(): bool
    {
        return $this->conn()->transStatus();
    }

    public function trans_begin(bool $testMode = false)
    {
        return $this->conn()->transBegin($testMode);
    }

    public function trans_commit()
    {
        return $this->conn()->transCommit();
    }

    public function trans_rollback()
    {
        return $this->conn()->transRollback();
    }
}
