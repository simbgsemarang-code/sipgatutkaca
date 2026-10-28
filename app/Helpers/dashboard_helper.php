<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Data ringkas untuk seksi "Distribusi Status" & "Aktivitas Terkini"
 * pada dashboard Admin / PU / TPA. Sumber: pengajuan_pbg
 * (dan tabel persetujuan TPA per-bidang untuk aktivitas TPA).
 */

if (! function_exists('dashboard_status_label'))
{
	function dashboard_status_label()
	{
		return array('permohonan_baru'=>'Permohonan Baru','review_tpa'=>'Review TPA','perlu_perbaikan'=>'Perlu Perbaikan','review_ulang'=>'Review Ulang','selesai'=>'Sudah Sesuai');
	}
}

if (! function_exists('dashboard_distribusi'))
{
	/** Jumlah permohonan PBG + SLF per status (kunci = status enum). */
	function dashboard_distribusi()
	{
		$ci   =& get_instance();
		$dist = array_fill_keys(array_keys(dashboard_status_label()), 0);

		if($ci->db->table_exists('permohonan_pbg'))foreach($ci->db->select('status,tahap')->get('permohonan_pbg')->result_array() as $r){$k=$r['status']==='disetujui'||(int)$r['tahap']>=4?'selesai':((int)$r['tahap']===3?'review_ulang':((int)$r['tahap']===2?'perlu_perbaikan':($r['status']==='diajukan'?'permohonan_baru':'review_tpa')));$dist[$k]++;}
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

		foreach (array(array('permohonan_pbg', 'PBG')) as $t)
		{
			if (! $ci->db->table_exists($t[0])) continue;
			foreach ($ci->db->select('no_permohonan AS no_registrasi, status, tahap, nama_pemohon, updated_at, created_at')
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
			$k=$r['status']==='disetujui'||(int)$r['tahap']>=4?'selesai':((int)$r['tahap']===3?'review_ulang':((int)$r['tahap']===2?'perlu_perbaikan':($r['status']==='diajukan'?'permohonan_baru':'review_tpa')));
			$out[] = array(
				'ikon'  => _dashboard_inisial($r['nama_pemohon']),
				'judul' => $r['nama_pemohon'],
				'reg'   => $r['no_registrasi'] ?: ($r['jenis'] . ' (draf)'),
				'baris' => ($r['updated_at'] !== $r['created_at']) ? 'Status permohonan:' : 'Permohonan baru diajukan —',
				'badge' => $label[$k],
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
	function dashboard_aktivitas_tpa($user_id, $limit = 6)
	{
		$ci=&get_instance(); $norm=array();
		$rows=$ci->db->select('k.*,p.no_permohonan,p.nama_pemohon')->from('konsultasi_pbg k')->join('permohonan_pbg p','p.id=k.permohonan_id')->where('k.tpa_user_id',(int)$user_id)->order_by('k.assigned_at','DESC')->limit((int)$limit)->get()->result_array();
		foreach($rows as $r)
		{
			$badge=$r['status']==='direkomendasikan'?'Sudah Sesuai':($r['status']==='perlu_perbaikan'?'Perlu Perbaikan':((int)$r['putaran']>1?'Review Ulang':'Ditugaskan'));
			$norm[] = array(
				'ikon'  => _dashboard_inisial($r['nama_pemohon']),
				'judul' => $r['nama_pemohon'] ?: '(pemohon)',
				'reg'   => $r['no_permohonan'],
				'baris' => 'Putaran '.(int)$r['putaran'].' · '.ucfirst($r['bidang']).' —',
				'badge' => $badge,
				'meta'  => dashboard_lalu($r['reviewed_at']?:$r['assigned_at']).' · Permohonan PBG',
			);
		}
		return $norm;
	}
}

if(!function_exists('dashboard_distribusi_tpa'))
{
	function dashboard_distribusi_tpa($user_id)
	{
		$ci=&get_instance();$dist=array_fill_keys(array_keys(dashboard_status_label()),0);
		$rows=$ci->db->select('status,putaran')->where('tpa_user_id',(int)$user_id)->get('konsultasi_pbg')->result_array();
		foreach($rows as $r){$k=$r['status']==='direkomendasikan'?'selesai':($r['status']==='perlu_perbaikan'?'perlu_perbaikan':((int)$r['putaran']>1?'review_ulang':'review_tpa'));$dist[$k]++;}
		return $dist;
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
