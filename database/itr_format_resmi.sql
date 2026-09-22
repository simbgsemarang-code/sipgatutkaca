-- Menyesuaikan Pengajuan ITR dengan format resmi Surat Permohonan ITR
-- 2021 (Perorangan & Perusahaan) - kolom baru semuanya nullable/ada
-- default supaya data pengajuan lama tetap valid tanpa perlu diisi ulang.
ALTER TABLE `pengajuan_itr`
  ADD COLUMN IF NOT EXISTS `jenis_pemohon` ENUM('perorangan','perusahaan') NOT NULL DEFAULT 'perorangan' AFTER `user_id`,
  ADD COLUMN IF NOT EXISTS `pekerjaan` VARCHAR(150) NULL AFTER `nama_pemohon`,
  ADD COLUMN IF NOT EXISTS `alamat_pemohon` TEXT NULL AFTER `pekerjaan`,
  ADD COLUMN IF NOT EXISTS `nib` VARCHAR(50) NULL AFTER `alamat_pemohon`,
  ADD COLUMN IF NOT EXISTS `no_npwp` VARCHAR(30) NULL AFTER `nib`,
  MODIFY COLUMN `nik` VARCHAR(16) NULL,
  ADD COLUMN IF NOT EXISTS `jenis_kegiatan` VARCHAR(255) NULL AFTER `nik`,
  ADD COLUMN IF NOT EXISTS `fungsi_bangunan` VARCHAR(150) NULL AFTER `jenis_kegiatan`,
  ADD COLUMN IF NOT EXISTS `lokasi_jalan` VARCHAR(255) NULL AFTER `alamat_lokasi`,
  ADD COLUMN IF NOT EXISTS `lokasi_rt_rw` VARCHAR(50) NULL AFTER `lokasi_jalan`,
  ADD COLUMN IF NOT EXISTS `lokasi_desa_kel` VARCHAR(150) NULL AFTER `lokasi_rt_rw`,
  ADD COLUMN IF NOT EXISTS `lokasi_kecamatan` VARCHAR(150) NULL AFTER `lokasi_desa_kel`,
  ADD COLUMN IF NOT EXISTS `luas_bangunan` DECIMAL(16,2) NULL AFTER `luas_lahan`,
  ADD COLUMN IF NOT EXISTS `lantai_bangunan` SMALLINT UNSIGNED NULL AFTER `luas_bangunan`,
  ADD COLUMN IF NOT EXISTS `status_tanah` VARCHAR(50) NULL AFTER `lantai_bangunan`,
  ADD COLUMN IF NOT EXISTS `penggunaan_air` VARCHAR(50) NULL AFTER `status_tanah`,
  ADD COLUMN IF NOT EXISTS `keterangan_perijinan` VARCHAR(255) NULL AFTER `penggunaan_air`,
  MODIFY COLUMN `rencana_kegiatan` TEXT NULL,
  ADD COLUMN IF NOT EXISTS `titik_koordinat` TEXT NULL COMMENT 'JSON array [{lat,lng},...] poligon lokasi, minimal 4 titik' AFTER `longitude`,
  ADD COLUMN IF NOT EXISTS `file_denah_foto` VARCHAR(255) NULL AFTER `file_siteplan`,
  ADD COLUMN IF NOT EXISTS `file_nib` VARCHAR(255) NULL AFTER `file_denah_foto`,
  ADD COLUMN IF NOT EXISTS `file_npwp` VARCHAR(255) NULL AFTER `file_nib`,
  ADD COLUMN IF NOT EXISTS `file_akta` VARCHAR(255) NULL AFTER `file_npwp`;
