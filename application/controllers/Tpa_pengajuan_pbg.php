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

	/** Sama persis dengan Pengajuan_pbg::$peta_dokumen - disalin, bukan dibagi lewat library. Dipakai di sini untuk menyaring dokumen sesuai bidang TPA yang login (lihat _grup_dokumen_untuk_peran()). */
	private $peta_dokumen = array(
		'Data Umum' => array(
			'tpa_bidang' => null,
			'dokumen'    => array(
				'ktp'           => 'Data Identitas Pemilik Bangunan (KTP/KITAS)',
				'penyedia_jasa' => 'Data Penyedia Jasa Perencana',
			),
		),
		'Bidang Arsitektur & Tata Kota' => array(
			'tpa_bidang' => array('tpa_arsitek'),
			'dokumen'    => array(
				'kkpr'       => 'Dokumen KKPR / KRK',
				'situasi'    => 'Gambar Situasi',
				'tapak'      => 'Gambar Rencana Tapak Bangunan',
				'denah'      => 'Gambar Rencana Denah Bangunan',
				'potongan'   => 'Gambar Rencana Potongan Bangunan',
				'tampak'     => 'Gambar Rencana Tampak Bangunan',
				'lingkungan' => 'Dokumen Lingkungan (SPPL/UKL-UPL/AMDAL)',
			),
		),
		'Bidang Struktur & Sipil' => array(
			'tpa_bidang' => array('tpa_struktur'),
			'dokumen'    => array(
				'struktur' => 'Gambar & Perhitungan Struktur',
				'gempa'    => 'Analisis Beban & Ketahanan Gempa',
			),
		),
		'Bidang Mekanikal, Elektrikal & Perpipaan (MEP)' => array(
			'tpa_bidang' => array('tpa_mep'),
			'dokumen'    => array(
				'elektrikal'         => 'Gambar Instalasi Elektrikal',
				'plumbing'           => 'Gambar Instalasi Perpipaan (Plumbing)',
				'proteksi_kebakaran' => 'Sistem Proteksi Kebakaran',
			),
		),
		'Dokumen Tambahan' => array(
			'tpa_bidang' => null,
			'dokumen'    => array(
				'tambahan' => 'Dokumen pendukung lain (opsional)',
			),
		),
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

		$dokumen_terunggah = $this->db->where('id_pengajuan', $id)->get('pengajuan_pbg_dokumen')->result_array();
		$peran             = (string) $this->session->userdata('role');

		$data['row']              = $row;
		$data['dokumen_kelompok'] = $this->_kelompokkan_dokumen($dokumen_terunggah, $peran);
		$data['bisa_ditandai']    = ($row['status'] === $this->status_bisa_ditandai);
		$data['opsi_kepemilikan'] = $this->opsi_kepemilikan;
		$data['opsi_kondisi']     = $this->opsi_kondisi;
		$data['error']            = $this->session->flashdata('error');
		$data['sukses']           = $this->session->flashdata('sukses');
		$data['old']              = $this->session->flashdata('old');
		$data['nama_pengguna']    = $this->session->userdata('nama');
		$this->load->view('pages/tpa_pengajuan_pbg_detail', $data);
	}

	/**
	 * Kelompokkan dokumen yang sudah diunggah ke dalam grup bidang
	 * sesuai $peta_dokumen, DISARING supaya cuma grup yang relevan
	 * dengan $peran yang dikembalikan - grup ber-tpa_bidang NULL
	 * (dokumen umum, bukan wewenang satu bidang tertentu) selalu ikut
	 * ditampilkan ke bidang manapun. Akun 'tpa' generik (peran lama)
	 * melihat SEMUA grup, karena tidak terikat satu spesialisasi.
	 * Dokumen yang jenisnya sudah tidak dikenali $peta_dokumen (mis.
	 * data lama sebelum checklist ini ada) tetap dikembalikan lewat
	 * grup "Lainnya" supaya tidak diam-diam hilang dari tampilan.
	 */
	private function _kelompokkan_dokumen($dokumen_terunggah, $peran)
	{
		$per_label = array();
		foreach ($dokumen_terunggah as $d)
		{
			$per_label[$d['jenis_dokumen']][] = $d;
		}

		$hasil = array();
		foreach ($this->peta_dokumen as $judul_grup => $grup)
		{
			$relevan = ($grup['tpa_bidang'] === null) || ($peran === 'tpa') || in_array($peran, $grup['tpa_bidang'], TRUE);

			$berkas = array();
			foreach ($grup['dokumen'] as $label)
			{
				if (isset($per_label[$label]))
				{
					foreach ($per_label[$label] as $d)
					{
						$berkas[] = $d;
					}
					// Label grup ini SELALU dikonsumsi dari $per_label,
					// baik relevan atau tidak - supaya dokumen milik
					// bidang lain tidak keliru nyasar ke "Lainnya" di
					// bawah, cukup disembunyikan (tidak masuk $hasil).
					unset($per_label[$label]);
				}
			}

			if ($relevan)
			{
				$hasil[$judul_grup] = array('tpa_bidang' => $grup['tpa_bidang'], 'berkas' => $berkas);
			}
		}

		$sisa = array();
		foreach ($per_label as $daftar)
		{
			foreach ($daftar as $d)
			{
				$sisa[] = $d;
			}
		}
		if (! empty($sisa))
		{
			$hasil['Lainnya'] = array('tpa_bidang' => null, 'berkas' => $sisa);
		}

		return $hasil;
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
		// 'disetujui_tpa' = semua dokumen dinilai sudah sesuai, tidak
		// perlu perbaikan - satu-satunya keputusan di sini yang
		// catatannya OPSIONAL (2 lainnya tetap wajib diisi alasannya).
		$status_valid  = array('perbaikan_dokumen', 'perbaikan_dokumen_konsultasi', 'disetujui_tpa');
		$butuh_catatan = ($status_baru !== 'disetujui_tpa');

		if (! in_array($status_baru, $status_valid, TRUE))
		{
			$this->session->set_flashdata('error', 'Pilih salah satu keputusan peninjauan terlebih dahulu.');
			$this->session->set_flashdata('old', array('catatan_tpa' => $catatan, 'status_baru' => $status_baru));
			redirect('tpa-pengajuan-pbg/lihat/' . $id);
			return;
		}
		if ($butuh_catatan && $catatan === '')
		{
			$this->session->set_flashdata('error', 'Isi catatan untuk pemohon/PU terlebih dahulu.');
			$this->session->set_flashdata('old', array('catatan_tpa' => $catatan, 'status_baru' => $status_baru));
			redirect('tpa-pengajuan-pbg/lihat/' . $id);
			return;
		}
		// Tidak boleh menyetujui semua kalau masih ada dokumen yang
		// ditandai "tidak sesuai" dan belum dibatalkan tandanya -
		// dua-duanya sekaligus kontradiktif.
		if ($status_baru === 'disetujui_tpa')
		{
			$masih_ditolak = $this->db->where('id_pengajuan', $id)->where('status', 'ditolak')->count_all_results('pengajuan_pbg_dokumen');
			if ($masih_ditolak > 0)
			{
				$this->session->set_flashdata('error', 'Masih ada ' . $masih_ditolak . ' dokumen bertanda "tidak sesuai" - batalkan dulu tandanya (atau pilih salah satu jenis Perbaikan Dokumen) sebelum menyetujui semua.');
				$this->session->set_flashdata('old', array('catatan_tpa' => $catatan, 'status_baru' => $status_baru));
				redirect('tpa-pengajuan-pbg/lihat/' . $id);
				return;
			}
		}

		$this->db->where('id', $id)->update('pengajuan_pbg', array(
			'status'        => $status_baru,
			'catatan_tpa'   => ($catatan !== '') ? $catatan : NULL,
			'ditinjau_oleh' => (int) $this->session->userdata('user_id'),
			'ditinjau_pada' => date('Y-m-d H:i:s'),
		));

		$pesan_sukses = ($status_baru === 'disetujui_tpa')
			? 'Permohonan ditandai selesai ditinjau - semua dokumen dinyatakan sesuai.'
			: 'Catatan perbaikan berhasil dikirim - permohonan kembali ke PU/pemohon untuk ditindaklanjuti.';
		$this->session->set_flashdata('sukses', $pesan_sukses);
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
