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

	private function _foto_url($f)
	{
		if ($f === NULL || $f === '') return NULL;
		if (preg_match('#^https?://#i', $f)) return $f;
		return base_url('assets/foto-cagar-budaya/' . ltrim($f, '/'));
	}
}
