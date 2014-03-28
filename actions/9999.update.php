<?php 
// Rizki, NOV 08 14:09:04 WIB 2012
$PID = "9999";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");

//Query Update Start
$SQL="update rs000199 set description='". $_POST["f_description"] ."' where description_code='". $_POST["description_code"] ."'";
pg_query($con,$SQL);
//Query Update End

header("Location: ../index2.php?p=$PID");

exit;

?>
