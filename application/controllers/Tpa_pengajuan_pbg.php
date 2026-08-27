<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Peninjauan Pengajuan PBG oleh TPA (Tim Profesi Ahli) - menandai
 * dokumen yang tidak sesuai dan memutuskan jenis perbaikan yang
 * perlu dilalui pemohon/PU. Melengkapi alur loket di
 * Pengajuan_pbg.php (sisi PU/pemohon) dengan sisi peninjau,
 * mengikuti Panduan Permohonan PBG halaman 30-44.
 *
 * Panduan sumbernya sendiri cuma dari sisi pemohon (tidak
 * menunjukkan halaman peninjau) - controller ini yang mengisi sisi
 * itu, mengikuti pola transisi status yang tersirat: TPA menandai
 * permohonan berstatus "Verifikasi Kelengkapan Dokumen" sebagai
 * perlu "Perbaikan Dokumen" atau "Perbaikan Dokumen Konsultasi";
 * begitu pemohon/PU merespons (lihat Pengajuan_pbg::kirim_perbaikan()),
 * status kembali ke "Verifikasi Kelengkapan Dokumen" atau maju ke
 * "Menunggu Jadwal Konsultasi". Langkah setelah itu (pelaksanaan
 * konsultasi, penerbitan SK PBG) belum tercakup di sini.
 */
class Tpa_pengajuan_pbg extends CI_Controller {

	/** 'tpa' generik dipertahankan untuk akun lama; anggota baru memakai salah satu spesialisasi. */
	private $peran_tpa = array('tpa', 'tpa_arsitek', 'tpa_struktur', 'tpa_mep');

	/** Cuma permohonan di status ini yang bisa ditandai TPA - lihat catatan class di atas. */
	private $status_bisa_ditandai = 'verifikasi_dokumen';

	/** Sama persis dengan Pengajuan_pbg - disalin, bukan dibagi lewat library, mengikuti pola guard peran di codebase ini. */
	private $opsi_kepemilikan = array(
		'perorangan'        => 'Perorangan',
		'badan_hukum_usaha' => 'Badan Hukum / Badan Usaha',
		'pemerintah'        => 'Pemerintah',
	);

