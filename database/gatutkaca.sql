CREATE DATABASE IF NOT EXISTS `gatutkaca` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gatutkaca`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nik` VARCHAR(20) DEFAULT NULL,
  `nama` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('pemohon','admin') NOT NULL DEFAULT 'pemohon',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `konsultan` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `no_lisensi` VARCHAR(50) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pengajuan_itr` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED DEFAULT NULL,
  `nama_pemohon` VARCHAR(150) NOT NULL,
  `alamat` TEXT,
  `no_hp` VARCHAR(20) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `lokasi_tanah` TEXT,
  `keterangan` TEXT,
  `file_pendukung` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('diajukan','diproses','selesai','ditolak') NOT NULL DEFAULT 'diajukan',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `konsultasi` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED DEFAULT NULL,
  `nama` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `no_hp` VARCHAR(20) DEFAULT NULL,
  `topik` VARCHAR(200) DEFAULT NULL,
  `pesan` TEXT,
  `status` ENUM('menunggu','dibalas','selesai') NOT NULL DEFAULT 'menunggu',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
