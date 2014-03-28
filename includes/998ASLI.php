<?php // Nugraha, Tue Jun  1 20:33:49 WIT 2004

$PID = "998";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

title("Konfigurasi Aplikasi");
echo "<br>";

$acfg = Array(
            "1" => "Karcis Layanan",
            "2" => "Paket Laboratorium",

        );

$f = new Form("index2.php", "GET", "name=frmConfig");
$f->hidden("p", $PID);
$f->selectArray("configtype", "Konfigurasi", $acfg, $_GET["configtype"],
    "onChange='document.frmConfig.submit();';");
$f->execute();

$_GET["configtype"] = strlen($_GET["configtype"]) == 0 ? 1 : $_GET["configtype"];
if (file_exists("includes/$PID." . $_GET["configtype"] . ".php")) {
    echo "<DIV CLASS=BOX>";
    include_once("includes/$PID." . $_GET["configtype"] . ".php");
    echo "</DIV>";
}

?>
