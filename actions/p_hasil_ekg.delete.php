<?php 

session_start();

$PID = "p_hasil_ekg";

require_once("../lib/dbconn.php");

$_GET["rg"] = $_GET[rg];
$rg = isset($_GET["rg"])? $_GET["rg"] : $_POST["rg"];
$mr = isset($_GET["mr"])? $_GET["mr"] : $_POST["mr"];
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
    pg_query( 	"insert into rs00005 (id, reg, tgl_entry, kasir, is_obat, ".
	    		"is_karcis, layanan, jumlah, is_bayar) ".
	    		"values (nextval('kasir_seq'), '".$_GET[rg]."', CURRENT_DATE, '$kasir', 'N', 'N', $lyn, $jmlx, 'N') ");

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
}

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID&list=layanan&rg1=".$_GET["rg1"]."&rg=".$_GET["rg"]."&ri=".$_GET["ri"]."&mr=".$_GET["mr"]."$add");
exit;

?>
