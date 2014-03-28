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
$Judul_kartu	   = "KARTU BEROBAT";

?>

<h3TML>

<h3EAD>
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


</h3EAD>

<BODY TOPMARGIN=5 LEFTMARGIN=5 MARGINWIDTH=0 MARGINHEIGHT=0>

<?
$reg = $_GET["rg"];
//$reg = str_pad(((int) $_GET["rg"]), 6, "0", STR_PAD_LEFT);

$r = pg_query($con,"select nama,umur,to_char(tgl_lahir,'dd/mm/yyyy') as tgl_lahir,jenis_kelamin,nama_ayah,to_char(tgl_reg,'dd/mm/yyyy') as tgl_reg,lower(alm_tetap) as alm_tetap,nama_ibu,
case status_nikah
	when '001' then 'T'
	else 'Y'
end as status_nikah,
case agama_id 
	when '001' then 'Islam'
	when '002' then 'Kristen'
	when '003' then 'Katolik'
	when '004' then 'Hindu'
	when '005' then 'Budha'
	else 'Keprc. Lain'
end as agama
from rs00002 where mr_no='$reg'");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);

 
?>
<br>
<br>
<div style="position:absolute;top:40;left:50"><h1><?=$reg?></div>
<div style="position:absolute;top:80;left:50"><h4><?=$d->nama?></h4></div>
<div style="position:absolute;top:100;left:50"><h3><?=$d->jenis_kelamin?></h3></div>
<div style="position:absolute;top:100;left:100"><h4><?=$d->umur?></h4></div>
<div style="position:absolute;top:100;left:150"><h5><?=$d->tgl_lahir?></h5></div>

<table border="0" style="position:absolute;top:140;left:50"><tr><td width="200"><h6><?=$d->alm_tetap?></h6></td></tr></table>
<div style="position:absolute;top:100;left:300"><h4><?=$d->status_nikah?></h4></div>
<div style="position:absolute;top:100;left:350"><h4><?=$d->agama?></h4></div>

<div style="position:absolute;top:150;left:50"><h5><?=$d->tgl_reg?></h5></div>
<!--/* <table border="1">
	<tr>
		<td><?=$reg?></td>
	</tr>
	<tr>
		<td><?=$d->nama?></td>
		<td><?=$d->jenis_kelamin?></td>
		<td><?=$d->umur?></td>
		<td><?=$d->tgl_lahir?></td>
		<td><?=$reg?></td>
	</tr>
	<tr>
		<td><?=$d->alm_tetap?></td>
		<td><?=$d->status_nikah?></td>
		<td><?=$d->agama?></td>
		<td><?=$d->tgl_lahir?></td>
		<td><?=$reg?></td>
	</tr>
</table> */-->
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
printWindow();
//  End -->
</script>

</body>
</h3tml>
