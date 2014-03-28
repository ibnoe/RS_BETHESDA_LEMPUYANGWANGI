<?
$ROWS_PER_PAGE     = 14;
$RS_NAME           = "";
$ROOM_LEAP_TIME    = "12:00:00";

require_once("lib/setting.php");
$PID = "home";
$SC = $_SERVER["SCRIPT_NAME"];
session_start();

if (isset($_GET["httpHeader"]) && file_exists("includes/".$_GET["p"].".php")) {
    include("includes/".$_GET["p"].".php");
    exit;
}

?>

<html>
<head>
<title><?=$set_client_name ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

    <SCRIPT language="JavaScript" src="menu_style.js"></SCRIPT>

<link href="css/template.css" rel="stylesheet" type="text/css" />
    <LINK rel='StyleSheet' type='text/css' href='default.css'>
    <LINK rel="stylesheet" type="text/css" href="menu.css">
    <LINK rel="stylesheet" type="text/css" href="tabbar.css">
    <LINK rel="icon" href="images/icon.png" type="image/png">
    <LINK rel="shortcut icon" href="images/icon.png" type="image/png">
    <SCRIPT language="JavaScript" src="lib/sjsm.js"></SCRIPT>
    
    <SCRIPT language="JavaScript" src="lib/date/CalendarPopup.js"></SCRIPT>
    <SCRIPT language="JavaScript" src="lib/date/date.js"></SCRIPT>
    <SCRIPT language="JavaScript" src="lib/date/AnchorPosition.js"></SCRIPT>
    <SCRIPT language="JavaScript" src="lib/date/PopupWindow.js"></SCRIPT>
	<SCRIPT LANGUAGE="JavaScript">
		var cal = new CalendarPopup();
	</SCRIPT>
    
    
<script type="text/javascript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>    

</head>

