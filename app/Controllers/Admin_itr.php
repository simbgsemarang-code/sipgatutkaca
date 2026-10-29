<?php

namespace App\Controllers;

class Admin_itr extends LegacyController {
 public function __legacy_construct(){$this->load->library('session');$this->load->helper(array('url','form','itr','berkas'));if(!$this->session->userdata('logged_in')){ci_redirect('login?from=admin');exit;}if($this->session->userdata('role')!=='admin'){show_error('Khusus administrator.',403);exit;}if(!$this->session->userdata('admin_itr_token'))$this->session->set_userdata('admin_itr_token',bin2hex(random_bytes(32)));}

 /** Status pengajuan ITR yang sah (urutan = alur proses). */
 private $status_itr=array('diajukan'=>'Diajukan','sedang_diverifikasi'=>'Sedang Diverifikasi','perlu_perbaikan'=>'Perlu Perbaikan','disetujui'=>'Disetujui','ditolak'=>'Ditolak');

 /** Baca & bersihkan parameter pencarian/filter dari URL (dipakai daftar DAN ekspor, jadi hasilnya selalu sama). */
 private function _filter(){
  $q=trim((string)$this->input->get('q'));
  $status=(string)$this->input->get('status');
  $jenis=(string)$this->input->get('jenis');
  $dari=(string)$this->input->get('dari');
  $sampai=(string)$this->input->get('sampai');
  return array(
   'q'=>mb_substr($q,0,100),
   'status'=>isset($this->status_itr[$status])?$status:'',
   'jenis'=>in_array($jenis,array('perorangan','perusahaan'),TRUE)?$jenis:'',
   'kec'=>mb_substr(trim((string)$this->input->get('kec')),0,150),
   'dari'=>preg_match('/^\d{4}-\d{2}-\d{2}$/',$dari)?$dari:'',
   'sampai'=>preg_match('/^\d{4}-\d{2}-\d{2}$/',$sampai)?$sampai:'',
  );
 }

 /** Ambil pengajuan sesuai filter (kosong semua = seluruh data), terbaru dulu. */
 private function _ambil($f){
  if(!$this->db->table_exists('pengajuan_itr'))return array();
  if($f['q']!==''){
   $this->db->group_start();
   foreach(array('no_permohonan','nama_pemohon','nik','nib','no_hp','email','pekerjaan','alamat_pemohon','alamat_lokasi','lokasi_desa_kel','lokasi_kecamatan','jenis_kegiatan','fungsi_bangunan') as $i=>$kol){
    if($i===0)$this->db->like($kol,$f['q']);else $this->db->or_like($kol,$f['q']);
   }
   $this->db->group_end();
  }
  if($f['status']!=='')$this->db->where('status',$f['status']);
  if($f['jenis']!=='')$this->db->where('jenis_pemohon',$f['jenis']);
  if($f['kec']!=='')$this->db->where('lokasi_kecamatan',$f['kec']);
  if($f['dari']!=='')$this->db->where('created_at >=',$f['dari'].' 00:00:00');
  if($f['sampai']!=='')$this->db->where('created_at <=',$f['sampai'].' 23:59:59');
  return $this->db->order_by('id','DESC')->get('pengajuan_itr')->result_array();
 }

 public function index(){
  $data['nama_pengguna']=$this->session->userdata('nama');
  $f=$this->_filter();
  $daftar=$this->_ambil($f);
  foreach($daftar as &$r){
   $r['_status_berkas']=itr_status_berkas($r['id']);
   $r['_semua_diterima']=itr_semua_diterima($r);
  }
  unset($r);
  $data['daftar']=$daftar;
  $data['filter']=$f;
  $data['filter_aktif']=$f['q']!==''||$f['status']!==''||$f['jenis']!==''||$f['kec']!==''||$f['dari']!==''||$f['sampai']!=='';
  $data['status_itr']=$this->status_itr;
  $data['total_semua']=$this->db->table_exists('pengajuan_itr')?$this->db->count_all('pengajuan_itr'):0;
  $kec=array();
  if($this->db->table_exists('pengajuan_itr')){
   foreach($this->db->select('lokasi_kecamatan')->distinct()->order_by('lokasi_kecamatan','ASC')->get('pengajuan_itr')->result_array() as $k){if(trim((string)$k['lokasi_kecamatan'])!=='')$kec[]=$k['lokasi_kecamatan'];}
  }
  $data['daftar_kecamatan']=$kec;
  $data['query_ekspor']=http_build_query(array_filter($f,function($v){return $v!=='';}));
  $this->load->view('pages/admin_itr',$data);
 }

