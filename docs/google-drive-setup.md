# Setup Google Drive untuk penyimpanan berkas

Tujuannya: berkas yang diunggah (dokumen PBG, lampiran konsultasi TPA,
foto cagar budaya/bangunan, PDF regulasi) tersimpan di Google Drive,
bukan di disk server - jadi kalau nanti domain/hosting berpindah,
berkas lama tetap aman dan bisa diakses.

Kredensial di sini sepenuhnya terpisah dari kode. Kalau nanti akun
Google Drive-nya perlu diganti, cukup ulangi langkah di bawah dengan
akun baru dan timpa file kredensial + folder ID di
`application/config/gdrive.php` - tidak perlu mengubah satu baris
kode pun.

## 0. Sekali saja: siapkan application/config/gdrive.php

File `gdrive.php` **tidak ikut Git** (sama seperti `database.php`) -
supaya nilai `gdrive_enabled`/`gdrive_folder_id` yang kamu isi di
server tidak tertimpa balik ke default tiap kali deploy commit baru.
Karena itu, file ini harus disiapkan **manual, sekali saja, di setiap
server** (lokal dan live terpisah):

1. Lewat cPanel File Manager, salin `application/config/gdrive.php.example` jadi `application/config/gdrive.php` (di folder yang sama).
2. Edit isinya sesuai Cara A atau Cara B di bawah.

Kalau langkah ini belum dilakukan, `gdrive.php` akan otomatis dibuat
ulang oleh Git dengan `gdrive_enabled = false` setiap deploy, dan
berkas akan terus tersimpan di server, bukan Drive - **ini penyebab
paling umum kalau upload masih ke server padahal sudah pernah
diaktifkan sebelumnya.**

## Pilih salah satu cara

- **Akun Gmail biasa** (`@gmail.com`, bukan dari instansi) → pakai
  **Cara A (OAuth)** di bawah. Ini kasus paling umum.
- **Akun Google Workspace instansi** (dengan fitur Shared Drive) →
  boleh pakai **Cara B (Service Account)**, setupnya lebih sederhana.

Google **tidak mengizinkan** Service Account menulis berkas ke folder
milik akun Gmail biasa (error `storageQuotaExceeded`) - itu sebabnya
akun Gmail biasa wajib pakai Cara A.

---

## Cara A: OAuth (akun Gmail biasa)

### A1. Buat Google Cloud Project & aktifkan Drive API

1. Buka https://console.cloud.google.com/
2. Dropdown project di kiri atas → **New Project** → beri nama bebas (mis. `sipgatutkaca-storage`) → **Create**. Pastikan project itu terpilih.
3. Kotak pencarian atas → ketik **Google Drive API** → buka hasilnya → **Enable**.

### A2. Atur OAuth consent screen

1. Menu ☰ → **APIs & Services** → **OAuth consent screen**.
2. User Type: **External** → **Create**.
3. Isi App name bebas (mis. "SIP Gatutkaca Storage"), User support email dan Developer contact diisi email Gmail-mu → **Save and Continue** terus sampai selesai (bagian Scopes dan Test users boleh dilewati dulu).
4. Di halaman **Audience** (atau "Test users" tergantung versi Console), klik **Add users** → tambahkan alamat Gmail yang jadi pemilik folder Drive nanti. Wajib, karena aplikasi ini akan tetap berstatus "Testing" (tidak perlu diajukan untuk verifikasi Google).

### A3. Buat OAuth Client ID

1. Menu ☰ → **APIs & Services** → **Credentials** → **Create Credentials** → **OAuth client ID**.
2. Application type: **Web application**.
3. Authorized redirect URIs → **Add URI** → isi:
   ```
   https://sipgatutkaca.sigaru.my.id/admin/gdrive-oauth-callback
   ```
   (ganti domain kalau live-nya pindah nanti - dan boleh tambahkan juga versi `http://localhost/sipgatutkaca/admin/gdrive-oauth-callback` untuk uji coba di lokal.)
4. **Create**. Catat/copy **Client ID** dan **Client secret** yang muncul.

### A4. Buat folder Drive

1. Buka https://drive.google.com dengan akun Gmail yang tadi didaftarkan sebagai test user.
2. Buat folder baru, misalnya `SIP Gatutkaca - Berkas`.
3. Buka folder itu, lihat URL: `drive.google.com/drive/folders/XXXXXXXXXXXX` - `XXXXXXXXXXXX` itu **Folder ID**-nya. (Tidak perlu di-share ke siapa pun untuk Cara A, karena aplikasi login sebagai akun Gmail ini sendiri.)

### A5. Pasang di server

1. Buat file **`application/gdrive/oauth-client.json`** (folder ini sudah diblokir dari akses publik lewat `.htaccess`) isinya:
   ```json
   {
     "client_id": "TEMPEL_CLIENT_ID_DARI_A3",
     "client_secret": "TEMPEL_CLIENT_SECRET_DARI_A3"
   }
   ```
