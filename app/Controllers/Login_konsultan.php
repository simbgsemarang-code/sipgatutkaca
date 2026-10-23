<?php

namespace App\Controllers;

class Login_konsultan extends LegacyController {

	public function index()
	{
		$this->load->view('pages/login_konsultan');
	}
}
