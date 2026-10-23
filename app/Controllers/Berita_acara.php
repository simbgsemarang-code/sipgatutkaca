<?php

namespace App\Controllers;

class Berita_acara extends LegacyController
{
	private $bidang_role = array('tpa_arsitek' => 'arsitektur', 'tpa_struktur' => 'struktur', 'tpa_mep' => 'mep');
	private $bidang_label = array('arsitektur' => 'ARSITEKTUR', 'struktur' => 'STRUKTUR', 'mep' => 'MEP');
	private $bulan_romawi = array('I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII');

	public function __legacy_construct()
	{
		
		$this->load->library('session');
		$this->load->helper(array('url'));
		if (! $this->session->userdata('logged_in')) ci_redirect('login');
	}

	/** Ambil BA untuk satu review (konsultasi_pbg pemicu); terbitkan dulu kalau belum ada. */
	private function ba_pastikan($trigger)
	{
		$id = (int) $trigger['id'];
		$ba = $this->db->where('konsultasi_id', $id)->get('berita_acara_pbg')->row_array();
		if ($ba === NULL)
		{
			$snapshot = array();
			foreach (array_keys($this->bidang_label) as $bidang)
			{
				$r = $this->db->select('k.*, u.nama AS nama_tpa')->from('konsultasi_pbg k')
					->join('users u', 'u.id = k.tpa_user_id', 'left')
					->where('k.permohonan_id', $trigger['permohonan_id'])->where('k.bidang', $bidang)
					->where('k.reviewed_at IS NOT NULL')->where('k.reviewed_at <=', $trigger['reviewed_at'])
					->order_by('k.reviewed_at', 'DESC')->limit(1)->get()->row_array();
				$snapshot[$bidang] = $r ? array(
					'nama_tpa'    => $r['nama_tpa'],
					'rekomendasi' => $r['rekomendasi_tpa'],
					'status'      => $r['status'],
					'reviewed_at' => $r['reviewed_at'],
				) : null;
			}

			$tahun = date('Y', strtotime($trigger['reviewed_at']));
			$bulan = (int) date('n', strtotime($trigger['reviewed_at']));
			$urut  = $this->db->where('YEAR(created_at)', $tahun)->count_all_results('berita_acara_pbg') + 1;
			$nomor = '600.1.15.2/' . $urut . '/BA-PBG/' . $this->bulan_romawi[$bulan - 1] . '/' . $tahun;

			$this->db->insert('berita_acara_pbg', array(
				'konsultasi_id'  => $id,
				'permohonan_id'  => $trigger['permohonan_id'],
				'nomor'          => $nomor,
				'diterbitkan_at' => $trigger['reviewed_at'],
				'snapshot'       => json_encode($snapshot),
				'created_at'     => date('Y-m-d H:i:s'),
			));
			$ba = $this->db->where('konsultasi_id', $id)->get('berita_acara_pbg')->row_array();
		}
		return $ba;
	}

	private function kolom_tanggal_ada()
	{
		return $this->db->query("SHOW COLUMNS FROM `berita_acara_pbg` LIKE 'tanggal_ba'")->num_rows() > 0;
	}

	/**
	 * PU mengubah tanggal BA lewat modal "Unduh BA": tanggal yang sama dipakai untuk
	 * SELURUH BA permohonan ini (BA yang belum terbit ikut diterbitkan dulu), lalu BA
	 * yang diminta dibuka. Tanggal disimpan di berita_acara_pbg.tanggal_ba.
	 */
	public function ubah_tanggal($permohonan_id)
	{
		if ($this->input->method(TRUE) !== 'POST') show_404();
		$pid = (int) $permohonan_id;
		$row = $this->db->where('id', $pid)->get('permohonan_pbg')->row_array();
		if ($row === NULL) show_404();
		if ((string) $this->session->userdata('role') !== 'pu' || (int) $row['user_id'] !== (int) $this->session->userdata('user_id'))
		{
			show_error('Anda tidak berhak mengubah berita acara permohonan ini.', 403);
			return;
		}
		$tgl = trim((string) $this->input->post('tanggal_ba'));
		if (! preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $tgl, $mm) || ! checkdate((int) $mm[2], (int) $mm[3], (int) $mm[1])) show_error('Tanggal berita acara tidak valid.', 422);
		$pemicu = $this->db->where('id', (int) $this->input->post('konsultasi_id'))->where('permohonan_id', $pid)->get('konsultasi_pbg')->row_array();
		if ($pemicu === NULL || $pemicu['status'] === 'ditugaskan') show_404();
		if (! $this->kolom_tanggal_ada()) $this->db->query('ALTER TABLE `berita_acara_pbg` ADD COLUMN `tanggal_ba` DATE NULL AFTER `diterbitkan_at`');
		if (! $this->kolom_tanggal_ada()) show_error('Kolom tanggal_ba belum ada. Jalankan database/berita_acara_tanggal.sql lebih dulu.', 503);