	private $opsi_kondisi = array(
		'sudah_ada'        => 'Sudah Ada (Eksisting)',
		'belum_berdiri'    => 'Belum Berdiri',
		'sedang_dibangun'  => 'Sedang Dibangun',
		'renovasi'         => 'Renovasi (Perubahan Bangunan Gedung)',
		'perpanjangan_slf' => 'Sudah Ada (Perpanjangan SLF)',
	);

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->_wajib_tpa();
	}

	private function _wajib_tpa()
	{
		if (! $this->session->userdata('logged_in'))
		{
			redirect('login');
			exit;
		}
		if (! in_array($this->session->userdata('role'), $this->peran_tpa, TRUE))
		{
			show_error('Halaman ini khusus untuk TPA.', 403, 'Akses Ditolak');
		}
	}

	/** Daftar permohonan yang sudah terkirim (bukan draf) - jadi antrean peninjauan TPA. */
	public function index()
	{
		$data['daftar']        = $this->db->where('status !=', 'draf')->order_by('created_at', 'DESC')->get('pengajuan_pbg')->result_array();
		$data['sukses']        = $this->session->flashdata('sukses');
		$data['error']         = $this->session->flashdata('error');
		$data['nama_pengguna'] = $this->session->userdata('nama');
		$this->load->view('pages/tpa_pengajuan_pbg_list', $data);
	}

	/** Detail permohonan + aksi peninjauan (kalau statusnya masih bisa ditandai). */
	public function lihat($id = null)
	{
		$id  = (int) $id;
		$row = $id > 0
			? $this->db->select('pengajuan_pbg.*, peninjau.nama AS nama_peninjau')
				->from('pengajuan_pbg')
				->join('users AS peninjau', 'peninjau.id = pengajuan_pbg.ditinjau_oleh', 'left')
				->where('pengajuan_pbg.id', $id)->where('pengajuan_pbg.status !=', 'draf')
				->get()->row_array()
			: NULL;

		if ($row === NULL)
		{
			show_404();
			return;
		}

		$data['row']              = $row;
		$data['dokumen']          = $this->db->where('id_pengajuan', $id)->get('pengajuan_pbg_dokumen')->result_array();
		$data['bisa_ditandai']    = ($row['status'] === $this->status_bisa_ditandai);
		$data['opsi_kepemilikan'] = $this->opsi_kepemilikan;
		$data['opsi_kondisi']     = $this->opsi_kondisi;
		$data['error']            = $this->session->flashdata('error');
		$data['sukses']           = $this->session->flashdata('sukses');
		$data['old']              = $this->session->flashdata('old');
		$data['nama_pengguna']    = $this->session->userdata('nama');
		$this->load->view('pages/tpa_pengajuan_pbg_detail', $data);
	}

	/** Tandai satu dokumen "tidak sesuai" (dengan catatan alasan) atau batalkan tandanya. */
	public function tandai_dokumen($id_dokumen = null)
	{
		$id_dokumen = (int) $id_dokumen;
		$dok = $this->db->where('id', $id_dokumen)->get('pengajuan_pbg_dokumen')->row_array();

		if ($dok === NULL)
		{
			show_404();
			return;
		}

		$tujuan_ulang = 'tpa-pengajuan-pbg/lihat/' . (int) $dok['id_pengajuan'];
		$aksi = (string) $this->input->post('aksi');

		if ($aksi === 'tolak')
		{
			$catatan = trim((string) $this->input->post('catatan'));
			if ($catatan === '')
			{
				$this->session->set_flashdata('error', 'Catatan alasan wajib diisi saat menandai dokumen tidak sesuai.');
				redirect($tujuan_ulang);
				return;
			}
			$this->db->where('id', $id_dokumen)->update('pengajuan_pbg_dokumen', array(
				'status'            => 'ditolak',
				'catatan_penolakan' => $catatan,
			));
		}
		elseif ($aksi === 'batal')
		{
			$this->db->where('id', $id_dokumen)->update('pengajuan_pbg_dokumen', array(
				'status'            => 'terunggah',
				'catatan_penolakan' => NULL,
			));
		}

		redirect($tujuan_ulang);
	}

	/** Kirim keputusan peninjauan: permohonan perlu Perbaikan Dokumen atau Perbaikan Dokumen Konsultasi. */
	public function kirim_catatan($id = null)
	{
		$id  = (int) $id;
		$row = $this->db->where('id', $id)->where('status', $this->status_bisa_ditandai)->get('pengajuan_pbg')->row_array();

		if ($row === NULL)
		{
			$this->session->set_flashdata('error', 'Permohonan tidak ditemukan atau statusnya sudah berubah - muat ulang halaman.');
			redirect('tpa-pengajuan-pbg');
			return;
		}

		$status_baru  = (string) $this->input->post('status_baru');
		$catatan      = trim((string) $this->input->post('catatan_tpa'));
		$status_valid = array('perbaikan_dokumen', 'perbaikan_dokumen_konsultasi');

		if (! in_array($status_baru, $status_valid, TRUE) || $catatan === '')
		{
			$this->session->set_flashdata('error', 'Pilih jenis perbaikan dan isi catatan untuk pemohon terlebih dahulu.');
			$this->session->set_flashdata('old', array('catatan_tpa' => $catatan, 'status_baru' => $status_baru));
			redirect('tpa-pengajuan-pbg/lihat/' . $id);
			return;
		}

		$this->db->where('id', $id)->update('pengajuan_pbg', array(
			'status'        => $status_baru,
			'catatan_tpa'   => $catatan,
			'ditinjau_oleh' => (int) $this->session->userdata('user_id'),
			'ditinjau_pada' => date('Y-m-d H:i:s'),
		));

		$this->session->set_flashdata('sukses', 'Catatan perbaikan berhasil dikirim - permohonan kembali ke PU/pemohon untuk ditindaklanjuti.');
		redirect('tpa-pengajuan-pbg');
	}

	/**
	 * Sajikan satu berkas terunggah dengan aman - path SELALU
	 * ditentukan lewat lookup database (tidak pernah dari input
	 * pengguna), akses dijaga _wajib_tpa() lewat constructor. Sengaja
	 * disalin dari Pengajuan_pbg::berkas() (bukan dibagi lewat
	 * library) mengikuti pola guard peran di codebase ini.
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
}
