-- 3 data contoh (dummy) untuk kotak masuk Saran & Masukan.
-- Aman dijalankan ulang: tiap baris hanya masuk kalau belum ada
-- (dicek dari kombinasi nama + pesan).
-- Jalankan lewat phpMyAdmin -> pilih database -> tab SQL / Import.

INSERT INTO `saran_masukan` (`nama`,`email`,`no_hp`,`topik`,`pesan`,`status`,`catatan`,`created_at`)
SELECT * FROM (SELECT
  'Rangga Wijaya' AS `nama`,
  'rangga.wijaya88@gmail.com' AS `email`,
  '081327754190' AS `no_hp`,
  'Tampilan Peta' AS `topik`,
  'Peta pada halaman Analisa Kerusakan cukup berat dibuka lewat HP. Mungkin bisa ditambahkan opsi untuk mematikan sebagian lapisan secara default supaya lebih cepat. Selebihnya informasinya sangat membantu.' AS `pesan`,
  'baru' AS `status`,
  NULL AS `catatan`,
  '2026-08-24 09:12:41' AS `created_at`
) x
WHERE NOT EXISTS (SELECT 1 FROM `saran_masukan` s WHERE s.`nama` = x.`nama` AND s.`pesan` = x.`pesan`);

INSERT INTO `saran_masukan` (`nama`,`email`,`no_hp`,`topik`,`pesan`,`status`,`catatan`,`created_at`)
SELECT * FROM (SELECT
  'Siti Nurhaliza' AS `nama`,
  'sitinurhaliza.arch@gmail.com' AS `email`,
  NULL AS `no_hp`,
  'Layanan PBG' AS `topik`,
  'Mohon informasi perkiraan lama proses PBG untuk rumah tinggal 2 lantai. Akan lebih baik jika daftar dokumen yang harus dilampirkan ditampilkan lebih jelas di awal, sebelum pemohon mulai mengisi formulir.' AS `pesan`,
  'ditinjau' AS `status`,
  'Sudah dibalas via email 25 Agu, diarahkan ke menu Konsultasi. Menunggu tim PU melengkapi info estimasi waktu.' AS `catatan`,
  '2026-08-21 14:03:17' AS `created_at`
) x
WHERE NOT EXISTS (SELECT 1 FROM `saran_masukan` s WHERE s.`nama` = x.`nama` AND s.`pesan` = x.`pesan`);

INSERT INTO `saran_masukan` (`nama`,`email`,`no_hp`,`topik`,`pesan`,`status`,`catatan`,`created_at`)
SELECT * FROM (SELECT
  'Bambang Sutrisno' AS `nama`,
  NULL AS `email`,
  '085869902137' AS `no_hp`,
  'Saran Fitur' AS `topik`,
  'Usul: kirim notifikasi WhatsApp otomatis setiap status permohonan berubah, supaya pemohon tidak perlu bolak-balik mengecek. Terima kasih atas layanannya.' AS `pesan`,
  'selesai' AS `status`,
  'Usulan dicatat untuk pengembangan tahap berikutnya. Sudah dikonfirmasi ke pengusul via telepon 20 Agu.' AS `catatan`,
  '2026-08-18 16:47:55' AS `created_at`
) x
WHERE NOT EXISTS (SELECT 1 FROM `saran_masukan` s WHERE s.`nama` = x.`nama` AND s.`pesan` = x.`pesan`);
