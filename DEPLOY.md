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

## 9. Deploy otomatis (GitHub Actions) — ⚠️ saat ini NONAKTIF, 3 percobaan gagal

**Status: belum ada satu pun mekanisme push-based yang berhasil.**
Tiga percobaan berbeda, tiga gejala berbeda, tapi mengarah ke satu
kesimpulan yang sama:

1. **FTP (FTPS & polos)** → `Timeout when trying to open data connection`.
   Koneksi kontrol (port 21) sukses, koneksi data (port acak passive
   mode) diam/timeout. Gejala: firewall men-drop paket ke port data
   dari IP luar.
2. **cPanel Git Version Control** → clone berhasil "katanya" tapi hasil
   folder-nya kosong. Kemungkinan soal autentikasi repo private yang
   tidak didukung form Create di cPanel host ini (form tidak punya
   kolom kredensial, dan menempel token di URL clone ditolak eksplisit
   oleh cPanel: "clone URL cannot include a password").
3. **SSH/rsync** → `Connection refused`, cepat (~0.3 detik). **Beda**
   dari FTP (yang diam/timeout) — ini penolakan aktif. Gejala: akses
   SSH dibatasi hanya untuk IP tertentu (allowlist), IP GitHub Actions
   (dinamis, dari rentang besar) tidak termasuk.

**Kesimpulan:** hosting ini kemungkinan besar sengaja membatasi semua
akses non-web (FTP, SSH) hanya dari IP yang dikenal/di-allowlist,
sementara port web (80/443) dibuka untuk umum — konsisten dengan situs
yang bisa diakses publik tapi tiap jalur deploy otomatis dari GitHub
Actions (IP-nya selalu berubah) mentok. Kalau ini benar, **tidak ada
protokol push-based yang akan berhasil** sampai admin hosting membuka
akses tsb — gantiprotokol tidak akan membantu.

**Langkah yang masuk akal berikutnya** (belum dieksekusi, pilih salah satu):
1. **Hubungi support hosting** — tanyakan eksplisit apakah SSH/FTP
   dibatasi ke IP tertentu, dan minta dibuka untuk IP dinamis GitHub
   Actions (daftar rentang IP resminya di
   `https://api.github.com/meta`, key `actions`) — atau alternatif:
   apakah ada cara lain (webhook, API) yang tidak kena batasan ini.
2. **Terima saja deploy manual** (Cara A, Langkah 3) sebagai cara
   tetap — situs sudah live dan berfungsi normal, cuma update kode ke
   depan perlu diulang manual tiap ada perubahan. Ini pilihan yang
   sepenuhnya wajar kalau frekuensi update tidak sering.

Detail setup SSH key (kalau nanti mau dicoba lagi setelah dikonfirmasi
IP-nya dibuka) tetap didokumentasikan di bawah ini:

### 9a. Buat SSH key khusus untuk deploy (dibatasi login-only, no passphrase)

Lewat **cPanel → Terminal** (atau SSH client kalau login manual):

```bash
mkdir -p ~/.ssh
chmod 700 ~/.ssh
ssh-keygen -t ed25519 -f ~/.ssh/github-deploy -N ""
cat ~/.ssh/github-deploy.pub >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
cat ~/.ssh/github-deploy
```

Baris terakhir menampilkan **private key** — copy semuanya (dari
`-----BEGIN OPENSSH PRIVATE KEY-----` sampai `-----END...-----`) untuk
dipakai di langkah 9b. Jangan kirim isinya ke siapa pun termasuk lewat
chat ke Claude.

Catatan: fingerprint hasil `ssh-keygen` biasanya menunjukkan hostname
server (format `user@hostname`) — ini bisa dipakai sebagai `SSH_HOST`
kalau IP server tidak ingin dipakai langsung.

### 9b. Simpan sebagai GitHub Secrets

Repo di github.com → **Settings** → **Secrets and variables** →
**Actions** → **New repository secret**, buat 4 secret:
- `SSH_HOST` → hostname/IP server
- `SSH_PORT` → port SSH (cek dengan
  `grep -i "^Port" /etc/ssh/sshd_config` di Terminal kalau ragu,
  default 22)
- `SSH_USERNAME` → username cPanel Anda
- `SSH_PRIVATE_KEY` → seluruh isi private key dari langkah 9a

### Cara kerjanya setelah ini

- PR di-merge ke `main`, atau ada commit baru masuk ke `main` → tab
  **Actions** di GitHub otomatis menjalankan job "Deploy ke cPanel" →
  `rsync` menyinkronkan seluruh isi repo (kecuali `.git`, `.github`,
  `application/config/database.php`, `application/logs/`,
  `application/cache/`, `_legacy_html_backup/`) ke document root
  subdomain lewat koneksi SSH.
