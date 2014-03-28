<?php // Nugraha, Wed Jun  2 16:31:13 WIT 2004

session_start();
$PID = "999";
//echo"poli=".$_POST["nmpoli"];exit;

require_once("../lib/visit_setting.php");
require_once("../lib/dbconn.php");


$r1 = pg_query($con,
    "select rs99997.item_id, rs99997.qty, layanan, harga, harga_atas, harga_bawah, tdesc " .
    "from rs99997 " .
    "left join rs00034 on rs99997.item_id = rs00034.id " .
    "left join rs00001 on satuan_id = tc and tt = 'SAT'".
    "     ".
    "where preset_id = '".$_POST["preset"]."'");


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
$_SESSION["LAST_PRESET"] = $_POST["preset"];

	header("Location: ../index2.php?p=p_laboratorium&list=layanan&rg=".$_POST["rg"]."&mr=".$_POST["mr"]);
	exit;	


?>