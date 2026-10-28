<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Rows ordered newest first. Retain recommendations from previous rounds,
// but replace unresolved members with the selected team in the latest round.
function pbg_ringkasan_konsultasi($rows)
{
    $latest=array(); $rounds=array();
    foreach($rows as $r){
        $key=$r['bidang'].':'.$r['tpa_user_id'];
        if(!isset($latest[$key]))$latest[$key]=$r;
        $rounds[$r['bidang']]=max($rounds[$r['bidang']]??0,(int)$r['putaran']);
    }
    $groups=array();
    foreach($latest as $r){
        if((int)$r['putaran']!==$rounds[$r['bidang']]&&$r['status']!=='direkomendasikan')continue;
        $groups[$r['bidang']][]=$r;
    }
    $result=array();
    foreach($groups as $field=>$members){
        $summary=$members[0]; $summary['anggota']=$members;
        $summary['tpa_ids']=array_column($members,'tpa_user_id');
        $summary['nama_tpa']=implode(', ',array_column($members,'nama_tpa'));
        $summary['status']='direkomendasikan'; $summary['perbaikan_dikirim_at']='lengkap';
        foreach($members as $r){
            if($r['status']==='ditugaskan')$summary['status']='ditugaskan';
            elseif($r['status']==='perlu_perbaikan'&&$summary['status']!=='ditugaskan')$summary['status']='perlu_perbaikan';
            if($r['status']==='perlu_perbaikan'&&empty($r['perbaikan_dikirim_at']))$summary['perbaikan_dikirim_at']=null;
        }
        $result[$field]=$summary;
    }
    return $result;
}

// Nilai status di database tetap (ditugaskan/perlu_perbaikan/direkomendasikan);
// hanya tulisan yang tampil ke pengguna yang diganti.
function konsultasi_label_status($status)
{
    $peta=array("direkomendasikan"=>"Sudah Sesuai","perlu_perbaikan"=>"Perlu Perbaikan","ditugaskan"=>"Ditugaskan");
    return isset($peta[$status])?$peta[$status]:ucwords(str_replace("_"," ",(string)$status));
}

/**
 * Sinkronkan tahap dan status permohonan dari hasil review terbaru.
 */
function pbg_sinkron_tahap($permohonan_id)
{
    $ci =& get_instance();
    $permohonan_id = (int) $permohonan_id;
    $row = $ci->db->select('id,tahap,status')->where('id', $permohonan_id)->get('permohonan_pbg')->row_array();
    if (!$row || $row['status'] === 'ditolak') return FALSE;
    $rows=$ci->db->select('k.*,u.nama AS nama_tpa')->from('konsultasi_pbg k')->join('users u','u.id=k.tpa_user_id','left')->where('k.permohonan_id',$permohonan_id)->order_by('k.putaran','DESC')->order_by('k.id','DESC')->get()->result_array();
    if(!$rows)return FALSE;
    $ringkas=pbg_ringkasan_konsultasi($rows); $menunggu=FALSE; $perbaikan=FALSE; $semua_sesuai=!empty($ringkas); $putaran=1;
    foreach($ringkas as $x){$putaran=max($putaran,(int)$x['putaran']);if($x['status']==='ditugaskan')$menunggu=TRUE;if($x['status']==='perlu_perbaikan')$perbaikan=TRUE;if($x['status']!=='direkomendasikan')$semua_sesuai=FALSE;}
    if($semua_sesuai){$tujuan=4;$status='disetujui';}
    elseif($menunggu){$tujuan=$putaran>1?3:1;$status='diverifikasi';}
    else{$tujuan=$perbaikan?2:(int)$row['tahap'];$status='diverifikasi';}
    if ($tujuan === (int)$row['tahap'] && $status===$row['status']) return FALSE;
    $ci->db->where('id', $permohonan_id)->update('permohonan_pbg', array('tahap'=>$tujuan,'status'=>$status,'updated_at'=>date('Y-m-d H:i:s')));
    return TRUE;
}

