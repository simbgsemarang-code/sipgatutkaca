-- Seragamkan penulisan kolom `fungsi` di bangunan_gis supaya saringan
-- Fungsi di peta Analisa Kerusakan ringkas (9 pilihan, bukan 23).
-- Aman dijalankan berulang: baris yang sudah rapi tidak tersentuh.
-- Bangunan dengan lebih dari satu fungsi digabung jadi 'Campuran'.

UPDATE `bangunan_gis` SET `fungsi` = 'Perkantoran'
 WHERE `fungsi` IN ('Bangunan Gedung Kantor Permanen', 'Bangunan Gedung Kantor Lain-lain');

UPDATE `bangunan_gis` SET `fungsi` = 'Keagamaan'
 WHERE `fungsi` IN ('Peribadahan', 'Tempat Ibadah');

UPDATE `bangunan_gis` SET `fungsi` = 'Usaha'
 WHERE `fungsi` = 'Gedung Pertokoan/Koperasi/Pasar Permanen';

UPDATE `bangunan_gis` SET `fungsi` = 'Sosial Budaya'
 WHERE `fungsi` = 'Bangunan Gedung Pertemuan Permanen';

UPDATE `bangunan_gis` SET `fungsi` = 'Sarana Pendukung'
 WHERE `fungsi` IN ('MCK', 'Sarana Pendukung Olaraga', 'Gedung Garasi/Pool Semi Permanen', 'Bangunan Gudang Lin-lain (dst)');

UPDATE `bangunan_gis` SET `fungsi` = 'Campuran'
 WHERE `fungsi` IN ('Hunian dan Perkantoran', 'Hunian dan Sosial Budaya', 'Sarana Pendukung dan Keagamaan', 'Keagamaan dan Sarana Pendukung', 'Perkantoran, Sosial Budaya dan Keagamaan');
