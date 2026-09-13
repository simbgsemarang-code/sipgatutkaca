<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin_itr extends CI_Controller {
 public function __construct(){parent::__construct();$this->load->library('session');$this->load->helper(array('url','form'));if(!$this->session->userdata('logged_in')){redirect('login?from=admin');exit;}if($this->session->userdata('role')!=='admin'){show_error('Khusus administrator.',403);exit;}if(!$this->session->userdata('admin_itr_token'))$this->session->set_userdata('admin_itr_token',bin2hex(random_bytes(32)));}
 public function index(){
  $data['nama_pengguna']=$this->session->userdata('nama');
  $data['daftar']=$this->db->table_exists('pengajuan_itr')?$this->db->order_by('id','DESC')->get('pengajuan_itr')->result_array():array();
  $data['pesan']=$this->db->table_exists('pesan_itr')?$this->db->select('p.*,u.nama AS nama_admin')->from('pesan_itr p')->join('users u','u.id=p.admin_id','left')->order_by('p.id','DESC')->get()->result_array():array();
  $this->load->view('pages/admin_itr',$data);
 }
 public function simpan($id=0){
  if($this->input->method()!=='post'){show_404();return;}
  if(!hash_equals((string)$this->session->userdata('admin_itr_token'),(string)$this->input->post('itr_token'))){show_error('Formulir tidak valid.',403);return;}
  if(!$this->db->table_exists('pesan_itr')){show_error('Jalankan migrasi itr_pengelolaan.sql.',503);return;}
  $row=$this->db->where('id',(int)$id)->get('pengajuan_itr')->row_array();if(!$row){show_404();return;}
  $status=(string)$this->input->post('status');$isi=trim((string)$this->input->post('informasi'));
  if(!in_array($status,array('diajukan','sedang_diverifikasi','perlu_perbaikan','disetujui','ditolak'),TRUE)||strlen($isi)>10000){show_error('Status atau informasi tidak valid.',400);return;}
  if($status!==$row['status']&&$isi==='')$isi='Status pengajuan diperbarui menjadi '.ucwords(str_replace('_',' ',$status)).'.';
  if($isi===''){redirect('admin_itr');return;}
  $this->db->trans_begin();$this->db->where('id',(int)$id)->update('pengajuan_itr',array('status'=>$status));
  $this->db->insert('pesan_itr',array('pengajuan_id'=>(int)$id,'user_id'=>$row['user_id'],'admin_id'=>(int)$this->session->userdata('user_id'),'isi'=>$isi));
  $this->db->insert('aktivitas_itr',array('user_id'=>$row['user_id'],'pengajuan_id'=>(int)$id,'keterangan'=>'Admin mengirim informasi untuk '.$row['no_permohonan'].'. Status: '.ucwords(str_replace('_',' ',$status))));
  if(!$this->db->trans_status()){$this->db->trans_rollback();show_error('Informasi gagal disimpan.',500);return;}$this->db->trans_commit();$this->session->set_flashdata('sukses','Status dan informasi berhasil dikirim kepada pemohon.');redirect('admin_itr');
 }
 public function berkas($id=0,$field=''){
  if(!in_array($field,array('file_permohonan','file_ktp','file_sertifikat','file_siteplan'),TRUE)){show_404();return;}
  $row=$this->db->where('id',(int)$id)->get('pengajuan_itr')->row_array();if(!$row||empty($row[$field])){show_404();return;}
  $file=APPPATH.'uploads/itr/'.basename($row[$field]);if(!is_file($file)){show_404();return;}$this->load->helper('download');force_download(basename($file),file_get_contents($file),TRUE);
 }
}
