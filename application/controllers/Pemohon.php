<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemohon extends CI_Controller {

	/** Label portal di dashboard, mengikuti tombol asal saat login (lihat Login::proses()). */
	private $peta_label_portal = array(
		'pbg' => 'Portal Pemohon PBG',
		'slf' => 'Portal Pemohon SLF',
	);

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
		$asal = $this->session->userdata('asal_layanan');

		$data['nama_pengguna']  = $this->session->userdata('nama');
		$data['email_pengguna'] = $this->session->userdata('email');
		$data['label_portal']   = isset($this->peta_label_portal[$asal]) ? $this->peta_label_portal[$asal] : 'Portal Pemohon';
		$this->load->view('pages/pemohon_dashboard', $data);
	}
}
