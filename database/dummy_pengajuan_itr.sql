START TRANSACTION;
INSERT INTO pengajuan_itr (user_id,no_permohonan,nama_pemohon,nik,no_hp,email,alamat_lokasi,luas_lahan,rencana_kegiatan,latitude,longitude,file_permohonan,file_ktp,file_sertifikat,file_siteplan,status)
SELECT id,'ITR-DEMO-0001','Budi Santoso','0000000000000000','000000000000','budi.santoso.itr@sipgatutkaca.local','Lokasi ilustrasi: Lomanis, Cilacap Tengah, Kabupaten Cilacap',250,'DATA DUMMY: rencana pembangunan rumah tinggal',-7.7267,109.0154,'dummy_itr.txt','dummy_itr.txt','dummy_itr.txt','dummy_itr.txt','diajukan'
FROM users WHERE email='budi.santoso.itr@sipgatutkaca.local' AND role='pemohon'
AND NOT EXISTS (SELECT 1 FROM pengajuan_itr WHERE no_permohonan='ITR-DEMO-0001');
INSERT INTO aktivitas_itr (user_id,pengajuan_id,keterangan)
SELECT user_id,id,'Pengajuan ITR-DEMO-0001 dikirim (data dummy).'
FROM pengajuan_itr i WHERE no_permohonan='ITR-DEMO-0001'
AND NOT EXISTS (SELECT 1 FROM aktivitas_itr WHERE pengajuan_id=i.id);
INSERT INTO pesan_itr (pengajuan_id,user_id,admin_id,isi)
SELECT id,user_id,(SELECT id FROM users WHERE role='admin' ORDER BY id LIMIT 1),'Contoh informasi admin: pengajuan Anda telah diterima dan akan diperiksa oleh petugas. Pantau status melalui dashboard. Ini merupakan pesan demonstrasi.'
FROM pengajuan_itr i WHERE no_permohonan='ITR-DEMO-0001'
AND NOT EXISTS (SELECT 1 FROM pesan_itr WHERE pengajuan_id=i.id);
COMMIT;
