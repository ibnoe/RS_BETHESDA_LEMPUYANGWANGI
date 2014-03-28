<?php 

/******************
   REGISTRASI MR
******************/

$PID = "rm_daftar_bayi";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
/*
if ($_POST[f_nama] == "") {
   header("Location: ../index2.php?p=120&registered=N");
   exit();
}
*/

$thnini = date("Y", time());
if ($_POST[f_umur] == "") {

   $_POST[f_umur] = $thnini - $_POST[f_tgl_lahirY];
} else {
   $_POST[f_tgl_lahirD] = 1;
   $_POST[f_tgl_lahirM] = 1;
   $_POST[f_tgl_lahirY] = $thnini - $_POST[f_umur];

}

//echo "xxx: ".$_POST[f_umur];
//exit();

$cetak_kartu = $_POST[cek_printer] ;

$qb = New InsertQuery();
$qb->TableName = "rs00002";
$qb->HttpAction = "POST";
$qb->VarPrefix = "f_";
$qb->VarTypeIsDate = Array("tgl_lahir");
$qb->addFieldValue("mr_no", "lpad(nextval('rs00002_seq'),6,'0')");
$qb->addFieldValue("is_bayi", "'Y'");
$SQL = $qb->build();

pg_query($con, $SQL);


if ($_POST["p"] == "120") {
    $r = pg_query($con, "select lpad(currval('rs00002_seq'),6,'0') as mr_no");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    header("Location: ../index2.php?p=120&q=reg&mr_no=$d->mr_no&cetak=$cetak_kartu");
} else {
    header("Location: ../index2.php?p=rm_bayi_edit");
}
exit;

?>
