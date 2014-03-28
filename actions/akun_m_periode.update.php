<?php 

// Wildan ST. 18 Feb 2014

$PID = "akun_m_periode";

require_once("../lib/dbconn.php");

$SQL = "update triwulan set ket_triwulan = '".$_POST["f_ket_triwulan"]."',bln_awal = '".$_POST["f_bln_awal"]."',bln_akhir = '".$_POST["f_bln_akhir"]."',keterangan = '".$_POST["f_keterangan"]."' ".
       "where kode = '".$_POST["kode"]."'";

pg_query($con, $SQL);

$PID2 = "Setting Periode";
$SQL2 = "insert into history_user " .
        "(id_history, tanggal_entry,waktu_entry,trans_form,item_id,keterangan,user_id,nama_user) ".
        "values".
        "(nextval('rshistory_user_seq'),CURRENT_DATE,CURRENT_TIME,'$PID2','Akuntansi -> $PID2','Mengubah Periode ".$_POST["f_ket_triwulan"]." ','".$_SESSION["uid"]."','".$_SESSION["nama_usr"]."')";
		
pg_query($con, $SQL2);	

header("Location: ../index2.php?p=$PID");
exit;

?>