<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004

$PID = "hrd_status";

require_once("../lib/dbconn.php");

$SQL = "delete from hrd_status where ".
       "code = '".$_GET["code"]."'";

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID");
exit;

?>
