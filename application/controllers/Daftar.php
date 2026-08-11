<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daftar extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
	}

	public function index()
	{
		// Kalau sudah login, tidak perlu lihat form pendaftaran lagi.
		if ($this->session->userdata('logged_in'))
		{
			redirect($this->_tujuan_setelah_login($this->session->userdata('role')));
			return;
		}

		$data['error'] = $this->session->flashdata('error');
		$data['old']   = $this->session->flashdata('old');
		$this->load->view('pages/daftar', $data);
	}

	public function proses()
	{
		$nama          = trim((string) $this->input->post('nama'));
		$email         = trim((string) $this->input->post('email'));
		$nik_mentah    = trim((string) $this->input->post('nik'));
		$nik           = preg_replace('/[^0-9]/', '', $nik_mentah);
		$password      = (string) $this->input->post('password');
		$ulang_password = (string) $this->input->post('ulang_password');

		$old = array('nama' => $nama, 'email' => $email, 'nik' => $nik_mentah);

		if ($nama === '' || $email === '' || $nik_mentah === '' || $password === '' || $ulang_password === '')
		{
			$this->session->set_flashdata('error', 'Semua kolom wajib diisi.');
			$this->session->set_flashdata('old', $old);
			redirect('daftar');
			return;
		}

		if ( ! filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$this->session->set_flashdata('error', 'Masukkan alamat email yang valid.');
			$this->session->set_flashdata('old', $old);
			redirect('daftar');
			return;
		}

		if (strlen($nik) < 15 || strlen($nik) > 16)
		{
			$this->session->set_flashdata('error', 'NIK/NPWP harus berupa 15-16 digit angka.');
			$this->session->set_flashdata('old', $old);
			redirect('daftar');
			return;
		}

		if (strlen($password) < 8)
		{
			$this->session->set_flashdata('error', 'Kata sandi minimal 8 karakter.');
			$this->session->set_flashdata('old', $old);
			redirect('daftar');
			return;
		}

		if ($password !== $ulang_password)
		{
			$this->session->set_flashdata('error', 'Konfirmasi kata sandi tidak sama dengan kata sandi.');
			$this->session->set_flashdata('old', $old);
			redirect('daftar');
			return;
		}

		$this->db->where('email', $email);
		if ($this->db->get('users')->row_array() !== NULL)
		{
			$this->session->set_flashdata('error', 'Email ini sudah terdaftar. Kalau ini akun Anda, gunakan halaman lupa kata sandi.');
			$this->session->set_flashdata('old', $old);
			redirect('daftar');
			return;
		}

		$this->db->where('nik', $nik);
		if ($this->db->get('users')->row_array() !== NULL)
		{
			$this->session->set_flashdata('error', 'NIK/NPWP ini sudah terdaftar.');
			$this->session->set_flashdata('old', $old);
			redirect('daftar');
			return;
		}

		$this->db->insert('users', array(
			'nik'      => $nik,
			'nama'     => $nama,
			'email'    => $email,
			'password' => password_hash($password, PASSWORD_DEFAULT),
			'role'     => 'pemohon',
		));

		$user_id = (int) $this->db->insert_id();

		// Langsung masuk-kan pengguna, tidak perlu login manual lagi
		// sesudah daftar - mereka baru saja membuktikan tahu kata
		// sandinya sendiri (baru diketik dua kali di form ini).
		$this->session->sess_regenerate(TRUE);
		$this->session->set_userdata(array(
			'logged_in' => TRUE,
			'user_id'   => $user_id,
			'nama'      => $nama,
			'email'     => $email,
			'role'      => 'pemohon',
		));

		redirect('pemohon');
	}

	/**
	 * Sama dengan Login::_tujuan_setelah_login() - disalin (bukan
	 * dibagi lewat library) karena cuma peta kecil role->URL, cukup
	 * kecil risikonya kalau suatu saat menyimpang sedikit.
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
