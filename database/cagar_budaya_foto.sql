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
