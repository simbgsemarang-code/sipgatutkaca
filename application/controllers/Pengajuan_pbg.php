<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pengajuan PBG - loket PU menginput permohonan Persetujuan Bangunan
 * Gedung atas nama warga yang datang langsung (bukan pemohon login
 * sendiri). Mengikuti alur "PENGISIAN TYPEFORM" + "FORMULIR DOKUMEN
 * TANAH BANGUNAN" + "UNGGAH DOKUMEN TEKNIS" pada Panduan Permohonan
 * PBG (Kementerian PU / aplikasi SIMBG), sampai status permohonan
 * terkirim untuk diverifikasi. Alur lanjutan (perbaikan dokumen,
 * konsultasi, ubah data tanah) belum tercakup - lihat catatan di
 * database/pengajuan_pbg.sql.
 */
class Pengajuan_pbg extends CI_Controller {

	/**
	 * Kategori fungsi bangunan (checkbox multi-pilih) beserta daftar
	 * sub-fungsi resminya, persis seperti pada typeform SIMBG. Semua
	 * sub-fungsi diperlakukan sebagai checkbox multi-pilih di sini demi
	 * kesederhanaan formulir - pada aplikasi aslinya sebagian tampil
	 * sebagai pilihan tunggal.
	 */
	private $peta_fungsi = array(
		'hunian' => array(
			'label' => 'Fungsi Hunian',
			'sub'   => array(
				'Rumah Tinggal Deret', 'Rumah Tinggal Deret (MBR)',
				'Rumah Tinggal Tunggal', 'Rumah Tinggal Tunggal (MBR)',
				'Rumah Susun', 'Rumah Susun (MBR)',
			),
		),
		'usaha' => array(
			'label' => 'Fungsi Usaha',
			'sub'   => array(
				'Bangunan Gedung Perkantoran', 'Bangunan Gedung Perdagangan',
				'Bangunan Gedung Perindustrian', 'Bangunan Gedung Perhotelan',
				'Bangunan Wisata dan Rekreasi', 'Bangunan Gedung Terminal',
				'Bangunan Gedung Tempat Penyimpanan', 'Bangunan Gedung Peternakan',
				'Bangunan Gedung Laboratorium (bukan fasilitas kesehatan atau layanan pendidikan)',
			),
		),
		'keagamaan' => array(
			'label' => 'Fungsi Keagamaan',
			'sub'   => array(
				'Bangunan Masjid termasuk Musala', 'Bangunan Gereja termasuk Kapel',
				'Bangunan Pura', 'Bangunan Vihara', 'Bangunan Kelenteng',
				'Bangunan peribadatan agama/kepercayaan lainnya yang diakui oleh negara',
			),
		),
		'umkm' => array(
			'label' => 'Fungsi Usaha (UMKM)',
			'sub'   => array(
				'Bangunan Gedung Perkantoran', 'Bangunan Gedung Perdagangan',
				'Bangunan Gedung Perindustrian', 'Bangunan Gedung Perhotelan',
				'Bangunan Wisata dan Rekreasi', 'Bangunan Gedung Terminal',
				'Bangunan Gedung Tempat Penyimpanan',
			),
		),
		'prasarana' => array(
			'label' => 'Bangunan Prasarana',
			'sub'   => array(
				'Konstruksi Pembatas/Penahan/Pengaman', 'Konstruksi Penanda Masuk Lokasi',
				'Konstruksi Perkerasan Aspal, Beton', 'Konstruksi Perkerasan Grassblock',
				'Konstruksi Penghubung (Jembatan antar Gedung)',
				'Konstruksi Penghubung (Jembatan Penyeberangan Orang/Barang)',
				'Konstruksi Penghubung (Jembatan Bawah Tanah/Underpass)',
				'Konstruksi Kolam/Reservoir Bawah Tanah', 'Konstruksi Septic Tank, Sumur Resapan',
				'Konstruksi Menara', 'Konstruksi Menara Air', 'Konstruksi Monumen',
				'Konstruksi Instalasi/Gardu Listrik', 'Konstruksi Reklame/Papan Nama',
				'Fondasi Mesin (Diluar Bangunan)', 'Konstruksi Menara Televisi',
				'Konstruksi Antena Radio',
			),
		),
		'sosial_budaya' => array(
			'label' => 'Sub Fungsi Sosial Budaya',
			'sub'   => array(
				'Bangunan Gedung Pendidikan (Sekolah Dasar, Sekolah Menengah Pertama, Sekolah Menengah Atas, Perguruan Tinggi, dan Sekolah Terpadu)',
				'Bangunan Gedung Kebudayaan (Museum, Gedung Pameran dan Gedung Kesenian)',
				'Bangunan Gedung Kesehatan (Puskesmas, Klinik Bersalin, Tempat Praktik Dokter Bersama, Rumah Sakit, dan Laboratorium)',
				'Bangunan Gedung Pelayanan Umum Lainnya',
			),
		),
	);

