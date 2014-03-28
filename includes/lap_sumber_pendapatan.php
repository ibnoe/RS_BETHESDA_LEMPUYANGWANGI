<link rel="stylesheet" type='text/css' href="jquery-ui.custom.css"/>
<script type="text/javascript" src="plugin/jquery-1.8.2.js"></script>
<script type="text/javascript" src="plugin/jquery-ui.js"></script>	
<?php

$PID = 'lap_sumber_pendapatan';
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
title("<img src='icon/keuangan-2.gif' align='absmiddle' > LAPORAN SUMBER PENDAPATAN");
if(!$GLOBALS['print']){
	$f = new Form($SC, "GET", "NAME=Form1");
	$f->PgConn = $con;
	$f->hidden("p", $PID);
	include 'xxx2';
	$f->selectSQL("sbpx", "Sumber Pendapatan",
			"select '' as tc, ' ' as tdesc union ".
			"select tc, tdesc ".
			"from rs00001  ".
			"where tt='SBP' and tc != '000' order by tdesc", $_GET["sbpx"],
			null);
	$f->text("item","Layanan / Item",null, null, $_GET['item'],null);
	$f->selectArray("status_bayar", "Status Bayar",Array(""=>"", "Y" => "SUDAH BAYAR", "N" => "BELUM BAYAR"),$_GET['status_bayar'], null);
	$f->selectArray("rawatan", "Rawatan",Array(""=>"", "I" => "RAWAT INAP", "Y" => "RAWAT JALAN", "N" => "IGD"),$_GET['rawatan'], null);
	$f->text("pasien","No. Reg / No. CM / Nama",null, null, $_GET['pasien'],null);
	$f->selectSQL("mPASIEN", "Tipe Pasien",
			"select '' as tc, ' ' as tdesc union ".
			"select tc, tdesc ".
			"from rs00001  ".
			"where tt='JEP' and tc != '000' order by tdesc", $_GET["mPASIEN"],
			null);
	$f->submit ("TAMPILKAN"); 
	$f->execute();
	title_excel(str_replace('p=','',$QS));
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
			<td>Sumber Pendapatan</td><td>:</td><td><?php echo (empty($_GET['item'])) ? 'Semua Sumber Pendapatan' : $_GET['item'];?></td>
		</tr>
		<tr>
			<td>Layanan / Item</td><td>:</td><td><?php echo (empty($_GET['item'])) ? 'Semua Layanan / Item' : $_GET['item'];?></td>
		</tr>
		<tr>
			<td>Status Bayar</td><td>:</td><td><?php echo (empty($_GET['status_bayar'])) ? 'Semua Status' : (($_GET['status_bayar']=='Y') ? 'Sudah Bayar' : null);?></td>
		</tr>
		<tr>
			<td>No. Reg / No. CM / Nama</td><td>:</td><td><?php echo (empty($_GET['pasien'])) ? 'Semua Pasien' : $_GET['pasien'];?></td>
		</tr>		
		<tr>
			<td>Tipe Pasien</td><td>:</td><td><?php echo (empty($_GET['mPASIEN'])) ? 'Semua Tipe Pasien' : getFromTable("SELECT tdesc FROM rs00001 WHERE tt='JEP' AND tc='".$_GET['mPASIEN']."'");?></td>
		</tr>
	</table>
	<?php
}
$t = new PgTable($con, "100%");
$t->setlocale("id_ID");
$t->ColHeader  = array("TANGGAL","NO.REG", "NO.CM", "NAMA", "TIPE PASIEN", "RAWATAN", "SUMBER PENDAPATAN","LAYANAN / ITEM", "HARGA", "QTY", "DISKON", "DIBAYAR</br>PENJAMIN", "TAGIHAN","PENINDAK","RINCIAN KASIR");

