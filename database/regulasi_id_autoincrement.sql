-- Migrasi tambahan bagi instalasi yang sudah menjalankan regulasi_crud.sql versi awal.
-- ID sudah AUTO_INCREMENT sejak tabel dibuat; kolom urutan manual tidak lagi dipakai.
ALTER TABLE `regulasi` DROP INDEX IF EXISTS `idx_regulasi_aktif_urutan`;
ALTER TABLE `regulasi` DROP COLUMN IF EXISTS `urutan`;
CREATE INDEX `idx_regulasi_aktif` ON `regulasi` (`aktif`);
