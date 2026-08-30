<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cagar_budaya extends CI_Controller {

	public function index()
	{
		$rows = array();
		if ($this->db->table_exists('cagar_budaya'))
		{
			$rows = $this->db
				->order_by('kategori', 'ASC')
				->order_by('nama', 'ASC')
				->get('cagar_budaya')->result_array();
		}

		// Normalkan foto -> URL siap pakai
		foreach ($rows as &$r)
		{
			$r['foto_url'] = $this->_foto_url(isset($r['foto']) ? $r['foto'] : NULL);
		}
		unset($r);

		$data['daftar']     = $rows;
		$data['total']      = count($rows);
		$data['total_peta'] = 0;
		foreach ($rows as $r)
		{
			if ($r['latitude'] !== NULL && $r['longitude'] !== NULL) $data['total_peta']++;
		}

		$this->load->view('pages/cagar_budaya', $data);
	}

	/** Halaman detail satu objek cagar budaya (dibuka dari tombol
	 *  "Detail" pada popup peta). Route: /cagar-budaya/detail/{id} */
	public function detail($id = null)
	{
		$id  = (int) $id;
		$row = ($id > 0 && $this->db->table_exists('cagar_budaya'))
			? $this->db->where('id', $id)->get('cagar_budaya')->row_array()
			: NULL;

		if ($row === NULL)
		{
			show_404();
			return;
		}

		$data['cb']       = $row;
		$data['foto_url']  = $this->_foto_url(isset($row['foto']) ? $row['foto'] : NULL);
		$data['warna_kat'] = array(
			'Benda'    => '#8E6FCE',
			'Bangunan' => '#3E7CB1',
			'Struktur' => '#C0392B',
			'Situs'    => '#2EA84F',
			'Kawasan'  => '#D9822B',
		);

		$this->load->view('pages/cagar_budaya_detail', $data);
	}

	private function _foto_url($f)
	{
		if ($f === NULL || $f === '') return NULL;
		if (preg_match('#^https?://#i', $f)) return $f;
		return base_url('assets/foto-cagar-budaya/' . ltrim($f, '/'));
	}
}
