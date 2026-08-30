-- Isi kolom `foto` untuk objek cagar budaya yang fotonya tersedia di
-- Wikimedia Commons (lisensi bebas). Aman dijalankan ulang (UPDATE by nama).
-- Jalankan lewat phpMyAdmin -> pilih database -> tab SQL -> paste -> Go.

UPDATE `cagar_budaya` SET `foto` = 'https://commons.wikimedia.org/wiki/Special:FilePath/Benteng_Pendem_Cilacap_panorama.jpg?width=1400'
  WHERE `nama` LIKE 'Benteng Pendem%' AND (`foto` IS NULL OR `foto` = '');

UPDATE `cagar_budaya` SET `foto` = 'https://commons.wikimedia.org/wiki/Special:FilePath/West_end_of_Karang_Bolong,_Nusakembangan,_Cilacap_2015-03-21.jpg?width=1400'
  WHERE `nama` = 'Benteng Karangbolong' AND (`foto` IS NULL OR `foto` = '');

UPDATE `cagar_budaya` SET `foto` = 'https://commons.wikimedia.org/wiki/Special:FilePath/COLLECTIE_TROPENMUSEUM_Weg_omhoog_naar_een_vuurtoren_Nederlands-Indi%C3%AB_TMnr_60054013.jpg?width=1000'
  WHERE `nama` = 'Mercusuar Cimiring' AND (`foto` IS NULL OR `foto` = '');

UPDATE `cagar_budaya` SET `foto` = 'https://commons.wikimedia.org/wiki/Special:FilePath/The_frontage_of_Cilacap_Station_-_2025.jpg?width=1400'
  WHERE `nama` = 'Stasiun Cilacap' AND (`foto` IS NULL OR `foto` = '');

UPDATE `cagar_budaya` SET `foto` = 'https://commons.wikimedia.org/wiki/Special:FilePath/Grand_mosque_of_cilacap.jpg?width=1400'
  WHERE `nama` = 'Masjid Agung Darussalam Cilacap' AND (`foto` IS NULL OR `foto` = '');

-- Situs Gunung Selok: foto Pantai Selok (Wikimedia Commons, CC BY-SA 4.0,
-- oleh Ardiwibowo) + koreksi koordinat (seed lama 109.08 keliru -> geser
-- ke Karangkandri; posisi sebenarnya ± 7°41,5' LS 109°10,5' BT).
UPDATE `cagar_budaya` SET `foto` = 'https://commons.wikimedia.org/wiki/Special:FilePath/Detik-Detik_Tenggelamnya_Matahari_Di_Pantai_Selok_1.jpg?width=1400'
  WHERE `nama` = 'Situs Gunung Selok' AND (`foto` IS NULL OR `foto` = '');

UPDATE `cagar_budaya` SET `latitude` = -7.69170000, `longitude` = 109.17500000
  WHERE `nama` = 'Situs Gunung Selok' AND ROUND(`longitude`, 2) = 109.08;
