<?php

namespace App\Controllers;

class Welcome extends LegacyController {

	public function index()
	{
		$this->load->view('pages/beranda');
	}
}
