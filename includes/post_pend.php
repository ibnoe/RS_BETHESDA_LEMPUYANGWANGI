<?php
$PID = "post_pend";
$SC = $_SERVER["SCRIPT_NAME"];

//require_once("startup.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/visit_setting.php");


if($_GET["edit"] == "view") {
	include ("post_pend_coa_view.php");
} else {
	include ("post_pend_coa.php");
}

?>