		$semua = $this->db->where('permohonan_id', $pid)->where('status !=', 'ditugaskan')->where('reviewed_at IS NOT NULL', NULL, FALSE)
			->order_by('reviewed_at', 'ASC')->order_by('id', 'ASC')->get('konsultasi_pbg')->result_array();
		$this->db->trans_start();
		foreach ($semua as $k) $this->ba_pastikan($k);
		$this->db->where('permohonan_id', $pid)->update('berita_acara_pbg', array('tanggal_ba' => $tgl));
		$this->db->trans_complete();
		ci_redirect('pengajuan-pbg/berita-acara/' . (int) $pemicu['id']);
	}
	/**
	 * BA terbit setiap SATU baris konsultasi_pbg selesai direview
	 * (statusnya bukan lagi 'ditugaskan') - bukan menunggu ketiga
	 * bidang selesai. $id = konsultasi_pbg.id pemicunya.
	 */
	public function pbg($id)
	{
		$id      = (int) $id;
		$trigger = $this->db->where('id', $id)->get('konsultasi_pbg')->row_array();
		if ($trigger === NULL || $trigger['status'] === 'ditugaskan') show_404();

		$row = $this->db->where('id', $trigger['permohonan_id'])->get('permohonan_pbg')->row_array();
		if ($row === NULL) show_404();

		$role = (string) $this->session->userdata('role');
		if ($role === 'pu')
		{
			if ((int) $row['user_id'] !== (int) $this->session->userdata('user_id'))
			{
				show_error('Anda tidak berhak mengakses berita acara permohonan ini.', 403);
				return;
			}
		}
		elseif (isset($this->bidang_role[$role]))
		{
			$ikut = $this->db->where('permohonan_id', $trigger['permohonan_id'])
				->where('tpa_user_id', (int) $this->session->userdata('user_id'))->count_all_results('konsultasi_pbg');
			if ($ikut === 0)
			{
				show_error('Anda tidak pernah ditugaskan pada permohonan ini.', 403);
				return;
			}
		}
		else
		{
			show_error('Halaman ini khusus PU dan TPA.', 403);
			return;
		}

		$ba = $this->ba_pastikan($trigger);

		$data = array(
			'row'          => $row,
			'putaran'      => $trigger['putaran'],
			'bidang_pemicu'=> $trigger['bidang'],
			'nomor'        => $ba['nomor'],
			'tanggal_ba'   => ! empty($ba['tanggal_ba']) ? $ba['tanggal_ba'] : $ba['diterbitkan_at'],
			'snapshot'     => json_decode($ba['snapshot'], true),
			'riwayat'      => $this->riwayat_review($trigger['permohonan_id'], $ba['diterbitkan_at']),
			'bidang_label' => $this->bidang_label,
		);
		$this->load->view('pbg_pu/berita_acara', $data);
	}

	/**
	 * Seluruh review TPA (semua putaran, semua TPA) per bidang yang sudah masuk sampai
	 * saat BA terbit, urut waktu. Review tidak bisa diubah setelah dikirim, jadi hasilnya
	 * tetap sama kapan pun BA dibuka (BA lama ikut memuat riwayat lengkap).
	 */
	private function riwayat_review($permohonan_id, $sampai)
	{
		$hasil = array('arsitektur' => array(), 'struktur' => array(), 'mep' => array());
		$rows = $this->db->select('k.bidang,k.putaran,k.status,k.rekomendasi_tpa,k.reviewed_at,u.nama AS nama_tpa')->from('konsultasi_pbg k')
			->join('users u', 'u.id = k.tpa_user_id', 'left')
			->where('k.permohonan_id', (int) $permohonan_id)->where('k.reviewed_at IS NOT NULL')->where('k.reviewed_at <=', $sampai)
			->order_by('k.reviewed_at', 'ASC')->order_by('k.id', 'ASC')->get()->result_array();
		foreach ($rows as $r) { if (isset($hasil[$r['bidang']])) $hasil[$r['bidang']][] = $r; }
		return $hasil;
	}

	/**
	 * Berita acara DISTRIBUSI tugas ke TPA (satu per putaran). Daftar TPA dan
	 * saran dibaca langsung dari konsultasi_pbg, jadi ikut berubah bila PU
	 * mengganti TPA atau TPA mengirim hasil review. $id = berita_acara_distribusi.id.
	 */
	public function distribusi($id)
	{
		if (! $this->db->table_exists('berita_acara_distribusi')) show_404();
		$ba  = $this->db->where('id', (int) $id)->get('berita_acara_distribusi')->row_array();
		if ($ba === NULL) show_404();
		$row = $this->db->where('id', $ba['permohonan_id'])->get('permohonan_pbg')->row_array();
		if ($row === NULL) show_404();

		$role = (string) $this->session->userdata('role');
		if ($role === 'pu')
		{
			if ((int) $row['user_id'] !== (int) $this->session->userdata('user_id'))
			{
				show_error('Anda tidak berhak mengakses berita acara permohonan ini.', 403);
				return;
			}
		}
		elseif (isset($this->bidang_role[$role]))
		{
			$ikut = $this->db->where('permohonan_id', $ba['permohonan_id'])
				->where('tpa_user_id', (int) $this->session->userdata('user_id'))->count_all_results('konsultasi_pbg');
			if ($ikut === 0)
			{
				show_error('Anda tidak pernah ditugaskan pada permohonan ini.', 403);
				return;
			}
		}
		else
		{
			show_error('Halaman ini khusus PU dan TPA.', 403);
			return;
		}

		$anggota = array('arsitektur' => array(), 'struktur' => array(), 'mep' => array());
		foreach ($this->db->select('k.*, u.nama AS nama_tpa')->from('konsultasi_pbg k')
			->join('users u', 'u.id = k.tpa_user_id', 'left')
			->where('k.permohonan_id', $ba['permohonan_id'])->where('k.putaran', $ba['putaran'])
			->order_by('k.id', 'ASC')->get()->result_array() as $k)
		{
			$anggota[$k['bidang']][] = $k;
		}

		$this->load->view('pbg_pu/berita_acara_distribusi', array(
			'row'          => $row,
			'putaran'      => (int) $ba['putaran'],
			'nomor'        => $ba['nomor'],
			'tanggal_ba'   => $ba['tanggal_ba'],
			'anggota'      => $anggota,
			'bidang_label' => $this->bidang_label,
		));
	}
}
