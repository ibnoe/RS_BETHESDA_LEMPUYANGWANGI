<?php  // Nugraha, Sat Apr 24 16:39:35 WIT 2004

session_start();
$PID = "input_laundry";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");




$r = pg_query($con, "select nextval('laundry_c_seq') as no_reg");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);

$id=$d->no_reg;

$post = $_POST["action"];

if ($post=="tambah"){
pg_query($con,"INSERT INTO laundry_c (id_ruang, petugas, tanggal, id) VALUES('".$_POST["f_id_ruang"]."',
'".$_POST["f_petugas"]."','".$_POST["f_tanggal"]."',$id)");
header("Location: ../index2.php?p=$PID&action=tambah2&id=$id");
}

if ($post=="tambah2"){
$jumlah=$_POST["jumlah"];

$id_laundry=$_POST["id_laundry"];
$i=1;

while($i<=$jumlah){


$r = pg_query($con, "select nextval('laundry_item_seq') as no_reg");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);

$id=$d->no_reg;
$linen="id_linen".$i;
$jumlah_linen="jumlah_linen".$i;
$id_linen=$_POST[$linen];
$jumlahnya=$_POST[$jumlah_linen];
pg_query($con,"INSERT INTO laundry_item(id, id_linen, id_laundry, jumlah) VALUES($id,$id_linen,$id_laundry,$jumlahnya)");
$i=$i+1;
//echo "INSERT INTO laundry_item(id, id_linen, id_laundry, jumlah) VALUES($id,$linen,$id_laundry,$jumlahnya)";
}
header("Location: ../index2.php?p=$PID");
}

if ($post=="selesai"){
$id_laundry=$_POST["id_laundry"];
$status=$_POST["update"];
pg_query($con,"update laundry_c set status=$status where id=$id_laundry ");
header("Location: ../index2.php?p=$PID");
}
exit;
?>
