<?php // Nugraha, 18/02/2004
	 //	 Apep, 06/11/2007

$PID = "lap_pengadaan";

require_once("../lib/dbconn.php");

if (empty($_GET[sure])) {
	
        header("Location: ../index2.php?p=$PID");
	exit();

} elseif ($_GET[sure] == "YA") {

         $SQL = "delete from c_po where po_id = '".$_GET["po_id"]."'";
         $SQL2 = "delete from c_po_item where po_id = '".$_GET["po_id"]."'";
         $SQL3 = "delete from c_po_item_terima where po_id = '".$_GET["po_id"]."'";

} else {

    header("Location: ../index2.php?p=$PID");
    exit();

}



pg_query($con, $SQL);
pg_query($con, $SQL2);
pg_query($con, $SQL3);


header("Location: ../index2.php?p=$PID"); 
exit;

?>
