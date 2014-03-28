<?php
session_start();
$ROWS_PER_PAGE     = 14;
$RS_NAME           = "RUMAH SAKIT";

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
require_once("../lib/class.PgTrans.php");

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

<BODY >

<?
$reg = (int) $_GET["rg"];
$reg_count = getFromTable("select count(mr_no) from rs00006 where mr_no = (select mr_no from rs00006 where id = '$reg') and id <='$reg'");
//$xreg_count = pg_fetch_array($reg_count);
//$reg_count = $xreg_count[0];

$r = pg_query($con,"select a.id, a.mr_no,upper(b.nama)as nama, b.tgl_lahir,b.alm_tetap, b.jenis_kelamin, ".
				   "a.tanggal_reg, a.waktu_reg, a.tipe, a.rujukan, upper(b.kota_tetap)as kota_tetap, a.rawat_inap, b.umur, upper(b.alm_tetap )as alm_tetap , upper(a.diagnosa_sementara)as diagnosa_sementara, a.is_baru, a.poli ".
				   "from rs00006 as a, rs00002 as b ".	//"left join rs00034 c on a.poli = c.id  ".
				   "where a.mr_no = b.mr_no ".
				   "and a.id = '$reg'");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);
//echo "xxx: $reg ".$_GET[rg]; exit();
//$namapoli = getFromTable("select layanan from rs00034 where id = $d->poli");
$namapoli = getFromTable("select tdesc from rs00001 where tc = '$d->poli'");
$tipepas = getFromTable("select tdesc from rs00001 where tc_tipe  = '$d->tipe'");
$noUrut = getFromTable("select count(id) from rs00006 where poli = $d->poli and tanggal_reg = '$d->tanggal_reg'");
/*
?>
<table width="40%" cellpadding="0" cellspacing="0" border="1">
<tr><td>

<TABLE border="1" cellpadding="3" cellspacing="0" width="40%">

<TR>
    <TD colspan=2 align=center><font size="3">KARTU REGISTRASI</font></B><HR noshade="true" size="3"></TD>
</TR>

<tr>
    <td colspan=2 align=left><font size="3">NO. URUT : </font></B><font size="3"><? echo $noUrut;?></font></B></td>
</tr>
<tr>
    <td colspan=2>

    <table width="40%" cellpadding="0" cellspacing="0" border="1">
    <tr>
        <td width="20%">NO. MR</td>
        <td>&nbsp;:&nbsp;</td>
        <td><font size="3"><?=$d->mr_no?></td>
    </tr>
    <tr>
        <td>NAMA</td>
        <td>&nbsp;:&nbsp;</td>
        <td><?=$d->nama?></td>
    </tr>
    <tr>
        <td valign=top>PASIEN</td>
        <td valign=top>&nbsp;:&nbsp;</td>
        <td><? echo $namapoli;?></td>
    </tr>
<tr>
        <td valign=top>No Reg<td>
        <td valign=top>&nbsp;:&nbsp;</td>
        <td align=left><?=$reg?></td>
    </tr>

    </table>

    </td>
</tr>
<tr>
    <td><br></td>
</tr>
<tr>
    <td align=left>&nbsp;&nbsp;&nbsp;&nbsp;KUNJUNGAN: <? echo $reg_count;?></td>
    <td align="right"><?=date("d/m/Y",pgsql2mktime($d->tanggal_reg))." ".substr($d->waktu_reg,0,8)?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>

</TABLE>

</td></tr>
</table>

*/?>
    
    <table border="0" cellpadding="0" cellspacing="0" align=CENTER>
      <tr>
            <td class="TBL_BODY5" align=CENTER><U><font size="4" face="Times New Roman">FORM PASIEN <? echo $tipepas?></font></B></TD>
        </tr> 
		
      <!--   <tr>
            <td colspan=3 align=right><font size="3">NO. URUT : </font></B><font size="3"><? echo $noUrut;?></font></B></td>
        </tr> -->
		</table>
		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman">TGL & WAKTU</font></B></td>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman">:</font></B></td>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman"><?=date("d / m / Y,",pgsql2mktime($d->tanggal_reg))." ".substr($d->waktu_reg,0,8)?></font></B></td>
		</tr>
		<tr>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman">NO.REG</font></B></td>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman">:</font></B></td>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman"><?=$reg?></font></B></td>
		</tr>
		<tr>
            <td class="TBL_BODY5" align=left width="5%"><font size="4" face="Times New Roman">NO. MR</font></B></td>
            <td class="TBL_BODY5" align=left width="1%"><font size="4" face="Times New Roman">:</font></B></td>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman"><?= $d->mr_no?>,&nbsp; UMUR:<?=$d->umur?></font></B></td>
        </tr>
		
        <tr>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman">NAMA</font></B></td>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman">:</font></B></td>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman"><?=$d->nama?></font></B></td>
        </tr>
		<tr>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman">ALAMAT</font></B></td>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman">:</font></B></td>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman"><?=$d->alm_tetap?></font></B></td>
        </tr>
		<tr>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman">KOTA</font></B></td>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman">:</font></B></td>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman"><?=$d->kota_tetap?></font></B></td>
        </tr>
        <tr>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman">LAYANAN</font></B></td>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman">:</font></B></td>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman"><? echo $namapoli;?></font></B></td>
        </tr>
        
		<tr>
		    <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman">PASIEN</font></B></td>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman">:</font></B></td>
			<td class="TBL_BODY5" align=left><font size="4" face="Times New Roman"><? echo $tipepas?></font></B></td>
		</tr>
		<tr>
		    <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman">DOKTER</font></B></td>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman">:</font></B></td>
			<td class="TBL_BODY5" align=left><font size="4" face="Times New Roman"><?=$d->diagnosa_sementara?></font></B></td>
		</tr>
		<tr>
		    <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman">DIAGNOSA</font></B></td>
            <td class="TBL_BODY5" align=left><font size="4" face="Times New Roman">:</font></B></td>
		</tr>
		
		<tr>
		<td class="TBL_BODY5" align=RIGHT ><font size="4" face="Times New Roman"> </font></B></td>
		<td class="TBL_BODY5" align=RIGHT><font size="4" face="Times New Roman">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </font></B></td>
		<td class="TBL_BODY5" align=RIGHT COLSPAN="1"><U><font size="2" face="Times New Roman"><?=$d->diagnosa_sementara?></font></B></td>
		</tr>
      <!--  <tr>
            <td align=left><font size="3">&nbsp;</font></B></td>
            <td align=left><font size="3">&nbsp;</font></B></td>
            <td align=left><font size="3">&nbsp;</font></B></td>
        </tr>
        <tr>
            <td align=left><font size="3">KUNJUNGAN KE-<? echo $reg_count;?></font></B></td>
            <td align=left><font size="3">&nbsp;</font></B></td>
            <td align="right"><?=date("d/m/Y",pgsql2mktime($d->tanggal_reg))." ".substr($d->waktu_reg,0,8)?></td>
        </tr>
		<tr>
            <td align=left colspan="3"><font size="3">PERHATIAN ! <br>Kartu ini dibawa Pasien dan ditunjukan kepada petugas sampai Kasir</font></B></td>
        </tr> 
        <tr>
            <td colspan="3"><HR noshade="true" size="3"></td>
        </tr> -->
        
    </table>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
printWindow();
//  End -->
</script>

</body>
</html>