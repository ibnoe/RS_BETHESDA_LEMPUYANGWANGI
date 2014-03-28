<?
$ROWS_PER_PAGE     = 14;
$RS_NAME           = "";
$ROOM_LEAP_TIME    = "12:00:00";

require_once("lib/setting.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
$PID = "home";
$SC = $_SERVER["SCRIPT_NAME"];
session_start();
$_SESSION[sid] = session_id();
$_SESSION[uid] = 'dinkes';
$_SESSION[vuid] = md5($_SESSION[sid].$_SESSION[uid]);
	    
$_SESSION[nama_usr] = 'dinkes';   
$_SESSION[gr] = trim('d01'); 

if (isset($_GET["httpHeader"]) && file_exists("includes/".$_GET["p"].".php")) {
    include("includes/".$_GET["p"].".php");
    exit;
}

?>

<html>
<head>
<title><?=$set_client_name ?></title>
<meta http-equiv="refresh" content="300" charset=iso-8859-1">

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
				
				
			<? //} ?>
			<TR>
			
                            <TD colspan="2" align="center" valign="top" ><?
			title(" <img src='icon/patient_info-icon.gif' align='absmiddle' > PASIEN RAWAT JALAN"); 
			
			$t = new PgTable($con, "100%");
                        $tglhariini = date("Y-m-d", time());
    $t->SQL = "select a.id, upper(b.nama)as nama, ".
              "c.tdesc as poli FROM rs00006 a ".
              "left join rs00002 b on b.mr_no = a.mr_no  ".
              "left join rs00001 c on c.tc_poli = a.poli ".
		"left join rs00001 d on b.tipe_pasien = d.tc and d.tt='JEP' ".
              "where a.tanggal_reg = '$tglhariini' ".
              //"OR (a.mr_no LIKE '%".$_GET["search"]."%')) ".
              " ";

           $_GET[sort] = "a.tanggal_reg";
           $_GET[order] = "desc";

    $t->ColHeader = array("NO.REG","NAMA","RAWATAN");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[1] = "LEFT";
    $t->ColAlign[2] = "LEFT";
    
        
    if(!$GLOBALS['print']){
		$t->RowsPerPage = 15;
    }else{
    	$t->RowsPerPage = 15;
    	//$t->DisableNavButton = true;
    	//$t->DisableScrollBar = true;
    	//$t->DisableStatusBar = true;
    }
    $t->execute();

			?> </TD>			  
			      
			<TD  rowspan="2" colspan="2" align="center" valign="top"><?
			title(" <img src='icon/hospital-icon.gif' align='absmiddle' > PASIEN RAWAT INAP");
			$SQLSTR = "select upper(b.nama)as nama, b.umur, ".
	  	  "f.bangsal || ' / ' || e.bangsal|| ' / ' || d.bangsal as bangsal ".
          "from rs00010 as a ".
          "    join rs00006 as c on a.no_reg = c.id ".
          "    join rs00002 as b on c.mr_no = b.mr_no ".
          "    join rs00012 as d on a.bangsal_id = d.id ".
          "    join rs00012 as e on e.hierarchy = substr(d.hierarchy,1,6) || '000000000' ".
          "    join rs00012 as f on f.hierarchy = substr(d.hierarchy,1,3) || '000000000000' ".
	   "	 left join rs00001 g on g.tc = b.tipe_pasien and g.tt='JEP' ".
          "where a.ts_calc_stop is null ";          

$te = new PgTable($con, "100%");
$te->SQL = "$SQLSTR";
           $_GET[sort] = "a.ts_check_in";
           $_GET[order] = "desc";
$te->ColHeader = array("NAMA", "UMUR", "RUANGAN");
$te->ShowRowNumber = true;
$te->ColAlign[0] = "LEFT";
$te->ColAlign[1] = "CENTER";
$te->ColAlign[2] = "LEFT";
$te->RowsPerPage = 15;
$te->execute();
                        }
			?> </TD>
			  </tr>
			   
			
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
