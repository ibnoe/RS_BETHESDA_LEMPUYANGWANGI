<?php // Nugraha, Thu Apr 22 11:58:22 WIT 2004
      // sfdn, 23-04-2004: tambah harga obat
      // sfdn, 09-05-2004
	  // sfdn, 31-05-2004

session_start();
$PID = $_GET["p"];

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

$tokit = pg_query("select nextval('rs00008_seq_group')");
pg_query("select nextval('kasir_seq')");

$tr = new PgTrans;
$tr->PgConn = $con;
if ($_GET[tt] == "igd") {
      $loket = "BYG";
	  $PID1 = "320RJ_IGDU";
   } elseif ($_GET[tt] == "swd") {
      $loket = "BYS";
	  $PID1 = "320RJ_SWDU";
   } elseif ($_GET[tt] == "cdm") {
      $loket = "BYC";
	  $PID1 = "320RJ_CDMU";
   } else {
      $loket = "ASK";
	  $PID1 = "320RJ_ASKU";
   }
        $tr->addSQL("update rs00008 set is_bayar='Y' where no_reg='".$_GET[rg]."'");
		
		$SQL=("insert into rs00005 (id, reg, tgl_entry, kasir, is_obat, is_karcis, layanan, jumlah, is_bayar,user_id) 
					values (nextval('kasir_seq'), '".$_GET["rg"]."',CURRENT_DATE, '$loket', 'Y', 'N', '$PID1', ".$_GET[hrg].", 'Y','".$_SESSION["uid"]."' ) ");
		pg_query($con,$SQL);
		
		if ($_GET[keringanan] != 0){
		$SQL2=("insert into rs00005 (id, reg, tgl_entry, kasir, is_obat, is_karcis, layanan, jumlah, is_bayar,user_id) 
					values (nextval('kasir_seq'), '".$_GET["rg"]."',CURRENT_DATE, 'POT', 'Y', 'N', '$PID1', ".$_GET[keringanan].", 'Y','".$_SESSION["uid"]."' ) ");
		pg_query($con,$SQL2);
		}


 if ($tr->execute()) {

    unset($_SESSION["obat"]);

    if ($_SESSION[gr] == "laborat" || $_SESSION[gr] == "radiologi" ) {
	header("Location: ../index2.php?p=$PID&list=bayar&tt=".$_GET[tt]."&rg=".$_GET[rg]."&sub=".$_GET[sub]);
    } else {
        header("Location: ../index2.php?p=$PID&list=bayar&tt=".$_GET[tt]."&rg=".$_GET[rg]."&sub=".$_GET[sub]);
    }
    exit;
} else {
    echo $tr->ErrMsg;
} 

?>
