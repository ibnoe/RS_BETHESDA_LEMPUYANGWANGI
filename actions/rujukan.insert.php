<?php

$PID = "input_rujukan";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb = New InsertQuery();
$qb->TableName = "rl100024";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";

/*$qb->VarTypeIsDate = Array("tgl_lahir");*/

$qb->addFieldValue("id", "nextval('rl100024_seq')");
$SQL = $qb->build();

pg_query($con, $SQL);
header("Location: ../index2.php?p=$PID");

exit;

?>
