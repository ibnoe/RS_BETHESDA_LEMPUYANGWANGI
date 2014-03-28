<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004

$PID = "817";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb = New UpdateQuery();
$qb->HttpAction = "POST";
$qb->TableName = "rs00038";
$qb->VarPrefix = "f_";
$qb->addPrimaryKey("id", "'" . $_POST["id"] . "'");
$SQL = $qb->build();

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID".
                              "&mUNSUR=".$_POST["u"].
                              "&mJENJANG=".$_POST["j"]);

exit;

?>
