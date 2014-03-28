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
$SQL = "insert into rs00001 (tt, tc, tdesc,comment,tc_poli) ".
       "values ('".$_POST["tt"]."','".$_POST["tc"]."','".$_POST["tdesc"]."','$comment',$tc_poli)";
}else{
$SQL = "insert into rs00001 (tt, tc, tdesc) ".
       "values ('".$_POST["tt"]."','".$_POST["tc"]."','".$_POST["tdesc"]."')";
}
@$err = pg_query($con, $SQL);

if($err == false) {
    header("Location: ../index2.php?p=$PID&tt=".$_POST["tt"].
        "&tc=".$_POST["tc"]."&tdesc=".$_POST["tdesc"]."&err=".
        urlencode(pg_last_error($con))."&e=new");
    exit;
} else {
    header("Location: ../index2.php?p=$PID&tt=".$_POST["tt"]);
    exit;
}

?>