- Progress & hasilnya bisa dipantau di tab **Actions**.
- File `database.php`, folder `logs`/`cache` di server **tidak pernah
  tertimpa/terhapus** (dikecualikan dari rsync, dan workflow ini
  sengaja tidak pakai flag `--delete`).
- Bisa juga dipicu manual: tab **Actions** → pilih workflow → **Run
  workflow** (butuh branch dengan file workflow ini sudah pernah ada di
  `main` minimal sekali, baru muncul di daftar).

## 10. Auto-deploy via webhook GitHub (jalur baru, tidak butuh SSH/FTP)

**Kenapa ini punya peluang berhasil padahal §9 gagal:** FTP dan SSH
diblokir karena firewall hosting ini kemungkinan besar cuma mengizinkan
koneksi *masuk* ke port web (80/443) dari IP mana pun, sementara port
non-web (21, 22) dibatasi ke IP tertentu saja. Webhook memakai arah
yang berbeda — **GitHub yang memanggil server** (bukan sebaliknya)
lewat HTTPS biasa (port 443), port yang sama persis dengan yang dipakai
pengunjung mengakses situs ini. Jadi jalur ini tidak menabrak
pembatasan yang sama dengan FTP/SSH.

**Prasyarat: repo GitHub harus publik**, supaya cPanel Git Version
Control bisa clone tanpa Personal Access Token (lihat pembahasan
sebelumnya soal ini). Sudah dicek: `application/config/database.php`
di-`.gitignore` dan tidak pernah ter-commit, jadi aman dijadikan
publik. Satu pengecualian — kalau akun admin seed
(`admin@sipgatutkaca.local` / password `f0250dc5621e` dari
`database/tambah_role_dan_admin.sql`) masih aktif, ganti dulu
password-nya sebelum/segera setelah repo dipublikkan, karena string
itu akan ikut terlihat di riwayat repo publik.

### 10a. Jadikan repo publik

GitHub repo → **Settings** → scroll ke **Danger Zone** → **Change
visibility** → **Change to public** → konfirmasi dengan mengetik nama
repo.

### 10b. Setup cPanel Git Version Control

Sama seperti **Cara B** di Langkah 3, tapi sekarang tanpa Personal
Access Token karena repo sudah publik:

- cPanel → **Git Version Control** → **Create**:
  - Clone URL: `https://github.com/simbgsemarang-code/sipgatutkaca.git`
  - Branch: `claude/clone-ke-dalam-komputerku-zwz9iz`
  - Repository Path: document root subdomain (folder yang sama dengan
    `index.php`, `.htaccess`, dst)
- Kalau document root sudah berisi file dari deploy manual sebelumnya
  (Cara A), kosongkan dulu foldernya sebelum clone supaya tidak
  bentrok, ATAU clone ke folder sementara lalu pindahkan isinya
  menimpa document root (mirip Langkah 7 di Cara A) — yang penting
  hasil akhirnya folder itu jadi git working copy yang valid (ada
  subfolder `.git` di dalamnya).

### 10c. Salin & isi konfigurasi webhook

File `deploy-hook.php` dan `deploy-hook.config.php.example` sudah ikut
ter-clone bersama kode lainnya. Lewat File Manager, di document root:

1. Duplikat/copy `deploy-hook.config.php.example` → beri nama baru
   `deploy-hook.config.php` (persis, tanpa `.example`).
2. Edit isinya, ganti `'secret' => 'GANTI_DENGAN_STRING_ACAK_PANJANG'`
   dengan string acak panjang (40+ karakter). Kalau ada akses
   **Terminal** di cPanel, bisa generate cepat:
   ```bash
   php -r "echo bin2hex(random_bytes(32)), PHP_EOL;"
   ```
   Tanpa Terminal, pakai generator password online mana pun, ambil
   yang panjang dan acak. **Simpan string ini** — dipakai lagi di
   langkah 10e.
3. Pastikan `'branch'` di file itu sama dengan branch yang di-clone di
   langkah 10b (`claude/clone-ke-dalam-komputerku-zwz9iz`).

File `deploy-hook.config.php` ini **sengaja di-`.gitignore`** — tidak
akan pernah ter-commit ke repo publik, isinya aman berisi secret asli.

### 10d. Cek kesiapan server SEBELUM setting webhook di GitHub

Buka lewat browser (ganti `SECRET_ANDA` dengan secret dari langkah
10c):

```
https://sipgatutkaca.sigaru.my.id/deploy-hook.php?diag=SECRET_ANDA
```

Harus muncul teks diagnostik yang menyebutkan:
- `exec() tersedia: YA`
- `git binary` terdeteksi dengan nomor versi
- `folder ini git working copy: YA`
- `tes fetch dari origin: BERHASIL`

