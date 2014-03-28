<?php // Nugraha, 18/02/2004
      // Pur, 27/02/2004

$PID = "808";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb = New UpdateQuery();
$qb->HttpAction = "POST";
$qb->TableName = "rs00019";
$qb->VarPrefix = "f_";
$qb->addPrimaryKey("diagnosis_code", "'" . $_POST["diagnosis_code"] . "'");
$SQL = $qb->build();

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID");
exit;

?>
