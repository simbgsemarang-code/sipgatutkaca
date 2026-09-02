-- Penyelarasan penuh modul PBG dengan SI DAI TERBANG.
-- Aman untuk data lama: tabel pengajuan_pbg tidak dihapus.
-- Perbedaan SIP GATUTKACA: user_id menunjuk akun PU penginput dan PU mengontrol tahap.

CREATE TABLE IF NOT EXISTS `permohonan_pbg` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED DEFAULT NULL COMMENT 'Akun PU yang menginput permohonan',
  `no_permohonan` VARCHAR(30) NOT NULL,
  `nama_pemohon` VARCHAR(150) NOT NULL,
  `nik` VARCHAR(20) DEFAULT NULL,
  `no_hp` VARCHAR(20) NOT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `alamat_bangunan` VARCHAR(255) NOT NULL,
  `jenis_bangunan` VARCHAR(100) NOT NULL,
  `kategori_bangunan` ENUM('sederhana','tidak_sederhana','perumahan') NOT NULL DEFAULT 'sederhana',
  `luas_bangunan` DECIMAL(10,2) DEFAULT NULL,
  `keterangan` TEXT,
  `catatan_admin` TEXT DEFAULT NULL COMMENT 'Catatan proses yang diisi PU',
  `catatan_admin_at` DATETIME DEFAULT NULL,
  `file_ktp` VARCHAR(255) DEFAULT NULL,
  `file_kepemilikan_tanah` VARCHAR(255) DEFAULT NULL,
  `file_rencana_teknis` VARCHAR(255) DEFAULT NULL,
  `file_pernyataan_tataruang` VARCHAR(255) DEFAULT NULL,
  `file_dokumen_lingkungan` VARCHAR(255) DEFAULT NULL,
  `file_pkkpr` VARCHAR(255) DEFAULT NULL,
  `file_data_perencana` VARCHAR(255) DEFAULT NULL,
  `file_teknis_struktur` VARCHAR(255) DEFAULT NULL,
  `file_checklist_mep` VARCHAR(255) DEFAULT NULL,
  `file_ketentuan_teknis_tanah` VARCHAR(255) DEFAULT NULL,
  `file_kkop_skub` VARCHAR(255) DEFAULT NULL,
  `file_siteplan_disahkan` VARCHAR(255) DEFAULT NULL,
  `file_gss` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('diajukan','diverifikasi','disetujui','ditolak') NOT NULL DEFAULT 'diajukan',
  `tahap` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '1-4, dikendalikan PU',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`), UNIQUE KEY `no_permohonan` (`no_permohonan`),
  KEY `idx_pu` (`user_id`), KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `aktivitas_pbg` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `permohonan_id` INT UNSIGNED NOT NULL,
  `no_permohonan` VARCHAR(30) NOT NULL,
  `nama_pemohon` VARCHAR(150) NOT NULL,
  `tahap` TINYINT UNSIGNED NOT NULL,
  `status` ENUM('diajukan','diverifikasi','disetujui','ditolak') NOT NULL,
  `keterangan` VARCHAR(255) NOT NULL,
  `actor_id` INT UNSIGNED DEFAULT NULL,
  `actor` VARCHAR(150) NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`), KEY `permohonan` (`permohonan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Salin ringkasan data lama satu kali. Detail lama tetap utuh di tabel asal.
INSERT IGNORE INTO `permohonan_pbg`
(`id`,`user_id`,`no_permohonan`,`nama_pemohon`,`nik`,`no_hp`,`alamat_bangunan`,`jenis_bangunan`,`kategori_bangunan`,`luas_bangunan`,`keterangan`,`status`,`tahap`,`created_at`,`updated_at`)
SELECT p.id,p.dibuat_oleh,
 COALESCE(NULLIF(p.no_registrasi,''),CONCAT('PBG-LEGACY-',LPAD(p.id,6,'0'))),
 p.nama_pemohon,p.nik_pemohon,COALESCE(NULLIF(p.kontak_pemohon,''),'-'),
 COALESCE(NULLIF(p.lokasi_alamat,''),'-'),COALESCE(NULLIF(p.bangunan_nama,''),'Bangunan Gedung'),
 'sederhana',NULL,p.fungsi_bangunan,
 CASE WHEN p.status='disetujui_tpa' THEN 'disetujui' WHEN p.status='ditolak' THEN 'ditolak' WHEN p.status='draf' THEN 'diajukan' ELSE 'diverifikasi' END,
 CASE WHEN p.status='disetujui_tpa' OR p.status='ditolak' THEN 4 WHEN p.status IN ('menunggu_jadwal_konsultasi','perbaikan_dokumen_konsultasi') THEN 3 WHEN p.status='draf' THEN 1 ELSE 2 END,
 p.created_at,p.updated_at
FROM `pengajuan_pbg` p;
