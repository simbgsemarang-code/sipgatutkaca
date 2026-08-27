-- Data UJI COBA untuk fitur Pengajuan PBG (2 permohonan dummy, lokasi
-- Kabupaten Cilacap, Jawa Tengah). Jalankan lewat phpMyAdmin -> pilih
-- database Anda -> tab SQL -> paste semua isi file ini -> Go.
--
-- WAJIB dijalankan SETELAH:
--   1. database/pengajuan_pbg.sql   (bikin tabelnya dulu)
--   2. database/akun_uji_coba.sql   (bikin akun Ahmad Wijaya & Siti
--      Rahmawati dulu - dipakai di sini sebagai staf PU yang
--      "menginput" kedua dummy ini)
-- Tanpa keduanya, INSERT di bawah akan gagal (tabel belum ada / akun
-- rujukannya belum ada sehingga dibuat_oleh jadi NULL, padahal kolom
-- itu NOT NULL).
--
-- Aman dijalankan berkali-kali - masing-masing INSERT dijaga WHERE
-- NOT EXISTS supaya tidak menumpuk baris duplikat kalau file ini
-- dijalankan ulang.
--
-- Ini data UJI COBA, bukan permohonan sungguhan - setelah selesai
-- uji coba, sebaiknya dihapus lewat tombol Hapus (untuk yang masih
-- berstatus draf) atau lewat query DELETE manual (untuk yang sudah
-- Verifikasi Kelengkapan Dokumen, karena tombol Hapus di aplikasi
-- sengaja cuma berlaku untuk draf).

-- 1) Sudah dikirim (status: Verifikasi Kelengkapan Dokumen)
INSERT INTO `pengajuan_pbg` (
  `dibuat_oleh`, `no_registrasi`, `status`,
  `nama_pemohon`, `nik_pemohon`, `kontak_pemohon`,
  `intensitas_ada`,
  `lokasi_provinsi`, `lokasi_kabupaten`, `lokasi_kecamatan`, `lokasi_kelurahan`, `lokasi_alamat`,
  `jumlah_bukti_tanah`, `kepemilikan_bangunan`, `kondisi_bangunan`,
  `pakai_prototipe`, `masa_pemanfaatan`,
  `fungsi_bangunan`,
  `punya_basemen`, `bangunan_nama`, `bangunan_luas_per_unit`, `bangunan_tinggi`, `bangunan_jumlah_lantai`, `bangunan_jumlah_unit`, `bangunan_estimasi_penghuni`, `bangunan_latitude`, `bangunan_longitude`,
  `tanah_jenis_dokumen`, `tanah_nomor_dokumen`, `tanah_tanggal_terbit`, `tanah_luas`, `tanah_hak_kepemilikan`, `tanah_nama_pemilik`, `tanah_provinsi`, `tanah_kabupaten`, `tanah_kecamatan`, `tanah_kelurahan`, `tanah_alamat`, `tanah_pemilik_sama`,
  `created_at`
)
SELECT
  (SELECT id FROM `users` WHERE email = 'ahmad.wijaya@sipgatutkaca.local'), 'PBG-20260815-0001', 'verifikasi_dokumen',
  'Slamet Riyadi', '3301021503850001', '082134567891',
  'tidak',
  'Jawa Tengah', 'Cilacap', 'Cilacap Tengah', 'Sidanegara', 'Jl. Gatot Subroto No. 45',
  1, 'perorangan', 'belum_berdiri',
  'tidak', 'lebih_5_tahun',
  'Fungsi Hunian: Rumah Tinggal Tunggal',
  'tidak', 'Rumah Tinggal Slamet Riyadi', '90', '7', 2, 1, 4, '-7.7226', '109.0134',
  'Sertifikat Hak Milik', 'SHM-00123/Sidanegara', '2015-03-10', '120', 'Hak Milik', 'Slamet Riyadi', 'Jawa Tengah', 'Cilacap', 'Cilacap Tengah', 'Sidanegara', 'Jl. Gatot Subroto No. 45', 'sama',
  '2026-08-15 09:20:00'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_pbg` WHERE `nama_pemohon` = 'Slamet Riyadi' AND `tanah_nomor_dokumen` = 'SHM-00123/Sidanegara'
);

-- 2) Masih draf (status: Draf) - belum dikirim, sengaja beberapa
--    field dibiarkan kosong supaya juga jadi contoh permohonan yang
--    belum lengkap.
INSERT INTO `pengajuan_pbg` (
  `dibuat_oleh`, `status`,
  `nama_pemohon`, `nik_pemohon`, `kontak_pemohon`,
  `lokasi_provinsi`, `lokasi_kabupaten`, `lokasi_kecamatan`, `lokasi_kelurahan`, `lokasi_alamat`,
  `jumlah_bukti_tanah`, `kepemilikan_bangunan`, `kondisi_bangunan`,
  `fungsi_bangunan`,
  `bangunan_nama`, `bangunan_luas_per_unit`, `bangunan_jumlah_lantai`,
  `created_at`
)
SELECT
  (SELECT id FROM `users` WHERE email = 'siti.rahmawati@sipgatutkaca.local'), 'draf',
  'Wahyuni Astuti', '3301025804920002', '085712345678',
  'Jawa Tengah', 'Cilacap', 'Cilacap Tengah', 'Gunungsimping', 'Jl. Nusantara No. 12',
  1, 'perorangan', 'sudah_ada',
  'Fungsi Usaha: Bangunan Gedung Perdagangan',
  'Toko Kelontong Wahyuni', '48', 1,
  '2026-08-26 14:05:00'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_pbg` WHERE `nama_pemohon` = 'Wahyuni Astuti' AND `bangunan_nama` = 'Toko Kelontong Wahyuni'
);
