<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

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

		$data['error'] = $this->session->flashdata('error');
		$data['old']   = $this->session->flashdata('old');
		$this->load->view('pages/login', $data);
	}

	public function proses()
	{
		$user_input = trim((string) $this->input->post('user'));
		$password   = (string) $this->input->post('password');

		if ($user_input === '' || $password === '')
		{
			$this->session->set_flashdata('error', 'NIK/surel dan kata sandi wajib diisi.');
			$this->session->set_flashdata('old', array('user' => $user_input));
			redirect('login');
			return;
		}

		$this->db->where('email', $user_input);
		$this->db->or_where('nik', $user_input);
		$row = $this->db->get('users')->row_array();

		if ($row === NULL || ! password_verify($password, $row['password']))
		{
			$this->session->set_flashdata('error', 'NIK/surel atau kata sandi salah.');
			$this->session->set_flashdata('old', array('user' => $user_input));
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
	 * Tentukan halaman tujuan setelah login berhasil, sesuai peran.
	 * Saat ini baru peran 'admin' yang punya halaman khusus (kelola
	 * pengguna); peran lain diarahkan ke beranda.
	 */
	private function _tujuan_setelah_login($role)
	{
		if ($role === 'admin')
		{
			return 'admin/pengguna';
		}
		return '';
	}
}
