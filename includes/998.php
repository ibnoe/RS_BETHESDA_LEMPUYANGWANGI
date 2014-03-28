<?php // Nugraha, Tue Jun  1 20:33:49 WIT 2004

$PID = "998";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

title(" <img src='icon/keuangan-2.gif' align='absmiddle' > Konfigurasi Paket Layanan ");
echo "<br>";



$_GET["configtype"] = strlen($_GET["configtype"]) == 0 ? 1 : $_GET["configtype"];
if (file_exists("includes/$PID." . $_GET["configtype"] . ".php")) {
   // echo "<DIV CLASS=BOX>";
    include_once("includes/$PID." . $_GET["configtype"] . ".php");
   // echo "</DIV>";
}

?>
