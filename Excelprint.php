<?
//hery july 01, 2007

$ROWS_PER_PAGE     = 14;
$RS_NAME           = "";
$ROOM_LEAP_TIME    = "12:00:00";

require_once("lib/setting.php");
require_once("startup.php");
session_start();

$print = true;
header('Content-type: application/excel');
header('Content-Disposition: attachment; filename="'.$_GET["p"].'.xls"');	
?>


<?php
/*
if (isset($_GET['p'])) {
    include("includes/{$_GET['p']}.php");
} else {
    include($INCLUDE_DIR . "rm_pasien.php");
}
*/
?>

			<? 
			if ($_SESSION[uid]){
			?>
				
			<? } ?>
				
			<?
				if (isset($_GET[p]) && file_exists("includes/".$_GET["p"].".php")) {
					include("includes/".$_GET["p"].".php");
				} elseif (empty($_SESSION[uid])) {
					include("login/index.php");
				} else {
					echo "<img src=\"images/spacer.gif\" border=0 width=1 height=150><br>";
					echo "<div align=center><font class=form_title>".strtoupper($_SESSION[uid])." siap beroperasi.";
					echo "<br>Pilih menu di atas.</font></div>";
				}
		
			?>
                
</body>
</html>
