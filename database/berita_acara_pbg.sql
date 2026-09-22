-- Nomor surat Berita Acara Konsultasi TPA, dibuat sekali per (permohonan, putaran)
-- lalu dipakai ulang setiap kali PU/TPA mengunduh BA yang sama supaya nomornya stabil.
CREATE TABLE IF NOT EXISTS `berita_acara_pbg` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `permohonan_id` INT UNSIGNED NOT NULL,
  `putaran` SMALLINT UNSIGNED NOT NULL,
  `nomor` VARCHAR(60) NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permohonan_putaran` (`permohonan_id`,`putaran`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
