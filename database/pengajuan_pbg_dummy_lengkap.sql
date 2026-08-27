-- Data UJI COBA untuk fitur Pengajuan PBG: 1 permohonan dummy dengan
-- SEMUA kolom terisi (termasuk field yang belum dicontohkan di
-- database/pengajuan_pbg_uji_coba.sql - Intensitas Pemanfaatan Ruang,
-- Desain Prototipe, basemen, dan Izin Pemanfaatan Tanah) LENGKAP
-- dengan seluruh 15 dokumen checklist teknis + 3 lampiran tunggal
-- (Peta Prototipe, Peta Rencana Tapak Bangunan, Lampiran Kepemilikan
-- Tanah) sungguhan bisa dibuka - bukan cuma nama berkas kosong.
-- Jalankan lewat phpMyAdmin -> pilih database Anda -> tab SQL -> paste
-- semua isi file ini -> Go.
--
-- WAJIB dijalankan SETELAH:
--   1. database/pengajuan_pbg.sql          (bikin tabelnya dulu)
--   2. database/pengajuan_pbg_perbaikan.sql (kolom status lanjutan -
--      tidak dipakai di sini, tapi urutan migrasi tetap harus lengkap)
--   3. database/akun_uji_coba.sql           (akun Ahmad Wijaya dipakai
--      di sini sebagai staf PU yang "menginput" permohonan ini)
--
-- Berkas dokumennya (18 JPG placeholder) SUDAH ada di folder
-- application/uploads/pengajuan_pbg/dummy_lengkap/ (ditrack git, lihat
-- .gitignore) - begitu server Anda sudah menarik commit ini, berkasnya
-- otomatis ada, tidak perlu diunggah manual. Isinya cuma JPG bertuliskan
-- nama dokumennya (jelas ditandai "BUKAN dokumen sungguhan") supaya
-- tombol Lihat di halaman PU maupun TPA benar-benar menampilkan
-- sesuatu, bukan 404.
--
-- Aman dijalankan berkali-kali - INSERT dijaga WHERE NOT EXISTS per
-- baris (permohonan induk maupun tiap dokumen), dan UPDATE lampiran
-- tunggal aman diulang karena selalu men-set nilai yang sama.
--
-- Ini data UJI COBA, bukan permohonan sungguhan - setelah selesai
-- dipakai demo, sebaiknya dihapus lewat query DELETE manual (baris
-- pengajuan_pbg_dokumen ikut terhapus otomatis lewat ON DELETE
-- CASCADE - lihat database/pengajuan_pbg.sql).

-- 1) Permohonan induk - status Verifikasi Kelengkapan Dokumen (sudah
--    terkirim lengkap, siap ditinjau TPA).
INSERT INTO `pengajuan_pbg` (
  `dibuat_oleh`, `no_registrasi`, `status`,
  `nama_pemohon`, `nik_pemohon`, `kontak_pemohon`,
  `intensitas_ada`, `intensitas_no_dokumen`, `intensitas_gsb`, `intensitas_kdb`, `intensitas_klb`, `intensitas_kdh`,
  `lokasi_provinsi`, `lokasi_kabupaten`, `lokasi_kecamatan`, `lokasi_kelurahan`, `lokasi_alamat`,
  `jumlah_bukti_tanah`, `kepemilikan_bangunan`, `kondisi_bangunan`,
  `pakai_prototipe`, `prototipe_jumlah_unit`, `prototipe_latitude`, `prototipe_longitude`, `prototipe_jenis`, `prototipe_peta`,
  `fungsi_bangunan`,
  `punya_basemen`, `bangunan_nama`, `bangunan_luas_per_unit`, `bangunan_tinggi`, `bangunan_jumlah_lantai`,
  `bangunan_luas_basemen`, `bangunan_jumlah_lapis_basemen`, `bangunan_jumlah_unit`, `bangunan_estimasi_penghuni`,
  `bangunan_latitude`, `bangunan_longitude`, `bangunan_peta`,
  `tanah_jenis_dokumen`, `tanah_nomor_dokumen`, `tanah_tanggal_terbit`, `tanah_luas`, `tanah_hak_kepemilikan`,
  `tanah_nama_pemilik`, `tanah_lampiran`,
  `tanah_provinsi`, `tanah_kabupaten`, `tanah_kecamatan`, `tanah_kelurahan`, `tanah_alamat`, `tanah_pemilik_sama`,
  `tanah_nomor_izin`, `tanah_tanggal_izin`,
  `created_at`
)
SELECT
  (SELECT id FROM `users` WHERE email = 'ahmad.wijaya@sipgatutkaca.local'), 'PBG-20260827-0003', 'verifikasi_dokumen',
  'Eko Prasetyo', '3301031207880007', '081227334455',
  'ya', 'KKPR-188/2025/CLP', '4 meter', '60%', '1.2', '30%',
  'Jawa Tengah', 'Cilacap', 'Cilacap Selatan', 'Tambakreja', 'Jl. Perintis Kemerdekaan No. 78',
  1, 'perorangan', 'sedang_dibangun',
  'ya', 1, '-7.7398', '109.0163', 'Rumah Tinggal Tipe 45', 'dummy_lengkap/prototipe_peta.jpg',
  'Fungsi Hunian: Rumah Tinggal Tunggal',
  'ya', 'Rumah Tinggal Eko Prasetyo', '45', '4.5', 1,
  '20', 1, 1, 4,
  '-7.7398', '109.0163', 'dummy_lengkap/bangunan_peta.jpg',
  'Sertifikat Hak Milik', 'SHM-00456/Tambakreja', '2018-06-20', '90', 'Hak Milik',
  'Eko Prasetyo', 'dummy_lengkap/tanah_lampiran.jpg',
  'Jawa Tengah', 'Cilacap', 'Cilacap Selatan', 'Tambakreja', 'Jl. Perintis Kemerdekaan No. 78', 'sama',
  'IPT-2018-00456', '2018-06-25',
  '2026-08-27 10:15:00'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007'
);

