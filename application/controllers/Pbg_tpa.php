<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pbg_tpa extends CI_Controller
{
	private $roles=array('tpa_arsitek'=>'arsitektur','tpa_struktur'=>'struktur','tpa_mep'=>'mep');
	public function __construct(){parent::__construct();$this->load->library(array('session','form_validation','upload'));$this->load->helper(array('url','form'));if(!$this->session->userdata('logged_in'))redirect('login');if(!isset($this->roles[$this->session->userdata('role')]))show_error('Halaman ini khusus TPA bidang.',403);}
	private function uid(){return (int)$this->session->userdata('user_id');}
	public function index(){
		$data['nama_pengguna']=$this->session->userdata('nama');$data['bidang']=$this->roles[$this->session->userdata('role')];
		$data['daftar']=$this->db->select('k.*,p.no_permohonan,p.nama_pemohon,p.alamat_bangunan,p.jenis_bangunan')->from('konsultasi_pbg k')->join('permohonan_pbg p','p.id=k.permohonan_id')->where('k.tpa_user_id',$this->uid())->order_by('k.assigned_at','DESC')->get()->result_array();
		$this->load->view('pbg_tpa/index',$data);
	}
	public function review($id){
		$row=$this->db->select('k.*,p.* ,k.id AS konsultasi_id,k.status AS status_konsultasi')->from('konsultasi_pbg k')->join('permohonan_pbg p','p.id=k.permohonan_id')->where('k.id',(int)$id)->where('k.tpa_user_id',$this->uid())->get()->row_array();if(!$row)show_404();
		$error=null;if($this->input->method(TRUE)==='POST'){$this->form_validation->set_rules('status_review','Hasil review','required|in_list[perlu_perbaikan,direkomendasikan]');$this->form_validation->set_rules('rekomendasi','Rekomendasi','required|trim');if($row['status_konsultasi']!=='ditugaskan')$error='Hasil review yang sudah dikirim tidak dapat diubah.';elseif($this->form_validation->run()){$file=null;if(!empty($_FILES['file_rekomendasi']['name'])){$dir=FCPATH.'assets/uploads/konsultasi_pbg/';if(!is_dir($dir))mkdir($dir,0755,true);$this->upload->initialize(array('upload_path'=>$dir,'allowed_types'=>'pdf|doc|docx|jpg|jpeg|png','max_size'=>10240,'encrypt_name'=>TRUE),TRUE);if(!$this->upload->do_upload('file_rekomendasi'))$error=strip_tags($this->upload->display_errors('',''));else $file=$this->upload->data('file_name');}if(!$error){$update=array('status'=>$this->input->post('status_review'),'rekomendasi_tpa'=>trim($this->input->post('rekomendasi')),'reviewed_at'=>date('Y-m-d H:i:s'));if($file)$update['file_rekomendasi']=$file;$this->db->where('id',$row['konsultasi_id'])->where('tpa_user_id',$this->uid())->where('status','ditugaskan')->update('konsultasi_pbg',$update);$this->session->set_flashdata('sukses','Hasil konsultasi putaran '.$row['putaran'].' berhasil dikirim.');redirect('tpa-pengajuan-pbg');return;}}}
		$fields=array('arsitektur'=>array('file_pkkpr'=>'PKKPR/KRK','file_rencana_teknis'=>'Dokumen Teknis Arsitektur','file_dokumen_lingkungan'=>'Dokumen Lingkungan'),'struktur'=>array('file_teknis_struktur'=>'Dokumen Teknis Struktur'),'mep'=>array('file_checklist_mep'=>'Dokumen MEP'));
		$data=array('row'=>$row,'dokumen'=>$fields[$row['bidang']],'nama_pengguna'=>$this->session->userdata('nama'),'error'=>$error);$this->load->view('pbg_tpa/review',$data);
	}
}
