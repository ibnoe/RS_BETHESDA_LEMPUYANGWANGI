<?php // Nugraha, 18/02/2004
      // Pur, 27/02/2004

$PID = "hrd_status";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb = New UpdateQuery();
$qb->HttpAction = "POST";
$qb->TableName = "hrd_status";
$qb->VarPrefix = "f_";
$qb->addPrimaryKey("code", "'" . $_POST["code"] . "'");
$SQL = $qb->build();

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID");
exit;

?>
