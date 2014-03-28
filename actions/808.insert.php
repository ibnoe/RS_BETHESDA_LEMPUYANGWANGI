<?php // Nugraha, 14/02/2004

$PID = "808";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

 
$qb = New InsertQuery();
$qb->HttpAction = "POST";
$qb->TableName = "rs00019";
$qb->VarPrefix = "f_";

$qb->addFieldValue("type"		, "'-'");
$qb->addFieldValue("inclusive"		, "'-'");
$qb->addFieldValue("exclusive"		, "'-'");
$qb->addFieldValue("notes"		, "'-'");
$qb->addFieldValue("std_code"		, "'-'");
//$qb->addFieldValue("sub_level"		, "'0'");
$qb->addFieldValue("remarks"		, "'-'");
$qb->addFieldValue("extra_codes"	, "'-'");
$qb->addFieldValue("extra_subclass"	, "'-'");

$SQL = $qb->build();
// echo $SQL ; 

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID");
exit;

?>
