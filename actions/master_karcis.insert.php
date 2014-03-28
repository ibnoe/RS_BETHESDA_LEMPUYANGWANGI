<?php // efrizal, 07/01/2011
$PID = "master_karcis";

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/functions.php");
require_once("../lib/class.PgTrans.php");

$SQL = "insert into master_karcis (id, jmk, code, harga,js,jp) ".
       "values ('".$_POST["f_id"]."','".$_POST["f_jmk"]."','".$_POST["f_code"]."',".$_POST["f_harga"].",".$_POST["f_js"].",".$_POST["f_jp"].")";

pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID");
exit;
?>