$SQL_mPASIEN = (!empty($_GET['mPASIEN'])) ? " AND c.tipe_pasien = '".$_GET['mPASIEN']."' " : null ;
$SQL_no_reg = (!empty($_GET['pasien'])) ? " AND (c.nama ILIKE '%".$_GET['pasien']."%' OR c.mr_no ILIKE '%".$_GET['pasien']."%' OR b.id ILIKE '%".$_GET['pasien']."%') " : null ;
$SQL_sbpx = (!empty($_GET['sbpx'])) ? " AND e.sumber_pendapatan_id = '".$_GET['sbpx']."'" : null ;
$SQL_item_id = (!empty($_GET['item'])) ? " AND e.layanan ILIKE '%".$_GET['item']."%'" : null ;
$SQL_rawatan = (!empty($_GET['rawatan'])) ? " AND b.rawat_inap = '".$_GET['rawatan']."'" : null ;
$SQL_status_bayar = (!empty($_GET['status_bayar'])) ? " AND a.is_bayar = '".$_GET['status_bayar']."'" : null ;
$SQL = "SELECT tanggal(a.tanggal_trans,2), b.id, c.mr_no, c.nama, d.tdesc AS tipe_pasien, 
CASE WHEN b.rawat_inap = 'I' THEN 'RAWAT INAP' 
WHEN b.rawat_inap = 'Y' THEN 'RAWAT JALAN' 
WHEN b.rawat_inap = 'N' THEN 'IGD' END AS rawatan, f.tdesc AS sumber_pendapatan, e.layanan, a.harga, a.qty, a.diskon, 
COALESCE(a.dibayar_penjamin,0) AS dibayar_penjamin, (a.tagihan-COALESCE(a.dibayar_penjamin,0)) AS tagihan, g.nama,
CASE WHEN b.rawat_inap = 'I' THEN
				'rg=' || a.no_reg || '&sub=3&kas=ri'
				WHEN b.rawat_inap = 'Y' THEN
				'rg=' || a.no_reg || '&sub=3&kas=rj'
				WHEN b.rawat_inap = 'N' THEN
				'rg=' || a.no_reg || '&sub=3&kas=igd'
				END AS kasir 
FROM rs00008 a JOIN rs00006 b ON a.no_reg = b.id JOIN rs00002 c ON b.mr_no = c.mr_no 
JOIN rs00001 d ON d.tt = 'JEP' AND d.tc = c.tipe_pasien JOIN rs00034 e ON a.item_id::numeric = e.id 
JOIN rs00001 f ON f.tt = 'SBP' AND f.tc = e.sumber_pendapatan_id
LEFT JOIN rs00017 g ON g.id = a.no_kwitansi
WHERE trans_type = 'LTM' AND a.tanggal_trans BETWEEN '".$ts_check_in1."' AND '".$ts_check_in2."' ".$SQL_mPASIEN.$SQL_no_reg.
$SQL_item_id.$SQL_sbpx.$SQL_rawatan.$SQL_status_bayar  ;
$t->SQL = $SQL;

if($GLOBALS['print']){
    $t->ColHeader[14] = '';
    $t->ColFormatHtml[14] = '';	 
    $t->RowsPerPage = pg_num_rows(pg_query($SQL));
    $t->DisableStatusBar = true;
}
else{
    $t->ColFormatHtml[14] = '<a href="./index2.php?p=335&<#14#>" target="_blank">'.icon('view','View').'</a>';
}
$SQL_SUM = "SELECT SUM((a.tagihan-COALESCE(a.dibayar_penjamin,0))) AS tagihan
FROM rs00008 a JOIN rs00006 b ON a.no_reg = b.id JOIN rs00002 c ON b.mr_no = c.mr_no 
JOIN rs00001 d ON d.tt = 'JEP' AND d.tc = c.tipe_pasien JOIN rs00034 e ON a.item_id::numeric = e.id 
JOIN rs00001 f ON f.tt = 'SBP' AND f.tc = e.sumber_pendapatan_id
LEFT JOIN rs00017 g ON g.id = a.no_kwitansi
WHERE trans_type = 'LTM' AND a.tanggal_trans BETWEEN '".$ts_check_in1."' AND '".$ts_check_in2."' ".$SQL_mPASIEN.$SQL_no_reg.
$SQL_item_id.$SQL_sbpx.$SQL_rawatan.$SQL_status_bayar ;

$jumlah = pg_fetch_array(pg_query($SQL_SUM ));	 
$t->ColAlign[14] = 'CENTER';
$t->ColFooter [12]=  number_format($jumlah['tagihan'],2,',','.');
$t->execute();
