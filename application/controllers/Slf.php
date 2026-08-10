<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slf extends CI_Controller {

	public function index()
	{
		$this->load->view('pages/slf');
	}
}
