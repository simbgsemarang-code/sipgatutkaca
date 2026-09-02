<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pbg_pu extends CI_Controller
{
	private $files = array(
		'file_ktp'=>'KTP/KITAS','file_kepemilikan_tanah'=>'Dokumen kepemilikan tanah',
		'file_data_perencana'=>'Data penyedia jasa perencana','file_pkkpr'=>'Dokumen PKKPR/KRK',
		'file_rencana_teknis'=>'Dokumen teknis arsitektur','file_teknis_struktur'=>'Dokumen teknis struktur',
		'file_checklist_mep'=>'Dokumen mekanikal, elektrikal dan perpipaan',
		'file_pernyataan_tataruang'=>'Surat pernyataan tata ruang','file_dokumen_lingkungan'=>'Dokumen lingkungan',
	);

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session','form_validation','upload'));
		$this->load->helper(array('url','form'));
		$this->load->model('Permohonan_pbg_model','pbg');
		if (!$this->session->userdata('logged_in')) redirect('login');
		if ($this->session->userdata('role') !== 'pu') show_error('Halaman ini khusus PU.',403);
	}

	private function pu_id(){ return (int) $this->session->userdata('user_id'); }
	private function common(){ return array('nama_pengguna'=>$this->session->userdata('nama')); }

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
		if ($id && (!$row || $row['status']!=='diajukan')) show_404();
		$data=$this->common()+array('row'=>$row,'files'=>$this->files,'errors'=>array());
		if ($this->input->method(TRUE)==='POST') {
			foreach(array('nama_pemohon'=>'Nama pemohon','no_hp'=>'Nomor HP','alamat_bangunan'=>'Alamat bangunan','jenis_bangunan'=>'Jenis bangunan') as $f=>$l) $this->form_validation->set_rules($f,$l,'required|trim');
			$this->form_validation->set_rules('nik','NIK','trim|numeric');
			$this->form_validation->set_rules('email','Email','trim|valid_email');
			if ($this->form_validation->run()) {
				$payload=array(
					'user_id'=>$this->pu_id(),'nama_pemohon'=>trim($this->input->post('nama_pemohon')),
					'nik'=>trim($this->input->post('nik'))?:null,'no_hp'=>trim($this->input->post('no_hp')),
					'email'=>trim($this->input->post('email'))?:null,'alamat_bangunan'=>trim($this->input->post('alamat_bangunan')),
					'jenis_bangunan'=>trim($this->input->post('jenis_bangunan')),
					'kategori_bangunan'=>$this->input->post('kategori_bangunan')?:'sederhana',
					'luas_bangunan'=>$this->input->post('luas_bangunan')?:null,'keterangan'=>trim($this->input->post('keterangan'))?:null,
					'updated_at'=>date('Y-m-d H:i:s')
				);
				foreach($this->files as $field=>$label){
					if(!empty($_FILES[$field]['name'])){
						$dir=FCPATH.'assets/uploads/pbg/'; if(!is_dir($dir)) mkdir($dir,0755,true);
						$this->upload->initialize(array('upload_path'=>$dir,'allowed_types'=>'jpg|jpeg|png|pdf','max_size'=>5120,'encrypt_name'=>TRUE),TRUE);
						if($this->upload->do_upload($field)) $payload[$field]=$this->upload->data('file_name'); else $data['errors'][]=$label.': '.strip_tags($this->upload->display_errors('',''));
					}
				}
				if(empty($data['errors'])){
					if($row) $this->pbg->update_owned($row['id'],$this->pu_id(),$payload);
					else { $payload+=array('no_permohonan'=>$this->pbg->generate_no(),'status'=>'diajukan','tahap'=>1,'created_at'=>date('Y-m-d H:i:s')); $this->pbg->insert($payload); }
					$this->session->set_flashdata('sukses','Pengajuan PBG berhasil disimpan.'); redirect('pengajuan-pbg'); return;
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
		$data['riwayat']=$this->db->where('permohonan_id',$id)->order_by('created_at','ASC')->get('aktivitas_pbg')->result_array();
		$data['tpa_per_bidang']=array();
		foreach(array('arsitektur'=>'tpa_arsitek','struktur'=>'tpa_struktur','mep'=>'tpa_mep') as $bidang=>$role){
			$data['tpa_per_bidang'][$bidang]=$this->db->where('role',$role)->order_by('nama','ASC')->get('users')->result_array();
		}
		$data['konsultasi']=$this->db->select('k.*,u.nama AS nama_tpa,u.email AS email_tpa')->from('konsultasi_pbg k')->join('users u','u.id=k.tpa_user_id','left')->where('k.permohonan_id',$id)->order_by('k.putaran','DESC')->order_by('k.bidang','ASC')->get()->result_array();
		$latest=$this->db->select_max('putaran','maks')->where('permohonan_id',$id)->get('konsultasi_pbg')->row_array();$putaran=(int)$latest['maks'];
		$data['konsultasi_selesai']=$putaran>0&&$this->db->where('permohonan_id',$id)->where('putaran',$putaran)->where('status','direkomendasikan')->count_all_results('konsultasi_pbg')===3;
		$this->load->view('pbg_pu/tahap',$data);
	}

	public function ajukan_konsultasi($id)
	{
		$row=$this->pbg->owned($id,$this->pu_id()); if(!$row) show_404();
		$pilihan=array('arsitektur'=>(int)$this->input->post('tpa_arsitektur'),'struktur'=>(int)$this->input->post('tpa_struktur'),'mep'=>(int)$this->input->post('tpa_mep'));
		$roles=array('arsitektur'=>'tpa_arsitek','struktur'=>'tpa_struktur','mep'=>'tpa_mep');
		foreach($pilihan as $bidang=>$uid){if(!$uid||!$this->db->where('id',$uid)->where('role',$roles[$bidang])->count_all_results('users')) show_error('Pilihan TPA '.$bidang.' tidak valid.',422);}
		$file=null;
		if(!empty($_FILES['file_konsultasi']['name'])){
			$dir=FCPATH.'assets/uploads/konsultasi_pbg/'; if(!is_dir($dir)) mkdir($dir,0755,true);
			$this->upload->initialize(array('upload_path'=>$dir,'allowed_types'=>'pdf|doc|docx|jpg|jpeg|png','max_size'=>10240,'encrypt_name'=>TRUE),TRUE);
			if(!$this->upload->do_upload('file_konsultasi')){ $this->session->set_flashdata('error',strip_tags($this->upload->display_errors('',''))); redirect('pengajuan-pbg/tahap/'.$id.'/3'); return; }
			$file=$this->upload->data('file_name');
		}
		$max=$this->db->select_max('putaran','maks')->where('permohonan_id',$id)->get('konsultasi_pbg')->row_array(); $putaran=((int)$max['maks'])+1;
		$this->db->trans_start(); foreach($pilihan as $bidang=>$uid){$this->db->insert('konsultasi_pbg',array('permohonan_id'=>$id,'tpa_user_id'=>$uid,'bidang'=>$bidang,'putaran'=>$putaran,'status'=>'ditugaskan','komentar_pu'=>trim($this->input->post('komentar_pu'))?:null,'pernyataan_pu'=>trim($this->input->post('pernyataan_pu'))?:null,'file_pu'=>$file,'assigned_by'=>$this->pu_id(),'assigned_at'=>date('Y-m-d H:i:s')));} $this->pbg->update_owned($id,$this->pu_id(),array('tahap'=>3,'status'=>'diverifikasi','updated_at'=>date('Y-m-d H:i:s'))); $this->db->trans_complete();
		$this->session->set_flashdata('sukses','Konsultasi putaran '.$putaran.' berhasil ditugaskan kepada tiga TPA.'); redirect('pengajuan-pbg/tahap/'.$id.'/3');
	}

	public function ubah_tahap($id)
	{
		$row=$this->pbg->owned($id,$this->pu_id()); if(!$row) show_404();
		$t=(int)$this->input->post('tahap'); $status=$this->input->post('status');
		if($t<1||$t>4||!in_array($status,array('diajukan','diverifikasi','disetujui','ditolak'),TRUE)) show_error('Tahap/status tidak valid.',422);
		if($t===4&&$status==='disetujui'){
			$latest=$this->db->select_max('putaran','maks')->where('permohonan_id',$id)->get('konsultasi_pbg')->row_array();$putaran=(int)$latest['maks'];
			if($putaran<1||$this->db->where('permohonan_id',$id)->where('putaran',$putaran)->where('status','direkomendasikan')->count_all_results('konsultasi_pbg')!==3) show_error('Proses belum dapat diselesaikan sebelum ketiga bidang TPA memberikan rekomendasi.',422);
		}
		$catatan=trim($this->input->post('catatan')); if($status==='ditolak'&&$catatan==='') show_error('Catatan penolakan wajib diisi.',422);
		$this->pbg->update_owned($id,$this->pu_id(),array('tahap'=>$t,'status'=>$status,'catatan_admin'=>$catatan?:null,'catatan_admin_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')));
		$this->db->insert('aktivitas_pbg',array('permohonan_id'=>$id,'no_permohonan'=>$row['no_permohonan'],'nama_pemohon'=>$row['nama_pemohon'],'tahap'=>$t,'status'=>$status,'keterangan'=>$catatan?:'Tahap diperbarui oleh PU','actor_id'=>$this->pu_id(),'actor'=>$this->session->userdata('nama'),'created_at'=>date('Y-m-d H:i:s')));
		$this->session->set_flashdata('sukses','Tahap permohonan berhasil diperbarui.'); redirect('pengajuan-pbg/tahap/'.$id.'/'.$t);
	}

	public function hapus($id){ $this->pbg->delete_owned($id,$this->pu_id()); redirect('pengajuan-pbg'); }
}
