<link rel="stylesheet" type='text/css' href="jquery-ui.custom.css"/>
<script type="text/javascript" src="plugin/jquery-1.8.2.js"></script>
<script type="text/javascript" src="plugin/jquery-ui.js"></script>	
<?php

$PID = 'lap_asuhan_keperawatan';
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
title("<img src='icon/keuangan-2.gif' align='absmiddle' > LAPORAN ASUHAN KEPERAWATAN");
$exclude_hierarchy = " AND a.hierarchy NOT LIKE '004003%' AND a.hierarchy NOT LIKE '004004%' AND a.hierarchy NOT LIKE '004005%' AND a.hierarchy NOT LIKE '001%' AND a.hierarchy NOT LIKE '006%' AND a.hierarchy NOT LIKE '00401%' AND a.hierarchy NOT LIKE '004009%' AND a.hierarchy NOT LIKE '003002%'";
if(!empty($_GET['view'])){
	$ts_check_in1 = $_GET['tanggal1Y'].'-'.$_GET['tanggal1M'].'-'.$_GET['tanggal1D'];
	$ts_check_in2 = $_GET['tanggal2Y'].'-'.$_GET['tanggal2M'].'-'.$_GET['tanggal2D'];
	if($GLOBALS['print']){
	?>
	<table>
		<tr>
			<td>Tanggal</td><td>:</td><td><?php echo date('d/m/Y',strtotime($ts_check_in1)).' s/d '.date('d/m/Y',strtotime($ts_check_in2));?></td>
		</tr>
		<tr>
			<td>Jabatan Medis</td><td>:</td><td><?php echo $_GET['jab'];?></td>
		</tr>
		<tr>
			<td>Rawatan</td><td>:</td><td><?php echo $_GET['view'];?></td>
		</tr>
		<tr>
			<td colspan="3"><input type="submit" value="Simpan"/></td>
		</tr>
	</table>
	<?php
	}
	else{
		$f = new Form($SC, "GET", "NAME=Form1");
		$f->PgConn = $con;
		$f->hidden("p", $PID);
		$f->hidden("view",$_GET['view']);
		$f->hidden("jab",$_GET['jab']);
		include 'xxx2';
		$f->plain_text("Rawatan", $_GET['rawatan']);
		$f->plain_text("Jabatan", $_GET['jab']);
		$f->text("layanan","Layanan / Item",null, null, $_GET['layanan'],null);
		$f->submit("TAMPILKAN");
		$f->execute();
		}
	if(!$GLOBALS['print']){
		title_excel(str_replace('p=','',$_SERVER['QUERY_STRING']));
	}
	$t = new PgTable($con, "100%");
	$t->setlocale("id_ID");
	$t->ColHeader  = array("TANGGAL","NO. REG","NO. CM", "NAMA", "LAYANAN", "TAGIHAN", "DISKON", "JASA PERAWAT", "PENINDAK","RINCIAN KASIR");
	
	$SQL_layanan = (!empty($_GET['layanan'])) ? " AND a.layanan ILIKE '%".$_GET['layanan']."%'" : null ;
	$SQL_no_reg = (!empty($_GET['pasien'])) ? " AND (c.nama ILIKE '%".$_GET['pasien']."%' OR c.mr_no ILIKE '%".$_GET['pasien']."%' OR c.no_reg ILIKE '%".$_GET['pasien']."%') " : null ;
	$SQL_item_id = (!empty($_GET['layanan'])) ? " AND a.layanan ILIKE '%".$_GET['layanan']."%'" : null ;
	$SQL_jabatan = (!empty($_GET['jab'])) ? "AND a.jabatan_medis_fungsional = '".$_GET['jab']."'": "AND a.jabatan_medis_fungsional IS NULL" ;
	$SQL = "SELECT  tanggal(a.tanggal_trans,2) AS tanggal, a.no_reg, a.mr_no,a.nama, a.layanan, a.tagihan, a.diskon, a.jasmed[2] AS jasa_perawat, a.nama_dokter,
			CASE WHEN rawat_inap = 'RAWAT INAP' THEN
			'rg=' || a.no_reg || '&sub=3&kas=ri'
			WHEN rawat_inap = 'RAWAT JALAN' THEN
			'rg=' || a.no_reg || '&sub=3&kas=rj'
			WHEN rawat_inap = 'IGD' THEN
			'rg=' || a.no_reg || '&sub=3&kas=igd'
			END AS kasir
			FROM rsv_layanan_non_paket_pasien a WHERE is_bayar = 'Y' AND a.is_bayar = 'Y' 
			AND (a.jabatan_medis_fungsional LIKE '%DOKTER%' OR a.jabatan_medis_fungsional LIKE 'PERAWAT' OR a.jabatan_medis_fungsional IS NULL)
			AND a.tipe_pasien ILIKE '%umum%' AND a.tanggal_trans BETWEEN '".$ts_check_in1."' AND '".$ts_check_in2."' 
			".$SQL_jabatan." AND a.rawat_inap = '".$_GET['view']."' ".$SQL_item_id.$exclude_hierarchy  ;
	$t->SQL = $SQL;
	//echo $SQL;
	if($GLOBALS['print']){
		$t->RowsPerPage = pg_num_rows(pg_query($SQL));
		$t->DisableStatusBar = true;
		$t->ColFormatHtml[9] = '';
		$t->ColHeader[9] = '';
	}
	else{
		$t->ColFormatHtml[9] = '<a href="./index2.php?p=335&<#9#>" target="_blank">'.icon('view','View').'</a>';
		$t->ColAlign[9] = "CENTER";	
	}
	$SQL_SUM = "SELECT  SUM(a.jasmed[2]) AS jasa_perawat
				FROM rsv_layanan_non_paket_pasien a WHERE is_bayar = 'Y' AND a.is_bayar = 'Y' 
				AND (a.jabatan_medis_fungsional LIKE '%DOKTER%' OR a.jabatan_medis_fungsional LIKE 'PERAWAT' OR a.jabatan_medis_fungsional IS NULL)
				AND a.tipe_pasien ILIKE '%umum%' AND a.tanggal_trans BETWEEN '".$ts_check_in1."' AND '".$ts_check_in2."' 
				".$SQL_jabatan." AND a.rawat_inap = '".$_GET['view']."' ".$SQL_item_id.$exclude_hierarchy ;

	$jumlah = pg_fetch_array(pg_query($SQL_SUM ));
	$t->ColFooter [7] = number_format($jumlah['jasa_perawat'],2,',','.');
	$t->execute();
}
else{
	if(!$GLOBALS['print']){
		$f = new Form($SC, "GET", "NAME=Form1");
		$f->PgConn = $con;
		$f->hidden("p", $PID);
		include 'xxx2';
		$f->selectArray("rawatan", "Rawatan",Array(""=>"", "RAWAT INAP" => "RAWAT INAP", "RAWAT JALAN" => "RAWAT JALAN", "IGD" => "IGD"),$_GET['rawatan'], null);
		//$f->text("pasien","No. Reg / No. CM / Nama",null, null, $_GET['pasien'],null);
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
				<td>Rawatan</td><td>:</td><td><?php echo (empty($_GET['rawatan'])) ? 'Semua Rawatan' : $_GET['rawatan'];?></td>
			</tr>
		</table>
		<?php
	}
	$t = new PgTable($con, "100%");
	$t->setlocale("id_ID");
	$t->ColHeader  = array("JABATAN PENINDAK","RAWATAN", "TAGIHAN", "DISKON", "JASA PERAWAT","VIEW");
	$SQL_rawatan = (!empty($_GET['rawatan'])) ? " AND a.rawat_inap = '".$_GET['rawatan']."'" : null ;
	$SQL_status_bayar = (!empty($_GET['status_bayar'])) ? " AND a.is_bayar = '".$_GET['status_bayar']."'" : null ;
	$SQL = "SELECT  a.jabatan_medis_fungsional, a.rawat_inap, SUM(a.tagihan) AS tagihan, SUM(a.diskon) AS diskon, SUM(a.jasmed[2]) AS jasa_perawat, a.rawat_inap
			FROM rsv_layanan_non_paket_pasien a WHERE a.is_bayar = 'Y' and a.jabatan_medis_fungsional is not null
			AND (a.jabatan_medis_fungsional LIKE '%DOKTER%' OR a.jabatan_medis_fungsional LIKE 'PERAWAT' OR a.jabatan_medis_fungsional IS NULL)
			AND a.tipe_pasien ILIKE '%umum%' AND a.tanggal_trans BETWEEN '".$ts_check_in1."' AND '".$ts_check_in2."'".
			$SQL_rawatan.$exclude_hierarchy." GROUP BY a.jabatan_medis_fungsional, a.rawat_inap"  ;
	$t->SQL = $SQL;
	if($GLOBALS['print']){
		$t->RowsPerPage = pg_num_rows(pg_query($SQL));
		$t->DisableStatusBar = true;
		$t->ColHeader[5] = '';
		$t->ColAlign[5] = ''; 
		$t->ColFormatHtml[5] = '';
	}
	else{		
		$t->ColAlign[5] = 'CENTER'; 
		$t->ColFormatHtml[5] = '<a href="./index2.php?'.$QS.'&view=<#1#>&jab=<#0#>">'.icon('view','View').'</a>';		
	}
	
	$SQL_SUM = "SELECT  SUM(a.jasmed[2]) AS jasa_perawat
				FROM rsv_layanan_non_paket_pasien a WHERE a.is_bayar = 'Y' 
				AND (a.jabatan_medis_fungsional LIKE '%DOKTER%' OR a.jabatan_medis_fungsional LIKE 'PERAWAT' OR a.jabatan_medis_fungsional IS NULL)
				AND a.tipe_pasien ILIKE '%umum%' AND a.tanggal_trans BETWEEN '".$ts_check_in1."' AND '".$ts_check_in2."' ".$SQL_rawatan.$exclude_hierarchy;
	

	$jumlah = pg_fetch_array(pg_query($SQL_SUM ));
	$t->ColFooter [4] = number_format($jumlah['jasa_perawat'],2,',','.');
	$QS = $_SERVER['QUERY_STRING'];
	$t->execute();
}
