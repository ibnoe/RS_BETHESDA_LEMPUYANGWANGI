<?php 
// najla 23032011	di pengungsian
session_start();
$PID = "akun_pengeluaran";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");
require_once("../lib/querybuilder.php");

pg_query($con,"select nextval('kas_keluar_seq')");
pg_query($con,"select nextval('jurnal_umum_seq')");

$qb = New InsertQuery();
$qb->TableName = "kas_keluar";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->addFieldValue("id", "currval('kas_keluar_seq')");
$SQL = $qb->build();

$sql2="insert into jurnal_umum (id,tanggal_akun,no_akun,keterangan,debet,kredit,user_id,nm_kasir,kasir_type) values (nextval('jurnal_umum_seq'),CURRENT_DATE,'".$_SESSION["AKUN_L$level"]["kode"]."','".$_POST['f_keterangan']."','".$_POST[f_jumlah]."',0,'".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."','akun_pengeluaran')";
$sql3="insert into jurnal_umum (id,tanggal_akun,no_akun,keterangan,debet,kredit,user_id,nm_kasir,kasir_type) values (nextval('jurnal_umum_seq'),CURRENT_DATE,'1101.02','Kas Kecil',0,'".$_POST[f_jumlah]."','".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."','akun_pengeluaran')";

//echo $sql2."<br>".$sql3;
pg_query($con, $SQL);
pg_query($con, $sql2);
pg_query($con, $sql3);
/*
	$SQL2 = "insert into history_user " .
            "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id) ".
            "values".
            "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'$PID','Keuangan -> Cash Out','Pengambilan uang kas untuk pembayaran','".$_SESSION["uid"]."')";
    pg_query($con, $SQL2);
*/
 

unset($_SESSION["AKUN_L$level"]["kode"]);
unset($_SESSION["AKUN_L$level"]["nama"]);
header("Location: ../index2.php?p=$PID");exit;

?>
