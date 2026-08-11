<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Dipakai oleh fitur "Lupa Kata Sandi" untuk mengirim tautan atur ulang.
|
| Bawaan: protocol 'mail' -> memanggil fungsi mail() bawaan PHP lewat
| sendmail di server. Ini TIDAK butuh exec()/shell_exec() dkk, jadi
| aman dipakai di hosting ini walau disable_functions mematikan semua
| fungsi shell (lihat DEPLOY.md).
|
| Kalau setelah dicoba email tidak pernah sampai (banyak terjadi di
| shared hosting karena SPF/DKIM domain pengirim tidak cocok), ganti
| $config['protocol'] jadi 'smtp' dan isi blok SMTP di bawah dengan
| kredensial akun email SMTP yang sungguhan (misalnya email hosting cPanel
| Anda sendiri, atau layanan seperti Gmail/SendGrid/Mailgun).
*/
$config['protocol']    = 'mail';
$config['mailtype']    = 'text';
$config['charset']     = 'utf-8';
$config['newline']     = "\r\n";
$config['wordwrap']    = TRUE;

// ---- Blok SMTP (aktifkan kalau protocol 'mail' tidak mengirim email) ----
// $config['protocol']    = 'smtp';
// $config['smtp_host']   = 'mail.namadomainanda.go.id'; // atau ssl://... untuk port 465
// $config['smtp_port']   = 587;
// $config['smtp_user']   = 'no-reply@namadomainanda.go.id';
// $config['smtp_pass']   = 'kata_sandi_email_smtp';
// $config['smtp_crypto'] = 'tls'; // isi 'tls' untuk port 587, kosongkan untuk port 465 (sudah pakai ssl:// di smtp_host)
// $config['smtp_timeout']= 10;
