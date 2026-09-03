<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regulasi extends CI_Controller {

	public function index()
	{
		$data['daftar'] = $this->db->table_exists('regulasi')
			? $this->db->where('aktif', 1)->order_by('id', 'ASC')->get('regulasi')->result_array()
			: array();
		$this->load->view('pages/regulasi', $data);
	}

	public function unduh($id = null)
	{
		$id = (int) $id;
		$row = ($id > 0 && $this->db->table_exists('regulasi'))
			? $this->db->where('id', $id)->where('aktif', 1)->get('regulasi')->row_array()
			: NULL;
		if ($row === NULL || empty($row['file_pdf'])) show_404();
		$path = FCPATH . 'assets/dokumen-regulasi/' . basename($row['file_pdf']);
		if (! is_file($path)) show_404();
		$this->load->helper('download');
		force_download($path, NULL);
	}
}
