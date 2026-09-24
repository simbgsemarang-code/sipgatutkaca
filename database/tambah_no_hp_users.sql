-- Tambah kolom No. HP/WhatsApp di tabel users, dipakai fitur "Reset &
-- Kirim" kata sandi di Kelola Pengguna (kirim kredensial lewat WhatsApp
-- - lihat application/helpers/kredensial_helper.php). Opsional, boleh
-- kosong (pengguna lama tanpa No. HP tetap bisa dikirimi lewat Email).
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `no_hp` VARCHAR(20) NULL AFTER `nik`;
