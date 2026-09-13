-- Penilaian kesesuaian berkas oleh PU. Aman diimpor berulang kali.
CREATE TABLE IF NOT EXISTS `pbg_penilaian_dokumen` (
  `permohonan_id` INT UNSIGNED NOT NULL,
  `field_dokumen` VARCHAR(64) NOT NULL,
  `nama_file` VARCHAR(255) NOT NULL,
  `status` ENUM('sesuai','tidak_sesuai') NOT NULL,
  `pu_id` INT UNSIGNED NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`permohonan_id`, `field_dokumen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