	/** Checklist dokumen teknis, dikelompokkan sesuai tampilan SIMBG. */
	private $peta_dokumen = array(
		'Data Umum' => array(
			'ktp'           => 'Data Identitas Pemilik Bangunan (KTP/KITAS)',
			'kkpr'          => 'Data Intensitas Bangunan (KKPR/KRK)',
			'penyedia_jasa' => 'Data Penyedia Jasa Perencana',
		),
		'Data Teknis Arsitektur' => array(
			'situasi'  => 'Gambar Situasi',
			'tapak'    => 'Gambar Rencana Tapak Bangunan',
			'denah'    => 'Gambar Rencana Denah Bangunan',
			'potongan' => 'Gambar Rencana Potongan Bangunan',
			'tampak'   => 'Gambar Rencana Tampak Bangunan',
		),
		'Dokumen Tambahan' => array(
			'tambahan' => 'Dokumen pendukung lain (opsional)',
		),
	);

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

	private $upload_tipe_izin = 'jpg|jpeg|png|pdf';
	private $upload_maks_kb   = 5120;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->_wajib_pu();
	}

	private function _wajib_pu()
	{
		if (! $this->session->userdata('logged_in'))
		{
			redirect('login');
			exit;
		}
		if ($this->session->userdata('role') !== 'pu')
		{
			show_error('Halaman ini khusus untuk PU.', 403, 'Akses Ditolak');
		}
	}

	/** Daftar Permohonan - semua permohonan yang pernah diinput staf PU (loket bersama, tidak disaring per staf). */
	public function index()
	{
		$semua = $this->db->order_by('created_at', 'DESC')->get('pengajuan_pbg')->result_array();
		$jumlah_draf = 0;
		foreach ($semua as $p)
		{
			if ($p['status'] === 'draf')
			{
				$jumlah_draf++;
			}
		}

		$data['daftar']          = $semua;
		$data['total']           = count($semua);
		$data['jumlah_draf']     = $jumlah_draf;
		$data['jumlah_terkirim'] = count($semua) - $jumlah_draf;
		$data['sukses']          = $this->session->flashdata('sukses');
		$data['error']           = $this->session->flashdata('error');
		$data['nama_pengguna']   = $this->session->userdata('nama');

		$this->load->view('pages/pengajuan_pbg_list', $data);
	}

	/** Form tambah permohonan baru, atau lanjutkan draf lama kalau $id diisi. */
	public function tambah($id = null)
	{
		$id  = (int) $id;
		$row = null;

		if ($id > 0)
		{
			$row = $this->_ambil_draf($id);
			if ($row === NULL)
			{
				show_404();
				return;
			}
		}

		$data['row']              = $row;
		$data['peta_fungsi']      = $this->peta_fungsi;
		$data['peta_dokumen']     = $this->peta_dokumen;
		$data['opsi_kepemilikan'] = $this->opsi_kepemilikan;
		$data['opsi_kondisi']     = $this->opsi_kondisi;
		$data['dokumen_ada']      = $id > 0 ? $this->db->where('id_pengajuan', $id)->get('pengajuan_pbg_dokumen')->result_array() : array();
		$data['error']            = $this->session->flashdata('error');
		$data['old']              = $this->session->flashdata('old');
		$data['nama_pengguna']    = $this->session->userdata('nama');

		$this->load->view('pages/pengajuan_pbg_form', $data);
	}

	/** Detail permohonan (draf maupun yang sudah terkirim). */
	public function lihat($id = null)
	{
		$id  = (int) $id;
		$row = $id > 0 ? $this->db->where('id', $id)->get('pengajuan_pbg')->row_array() : NULL;

		if ($row === NULL)
		{
			show_404();
			return;
		}

		$data['row']              = $row;
		$data['dokumen']          = $this->db->where('id_pengajuan', $id)->get('pengajuan_pbg_dokumen')->result_array();
		$data['opsi_kepemilikan'] = $this->opsi_kepemilikan;
		$data['opsi_kondisi']     = $this->opsi_kondisi;
		$data['nama_pengguna']    = $this->session->userdata('nama');

		$this->load->view('pages/pengajuan_pbg_detail', $data);
	}

	/**
	 * Simpan (POST). Dipakai baik untuk permohonan baru maupun
	 * melanjutkan draf ($id > 0). Tombol "aksi" membedakan disimpan
	 * sebagai draf saja, atau langsung dikirim (perlu field inti
	 * lengkap dulu - lihat $wajib_kirim).
	 */
	public function simpan($id = null)
	{
		$id   = (int) $id;
		$aksi = (string) $this->input->post('aksi');
		$row  = null;

		if ($id > 0)
		{
			$row = $this->_ambil_draf($id);
			if ($row === NULL)
			{
				show_404();
				return;
			}
		}

		$data = $this->_kumpulkan_input($row);

		if ($data['nama_pemohon'] === '')
		{
			$this->session->set_flashdata('error', 'Nama pemohon wajib diisi.');
			$this->session->set_flashdata('old', $this->input->post());
			redirect('pengajuan-pbg/tambah' . ($id > 0 ? '/' . $id : ''));
			return;
		}

		if ($aksi === 'kirim')
		{
			$wajib_kirim = array(
				'lokasi_alamat'        => 'Alamat lokasi bangunan',
				'kepemilikan_bangunan' => 'Kepemilikan bangunan gedung',
				'kondisi_bangunan'     => 'Kondisi bangunan',
				'punya_basemen'        => 'Keterangan basemen',
				'bangunan_nama'        => 'Nama bangunan',
			);
			$kosong = array();
			foreach ($wajib_kirim as $kolom => $label)
			{
				if ($data[$kolom] === NULL || $data[$kolom] === '')
				{
					$kosong[] = $label;
				}
			}
			if ($data['fungsi_bangunan'] === NULL)
			{
				$kosong[] = 'Fungsi bangunan';
			}

			if (! empty($kosong))
			{
				$this->session->set_flashdata('error', 'Lengkapi dulu sebelum mengirim: ' . implode(', ', $kosong) . '.');
				$this->session->set_flashdata('old', $this->input->post());
				redirect('pengajuan-pbg/tambah' . ($id > 0 ? '/' . $id : ''));
				return;
			}
		}

		// Simpan/perbarui data teks dulu supaya id tersedia sebelum
		// berkas diunggah ke folder application/uploads/pengajuan_pbg/{id}/.
		if ($id > 0)
		{
			$this->db->where('id', $id)->update('pengajuan_pbg', $data);
		}
		else
		{
			$data['dibuat_oleh'] = (int) $this->session->userdata('user_id');
			$this->db->insert('pengajuan_pbg', $data);
			$id = (int) $this->db->insert_id();
		}

		$this->_proses_unggah_tunggal($id, 'prototipe_peta');
		$this->_proses_unggah_tunggal($id, 'bangunan_peta');
		$this->_proses_unggah_tunggal($id, 'tanah_lampiran');
		$this->_proses_unggah_dokumen($id);

		if ($aksi === 'kirim')
		{
			$no_reg = 'PBG-' . date('Ymd') . '-' . str_pad((string) $id, 4, '0', STR_PAD_LEFT);
			$this->db->where('id', $id)->update('pengajuan_pbg', array(
				'status'        => 'verifikasi_dokumen',
				'no_registrasi' => $no_reg,
			));
			$this->session->set_flashdata('sukses', 'Permohonan berhasil dikirim. No. Registrasi ' . $no_reg . ', status: Verifikasi Kelengkapan Dokumen.');
			redirect('pengajuan-pbg');
			return;
		}

		$this->session->set_flashdata('sukses', 'Permohonan disimpan sebagai draf. Anda bisa melanjutkannya kapan saja lewat tombol Lanjutkan.');
		redirect('pengajuan-pbg/tambah/' . $id);
	}

	/** Hapus draf (permohonan yang sudah terkirim tidak bisa dihapus dari sini). */
	public function hapus($id = null)
	{
		$id  = (int) $id;
		$row = $this->_ambil_draf($id);

		if ($row === NULL)
		{
			$this->session->set_flashdata('error', 'Permohonan tidak ditemukan atau bukan berstatus draf.');
			redirect('pengajuan-pbg');
			return;
		}

		$dir = APPPATH . 'uploads/pengajuan_pbg/' . $id;
		if (is_dir($dir))
		{
			foreach (glob($dir . '/*') as $berkas)
			{
				if (is_file($berkas))
				{
					@unlink($berkas);
				}
			}
			@rmdir($dir);
		}

		// Baris pengajuan_pbg_dokumen ikut terhapus otomatis (ON DELETE CASCADE).
		$this->db->where('id', $id)->delete('pengajuan_pbg');
		$this->session->set_flashdata('sukses', 'Draf permohonan berhasil dihapus.');
		redirect('pengajuan-pbg');
	}

	/**
	 * Sajikan satu berkas terunggah dengan aman - path SELALU
	 * ditentukan lewat lookup database di sini (tidak pernah langsung
	 * dari input pengguna), dan seluruh action di controller ini sudah
	 * dijaga _wajib_pu() lewat constructor.
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
		// read_file() dari CI3 MENGEMBALIKAN isi berkas sebagai string,
		// bukan langsung mencetaknya - wajib di-echo di sini.
		echo read_file($path);
	}

	private function _ambil_draf($id)
	{
		if ($id <= 0)
		{
			return NULL;
		}
		return $this->db->where('id', $id)->where('status', 'draf')->get('pengajuan_pbg')->row_array();
	}

	/**
	 * Kumpulkan seluruh field form jadi array siap insert/update.
	 * $row (kalau sedang mengedit draf) dipakai sebagai fallback nilai
	 * fungsi_bangunan kalau checklist fungsi tidak disentuh ulang.
	 */
	private function _kumpulkan_input($row = null)
	{
		$p = function ($nama) {
			$v = trim((string) $this->input->post($nama));
			return $v !== '' ? $v : NULL;
		};
		$pi = function ($nama) {
			$v = trim((string) $this->input->post($nama));
			return ($v !== '' && is_numeric($v)) ? (int) $v : NULL;
		};

		return array(
			'nama_pemohon'   => trim((string) $this->input->post('nama_pemohon')),
			'nik_pemohon'    => $p('nik_pemohon'),
			'kontak_pemohon' => $p('kontak_pemohon'),

			'intensitas_ada'        => $p('intensitas_ada'),
			'intensitas_no_dokumen' => $p('intensitas_no_dokumen'),
			'intensitas_gsb'        => $p('intensitas_gsb'),
			'intensitas_kdb'        => $p('intensitas_kdb'),
			'intensitas_klb'        => $p('intensitas_klb'),
			'intensitas_kdh'        => $p('intensitas_kdh'),

			'lokasi_provinsi'  => $p('lokasi_provinsi'),
			'lokasi_kabupaten' => $p('lokasi_kabupaten'),
			'lokasi_kecamatan' => $p('lokasi_kecamatan'),
			'lokasi_kelurahan' => $p('lokasi_kelurahan'),
			'lokasi_alamat'    => $p('lokasi_alamat'),

			'jumlah_bukti_tanah'   => $pi('jumlah_bukti_tanah'),
			'kepemilikan_bangunan' => $p('kepemilikan_bangunan'),
			'kondisi_bangunan'     => $p('kondisi_bangunan'),

			'pakai_prototipe'       => $p('pakai_prototipe'),
			'prototipe_jumlah_unit' => $pi('prototipe_jumlah_unit'),
			'prototipe_latitude'    => $p('prototipe_latitude'),
			'prototipe_longitude'   => $p('prototipe_longitude'),
			'prototipe_jenis'       => $p('prototipe_jenis'),
			'masa_pemanfaatan'      => $p('masa_pemanfaatan'),

			'fungsi_bangunan' => $this->_format_fungsi_bangunan($row !== null ? $row['fungsi_bangunan'] : null),

			'punya_basemen'                 => $p('punya_basemen'),
			'bangunan_nama'                 => $p('bangunan_nama'),
			'bangunan_luas_per_unit'        => $p('bangunan_luas_per_unit'),
			'bangunan_tinggi'               => $p('bangunan_tinggi'),
			'bangunan_jumlah_lantai'        => $pi('bangunan_jumlah_lantai'),
			'bangunan_luas_basemen'         => $p('bangunan_luas_basemen'),
			'bangunan_jumlah_lapis_basemen' => $pi('bangunan_jumlah_lapis_basemen'),
			'bangunan_jumlah_unit'          => $pi('bangunan_jumlah_unit'),
			'bangunan_estimasi_penghuni'    => $pi('bangunan_estimasi_penghuni'),
			'bangunan_latitude'             => $p('bangunan_latitude'),
			'bangunan_longitude'            => $p('bangunan_longitude'),

			'tanah_jenis_dokumen'   => $p('tanah_jenis_dokumen'),
			'tanah_nomor_dokumen'   => $p('tanah_nomor_dokumen'),
			'tanah_tanggal_terbit'  => $p('tanah_tanggal_terbit'),
			'tanah_luas'            => $p('tanah_luas'),
			'tanah_hak_kepemilikan' => $p('tanah_hak_kepemilikan'),
			'tanah_nama_pemilik'    => $p('tanah_nama_pemilik'),
			'tanah_provinsi'        => $p('tanah_provinsi'),
			'tanah_kabupaten'       => $p('tanah_kabupaten'),
			'tanah_kecamatan'       => $p('tanah_kecamatan'),
			'tanah_kelurahan'       => $p('tanah_kelurahan'),
			'tanah_alamat'          => $p('tanah_alamat'),
			'tanah_pemilik_sama'    => $p('tanah_pemilik_sama'),
			'tanah_nomor_izin'      => $p('tanah_nomor_izin'),
			'tanah_tanggal_izin'    => $p('tanah_tanggal_izin'),
		);
	}

	private function _format_fungsi_bangunan($existing = null)
	{
		$dipilih = $this->input->post('fungsi');
		if (empty($dipilih) || ! is_array($dipilih))
		{
			// Checklist fungsi tidak disentuh ulang (mis. sedang
			// mengedit draf dan cuma mengubah bagian lain) - pertahankan
			// nilai lama supaya tidak diam-diam terhapus.
			return $existing !== '' ? $existing : NULL;
		}

		$baris = array();
		foreach ($dipilih as $kunci)
		{
			if (! isset($this->peta_fungsi[$kunci]))
			{
				continue;
			}
			$sub      = $this->input->post('sub_fungsi_' . $kunci);
			$sub_teks = (! empty($sub) && is_array($sub)) ? implode(', ', $sub) : '-';
			$baris[]  = $this->peta_fungsi[$kunci]['label'] . ': ' . $sub_teks;
		}

		return ! empty($baris) ? implode("\n", $baris) : NULL;
	}

	private function _direktori_unggah($id)
	{
		$dir = APPPATH . 'uploads/pengajuan_pbg/' . $id . '/';
		if (! is_dir($dir))
		{
			mkdir($dir, 0755, TRUE);
		}
		return $dir;
	}

	private function _konfigurasi_unggah($id)
	{
		return array(
			'upload_path'   => $this->_direktori_unggah($id),
			'allowed_types' => $this->upload_tipe_izin,
			'max_size'      => $this->upload_maks_kb,
			'encrypt_name'  => TRUE,
		);
	}

	private function _proses_unggah_tunggal($id, $kolom)
	{
		if (empty($_FILES[$kolom]['name']))
		{
			return;
		}

		$this->load->library('upload');
		$this->upload->initialize($this->_konfigurasi_unggah($id));

		if ($this->upload->do_upload($kolom))
		{
			$hasil = $this->upload->data();
			$this->db->where('id', $id)->update('pengajuan_pbg', array(
				$kolom => $id . '/' . $hasil['file_name'],
			));
		}
		else
		{
			// Gagal unggah (mis. tipe/ukuran file tidak sesuai) tidak
			// menggagalkan penyimpanan field lainnya - cukup diberi
			// tahu lewat flashdata supaya berkas itu diunggah ulang.
			$pesan_lama = $this->session->flashdata('error');
			$pesan_baru = 'Berkas untuk "' . $kolom . '" gagal diunggah: ' . strip_tags($this->upload->display_errors('', ''));
			$this->session->set_flashdata('error', $pesan_lama !== '' ? $pesan_lama . ' ' . $pesan_baru : $pesan_baru);
		}
	}

	private function _proses_unggah_dokumen($id)
	{
		if (empty($_FILES['dokumen']['name']) || ! is_array($_FILES['dokumen']['name']))
		{
			return;
		}

		$daftar_jenis = array();
		foreach ($this->peta_dokumen as $grup)
		{
			foreach ($grup as $slug => $label)
			{
				$daftar_jenis[$slug] = $label;
			}
		}

		foreach ($_FILES['dokumen']['name'] as $slug => $nama_asli)
		{
			if ($nama_asli === '' || ! isset($daftar_jenis[$slug]))
			{
				continue;
			}

			$_FILES['berkas_dokumen_sementara'] = array(
				'name'     => $_FILES['dokumen']['name'][$slug],
				'type'     => $_FILES['dokumen']['type'][$slug],
				'tmp_name' => $_FILES['dokumen']['tmp_name'][$slug],
				'error'    => $_FILES['dokumen']['error'][$slug],
				'size'     => $_FILES['dokumen']['size'][$slug],
			);

			$this->load->library('upload');
			$this->upload->initialize($this->_konfigurasi_unggah($id));

			if (! $this->upload->do_upload('berkas_dokumen_sementara'))
			{
				$pesan_lama = $this->session->flashdata('error');
				$pesan_baru = 'Dokumen "' . $daftar_jenis[$slug] . '" gagal diunggah: ' . strip_tags($this->upload->display_errors('', ''));
				$this->session->set_flashdata('error', $pesan_lama !== '' ? $pesan_lama . ' ' . $pesan_baru : $pesan_baru);
				continue;
			}

			$hasil = $this->upload->data();

			// Timpa dokumen lama dengan jenis yang sama supaya unggah
			// ulang menggantikan, bukan menumpuk baris duplikat.
			$lama = $this->db->where('id_pengajuan', $id)->where('jenis_dokumen', $daftar_jenis[$slug])->get('pengajuan_pbg_dokumen')->row_array();
			if ($lama !== NULL)
			{
				@unlink(APPPATH . 'uploads/pengajuan_pbg/' . $lama['path_file']);
				$this->db->where('id', $lama['id'])->delete('pengajuan_pbg_dokumen');
			}

			$this->db->insert('pengajuan_pbg_dokumen', array(
				'id_pengajuan'   => $id,
				'jenis_dokumen'  => $daftar_jenis[$slug],
				'nama_file_asli' => $hasil['orig_name'],
				'path_file'      => $id . '/' . $hasil['file_name'],
			));
		}
	}
}
