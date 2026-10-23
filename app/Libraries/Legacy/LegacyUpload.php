<?php

namespace App\Libraries\Legacy;

use Config\Mimes;

/**
 * Subset CI_Upload (CI3): initialize(), do_upload(), data(), display_errors().
 * Aturan sama: ekstensi harus ada di allowed_types DAN tipe MIME hasil deteksi
 * isi berkas harus cocok dengan ekstensinya; max_size dalam KB; encrypt_name
 * menghasilkan nama acak.
 */
class LegacyUpload
{
    private array $cfg = [];
    private array $errors = [];
    private array $data = [];

    private const PESAN = [
        'userfile_not_set'      => 'Unable to find a post variable called %s.',
        'file_exceeds_limit'    => 'The uploaded file exceeds the maximum allowed size in your PHP configuration file.',
        'file_exceeds_form_limit' => 'The uploaded file exceeds the maximum size allowed by the submission form.',
        'file_partial'          => 'The file was only partially uploaded.',
        'no_file_selected'      => 'You did not select a file to upload.',
        'no_temp_directory'     => 'The temporary folder is missing.',
        'unable_to_write_file'  => 'The file could not be written to disk.',
        'stopped_by_extension'  => 'The file upload was stopped by extension.',
        'invalid_filetype'      => 'The filetype you are attempting to upload is not allowed.',
        'invalid_filesize'      => 'The file you are attempting to upload is larger than the permitted size.',
        'no_filepath'           => 'The upload path does not appear to be valid.',
        'not_writable'          => 'The upload destination folder does not appear to be writable.',
        'bad_filename'          => 'The file name you submitted already exists on the server.',
    ];

    public function initialize(array $config = [], bool $reset = true): self
    {
        $this->cfg    = $reset ? $config : array_merge($this->cfg, $config);
        $this->errors = [];
        $this->data   = [];

        return $this;
    }

    private function galat(string $kunci, ...$arg): bool
    {
        $teks = self::PESAN[$kunci] ?? $kunci;
        $this->errors[] = $arg ? sprintf($teks, ...$arg) : $teks;

        return false;
    }

