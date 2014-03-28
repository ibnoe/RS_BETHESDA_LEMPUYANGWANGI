<?php // Nugraha, Sat May  8 16:54:44 WIT 2004
      // sfdn, 10-05-2004
      // hery, 03-07-2007 print

$PID = "730";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");


if (!$GLOBALS['print']){
		title_print("<img src='icon/informasi-2.gif' align='absmiddle' > INFO BANGSAL/RUANGAN");
		title_excel("730&tblstart=".$_GET['tblstart']);
		echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
//title_print("730&sub=".$_GET["sub"]."&L1=".$_GET["L1"]."&L2=".$_GET["L2"]."");

}else {
		title_print("<img src='icon/informasi.gif' align='absmiddle' > INFO BANGSAL/RUANGAN");
}
//echo "<br>";
    if (file_exists("includes/$PID.3.php")) include_once("includes/$PID.3.php");
?>
