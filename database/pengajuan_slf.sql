-- Migrasi untuk fitur "Pengajuan SLF" (Sertifikat Laik Fungsi) di
-- dashboard PU. Jalankan lewat phpMyAdmin -> pilih database Anda ->
-- tab SQL -> paste semua isi file ini -> Go. Aman dijalankan
-- berkali-kali (CREATE TABLE IF NOT EXISTS).
--
-- TAHAP 1 (file ini): pengajuan SLF oleh PU di loket + verifikasi
-- kelengkapan dokumen oleh TPA per bidang - MENGIKUTI POLA PERSIS
-- database/pengajuan_pbg.sql beserta migrasi lanjutannya
-- (pengajuan_pbg_reviewer.sql, pengajuan_pbg_persetujuan_tpa.sql,
-- pengajuan_pbg_perbaikan.sql), tapi digabung jadi satu CREATE TABLE
-- utuh di sini karena tabel SLF memang baru. Kolom, ENUM status, dan
-- alur transisinya identik dengan PBG.
--
-- Tahap lanjutan SLF (unggah jadwal konstruksi, unggah/perbaikan
-- dokumen akhir, penerbitan SK SLF + kode unik - lihat Panduan
-- Permohonan SLF SIMBG) BELUM tercakup di sini, menyusul sebagai
-- nilai ENUM `status` tambahan (bukan mengganti yang sudah ada).
--
-- Seperti pengajuan_pbg, permohonan SLF SENGAJA tidak dibuatkan tabel
-- "pemohon" terpisah - warga datang langsung ke loket PU, identitasnya
-- disimpan sebagai kolom pada baris permohonan.

CREATE TABLE IF NOT EXISTS `pengajuan_slf` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `dibuat_oleh` INT UNSIGNED NOT NULL COMMENT 'users.id staf PU yang menginput permohonan ini di loket',
  `no_registrasi` VARCHAR(50) DEFAULT NULL COMMENT 'Diisi otomatis saat status berubah dari draf ke verifikasi_dokumen',
  `status` ENUM('draf','verifikasi_dokumen','perbaikan_dokumen','perbaikan_dokumen_konsultasi','menunggu_jadwal_konsultasi','disetujui_tpa') NOT NULL DEFAULT 'draf',

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
  `prototipe_peta` VARCHAR(255) DEFAULT NULL COMMENT 'Path relatif file di application/uploads/pengajuan_slf/',
  `masa_pemanfaatan` ENUM('lebih_5_tahun','kurang_5_tahun') DEFAULT NULL,

  -- Fungsi bangunan (ringkasan teks "Kategori: Sub-fungsi" per baris)
  `fungsi_bangunan` TEXT,

  -- Peninjauan TPA (lihat Tpa_pengajuan_slf)
  `catatan_tpa` TEXT DEFAULT NULL,
  `ditinjau_oleh` INT UNSIGNED DEFAULT NULL COMMENT 'users.id staf TPA yang terakhir meninjau',
  `ditinjau_pada` DATETIME DEFAULT NULL,
  `reviewer_arsitek_id` INT UNSIGNED DEFAULT NULL COMMENT 'users.id staf TPA Arsitek yang ditugaskan PU - NULL berarti belum ditugaskan (semua akun tpa_arsitek boleh akses)',
  `reviewer_struktur_id` INT UNSIGNED DEFAULT NULL COMMENT 'users.id staf TPA Struktur yang ditugaskan - lihat catatan reviewer_arsitek_id',
  `reviewer_mep_id` INT UNSIGNED DEFAULT NULL COMMENT 'users.id staf TPA MEP yang ditugaskan - lihat catatan reviewer_arsitek_id',

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
  `bangunan_peta` VARCHAR(255) DEFAULT NULL COMMENT 'Path relatif file di application/uploads/pengajuan_slf/',

  -- Formulir Dokumen Tanah Bangunan
  `tanah_jenis_dokumen` VARCHAR(100) DEFAULT NULL,
  `tanah_nomor_dokumen` VARCHAR(100) DEFAULT NULL,
  `tanah_tanggal_terbit` DATE DEFAULT NULL,
  `tanah_luas` VARCHAR(30) DEFAULT NULL,
  `tanah_hak_kepemilikan` VARCHAR(100) DEFAULT NULL,
  `tanah_nama_pemilik` VARCHAR(150) DEFAULT NULL,
  `tanah_lampiran` VARCHAR(255) DEFAULT NULL COMMENT 'Path relatif file di application/uploads/pengajuan_slf/',
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
  KEY `idx_slf_dibuat_oleh` (`dibuat_oleh`),
  KEY `idx_slf_status` (`status`),
  KEY `fk_pengajuan_slf_ditinjau_oleh` (`ditinjau_oleh`),
  KEY `fk_pengajuan_slf_reviewer_arsitek` (`reviewer_arsitek_id`),
  KEY `fk_pengajuan_slf_reviewer_struktur` (`reviewer_struktur_id`),
  KEY `fk_pengajuan_slf_reviewer_mep` (`reviewer_mep_id`),
  CONSTRAINT `fk_pengajuan_slf_user` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_pengajuan_slf_ditinjau_oleh` FOREIGN KEY (`ditinjau_oleh`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_pengajuan_slf_reviewer_arsitek` FOREIGN KEY (`reviewer_arsitek_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_pengajuan_slf_reviewer_struktur` FOREIGN KEY (`reviewer_struktur_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_pengajuan_slf_reviewer_mep` FOREIGN KEY (`reviewer_mep_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dokumen teknis yang diunggah untuk satu permohonan SLF.
CREATE TABLE IF NOT EXISTS `pengajuan_slf_dokumen` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pengajuan` INT UNSIGNED NOT NULL,
  `jenis_dokumen` VARCHAR(150) NOT NULL,
  `nama_file_asli` VARCHAR(255) NOT NULL,
  `path_file` VARCHAR(255) NOT NULL COMMENT 'Path relatif di application/uploads/pengajuan_slf/',
  `status` ENUM('terunggah','ditolak') NOT NULL DEFAULT 'terunggah',
  `catatan_penolakan` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_slf_dok_id_pengajuan` (`id_pengajuan`),
  CONSTRAINT `fk_pengajuan_slf_dokumen` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_slf` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Persetujuan TPA per bidang (satu baris per bidang per permohonan).
-- Status pengajuan_slf.status DITURUNKAN dari isi tabel ini lewat
-- Tpa_pengajuan_slf::_hitung_status_keseluruhan() - pola sama persis
-- dengan pengajuan_pbg_persetujuan_tpa.
CREATE TABLE IF NOT EXISTS `pengajuan_slf_persetujuan_tpa` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pengajuan` INT UNSIGNED NOT NULL,
  `bidang` ENUM('tpa_arsitek','tpa_struktur','tpa_mep') NOT NULL,
  `status` ENUM('disetujui','perbaikan_dokumen','perbaikan_dokumen_konsultasi') NOT NULL,
  `catatan` TEXT DEFAULT NULL,
  `ditinjau_oleh` INT UNSIGNED DEFAULT NULL,
  `ditinjau_pada` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_slf_pengajuan_bidang` (`id_pengajuan`,`bidang`),
  KEY `idx_slf_persetujuan_id_pengajuan` (`id_pengajuan`),
  KEY `fk_slf_persetujuan_tpa_user` (`ditinjau_oleh`),
  CONSTRAINT `fk_slf_persetujuan_tpa_pengajuan` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_slf` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_slf_persetujuan_tpa_user` FOREIGN KEY (`ditinjau_oleh`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
