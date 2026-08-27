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
 * itu, mengikuti pola transisi status yang tersirat.
 *
 * PERSETUJUAN PER BIDANG: ketiga spesialisasi TPA (Arsitektur & Tata
 * Kota, Struktur & Sipil, MEP) meninjau & memutuskan SECARA
 * INDEPENDEN lewat tabel pengajuan_pbg_persetujuan_tpa - satu baris
 * per bidang per permohonan (tanpa baris = bidang itu belum
 * meninjau). Kolom pengajuan_pbg.status DITURUNKAN dari isi tabel
 * itu lewat _hitung_status_keseluruhan(), bukan ditulis langsung
 * oleh satu keputusan: permohonan baru berstatus "Disetujui TPA"
 * kalau KETIGA bidang sudah menyetujui. Kalau satu bidang menandai
 * "Perbaikan Dokumen" dan PU sudah memperbaikinya, cuma baris bidang
 * itu yang dihapus (perlu ditinjau ulang) - bidang lain yang sudah
 * lebih dulu menyetujui TIDAK ikut direset (lihat
 * Pengajuan_pbg::kirim_perbaikan()). "Perbaikan Dokumen Konsultasi"
 * tetap jadi jalan keluar satu arah ke "Menunggu Jadwal Konsultasi"
 * begitu diperbaiki, apapun keputusan bidang lain yang masih
 * menggantung - lihat _hitung_status_keseluruhan(). Akun 'tpa'
 * generik lama TIDAK berpartisipasi dalam persetujuan per bidang ini
 * (lihat kirim_catatan()) - cuma bisa melihat & menandai dokumen
 * per-item seperti biasa. Langkah setelah SEMUA bidang menyetujui
 * (penerbitan SK PBG, dst.) belum tercakup di sini.
 */
class Tpa_pengajuan_pbg extends CI_Controller {

	/** 'tpa' generik dipertahankan untuk akun lama; anggota baru memakai salah satu spesialisasi. */
	private $peran_tpa = array('tpa', 'tpa_arsitek', 'tpa_struktur', 'tpa_mep');

	/** 3 spesialisasi yang SUNGGUHAN berpartisipasi dalam persetujuan per bidang - tidak termasuk 'tpa' generik. */
	private $peran_bidang = array('tpa_arsitek', 'tpa_struktur', 'tpa_mep');

	/**
	 * Status permohonan yang TPA-nya masih "aktif" - bidang yang belum
	 * mengirim keputusan masih boleh menandai dokumen & mengirim
	 * keputusan, TERLEPAS dari bidang lain sudah memutuskan apa (per
	 * bidang independen - lihat catatan class di atas). 'verifikasi_dokumen'
	 * = belum ada satupun bidang minta perbaikan; 'perbaikan_dokumen'
	 * = ADA bidang lain yang minta perbaikan tapi bidang Anda sendiri
	 * belum memutuskan apa-apa, tetap boleh menilai independen.
	 * SENGAJA tidak termasuk 'perbaikan_dokumen_konsultasi' - begitu
	 * SATU bidang memilih itu, seluruh proses keluar ke jalur
	 * konsultasi (lihat Pengajuan_pbg::kirim_perbaikan()), menutup
	 * peninjauan per bidang lebih lanjut.
	 */
	private $status_tpa_aktif = array('verifikasi_dokumen', 'perbaikan_dokumen');

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

		$persetujuan = array();
		foreach ($this->db->select('pengajuan_pbg_persetujuan_tpa.*, peninjau.nama AS nama_peninjau')
			->from('pengajuan_pbg_persetujuan_tpa')
			->join('users AS peninjau', 'peninjau.id = pengajuan_pbg_persetujuan_tpa.ditinjau_oleh', 'left')
			->where('id_pengajuan', $id)->get()->result_array() as $p)
		{
			$persetujuan[$p['bidang']] = $p;
		}

