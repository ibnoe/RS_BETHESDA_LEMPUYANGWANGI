<?php 

session_start();

$PID = "p_mata";

require_once("../lib/dbconn.php");

$_GET["rg"] = $_GET[rg];
$rg = isset($_GET["rg"])? $_GET["rg"] : $_POST["rg"];
$mr = isset($_GET["mr"])? $_GET["mr"] : $_POST["mr"];
// Agung Sunandar 22:20 26/06/2012 menambahkan simpan ke History User Untuk hapus layanan
$sub = isset($_GET["sub"])? $_GET["sub"] : $_POST["sub"];
//echo "rg=".$_GET["rg"];exit;
if ($_SESSION[uid] == "kasir1") {
    $kasir = "RJL";
    $lyn = getFromTable("select layanan from rs00005 where reg='".$_GET[rg]."' and layanan not in (99997,99998,99999)");

//echo "lyn: $lyn"; exit();

} elseif ($_SESSION[uid] == "kasir2") {
    $kasir = "RIN";    
    $lyn = 0;
} else {
    $status = getFromTable("select rawat_inap from rs00006 where id = '".$_GET[rg]."'");
    if ($status == "Y") {
	$kasir = "RJL";
    } elseif ($status == "N") {
	$kasir = "IGD";
    } else {
	$kasir = "RIN";    
    }

    $lyn = 99997;
}

if ($_GET[tbl] == "bayar") {
    $SQL = "delete from rs00005 where ".
    	   "id = ".$_GET["del"];

}elseif ($_GET[tbl] == "del_paket") {
    $add = "&sub2=paket";
	$SQL = "delete from rs00008 where ".
    	   "id = ".$_GET["del"];


    $jml = getFromTable("select (tagihan) as jumlah from rs00008 where id=".$_GET[del]);
    $jmlx = 0 - $jml;


	$kodepoli = getFromTable("SELECT POLI FROM RS00006 WHERE ID = '".$_GET["rg"]."' ");  
 
    pg_query( 	"insert into rs00005 (id, reg, tgl_entry, kasir, is_obat, ".
	    		"is_karcis, layanan, jumlah, user_id,is_bayar) ".
	    		"values (nextval('kasir_seq'), '".$_GET[rg]."', CURRENT_DATE, 'RJL', 'N', 'N', '888', $jmlx, '".$_SESSION["uid"]."','N') ");
	
	$cek_id_paket=getFromTable("select to_number(item_id,'99999999') from rs00008 where id= ".$_GET[del]." ");
	$r1 = pg_query($con,"select item_id,qty from rs99997 where preset_id=$cek_id_paket and trans_type='OBI' ");
		
	$rows = pg_num_rows($r1);
		
    // Agung SUnandar 11:01 16/07/2012 membetulkan field qty yang di ambil
	$stok = getFromTable("select id_transaksi from rs00008 where id=".$_GET[del]);
	
    for ($n = 1; $n < 5; $n++) $prevLevel[$n] = "";
    while ($d1 = pg_fetch_object($r1)) {
	$id=getFromTable("select tc from rs00001 where tc_poli=$poli  and tt='GDP' ");
	pg_query("update rs00016a set $stok = $stok + $d1->qty where obat_id=$d1->item_id ");
	}
	
	//========== hystory user
$SQL2 = "insert into history_user " .
            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
            "values".
            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'Poli Mata','Pelayanan -> Poliklinik Mata','Menghapus Pelayanan Paket No.MR $mr No.REG $rg Total $jml','".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."')";
pg_query($con, $SQL2);

