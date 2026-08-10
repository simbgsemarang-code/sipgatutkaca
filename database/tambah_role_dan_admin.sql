-- Migrasi untuk database yang SUDAH terpasang di server (jalankan lewat
-- phpMyAdmin -> pilih database Anda -> tab SQL -> paste semua isi file
-- ini -> Go). Aman dijalankan berkali-kali (idempotent untuk ALTER-nya;
-- INSERT admin di bawah hanya akan gagal diam-diam kalau email-nya
-- sudah pernah dipakai, karena kolom email UNIQUE).

-- 1. Tambah pilihan peran 'pu' dan 'tpa' (sebelumnya cuma pemohon/admin)
ALTER TABLE `users`
  MODIFY COLUMN `role` ENUM('admin','pu','tpa','pemohon') NOT NULL DEFAULT 'pemohon';

-- 2. Buat SATU akun admin awal supaya ada yang bisa login pertama kali
--    dan menambahkan pengguna lain lewat halaman /admin/pengguna.
--
--    Email default: admin@sipgatutkaca.local
--    Password awal : f0250dc5621e
--
--    GANTI baris email di bawah ini kalau mau pakai email lain, SEBELUM
--    menjalankan query ini. Setelah bisa login, sebaiknya buat akun
--    admin baru dengan email/password pilihan Anda sendiri lewat
--    halaman /admin/pengguna, lalu hapus akun default ini.
INSERT INTO `users` (`nik`, `nama`, `email`, `password`, `role`)
VALUES (
  NULL,
  'Administrator',
  'admin@sipgatutkaca.local',
  '$2y$12$y53ifueBYJKw7h6dXj9pE.plaoLWCtZAftecDR23taLTCaJEKCn.i',
  'admin'
);
