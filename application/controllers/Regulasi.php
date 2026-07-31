<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regulasi extends CI_Controller {

	public function index()
	{
		$this->load->view('pages/regulasi');
	}
}
