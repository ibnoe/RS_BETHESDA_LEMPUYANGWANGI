<?php // Nugraha, 18/02/2004

session_start();

$PID = "335";

require_once("../lib/dbconn.php");


if ($_SESSION[uid] == "igd") {
    $kasir = "IGD";
    $lyn = 10;

} elseif ($_SESSION[uid] == "kasir1") {
    $kasir = "RJL";
    $lyn = getFromTable("select layanan from rs00005 where reg='".$_GET[rg]."' and layanan not in (99997,99998,99999)");

//echo "lyn: $lyn"; exit();

} else {
    $kasir = "RIN";    
    $lyn = 0;
}

if ($_GET[tbl] == "bayar") {
    $SQL = "delete from rs00005 where ".
    	   "id = ".$_GET["del"];

} elseif ($_GET[tbl] == "tindakan") {
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
    pg_query("insert into rs00005 (id, reg, tgl_entry, kasir, is_obat, ".
	    "is_karcis, layanan, jumlah, is_bayar) ".
	    "values (nextval('kasir_seq'), '".$_GET[rg]."', CURRENT_DATE, '$kasir', 'N', 'N', $lyn, $jmlx, 'N') ");

} elseif ($_GET[tbl] == "obat1") {
    $SQL = "delete from rs00008 where ".
    	   "id = ".$_GET["del"];

    $jml = getFromTable("select (qty * harga) as jumlah from rs00008 where id=".$_GET[del]);
    $jmlx = 0 - $jml;

    pg_query("insert into rs00005 (id, reg, tgl_entry, kasir, is_obat, ".
	    "is_karcis, layanan, jumlah, is_bayar) ".
	    "values (nextval('kasir_seq'), '".$_GET[rg]."', CURRENT_DATE, '$kasir', 'Y', 'N', 99997, $jmlx, 'N') ");

}



pg_query($con, $SQL);


header("Location: ../index2.php?p=$PID&sub=3&rg=".$_GET["rg"]);
exit;

?>