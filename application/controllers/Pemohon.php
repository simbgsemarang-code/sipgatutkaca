<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemohon extends CI_Controller {

	/** Label portal di dashboard, mengikuti tombol asal saat login (lihat Login::proses()). */
	private $peta_label_portal = array(
		'pbg' => 'Portal Pemohon PBG',
		'itr' => 'Portal Pemohon ITR',
		'slf' => 'Portal Pemohon SLF',
	);

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->_wajib_pemohon();
	}

	private function _wajib_pemohon()
	{
		if (! $this->session->userdata('logged_in'))
		{
			redirect('login');
			exit;
		}
		if ($this->session->userdata('role') !== 'pemohon')
		{
			show_error('Halaman ini khusus untuk pemohon.', 403, 'Akses Ditolak');
		}
	}

	public function index()
	{
		$data['pengajuan_itr'] = $this->db->table_exists('pengajuan_itr') ? $this->db->where('user_id', (int)$this->session->userdata('user_id'))->order_by('id','DESC')->get('pengajuan_itr')->result_array() : array();
		$data['aktivitas_itr'] = $this->db->table_exists('aktivitas_itr') ? $this->db->where('user_id', (int)$this->session->userdata('user_id'))->order_by('id','DESC')->limit(30)->get('aktivitas_itr')->result_array() : array();
		$this->render_portal('partials/pemohon_itr_dashboard', $data);
	}

	private function render_portal($content, $data = array())
	{
		$asal = $this->session->userdata('asal_layanan');

		$data['nama_pengguna']  = $this->session->userdata('nama');
		$data['email_pengguna'] = $this->session->userdata('email');
		$data['label_portal']   = isset($this->peta_label_portal[$asal]) ? $this->peta_label_portal[$asal] : 'Portal Pemohon';
		$data['portal_content'] = $content;
		$data['aktif_itr'] = $content === 'partials/pemohon_itr_form';
		$this->load->helper(array('form','url'));
		$this->load->view('pages/pemohon_dashboard', $data);
	}

	public function pengajuan_itr()
	{
		if (!$this->session->userdata('itr_form_token')) $this->session->set_userdata('itr_form_token', bin2hex(random_bytes(32)));
		$this->render_portal('partials/pemohon_itr_form', array('error'=>'','old'=>array()));
	}

	public function simpan_itr()
	{
		if ($this->input->method() !== 'post') { show_404(); return; }
		$token = (string)$this->session->userdata('itr_form_token');
		if (!$token || !hash_equals($token, (string)$this->input->post('itr_token'))) { show_error('Formulir kedaluwarsa. Muat ulang halaman.',403); return; }
		if (!$this->db->table_exists('pengajuan_itr') || !$this->db->table_exists('aktivitas_itr')) { show_error('Database pengajuan ITR belum dimigrasi.',503); return; }
		$fields = array('nama_pemohon','nik','no_hp','email','alamat_lokasi','luas_lahan','rencana_kegiatan','latitude','longitude');
		$old=array(); foreach($fields as $field) $old[$field]=trim((string)$this->input->post($field));
		$this->load->library('form_validation');
		foreach($fields as $field) $this->form_validation->set_rules($field, ucwords(str_replace('_',' ',$field)), 'required|trim');
		$this->form_validation->set_rules('nik','NIK','required|exact_length[16]|numeric');
		$this->form_validation->set_rules('email','Email','required|valid_email|max_length[150]');
		$this->form_validation->set_rules('nama_pemohon','Nama pemohon','required|max_length[150]');
		$this->form_validation->set_rules('no_hp','Nomor HP','required|max_length[30]');
		$this->form_validation->set_rules('luas_lahan','Luas lahan','required|numeric|greater_than[0]|less_than[1000000000000]');
		$this->form_validation->set_rules('latitude','Latitude','required|numeric|greater_than_equal_to[-90]|less_than_equal_to[90]');
		$this->form_validation->set_rules('longitude','Longitude','required|numeric|greater_than_equal_to[-180]|less_than_equal_to[180]');
		if (!$this->form_validation->run()) { $this->render_portal('partials/pemohon_itr_form',array('error'=>strip_tags(validation_errors()),'old'=>$old)); return; }
		$this->load->library('upload'); $dir=APPPATH.'uploads/itr/';
		if (!is_dir($dir) && !mkdir($dir,0750,TRUE)) { show_error('Penyimpanan berkas tidak tersedia.',503); return; }
		$uploaded=array();$error='';
		foreach(array('file_permohonan','file_ktp','file_sertifikat','file_siteplan') as $field) {
			if (empty($_FILES[$field]['name'])) { $error='Seluruh lampiran wajib diunggah.'; break; }
			$this->upload->initialize(array('upload_path'=>$dir,'allowed_types'=>'pdf|jpg|jpeg|png','max_size'=>5120,'encrypt_name'=>TRUE),TRUE);
			if (!$this->upload->do_upload($field)) { $error=strip_tags($this->upload->display_errors()); break; }
			$uploaded[$field]=$this->upload->data('file_name');
		}
		if ($error) { foreach($uploaded as $file) unlink($dir.$file); $this->render_portal('partials/pemohon_itr_form',array('error'=>$error,'old'=>$old)); return; }
		$uid=(int)$this->session->userdata('user_id'); $this->db->trans_begin();
		$this->db->insert('pengajuan_itr',array_merge($old,$uploaded,array('user_id'=>$uid,'status'=>'diajukan'))); $id=(int)$this->db->insert_id();
		$number='ITR-'.date('Ymd').'-'.sprintf('%06d',$id); $this->db->where('id',$id)->update('pengajuan_itr',array('no_permohonan'=>$number));
		$this->db->insert('aktivitas_itr',array('user_id'=>$uid,'pengajuan_id'=>$id,'keterangan'=>'Pengajuan '.$number.' dikirim beserta seluruh dokumen.','created_at'=>date('Y-m-d H:i:s')));
		if (!$this->db->trans_status()) { $this->db->trans_rollback(); foreach($uploaded as $file) unlink($dir.$file); show_error('Pengajuan gagal disimpan. Silakan ulangi.',500); return; }
		$this->db->trans_commit(); $this->session->unset_userdata('itr_form_token'); $this->session->set_flashdata('sukses','Pengajuan ITR berhasil dikirim. Nomor permohonan: '.$number); redirect('pemohon');
	}

	public function berkas_itr($id=0,$field='')
	{
		if (!in_array($field,array('file_permohonan','file_ktp','file_sertifikat','file_siteplan'),TRUE) || !$this->db->table_exists('pengajuan_itr')) { show_404(); return; }
		$row=$this->db->where('id',(int)$id)->where('user_id',(int)$this->session->userdata('user_id'))->get('pengajuan_itr')->row_array();
		if (!$row || empty($row[$field])) { show_404(); return; }
		$file=APPPATH.'uploads/itr/'.basename($row[$field]); if(!is_file($file)){show_404();return;}
		$this->load->helper('download'); force_download(basename($file),file_get_contents($file),TRUE);
	}
}
