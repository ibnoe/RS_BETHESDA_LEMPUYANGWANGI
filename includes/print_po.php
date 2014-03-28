<?php // Nugraha Tue Mar 30 05:06:49 WIT 2004

//$PID = "121";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/functions.php");

if ($_GET["jenis"]==""){
include("print/350.php");
} else if ($_GET["jenis"]=="001"){
include("print/350.php");
}else if ($_GET["jenis"]=="002"){
include("print/350_psi.php");
}else if ($_GET["jenis"]=="003"){
include("print/350_nar.php");
}else if ($_GET["jenis"]=="004"){
include("print/350_pre.php");
}

?>
