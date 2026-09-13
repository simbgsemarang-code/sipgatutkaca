-- Akun uji coba pemohon ITR. Tidak mengubah akun yang sudah ada.
INSERT INTO users (nama,email,password,role)
SELECT 'Budi Santoso','budi.santoso.itr@sipgatutkaca.local','$2y$10$iCZlawzW2treU/gFRZIUS.vdFsRoFI7xqp6l3RizL0/1inRn6B3R6','pemohon'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email='budi.santoso.itr@sipgatutkaca.local');
INSERT INTO users (nama,email,password,role)
SELECT 'Dewi Anggraini','dewi.anggraini.itr@sipgatutkaca.local','$2y$10$Dptm6xSBYjqzWrKOkzHHaet893u6RmmgC7OPuQukZcpf3Q/iaqeh.','pemohon'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email='dewi.anggraini.itr@sipgatutkaca.local');
