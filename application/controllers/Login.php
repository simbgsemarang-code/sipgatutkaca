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
		array('label' => 'Admin',   'email' => 'admin@sipgatutkaca.local',      'password' => 'f0250dc5621e'),
		array('label' => 'PU',      'email' => 'pu.uji@sipgatutkaca.local',      'password' => '1965ad22f258'),
		array('label' => 'TPA',     'email' => 'tpa.uji@sipgatutkaca.local',     'password' => '309997a80684'),
		array('label' => 'Pemohon', 'email' => 'pemohon.uji@sipgatutkaca.local', 'password' => '092d2a5cd461'),
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

		$data['error']   = $this->session->flashdata('error');
		$data['old']     = $this->session->flashdata('old');
		$data['sapaan']  = isset($this->peta_sapaan[$from]) ? $this->peta_sapaan[$from] : 'Selamat Datang';
		$data['akun_uji'] = (ENVIRONMENT === 'development') ? $this->akun_uji : array();
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
}
