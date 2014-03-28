<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004

$PID = "803";

require_once("../lib/dbconn.php");

$SQL = "delete from rs00010 where ".
       "id = '".$_GET["id"]."'";

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID");
exit;

?>
