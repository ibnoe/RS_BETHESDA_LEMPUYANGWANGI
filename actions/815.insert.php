<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004

$PID = "815";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb = New InsertQuery();
$qb->TableName = "rs00025";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->addFieldValue("id_rincian", "lpad(currval('rs00025_seq'),8,'0')");
$qb->addFieldValue("id", "nextval('rs00025_seq')");

$SQL = $qb->build();

pg_query($con, $SQL);
header("Location: ../index2.php?p=$PID".
                              "&mUNSUR=".$_POST["u"].
                              "&mSUBUNSUR=".$_POST["s"].
                              "&mBIDANG=".$_POST["b"]);
exit;

?>