//======================
	} elseif ($_GET[tbl] == "bhp") {
    $add = "&sub2=bhp";
	$SQL = "delete from rs00008 where ".
    	   "id = ".$_GET["del"];
	

    $jml = getFromTable("select (tagihan) as jumlah from rs00008 where id=".$_GET[del]);
    $jmlx = 0 - $jml;


	$kodepoli = getFromTable("SELECT POLI FROM RS00006 WHERE ID = '".$_GET["rg"]."' ");  
 
    pg_query(	"insert into rs00005 (id, reg, tgl_entry, kasir, is_obat, ".
                "is_karcis, layanan, jumlah, user_id,is_bayar) ".
                "values (nextval('kasir_seq'), '".$_GET[rg]."', CURRENT_DATE, 'RJL', 'N', 'N', '333', $jmlx, '".$_SESSION["uid"]."','N') ");
	
	// Agung SUnandar 11:01 16/07/2012 membetulkan field qty yang di ambil
	$stok = getFromTable("select id_transaksi from rs00008 where id=".$_GET[del]);
        $id_obt = getFromTable("select item_id from rs00008 where id=".$_GET[del]);
        $jml_qty = getFromTable("select qty from rs00008 where id=".$_GET[del]);

	pg_query("update rs00016a set $stok = $stok + $jml_qty where obat_id=$id_obt ");
	
	
	//========== hystory user
$SQL2 = "insert into history_user " .
            " (id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
            " values ".
            " (nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'Poli Jantung','Pelayanan -> Poliklinik Jantung','Menghapus BHP No.MR $mr No.REG $rg dengan Total Tagihan $jml','".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."')";
pg_query($con, $SQL2);
//======================

} elseif ($_GET[tbl] == "tindakan") {
    $add = "&sub2=nonpaket";
	$SQL = "delete from rs00008 where ".
    	   "id = ".$_GET["del"];

    
    $lab_or_rad = getFromTable("select trans_form from rs00008 where id = ".$_GET[del]);
    
    if ($lab_or_rad == "LAB")  {
	$lyn = 99998;
	
    } elseif ($lab_or_rad == "RAD") {	
	$lyn = 99999;	
	
    } 
    
//    echo "labrad: $lab_or_rad - $lyn"; exit();
    $jml = getFromTable("select (qty * harga) as jumlah from rs00008 where id=".$_GET[del]);
    $jmlx = 0 - $jml;


//echo "$lyn $jmlx "; exit();    
    pg_query( 	"insert into rs00005 (id, reg, tgl_entry, kasir, is_obat, ".
	    		"is_karcis, layanan, jumlah, is_bayar,user_id) ".
	    		"values (nextval('kasir_seq'), '".$_GET[rg]."', CURRENT_DATE, '$kasir', 'N', 'N', $lyn, $jmlx, 'N','".$_SESSION["uid"]."') ");

//========== Agung Sunandar 22:19 26/06/2012 hystory user
$SQL2 = "insert into history_user " .
            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
            "values".
            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'Poli Mata','Pelayanan -> Poliklinik Mata','Menghapus Pelayanan Non Paket No.MR $mr No.REG $rg Total $jml','".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."')";
    pg_query($con, $SQL2);
//======================
} elseif ($_GET[tbl] == "obat1") {
    $add = "&sub=obat";
    $SQL = "delete from rs00008 where ".
    	   "id = ".$_GET["del"];

    $jml = getFromTable("select (qty * harga) as jumlah from rs00008 where id=".$_GET[del]);
    $jmlx = 0 - $jml;

    pg_query(	"insert into rs00005 (id, reg, tgl_entry, kasir, is_obat, ".
	    		"is_karcis, layanan, jumlah, is_bayar) ".
	    		"values (nextval('kasir_seq'), '".$_GET[rg]."', CURRENT_DATE, '$kasir', 'Y', 'N', 99997, $jmlx, 'N') ");

   $cek_karcis = getFromTable("select jumlah from rs00005 where reg = '".$_GET[rg]."' and is_karcis = 'Y'");
   $totalObat = getFromTable("select sum(jumlah) from rs00005 where reg = '".$_GET[rg]."' and is_obat = 'Y' and layanan != 99995");
   if ($cek_karcis == 4500) {
      if ($totalObat <= 2000) {
         pg_query("delete from rs00005 where reg = '".$_GET[rg]."' and layanan = 99995");
      }

   } elseif ($cek_karcis == 9000) {
      if ($totalObat <= 4000) {
         pg_query("delete from rs00005 where reg = '".$_GET[rg]."' and layanan = 99995");
      }
   }
}elseif ($_GET[tbl] == "konsultasi") {
$cek=getFromTable("select user_id from c_visit where oid = ".$_GET["oid"]. "");
if ($cek == ''){
$SQL = "delete from c_visit where ".
           "oid = ".$_GET["oid"];
    }else{
    $SQL = "update c_visit set  id_konsul='' where ".
           "oid = ".$_GET["oid"];
    }
}

pg_query($con, $SQL);
if ($_GET[tbl] == "konsultasi") {
header("Location: ../index2.php?p=$PID&list=konsultasi&rg=".$_GET["rg"]."&poli=102&mr=".$_GET["mr"]."$add");
}else{
header("Location: ../index2.php?p=$PID&list=layanan&rg=".$_GET["rg"]."&poli=102&mr=".$_GET["mr"]."$add");
}
exit;

?>
