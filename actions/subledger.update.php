<?php 
// Wildan ST. 18 Feb 2014
session_start();
$PID = "subledger_peny";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

if ($_POST["ket"]=="Debet"){
pg_query("update jurnal_umum set keterangan='".$_POST["keterangan"]."',ket='".$_POST["ket"]."',debet=".$_POST["jumlah"].",kredit=0  where id= '".$_POST["id"]."' and no_akun='".$_POST["no_akun"]."' ");
}else{
    pg_query("update jurnal_umum set keterangan='".$_POST["keterangan"]."',ket='".$_POST["ket"]."',debet=0,kredit=".$_POST["jumlah"]." where id= '".$_POST["id"]."' and no_akun='".$_POST["no_akun"]."' ");
}
header("Location: ../index2.php?p=$PID&id=$_POST[id]&edit=view");
exit;

?>