/** Terbitkan BA distribusi untuk satu putaran (satu per putaran). TRUE kalau baru terbit. */
function pbg_buat_ba_distribusi($permohonan_id, $putaran, $tanggal, $oleh, $nomor = null)
{
    $ci =& get_instance();
    if (!$ci->db->table_exists('berita_acara_distribusi')) return FALSE;
    if ($ci->db->where('permohonan_id', $permohonan_id)->where('putaran', $putaran)->count_all_results('berita_acara_distribusi')) return FALSE;
    $romawi = array('I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII');
    $tahun = date('Y', strtotime($tanggal)); $bulan = (int) date('n', strtotime($tanggal));
    $urut = $ci->db->where('YEAR(tanggal_ba)', $tahun)->count_all_results('berita_acara_distribusi') + 1;
    $nomor=trim((string)$nomor); if($nomor==='')$nomor='600.1.15.2/'.$urut.'/BA-DTPA/'.$romawi[$bulan - 1].'/'.$tahun;
    $ci->db->insert('berita_acara_distribusi', array('permohonan_id' => $permohonan_id, 'putaran' => $putaran, 'nomor' => $nomor, 'tanggal_ba' => $tanggal, 'dibuat_oleh' => $oleh, 'created_at' => date('Y-m-d H:i:s')));
    return TRUE;
}

/**
 * TPA yang meminta perbaikan ditugaskan lagi (putaran berikutnya) begitu PU
 * mengirim link Google Drive perbaikannya, tanpa PU mengajukan konsultasi lagi.
 * Idempoten: hanya TPA yang perbaikannya sudah terkirim lewat link, belum
 * punya tugas lanjutan, dan yang putarannya masih yang terbaru di bidangnya.
 * Aman dipanggil berulang (juga untuk menyusul link yang terkirim sebelum
 * fitur ini ada). Mengembalikan daftar penugasan baru: konsultasi_id, tpa_user_id, nama, bidang, putaran.
 */
function pbg_tugaskan_ulang_perbaikan($permohonan_id)
{
    $ci =& get_instance(); $id = (int) $permohonan_id;
    if (!$ci->db->table_exists('link_perbaikan_pbg')) return array();
    $p = $ci->db->select('id,status')->where('id', $id)->get('permohonan_pbg')->row_array();
    if (!$p || in_array($p['status'], array('disetujui', 'ditolak'), TRUE)) return array();
    $rows = $ci->db->select('k.*,u.nama AS nama_tpa')->from('konsultasi_pbg k')->join('users u', 'u.id=k.tpa_user_id', 'left')->where('k.permohonan_id', $id)->order_by('k.putaran', 'DESC')->order_by('k.id', 'DESC')->get()->result_array();
    if (!$rows) return array();
    $maks = array();
    foreach ($rows as $r) $maks[$r['bidang']] = max(isset($maks[$r['bidang']]) ? $maks[$r['bidang']] : 0, (int) $r['putaran']);
    $hasil = array(); $sekarang = date('Y-m-d H:i:s');
    $ci->db->trans_start();
    foreach (pbg_ringkasan_konsultasi($rows) as $bidang => $ringkas) {
        foreach ($ringkas['anggota'] as $m) {
            if ($m['status'] !== 'perlu_perbaikan' || empty($m['perbaikan_dikirim_at']) || (int) $m['putaran'] !== $maks[$bidang]) continue;
            $link = $ci->db->where('permohonan_id', $id)->where('putaran', (int) $m['putaran'])->order_by('id', 'DESC')->get('link_perbaikan_pbg')->row_array();
            if (!$link) continue;
            $baru = (int) $m['putaran'] + 1; $oleh = $link['dibuat_oleh'] ? (int) $link['dibuat_oleh'] : (int) $m['assigned_by'];
            $ci->db->insert('konsultasi_pbg', array('permohonan_id' => $id, 'tpa_user_id' => $m['tpa_user_id'], 'bidang' => $bidang, 'putaran' => $baru, 'status' => 'ditugaskan', 'komentar_pu' => $link['keterangan'] !== null && $link['keterangan'] !== '' ? $link['keterangan'] : null, 'assigned_by' => $oleh, 'assigned_at' => $sekarang));
            pbg_buat_ba_distribusi($id, $baru, date('Y-m-d'), $oleh);
            $hasil[] = array('konsultasi_id' => $ci->db->insert_id(), 'tpa_user_id' => (int) $m['tpa_user_id'], 'nama' => $m['nama_tpa'] ? $m['nama_tpa'] : 'TPA', 'bidang' => $bidang, 'putaran' => $baru);
        }
    }
    if ($hasil) $ci->db->where('id', $id)->update('permohonan_pbg', array('tahap' => 3, 'status' => 'diverifikasi', 'updated_at' => $sekarang));
    $ci->db->trans_complete();
    return $hasil;
}
