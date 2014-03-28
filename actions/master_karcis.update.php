<?php // efrizal, 07/01/2011

$PID = "master_karcis";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

pg_query("update master_karcis set harga=".$_POST[f_harga]." ,js=".$_POST[f_js]." ,jp=".$_POST[f_jp]." , jmk='".$_POST[f_jmk]."', code='".$_POST[f_code]."' ".
	 	 "where id='".$_POST["f_id"]."'") or die("error atuh");

header("Location: ../index2.php?p=$PID");
exit;

?>
