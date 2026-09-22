<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pu extends CI_Controller {

	/** Status yang sah untuk saran & masukan. */
	private $status_valid = array('baru', 'ditinjau', 'selesai');

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->_wajib_pu();
	}

	private function _wajib_pu()
	{
		if (! $this->session->userdata('logged_in'))
		{
			redirect('login');
			exit;
		}
		if ($this->session->userdata('role') !== 'pu')
		{
			show_error('Halaman ini khusus untuk PU.', 403, 'Akses Ditolak');
		}
	}

	public function index()
	{
		$data['daftar_masukan'] = $this->db->order_by('created_at', 'DESC')->get('saran_masukan')->result_array();
		$data['sukses']         = $this->session->flashdata('sukses');
		$data['error']          = $this->session->flashdata('error');
		$data['nama_pengguna']  = $this->session->userdata('nama');

		// Ringkasan Pengajuan PBG - dibungkus table_exists() supaya
		// dashboard tetap tampil normal walau migrasi
		// database/pengajuan_pbg.sql belum sempat dijalankan.
		$data['total_pbg']    = 0;
		$data['draf_pbg']     = 0;
		$data['terkirim_pbg'] = 0;
		if ($this->db->table_exists('pengajuan_pbg'))
		{
			$status_pbg = $this->db->select('status')->get('pengajuan_pbg')->result_array();
			$data['total_pbg'] = count($status_pbg);
			foreach ($status_pbg as $p)
			{
				if ($p['status'] === 'draf')
				{
					$data['draf_pbg']++;
				}
			}
			$data['terkirim_pbg'] = $data['total_pbg'] - $data['draf_pbg'];
		}

		/* ---- Distribusi status + aktivitas terkini (lihat dashboard_helper) ---- */
		$data['status_label'] = dashboard_status_label();
		$data['distribusi']   = dashboard_distribusi();
		$data['aktivitas']    = dashboard_aktivitas(6);

		/* ---- Berita Acara terbaru milik PU ini (lihat Berita_acara::pbg) ---- */
		$data['ba_terbaru'] = array();
		if ($this->db->table_exists('berita_acara_pbg'))
		{
			$data['ba_terbaru'] = $this->db->select('b.*, p.nama_pemohon, p.no_permohonan, k.bidang')
				->from('berita_acara_pbg b')
				->join('permohonan_pbg p', 'p.id = b.permohonan_id')
				->join('konsultasi_pbg k', 'k.id = b.konsultasi_id')
				->where('p.user_id', (int) $this->session->userdata('user_id'))
				->order_by('b.diterbitkan_at', 'DESC')->limit(5)->get()->result_array();
		}

		$this->load->view('pages/pu_dashboard', $data);
	}

	public function tandai_status($id = null)
	{
		$id     = (int) $id;
		$status = (string) $this->input->post('status');

		if (! in_array($status, $this->status_valid, TRUE))
		{
			$this->session->set_flashdata('error', 'Status tidak valid.');
			redirect('pu');
			return;
		}

		$this->db->where('id', $id)->update('saran_masukan', array('status' => $status));
		$this->session->set_flashdata('sukses', 'Status berhasil diperbarui.');
		redirect('pu');
	}
}
