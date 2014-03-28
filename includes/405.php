<?php // Nugraha, Sat May  8 16:54:44 WIT 2004
      // sfdn, 18-05-2004

$PID = "405";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

title("DATA PASIEN RAWAT INAP");
echo "<br>";

$sub = isset($_GET["sub"]) ? $_GET["sub"] : "1";
if (file_exists("includes/$PID.$sub.php")) include_once("includes/$PID.$sub.php");

?>
