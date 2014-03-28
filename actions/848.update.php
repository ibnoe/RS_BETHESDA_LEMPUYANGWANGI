<?php // Nugraha, 22/02/2004

$PID = "848";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb = New UpdateQuery();
$qb->HttpAction = "POST";
$qb->TableName = "margin_apotik";
$qb->VarPrefix = "f_";
$qb->addPrimaryKey("margin_id", "'" . $_POST["margin_id"] . "'");
$SQL = $qb->build();
pg_query($con, $SQL);


header("Location: ../index2.php?p=$PID");
exit;

?>
