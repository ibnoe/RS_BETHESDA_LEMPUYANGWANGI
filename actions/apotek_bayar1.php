<?php 

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
	  $PID1 = "320RJ_IGD";
   } elseif ($_GET[tt] == "swd") {
      $loket = "BYS";
	  $PID1 = "320RJ_SWD";
   } elseif ($_GET[tt] == "cdm") {
      $loket = "BYC";
	  $PID1 = "320RJ_CDM";
   } else {
      $loket = "BYA";
	  $PID1 = "320RJ_ASK";
   }
        $tr->addSQL("update rs00008 set is_bayar='Y' where no_reg='".$_GET[rg]."' and trans_form like '%320RJ%' ");
		
		$SQL=("insert into rs00005 (id, reg, tgl_entry, kasir, is_obat, is_karcis, layanan, jumlah, is_bayar,user_id) 
					values (nextval('kasir_seq'), '".$_GET["rg"]."',CURRENT_DATE, '$loket', 'Y', 'N', '$PID1', ".$_GET[hrg].", 'Y','".$_SESSION["uid"]."'  ) ");
		pg_query($con,$SQL);
		
		if ($_GET[keringanan] != 0){
		$SQL2=("insert into rs00005 (id, reg, tgl_entry, kasir, is_obat, is_karcis, layanan, jumlah, is_bayar,user_id) 
					values (nextval('kasir_seq'), '".$_GET["rg"]."',CURRENT_DATE, 'POT', 'Y', 'N', '$PID1', ".$_GET[keringanan].", 'Y','".$_SESSION["uid"]."' ) ");
		pg_query($con,$SQL2);
		}

 if ($tr->execute()) {

    unset($_SESSION["obat"]);

    if ($_SESSION[gr] == "laborat" || $_SESSION[gr] == "radiologi" ) {
	header("Location: ../index2.php?p=320RJ&list=bayar&tt=".$_GET[tt]."&rg=".$_GET[rg]."&sub=".$_GET[sub]);
    } else {
        header("Location: ../index2.php?p=320RJ&list=bayar&tt=".$_GET[tt]."&rg=".$_GET[rg]."&sub=".$_GET[sub]);
    }
    exit;
} else {
    echo $tr->ErrMsg;
}

?>
