-- Akun UJI COBA untuk peran pu, tpa_arsitek, tpa_struktur, tpa_mep,
-- dan pemohon (peran admin sudah ada duluan dari
-- database/tambah_role_dan_admin.sql, dipakai ulang).
--
-- WAJIB jalankan database/tambah_tpa_spesialis.sql LEBIH DULU - file
-- itu yang menambahkan tpa_arsitek/tpa_struktur/tpa_mep ke daftar
-- ENUM kolom role. Tanpa itu, 3 baris TPA di bawah akan gagal
-- (nilai role belum sah).
--
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
  (NULL, 'Rudi Hartono', 'rudi.hartono@sipgatutkaca.local', '$2y$12$vGMasnSZusvhY58V8C77Tu/XwVlWZBGuONiQU.YDnOGcCsp6ZNsUm', 'tpa_arsitek'),
  (NULL, 'Yulia Permatasari', 'yulia.permatasari@sipgatutkaca.local', '$2y$12$GTlXMH1wiD8qXl95Y95rROH.ixoLscf8AC9OiQ0JSLIytVGAmmhHm', 'tpa_struktur'),
  (NULL, 'Hendra Kusnadi', 'hendra.kusnadi@sipgatutkaca.local', '$2y$12$3u2bFKgNQlFBAZM6EUfy8e5.j9z.z5OzlPK7iIPqWjTCF.XN/RL.u', 'tpa_mep'),
  (NULL, 'Uji Coba Pemohon', 'pemohon.uji@sipgatutkaca.local', '$2y$12$E3eIivhnW59fPDgFmK3f5uW/zUrOtXPlJEiv/DsOwNxytetcGwOve', 'pemohon');

-- Kata sandi masing-masing (SEBELUM di-hash, di atas sudah dalam
-- bentuk bcrypt hash siap pakai):
--   ahmad.wijaya@sipgatutkaca.local       / b596c84a9d7a
--   siti.rahmawati@sipgatutkaca.local     / e2160c77feb5
--   rudi.hartono@sipgatutkaca.local       / 191b9dc53b2d   (TPA Arsitek)
--   yulia.permatasari@sipgatutkaca.local  / b59981e87fad   (TPA Struktur)
--   hendra.kusnadi@sipgatutkaca.local     / 6f21a582f9ec   (TPA MEP)
--   pemohon.uji@sipgatutkaca.local        / 092d2a5cd461
