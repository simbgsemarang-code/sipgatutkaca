-- Link Google Drive berkas perbaikan PBG: saat ada TPA yang meminta perbaikan,
-- PU mengirim link Google Drive (satu atau lebih) untuk putaran itu. Riwayat
-- disimpan per putaran (append-only) dan ditampilkan ke PU dan TPA.
-- `putaran` = putaran review yang meminta perbaikan (link dipakai untuk putaran
-- berikutnya). Aman dijalankan berulang. Kalau tabel belum ada, halaman PU
-- tetap memakai alur lama (unggah berkas perbaikan).
CREATE TABLE IF NOT EXISTS `link_perbaikan_pbg` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `permohonan_id` INT UNSIGNED NOT NULL,
  `putaran` SMALLINT UNSIGNED NOT NULL,
  `url` VARCHAR(500) NOT NULL,
  `keterangan` VARCHAR(255) NULL,
  `dibuat_oleh` INT UNSIGNED NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `permohonan_putaran` (`permohonan_id`,`putaran`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
