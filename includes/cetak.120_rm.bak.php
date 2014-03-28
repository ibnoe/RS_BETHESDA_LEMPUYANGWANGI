<?php
session_start();
require_once("../lib/dbconn.php");
?>

<HTML>

<HEAD>
<TITLE></TITLE>
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

$r = pg_query($con,"select b.mr_no, b.nama, b.tgl_lahir, b.jenis_kelamin,  b.umur, b.alm_tetap ".
				   "from  rs00002 as b ".	 
				   "where b.mr_no = '$reg' ");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);

?>

<table cellpadding="0" cellspacing="0" border="0" width="325" style="font-family: tahoma; font-size: 12px;">
    <!-- original
    <tr>
        <td colspan="3" >&nbsp;</td>
    </tr>
    <tr>
        <td width="8">&nbsp;&nbsp;</td>
        <td width="90"><B>No. MR</B></td>
        <td width="194">: <B><?=$d->mr_no?></B></td>
    </tr>
    <tr>
        <td width="8">&nbsp;&nbsp;</td>
        <td width="90">Nama</td>
        <td width="194">: <?=$d->nama?></td>
    </tr>
    <tr>
        <td width="8">&nbsp;&nbsp;</td>
        <td valign=top width="90">Alamat</td>
        <td width="194">: <?=$d->alm_tetap?></td>
    </tr>
        <td width="8">&nbsp;&nbsp;</td>
        <td valign=top width="90">&nbsp;</td>
        <td width="194"><img src="<?php echo 'cetak.kartu_pasien.php?rg='.$reg?>" /></td>
    </tr>
    -->
    <tr>
        <td height="0" width="210" colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td height="0" width="210" colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td height="0" width="210" colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td height="0" width="210" colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td height="30" width="210" colspan="2"><font size="3"><b><?php echo $d->nama;?></b></font></td>
    </tr>
    <tr>
        <td height="0" width="210"><font size="5"><b><?php echo $d->mr_no;?></b></font></td>
        <td height="0" width="210" align="center"><img src="<?php echo 'cetak.kartu_pasien.php?rg='.$reg?>" /></td>
    </tr>
</table>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
printWindow();
//  End -->
</script>

</body>
</html>
