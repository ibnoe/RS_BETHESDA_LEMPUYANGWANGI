<style>
#table-data{
	border:thin solid #000000;
	white-space:nowrap;
	}
	
</style>
<link rel="stylesheet" type='text/css' href="jquery-ui.custom.css"/>
<script type="text/javascript" src="plugin/jquery-1.8.2.js"></script>
<script type="text/javascript" src="plugin/jquery-ui.js"></script>
<script language="javascript">
$(document).ready(function(){
	$("input:text[name='dokter_asisten']").autocomplete({
		type:'GET',
		source:function(request,response){
		$.ajax({
		url:'./lib/getPegawai.php',
		data: {term : request.term},
		dataType : 'json',
		success : function(data){
		response(data);
			},
		});
	},
		selectFirst: true,
		select: function( event, ui ) {
		$("input:hidden[name='id_dokter_asisten']").val(ui.item.id);
		
		},		
		});	
	});
</script>
<?php
/**
 * Gema Perbangsa
 * 26 September 2013
 */ 
//ini_set('display_errors',1);
$PID = "lap_jasmed_operasi";
$SC = $_SERVER["SCRIPT_NAME"];
require_once 'lib/dbconn.php';
require_once 'lib/class.PgTable.php';
require_once 'lib/form.php';
require_once 'lib/functions.php';
if(!empty($_GET['input'])){
include 'operasi2.php';
}
else{
title("<img src='icon/keuangan-2.gif' align='absmiddle' > JASA MEDIS OPERASI & ANESTESI");

if($_GET['print']!=1){	
	$f = new Form($SC, "get", "name='form1'");
	$f->hidden("p", $PID);	
	$f->PgConn = $con;
	include('xxx');
	$id_dokter_asisten = (empty($_GET['dokter_asisten'])) ? null : $_GET['id_dokter_asisten'];
	$f->hidden("id_dokter_asisten", $id_dokter_asisten);
	$f->selectArray("tindakan","Tindakan", array(""=>"","op"=>"Operasi", "an"=>"Anestesi"),$_GET['tindakan'],null);
	$f->text("dokter_asisten","Dokter / Asisten",24,24,$_GET['dokter_asisten'],null);
	$f->text("pasien","No. Reg / No. CM / Nama",24,24,$_GET['pasien'],null);
	$f->submit ("TAMPILKAN");
	$f->execute();	
}
else{
	$ts_check_in1 = $_GET['tanggal1Y'].'-'.$_GET['tanggal1M'].'-'.$_GET['tanggal1D'];
	$ts_check_in2 = $_GET['tanggal2Y'].'-'.$_GET['tanggal2M'].'-'.$_GET['tanggal2D'];
	$id_dokter_asisten = (empty($_GET['id_dokter_asisten'])) ? null : $_GET['id_dokter_asisten'];
	?>
	<table>
		<tr>
			<td>Dari Tanggal</td><td>:</td><td><?php echo $_GET['tanggal1D'].'-'.$_GET['tanggal1M'].'-'.$_GET['tanggal1Y'];?></td>
		</tr>
		<tr>
			<td>s/d</td><td>:</td><td><?php echo $_GET['tanggal2D'].'-'.$_GET['tanggal2M'].'-'.$_GET['tanggal2Y'];?></td>
		</tr>
		<tr>
			<td>Tindakan</td><td>:</td><td><?php if($_GET['tindakan']=='op'){ echo 'Operasi';}else if($_GET['tindakan']=='an'){ echo 'Anestesi';}else{ echo 'Operasi & Anestesi';}?></td>
		</tr>
		<tr>
			<td>Dokter / Asisten</td><td>:</td><td><?php echo getFromTable("SELECT nama FROM rs00017 WHERE id = ".$id_dokter_asisten);?></td>
		</tr>
		<tr>
			<td>No. Reg / No. CM / Nama</td><td>:</td><td><?php echo $_GET['pasien'];?></td>
		</tr>
	</table>
	<?php
	}
$pasien = (empty($_GET['pasien'])) ? null: "AND (a.no_reg LIKE '%".$_GET['pasien']."%' OR a.mr_no LIKE '%".$_GET['pasien']."%' OR a.nama ILIKE '%".$_GET['pasien']."%')";
$dokter_asisten = (empty($_GET['id_dokter_asisten']) || empty($_GET['dokter_asisten'])) ? null : ' AND (c.id = '.$_GET['id_dokter_asisten'].' OR e.id = '.$_GET['id_dokter_asisten'].' 
OR g.id ='.$_GET['id_dokter_asisten'].' OR i.id = '.$_GET['id_dokter_asisten'].' OR k.id = '.$_GET['id_dokter_asisten'].')';
if($_GET['tindakan']=='op'){
$hierarchy = "AND hierarchy LIKE '004003%'";	
	}
