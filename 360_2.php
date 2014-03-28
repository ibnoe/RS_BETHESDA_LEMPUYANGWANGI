<?php // Agung Sunandar 10:00 18/09/2012 New Penerimaan

$PID = "360_2";
$SC = $_SERVER["SCRIPT_NAME"];

//require_once("startup.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/visit_setting.php");


if($_GET["edit"] == "view" or $_GET["edit"] == "view2") {

   include ("pepeneperipimapaapan_hapargapa.php");
   
} elseif($_GET["edit"] == "edit" or $_GET["edit"] == "edit_harga") {

include("pepeneperipimapaapan_edthrga.php");

} elseif($_GET["edit"] == "edit1") {
	include("pepeneperipimapaapan_fapaktupur.php");
}elseif($_GET["edit"]=="bonus"){
	include("penerimaan_bonus.php");
}else {

  include ("pepeneperipimapaapan.php");
}

echo "
<script type='text/javascript'>
function selesai()
{
var sip = '' + reg;
var stay= confirm('Apakah Anda yakin sudah selesai periksa?')
if (!stay) {
window.location='$SC?p=$PID';
}else{
window.location=sip;
}
}
</script>";
?>