		// Bidang akun yang login (NULL kalau peran 'tpa' generik - tidak
		// ikut sistem persetujuan per bidang, lihat kirim_catatan()).
		$bidang_saya = in_array($peran, $this->peran_bidang, TRUE) ? $peran : null;

		$data['row']              = $row;
		$data['dokumen_kelompok'] = $this->_kelompokkan_dokumen($dokumen_terunggah, $peran);
		$data['bisa_ditandai']    = in_array($row['status'], $this->status_tpa_aktif, TRUE);
		$data['persetujuan']      = $persetujuan;
		$data['bidang_saya']      = $bidang_saya;
		$data['bidang_boleh_menilai'] = ($bidang_saya !== null) && in_array($row['status'], $this->status_tpa_aktif, TRUE) && ! isset($persetujuan[$bidang_saya]);
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

	/**
	 * Status pengajuan_pbg.status DITURUNKAN dari isi
	 * pengajuan_pbg_persetujuan_tpa, bukan ditulis langsung oleh satu
	 * keputusan - dipanggil ulang tiap ada keputusan baru dari TPA
	 * (kirim_catatan()) atau perbaikan dikirim PU
	 * (Pengajuan_pbg::kirim_perbaikan(), yang menyalin fungsi ini).
	 * Prioritas: ada bidang minta konsultasi -> itu duluan (jalan
	 * keluar satu arah, mengalahkan segalanya); ada bidang minta
	 * perbaikan dokumen biasa -> itu; ketiga bidang sudah py baris
	 * keputusan (berarti pasti semuanya 'disetujui', karena 2
	 * kemungkinan lain sudah ditangani duluan) -> Disetujui TPA;
	 * selain itu masih menunggu sebagian bidang -> tetap Verifikasi
	 * Kelengkapan Dokumen.
	 */
	private function _hitung_status_keseluruhan($id)
	{
		$status_per_bidang = array();
		foreach ($this->db->select('bidang, status')->where('id_pengajuan', $id)->get('pengajuan_pbg_persetujuan_tpa')->result_array() as $b)
		{
			$status_per_bidang[$b['bidang']] = $b['status'];
		}

		if (in_array('perbaikan_dokumen_konsultasi', $status_per_bidang, TRUE))
		{
			return 'perbaikan_dokumen_konsultasi';
		}
		if (in_array('perbaikan_dokumen', $status_per_bidang, TRUE))
		{
			return 'perbaikan_dokumen';
		}
		if (count($status_per_bidang) === count($this->peran_bidang))
		{
			return 'disetujui_tpa';
		}
		return 'verifikasi_dokumen';
	}

