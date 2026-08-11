<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	/** Sapaan kartu login disesuaikan dengan halaman/tombol asal. */
	private $peta_sapaan = array(
		'pbg'   => 'Selamat Datang PBG',
		'slf'   => 'Selamat Datang SLF',
		'tpa'   => 'Selamat Datang TPA',
		'pu'    => 'Selamat Datang PU',
		'admin' => 'Selamat Datang Admin',
	);

	/**
	 * Akun uji coba untuk tombol "masuk cepat" - HANYA ditampilkan kalau
	 * ENVIRONMENT development (lihat index()). Ini bukan celah lewat
	 * jalur login: tombolnya cuma submit form biasa ke proses(), yang
	 * tetap memverifikasi password lewat password_verify() seperti
	 * login manual - jadi kalau password di DB pernah diganti, tombol
	 * ini otomatis berhenti berfungsi (bukan diam-diam tetap tembus).
	 */
	private $akun_uji = array(
		'admin'   => array('label' => 'Admin',   'email' => 'admin@sipgatutkaca.local',      'password' => 'f0250dc5621e'),
		'pu'      => array('label' => 'PU',      'email' => 'pu.uji@sipgatutkaca.local',      'password' => '1965ad22f258'),
		'tpa'     => array('label' => 'TPA',     'email' => 'tpa.uji@sipgatutkaca.local',     'password' => '309997a80684'),
		'pemohon' => array('label' => 'Pemohon', 'email' => 'pemohon.uji@sipgatutkaca.local', 'password' => '092d2a5cd461'),
	);

	/**
	 * Tombol "masuk cepat" mana yang relevan untuk tiap nilai ?from=.
	 * PBG dan SLF sama-sama memakai akun 'pemohon' (satu-satunya peran
	 * warga/pemohon di sistem ini) tapi labelnya disesuaikan supaya
	 * terasa nyambung dengan halaman asalnya.
	 */
	private $peta_tombol_uji = array(
		'admin' => array('akun' => 'admin',   'label' => 'Admin'),
		'pu'    => array('akun' => 'pu',      'label' => 'PU'),
		'tpa'   => array('akun' => 'tpa',     'label' => 'TPA'),
		'pbg'   => array('akun' => 'pemohon', 'label' => 'PBG'),
		'slf'   => array('akun' => 'pemohon', 'label' => 'SLF'),
	);

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
	}

	public function index()
	{
		// Kalau sudah login, tidak perlu lihat form login lagi.
		if ($this->session->userdata('logged_in'))
		{
			redirect($this->_tujuan_setelah_login($this->session->userdata('role')));
			return;
		}

		$from = (string) $this->input->get('from');

		$data['error']    = $this->session->flashdata('error');
		$data['old']      = $this->session->flashdata('old');
		$data['sapaan']   = isset($this->peta_sapaan[$from]) ? $this->peta_sapaan[$from] : 'Selamat Datang';
		$data['akun_uji'] = $this->_akun_uji_untuk($from);
		$this->load->view('pages/login', $data);
	}

	public function proses()
	{
		$email    = trim((string) $this->input->post('email'));
		$password = (string) $this->input->post('password');

		if ($email === '' || $password === '')
		{
			$this->session->set_flashdata('error', 'Email dan kata sandi wajib diisi.');
			$this->session->set_flashdata('old', array('email' => $email));
			redirect('login');
			return;
		}

		$this->db->where('email', $email);
		$row = $this->db->get('users')->row_array();

		if ($row === NULL || ! password_verify($password, $row['password']))
		{
			$this->session->set_flashdata('error', 'Email atau kata sandi salah.');
			$this->session->set_flashdata('old', array('email' => $email));
			redirect('login');
			return;
		}

		// Cegah session fixation: buat ulang ID sesi setelah login berhasil.
		$this->session->sess_regenerate(TRUE);

		$this->session->set_userdata(array(
			'logged_in' => TRUE,
			'user_id'   => (int) $row['id'],
			'nama'      => $row['nama'],
			'email'     => $row['email'],
			'role'      => $row['role'],
		));

		redirect($this->_tujuan_setelah_login($row['role']));
	}

	public function keluar()
	{
		$this->session->sess_destroy();
		redirect('login');
	}

	/**
	 * Form permintaan tautan atur ulang kata sandi. Sengaja TIDAK
	 * dijaga dengan pengecekan "sudah login" seperti index() - supaya
	 * tetap bisa dipakai kalau browser yang sama kebetulan sedang
	 * login sebagai akun lain (mis. dipakai bersama/komputer admin).
	 */
	public function lupa_password()
	{
		$data['error']  = $this->session->flashdata('error');
		$data['sukses'] = $this->session->flashdata('sukses');
		$data['old']    = $this->session->flashdata('old');
		$this->load->view('pages/lupa_password', $data);
	}

	public function proses_lupa()
	{
		$email = trim((string) $this->input->post('email'));

		if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$this->session->set_flashdata('error', 'Masukkan alamat email yang valid.');
			$this->session->set_flashdata('old', array('email' => $email));
			redirect('login/lupa-password');
			return;
		}

		$this->db->where('email', $email);
		$user = $this->db->get('users')->row_array();

		if ($user !== NULL)
		{
			$this->_kirim_tautan_reset($user);
		}

		// Pesan sukses SELALU sama, baik email-nya terdaftar atau tidak -
		// supaya halaman ini tidak bisa dipakai menebak email mana yang
		// punya akun di sistem ini (mencegah user enumeration).
		$this->session->set_flashdata('sukses', 'Jika email tersebut terdaftar, tautan atur ulang kata sandi sudah kami kirim. Silakan cek kotak masuk (dan folder spam).');
		redirect('login/lupa-password');
	}

	/**
	 * Form kata sandi baru, diakses lewat tautan pada email. Validitas
	 * token dicek di sini supaya halaman langsung menampilkan pesan
	 * "tautan tidak berlaku" tanpa perlu submit form dulu.
	 */
	public function atur_ulang($token = null)
	{
		$data['token'] = (string) $token;
		$data['valid'] = $this->_token_valid((string) $token) !== NULL;
		$data['error'] = $this->session->flashdata('error');
		$this->load->view('pages/atur_ulang_password', $data);
	}

	public function proses_atur_ulang()
	{
		$token    = (string) $this->input->post('token');
		$password = (string) $this->input->post('password');
		$ulang    = (string) $this->input->post('ulang_password');

		$baris = $this->_token_valid($token);

		if ($baris === NULL)
		{
			$this->session->set_flashdata('error', 'Tautan sudah tidak berlaku. Silakan minta tautan baru.');
			redirect('login/lupa-password');
			return;
		}

		if (strlen($password) < 8)
		{
			$this->session->set_flashdata('error', 'Kata sandi minimal 8 karakter.');
			redirect('login/atur-ulang/' . $token);
			return;
		}

		if ($password !== $ulang)
		{
			$this->session->set_flashdata('error', 'Konfirmasi kata sandi tidak sama dengan kata sandi baru.');
			redirect('login/atur-ulang/' . $token);
			return;
		}

		$this->db->where('id', (int) $baris['user_id']);
		$this->db->update('users', array('password' => password_hash($password, PASSWORD_DEFAULT)));

		// Tandai dipakai (bukan dihapus) supaya tetap ada jejaknya, tapi
		// token yang sama tidak bisa dipakai dua kali.
		$this->db->where('id', (int) $baris['id']);
		$this->db->update('reset_password', array('dipakai_pada' => date('Y-m-d H:i:s')));

		$this->session->set_flashdata('sukses', 'Kata sandi berhasil diperbarui. Silakan masuk dengan kata sandi baru.');
		redirect('login');
	}

	/**
	 * Daftar tombol "masuk cepat" yang ditampilkan, disaring sesuai
	 * halaman/tombol asal ($from). Kalau $from cocok dengan salah satu
	 * peta_tombol_uji, cuma SATU tombol yang relevan yang ditampilkan.
	 * Kalau tidak ada $from spesifik (kunjungan langsung ke /login),
	 * tampilkan semua akun sebagai jaga-jaga supaya tetap bisa diuji.
	 * Selalu kosong di luar ENVIRONMENT development.
	 */
	private function _akun_uji_untuk($from)
	{
		if (ENVIRONMENT !== 'development')
		{
			return array();
		}

		if (isset($this->peta_tombol_uji[$from]))
		{
			$t    = $this->peta_tombol_uji[$from];
			$akun = $this->akun_uji[$t['akun']];
			return array(array('label' => $t['label'], 'email' => $akun['email'], 'password' => $akun['password']));
		}

		return array_values($this->akun_uji);
	}

	/**
	 * Tentukan halaman tujuan (dashboard) setelah login berhasil,
	 * sesuai peran. Peran di luar peta ini (seharusnya tidak pernah
	 * terjadi karena kolom role sudah dibatasi ENUM) diarahkan ke
	 * beranda sebagai jaga-jaga.
	 */
	private function _tujuan_setelah_login($role)
	{
		$peta_tujuan = array(
			'admin'   => 'admin/pengguna',
			'pu'      => 'pu',
			'tpa'     => 'tpa',
			'pemohon' => 'pemohon',
		);
		return isset($peta_tujuan[$role]) ? $peta_tujuan[$role] : '';
	}

	/**
	 * Buat token reset baru untuk $user dan kirim tautannya lewat email.
	 * Yang disimpan ke DB cuma HASH token (SHA-256) - token mentahnya
	 * cuma pernah ada di email yang dikirim, tidak pernah disimpan.
	 * Token lama milik user ini yang belum dipakai dibuang dulu supaya
	 * cuma satu tautan yang berlaku setiap saat.
	 */
	private function _kirim_tautan_reset($user)
	{
		// Jangan kirim ulang kalau baru saja ada permintaan untuk akun
		// yang sama (cegah kotak masuk orang lain dibanjiri kalau
		// formulir ini di-submit berkali-kali).
		$this->db->where('user_id', (int) $user['id']);
		$this->db->where('created_at >=', date('Y-m-d H:i:s', time() - 120));
		if ((int) $this->db->count_all_results('reset_password') > 0)
		{
			return;
		}

		$token      = bin2hex(random_bytes(32));
		$token_hash = hash('sha256', $token);

		$this->db->where('user_id', (int) $user['id']);
		$this->db->where('dipakai_pada', NULL);
		$this->db->delete('reset_password');

		$this->db->insert('reset_password', array(
			'user_id'     => (int) $user['id'],
			'token_hash'  => $token_hash,
			'kedaluwarsa' => date('Y-m-d H:i:s', time() + 3600),
		));

		$tautan = base_url('login/atur-ulang/' . $token);
		$domain = parse_url(base_url(), PHP_URL_HOST);

		// Preferensi (protocol, smtp_*, dst) diambil otomatis dari
		// application/config/email.php - JANGAN initialize() manual di
		// sini, supaya kalau admin ganti config itu ke SMTP, perubahannya
		// benar-benar kepakai (bukan ketimpa balik ke 'mail').
		$this->load->library('email');
		$this->email->from('no-reply@' . $domain, 'SIP Gatutkaca');
		$this->email->reply_to('siptaru@cilacapkab.go.id', 'DPUPR Kabupaten Cilacap');
		$this->email->to($user['email']);
		$this->email->subject('Atur Ulang Kata Sandi - SIP Gatutkaca');
		$this->email->message(
			"Halo " . $user['nama'] . ",\n\n" .
			"Kami menerima permintaan atur ulang kata sandi untuk akun SIP Gatutkaca Anda.\n\n" .
			"Klik tautan berikut untuk membuat kata sandi baru (berlaku 1 jam sejak email ini dikirim):\n" . $tautan . "\n\n" .
			"Kalau Anda tidak merasa meminta ini, abaikan saja email ini - kata sandi Anda tidak akan berubah.\n\n" .
			"Salam,\nSIP Gatutkaca - DPUPR Kabupaten Cilacap"
		);

		// @ di sini sengaja: kalau server tidak bisa kirim email (mis.
		// sendmail belum terkonfigurasi), pengguna tetap melihat pesan
		// sukses yang sama (lihat proses_lupa()) supaya tidak bocor info
		// akun mana yang terdaftar. Kegagalan kirim sebaiknya dipantau
		// lewat log server, bukan lewat respons ke pengguna.
		@$this->email->send();
	}

	/**
	 * Cari baris reset_password yang cocok dengan $token, dan masih
	 * berlaku (belum kedaluwarsa, belum dipakai). Token mentah di-hash
	 * dulu sebelum dicocokkan karena yang tersimpan di DB cuma hash-nya.
	 */
	private function _token_valid($token)
	{
		if ($token === '')
		{
			return NULL;
		}

		$this->db->where('token_hash', hash('sha256', $token));
		$this->db->where('dipakai_pada', NULL);
		$this->db->where('kedaluwarsa >=', date('Y-m-d H:i:s'));
		return $this->db->get('reset_password')->row_array();
	}
}
