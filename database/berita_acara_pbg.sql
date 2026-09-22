-- Berita Acara Konsultasi TPA: satu baris TERBIT setiap kali SATU TPA
-- menyelesaikan review (konsultasi_pbg.id tertentu berubah dari status
-- 'ditugaskan'), bukan menunggu ketiga bidang selesai. Nomor dan isi
-- (snapshot saran-masukan tiap bidang per saat itu) dikunci sekali saat
-- pertama diterbitkan supaya BA lama tidak berubah kalau ada review baru.
CREATE TABLE IF NOT EXISTS `berita_acara_pbg` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `konsultasi_id` INT UNSIGNED NOT NULL COMMENT 'konsultasi_pbg.id yang memicu terbitnya BA ini',
  `permohonan_id` INT UNSIGNED NOT NULL,
  `nomor` VARCHAR(60) NOT NULL,
  `diterbitkan_at` DATETIME NOT NULL COMMENT 'Waktu TPA pemicu menyelesaikan review (konsultasi_pbg.reviewed_at)',
  `snapshot` TEXT NOT NULL COMMENT 'JSON: per bidang -> {nama_tpa,rekomendasi,reviewed_at} atau null',
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `konsultasi_unique` (`konsultasi_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
