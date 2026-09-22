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
		$this->load->helper(array('wilayah_cilacap','berkas','itr'));
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
		if(!$this->session->userdata('itr_message_token')) $this->session->set_userdata('itr_message_token',bin2hex(random_bytes(32)));
		$data['pesan_itr']=$this->db->table_exists('pesan_itr')?$this->db->select('p.*,i.no_permohonan,u.nama AS nama_admin')->from('pesan_itr p')->join('pengajuan_itr i','i.id=p.pengajuan_id')->join('users u','u.id=p.admin_id','left')->where('p.user_id',(int)$this->session->userdata('user_id'))->order_by('p.id','DESC')->get()->result_array():array();
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
		$data['aktif_itr'] = in_array($content, array('partials/pemohon_itr_form','partials/pemohon_itr_upload'), TRUE);
		$this->load->helper(array('form','url'));
		$this->load->view('pages/pemohon_dashboard', $data);
	}

	public function pengajuan_itr()
	{
		if (!$this->session->userdata('itr_form_token')) $this->session->set_userdata('itr_form_token', bin2hex(random_bytes(32)));
		$this->render_portal('partials/pemohon_itr_form', array('error'=>'','old'=>array()));
	}

	public function baca_pesan_itr($id=0)
	{
		if($this->input->method()!=='post'){show_404();return;}
		$token=(string)$this->session->userdata('itr_message_token');
		if(!$token||!hash_equals($token,(string)$this->input->post('itr_token'))){show_error('Formulir tidak valid.',403);return;}
		if(!$this->db->table_exists('pesan_itr')){show_404();return;}
		$this->db->where('id',(int)$id)->where('user_id',(int)$this->session->userdata('user_id'))->where('dibaca_pada',NULL)->update('pesan_itr',array('dibaca_pada'=>date('Y-m-d H:i:s')));
		redirect('pemohon');
	}

	/** Field sesuai Format Surat Permohonan ITR 2021 (Perorangan & Perusahaan). */
	private $itr_field_umum = array('no_hp','email','jenis_kegiatan','fungsi_bangunan','lokasi_jalan','lokasi_desa_kel','lokasi_kecamatan','luas_lahan','status_tanah','penggunaan_air');
	private $itr_field_opsional = array('lokasi_rt_rw','no_npwp','luas_bangunan','lantai_bangunan','keterangan_perijinan');
	private $itr_field_perorangan = array('nama_pemohon','nik','pekerjaan','alamat_pemohon');
	private $itr_field_perusahaan = array('nib','nama_pemohon','alamat_pemohon'); // nama_pemohon = Nama Direktur untuk perusahaan
	// Daftar field berkas ITR (itr_file_umum/itr_file_perusahaan/itr_file_privat) ada di application/helpers/itr_helper.php - sumber tunggal, dipakai juga oleh Admin_itr.

	public function simpan_itr()
	{
		if ($this->input->method() !== 'post') { show_404(); return; }
		$token = (string)$this->session->userdata('itr_form_token');
		if (!$token || !hash_equals($token, (string)$this->input->post('itr_token'))) { show_error('Formulir kedaluwarsa. Muat ulang halaman.',403); return; }
		if (!$this->db->table_exists('pengajuan_itr') || !$this->db->table_exists('aktivitas_itr')) { show_error('Database pengajuan ITR belum dimigrasi.',503); return; }

		$jenis = (string) $this->input->post('jenis_pemohon');
		if (!in_array($jenis, array('perorangan','perusahaan'), TRUE)) $jenis = 'perorangan';
		$field_jenis = $jenis === 'perorangan' ? $this->itr_field_perorangan : $this->itr_field_perusahaan;
		$file_jenis  = itr_files_untuk($jenis);

		$semua_field = array_unique(array_merge($this->itr_field_umum, $this->itr_field_opsional, $this->itr_field_perorangan, $this->itr_field_perusahaan));
		$old = array(); foreach ($semua_field as $f) $old[$f] = trim((string) $this->input->post($f));
		$old['jenis_pemohon'] = $jenis;

		$this->load->library('form_validation');
		foreach ($this->itr_field_umum as $field) $this->form_validation->set_rules($field, ucwords(str_replace('_',' ',$field)), 'required|trim');
		foreach ($field_jenis as $field) $this->form_validation->set_rules($field, ucwords(str_replace('_',' ',$field)), 'required|trim');
		if ($jenis === 'perorangan') $this->form_validation->set_rules('nik','NIK','required|exact_length[16]|numeric');
		$this->form_validation->set_rules('email','Email','required|valid_email|max_length[150]');
		$this->form_validation->set_rules('luas_lahan','Luas lahan','required|numeric|greater_than[0]|less_than[1000000000000]');

		$titik = json_decode((string) $this->input->post('titik_koordinat'), TRUE);
		$titik_valid = is_array($titik) && count($titik) >= 4;
		if ($titik_valid) foreach ($titik as $t) { if (!isset($t['lat'],$t['lng']) || !is_numeric($t['lat']) || !is_numeric($t['lng'])) { $titik_valid = FALSE; break; } }

		$wilayah = wilayah_cilacap();
		$wilayah_valid = isset($wilayah[$old['lokasi_kecamatan']]) && in_array($old['lokasi_desa_kel'], $wilayah[$old['lokasi_kecamatan']], TRUE);

		if (!$this->form_validation->run() || !$titik_valid || !$wilayah_valid)
		{
			$error = !$this->form_validation->run() ? strip_tags(validation_errors()) : '';
			if (!$titik_valid) $error .= ' Tandai minimal 4 titik koordinat di peta membentuk poligon lokasi.';
			if (!$wilayah_valid) $error .= ' Pilih Kecamatan dan Desa/Kelurahan yang valid.';
			$this->render_portal('partials/pemohon_itr_form', array('error'=>trim($error),'old'=>$old)); return;
		}

		$lat_sum=0;$lng_sum=0; foreach($titik as $t){$lat_sum+=(float)$t['lat'];$lng_sum+=(float)$t['lng'];}
		$n=count($titik);
		$payload = $old;
		$payload['latitude']=round($lat_sum/$n,7); $payload['longitude']=round($lng_sum/$n,7);
		$payload['titik_koordinat']=json_encode(array_map(function($t){return array('lat'=>(float)$t['lat'],'lng'=>(float)$t['lng']);},$titik));
		$payload['alamat_lokasi']=trim($old['lokasi_jalan'].', RT/RW '.$old['lokasi_rt_rw'].', '.$old['lokasi_desa_kel'].', Kec. '.$old['lokasi_kecamatan'],' ,');
		$payload['rencana_kegiatan']=$old['jenis_kegiatan'];
		if ($jenis === 'perusahaan') { $payload['nik']=null; $payload['pekerjaan']=null; }
		else { $payload['nib']=null; }

		$uid=(int)$this->session->userdata('user_id');
		$this->db->insert('pengajuan_itr',array_merge($payload,array('user_id'=>$uid,'status'=>'diajukan'))); $id=(int)$this->db->insert_id();
		$number='ITR-'.date('Ymd').'-'.sprintf('%06d',$id); $this->db->where('id',$id)->update('pengajuan_itr',array('no_permohonan'=>$number));
		$this->db->insert('aktivitas_itr',array('user_id'=>$uid,'pengajuan_id'=>$id,'keterangan'=>'Pengajuan '.$number.' dikirim, menunggu lampiran berkas.','created_at'=>date('Y-m-d H:i:s')));
		$this->session->unset_userdata('itr_form_token'); $this->session->set_flashdata('sukses','Data pengajuan ITR tersimpan. Nomor permohonan: '.$number.'. Lanjutkan unggah berkas persyaratan di bawah.'); redirect('pemohon/upload-berkas-itr/'.$id);
	}

	/** Berkas ITR diunggah terpisah setelah data tersimpan - satu field per permintaan, seperti Upload Berkas PBG. */
	public function upload_berkas_itr($id)
	{
		$id=(int)$id;
		$row=$this->db->where('id',$id)->where('user_id',(int)$this->session->userdata('user_id'))->get('pengajuan_itr')->row_array();
		if (!$row) show_404();
		$files = itr_files_untuk($row['jenis_pemohon']??'perorangan');
		$privat = itr_file_privat();
		$errors=array();
		if ($this->input->method(TRUE)==='POST')
		{
			$this->load->library('upload'); $dir=APPPATH.'uploads/itr/';
			if (!is_dir($dir) && !mkdir($dir,0750,TRUE)) { show_error('Penyimpanan berkas tidak tersedia.',503); return; }
			$terunggah=0;
			foreach ($files as $field=>$label)
			{
				if (empty($_FILES[$field]['name'])) continue;
				$this->upload->initialize(array('upload_path'=>$dir,'allowed_types'=>'pdf|jpg|jpeg|png','max_size'=>102400,'encrypt_name'=>TRUE),TRUE);
				if ($this->upload->do_upload($field))
				{
					$lama=$row[$field];
					$nilai = in_array($field,$privat,TRUE) ? $this->upload->data('file_name') : berkas_simpan($this->upload->data());
					$this->db->where('id',$id)->update('pengajuan_itr',array($field=>$nilai));
					if ($lama && stripos($lama,'http')!==0) @unlink($dir.$lama);
					// Berkas baru/ganti selalu kembali ke antrean review - status lama (mis. ditolak) tidak relevan lagi.
					$this->db->where('pengajuan_id',$id)->where('field',$field)->delete('pengajuan_itr_berkas_status');
					$this->db->insert('pengajuan_itr_berkas_status',array('pengajuan_id'=>$id,'field'=>$field,'status'=>'menunggu'));
					$terunggah++;
				}
				else { $errors[]=$label.': '.strip_tags($this->upload->display_errors('','')); }
			}
			if (!$terunggah && empty($errors)) $errors[]='Pilih minimal satu berkas untuk diunggah.';
			if ($terunggah)
			{
				$this->session->set_flashdata('sukses','Berkas berhasil diunggah, menunggu ditinjau admin.');
				$this->db->insert('aktivitas_itr',array('user_id'=>(int)$this->session->userdata('user_id'),'pengajuan_id'=>$id,'keterangan'=>'Pemohon mengunggah '.$terunggah.' berkas untuk ditinjau ulang.','created_at'=>date('Y-m-d H:i:s')));
			}
			$row=$this->db->where('id',$id)->get('pengajuan_itr')->row_array();
		}
		$status_berkas = itr_status_berkas($id);
		$this->render_portal('partials/pemohon_itr_upload', array('row'=>$row,'files'=>$files,'errors'=>$errors,'status_berkas'=>$status_berkas));
	}

	public function berkas_itr($id=0,$field='')
	{
		$field_sah = array_keys(array_merge(itr_file_umum(), itr_file_perusahaan()));
		if (!in_array($field,$field_sah,TRUE) || !$this->db->table_exists('pengajuan_itr')) { show_404(); return; }
		$row=$this->db->where('id',(int)$id)->where('user_id',(int)$this->session->userdata('user_id'))->get('pengajuan_itr')->row_array();
		if (!$row || empty($row[$field])) { show_404(); return; }
		if (stripos($row[$field],'http')===0) { redirect($row[$field]); return; }
		$file=APPPATH.'uploads/itr/'.basename($row[$field]); if(!is_file($file)){show_404();return;}
		$this->load->helper('download'); force_download(basename($file),file_get_contents($file),TRUE);
	}

	/** Dokumen hasil ITR resmi (PDF) yang diunggah admin setelah semua berkas diterima. */
	public function hasil_itr($id=0)
	{
		$row=$this->db->where('id',(int)$id)->where('user_id',(int)$this->session->userdata('user_id'))->get('pengajuan_itr')->row_array();
		if (!$row || empty($row['file_hasil_itr'])) { show_404(); return; }
		if (stripos($row['file_hasil_itr'],'http')===0) { redirect($row['file_hasil_itr']); return; }
		$file=APPPATH.'uploads/itr/'.basename($row['file_hasil_itr']); if(!is_file($file)){show_404();return;}
		$this->load->helper('download'); force_download(basename($file),file_get_contents($file),TRUE);
	}
}
