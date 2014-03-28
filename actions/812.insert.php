<?php // Pur, 07/04/2004
session_start();

$PID = "812";
$PID2="Kelompok Sumber Pendapatan";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb = New InsertQuery();
$qb->TableName = "rs00021";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->addFieldValue("id", "nextval('rs00021_seq')");
$SQL = $qb->build();

//========== hystory user
$SQL2 = "insert into history_user " .
            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
            "values".
            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'$PID2','SysAdmin -> Kelompok Sumber Pendapatan','Menambah Sum. Pendapatan ".$_POST["f_jasa_medis"]."','".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."')";
    pg_query($con, $SQL2);
//======================
pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID");
exit;

?>
