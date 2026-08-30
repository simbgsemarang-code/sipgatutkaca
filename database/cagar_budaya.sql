-- Migrasi + seed untuk fitur "Cagar Budaya" (daftar objek cagar budaya /
-- objek diduga cagar budaya di Kabupaten Cilacap yang tampil sebagai
-- TABEL + PETA di halaman publik /cagar-budaya, dan dikelola admin lewat
-- Admin::cagar_budaya*).
--
-- Jalankan lewat phpMyAdmin -> pilih database -> tab SQL -> paste -> Go.
-- Seed hanya dimuat kalau tabel `cagar_budaya` masih kosong, jadi aman
-- dijalankan ulang (tidak menggandakan baris).
--
-- CATATAN DATA: daftar awal ini dihimpun dari sumber publik (Registrasi
-- Nasional Cagar Budaya Kemdikbud, BPCB Jawa Tengah, Wikipedia, dan
-- pemberitaan resmi Pemkab Cilacap). Sebagian koordinat masih PERKIRAAN
-- dan status sebagian objek masih "Dalam Kajian" / "Objek Diduga Cagar
-- Budaya". Mohon diverifikasi & dilengkapi oleh Tim Ahli Cagar Budaya
-- (TACB) Kabupaten Cilacap melalui menu admin "Kelola Cagar Budaya".

CREATE TABLE IF NOT EXISTS `cagar_budaya` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(255) NOT NULL,
  `kategori` ENUM('Benda','Bangunan','Struktur','Situs','Kawasan') NOT NULL DEFAULT 'Bangunan',
  `kecamatan` VARCHAR(100) DEFAULT NULL,
  `kelurahan` VARCHAR(100) DEFAULT NULL,
  `alamat` VARCHAR(255) DEFAULT NULL,
  `tahun` VARCHAR(60) DEFAULT NULL COMMENT 'teks bebas, mis. "1861-1879" atau "abad ke-19"',
  `status` ENUM('Ditetapkan','Terdaftar Register Nasional','Dalam Kajian','Diusulkan','Objek Diduga Cagar Budaya') NOT NULL DEFAULT 'Objek Diduga Cagar Budaya',
  `no_sk` VARCHAR(150) DEFAULT NULL COMMENT 'nomor SK penetapan (jika ada)',
  `latitude` DECIMAL(11,8) DEFAULT NULL,
  `longitude` DECIMAL(11,8) DEFAULT NULL,
  `deskripsi` TEXT,
  `sumber` VARCHAR(255) DEFAULT NULL COMMENT 'sumber data / rujukan',
  `foto` VARCHAR(255) DEFAULT NULL COMMENT 'nama file foto (assets/foto-cagar-budaya/) atau URL',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_cagar_kecamatan` (`kecamatan`),
  KEY `idx_cagar_kategori` (`kategori`),
  KEY `idx_cagar_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `cagar_budaya`
  (`nama`,`kategori`,`kecamatan`,`kelurahan`,`alamat`,`tahun`,`status`,`no_sk`,`latitude`,`longitude`,`deskripsi`,`sumber`)
