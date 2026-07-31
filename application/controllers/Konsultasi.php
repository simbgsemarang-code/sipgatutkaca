<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konsultasi extends CI_Controller {

	public function index($layanan = null)
	{
		$this->load->view('pages/konsultasi', array('layanan' => $layanan));
	}
}
