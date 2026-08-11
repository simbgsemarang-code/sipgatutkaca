-- Migrasi untuk fitur "Pengajuan" (data pemohon PBG/SLF). Jalankan
-- lewat phpMyAdmin -> pilih database Anda -> tab SQL -> paste semua
-- isi file ini -> Go. Aman dijalankan berkali-kali (CREATE TABLE IF
-- NOT EXISTS).
--
-- Kolom-kolomnya mengikuti "DATA UMUM" pada persyaratan PBG (Peraturan
-- Bupati Cilacap Nomor 52 Tahun 2023): identitas pemilik, data
-- intensitas bangunan (KKPR/KRK/ITR), bukti kepemilikan tanah, dan
-- data penyedia jasa perencana/arsitek berlisensi. Berkas "DATA
-- TEKNIS" (gambar teknis, RAB, dst - lihat gambar persyaratan) berupa
-- dokumen/lampiran dan BELUM ditampung di sini - fitur unggah berkas
-- belum ada di aplikasi ini, jadi kolomnya sengaja tidak dibuat dulu
-- supaya tidak ada field yang terlihat "ada" padahal tidak benar2
-- dipakai. Bisa ditambahkan lewat migrasi terpisah kalau fitur unggah
-- berkas sudah dibangun.
CREATE TABLE IF NOT EXISTS `pemohon` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  -- Akun pemohon yang mengajukan (pemilik data ini).
  `id_user` INT UNSIGNED NOT NULL,
  -- Staf PU/TPA yang menangani pengajuan ini. NULL = belum ditugaskan
  -- siapa pun. Sengaja mengarah ke users.id (bukan tabel konsultan
  -- lama yang sudah tidak dipakai) karena akun PU/TPA di aplikasi ini
  -- memang baris di tabel users dengan role pu/tpa.
  `id_konsultan` INT UNSIGNED DEFAULT NULL,
  `jenis_layanan` ENUM('pbg','slf') NOT NULL,
  `jenis_bangunan` ENUM('hunian','non_hunian') NOT NULL DEFAULT 'hunian',
  `nama_pemohon` VARCHAR(150) NOT NULL,
  `nik_ktp` VARCHAR(30) NOT NULL COMMENT 'Data Identitas Pemilik Bangunan - KTP/KITAS',
  `nib` VARCHAR(30) DEFAULT NULL COMMENT 'Wajib untuk bangunan umum non hunian & campuran',
  `alamat_bangunan` VARCHAR(255) NOT NULL,
  `no_kkpr_krk` VARCHAR(100) DEFAULT NULL COMMENT 'Data Intensitas Bangunan (KKPR/KRK) / Informasi Tata Ruang (ITR)',
  `bukti_tanah` VARCHAR(150) DEFAULT NULL COMMENT 'Sertifikat Tanah/Girik/Letter C',
  `no_sppt_nop` VARCHAR(100) DEFAULT NULL COMMENT 'SPPT / Keterangan NOP',
  `nama_perencana` VARCHAR(150) DEFAULT NULL COMMENT 'Penyedia Jasa Perencana Konstruksi / Arsitek',
  `no_lisensi_perencana` VARCHAR(100) DEFAULT NULL COMMENT 'SKK/STRA/STRI',
  `luas_bangunan` DECIMAL(10,2) DEFAULT NULL COMMENT 'meter persegi',
  `jumlah_lantai` TINYINT UNSIGNED DEFAULT NULL,
  `status` ENUM('diajukan','diverifikasi','revisi','disetujui','ditolak') NOT NULL DEFAULT 'diajukan',
  `catatan` TEXT DEFAULT NULL COMMENT 'Catatan dari PU/TPA - diisi lewat proses tinjau, bukan oleh pemohon',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_id_user` (`id_user`),
  KEY `idx_id_konsultan` (`id_konsultan`),
  CONSTRAINT `fk_pemohon_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pemohon_konsultan` FOREIGN KEY (`id_konsultan`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
