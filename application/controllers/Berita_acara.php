<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Berita_acara extends CI_Controller
{
	private $bidang_role = array('tpa_arsitek' => 'arsitektur', 'tpa_struktur' => 'struktur', 'tpa_mep' => 'mep');
	private $bidang_label = array('arsitektur' => 'ARSITEKTUR', 'struktur' => 'STRUKTUR', 'mep' => 'MEP');
	private $bulan_romawi = array('I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII');

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper(array('url'));
		if (! $this->session->userdata('logged_in')) redirect('login');
	}

	public function pbg($id, $putaran)
	{
		$id      = (int) $id;
		$putaran = (int) $putaran;
		$row     = $this->db->where('id', $id)->get('permohonan_pbg')->row_array();
		if ($row === NULL) show_404();

		$role = (string) $this->session->userdata('role');
		if ($role === 'pu')
		{
			if ((int) $row['user_id'] !== (int) $this->session->userdata('user_id'))
			{
				show_error('Anda tidak berhak mengakses berita acara permohonan ini.', 403);
				return;
			}
		}
		elseif (isset($this->bidang_role[$role]))
		{
			$ditugaskan = $this->db->where('permohonan_id', $id)->where('putaran', $putaran)
				->where('tpa_user_id', (int) $this->session->userdata('user_id'))->count_all_results('konsultasi_pbg');
			if ($ditugaskan === 0)
			{
				show_error('Anda tidak ditugaskan pada putaran konsultasi ini.', 403);
				return;
			}
		}
		else
		{
			show_error('Halaman ini khusus PU dan TPA.', 403);
			return;
		}

		$anggota = $this->db->select('k.*, u.nama AS nama_tpa')
			->from('konsultasi_pbg k')->join('users u', 'u.id = k.tpa_user_id', 'left')
			->where('k.permohonan_id', $id)->where('k.putaran', $putaran)
			->order_by('k.bidang', 'ASC')->order_by('k.id', 'ASC')->get()->result_array();

		$bidang_ada = array();
		foreach ($anggota as $a) $bidang_ada[$a['bidang']] = true;
		$selesai = count($bidang_ada) === 3;
		foreach ($anggota as $a) if ($a['status'] === 'ditugaskan') $selesai = false;

		if (! $selesai)
		{
			show_error('Berita acara belum tersedia - masih ada bidang TPA yang belum menyelesaikan review pada putaran ini.', 422);
			return;
		}

		$per_bidang = array();
		foreach ($anggota as $a) $per_bidang[$a['bidang']][] = $a;

		$ba = $this->db->where('permohonan_id', $id)->where('putaran', $putaran)->get('berita_acara_pbg')->row_array();
		if ($ba === NULL)
		{
			$tahun = date('Y');
			$urut  = $this->db->where('YEAR(created_at)', $tahun)->count_all_results('berita_acara_pbg') + 1;
			$nomor = '600.1.15.2/' . $urut . '/BA-PBG/' . $this->bulan_romawi[(int) date('n') - 1] . '/' . $tahun;
			$this->db->insert('berita_acara_pbg', array(
				'permohonan_id' => $id, 'putaran' => $putaran, 'nomor' => $nomor, 'created_at' => date('Y-m-d H:i:s'),
			));
			$ba = $this->db->where('permohonan_id', $id)->where('putaran', $putaran)->get('berita_acara_pbg')->row_array();
		}

		$data = array(
			'row'          => $row,
			'putaran'      => $putaran,
			'nomor'        => $ba['nomor'],
			'tanggal_ba'   => $ba['created_at'],
			'per_bidang'   => $per_bidang,
			'bidang_label' => $this->bidang_label,
		);
		$this->load->view('pbg_pu/berita_acara', $data);
	}
}
