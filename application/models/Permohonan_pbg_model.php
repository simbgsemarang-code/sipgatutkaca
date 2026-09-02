<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permohonan_pbg_model extends CI_Model
{
	private $table = 'permohonan_pbg';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function generate_no()
	{
		return 'PBG-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
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
		return $this->db->where('id', (int) $id)->where('user_id', (int) $pu_id)->where('status', 'diajukan')->delete($this->table);
	}
}
