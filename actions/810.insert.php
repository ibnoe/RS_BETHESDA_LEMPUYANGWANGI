<?php // Nugraha, 22/02/2004

$PID = "810";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb = New InsertQuery();
$qb->TableName = "rs00018";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->addFieldValue("id", "nextval('rs00018_seq')");
$SQL = $qb->build();

pg_query($con, $SQL);


header("Location: ../index2.php?p=$PID");
exit;

?>
