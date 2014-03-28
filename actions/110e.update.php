<?php // Nugraha, 14/02/2004

$PID = "120&registered=Y&q=search&search=a";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
 
$thnini = date("Y", time());
if ($_POST[f_umur] == "") {

   $_POST[f_umur] = $thnini - $_POST[f_tgl_lahirY];
} else {
  // $_POST[f_tgl_lahirD] = 1;
  // $_POST[f_tgl_lahirM] = 1;
   $_POST[f_tgl_lahirY] = $thnini - $_POST[f_umur];

}
 
$qb = New UpdateQuery();
$qb->HttpAction = "POST";
$qb->TableName = "rs00002";
$qb->VarPrefix = "f_";
$qb->VarTypeIsDate = Array("tgl_lahir");
$qb->addPrimaryKey("mr_no", "'" . $_POST["mr_no"] . "'");
$SQL = $qb->build();


 pg_query($con, $SQL);

 header("Location: ../index2.php?p=$PID");
 exit;

?>
