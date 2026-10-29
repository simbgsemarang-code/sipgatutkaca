<?php

namespace App\Controllers;

class Analisa_kerusakan extends LegacyController {

	private $kondisi_label = array('1' => 'Baik', '2' => 'Rusak Ringan', '3' => 'Rusak Sedang', '4' => 'Rusak Berat');

	public function index()
	{
		$this->load->view('pages/analisa_kerusakan');
	}

	/**
	 * Unduh hasil analisis kerusakan bangunan (.xlsx): data bangunan + rekapitulasi per kondisi,
	 * kecamatan, fungsi, dan OPD. Filter sama dengan halaman (q, kec, fungsi, kondisi);
	 * tanpa parameter = seluruh bangunan. Data yang sama sudah publik lewat /gis/bangunan.
	 */
	public function ekspor()
	{
		$f = array(
			'q'       => mb_substr(trim((string) $this->input->get('q')), 0, 100),
			'kec'     => mb_substr(trim((string) $this->input->get('kec')), 0, 100),
			'fungsi'  => mb_substr(trim((string) $this->input->get('fungsi')), 0, 100),
			'kondisi' => (string) $this->input->get('kondisi'),
		);
		if (! isset($this->kondisi_label[$f['kondisi']])) $f['kondisi'] = '';

		$baris_db = array();
		if ($this->db->table_exists('bangunan_gis'))
		{
			if ($f['q'] !== '')
			{
				$this->db->group_start();
				$this->db->like('nama_bangunan', $f['q']);
				$this->db->or_like('opd', $f['q']);
				$this->db->or_like('institusi', $f['q']);
				$this->db->or_like('unit', $f['q']);
				$this->db->or_like('alamat', $f['q']);
				$this->db->group_end();
			}
			if ($f['kec'] !== '')     $this->db->where('kecamatan', $f['kec']);
			if ($f['fungsi'] !== '')  $this->db->where('fungsi', $f['fungsi']);
			if ($f['kondisi'] !== '') $this->db->where('kondisi', $f['kondisi']);
			$baris_db = $this->db->order_by('id', 'ASC')->get('bangunan_gis')->result_array();
		}

		$data = array();
		$no   = 0;
		foreach ($baris_db as $r)
		{
			$no++;
			$k = (string) $r['kondisi'];
			$data[] = array(
				$no,
				(int) $r['id'],
				$r['opd'],
				$r['unit'],
				$r['institusi'],
				$r['nama_bangunan'],
				$r['fungsi'],
				($r['jumlah_lantai'] === NULL || $r['jumlah_lantai'] === '') ? '' : (int) $r['jumlah_lantai'],
				$r['kecamatan'],
				$r['kelurahan'],
				$r['alamat'],
				isset($this->kondisi_label[$k]) ? $this->kondisi_label[$k] : 'Tidak diketahui',
				isset($this->kondisi_label[$k]) ? (int) $k : '',
				number_format((float) $r['latitude'], 6, '.', ''),
				number_format((float) $r['longitude'], 6, '.', ''),
				! empty($r['foto']) ? 'Ada' : 'Belum ada',
				$r['updated_at'] ? $r['updated_at'] : $r['created_at'],
			);
		}

		$kolom_data = array(
			array('judul' => 'No.', 'lebar' => 6, 'tipe' => 'bulat'),
			array('judul' => 'ID Bangunan', 'lebar' => 11, 'tipe' => 'bulat'),
			array('judul' => 'OPD', 'lebar' => 34),
			array('judul' => 'Unit', 'lebar' => 26),
			array('judul' => 'Institusi', 'lebar' => 26),
			array('judul' => 'Nama Bangunan', 'lebar' => 36),
			array('judul' => 'Fungsi', 'lebar' => 18),
			array('judul' => 'Jumlah Lantai', 'lebar' => 10, 'tipe' => 'bulat'),
			array('judul' => 'Kecamatan', 'lebar' => 18),
			array('judul' => 'Kelurahan / Desa', 'lebar' => 20),
			array('judul' => 'Alamat', 'lebar' => 44),
			array('judul' => 'Kondisi', 'lebar' => 16),
			array('judul' => 'Kode Kondisi (1-4)', 'lebar' => 12, 'tipe' => 'bulat'),
			array('judul' => 'Latitude', 'lebar' => 13),
			array('judul' => 'Longitude', 'lebar' => 13),
			array('judul' => 'Foto Lapangan', 'lebar' => 13),
			array('judul' => 'Terakhir Diperbarui', 'lebar' => 18, 'tipe' => 'tanggal'),
		);

		$total = count($baris_db);

		// rekap per kondisi
		$hit = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '' => 0);
		foreach ($baris_db as $r)
		{
			$k = (string) $r['kondisi'];
			$hit[isset($hit[$k]) ? $k : '']++;
		}
		$rekap_kondisi = array();
		foreach ($this->kondisi_label as $k => $label)
		{
			$rekap_kondisi[] = array($label, $hit[$k], $total > 0 ? round($hit[$k] * 100 / $total, 2) : 0);
		}
		if ($hit[''] > 0) $rekap_kondisi[] = array('Tidak diketahui', $hit[''], round($hit[''] * 100 / $total, 2));
		$rekap_kondisi[] = array('JUMLAH', $total, $total > 0 ? 100 : 0);

		$kolom_kondisi = array(
			array('judul' => 'Kondisi', 'lebar' => 22),
			array('judul' => 'Jumlah Bangunan', 'lebar' => 16, 'tipe' => 'bulat'),
			array('judul' => 'Persentase (%)', 'lebar' => 15, 'tipe' => 'angka'),
		);

