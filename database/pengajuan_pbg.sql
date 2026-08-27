-- Migrasi untuk fitur "Pengajuan PBG" di dashboard PU. Jalankan lewat
-- phpMyAdmin -> pilih database Anda -> tab SQL -> paste semua isi file
-- ini -> Go. Aman dijalankan berkali-kali (CREATE TABLE IF NOT EXISTS).
--
-- TAHAP AWAL: mengikuti alur "PENGISIAN TYPEFORM" + "FORMULIR DOKUMEN
-- TANAH BANGUNAN" + "UNGGAH DOKUMEN TEKNIS" pada Panduan Permohonan
-- PBG (Kementerian PU, via aplikasi SIMBG) sampai status permohonan
-- terkirim untuk diverifikasi. Alur lanjutan (perbaikan dokumen,
-- konsultasi, ubah data tanah) BELUM tercakup di sini - menyusul di
-- pembaruan berikutnya, kemungkinan besar sebagai nilai ENUM `status`
-- tambahan (bukan mengganti yang sudah ada) supaya baris lama tidak
-- pernah rusak - mengikuti pola yang sama seperti perluasan peran TPA
-- di database/tambah_tpa_spesialis.sql.
--
-- Berbeda dari fitur "Pengajuan" yang sempat dibuat lalu dibatalkan
-- (lihat riwayat git) - permohonan di sini SENGAJA tidak dibuatkan
-- tabel "pemohon" terpisah. Warga yang mengajukan datang langsung ke
-- loket PU (bukan login sendiri), jadi identitasnya cukup disimpan
-- sebagai kolom pada baris permohonan itu sendiri - sama seperti pola
-- yang sudah dipakai tabel `pengajuan_itr` di database/gatutkaca.sql.

