<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (! function_exists('pbg_alur_steps'))
{
	function pbg_alur_steps()
	{
		return array(
			1 => array('label' => 'Pendaftaran Permohonan', 'desc' => 'Petugas PU menginput data dan dokumen warga.'),
			2 => array('label' => 'Pemeriksaan Kelengkapan Data', 'desc' => 'Kelengkapan administrasi dan dokumen diperiksa.'),
			3 => array('label' => 'Verifikasi dan Konsultasi TPA', 'desc' => 'Dokumen teknis ditinjau per bidang dan dapat diperbaiki berulang.'),
			4 => array('label' => 'Pemeriksaan Selesai', 'desc' => 'Seluruh bidang telah menyetujui permohonan.'),
		);
	}
}

if (! function_exists('pbg_tahap_dari_status'))
{
	function pbg_tahap_dari_status($status)
	{
		$peta = array(
			'draf'                          => 1,
			'verifikasi_dokumen'            => 2,
			'perbaikan_dokumen'             => 2,
			'perbaikan_dokumen_konsultasi' => 3,
			'menunggu_jadwal_konsultasi'   => 3,
			'disetujui_tpa'                 => 4,
			'ditolak'                       => 4,
		);
		return isset($peta[$status]) ? $peta[$status] : 1;
	}
}

if (! function_exists('pbg_label_status'))
{
	function pbg_label_status($status)
	{
		$label = array(
			'draf'                          => 'Draf',
			'verifikasi_dokumen'            => 'Pemeriksaan Kelengkapan Data',
			'perbaikan_dokumen'             => 'Perbaikan Kelengkapan Data',
			'perbaikan_dokumen_konsultasi' => 'Perbaikan Hasil Konsultasi TPA',
			'menunggu_jadwal_konsultasi'   => 'Verifikasi dan Konsultasi TPA',
			'disetujui_tpa'                 => 'Pemeriksaan Selesai',
			'ditolak'                       => 'Ditolak',
		);
		return isset($label[$status]) ? $label[$status] : ucwords(str_replace('_', ' ', $status));
	}
}

if (! function_exists('pbg_render_timeline'))
{
	function pbg_render_timeline($status)
	{
		$aktif = pbg_tahap_dari_status($status);
		$ditolak = ($status === 'ditolak');
		$html = '<div class="pbg-process" aria-label="Tahapan proses permohonan PBG">';
		foreach (pbg_alur_steps() as $nomor => $step)
		{
			$kelas = $nomor < $aktif ? 'done' : ($nomor === $aktif ? ($ditolak ? 'rejected' : 'current') : 'pending');
			$html .= '<div class="pbg-process-item ' . $kelas . '">';
			$html .= '<span class="pbg-process-dot">' . ($nomor < $aktif ? '&#10003;' : $nomor) . '</span>';
			$html .= '<div><b>' . htmlspecialchars($step['label'], ENT_QUOTES, 'UTF-8') . '</b><small>' . htmlspecialchars($step['desc'], ENT_QUOTES, 'UTF-8') . '</small></div>';
			$html .= '</div>';
		}
		return $html . '</div>';
	}
}

