-- Migrasi untuk memecah peran "TPA" menjadi 3 spesialisasi. Jalankan
-- lewat phpMyAdmin -> pilih database Anda -> tab SQL -> paste semua
-- isi file ini -> Go. Aman dijalankan berkali-kali (MODIFY COLUMN ke
-- definisi yang sama tidak merusak apa pun).
--
-- 'tpa' (generik) SENGAJA TETAP ada di daftar ENUM di bawah, TIDAK
-- dihapus - supaya akun lama yang masih berperan 'tpa' (kalau ada)
-- tidak tiba-tiba jadi nilai yang tidak sah/rusak. Yang berubah
-- hanya: pengguna BARU tidak bisa lagi dibuat dengan peran 'tpa'
-- generik lewat halaman /admin/pengguna (lihat perubahan kode di
-- Admin.php) - harus pilih salah satu dari 3 spesialisasi baru.
ALTER TABLE `users`
  MODIFY COLUMN `role` ENUM('admin','pu','tpa','pemohon','tpa_arsitek','tpa_struktur','tpa_mep') NOT NULL DEFAULT 'pemohon';
