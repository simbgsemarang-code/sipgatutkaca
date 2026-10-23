<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sumber tunggal daftar field lampiran ITR + status review per-berkas,
 * dipakai bersama oleh Pemohon.php (unggah) dan Admin_itr.php (tinjau)
 * supaya kedua sisi selalu sinkron.
 */

if (! function_exists('itr_file_umum'))
{
	function itr_file_umum()
	{
		return array('file_permohonan'=>'Surat Permohonan','file_ktp'=>'KTP','file_sertifikat'=>'Sertifikat Tanah/Letter C','file_siteplan'=>'Rencana Teknis/Site Plan','file_denah_foto'=>'Denah dan Foto Lokasi');
	}
}

if (! function_exists('itr_file_perusahaan'))
{
	function itr_file_perusahaan()
	{
		return array('file_nib'=>'NIB','file_npwp'=>'NPWP','file_akta'=>'Akta Pendirian Perusahaan');
	}
}

if (! function_exists('itr_file_privat'))
{
	/** Dokumen identitas paling sensitif - TETAP di server, tidak ke Google Drive. */
	function itr_file_privat()
	{
		return array('file_ktp','file_npwp');
	}
}

if (! function_exists('itr_files_untuk'))
{
	function itr_files_untuk($jenis_pemohon)
	{
		$umum = itr_file_umum();
		return $jenis_pemohon === 'perusahaan' ? array_merge($umum, itr_file_perusahaan()) : $umum;
	}
}

if (! function_exists('itr_status_berkas'))
{
	/** array field => baris (status,catatan,ditinjau_pada,...) untuk satu pengajuan. */
	function itr_status_berkas($pengajuan_id)
	{
		$ci =& get_instance();
		$hasil = array();
		foreach ($ci->db->where('pengajuan_id',(int)$pengajuan_id)->get('pengajuan_itr_berkas_status')->result_array() as $r)
		{
			$hasil[$r['field']] = $r;
		}
		return $hasil;
	}
}

if (! function_exists('itr_semua_diterima'))
{
	/** TRUE kalau semua berkas wajib (sesuai jenis_pemohon) sudah diunggah DAN berstatus diterima. */
	function itr_semua_diterima($row)
	{
		$files  = itr_files_untuk($row['jenis_pemohon'] ?? 'perorangan');
		$status = itr_status_berkas($row['id']);
		foreach ($files as $field=>$label)
		{
			if (empty($row[$field])) return FALSE;
			if (($status[$field]['status'] ?? 'menunggu') !== 'diterima') return FALSE;
		}
		return TRUE;
	}
}
