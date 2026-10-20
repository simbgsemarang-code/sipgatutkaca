-- Massa bangunan: satu bangunan (bangunan_gis) dapat terdiri dari beberapa massa/blok
-- (mis. Massa A, Massa B) yang masing-masing punya jumlah lantai, luas, dan kondisi sendiri.
-- Tabel ini juga dibuat otomatis saat admin pertama kali membuka halaman Massa Bangunan
-- (bila akun database punya hak CREATE); jalankan manual hanya jika muncul pesan tabel belum ada.
CREATE TABLE IF NOT EXISTS `bangunan_massa` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `bangunan_id` INT UNSIGNED NOT NULL,
  `nama_massa` VARCHAR(150) NOT NULL,
  `jumlah_lantai` SMALLINT UNSIGNED NULL,
  `luas_m2` DECIMAL(12,2) NULL,
  `kondisi` ENUM('1','2','3','4') NOT NULL DEFAULT '1',
  `tahun_dibangun` SMALLINT UNSIGNED NULL,
  `keterangan` VARCHAR(255) NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bangunan_id` (`bangunan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
