-- Migrasi lanjutan fitur Pengajuan PBG: penugasan reviewer TPA PER
-- PERMOHONAN. Menyusul dari database/pengajuan_pbg.sql,
-- pengajuan_pbg_perbaikan.sql, pengajuan_pbg_disetujui_tpa.sql, dan
-- pengajuan_pbg_persetujuan_tpa.sql (WAJIB dijalankan lebih dulu,
-- urutan sama seperti sebelumnya).
--
-- Jalankan lewat phpMyAdmin -> pilih database Anda -> tab SQL -> paste
-- semua isi file ini -> Go.
--
-- LATAR BELAKANG: sebelum migrasi ini, SIAPA SAJA akun berperan
-- tpa_arsitek/tpa_struktur/tpa_mep bisa melihat & meninjau SEMUA
-- permohonan di bidang itu - cocok kalau cuma ada 1 staf per bidang,
-- tapi tidak cocok kalau ada beberapa staf per bidang dan PU perlu
-- menentukan siapa yang menangani permohonan yang mana. 3 kolom baru
-- ini menyimpan staf yang DITUGASKAN PU untuk tiap bidang pada
-- permohonan tertentu - diatur lewat tombol "Atur Reviewer TPA" di
-- halaman detail PU.
--
-- PENTING soal kompatibilitas mundur: kalau salah satu kolom ini
-- masih NULL (belum ditugaskan - termasuk SEMUA permohonan lama
-- sebelum migrasi ini dijalankan), PERILAKU LAMA tetap berlaku -
-- SEMUA akun bidang itu tetap bisa melihat & meninjau permohonan itu,
-- SAMPAI PU menugaskan seseorang secara eksplisit. Begitu diisi,
-- HANYA staf yang ditugaskan yang bisa mengakses permohonan itu untuk
-- bidangnya - lihat Tpa_pengajuan_pbg::_bidang_boleh_akses(). Jadi
-- migrasi ini AMAN dijalankan di sistem yang sudah berjalan - tidak
-- ada permohonan yang tiba-tiba tidak bisa diakses TPA manapun.
--
-- Akun 'tpa' generik lama TIDAK terpengaruh sama sekali oleh
-- penugasan ini (tetap melihat semua, seperti biasa - konsisten
-- dengan desain di pengajuan_pbg_persetujuan_tpa.sql).
--
-- CATATAN "aman dijalankan berkali-kali": SENGAJA TIDAK idempotent
-- lewat "IF NOT EXISTS" (belum tentu didukung versi MySQL/MariaDB
-- hosting ini - lihat catatan yang sama di pengajuan_pbg_perbaikan.sql).
-- Efeknya kalau file ini TIDAK SENGAJA dijalankan dua kali: MySQL
-- menolak dengan pesan jelas seperti "Duplicate column name" - aman
-- diabaikan.

ALTER TABLE `pengajuan_pbg`
  ADD COLUMN `reviewer_arsitek_id` INT UNSIGNED DEFAULT NULL COMMENT 'users.id staf TPA Arsitek yang ditugaskan PU utk permohonan ini - NULL berarti belum ditugaskan (semua akun tpa_arsitek boleh akses)' AFTER `ditinjau_pada`,
  ADD COLUMN `reviewer_struktur_id` INT UNSIGNED DEFAULT NULL COMMENT 'users.id staf TPA Struktur yang ditugaskan - lihat catatan reviewer_arsitek_id' AFTER `reviewer_arsitek_id`,
  ADD COLUMN `reviewer_mep_id` INT UNSIGNED DEFAULT NULL COMMENT 'users.id staf TPA MEP yang ditugaskan - lihat catatan reviewer_arsitek_id' AFTER `reviewer_struktur_id`,
  ADD CONSTRAINT `fk_pengajuan_pbg_reviewer_arsitek` FOREIGN KEY (`reviewer_arsitek_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_pengajuan_pbg_reviewer_struktur` FOREIGN KEY (`reviewer_struktur_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_pengajuan_pbg_reviewer_mep` FOREIGN KEY (`reviewer_mep_id`) REFERENCES `users` (`id`);
