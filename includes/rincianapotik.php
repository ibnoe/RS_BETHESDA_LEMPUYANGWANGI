<?php // Agung Sunandar 15:13 08/09/2012 

$_GET["rg"] = $_GET[rg];
$rg = isset($_GET["rg"])? $_GET["rg"] : $_POST["rg"];
$mr = isset($_GET["mr"])? $_GET["mr"] : $_POST["mr"];   


?>
<table width="100%">
	<tr>
		<td align="center" class="TBL_HEAD" width="10%">TANGGAL</td>
		<td align="center" class="TBL_HEAD">DESCRIPTION</td>
		<td align="center" class="TBL_HEAD" width="10%">JUMLAH</td>
		<td align="center" class="TBL_HEAD" width="10%">TAGIHAN</td>
		<td align="center" class="TBL_HEAD" width="10%">BATAL</td>
	</tr>

<?
		///Batas Pembelian Obat

                // Pembelian Obat
		$rec3 = getFromTable ("select count(id) from rs00008 ".
				     "where trans_type = 'OB1' and to_number(no_reg,'999999999999') = $reg and referensi != 'F'");
		
		
		if ($rec3 > 0){
		$sqlf= "select a.id, to_char(tanggal_trans,'DD-MM-YYYY') as tanggal_trans,  
		obat, a.qty ||' '|| c.tdesc as qty, a.qty as qty1, sum(tagihan) as tagihan, pembayaran, trans_group, d.tdesc as kategori, a.trans_form ,a.is_bayar,a.item_id::integer as obat_id 
		from rs00008 a, rs00015 b, rs00001 c, rs00001 d 
		where to_number(a.item_id,'999999999999') = b.id  
		and b.satuan_id = c.tc and a.trans_type = 'OB1' 
		and c.tt = 'SAT' 
		and b.kategori_id = d.tc and d.tt = 'GOB' 
		and to_number(a.no_reg,'999999999999')= $reg  and referensi != 'F'
		group by  d.tdesc, a.tanggal_trans, a.id, b.obat, a.qty, a.pembayaran, a.trans_group, c.tdesc, a.trans_form,a.is_bayar,a.item_id ";
		@$r5 = pg_query($con,$sqlf);
		@$n5 = pg_num_rows($r5);
		
		$max_row5= 200 ;
		$mulai5 = $HTTP_GET_VARS["rec"] ;
		if (!$mulai5){$mulai5=1;}
		
		?>
		<tr>
		<td bgcolor="#8ADFD3" align="center">&nbsp;</td>
		<td bgcolor="#8ADFD3" align="left"><b><u>RINCIAN OBAT APOTEK</u></b></td>
		<td bgcolor="#8ADFD3" align="center">&nbsp;</td>
		<td bgcolor="#8ADFD3" align="right">&nbsp;</td>
		<td bgcolor="#8ADFD3" align="right">&nbsp;</td>
	</tr>
		<?
		
		$row5=0;
		$tagihan5=0;
		$i5= 1 ;
		$j5= 1 ;
		$last_id5=1;
		while (@$row5 = pg_fetch_array($r5)){
			  if (($j5<=$max_row5) AND ($i5 >= $mulai5)){
			  $no5=$i5;
		?>
		<tr>
			<td class="TBL_BODY" align="center"><b><?=$row5["tanggal_trans"] ?></b></td>
			<td class="TBL_BODY" align="left"><?=$row5["obat"] ?></td>
			<td class="TBL_BODY" align="left"><?=$row5["qty"] ?></td>
			<td class="TBL_BODY" align="right"><b><?=number_format($row5["tagihan"],2,",",".") ?></b></td>
			<?
			if (($row5["is_bayar"] == 'N') or ($row5["is_bayar"] == 'Y' and $_SESSION[uid] == 'root')){
                echo "<td class='TBL_BODY' align='center'><a href='actions/retur_obat_apotik.delete.php?pid=$PID&del=$row5[id]&id=$row5[obat_id]&qty=$row5[qty1]&tbl=retur&rg=".$_GET["rg"]."&tt=".$_GET["tt"]."&sub=".$_GET["sub"]."'>".icon("del-left","Hapus")."</a></td>";
                }else{
			?>
			<td class="TBL_BODY" align="center">&nbsp;</td>
			<? } ?>
		</tr>
		<?
		$tagihan5=$tagihan5+$row5["tagihan"];		 
					 
             ;$j5++;}
			 
          $i5++;}
		  ?>
		<?
		
		
		}
		
		
		///Batas Pembelian Obat