 /** Ekspor Excel (.xlsx): tanpa parameter = SEMUA data; dengan q/status/jenis/kec/dari/sampai = hanya hasil filter. */
 public function ekspor(){
  $f=$this->_filter();
  $daftar=$this->_ambil($f);
  $kolom=array(
   array('judul'=>'No.','lebar'=>6,'tipe'=>'bulat'),
   array('judul'=>'No. Permohonan','lebar'=>24),
   array('judul'=>'Tanggal Pengajuan','lebar'=>18,'tipe'=>'tanggal'),
   array('judul'=>'Status','lebar'=>20),
   array('judul'=>'Jenis Pemohon','lebar'=>14),
   array('judul'=>'Nama Pemohon / Direktur','lebar'=>26),
   array('judul'=>'NIK','lebar'=>20),
   array('judul'=>'NIB','lebar'=>20),
   array('judul'=>'Pekerjaan','lebar'=>18),
   array('judul'=>'No. HP','lebar'=>16),
   array('judul'=>'Email','lebar'=>26),
   array('judul'=>'No. NPWP','lebar'=>22),
   array('judul'=>'Alamat Pemohon / Perusahaan','lebar'=>38),
   array('judul'=>'Jenis Kegiatan / Usaha','lebar'=>26),
   array('judul'=>'Fungsi Bangunan','lebar'=>18),
   array('judul'=>'Lokasi Kegiatan - Jalan','lebar'=>30),
   array('judul'=>'Lokasi Kegiatan - RT/RW','lebar'=>12),
   array('judul'=>'Lokasi Kegiatan - Desa/Kelurahan','lebar'=>22),
   array('judul'=>'Lokasi Kegiatan - Kecamatan','lebar'=>20),
   array('judul'=>'Alamat Lokasi Kegiatan (lengkap)','lebar'=>44),
   array('judul'=>'Luas Lahan (m2)','lebar'=>14,'tipe'=>'angka'),
   array('judul'=>'Luas Bangunan (m2)','lebar'=>16,'tipe'=>'angka'),
   array('judul'=>'Jumlah Lantai','lebar'=>10,'tipe'=>'bulat'),
   array('judul'=>'Status Tanah','lebar'=>16),
   array('judul'=>'Penggunaan Air Baku','lebar'=>18),
   array('judul'=>'Keterangan Perijinan','lebar'=>26),
   array('judul'=>'Rencana Kegiatan','lebar'=>40),
   array('judul'=>'Latitude','lebar'=>13),
   array('judul'=>'Longitude','lebar'=>13),
   array('judul'=>'Jumlah Titik Poligon','lebar'=>12,'tipe'=>'bulat'),
   array('judul'=>'Titik Koordinat Poligon (lat, lng)','lebar'=>50),
   array('judul'=>'Berkas Wajib Diunggah','lebar'=>14),
   array('judul'=>'Berkas Diterima','lebar'=>14),
   array('judul'=>'Hasil ITR','lebar'=>16),
   array('judul'=>'Hasil ITR Diunggah Pada','lebar'=>18,'tipe'=>'tanggal'),
  );
  $baris=array();$no=0;
  foreach($daftar as $r){
   $no++;
   $wajib=itr_files_untuk($r['jenis_pemohon']?:'perorangan');
   $status_berkas=itr_status_berkas($r['id']);
   $jml_unggah=0;$jml_terima=0;
   foreach($wajib as $field=>$label){
    if(!empty($r[$field]))$jml_unggah++;
    if(!empty($r[$field])&&isset($status_berkas[$field])&&$status_berkas[$field]['status']==='diterima')$jml_terima++;
   }
   $titik=json_decode((string)($r['titik_koordinat']??''),TRUE);
   $titik=is_array($titik)?$titik:array();
   $daftar_titik=implode(' | ',array_map(function($t){return number_format((float)$t['lat'],6,'.','').', '.number_format((float)$t['lng'],6,'.','');},$titik));
   $baris[]=array(
    $no,
    $r['no_permohonan'],
    $r['created_at'],
    isset($this->status_itr[$r['status']])?$this->status_itr[$r['status']]:ucwords(str_replace('_',' ',(string)$r['status'])),
    $r['jenis_pemohon']==='perusahaan'?'Perusahaan':'Perorangan',
    $r['nama_pemohon'],
    $r['nik'],
    $r['nib'],
    $r['pekerjaan'],
    $r['no_hp'],
    $r['email'],
    $r['no_npwp'],
    $r['alamat_pemohon'],
    $r['jenis_kegiatan'],
    $r['fungsi_bangunan'],
    $r['lokasi_jalan'],
    $r['lokasi_rt_rw'],
    $r['lokasi_desa_kel'],
    $r['lokasi_kecamatan'],
    $r['alamat_lokasi'],
    $r['luas_lahan'],
    $r['luas_bangunan'],
    $r['lantai_bangunan'],
    $r['status_tanah'],
    $r['penggunaan_air'],
    $r['keterangan_perijinan'],
    $r['rencana_kegiatan'],
    $r['latitude']!==null?number_format((float)$r['latitude'],7,'.',''):'',
    $r['longitude']!==null?number_format((float)$r['longitude'],7,'.',''):'',
    count($titik),
    $daftar_titik,
    $jml_unggah.' dari '.count($wajib),
    $jml_terima.' dari '.count($wajib),
    !empty($r['file_hasil_itr'])?'Sudah diunggah':'Belum',
    $r['hasil_diunggah_pada'],
   );
  }
  $this->load->helper('download');
  $isi=\App\Libraries\XlsxWriter::buat('Pengajuan ITR',$kolom,$baris);
  $nama='Pengajuan-ITR_'.date('Ymd-His').(array_filter($f)?'_filter':'_semua').'.xlsx';
  force_download($nama,$isi,TRUE);
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
  if($isi===''){ci_redirect('admin_itr/detail/'.$id);return;}
  $this->db->trans_begin();$this->db->where('id',(int)$id)->update('pengajuan_itr',array('status'=>$status));
  $this->db->insert('pesan_itr',array('pengajuan_id'=>(int)$id,'user_id'=>$row['user_id'],'admin_id'=>(int)$this->session->userdata('user_id'),'isi'=>$isi));
  $this->db->insert('aktivitas_itr',array('user_id'=>$row['user_id'],'pengajuan_id'=>(int)$id,'keterangan'=>'Admin mengirim informasi untuk '.$row['no_permohonan'].'. Status: '.ucwords(str_replace('_',' ',$status))));
  if(!$this->db->trans_status()){$this->db->trans_rollback();show_error('Informasi gagal disimpan.',500);return;}$this->db->trans_commit();$this->session->set_flashdata('sukses','Status dan informasi berhasil dikirim kepada pemohon.');ci_redirect('admin_itr/detail/'.$id);
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
   $this->session->set_flashdata('error',$pesan);ci_redirect('admin_itr/detail/'.$id);return;
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
  $this->session->set_flashdata('sukses',$pesan_sukses); ci_redirect('admin_itr/detail/'.$id);
 }

