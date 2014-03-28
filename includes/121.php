<?php // Nugraha Tue Mar 30 05:06:49 WIT 2004

$PID = "121";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/functions.php");

title("Kartu Registrasi");
echo "<br>";

include("print/121.php");

?>
