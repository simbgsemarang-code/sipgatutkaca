-- Enam akun dummy TPA: masing-masing dua orang untuk bidang
-- Arsitektur, Struktur, dan MEP.
-- Jalankan database/tambah_tpa_spesialis.sql terlebih dahulu.
-- Aman dijalankan berulang karena email UNIQUE dan INSERT IGNORE.

INSERT IGNORE INTO `users` (`nik`, `nama`, `email`, `password`, `role`) VALUES
  (NULL, 'Rudi Hartono', 'rudi.hartono@sipgatutkaca.local', '$2y$12$vGMasnSZusvhY58V8C77Tu/XwVlWZBGuONiQU.YDnOGcCsp6ZNsUm', 'tpa_arsitek'),
  (NULL, 'Raka Pratama', 'raka.pratama@sipgatutkaca.local', '$2y$10$Taoh.Dg7PxGi0OTEVDPR9OQCtosOZkV65mzkdN/DwK.JXpuvOWqzS', 'tpa_arsitek'),
  (NULL, 'Yulia Permatasari', 'yulia.permatasari@sipgatutkaca.local', '$2y$12$GTlXMH1wiD8qXl95Y95rROH.ixoLscf8AC9OiQ0JSLIytVGAmmhHm', 'tpa_struktur'),
  (NULL, 'Dimas Saputra', 'dimas.saputra@sipgatutkaca.local', '$2y$10$83b3InXilqwX14TzCnZkDu9IJTsDFXGgMzwrv5tpHyQJsGd.XaC3e', 'tpa_struktur'),
  (NULL, 'Hendra Kusnadi', 'hendra.kusnadi@sipgatutkaca.local', '$2y$12$3u2bFKgNQlFBAZM6EUfy8e5.j9z.z5OzlPK7iIPqWjTCF.XN/RL.u', 'tpa_mep'),
  (NULL, 'Maya Lestari', 'maya.lestari@sipgatutkaca.local', '$2y$10$RHXRJ.Bin.mY/w4I0671ueIgGHtBu0ZUpM2A.x3B6aZOeOI6b6dX.', 'tpa_mep');

-- Kredensial akun dummy:
-- rudi.hartono@sipgatutkaca.local / 191b9dc53b2d
-- raka.pratama@sipgatutkaca.local / Arsitek#2026B
-- yulia.permatasari@sipgatutkaca.local / b59981e87fad
-- dimas.saputra@sipgatutkaca.local / Struktur#2026B
-- hendra.kusnadi@sipgatutkaca.local / 6f21a582f9ec
-- maya.lestari@sipgatutkaca.local / MEP#2026B
