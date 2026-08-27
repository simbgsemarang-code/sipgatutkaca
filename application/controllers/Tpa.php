<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tpa extends CI_Controller {

	/** Status yang sah untuk saran & masukan. */
	private $status_valid = array('baru', 'ditinjau', 'selesai');

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->_wajib_tpa();
	}

	/** 'tpa' generik dipertahankan di sini untuk akun lama; anggota baru memakai salah satu spesialisasi. */
	private $peran_tpa = array('tpa', 'tpa_arsitek', 'tpa_struktur', 'tpa_mep');

	private function _wajib_tpa()
	{
		if (! $this->session->userdata('logged_in'))
		{
			redirect('login');
			exit;
		}
		if (! in_array($this->session->userdata('role'), $this->peran_tpa, TRUE))
		{
			show_error('Halaman ini khusus untuk TPA.', 403, 'Akses Ditolak');
		}
	}

	public function index()
	{
		$data['daftar_masukan'] = $this->db->order_by('created_at', 'DESC')->get('saran_masukan')->result_array();
		$data['sukses']         = $this->session->flashdata('sukses');
		$data['error']          = $this->session->flashdata('error');
		$data['nama_pengguna']  = $this->session->userdata('nama');
		$this->load->view('pages/tpa_dashboard', $data);
	}

	public function tandai_status($id = null)
	{
		$id     = (int) $id;
		$status = (string) $this->input->post('status');

		if (! in_array($status, $this->status_valid, TRUE))
		{
			$this->session->set_flashdata('error', 'Status tidak valid.');
			redirect('tpa');
			return;
		}

		$this->db->where('id', $id)->update('saran_masukan', array('status' => $status));
		$this->session->set_flashdata('sukses', 'Status berhasil diperbarui.');
		redirect('tpa');
	}
}
