-- Migrasi lanjutan fitur Pengajuan PBG: persetujuan TPA PER BIDANG.
-- Menyusul dari database/pengajuan_pbg.sql, pengajuan_pbg_perbaikan.sql,
-- dan pengajuan_pbg_disetujui_tpa.sql (WAJIB dijalankan lebih dulu,
-- urutan sama seperti sebelumnya).
--
-- Jalankan lewat phpMyAdmin -> pilih database Anda -> tab SQL -> paste
-- semua isi file ini -> Go. Aman dijalankan berkali-kali
-- (CREATE TABLE IF NOT EXISTS).
--
-- LATAR BELAKANG: ada 3 spesialisasi TPA (Arsitektur & Tata Kota,
-- Struktur & Sipil, MEP). Sebelum migrasi ini, status 'disetujui_tpa'
-- bisa dipicu SIAPA SAJA dari TPA sekali klik - padahal maksudnya
-- SEMUA TIGA bidang harus menyetujui dulu baru permohonan benar-benar
-- selesai ditinjau. Tabel ini menyimpan keputusan TIAP bidang secara
-- independen; kolom `pengajuan_pbg.status` sekarang DITURUNKAN dari
-- isi tabel ini (dihitung ulang tiap ada keputusan baru atau perbaikan
-- dikirim PU), bukan ditulis langsung oleh satu keputusan tunggal.
-- Lihat Tpa_pengajuan_pbg::_hitung_status_keseluruhan().
--
-- Tidak ada baris untuk satu bidang = bidang itu BELUM meninjau
-- ("menunggu"). Begitu bidang itu mengirim keputusan, satu baris
-- dibuat (disetujui / perbaikan_dokumen / perbaikan_dokumen_konsultasi).
-- Kalau PU merespons "perbaikan_dokumen" (bukan varian konsultasi),
-- baris bidang yang tadi minta perbaikan itu DIHAPUS lagi (supaya
-- kembali ke "menunggu", perlu ditinjau ulang) - bidang LAIN yang
-- sudah lebih dulu menyetujui TIDAK ikut direset, sesuai keputusan
-- yang diambil saat membangun fitur ini. Varian "...konsultasi" tetap
-- jadi jalan keluar ke status Menunggu Jadwal Konsultasi begitu
-- diperbaiki (bukan siklus tinjau-ulang lagi), sama seperti sebelum
-- ada pembagian per bidang ini.
--
-- Akun TPA generik lama (peran 'tpa') SENGAJA tidak diikutkan sebagai
-- nilai `bidang` di sini - keputusannya tidak dihitung sebagai
-- persetujuan bidang manapun (lihat Tpa_pengajuan_pbg::kirim_catatan()),
-- meski akun itu tetap bisa melihat semua dokumen & menandai dokumen
-- per-item seperti biasa.
--
-- CATATAN: kolom pengajuan_pbg.catatan_tpa / ditinjau_oleh / ditinjau_pada
-- (dari pengajuan_pbg_perbaikan.sql) TIDAK dihapus - data lama tetap
-- ada - tapi TIDAK DIPAKAI LAGI oleh alur baru ini (digantikan catatan
-- per baris di tabel ini). Tampilan sekarang membaca dari tabel ini.

CREATE TABLE IF NOT EXISTS `pengajuan_pbg_persetujuan_tpa` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pengajuan` INT UNSIGNED NOT NULL,
  `bidang` ENUM('tpa_arsitek','tpa_struktur','tpa_mep') NOT NULL COMMENT 'Spesialisasi TPA yang memutuskan - BUKAN akun generik "tpa"',
  `status` ENUM('disetujui','perbaikan_dokumen','perbaikan_dokumen_konsultasi') NOT NULL,
  `catatan` TEXT DEFAULT NULL COMMENT 'Opsional kalau status=disetujui, wajib untuk 2 status perbaikan',
  `ditinjau_oleh` INT UNSIGNED DEFAULT NULL COMMENT 'users.id staf TPA yang memutuskan',
  `ditinjau_pada` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_pengajuan_bidang` (`id_pengajuan`, `bidang`),
  KEY `idx_id_pengajuan` (`id_pengajuan`),
  CONSTRAINT `fk_persetujuan_tpa_pengajuan` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_pbg` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_persetujuan_tpa_user` FOREIGN KEY (`ditinjau_oleh`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
