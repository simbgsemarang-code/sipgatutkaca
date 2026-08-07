# Panduan Deploy ke cPanel (Subdomain)

Target: `https://sipgatutkaca.sigaru.my.id` (subdomain baru, sejajar dengan
`cacah.sigaru.my.id` yang sudah ada di akun cPanel yang sama).

Aplikasi ini adalah **CodeIgniter 3.1.13 (PHP)**. Kebutuhan server:
PHP 7.4–8.x dengan ekstensi `mysqli`, MySQL/MariaDB, Apache/LiteSpeed
dengan `mod_rewrite` (default aktif di hampir semua cPanel).

## 1. Buat subdomain di cPanel

cPanel → **Domains** (atau **Subdomains** di cPanel versi lama) → buat
subdomain `sipgatutkaca` untuk domain `sigaru.my.id`.

- Document root yang disarankan cPanel biasanya
  `public_html/sipgatutkaca.sigaru.my.id` — biarkan default, atau catat
  path-nya karena dipakai di langkah 3.
- **Penting:** isi `index.php`, `application/`, `system/`, `assets/`,
  `.htaccess`, dst harus ada **langsung** di document root ini (bukan di
  dalam subfolder tambahan seperti `.../sipgatutkaca-main/`).

## 2. Cek DNS — kemungkinan lewat Cloudflare

Domain `sigaru.my.id` terdeteksi memakai IP Cloudflare (bukan IP hosting
langsung). Artinya nameserver kemungkinan besar diarahkan ke Cloudflare,
sehingga **membuat subdomain di cPanel saja mungkin belum cukup** —
DNS-nya juga perlu ditambahkan di dashboard Cloudflare:

- Login ke Cloudflare → pilih domain `sigaru.my.id` → tab **DNS**.
- Cek record untuk `cacah` (tipe A atau CNAME, target ke server hosting)
  → buat record baru serupa untuk `sipgatutkaca` dengan tipe & target
  yang sama (proxy status/"awan oranye" disamakan dengan punya `cacah`).
- Kalau ternyata cPanel Anda punya integrasi Cloudflare otomatis (plugin
  "Cloudflare" muncul di cPanel), langkah ini mungkin sudah otomatis —
  cek dulu sebelum menambah manual.

Kalau dilewati padahal perlu, gejalanya: subdomain sudah dibuat di
cPanel tapi browser tidak bisa membuka `sipgatutkaca.sigaru.my.id` sama
sekali (DNS_PROBE_FINISHED_NXDOMAIN).

## 3. Upload kode ke document root subdomain

Pilih salah satu:

**A. cPanel Git Version Control (paling rapi, cocok karena repo ini di GitHub)**
- cPanel → **Git Version Control** → Create → Clone URL:
  `https://github.com/simbgsemarang-code/sipgatutkaca.git`, branch
  `main`, Repository Path: document root subdomain dari langkah 1.
- Kalau repo private, perlu buat Personal Access Token GitHub dan
  pakai sebagai password saat clone.

**B. Upload ZIP lewat File Manager**
- Download ZIP dari GitHub (branch `main`), upload via **File Manager**
  ke document root subdomain, klik kanan → **Extract**.
- Pastikan hasil ekstrak tidak bersarang (isi ZIP langsung ke document
  root, bukan ke dalam folder `sipgatutkaca-main/`).

**C. FTP/SFTP**
- Pakai FileZilla/klien FTP dengan kredensial FTP cPanel, upload semua
  isi repo ke document root subdomain.

## 4. Buat database MySQL

cPanel → **MySQL® Databases**:
1. Buat database baru (nama otomatis berawalan `namacpanel_`, misalnya
   `namacpanel_gatutkaca`).
2. Buat MySQL user baru + password kuat.
3. Tambahkan user tsb ke database dengan **All Privileges**.

Lalu **phpMyAdmin** → pilih database yang baru dibuat → tab **Import**:
- Sebelum upload `database/gatutkaca.sql`, **hapus dulu 2 baris paling
  atas file itu** (`CREATE DATABASE IF NOT EXISTS ...` dan
  `USE \`gatutkaca\`;`). Di shared hosting, user database biasanya tidak
  boleh `CREATE DATABASE`/`USE` ke database lain — dua baris itu memang
  cuma buat kemudahan setup lokal (XAMPP/root), bukan untuk hosting.
  Karena di phpMyAdmin Anda sudah "masuk" ke database yang benar,
  sisa isi file akan otomatis kebuat di situ.

## 5. Konfigurasi aplikasi

Lewat File Manager (atau Terminal cPanel kalau tersedia):
1. Copy `application/config/database.php.example` →
   `application/config/database.php`.
2. Edit isinya sesuai kredensial dari langkah 4:
   ```php
   'hostname' => 'localhost',
   'username' => 'namacpanel_userdb',
   'password' => 'password-yang-dibuat',
   'database' => 'namacpanel_gatutkaca',
   ```
   (`database.php` sengaja di-`.gitignore`, jadi aman berisi kredensial
   asli dan tidak akan ikut ter-commit ke GitHub.)

`base_url` **tidak perlu diedit manual** — sudah dibuat auto-detect dari
host yang diakses (lihat `application/config/config.php`), jadi otomatis
benar begitu diakses lewat `https://sipgatutkaca.sigaru.my.id/`.

## 6. Permission folder

Set folder berikut writable oleh proses PHP (biasanya `755` sudah cukup
di cPanel karena PHP jalan sebagai user pemilik file; kalau masih error
"permission denied" di log, naikkan ke `775`):
- `application/logs/`
- `application/cache/`

## 7. SSL

cPanel → **SSL/TLS Status** → jalankan **AutoSSL** untuk subdomain baru
(gratis, Let's Encrypt). Kalau domain memang di-proxy Cloudflare, mode
SSL di Cloudflare (menu **SSL/TLS**) sebaiknya **Full** atau
**Full (strict)** — bukan "Flexible" — supaya jalur Cloudflare↔origin
juga terenkripsi, bukan cuma browser↔Cloudflare.

## 8. Verifikasi

- Buka `https://sipgatutkaca.sigaru.my.id/` → halaman beranda harus
  tampil dengan CSS/gambar termuat normal (bukti `base_url` sudah benar).
- Coba buka rute lain, mis. `/login`, `/konsultasi`, untuk pastikan
  `.htaccess`/routing CodeIgniter jalan (bukan 404).
- Kalau muncul halaman putih/500: cek **Errors** di cPanel (Metrics →
  Errors) atau `application/logs/log-*.php`. Untuk mempermudah debug di
  awal, `ENVIRONMENT` aplikasi ini default `development` (pesan error
  PHP tampil apa adanya) — setelah situs stabil, sebaiknya diamankan ke
  `production` dengan menambahkan `SetEnv CI_ENV production` di
  `.htaccess` root subdomain, supaya detail error tidak tampil ke publik.
