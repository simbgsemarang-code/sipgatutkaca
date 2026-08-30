-- Pengelolaan Saran & Masukan oleh admin + tabel FAQ publik.
-- Jalankan lewat phpMyAdmin -> pilih database -> tab SQL / Import.
-- Aman dijalankan ulang.

-- 1) Kolom catatan internal admin pada saran_masukan (kalau belum ada).
SET @c := (SELECT COUNT(*) FROM information_schema.COLUMNS
           WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'saran_masukan' AND COLUMN_NAME = 'catatan');
SET @sql := IF(@c = 0, 'ALTER TABLE `saran_masukan` ADD COLUMN `catatan` TEXT NULL AFTER `status`', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 2) Tabel FAQ yang tampil di halaman /saran-masukan, dikelola admin.
CREATE TABLE IF NOT EXISTS `faq` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pertanyaan` VARCHAR(255) NOT NULL,
  `jawaban` TEXT NOT NULL,
  `urutan` INT NOT NULL DEFAULT 0,
  `tampil` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 = tampil di halaman publik',
  `sumber_saran_id` INT UNSIGNED DEFAULT NULL COMMENT 'id saran_masukan asal, jika dibuat dari suatu saran',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_faq_urutan` (`urutan`),
  KEY `idx_faq_tampil` (`tampil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3) Seed 5 FAQ awal (hanya kalau tabel masih kosong).
INSERT INTO `faq` (`pertanyaan`, `jawaban`, `urutan`)
SELECT `pertanyaan`, `jawaban`, `urutan` FROM (
  SELECT 'Kapan masukan saya ditinjau?' AS `pertanyaan`,
         'Masukan ditinjau pada hari kerja, Senin sampai Jumat pukul 08.00 hingga 15.30 WIB.' AS `jawaban`, 1 AS `urutan`
  UNION ALL SELECT 'Apakah data kontak saya dipublikasikan?',
         'Tidak. Data kontak Anda hanya digunakan untuk menindaklanjuti masukan dan tidak dipublikasikan.', 2
  UNION ALL SELECT 'Saya punya pengaduan teknis soal permohonan PBG/SLF, ke mana?',
         'Gunakan menu Konsultasi agar permohonan PBG/SLF Anda diproses oleh tim yang sesuai.', 3
  UNION ALL SELECT 'Apakah nama, email, dan nomor HP wajib diisi?',
         'Hanya Nama dan Saran / Masukan yang wajib. Email serta No. HP / WhatsApp bersifat opsional, namun membantu tim menghubungi Anda bila perlu klarifikasi.', 4
  UNION ALL SELECT 'Apakah saya akan mendapat balasan?',
         'Tim DPUPR Kabupaten Cilacap menindaklanjuti masukan yang memerlukan tanggapan melalui kontak yang Anda berikan.', 5
) seed
WHERE (SELECT COUNT(*) FROM `faq`) = 0;
