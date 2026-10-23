<?php

namespace App\Controllers;

class Pbg_tpa extends LegacyController
{
	private $roles=array('tpa_arsitek'=>'arsitektur','tpa_struktur'=>'struktur','tpa_mep'=>'mep');
	public function __legacy_construct(){$this->load->library(array('session','form_validation','upload'));$this->load->helper(array('url','form','berkas','pbg_konsultasi'));if(!$this->session->userdata('logged_in'))ci_redirect('login');if(!isset($this->roles[$this->session->userdata('role')]))show_error('Halaman ini khusus TPA bidang.',403);}
	private function uid(){return (int)$this->session->userdata('user_id');}
	public function index(){
		// Susul penugasan ulang bagi TPA yang perbaikannya sudah dikirim PU lewat link Google Drive
		foreach($this->db->distinct()->select('permohonan_id')->where('tpa_user_id',$this->uid())->where('status','perlu_perbaikan')->where('perbaikan_dikirim_at IS NOT NULL',NULL,FALSE)->get('konsultasi_pbg')->result_array() as $x)pbg_tugaskan_ulang_perbaikan($x['permohonan_id']);
		$data['nama_pengguna']=$this->session->userdata('nama');$data['bidang']=$this->roles[$this->session->userdata('role')];
		$data['daftar']=$this->db->select('k.*,p.no_permohonan,p.nama_pemohon,p.nik,p.keterangan')->from('konsultasi_pbg k')->join('permohonan_pbg p','p.id=k.permohonan_id')->where('k.tpa_user_id',$this->uid())->order_by('k.assigned_at','DESC')->get()->result_array();
		$this->load->view('pbg_tpa/index',$data);
	}
	public function review($id){
		$pm=$this->db->select('permohonan_id')->where('id',(int)$id)->where('tpa_user_id',$this->uid())->get('konsultasi_pbg')->row_array(); if($pm)pbg_tugaskan_ulang_perbaikan($pm['permohonan_id']);
		$row=$this->db->select('k.*,p.* ,k.id AS konsultasi_id,k.status AS status_konsultasi')->from('konsultasi_pbg k')->join('permohonan_pbg p','p.id=k.permohonan_id')->where('k.id',(int)$id)->where('k.tpa_user_id',$this->uid())->get()->row_array();if(!$row)show_404();
		$error=null;if($this->input->method(TRUE)==='POST'){$this->form_validation->set_rules('status_review','Hasil review','required|in_list[perlu_perbaikan,direkomendasikan]');$this->form_validation->set_rules('rekomendasi','Rekomendasi',$this->input->post('status_review')==='perlu_perbaikan'?'required|trim':'trim');if($row['status_konsultasi']!=='ditugaskan')$error='Hasil review yang sudah dikirim tidak dapat diubah.';elseif($this->form_validation->run()){$file=null;if(!empty($_FILES['file_rekomendasi']['name'])){$dir=FCPATH.'assets/uploads/konsultasi_pbg/';if(!is_dir($dir))mkdir($dir,0755,true);$this->upload->initialize(array('upload_path'=>$dir,'allowed_types'=>'pdf|doc|docx|jpg|jpeg|png','max_size'=>102400,'encrypt_name'=>TRUE),TRUE);if(!$this->upload->do_upload('file_rekomendasi'))$error=strip_tags($this->upload->display_errors('',''));else $file=berkas_simpan($this->upload->data());}if(!$error){$update=array('status'=>$this->input->post('status_review'),'rekomendasi_tpa'=>trim($this->input->post('rekomendasi')),'reviewed_at'=>date('Y-m-d H:i:s'));if($file)$update['file_rekomendasi']=$file;$this->db->where('id',$row['konsultasi_id'])->where('tpa_user_id',$this->uid())->where('status','ditugaskan')->update('konsultasi_pbg',$update);pbg_sinkron_tahap($row['permohonan_id']);$this->session->set_flashdata('sukses','Hasil konsultasi putaran '.$row['putaran'].' berhasil dikirim.');ci_redirect('tpa-pengajuan-pbg');return;}}}
		$riwayat_ba=$this->db->where('permohonan_id',$row['permohonan_id'])->where('status !=','ditugaskan')->order_by('putaran','DESC')->order_by('bidang','ASC')->get('konsultasi_pbg')->result_array();
		$link_perbaikan=$this->db->table_exists('link_perbaikan_pbg')?$this->db->where('permohonan_id',$row['permohonan_id'])->order_by('putaran','DESC')->order_by('id','DESC')->get('link_perbaikan_pbg')->result_array():array();
		$tugas_baru=null; if($row['status_konsultasi']!=='ditugaskan')$tugas_baru=$this->db->where('permohonan_id',$row['permohonan_id'])->where('tpa_user_id',$this->uid())->where('bidang',$row['bidang'])->where('putaran >',(int)$row['putaran'])->where('status','ditugaskan')->order_by('putaran','ASC')->get('konsultasi_pbg')->row_array();
		$data=array('row'=>$row,'tugas_baru'=>$tugas_baru,'link_perbaikan'=>$link_perbaikan,'riwayat_ba'=>$riwayat_ba,'nama_pengguna'=>$this->session->userdata('nama'),'error'=>$error);$this->load->view('pbg_tpa/review',$data);
	}
}
