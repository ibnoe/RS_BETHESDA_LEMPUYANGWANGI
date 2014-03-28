<?php // Nugraha, Sat May  8 16:54:44 WIT 2004

if ($_SESSION[uid] == "igd" || $_SESSION[uid] == "user" || $_SESSION[uid] == "root") {


$PID = "p_rawat_inap";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

title("<img src='icon/rawat-inap-2.gif' align='absmiddle' > ADMINISTRASI AKOMODASI");
echo "<div class=box>";
echo "<form name=Form3>";
echo "<input name=b1 type=button value='Pasien' onClick='window.location=\"$SC?p=$PID&sub=1\";'".
     ($_GET["sub"] == "1" || $_GET["sub"] == "" ? " DISABLED" : "").">&nbsp;";
echo "<input name=b2 type=button value='Pendaftaran' onClick='window.location=\"$SC?p=$PID&sub=2\";'".
     ($_GET["sub"] == "2" ? " DISABLED" : "").">&nbsp;";
//echo "<input name=b3 type=button value='Ruangan' onClick='window.location=\"$SC?p=$PID&sub=3\";'".
//     ($_GET["sub"] == "3" ? " DISABLED" : "").">&nbsp;";
echo "</form>";
echo"<hr noshade size='1'>";
$sub = isset($_GET["sub"]) ? $_GET["sub"] : "1";
if (file_exists("includes/$PID.$sub.php")) include_once("includes/$PID.$sub.php");
echo "</div>";

} // end of $_SESSION[uid] == igd || root
?>
