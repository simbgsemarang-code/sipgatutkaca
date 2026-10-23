<?php

namespace App\Controllers;

class Slf extends LegacyController {

	public function index()
	{
		$this->load->view('pages/slf');
	}
}
