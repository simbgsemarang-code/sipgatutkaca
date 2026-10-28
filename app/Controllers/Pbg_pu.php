<?php

namespace App\Controllers;

class Pbg_pu extends LegacyController
{
	private $files = array(
		'file_rencana_teknis'=>'Dokumen Arsitektur',
		'file_teknis_struktur'=>'Dokumen Struktur',
		'file_checklist_mep'=>'Dokumen MEP',
	);

	public function __legacy_construct()
	{
		
		$this->load->library(array('session','form_validation','upload'));
		$this->load->helper(array('url','form','pbg_status','pbg_konsultasi','berkas'));
		$this->load->model('Permohonan_pbg_model','pbg');
		if (!$this->session->userdata('logged_in')) ci_redirect('login');
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
		foreach($data['daftar'] as &$p){
			pbg_sinkron_tahap($p['id']); $p=$this->pbg->owned($p['id'],$this->pu_id());
			$ringkas=$this->konsultasi_terakhir_per_bidang($p['id']); $putaran=0;$menunggu=0;$perbaikan=0;$selesai=0;
			foreach($ringkas as $x){$putaran=max($putaran,(int)$x['putaran']);foreach($x['anggota'] as $m){if($m['status']==='ditugaskan')$menunggu++;elseif($m['status']==='perlu_perbaikan')$perbaikan++;else$selesai++;}}
			if($p['status']==='disetujui'||(int)$p['tahap']>=4)$p['status_rinci']='Sudah Sesuai';
			elseif(!$ringkas)$p['status_rinci']='Permohonan Baru';
			elseif($menunggu)$p['status_rinci']=($putaran>1?'Review Ulang / Revisi '.($putaran-1):'Review TPA').' · '.$selesai.' selesai, '.$menunggu.' menunggu';
			elseif($perbaikan)$p['status_rinci']='Perlu Perbaikan · Revisi '.$putaran.' ('.$perbaikan.' catatan)';
			else $p['status_rinci']=pbg_status_label($p['status'],$p['tahap']);
		} unset($p);
		$data['ba_terbaru']=array();
		foreach($this->db->select('permohonan_id, MAX(id) AS id')->where('status !=','ditugaskan')->group_by('permohonan_id')->get('konsultasi_pbg')->result_array() as $r){
			$data['ba_terbaru'][$r['permohonan_id']]=$r['id'];
		}
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
			$this->form_validation->set_rules('nik','Nomor Registrasi','trim|max_length[50]');
			if ($this->form_validation->run()) {
				$payload=array(
					'user_id'=>$this->pu_id(),'nama_pemohon'=>trim($this->input->post('nama_pemohon')),
					'nik'=>trim($this->input->post('nik'))?:null,
					'nama_bangunan'=>trim($this->input->post('nama_bangunan')),
					'alamat_bangunan'=>trim($this->input->post('alamat_bangunan')),
					'updated_at'=>date('Y-m-d H:i:s')
				);
				if(!$row) $payload+=array('no_hp'=>'','email'=>null,'jenis_bangunan'=>'','kategori_bangunan'=>'sederhana','luas_bangunan'=>null);
				if(empty($data['errors'])){
					if($row){ $this->pbg->update_owned($row['id'],$this->pu_id(),$payload); }
					else { $payload+=array('no_permohonan'=>$this->pbg->generate_no(),'status'=>'diajukan','tahap'=>1,'created_at'=>date('Y-m-d H:i:s')); $this->pbg->insert($payload); }
					$this->session->set_flashdata('sukses','Data pengajuan PBG berhasil disimpan. Lanjutkan melalui tombol Proses Pengajuan.'); ci_redirect('pengajuan-pbg'); return;
				}
			}
		}
		$this->load->view('pbg_pu/form',$data);
	}

	public function dokumen($id)
	{
		$row=$this->pbg->owned($id,$this->pu_id()); if(!$row) show_404();
		if(in_array($row['status'],array('disetujui','ditolak'),TRUE)) show_error('Berkas permohonan yang telah selesai tidak dapat diubah.',422);
		$perbaikan=$this->konsultasi_terakhir_per_bidang($id); $boleh_perbaiki=false; foreach($perbaikan as $k){if($k['status']==='perlu_perbaikan'&&empty($k['perbaikan_dikirim_at']))$boleh_perbaiki=true;}
		$data=$this->common()+array('row'=>$row,'files'=>$this->files,'errors'=>array(),'mode_perbaikan'=>$boleh_perbaiki);
		$is_ajax=$this->input->is_ajax_request();
		if($this->input->method(TRUE)==='POST'){
			$payload=array('updated_at'=>date('Y-m-d H:i:s')); $terunggah=0; $field_diunggah=null;
			foreach($this->files as $field=>$label){
				if(empty($_FILES[$field]['name']))continue;
				$dir=FCPATH.'assets/uploads/pbg/'; if(!is_dir($dir))mkdir($dir,0755,true);
				$this->upload->initialize(array('upload_path'=>$dir,'allowed_types'=>'jpg|jpeg|png|pdf','max_size'=>102400,'encrypt_name'=>TRUE),TRUE);
				if($this->upload->do_upload($field)){$payload[$field]=berkas_simpan($this->upload->data());$terunggah++;$field_diunggah=$field;}
				else $data['errors'][]=$label.': '.strip_tags($this->upload->display_errors('',''));
			}
			if(!$terunggah&&empty($data['errors']))$data['errors'][]='Pilih minimal satu berkas untuk diunggah.';
			if(empty($data['errors'])){
				$this->pbg->update_owned($id,$this->pu_id(),$payload);
				if($boleh_perbaiki)$this->db->where('permohonan_id',$id)->where('status','perlu_perbaikan')->where('perbaikan_dikirim_at IS NULL',NULL,FALSE)->update('konsultasi_pbg',array('catatan_perbaikan'=>'Berkas diperbarui oleh PU.','perbaikan_dikirim_at'=>date('Y-m-d H:i:s')));
				$pesan=$boleh_perbaiki?'Perbaikan berkas berhasil dikirim.':'Berkas berhasil diunggah.';
				if($is_ajax){
					$href=$field_diunggah?berkas_href($payload[$field_diunggah],'assets/uploads/pbg/'):null;
					$this->output->set_content_type('application/json')->set_output(json_encode(array('ok'=>TRUE,'field'=>$field_diunggah,'href'=>$href,'pesan'=>$pesan)));
					return;
				}
				$this->session->set_flashdata('sukses',$pesan); ci_redirect('pengajuan-pbg/upload-berkas/'.$id); return;
			}
			if($is_ajax){
				$this->output->set_content_type('application/json')->set_output(json_encode(array('ok'=>FALSE,'message'=>implode(' ',$data['errors']))));
				return;
			}
		}
		$this->load->view('pbg_pu/upload_berkas',$data);
	}

	public function tahap($id,$tahap=null)
	{
		$row=$this->pbg->owned($id,$this->pu_id()); if(!$row) show_404();
		if(pbg_tugaskan_ulang_perbaikan($id)||pbg_sinkron_tahap($id)) $row=$this->pbg->owned($id,$this->pu_id());
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
		$data['konsultasi_terakhir']=$this->konsultasi_terakhir_per_bidang($id); $data['konsultasi_selesai']=!empty($data['konsultasi_terakhir']); foreach($data['konsultasi_terakhir'] as $k){if($k['status']!=='direkomendasikan')$data['konsultasi_selesai']=false;}
		$data['link_aktif']=$this->db->table_exists('link_perbaikan_pbg'); $data['link_perbaikan']=array();
		if($data['link_aktif']){$data['link_perbaikan']=$this->daftar_link($id);}
		$data['ba_tanggal']=array(); if($this->db->table_exists('berita_acara_pbg')){foreach($this->db->where('permohonan_id',$id)->get('berita_acara_pbg')->result_array() as $b){$data['ba_tanggal'][(int)$b['konsultasi_id']]=!empty($b['tanggal_ba'])?$b['tanggal_ba']:substr($b['diterbitkan_at'],0,10);}}
		$data['ba_aktif']=$this->db->table_exists('berita_acara_distribusi'); $data['ba_distribusi']=array();
		if($data['ba_aktif']){foreach($this->db->where('permohonan_id',$id)->get('berita_acara_distribusi')->result_array() as $b){$data['ba_distribusi'][(int)$b['putaran']]=$b;}}
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
		ci_redirect('pengajuan-pbg/tahap/'.$id.'/'.$tahap);
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
			$this->session->set_flashdata('error','Silakan pilih berkas yang akan diunggah.'); ci_redirect($kembali); return;
		}
		$dir=FCPATH.'assets/uploads/pbg/'; if(!is_dir($dir)) mkdir($dir,0755,true);
		$this->upload->initialize(array('upload_path'=>$dir,'allowed_types'=>'jpg|jpeg|png|pdf','max_size'=>102400,'encrypt_name'=>TRUE),TRUE);
		if(!$this->upload->do_upload('dokumen')){
			$this->session->set_flashdata('error',$this->files[$field].': '.strip_tags($this->upload->display_errors('',''))); ci_redirect($kembali); return;
		}
		$this->pbg->update_owned($id,$this->pu_id(),array($field=>berkas_simpan($this->upload->data()),'updated_at'=>date('Y-m-d H:i:s')));
		$this->session->set_flashdata('sukses',$this->files[$field].' berhasil diunggah.'); ci_redirect($kembali);
	}

	public function ajukan_konsultasi($id)
	{
		$row=$this->pbg->owned($id,$this->pu_id()); if(!$row) show_404();
		if($this->input->method(TRUE)!=='POST')show_404();
		if(in_array($row['status'],array('disetujui','ditolak'),TRUE))show_error('Permohonan telah selesai.',422);
		$pilihan=array(); $terakhir=$this->konsultasi_terakhir_per_bidang($id);
		$roles=array('tpa_arsitek'=>'arsitektur','tpa_struktur'=>'struktur','tpa_mep'=>'mep');
		foreach($terakhir as $k){if($k['status']==='ditugaskan')show_error('Tunggu seluruh TPA menyelesaikan review.',422);}
		if($terakhir)show_error('Konsultasi ulang dikirim otomatis melalui link perbaikan.',422);
		$uid=(int)$this->input->post('tpa_awal'); $user=$this->db->select('id,role')->where('id',$uid)->where_in('role',array_keys($roles))->get('users')->row_array();
		if(!$user)show_error('Pilih satu TPA yang valid.',422); $pilihan[$roles[$user['role']]]=array($uid);
		$max=$this->db->select_max('putaran','maks')->where('permohonan_id',$id)->get('konsultasi_pbg')->row_array(); $putaran=((int)$max['maks'])+1;
		// Putaran 1 = distribusi tugas ke TPA, tetap di Tahap 1 (berkas ditinjau TPA dari SIMBG) sampai semua TPA mengirim hasil
		// (lalu Tahap 2 otomatis - lihat pbg_sinkron_tahap); putaran berikutnya (hasil perbaikan) di Tahap 3.
		$tahap_tujuan=$putaran===1?1:3;
		$this->db->trans_start(); foreach($pilihan as $bidang=>$uids){foreach($uids as $uid){$this->db->insert('konsultasi_pbg',array('permohonan_id'=>$id,'tpa_user_id'=>$uid,'bidang'=>$bidang,'putaran'=>$putaran,'status'=>'ditugaskan','komentar_pu'=>trim($this->input->post('komentar_pu'))?:null,'assigned_by'=>$this->pu_id(),'assigned_at'=>date('Y-m-d H:i:s')));}} $this->pbg->update_owned($id,$this->pu_id(),array('tahap'=>$tahap_tujuan,'status'=>'diverifikasi','updated_at'=>date('Y-m-d H:i:s'))); $ba_terbit=$this->buat_ba_distribusi($id,$putaran,$this->tanggal_ba(),$this->pu_id(),trim((string)$this->input->post('nomor_ba'))); $this->db->trans_complete();
		$this->session->set_flashdata('sukses',($putaran===1?'Tugas review berhasil didistribusikan kepada TPA yang dipilih (putaran 1).':'Konsultasi putaran '.$putaran.' berhasil ditugaskan kepada seluruh TPA yang dipilih.').($ba_terbit?' Berita acara distribusi telah terbit.':'')); ci_redirect('pengajuan-pbg/tahap/'.$id.'/'.$tahap_tujuan);
	}

	/** Tanggal berita acara dari input (Y-m-d valid); bila kosong/tidak valid: hari ini. */
	private function tanggal_ba($nilai=null)
	{
		$n=trim((string)($nilai===null?$this->input->post('tanggal_ba'):$nilai));
		return $this->tanggal_valid($n)?$n:date('Y-m-d');
	}
	private function tanggal_valid($n){ return (bool)(preg_match('/^(\d{4})-(\d{2})-(\d{2})$/',(string)$n,$m)&&checkdate((int)$m[2],(int)$m[3],(int)$m[1])); }

	/** Terbitkan BA distribusi untuk satu putaran (satu per putaran). TRUE kalau baru terbit. */
	private function buat_ba_distribusi($permohonan_id,$putaran,$tanggal,$oleh,$nomor=null){ return pbg_buat_ba_distribusi($permohonan_id,$putaran,$tanggal,$oleh,$nomor); }

	/** Link Google Drive valid: https, host drive.google.com / docs.google.com. */
	private function link_drive_valid($url)
	{
		if(strlen($url)>500||!filter_var($url,FILTER_VALIDATE_URL))return FALSE;
		$p=parse_url($url);
		return isset($p['scheme'],$p['host'])&&strtolower($p['scheme'])==='https'&&in_array(strtolower($p['host']),array('drive.google.com','docs.google.com'),TRUE);
	}

	/** Riwayat link perbaikan satu permohonan: putaran turun, terbaru dulu. */
	private function daftar_link($id)
	{
		return $this->db->select('l.*,u.nama AS nama_pengirim')->from('link_perbaikan_pbg l')->join('users u','u.id=l.dibuat_oleh','left')->where('l.permohonan_id',$id)->order_by('l.putaran','DESC')->order_by('l.id','DESC')->get()->result_array();
	}

	/** Galat form link: halaman galat biasa, atau JSON bila dipanggil lewat AJAX. */
	private function link_gagal($ajax,$pesan,$kode=422)
	{
		if(!$ajax)show_error($pesan,$kode);
		$this->output->set_status_header($kode)->set_content_type('application/json')->set_output(json_encode(array('ok'=>FALSE,'pesan'=>$pesan)));
	}

	/**
	 * PU mengirim link Google Drive berkas perbaikan untuk putaran yang meminta
	 * perbaikan. Disimpan sebagai riwayat per putaran (append-only) dan menandai
	 * perbaikan sebagai terkirim supaya konsultasi berikutnya bisa diajukan.
	 * Lewat AJAX: jawaban JSON berisi riwayat terbaru (HTML) tanpa muat ulang halaman.
	 */
	public function kirim_link_perbaikan($id)
	{
		$ajax=$this->input->is_ajax_request();
		$row=$this->pbg->owned($id,$this->pu_id()); if(!$row) show_404();
		if($this->input->method(TRUE)!=='POST')show_404();
		if(in_array($row['status'],array('disetujui','ditolak'),TRUE))return $this->link_gagal($ajax,'Permohonan telah selesai.');
		if(!$this->db->table_exists('link_perbaikan_pbg'))return $this->link_gagal($ajax,'Tabel link_perbaikan_pbg belum ada. Jalankan migrasi database/link_perbaikan_pbg.sql lebih dulu.',503);
		$url=trim((string)$this->input->post('link_drive')); $ket=trim((string)$this->input->post('keterangan'));
		if(!$this->link_drive_valid($url))return $this->link_gagal($ajax,'Link harus berupa alamat https dari drive.google.com atau docs.google.com.');
		if(mb_strlen($ket)>255)$ket=mb_substr($ket,0,255);
		$putaran=0; foreach($this->konsultasi_terakhir_per_bidang($id) as $k){foreach($k['anggota'] as $m){if($m['status']==='perlu_perbaikan')$putaran=max($putaran,(int)$m['putaran']);}}
		if(!$putaran)return $this->link_gagal($ajax,'Tidak ada TPA yang meminta perbaikan.');
		$this->db->trans_start();
		$this->db->insert('link_perbaikan_pbg',array('permohonan_id'=>$id,'putaran'=>$putaran,'url'=>$url,'keterangan'=>$ket!==''?$ket:null,'dibuat_oleh'=>$this->pu_id(),'created_at'=>date('Y-m-d H:i:s')));
		$this->db->where('permohonan_id',$id)->where('status','perlu_perbaikan')->where('perbaikan_dikirim_at IS NULL',NULL,FALSE)->update('konsultasi_pbg',array('catatan_perbaikan'=>'Link Google Drive perbaikan dikirim oleh PU.','perbaikan_dikirim_at'=>date('Y-m-d H:i:s')));
		// TPA yang meminta perbaikan langsung ditugaskan lagi (putaran berikutnya) agar bisa meninjau ulang tanpa PU mengajukan konsultasi lagi.
		$ditugaskan=pbg_tugaskan_ulang_perbaikan($id); $nama_tpa=array_column($ditugaskan,'nama'); $putaran_baru=$ditugaskan?max(array_column($ditugaskan,'putaran')):0;
		$this->db->trans_complete();
		$pesan='Link perbaikan putaran '.$putaran.' berhasil dikirim.'.($putaran_baru?' '.implode(', ',array_unique($nama_tpa)).' kini dapat meninjau ulang (putaran '.$putaran_baru.').':'');
		if($ajax){
			$this->output->set_content_type('application/json')->set_output(json_encode(array('ok'=>TRUE,'pesan'=>$pesan,'ditugaskan_ulang'=>(bool)$putaran_baru,'riwayat_html'=>$this->load->view('pbg_pu/link_riwayat',array('link_perbaikan'=>$this->daftar_link($id)),TRUE))));
			return;
		}
		$this->session->set_flashdata('sukses',$pesan);
		ci_redirect('pengajuan-pbg/tahap/'.$id.'/'.($putaran_baru?3:(int)$row['tahap']));
	}
	/**
	 * Ubah TPA yang sudah ditugaskan SEBELUM mengirim hasil review (putaran 1).
	 * TPA berstatus 'ditugaskan' boleh diganti/ditambah/dibatalkan; yang sudah
	 * mengirim hasil terkunci. Putaran lanjutan tidak bisa diubah di sini karena
	 * hasil perbaikan wajib kembali ke TPA yang meminta perbaikan.
	 */
	public function ubah_tpa($id)
	{
		$row=$this->pbg->owned($id,$this->pu_id()); if(!$row) show_404();
		if($this->input->method(TRUE)!=='POST')show_404();
		if(in_array($row['status'],array('disetujui','ditolak'),TRUE))show_error('Permohonan telah selesai.',422);
		$terakhir=$this->konsultasi_terakhir_per_bidang($id);
		$roles=array('arsitektur'=>'tpa_arsitek','struktur'=>'tpa_struktur','mep'=>'tpa_mep');
		$rencana=array();
		$putaran_ba=(int)$this->input->post('putaran_ba'); $tanggal_ba=trim((string)$this->input->post('tanggal_ba'));
		if($putaran_ba&&$tanggal_ba!==''&&!$this->tanggal_valid($tanggal_ba))show_error('Tanggal berita acara tidak valid.',422);
		foreach($roles as $bidang=>$role){
			$akhir=$terakhir[$bidang]??null;
			if(!$akhir||$akhir['status']!=='ditugaskan')continue;
			$menunggu=array();$terkunci=array();$contoh=null;$awal=true;
			foreach($akhir['anggota'] as $m){
				if($m['status']==='ditugaskan'){$menunggu[(int)$m['tpa_user_id']]=TRUE;$contoh=$contoh?:$m;if((int)$m['putaran']!==1)$awal=false;}
				else $terkunci[(int)$m['tpa_user_id']]=TRUE;
			}
			if(!$awal)continue;
			$raw=$this->input->post('tpa_'.$bidang); $uids=is_array($raw)?array_values(array_unique(array_map('intval',$raw))):array();
			foreach($uids as $uid){
				if(isset($terkunci[$uid]))show_error('TPA yang sudah mengirim hasil tidak dapat diubah.',422);
				if(!$uid||!$this->db->where('id',$uid)->where('role',$role)->count_all_results('users'))show_error('Pilihan TPA '.$bidang.' tidak valid.',422);
			}
			if(count($terkunci)+count($uids)<1)show_error('Bidang '.$bidang.' harus memiliki minimal satu TPA.',422);
			$rencana[$bidang]=array('hapus'=>array_values(array_diff(array_keys($menunggu),$uids)),'tambah'=>array_values(array_diff($uids,array_keys($menunggu))),'contoh'=>$contoh);
		}
		$berubah=0;
		$this->db->trans_start();
		foreach($rencana as $bidang=>$r){
			if(!empty($r['hapus'])){
				$this->db->where('permohonan_id',$id)->where('bidang',$bidang)->where('putaran',1)->where('status','ditugaskan')->where_in('tpa_user_id',$r['hapus'])->delete('konsultasi_pbg');
				$berubah+=$this->db->affected_rows();
			}
			foreach($r['tambah'] as $uid){
				$this->db->insert('konsultasi_pbg',array('permohonan_id'=>$id,'tpa_user_id'=>$uid,'bidang'=>$bidang,'putaran'=>1,'status'=>'ditugaskan','komentar_pu'=>$r['contoh']['komentar_pu'],'pernyataan_pu'=>$r['contoh']['pernyataan_pu'],'file_pu'=>$r['contoh']['file_pu'],'assigned_by'=>$this->pu_id(),'assigned_at'=>date('Y-m-d H:i:s')));
				$berubah++;
			}
		}
		$ba_berubah=FALSE;
		if($putaran_ba&&$tanggal_ba!==''&&$this->db->table_exists('berita_acara_distribusi')&&$this->db->where('permohonan_id',$id)->where('putaran',$putaran_ba)->count_all_results('konsultasi_pbg')){
			$ada=$this->db->where('permohonan_id',$id)->where('putaran',$putaran_ba)->get('berita_acara_distribusi')->row_array();
			if($ada){if($ada['tanggal_ba']!==$tanggal_ba){$this->db->where('id',$ada['id'])->update('berita_acara_distribusi',array('tanggal_ba'=>$tanggal_ba,'updated_at'=>date('Y-m-d H:i:s')));$ba_berubah=TRUE;}}
			elseif($this->buat_ba_distribusi($id,$putaran_ba,$tanggal_ba,$this->pu_id()))$ba_berubah=TRUE;
		}
		$this->db->trans_complete();
		pbg_sinkron_tahap($id); $row=$this->pbg->owned($id,$this->pu_id());
		$pesan=array(); if($berubah)$pesan[]='Penugasan TPA berhasil diperbarui.'; if($ba_berubah)$pesan[]='Tanggal berita acara berhasil disimpan.';
		$this->session->set_flashdata('sukses',$pesan?implode(' ',$pesan):'Tidak ada perubahan.');
		ci_redirect('pengajuan-pbg/tahap/'.$id.'/'.(int)$row['tahap']);
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
			ci_redirect('pengajuan-pbg/tahap/'.$id.'/2'); return;
		}
		if($t===4&&$status==='disetujui'){
			$akhir=$this->konsultasi_terakhir_per_bidang($id);$selesai=!empty($akhir);foreach($akhir as $k){if($k['status']!=='direkomendasikan')$selesai=false;}if(!$selesai)show_error('Proses belum dapat diselesaikan sebelum seluruh TPA yang ditugaskan menyatakan sudah sesuai.',422);
		}
		$catatan=trim($this->input->post('catatan')); if($status==='ditolak'&&$catatan==='') show_error('Catatan penolakan wajib diisi.',422);
		$this->pbg->update_owned($id,$this->pu_id(),array('tahap'=>$t,'status'=>$status,'catatan_admin'=>$catatan?:null,'catatan_admin_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')));
		$this->db->insert('aktivitas_pbg',array('permohonan_id'=>$id,'no_permohonan'=>$row['no_permohonan'],'nama_pemohon'=>$row['nama_pemohon'],'tahap'=>$t,'status'=>$status,'keterangan'=>$catatan?:'Tahap diperbarui oleh PU','actor_id'=>$this->pu_id(),'actor'=>$this->session->userdata('nama'),'created_at'=>date('Y-m-d H:i:s')));
		$this->session->set_flashdata('sukses','Tahap permohonan berhasil diperbarui.'); ci_redirect('pengajuan-pbg/tahap/'.$id.'/'.$t);
	}

	public function hapus($id){ $this->pbg->delete_owned($id,$this->pu_id()); ci_redirect('pengajuan-pbg'); }
}
