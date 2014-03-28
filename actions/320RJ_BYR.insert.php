<?php // Agung Sunandar

session_start();
$PID = "320RJ";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

$tokit = pg_query("select nextval('rs00008_seq_group')");
pg_query("select nextval('kasir_seq')");


$tr = new PgTrans;
$tr->PgConn = $con;


// PEMBAYARAN
if ($_GET["e"]=="byr") {

		pg_query("insert into rs00005 ".
			" values(nextval('kasir_seq'),'".$_GET[rg]."',CURRENT_DATE,'BYR','Y','N',0,$_GET[bayar],'Y')");
		
		$sql1=("update rs00006 set is_bayar = 'Y' where id = '".$_GET[rg]."' ");
		$sql2=("insert into rs00005 values(nextval('kasir_seq'),'".$_GET[rg]."',CURRENT_DATE,'POT','Y','N',0,$_GET[keringanan],'Y')");
		$sql3=("update rs00008 set is_bayar = 'Y' where no_reg = '".$_GET[rg]."' and trans_type in ('OB1','RCK')");
		pg_query($con, $sql1);	
		pg_query($con, $sql2);
		pg_query($con, $sql3);
}
if ($tr->execute()) {


    unset($_SESSION["layanan"]);
    unset($_SESSION["s2note"]);
    unset($_SESSION["icd"]);
    unset($_SESSION["obat"]);
	unset($_SESSION["racikan"]);

    if ($_SESSION[gr] == "laborat" || $_SESSION[gr] == "radiologi" ) {
	header("Location: ../index2.php?p=320RJ&rg=".$_GET[rg]."&sub=".$_GET[sub]);

    } else {
        header("Location: ../index2.php?p=320RJ&tt=".$_GET[tt]."&rg=".$_GET[rg]."&sub=".$_GET[sub]);
	//header("Location: ../index2.php?p=dialog");
    }
    
    exit;
} else {
    echo $tr->ErrMsg;
}

?>
