<?php

namespace App\Controllers;

class Konsultasi extends LegacyController {

	public function index($layanan = null)
	{
		$this->load->view('pages/konsultasi', array('layanan' => $layanan));
	}
}