else if($_GET['tindakan']=='an'){
$hierarchy = "AND hierarchy LIKE '004004%'";		
	}	
$sql = "SELECT tanggal(a.tanggal_trans,0) AS tanggal_trans,a.item_id,a.mr_no,a.no_reg,a.tdesc, a.nama, a.layanan, a.harga, a.diskon, a.dibayar_penjamin,a.tagihan, 
CASE WHEN a.status = 1 THEN 'SUDAH DIINPUT' ELSE 'BELUM DIINPUT' END AS status_input, a.id, a.referensi, a.persen, a.no_kwitansi,
c.id AS id_dokter1,c.nama AS dokter1,b.persen AS persen_dokter1, b.diskon AS diskon_dokter1, b.terima AS terima_dokter1,
e.id AS id_dokter2, e.nama AS dokter2,d.persen AS persen_dokter2, d.diskon AS diskon_dokter2, d.terima AS terima_dokter2,
g.id AS id_asisten1, g.nama AS asisten1,f.persen AS persen_asisten1, f.diskon AS diskon_asisten1, f.terima AS terima_asisten1,
i.id AS id_asisten2, i.nama AS asisten2,h.persen AS persen_asisten2, h.diskon AS diskon_asisten2, h.terima AS terima_asisten2,
k.id AS id_asisten3, k.nama AS asisten3,j.persen AS persen_asisten3, j.diskon AS diskon_asisten3, j.terima AS terima_asisten3

