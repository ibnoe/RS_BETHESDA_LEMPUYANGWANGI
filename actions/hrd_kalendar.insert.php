<?php 
$PID = "hrd_kalendar";

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/functions.php");
require_once("../lib/class.PgTrans.php");

$SQL = "insert into hrd_kalendar (code, tanggal, event, libur) ".
       "values ('".$_POST["f_code"]."','".$_POST["f_tanggal"]."','".$_POST["f_event"]."','".$_POST["f_libur"]."')";

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID");
exit;
?>


