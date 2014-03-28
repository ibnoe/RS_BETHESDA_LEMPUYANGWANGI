<?php 
$PID = "hrd_status";

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/functions.php");
require_once("../lib/class.PgTrans.php");

$SQL = "insert into hrd_status (code, status) ".
       "values ('".$_POST["f_code"]."','".$_POST["f_status"]."')";

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID");
exit;
?>