-- 2) Dokumen checklist teknis (15 berkas - lengkap seluruh grup di
--    Pengajuan_pbg::$peta_dokumen, termasuk "Dokumen Tambahan" yang
--    sifatnya opsional). `jenis_dokumen` WAJIB persis sama dengan
--    label di $peta_dokumen supaya cocok dengan pengelompokan TPA
--    (Tpa_pengajuan_pbg::_kelompokkan_dokumen()) dan reverse-lookup
--    slug di pengajuan_pbg_perbaikan.php.

INSERT INTO `pengajuan_pbg_dokumen` (`id_pengajuan`, `jenis_dokumen`, `nama_file_asli`, `path_file`)
SELECT (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007'),
  'Data Identitas Pemilik Bangunan (KTP/KITAS)', 'KTP_Eko_Prasetyo.jpg', 'dummy_lengkap/ktp.jpg'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_pbg_dokumen` WHERE `id_pengajuan` = (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007') AND `jenis_dokumen` = 'Data Identitas Pemilik Bangunan (KTP/KITAS)'
);

INSERT INTO `pengajuan_pbg_dokumen` (`id_pengajuan`, `jenis_dokumen`, `nama_file_asli`, `path_file`)
SELECT (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007'),
  'Data Penyedia Jasa Perencana', 'Penyedia_Jasa_Eko_Prasetyo.jpg', 'dummy_lengkap/penyedia_jasa.jpg'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_pbg_dokumen` WHERE `id_pengajuan` = (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007') AND `jenis_dokumen` = 'Data Penyedia Jasa Perencana'
);

INSERT INTO `pengajuan_pbg_dokumen` (`id_pengajuan`, `jenis_dokumen`, `nama_file_asli`, `path_file`)
SELECT (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007'),
  'Dokumen KKPR / KRK', 'KKPR_Eko_Prasetyo.jpg', 'dummy_lengkap/kkpr.jpg'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_pbg_dokumen` WHERE `id_pengajuan` = (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007') AND `jenis_dokumen` = 'Dokumen KKPR / KRK'
);

INSERT INTO `pengajuan_pbg_dokumen` (`id_pengajuan`, `jenis_dokumen`, `nama_file_asli`, `path_file`)
SELECT (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007'),
  'Gambar Situasi', 'Gambar_Situasi_Eko_Prasetyo.jpg', 'dummy_lengkap/situasi.jpg'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_pbg_dokumen` WHERE `id_pengajuan` = (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007') AND `jenis_dokumen` = 'Gambar Situasi'
);

INSERT INTO `pengajuan_pbg_dokumen` (`id_pengajuan`, `jenis_dokumen`, `nama_file_asli`, `path_file`)
SELECT (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007'),
  'Gambar Rencana Tapak Bangunan', 'Gambar_Tapak_Eko_Prasetyo.jpg', 'dummy_lengkap/tapak.jpg'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_pbg_dokumen` WHERE `id_pengajuan` = (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007') AND `jenis_dokumen` = 'Gambar Rencana Tapak Bangunan'
);

INSERT INTO `pengajuan_pbg_dokumen` (`id_pengajuan`, `jenis_dokumen`, `nama_file_asli`, `path_file`)
SELECT (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007'),
  'Gambar Rencana Denah Bangunan', 'Gambar_Denah_Eko_Prasetyo.jpg', 'dummy_lengkap/denah.jpg'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_pbg_dokumen` WHERE `id_pengajuan` = (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007') AND `jenis_dokumen` = 'Gambar Rencana Denah Bangunan'
);

INSERT INTO `pengajuan_pbg_dokumen` (`id_pengajuan`, `jenis_dokumen`, `nama_file_asli`, `path_file`)
SELECT (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007'),
  'Gambar Rencana Potongan Bangunan', 'Gambar_Potongan_Eko_Prasetyo.jpg', 'dummy_lengkap/potongan.jpg'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_pbg_dokumen` WHERE `id_pengajuan` = (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007') AND `jenis_dokumen` = 'Gambar Rencana Potongan Bangunan'
);

INSERT INTO `pengajuan_pbg_dokumen` (`id_pengajuan`, `jenis_dokumen`, `nama_file_asli`, `path_file`)
SELECT (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007'),
  'Gambar Rencana Tampak Bangunan', 'Gambar_Tampak_Eko_Prasetyo.jpg', 'dummy_lengkap/tampak.jpg'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_pbg_dokumen` WHERE `id_pengajuan` = (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007') AND `jenis_dokumen` = 'Gambar Rencana Tampak Bangunan'
);

INSERT INTO `pengajuan_pbg_dokumen` (`id_pengajuan`, `jenis_dokumen`, `nama_file_asli`, `path_file`)
SELECT (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007'),
  'Dokumen Lingkungan (SPPL/UKL-UPL/AMDAL)', 'Dokumen_Lingkungan_Eko_Prasetyo.jpg', 'dummy_lengkap/lingkungan.jpg'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_pbg_dokumen` WHERE `id_pengajuan` = (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007') AND `jenis_dokumen` = 'Dokumen Lingkungan (SPPL/UKL-UPL/AMDAL)'
);

INSERT INTO `pengajuan_pbg_dokumen` (`id_pengajuan`, `jenis_dokumen`, `nama_file_asli`, `path_file`)
SELECT (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007'),
  'Gambar & Perhitungan Struktur', 'Gambar_Struktur_Eko_Prasetyo.jpg', 'dummy_lengkap/struktur.jpg'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_pbg_dokumen` WHERE `id_pengajuan` = (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007') AND `jenis_dokumen` = 'Gambar & Perhitungan Struktur'
);

INSERT INTO `pengajuan_pbg_dokumen` (`id_pengajuan`, `jenis_dokumen`, `nama_file_asli`, `path_file`)
SELECT (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007'),
  'Analisis Beban & Ketahanan Gempa', 'Analisis_Gempa_Eko_Prasetyo.jpg', 'dummy_lengkap/gempa.jpg'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_pbg_dokumen` WHERE `id_pengajuan` = (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007') AND `jenis_dokumen` = 'Analisis Beban & Ketahanan Gempa'
);

INSERT INTO `pengajuan_pbg_dokumen` (`id_pengajuan`, `jenis_dokumen`, `nama_file_asli`, `path_file`)
SELECT (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007'),
  'Gambar Instalasi Elektrikal', 'Gambar_Elektrikal_Eko_Prasetyo.jpg', 'dummy_lengkap/elektrikal.jpg'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_pbg_dokumen` WHERE `id_pengajuan` = (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007') AND `jenis_dokumen` = 'Gambar Instalasi Elektrikal'
);

INSERT INTO `pengajuan_pbg_dokumen` (`id_pengajuan`, `jenis_dokumen`, `nama_file_asli`, `path_file`)
SELECT (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007'),
  'Gambar Instalasi Perpipaan (Plumbing)', 'Gambar_Plumbing_Eko_Prasetyo.jpg', 'dummy_lengkap/plumbing.jpg'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_pbg_dokumen` WHERE `id_pengajuan` = (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007') AND `jenis_dokumen` = 'Gambar Instalasi Perpipaan (Plumbing)'
);

INSERT INTO `pengajuan_pbg_dokumen` (`id_pengajuan`, `jenis_dokumen`, `nama_file_asli`, `path_file`)
SELECT (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007'),
  'Sistem Proteksi Kebakaran', 'Proteksi_Kebakaran_Eko_Prasetyo.jpg', 'dummy_lengkap/proteksi_kebakaran.jpg'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_pbg_dokumen` WHERE `id_pengajuan` = (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007') AND `jenis_dokumen` = 'Sistem Proteksi Kebakaran'
);

INSERT INTO `pengajuan_pbg_dokumen` (`id_pengajuan`, `jenis_dokumen`, `nama_file_asli`, `path_file`)
SELECT (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007'),
  'Dokumen pendukung lain (opsional)', 'Dokumen_Tambahan_Eko_Prasetyo.jpg', 'dummy_lengkap/tambahan.jpg'
WHERE NOT EXISTS (
  SELECT 1 FROM `pengajuan_pbg_dokumen` WHERE `id_pengajuan` = (SELECT id FROM `pengajuan_pbg` WHERE `nik_pemohon` = '3301031207880007') AND `jenis_dokumen` = 'Dokumen pendukung lain (opsional)'
);
