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
					
			 <td  height="64" align="left" background="images/top.gif"><img src="<?=$set_client_image?>" align="right"/>
 									
  			</td>
    		          

  			</tr>

			
		  </table>
		  
		</td>				
          </tr>	   	   
	  <!-- <tr><td align="center" valign="top"><img src="image/bg/bg.home.jpg" border="0" ></td></tr> -->
          <tr><TD bgcolor="#02528E" align="left"> <script language="Javascript">d.write(menu.m)/*Menu inserted*/</script></TD></tr>
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
				echo "<td width=10% align=center height=70><a href=\"index2.php?p=720\" onMouseOut=MM_swapImgRestore() onMouseOver=MM_swapImage('daftar','','icon/daftar-2.gif',1)><img name=\"daftar\" border=0 src=\"icon/patient_info-icon.gif\"></a></td>";															
				echo "<td width=10% align=center height=70><a href=\"index2.php?p=730\" onMouseOut=MM_swapImgRestore() onMouseOver=MM_swapImage('daftar','','icon/daftar-2.gif',1)><img name=\"daftar\" border=0 src=\"icon/hospital-icon.gif\"></a></td>";
				echo "<td width=10% align=center height=70><a href=\"index2.php?p=740\" onMouseOut=MM_swapImgRestore() onMouseOver=MM_swapImage('daftar','','icon/daftar-2.gif',1)><img name=\"daftar\" border=0 src=\"icon/medical_insurance-icon.gif\"></a></td>";		
				echo "<td width=10% align=center height=70><a href=\"index2.php?p=405\" onMouseOut=MM_swapImgRestore() onMouseOver=MM_swapImage('daftar','','icon/daftar-2.gif',1)><img name=\"daftar\" border=0 src=\"icon/surgeon-icon.gif\"></a></td>";	
				echo "";	
				echo "</tr>";	
				echo "<tr valign=top align=center class=font01>";
    		  	echo "<td>Info Pasien</td>";
    		  	echo "<td>Info Bangsal </td>";
  			 	echo "<td>Info Tarif</td>";
			  	echo "<td>Pasien Rawat Inap</td>";
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
			<TR>
			
			<TD colspan="2" align="center"><? include("123Includes/FusionCharts.php");
			include("lib/dbconn.php");	
		$strXML = "<graph caption='GRAFIK KUNJUNGAN PASIEN RAWAT JALAN' subCaption='".date("M Y", time())."' showBorder='1' showNames='1' formatNumberScale='0' numberSuffix='' decimalPrecision='0' xAxisName='Tanggal' yAxisName='Pasien'>";

                if (date("Y", time()) % 4 == 0){
                    if (date("m", time()) == '04' or date("m", time()) == '06' or date("m", time()) == '09' or date("m", time()) == '11'){
                        $bulanini = 30;
                    }elseif (date("m", time()) == '02'){
                        $bulanini = 29;
                    } else {
                        $bulanini = 31;
                    }
                } else {
                    if (date("m", time()) == '04' or date("m", time()) == '06' or date("m", time()) == '09' or date("m", time()) == '11'){
                        $bulanini = 30;
                    }elseif (date("m", time()) == '02'){
                        $bulanini = 28;
                    } else {
                        $bulanini = 31;
                    }
                }
		 $tgl = 1;
	 
	//if ($result) {
		for ($tgl=1;$tgl<=$bulanini;$tgl++) {
			//Now create a second query to get details for this factory
		$thnini=date("Y", time());
		$blnini=date("m", time());
		//$strQuery2 = "select * from rs00006 WHERE tanggal_reg ='$thnini-$blnini-$tgl'";
                $strQuery3 = getfromtable("select count(a.id) from rs00006 a where ".
                          "tanggal_reg ='$thnini-$blnini-$tgl' ");
			//$result2 = pg_query($con, "$strQuery2");
			//$row = pg_num_rows($result2);
	   
			$strXML .= "<set name='" . $tgl . "' value='" . $strQuery3 . "' color='008E8E' />";
	
		}

	//Finally, close <graph> element
	$strXML .= "</graph>";
	
	//Create the chart - Pie 3D Chart with data from $strXML
	echo renderChart("FusionCharts/FCF_Line.swf", "", $strXML, "2", 500, 250, $debugMode=false, $registerWithJS=false, $setTransparent="");
	
			
	// echo renderChartHTML("FusionCharts/FCF_Line.swf", "Data/Data.xml", "", "myFirst", 500, 250);?> </TD>			  
			   
			   
			  <TD  rowspan="2" colspan="2" align="center"><? 
			  //include("123Includes/FusionCharts.php");	
				
			$strXML = "<graph caption='DATA PASIEN POLIKLINIK ' subCaption='".date("d M Y", time())."' showBorder='1' showNames='1' formatNumberScale='1' numberSuffix='' decimalPrecision='0' xAxisName='' yAxisName='Pasien'>";
			
			$strQuery = "SELECT * FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','201','202','206','207','208')
							 order by tdesc";
	$result = pg_query($con, "$strQuery");	
	if ($result) {
		while($ors = pg_fetch_array($result)) {
		
			$tglhariini = date("Y-m-d", time());
			$strQuery2 = "select * from rsv_pasien4 WHERE poli=". $ors['tc']." and TANGGAL_REG = '$tglhariini'";
			$result2 = pg_query($con, "$strQuery2");
			$row = pg_num_rows($result2) ;		   
			//$row = $row +5;
			$strXML .= "<set name='" . $ors[tdesc] . "' value='" . $row . "' color='008E8E' />";
	
		}
	} 

	
	$strXML .= "</graph>";
	
	//Create the chart - Pie 3D Chart with data from $strXML
	echo renderChart("FusionCharts/FCF_Bar2D.swf", "", $strXML, "Pasien", 600, 500);
			
		//	  echo renderChartHTML("FusionCharts/FCF_Bar2D.swf", "Data/Data5.xml", "", "myFirst", 400, 500);?> </TD>
			  </tr>
			  <TD colspan="2" align="center"><? 
			  
			  $strXML = "<graph caption='GRAFIK KUNJUNGAN PASIEN RAWAT INAP' subCaption='".date("M Y", time())."' showBorder='1' showNames='1' formatNumberScale='0' numberSuffix='' decimalPrecision='0' xAxisName='Tanggal' yAxisName='Pasien'>";
			  $tgl = 1;
	 
	
		for ($tgl=1;$tgl<=$bulanini;$tgl++) {
		
		$thnini=date("Y", time());
		$blnini=date("m", time());
		$sql_satus = getFromTable("select count(b.id) ".
                              "from rs00010 b ".
                              "where b.awal = 1 ".
                              "and extract(YEAR from b.ts_check_in) = $thnini ".
                             "and extract(MONTH from b.ts_check_in) = $blnini ".
                             "and extract(day from b.ts_check_in) = $tgl ".
                              "    ");
			//$result2 = pg_query($con, "$strQuery2");
			//$row = pg_num_rows($result2);
			$strXML .= "<set name='" . $tgl . "' value='" . $sql_satus . "' color='008E8E' />";
	
		}
	
	//Finally, close <graph> element
	$strXML .= "</graph>";
	
	//Create the chart - Pie 3D Chart with data from $strXML
	echo renderChart("FusionCharts/FCF_Line.swf", "", $strXML, "1", 500, 250);
		?> 
			   </TD>
			   
			
			</TABLE>
			
	     </td>
	</tr>
      </table>
</td>
</tr>
 </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right" >
  			<tr valign="middle" >
			<td  height="56" align="right" background="images/bottom.gif"><img src="<?=$set_client_bottomleft?>" align="left"/>
			        <font color=white>
					<div class="SUBTITLE_SIM" >&nbsp</div>
					<div class="SUBTITLE_SIM" ><?=$set_copy?></div>
					<div class="SUBTITLE_SIM" ><?=$set_man?></div>
		
			
 									
  			</td>
    		          

  			</tr>

			
		  </table>
<p>&nbsp;</p>
</center>
 </body>
</html>
