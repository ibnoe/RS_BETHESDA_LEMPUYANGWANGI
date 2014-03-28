<?php
/**
 * Gema Perbangsa
 * 10 September 2013
 */ 
include '../lib/dbconn.php';
ini_set('display_errors',1);
$SQL = "SELECT a.id, UPPER(b.nama) AS nama,UPPER(b.alm_tetap) AS alamat, c.tdesc as poli FROM rs00006 a JOIN rs00002 b ON a.mr_no = b.mr_no 
JOIN rs00001 c ON a.poli = c.tc::numeric AND c.tt = 'LYN' WHERE a.poli = '".$_REQUEST['poli']."'::numeric AND a.tanggal_reg=CURRENT_DATE
UNION
SELECT a.id, UPPER(b.nama) AS nama,UPPER(b.alm_tetap) AS alamat, d.tdesc AS poli FROM c_visit c 
JOIN rs00006 a ON a.id = c.no_reg 
JOIN rs00002 b ON a.mr_no = b.mr_no
JOIN rs00001 d ON c.id_konsul = d.tc AND d.tt = 'LYN'
WHERE c.id_konsul = '".$_REQUEST['poli']."' AND c.tanggal_konsul=CURRENT_DATE ORDER BY poli, id";

$result = pg_query($SQL);
$i=1;$data = array();
while($row = pg_fetch_array($result)){
array_push($data, $row);
}

echo json_encode($data);
