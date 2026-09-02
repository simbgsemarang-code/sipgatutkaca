-- Satu permohonan uji yang lengkap dan tetap berstatus Diajukan.
-- Aman dijalankan berulang: nomor permohonan dibuat unik.
INSERT INTO `permohonan_pbg` (
  `user_id`, `no_permohonan`, `nama_pemohon`, `nik`, `no_hp`, `email`,
  `alamat_bangunan`, `jenis_bangunan`, `kategori_bangunan`, `luas_bangunan`,
  `keterangan`, `file_ktp`, `file_kepemilikan_tanah`, `file_data_perencana`,
  `file_pkkpr`, `file_rencana_teknis`, `file_teknis_struktur`,
  `file_checklist_mep`, `file_pernyataan_tataruang`, `file_dokumen_lingkungan`,
  `status`, `tahap`, `created_at`, `updated_at`
)
SELECT
  (SELECT `id` FROM `users` WHERE `role`='pu' ORDER BY `id` LIMIT 1),
  'PBG-20260902-DUMMY01', 'Rina Wulandari', '3301024508900003',
  '081234567890', 'rina.wulandari@example.test',
  'Jl. Gatot Subroto No. 18, Cilacap Tengah, Kabupaten Cilacap',
  'Rumah Tinggal', 'sederhana', 96.00,
  'Data simulasi untuk pengujian alur permohonan PBG oleh petugas PU.',
  'dummy-ktp.jpg', 'dummy-tanah.jpg', 'dummy-perencana.jpg',
  'dummy-pkkpr.jpg', 'dummy-arsitektur.jpg', 'dummy-struktur.jpg',
  'dummy-mep.jpg', 'dummy-pernyataan.jpg', 'dummy-lingkungan.jpg',
  'diajukan', 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `permohonan_pbg` WHERE `no_permohonan`='PBG-20260902-DUMMY01'
);
