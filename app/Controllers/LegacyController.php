<?php

namespace App\Controllers;

use App\Libraries\Legacy\Legacy;
use App\Libraries\Legacy\LegacyConfig;
use App\Libraries\Legacy\LegacyDb;
use App\Libraries\Legacy\LegacyInput;
use App\Libraries\Legacy\LegacyLoader;
use App\Libraries\Legacy\LegacyOutput;
use App\Libraries\Legacy\LegacySession;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Induk semua controller hasil migrasi dari CodeIgniter 3.
 *
 * Menyediakan objek-objek bergaya CI3 ($this->db, $this->input, $this->session,
 * $this->load, $this->output, $this->config, ...) sehingga kode controller lama
 * tetap berjalan. Konstruktor lama dipindahkan ke __legacy_construct() karena di CI4
 * objek request/response baru tersedia setelah initController().
 */
#[\AllowDynamicProperties]
abstract class LegacyController extends Controller
{
    public $db;
    public $input;
    public $session;
    public $output;
    public $load;
    public $config;
    public $upload;
    public $form_validation;
    public $pagination;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        Legacy::setController($this);
        $this->config  = new LegacyConfig();
        $this->input   = new LegacyInput();
        $this->output  = new LegacyOutput();
        $this->session = new LegacySession();
        $this->db      = new LegacyDb();
        $this->load    = new LegacyLoader($this);

        if (method_exists($this, '__legacy_construct')) {
            $this->__legacy_construct();
        }
    }
}
