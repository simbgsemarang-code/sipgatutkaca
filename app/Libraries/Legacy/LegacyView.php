<?php

namespace App\Libraries\Legacy;

use CodeIgniter\View\View;

/**
 * Renderer view yang meniru lingkungan view CI3: di dalam berkas view, $this->load,
 * $this->session, $this->input, $this->db, $this->config masih tersedia
 * ($this->load->view('partial', $data), $this->session->flashdata('x'), dst.).
 */
#[\AllowDynamicProperties]
class LegacyView extends View
{
    public $load;
    public $session;
    public $input;
    public $db;
    public $config;
    public $output;
}
