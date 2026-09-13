-- Upgrade non-destruktif untuk tabel ITR lama maupun versi formulir terbaru.
SET SESSION group_concat_max_len=10000;
SELECT CONCAT('ALTER TABLE pengajuan_itr MODIFY COLUMN status VARCHAR(30) NOT NULL DEFAULT ''diajukan'', MODIFY COLUMN no_hp VARCHAR(30) NULL',IFNULL(CONCAT(', ',GROUP_CONCAT(CONCAT('ADD COLUMN ',c.n,' ',c.d) SEPARATOR ', ')),'')) INTO @itr_upgrade
FROM (
 SELECT 'no_permohonan' n,'VARCHAR(50) NULL' d UNION ALL
 SELECT 'nik','VARCHAR(16) NOT NULL DEFAULT ''''' UNION ALL
 SELECT 'alamat_lokasi','TEXT NULL' UNION ALL
 SELECT 'luas_lahan','DECIMAL(16,2) NOT NULL DEFAULT 0' UNION ALL
 SELECT 'rencana_kegiatan','TEXT NULL' UNION ALL
 SELECT 'latitude','DECIMAL(10,7) NOT NULL DEFAULT 0' UNION ALL
 SELECT 'longitude','DECIMAL(11,7) NOT NULL DEFAULT 0' UNION ALL
 SELECT 'file_permohonan','VARCHAR(255) NOT NULL DEFAULT ''''' UNION ALL
 SELECT 'file_ktp','VARCHAR(255) NOT NULL DEFAULT ''''' UNION ALL
 SELECT 'file_sertifikat','VARCHAR(255) NOT NULL DEFAULT ''''' UNION ALL
 SELECT 'file_siteplan','VARCHAR(255) NOT NULL DEFAULT '''''
) c WHERE NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='pengajuan_itr' AND COLUMN_NAME=c.n);
PREPARE itr_upgrade_statement FROM @itr_upgrade;
EXECUTE itr_upgrade_statement;
DEALLOCATE PREPARE itr_upgrade_statement;
UPDATE pengajuan_itr SET no_permohonan=CONCAT('ITR-LEGACY-',LPAD(id,6,'0')) WHERE no_permohonan IS NULL;
UPDATE pengajuan_itr SET status='sedang_diverifikasi' WHERE status='diproses';
UPDATE pengajuan_itr SET status='disetujui' WHERE status='selesai';
CREATE TABLE IF NOT EXISTS pesan_itr (
 id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 pengajuan_id INT UNSIGNED NOT NULL,
 user_id INT UNSIGNED NOT NULL,
 admin_id INT UNSIGNED NULL,
 isi TEXT NOT NULL,
 dibaca_pada DATETIME NULL,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 KEY user_id (user_id), KEY pengajuan_id (pengajuan_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
