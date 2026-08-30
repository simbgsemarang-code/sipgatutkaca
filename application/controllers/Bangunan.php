<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Halaman publik detail satu bangunan (tabel bangunan_gis) - dibuka dari
 * tombol "Detail Bangunan" pada popup peta Analisa Kerusakan / Spasial.
 * Route: /bangunan/{id} (lihat application/config/routes.php).
 */
class Bangunan extends CI_Controller {

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

		$this->load->view('pages/bangunan_detail', $data);
	}

	private function _foto_url($f)
	{
		if ($f === NULL || $f === '') return NULL;
		if (preg_match('#^https?://#i', $f)) return $f;
		return base_url('assets/foto-bangunan/' . ltrim($f, '/'));
	}
}
