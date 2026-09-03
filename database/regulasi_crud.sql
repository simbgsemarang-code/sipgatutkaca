-- Tabel dan data awal pustaka regulasi.
-- Jalankan sekali melalui phpMyAdmin pada database SIP Gatutkaca.

CREATE TABLE IF NOT EXISTS `regulasi` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `judul` TEXT NOT NULL,
  `urutan` INT UNSIGNED NOT NULL DEFAULT 0,
  `file_pdf` VARCHAR(255) DEFAULT NULL,
  `aktif` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_regulasi_aktif_urutan` (`aktif`,`urutan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `regulasi` (`judul`,`urutan`,`aktif`)
SELECT seed.judul, seed.urutan, 1 FROM (
  SELECT 'Undang-undang Nomor 28 Tahun 2002 tentang Bangunan Gedung' judul, 1 urutan
  UNION ALL SELECT 'Undang-undang Nomor 11 Tahun 2010 tentang Cagar Budaya', 2
  UNION ALL SELECT 'Undang-undang Nomor 11 Tahun 2020 tentang Cipta Kerja', 3
  UNION ALL SELECT 'Peraturan Pemerintah Nomor 16 Tahun 2021 tentang Peraturan Pelaksanaan Undang-undang Nomor 28 Tahun 2002 tentang Bangunan Gedung', 4
  UNION ALL SELECT 'Peraturan Pemerintah Nomor 1 Tahun 2022 tentang Register Nasional dan Pelestarian Cagar Budaya', 5
  UNION ALL SELECT 'Peraturan Menteri Pekerjaan Umum Nomor 24/PRT/M/2008 tentang Pedoman Pemeliharaan dan Perawatan Bangunan Gedung', 6
  UNION ALL SELECT 'Peraturan Menteri Pekerjaan Umum dan Perumahan Rakyat Nomor 16/PRT/M/2010 Tahun 2010 tentang Pedoman Teknis Pemeriksaan Berkala Bangunan Gedung', 7
  UNION ALL SELECT 'Peraturan Menteri Pekerjaan Umum dan Perumahan Rakyat Nomor 22/PRT/M/2018 tentang Pembangunan Bangunan Gedung Negara', 8
  UNION ALL SELECT 'Peraturan Menteri Pekerjaan Umum dan Perumahan Rakyat Nomor 27/PRT/M/2018 Tahun 2018 tentang Sertifikat Laik Fungsi Bangunan Gedung', 9
  UNION ALL SELECT 'Peraturan Menteri Pekerjaan Umum dan Perumahan Rakyat Nomor 8 Tahun 2021 tentang Penilai Ahli, Kegagalan Bangunan dan Penilaian Kegagalan Bangunan', 10
  UNION ALL SELECT 'Peraturan Menteri Pekerjaan Umum dan Perumahan Rakyat Nomor 19 Tahun 2021 tentang Pedoman Teknis Penyelenggaraan Bangunan Gedung Cagar Budaya yang Dilestarikan', 11
  UNION ALL SELECT 'Peraturan Menteri Pekerjaan Umum dan Perumahan Rakyat Nomor 21 Tahun 2021 tentang Penilaian Kinerja Bangunan Gedung Hijau', 12
  UNION ALL SELECT 'Keputusan Bersama Menteri Perumahan dan Kawasan Permukimaan, Menteri Pekerjaan Umum dan Menteri Dalam Negeri Nomor 03.HK/KPTS/Mn/2024, Nomor 3015/KPTS/M/2024, Nomor 600.10-4849 Tahun 2024 tentang Dukungan Percepatan Pelaksanaan Program Pembangunan Tiga Juta Rumah', 13
  UNION ALL SELECT 'Perda Kabupaten Cilacap Nomor 17 Tahun 2008 tentang Urusan Pemerintahan Kabupaten Cilacap', 14
  UNION ALL SELECT 'Peraturan Daerah Kabupaten Cilacap Nomor 11 Tahun 2011 tentang Bangunan Gedung', 15
  UNION ALL SELECT 'Peraturan Daerah Kabupaten Cilacap Nomor 1 Tahun 2024 tentang Pajak Daerah dan Retribusi Daerah', 16
  UNION ALL SELECT 'Peraturan Bupati Cilacap Nomor 34 Tahun 2023 tentang Kedudukan, Susunan Organisasi, Tugas dan Fungsi serta Tata Kerja Dinas Daerah', 17
  UNION ALL SELECT 'Peraturan Bupati Cilacap Nomor 52 Tahun 2023 tentang Tata Cara Penyelenggaraan Persetujuan Bangunan Gedung dan Sertifikat Laik Fungsi', 18
  UNION ALL SELECT 'Peraturan Bupati Cilacap Nomor 61 Tahun 2024 tentang Pembebasan Retribusi Persetujuan Bangunan Gedung bagi Masyarakat Berpenghasilan Rendah', 19
  UNION ALL SELECT 'Peraturan Bupati Cilacap Nomor 50 Tahun 2025 tentang Petunjuk Pelaksanaan Pemungutan Retribusi Persetujuan Bangunan Gedung', 20
) seed
WHERE NOT EXISTS (SELECT 1 FROM `regulasi` LIMIT 1);
