<?php // efrizal, 07/01/2011

$PID = "kasir_karcis";

require_once("../lib/dbconn.php");

$SQL = "delete from kasir_karcis where ".
       "id = ".$_GET["id"]."";

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID");
exit;

?>
