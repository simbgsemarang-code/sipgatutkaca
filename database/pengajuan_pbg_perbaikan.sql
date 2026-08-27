-- Migrasi lanjutan fitur Pengajuan PBG: alur PERBAIKAN DOKUMEN,
-- PERBAIKAN DOKUMEN KONSULTASI, dan UBAH DATA TANAH (Panduan
-- Permohonan PBG halaman 30-44) - menyusul dari tahap awal di
-- database/pengajuan_pbg.sql (WAJIB dijalankan lebih dulu).
--
-- Jalankan lewat phpMyAdmin -> pilih database Anda -> tab SQL -> paste
-- semua isi file ini -> Go.
--
-- Peran peninjau (yang menandai dokumen "tidak sesuai" dan memilih
-- status perbaikan apa) di sistem ini adalah TPA - lihat
-- Tpa_pengajuan_pbg.php. Panduan sumbernya sendiri cuma dari sisi
-- pemohon (tidak menunjukkan halaman peninjau), jadi urutan status
-- di bawah ini disusun berdasarkan pola transisi yang tersirat dari
-- panduan, bukan disalin langsung dari tangkapan layar peninjau
-- (yang memang tidak ada di panduan itu).
--
-- CATATAN SOAL "aman dijalankan berkali-kali": bagian MODIFY COLUMN
-- di bawah aman diulang seperti migrasi lain di folder ini. Bagian
-- ADD COLUMN / ADD CONSTRAINT SENGAJA TIDAK dibuat idempotent lewat
-- "IF NOT EXISTS" - sintaks itu baru didukung MySQL 8.0.29+ (belum
-- tentu tersedia di versi MySQL/MariaDB hosting ini), jadi lebih
-- aman ditulis polos. Efeknya: kalau file ini TIDAK SENGAJA dijalankan
-- dua kali, MySQL akan menolak dengan pesan jelas seperti "Duplicate
-- column name" - error itu aman diabaikan (tandanya migrasi ini
-- memang sudah pernah berhasil dijalankan sebelumnya), bukan tanda
-- ada yang rusak.

ALTER TABLE `pengajuan_pbg`
  MODIFY COLUMN `status` ENUM(
    'draf',
    'verifikasi_dokumen',
    'perbaikan_dokumen',
    'perbaikan_dokumen_konsultasi',
    'menunggu_jadwal_konsultasi'
  ) NOT NULL DEFAULT 'draf';

-- Catatan umum dari TPA saat menandai permohonan perlu perbaikan
-- (beda dari catatan per-dokumen di pengajuan_pbg_dokumen.catatan_penolakan
-- di bawah) + jejak siapa/kapan meninjau.
ALTER TABLE `pengajuan_pbg`
  ADD COLUMN `catatan_tpa` TEXT DEFAULT NULL AFTER `fungsi_bangunan`,
  ADD COLUMN `ditinjau_oleh` INT UNSIGNED DEFAULT NULL COMMENT 'users.id staf TPA yang terakhir meninjau' AFTER `catatan_tpa`,
  ADD COLUMN `ditinjau_pada` DATETIME DEFAULT NULL AFTER `ditinjau_oleh`,
  ADD CONSTRAINT `fk_pengajuan_pbg_ditinjau_oleh` FOREIGN KEY (`ditinjau_oleh`) REFERENCES `users` (`id`);

-- Status per dokumen teknis terunggah, supaya TPA bisa menandai
-- dokumen MANA yang tidak sesuai (bukan cuma permohonannya secara
-- umum), lengkap dengan alasannya. Kalau pemohon/PU unggah ulang
-- lewat dokumen jenis yang sama, baris lama (berikut catatannya)
-- otomatis diganti baris baru berstatus "terunggah" - lihat
-- Pengajuan_pbg::_proses_unggah_dokumen().
ALTER TABLE `pengajuan_pbg_dokumen`
  ADD COLUMN `status` ENUM('terunggah','ditolak') NOT NULL DEFAULT 'terunggah' AFTER `path_file`,
  ADD COLUMN `catatan_penolakan` TEXT DEFAULT NULL AFTER `status`;
