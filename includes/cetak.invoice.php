<?php
	// sfdn, 24-12-2006
session_start();

require_once("../lib/setting.php");
require_once("../lib/terbilang.php");

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");

require_once("../startup.php");
require_once("../lib/visit_setting.php");
require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
require_once("../lib/setting.php"); 	

$ROWS_PER_PAGE     = 999999;
//$RS_NAME           = $set_header[0]."<br>".$set_header[1];
//$RS_ALAMAT         = $set_header[2]."<br>".$set_header[3].$set_header[4];

?>

<HTML>


<HEAD>
<TITLE>::: Sistem Informasi <?php echo $RS_NAME; ?> :::</TITLE>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<LINK rel='styleSheet' type='text/css' href='../invoice.css'>
<style type="text/css" id="wrc-middle-css">.wrc_whole_window{	display: none; 	position: fixed; 	z-index: 2147483647;	background-color: rgba(40, 40, 40, 0.9);	word-spacing: normal;	margin: 0px;	padding: 0px;	border: 0px;	line-height: normal;	letter-spacing: normal;}.wrc_middle_main {	font-family: Segoe UI, Arial Unicode MS, Arial, Sans-Serif;	font-size: 14px;	width: 600px;	height: auto;	margin: 0px auto;	margin-top: 15%;    background: url("chrome://wrc/skin/png/background-body.png");	background-color: rgb(39, 53, 62);}.wrc_middle_logo {    background: url("chrome://wrc/skin/logo.jpg") no-repeat left bottom;    width: 140px;    height: 42px;    color: orange;    display: table-cell;    text-align: right;    vertical-align: middle;}.wrc_icon_warning {	margin: 20px 10px 20px 15px;	float: left;	background-color: transparent;}.wrc_middle_title {    color: #b6bec7;	height: auto;    margin: 0px auto;	font-size: 2.2em;	white-space: nowrap;	text-align: center;}.wrc_middle_hline {    height: 2px;	width: 100%;    display: block;}.wrc_middle_description {	text-align: center;	margin: 15px;	font-size: 1.4em;	padding: 20px;	height: auto;	color: white;	min-height: 3.5em;}.wrc_middle_actions_main_div {	text-align: center;	margin-bottom: 15px;}.wrc_middle_actions_blue_button {	-moz-appearance: none;	border-radius: 7px;	-moz-border-radius: 7px/7px;	border-radius: 7px/7px;	background-color: rgb(0, 173, 223) !important;	display: inline-block;	width: auto;	cursor: Pointer;	border: 2px solid #00dddd;	text-decoration: none;}.wrc_middle_actions_blue_button:hover {	background-color: rgb(0, 159, 212) !important;}.wrc_middle_actions_blue_button:active {	background-color: rgb(0, 146, 200) !important;	border: 2px solid #00aaaa;}.wrc_middle_actions_blue_button div {	display: inline-block;	width: auto;	cursor: Pointer;	margin: 3px 10px 3px 10px;	color: white !important;	font-size: 1.2em;	font-weight: bold;}.wrc_middle_action_low {	font-size: 0.9em;	white-space: nowrap;	cursor: Pointer;	color: grey !important;	margin: 10px 10px 0px 10px;	text-decoration: none;}.wrc_middle_action_low:hover {	color: #aa4400 !important;}.wrc_middle_actions_rest_div {	padding-top: 5px;	white-space: nowrap;	text-align: center;}.wrc_middle_action {	white-space: nowrap;	cursor: Pointer;	color: red !important;	font-size: 1.2em;	margin: 10px 10px 0px 10px;	text-decoration: none;}.wrc_middle_action:hover {	color: #aa4400 !important;}</style><script language="JavaScript" type="text/javascript" id="wrc-script-middle_window">var g_inputsCnt = 0;var g_InputThis = new Array(null, null, null, null);var g_alerted = false;/* we test the input if it includes 4 digits   (input is a part of 4 inputs for filling the credit-card number)*/function is4DigitsCardNumber(val){	var regExp = new RegExp('[0-9]{4}');	return (val.length == 4 && val.search(regExp) == 0);}/* testing the whole credit-card number 19 digits devided by three '-' symbols or   exactly 16 digits without any dividers*/function isCreditCardNumber(val){	if(val.length == 19)	{		var regExp = new RegExp('[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}');		return (val.search(regExp) == 0);	}	else if(val.length == 16)	{		var regExp = new RegExp('[0-9]{4}[0-9]{4}[0-9]{4}[0-9]{4}');		return (val.search(regExp) == 0);	}	return false;}function CheckInputOnCreditNumber(self){	if(g_alerted)		return false;	var value = self.value;	if(self.type == 'text')	{		if(is4DigitsCardNumber(value))		{			var cont = true;			for(i = 0; i < g_inputsCnt; i++)				if(g_InputThis[i] == self)					cont = false;			if(cont && g_inputsCnt < 4)			{				g_InputThis[g_inputsCnt] = self;				g_inputsCnt++;			}		}		g_alerted = (g_inputsCnt == 4);		if(g_alerted)			g_inputsCnt = 0;		else			g_alerted = isCreditCardNumber(value);	}	return g_alerted;}function CheckInputOnPassword(self){	if(g_alerted)		return false;	var value = self.value;	if(self.type == 'password')	{		g_alerted = (value.length > 0);	}	return g_alerted;}function onInputBlur(self, bRatingOk, bFishingSite){	var bCreditNumber = CheckInputOnCreditNumber(self);	var bPassword = CheckInputOnPassword(self);	if((!bRatingOk || bFishingSite == 1) && (bCreditNumber || bPassword) )	{		var warnDiv = document.getElementById("wrcinputdiv");		if(warnDiv)		{			/* show the warning div in the middle of the screen */			warnDiv.style.left = "0px";			warnDiv.style.top = "0px";			warnDiv.style.width = "100%";			warnDiv.style.height = "100%";			document.getElementById("wrc_warn_fs").style.display = 'none';			document.getElementById("wrc_warn_cn").style.display = 'none';			if(bFishingSite)				document.getElementById("wrc_warn_fs").style.display = 'block';			else				document.getElementById("wrc_warn_cn").style.display = 'block';			warnDiv.style.display = 'block';		}	}}</script></head>

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>

</HEAD>

<BODY TOPMARGIN=1 LEFTMARGIN=5 MARGINWIDTH=0 MARGINHEIGHT=0 />

<?

if ($_GET["mTAHUN"] % 4 == 0){
                    if ($mBULAN == '04' or $mBULAN == '06' or $mBULAN == '09' or $mBULAN == '11'){
                        $bulanini = 30;
                    }elseif ($mBULAN == '02'){
                        $bulanini = 29;
                    } else {
                        $bulanini = 31;
                    }
                } else {
                    if ($mBULAN == '04' or $mBULAN == '06' or $mBULAN == '09' or $mBULAN == '11'){
                        $bulanini = 30;
                    }elseif ($mBULAN == '02'){
                        $bulanini = 28;
                    } else {
                        $bulanini = 31;
                    }
                }
                //$tgl = 1;
				for ($tgl=1;$tgl<=$bulanini;$tgl++) 
				$tgl1 = $tgl - 1;
				
$r = pg_query($con, "select tanggal('".$_GET['mTAHUN']."-".$_GET['mBULAN']."-01'::date,0) as tanggal1, tanggal('".$_GET['mTAHUN']."-".$_GET['mBULAN']."-$tgl1'::date,0) as tanggal2 ");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);


