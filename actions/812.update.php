<?php // Pur, 07/04/2004

session_start();

$PID = "812";
$PID2="Kelompok Sumber Pendapatan";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb = New UpdateQuery();
$qb->HttpAction = "POST";
$qb->TableName = "rs00021";
$qb->VarPrefix = "f_";
$qb->addPrimaryKey("id", "'" . $_POST["id"] . "'");
$SQL = $qb->build();
pg_query($con, $SQL);
//========== hystory user
$SQL2 = "insert into history_user " .
            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
            "values".
            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'$PID2','SysAdmin -> Kelompok Sumber Pendapatan','Mengubah Sum. Pendapatan ".$_POST["f_jasa_medis"]."','".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."')";
    pg_query($con, $SQL2);
//======================
header("Location: ../index2.php?p=$PID");
exit;

?>
