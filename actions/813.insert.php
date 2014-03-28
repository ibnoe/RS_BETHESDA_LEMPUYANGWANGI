<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004
      // sfdn, 19-05-2004

$PID = "813";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb = New InsertQuery();
$qb->TableName = "rs00023";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->addFieldValue("id_kegiatan", "lpad(nextval('rs00023_seq'),4,'0')");
$SQL = $qb->build();

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID");
exit;

?>
