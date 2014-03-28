<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004
      // sfdn, 19-05-2004

$PID = "814";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb = New InsertQuery();
$qb->TableName = "rs00024";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->addFieldValue("id_bidang", "lpad(currval('rs00024_seq'),6,'0')");
$qb->addFieldValue("id", "nextval('rs00024_seq')");

$SQL = $qb->build();

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID&mUNSUR=".$_POST["unsur"]."&mSUBUNSUR=".$_POST["sub"]);

exit;

?>
