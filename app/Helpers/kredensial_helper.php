<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper untuk fitur "Reset & Kirim" kata sandi di Kelola Pengguna -
 * admin bisa mengirim email+kata sandi baru lewat WhatsApp (link
 * wa.me, membuka WhatsApp Web/App dengan pesan sudah terisi) atau
 * Email (link mailto:, membuka aplikasi email admin dengan subjek+isi
 * sudah terisi). Tidak ada pengiriman otomatis dari server - admin
 * yang menekan tombol kirim di aplikasi WA/email miliknya sendiri,
 * jadi tidak perlu konfigurasi SMTP atau API pihak ketiga.
 */

if (! function_exists('normalize_wa_number'))
{
	/** Ubah No. HP/WA (format bebas: spasi, strip, +62, 08xx, dll) jadi digit murni berawalan 62 untuk link wa.me. */
	function normalize_wa_number($no_hp)
	{
		$digit = preg_replace('/\D+/', '', (string) $no_hp);
		if ($digit === '') { return ''; }
		if (substr($digit, 0, 1) === '0') { $digit = '62' . substr($digit, 1); }
		return $digit;
	}
}

if (! function_exists('kredensial_pesan_teks'))
{
	/** Isi pesan (teks polos) kredensial login - dipakai untuk WA maupun body email. */
	function kredensial_pesan_teks($nama, $email, $password)
	{
		return "Yth. {$nama},\n\n"
			. "Berikut akun untuk masuk ke SIP Gatutkaca (Sistem Informasi Penataan Ruang) DPUPR Kabupaten Cilacap:\n\n"
			. "Email: {$email}\n"
			. "Kata Sandi: {$password}\n"
			. "Login: " . base_url('login') . "\n\n"
			. "Mohon segera ganti kata sandi setelah login pertama kali. Terima kasih.";
	}
}

if (! function_exists('kredensial_wa_link'))
{
	/** Link wa.me siap-kirim (kosong kalau no_hp tidak valid/tidak diisi). */
	function kredensial_wa_link($no_hp, $nama, $email, $password)
	{
		$nomor = normalize_wa_number($no_hp);
		if ($nomor === '') { return ''; }
		return 'https://wa.me/' . $nomor . '?text=' . rawurlencode(kredensial_pesan_teks($nama, $email, $password));
	}
}

if (! function_exists('kredensial_mailto_link'))
{
	/** Link mailto: siap-kirim (subjek + isi sudah terisi). */
	function kredensial_mailto_link($email_tujuan, $nama, $email, $password)
	{
		$subjek = 'Akun Login SIP Gatutkaca - ' . $nama;
		return 'mailto:' . $email_tujuan
			. '?subject=' . rawurlencode($subjek)
			. '&body=' . rawurlencode(kredensial_pesan_teks($nama, $email, $password));
	}
}
