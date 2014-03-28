<?php 
$PID = "hrd_shift";

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/functions.php");
require_once("../lib/class.PgTrans.php");

$jm_mulai = $_POST["f_jm_mulai"];
$jm_selesai = $_POST["f_jm_selesai"];
$SQL = "insert into hrd_shift (code, shift, jm_mulai, jm_selesai) ".
       "values ('".$_POST["f_code"]."','".$_POST["f_shift"]."','$jm_mulai'::time,'$jm_selesai'::time)";

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID");
exit;
?>


