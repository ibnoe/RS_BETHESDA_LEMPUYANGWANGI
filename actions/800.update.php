<?php // Nugraha, 18/02/2004

$PID = "800";

require_once("../lib/dbconn.php");
if ($_POST["comment"]== null){
$comment=0;
}else{
$comment=$_POST["comment"];
}

if ($_POST["tc_poli"]== null){
$tc_poli=0;
}else{
$tc_poli=$_POST["tc_poli"];
}

if ($_POST["tt"]=="GOB"){
$SQL = "update rs00001 set tdesc = '".$_POST["tdesc"]."',comment='$comment',tc_poli=$tc_poli ".
       "where tt = '".$_POST["tt"]."' ".
       "and tc = '".$_POST["tc"]."'";
}else{
$SQL = "update rs00001 set tdesc = '".$_POST["tdesc"]."' ".
       "where tt = '".$_POST["tt"]."' ".
       "and tc = '".$_POST["tc"]."'";
}
pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID&tt=".$_POST["tt"]);
exit;

?>