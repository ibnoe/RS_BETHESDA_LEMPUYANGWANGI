<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004


$PID = $_GET["p"];

require_once("../lib/dbconn.php");


         $SQL = "delete from rs00008 where oid = '".$_GET["id"]."' ";

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID&sub=icd9&list=icd9&mr=".$_GET["mr"]."&ri=".$_GET["poli"]."&rg=".$_GET["rg"]."&rg1=".$_GET["rg"]."");
exit;

?>
