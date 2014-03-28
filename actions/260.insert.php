<?php

$PID = "260";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb = New InsertQuery();
$qb->TableName = "rs00043";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";

/*$qb->VarTypeIsDate = Array("tgl_lahir");*/

$qb->addFieldValue("id", "lpad(nextval('rs00043_seq'),10,'0')");
$SQL = $qb->build();
$k = $_POST["f_kode_menu"];
pg_query($con, $SQL);
header("Location: ../index2.php?p=$PID&mMENU=$k");

exit;

?>
