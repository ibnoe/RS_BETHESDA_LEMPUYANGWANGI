<?php // Nugraha, 22/02/2004

$PID = "510";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb = New InsertQuery();
$qb->TableName = "rs00031";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->VarTypeIsDate = Array("tanggal");
$qb->addFieldValue("id", "nextval('rs00031_seq')");
$SQL = $qb->build();

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID");
exit;

?>
