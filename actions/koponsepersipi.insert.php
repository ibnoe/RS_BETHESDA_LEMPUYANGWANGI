<?php // Nugraha, Wed Jun  2 16:19:34 WIT 2004

session_start();

$PID = "koponsepersipi";

require_once("../lib/dbconn.php");

//if (strlen($_POST["satuan1"]) && strlen($_POST["jumlah2"]) > 0) {
    $cek_kode=getFromTable("select max(kode_trans::int) from rs00016d");
	if ($cek_kode > 0){
		$kode = $cek_kode + 1;
	}else{
		$kode = 1;
	}
	
	$SQL = "insert into rs00016d " .
           "(kode_trans, kode_obat, satuan1, jumlah1,satuan2,jumlah2) ".
           "values".
           "('$kode','".$_POST["kode_obat"]."','".$_POST["satuan1"]."','".$_POST["jumlah1"]."','".$_POST["satuan2"]."','1')";
		   
    pg_query($con, $SQL);
    unset($_SESSION["SELECT_LAYANAN"]);
//}

header("Location: ../index2.php?p=$PID&e=".$_POST["kode_obat"]."&o=".$_POST["o"]."&f=kon");
exit;

?>
