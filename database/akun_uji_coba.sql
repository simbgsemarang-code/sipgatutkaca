-- Akun UJI COBA untuk peran pu, tpa, dan pemohon (peran admin sudah
-- ada duluan dari database/tambah_role_dan_admin.sql, dipakai ulang).
-- Jalankan lewat phpMyAdmin -> pilih database Anda -> tab SQL -> paste
-- semua isi file ini -> Go.
--
-- Aman dijalankan berkali-kali - INSERT di bawah cuma akan gagal diam
-- -diam kalau email-nya sudah pernah dipakai (kolom email UNIQUE).
--
-- Ini akun UJI COBA, bukan untuk dipakai produksi sungguhan - setelah
-- selesai uji coba, sebaiknya dihapus lewat halaman /admin/pengguna
-- atau lewat query DELETE manual.

INSERT INTO `users` (`nik`, `nama`, `email`, `password`, `role`) VALUES
  (NULL, 'Uji Coba PU', 'pu.uji@sipgatutkaca.local', '$2y$12$37OBk0CjdWaWtulQatiXv.dSD5kfd742cAbLJmGj5ZpJlimk3WtU.', 'pu'),
  (NULL, 'Uji Coba TPA', 'tpa.uji@sipgatutkaca.local', '$2y$12$GjElyhNoN8uPw1Q1izr0DeO/eBGEY2Y6hwdtAz756fdh80xGbwiG6', 'tpa'),
  (NULL, 'Uji Coba Pemohon', 'pemohon.uji@sipgatutkaca.local', '$2y$12$E3eIivhnW59fPDgFmK3f5uW/zUrOtXPlJEiv/DsOwNxytetcGwOve', 'pemohon');

-- Kata sandi masing-masing (SEBELUM di-hash, di atas sudah dalam
-- bentuk bcrypt hash siap pakai):
--   pu.uji@sipgatutkaca.local       / 1965ad22f258
--   tpa.uji@sipgatutkaca.local      / 309997a80684
--   pemohon.uji@sipgatutkaca.local  / 092d2a5cd461
