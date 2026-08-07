# Panduan Deploy ke cPanel (Subdomain)

Target: `https://sipgatutkaca.sigaru.my.id` (subdomain baru, sejajar dengan
`cacah.sigaru.my.id` yang sudah ada di akun cPanel yang sama).

Aplikasi ini adalah **CodeIgniter 3.1.13 (PHP)**. Kebutuhan server:
PHP 7.4–8.x dengan ekstensi `mysqli`, MySQL/MariaDB, Apache/LiteSpeed
dengan `mod_rewrite` (default aktif di hampir semua cPanel).

## 1. Buat subdomain di cPanel

### 1a. Login ke cPanel

URL login biasanya salah satu dari:
- `https://sigaru.my.id:2083` atau `https://sigaru.my.id/cpanel`
- `https://<hostname-server-dari-provider>:2083`
- Tombol "Login to cPanel" / "Kelola Hosting" di member area penyedia
  hosting Anda

Login pakai **username & password akun hosting** (bukan password email
atau WordPress). Kalau lupa, cek email "Informasi Akun Hosting" saat
pertama daftar, atau minta provider reset.

### 1b. Cari menu pembuatan subdomain

Ada dua kemungkinan tampilan tergantung tema cPanel yang dipakai
provider Anda. Cek dulu: buka menu **Domains**, kalau di situ langsung
ada tabel berisi `sigaru.my.id` dan `cacah.sigaru.my.id` dengan tombol
**Create A New Domain**, berarti Anda pakai **tampilan terpadu (baru)**
— ikuti bagian "Cara B". Kalau menu **Domains** hanya berisi ikon-ikon
(Addon Domains, Subdomains, Aliases terpisah), berarti **tampilan
klasik** — ikuti "Cara A".

**Cara A — tema klasik (ikon "Subdomains" terpisah):**
1. Di kotak pencarian cPanel (atas), ketik `subdomain` → klik ikon
   **Subdomains**.
2. Isi form "Create a Subdomain":
   - **Subdomain**: ketik `sipgatutkaca` saja — jangan tulis domainnya.
   - **Domain**: pilih `sigaru.my.id` dari dropdown.
   - cPanel otomatis menampilkan preview lengkap:
     `sipgatutkaca.sigaru.my.id`.
   - **Document Root**: biarkan nilai default yang otomatis terisi
     (biasanya `public_html/sipgatutkaca.sigaru.my.id`) — **catat path
     ini**, dipakai lagi di Langkah 3.
3. Klik **Create**.
4. Subdomain baru langsung muncul di tabel bawah form (lengkap dengan
   link, document root, tombol Manage/Remove) — tandanya berhasil dibuat.

**Cara B — tampilan terpadu "Domains" (cPanel versi baru):**
1. Buka menu **Domains** → klik tombol **Create A New Domain** (kanan
   atas).
2. Di field **Domain**, ketik langsung nama lengkapnya:
   `sipgatutkaca.sigaru.my.id`.
3. Biarkan opsi **Share Document Root** (kalau muncul) dalam keadaan
   **tidak dicentang**, supaya subdomain ini punya folder sendiri,
   terpisah dari `cacah`.
4. Cek field **Document Root** yang otomatis terisi (biasanya
   `/home/USERNAME/sipgatutkaca.sigaru.my.id`) — **catat path ini**.
5. Klik **Submit**.

### 1c. Samakan pola dengan `cacah` (opsional, disarankan)

Buka detail subdomain `cacah.sigaru.my.id` yang sudah ada (menu yang
sama), lihat pola Document Root-nya. Kalau formatnya misalnya
`public_html/cacah.sigaru.my.id`, maka default
`public_html/sipgatutkaca.sigaru.my.id` untuk subdomain baru sudah
otomatis konsisten — tidak perlu diubah manual.

### 1d. Verifikasi cepat

- Buka `http://sipgatutkaca.sigaru.my.id/` di browser (http dulu saja,
  SSL belum tentu aktif di tahap ini).
- **Wajar** kalau yang tampil halaman kosong, "Index of /", atau 404 —
  karena belum ada file diupload (itu Langkah 3 nanti). Yang penting
  domainnya sudah "kebuka", bukan error DNS.
- Kalau muncul **"This site can't be reached" / DNS_PROBE_FINISHED_NXDOMAIN**,
  itu biasanya isu Cloudflare (Langkah 2 di bawah), bukan salah di
  langkah ini — lanjut cek Langkah 2 dulu sebelum upload file.

**Penting untuk langkah selanjutnya:** isi `index.php`, `application/`,
`system/`, `assets/`, `.htaccess`, dst dari repo ini harus ada
**langsung** di document root yang dicatat di atas (bukan di dalam
subfolder tambahan seperti `.../sipgatutkaca-main/`).

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
