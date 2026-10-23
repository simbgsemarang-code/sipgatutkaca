# Migrasi SIP Gatutkaca: CodeIgniter 3 → CodeIgniter 4

Cabang `ci4-migrasi` memindahkan aplikasi dari **CodeIgniter 3.1.13** ke **CodeIgniter 4.6.x**
(PHP 8.1 – 8.4, disarankan **8.3**). URL, tampilan, database, dan berkas unggahan **tidak berubah**.

## 1. Apa yang berubah dan apa yang tetap

| | Sebelum (CI3) | Sesudah (CI4) |
|---|---|---|
| Kerangka | `system/` + `application/` | `vendor/codeigniter4/framework` + `app/` |
| Pintu masuk | `index.php` di root | `index.php` di root (tata letak sama, `assets/` tidak pindah) |
| Konfigurasi database | `application/config/database.php` | `.env` (tidak di-commit) |
| Konfigurasi Google Drive | `application/config/gdrive.php` | `app/Config/legacy/gdrive.php` (tidak di-commit) |
| Berkas runtime | `application/logs`, `application/cache` | `writable/logs`, `writable/cache`, `writable/session` |
| Unggahan PBG/SLF/ITR, kredensial Drive | `application/uploads`, `application/gdrive` | **tetap di sana** (konstanta `LEGACY_STORAGE`) |
| Foto & unggahan PU | `assets/uploads`, `assets/foto-bangunan` | tetap |

**Tetap sama:** seluruh URL, tampilan halaman (view disalin apa adanya), isi database, peran & alur
(PU, TPA, pemohon, admin), serta pesan-pesan yang dilihat pengguna.

### Lapisan kompatibilitas

Kode aplikasi (27 controller, 81 view) dipindahkan **tanpa menulis ulang logikanya**. Gaya CI3
(`$this->db->where(...)->get('tabel')->row_array()`, `$this->input->post()`, `$this->session`,
`$this->load->view()`, `$this->upload`, `form_validation`, ...) dijalankan oleh kelas-kelas di
`app/Libraries/Legacy/` di atas komponen CI4. `app/Controllers/LegacyController.php` adalah induk
semua controller, dan `app/Common.php` menyediakan fungsi global gaya CI3 (`get_instance`,
`show_404`, `show_error`, `ci_redirect`, `force_download`, ...).

Konsekuensinya aplikasi berjalan di CI4 yang masih didukung, sementara penulisan ulang ke gaya
CI4 murni bisa dilakukan **bertahap per modul** tanpa memutus layanan.

## 2. Prasyarat server (WAJIB diperiksa sebelum cutover)

* **PHP 8.1 atau lebih baru** — disarankan 8.3. (Di cPanel: *Select PHP Version* / *MultiPHP Manager*.)
* Ekstensi PHP: **`intl` (baru, wajib untuk CI4)**, `mbstring`, `mysqli` (atau `mysqlnd`), `curl`,
  `openssl`, `fileinfo`, `gd`, `zip`, `json`. Aktifkan `intl` di *Select PHP Version → Extensions*.
* `mod_rewrite` / LiteSpeed seperti sebelumnya. Baris `php_value` di `.htaccess` (batas unggah 100 MB)
  tetap dipakai; bila setelah cutover muncul error 500 pindahkan nilainya ke *MultiPHP INI Editor*.

## 3. Langkah cutover di cPanel

1. **Backup**: ekspor database (phpMyAdmin) dan unduh folder situs (terutama `application/uploads`,
   `application/gdrive`, `application/config/database.php`, `application/config/gdrive.php`).
2. **Siapkan PHP**: pilih PHP 8.3 untuk domain dan aktifkan ekstensi `intl` (butir 2).
3. **Gabungkan cabang** `ci4-migrasi` ke `main` lalu *Update from Remote → Deploy HEAD Commit*
   (atau webhook `deploy-hook.php` seperti biasa). Folder `vendor/` sudah ikut di repositori, jadi
   **tidak perlu** menjalankan Composer di server.
