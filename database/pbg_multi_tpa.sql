-- Allow multiple TPA in the same field and round. Safe to run repeatedly.
ALTER TABLE `konsultasi_pbg`
  DROP INDEX `putaran_bidang`,
  ADD UNIQUE KEY `putaran_bidang` (`permohonan_id`,`putaran`,`bidang`,`tpa_user_id`);
