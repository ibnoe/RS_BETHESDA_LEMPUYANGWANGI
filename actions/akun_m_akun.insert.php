<?php // Nugraha, 18/02/2004

$PID = "akun_m_akun";

require_once("../lib/dbconn.php");

$SQL = "insert into rs00001 (tt, tc, tdesc) ".
       "values ('".$_POST["tt"]."','".$_POST["tc"]."','".$_POST["tdesc"]."')";

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