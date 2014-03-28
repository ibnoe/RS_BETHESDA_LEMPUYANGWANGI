<?php

include '../lib/dbconn.php';
ini_set('display_errors',1);

$SQL = "select c.id,upper(b.nama)as nama, 
	  	  e.bangsal|| ' / ' || d.bangsal as bangsal, b.alm_tetap as alamat 
          from rs00010 as a 
              join rs00006 as c on a.no_reg = c.id 
              join rs00002 as b on c.mr_no = b.mr_no 
              join rs00012 as d on a.bangsal_id = d.id 
              join rs00012 as e on e.hierarchy = substr(d.hierarchy,1,6) || '000000000' 
              join rs00012 as f on f.hierarchy = substr(d.hierarchy,1,3) || '000000000000' 
			  left join rs00001 g on g.tc = b.tipe_pasien and g.tt='JEP' 
			  where a.ts_calc_stop is null 
			  AND substr(d.hierarchy,1,3) = (SELECT substr(hierarchy,1,3) FROM rs00012 WHERE id = ".$_REQUEST['bangsal'].")
			  group by c.id,b.nama,b.umur,f.bangsal,e.bangsal,d.bangsal,a.ts_check_in, b.alm_tetap ORDER BY bangsal";

$result = pg_query($SQL);
$table = null;
$i=1;$data = array();
while($row = pg_fetch_array($result)){
array_push($data, $row);
}

echo json_encode($data);
