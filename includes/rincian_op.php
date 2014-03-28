<?php
/**
 * Gema Perbangsa
 * 19 September 2013
 */ 
include '../lib/dbconn.php';

$query = pg_query("SELECT tanggal(a.tanggal_trans,3) AS tanggal_trans, a.trans_group,b.layanan, a.harga,a.referensi, a.tagihan, a.dibayar_penjamin, a.diskon, 
d.nama AS dokter1, c.diskon_dokter1,e.nama AS dokter2,c.diskon_dokter2,f.nama AS asisten1, c.diskon_asisten1,g.nama AS asisten2,c.diskon_asisten2,
h.nama AS asisten3,c.diskon_asisten3 FROM rs00008 a 
JOIN rs00034 b ON a.item_id::numeric = b.id AND a.trans_type = 'LTM'
JOIN rs00008_op c ON a.id = c.id_rs08
LEFT JOIN rs00017 d ON c.id_dokter1 = d.id
LEFT JOIN rs00017 e ON c.id_dokter2 = e.id
LEFT JOIN rs00017 f ON c.id_asisten1 = f.id
LEFT JOIN rs00017 g ON c.id_asisten2 = g.id
LEFT JOIN rs00017 h ON c.id_asisten3 = h.id WHERE a.no_reg='".$_GET['rg']."' ORDER BY a.id");
?>
<table cellpadding="5" cellspacing="0">
	<tr>
		<td class="TBL_HEAD" align="center">LAYANAN</td>
		<td class="TBL_HEAD" align="center">DOKTER</td><td class="TBL_HEAD" align="center">HARGA</td>
		<td class="TBL_HEAD" align="center">CITO</td><td class="TBL_HEAD" align="center">DIBAYAR PENJAMIN</td>
		<td class="TBL_HEAD" align="center">DISKON</td><td class="TBL_HEAD" align="center">TAGIHAN</td>
		<td class="TBL_HEAD" align="center">&nbsp;</td><td class="TBL_HEAD" align="center">&nbsp;</td>
	</tr>

<?php
$i=0;
while($r = pg_fetch_array($query)){
	if($i%2==0){
		?>
	<tr bgcolor="#C8FFA9">
		<td colspan="7"><b><?php echo $r['tanggal_trans']; ?></b></td>
		<td align="right" ><b><a href="#" onclick="getOperasi(<?php echo $r['trans_group'];?>)">EDIT</a></b></td>
		<td ><b><a href="#" onclick="hapus(<?php echo $r['trans_group']?>, event)">HAPUS</a></b></td
	</tr>
	<?php	
	}
	?>
	<tr>
		<td class="TBL_BODY" style="border-bottom:1px solid;"><b><?php echo $r['layanan']; ?></b></td>
		<td class="TBL_BODY" style="border-bottom:1px solid;">
			<table>
				<tr>
					<td align="center"><b>Dokter 1</b></b></td><td>:</td><td><b><?php echo $r['dokter1']; ?></b></td>
				</tr>
				<tr>
					<td align="center"><b>Dokter 2</td></b><td>:</td><td><b><?php echo $r['dokter2']; ?></b></td>
				</tr>
				<tr>
					<td align="center"><b>Asisten 1</td></b><td>:</td><td><b><?php echo $r['asisten1']; ?></b></td>
				</tr>
				<tr>
					<td align="center"><b>Asisten 2</td></b><td>:</td><td><b><?php echo $r['asisten2']; ?></b></td>
				</tr>
				<tr>
					<td align="center"><b>Asisten 3</td></b><td>:</td><td><b><?php echo $r['asisten3']; ?></b></td>
				</tr>
				
			</table>
		</td>
		<td align="right" class="TBL_BODY" style="border-bottom:1px solid;"><b><?php echo number_format($r['harga'],2); ?></b></td>
		<td align="right" class="TBL_BODY" style="border-bottom:1px solid;"><b><?php echo number_format((double)$r['referensi'], 2); ?></b></td>
		<td align="right" class="TBL_BODY" style="border-bottom:1px solid;"><b><?php echo number_format($r['dibayar_penjamin'],2); ?></b></td>
		<td align="right" class="TBL_BODY" style="border-bottom:1px solid;"><b><?php echo number_format($r['diskon'],2); ?></b></td>
		<td align="right" class="TBL_BODY" style="border-bottom:1px solid;"><b><?php echo number_format($r['tagihan']-$r['dibayar_penjamin'],2); ?></b></td>
		<td align="right" class="TBL_BODY" style="border-bottom:1px solid;">&nbsp;</td>
		<td class="TBL_BODY" style="border-bottom:1px solid;">&nbsp;</td>
	</tr>
	<?php
	$i++;
	}
?>
</table>






