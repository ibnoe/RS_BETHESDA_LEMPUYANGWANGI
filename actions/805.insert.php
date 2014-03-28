<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004
      // sfdn, 19-05-2004

$PID = "805";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb = New InsertQuery();
$qb->TableName = "rs00027";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->addFieldValue("id", "nextval('rs00013_seq')");
$SQL = $qb->build();

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID".
                              "&mJENJANG=".$_POST["j"]);

exit;

?>
