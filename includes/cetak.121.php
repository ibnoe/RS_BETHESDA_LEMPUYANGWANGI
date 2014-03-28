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
</script>


</HEAD>

<BODY >

<?
$reg = $_GET["rg"];
$reg_count = getFromTable("select count(mr_no) from rs00006 where mr_no = (select mr_no from rs00006 where id = '$reg') and id <='$reg'");

$r = pg_query($con,"select a.id, a.mr_no,upper(b.nama)as nama, b.tgl_lahir,b.alm_tetap, b.jenis_kelamin, ".
				   "a.tanggal_reg, a.waktu_reg, a.tipe, a.rujukan, upper(b.kota_tetap)as kota_tetap, a.rawat_inap, b.umur, upper(b.alm_tetap )as alm_tetap , upper(a.diagnosa_sementara)as diagnosa_sementara, a.is_baru, a.poli ".
				   "from rs00006 as a, rs00002 as b ".	//"left join rs00034 c on a.poli = c.id  ".
				   "where a.mr_no = b.mr_no ".
				   "and a.id = '$reg'");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);
$namapoli = getFromTable("select tdesc from rs00001 where tc = '$d->poli'");
$tipepas = getFromTable("select tdesc from rs00001 where tc_tipe  = '$d->tipe'");
$noUrut = getFromTable("select count(id) from rs00006 where poli = $d->poli and tanggal_reg = '$d->tanggal_reg'");
?>
    
   <table width="300" cellpadding="0" cellspacing="0" border="1">
<tr><td>

<TABLE border="0" cellpadding="3" cellspacing="0" width="300">
<!--<TR>
    <TD colspan=2 align=center><B><? //=$RS_NAME?></B></TD>
</TR>-->
<TR>
    <TD colspan=2 align=center><FONT SIZE="2px"><B>KARTU REGISTRASI</B><br><HR noshade="true" size="1"></TD>
</TR>

<tr>
    <td colspan=2 valign=top align=right><FONT SIZE="1.8px"><B>NO. REGISTRASI:&nbsp;&nbsp;&nbsp;&nbsp;</B><FONT SIZE="1.8px"><B><?=formatRegNo($_GET["rg"]);?>&nbsp;&nbsp;&nbsp;</B></td>
</tr>
<tr>
    <td colspan=2>

    <table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td valign=top><FONT SIZE="0.5px"><B>NO. RM</B></td>
        <td valign=top><FONT SIZE="0.5px"><B>&nbsp;:&nbsp;</B></td>
        <td valign=top><FONT SIZE="0.5px"><B><?echo $d->mr_no;?></B></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;</td>
        <td valign=top><FONT SIZE="0.5px"><B>NAMA</B></td>
        <td valign=top><FONT SIZE="0.5px"><B>&nbsp;:&nbsp;</B></td>
        <td valign=top><FONT SIZE="0.5px"><B><?echo $d->nama;?></B></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td valign=top><FONT SIZE="0.5px"><B>ALAMAT</B></td>
        <td valign=top><FONT SIZE="0.5px"><B>&nbsp;:&nbsp;</B></td>
        <td valign=top><FONT SIZE="0.5px"><B><?echo $d->alm_tetap.',&nbsp;'.$d->kota_tetap;?></B></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td valign=top><FONT SIZE="0.5px"><B>LAYANAN</B></td>
        <td valign=top><FONT SIZE="0.5px"><B>&nbsp;:&nbsp;</B></td>
        <td valign=top><FONT SIZE="0.5px"><B><?php echo $namapoli;?></B></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td valign=top><FONT SIZE="0.5px"><B>DOKTER</B></td>
        <td valign=top><FONT SIZE="0.5px"><B>&nbsp;:&nbsp;</B></td>
        <td valign=top><FONT SIZE="0.5px"><B><?php echo $d->diagnosa_sementara;?></B></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td valign=top><FONT SIZE="0.5px"><B>NO. URUT</B></td>
        <td valign=top><FONT SIZE="0.5px"><B>&nbsp;:&nbsp;</B></td>
        <td valign=top><FONT SIZE="0.5px"><B><? echo $noUrut;?></B></td>
    </tr>

    </table>

    </td>
</tr>
<tr>
    <td valign=top align=left><FONT SIZE="1.8px">&nbsp;&nbsp;&nbsp;&nbsp;KUNJUNGAN: <? echo $reg_count;?></td>
    <td valign=top align="right"><FONT SIZE="1.8px"><?=date("d/m/Y",pgsql2mktime($d->tanggal_reg))." ".substr($d->waktu_reg,0,8)?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
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