$rj=getFromTable("select count(id) from rsv_daftar_kso where (tanggal_reg between '".$_GET['mTAHUN']."-".$_GET['mBULAN']."-01' and '".$_GET['mTAHUN']."-".$_GET['mBULAN']."-$tgl1') and rawat_inap='Y'");
$ri=getFromTable("select count(id) from rsv_daftar_kso where (tanggal_reg between '".$_GET['mTAHUN']."-".$_GET['mBULAN']."-01' and '".$_GET['mTAHUN']."-".$_GET['mBULAN']."-$tgl1') and rawat_inap='I'");
$igd=getFromTable("select count(id) from rsv_daftar_kso where (tanggal_reg between '".$_GET['mTAHUN']."-".$_GET['mBULAN']."-01' and '".$_GET['mTAHUN']."-".$_GET['mBULAN']."-$tgl1') and rawat_inap='N'");

$jml_tagihan=($rj * 5000) + ($igd * 5000) + ($ri * 15000);
$ppn=((($rj * 5000) + ($igd * 5000) + ($ri * 15000))*10/100);
$total=$jml_tagihan + $ppn;
?> 		
<div class="wrapper">	

<table class="header"><tbody><tr><td nowrap="nowrap" width="50%">

<p><img src="../images/stempel-mwn-small.png" title="MWN"></p>
</td><td align="center" width="50%">

