<?php // Wildan ST. 18 Feb 2014, Penyimpanan ke tabel jurnal umum master

session_start();
$PID = "subledger";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

pg_query("select nextval('jurnal_umum_seq')");

$ro = pg_query($con, "select currval('jurnal_umum_seq') as mr_no");
$do = pg_fetch_object($ro);
pg_free_result($ro);
$id = $do->mr_no;

$tanggal = $_POST["tanggal"];
$no_faktur=$_POST["no_faktur"];
$keterangan=$_POST["keterangan"];


$SQL1="insert into jurnal_umum_m (id,tanggal,no_faktur,keterangan,jns_akun) values ($id,'".$tanggal."','".$no_faktur."','".$keterangan."','SUB')";
pg_query($con, $SQL1);


header("Location: ../index2.php?p=$PID&id=$id");
exit;
?>
