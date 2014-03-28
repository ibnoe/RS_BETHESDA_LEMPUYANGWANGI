<?php
//hery july 01, 2007
$ROWS_PER_PAGE     = 14;
$RS_NAME           = "";
$ROOM_LEAP_TIME    = "12:00:00";
require_once("lib/setting.php");
require_once("startup.php");
session_start();
$print = true;
?>
<html>
<head>
<title><?php echo $set_header[0];?></title>
<meta http-equiv=Content-Type content="text/html; charset=iso-8859-1">
	<script language="JavaScript" src="menu_style.js"></script>	 
    <link rel="stylesheet" type="text/css" href="cetak.css">    
    <script language="JavaScript" src="lib/sjsm.js"></script>    
</head>
<body>
<?php
			if ($_SESSION[uid]){
			?>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" >
	  			<tr>
		  			<td rowspan="4" height="65" valign="middle" align="left">		 					
		  			 		<div class="TITLE_SIM" align="center"><?php echo $set_header[0];?></div>
		 					<div class="SUBTITLE_SIM" align="center"><b><?php echo $set_header[1];?></b></div>
		 					<div class="SUBTITLE_SIM" align="center"><?php echo $set_header[2];?></div>
		 					<div class="SUBTITLE_SIM" align="center"><?php echo $set_header[3];?></div> 					
		  			</td>
				</tr></table>
			<? } ?>
				<br />
				<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" >
				<tr><td>
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
				</td></tr>		
				</table>	
</body>
</html>
<script language="JavaScript">
window.print();
</script>