FROM tindakan_operasi_rsv a 
LEFT JOIN rs00008_op2 b ON a.id = b.id_rs08 AND b.status_penindak = 'OP1'
LEFT JOIN rs00017 c ON b.id_rs17 = c.id 
LEFT JOIN rs00008_op2 d ON a.id = d.id_rs08 AND d.status_penindak = 'OP2'
LEFT JOIN rs00017 e ON d.id_rs17 = e.id 
LEFT JOIN rs00008_op2 f ON a.id = f.id_rs08 AND f.status_penindak = 'AST1'
LEFT JOIN rs00017 g ON f.id_rs17 = g.id 
LEFT JOIN rs00008_op2 h ON a.id = h.id_rs08 AND h.status_penindak = 'AST2'
LEFT JOIN rs00017 i ON h.id_rs17 = i.id
LEFT JOIN rs00008_op2 j ON a.id = j.id_rs08 AND j.status_penindak = 'AST3'
LEFT JOIN rs00017 k ON j.id_rs17 = k.id
WHERE a.tanggal_trans BETWEEN '".$ts_check_in1."' AND '".$ts_check_in2."' ".$dokter_asisten." ".$hierarchy." ".$pasien; 
//echo '<pre>'.$sql.'</pre>';
$result = pg_query($sql);
title_excel('lap_jasmed_operasi&tanggal1D='.$_GET['tanggal1D'].'&tanggal1M='.$_GET['tanggal1M'].'&tanggal1Y='.$_GET['tanggal1Y'].'
&tanggal2D='.$_GET['tanggal2D'].'&tanggal2M='.$_GET['tanggal2M'].'&tanggal2Y='.$_GET['tanggal2Y'].'&dokter_asisten='.$_GET['dokter_asisten'].'&id_dokter_asisten='.$_GET['id_dokter_asisten'].'&tindakan='.$_GET['tindakan'].'&pasien='.$_GET['pasien'].'&print=1');
?>
<table id="tabel-data" width="100%" border="1" cellspacing="0" cellpadding="5">
	<thead>
		<tr>
			<td class="TBL_HEAD" align="center" rowspan="2">NO.</td><td class="TBL_HEAD" align="center" rowspan="2">NO. REG</td>
			<td class="TBL_HEAD" align="center" rowspan="2">NO. CM</td><td class="TBL_HEAD" align="center" rowspan="2">NAMA PASIEN</td>
			<td class="TBL_HEAD" align="center" rowspan="2">TINDAKAN</td><td class="TBL_HEAD" align="center" rowspan="2">TARIF OP</td>
			<td class="TBL_HEAD" align="center" rowspan="2">POTONGAN</td><td class="TBL_HEAD" align="center" rowspan="2">RS (10%)</td>
			<td class="TBL_HEAD" align="center" rowspan="2">RS (2.5%)</td><td class="TBL_HEAD" align="center" rowspan="2">NON MEDIS (2.5%)</td>
			<td class="TBL_HEAD" align="center" rowspan="2">ZAKAT (2.5%)</td><td class="TBL_HEAD" align="center" colspan="2">TERIMA</td>
			<td class="TBL_HEAD" align="center" rowspan="2">ASISTEN OP</td><td class="TBL_HEAD" align="center" rowspan="2">RS (5%)</td>
			<td class="TBL_HEAD" align="center" rowspan="2">RS (2.5%)</td><td class="TBL_HEAD" align="center" rowspan="2">NON MEDIS (2.5%)</td>
			<td class="TBL_HEAD" align="center" rowspan="2">ZAKAT (2.5%)</td><td class="TBL_HEAD" align="center" colspan="3">TERIMA</td>
			<?php if ($_GET['print']!=1){ ?>
			<td class="TBL_HEAD" align="center" rowspan="2">&nbsp;</td>
			<?php } ?>
		</tr>
		<tr>
			<td class="TBL_HEAD" align="center">DOKTER 1</td><td class="TBL_HEAD" align="center">DOKTER 2</td>
			<td class="TBL_HEAD" align="center">ASISTEN 1</td><td class="TBL_HEAD" align="center">ASISTEN 2</td>
			<td class="TBL_HEAD" align="center">ASISTEN 3</td>
		</tr>
	</thead>
	<tbody  class="table-body-box">
<?php
$i=1;
$cari = getFromTable("SELECT nama FROM rs00017 WHERE id = ".$_GET['id_dokter_asisten']);
while($row = pg_fetch_array($result)){
	$jasmedOp = getJasmedOp($row);
	$jumlahPotonganOp += $jasmedOp['potongan'];
	$jumlahRS10Op += $jasmedOp['rs_10'];
	$jumlahRS025Op += $jasmedOp['rs_025'];
	$jumlahNonMedisOp += $jasmedOp['non_medis'];
	$jumlahZakatOp += $jasmedOp['zakat'];
	$jumlahTerimaDokter += $jasmedOp['terima_dokter'];
	$jumlahPotonganAsst += $jasmedOp['asst_potongan'];
	$jumlahRS05As += $jasmedOp['asst_rs_05'];
	$jumlahRS025As += $jasmedOp['asst_rs_025'];
	$jumlahNonMedisAs += $jasmedOp['asst_non_medis'];
	$jumlahZakatAs += $jasmedOp['asst_zakat'];
	$jumlahTerimaAsisten += $jasmedOp['terima_asisten'];
	?>
		<tr>
			<td rowspan="4"><?php echo $i++;?>.</td><td colspan="20"><?php echo $row['tanggal_trans'];?></td>
		<?php if ($_GET['print']!=1){ ?>
			<td align="center" rowspan="4"><a href="./index2.php?p=<?php echo $PID.'&input='.$row['id'];?>"><?php echo icon('view','view');?></a></td>
		<?php } ?>
		</tr>
		<tr>
			<td><?php echo $row['no_reg'];?></td>
			<td><?php echo $row['mr_no'];?></td>
			<td><?php echo $row['nama'];?></td>
			<td><?php echo $row['layanan'];?></td>
			<td align="right"><?php echo number_format($row['tagihan'],2,'.','');?></td>	
			<td align="right"><?php echo number_format($jasmedOp['potongan'],2,'.','');?></td>
			<td align="right"><?php echo number_format($jasmedOp['rs_10'],2,'.','');?></td>
			<td align="right"><?php echo number_format($jasmedOp['rs_025'],2,'.','');?></td>
			<td align="right"><?php echo number_format($jasmedOp['non_medis'],2,'.','');?></td>
			<td align="right"><?php echo number_format($jasmedOp['zakat'],2,'.','');?></td>		
			<td align="right" colspan="2"><?php echo number_format($jasmedOp['terima_dokter'],2,'.','');?></td>
			<td align="right"><?php echo number_format($jasmedOp['asst_potongan'],2,'.','');?></td>
			<td align="right"><?php echo number_format($jasmedOp['asst_rs_05'],2,'.','');?></td>
			<td align="right"><?php echo number_format($jasmedOp['asst_rs_025'],2,'.','');?></td>
			<td align="right"><?php echo number_format($jasmedOp['asst_non_medis'],2,'.','');?></td>
			<td align="right"><?php echo number_format($jasmedOp['asst_zakat'],2,'.','');?></td>		
			<td align="right" colspan="3"><?php echo number_format($jasmedOp['terima_asisten'],2,'.','');?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right">&nbsp;<?php echo $row['terima_dokter1'];?></td>
			<td align="right">&nbsp;<?php echo $row['terima_dokter2'];?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right">&nbsp;<?php echo $row['terima_asisten1'];?></td>
			<td align="right">&nbsp;<?php echo $row['terima_asisten2'];?></td>
			<td align="right">&nbsp;<?php echo $row['terima_asisten3'];?></td>		
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;<?php echo $row['dokter1'];?></td>
			<td>&nbsp;<?php echo $row['dokter2'];?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;<?php echo $row['asisten1'];?></td>
			<td>&nbsp;<?php echo $row['asisten2'];?></td>
			<td>&nbsp;<?php echo $row['asisten3'];?></td>		
		</tr>
	
	<?php
	}	
?>	
		<tr>
			<td><b>TOTAL</b></td>
			<td>:</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>	
			<td align="right"><?php echo number_format($jumlahPotonganOp,2,'.','');?></td>
			<td align="right"><?php echo number_format($jumlahRS10Op,2,'.','');?></td>
			<td align="right"><?php echo number_format($jumlahRS025Op,2,'.','');?></td>
			<td align="right"><?php echo number_format($jumlahNonMedisOp,2,'.','');?></td>
			<td align="right"><?php echo number_format($jumlahZakatOp,2,'.','');?></td>		
			<td align="right" colspan="2"><?php echo number_format($jumlahTerimaDokter,2,'.','');?></td>
			<td align="right"><?php echo number_format($jumlahPotonganAsst,2,'.','');?></td>
			<td align="right"><?php echo number_format($jumlahRS05As,2,'.','');?></td>
			<td align="right"><?php echo number_format($jumlahRS025As,2,'.','');?></td>
			<td align="right"><?php echo number_format($jumlahNonMedisAs,2,'.','');?></td>
			<td align="right"><?php echo number_format($jumlahZakatAs,2,'.','');?></td>		
			<td align="right" colspan="3"><?php echo number_format($jumlahTerimaAsisten,2,'.','');?></td>
			<td>&nbsp;<?php echo $row['asisten3'];?></td>		
		</tr>
<?php if(!empty($id_dokter_asisten)){?>
		<tr>
			<td colspan="6"><b>TOTAL TERIMA (<?php echo getFromTable("SELECT nama FROM rs00017 WHERE id = ".$id_dokter_asisten); ?>)</b></td>
			<td align="center">:</td>
			<td colspan="2" align="right"><b><?php echo getFromTable("SELECT SUM(terima)AS terima FROM rs00008_op2 WHERE id_rs17  = ".$id_dokter_asisten." AND status_penindak IN ('OP1','OP2')")
							    +
							    getFromTable("SELECT CASE WHEN id_rs17 IN(49, 82, 190, 198) THEN SUM(terima)*0.25+450000 
											  WHEN id_rs17 IN(2) THEN SUM(terima)*0.5
											  ELSE SUM(terima) END AS terima FROM rs00008_op2 
											  WHERE id_rs17 = ".$id_dokter_asisten." AND status_penindak IN ('AST1','AST2','AST3') 
											  GROUP BY id_rs17");?>
										  </b>
			</td>
			<td colspan="12">&nbsp;</td>
<?php if ($_GET['print']!=1){ ?>
			<td>&nbsp;</td>
<?php } ?>
		</tr>
<?php } ?>	
	</tbody>
</table>
<?php
}

function getJasmedOp($row){
$jasmed = array();	
$row['tagihan']=(double)$row['tagihan'];
$jasmed['potongan']	= $row['tagihan']*0.77;
$jasmed['rs_10']	= $jasmed['potongan']*0.1;
$jasmed['rs_025']	= $jasmed['potongan']*0.025;
$jasmed['non_medis']	= $jasmed['potongan']*0.025;
$jasmed['zakat']	= ($jasmed['potongan']-$jasmed['rs_10']-$jasmed['rs_025']-$jasmed['non_medis'])*0.025;
$jasmed['terima_dokter'] = $jasmed['potongan']-$jasmed['rs_10']-$jasmed['rs_025']-$jasmed['non_medis']-$jasmed['zakat'];
$jasmed['asst_potongan'] = $row['tagihan']-$jasmed['potongan'];
$jasmed['asst_rs_05'] = $jasmed['asst_potongan']*0.05;
$jasmed['asst_rs_025'] = $jasmed['asst_potongan']*0.025;
$jasmed['asst_non_medis'] = $jasmed['asst_potongan']*0.025;
$jasmed['asst_zakat'] = $jasmed['asst_potongan']*0.025;
$jasmed['terima_asisten'] = $jasmed['asst_potongan']-$jasmed['asst_rs_05']-$jasmed['asst_rs_025']-$jasmed['asst_non_medis']-$jasmed['asst_zakat'];
return $jasmed;	
}
?>
