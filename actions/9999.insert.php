<?php 
// Rizki, NOV 08 14:09:04 WIB 2012
$PID = "9999";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

//Query Insert Start
$qb = New InsertQuery();
$qb->HttpAction = "POST";
$qb->TableName = "rs000199";
$qb->VarPrefix = "f_";
$qb->addFieldValue("id", "nextval('icd_9_seq')");

$SQL = $qb->build();
pg_query($con, $SQL);
//Query Insert End

header("Location: ../index2.php?p=$PID");
exit;

?>
