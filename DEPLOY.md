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

## 2. Tambah DNS record di Cloudflare

Domain `sigaru.my.id` nameserver-nya diarahkan ke Cloudflare, jadi
**membuat subdomain di cPanel saja tidak cukup** — DNS publiknya juga
perlu ditambahkan manual di dashboard Cloudflare, baru situs bisa
diakses dari luar.

**Catatan:** jangan meniru record `cacah.sigaru.my.id` — record itu
bertipe **Tunnel** (Cloudflare Tunnel, `cacah-lhr`), tandanya `cacah`
disajikan dari server/mekanisme lain, bukan dari akun cPanel yang sama.
Aplikasi ini (CodeIgniter di cPanel) butuh record **A biasa** yang
menunjuk ke IP server hosting cPanel Anda.

1. Login ke **https://dash.cloudflare.com** → pilih domain `sigaru.my.id`
   → menu **DNS** → **Records**.
2. Cek IP server hosting: lihat record `sigaru.my.id` (root, tipe A) dan
   `ftp.sigaru.my.id` — keduanya menunjuk ke IP yang sama, itulah IP
   server cPanel Anda (di kasus ini: `103.16.198.177`).
3. Klik **+ Add record**, isi:
   - **Type**: `A`
   - **Name**: `sipgatutkaca`
   - **IPv4 address**: IP server dari langkah 2 (`103.16.198.177`)
   - **Proxy status**: **Proxied** (awan oranye)
   - **TTL**: Auto
4. **Save**.
5. Tunggu 1–2 menit, lalu buka lagi `http://sipgatutkaca.sigaru.my.id/`.
   `ERR_NAME_NOT_RESOLVED`/`DNS_PROBE_FINISHED_NXDOMAIN` seharusnya
   sudah hilang (boleh masih 403/404/halaman kosong — wajar, file belum
   diupload, itu Langkah 3).

Kalau dilewati padahal perlu, gejalanya: subdomain sudah dibuat di
cPanel tapi browser tidak bisa membuka `sipgatutkaca.sigaru.my.id` sama
sekali (DNS_PROBE_FINISHED_NXDOMAIN).

## 3. Upload kode ke document root subdomain

⚠️ **Perbaikan `base_url` dan panduan ini masih di branch
`claude/clone-ke-dalam-komputerku-zwz9iz`, belum digabung ke `main`.**
Kalau ambil dari `main`, akan dapat versi lama yang masih bug (asset
mengarah ke `/sipgatutkaca/` yang 404). Pastikan ambil dari branch
`claude/clone-ke-dalam-komputerku-zwz9iz` sampai branch ini digabung.

Repo ini juga **private**, jadi Download ZIP/clone butuh login/akses
GitHub yang sesuai.

### Cara A (disarankan): Download ZIP + Upload File Manager

Paling gampang untuk repo private, tidak perlu bikin token apa pun:

1. Buka `https://github.com/simbgsemarang-code/sipgatutkaca` (login
   dengan akun GitHub yang punya akses).
2. Klik dropdown branch (tulisan "main", kiri atas daftar file) → pilih
   **`claude/clone-ke-dalam-komputerku-zwz9iz`**.
3. Klik tombol hijau **`<> Code`** → **Download ZIP**.
4. cPanel → **File Manager** → masuk ke folder document root subdomain
   (`sipgatutkaca.sigaru.my.id`, dari Langkah 1).
5. Klik **Upload** → pilih file ZIP yang baru diunduh → tunggu selesai
   (repo ini lumayan besar, ada file data peta ~8MB, jadi upload bisa
   beberapa menit tergantung koneksi).
6. Kembali ke folder (Go Back/reload), klik kanan file ZIP-nya →
   **Extract**.
7. Hasil extract akan masuk ke dalam satu subfolder (nama polanya
   `sipgatutkaca-claude-clone-ke-dalam-komputerku-zwz9iz`). **Isi
   subfolder itu harus dipindah ke document root**, bukan foldernya
   sendiri: masuk ke subfolder tsb → pilih semua file/folder di
   dalamnya (Select All) → **Move** → ubah path tujuan jadi document
   root (hapus nama subfoldernya dari path, sisakan
   `.../sipgatutkaca.sigaru.my.id/`) → jalankan.
8. Setelah `index.php`, `application/`, `system/`, `assets/`,
   `.htaccess` dst sudah langsung di document root, hapus file ZIP dan
   subfolder kosong sisa extract untuk beres-beres.

### Cara B: cPanel Git Version Control

Lebih rapi untuk update di masa depan (tinggal klik "Pull" tiap ada
perubahan), tapi karena repo private perlu Personal Access Token
GitHub dulu (GitHub → Settings → Developer settings → Personal access
tokens → generate, scope `repo`).

- cPanel → **Git Version Control** → **Create**:
  - Clone URL: `https://github.com/simbgsemarang-code/sipgatutkaca.git`
  - Branch: `claude/clone-ke-dalam-komputerku-zwz9iz`
  - Repository Path: document root subdomain dari Langkah 1
- Saat diminta autentikasi, username = username GitHub, password =
  Personal Access Token (bukan password akun GitHub biasa).

### Cara C: FTP/SFTP

Kalau punya klien FTP (FileZilla dll) dan sudah punya salinan kode di
komputer sendiri (misalnya dari `git clone` branch yang benar), upload
semua isinya ke document root subdomain via kredensial FTP cPanel.

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

⚠️ **Kalau lewat editor File Manager, jangan edit sebagian baris saja
kalau ragu** — lebih aman **Select All → Delete → paste ulang** seluruh
isi file dengan template lengkap, lalu ganti `hostname`/`username`/
`password`/`database`-nya saja. Pernah kejadian `hostname` ikut
terhapus/rusak pas edit, hasilnya warning
`Trying to access array offset on value of type null` di
`mysqli_driver.php` (baris `if ($this->hostname[0] === '/')`) —
karena `hostname` jadi NULL alih-alih `'localhost'`.

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

