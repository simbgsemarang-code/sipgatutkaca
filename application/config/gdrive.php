<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Penyimpanan berkas di Google Drive (bukan disk server) - antisipasi
 * kalau nanti domain/hosting berpindah, berkas lama tidak ikut hilang.
 * Isi tiga nilai ini, semuanya bisa diganti kapan pun (mis. kalau
 * akun Google Drive-nya berganti) TANPA mengubah kode sama sekali:
 *
 * 1. gdrive_enabled          - set TRUE setelah dua hal di bawah siap.
 * 2. gdrive_credentials_path - path file JSON kunci service account
 *                              (taruh di dalam application/gdrive/,
 *                              folder ini sudah diblokir dari akses
 *                              publik lewat application/.htaccess).
 * 3. gdrive_folder_id        - ID folder Drive tujuan upload, diambil
 *                              dari URL folder itu di browser:
 *                              drive.google.com/drive/folders/INI_ID_NYA
 *
 * Lihat docs/google-drive-setup.md untuk langkah lengkap membuatnya.
 */
$config['gdrive_enabled']          = false;
$config['gdrive_credentials_path'] = APPPATH . 'gdrive/credentials.json';
$config['gdrive_folder_id']        = '';
