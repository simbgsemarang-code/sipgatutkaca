# Setup Google Drive untuk penyimpanan berkas

Tujuannya: berkas yang diunggah (dokumen PBG, KTP, lampiran konsultasi
TPA, foto cagar budaya, dsb.) tersimpan di Google Drive, bukan di disk
server - jadi kalau nanti domain/hosting berpindah, berkas lama tetap
aman dan bisa diakses.

Kredensial di sini sepenuhnya terpisah dari kode. Kalau nanti akun
Google Drive-nya perlu diganti (misalnya pindah ke akun instansi yang
baru), cukup ulangi langkah di bawah dengan akun baru dan ganti dua
nilai di `application/config/gdrive.php` - tidak perlu mengubah satu
baris kode pun.

## 1. Buat Google Cloud Project

1. Buka https://console.cloud.google.com/
2. Klik dropdown project di kiri atas → **New Project**.
3. Beri nama bebas, misalnya `sipgatutkaca-storage` → **Create**.
4. Pastikan project itu terpilih (cek dropdown di kiri atas).

## 2. Aktifkan Google Drive API

1. Di kotak pencarian atas, ketik **Google Drive API** → buka hasilnya.
2. Klik **Enable**.

## 3. Buat Service Account

1. Menu ☰ → **IAM & Admin** → **Service Accounts**.
2. Klik **Create Service Account**.
3. Nama bebas, misalnya `sipgatutkaca-drive` → **Create and Continue**.
4. Bagian "Grant this service account access to project" boleh **dilewati** (Continue) - tidak perlu role apa pun di level project.
5. Klik **Done**.

## 4. Buat & unduh kunci JSON

1. Di daftar Service Accounts, klik service account yang baru dibuat.
2. Tab **Keys** → **Add Key** → **Create new key**.
3. Pilih **JSON** → **Create**. File JSON otomatis terunduh ke komputermu.
4. **Catat alamat emailnya** (terlihat di halaman itu, formatnya seperti `sipgatutkaca-drive@nama-project.iam.gserviceaccount.com`) - dipakai di langkah 5.

## 5. Buat folder Drive & bagikan ke service account

1. Buka https://drive.google.com dengan akun Google-mu (akun pribadi/instansi biasa, bukan service account).
2. Buat folder baru, misalnya `SIP Gatutkaca - Berkas`.
3. Klik kanan folder → **Share** → tempel alamat email service account dari langkah 4 → beri akses **Editor** → **Send/Share** (boleh centang "Notify people" dimatikan, service account tidak punya inbox).
4. Buka folder itu, lihat URL di address bar: `drive.google.com/drive/folders/XXXXXXXXXXXX` - bagian `XXXXXXXXXXXX` itu **Folder ID**-nya.

## 6. Pasang di server

1. Beri nama file JSON yang terunduh tadi menjadi `credentials.json`.
2. Upload/copy ke folder **`application/gdrive/credentials.json`** di server (folder ini sudah diblokir dari akses publik lewat `.htaccess` - jangan pernah taruh di `assets/` atau folder publik lainnya, dan jangan pernah commit ke git).
3. Edit `application/config/gdrive.php`:
   ```php
   $config['gdrive_enabled']   = true;
   $config['gdrive_folder_id'] = 'XXXXXXXXXXXX'; // dari langkah 5.4
   ```
4. Selesai - upload berkas baru di aplikasi akan otomatis masuk ke Drive.

## Kalau nanti ganti akun Google Drive

Ulangi langkah 1-5 dengan akun/project baru, timpa
`application/gdrive/credentials.json` dengan kunci JSON yang baru, dan
ganti `gdrive_folder_id` di config sesuai folder barunya. Berkas lama
yang sudah terlanjur ada di Drive akun lama tetap tersimpan di sana
(tautannya di database tetap mengarah ke sana); hanya unggahan
BARU yang akan masuk ke folder/akun yang baru.

## Berkas yang SENGAJA tidak dipindah ke Drive

KTP & sertifikat tanah pemohon ITR (`Pemohon::simpan_itr`, disimpan di
`application/uploads/itr/`) dan berkas PBG jalur lama
(`Pengajuan_pbg`, disimpan di `application/uploads/pengajuan_pbg/`)
tetap di disk server, atas keputusan eksplisit (2026-09-22). Alasannya:
kedua jalur itu bersifat privat - hanya pemilik yang login yang bisa
mengaksesnya lewat controller yang memeriksa kepemilikan. Google Drive
lewat service account hanya bisa dibagikan sebagai "siapa saja dengan
tautan", yang akan menurunkan privasi dokumen kependudukan tersebut.
Kalau nanti keputusan ini ingin diubah, `berkas_simpan()` di
`application/helpers/berkas_helper.php` bisa dipakai di kedua
controller itu juga - tapi pertimbangkan dulu implikasi privasinya.

## Kalau belum sempat setup (default saat ini)

Selama `gdrive_enabled` masih `false` (atau file kredensialnya belum
ada), aplikasi otomatis kembali menyimpan berkas ke disk server
seperti sebelumnya - tidak ada yang rusak.
