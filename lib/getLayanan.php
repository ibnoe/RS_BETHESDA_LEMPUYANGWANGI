<?php

include '../lib/dbconn.php';

$query = pg_query("SELECT LPAD(a.id::character varying,5,'0') AS item_id, a.layanan, a.harga FROM rs00034 a  WHERE a.layanan ILIKE '%".$_REQUEST['term']."%' AND a.is_group='N' AND status='1' LIMIT 20 OFFSET 0");				    
	$returnRec = array();
		while($row = pg_fetch_array($query)){
			$rec['item_id'] = $row['item_id'];
			$rec['label'] = $row['layanan'];
			$rec['harga'] = $row['harga'];
			$returnRec[] = $rec;
	}

echo json_encode($returnRec);
