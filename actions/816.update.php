<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004

$PID = "816";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$_POST[f_rincian_kegiatan_id] = (int) $_POST[f_rincian_kegiatan_id];

$qb = New UpdateQuery();
$qb->HttpAction = "POST";
$qb->TableName = "rs00026";
$qb->VarPrefix = "f_";
$qb->addPrimaryKey("id_akkm", "'" . $_POST["id"] . "'");
$SQL = $qb->build();

pg_query($con, $SQL);


header("Location: ../index2.php?p=$PID".
                              "&mUNSUR=".$_POST["u"].
                              "&mSUBUNSUR=".$_POST["s"].
                              "&mBIDANG=".$_POST["b"].
                              "&mRINCIAN=".$_POST["r"].
                              "&mJENJANG=".$_POST["v"]);
exit;

?>
