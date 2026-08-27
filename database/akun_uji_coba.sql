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
  (NULL, 'Ahmad Wijaya', 'ahmad.wijaya@sipgatutkaca.local', '$2y$12$HOB7fUciCO5hJywRSKRiBe7ucnZ9rsDc9S2ATJOZzJ8ZtfOIro2pW', 'pu'),
  (NULL, 'Siti Rahmawati', 'siti.rahmawati@sipgatutkaca.local', '$2y$12$S2YPBKKXMsY5NMXwbMi6SeeDrT/AXZw0N4UFmU7SBgAHOU97sJpk2', 'pu'),
  (NULL, 'Uji Coba TPA', 'tpa.uji@sipgatutkaca.local', '$2y$12$GjElyhNoN8uPw1Q1izr0DeO/eBGEY2Y6hwdtAz756fdh80xGbwiG6', 'tpa'),
  (NULL, 'Uji Coba Pemohon', 'pemohon.uji@sipgatutkaca.local', '$2y$12$E3eIivhnW59fPDgFmK3f5uW/zUrOtXPlJEiv/DsOwNxytetcGwOve', 'pemohon');

-- Kata sandi masing-masing (SEBELUM di-hash, di atas sudah dalam
-- bentuk bcrypt hash siap pakai):
--   ahmad.wijaya@sipgatutkaca.local    / b596c84a9d7a
--   siti.rahmawati@sipgatutkaca.local  / e2160c77feb5
--   tpa.uji@sipgatutkaca.local         / 309997a80684
--   pemohon.uji@sipgatutkaca.local     / 092d2a5cd461
