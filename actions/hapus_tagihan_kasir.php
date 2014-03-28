<?php
/**
 * Gema Perbangsa
 * 24 September 2013
 */ 
include '../lib/dbconn.php';

if(!empty($_GET['rg'])){
	if($_GET['ref']=='sewa_kamar'){
	$result = pg_fetch_array(pg_query("SELECT bangsal_id || '-' || qty AS keterangan, tanggal_entry FROM rs00008 WHERE id = '".$_GET['id']."'"));
	pg_query("DELETE FROM rs00008 WHERE id = ".$_GET['id']);	
	pg_query("DELETE FROM rs00005 WHERE tanggal_entry = ".$result['tanggal_entry']." AND keterangan = ".$result['keterangan']." AND layanan = '99996' LIMIT 1 OFFSET 0" );
	}
	else{
	pg_query("DELETE FROM rs00008 WHERE id = ".$_GET['id']);	
	}
}

header('Location:../index2.php?p=335&kas='.$_GET['kas'].'&rg='.$_GET['rg'].'&sub=3');



