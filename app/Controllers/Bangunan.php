<?php

namespace App\Controllers;

/**
 * Halaman publik detail satu bangunan (tabel bangunan_gis) - dibuka dari
 * tombol "Detail Bangunan" pada popup peta Analisa Kerusakan / Spasial.
 * Route: /bangunan/{id} (lihat application/config/routes.php).
 */
class Bangunan extends LegacyController {

	public function index($id = null)
	{
		$id  = (int) $id;
		$row = ($id > 0 && $this->db->table_exists('bangunan_gis'))
			? $this->db->where('id', $id)->get('bangunan_gis')->row_array()
			: NULL;

		if ($row === NULL)
		{
			show_404();
			return;
		}

		$data['b']            = $row;
		$data['kondisi_label'] = array(
			'1' => 'Baik',
			'2' => 'Rusak Ringan',
			'3' => 'Rusak Sedang',
			'4' => 'Rusak Berat',
		);
		$data['kondisi_warna'] = array('1' => '#2EA84F', '2' => '#F2C230', '3' => '#D9822B', '4' => '#C0392B');
		$data['foto_url']      = $this->_foto_url($row['foto']);
		$data['massa']         = $this->_massa($row['id']);

		$this->load->view('pages/bangunan_detail', $data);
	}

	/** Massa/blok bangunan (admin: Sebaran Bangunan > Massa) beserta foto dan geometrinya; kosong bila belum ada. */
	private function _massa($bangunan_id)
	{
		if (! $this->db->table_exists('bangunan_massa')) return array();
		$rows = $this->db->where('bangunan_id', (int) $bangunan_id)->order_by('id', 'ASC')->get('bangunan_massa')->result_array();
		foreach ($rows as &$m)
		{
			$m['foto_url'] = $this->_foto_url(isset($m['foto']) ? $m['foto'] : NULL);
			$g = (isset($m['geom']) && $m['geom'] !== NULL && $m['geom'] !== '') ? json_decode($m['geom'], TRUE) : NULL;
			$m['geom'] = (is_array($g) && isset($g['type'], $g['coordinates'])) ? $g : NULL;
		}
		unset($m);
		return $rows;
	}

	private function _foto_url($f)
	{
		if ($f === NULL || $f === '') return NULL;
		if (preg_match('#^https?://#i', $f)) return $f;
		return base_url('assets/foto-bangunan/' . rawurlencode(ltrim($f, '/')));
	}
}
