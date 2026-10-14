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
 * Sinkronkan tahap permohonan untuk penugasan PUTARAN 1: selama masih ada TPA
 * berstatus "ditugaskan" permohonan berada di Tahap 1; setelah semua TPA
 * mengirim hasil review otomatis pindah ke Tahap 2. Putaran lanjutan (Tahap 3
 * dan seterusnya) tidak disentuh. TRUE bila tahap diubah.
 */
function pbg_sinkron_tahap($permohonan_id)
{
    $ci =& get_instance();
    $permohonan_id = (int) $permohonan_id;
    $row = $ci->db->select('id,tahap,status')->where('id', $permohonan_id)->get('permohonan_pbg')->row_array();
    if (!$row || !in_array((int) $row['tahap'], array(1, 2), TRUE) || in_array($row['status'], array('disetujui', 'ditolak'), TRUE)) return FALSE;
    $maks = $ci->db->select_max('putaran', 'maks')->where('permohonan_id', $permohonan_id)->get('konsultasi_pbg')->row_array();
    if ((int) $maks['maks'] !== 1) return FALSE;
    $menunggu = $ci->db->where('permohonan_id', $permohonan_id)->where('status', 'ditugaskan')->count_all_results('konsultasi_pbg');
    $tujuan = $menunggu > 0 ? 1 : 2;
    if ($tujuan === (int) $row['tahap']) return FALSE;
    $ci->db->where('id', $permohonan_id)->update('permohonan_pbg', array('tahap' => $tujuan, 'updated_at' => date('Y-m-d H:i:s')));
    return TRUE;
}
