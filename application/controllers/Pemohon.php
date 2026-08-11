<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemohon extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->_wajib_pemohon();
	}

	private function _wajib_pemohon()
	{
		if (! $this->session->userdata('logged_in'))
		{
			redirect('login');
			exit;
		}
		if ($this->session->userdata('role') !== 'pemohon')
		{
			show_error('Halaman ini khusus untuk pemohon.', 403, 'Akses Ditolak');
		}
	}

	public function index()
	{
		$data['nama_pengguna']  = $this->session->userdata('nama');
		$data['email_pengguna'] = $this->session->userdata('email');
		$this->load->view('pages/pemohon_dashboard', $data);
	}
}
