<?php // Agung Sunandar; lagi di bukittinggi ;)

$PID = "master_index";

require_once("../lib/dbconn.php");

$SQL = "update index_gaji set tdesc = '".$_POST["tdesc"]."', index = '".$_POST["index"]."'  ".
       "where tt = '".$_POST["tt"]."' ".
       "and tc = '".$_POST["tc"]."'";

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID&tt=".$_POST["tt"]);
exit;

?>