-- Pengaturan Google Drive lewat UI admin (menggantikan edit manual
-- application/config/gdrive.php + application/gdrive/*.json lewat cPanel).
-- Satu baris saja (id selalu 1). Lihat Admin::pengaturan_drive().
CREATE TABLE IF NOT EXISTS `pengaturan_gdrive` (
  `id` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `enabled` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `auth_mode` VARCHAR(20) NOT NULL DEFAULT 'oauth' COMMENT 'oauth atau service_account',
  `folder_id` VARCHAR(191) NULL,
  `oauth_client_id` VARCHAR(255) NULL,
  `oauth_client_secret` VARCHAR(255) NULL,
  `oauth_refresh_token` TEXT NULL,
  `service_account_json` TEXT NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
