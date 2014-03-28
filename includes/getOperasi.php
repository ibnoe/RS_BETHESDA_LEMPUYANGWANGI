<?php
/**
 * Gema Perbangsa
 * 19 September 2013
 */ 
//ini_set('display_errors',1);
require_once '../lib/dbconn.php';
$tbl = $_REQUEST['tbl'];
$term = $_REQUEST['term'];
if($tbl =='operasi'){
	$query = pg_query("SELECT a.id,a.hierarchy, a.layanan || '(' || (SELECT layanan FROM rs00034 WHERE SUBSTRING(hierarchy,1,9) = 
					  SUBSTRING(a.hierarchy,1,9) AND is_group = 'Y') || ')' AS layanan, harga FROM rs00034 a
					  WHERE a.layanan ILIKE '%".$_REQUEST['term']."%' AND a.hierarchy LIKE '004003%' AND is_group = 'N' LIMIT 20 OFFSET 0");
	$returnRec = array();
		while($row = pg_fetch_array($query)){
			$rec['id'] = $row['id'];
			$rec['label'] = $row['layanan'];
			$rec['harga'] = $row['harga'];
			$returnRec[] = $rec;
	}
}

else if($tbl =='anestesi'){
	$query = pg_query("SELECT a.id,a.hierarchy, a.layanan || '(' || (SELECT layanan FROM rs00034 WHERE SUBSTRING(hierarchy,1,9) = 
					  SUBSTRING(a.hierarchy,1,9) AND is_group = 'Y') || ')' AS layanan, harga FROM rs00034 a
					  WHERE a.layanan ILIKE '%".$_REQUEST['term']."%' AND a.hierarchy LIKE '004004%' AND is_group = 'N' LIMIT 20 OFFSET 0");
	$returnRec = array();
		while($row = pg_fetch_array($query)){
			$rec['id'] = $row['id'];
			$rec['label'] = $row['layanan'];
			$rec['harga'] = $row['harga'];
			$returnRec[] = $rec;
	}
}
else if($tbl =='dokter'){
	$query = pg_query("SELECT a.id,a.nama FROM rs00017 a JOIN rs00018 b ON jabatan_medis_fungsional_id = b.id 
				   AND b.jabatan_medis_fungsional ILIKE '%dokter%' WHERE nama ILIKE '%".$term."%' LIMIT 10 OFFSET 0");
	$returnRec = array();
		while($row = pg_fetch_array($query)){
			$rec['id'] = $row['id'];
			$rec['label'] = $row['nama'];
			$returnRec[] = $rec;
	}
}
else if($tbl =='asisten'){
	$query = pg_query("SELECT a.id,a.nama FROM rs00017 a JOIN rs00018 b ON jabatan_medis_fungsional_id = b.id 
				   AND(b.jabatan_medis_fungsional ILIKE '%dokter%' OR b.jabatan_medis_fungsional ILIKE '%perawat%') WHERE nama ILIKE '%".$term."%' LIMIT 10 OFFSET 0");
	$returnRec = array();
		while($row = pg_fetch_array($query)){
			$rec['id'] = $row['id'];
			$rec['label'] = $row['nama'];
			$returnRec[] = $rec;
	}
}
else if($tbl=='getData'){
	$query = pg_query("SELECT a.id,a.item_id,tanggal(a.tanggal_trans,1) AS tanggal_trans, a.trans_group,b.layanan, a.harga,a.referensi, a.tagihan, 
						a.dibayar_penjamin, a.diskon,c.id_dokter1,d.nama AS dokter1, c.diskon_dokter1,c.id_dokter2,e.nama AS dokter2,
						c.diskon_dokter2,c.id_asisten1,f.nama AS asisten1, c.diskon_asisten1,c.id_asisten2,g.nama AS asisten2,c.diskon_asisten2,
						c.id_asisten3,h.nama AS asisten3,c.diskon_asisten3 FROM rs00008 a 
						JOIN rs00034 b ON a.item_id::numeric = b.id AND a.trans_type = 'LTM'
						JOIN rs00008_op c ON a.id = c.id_rs08
						LEFT JOIN rs00017 d ON c.id_dokter1 = d.id
						LEFT JOIN rs00017 e ON c.id_dokter2 = e.id
						LEFT JOIN rs00017 f ON c.id_asisten1 = f.id
						LEFT JOIN rs00017 g ON c.id_asisten2 = g.id
						LEFT JOIN rs00017 h ON c.id_asisten3 = h.id WHERE a.trans_group = ".$term." ORDER BY a.id");
	$returnRec = array();
		$i=0;
		while($row = pg_fetch_array($query)){
			$rec['id'][$i] = $row['id'];
			$rec['item_id'][$i] = $row['item_id'];
			$rec['tanggal_trans'][$i] = $row['tanggal_trans'];
			$rec['trans_group'][$i] = $row['trans_group'];
			$rec['layanan'][$i] = $row['layanan'];
			$rec['harga'][$i] = $row['harga'];
			$rec['diskon'][$i] = $row['diskon'];
			$rec['referensi'][$i] = $row['referensi'];
			$rec['tagihan'][$i] = $row['tagihan'];
			$rec['dibayar_penjamin'][$i] = $row['dibayar_penjamin'];
			$rec['id_dokter1'][$i] = $row['id_dokter1'];
			$rec['dokter1'][$i] = $row['dokter1'];
			$rec['diskon_dokter1'][$i] = $row['diskon_dokter1'];
			$rec['id_dokter2'][$i] = $row['id_dokter2'];
			$rec['dokter2'][$i] = $row['dokter2'];
			$rec['diskon_dokter2'][$i] = $row['diskon_dokter2'];
			$rec['id_asisten1'][$i] = $row['id_asisten1'];
			$rec['asisten1'][$i] = $row['asisten1'];
			$rec['diskon_asisten1'][$i] = $row['diskon_asisten1'];
			$rec['id_asisten2'][$i] = $row['id_asisten2'];
			$rec['asisten2'][$i] = $row['asisten2'];
			$rec['diskon_asisten2'][$i] = $row['diskon_asisten2'];
			$rec['id_asisten3'][$i] = $row['id_asisten3'];
			$rec['asisten3'][$i] = $row['asisten3'];
			$rec['diskon_asisten3'][$i] = $row['diskon_asisten3'];
			
			$i++;
	}
			$returnRec[] = $rec;
	}	

echo json_encode($returnRec);
//print_r(pg_fetch_array($query));
//print_r($rec);
