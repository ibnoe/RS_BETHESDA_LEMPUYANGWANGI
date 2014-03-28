<?php

include '../lib/dbconn.php';

$query = pg_query("SELECT a.id,a.nama FROM rs00017 a 
					JOIN rs00018 b ON jabatan_medis_fungsional_id = b.id  
					WHERE (b.jabatan_medis_fungsional ILIKE '%DOKTER%' OR b.jabatan_medis_fungsional ILIKE '%PERAWAT%') 
					AND nama ILIKE '%".$_REQUEST['term']."%' LIMIT 10 OFFSET 0");
	$returnRec = array();
		while($row = pg_fetch_array($query)){
			$rec['id'] = $row['id'];
			$rec['label'] = $row['nama'];
			$returnRec[] = $rec;
	}

echo json_encode($returnRec);

/*
include '../lib/dbconn.php';

$query = pg_query("SELECT a.id,a.nama FROM rs00017 a JOIN rs00018 b ON jabatan_medis_fungsional_id = b.id 
				    WHERE a.jabatan_medis_fungsional_id != '234' and nama ILIKE '%".$_REQUEST['term']."%' LIMIT 20 OFFSET 0");
	$returnRec = array();
		while($row = pg_fetch_array($query)){
			$rec['id'] = $row['id'];
			$rec['label'] = $row['nama'];
			$returnRec[] = $rec;
	}

echo json_encode($returnRec);
*/
?>