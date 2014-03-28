<style>
table td{
	border:0 none;
	white-space:nowrap;
	}
</style>
<?php

/**
 * Gema Perbangsa
 * 21 September 2013
 */ 

//ini_set('display_errors',1);

$PID = "lap_operasi";
$SC = $_SERVER["SCRIPT_NAME"];

require_once 'lib/dbconn.php';
require_once 'lib/class.PgTable.php';
require_once 'lib/form.php';
require_once 'lib/functions.php';

title("<img src='icon/keuangan-2.gif' align='absmiddle' > LAPORAN OPERASI");
$f = new Form($SC, "get", "name='form1'");
$f->hidden("p", $PID);	
$f->PgConn = $con;
include('xxx');
$f->submit ("TAMPILKAN");
$f->execute();

$SQL = "SELECT j.nama, k.tdesc AS tipe_pasien,l.bangsal, j.alm_tetap,tanggal(a.tanggal_trans,3) AS tanggal_trans, a.trans_group,b.layanan, a.harga,a.referensi, a.tagihan, a.dibayar_penjamin, a.diskon, 
d.nama AS dokter1, c.diskon_dokter1,e.nama AS dokter2,c.diskon_dokter2,f.nama AS asisten1, c.diskon_asisten1,g.nama AS asisten2,c.diskon_asisten2,
h.nama AS asisten3,c.diskon_asisten3 FROM rs00008 a 
JOIN rs00034 b ON a.item_id::numeric = b.id AND a.trans_type = 'LTM'
JOIN rs00008_op c ON a.id = c.id_rs08
LEFT JOIN rs00017 d ON c.id_dokter1 = d.id
LEFT JOIN rs00017 e ON c.id_dokter2 = e.id
LEFT JOIN rs00017 f ON c.id_asisten1 = f.id
LEFT JOIN rs00017 g ON c.id_asisten2 = g.id
LEFT JOIN rs00017 h ON c.id_asisten3 = h.id 
JOIN rs00006 i ON a.no_reg = i.id 
JOIN rs00002 j ON i.mr_no = j.mr_no
JOIN rs00001 k ON i.tipe = k.tc AND k.tt = 'JEP'
LEFT JOIN rs00012 l ON l.hierarchy = (SELECT SUBSTR(hierarchy,1,6) FROM rs00012 WHERE id=a.bangsal_id) || '000000000'
WHERE a.tanggal_trans BETWEEN '$ts_check_in1' AND '$ts_check_in2' 
ORDER BY a.id";
 $result = pg_query($SQL);
 $i=0;
