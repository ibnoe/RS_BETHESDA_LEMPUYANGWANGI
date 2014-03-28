<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004


$PID = "p_jenazah2";

require_once("../lib/dbconn.php");
$no_reg=$_GET['no_reg'];
pg_query("DELETE FROM jenazah  WHERE no_reg='".$no_reg."'");

header("Location: ../index2.php?p=$PID");
exit;


?>
