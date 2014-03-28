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

$r = pg_query($con,"select b.mr_no, b.nama, b.tgl_lahir, b.jenis_kelamin,  b.umur, b.alm_tetap ".
				   "from  rs00002 as b ".	 
				   "where b.mr_no = '$reg' ");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);

 
?>

<!--<div style="poposition:absolute;top:100;left:1100;size=20px"><h2>terserah aja</h2></div>
<div style="position:absolute;top:0;left:800;size=20x"><h2>sasasa</h2></div>
<div style="pposition:absolute;top:30;left:800;size=20px"><h2>dsadsad</h2></div>-->

<div style="position:absolute;top:30;right:0"><h3><?=$d->mr_no?></h3></div>
<div style="position:absolute;top:-30;left:400"><h3><?=$d->nama?></h3></div>
<div style="position:absolute;top:0;left:400;"><h3><?=$d->alm_tetap?></h3></div>


<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
printWindow();
//  End -->
</script>

</body>
</html>