	/**
	 * Ada dokumen berstatus "ditolak" di antara dokumen yang KELIHATAN
	 * oleh $peran (bidangnya sendiri + grup umum Data Umum/Dokumen
	 * Tambahan)? Dipakai buat menjaga supaya 1 bidang tidak bisa
	 * menyetujui selama masih ada tanda "tidak sesuai" yang belum
	 * dibatalkan pada dokumen yang mereka lihat sendiri.
	 */
	private function _ada_dokumen_ditolak_untuk($id, $peran)
	{
		$dokumen_terunggah = $this->db->where('id_pengajuan', $id)->get('pengajuan_pbg_dokumen')->result_array();
		foreach ($this->_kelompokkan_dokumen($dokumen_terunggah, $peran) as $grup)
		{
			foreach ($grup['berkas'] as $d)
			{
				if ($d['status'] === 'ditolak')
				{
					return TRUE;
				}
			}
		}
		return FALSE;
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

	/**
	 * Kirim keputusan peninjauan UNTUK BIDANG AKUN YANG LOGIN SAJA
	 * (disetujui / perbaikan_dokumen / perbaikan_dokumen_konsultasi) -
	 * disimpan sebagai 1 baris di pengajuan_pbg_persetujuan_tpa, lalu
	 * pengajuan_pbg.status dihitung ulang dari SEMUA baris lewat
	 * _hitung_status_keseluruhan(). Akun 'tpa' generik tidak
	 * berpartisipasi - lihat catatan class di atas.
	 */
	public function kirim_catatan($id = null)
	{
		$id  = (int) $id;
		$row = $this->db->where('id', $id)->where_in('status', $this->status_tpa_aktif)->get('pengajuan_pbg')->row_array();

		if ($row === NULL)
		{
			$this->session->set_flashdata('error', 'Permohonan tidak ditemukan atau statusnya sudah berubah - muat ulang halaman.');
			redirect('tpa-pengajuan-pbg');
			return;
		}

		$peran = (string) $this->session->userdata('role');
		if (! in_array($peran, $this->peran_bidang, TRUE))
		{
			$this->session->set_flashdata('error', 'Akun peran TPA generik tidak berpartisipasi dalam persetujuan per bidang - gunakan salah satu akun spesialis (Arsitek/Struktur/MEP) untuk mengirim keputusan.');
			redirect('tpa-pengajuan-pbg/lihat/' . $id);
			return;
		}

		$sudah_memutuskan = $this->db->where('id_pengajuan', $id)->where('bidang', $peran)->get('pengajuan_pbg_persetujuan_tpa')->row_array();
		if ($sudah_memutuskan !== NULL)
		{
			$this->session->set_flashdata('error', 'Bidang Anda sudah mengirim keputusan untuk permohonan ini - muat ulang halaman.');
			redirect('tpa-pengajuan-pbg/lihat/' . $id);
			return;
		}

		$status_baru  = (string) $this->input->post('status_baru');
		$catatan      = trim((string) $this->input->post('catatan_tpa'));
		// 'disetujui' = bidang ini menilai dokumen di bidangnya sudah
		// sesuai, tidak perlu perbaikan - satu-satunya keputusan di
		// sini yang catatannya OPSIONAL (2 lainnya tetap wajib diisi
		// alasannya).
		$status_valid  = array('disetujui', 'perbaikan_dokumen', 'perbaikan_dokumen_konsultasi');
		$butuh_catatan = ($status_baru !== 'disetujui');

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
		// Tidak boleh menyetujui kalau masih ada dokumen yang
		// KELIHATAN oleh bidang ini ditandai "tidak sesuai" dan belum
		// dibatalkan tandanya - dua-duanya sekaligus kontradiktif.
		if ($status_baru === 'disetujui' && $this->_ada_dokumen_ditolak_untuk($id, $peran))
		{
			$this->session->set_flashdata('error', 'Masih ada dokumen bertanda "tidak sesuai" di bidang Anda - batalkan dulu tandanya (atau pilih salah satu jenis Perbaikan Dokumen) sebelum menyetujui.');
			$this->session->set_flashdata('old', array('catatan_tpa' => $catatan, 'status_baru' => $status_baru));
			redirect('tpa-pengajuan-pbg/lihat/' . $id);
			return;
		}

		$this->db->insert('pengajuan_pbg_persetujuan_tpa', array(
			'id_pengajuan'  => $id,
			'bidang'        => $peran,
			'status'        => $status_baru,
			'catatan'       => ($catatan !== '') ? $catatan : NULL,
			'ditinjau_oleh' => (int) $this->session->userdata('user_id'),
			'ditinjau_pada' => date('Y-m-d H:i:s'),
		));

		$status_keseluruhan = $this->_hitung_status_keseluruhan($id);
		$this->db->where('id', $id)->update('pengajuan_pbg', array('status' => $status_keseluruhan));

		if ($status_baru === 'disetujui')
		{
			$pesan_sukses = ($status_keseluruhan === 'disetujui_tpa')
				? 'Persetujuan bidang Anda terkirim - ketiga bidang TPA sudah menyetujui, permohonan selesai ditinjau.'
				: 'Persetujuan bidang Anda terkirim - masih menunggu bidang TPA lain menyelesaikan peninjauan.';
		}
		else
		{
			$pesan_sukses = 'Catatan perbaikan berhasil dikirim - permohonan kembali ke PU/pemohon untuk ditindaklanjuti.';
		}
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
