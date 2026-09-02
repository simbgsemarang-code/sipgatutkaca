-- Penyelarasan alur Pengajuan PBG dengan mekanisme SI DAI TERBANG.
-- Data permohonan lama tidak dihapus. Jalankan sekali setelah backup database.

ALTER TABLE `pengajuan_pbg`
  MODIFY COLUMN `status` ENUM(
    'draf','verifikasi_dokumen','perbaikan_dokumen',
    'perbaikan_dokumen_konsultasi','menunggu_jadwal_konsultasi',
    'disetujui_tpa','ditolak'
  ) NOT NULL DEFAULT 'draf',
  ADD COLUMN IF NOT EXISTS `catatan_admin` TEXT DEFAULT NULL AFTER `catatan_tpa`,
  ADD COLUMN IF NOT EXISTS `updated_at` DATETIME DEFAULT NULL AFTER `created_at`;

CREATE TABLE IF NOT EXISTS `pengajuan_pbg_riwayat` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pengajuan` INT UNSIGNED NOT NULL,
  `status_lama` VARCHAR(50) DEFAULT NULL,
  `status_baru` VARCHAR(50) NOT NULL,
  `tahap` TINYINT UNSIGNED NOT NULL,
  `keterangan` TEXT DEFAULT NULL,
  `diubah_oleh` INT UNSIGNED DEFAULT NULL,
  `diubah_pada` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `riwayat_pengajuan` (`id_pengajuan`,`diubah_pada`),
  CONSTRAINT `fk_pbg_riwayat_pengajuan` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_pbg` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pbg_riwayat_user` FOREIGN KEY (`diubah_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

