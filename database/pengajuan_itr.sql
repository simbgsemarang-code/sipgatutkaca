CREATE TABLE IF NOT EXISTS pengajuan_itr (
 id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 user_id INT UNSIGNED NOT NULL,
 no_permohonan VARCHAR(50) NULL,
 nama_pemohon VARCHAR(150) NOT NULL,
 nik VARCHAR(16) NOT NULL,
 no_hp VARCHAR(30) NOT NULL,
 email VARCHAR(150) NOT NULL,
 alamat_lokasi TEXT NOT NULL,
 luas_lahan DECIMAL(16,2) NOT NULL,
 rencana_kegiatan TEXT NOT NULL,
 latitude DECIMAL(10,7) NOT NULL,
 longitude DECIMAL(11,7) NOT NULL,
 file_permohonan VARCHAR(255) NOT NULL,
 file_ktp VARCHAR(255) NOT NULL,
 file_sertifikat VARCHAR(255) NOT NULL,
 file_siteplan VARCHAR(255) NOT NULL,
 status VARCHAR(30) NOT NULL DEFAULT 'diajukan',
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY no_permohonan (no_permohonan), KEY user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS aktivitas_itr (
 id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 user_id INT UNSIGNED NOT NULL,
 pengajuan_id INT UNSIGNED NOT NULL,
 keterangan TEXT NOT NULL,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY user_id (user_id), KEY pengajuan_id (pengajuan_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
