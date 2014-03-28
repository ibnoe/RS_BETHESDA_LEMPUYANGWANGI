<?php

include '../lib/dbconn.php';

$query = pg_query("SELECT a.id,a.bangsal_detail,a.harga FROM rsv_info_kamar a  WHERE a.bangsal_detail ILIKE '%".$_REQUEST['term']."%' LIMIT 25 OFFSET 0");				    
	$returnRec = array();
		while($row = pg_fetch_array($query)){
			$rec['id'] = $row['id'];
			$rec['label'] = $row['bangsal_detail'];
			$rec['harga'] = $row['harga'];
			$returnRec[] = $rec;
	}

echo json_encode($returnRec);
