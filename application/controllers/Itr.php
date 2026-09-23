<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Itr extends CI_Controller {

	public function index()
	{
		$per_page = 4;
		$q = trim((string) $this->input->get('q'));
		$page = max(1, (int) $this->input->get('page'));

		$data = array('q'=>$q, 'page'=>1, 'total_halaman'=>1, 'daftar_pemohon'=>array());

		if ($this->db->table_exists('pengajuan_itr'))
		{
			$filter = function ($builder) use ($q) {
				if ($q !== '')
				{
					$builder->group_start()->like('nama_pemohon', $q)->or_like('lokasi_kecamatan', $q)->group_end();
				}
				return $builder;
			};

			$total = $filter($this->db->from('pengajuan_itr'))->count_all_results();
			$total_halaman = max(1, (int) ceil($total / $per_page));
			$page = min($page, $total_halaman);

			$daftar = $filter($this->db->from('pengajuan_itr'))
				->order_by('created_at', 'DESC')
				->limit($per_page, ($page - 1) * $per_page)
				->get()->result_array();

			$data['page'] = $page;
			$data['total_halaman'] = $total_halaman;
			$data['daftar_pemohon'] = $daftar;
		}

		$this->load->view('pages/itr', $data);
	}
}