    public function do_upload(string $field = 'userfile'): bool
    {
        $this->errors = [];
        $this->data   = [];

        if (! isset($_FILES[$field]) || ! is_array($_FILES[$field]) || is_array($_FILES[$field]['name'] ?? null)) {
            return $this->galat('userfile_not_set', $field);
        }
        $f = $_FILES[$field];

        switch ((int) $f['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
                return $this->galat('file_exceeds_limit');
            case UPLOAD_ERR_FORM_SIZE:
                return $this->galat('file_exceeds_form_limit');
            case UPLOAD_ERR_PARTIAL:
                return $this->galat('file_partial');
            case UPLOAD_ERR_NO_FILE:
                return $this->galat('no_file_selected');
            case UPLOAD_ERR_NO_TMP_DIR:
                return $this->galat('no_temp_directory');
            case UPLOAD_ERR_CANT_WRITE:
                return $this->galat('unable_to_write_file');
            case UPLOAD_ERR_EXTENSION:
                return $this->galat('stopped_by_extension');
            default:
                return $this->galat('no_file_selected');
        }

        $path = (string) ($this->cfg['upload_path'] ?? '');
        $path = $path === '' ? '' : rtrim(str_replace('\\', '/', $path), '/') . '/';
        if ($path === '' || ! is_dir($path)) {
            return $this->galat('no_filepath');
        }
        if (! is_writable($path)) {
            return $this->galat('not_writable');
        }

        $asli = (string) $f['name'];
        $ext  = strtolower((string) pathinfo($asli, PATHINFO_EXTENSION));

        // --- jenis berkas yang diizinkan
        $diizinkan = (string) ($this->cfg['allowed_types'] ?? '*');
        if ($diizinkan !== '*') {
            $daftar = array_filter(array_map('strtolower', explode('|', $diizinkan)));
            if ($ext === '' || ! in_array($ext, $daftar, true)) {
                return $this->galat('invalid_filetype');
            }
        }

        // --- ukuran (KB)
        $maks = (float) ($this->cfg['max_size'] ?? 0);
        $kb   = $f['size'] / 1024;
        if ($maks > 0 && $kb > $maks) {
            return $this->galat('invalid_filesize');
        }

        // --- MIME hasil deteksi isi berkas harus cocok dengan ekstensi
        $mime = $this->deteksiMime($f['tmp_name'], (string) ($f['type'] ?? ''));
        if ($diizinkan !== '*' && ! $this->mimeCocok($ext, $mime)) {
            return $this->galat('invalid_filetype');
        }

        // --- nama berkas
        $extDot = $ext !== '' ? '.' . $ext : '';
        if (! empty($this->cfg['encrypt_name'])) {
            $nama = md5(uniqid((string) mt_rand(), true)) . $extDot;
        } elseif (! empty($this->cfg['file_name'])) {
            $nama = (string) $this->cfg['file_name'];
        } else {
            $nama = preg_replace('/\s+/', '_', basename($asli));
            $nama = preg_replace('/[^A-Za-z0-9._\-]/', '', $nama);
            if ($nama === '' || $nama === $extDot) {
                $nama = 'berkas' . $extDot;
            }
        }
        if (empty($this->cfg['overwrite']) && empty($this->cfg['encrypt_name']) && is_file($path . $nama)) {
            $dasar = pathinfo($nama, PATHINFO_FILENAME);
            $i     = 1;
            do {
                $nama = $dasar . '_' . $i++ . $extDot;
            } while (is_file($path . $nama) && $i < 1000);
        }

        if (! @move_uploaded_file($f['tmp_name'], $path . $nama)) {
            // mode uji tanpa HTTP upload nyata: izinkan salin dari berkas sementara
            if (! (defined('LEGACY_UPLOAD_TEST') && @copy($f['tmp_name'], $path . $nama))) {
                return $this->galat('unable_to_write_file');
            }
        }

        $isGambar = strpos($mime, 'image/') === 0;
        $this->data = [
            'file_name'    => $nama,
            'file_type'    => $mime,
            'file_path'    => $path,
            'full_path'    => $path . $nama,
            'raw_name'     => pathinfo($nama, PATHINFO_FILENAME),
            'orig_name'    => $nama,
            'client_name'  => $asli,
            'file_ext'     => $extDot,
            'file_size'    => round($kb, 2),
            'is_image'     => $isGambar,
            'image_width'  => null,
            'image_height' => null,
            'image_type'   => '',
            'image_size_str' => '',
        ];
        if ($isGambar && ($dim = @getimagesize($path . $nama))) {
            $this->data['image_width']  = $dim[0];
            $this->data['image_height'] = $dim[1];
            $this->data['image_type']   = image_type_to_extension($dim[2], false);
            $this->data['image_size_str'] = $dim[3];
        }

        return true;
    }

    public function data(?string $index = null)
    {
        return $index === null ? $this->data : ($this->data[$index] ?? null);
    }

    public function display_errors(string $open = '<p>', string $close = '</p>'): string
    {
        return $this->errors === [] ? '' : $open . implode($close . $open, $this->errors) . $close;
    }

    // ---------------------------------------------------------------- bantu
    private function deteksiMime(string $tmp, string $dariKlien): string
    {
        if (function_exists('finfo_open') && ($fi = @finfo_open(FILEINFO_MIME_TYPE))) {
            $m = @finfo_file($fi, $tmp);
            @finfo_close($fi);
            if (is_string($m) && $m !== '') {
                return $m;
            }
        }
        if (function_exists('mime_content_type') && ($m = @mime_content_type($tmp))) {
            return $m;
        }

        return $dariKlien !== '' ? $dariKlien : 'application/octet-stream';
    }

    private function mimeCocok(string $ext, string $mime): bool
    {
        $tabel = Mimes::$mimes[$ext] ?? null;
        if ($tabel === null) {
            return false;
        }

        return in_array($mime, (array) $tabel, true);
    }
}
