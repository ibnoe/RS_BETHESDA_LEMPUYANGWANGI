<?php // Agung Sunandar

$PID = "121";
session_start();
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/functions.php");
require_once("lib/class.PgTrans.php");
require_once("lib/terbilang.php");


//$no_faktur = getFromTable("select tdesc from rs00001 where tc = '$d->poli'");
$supplier = getFromTable("select a.nama from rs00028 a, c_po b where  b.no_faktur='".$_GET["po_id"]."' and a.id::numeric= b.supp_id");
$id_sup=getFromTable("select supp_id from c_po  where no_faktur='".$_GET["po_id"]."'");

$alamat=getFromTable("select alamat_jln1||', '||alamat_kota from rs00028  where id::numeric=$id_sup");
$tgl_sekarang=date("d M Y");
//$jumlah=
?>
<table width="300" cellpadding="0" cellspacing="0" border="1">
<tr><td>

<TABLE border="0" cellpadding="3" cellspacing="0" width="300">
<TR>
    <TD colspan=2 align=center><BIG><BIG><B><? //=$RS_NAME?></B></BIG></BIG></TD>
</TR>
<TR>
    <TD colspan=2 align=center><BIG><B>KWITANSI PEMBAYARAN</B></BIG><br><HR noshade="true" size="1"></TD>
</TR>

<tr>
    <td colspan=2 align=center><BIG><b>&nbsp;</b></BIG><br><BIG><BIG><BIG><B><? echo $set_header[0]?></B></BIG></BIG></BIG></td>
</tr>
<tr>
    <td colspan=2>
<br>
    <table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>&nbsp;&nbsp;&nbsp;</td>
        <td><B>NO. FAKTUR</B></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td><B><?echo $_GET["po_id"];?></B></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><B>NAMA SUPPLIER</B></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td><B><?echo $supplier;?></B></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td><B>ALAMAT</B></td>
        <td><B>&nbsp;:&nbsp;</B></td>
        <td><B><?echo $alamat;?></B></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td valign=top><B>JUMLAH BAYAR</B></td>
        <td valign=top><B>&nbsp;:&nbsp;</B></td>
        <td><B>Rp. <?php echo number_format($_GET["jumlah"],2,",",".");?></B></td>
    </tr>
	<tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td valign=top><B>TERBILANG</B></td>
        <td valign=top><B>&nbsp;:&nbsp;</B></td>
        <td><B><?php $y=terbilang($_GET["jumlah"]);
						echo strtoupper($y);?> RUPIAH</B></td>
    </tr>
    </table>

    </td>
</tr>
<tr>
    <td><br></td>
</tr>
<tr>
    <td align=left><? echo $set_header[1]?>, <?echo $tgl_sekarang;?></td>
</tr>
<tr>
    <td align=left>&nbsp;</td>
</tr>
<tr>
    <td align=left>&nbsp;</td>
</tr>
<tr>
    <td align=left><? echo $_SESSION["nama_usr"];?></td>
</tr>
	
</TABLE>

</td></tr>
</table>

<BR>
<?
echo "\n<script language='JavaScript'>\n";
echo "function cetakaja(tag) {\n";
echo "    sWin = window.open('includes/cetak.pembayaran_hutang.php?po_id=".$_GET["po_id"]."&jumlah=".$_GET["jumlah"]."', 'xWin',".
     " 'top=0,left=0,width=500,height=300,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";

?>
<br>
<div align="left">
<a href="javascript: cetakaja(<? echo (int) $_GET[po_id];?>)" ><img src="images/cetak.gif" border="0"></a>
</div>
