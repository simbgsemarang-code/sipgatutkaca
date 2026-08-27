<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Halaman pengaturan akun - dipakai bareng oleh SEMUA peran (admin,
 * pu, tpa, pemohon), makanya jadi controller sendiri dan bukan
 * method di Admin/Pu/Tpa/Pemohon. Cuma butuh "sudah login" (peran
 * apa saja), beda dengan keempat controller dashboard yang masing2
 * mengunci ke satu peran tertentu.
 */
class Pengaturan extends CI_Controller {

	/** Halaman dashboard tujuan tiap peran - dipakai buat link "Dashboard" di sidebar. */
	private $peta_dashboard = array(
		'admin'        => 'admin/pengguna',
		'pu'           => 'pu',
		'tpa'          => 'tpa',
		'tpa_arsitek'  => 'tpa',
		'tpa_struktur' => 'tpa',
		'tpa_mep'      => 'tpa',
		'pemohon'      => 'pemohon',
	);

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');

		if (! $this->session->userdata('logged_in'))
		{
			redirect('login');
			exit;
		}
	}

	public function index()
	{
		$role = $this->session->userdata('role');

		$data['nama']            = $this->session->userdata('nama');
		$data['email']           = $this->session->userdata('email');
		$data['role']            = $role;
		$data['dashboard_url']   = isset($this->peta_dashboard[$role]) ? $this->peta_dashboard[$role] : '';
		$data['sukses']          = $this->session->flashdata('sukses');
		$data['error']           = $this->session->flashdata('error');
		$this->load->view('pages/pengaturan', $data);
	}

	public function ubah_password()
	{
		$lama  = (string) $this->input->post('password_lama');
		$baru  = (string) $this->input->post('password_baru');
		$ulang = (string) $this->input->post('ulang_password_baru');

		if ($lama === '' || $baru === '' || $ulang === '')
		{
			$this->session->set_flashdata('error', 'Semua kolom kata sandi wajib diisi.');
			redirect('pengaturan');
			return;
		}

		$this->db->where('id', (int) $this->session->userdata('user_id'));
		$user = $this->db->get('users')->row_array();

		if ($user === NULL || ! password_verify($lama, $user['password']))
		{
			$this->session->set_flashdata('error', 'Kata sandi lama yang Anda masukkan salah.');
			redirect('pengaturan');
			return;
		}

		if (strlen($baru) < 8)
		{
			$this->session->set_flashdata('error', 'Kata sandi baru minimal 8 karakter.');
			redirect('pengaturan');
			return;
		}

		if ($baru !== $ulang)
		{
			$this->session->set_flashdata('error', 'Konfirmasi kata sandi baru tidak sama dengan kata sandi baru.');
			redirect('pengaturan');
			return;
		}

		$this->db->where('id', (int) $user['id']);
		$this->db->update('users', array('password' => password_hash($baru, PASSWORD_DEFAULT)));

		$this->session->set_flashdata('sukses', 'Kata sandi berhasil diperbarui.');
		redirect('pengaturan');
	}
}
