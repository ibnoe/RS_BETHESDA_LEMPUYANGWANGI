<?php
session_start();
$ROWS_PER_PAGE     = 14;


require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
require_once("../lib/class.PgTrans.php");
require_once("../lib/setting.php");
$RS_NAME           = $set_client_name ;
$RS_NAME1	   = $set_header[0];
$RS_NAME2          = $set_header[1];
$Judul_kartu	   = "KARCIS";

?>

<HTML>

<HEAD>
<TITLE>.: Sistem Informasi <?php echo $RS_NAME; ?> :.</TITLE>
<LINK rel='styleSheet' type='text/css' href='../cetak.css'>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>


</HEAD>

<BODY TOPMARGIN=5 LEFTMARGIN=5 MARGINWIDTH=0 MARGINHEIGHT=0>

<?
$reg = $_GET["rg"];
//$reg = str_pad(((int) $_GET["rg"]), 6, "0", STR_PAD_LEFT);

$r = pg_query($con,"select * from  kasir_karcis as b where b.id = '$reg' ");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);
$ri = pg_query($con,"select * from master_karcis where id = '".$d->poli."' ");
$di = pg_fetch_object($ri);
pg_free_result($ri);
$ra = pg_query($con,"select * from rs00001 where tt='JMK' and tc = '".$di->jmk."' ");
$da = pg_fetch_object($ra);
pg_free_result($ra);
 
?>
<table width="550" cellpadding="0" cellspacing="0" border="0"   >
<tr><td>

<TABLE border="0" cellpadding="3" cellspacing="0" width="550" >

<TR>
 <td><B>&nbsp;</B></td>  
</TR>
 
<tr>
    <td colspan=2>

    <table cellpadding="0" cellspacing="0" border="0">

    <tr>
        <td>&nbsp;&nbsp;</td>
        <td><img src='../images/logo_kotakab3.png' align='absmiddle'></td>
        <td colspan="4">
        <div align=right><BIG><b><?=$RS_NAME1?></b></BIG></div>
        <div align=right><BIG><b><?=$RS_NAME2?></b></BIG></div>
        </td>
    </tr>
	 <tr>
        <td>&nbsp;&nbsp;</td>
        <td><BIG>NO.REG</BIG></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td colspan=3><BIG><?=$d->no_reg?></BIG></td>
    </tr>
	 <tr>
        <td>&nbsp;&nbsp;</td>
        <td><BIG>NO.MR</BIG></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td colspan=3><BIG><?=$d->no_mr?></BIG></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;</td>
        <td><BIG>NAMA</BIG></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td colspan=3><BIG><?=$d->nama?></BIG></td>
    </tr>
    <tr>
<td>&nbsp;&nbsp;</td>
        <td valign=top><BIG>WAKTU BELI KARCIS</BIG></td>
        <td valign=top><B>&nbsp;:&nbsp;</B></td>
        <td colspan=3><BIG><?=date("d M Y", pgsql2mktime($d->tanggal_reg))?></BIG></td>
      
    </tr>
    <tr>
          <td>&nbsp;&nbsp;</td>
        <td><BIG>ALAMAT</BIG></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td colspan=3><BIG><?=$d->alamat?></BIG></td>
        
    </tr>
    <tr>
          <td>&nbsp;&nbsp;</td>
        <td><BIG>KARCIS</BIG></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td colspan=3><BIG><?=$da->tdesc?></BIG></td>

    </tr>
    <tr>
          <td>&nbsp;&nbsp;</td>
        <td><BIG>POLI</BIG></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td colspan=3><BIG><?=$di->code?></BIG></td>

    </tr>
        <tr>
          <td>&nbsp;&nbsp;</td>
        <td><BIG>HARGA</BIG></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td colspan=3><BIG><?=$d->harga?></BIG></td>

    </tr>
    </table>

    </td>
</tr>
<tr>
    <td><br></td>
</tr>

</TABLE>

</td></tr>
</table>


<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
printWindow();
//  End -->
</script>

</body>
</html>