title_excel($PID.'&tanggal1D='.$_GET['tanggal1D'].'&tanggal1M='.$_GET['tanggal1M'].'&tanggal1Y='.$_GET['tanggal1Y'].
'&tanggal2D='.$_GET['tanggal2D'].'&tanggal2M='.$_GET['tanggal2M'].'&tanggal2Y='.$_GET['tanggal2Y'].'&print=1');

 ?>
 <div>
 <table border="3" cellspacing="1" cellpadding="5"  width="1000px" style="overflow-x:scroll;font-weight:bold;">
	<tr>
	 <th colspan="15">OPERASI</th><th colspan="14">ANESTESI</th>
	</tr>
	<tr>
	 <td align="center" class="TBL_HEAD">NO.</td>
	 <td align="center" class="TBL_HEAD">LAYANAN <br>TINDAKAN OPERASI</td><td align="center" class="TBL_HEAD"  width="250">TARIF</td><td align="center" class="TBL_HEAD">POTONGAN</td>
	 <td align="center" class="TBL_HEAD">RS (10%)</td><td align="center" class="TBL_HEAD">RS (2.5%)</td><td align="center" class="TBL_HEAD">NON MEDIS (2.5%)</td>
	 <td align="center" class="TBL_HEAD">ZAKAT (2.5%)</td><td align="center" class="TBL_HEAD">TERIMA</td>
	 <td align="center" class="TBL_HEAD">Asst. OP</td><td align="center" class="TBL_HEAD">RS (5%)</td><td align="center" class="TBL_HEAD">RS (2.5%)</td>
	 <td align="center" class="TBL_HEAD">NON MEDIS (2.5%)</td>
	 <td align="center" class="TBL_HEAD">ZAKAT (2.5%)</td><td align="center" class="TBL_HEAD">TERIMA</td>
	 
	 <td align="center" class="TBL_HEAD">LAYANAN <br>TINDAKAN ANESTESI</td><td align="center" class="TBL_HEAD">TARIF</td><td align="center" class="TBL_HEAD">POTONGAN</td>
	 <td align="center" class="TBL_HEAD">RS (10%)</td><td align="center" class="TBL_HEAD">RS (2.5%)</td><td align="center" class="TBL_HEAD">NON MEDIS (2.5%)</td>
	 <td align="center" class="TBL_HEAD">ZAKAT (2.5%)</td><td align="center" class="TBL_HEAD">TERIMA</td>
	 <td align="center" class="TBL_HEAD">Asst. OP</td><td align="center" class="TBL_HEAD">RS (5%)</td><td align="center" class="TBL_HEAD">RS (2.5%)</td>
	 <td align="center" class="TBL_HEAD">NON MEDIS (2.5%)</td>
	 <td align="center" class="TBL_HEAD">ZAKAT (2.5%)</td><td align="center" class="TBL_HEAD">TERIMA</td>
	</tr>
 <?php
 $i = 0;
 $count = pg_num_rows($result)-1;
 $j=1;
 while($i <= $count){
	 pg_result_seek($result, $i);
	 $row1 = pg_fetch_array($result);
	 $jasmedOp = getJasmedOp($row1);
	 $totalTarifOp += $row1['tagihan'];
	 $totalPotonganOp += $jasmedOp['potongan'];
	 $totalRs10Op += $jasmedOp['rs_10'];
	 $totalRs025Op += $jasmedOp['rs_025'];
	 $totalRsNonMedisOp += $jasmedOp['non_medis'];
	 $totalZakatOp += $jasmedOp['zakat'];
	 $totalTerimaOp += $jasmedOp['terima_dokter'];
	 
	 $totalPotonganOpAsst += $jasmedOp['asst_potongan'];
	 $totalRs05OpAsst += $jasmedOp['asst_rs_05'];
	 $totalRs025OpAsst += $jasmedOp['asst_rs_025'];
	 $totalRsNonMedisOpAsst += $jasmedOp['asst_non_medis'];
	 $totalZakatOpAsst += $jasmedOp['asst_zakat'];
	 $totalTerimaOpAsst += $jasmedOp['terima_asisten'];
	 
?>
	  <tr bgcolor="#C8FFA9">
	  <td ><?php echo $j;?>.</td>
	  <td ><?php echo $row1['layanan'];?></td><td align="right"><?php echo number_format($row1['tagihan'],2);?></td>
	  <td align="right" ><?php echo number_format($jasmedOp['potongan'], 2);?></td><td align="right" ><?php echo number_format($jasmedOp['rs_10'],2);?></td>
	  <td align="right" ><?php echo number_format($jasmedOp['rs_025'],2);?></td><td align="right" ><?php echo number_format($jasmedOp['non_medis'],2);?></td>
	  <td align="right" ><?php echo number_format($jasmedOp['zakat'],2);?></td><td align="right" ><?php echo number_format($jasmedOp['terima_dokter'],2);?></td>
	  <td align="right" ><?php echo number_format($jasmedOp['asst_potongan'], 2);?></td><td align="right" ><?php echo number_format($jasmedOp['asst_rs_05'],2);?></td>
	  <td align="right" ><?php echo number_format($jasmedOp['asst_rs_025'],2);?></td><td align="right" ><?php echo number_format($jasmedOp['asst_non_medis'],2);?></td>
	  <td align="right" ><?php echo number_format($jasmedOp['asst_zakat'],2);?></td><td align="right" ><?php echo number_format($jasmedOp['terima_asisten'],2);?></td>
	  <?php
	  pg_result_seek($result, (++$i));
	  $row2 = pg_fetch_array($result);
	  $jasmedAn = getJasmedOp($row2);
	  $totalTarifAn += $row2['tagihan'];
	  $totalPotonganAn += $jasmedAn['potongan'];
	  $totalRs10An += $jasmedAn['rs_10'];
	  $totalRs025An += $jasmedAn['rs_025'];
	  $totalRsNonMedisAn += $jasmedAn['non_medis'];
	  $totalZakatAn += $jasmedAn['zakat'];
	  $totalTerimaAn += $jasmedAn['terima_dokter'];
	 
	  $totalPotonganAnAsst += $jasmedAn['asst_potongan'];
	  $totalRs05AnAsst += $jasmedAn['asst_rs_05'];
	  $totalRs025AnAsst += $jasmedAn['asst_rs_025'];
	  $totalRsNonMedisAnAsst += $jasmedAn['asst_non_medis'];
	  $totalZakatAnAsst += $jasmedAn['asst_zakat'];
	  $totalTerimaAnAsst += $jasmedAn['terima_asisten'];
	  ?>
	  <td><?php echo $row2['layanan'];?></td><td align="right" ><?php echo number_format($row2['tagihan'],2);?></td>
	  <td align="right" ><?php echo number_format($jasmedAn['potongan'], 2);?></td><td align="right" ><?php echo number_format($jasmedAn['rs_10'],2);?></td>
	  <td align="right" ><?php echo number_format($jasmedAn['rs_025'],2);?></td><td align="right" ><?php echo number_format($jasmedAn['non_medis'],2);?></td>
	  <td align="right" ><?php echo number_format($jasmedAn['zakat'],2);?></td><td align="right" ><?php echo number_format($jasmedAn['terima_dokter'],2);?></td>
	  <td align="right" ><?php echo number_format($jasmedAn['asst_potongan'], 2);?></td><td align="right" ><?php echo number_format($jasmedAn['asst_rs_05'],2);?></td>
	  <td align="right" ><?php echo number_format($jasmedAn['asst_rs_025'],2);?></td><td align="right" ><?php echo number_format($jasmedAn['asst_non_medis'],2);?></td>
	  <td align="right" ><?php echo number_format($jasmedAn['asst_zakat'],2);?></td><td align="right" ><?php echo number_format($jasmedAn['terima_asisten'],2);?></td>
	  </tr>
	  <tr>
	  <td colspan="8" class="TBL_BODY">&nbsp;</td><td class="TBL_BODY">
									Dokter 1:<?php echo $row1['dokter1'];?>
									<br>
									Dokter 2:<?php echo $row1['dokter2'];?>
								 </td>
	  <td colspan="5" class="TBL_BODY">&nbsp;</td><td class="TBL_BODY">
									Asisten 1:<?php echo $row1['asisten1'];?>
									<br>
									Asisten 2:<?php echo $row1['asisten2'];?>
									<br>
									Asisten 3:<?php echo $row1['asisten3'];?>
								 </td>
	  <td colspan="7" class="TBL_BODY">&nbsp;</td><td class="TBL_BODY">
									Dokter 1:<?php echo $row2['dokter1'];?>
									<br>
									Dokter 2:<?php echo $row2['dokter2'];?>
								 </td>
	  <td colspan="5" class="TBL_BODY">&nbsp;</td><td class="TBL_BODY">
									Asisten 1:<?php echo $row2['asisten1'];?>
									<br>
									Asisten 2:<?php echo $row2['asisten2'];?>
									<br>
									Asisten 3:<?php echo $row2['asisten3'];?>
								 </td>						 								 
	  </tr>
	  <?php
	  ++$i;
	  ++$j;
}
?>
<tr bgcolor="#C8FFA9">
	<td align="center" colspan="2">TOTAL : </td>
	<td align="right"><?php echo number_format($totalTarifOp,2);?></td><td align="right"><?php echo number_format($totalPotonganOp,2);?></td>
	<td align="right"><?php echo number_format($totalRs10Op,2);?></td><td align="right"><?php echo number_format($totalRs025Op,2);?></td>
	<td align="right"><?php echo number_format($totalRsNonMedisOp,2);?></td><td align="right"><?php echo number_format($totalZakatOp,2);?></td>
	<td align="right"><?php echo number_format($totalTerimaOp,2);?></td><td align="right"><?php echo number_format($totalPotonganOpAsst,2);?></td>
	<td align="right"><?php echo number_format($totalRs05OpAsst,2);?></td><td align="right"><?php echo number_format($totalRs025OpAsst,2);?></td>
	<td align="right"><?php echo number_format($totalRsNonMedisOpAsst,2);?></td><td align="right"><?php echo number_format($totalZakatOpAsst,2);?></td>
	<td align="right"><?php echo number_format($totalTerimaOpAsst,2);?></td>
	<td>&nbsp;</td>
	<td align="right"><?php echo number_format($totalTarifAn,2);?></td><td align="right"><?php echo number_format($totalPotonganAn,2);?></td>
	<td align="right"><?php echo number_format($totalRs10An,2);?></td><td align="right"><?php echo number_format($totalRs025An,2);?></td>
	<td align="right"><?php echo number_format($totalRsNonMedisAn,2);?></td><td align="right"><?php echo number_format($totalZakatAn,2);?></td>
	<td align="right"><?php echo number_format($totalTerimaAn,2);?></td><td align="right"><?php echo number_format($totalPotonganAnAsst,2);?></td>
	<td align="right"><?php echo number_format($totalRs05AnAsst,2);?></td><td align="right"><?php echo number_format($totalRs025AnAsst,2);?></td>
	<td align="right"><?php echo number_format($totalRsNonMedisAnAsst,2);?></td><td align="right"><?php echo number_format($totalZakatAnAsst,2);?></td>
	<td align="right"><?php echo number_format($totalTerimaAnAsst,2);?></td>
