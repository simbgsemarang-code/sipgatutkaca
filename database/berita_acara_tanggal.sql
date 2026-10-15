-- Tanggal berita acara konsultasi TPA yang bisa diubah PU (modal "Unduh BA").
-- NULL = pakai tanggal review (diterbitkan_at). Diubah untuk SELURUH BA satu permohonan.
-- Kolom ini juga ditambahkan otomatis oleh aplikasi saat PU pertama kali menyimpan tanggal
-- (bila akun database punya hak ALTER); jalankan manual hanya jika muncul pesan kolom belum ada.
ALTER TABLE `berita_acara_pbg` ADD COLUMN `tanggal_ba` DATE NULL AFTER `diterbitkan_at`;