		$lembar = array(
			array('nama' => 'Data Bangunan', 'kolom' => $kolom_data, 'baris' => $data),
			array('nama' => 'Rekap Kondisi', 'kolom' => $kolom_kondisi, 'baris' => $rekap_kondisi),
			array('nama' => 'Rekap Kecamatan', 'kolom' => $this->_kolom_rekap('Kecamatan'), 'baris' => $this->_rekap($baris_db, 'kecamatan')),
			array('nama' => 'Rekap Fungsi', 'kolom' => $this->_kolom_rekap('Fungsi Bangunan'), 'baris' => $this->_rekap($baris_db, 'fungsi')),
			array('nama' => 'Rekap OPD', 'kolom' => $this->_kolom_rekap('OPD'), 'baris' => $this->_rekap($baris_db, 'opd')),
		);

		$saring = array();
		if ($f['q'] !== '')       $saring[] = 'Pencarian: "' . $f['q'] . '"';
		if ($f['kec'] !== '')     $saring[] = 'Kecamatan: ' . $f['kec'];
		if ($f['fungsi'] !== '')  $saring[] = 'Fungsi: ' . $f['fungsi'];
		if ($f['kondisi'] !== '') $saring[] = 'Kondisi: ' . $this->kondisi_label[$f['kondisi']];
		$lembar[] = array(
			'nama'  => 'Info Unduhan',
			'kolom' => array(array('judul' => 'Keterangan', 'lebar' => 28), array('judul' => 'Nilai', 'lebar' => 70)),
			'baris' => array(
				array('Judul', 'Hasil Analisis Kerusakan Bangunan - SIP Gatutkaca, Kabupaten Cilacap'),
				array('Tanggal unduh', date('d/m/Y H:i')),
				array('Filter', $saring ? implode('; ', $saring) : 'Tanpa filter (seluruh bangunan)'),
				array('Jumlah bangunan', (string) $total),
				array('Keterangan kondisi', '1 = Baik; 2 = Rusak Ringan; 3 = Rusak Sedang; 4 = Rusak Berat'),
				array('Sumber data', 'Data sebaran bangunan yang dikelola admin SIP Gatutkaca (tabel bangunan_gis)'),
			),
		);

		$isi  = \App\Libraries\XlsxWriter::buatBanyak($lembar);
		$nama = 'Analisa-Kerusakan-Bangunan_' . date('Ymd-His') . ($saring ? '_filter' : '_semua') . '.xlsx';
		$this->load->helper('download');
		force_download($nama, $isi, TRUE);
	}

	private function _kolom_rekap($judul_pertama)
	{
		return array(
			array('judul' => $judul_pertama, 'lebar' => 38),
			array('judul' => 'Baik', 'lebar' => 12, 'tipe' => 'bulat'),
			array('judul' => 'Rusak Ringan', 'lebar' => 14, 'tipe' => 'bulat'),
			array('judul' => 'Rusak Sedang', 'lebar' => 14, 'tipe' => 'bulat'),
			array('judul' => 'Rusak Berat', 'lebar' => 13, 'tipe' => 'bulat'),
			array('judul' => 'Tidak Diketahui', 'lebar' => 15, 'tipe' => 'bulat'),
			array('judul' => 'Jumlah', 'lebar' => 12, 'tipe' => 'bulat'),
		);
	}

	/** Hitung jumlah bangunan per nilai $kunci (kecamatan/fungsi/opd) dan per kondisi, urut abjad, baris JUMLAH di akhir. */
	private function _rekap($rows, $kunci)
	{
		$kosong = '(Tidak diisi)';
		$grup   = array();
		$ejaan  = array(); // kunci -> [ejaan asli => jumlah], dipakai memilih tulisan yang paling sering
		foreach ($rows as $r)
		{
			$asli  = trim(preg_replace('/\s+/', ' ', (string) $r[$kunci]));
			$kunci_grup = ($asli === '' || $asli === '-') ? $kosong : mb_strtolower($asli);
			if ($kunci_grup !== $kosong) $ejaan[$kunci_grup][$asli] = ($ejaan[$kunci_grup][$asli] ?? 0) + 1;
			if (! isset($grup[$kunci_grup])) $grup[$kunci_grup] = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '' => 0);
			$k = (string) $r['kondisi'];
			$grup[$kunci_grup][isset($grup[$kunci_grup][$k]) ? $k : '']++;
		}
		$label = array();
		foreach ($grup as $kg => $h)
		{
			if ($kg === $kosong) { $label[$kg] = $kosong; continue; }
			arsort($ejaan[$kg]);
			$label[$kg] = (string) key($ejaan[$kg]);
		}
		uksort($grup, function ($a, $b) use ($kosong) {
			if ($a === $kosong) return 1;
			if ($b === $kosong) return -1;
			return strcmp($a, $b);
		});
		$hasil = array();
		$jml   = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '' => 0);
		foreach ($grup as $kg => $h)
		{
			$hasil[] = array($label[$kg], $h['1'], $h['2'], $h['3'], $h['4'], $h[''], array_sum($h));
			foreach ($h as $k => $v) $jml[$k] += $v;
		}
		$hasil[] = array('JUMLAH', $jml['1'], $jml['2'], $jml['3'], $jml['4'], $jml[''], array_sum($jml));
		return $hasil;
	}
}