</tr>
</table>
</div>
<?php
function getJasmedOp($row){
$jasmed = array();	
$jasmed['potongan']	= $row['tagihan']*0.77;
$jasmed['rs_10']	= $jasmed['potongan']*0.1;
$jasmed['rs_025']	= $jasmed['potongan']*0.025;
$jasmed['non_medis']	= $jasmed['potongan']*0.25;
$jasmed['zakat']	= ($jasmed['potongan']-$jasmed['rs_10']-$jasmed['rs_025']-$jasmed['non_medis'])*0.25;
$jasmed['terima_dokter'] = $jasmed['potongan']-$jasmed['rs_10']-$jasmed['rs_025']-$jasmed['non_medis']-$jasmed['zakat'];

$jasmed['asst_potongan'] = $row['tagihan']-$jasmed['potongan'];
$jasmed['asst_rs_05'] = $jasmed['asst_potongan']*0.05;
$jasmed['asst_rs_025'] = $jasmed['asst_potongan']*0.025;
$jasmed['asst_non_medis'] = $jasmed['asst_potongan']*0.025;
$jasmed['asst_zakat'] = $jasmed['asst_potongan']*0.025;
$jasmed['terima_asisten'] = $jasmed['asst_potongan']-$jasmed['asst_rs_05']-$jasmed['asst_rs_025']-$jasmed['asst_non_medis']-$jasmed['asst_zakat'];
return $jasmed;	
}

/**
function getJasmedAn($row){
	
}
**/
?>
