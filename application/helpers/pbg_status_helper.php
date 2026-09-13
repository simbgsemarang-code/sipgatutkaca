<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function pbg_status_label($status, $tahap = 0)
{
    if ($status === 'disetujui' || ($status === 'diverifikasi' && (int) $tahap >= 4)) {
        return 'Sudah diverifikasi';
    }
    if ($status === 'diverifikasi') {
        return 'Sedang diverifikasi';
    }
    return ucfirst($status);
}
