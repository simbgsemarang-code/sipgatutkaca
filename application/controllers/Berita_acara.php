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

	/**
	 * BA terbit setiap SATU baris konsultasi_pbg selesai direview
	 * (statusnya bukan lagi 'ditugaskan') - bukan menunggu ketiga
	 * bidang selesai. $id = konsultasi_pbg.id pemicunya.
	 */
	public function pbg($id)
	{
		$id      = (int) $id;
		$trigger = $this->db->where('id', $id)->get('konsultasi_pbg')->row_array();
		if ($trigger === NULL || $trigger['status'] === 'ditugaskan') show_404();

		$row = $this->db->where('id', $trigger['permohonan_id'])->get('permohonan_pbg')->row_array();
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
			$ikut = $this->db->where('permohonan_id', $trigger['permohonan_id'])
				->where('tpa_user_id', (int) $this->session->userdata('user_id'))->count_all_results('konsultasi_pbg');
			if ($ikut === 0)
			{
				show_error('Anda tidak pernah ditugaskan pada permohonan ini.', 403);
				return;
			}
		}
		else
		{
			show_error('Halaman ini khusus PU dan TPA.', 403);
			return;
		}

		$ba = $this->db->where('konsultasi_id', $id)->get('berita_acara_pbg')->row_array();
		if ($ba === NULL)
		{
			$snapshot = array();
			foreach (array_keys($this->bidang_label) as $bidang)
			{
				$r = $this->db->select('k.*, u.nama AS nama_tpa')->from('konsultasi_pbg k')
					->join('users u', 'u.id = k.tpa_user_id', 'left')
					->where('k.permohonan_id', $trigger['permohonan_id'])->where('k.bidang', $bidang)
					->where('k.reviewed_at IS NOT NULL')->where('k.reviewed_at <=', $trigger['reviewed_at'])
					->order_by('k.reviewed_at', 'DESC')->limit(1)->get()->row_array();
				$snapshot[$bidang] = $r ? array(
					'nama_tpa'    => $r['nama_tpa'],
					'rekomendasi' => $r['rekomendasi_tpa'],
					'reviewed_at' => $r['reviewed_at'],
				) : null;
			}

			$tahun = date('Y', strtotime($trigger['reviewed_at']));
			$bulan = (int) date('n', strtotime($trigger['reviewed_at']));
			$urut  = $this->db->where('YEAR(created_at)', $tahun)->count_all_results('berita_acara_pbg') + 1;
			$nomor = '600.1.15.2/' . $urut . '/BA-PBG/' . $this->bulan_romawi[$bulan - 1] . '/' . $tahun;

			$this->db->insert('berita_acara_pbg', array(
				'konsultasi_id'  => $id,
				'permohonan_id'  => $trigger['permohonan_id'],
				'nomor'          => $nomor,
				'diterbitkan_at' => $trigger['reviewed_at'],
				'snapshot'       => json_encode($snapshot),
				'created_at'     => date('Y-m-d H:i:s'),
			));
			$ba = $this->db->where('konsultasi_id', $id)->get('berita_acara_pbg')->row_array();
		}

		$data = array(
			'row'          => $row,
			'putaran'      => $trigger['putaran'],
			'bidang_pemicu'=> $trigger['bidang'],
			'nomor'        => $ba['nomor'],
			'tanggal_ba'   => $ba['diterbitkan_at'],
			'snapshot'     => json_decode($ba['snapshot'], true),
			'bidang_label' => $this->bidang_label,
		);
		$this->load->view('pbg_pu/berita_acara', $data);
	}
}