SELECT `nama`,`kategori`,`kecamatan`,`kelurahan`,`alamat`,`tahun`,`status`,`no_sk`,`latitude`,`longitude`,`deskripsi`,`sumber` FROM (
  SELECT
    'Benteng Pendem (Kusbatterij op de Landtong te Tjilatjap)' AS `nama`,
    'Struktur' AS `kategori`, 'Cilacap Selatan' AS `kecamatan`, 'Cilacap' AS `kelurahan`,
    'Jl. Benteng, kompleks wisata Teluk Penyu' AS `alamat`, '1861-1879' AS `tahun`,
    'Terdaftar Register Nasional' AS `status`, NULL AS `no_sk`,
    -7.74920560 AS `latitude`, 109.01708610 AS `longitude`,
    'Benteng pertahanan pantai peninggalan Hindia Belanda seluas ratusan meter persegi di ujung daratan Cilacap; digali kembali sejak 1986 dan kini menjadi objek wisata sejarah.' AS `deskripsi`,
    'Wikipedia; BPCB Jawa Tengah; Registrasi Nasional Cagar Budaya' AS `sumber`
  UNION ALL SELECT 'Benteng Karangbolong','Struktur','Cilacap Selatan','Tambakreja',
    'Ujung timur Pulau Nusakambangan','abad ke-19','Terdaftar Register Nasional',NULL,
    -7.74200000,108.99000000,
    'Benteng pertahanan Hindia Belanda di Pulau Nusakambangan seluas kurang lebih 12 ha, dilengkapi Menara Napoleon; bagian dari sistem pertahanan Teluk Penyu.',
    'Wikipedia; BPCB Jawa Tengah'
  UNION ALL SELECT 'Benteng Klingker','Struktur','Cilacap Selatan',NULL,
    'Pulau Nusakambangan, ± 1,6 km barat Benteng Karangbolong','abad ke-19','Objek Diduga Cagar Budaya',NULL,
    -7.74450000,108.97400000,
    'Benteng bata (klinker) peninggalan Hindia Belanda; sebagian struktur masih kokoh, sebagian telah runtuh.',
    'BPCB Jawa Tengah; catatan penelusuran'
  UNION ALL SELECT 'Mercusuar Cimiring','Struktur','Cilacap Selatan',NULL,
    'Ujung timur Pulau Nusakambangan','masa Hindia Belanda','Objek Diduga Cagar Budaya',NULL,
    -7.74600000,108.98200000,
    'Menara suar sekaligus menara pengawasan yang berkaitan dengan Benteng Karangbolong, Benteng Klingker, dan Benteng Pendem.',
    'Catatan penelusuran; berbagai sumber'
  UNION ALL SELECT 'Stasiun Cilacap','Bangunan','Cilacap Tengah','Sidanegara',
    'Jl. Dr. Rajiman, kawasan Pelabuhan Cilacap','jalur rel 1879-1887','Dalam Kajian',NULL,
    -7.72880000,109.00950000,
    'Stasiun kereta api yang menjadikan Cilacap kota transito komoditas dari Banyumas, Kedu, dan Yogyakarta pada masa kolonial.',
    'BPCB Jawa Tengah'
  UNION ALL SELECT 'Pendopo Wijayakusuma Cakti','Bangunan','Cilacap Tengah','Sidanegara',
    'Jl. Jend. Sudirman No. 32','—','Dalam Kajian',NULL,
    -7.72200000,109.01480000,
    'Pendopo Kabupaten Cilacap beserta perabot (gamelan), sumur, dapur, dan bekas kamar bupati yang bernilai sejarah.',
    'jatengprov.go.id; Tribun Jateng'
  UNION ALL SELECT 'Makam Adipati Cilacap Karangsuci','Situs','Cilacap Tengah','Gunungsimping',
    'Karangsuci','—','Ditetapkan','SK Bupati No. 556/204/15 Tahun 2019',
    NULL,NULL,
    'Kompleks makam Adipati Cilacap beserta keturunan dan kerabatnya. Koordinat belum tercatat lengkap pada Registrasi Nasional.',
    'Kemdikbud – Budaya Kita (objek KB004328)'
  UNION ALL SELECT 'Klenteng Kong Tik Su Cilacap','Bangunan','Cilacap Selatan','Sidakaya',
    'Kawasan Pecinan Cilacap','—','Objek Diduga Cagar Budaya',NULL,
    -7.72750000,109.01450000,
    'Rumah ibadah Tridharma di kawasan Pecinan lama Cilacap; salah satu penanda kawasan cagar budaya yang diusulkan.',
    'Catatan penelusuran; berbagai sumber'
  UNION ALL SELECT 'Masjid Agung Darussalam Cilacap','Bangunan','Cilacap Tengah','Sidanegara',
    'Sisi barat Alun-alun Cilacap','—','Objek Diduga Cagar Budaya',NULL,
    -7.72350000,109.01430000,
    'Masjid besar di sisi barat Alun-alun Cilacap; bagian dari kawasan pusat kota lama.',
    'Catatan penelusuran; berbagai sumber'
  UNION ALL SELECT 'Kawasan Kota Lama Cilacap','Kawasan','Cilacap Tengah','Sidanegara',
    'Koridor Jl. Jend. Ahmad Yani – Jl. Jend. Sudirman','—','Dalam Kajian',NULL,
    -7.72400000,109.01300000,
    'Kawasan bangunan bergaya indis: bekas rumah BRI Jl. Ahmad Yani, kantor Damri, gedung SMPN 1 Cilacap, dan sekitarnya. Lima objek di kawasan ini dikaji TACB pada 2026.',
    'Suara Merdeka Banyumas; jatengprov.go.id'
  UNION ALL SELECT 'Situs Gunung Selok','Situs','Adipala','Karangbenda',
    'Gunung Selok','—','Objek Diduga Cagar Budaya',NULL,
    -7.67800000,109.08300000,
    'Bukit di pesisir selatan dengan padepokan Jambe Lima & Jambe Pitu serta gua-gua bersejarah dan tempat ritual.',
    'Catatan penelusuran; berbagai sumber'
  UNION ALL SELECT 'Situs Gunung Srandil','Situs','Adipala','Glempangpasir',
    'Gunung Srandil','—','Objek Diduga Cagar Budaya',NULL,
    -7.68800000,109.07500000,
    'Bukit petilasan bertingkat berisi sejumlah makam dan tempat ritual yang ramai diziarahi.',
    'Catatan penelusuran; berbagai sumber'
) seed
WHERE (SELECT COUNT(*) FROM `cagar_budaya`) = 0;
