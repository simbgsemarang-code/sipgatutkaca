<?php

namespace App\Controllers;

class Itr extends LegacyController {

	public function index()
	{
		$this->load->view('pages/itr');
	}
}
