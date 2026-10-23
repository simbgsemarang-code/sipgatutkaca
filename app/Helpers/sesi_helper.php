<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Info sesi login untuk navbar halaman PUBLIK (Masuk/Daftar vs nama
 * pengguna + tautan ke dashboard). Dipakai langsung dari dalam view -
 * bukan lewat data yang dioper controller - karena hampir SEMUA
 * halaman publik butuh info ini di navbar, padahal controller-nya
 * masing-masing sangat sederhana (kebanyakan cuma satu baris
 * $this->load->view(...)). Taruh di sini lebih ringkas daripada
 * menambahkan session-loading + data array di setiap controller.
 */
function info_sesi_navbar()
{
	$ci =& get_instance();
	$ci->load->library('session');

	$peta_dashboard = array(
		'admin'        => 'admin',
		'pu'           => 'pu',
		'tpa'          => 'tpa',
		'tpa_arsitek'  => 'tpa',
		'tpa_struktur' => 'tpa',
		'tpa_mep'      => 'tpa',
		'pemohon'      => 'pemohon',
	);

	$role = $ci->session->userdata('role');

	return array(
		'masuk'            => (bool) $ci->session->userdata('logged_in'),
		'nama'             => (string) $ci->session->userdata('nama'),
		'tujuan_dashboard' => isset($peta_dashboard[$role]) ? $peta_dashboard[$role] : '',
	);
}

/**
 * Apakah panel kredensial akun uji coba boleh tampil di halaman login (dan modal ITR)?
 * Dikendalikan admin lewat menu "Pengaturan Login" (tabel pengaturan_login, satu baris id=1).
 * Tabel/baris belum ada = tampil (perilaku lama). Tidak lagi bergantung pada ENVIRONMENT.
 */
function login_panel_uji_aktif()
{
	$db = \Config\Database::connect();
	if (! $db->tableExists("pengaturan_login")) { return TRUE; }
	$baris = $db->table("pengaturan_login")->where("id", 1)->get()->getRowArray();
	return $baris === NULL || ! empty($baris["tampilkan_akun_uji"]);
}
