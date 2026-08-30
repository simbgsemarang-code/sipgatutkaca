<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Endpoint data GIS publik untuk peta (Analisa Kerusakan & Spasial).
 * Sebelumnya data bangunan statis di gis-data.js; sekarang diambil dari
 * tabel `bangunan_gis` yang dikelola admin (lihat Admin::bangunan*).
 * gis-data.js tetap dipakai untuk layer referensi (batas kecamatan,
 * kabupaten, jalan) yang tidak dikelola admin.
 */
class Gis extends CI_Controller {

	/** FeatureCollection titik bangunan — dipetakan ke properti yang sama
	 *  seperti gisBangunan lama supaya view peta tidak perlu diubah. */
	public function bangunan()
	{
		$features = array();

		if ($this->db->table_exists('bangunan_gis'))
		{
			foreach ($this->db->order_by('id', 'ASC')->get('bangunan_gis')->result_array() as $r)
			{
				$lat = (float) $r['latitude'];
				$lng = (float) $r['longitude'];
				$features[] = array(
					'type'       => 'Feature',
					'properties' => array(
						'idBangunan'   => (int) $r['id'],
						'opd'          => $r['opd'],
						'unit'         => $r['unit'],
						'institusi'    => $r['institusi'],
						'namaBangunan' => $r['nama_bangunan'],
						'fungsi'       => $r['fungsi'],
						'jumlahLantai' => ($r['jumlah_lantai'] === NULL || $r['jumlah_lantai'] === '') ? NULL : (int) $r['jumlah_lantai'],
						'kecamatan'    => $r['kecamatan'],
						'kelurahan'    => $r['kelurahan'],
						'alamat'       => $r['alamat'],
						'kondisi'      => (string) $r['kondisi'],
						'foto'         => $this->_foto_url($r['foto']),
						'titikLokasi'  => $lat . ',' . $lng,
					),
					'geometry' => array(
						'type'        => 'Point',
						'coordinates' => array($lng, $lat),
					),
				);
			}
		}

		$this->output
			->set_content_type('application/json; charset=utf-8')
			->set_header('Cache-Control: no-cache, must-revalidate')
			->set_output(json_encode(array('type' => 'FeatureCollection', 'features' => $features)));
	}

	/** Kolom `foto` bisa berupa nama file hasil unggah (baru) atau URL
	 *  penuh (data lama sebelum diganti ke input file) - normalkan jadi
	 *  URL yang bisa dipakai langsung di <img src>. */
	private function _foto_url($f)
	{
		if ($f === NULL || $f === '') return NULL;
		if (preg_match('#^https?://#i', $f)) return $f;
		return base_url('assets/foto-bangunan/' . ltrim($f, '/'));
	}
}
