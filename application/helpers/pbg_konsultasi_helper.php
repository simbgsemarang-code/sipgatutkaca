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
