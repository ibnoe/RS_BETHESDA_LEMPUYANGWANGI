<?php // Nugraha, 14/02/2004

$PID = "809";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$rs00027_id = getFromTable("select id from rs00027 where jjd_id='".$_POST[f_jjd_id]."' and gol_ruang_id='".$_POST[f_gol_ruang_id]."'");
if (empty($rs00027_id)) {
   $rs00027_id = 0;
}

$qb = New InsertQuery();
$qb->TableName = "rs00017";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->VarTypeIsDate = Array("tanggal_lahir");
$qb->addFieldValue("id", "nextval('rs00017_seq')");
$qb->addFieldValue("rs00027_id", "$rs00027_id");

$SQL = $qb->build();

pg_query($con, $SQL);

header("Location: ../index2.php".
        "?p=$PID".
        "&mPEG=" . $_POST["mPEG"] .
        "&mJAB=" . $_POST["mJAB"]);
exit;

?>
