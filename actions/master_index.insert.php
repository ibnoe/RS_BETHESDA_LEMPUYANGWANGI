<?php // Agung Sunandar; lagi di bukittinggi ;)

$PID = "master_index";

require_once("../lib/dbconn.php");

$SQL = "insert into index_gaji (tt, tc, tdesc,index) ".
       "values ('".$_POST["tt"]."','".$_POST["tc"]."','".$_POST["tdesc"]."',".$_POST["index"].")";

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