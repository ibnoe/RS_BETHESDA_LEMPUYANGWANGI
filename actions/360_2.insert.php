<?php // Agung Sunandar

session_start();
$PID = "360_2";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

$kode_trans=getFromTable("select kode_trans from c_po_item where item_id='".$_GET["item_id"]."' and po_id='".$_GET["poid"]."'");
$qty = getFromTable("select jumlah1 from rs00016d where kode_trans = '$kode_trans' ");
$jum = $qty * $_GET["qty"];

$SQL = "update rs00016a set qty_ri = qty_ri + $jum where obat_id = '".$_GET["item_id"]."' ";

$SQL1 = "update rs00015 set batch ='".$_GET["batch"]."', expire='".$_GET["expire"]."' where id = '".$_GET["item_id"]."'";	   

$SQL3 = "update c_po_item set po_status = 1
		where item_id = '".$_GET["item_id"]."' and po_id='".$_GET["poid"]."'";	

$SQL4 = "update rs00016 set qty_terima = $jum, tanggal_entry = CURRENT_DATE 
		where obat_id::text = '".$_GET["item_id"]."'";
    		
pg_query($con, $SQL);
pg_query($con, $SQL1);
pg_query($con, $SQL3);
pg_query($con, $SQL4);	

$tot=getFromTable("select Sum(item_qty) from c_po_item where po_id='".$_GET["poid"]."' and po_status=0");
if ($tot==0) 
	{
	$SQL4 = "update c_po set po_status =1 ".
       "where po_id='".$_GET["poid"]."'";
	pg_query($con, $SQL4);
	
	}
	
header("Location: ../index2.php?p=$PID");
exit;

?>
