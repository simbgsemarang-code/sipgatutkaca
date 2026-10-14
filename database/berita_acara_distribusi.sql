-- Berita acara DISTRIBUSI TUGAS ke TPA: terbit saat PU membagikan tugas review
-- (satu per putaran per permohonan). Tanggalnya bisa diubah PU. Isi berita
-- acara (daftar TPA + saran) dibaca langsung dari konsultasi_pbg saat dibuka.
-- Terpisah dari berita_acara_pbg (BA hasil review per-TPA) supaya tidak
-- mengganggu alur lama. Aman dijalankan berulang.
-- Kalau tabel ini belum ada, fitur BA distribusi otomatis tersembunyi dan
-- pembagian tugas ke TPA tetap berjalan seperti biasa.
CREATE TABLE IF NOT EXISTS `berita_acara_distribusi` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `permohonan_id` INT UNSIGNED NOT NULL,
  `putaran` SMALLINT UNSIGNED NOT NULL,
  `nomor` VARCHAR(60) NOT NULL,
  `tanggal_ba` DATE NOT NULL,
  `dibuat_oleh` INT UNSIGNED NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_permohonan_putaran` (`permohonan_id`,`putaran`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
