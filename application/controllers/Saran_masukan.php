<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Saran_masukan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
	}

	public function index()
	{
		$data['sukses'] = $this->session->flashdata('sukses');
		$data['error']  = $this->session->flashdata('error');
		$data['old']    = $this->session->flashdata('old');

		$data['faq'] = array();
		if ($this->db->table_exists('faq'))
		{
			$data['faq'] = $this->db
				->where('tampil', 1)
				->order_by('urutan', 'ASC')
				->order_by('id', 'ASC')
				->get('faq')->result_array();
		}

		$this->load->view('pages/saran_masukan', $data);
	}

	public function kirim()
	{
		$nama  = trim((string) $this->input->post('nama'));
		$email = trim((string) $this->input->post('email'));
		$no_hp = trim((string) $this->input->post('no_hp'));
		$topik = trim((string) $this->input->post('topik'));
		$pesan = trim((string) $this->input->post('pesan'));

		if ($nama === '' || $pesan === '')
		{
			$this->session->set_flashdata('error', 'Nama dan isi saran/masukan wajib diisi.');
			$this->session->set_flashdata('old', array(
				'nama' => $nama, 'email' => $email, 'no_hp' => $no_hp, 'topik' => $topik, 'pesan' => $pesan,
			));
			redirect('saran-masukan');
			return;
		}

		$this->db->insert('saran_masukan', array(
			'nama'  => $nama,
			'email' => $email !== '' ? $email : NULL,
			'no_hp' => $no_hp !== '' ? $no_hp : NULL,
			'topik' => $topik !== '' ? $topik : NULL,
			'pesan' => $pesan,
		));

		$this->session->set_flashdata('sukses', 'Terima kasih, saran dan masukan Anda sudah kami terima.');
		redirect('saran-masukan');
	}
}
