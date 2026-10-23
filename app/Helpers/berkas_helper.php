<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (! function_exists('berkas_href'))
{
	/**
	 * Tautan untuk melihat satu berkas, tanpa peduli disimpan di Google
	 * Drive (nilai kolomnya berupa URL https://... penuh) atau masih di
	 * disk server (nilai kolomnya nama file lokal, seperti sebelumnya).
	 * $prefix_lokal contoh: 'assets/uploads/pbg/'.
	 */
	function berkas_href($nilai, $prefix_lokal)
	{
		$nilai = (string) $nilai;
		if ($nilai === '') return '';
		if (stripos($nilai, 'http://') === 0 || stripos($nilai, 'https://') === 0) return $nilai;
		return base_url($prefix_lokal . rawurlencode($nilai));
	}
}

if (! function_exists('berkas_simpan'))
{
	/**
	 * Dipanggil SETELAH $this->upload->do_upload() sukses (berkas sudah
	 * ada di disk lokal). Kalau Google Drive aktif dan berhasil, berkas
	 * dipindah ke sana dan fungsi ini mengembalikan URL Drive-nya (file
	 * lokal langsung dihapus supaya tidak dobel). Kalau Drive nonaktif
	 * atau gagal, fallback diam-diam ke nama file lokal seperti biasa -
	 * tidak ada upload yang gagal gara-gara Drive bermasalah.
	 *
	 * $upload_data = hasil $this->upload->data() dari CI Upload library.
	 */
	function berkas_simpan(array $upload_data)
	{
		$ci =& get_instance();
		$ci->load->library('gdrive');

		if ($ci->gdrive->aktif())
		{
			$hasil = $ci->gdrive->upload($upload_data['full_path'], $upload_data['file_name'], $upload_data['file_type']);
			if ($hasil)
			{
				@unlink($upload_data['full_path']);
				return $hasil['url'];
			}
		}
		return $upload_data['file_name'];
	}
}
