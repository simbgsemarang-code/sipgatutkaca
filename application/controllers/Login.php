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
	 * Kredensial akun uji coba - ditampilkan LENGKAP (email + kata
	 * sandi, bukan cuma tombol berlabel) di halaman login, HANYA kalau
	 * ENVIRONMENT development (lihat index()). Ini bukan celah lewat
	 * jalur login: tombol "Masuk" di sebelahnya cuma submit form biasa
	 * ke proses(), yang tetap memverifikasi password lewat
	 * password_verify() seperti login manual - jadi kalau password di
	 * DB pernah diganti, baik tampilan maupun tombolnya otomatis tidak
	 * sinkron lagi (bukan diam-diam tetap tembus).
	 *
	 * 'grup' dipakai _akun_uji_untuk() untuk menyaring sesuai ?from=.
	 * Semua akun anggota grup yang sama ditampilkan sekaligus (mis.
	 * grup 'pu' menampilkan Ahmad Wijaya DAN Siti Rahmawati).
	 *
	 * Sengaja TIDAK ada akun uji coba untuk peran pemohon - beda dari
	 * admin/pu/tpa (dibuatkan admin lewat /admin/pengguna), pemohon
	 * sendiri yang mendaftar (lihat tampilkan_daftar di index()), jadi
	 * cara uji yang lebih apa adanya adalah daftar akun baru lewat
	 * halaman PBG/SLF, bukan lewat kredensial bersama yang dipajang.
	 */
	private $akun_uji = array(
		array('grup' => 'admin', 'label' => 'Admin',       'nama' => 'Administrator',     'email' => 'admin@sipgatutkaca.local',             'password' => 'f0250dc5621e'),
		array('grup' => 'pu',    'label' => 'PU',           'nama' => 'Ahmad Wijaya',      'email' => 'ahmad.wijaya@sipgatutkaca.local',      'password' => 'b596c84a9d7a'),
		array('grup' => 'pu',    'label' => 'PU',           'nama' => 'Siti Rahmawati',    'email' => 'siti.rahmawati@sipgatutkaca.local',    'password' => 'e2160c77feb5'),
		array('grup' => 'tpa',   'label' => 'TPA Arsitek',  'nama' => 'Rudi Hartono',      'email' => 'rudi.hartono@sipgatutkaca.local',      'password' => '191b9dc53b2d'),
		array('grup' => 'tpa',   'label' => 'TPA Struktur', 'nama' => 'Yulia Permatasari', 'email' => 'yulia.permatasari@sipgatutkaca.local', 'password' => 'b59981e87fad'),
		array('grup' => 'tpa',   'label' => 'TPA MEP',      'nama' => 'Hendra Kusnadi',    'email' => 'hendra.kusnadi@sipgatutkaca.local',    'password' => '6f21a582f9ec'),
	);

	/**
	 * Grup akun uji coba mana yang relevan untuk tiap nilai ?from=
	 * (SATU $from bisa memetakan ke BEBERAPA grup sekaligus - dipakai
	 * supaya ketiga portal staf menampilkan gabungan admin+pu+tpa,
	 * bukan cuma grupnya sendiri-sendiri, karena tombol "Masuk" di
	 * kop halaman manapun sama-sama mengarah ke login?from=admin dan
	 * staf sering perlu ganti-ganti akun peran saat menguji).
	 *
	 * 'pbg' dan 'slf' TETAP dipetakan HANYA ke grup 'pemohon' walau
	 * grup itu sekarang kosong (lihat $akun_uji) - PENTING supaya
	 * halaman login yang diakses publik lewat tombol Ajukan PBG/SLF
	 * tetap hanya menampilkan array kosong (panel disembunyikan),
	 * bukan malah jatuh ke default "tampilkan semua akun" di
	 * _akun_uji_untuk() sehingga kredensial admin/pu/tpa bocor ke
	 * pengunjung umum. JANGAN gabungkan 'pemohon' dengan grup staf di
	 * atas.
	 */
	private $peta_tombol_uji = array(
		'admin' => array('admin', 'pu', 'tpa'),
		'pu'    => array('admin', 'pu', 'tpa'),
		'tpa'   => array('admin', 'pu', 'tpa'),
		'pbg'   => array('pemohon'),
		'slf'   => array('pemohon'),
	);

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('token_akun');
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

		$data['from']     = $from;
		$data['error']    = $this->session->flashdata('error');
		$data['old']      = $this->session->flashdata('old');
		$data['sapaan']   = isset($this->peta_sapaan[$from]) ? $this->peta_sapaan[$from] : 'Selamat Datang';
		$data['akun_uji'] = $this->_akun_uji_untuk($from);
		// Cuma pemohon (lewat halaman PBG/SLF) yang bisa daftar sendiri -
		// akun PU/TPA/Admin tetap dibuatkan admin lewat /admin/pengguna.
		$data['tampilkan_daftar'] = in_array($from, array('pbg', 'slf'), TRUE);
		$this->load->view('pages/login', $data);
	}

	public function proses()
	{
		$email    = trim((string) $this->input->post('email'));
		$password = (string) $this->input->post('password');
		// Dibawa dari input tersembunyi di form login, supaya kalau login
		// gagal/diulang, halaman login tetap tahu berasal dari tombol PBG
		// atau SLF mana (sapaan & tombol uji coba tetap sesuai konteks).
		$from     = (string) $this->input->post('from');
		$tujuan_ulang = 'login' . ($from !== '' ? '?from=' . rawurlencode($from) : '');

		if ($email === '' || $password === '')
		{
			$this->session->set_flashdata('error', 'Email dan kata sandi wajib diisi.');
			$this->session->set_flashdata('old', array('email' => $email));
			redirect($tujuan_ulang);
			return;
		}

		$this->db->where('email', $email);
		$row = $this->db->get('users')->row_array();

		if ($row === NULL || ! password_verify($password, $row['password']))
		{
			$this->session->set_flashdata('error', 'Email atau kata sandi salah.');
			$this->session->set_flashdata('old', array('email' => $email));
			redirect($tujuan_ulang);
			return;
		}

		// Cegah session fixation: buat ulang ID sesi setelah login berhasil.
		$this->session->sess_regenerate(TRUE);

		$this->session->set_userdata(array(
			'logged_in'    => TRUE,
			'user_id'      => (int) $row['id'],
			'nama'         => $row['nama'],
			'email'        => $row['email'],
			'role'         => $row['role'],
			// Dipakai dashboard pemohon buat menampilkan "Portal Pemohon
			// PBG"/"Portal Pemohon SLF" sesuai tombol yang dipakai buat
			// masuk. Kosong kalau bukan lewat salah satu dari keduanya
			// (mis. login langsung, atau peran selain pemohon).
			'asal_layanan' => in_array($from, array('pbg', 'slf'), TRUE) ? $from : '',
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
			$this->token_akun->kirim_tautan($user, 'reset');
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
		$data['valid'] = $this->token_akun->token_valid((string) $token) !== NULL;
		$data['error'] = $this->session->flashdata('error');
		$this->load->view('pages/atur_ulang_password', $data);
	}

	public function proses_atur_ulang()
	{
		$token    = (string) $this->input->post('token');
		$password = (string) $this->input->post('password');
		$ulang    = (string) $this->input->post('ulang_password');

		$baris = $this->token_akun->token_valid($token);

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
	 * Daftar kredensial uji coba yang ditampilkan, disaring sesuai
	 * halaman/tombol asal ($from). Kalau $from cocok dengan salah satu
	 * peta_tombol_uji, HANYA akun yang grupnya ada di daftar grup
	 * $from itu yang ditampilkan (mis. 'admin'/'pu'/'tpa' sama-sama
	 * memetakan ke gabungan admin+pu+tpa, jadi ketiganya menampilkan
	 * akun staf yang sama - lihat catatan $peta_tombol_uji). Kalau
	 * tidak ada $from spesifik (kunjungan langsung ke /login), tampilkan
	 * semua akun dari semua grup. Selalu kosong di luar ENVIRONMENT
	 * development.
	 */
	private function _akun_uji_untuk($from)
	{
		if (ENVIRONMENT !== 'development')
		{
			return array();
		}

		if (isset($this->peta_tombol_uji[$from]))
		{
			$grup_diizinkan = $this->peta_tombol_uji[$from];
			return array_values(array_filter($this->akun_uji, function ($a) use ($grup_diizinkan) {
				return in_array($a['grup'], $grup_diizinkan, TRUE);
			}));
		}

		return $this->akun_uji;
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
			'admin'        => 'admin',
			'pu'           => 'pu',
			'tpa'          => 'tpa',
			'tpa_arsitek'  => 'tpa',
			'tpa_struktur' => 'tpa',
			'tpa_mep'      => 'tpa',
			'pemohon'      => 'pemohon',
		);
		return isset($peta_tujuan[$role]) ? $peta_tujuan[$role] : '';
	}
}
