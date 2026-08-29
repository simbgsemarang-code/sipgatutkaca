<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	/**
	 * Peran yang sah untuk dipilih saat menambahkan pengguna lewat
	 * halaman ini. 'tpa' generik dan 'pemohon' sengaja tidak ada di
	 * sini (meski keduanya masih nilai ENUM yang sah di database,
	 * untuk akun lama) - TPA sekarang harus salah satu dari 3
	 * spesialisasi, dan pemohon cuma bisa daftar sendiri lewat /daftar.
	 */
	private $peran_valid = array('admin', 'pu', 'tpa_arsitek', 'tpa_struktur', 'tpa_mep');

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->_wajib_admin();
	}

	/**
	 * Semua method di controller ini hanya boleh diakses pengguna yang
	 * sudah login DAN berperan admin. Selain itu ditolak/diarahkan ke
	 * halaman login.
	 */
	private function _wajib_admin()
	{
		if (! $this->session->userdata('logged_in'))
		{
			redirect('login');
			exit;
		}
		if ($this->session->userdata('role') !== 'admin')
		{
			show_error('Halaman ini khusus untuk admin.', 403, 'Akses Ditolak');
		}
	}

	public function pengguna()
	{
		$data['daftar_user'] = $this->db->order_by('created_at', 'DESC')->get('users')->result_array();
		$data['sukses']      = $this->session->flashdata('sukses');
		$data['error']       = $this->session->flashdata('error');
		$data['old']         = $this->session->flashdata('old');
		$data['nama_admin']  = $this->session->userdata('nama');
		$this->load->view('pages/admin_pengguna', $data);
	}

	public function tambah_pengguna()
	{
		$nama     = trim((string) $this->input->post('nama'));
		$email    = trim((string) $this->input->post('email'));
		$nik      = trim((string) $this->input->post('nik'));
		$password = (string) $this->input->post('password');
		$role     = (string) $this->input->post('role');

		$old = array('nama' => $nama, 'email' => $email, 'nik' => $nik, 'role' => $role);

		if ($nama === '' || $email === '' || $password === '' || ! in_array($role, $this->peran_valid, TRUE))
		{
			$this->session->set_flashdata('error', 'Nama, surel, kata sandi, dan jenis pengguna wajib diisi dengan benar.');
			$this->session->set_flashdata('old', $old);
			redirect('admin/pengguna');
			return;
		}

		if (! filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$this->session->set_flashdata('error', 'Format surel tidak valid.');
			$this->session->set_flashdata('old', $old);
			redirect('admin/pengguna');
			return;
		}

		if (strlen($password) < 8)
		{
			$this->session->set_flashdata('error', 'Kata sandi minimal 8 karakter.');
			$this->session->set_flashdata('old', $old);
			redirect('admin/pengguna');
			return;
		}

		$this->db->where('email', $email);
		if ($this->db->get('users')->num_rows() > 0)
		{
			$this->session->set_flashdata('error', 'Surel tersebut sudah terdaftar.');
			$this->session->set_flashdata('old', $old);
			redirect('admin/pengguna');
			return;
		}

		$this->db->insert('users', array(
			'nik'      => $nik !== '' ? $nik : NULL,
			'nama'     => $nama,
			'email'    => $email,
			'password' => password_hash($password, PASSWORD_DEFAULT),
			'role'     => $role,
		));

		$this->session->set_flashdata('sukses', 'Pengguna "' . $nama . '" berhasil ditambahkan sebagai ' . strtoupper($role) . '.');
		redirect('admin/pengguna');
	}

	/**
	 * Daftar SEMUA permohonan PBG (semua staf PU), read-only untuk
	 * admin. Sengaja tidak ada tombol tambah/edit/hapus di sini -
	 * pengelolaan permohonan tetap wewenang PU (lihat Pengajuan_pbg,
	 * yang dijaga _wajib_pu()). Peta persetujuan TPA per bidang
	 * dikumpulkan sekali di sini, pola sama seperti
	 * Pengajuan_pbg::index().
	 */
	public function pengajuan()
	{
		$persetujuan_per_id = array();
		foreach ($this->db->get('pengajuan_pbg_persetujuan_tpa')->result_array() as $p)
		{
			$persetujuan_per_id[$p['id_pengajuan']][$p['bidang']] = $p['status'];
		}

		$data['daftar'] = $this->db
			->select('pengajuan_pbg.*, pembuat.nama AS nama_pembuat')
			->from('pengajuan_pbg')
			->join('users AS pembuat', 'pembuat.id = pengajuan_pbg.dibuat_oleh', 'left')
			->order_by('pengajuan_pbg.created_at', 'DESC')
			->get()->result_array();
		$data['persetujuan_per_id'] = $persetujuan_per_id;
		$data['nama_admin']         = $this->session->userdata('nama');

		$this->load->view('pages/admin_pengajuan', $data);
	}

	/**
	 * Detail satu permohonan PBG, read-only. Query-nya sengaja
	 * menyalin Pengajuan_pbg::lihat() (join reviewer + peninjau) tapi
	 * TANPA cek _milik_saya() - admin boleh lihat permohonan siapa
	 * pun. View 'pages/pengajuan_pbg_detail' dipakai bersama; flag
	 * admin_mode di sana menyembunyikan tombol aksi PU dan mengganti
	 * sidebar + tautan berkas.
	 */
	public function pengajuan_lihat($id = null)
	{
		$id  = (int) $id;
		$row = $id > 0
			? $this->db->select('pengajuan_pbg.*, peninjau.nama AS nama_peninjau, ra.nama AS nama_reviewer_arsitek, rs.nama AS nama_reviewer_struktur, rm.nama AS nama_reviewer_mep, pembuat.nama AS nama_pembuat')
				->from('pengajuan_pbg')
				->join('users AS peninjau', 'peninjau.id = pengajuan_pbg.ditinjau_oleh', 'left')
				->join('users AS ra', 'ra.id = pengajuan_pbg.reviewer_arsitek_id', 'left')
				->join('users AS rs', 'rs.id = pengajuan_pbg.reviewer_struktur_id', 'left')
				->join('users AS rm', 'rm.id = pengajuan_pbg.reviewer_mep_id', 'left')
				->join('users AS pembuat', 'pembuat.id = pengajuan_pbg.dibuat_oleh', 'left')
				->where('pengajuan_pbg.id', $id)
				->get()->row_array()
			: NULL;

		if ($row === NULL)
		{
			show_404();
			return;
		}

		$persetujuan = array();
		foreach ($this->db->select('pengajuan_pbg_persetujuan_tpa.*, peninjau.nama AS nama_peninjau')
			->from('pengajuan_pbg_persetujuan_tpa')
			->join('users AS peninjau', 'peninjau.id = pengajuan_pbg_persetujuan_tpa.ditinjau_oleh', 'left')
			->where('id_pengajuan', $id)->get()->result_array() as $p)
		{
			$persetujuan[$p['bidang']] = $p;
		}

		$data['row']              = $row;
		$data['dokumen']          = $this->db->where('id_pengajuan', $id)->get('pengajuan_pbg_dokumen')->result_array();
		$data['persetujuan']      = $persetujuan;
		$data['opsi_kepemilikan'] = array(
			'perorangan'        => 'Perorangan',
			'badan_hukum_usaha' => 'Badan Hukum / Badan Usaha',
			'pemerintah'        => 'Pemerintah',
		);
		$data['opsi_kondisi'] = array(
			'sudah_ada'        => 'Sudah Ada (Eksisting)',
			'belum_berdiri'    => 'Belum Berdiri',
			'sedang_dibangun'  => 'Sedang Dibangun',
			'renovasi'         => 'Renovasi (Perubahan Bangunan Gedung)',
			'perpanjangan_slf' => 'Sudah Ada (Perpanjangan SLF)',
		);
		$data['sukses']        = NULL;
		$data['error']         = NULL;
		$data['nama_pengguna'] = $this->session->userdata('nama');
		$data['admin_mode']    = TRUE;

		$this->load->view('pages/pengajuan_pbg_detail', $data);
	}

	/**
	 * Sajikan satu berkas terunggah milik permohonan PBG mana pun.
	 * Path SELALU ditentukan lewat lookup database (tidak pernah dari
	 * input), sama seperti Pengajuan_pbg::berkas() - bedanya di sini
	 * tanpa cek _milik_saya() karena admin memang boleh melihat
	 * semua permohonan.
	 */
	public function berkas($tipe = null, $id = null)
	{
		$id          = (int) $id;
		$kolom_valid = array('prototipe_peta', 'bangunan_peta', 'tanah_lampiran');

		if ($tipe === 'dokumen')
		{
			$dok = $this->db->where('id', $id)->get('pengajuan_pbg_dokumen')->row_array();
			if ($dok === NULL)
			{
				show_404();
				return;
			}
			$path       = APPPATH . 'uploads/pengajuan_pbg/' . $dok['path_file'];
			$nama_unduh = $dok['nama_file_asli'];
		}
		elseif (in_array($tipe, $kolom_valid, TRUE))
		{
			$row = $this->db->where('id', $id)->get('pengajuan_pbg')->row_array();
			if ($row === NULL || empty($row[$tipe]))
			{
				show_404();
				return;
			}
			$path       = APPPATH . 'uploads/pengajuan_pbg/' . $row[$tipe];
			$nama_unduh = basename($path);
		}
		else
		{
			show_404();
			return;
		}

		if (! is_file($path))
		{
			show_404();
			return;
		}

		$this->load->helper('file');
		$mime = function_exists('mime_content_type') ? mime_content_type($path) : 'application/octet-stream';
		header('Content-Type: ' . $mime);
		header('Content-Disposition: inline; filename="' . str_replace('"', '', $nama_unduh) . '"');
		header('Content-Length: ' . filesize($path));
		echo read_file($path);
	}

	/**
	 * Daftar SEMUA permohonan SLF, read-only untuk admin - pola sama
	 * persis dengan pengajuan() versi PBG di atas, cuma ganti tabel
	 * pengajuan_slf* dan view admin_pengajuan_slf.
	 */
	public function pengajuan_slf()
	{
		$persetujuan_per_id = array();
		foreach ($this->db->get('pengajuan_slf_persetujuan_tpa')->result_array() as $p)
		{
			$persetujuan_per_id[$p['id_pengajuan']][$p['bidang']] = $p['status'];
		}

		$data['daftar'] = $this->db
			->select('pengajuan_slf.*, pembuat.nama AS nama_pembuat')
			->from('pengajuan_slf')
			->join('users AS pembuat', 'pembuat.id = pengajuan_slf.dibuat_oleh', 'left')
			->order_by('pengajuan_slf.created_at', 'DESC')
			->get()->result_array();
		$data['persetujuan_per_id'] = $persetujuan_per_id;
		$data['nama_admin']         = $this->session->userdata('nama');

		$this->load->view('pages/admin_pengajuan_slf', $data);
	}

	/**
	 * Detail satu permohonan SLF, read-only - menyalin
	 * pengajuan_lihat() versi PBG, ganti tabel pengajuan_slf* dan
	 * view pengajuan_slf_detail (flag admin_mode di sana yang
	 * menyembunyikan aksi PU + mengarahkan sidebar/berkas ke rute admin).
	 */
	public function pengajuan_slf_lihat($id = null)
	{
		$id  = (int) $id;
		$row = $id > 0
			? $this->db->select('pengajuan_slf.*, peninjau.nama AS nama_peninjau, ra.nama AS nama_reviewer_arsitek, rs.nama AS nama_reviewer_struktur, rm.nama AS nama_reviewer_mep, pembuat.nama AS nama_pembuat')
				->from('pengajuan_slf')
				->join('users AS peninjau', 'peninjau.id = pengajuan_slf.ditinjau_oleh', 'left')
				->join('users AS ra', 'ra.id = pengajuan_slf.reviewer_arsitek_id', 'left')
				->join('users AS rs', 'rs.id = pengajuan_slf.reviewer_struktur_id', 'left')
				->join('users AS rm', 'rm.id = pengajuan_slf.reviewer_mep_id', 'left')
				->join('users AS pembuat', 'pembuat.id = pengajuan_slf.dibuat_oleh', 'left')
				->where('pengajuan_slf.id', $id)
				->get()->row_array()
			: NULL;

		if ($row === NULL)
		{
			show_404();
			return;
		}

		$persetujuan = array();
		foreach ($this->db->select('pengajuan_slf_persetujuan_tpa.*, peninjau.nama AS nama_peninjau')
			->from('pengajuan_slf_persetujuan_tpa')
			->join('users AS peninjau', 'peninjau.id = pengajuan_slf_persetujuan_tpa.ditinjau_oleh', 'left')
			->where('id_pengajuan', $id)->get()->result_array() as $p)
		{
			$persetujuan[$p['bidang']] = $p;
		}

		$data['row']              = $row;
		$data['dokumen']          = $this->db->where('id_pengajuan', $id)->get('pengajuan_slf_dokumen')->result_array();
		$data['persetujuan']      = $persetujuan;
		$data['opsi_kepemilikan'] = array(
			'perorangan'        => 'Perorangan',
			'badan_hukum_usaha' => 'Badan Hukum / Badan Usaha',
			'pemerintah'        => 'Pemerintah',
		);
		$data['opsi_kondisi'] = array(
			'sudah_ada'        => 'Sudah Ada (Eksisting)',
			'belum_berdiri'    => 'Belum Berdiri',
			'sedang_dibangun'  => 'Sedang Dibangun',
			'renovasi'         => 'Renovasi (Perubahan Bangunan Gedung)',
			'perpanjangan_slf' => 'Sudah Ada (Perpanjangan SLF)',
		);
		$data['sukses']        = NULL;
		$data['error']         = NULL;
		$data['nama_pengguna'] = $this->session->userdata('nama');
		$data['admin_mode']    = TRUE;

		$this->load->view('pages/pengajuan_slf_detail', $data);
	}

	/** Sajikan berkas permohonan SLF mana pun - salinan berkas() versi PBG dengan tabel/direktori pengajuan_slf. */
	public function berkas_slf($tipe = null, $id = null)
	{
		$id          = (int) $id;
		$kolom_valid = array('prototipe_peta', 'bangunan_peta', 'tanah_lampiran');

		if ($tipe === 'dokumen')
		{
			$dok = $this->db->where('id', $id)->get('pengajuan_slf_dokumen')->row_array();
			if ($dok === NULL)
			{
				show_404();
				return;
			}
			$path       = APPPATH . 'uploads/pengajuan_slf/' . $dok['path_file'];
			$nama_unduh = $dok['nama_file_asli'];
		}
		elseif (in_array($tipe, $kolom_valid, TRUE))
		{
			$row = $this->db->where('id', $id)->get('pengajuan_slf')->row_array();
			if ($row === NULL || empty($row[$tipe]))
			{
				show_404();
				return;
			}
			$path       = APPPATH . 'uploads/pengajuan_slf/' . $row[$tipe];
			$nama_unduh = basename($path);
		}
		else
		{
			show_404();
			return;
		}

		if (! is_file($path))
		{
			show_404();
			return;
		}

		$this->load->helper('file');
		$mime = function_exists('mime_content_type') ? mime_content_type($path) : 'application/octet-stream';
		header('Content-Type: ' . $mime);
		header('Content-Disposition: inline; filename="' . str_replace('"', '', $nama_unduh) . '"');
		header('Content-Length: ' . filesize($path));
		echo read_file($path);
	}

	public function hapus_pengguna($id = null)
	{
		$id = (int) $id;

		if ($id === (int) $this->session->userdata('user_id'))
		{
			$this->session->set_flashdata('error', 'Tidak bisa menghapus akun yang sedang Anda gunakan sendiri.');
			redirect('admin/pengguna');
			return;
		}

		$this->db->where('id', $id)->delete('users');
		$this->session->set_flashdata('sukses', 'Pengguna berhasil dihapus.');
		redirect('admin/pengguna');
	}

	/* ================= KELOLA SEBARAN BANGUNAN (tabel bangunan_gis) =================
	 * Data titik bangunan yang tampil di peta Analisa Kerusakan & Spasial.
	 * Sumbernya sekarang tabel (bukan gis-data.js), disajikan ke peta lewat
	 * Gis::bangunan(). Kolom `kondisi` ENUM '1'..'4' = Baik/Rusak Ringan/
	 * Rusak Sedang/Rusak Berat. */

	private $kondisi_label = array(
		'1' => 'Baik',
		'2' => 'Rusak Ringan',
		'3' => 'Rusak Sedang',
		'4' => 'Rusak Berat',
	);

	/** Daftar bangunan — dengan pencarian + saring kecamatan/kondisi + halaman. */
	public function bangunan()
	{
		$q    = trim((string) $this->input->get('q'));
		$kec  = trim((string) $this->input->get('kec'));
		$kon  = trim((string) $this->input->get('kondisi'));
		$page = max(1, (int) $this->input->get('page'));
		$per  = 25;

		$filter = function () use ($q, $kec, $kon) {
			if ($q !== '')
			{
				$this->db->group_start()
					->like('nama_bangunan', $q)
					->or_like('opd', $q)
					->or_like('alamat', $q)
					->or_like('kelurahan', $q)
					->group_end();
			}
			if ($kec !== '') $this->db->where('kecamatan', $kec);
			if (in_array($kon, array('1', '2', '3', '4'), TRUE)) $this->db->where('kondisi', $kon);
		};

		$filter();
		$total = $this->db->count_all_results('bangunan_gis');

		$filter();
		$data['daftar'] = $this->db
			->order_by('id', 'DESC')
			->limit($per, ($page - 1) * $per)
			->get('bangunan_gis')->result_array();

		$data['total']         = $total;
		$data['page']          = $page;
		$data['per']           = $per;
		$data['total_page']    = max(1, (int) ceil($total / $per));
		$data['q']             = $q;
		$data['kec']           = $kec;
		$data['kondisi']       = $kon;
		$data['daftar_kec']    = $this->db->distinct()->select('kecamatan')->where('kecamatan !=', '')->order_by('kecamatan', 'ASC')->get('bangunan_gis')->result_array();
		$data['kondisi_label'] = $this->kondisi_label;
		$data['sukses']        = $this->session->flashdata('sukses');
		$data['error']         = $this->session->flashdata('error');
		$data['nama_admin']    = $this->session->userdata('nama');

		$this->load->view('pages/admin_bangunan', $data);
	}

	/** Form tambah bangunan baru. */
	public function bangunan_tambah()
	{
		$this->_bangunan_form(NULL);
	}

	/** Form ubah bangunan. */
	public function bangunan_ubah($id = null)
	{
		$row = $this->db->where('id', (int) $id)->get('bangunan_gis')->row_array();
		if ($row === NULL)
		{
			show_404();
			return;
		}
		$this->_bangunan_form($row);
	}

	private function _bangunan_form($row)
	{
		$data['row']           = $row;
		$data['kondisi_label'] = $this->kondisi_label;
		$data['error']         = $this->session->flashdata('error');
		$data['old']           = $this->session->flashdata('old');
		$data['nama_admin']    = $this->session->userdata('nama');
		$this->load->view('pages/admin_bangunan_form', $data);
	}

	/** Simpan (POST) tambah maupun ubah. $id NULL = tambah. */
	public function bangunan_simpan($id = null)
	{
		$id  = (int) $id;
		$row = $id > 0 ? $this->db->where('id', $id)->get('bangunan_gis')->row_array() : NULL;
		if ($id > 0 && $row === NULL)
		{
			show_404();
			return;
		}

		$p = function ($nama) {
			$v = trim((string) $this->input->post($nama));
			return $v !== '' ? $v : NULL;
		};

		$nama    = trim((string) $this->input->post('nama_bangunan'));
		$lat_in  = trim((string) $this->input->post('latitude'));
		$lng_in  = trim((string) $this->input->post('longitude'));
		$kondisi = (string) $this->input->post('kondisi');
		$lantai  = trim((string) $this->input->post('jumlah_lantai'));

		$tujuan = $id > 0 ? 'admin/bangunan-ubah/' . $id : 'admin/bangunan-tambah';

		$kosong = array();
		if ($nama === '')                                    $kosong[] = 'Nama bangunan';
		if (! is_numeric($lat_in) || abs((float) $lat_in) > 90)  $kosong[] = 'Latitude (angka -90..90)';
		if (! is_numeric($lng_in) || abs((float) $lng_in) > 180) $kosong[] = 'Longitude (angka -180..180)';
		if (! in_array($kondisi, array('1', '2', '3', '4'), TRUE)) $kosong[] = 'Kondisi';

		if (! empty($kosong))
		{
			$this->session->set_flashdata('error', 'Periksa lagi: ' . implode(', ', $kosong) . '.');
			$this->session->set_flashdata('old', $this->input->post());
			redirect($tujuan);
			return;
		}

		$simpan = array(
			'opd'           => $p('opd'),
			'unit'          => $p('unit'),
			'institusi'     => $p('institusi'),
			'nama_bangunan' => $nama,
			'fungsi'        => $p('fungsi'),
			'jumlah_lantai' => ($lantai !== '' && is_numeric($lantai)) ? (int) $lantai : NULL,
			'kecamatan'     => $p('kecamatan'),
			'kelurahan'     => $p('kelurahan'),
			'alamat'        => $p('alamat'),
			'kondisi'       => $kondisi,
			'latitude'      => round((float) $lat_in, 8),
			'longitude'     => round((float) $lng_in, 8),
			'foto'          => $p('foto'),
		);

		if ($id > 0)
		{
			$this->db->where('id', $id)->update('bangunan_gis', $simpan);
			$this->session->set_flashdata('sukses', 'Data bangunan "' . $nama . '" berhasil diperbarui.');
		}
		else
		{
			$this->db->insert('bangunan_gis', $simpan);
			$this->session->set_flashdata('sukses', 'Bangunan "' . $nama . '" berhasil ditambahkan ke peta.');
		}
		redirect('admin/bangunan');
	}

	/** Hapus (POST). */
	public function bangunan_hapus($id = null)
	{
		$id  = (int) $id;
		$row = $this->db->where('id', $id)->get('bangunan_gis')->row_array();
		if ($row === NULL)
		{
			$this->session->set_flashdata('error', 'Data bangunan tidak ditemukan.');
			redirect('admin/bangunan');
			return;
		}
		$this->db->where('id', $id)->delete('bangunan_gis');
		$this->session->set_flashdata('sukses', 'Bangunan "' . $row['nama_bangunan'] . '" berhasil dihapus dari peta.');
		redirect('admin/bangunan');
	}
}
