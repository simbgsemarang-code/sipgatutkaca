-- Tabel dan data awal pustaka regulasi.
-- Jalankan sekali melalui phpMyAdmin pada database SIP Gatutkaca.

CREATE TABLE IF NOT EXISTS `regulasi` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `judul` TEXT NOT NULL,
  `file_pdf` VARCHAR(255) DEFAULT NULL,
  `aktif` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_regulasi_aktif` (`aktif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `regulasi` (`judul`,`aktif`)
SELECT seed.judul, 1 FROM (
  SELECT 'Undang-undang Nomor 28 Tahun 2002 tentang Bangunan Gedung' judul
  UNION ALL SELECT 'Undang-undang Nomor 11 Tahun 2010 tentang Cagar Budaya'
  UNION ALL SELECT 'Undang-undang Nomor 11 Tahun 2020 tentang Cipta Kerja'
  UNION ALL SELECT 'Peraturan Pemerintah Nomor 16 Tahun 2021 tentang Peraturan Pelaksanaan Undang-undang Nomor 28 Tahun 2002 tentang Bangunan Gedung'
  UNION ALL SELECT 'Peraturan Pemerintah Nomor 1 Tahun 2022 tentang Register Nasional dan Pelestarian Cagar Budaya'
  UNION ALL SELECT 'Peraturan Menteri Pekerjaan Umum Nomor 24/PRT/M/2008 tentang Pedoman Pemeliharaan dan Perawatan Bangunan Gedung'
  UNION ALL SELECT 'Peraturan Menteri Pekerjaan Umum dan Perumahan Rakyat Nomor 16/PRT/M/2010 Tahun 2010 tentang Pedoman Teknis Pemeriksaan Berkala Bangunan Gedung'
  UNION ALL SELECT 'Peraturan Menteri Pekerjaan Umum dan Perumahan Rakyat Nomor 22/PRT/M/2018 tentang Pembangunan Bangunan Gedung Negara'
  UNION ALL SELECT 'Peraturan Menteri Pekerjaan Umum dan Perumahan Rakyat Nomor 27/PRT/M/2018 Tahun 2018 tentang Sertifikat Laik Fungsi Bangunan Gedung'
  UNION ALL SELECT 'Peraturan Menteri Pekerjaan Umum dan Perumahan Rakyat Nomor 8 Tahun 2021 tentang Penilai Ahli, Kegagalan Bangunan dan Penilaian Kegagalan Bangunan'
  UNION ALL SELECT 'Peraturan Menteri Pekerjaan Umum dan Perumahan Rakyat Nomor 19 Tahun 2021 tentang Pedoman Teknis Penyelenggaraan Bangunan Gedung Cagar Budaya yang Dilestarikan'
  UNION ALL SELECT 'Peraturan Menteri Pekerjaan Umum dan Perumahan Rakyat Nomor 21 Tahun 2021 tentang Penilaian Kinerja Bangunan Gedung Hijau'
  UNION ALL SELECT 'Keputusan Bersama Menteri Perumahan dan Kawasan Permukimaan, Menteri Pekerjaan Umum dan Menteri Dalam Negeri Nomor 03.HK/KPTS/Mn/2024, Nomor 3015/KPTS/M/2024, Nomor 600.10-4849 Tahun 2024 tentang Dukungan Percepatan Pelaksanaan Program Pembangunan Tiga Juta Rumah'
  UNION ALL SELECT 'Perda Kabupaten Cilacap Nomor 17 Tahun 2008 tentang Urusan Pemerintahan Kabupaten Cilacap'
  UNION ALL SELECT 'Peraturan Daerah Kabupaten Cilacap Nomor 11 Tahun 2011 tentang Bangunan Gedung'
  UNION ALL SELECT 'Peraturan Daerah Kabupaten Cilacap Nomor 1 Tahun 2024 tentang Pajak Daerah dan Retribusi Daerah'
  UNION ALL SELECT 'Peraturan Bupati Cilacap Nomor 34 Tahun 2023 tentang Kedudukan, Susunan Organisasi, Tugas dan Fungsi serta Tata Kerja Dinas Daerah'
  UNION ALL SELECT 'Peraturan Bupati Cilacap Nomor 52 Tahun 2023 tentang Tata Cara Penyelenggaraan Persetujuan Bangunan Gedung dan Sertifikat Laik Fungsi'
  UNION ALL SELECT 'Peraturan Bupati Cilacap Nomor 61 Tahun 2024 tentang Pembebasan Retribusi Persetujuan Bangunan Gedung bagi Masyarakat Berpenghasilan Rendah'
  UNION ALL SELECT 'Peraturan Bupati Cilacap Nomor 50 Tahun 2025 tentang Petunjuk Pelaksanaan Pemungutan Retribusi Persetujuan Bangunan Gedung'
) seed
WHERE NOT EXISTS (SELECT 1 FROM `regulasi` LIMIT 1);