4. **Buat `.env`** (Terminal cPanel, di folder situs):
   ```bash
   php spark migrasi:konfigurasi            # membuat .env + app/Config/legacy/gdrive.php dari konfigurasi lama
   ```
   Atau manual: salin `.env.example` → `.env` dan isi kredensial database. Opsi perintah:
   `--dry-run` (hanya menampilkan rencana), `--force` (timpa), `--lingkungan development`.
   `CI_ENVIRONMENT` harus **production** di server.
5. **Izin folder**: `writable/` dan isinya harus dapat ditulis oleh PHP (umumnya 755; 775 bila perlu).
6. **Uji** dengan daftar periksa di bagian 5.
7. **Bersih-bersih (opsional, setelah yakin)**: hapus sisa kode lama di server — `application/controllers`,
   `application/views`, `application/config/*.php` (setelah `.env` dibuat), `system/` — jangan hapus
   `application/uploads` dan `application/gdrive`. Semuanya sudah ditutup dari web oleh `.htaccess`.

> Semua pengguna harus **masuk ulang** setelah cutover (format sesi CI4 berbeda dengan CI3).

### Rollback

Jika ada masalah serius: kembalikan `main` ke commit sebelum penggabungan (*Deploy HEAD Commit* lagi),
kembalikan versi PHP ke sebelumnya bila diubah. Database tidak diubah oleh migrasi ini, sehingga
versi CI3 langsung berjalan kembali.

## 4. Perbedaan perilaku yang perlu diketahui

* Cookie sesi sekarang `HttpOnly` (lebih aman). Sesi lama tidak terbaca.
* Pengalihan setelah form POST memakai kode **303** (CI3: 302) — tidak terlihat oleh pengguna.
* Halaman 404/500 bergaya baru; di mode `production` detail galat tidak ditampilkan dan dicatat di
  `writable/logs/`. Di mode `development` galat tampil lengkap (jangan dipakai di server publik).
* Zona waktu aplikasi mengikuti zona waktu bawaan PHP (sama seperti CI3). Untuk memaksa WIB
  tambahkan `app.appTimezone = "Asia/Jakarta"` di `.env`.
* Log kini di `writable/logs/` (bukan `application/logs/`).

## 5. Daftar periksa pasca-cutover

* Beranda, Pola Ruang (peta + RDTR + legenda), Analisa Kerusakan, Cagar Budaya, ITR, Regulasi (unduh PDF).
* Masuk sebagai: admin, PU, TPA, pemohon; keluar; "lupa kata sandi" (email terkirim); daftar baru.
* PU: tambah permohonan → distribusi TPA → TPA review → kirim link perbaikan (AJAX) → berita acara & ubah tanggal.
* Admin: Kelola Pengguna, Sebaran Bangunan (+modal Massa: foto, poligon/marker, koordinat), Kelola Aturan (PDF), Cagar Budaya, Saran & FAQ, Pengaturan Drive/Login.
* ITR: ajukan → unggah berkas → tinjau admin → unggah hasil → unduh pemohon.
* Unggah berkas ≥ 10 MB (batas `php_value`) dan, bila dipakai, unggah ke Google Drive.
* `writable/logs/` tidak berisi galat berulang.

## 6. Untuk pengembang

* Controller baru: extends `App\Controllers\LegacyController` (gaya lama) atau `BaseController`
  (gaya CI4 murni). Rute kustom di `app/Config/Routes.php`; selebihnya auto-route
  `controller/metode/param` (tanda `-` pada URL = `_`).
* Konstruktor CI3 menjadi `__legacy_construct()` (dipanggil setelah request/response siap).
  Pengalihan: `ci_redirect('uri')` (menghentikan eksekusi seperti `redirect()` CI3).
* Skrip uji bandingan CI3↔CI4 yang dipakai saat migrasi: lihat riwayat commit cabang ini.
* Menjalankan lokal tanpa mengubah `php.ini`:
  `php -d extension=intl -S 127.0.0.1:8081 -t . router.php` (butuh `intl`; di XAMPP aktifkan
  `extension=intl` di `php.ini` bila ingin lewat Apache).
