<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004

$PID = "813";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb = New UpdateQuery();
$qb->HttpAction = "POST";
$qb->TableName = "rs00023";
$qb->VarPrefix = "f_";
$qb->addPrimaryKey("id_kegiatan", "'" . $_POST["id"] . "'");
$SQL = $qb->build();

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID");
exit;

?>
