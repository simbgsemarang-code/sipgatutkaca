<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Label status mengikuti posisi pada timeline tahapan PU (tahap.php):
 * 1 Pendaftaran Permohonan, 2 Distribusi Tugas ke TPA, 3 Revisi Dokumen Pengajuan,
 * 4 Sudah Sesuai (selesai / disetujui). Ditolak tetap "Ditolak".
 */
function pbg_status_label($status, $tahap = 0)
{
    $tahap = (int) $tahap;
    if ($status === 'ditolak') {
        return 'Ditolak';
    }
    if ($status === 'disetujui' || $tahap >= 4) {
        return 'Sudah Sesuai';
    }
    $label = array(1 => 'Pendaftaran Permohonan', 2 => 'Distribusi Tugas ke TPA', 3 => 'Revisi Dokumen Pengajuan');
    if (isset($label[$tahap])) {
        return $label[$tahap];
    }
    return ucfirst($status);
}
