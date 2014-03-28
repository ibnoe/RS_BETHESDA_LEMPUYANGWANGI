<?php // Nugraha, 22/02/2004
session_start();

$PID = "811_2";
$PID2 = "%Pembagian sum. pendapatan";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

$qb = New InsertQuery();
$qb->TableName = "rs00020";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->addFieldValue("id", "nextval('rs00020_seq')");
$SQL = $qb->build();
//========== hystory user
$SQL2 = "insert into history_user " .
            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
            "values".
            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'$PID2','SysAdmin -> %Pembagian Sumber Pendapatan','Menambah %Pemg. Sum. Pendapatan ','".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."')";
    pg_query($con, $SQL2);
//======================
pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID&unit_medis_id=$_POST[unit_medis_id]");
exit;

?>