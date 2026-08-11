<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pengajuan PBG/SLF milik pemohon yang sedang login. Khusus peran
 * pemohon - PU/TPA/Admin punya jalur/controller sendiri, belum ada
 * halaman "lihat semua pengajuan" untuk mereka di sini.
 */
class Pengajuan extends CI_Controller {

	private $jenis_layanan_valid  = array('pbg', 'slf');
	private $jenis_bangunan_valid = array('hunian', 'non_hunian');

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->_wajib_pemohon();
	}

	private function _wajib_pemohon()
	{
		if (! $this->session->userdata('logged_in'))
		{
			redirect('login');
			exit;
		}
		if ($this->session->userdata('role') !== 'pemohon')
		{
			show_error('Halaman ini khusus untuk pemohon.', 403, 'Akses Ditolak');
		}
	}

	public function index()
	{
		$this->db->where('id_user', (int) $this->session->userdata('user_id'));
		$this->db->order_by('created_at', 'DESC');
		$data['daftar_pengajuan'] = $this->db->get('pemohon')->result_array();
		$data['nama_pengguna'] = $this->session->userdata('nama');
		$data['sukses'] = $this->session->flashdata('sukses');
		$data['error']  = $this->session->flashdata('error');
		$this->load->view('pages/pengajuan_list', $data);
	}

	public function tambah()
	{
		$data['mode']  = 'tambah';
		$data['baris'] = array();
		$data['nama_pengguna'] = $this->session->userdata('nama');
		$data['old']   = $this->session->flashdata('old');
		$data['error'] = $this->session->flashdata('error');
		$this->load->view('pages/pengajuan_form', $data);
	}

	public function simpan()
	{
		list($isi, $pesan_error) = $this->_validasi_input();

		if ($pesan_error !== NULL)
		{
			$this->session->set_flashdata('error', $pesan_error);
			$this->session->set_flashdata('old', $this->input->post());
			redirect('pengajuan/tambah');
			return;
		}

		$isi['id_user'] = (int) $this->session->userdata('user_id');
		$this->db->insert('pemohon', $isi);

		$this->session->set_flashdata('sukses', 'Pengajuan berhasil disimpan.');
		redirect('pengajuan');
	}

	public function edit($id = null)
	{
		$baris = $this->_ambil_milik_sendiri($id);

		if ($baris === NULL)
		{
			$this->session->set_flashdata('error', 'Pengajuan tidak ditemukan.');
			redirect('pengajuan');
			return;
		}

		$data['mode']  = 'edit';
		$data['baris'] = $baris;
		// Kalau baru saja redirect balik karena validasi gagal, tampilkan
		// input yang sudah diketik ulang, bukan data lama dari DB.
		$old = $this->session->flashdata('old');
		if (! empty($old))
		{
			$data['baris'] = array_merge($baris, $old);
		}
		$data['nama_pengguna'] = $this->session->userdata('nama');
		$data['error'] = $this->session->flashdata('error');
		$this->load->view('pages/pengajuan_form', $data);
	}

	public function perbarui($id = null)
	{
		$baris = $this->_ambil_milik_sendiri($id);

		if ($baris === NULL)
		{
			$this->session->set_flashdata('error', 'Pengajuan tidak ditemukan.');
			redirect('pengajuan');
			return;
		}

		list($isi, $pesan_error) = $this->_validasi_input();

		if ($pesan_error !== NULL)
		{
			$this->session->set_flashdata('error', $pesan_error);
			$this->session->set_flashdata('old', $this->input->post());
			redirect('pengajuan/edit/' . (int) $baris['id']);
			return;
		}

		$this->db->where('id', (int) $baris['id']);
		$this->db->update('pemohon', $isi);

		$this->session->set_flashdata('sukses', 'Pengajuan berhasil diperbarui.');
		redirect('pengajuan');
	}

	public function hapus($id = null)
	{
		$baris = $this->_ambil_milik_sendiri($id);

		if ($baris === NULL)
		{
			$this->session->set_flashdata('error', 'Pengajuan tidak ditemukan.');
			redirect('pengajuan');
			return;
		}

		$this->db->where('id', (int) $baris['id']);
		$this->db->delete('pemohon');

		$this->session->set_flashdata('sukses', 'Pengajuan berhasil dihapus.');
		redirect('pengajuan');
	}

	/**
	 * Ambil satu baris pemohon, TAPI cuma kalau id_user-nya cocok
	 * dengan pengguna yang sedang login - supaya satu pemohon tidak
	 * bisa lihat/ubah/hapus pengajuan pemohon lain hanya dengan
	 * mengganti angka di URL.
	 */
	private function _ambil_milik_sendiri($id)
	{
		$id = (int) $id;
		if ($id <= 0)
		{
			return NULL;
		}

		$this->db->where('id', $id);
		$this->db->where('id_user', (int) $this->session->userdata('user_id'));
		return $this->db->get('pemohon')->row_array();
	}

	/**
	 * Validasi + susun data dari form tambah/edit. Mengembalikan
	 * array(data, null) kalau valid, atau array(null, pesan_error)
	 * kalau tidak. Status TIDAK termasuk di sini - pemohon tidak bisa
	 * mengubah status pengajuannya sendiri lewat form ini (itu jalur
	 * PU/TPA nanti), jadi status cuma diisi otomatis 'diajukan' saat
	 * pertama dibuat dan tidak pernah ditimpa balik oleh update().
	 */
	private function _validasi_input()
	{
		$jenis_layanan  = (string) $this->input->post('jenis_layanan');
		$jenis_bangunan = (string) $this->input->post('jenis_bangunan');
		$nama_pemohon   = trim((string) $this->input->post('nama_pemohon'));
		$nik_ktp        = trim((string) $this->input->post('nik_ktp'));
		$alamat         = trim((string) $this->input->post('alamat_bangunan'));
		$luas           = trim((string) $this->input->post('luas_bangunan'));
		$lantai         = trim((string) $this->input->post('jumlah_lantai'));

		if (! in_array($jenis_layanan, $this->jenis_layanan_valid, TRUE))
		{
			return array(NULL, 'Jenis layanan wajib dipilih (PBG atau SLF).');
		}
		if (! in_array($jenis_bangunan, $this->jenis_bangunan_valid, TRUE))
		{
			return array(NULL, 'Jenis bangunan wajib dipilih.');
		}
		if ($nama_pemohon === '' || $nik_ktp === '' || $alamat === '')
		{
			return array(NULL, 'Nama pemohon, NIK/KTP, dan alamat bangunan wajib diisi.');
		}
		if ($luas !== '' && ! is_numeric($luas))
		{
			return array(NULL, 'Luas bangunan harus berupa angka.');
		}
		if ($lantai !== '' && (! ctype_digit($lantai) || (int) $lantai < 1))
		{
			return array(NULL, 'Jumlah lantai harus berupa angka bulat positif.');
		}

		$isi = array(
			'jenis_layanan'        => $jenis_layanan,
			'jenis_bangunan'       => $jenis_bangunan,
			'nama_pemohon'         => $nama_pemohon,
			'nik_ktp'              => $nik_ktp,
			'nib'                  => $this->_kosong_jadi_null($this->input->post('nib')),
			'alamat_bangunan'      => $alamat,
			'no_kkpr_krk'          => $this->_kosong_jadi_null($this->input->post('no_kkpr_krk')),
			'bukti_tanah'          => $this->_kosong_jadi_null($this->input->post('bukti_tanah')),
			'no_sppt_nop'          => $this->_kosong_jadi_null($this->input->post('no_sppt_nop')),
			'nama_perencana'       => $this->_kosong_jadi_null($this->input->post('nama_perencana')),
			'no_lisensi_perencana' => $this->_kosong_jadi_null($this->input->post('no_lisensi_perencana')),
			'luas_bangunan'        => $luas === '' ? NULL : (float) $luas,
			'jumlah_lantai'        => $lantai === '' ? NULL : (int) $lantai,
		);

		return array($isi, NULL);
	}

	private function _kosong_jadi_null($nilai)
	{
		$nilai = trim((string) $nilai);
		return $nilai === '' ? NULL : $nilai;
	}
}
