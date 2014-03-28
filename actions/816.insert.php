<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004

$PID = "816";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$_POST[f_rincian_kegiatan_id] = (int) $_POST[f_rincian_kegiatan_id];

$qb = New InsertQuery();
$qb->TableName = "rs00026";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->addFieldValue("id_akkm", "lpad(currval('rs00026_seq'),8,'0')");
$qb->addFieldValue("id", "nextval('rs00026_seq')");

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
