<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Token sekali-pakai yang dikirim lewat email untuk mengizinkan
 * seseorang membuat/mengganti kata sandinya sendiri. Dipakai bareng
 * oleh dua alur yang secara teknis sama persis:
 *   - Login::proses_lupa()  -> "lupa kata sandi" (konteks 'reset')
 *   - Daftar::proses()      -> "aktivasi akun baru" (konteks 'aktivasi')
 * supaya logika pembuatan token, penyimpanan hash-nya, dan pengiriman
 * emailnya cuma ada di SATU tempat (bukan disalin dua kali - ini kode
 * yang berkaitan langsung dengan keamanan akun, jadi sebaiknya jangan
 * gampang menyimpang antara dua salinan).
 *
 * Tabel yang dipakai: reset_password (lihat database/reset_password.sql).
 */
class Token_akun {

	/** @var CI_Controller */
	private $ci;

	public function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->database();
	}

	/**
	 * Buat token baru untuk $user (array dengan minimal 'id', 'nama',
	 * 'email') dan kirim tautannya lewat email. $konteks cuma
	 * menentukan salam pembuka email ('aktivasi' vs 'reset') -
	 * mekanisme tokennya sendiri sama persis untuk keduanya.
	 *
	 * Token mentah TIDAK PERNAH disimpan ke DB - yang tersimpan cuma
	 * hash SHA-256-nya, supaya kalau tabel reset_password ini bocor,
	 * isinya tidak langsung bisa dipakai mengambil alih akun siapa pun.
	 * Token lama milik user ini yang belum dipakai dibuang dulu supaya
	 * cuma satu tautan yang berlaku setiap saat.
	 */
	public function kirim_tautan($user, $konteks = 'reset')
	{
		// Jangan kirim ulang kalau baru saja ada permintaan untuk akun
		// yang sama (cegah kotak masuk orang lain dibanjiri kalau
		// formulir terkait di-submit berkali-kali).
		$this->ci->db->where('user_id', (int) $user['id']);
		$this->ci->db->where('created_at >=', date('Y-m-d H:i:s', time() - 120));
		if ((int) $this->ci->db->count_all_results('reset_password') > 0)
		{
			return;
		}

		$token      = bin2hex(random_bytes(32));
		$token_hash = hash('sha256', $token);

		$this->ci->db->where('user_id', (int) $user['id']);
		$this->ci->db->where('dipakai_pada', NULL);
		$this->ci->db->delete('reset_password');

		$this->ci->db->insert('reset_password', array(
			'user_id'     => (int) $user['id'],
			'token_hash'  => $token_hash,
			'kedaluwarsa' => date('Y-m-d H:i:s', time() + 3600),
		));

		$tautan = base_url('login/atur-ulang/' . $token);
		$domain = parse_url(base_url(), PHP_URL_HOST);

		if ($konteks === 'aktivasi')
		{
			$subjek  = 'Aktivasi Akun - SIP Gatutkaca';
			$pembuka = 'Terima kasih sudah mendaftar di SIP Gatutkaca.';
			$ajakan  = 'Klik tautan berikut untuk membuat kata sandi dan mengaktifkan akun Anda (berlaku 1 jam):';
		}
		else
		{
			$subjek  = 'Atur Ulang Kata Sandi - SIP Gatutkaca';
			$pembuka = 'Kami menerima permintaan atur ulang kata sandi untuk akun SIP Gatutkaca Anda.';
			$ajakan  = 'Klik tautan berikut untuk membuat kata sandi baru (berlaku 1 jam sejak email ini dikirim):';
		}

		// Preferensi (protocol, smtp_*, dst) diambil otomatis dari
		// application/config/email.php.
		$this->ci->load->library('email');
		$this->ci->email->from('no-reply@' . $domain, 'SIP Gatutkaca');
		$this->ci->email->reply_to('siptaru@cilacapkab.go.id', 'DPUPR Kabupaten Cilacap');
		$this->ci->email->to($user['email']);
		$this->ci->email->subject($subjek);
		$this->ci->email->message(
			'Halo ' . $user['nama'] . ",\n\n" .
			$pembuka . "\n\n" .
			$ajakan . "\n" . $tautan . "\n\n" .
			"Kalau Anda tidak merasa meminta ini, abaikan saja email ini - tidak ada perubahan apa pun pada akun Anda.\n\n" .
			"Salam,\nSIP Gatutkaca - DPUPR Kabupaten Cilacap"
		);

		// @ di sini sengaja: kalau server tidak bisa kirim email (mis.
		// sendmail belum terkonfigurasi), pemanggil tetap menampilkan
		// pesan sukses yang sama supaya tidak bocor info akun mana yang
		// terdaftar/berhasil dibuat. Kegagalan kirim sebaiknya dipantau
		// lewat log server, bukan lewat respons ke pengguna.
		@$this->ci->email->send();
	}

	/**
	 * Cari baris reset_password yang cocok dengan $token, dan masih
	 * berlaku (belum kedaluwarsa, belum dipakai). Token mentah di-hash
	 * dulu sebelum dicocokkan karena yang tersimpan di DB cuma hash-nya.
	 */
	public function token_valid($token)
	{
		if ($token === '')
		{
			return NULL;
		}

		$this->ci->db->where('token_hash', hash('sha256', $token));
		$this->ci->db->where('dipakai_pada', NULL);
		$this->ci->db->where('kedaluwarsa >=', date('Y-m-d H:i:s'));
		return $this->ci->db->get('reset_password')->row_array();
	}
}