<font class="paid"><big><big><big>INVOICE</big></big></big></font><br>
<br>
</td></tr></tbody></table>

	
<table class="items"><tbody><tr><td width="50%">

<div class="addressbox">

<strong>Invoiced To</strong><br>
RS. SITI KHADIJAH<br>Jl. Bandung No. 39-47<br>
Sugih Waras - Pekalongan Timur <br>
Telp. Hunting (0285) 422845<br>
Indonesia

</div>

</td><td width="50%">

<div class="addressbox">

<strong>Pay To</strong><br>
PT. Chelonind Integrated<br>
NPWP : 02.160.756.9-014.000<br>
NPPKP : 02.160.756.9-014.000<br>
Alamat : Situ Batu 2 No. 28B <br>
Bandung 40121 

</div>

</td></tr></tbody></table>

<div class="row">
<span class="title"><?echo "No. Invoice: RS-SITI/".$_GET["mBULAN"]."/".$_GET["mTAHUN"]; ?></span><br>
Tanggal Penagihan : <? $tgl_sekarang = date("M Y", time()); echo "01 ".$tgl_sekarang;?><br>
Tanggal Jatuh Tempo : <? $tgl_sekarang = date("M Y", time()); echo $tgl_sekarang;?>
</div>

<table width="100%" class="TBL_BORDER" border="0">

<tr class="title">

<table width="100%" border="0" class="items"><tbody>
	<tr class="title textcenter">
		<td >DESCRIPTION</td>
		<td >AMOUNT</td>
	</tr>
	<tr >
		<td >Jumlah Pasien RJ : <? echo $rj; ?> Orang X Rp. <?=number_format(5000,2,",",".")?> / Orang</td>
		<td class="textright"> Rp. <?=number_format($rj * 5000,2,",",".")?> </td>
	</tr>
	<tr >
		<td >Jumlah Pasien IGD : <? echo $igd; ?> Orang X Rp. <?=number_format(5000,2,",",".")?> / Orang</td>
		<td class="textright"> Rp. <?=number_format($igd * 5000,2,",",".")?></td>
	</tr>
	<tr >
		<td >Jumlah Pasien RI : <? echo $ri; ?> Orang X Rp. <?=number_format(15000,2,",",".")?> / Orang</td>
		<td class="textright"> Rp. <?=number_format($ri * 15000,2,",",".")?></td>
	</tr>
	<tr class="title">
		<td class="textright">SUB TOTAL</b></td>
		<td class="textright">Rp. <?=number_format($jml_tagihan,2,",",".")?> </td>
	</tr>
	<tr class="title">
		<td class="textright">PPn 10.00 %</b></td>
		<td class="textright">Rp. <?=number_format($ppn,2,",",".")?> </td>
	</tr>
	<tr class="title">
		<td class="textright">TOTAL TAGIHAN</b></td>
		<td class="textright">Rp. <?=number_format($total,2,",",".")?> </td>
	</tr>
</tbody></table>
	</td>
</tr>
</table>
<br><br>
<table width="100%">
	<tr class="items">
		<td width="50%" align="center">&nbsp;</td>
		<td width="50%" align="center">Bandung, <? $tgl_sekarang = date("d M Y", time()); echo $tgl_sekarang;?></td>
	</tr>
	<tr class="title">
		<td width="50%" align="center">&nbsp;</td>
		<td width="50%" align="center"><br><br><br><b><u>Billing / Erika Diyah</u></b></td>
	</tr>
</table>
</div>

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
printWindow();
//  End -->
</script>