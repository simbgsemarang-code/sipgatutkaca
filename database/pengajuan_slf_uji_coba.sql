-- Data UJI COBA untuk fitur Pengajuan SLF (2 permohonan dummy dengan
-- nama pemohon yang realistis, lokasi Kabupaten Cilacap, Jawa Tengah).
-- Jalankan lewat phpMyAdmin -> pilih database Anda -> tab SQL -> paste
-- semua isi file ini -> Go. Pola sama persis dengan
-- database/pengajuan_pbg_uji_coba.sql.
--
-- WAJIB dijalankan SETELAH:
--   1. database/pengajuan_slf.sql   (bikin tabelnya dulu)
--   2. database/akun_uji_coba.sql   (bikin akun Ahmad Wijaya & Siti
--      Rahmawati - dipakai di sini sebagai staf PU yang "menginput"
--      kedua dummy ini di loket)
-- Tanpa keduanya, INSERT di bawah gagal (tabel belum ada / akun
-- rujukannya belum ada sehingga dibuat_oleh jadi NULL, padahal kolom
-- itu NOT NULL).
--
-- Aman dijalankan berkali-kali - tiap INSERT dijaga WHERE NOT EXISTS
-- supaya tidak menumpuk baris duplikat.
--
-- Ini data UJI COBA, bukan permohonan sungguhan - setelah selesai uji
-- coba, hapus lewat query DELETE manual (tombol Hapus di aplikasi
-- sengaja cuma berlaku untuk draf).

-- 1) Rumah tinggal eksisting - sudah dikirim (Verifikasi Kelengkapan Dokumen)
INSERT INTO `pengajuan_slf` (
  `dibuat_oleh`, `no_registrasi`, `status`,
  `nama_pemohon`, `nik_pemohon`, `kontak_pemohon`,
  `intensitas_ada`, `intensitas_no_dokumen`, `intensitas_gsb`, `intensitas_kdb`, `intensitas_klb`, `intensitas_kdh`,
  `lokasi_provinsi`, `lokasi_kabupaten`, `lokasi_kecamatan`, `lokasi_kelurahan`, `lokasi_alamat`,
  `jumlah_bukti_tanah`, `kepemilikan_bangunan`, `kondisi_bangunan`,
  `pakai_prototipe`, `masa_pemanfaatan`,
  `fungsi_bangunan`,
  `punya_basemen`, `bangunan_nama`, `bangunan_luas_per_unit`, `bangunan_tinggi`, `bangunan_jumlah_lantai`, `bangunan_jumlah_unit`, `bangunan_estimasi_penghuni`, `bangunan_latitude`, `bangunan_longitude`,
  `tanah_jenis_dokumen`, `tanah_nomor_dokumen`, `tanah_tanggal_terbit`, `tanah_luas`, `tanah_hak_kepemilikan`, `tanah_nama_pemilik`,
  `tanah_provinsi`, `tanah_kabupaten`, `tanah_kecamatan`, `tanah_kelurahan`, `tanah_alamat`, `tanah_pemilik_sama`,
  `created_at`
)
SELECT
  (SELECT id FROM `users` WHERE email = 'ahmad.wijaya@sipgatutkaca.local'), 'SLF-20260820-0001', 'verifikasi_dokumen',
  'Suryadi Nugroho', '3301081207790003', '081327884512',
  'ya', 'KKPR-241/DPMPTSP/2022', '3 meter', '60%', '1.2', '30%',
  'Jawa Tengah', 'Cilacap', 'Cilacap Selatan', 'Tegalkamulyan', 'Jl. Lingkar Selatan No. 88',
  1, 'perorangan', 'sudah_ada',
  'tidak', 'lebih_5_tahun',
  'Fungsi Hunian: Rumah Tinggal Tunggal',
  'tidak', 'Rumah Tinggal Suryadi Nugroho', '110', '8', 2, 1, 5, '-7.7451', '109.0089',
  'Sertifikat Hak Milik', 'SHM-01874/Tegalkamulyan', '2013-06-18', '150', 'Hak Milik', 'Suryadi Nugroho',
  'Jawa Tengah', 'Cilacap', 'Cilacap Selatan', 'Tegalkamulyan', 'Jl. Lingkar Selatan No. 88', 'sama',
  '2026-08-20 10:15:00'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_slf` WHERE `nama_pemohon` = 'Suryadi Nugroho' AND `tanah_nomor_dokumen` = 'SHM-01874/Tegalkamulyan'
);

-- 2) Bangunan usaha (ruko) eksisting - sudah dikirim (Verifikasi Kelengkapan Dokumen)
INSERT INTO `pengajuan_slf` (
  `dibuat_oleh`, `no_registrasi`, `status`,
  `nama_pemohon`, `nik_pemohon`, `kontak_pemohon`,
  `intensitas_ada`, `intensitas_no_dokumen`, `intensitas_gsb`, `intensitas_kdb`, `intensitas_klb`, `intensitas_kdh`,
  `lokasi_provinsi`, `lokasi_kabupaten`, `lokasi_kecamatan`, `lokasi_kelurahan`, `lokasi_alamat`,
  `jumlah_bukti_tanah`, `kepemilikan_bangunan`, `kondisi_bangunan`,
  `pakai_prototipe`, `masa_pemanfaatan`,
  `fungsi_bangunan`,
  `punya_basemen`, `bangunan_nama`, `bangunan_luas_per_unit`, `bangunan_tinggi`, `bangunan_jumlah_lantai`, `bangunan_jumlah_unit`, `bangunan_estimasi_penghuni`, `bangunan_latitude`, `bangunan_longitude`,
  `tanah_jenis_dokumen`, `tanah_nomor_dokumen`, `tanah_tanggal_terbit`, `tanah_luas`, `tanah_hak_kepemilikan`, `tanah_nama_pemilik`,
  `tanah_provinsi`, `tanah_kabupaten`, `tanah_kecamatan`, `tanah_kelurahan`, `tanah_alamat`, `tanah_pemilik_sama`,
  `created_at`
)
SELECT
  (SELECT id FROM `users` WHERE email = 'siti.rahmawati@sipgatutkaca.local'), 'SLF-20260825-0002', 'verifikasi_dokumen',
  'Ratna Kusumawardani', '3301024611860004', '085869213470',
  'ya', 'KKPR-318/DPMPTSP/2021', '4 meter', '70%', '2.1', '20%',
  'Jawa Tengah', 'Cilacap', 'Cilacap Utara', 'Gumilir', 'Jl. Urip Sumoharjo No. 27',
  1, 'badan_hukum_usaha', 'sudah_ada',
  'tidak', 'lebih_5_tahun',
  'Fungsi Usaha: Bangunan Gedung Perdagangan',
  'tidak', 'Ruko Gumilir Jaya', '72', '11', 3, 2, 12, '-7.6912', '109.0331',
  'Sertifikat Hak Guna Bangunan', 'HGB-00542/Gumilir', '2016-11-02', '96', 'Hak Guna Bangunan', 'PT Gumilir Jaya Sentosa',
  'Jawa Tengah', 'Cilacap', 'Cilacap Utara', 'Gumilir', 'Jl. Urip Sumoharjo No. 27', 'sama',
  '2026-08-25 13:40:00'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_slf` WHERE `nama_pemohon` = 'Ratna Kusumawardani' AND `tanah_nomor_dokumen` = 'HGB-00542/Gumilir'
);
