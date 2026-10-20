-- Data dummy massa bangunan untuk "Gedung SD Negeri Sadahayu 03" (Dinas Pendidikan dan Kebudayaan).
-- Tiga massa: dua poligon (bidang bangunan) dan satu marker, supaya semua jenis lokasi terlihat contohnya.
-- Aman dijalankan berulang: massa dengan nama yang sama untuk bangunan itu tidak diisi dua kali.
-- Koordinat bangunan acuan: -7.200381, 108.70808.

CREATE TABLE IF NOT EXISTS `bangunan_massa` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `bangunan_id` INT UNSIGNED NOT NULL,
  `nama_massa` VARCHAR(150) NOT NULL,
  `jumlah_lantai` SMALLINT UNSIGNED NULL,
  `luas_m2` DECIMAL(12,2) NULL,
  `kondisi` ENUM('1','2','3','4') NOT NULL DEFAULT '1',
  `tahun_dibangun` SMALLINT UNSIGNED NULL,
  `keterangan` VARCHAR(255) NULL,
  `foto` VARCHAR(255) NULL,
  `geom` MEDIUMTEXT NULL COMMENT 'GeoJSON geometry (Point / Polygon) lokasi massa',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bangunan_id` (`bangunan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `bangunan_massa` (`bangunan_id`,`nama_massa`,`jumlah_lantai`,`luas_m2`,`kondisi`,`tahun_dibangun`,`keterangan`,`geom`,`created_at`,`updated_at`)
SELECT b.id, d.nama_massa, d.jumlah_lantai, d.luas_m2, d.kondisi, d.tahun_dibangun, d.keterangan, d.geom, NOW(), NOW()
FROM `bangunan_gis` b
JOIN (
  SELECT 'Massa A — Ruang Kelas I–III' AS nama_massa, 1 AS jumlah_lantai, 288.00 AS luas_m2, '1' AS kondisi, 2012 AS tahun_dibangun,
         'Tiga ruang kelas dan selasar depan' AS keterangan,
         '{"type":"Polygon","coordinates":[[[108.70775,-7.20030],[108.70795,-7.20030],[108.70795,-7.20042],[108.70775,-7.20042],[108.70775,-7.20030]]]}' AS geom
  UNION ALL
  SELECT 'Massa B — Ruang Kelas IV–VI & Perpustakaan', 1, 336.00, '2', 2005,
         'Atap bocor ringan di sisi timur',
         '{"type":"Polygon","coordinates":[[[108.70815,-7.20030],[108.70845,-7.20030],[108.70845,-7.20041],[108.70815,-7.20041],[108.70815,-7.20030]]]}'
  UNION ALL
  SELECT 'Massa C — Kantor Guru & UKS', 1, 96.00, '3', 1998,
         'Plafon dan dinding perlu perbaikan',
         '{"type":"Point","coordinates":[108.70808,-7.20055]}'
) d
WHERE b.nama_bangunan = 'Gedung SD Negeri Sadahayu 03'
  AND b.opd = 'Dinas Pendidikan dan Kebudayaan'
  AND NOT EXISTS (SELECT 1 FROM `bangunan_massa` m WHERE m.bangunan_id = b.id AND m.nama_massa = d.nama_massa);
