<?php // Nugraha, 22/02/2004

$PID = "848";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb = New InsertQuery();
$qb->TableName = "margin_apotik";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->addFieldValue("margin_id", "nextval('margin_apotik_seq')");
$SQL = $qb->build();

pg_query($con, $SQL);


header("Location: ../index2.php?p=$PID");
exit;

?>
