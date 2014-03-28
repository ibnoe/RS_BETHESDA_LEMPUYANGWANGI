<?php

if($_GET['tc']!='view'){
?>
<table align="center" class='TBL_BORDER' width='100%' border='1' cellspacing='1' cellpadding='1'>
			<tr class="NONE" bgcolor="#00CCCC">     	
				<td rowspan="2" class="TBL_HPD" width="4%" align="center"><b>NO</b></td>				
				<td rowspan="2" width="25%" class="TBL_HPD"align="center"><b>NAMA</b></td>
				<td rowspan="2" width="24%" class="TBL_HPD"align="center"><b>LAYANAN</b></td>
				<td rowspan="2" width="10%" class="TBL_HPD"align="center"><b>TIPE PASIEN</b></td>
				<td colspan="6" align="center" class="TBL_HPD"><b>JASA (Rp.)</b></td>
				<td rowspan="2" width="10%" align="center" class="TBL_HPD"><b>PELAYANAN (Rp.)</b></td>
				<td rowspan="2" width="5%" align="center" class="TBL_HPD"><b>DETAIL</b></td>				
			</tr>
			<tr class="NONE" bgcolor="#00CCCC">
				<td align="center" class="TBL_HPD"><b>DITERIMA</b><br/><b>OPERATOR</b></td>
				<td align="center" class="TBL_HPD"><b>DITERIMA</b><br/><b>ASISTEN</b></td>
				<td align="center" class="TBL_HPD"><b>NON MEDIS</b></td>
				<td align="center" class="TBL_HPD"><b>DISKON</b></td>
				<td align="center" class="TBL_HPD"><b>ZAKAT</b></td>
				<td align="center" class="TBL_HPD"><b>RS</b></td>				
			</tr>
<?php
$i=1;
while($r = pg_fetch_array($r1)){
	$terima_dokter = ($r['pot_op']-$r['non_medis_op']-$r['zakat_op']-$r['jasa_rs_op_10']-$r['jasa_rs_op_025']);
	$terima_asisten = ($r['pot_ast']-$r['non_medis_ast']-$r['zakat_ast']-$r['jasa_rs_ast_05']-$r['jasa_rs_ast_025']);
	$non_medis = $r['non_medis_op']+$r['non_medis_ast'];
	$diskon = $r['diskon'];
	$zakat = $r['zakat_op']+$r['zakat_ast'];
	$rs = $r['jasa_rs_op_10']+$r['jasa_rs_ast_05']+$r['jasa_rs_op_025']+$r['jasa_rs_ast_025'];
	$tagihan = $r['tagihan'];
	
	?>
	<tr>
		<td align="right" bgcolor="#00CCCC" ><?=($i++)?>.</td><td><?=$r['dokter']?></td><td><?=$r['layanan']?></td><td><?=$r['tipe']?></td>
		<td align="right"><?=number_format($terima_dokter,2)?></td>
		<td align="right"><?=number_format($terima_asisten,2)?></td>
		<td align="right"><?=number_format($non_medis,2)?></td>
		<td align="right"><?=number_format($diskon,2)?></td>
		<td align="right"><?=number_format($zakat,2)?></td>
		<td align="right"><?=number_format($rs,2)?></td>
		<td align="right"><?=number_format($tagihan,2)?></td>
		<td align="center" bgcolor="#00CCCC" ><a class='TBL_HREF' href='<?=$SC.'?p='.$PID.'&tc=view&dok='.$r['id_dokter'].'&t1='.$ts_check_in1.'&t2='.$ts_check_in2.'&tipe='.$r['tc'].
		'&inap='.$_GET['rawat_inap']?>'/><?=icon("view","View")?></a></td>
	</tr>
	<?php
	$jml_terima_dokter += $terima_dokter;
	$jml_terima_asisten += $terima_asisten;
	$jml_non_medis += $non_medis;
	$jml_diskon += $diskon;
	$jml_zakat += $zakat;
	$jml_rs += $rs;
	$jml_tagihan += $tagihan;
	}
?>
<tr class="NONE" bgcolor="#00CCCC">  
	<td class="TBL_HPD" align="center" colspan="4" height="25" valign="middle"><b> TOTAL </b></td>
	<td class="TBL_HPD" align="right" valign="middle"><b><?=number_format($jml_terima_dokter,2,",",".") ?></b></td>
	<td class="TBL_HPD" align="right" valign="middle"><b><?=number_format($jml_terima_asisten,2,",",".") ?></b></td>
	<td class="TBL_HPD" align="right" valign="middle"><b><?=number_format($jml_non_medis,2,",",".") ?></b></td>
	<td class="TBL_HPD" align="right" valign="middle"><b><?=number_format($jml_diskon,2,",",".") ?></b></td>
	<td class="TBL_HPD" align="right" valign="middle"><b><?=number_format($jml_zakat,2,",",".") ?></b></td>
	<td class="TBL_HPD" align="right" valign="middle"><b><?=number_format($jml_rs,2,",",".") ?></td>
	<td class="TBL_HPD" align="right" valign="middle"><b><?=number_format($jml_tagihan,2,",",".") ?></td>
	<td class="TBL_HPD" align="right" valign="middle">&nbsp;</td>
</tr>			
</table>			
<?php }else {
	?>
<table align="center"  border="1" cellspacing="0" cellpadding="1" class="TBL_BORDER" style="overflow:scroll; overflow:auto;">
			<tr class="NONE" bgcolor="#00CCCC">     	
				<td rowspan="3" class="TBL_HPD" width="3%" align="center"><b>NO.</b></td>
				<td rowspan="3" class="TBL_HPD"align="center" width="5%" ><b>TANGGAL</B></td>
				<td rowspan="3" class="TBL_HPD"align="center" width="5%" ><b>NO.REG</b></td>
				<td rowspan="3" class="TBL_HPD"align="center" width="5%" ><b>NO.MR</b></td>
				<td rowspan="3" class="TBL_HPD"align="center" width="10%" ><b>NAMA PASIEN</b></td>
				<td rowspan="3" class="TBL_HPD"align="center" width="10%" ><b>LAYANAN</b></td>
				<td rowspan="3" class="TBL_HPD"align="center"><b>POTONGAN OPERATOR</b></td>
				<td colspan="4" width="25%" align="center" class="TBL_HPD"><b>JASA (Rp.)</b></td>
				<td rowspan="3" width="6%" align="center" class="TBL_HPD"><b>ZAKAT</b></td>
				<td rowspan="3" class="TBL_HPD"align="center"><b>POTONGAN ASISTEN</b></td>
				<td colspan="4" width="25%" align="center" class="TBL_HPD"><b>JASA (Rp.)</b></td>
				<td rowspan="3" width="6%" align="center" class="TBL_HPD"><b>ZAKAT</b></td>
				<td rowspan="3" width="6%" align="center" class="TBL_HPD"><b>DISKON</b></td>
				<td rowspan="3" width="6%" align="center" class="TBL_HPD"><b>TOTAL (Rp.)</b></td>
			</tr>
			<tr class="NONE" bgcolor="#00CCCC">
				<td rowspan="2" width="5%" align="center" class="TBL_HPD"><b>OPERATOR</b></td>
				<td rowspan="2" width="5%" align="center" class="TBL_HPD"><b>NON MEDIS</b></td>
				<td colspan="2" width="5%" align="center" class="TBL_HPD"><b>RS</b></td>
				<td rowspan="2" width="5%" align="center" class="TBL_HPD"><b>ASISTEN</b></td>
				<td rowspan="2" width="5%" align="center" class="TBL_HPD"><b>NON MEDIS</b></td>
				<td colspan="2" width="5%" align="center" class="TBL_HPD"><b>RS</b></td>
			</tr>
			<tr class="NONE" bgcolor="#00CCCC">
				<td align="center" class="TBL_HPD"><b>10 %</b></td>
				<td align="center" class="TBL_HPD"><b>2.5 %</b></td>
				<td align="center" class="TBL_HPD"><b>5 %</b></td>
				<td align="center" class="TBL_HPD"><b>2.5 %</b></td>
			</tr>
	<?php
	$i=1;
	//print_r(pg_fetch_array($r1));
	while($r=pg_fetch_array($r1)){
	$terima_operator = ($r['pot_op']-$r['non_medis_op']-$r['zakat_op']-$r['jasa_rs_op_10']-$r['jasa_rs_op_025']);
	$jml_pot_operator += $r['pot_op'];
	$jml_terima_operator += $terima_operator;
	$terima_asisten = ($r['pot_ast']-$r['non_medis_ast']-$r['zakat_ast']-$r['jasa_rs_ast_05']-$r['jasa_rs_ast_025']);
	$jml_terima_asisten += $terima_asisten;
	$jml_pot_asisten += $r['pot_ast'];
	$jml_non_medis_op = $r['non_medis_op'];
	$jml_non_medis_ast += $r['non_medis_ast'];
	$jml_diskon += $r['diskon'];
	$jml_zakat_op += $r['zakat_op'];
	$jml_zakat_ast += $r['zakat_ast'];
	$jml_rs_op_10 += $r['jasa_rs_op_10'];
	$jml_rs_op_025 += $r['jasa_rs_op_025'];
	$jml_rs_ast_05 += $r['jasa_rs_ast_05'];
	$jml_rs_ast_025 += $r['jasa_rs_ast_025'];
	$jml_tagihan += $r['tagihan'];
		?>
		<tr>
			<td bgcolor="#00CCCC" ><?=($i++)?></td>
			<td align="center"><?=$r['tanggal']?></td>
			<td align="center"><?=$r['no_reg']?></td>
			<td align="center"><?=$r['mr_no']?></td>
			<td><?=$r['pasien']?></td>
			<td><?=$r['layanan']?></td>
			<td align="right"><?=number_format($r['pot_op'] ,2)?></td>
			<td align="right"><?=number_format($terima_operator ,2)?></td>
			<td align="right"><?=number_format($r['non_medis_op'] ,2)?></td>
			<td align="right"><?=number_format($r['jasa_rs_op_10'] ,2)?></td>
			<td align="right"><?=number_format($r['jasa_rs_op_025'] ,2)?></td>
			<td align="right"><?=number_format($r['zakat_op'] ,2)?></td>
			<!-- ASISTEN -->
			<td align="right"><?=number_format($r['pot_ast'] ,2)?></td>
			<td align="right"><?=number_format($terima_asisten ,2)?></td>
			<td align="right"><?=number_format($r['non_medis_ast'] ,2)?></td>
			<td align="right"><?=number_format($r['jasa_rs_ast_05'] ,2)?></td>
			<td align="right"><?=number_format($r['jasa_rs_ast_025'] ,2)?></td>
			<td align="right"><?=number_format($r['zakat_ast'] ,2)?></td>
			<td align="right"><?=number_format($r['diskon'],2)?></td>
			<td align="right"><?=number_format($r['tagihan'],2)?></td>
		</tr>
		<?php
		}
		?>
			 <tr class="NONE" bgcolor="#00CCCC">
			<td align="center" colspan="6">TOTAL (Rp.)</td>
			<td align="right"><?=number_format($jml_pot_operator,2)?></td>
			<td align="right"><?=number_format($jml_terima_operator,2)?></td>
			<td align="right"><?=number_format($jml_non_medis_op,2)?></td>
			<td align="right"><?=number_format($jml_rs_op_10,2)?></td>
			<td align="right"><?=number_format($jml_rs_op_025,2)?></td>
			<td align="right"><?=number_format($jml_zakat_op,2)?></td>
			<td align="right"><?=number_format($jml_pot_asisten,2)?></td>
			<td align="right"><?=number_format($jml_terima_asisten,2)?></td>
			<td align="right"><?=number_format($jml_non_medis_ast,2)?></td>
			<td align="right"><?=number_format($jml_rs_ast_05,2)?></td>
			<td align="right"><?=number_format($jml_rs_ast_025,2)?></td>
			<td align="right"><?=number_format($jml_zakat_ast,2)?></td>
			<td align="right"><?=number_format($jml_diskon,2)?></td>
			<td align="right"><?=number_format($jml_tagihan,2)?></td>
	 </tr>
</table>
		<?php
	 } ?>
	
