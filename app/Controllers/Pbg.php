<?php

namespace App\Controllers;

class Pbg extends LegacyController {

	public function index()
	{
		$this->load->view('pages/pbg');
	}
}
