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

<BODY TOPMARGIN=5 LEFTMARGIN=5 MARGINWIDTH=0 MARGINHEIGHT=0>

<?
$waktu =$_GET["waktu"];
$jns= getFromTable("select jns_pasien from menu_pasien where id::text='".$_GET["id"]."'");
if ($jns == "I"){
$r = pg_query($con,"select to_char(d.tgl,'DD MON YYYY') as tgl, d.no_mr as no_mr ,e.nama as nama, c.bangsal || ' / ' || b.bangsal || ' / ' || a.bangsal as bangsal, d.pagi as pagi, d.siang as siang, d.malam as malam, d.snack_1 as snack1 , d.snack_2 as snack2, d.id,d.dummy from menu_pasien d , rs00002 e, rs00012 as a join rs00012 as b on b.hierarchy = substr(a.hierarchy,1,6) || '000000000' join rs00012 as c on c.hierarchy = substr(a.hierarchy,1,3) || '000000000000' where d.id='".$_GET['id']."' and a.id = d.id_bangsal and d.no_mr=e.mr_no");
}else{
$r = pg_query($con, "select to_char(d.tgl,'DD MON YYYY') as tgl, d.no_mr as no_mr ,e.nama as nama, 
			c.tdesc as bangsal, d.pagi as pagi, d.id
			from menu_pasien d , rs00002 e, rs00001 c  
			where c.tc = d.id_bangsal::text and c.tt='LYN' and d.no_mr=e.mr_no and d.jns_pasien='J' and d.id::text ='".$_GET["id"]."'");
}
$n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    
?>
<table width="400" cellpadding="0" cellspacing="0" border="1">
<tr><td>

<TABLE border="0" cellpadding="3" cellspacing="0" width="400">
<TR>
    <TD colspan=0 align=center><BIG><BIG><B><? //=$RS_NAME?></B></BIG></BIG></TD>
</TR>
<TR>
    <TD colspan=0 align=center><BIG><B>INSTALASI GIZI</B></BIG><br><HR noshade="true" size="1"></TD>
</TR>

<tr>
    <td colspan=0 align=right><BIG><b>NO. MR:&nbsp;&nbsp;&nbsp;&nbsp;</b></BIG><BIG><BIG><BIG><B><?=formatRegNo($d->no_mr);?>&nbsp;&nbsp;&nbsp;</B></BIG></BIG></BIG></td>
</tr>
<tr>
    <td colspan=0>

    <table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td width="120">Tanggal Pencatatan</td>
        <td>:</td>
        <td><?=$d->tgl?></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>Nama</td>
        <td>:</td>
        <td><?=$d->nama?></td>
    </tr>
	<? if ($jns == "I"){ ?>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>Waktu Makan</td>
        <td>:</td>
        <td><?=$waktu?></td>
    </tr>
	<?}?>
     <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>Ruangan</td>
        <td>:</td>
        <td><?=$d->bangsal?></td>
    </tr>
	
	<tr><td colspan="4" align="center">&nbsp;</td></tr>
	<? if ($jns == "I"){ ?>
	<tr><td colspan="4" align="center"><b>Menu</b></td></tr>
	<?}else{?>
	<tr><td colspan="4" align="center"><b>Catatan Diet</b></td></tr>
	<?}?>
	 <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
      
		<?php if($waktu=='pagi') {?>
        <td align=center colspan="3"><textarea readonly rows="5"  cols="40" style="border-style:groove; letter-spacing:inherit; direction:inherit; font-weight:200"><?=$d->pagi?></textarea></td>
		<?php } ?>
		<?php if($waktu=='siang') {?>
        <td align=center colspan="3"><textarea readonly rows="5"  cols="40" style="border-style:groove; letter-spacing:inherit; direction:inherit; font-weight:200"><?=$d->siang?></textarea></td>
		<?php } ?>
		<?php if($waktu=='malam') {?>
        <td align=center colspan="3"><textarea readonly rows="5"  cols="40" style="border-style:groove; letter-spacing:inherit; direction:inherit; font-weight:200"><?=$d->malam?></textarea></td>
		<?php } ?>
		<?php if($waktu=='snack_1') {?>
       <td align=center colspan="3"><textarea readonly rows="5"  cols="40" style="border-style:groove; letter-spacing:inherit; direction:inherit; font-weight:200"><?=$d->snack1?></textarea></td>
		<?php } ?>
		<?php if($waktu=='snack2') {?>
        <td align=center colspan="3"><textarea readonly rows="5"  cols="40" style="border-style:groove; letter-spacing:inherit; direction:inherit; font-weight:200"><?=$d->snack2?></textarea></td>
		<?php } ?>
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
