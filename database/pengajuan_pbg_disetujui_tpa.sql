-- Migrasi lanjutan fitur Pengajuan PBG: status "Disetujui TPA" - jalur
-- keputusan TPA saat SEMUA dokumen teknis dinilai sudah sesuai (tidak
-- perlu perbaikan sama sekali). Menyusul dari database/pengajuan_pbg.sql
-- dan database/pengajuan_pbg_perbaikan.sql (WAJIB dijalankan lebih
-- dulu, urutan sama seperti sebelumnya).
--
-- Jalankan lewat phpMyAdmin -> pilih database Anda -> tab SQL -> paste
-- semua isi file ini -> Go.
--
-- Sebelum ini, TPA cuma punya 2 keputusan yang bisa dikirim - Perbaikan
-- Dokumen / Perbaikan Dokumen Konsultasi (lihat
-- Tpa_pengajuan_pbg::kirim_catatan()) - keduanya berarti "ada yang
-- perlu diperbaiki". Tidak ada jalan kalau TPA menilai semuanya sudah
-- OK - permohonan cuma diam di status Verifikasi Kelengkapan Dokumen
-- tanpa jejak bahwa TPA sudah meninjau & menyetujuinya.
--
-- CATATAN: status ini SENGAJA jadi status akhir (belum diarahkan ke
-- tahap apapun) - tahap sesudah TPA menyetujui (mis. penerbitan PBG,
-- retribusi) belum dibangun di sistem ini sama sekali. Kalau nanti
-- tahap itu dibangun, tinggal tambah status baru lagi mengikuti pola
-- di sini (MODIFY COLUMN, additive - jangan pernah menghapus nilai
-- ENUM yang sudah ada supaya baris lama tidak pernah rusak).
--
-- Aman dijalankan berkali-kali (MODIFY COLUMN sama seperti migrasi
-- status lain di folder ini).

ALTER TABLE `pengajuan_pbg`
  MODIFY COLUMN `status` ENUM(
    'draf',
    'verifikasi_dokumen',
    'perbaikan_dokumen',
    'perbaikan_dokumen_konsultasi',
    'menunggu_jadwal_konsultasi',
    'disetujui_tpa'
  ) NOT NULL DEFAULT 'draf';
