<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	/** Peran yang sah untuk dipilih saat menambahkan pengguna. */
	private $peran_valid = array('admin', 'pu', 'tpa', 'pemohon');

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->_wajib_admin();
	}

	/**
	 * Semua method di controller ini hanya boleh diakses pengguna yang
	 * sudah login DAN berperan admin. Selain itu ditolak/diarahkan ke
	 * halaman login.
	 */
	private function _wajib_admin()
	{
		if (! $this->session->userdata('logged_in'))
		{
			redirect('login');
			exit;
		}
		if ($this->session->userdata('role') !== 'admin')
		{
			show_error('Halaman ini khusus untuk admin.', 403, 'Akses Ditolak');
		}
	}

	public function pengguna()
	{
		$data['daftar_user'] = $this->db->order_by('created_at', 'DESC')->get('users')->result_array();
		$data['sukses']      = $this->session->flashdata('sukses');
		$data['error']       = $this->session->flashdata('error');
		$data['old']         = $this->session->flashdata('old');
		$data['nama_admin']  = $this->session->userdata('nama');
		$this->load->view('pages/admin_pengguna', $data);
	}

	public function tambah_pengguna()
	{
		$nama     = trim((string) $this->input->post('nama'));
		$email    = trim((string) $this->input->post('email'));
		$nik      = trim((string) $this->input->post('nik'));
		$password = (string) $this->input->post('password');
		$role     = (string) $this->input->post('role');

		$old = array('nama' => $nama, 'email' => $email, 'nik' => $nik, 'role' => $role);

		if ($nama === '' || $email === '' || $password === '' || ! in_array($role, $this->peran_valid, TRUE))
		{
			$this->session->set_flashdata('error', 'Nama, surel, kata sandi, dan jenis pengguna wajib diisi dengan benar.');
			$this->session->set_flashdata('old', $old);
			redirect('admin/pengguna');
			return;
		}

		if (! filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$this->session->set_flashdata('error', 'Format surel tidak valid.');
			$this->session->set_flashdata('old', $old);
			redirect('admin/pengguna');
			return;
		}

		if (strlen($password) < 8)
		{
			$this->session->set_flashdata('error', 'Kata sandi minimal 8 karakter.');
			$this->session->set_flashdata('old', $old);
			redirect('admin/pengguna');
			return;
		}

		$this->db->where('email', $email);
		if ($this->db->get('users')->num_rows() > 0)
		{
			$this->session->set_flashdata('error', 'Surel tersebut sudah terdaftar.');
			$this->session->set_flashdata('old', $old);
			redirect('admin/pengguna');
			return;
		}

		$this->db->insert('users', array(
			'nik'      => $nik !== '' ? $nik : NULL,
			'nama'     => $nama,
			'email'    => $email,
			'password' => password_hash($password, PASSWORD_DEFAULT),
			'role'     => $role,
		));

		$this->session->set_flashdata('sukses', 'Pengguna "' . $nama . '" berhasil ditambahkan sebagai ' . strtoupper($role) . '.');
		redirect('admin/pengguna');
	}

	public function hapus_pengguna($id = null)
	{
		$id = (int) $id;

		if ($id === (int) $this->session->userdata('user_id'))
		{
			$this->session->set_flashdata('error', 'Tidak bisa menghapus akun yang sedang Anda gunakan sendiri.');
			redirect('admin/pengguna');
			return;
		}

		$this->db->where('id', $id)->delete('users');
		$this->session->set_flashdata('sukses', 'Pengguna berhasil dihapus.');
		redirect('admin/pengguna');
	}
}
