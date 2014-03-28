<?php // Nugraha, 18/02/2004

session_start();

//$PID = "320";

require_once("../lib/dbconn.php");




if ($_GET[tbl] == "retur") {
	$jml = getFromTable("select tagihan from rs00008 where id = ".$_GET["del"]." ");
	$jmlx= 0 - $jml;
	
    $SQL = "delete from rs00008 where ".
    	   "id = ".$_GET["del"];

    $obat_id = getFromTable("select item_id from rs00008 where id=".$_GET[del]);
    
	if ($_GET[tt] == "igd") {
      $loket = "IGD";
	  $PID1 = "320RJ_IGD";
   } elseif ($_GET[tt] == "swd") {
      $loket = "RJL";
	  $PID1 = "320RJ_SWD";
   } elseif ($_GET[tt] == "cdm") {
      $loket = "CDM";
	  $PID1 = "320RJ_CDM";
   } else {
      $loket = "ASK";
	  $PID1 = "320RJ_ASK";
   }
   
    pg_query("insert into rs00005 (id, reg, tgl_entry, kasir, is_obat, ".
	    "is_karcis, layanan, jumlah, is_bayar,user_id) ".
	    "values (nextval('kasir_seq'), '".$_GET[rg]."', CURRENT_DATE, '$loket', 'Y', 'N', '$PID1', $jmlx, 'N','".$_SESSION["uid"]."') "); 

    if ($_GET[tt] == "igd") {
    pg_query("update rs00016a set qty_interne = qty_interne + ".$_GET[qty].
	    "where obat_id =$obat_id");
    } elseif ($_GET[tt] == "swd") {
    pg_query("update rs00016a set qty_ri = qty_ri + ".$_GET[qty].
	    "where obat_id =$obat_id");
    } elseif ($_GET[tt] == "cdm") {
    pg_query("update rs00016a set qty_jiwa = qty_jiwa + ".$_GET[qty].
	    "where obat_id =$obat_id");
    } elseif ($_GET[tt] == "ask") {
    pg_query("update rs00016a set qty_kebid = qty_kebid + ".$_GET[qty].
	    "where obat_id =$obat_id");
    }

}



pg_query($con, $SQL);


header("Location: ../index2.php?p=".$_GET["pid"]."&tt=".$_GET["tt"]."&rg=".$_GET["rg"]."&sub=".$_GET["sub"]."");
exit;

?>
