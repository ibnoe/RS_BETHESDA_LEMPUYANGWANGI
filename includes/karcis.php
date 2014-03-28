<?php
if ($_POST[f_poli] == "") {
  $kodepoli = 100;
} else {
  $kodepoli = getFromTable("select poli from rs00006 where id = '$no_reg'");
  //$kodepoli = getFromTable("SELECT tc FROM rs00001 WHERE tt = 'LYN' AND tc = '$_POST[f_poli]'");
}

$r2 = pg_query($con, "select * from rs99996 where trans_type = 'LYN' and poli = '$kodepoli' order by description");
$d2 = pg_fetch_object($r2);
$d2n = pg_num_rows($r2);

 
if ($d2n > 0) {

	$r1 = pg_query($con,"select x.item_id, x.qty, b.layanan, b.harga, b.harga_atas, b.harga_bawah, c.tdesc " .
						"from rs99997 x " .
						"left join rs00034 b on x.item_id = b.id " .
						"left join rs00001 c on b.satuan_id = c.tc ".
						"and c.tt = 'SAT' ".
						"where x.preset_id = $d2->id and x.tipe_pasien = '$_POST[f_tipe]' ");
						//"where x.preset_id = $d2->id ");
	unset($_SESSION["layanan"]);
	$cnt = 0;
	while ($d1 = pg_fetch_object($r1)) {
	    $_SESSION["layanan"][$cnt]["id"]     = $d1->item_id;
	    $_SESSION["layanan"][$cnt]["nama"]   = $d1->layanan;
	    $_SESSION["layanan"][$cnt]["jumlah"] = $d1->qty;
	    $_SESSION["layanan"][$cnt]["satuan"] = $d1->tdesc;
	    $_SESSION["layanan"][$cnt]["harga"]  = $d1->harga;
	    $_SESSION["layanan"][$cnt]["total"]  = $d1->harga * $d1->qty;
	    $cnt++;
	}
	pg_free_result($r1);

} // end of is_array($d2)

if (is_array($_SESSION["layanan"])) {
	foreach ($_SESSION["layanan"] as $v) {
	        
		 
		@pg_query("insert into rs00008 (id,trans_type,trans_form, trans_group, tanggal_trans, " .
	                "tanggal_entry, waktu_entry, no_reg,     item_id,     referensi, ".
	                "qty,           harga,       tagihan,    pembayaran $v1 ".
	            ") values (".
	                "nextval('rs00008_seq'), '$_POST[f_poli]', '120', currval('rs00008_seq_group'), CURRENT_DATE, " .
	                "CURRENT_DATE, CURRENT_TIME, '$no_reg', '".$v["id"]."', '', " .
	                $v["jumlah"].",".$v["harga"].",".$v["total"].",0 $v2)"
	        );
	         
	          
	        $total += $v[total];
	 
	}
}  

$loket = getFromTable("select ".
         "case when rawat_inap = 'I' then 'RIN' ".
         "     when rawat_inap = 'Y' then 'RJL' ".
         "     else 'IGD' ".
         "end as rawatan ".
         "from rs00006 where id = '$no_reg'");
//$tipepasien = getFromTable("select  b.tipe from   rs00008  a,  rs00006 b 	         ".
		//	"where a.no_reg = '$no_reg'  AND b.id = a.no_reg ");

//menghilangkan otomatis tiket by djeko 11111
if ($loket == "IGD") {
  $lyn = 100;
  $hargatiket = 0;
} elseif ($loket == "RJL") {
  $lyn = $kodepoli;
  if ($lyn == 101 or $lyn == 105) { $hargatiket = 0; } else { $hargatiket = 0;}
} else {
  $lyn = 0;
}

// is karcis
if (is_array($_SESSION["layanan"])) {
pg_query("insert into rs00005 values(nextval('kasir_seq'), '$no_reg', CURRENT_DATE, '$loket', ".
         "'N', 'Y', $lyn, $hargatiket, 'N')");
}

pg_query("update rs00006 set is_karcis='Y' where id = '$no_reg'");

unset($_SESSION[layanan]);
?>
