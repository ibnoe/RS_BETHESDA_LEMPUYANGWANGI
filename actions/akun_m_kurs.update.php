<?php // Nugraha, 18/02/2004

$PID = "akun_m_kurs";

require_once("../lib/dbconn.php");

$SQL = "update rs00001 set tdesc = '".$_POST["tdesc"]."' ".
       "where tt = '".$_POST["tt"]."' ".
       "and tc = '".$_POST["tc"]."'";

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID&tt=".$_POST["tt"]);
exit;

?>