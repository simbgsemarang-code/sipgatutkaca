<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_konsultan extends CI_Controller {

	public function index()
	{
		$this->load->view('pages/login_konsultan');
	}
}