<body bgcolor="#ffffff" background="images/akta_lahir.jpg">
<center>
<script language="JavaScript" src="menu.php"></script>
<script language="JavaScript">d.write(menu.sm)/*Menu inserted*/</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 
  <tr valign="top">
    <td height="98%" align="center" >	 
	  <table width="100%" height="100%"  border="1" cellspacing="0" cellpadding="0" bgcolor="#ffffff" >
	   <tr >        
 
		<td  valign="absmiddle" >  

 		<!-- <img src="image/bg/rsau.gif" align="left"> -->
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right" >
  			<tr valign="middle" >
			<td rowspan="2" height="64" align="left" background="images/top.gif"><img src="<?=$set_client_logo?>" align="left" hspace="5" />
		          <font color=white>
					<div class="SUBTITLE_SIM" >&nbsp</div>
     			    <div class="TITLE_SIM" ><?=$set_header[0]?></div>
					<div class="TITLE_SIM"><?=$set_header[1]?></div>
					<div class="TITLE_SIM"><?=$client_city?></div>
			 <td  height="64" align="left" background="images/top.gif"><img src="<?=$set_client_image?>" align="right"/>
 									
  			</td>
    		          

  			</tr>

			
		  </table>
		  
		</td>				
          </tr>	   	   
	  <!-- <tr><td align="center" valign="top"><img src="image/bg/bg.home.jpg" border="0" ></td></tr> -->
          <tr><TD bgcolor="#198D19" align="left"> <script language="Javascript">d.write(menu.m)/*Menu inserted*/</script></TD></tr> 
	  <tr>
	  	<td>
	     	<!-- Main Application -->
			<TABLE border="0" width="100%" cellspacing="0" cellpadding="2" >
			<? 
			if ($_SESSION[uid]){
			?>
				<tr>
				<td class="SUBTITLE_SIM" align=left>Login  : <font color=#3a7301>[ <?=strtoupper($_SESSION[uid])?>]</font> - <?=$_SESSION[nama_usr]?>  </td>
				</tr>
				
				
			<? } ?>
			<TR><TD>
			<?
			if (isset($_GET[p]) && file_exists("includes/".$_GET["p"].".php")) {
				include("includes/".$_GET["p"].".php");
			} elseif (empty($_SESSION[uid])) {
				include("login/index.php");
			} else {
				//echo "<img src=\"images/spacer.gif\" border=0 width=1 height=150><br>";
				echo "<tr>";
			echo "<td>&nbsp</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>&nbsp</td>";
			echo "</tr>";
				echo "<tr>";
				
		echo "<td width=10% align=center height=70><body onload=MM_preloadImages('icon/info_pasien_bigbutton2.png')>
<a href=\"index2.php?p=infopasien\" onmouseout=MM_swapImgRestore() onmouseover=MM_swapImage('Daftar1','','icon/info_pasien_bigbutton2.png',1)><img src=\"icon/info_pasien_bigbutton.png\" name=\"daftar\" width=160 height=117 border=0 id=\"Daftar1\" /></a>
</body></td>";														
		echo "<td width=10% align=center height=70><body onload=MM_preloadImages('icon/info_bangsal_bigbutton2.png')>
<a href=\"index2.php?p=infobangsal\" onmouseout=MM_swapImgRestore() onmouseover=MM_swapImage('Bangsal1','','icon/info_bangsal_bigbutton2.png',1)><img src=\"icon/info_bangsal_bigbutton.png\" name=\"bangsal\" width=160 height=117 border=0 id=\"Bangsal1\" /></a>
</body></td>";	
		echo "<td width=10% align=center height=70><body onload=MM_preloadImages('icon/info_tarif_bigbutton2.png')>
<a href=\"index2.php?p=infotarif\" onmouseout=MM_swapImgRestore() onmouseover=MM_swapImage('Tarif1','','icon/info_tarif_bigbutton2.png',1)><img src=\"icon/info_tarif_bigbutton.png\" name=\"tarif\" width=160 height=117 border=0 id=\"Tarif1\" /></a>
</body></td>";
		echo "<td width=10% align=center height=70><body onload=MM_preloadImages('icon/info_ri_bigbutton2.png')>
<a href=\"index2.php?p=405\" onmouseout=MM_swapImgRestore() onmouseover=MM_swapImage('Ri1','','icon/info_ri_bigbutton2.png',1)><img src=\"icon/info_ri_bigbutton.png\" name=\"ri\" width=160 height=117 border=0 id=\"Ri1\" /></a>
</body></td>";
				echo "";	
				echo "</tr>";	
				echo "<tr valign=top align=center class=font01>";
    		  	//echo "<td>Info Pasien</td>";
    		  	//echo "<td>Info Bangsal </td>";
  			 	//echo "<td>Info Tarif</td>";
			  	//echo "<td>Pasien Rawat Inap</td>";
  			echo "</tr>";
			echo "<tr>";
			echo "<td>&nbsp</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>&nbsp</td>";
			echo "</tr>";
			echo "<tr>";
			echo "</tr>";
			
			

			//	echo "<img name=\"daftar\" border=0 src=\"icon/rawat-inap.gif\"><br></div>";								
			//	echo "<div align=center><font class=form_title>".strtoupper($_SESSION[uid])." siap beroperasi.";

				//echo "<br>Pilih menu di atas.</font></div>";
				
			}
		
			?>
			<SCRIPT LANGUAGE="Javascript" SRC="FusionCharts/FusionCharts.js"></SCRIPT>		
			</TD>
			</TR>
			
			
			</TABLE>
			
	     </td>
	</tr>
	<tr valign="middle" >
			<td  height="56" align="right" bgcolor="#198D19">
			        <font color=white>
					<div class="SUBTITLE_SIM_FOOTER" >&nbsp</div>
					<div class="SUBTITLE_SIM_FOOTER" ><?=$set_copy?></div>
					<div class="SUBTITLE_SIM_FOOTER" ><?=$set_man?></div>
		
			
 									
  			</td>
    		          

  			</tr>
      </table>
</td>
</tr>

 </table>
  
  			

			
		  
<p>&nbsp;</p>
</center>
 </body>
</html>
