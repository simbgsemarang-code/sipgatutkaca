-- Penggantian penuh data cagar budaya berdasarkan:
-- "koordinat cagar budaya.xlsx", lembar ODCB, baris 4-34.
-- Lembar ODCB sudah memuat 23 ODCB dan 8 Cagar Budaya; lembar CB tidak
-- diimpor lagi agar delapan Cagar Budaya tersebut tidak terduplikasi.

START TRANSACTION;

DELETE FROM `cagar_budaya`;
ALTER TABLE `cagar_budaya` AUTO_INCREMENT = 1;

INSERT INTO `cagar_budaya`
(`nama`,`kategori`,`kecamatan`,`kelurahan`,`alamat`,`tahun`,`status`,`no_sk`,`latitude`,`longitude`,`deskripsi`,`sumber`,`foto`)
VALUES
('Bangunan E4 Mes','Bangunan',NULL,NULL,'Jl Jendral Sudirman Cilacap',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2015/62',-7.72811100,109.00833300,'Nomor Register Nasional: PO2015021100011. Pemilik/Pengelola: KODIM CILACAP.','koordinat cagar budaya.xlsx',NULL),
('Kantor Kasdim (Wakil Dandim)','Bangunan',NULL,NULL,'Jl Jendral Sudirman KODIM',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2015/63',-7.72811821,109.00778376,'Nomor Register Nasional: PO2015021100012. Pemilik/Pengelola: KODIM CILACAP.','koordinat cagar budaya.xlsx',NULL),
('Rumah Dinas Dandim','Bangunan',NULL,NULL,'Jl Jendral Sudirman KODIM',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2015/64',-7.72812884,109.00808417,'Nomor Register Nasional: PO2015021100013. Pemilik/Pengelola: KODIM CILACAP.','koordinat cagar budaya.xlsx',NULL),
('Rumah Piket Kodim Cilacap','Bangunan',NULL,NULL,'Jl Jendral Sudirman KODIM',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2015/65',-7.72812884,109.00830947,'Nomor Register Nasional: PO2015021100014. Pemilik/Pengelola: KODIM CILACAP.','koordinat cagar budaya.xlsx',NULL),
('Sanggar Pramuka','Bangunan',NULL,NULL,'Jl Jendral Sudirman KODIM',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2015/66',-7.72811100,109.00879400,'Nomor Register Nasional: PO2015021100015. Pemilik/Pengelola: KODIM CILACAP.','koordinat cagar budaya.xlsx',NULL),
('Kantor Minvet AD','Bangunan',NULL,NULL,'Jl Jendral Sudirman KODIM',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2015/67',-7.72812800,109.00853900,'Nomor Register Nasional: PO2015021100016. Pemilik/Pengelola: KODIM CILACAP.','koordinat cagar budaya.xlsx',NULL),
('Stasiun Kereta Api Cilacap','Bangunan',NULL,NULL,'Jalan Aipda KS Tubun No. 1',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2017/2',-7.73581261,109.00704884,'Nomor Register Nasional: PO2017032700011. Pemilik/Pengelola: PT KAI DAOP V.','koordinat cagar budaya.xlsx',NULL),
('Pendopo Kabupaten Cilacap','Bangunan',NULL,NULL,'Jl. Jendral Sudirman Cilacap',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2018/4',-7.72573411,109.00967740,'Nomor Register Nasional: PO2018040401567. Pemilik/Pengelola: PEMDA CILACAP.','koordinat cagar budaya.xlsx',NULL),
('Gedung SMPN 1 Cilacap','Bangunan',NULL,NULL,'Jl. Ahmad Yani Cilacap',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2018/5',-7.73844703,109.00928039,'Nomor Register Nasional: PO2018040401187. Pemilik/Pengelola: PEMDA CILACAP.','koordinat cagar budaya.xlsx',NULL),
('Gereja Katolik Indis','Bangunan',NULL,NULL,'Jl. Ahmad Yani',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2022/4',-7.73887493,109.00966662,'Pemilik/Pengelola: Yayasan Paroki Santo Stephanus.','koordinat cagar budaya.xlsx',NULL),
('Klenteng Lam Tjeng Kiong','Bangunan',NULL,NULL,'Jl. Martadinata',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2022/5',-7.73278617,109.00910950,NULL,'koordinat cagar budaya.xlsx',NULL),
('Lapas IIB Cilacap','Bangunan',NULL,NULL,'Jl. Mataram I (Timur Alun Alun Cilacap)',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2023/1',-7.72712853,109.01007464,'Pemilik/Pengelola: Kementerian Imigrasi dan Pemasyarakatan.','koordinat cagar budaya.xlsx',NULL),
('Kantor Damri','Bangunan',NULL,NULL,'Jl. A. Yani A Cilacap',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2024/4',-7.73796863,109.00909800,'Pemilik/Pengelola: Perum. DAMRI.','koordinat cagar budaya.xlsx',NULL),
('Rumah Dinas Stasiun Cilacap No. 1','Bangunan',NULL,NULL,'Jl. Pasar RT 1 RW 1',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2015/34',-7.73525000,109.00738900,'Nomor Register Nasional: PO2015020300053. Pemilik/Pengelola: PT KAI DAOP V.','koordinat cagar budaya.xlsx',NULL),
('Rumah Dinas Stasiun Cilacap No. 2','Bangunan',NULL,NULL,'Jl. Pasar RT 1 RW 1 No. 1',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2015/35',-7.73522200,109.00750000,'Nomor Register Nasional: PO2015020300055. Pemilik/Pengelola: PT KAI DAOP V.','koordinat cagar budaya.xlsx',NULL),
('Rumah Tinggal BRI No. 1','Bangunan',NULL,NULL,'Jl. A. Yani No. 1',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2015/69',-7.74069400,109.00925000,'Nomor Register Nasional: PO2015020300056. Pemilik/Pengelola: BRI KANWIL YOGYAKARTA.','koordinat cagar budaya.xlsx',NULL),
('Rumah Tinggal BRI No. 2','Bangunan',NULL,NULL,'Jl. Veteran',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2015/36',-7.74063900,109.00888900,'Nomor Register Nasional: PO2015020300057. Pemilik/Pengelola: BRI KANWIL YOGYAKARTA.','koordinat cagar budaya.xlsx',NULL),
('Rumah Tinggal Veteran No. 44','Bangunan',NULL,NULL,'Jl. Veteran (barat MakinBar)',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2015/37',-7.74086100,109.00819400,'Nomor Register Nasional: PO2015020300058. Pemilik/Pengelola: PERUSDA PROVINSI.','koordinat cagar budaya.xlsx',NULL),
('Rumah Tinggal Veteran No. 48','Bangunan',NULL,NULL,'Jl. Veteran 46, 46A-48 (Resto Makin Bar)',NULL,'Objek Diduga Cagar Budaya','ODCB/BA/2015/42',-7.74075000,109.00802800,'Nomor Register Nasional: PO2015020500060. Pemilik/Pengelola: PERUSDA PROVINSI.','koordinat cagar budaya.xlsx',NULL),
('Rumah Kuno 1','Bangunan',NULL,NULL,'Jl. A. Yani',NULL,'Objek Diduga Cagar Budaya',NULL,-7.72928902,109.00927196,NULL,'koordinat cagar budaya.xlsx',NULL),
('Rumah Kuno 2','Bangunan',NULL,NULL,'Jl. A. Yani',NULL,'Objek Diduga Cagar Budaya',NULL,-7.73044784,109.00927331,NULL,'koordinat cagar budaya.xlsx',NULL),
('Rumah Kuno 3','Bangunan',NULL,NULL,'Jl. May. Jend. Sutoyo',NULL,'Objek Diduga Cagar Budaya',NULL,-7.73277536,109.01011818,NULL,'koordinat cagar budaya.xlsx',NULL),
('Rumah Kuno 4','Bangunan',NULL,NULL,'Jl. May. Jend. Sutoyo',NULL,'Objek Diduga Cagar Budaya',NULL,-7.73256093,109.01103509,NULL,'koordinat cagar budaya.xlsx',NULL),
('Pintu Gerbang Regol Kabupaten','Struktur',NULL,NULL,'Jl. Jend. Soedirman',NULL,'Ditetapkan','432.25/629/15/TAHUN 2020',-7.72644015,109.00957935,'Pemilik/Pengelola: PEMDA CILACAP.','koordinat cagar budaya.xlsx',NULL),
('Lonceng Kuno','Benda',NULL,NULL,'Jl. Jend. Soedirman',NULL,'Ditetapkan','556/204/15/TAHUN 2019',-7.72644081,109.00946304,'Pemilik/Pengelola: PEMDA CILACAP.','koordinat cagar budaya.xlsx',NULL),
('Masjid Agung Darussalam Cilacap','Bangunan',NULL,NULL,'Jl. Jend. Soedirman',NULL,'Ditetapkan','556/204/15/TAHUN 2020',-7.72701245,109.00864571,'Pemilik/Pengelola: YAYASAN MASJID AGUNG DARUSSALAM.','koordinat cagar budaya.xlsx',NULL),
('Gedung Kantor Disporapar Kabupaten Cilacap (Eks Kantor Asisten Residen Cilacap)','Bangunan',NULL,NULL,'Jl. A. Yani',NULL,'Ditetapkan','556/204/15/TAHUN 2021',-7.74001980,109.00963491,'Pemilik/Pengelola: PEMDA CILACAP.','koordinat cagar budaya.xlsx',NULL),
('Lokasi Pemerintahan Bupati Cilacap','Situs',NULL,NULL,'Jl. Jend. Soedirman',NULL,'Ditetapkan','400.6.2/68/15/TAHUN 2026',-7.72638800,109.00948300,'Pemilik/Pengelola: PEMDA CILACAP. Keterangan sumber: Proses SK 2026.','koordinat cagar budaya.xlsx',NULL),
('Lokasi Kantor Asisten Residen Cilacap','Situs',NULL,NULL,'Jl. Jend. Soedirman',NULL,'Ditetapkan','400.6.2/69/15/TAHUN 2026',-7.72553000,109.00952300,'Pemilik/Pengelola: PEMDA CILACAP. Keterangan sumber: Proses SK 2026.','koordinat cagar budaya.xlsx',NULL),
('Satuan Ruang Geografis Kota Lama Cilacap','Kawasan',NULL,NULL,'Jl. A. Yani',NULL,'Ditetapkan','400.6.2/70/15/TAHUN 2026',-7.74081900,109.00944300,'Pemilik/Pengelola: PEMDA CILACAP. Keterangan sumber: Proses SK 2026.','koordinat cagar budaya.xlsx',NULL),
('Bangunan Ruang Kelas SMP Negeri 8 Cilacap Eks Holland Inlandsche School','Bangunan',NULL,NULL,'Jl. Jend. Soedirman',NULL,'Ditetapkan','400.6.2/1102/15/TAHUN 2025',-7.72768527,109.01095018,'Pemilik/Pengelola: PEMDA CILACAP. Keterangan sumber: Proses SK 2025.','koordinat cagar budaya.xlsx',NULL);

COMMIT;
