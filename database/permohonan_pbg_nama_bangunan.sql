-- Nama bangunan gedung diisi eksplisit oleh PU saat mendaftarkan
-- permohonan, dipakai antara lain pada Berita Acara Konsultasi TPA.
ALTER TABLE `permohonan_pbg`
  ADD COLUMN IF NOT EXISTS `nama_bangunan` VARCHAR(150) NOT NULL DEFAULT '' AFTER `nama_pemohon`;
