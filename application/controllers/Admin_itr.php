<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin_itr extends CI_Controller {
 public function __construct(){parent::__construct();$this->load->library('session');$this->load->helper(array('url','form','itr','berkas'));if(!$this->session->userdata('logged_in')){redirect('login?from=admin');exit;}if($this->session->userdata('role')!=='admin'){show_error('Khusus administrator.',403);exit;}if(!$this->session->userdata('admin_itr_token'))$this->session->set_userdata('admin_itr_token',bin2hex(random_bytes(32)));}

 public function index(){
  $data['nama_pengguna']=$this->session->userdata('nama');
  $daftar=$this->db->table_exists('pengajuan_itr')?$this->db->order_by('id','DESC')->get('pengajuan_itr')->result_array():array();
  foreach($daftar as &$r){
   $r['_status_berkas']=itr_status_berkas($r['id']);
   $r['_semua_diterima']=itr_semua_diterima($r);
  }
  unset($r);
  $data['daftar']=$daftar;
  $this->load->view('pages/admin_itr',$data);
 }

 public function detail($id=0){
  $id=(int)$id;
  $row=$this->db->where('id',$id)->get('pengajuan_itr')->row_array();
  if(!$row){show_404();return;}
  $row['_status_berkas']=itr_status_berkas($id);
  $row['_semua_diterima']=itr_semua_diterima($row);
  $data['nama_pengguna']=$this->session->userdata('nama');
  $data['r']=$row;
  $data['pesan']=$this->db->table_exists('pesan_itr')?$this->db->select('p.*,u.nama AS nama_admin')->from('pesan_itr p')->join('users u','u.id=p.admin_id','left')->where('p.pengajuan_id',$id)->order_by('p.id','DESC')->get()->result_array():array();
  $this->load->view('pages/admin_itr_detail',$data);
 }

 public function simpan($id=0){
  if($this->input->method()!=='post'){show_404();return;}
  if(!hash_equals((string)$this->session->userdata('admin_itr_token'),(string)$this->input->post('itr_token'))){show_error('Formulir tidak valid.',403);return;}
  if(!$this->db->table_exists('pesan_itr')){show_error('Jalankan migrasi itr_pengelolaan.sql.',503);return;}
  $row=$this->db->where('id',(int)$id)->get('pengajuan_itr')->row_array();if(!$row){show_404();return;}
  $status=(string)$this->input->post('status');$isi=trim((string)$this->input->post('informasi'));
  if(!in_array($status,array('diajukan','sedang_diverifikasi','perlu_perbaikan','disetujui','ditolak'),TRUE)||strlen($isi)>10000){show_error('Status atau informasi tidak valid.',400);return;}
  if($status!==$row['status']&&$isi==='')$isi='Status pengajuan diperbarui menjadi '.ucwords(str_replace('_',' ',$status)).'.';
  if($isi===''){redirect('admin_itr/detail/'.$id);return;}
  $this->db->trans_begin();$this->db->where('id',(int)$id)->update('pengajuan_itr',array('status'=>$status));
  $this->db->insert('pesan_itr',array('pengajuan_id'=>(int)$id,'user_id'=>$row['user_id'],'admin_id'=>(int)$this->session->userdata('user_id'),'isi'=>$isi));
  $this->db->insert('aktivitas_itr',array('user_id'=>$row['user_id'],'pengajuan_id'=>(int)$id,'keterangan'=>'Admin mengirim informasi untuk '.$row['no_permohonan'].'. Status: '.ucwords(str_replace('_',' ',$status))));
  if(!$this->db->trans_status()){$this->db->trans_rollback();show_error('Informasi gagal disimpan.',500);return;}$this->db->trans_commit();$this->session->set_flashdata('sukses','Status dan informasi berhasil dikirim kepada pemohon.');redirect('admin_itr/detail/'.$id);
 }

 /** Admin menerima/menolak satu berkas. Menolak wajib disertai alasan; pemohon lihat & unggah ulang lewat pemohon/upload-berkas-itr. */
 public function tinjau_berkas($id=0){
  if($this->input->method()!=='post'){show_404();return;}
  $is_ajax=$this->input->is_ajax_request();
  if(!hash_equals((string)$this->session->userdata('admin_itr_token'),(string)$this->input->post('itr_token'))){
   if($is_ajax){$this->output->set_status_header(403)->set_content_type('application/json')->set_output(json_encode(array('ok'=>FALSE,'message'=>'Formulir tidak valid, muat ulang halaman.')));return;}
   show_error('Formulir tidak valid.',403);return;
  }
  $id=(int)$id;
  $row=$this->db->where('id',$id)->get('pengajuan_itr')->row_array(); if(!$row){show_404();return;}
  $field=(string)$this->input->post('field');
  $files=itr_files_untuk($row['jenis_pemohon']??'perorangan');
  if(!isset($files[$field])||empty($row[$field])){show_error('Berkas tidak valid.',422);return;}
  $keputusan=(string)$this->input->post('keputusan');
  $catatan=trim((string)$this->input->post('catatan'));
  if(!in_array($keputusan,array('diterima','ditolak'),TRUE)){show_error('Keputusan tidak valid.',422);return;}
  if($keputusan==='ditolak'&&$catatan===''){
   $pesan='Alasan penolakan berkas '.$files[$field].' wajib diisi.';
   if($is_ajax){$this->output->set_content_type('application/json')->set_output(json_encode(array('ok'=>FALSE,'message'=>$pesan)));return;}
   $this->session->set_flashdata('error',$pesan);redirect('admin_itr/detail/'.$id);return;
  }

  $this->db->where('pengajuan_id',$id)->where('field',$field)->delete('pengajuan_itr_berkas_status');
  $this->db->insert('pengajuan_itr_berkas_status',array('pengajuan_id'=>$id,'field'=>$field,'status'=>$keputusan,'catatan'=>$catatan?:null,'ditinjau_oleh'=>(int)$this->session->userdata('user_id'),'ditinjau_pada'=>date('Y-m-d H:i:s')));

  $status_baru=$this->_perbarui_status_otomatis($id);
  $ket=$keputusan==='diterima' ? ('Admin menerima berkas '.$files[$field].'.') : ('Admin menolak berkas '.$files[$field].': '.$catatan.' Silakan unggah ulang.');
  $this->db->insert('aktivitas_itr',array('user_id'=>$row['user_id'],'pengajuan_id'=>$id,'keterangan'=>$ket,'created_at'=>date('Y-m-d H:i:s')));
  $pesan_sukses='Hasil tinjauan berkas '.$files[$field].' tersimpan.';
  if($is_ajax){
   $row['status']=$status_baru;
   $this->output->set_content_type('application/json')->set_output(json_encode(array(
    'ok'=>TRUE,'field'=>$field,'status'=>$keputusan,'catatan'=>$catatan?:null,
    'status_pengajuan'=>$status_baru,'status_label'=>ucwords(str_replace('_',' ',$status_baru)),
    'semua_diterima'=>itr_semua_diterima($row),'pesan'=>$pesan_sukses,
   )));
   return;
  }
  $this->session->set_flashdata('sukses',$pesan_sukses); redirect('admin_itr/detail/'.$id);
 }

 /** Setelah SEMUA berkas diterima, admin mengunggah dokumen hasil ITR resmi (PDF) yang bisa diunduh pemohon. */
 public function unggah_hasil($id=0){
  if($this->input->method()!=='post'){show_404();return;}
  if(!hash_equals((string)$this->session->userdata('admin_itr_token'),(string)$this->input->post('itr_token'))){show_error('Formulir tidak valid.',403);return;}
  $id=(int)$id;
  $row=$this->db->where('id',$id)->get('pengajuan_itr')->row_array(); if(!$row){show_404();return;}
  if(!itr_semua_diterima($row)){$this->session->set_flashdata('error','Semua berkas wajib diterima dahulu sebelum mengunggah hasil ITR.');redirect('admin_itr/detail/'.$id);return;}
  if(empty($_FILES['file_hasil_itr']['name'])){$this->session->set_flashdata('error','Pilih berkas PDF hasil ITR terlebih dahulu.');redirect('admin_itr/detail/'.$id);return;}
  $this->load->library('upload'); $dir=APPPATH.'uploads/itr/';
  if(!is_dir($dir)&&!mkdir($dir,0750,TRUE)){show_error('Penyimpanan berkas tidak tersedia.',503);return;}
  $this->upload->initialize(array('upload_path'=>$dir,'allowed_types'=>'pdf','max_size'=>102400,'encrypt_name'=>TRUE),TRUE);
  if(!$this->upload->do_upload('file_hasil_itr')){$this->session->set_flashdata('error',strip_tags($this->upload->display_errors('','')));redirect('admin_itr/detail/'.$id);return;}
  $lama=$row['file_hasil_itr'];
  $nilai=berkas_simpan($this->upload->data());
  $this->db->where('id',$id)->update('pengajuan_itr',array('file_hasil_itr'=>$nilai,'hasil_diunggah_pada'=>date('Y-m-d H:i:s'),'status'=>'disetujui'));
  if($lama&&stripos($lama,'http')!==0) @unlink($dir.$lama);
  $this->db->insert('aktivitas_itr',array('user_id'=>$row['user_id'],'pengajuan_id'=>$id,'keterangan'=>'Dokumen hasil ITR resmi telah diterbitkan dan dapat diunduh.','created_at'=>date('Y-m-d H:i:s')));
  $this->session->set_flashdata('sukses','Dokumen hasil ITR berhasil diunggah dan dapat diunduh pemohon.'); redirect('admin_itr/detail/'.$id);
 }

 /** status pengajuan_itr.status dihitung otomatis dari status seluruh baris pengajuan_itr_berkas_status, bukan ditulis manual. */
 private function _perbarui_status_otomatis($id){
  $row=$this->db->where('id',$id)->get('pengajuan_itr')->row_array();
  $files=itr_files_untuk($row['jenis_pemohon']??'perorangan');
  $status_berkas=itr_status_berkas($id);
  $ada_ditolak=false;$semua_diterima=true;
  foreach($files as $field=>$label){
   if(empty($row[$field])){$semua_diterima=false;continue;}
   $st=$status_berkas[$field]['status']??'menunggu';
   if($st==='ditolak'){$ada_ditolak=true;$semua_diterima=false;}
   elseif($st==='menunggu'){$semua_diterima=false;}
  }
  $status_baru=$ada_ditolak?'perlu_perbaikan':($semua_diterima?'disetujui':'sedang_diverifikasi');
  $this->db->where('id',$id)->update('pengajuan_itr',array('status'=>$status_baru));
  return $status_baru;
 }

 public function berkas($id=0,$field=''){
  $field_sah=array_keys(array_merge(itr_file_umum(),itr_file_perusahaan()));
  if(!in_array($field,$field_sah,TRUE)){show_404();return;}
  $row=$this->db->where('id',(int)$id)->get('pengajuan_itr')->row_array();if(!$row||empty($row[$field])){show_404();return;}
  if(stripos($row[$field],'http')===0){redirect($row[$field]);return;}
  $file=APPPATH.'uploads/itr/'.basename($row[$field]);if(!is_file($file)){show_404();return;}$this->load->helper('download');force_download(basename($file),file_get_contents($file),TRUE);
 }

 public function hasil($id=0){
  $row=$this->db->where('id',(int)$id)->get('pengajuan_itr')->row_array();if(!$row||empty($row['file_hasil_itr'])){show_404();return;}
  if(stripos($row['file_hasil_itr'],'http')===0){redirect($row['file_hasil_itr']);return;}
  $file=APPPATH.'uploads/itr/'.basename($row['file_hasil_itr']);if(!is_file($file)){show_404();return;}$this->load->helper('download');force_download(basename($file),file_get_contents($file),TRUE);
 }
}
