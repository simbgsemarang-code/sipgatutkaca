<?php

namespace App\Models;

class Permohonan_pbg_model extends LegacyModel
{
	private $table = 'permohonan_pbg';

	public function __construct()
	{
		
		$this->load->database();
	}

	public function generate_no()
	{
		return 'PBG-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
	}

	public function penilaian_dokumen($id)
	{
		$hasil = array();
		if (! $this->db->table_exists('pbg_penilaian_dokumen')) return $hasil;
		foreach ($this->db->where('permohonan_id', (int) $id)->get('pbg_penilaian_dokumen')->result_array() as $r) {
			$hasil[$r['field_dokumen']] = $r;
		}
		return $hasil;
	}

	public function simpan_penilaian($id, $field, $file, $status, $pu_id)
	{
		if (! $this->owned($id, $pu_id) || ! $this->db->table_exists('pbg_penilaian_dokumen')) return FALSE;
		return $this->db->replace('pbg_penilaian_dokumen', array(
			'permohonan_id'=>(int)$id, 'field_dokumen'=>$field, 'nama_file'=>$file,
			'status'=>$status, 'pu_id'=>(int)$pu_id, 'updated_at'=>date('Y-m-d H:i:s')
		));
	}

	public function by_pu($pu_id)
	{
		return $this->db->where('user_id', (int) $pu_id)->order_by('created_at', 'DESC')->get($this->table)->result_array();
	}

	public function owned($id, $pu_id)
	{
		return $this->db->where('id', (int) $id)->where('user_id', (int) $pu_id)->get($this->table)->row_array();
	}

	public function insert($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update_owned($id, $pu_id, $data)
	{
		return $this->db->where('id', (int) $id)->where('user_id', (int) $pu_id)->update($this->table, $data);
	}

	public function delete_owned($id, $pu_id)
	{
		if(!$this->owned($id,$pu_id))return FALSE;
		$this->db->trans_start();
		foreach(array('berita_acara_pbg','berita_acara_distribusi','link_perbaikan_pbg','pbg_penilaian_dokumen','aktivitas_pbg') as $t){if($this->db->table_exists($t))$this->db->where('permohonan_id',(int)$id)->delete($t);}
		if($this->db->table_exists('konsultasi_pbg'))$this->db->where('permohonan_id',(int)$id)->delete('konsultasi_pbg');
		$this->db->where('id',(int)$id)->where('user_id',(int)$pu_id)->delete($this->table);
		$this->db->trans_complete(); return $this->db->trans_status();
	}
}
