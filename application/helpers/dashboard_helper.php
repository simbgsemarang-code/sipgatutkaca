<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Data ringkas untuk seksi "Distribusi Status" & "Aktivitas Terkini"
 * pada dashboard Admin / PU / TPA. Sumber: pengajuan_pbg + pengajuan_slf
 * (dan tabel persetujuan TPA per-bidang untuk aktivitas TPA).
 */

if (! function_exists('dashboard_status_label'))
{
	function dashboard_status_label()
	{
		return array(
			'draf'                         => 'Draf',
			'verifikasi_dokumen'           => 'Verifikasi Dokumen',
			'perbaikan_dokumen'            => 'Perbaikan Dokumen',
			'perbaikan_dokumen_konsultasi' => 'Perbaikan Dok. Konsultasi',
			'menunggu_jadwal_konsultasi'   => 'Menunggu Jadwal Konsultasi',
			'disetujui_tpa'                => 'Disetujui Semua TPA',
		);
	}
}

if (! function_exists('dashboard_distribusi'))
{
	/** Jumlah permohonan PBG + SLF per status (kunci = status enum). */
	function dashboard_distribusi()
	{
		$ci   =& get_instance();
		$dist = array_fill_keys(array_keys(dashboard_status_label()), 0);

		foreach (array('pengajuan_pbg', 'pengajuan_slf') as $t)
		{
			if (! $ci->db->table_exists($t)) continue;
			foreach ($ci->db->select('status, COUNT(*) AS n')->group_by('status')->get($t)->result_array() as $r)
			{
				if (isset($dist[$r['status']])) $dist[$r['status']] += (int) $r['n'];
			}
		}
		return $dist;
	}
}

if (! function_exists('_dashboard_inisial'))
{
	function _dashboard_inisial($nama)
	{
		$nama = trim((string) $nama);
		return $nama === '' ? '?' : mb_strtoupper(mb_substr($nama, 0, 1));
	}
}

if (! function_exists('dashboard_aktivitas'))
{
	/**
	 * Permohonan PBG/SLF terbaru menurut updated_at (untuk Admin & PU).
	 * Mengembalikan baris ternormalisasi: ikon, judul, reg, baris, badge, meta.
	 */
	function dashboard_aktivitas($limit = 6)
	{
		$ci     =& get_instance();
		$label  = dashboard_status_label();
		$mentah = array();

		foreach (array(array('pengajuan_pbg', 'PBG'), array('pengajuan_slf', 'SLF')) as $t)
		{
			if (! $ci->db->table_exists($t[0])) continue;
			foreach ($ci->db->select('no_registrasi, status, nama_pemohon, updated_at, created_at')
				->order_by('updated_at', 'DESC')->limit($limit)->get($t[0])->result_array() as $r)
			{
				$r['jenis'] = $t[1];
				$mentah[] = $r;
			}
		}
		usort($mentah, function ($a, $b) { return strcmp($b['updated_at'], $a['updated_at']); });

		$out = array();
		foreach (array_slice($mentah, 0, $limit) as $r)
		{
			$out[] = array(
				'ikon'  => _dashboard_inisial($r['nama_pemohon']),
				'judul' => $r['nama_pemohon'],
				'reg'   => $r['no_registrasi'] ?: ($r['jenis'] . ' (draf)'),
				'baris' => ($r['updated_at'] !== $r['created_at']) ? 'Status permohonan:' : 'Permohonan baru diajukan —',
				'badge' => isset($label[$r['status']]) ? $label[$r['status']] : $r['status'],
				'meta'  => dashboard_lalu($r['updated_at']) . ' · Permohonan ' . $r['jenis'],
			);
		}
		return $out;
	}
}

if (! function_exists('dashboard_aktivitas_tpa'))
{
	/**
	 * Keputusan TPA per-bidang terbaru (dari pengajuan_*_persetujuan_tpa).
	 * $bidang: NULL = semua bidang (akun 'tpa' generik), atau salah satu
	 * dari 'tpa_arsitek' / 'tpa_struktur' / 'tpa_mep'.
	 */
	function dashboard_aktivitas_tpa($bidang = NULL, $limit = 6)
	{
		$ci  =& get_instance();
		$out = array();

		$peta = array(
			array('pengajuan_pbg_persetujuan_tpa', 'pengajuan_pbg', 'PBG'),
			array('pengajuan_slf_persetujuan_tpa', 'pengajuan_slf', 'SLF'),
		);
		$bidang_label = array('tpa_arsitek' => 'Arsitektur', 'tpa_struktur' => 'Struktur', 'tpa_mep' => 'MEP');

		foreach ($peta as $p)
		{
			if (! $ci->db->table_exists($p[0])) continue;

			$ci->db->select('t.bidang, t.status, t.ditinjau_pada, p.no_registrasi, p.nama_pemohon')
				->from($p[0] . ' t')
				->join($p[1] . ' p', 'p.id = t.id_pengajuan', 'left')
				->where('t.ditinjau_pada IS NOT NULL')
				->order_by('t.ditinjau_pada', 'DESC')
				->limit($limit);
			if ($bidang !== NULL) $ci->db->where('t.bidang', $bidang);

			foreach ($ci->db->get()->result_array() as $r)
			{
				$r['jenis']        = $p[2];
				$r['bidang_label'] = isset($bidang_label[$r['bidang']]) ? $bidang_label[$r['bidang']] : $r['bidang'];
				$out[] = $r;
			}
		}
		usort($out, function ($a, $b) { return strcmp($b['ditinjau_pada'], $a['ditinjau_pada']); });

		$kep = array(
			'disetujui'                    => 'Disetujui',
			'perbaikan_dokumen'            => 'Minta Perbaikan Dokumen',
			'perbaikan_dokumen_konsultasi' => 'Perbaikan via Konsultasi',
		);
		$norm = array();
		foreach (array_slice($out, 0, $limit) as $r)
		{
			$norm[] = array(
				'ikon'  => _dashboard_inisial($r['nama_pemohon']),
				'judul' => $r['nama_pemohon'] ?: '(pemohon)',
				'reg'   => $r['no_registrasi'] ?: ($r['jenis'] . ' —'),
				'baris' => 'Peninjauan ' . $r['bidang_label'] . ' —',
				'badge' => isset($kep[$r['status']]) ? $kep[$r['status']] : $r['status'],
				'meta'  => dashboard_lalu($r['ditinjau_pada']) . ' · Permohonan ' . $r['jenis'],
			);
		}
		return $norm;
	}
}

if (! function_exists('dashboard_lalu'))
{
	/** "baru saja" / "x menit lalu" / "x jam lalu" / "x hari lalu" / tanggal. */
	function dashboard_lalu($dt)
	{
		$ts = is_numeric($dt) ? (int) $dt : strtotime((string) $dt);
		if (! $ts) return '';
		$d = time() - $ts;
		if ($d < 60)      return 'baru saja';
		if ($d < 3600)    return floor($d / 60) . ' menit lalu';
		if ($d < 86400)   return floor($d / 3600) . ' jam lalu';
		if ($d < 2592000) return floor($d / 86400) . ' hari lalu';
		return date('d M Y', $ts);
	}
}