CREATE TABLE IF NOT EXISTS `pengajuan_pbg` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `dibuat_oleh` INT UNSIGNED NOT NULL COMMENT 'users.id staf PU yang menginput permohonan ini di loket',
  `no_registrasi` VARCHAR(50) DEFAULT NULL COMMENT 'Diisi otomatis saat status berubah dari draf ke verifikasi_dokumen',
  `status` ENUM('draf','verifikasi_dokumen') NOT NULL DEFAULT 'draf',

  -- Data pemohon (warga yang datang ke loket - bukan akun sistem)
  `nama_pemohon` VARCHAR(150) NOT NULL,
  `nik_pemohon` VARCHAR(20) DEFAULT NULL,
  `kontak_pemohon` VARCHAR(100) DEFAULT NULL,

  -- Intensitas Pemanfaatan Ruang
  `intensitas_ada` ENUM('ya','tidak') DEFAULT NULL,
  `intensitas_no_dokumen` VARCHAR(100) DEFAULT NULL,
  `intensitas_gsb` VARCHAR(30) DEFAULT NULL,
  `intensitas_kdb` VARCHAR(30) DEFAULT NULL,
  `intensitas_klb` VARCHAR(30) DEFAULT NULL,
  `intensitas_kdh` VARCHAR(30) DEFAULT NULL,

  -- Lokasi bangunan
  `lokasi_provinsi` VARCHAR(100) DEFAULT NULL,
  `lokasi_kabupaten` VARCHAR(100) DEFAULT NULL,
  `lokasi_kecamatan` VARCHAR(100) DEFAULT NULL,
  `lokasi_kelurahan` VARCHAR(100) DEFAULT NULL,
  `lokasi_alamat` TEXT,

  -- Kepemilikan tanah & bangunan
  `jumlah_bukti_tanah` INT UNSIGNED DEFAULT NULL,
  `kepemilikan_bangunan` ENUM('perorangan','badan_hukum_usaha','pemerintah') DEFAULT NULL,
  `kondisi_bangunan` ENUM('sudah_ada','belum_berdiri','sedang_dibangun','renovasi','perpanjangan_slf') DEFAULT NULL,

  -- Desain prototipe
  `pakai_prototipe` ENUM('ya','tidak') DEFAULT NULL,
  `prototipe_jumlah_unit` INT UNSIGNED DEFAULT NULL,
  `prototipe_latitude` VARCHAR(50) DEFAULT NULL,
  `prototipe_longitude` VARCHAR(50) DEFAULT NULL,
  `prototipe_jenis` VARCHAR(150) DEFAULT NULL,
  `prototipe_peta` VARCHAR(255) DEFAULT NULL COMMENT 'Path relatif file di application/uploads/pengajuan_pbg/',
  `masa_pemanfaatan` ENUM('lebih_5_tahun','kurang_5_tahun') DEFAULT NULL COMMENT 'Cuma diisi kalau tidak pakai prototipe',

  -- Fungsi bangunan (checkbox multi-pilih + sub-fungsi per kategori
  -- terpilih) - disimpan sebagai ringkasan teks "Kategori: Sub-fungsi"
  -- per baris, bukan tabel anak terpisah, supaya tahap awal ini tetap
  -- sederhana untuk ditampilkan maupun diisi ulang.
  `fungsi_bangunan` TEXT,

  -- Data bangunan
  `punya_basemen` ENUM('ya','tidak') DEFAULT NULL,
  `bangunan_nama` VARCHAR(150) DEFAULT NULL,
  `bangunan_luas_per_unit` VARCHAR(30) DEFAULT NULL,
  `bangunan_tinggi` VARCHAR(30) DEFAULT NULL,
  `bangunan_jumlah_lantai` INT UNSIGNED DEFAULT NULL,
  `bangunan_luas_basemen` VARCHAR(30) DEFAULT NULL,
  `bangunan_jumlah_lapis_basemen` INT UNSIGNED DEFAULT NULL,
  `bangunan_jumlah_unit` INT UNSIGNED DEFAULT NULL,
  `bangunan_estimasi_penghuni` INT UNSIGNED DEFAULT NULL,
  `bangunan_latitude` VARCHAR(50) DEFAULT NULL,
  `bangunan_longitude` VARCHAR(50) DEFAULT NULL,
  `bangunan_peta` VARCHAR(255) DEFAULT NULL COMMENT 'Path relatif file di application/uploads/pengajuan_pbg/',

  -- Formulir Dokumen Tanah Bangunan
  `tanah_jenis_dokumen` VARCHAR(100) DEFAULT NULL,
  `tanah_nomor_dokumen` VARCHAR(100) DEFAULT NULL,
  `tanah_tanggal_terbit` DATE DEFAULT NULL,
  `tanah_luas` VARCHAR(30) DEFAULT NULL,
  `tanah_hak_kepemilikan` VARCHAR(100) DEFAULT NULL,
  `tanah_nama_pemilik` VARCHAR(150) DEFAULT NULL,
  `tanah_lampiran` VARCHAR(255) DEFAULT NULL COMMENT 'Path relatif file di application/uploads/pengajuan_pbg/',
  `tanah_provinsi` VARCHAR(100) DEFAULT NULL,
  `tanah_kabupaten` VARCHAR(100) DEFAULT NULL,
  `tanah_kecamatan` VARCHAR(100) DEFAULT NULL,
  `tanah_kelurahan` VARCHAR(100) DEFAULT NULL,
  `tanah_alamat` TEXT,
  `tanah_pemilik_sama` ENUM('sama','tidak') DEFAULT NULL,
  `tanah_nomor_izin` VARCHAR(100) DEFAULT NULL,
  `tanah_tanggal_izin` DATE DEFAULT NULL,

  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_dibuat_oleh` (`dibuat_oleh`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_pengajuan_pbg_user` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dokumen teknis yang diunggah untuk satu permohonan (Data Umum, Data
-- Teknis Arsitektur, dan dokumen tambahan opsional). Satu baris per
-- berkas - beda dari kolom *_peta/*_lampiran di atas (yang memang
-- cuma satu berkas tetap per field), tabel ini menampung daftar
-- berkas checklist yang jumlah jenisnya bisa bertambah di pembaruan
-- berikutnya tanpa perlu ALTER TABLE.
CREATE TABLE IF NOT EXISTS `pengajuan_pbg_dokumen` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pengajuan` INT UNSIGNED NOT NULL,
  `jenis_dokumen` VARCHAR(150) NOT NULL,
  `nama_file_asli` VARCHAR(255) NOT NULL,
  `path_file` VARCHAR(255) NOT NULL COMMENT 'Path relatif di application/uploads/pengajuan_pbg/',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_id_pengajuan` (`id_pengajuan`),
  CONSTRAINT `fk_pengajuan_pbg_dokumen` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_pbg` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
