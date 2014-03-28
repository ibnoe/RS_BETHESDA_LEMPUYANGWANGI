<?php // Nugraha, 18/02/2004

session_start();

$PID = "apotik_umum";

require_once("../lib/dbconn.php");




if ($_GET[tbl] == "retur") {


    $SQL = "delete from rs00008 where ".
    	   "id = ".$_GET["del"];

    $obat_id = getFromTable("select item_id from rs00008 where id=".$_GET[del]);
    

    /* pg_query("insert into rs00005 (id, reg, tgl_entry, kasir, is_obat, ".
	    "is_karcis, layanan, jumlah, is_bayar) ".
	    "values (nextval('kasir_seq'), '".$_GET[rg]."', CURRENT_DATE, '$kasir', 'Y', 'N', 90000, $jmlx, 'N') "); */

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


header("Location: ../index2.php?p=apotik_umum&list=resepobat&rg=".$_GET["rg"]."&tt=".$_GET["tt"]."&sub=".$_GET["sub"]."");
exit;

?>