## 9. Deploy otomatis (GitHub Actions) — ⚠️ saat ini NONAKTIF, belum jalan

**Status: sudah dicoba, gagal, dan trigger otomatisnya sengaja
dimatikan sementara** (lihat `.github/workflows/deploy.yml`, trigger
`push` di-comment). Baik FTPS maupun FTP polos gagal dengan error yang
sama:

```
Error: None of the available transfer strategies work.
Last error response was 'Error: Timeout when trying to open data connection to ***:xxxxx'.
```

Diagnosis: koneksi kontrol FTP (port 21) berhasil, tapi koneksi
**data** (dipakai untuk transfer file sungguhan, di port acak/passive
mode) di-timeout — ini gejala **firewall server memblokir port data
FTP** dari IP GitHub Actions. Sudah dicoba dengan protokol terenkripsi
(FTPS) maupun polos (FTP), keduanya gagal sama persis, jadi bukan soal
enkripsi.

Opsi untuk membereskan ini (belum dieksekusi, pilih salah satu lalu
aktifkan lagi trigger `push` di file workflow):
1. **Hubungi support hosting** — minta mereka membuka rentang port data
   FTP passive-mode untuk koneksi dari luar (paling "benar" secara
   arsitektur, tapi tergantung respons support).
2. **Pakai SFTP** (kalau hosting mendukung) — ganti `protocol: ftp` jadi
   `protocol: sftp` di workflow, dan `FTP_SERVER`/`FTP_USERNAME`/
   `FTP_PASSWORD` diisi kredensial SFTP (biasanya port 22, kadang pakai
   akun cPanel utama karena akun FTP sub-account jarang punya akses
   SFTP). SFTP tidak punya masalah port data seperti FTP karena cuma
   pakai satu koneksi terenkripsi.
3. **Ganti mekanisme ke pull-based**: pakai fitur **Git Version
   Control** cPanel (clone repo langsung di server), lalu trigger
   `git pull`-nya lewat cPanel API (UAPI) dari GitHub Actions — ini
   cuma butuh koneksi HTTPS keluar dari GitHub Actions ke port cPanel
   (yang sudah pasti terbuka, karena itu jalur Anda login ke cPanel),
   jadi tidak kena masalah firewall FTP sama sekali.

Sampai salah satu di atas dikerjakan, **update kode ke situs live masih
manual** — ulangi Langkah 3 (Cara A: Download ZIP + File Manager) tiap
ada perubahan. Setelah setup awal di bagian ini beres, langkah upload
manual itu tidak perlu diulang lagi — repo ini sudah punya workflow
`.github/workflows/deploy.yml` yang (nantinya) otomatis meng-upload
seluruh isi repo ke document root subdomain setiap kali ada push/merge
ke branch `main` (atau dipicu manual lewat tab **Actions** di GitHub →
pilih workflow-nya → **Run workflow**).

Dua hal ini perlu disiapkan sekali saja supaya workflow-nya jalan:

### 9a. Buat akun FTP khusus di cPanel (dibatasi ke folder subdomain saja)

cPanel → **FTP Accounts** → **Create FTP Account**:
- **Login**: bebas, mis. `deploy` (hasil akhirnya biasanya jadi
  `deploy@sipgatutkaca.sigaru.my.id` atau `namacpanel_deploy`,
  tergantung tema cPanel).
- **Password**: klik **Generate/Password Generator** lalu **simpan**
  password-nya (dipakai di langkah 9b, tidak ditampilkan lagi setelah
  ini).
- **Directory**: arahkan **persis** ke folder document root subdomain
  (`sipgatutkaca.sigaru.my.id`) — bukan `public_html` atau root akun.
  Ini penting supaya akun FTP ini cuma bisa akses folder itu saja, tidak
  bisa menyentuh `cacah` atau file lain di hosting (aman dipakai
  otomatis oleh GitHub).
- **Quota**: Unlimited (atau secukupnya).
- Klik **Create FTP Account**.
- Catat **FTP server/host**-nya juga — biasanya sama dengan domain utama
  (`sigaru.my.id`) atau ditampilkan di halaman yang sama/menu
  **FTP Accounts** bagian "Special FTP Accounts"/"Configure FTP Client".

### 9b. Simpan kredensial FTP sebagai GitHub Secrets

⚠️ Jangan kirim username/password FTP lewat chat ke siapa pun (termasuk
ke Claude) — masukkan langsung di form GitHub berikut:

1. Buka repo di github.com → **Settings** → **Secrets and variables** →
   **Actions**.
2. Klik **New repository secret**, buat 3 secret ini satu per satu:
   - `FTP_SERVER` → alamat server FTP dari Langkah 9a
   - `FTP_USERNAME` → username FTP lengkap dari Langkah 9a
   - `FTP_PASSWORD` → password FTP dari Langkah 9a

### Cara kerjanya setelah ini

- PR di-merge ke `main`, atau ada commit baru masuk ke `main` → tab
  **Actions** di GitHub otomatis menjalankan job "Deploy ke cPanel" →
  semua file (kecuali `.git`, `.github`, `application/config/database.php`,
  `application/logs/`, `application/cache/`, `_legacy_html_backup/` yang
  memang dikecualikan) ter-upload ke folder subdomain via FTPS.
- Progress & hasilnya (sukses/gagal) bisa dipantau di tab **Actions**
  pada commit terkait.
- File `database.php` di server **tidak akan pernah tertimpa/terhapus**
  oleh proses ini (memang dikecualikan), jadi kredensial database di
  server aman meski deploy berjalan berkali-kali.
