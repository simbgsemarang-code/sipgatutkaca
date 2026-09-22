-- Alur review per-berkas ITR: admin menerima/menolak tiap lampiran
-- satu-satu (bukan cuma status keseluruhan pengajuan), pemohon bisa
-- upload ulang berkas yang ditolak, dan setelah SEMUA berkas wajib
-- diterima, admin mengunggah dokumen hasil ITR (PDF) yang bisa
-- diunduh pemohon.

CREATE TABLE IF NOT EXISTS `pengajuan_itr_berkas_status` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pengajuan_id` INT UNSIGNED NOT NULL,
  `field` VARCHAR(50) NOT NULL COMMENT 'nama kolom berkas, mis. file_ktp',
  `status` ENUM('menunggu','diterima','ditolak') NOT NULL DEFAULT 'menunggu',
  `catatan` TEXT NULL COMMENT 'alasan wajib diisi kalau ditolak',
  `ditinjau_oleh` INT UNSIGNED NULL,
  `ditinjau_pada` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pengajuan_field` (`pengajuan_id`,`field`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `pengajuan_itr`
  ADD COLUMN IF NOT EXISTS `file_hasil_itr` VARCHAR(255) NULL COMMENT 'PDF hasil ITR resmi, diunggah admin setelah semua berkas diterima' AFTER `file_akta`,
  ADD COLUMN IF NOT EXISTS `hasil_diunggah_pada` DATETIME NULL AFTER `file_hasil_itr`;
