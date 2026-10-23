<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Membantu pindah dari CodeIgniter 3 ke CodeIgniter 4 di server yang sama:
 *   - membuat .env (kredensial database) dari application/config/database.php lama;
 *   - menyalin application/config/gdrive.php lama ke app/Config/legacy/gdrive.php
 *     (konstanta APPPATH diganti LEGACY_STORAGE agar path kredensial Drive tetap benar).
 *
 * Pemakaian:
 *   php spark migrasi:konfigurasi                 (membuat berkas bila belum ada)
 *   php spark migrasi:konfigurasi --dry-run       (hanya menampilkan rencana, tanpa menulis)
 *   php spark migrasi:konfigurasi --lingkungan development
 *   php spark migrasi:konfigurasi --force         (timpa .env / gdrive yang sudah ada)
 */
class MigrasiKonfigurasi extends BaseCommand
{
    protected $group       = 'Migrasi';
    protected $name        = 'migrasi:konfigurasi';
    protected $description = 'Buat .env & konfigurasi Drive CI4 dari konfigurasi CodeIgniter 3 lama.';
    protected $usage       = 'migrasi:konfigurasi [--dry-run] [--force] [--lingkungan production|development]';
    protected $options     = [
        '--dry-run' => 'Tampilkan rencana tanpa menulis berkas.',
        '--force'   => 'Timpa berkas tujuan yang sudah ada.',
        '--lingkungan' => 'Nilai CI_ENVIRONMENT di .env (bawaan: production).',
    ];

    public function run(array $params)
    {
        $kering = array_key_exists('dry-run', $params) || CLI::getOption('dry-run') !== null;
        $paksa  = array_key_exists('force', $params) || CLI::getOption('force') !== null;
        $env    = $params['lingkungan'] ?? CLI::getOption('lingkungan') ?? 'production';
        if (! in_array($env, ['production', 'development', 'testing'], true)) {
            CLI::error('Nilai --lingkungan tidak valid.');

            return EXIT_ERROR;
        }

        $dbLama  = ROOTPATH . 'application/config/database.php';
        $gdLama  = ROOTPATH . 'application/config/gdrive.php';
        $envBaru = ROOTPATH . '.env';
        $gdBaru  = APPPATH . 'Config/legacy/gdrive.php';

        // ---- .env dari database.php lama
        if (is_file($envBaru) && ! $paksa) {
            CLI::write('.env sudah ada — dilewati (pakai --force untuk menimpa).', 'yellow');
        } elseif (! is_file($dbLama)) {
            CLI::write('application/config/database.php tidak ditemukan — .env tidak dibuat. Salin .env.example ke .env lalu isi manual.', 'yellow');
        } else {
            $db = $this->bacaDbLama($dbLama);
            if ($db === null) {
                CLI::error('Gagal membaca konfigurasi database lama.');

                return EXIT_ERROR;
            }
            $isi = "# Dibuat oleh `php spark migrasi:konfigurasi`. JANGAN di-commit (sudah ada di .gitignore).\n"
                . "CI_ENVIRONMENT = {$env}\n\n"
                . 'database.default.hostname = ' . $this->nilai($db['hostname'] ?? 'localhost') . "\n"
                . 'database.default.database = ' . $this->nilai($db['database'] ?? '') . "\n"
                . 'database.default.username = ' . $this->nilai($db['username'] ?? '') . "\n"
                . 'database.default.password = ' . $this->nilai($db['password'] ?? '') . "\n"
                . "database.default.DBDriver = MySQLi\n"
                . 'database.default.port = ' . (int) ($db['port'] ?? 3306) . "\n";
            CLI::write(($kering ? '[uji] ' : '') . '.env ← kredensial database lama (host ' . ($db['hostname'] ?? '?') . ', db ' . ($db['database'] ?? '?') . ', CI_ENVIRONMENT=' . $env . ')', 'green');
            if (! $kering) {
                file_put_contents($envBaru, $isi);
                @chmod($envBaru, 0640);
            }
        }

        // ---- gdrive.php lama
        if (! is_file($gdLama)) {
            CLI::write('application/config/gdrive.php tidak ada — Google Drive tetap nonaktif (normal bila belum dipakai).', 'yellow');
        } elseif (is_file($gdBaru) && ! $paksa) {
            CLI::write('app/Config/legacy/gdrive.php sudah ada — dilewati (pakai --force untuk menimpa).', 'yellow');
        } else {
            $kode = str_replace('APPPATH', 'LEGACY_STORAGE', (string) file_get_contents($gdLama));
            CLI::write(($kering ? '[uji] ' : '') . 'app/Config/legacy/gdrive.php ← application/config/gdrive.php (APPPATH → LEGACY_STORAGE)', 'green');
            if (! $kering) {
                if (! is_dir(dirname($gdBaru))) {
                    mkdir(dirname($gdBaru), 0755, true);
                }
                file_put_contents($gdBaru, $kode);
                @chmod($gdBaru, 0640);
            }
        }

        // ---- folder penulisan CI4
        foreach (['session', 'logs', 'cache', 'uploads'] as $sub) {
            $dir = WRITEPATH . $sub;
            if (! is_dir($dir) && ! $kering) {
                mkdir($dir, 0755, true);
            }
            CLI::write(($kering ? '[uji] ' : '') . 'writable/' . $sub . ': ' . (is_dir($dir) ? (is_writable($dir) ? 'siap' : 'TIDAK dapat ditulis — ubah izin folder') : 'akan dibuat'), is_dir($dir) && is_writable($dir) ? 'green' : 'yellow');
        }

        CLI::newLine();
        CLI::write('Selesai. Langkah berikutnya: lihat docs/migrasi-ci4.md (daftar pemeriksaan setelah cutover).');

        return EXIT_SUCCESS;
    }

    /** Muat $db['default'] dari berkas database.php CI3 tanpa menjalankan CodeIgniter 3. */
    private function bacaDbLama(string $path): ?array
    {
        if (! defined('ENVIRONMENT_LAMA')) {
            define('ENVIRONMENT_LAMA', 'production');
        }
        $db = [];
        $kode = (string) file_get_contents($path);
        // berkas CI3 memeriksa BASEPATH dan membaca ENVIRONMENT; BASEPATH sudah didefinisikan CI4 (Constants), ENVIRONMENT juga.
        try {
            (static function () use ($kode, &$db): void {
                $active_group = 'default';
                $query_builder = true;
                eval('?>' . $kode);
                $GLOBALS['__db_lama'] = $db ?? [];
            })();
        } catch (\Throwable $e) {
            return null;
        }
        $semua = $GLOBALS['__db_lama'] ?? [];
        unset($GLOBALS['__db_lama']);

        return $semua['default'] ?? null;
    }

    private function nilai($v): string
    {
        $v = (string) $v;
        if ($v === '') {
            return '';
        }
        if (strpos($v, '"') === false) {
            return '"' . addcslashes($v, '\\') . '"';
        }

        return "'" . $v . "'";
    }
}
