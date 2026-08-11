-- Migrasi untuk fitur "Lupa Kata Sandi". Jalankan lewat phpMyAdmin ->
-- pilih database Anda -> tab SQL -> paste semua isi file ini -> Go.
-- Aman dijalankan berkali-kali (CREATE TABLE IF NOT EXISTS).

-- Menyimpan tautan atur ulang kata sandi yang sedang berlaku. Yang
-- disimpan cuma HASH dari token (SHA-256), bukan token mentahnya -
-- supaya kalau tabel ini bocor, isinya tidak langsung bisa dipakai
-- untuk mengambil alih akun siapa pun.
CREATE TABLE IF NOT EXISTS `reset_password` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `token_hash` VARCHAR(64) NOT NULL,
  `kedaluwarsa` DATETIME NOT NULL,
  `dipakai_pada` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_token_hash` (`token_hash`),
  CONSTRAINT `fk_reset_password_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