**Kalau ada yang gagal**, lihat bagian Troubleshooting (10g) di bawah
sebelum lanjut ke langkah berikutnya — jangan setting webhook GitHub
dulu kalau diagnostik ini belum semuanya beres, supaya tidak
bolak-balik menebak dari sisi GitHub.

### 10e. Tambah webhook di GitHub

Repo → **Settings** → **Webhooks** → **Add webhook**:
- **Payload URL**: `https://sipgatutkaca.sigaru.my.id/deploy-hook.php`
- **Content type**: `application/json`
- **Secret**: string yang sama persis dari langkah 10c
- **Which events**: pilih **"Just the push event"**
- **Active**: dicentang
- **Add webhook**

GitHub langsung mengirim satu event percobaan (`ping`) begitu webhook
dibuat. Cek di halaman webhook tsb → tab **Recent Deliveries** →
respons harus **200** dengan body `pong`.

### 10f. Tes end-to-end

Push commit apa pun ke branch `claude/clone-ke-dalam-komputerku-zwz9iz`
(atau di GitHub, buka delivery `ping` tadi → **Redeliver** kalau mau
tes ulang tanpa push baru — tapi redeliver `ping` cuma tes
konektivitas, bukan tes deploy sungguhan karena event-nya bukan
`push`). Setelah push asli:
- Tab **Recent Deliveries** di webhook harus menunjukkan event `push`
  dengan respons **200** dan body `Deploy sukses.`
- Buka situsnya, konfirmasi perubahan sudah tampil tanpa perlu upload
  manual sama sekali.

### 10g. ⚠️ PENTING: hentikan kebiasaan edit manual di File Manager

Begitu webhook ini aktif, **setiap push ke branch yang di-deploy akan
menjalankan `git reset --hard`**, yang menimpa ulang seluruh file yang
dilacak git supaya sama persis dengan GitHub — termasuk menghapus
perubahan apa pun yang dibuat langsung lewat File Manager dan belum
sempat di-commit ke repo. File yang **tidak** dilacak git (seperti
`application/config/database.php` dan `deploy-hook.config.php` sendiri,
karena ada di `.gitignore`) aman, tidak akan tersentuh.

Praktis: setelah ini jalan, alur kerjanya berubah total — **tidak perlu
lagi** instruksi "buka link Raw → copy → paste ke File Manager" yang
dipakai selama ini. Cukup commit + push ke branch yang di-deploy, dan
situsnya update sendiri dalam beberapa detik.

### 10h. Troubleshooting

- **`exec() tersedia: TIDAK`** — hosting ini menonaktifkan fungsi shell
  PHP lewat `disable_functions` (umum di shared hosting demi keamanan).
  Kalau begini, pendekatan `deploy-hook.php` ini tidak bisa dipakai
  sama sekali, tidak ada cara memaksanya dari sisi PHP. Alternatif:
  cek apakah cPanel punya endpoint API (UAPI/WHM API) untuk memicu
  "Pull or Deploy" pada Git Version Control secara remote — perlu
  API token cPanel (**Security → Manage API Tokens**); ini rute
  cadangan yang belum dieksplorasi, kabari kalau exec() ternyata mati
  supaya bisa dirancang lebih lanjut.
- **`git binary: TIDAK DITEMUKAN`** — jarang terjadi kalau cPanel Git
  Version Control sendiri berfungsi (fitur itu juga butuh binary git),
  tapi PATH untuk proses PHP bisa beda dengan proses cPanel. Kabari
  kalau ini terjadi.
- **`folder ini git working copy: TIDAK`** — Repository Path di
  langkah 10b belum benar, atau clone belum selesai/gagal. Cek lagi
  cPanel → Git Version Control.
- **`tes fetch dari origin: GAGAL`** — kemungkinan lain dari
  pembatasan firewall yang sama seperti kasus SSH/FTP di §9, tapi kali
  ini untuk koneksi keluar (egress) dari server ke GitHub, bukan
  masuk. Kalau terjadi, kemungkinan besar memang perlu bantuan hosting
  support juga.
- **Delivery di GitHub menunjukkan status selain 200** — klik
  delivery tsb untuk lihat response body-nya, biasanya pesannya sudah
  cukup jelas (mis. "Signature tidak valid" berarti secret di
  `deploy-hook.config.php` beda dengan yang diisi di form webhook
  GitHub).
- Semua percobaan (berhasil maupun gagal) tercatat di
  `deploy-hook.log` di document root — bisa dibuka lewat File Manager
  kalau butuh detail lebih lanjut (file ini diblokir dari akses
  browser langsung lewat `.htaccess`, jadi harus dibuka dari File
  Manager).
