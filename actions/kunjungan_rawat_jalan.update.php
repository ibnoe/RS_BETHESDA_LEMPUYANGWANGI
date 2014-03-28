<?php

$PID = "input_kunjungan_rawat_jalan";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb = New UpdateQuery();
$qb->HttpAction = "POST";
$qb->TableName = "rl100003";
$qb->VarPrefix = "f_";

/*$qb->VarTypeIsDate = Array("tgl_lahir");
$qb->addPrimaryKey("mr_no", "'" . $_POST["mr_no"] . "'");
*/

$qb->addPrimaryKey("no", "'" . $_POST["no"] . "'");
$SQL = $qb->build();

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID");
exit;

?>
