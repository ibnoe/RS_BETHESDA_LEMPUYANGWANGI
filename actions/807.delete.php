<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004

$PID = "807";

require_once("../lib/dbconn.php");

$SQL = "delete from rs00015 where ".
       "id = '".$_GET["e"]."' ";

//$SQL2 = "delete from rs00016 where ";

pg_query($con, $SQL);
//pg_query($con, $SQL2);

header("Location: ../index2.php?p=$PID&mOBT=".$_GET[o]."&search=".$_GET[search]."&sort=".$_GET[sort].
	"&order=".$_GET[order]."&tblstart=".$_GET[tblstart]);
exit;

?>
