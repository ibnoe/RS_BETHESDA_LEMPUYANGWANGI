<?php

include '../lib/dbconn.php';

$query = pg_query("SELECT a.id,a.id || ' - ' || a.obat || ' - ' || b.tdesc AS satuan FROM rs00015 a JOIN rs00001 b ON a.satuan_id = b.tc AND b.tt = 'SAT' 
WHERE a.obat ILIKE '%".$_GET["q"]."%' LIMIT 20 OFFSET 0");		
		while($row = pg_fetch_array($query)){
			echo $row['satuan']."\n";
	}