// Pembelian Obat Racikan
		$rec4 = getFromTable ("select count(id) from rs00008 ".
				     "where trans_type = 'RCK' and to_number(no_reg,'999999999999') = $reg and referensi != 'F'");
		
		
		if ($rec4 > 0){
		$sqlf= "select a.id, to_char(tanggal_trans,'DD-MM-YYYY') as tanggal_trans,  
		obat, qty ||' '|| c.tdesc as qty, a.qty as qty1, sum(tagihan) as tagihan, pembayaran, trans_group, d.tdesc as kategori, a.trans_form,a.is_bayar,a.item_id::integer as obat_id 
		from rs00008 a, rs00015 b, rs00001 c, rs00001 d 
		where to_number(a.item_id,'999999999999') = b.id  
		and b.satuan_id = c.tc and a.trans_type = 'RCK' 
		and c.tt = 'SAT' 
		and b.kategori_id = d.tc and d.tt = 'GOB' 
		and to_number(a.no_reg,'999999999999')= $reg  and referensi != 'F'
		group by  d.tdesc, a.tanggal_trans, a.id, b.obat, a.qty, a.pembayaran, a.trans_group, c.tdesc, a.trans_form,a.is_bayar,a.item_id ";
		
		@$r6 = pg_query($con,$sqlf);
		@$n6 = pg_num_rows($r6);
		
		$max_row6= 200 ;
		$mulai6 = $HTTP_GET_VARS["rec"] ;
		if (!$mulai6){$mulai6=1;}
		
		?>
		<tr>
		<td bgcolor="#8ADFD3" align="center">&nbsp;</td>
		<td bgcolor="#8ADFD3" align="left"><b><u>RINCIAN RACIKAN OBAT APOTEK</u></b></td>
		<td bgcolor="#8ADFD3" align="center">&nbsp;</td>
		<td bgcolor="#8ADFD3" align="right">&nbsp;</td>
		<td bgcolor="#8ADFD3" align="right">&nbsp;</td>
	</tr>
		<?
		
		$row6=0;
		$tagihan6=0;
		$i6= 1 ;
		$j6= 1 ;
		$last_id6=1;
		while (@$row6 = pg_fetch_array($r6)){
			  if (($j6<=$max_row6) AND ($i6 >= $mulai6)){
			  $no6=$i6;
		?>
		<tr>
			<td class="TBL_BODY" align="center"><b><?=$row6["tanggal_trans"] ?></b></td>
			<td class="TBL_BODY" align="left"><?=$row6["obat"] ?></td>
			<td class="TBL_BODY" align="left"><?=$row6["qty"] ?></td>
			<td class="TBL_BODY" align="right"><b><?=number_format($row6["tagihan"],2,",",".") ?></b></td>
			<?
			if (($row6["is_bayar"] == 'N') or ($row6["is_bayar"] == 'Y' and $_SESSION[uid] == 'root')){
                echo "<td class='TBL_BODY' align='center'><a href='actions/retur_obat_apotik.delete.php?pid=$PID&del=$row6[id]&id=$row6[obat_id]&qty=$row6[qty1]&tbl=retur&rg=".$_GET["rg"]."&tt=".$_GET["tt"]."&sub=".$_GET["sub"]."'>".icon("del-left","Hapus")."</a></td>";
                }else{
			?>
			<td class="TBL_BODY" align="center">&nbsp;</td>
			<? } ?>
		</tr>
		<?
		$tagihan6=$tagihan6+$row6["tagihan"];		 
					 
             ;$j6++;}
			 
          $i6++;}
		  ?>
		<?
		
		
		}
		
//Batas Pembelian Obat Racikan

?>
	<tr>
		<td class="TBL_HEAD" align="right" colspan="3"><b>T O T A L</b></td>
		<td class="TBL_HEAD" align="right"><b><?=number_format($tagihan6 + $tagihan5,2,",",".") ?></b></td>
		<td class="TBL_HEAD" align="center">&nbsp;</td>
	</tr>
</table>