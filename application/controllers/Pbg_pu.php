<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pbg_pu extends CI_Controller
{
	private $files = array(
		'file_rencana_teknis'=>'Dokumen Arsitektur',
		'file_teknis_struktur'=>'Dokumen Struktur',
		'file_checklist_mep'=>'Dokumen MEP',
	);

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session','form_validation','upload'));
		$this->load->helper(array('url','form','pbg_status','pbg_konsultasi'));
		$this->load->model('Permohonan_pbg_model','pbg');
		if (!$this->session->userdata('logged_in')) redirect('login');
		if ($this->session->userdata('role') !== 'pu') show_error('Halaman ini khusus PU.',403);
	}

	private function pu_id(){ return (int) $this->session->userdata('user_id'); }
	private function common(){ return array('nama_pengguna'=>$this->session->userdata('nama')); }
	private function dokumen_semua_sesuai($row)
	{
		$wajib=array('file_rencana_teknis','file_teknis_struktur','file_checklist_mep');
		foreach($wajib as $field){if(empty($row[$field]))return FALSE;}
		$penilaian=$this->pbg->penilaian_dokumen($row['id']);
		foreach($this->files as $field=>$label){
			if(empty($row[$field]))continue;
			if(!isset($penilaian[$field])||$penilaian[$field]['nama_file']!==$row[$field]||$penilaian[$field]['status']!=='sesuai')return FALSE;
		}
		return TRUE;
	}
	private function konsultasi_terakhir_per_bidang($id)
	{
		$rows=$this->db->select('k.*,u.nama AS nama_tpa')->from('konsultasi_pbg k')->join('users u','u.id=k.tpa_user_id','left')->where('k.permohonan_id',(int)$id)->order_by('k.putaran','DESC')->order_by('k.id','DESC')->get()->result_array();
		return pbg_ringkasan_konsultasi($rows);
	}

	public function index()
	{
		$data=$this->common(); $data['daftar']=$this->pbg->by_pu($this->pu_id());
		$this->load->view('pbg_pu/index',$data);
	}

	public function tambah(){ $this->form(); }
	public function edit($id){ $this->form($id); }

	private function form($id=null)
	{
		$row=$id ? $this->pbg->owned($id,$this->pu_id()) : null;
		$perbaikan=$row?$this->konsultasi_terakhir_per_bidang($id):array(); $boleh_perbaiki=false; foreach($perbaikan as $k){if($k['status']==='perlu_perbaikan')$boleh_perbaiki=true;}
		if ($id && (!$row || ($row['status']!=='diajukan'&&!$boleh_perbaiki))) show_404();
		$data=$this->common()+array('row'=>$row,'files'=>$this->files,'errors'=>array(),'mode_perbaikan'=>$boleh_perbaiki);
		if ($this->input->method(TRUE)==='POST') {
			$this->form_validation->set_rules('nama_pemohon','Nama pemohon','required|trim');
			$this->form_validation->set_rules('nik','NIK','trim|numeric');
			if ($this->form_validation->run()) {
				$payload=array(
					'user_id'=>$this->pu_id(),'nama_pemohon'=>trim($this->input->post('nama_pemohon')),
					'nik'=>trim($this->input->post('nik'))?:null,
					'keterangan'=>trim($this->input->post('keterangan'))?:null,
					'updated_at'=>date('Y-m-d H:i:s')
				);
				if(!$row) $payload+=array('no_hp'=>'','email'=>null,'alamat_bangunan'=>'','jenis_bangunan'=>'','kategori_bangunan'=>'sederhana','luas_bangunan'=>null);
				foreach($this->files as $field=>$label){
					if(!empty($_FILES[$field]['name'])){
						$dir=FCPATH.'assets/uploads/pbg/'; if(!is_dir($dir)) mkdir($dir,0755,true);
						$this->upload->initialize(array('upload_path'=>$dir,'allowed_types'=>'jpg|jpeg|png|pdf','max_size'=>5120,'encrypt_name'=>TRUE),TRUE);
						if($this->upload->do_upload($field)) $payload[$field]=$this->upload->data('file_name'); else $data['errors'][]=$label.': '.strip_tags($this->upload->display_errors('',''));
					}
				}
				if(empty($data['errors'])){
					if($row){ $this->pbg->update_owned($row['id'],$this->pu_id(),$payload); if($boleh_perbaiki)$this->db->where('permohonan_id',$row['id'])->where('status','perlu_perbaikan')->where('perbaikan_dikirim_at IS NULL',NULL,FALSE)->update('konsultasi_pbg',array('catatan_perbaikan'=>trim($this->input->post('catatan_perbaikan'))?:'Data dan berkas telah diperbaiki oleh PU.','perbaikan_dikirim_at'=>date('Y-m-d H:i:s'))); }
					else { $payload+=array('no_permohonan'=>$this->pbg->generate_no(),'status'=>'diajukan','tahap'=>1,'created_at'=>date('Y-m-d H:i:s')); $this->pbg->insert($payload); }
					$this->session->set_flashdata('sukses',$boleh_perbaiki?'Perbaikan data dan berkas berhasil dikirim. Silakan ajukan konsultasi berikutnya.':'Pengajuan PBG berhasil disimpan.'); redirect($boleh_perbaiki?'pengajuan-pbg/tahap/'.$row['id'].'/3':'pengajuan-pbg'); return;
				}
			}
		}
		$this->load->view('pbg_pu/form',$data);
	}

	public function tahap($id,$tahap=null)
	{
		$row=$this->pbg->owned($id,$this->pu_id()); if(!$row) show_404();
		$tahap=$tahap?(int)$tahap:(int)$row['tahap']; if($tahap<1||$tahap>4) show_404();
		$data=$this->common()+array('row'=>$row,'tahap'=>$tahap,'files'=>$this->files);
		$data['penilaian_dokumen']=$this->pbg->penilaian_dokumen($id);
		$data['semua_sesuai']=$this->dokumen_semua_sesuai($row);
		$data['riwayat']=$this->db->where('permohonan_id',$id)->order_by('created_at','ASC')->get('aktivitas_pbg')->result_array();
		$data['tpa_per_bidang']=array();
		foreach(array('arsitektur'=>'tpa_arsitek','struktur'=>'tpa_struktur','mep'=>'tpa_mep') as $bidang=>$role){
			$data['tpa_per_bidang'][$bidang]=$this->db->where('role',$role)->order_by('nama','ASC')->get('users')->result_array();
		}
		$data['konsultasi']=$this->db->select('k.*,u.nama AS nama_tpa,u.email AS email_tpa')->from('konsultasi_pbg k')->join('users u','u.id=k.tpa_user_id','left')->where('k.permohonan_id',$id)->order_by('k.putaran','DESC')->order_by('k.bidang','ASC')->get()->result_array();
		$data['konsultasi_terakhir']=$this->konsultasi_terakhir_per_bidang($id); $data['konsultasi_selesai']=count($data['konsultasi_terakhir'])===3; foreach($data['konsultasi_terakhir'] as $k){if($k['status']!=='direkomendasikan')$data['konsultasi_selesai']=false;}
		$this->load->view('pbg_pu/tahap',$data);
	}

	public function nilai_dokumen($id)
	{
		if($this->input->method(TRUE)!=='POST') show_404();
		$row=$this->pbg->owned($id,$this->pu_id()); if(!$row) show_404();
		$field=(string)$this->input->post('field'); $nilai=(string)$this->input->post('nilai');
		$tahap=(int)$this->input->post('tahap'); if(!in_array($tahap,array(1,2),TRUE)) show_error('Tahap tidak valid.',422);
		if(!isset($this->files[$field])||empty($row[$field])||!in_array($nilai,array('sesuai','tidak_sesuai'),TRUE)) show_error('Penilaian dokumen tidak valid.',422);
		if(in_array($row['status'],array('disetujui','ditolak'),TRUE)) show_error('Permohonan telah selesai dan tidak dapat dinilai ulang.',422);
		if((string)$this->input->post('nama_file')!==$row[$field]) {
			$this->session->set_flashdata('error','Berkas telah berubah. Silakan periksa berkas terbaru sebelum menilai.');
		} elseif($this->pbg->simpan_penilaian($id,$field,$row[$field],$nilai,$this->pu_id())) {
			$this->session->set_flashdata('sukses','Penilaian '.$this->files[$field].' berhasil disimpan.');
		} else {
			$this->session->set_flashdata('error','Penilaian belum tersimpan. Pastikan migrasi pbg_penilaian_dokumen.sql sudah dijalankan.');
		}
		redirect('pengajuan-pbg/tahap/'.$id.'/'.$tahap);
	}

	public function unggah_dokumen($id)
	{
		$row=$this->pbg->owned($id,$this->pu_id()); if(!$row) show_404();
		$tahap=(int)$this->input->post('tahap'); if($tahap<1||$tahap>2)$tahap=(int)$row['tahap'];
		$kembali='pengajuan-pbg/tahap/'.$id.'/'.$tahap;
		$field=(string)$this->input->post('field');
		if(!array_key_exists($field,$this->files)) show_error('Jenis dokumen tidak valid.',422);
		if(in_array($row['status'],array('disetujui','ditolak'),TRUE)) show_error('Dokumen permohonan yang telah selesai tidak dapat diubah.',422);
		if(!empty($row[$field])&&(!in_array($tahap,array(1,2),TRUE)||!in_array((int)$row['tahap'],array(1,2),TRUE))) show_error('Penggantian dokumen melalui unggah langsung hanya tersedia pada tahap pendaftaran dan pemeriksaan kelengkapan.',409);
		if(empty($_FILES['dokumen']['name'])){
			$this->session->set_flashdata('error','Silakan pilih berkas yang akan diunggah.'); redirect($kembali); return;
		}
		$dir=FCPATH.'assets/uploads/pbg/'; if(!is_dir($dir)) mkdir($dir,0755,true);
		$this->upload->initialize(array('upload_path'=>$dir,'allowed_types'=>'jpg|jpeg|png|pdf','max_size'=>5120,'encrypt_name'=>TRUE),TRUE);
		if(!$this->upload->do_upload('dokumen')){
			$this->session->set_flashdata('error',$this->files[$field].': '.strip_tags($this->upload->display_errors('',''))); redirect($kembali); return;
		}
		$this->pbg->update_owned($id,$this->pu_id(),array($field=>$this->upload->data('file_name'),'updated_at'=>date('Y-m-d H:i:s')));
		$this->session->set_flashdata('sukses',$this->files[$field].' berhasil diunggah.'); redirect($kembali);
	}

	public function ajukan_konsultasi($id)
	{
		$row=$this->pbg->owned($id,$this->pu_id()); if(!$row) show_404();
		if($this->input->method(TRUE)!=='POST')show_404();
		if(in_array($row['status'],array('disetujui','ditolak'),TRUE))show_error('Permohonan telah selesai.',422);
		$pilihan=array(); $terakhir=$this->konsultasi_terakhir_per_bidang($id);
		$roles=array('arsitektur'=>'tpa_arsitek','struktur'=>'tpa_struktur','mep'=>'tpa_mep');
		foreach($terakhir as $k){if($k['status']==='ditugaskan')show_error('Tunggu seluruh TPA menyelesaikan review.',422);}
		foreach($roles as $bidang=>$role){
			$akhir=$terakhir[$bidang]??null;
			if($akhir&&$akhir['status']==='direkomendasikan')continue;
			if($akhir&&empty($akhir['perbaikan_dikirim_at']))show_error('Perbaikan bidang '.$bidang.' belum dikirim.',422);
			$raw=$this->input->post('tpa_'.$bidang); $uids=is_array($raw)?array_values(array_unique(array_map('intval',$raw))):array();
			if(empty($uids))show_error('Pilih minimal satu TPA untuk bidang '.$bidang.'.',422);
			foreach($uids as $uid){
				foreach($akhir['anggota']??array() as $member){if((int)$member['tpa_user_id']===$uid&&$member['status']==='direkomendasikan')show_error('TPA yang telah merekomendasikan tidak dapat dipilih lagi.',422);}
				if(!$uid||!$this->db->where('id',$uid)->where('role',$role)->count_all_results('users'))show_error('Pilihan TPA '.$bidang.' tidak valid.',422);
			}
			$pilihan[$bidang]=$uids;
		}
		if(empty($pilihan))show_error('Seluruh bidang sudah direkomendasikan.',422);
		$file=null;
		if(!empty($_FILES['file_konsultasi']['name'])){
			$dir=FCPATH.'assets/uploads/konsultasi_pbg/'; if(!is_dir($dir)) mkdir($dir,0755,true);
			$this->upload->initialize(array('upload_path'=>$dir,'allowed_types'=>'pdf|doc|docx|jpg|jpeg|png','max_size'=>10240,'encrypt_name'=>TRUE),TRUE);
			if(!$this->upload->do_upload('file_konsultasi')){ $this->session->set_flashdata('error',strip_tags($this->upload->display_errors('',''))); redirect('pengajuan-pbg/tahap/'.$id.'/3'); return; }
			$file=$this->upload->data('file_name');
		}
		$max=$this->db->select_max('putaran','maks')->where('permohonan_id',$id)->get('konsultasi_pbg')->row_array(); $putaran=((int)$max['maks'])+1;
		$this->db->trans_start(); foreach($pilihan as $bidang=>$uids){foreach($uids as $uid){$this->db->insert('konsultasi_pbg',array('permohonan_id'=>$id,'tpa_user_id'=>$uid,'bidang'=>$bidang,'putaran'=>$putaran,'status'=>'ditugaskan','komentar_pu'=>trim($this->input->post('komentar_pu'))?:null,'pernyataan_pu'=>trim($this->input->post('pernyataan_pu'))?:null,'file_pu'=>$file,'assigned_by'=>$this->pu_id(),'assigned_at'=>date('Y-m-d H:i:s')));}} $this->pbg->update_owned($id,$this->pu_id(),array('tahap'=>3,'status'=>'diverifikasi','updated_at'=>date('Y-m-d H:i:s'))); $this->db->trans_complete();
		$this->session->set_flashdata('sukses','Konsultasi putaran '.$putaran.' berhasil ditugaskan kepada seluruh TPA yang dipilih.'); redirect('pengajuan-pbg/tahap/'.$id.'/3');
	}

	public function ubah_tahap($id)
	{
		$row=$this->pbg->owned($id,$this->pu_id()); if(!$row) show_404();
		$t=(int)$this->input->post('tahap'); $status=$this->input->post('status');
		if($t<1||$t>4||!in_array($status,array('diajukan','diverifikasi','disetujui','ditolak'),TRUE)) show_error('Tahap/status tidak valid.',422);
		$wajib=array('file_rencana_teknis','file_teknis_struktur','file_checklist_mep');
		$lengkap=true; foreach($wajib as $field){if(empty($row[$field]))$lengkap=false;}
		if($t===3&&!$lengkap){
			$this->session->set_flashdata('error','Lengkapi seluruh dokumen wajib sebelum melanjutkan.');
			redirect('pengajuan-pbg/tahap/'.$id.'/2'); return;
		}
		if($t===4&&$status==='disetujui'){
			$akhir=$this->konsultasi_terakhir_per_bidang($id);$selesai=count($akhir)===3;foreach($akhir as $k){if($k['status']!=='direkomendasikan')$selesai=false;}if(!$selesai)show_error('Proses belum dapat diselesaikan sebelum ketiga bidang TPA memberikan rekomendasi.',422);
		}
		$catatan=trim($this->input->post('catatan')); if($status==='ditolak'&&$catatan==='') show_error('Catatan penolakan wajib diisi.',422);
		$this->pbg->update_owned($id,$this->pu_id(),array('tahap'=>$t,'status'=>$status,'catatan_admin'=>$catatan?:null,'catatan_admin_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')));
		$this->db->insert('aktivitas_pbg',array('permohonan_id'=>$id,'no_permohonan'=>$row['no_permohonan'],'nama_pemohon'=>$row['nama_pemohon'],'tahap'=>$t,'status'=>$status,'keterangan'=>$catatan?:'Tahap diperbarui oleh PU','actor_id'=>$this->pu_id(),'actor'=>$this->session->userdata('nama'),'created_at'=>date('Y-m-d H:i:s')));
		$this->session->set_flashdata('sukses','Tahap permohonan berhasil diperbarui.'); redirect('pengajuan-pbg/tahap/'.$id.'/'.$t);
	}

	public function hapus($id){ $this->pbg->delete_owned($id,$this->pu_id()); redirect('pengajuan-pbg'); }
}