 /** Setelah SEMUA berkas diterima, admin mengunggah dokumen hasil ITR resmi (PDF) yang bisa diunduh pemohon. */
 public function unggah_hasil($id=0){
  if($this->input->method()!=='post'){show_404();return;}
  $is_ajax=$this->input->is_ajax_request();
  if(!hash_equals((string)$this->session->userdata('admin_itr_token'),(string)$this->input->post('itr_token'))){
   if($is_ajax){$this->output->set_status_header(403)->set_content_type('application/json')->set_output(json_encode(array('ok'=>FALSE,'message'=>'Formulir tidak valid, muat ulang halaman.')));return;}
   show_error('Formulir tidak valid.',403);return;
  }
  $id=(int)$id;
  $row=$this->db->where('id',$id)->get('pengajuan_itr')->row_array(); if(!$row){show_404();return;}
  if(!itr_semua_diterima($row)){
   $pesan='Semua berkas wajib diterima dahulu sebelum mengunggah hasil ITR.';
   if($is_ajax){$this->output->set_content_type('application/json')->set_output(json_encode(array('ok'=>FALSE,'message'=>$pesan)));return;}
   $this->session->set_flashdata('error',$pesan);ci_redirect('admin_itr/detail/'.$id);return;
  }
  if(empty($_FILES['file_hasil_itr']['name'])){
   $pesan='Pilih berkas PDF hasil ITR terlebih dahulu.';
   if($is_ajax){$this->output->set_content_type('application/json')->set_output(json_encode(array('ok'=>FALSE,'message'=>$pesan)));return;}
   $this->session->set_flashdata('error',$pesan);ci_redirect('admin_itr/detail/'.$id);return;
  }
  $this->load->library('upload'); $dir=LEGACY_STORAGE.'uploads/itr/';
  if(!is_dir($dir)&&!mkdir($dir,0750,TRUE)){show_error('Penyimpanan berkas tidak tersedia.',503);return;}
  $this->upload->initialize(array('upload_path'=>$dir,'allowed_types'=>'pdf','max_size'=>102400,'encrypt_name'=>TRUE),TRUE);
  if(!$this->upload->do_upload('file_hasil_itr')){
   $pesan=strip_tags($this->upload->display_errors('',''));
   if($is_ajax){$this->output->set_content_type('application/json')->set_output(json_encode(array('ok'=>FALSE,'message'=>$pesan)));return;}
   $this->session->set_flashdata('error',$pesan);ci_redirect('admin_itr/detail/'.$id);return;
  }
  $lama=$row['file_hasil_itr'];
  $nilai=berkas_simpan($this->upload->data());
  $diunggah_pada=date('Y-m-d H:i:s');
  $this->db->where('id',$id)->update('pengajuan_itr',array('file_hasil_itr'=>$nilai,'hasil_diunggah_pada'=>$diunggah_pada,'status'=>'disetujui'));
  if($lama&&stripos($lama,'http')!==0) @unlink($dir.$lama);
  $this->db->insert('aktivitas_itr',array('user_id'=>$row['user_id'],'pengajuan_id'=>$id,'keterangan'=>'Dokumen hasil ITR resmi telah diterbitkan dan dapat diunduh.','created_at'=>$diunggah_pada));
  $pesan_sukses='Dokumen hasil ITR berhasil diunggah dan dapat diunduh pemohon.';
  if($is_ajax){
   $this->output->set_content_type('application/json')->set_output(json_encode(array(
    'ok'=>TRUE,'href'=>base_url('admin_itr/hasil/'.$id),'diunggah_pada'=>date('d/m/Y H:i',strtotime($diunggah_pada)),
    'status_pengajuan'=>'disetujui','status_label'=>'Disetujui','pesan'=>$pesan_sukses,
   )));
   return;
  }
  $this->session->set_flashdata('sukses',$pesan_sukses); ci_redirect('admin_itr/detail/'.$id);
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
  if(stripos($row[$field],'http')===0){ci_redirect($row[$field]);return;}
  $file=LEGACY_STORAGE.'uploads/itr/'.basename($row[$field]);if(!is_file($file)){show_404();return;}$this->load->helper('download');force_download(basename($file),file_get_contents($file),TRUE);
 }

 public function hasil($id=0){
  $row=$this->db->where('id',(int)$id)->get('pengajuan_itr')->row_array();if(!$row||empty($row['file_hasil_itr'])){show_404();return;}
  if(stripos($row['file_hasil_itr'],'http')===0){ci_redirect($row['file_hasil_itr']);return;}
  $file=LEGACY_STORAGE.'uploads/itr/'.basename($row['file_hasil_itr']);if(!is_file($file)){show_404();return;}$this->load->helper('download');force_download(basename($file),file_get_contents($file),TRUE);
 }
}