2. Edit `application/config/gdrive.php`:
   ```php
   $config['gdrive_enabled']   = true;
   $config['gdrive_auth_mode'] = 'oauth';
   $config['gdrive_folder_id'] = 'XXXXXXXXXXXX'; // dari langkah A4
   ```
3. Login ke aplikasi sebagai **admin**, lalu buka di browser:
   ```
   https://sipgatutkaca.sigaru.my.id/admin/gdrive-oauth
   ```
4. Kamu akan diarahkan ke halaman login/consent Google - login dengan akun Gmail pemilik folder (langkah A4), lalu **Allow**.
5. Kalau berhasil, akan muncul pesan sukses dan file `application/gdrive/oauth-token.json` otomatis dibuat. Selesai - upload berkas baru di aplikasi akan otomatis masuk ke Drive.

Langkah A5.3-A5.5 (kunjungi `admin/gdrive-oauth`) hanya perlu dilakukan **sekali**. Kalau nanti tokennya kedaluwarsa/dicabut, ulangi cukup langkah itu saja.

---

## Cara B: Service Account (akun Google Workspace + Shared Drive)

### B1. Buat Google Cloud Project & aktifkan Drive API

Sama seperti A1 di atas.

### B2. Buat Service Account & kunci JSON

1. Menu ☰ → **IAM & Admin** → **Service Accounts** → **Create Service Account**.
2. Nama bebas (mis. `sipgatutkaca-drive`) → **Create and Continue** → bagian role boleh dilewati → **Done**.
3. Klik service account yang baru dibuat → tab **Keys** → **Add Key** → **Create new key** → pilih **JSON** → **Create**. File JSON otomatis terunduh.
4. Catat alamat emailnya (format `...@nama-project.iam.gserviceaccount.com`).

### B3. Buat Shared Drive & tambahkan service account

1. Di https://drive.google.com, buat **Shared Drive** baru (bukan folder biasa di My Drive - fitur ini cuma ada di akun Workspace).
2. Tambahkan alamat email service account (langkah B2.4) sebagai anggota dengan peran **Content Manager** atau lebih tinggi.
3. Folder ID diambil dari URL Shared Drive itu (atau buat subfolder di dalamnya dan pakai ID subfolder itu).

### B4. Pasang di server

1. Beri nama file JSON dari B2.3 menjadi `credentials.json`, taruh di **`application/gdrive/credentials.json`**.
2. Edit `application/config/gdrive.php`:
   ```php
   $config['gdrive_enabled']   = true;
   $config['gdrive_auth_mode'] = 'service_account';
   $config['gdrive_folder_id'] = 'XXXXXXXXXXXX'; // dari langkah B3.3
   ```
3. Selesai - tidak perlu langkah consent seperti Cara A.

---

## Kalau nanti ganti akun Google Drive

Ulangi langkah cara yang sesuai dengan akun baru, timpa file
kredensialnya (`oauth-client.json` + `oauth-token.json`, atau
`credentials.json`), dan ganti `gdrive_folder_id` di config. Berkas
lama yang sudah terlanjur ada di akun Drive lama tetap tersimpan di
sana (tautannya di database tetap mengarah ke sana); hanya unggahan
BARU yang masuk ke akun/folder yang baru.

## Berkas yang SENGAJA tidak dipindah ke Drive

- **PBG jalur lama** (`Pengajuan_pbg`, disimpan di
  `application/uploads/pengajuan_pbg/`): seluruhnya tetap di disk
  server (2026-09-22) - jalur ini privat, hanya pemilik yang login
  yang bisa mengaksesnya.
- **ITR - KTP & NPWP saja** (`Pemohon::upload_berkas_itr`, field
  `file_ktp` dan `file_npwp`, daftarnya di properti
  `$itr_file_privat` pada `application/controllers/Pemohon.php`):
  ini dua dokumen identitas paling sensitif, tetap di
  `application/uploads/itr/` (2026-09-24, meralat keputusan
  2026-09-22 yang tadinya mengecualikan SEMUA berkas ITR). Lampiran
  ITR lainnya (Surat Permohonan, Sertifikat Tanah, Site Plan, Denah &
  Foto, NIB, Akta Pendirian Perusahaan) SUDAH boleh ke Drive.

Alasannya: berkas yang dikecualikan bersifat privat - hanya pemilik
yang login yang bisa mengaksesnya lewat controller yang memeriksa
kepemilikan, dan mengunggahnya ke Drive berarti membaginya sebagai
"siapa saja dengan tautan", menurunkan privasi dokumen kependudukan
tersebut. Kalau nanti keputusan ini ingin diubah lagi,
`berkas_simpan()` di `application/helpers/berkas_helper.php` tinggal
dipakai/dilepas dari field terkait - tapi pertimbangkan dulu
implikasi privasinya.

## Kalau belum sempat setup (default saat ini)

Selama `gdrive_enabled` masih `false` (atau kredensialnya belum
lengkap), aplikasi otomatis kembali menyimpan berkas ke disk server
seperti sebelumnya - tidak ada yang rusak.
