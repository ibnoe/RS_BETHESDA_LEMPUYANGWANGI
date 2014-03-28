<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004
	  // sfdn, 06-06-2004

$PID = "265";

require_once("../lib/dbconn.php");

$SQL = "delete from rs00043 where ".
       "id = '".$_POST["id"]."'";

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID".
					"&mSAJI=".$_POST["s"].
					"&mKELAS=".$_POST["k"].
					"&mWAKTU=".$_POST["w"]);
exit;

?>
