<?php // Nugraha, 18/02/2004

session_start();

//$PID = "320";

require_once("../lib/dbconn.php");




if ($_GET[tbl] == "retur") {


    $SQL = "delete from rs00008 where ".
    	   "id = ".$_GET["del"];

    $jml = getFromTable("select (qty * harga) as jumlah from rs00008 where id=".$_GET[del]);
    $jmlx = 0 - $jml;

    //pg_query("insert into rs00005 (id, reg, tgl_entry, kasir, is_obat, ".
	//    "is_karcis, layanan, jumlah, is_bayar) ".
	//    "values (nextval('kasir_seq'), '".$_GET[rg]."', CURRENT_DATE, '$kasir', 'Y', 'N', 90000, $jmlx, 'N') ");

    /*if ($_SESSION[uid] == "apotek rj") {
    pg_query("update rs00016a set qty_rj = qty_rj - ".$_GET[qty].
	    "where obat_id = ".$_GET[id]);
    } elseif ($_SESSION[uid] == "apotek ri") {
    pg_query("update rs00016a set qty_ri = qty_ri - ".$_GET[qty].
	    "where obat_id = ".$_GET[id]);
    }*/

}



pg_query($con, $SQL);


header("Location: ../index2.php?p=".$_GET["pid"]."&list=resepobat&rg=".$_GET["rg"]."&sub=retur");
exit;

?>
