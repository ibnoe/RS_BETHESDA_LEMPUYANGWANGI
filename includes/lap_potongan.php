<link rel="stylesheet" type='text/css' href="jquery-ui.custom.css"/>
<script type="text/javascript" src="plugin/jquery-1.8.2.js"></script>
<script type="text/javascript" src="plugin/jquery-ui.js"></script>
<?php

$PID = 'lap_potongan';
/**
 * Gema Perbangsa
 * Oktober 2013
 */

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

$SC = $_SERVER["SCRIPT_NAME"];
$QS = $_SERVER["QUERY_STRING"];
title("<img src='icon/keuangan-2.gif' align='absmiddle' > LAPORAN POTONGAN");
if(!$GLOBALS['print']){
	$f = new Form($SC, "GET", "NAME=Form1");
	$f->PgConn = $con;
	$f->hidden("p", $PID);
	$f->hidden("item_id");
	include 'xxx2';
	$f->text("pasien","No. Reg / No. CM / Nama",null, null, $_GET['pasien'],null);
	$f->text("keterangan","Keterangan",null, null, $_GET['keterangan'],null);
	$f->selectSQL("mPASIEN", "Tipe Pasien",
			"select '' as tc, ' ' as tdesc union ".
			"select tc, tdesc ".
			"from rs00001  ".
			"where tt='JEP' and tc != '000' order by tdesc", $_GET["mPASIEN"],
			null);
	$f->selectArray('rawat_inap', 'Rawatan', array(''=>'', 'I'=>'RAWAT INAP','Y'=>'RAWAT JALAN','N'=>'IGD'), $_GET['rawat_inap']);
	$f->submit ("TAMPILKAN"); 
	$f->execute();
	title_excel(str_replace('p=','',$_SERVER['QUERY_STRING']));
}
else{
	$ts_check_in1 = $_GET["tanggal1Y"].'-'.$_GET["tanggal1M"].'-'.$_GET["tanggal1D"];
	$ts_check_in2 = $_GET["tanggal2Y"].'-'.$_GET["tanggal2M"].'-'.$_GET["tanggal2D"];
	?>
	<table>
		<tr>
			<td>Tanggal</td><td>:</td><td><?php echo date('d-m-Y', strtotime($ts_check_in1));?></td>
		</tr>
		<tr>
			<td>s/d Tanggal</td><td>:</td><td><?php echo date('d-m-Y', strtotime($ts_check_in2));?></td>
		</tr>
		<tr>
			<td>No. Reg / No. CM / Nama</td><td>:</td><td><?php echo (empty($_GET['pasien'])) ? 'Semua Pasien' : $_GET['pasien'];?></td>
		</tr>
		<?php if(!empty($_GET['keterangan'])){ ?>
		<tr>
			<td>Keterangan</td><td>:</td><td><?php echo $_GET['keterangan'];?></td>
		</tr>
		<?php } ?>
		<tr>
			<td>Tipe Pasien</td><td>:</td><td><?php echo (empty($_GET['mPASIEN'])) ? 'Semua Tipe Pasien' : getFromTable("SELECT tdesc FROM rs00001 WHERE tt='JEP' AND tc='".$_GET['mPASIEN']."'");?></td>
		</tr>
	</table>
	<?php
}
$t = new PgTable($con, "100%");
$t->setlocale("id_ID");
$t->ColHeader  = array("TANGGAL", "NO.KWITANSI", "NO. REG","NO. CM","NAMA", "TIPE PASIEN", "RAWATAN", "JUMLAH", "KETERANGAN", "USER</br>INPUT");

$SQL_mPASIEN = (!empty($_GET['mPASIEN'])) ? " AND b.tipe = '".$_GET['mPASIEN']."' " : " AND b.tipe LIKE '%%' " ;
$SQL_no_reg = (!empty($_GET['pasien'])) ? " AND (c.nama ILIKE '%".$_GET['pasien']."%' OR c.mr_no ILIKE '%".$_GET['pasien']."%' OR b.id ILIKE '%".$_GET['pasien']."%') " : null ;
$SQL_keterangan = (!empty($_GET['keterangan'])) ? " AND a.keterangan ILIKE '%".$_GET['keterangan']."%'" : null ;
$SQL_rawat_inap = (!empty($_GET['rawat_inap'])) ? " AND b.rawat_inap = '".$_GET['rawat_inap']."'" : null ; 
$SQL = "SELECT tanggal(a.tgl_entry,2),d.no_kwitansi,a.reg,c.mr_no,c.nama, 
e.tdesc,CASE 
WHEN b.rawat_inap = 'I' THEN 'RAWAT INAP'
WHEN b.rawat_inap = 'Y' THEN 'RAWAT JALAN'
WHEN b.rawat_inap = 'N' THEN 'RAWAT IGD' END AS rawatan,
a.jumlah,a.keterangan, f.nama FROM rs00005 a 
JOIN rs00006 b ON a.reg = b.id ".$SQL_mPASIEN.$SQL_rawat_inap."
JOIN rs00002 c ON b.mr_no = c.mr_no ".$SQL_no_reg."
JOIN rs00005 d ON a.reg = d.reg AND d.kasir IN ('BYR','BYD','BYI')
JOIN rs00001 e ON e.tt = 'JEP' AND e.tc = b.tipe
JOIN rs99995 f ON d.user_id = f.uid
WHERE a.kasir = 'POT'
AND a.is_bayar = 'Y' AND a.jumlah > 0 AND a.tgl_entry BETWEEN '".$ts_check_in1."' AND '".$ts_check_in2."'".$SQL_keterangan;
$t->SQL = $SQL;
if($GLOBALS['print']){
	$t->RowsPerPage = pg_num_rows(pg_query($SQL));
	$t->DisableStatusBar = true;
}
$SQL_SUM = "SELECT SUM(a.jumlah) AS jumlah FROM rs00005 a 
JOIN rs00006 b ON a.reg = b.id ".$SQL_mPASIEN.$SQL_rawat_inap."
JOIN rs00002 c ON b.mr_no = c.mr_no ".$SQL_no_reg."
JOIN rs00005 d ON a.reg = d.reg AND d.kasir IN ('BYR','BYD','BYI')
JOIN rs00001 e ON e.tt = 'JEP' AND e.tc = b.tipe
JOIN rs99995 f ON d.user_id = f.uid
WHERE a.kasir = 'POT'
AND a.is_bayar = 'Y' AND a.jumlah > 0 AND a.tgl_entry BETWEEN '".$ts_check_in1."' AND '".$ts_check_in2."'".$SQL_keterangan;
$jumlah = pg_fetch_array(pg_query($SQL_SUM ));
$t->ColFooter [7]=  number_format($jumlah['jumlah'],2,',','.');
$t->execute();
