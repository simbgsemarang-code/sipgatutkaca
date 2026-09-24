-- Saklar admin untuk menampilkan/menyembunyikan panel kredensial akun
-- uji coba di halaman login publik (lihat Login::_akun_uji_untuk() dan
-- Admin::pengaturan_login()). Satu baris saja (id selalu 1).
-- Kalau tabel ini belum ada / belum ada barisnya, perilaku lama tetap
-- jalan (panel tampil apa adanya selama ENVIRONMENT development).
CREATE TABLE IF NOT EXISTS `pengaturan_login` (
  `id` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `tampilkan_akun_uji` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
