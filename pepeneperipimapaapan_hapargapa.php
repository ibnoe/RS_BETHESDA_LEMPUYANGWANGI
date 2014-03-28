<SCRIPT language="JavaScript" src="plugin/jquery-1.8.2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="plugin/jquery-ui.js"></SCRIPT>
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.theme.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.autocomplete.css">
<?php
if($GLOBALS['print']){
	$font = "style='font-size:11px'";
}
 $r = pg_query($con, "select * from c_po where po_id = '".$_GET["poid"]."'");
    $n = pg_num_rows($r);
    if($n > 0) $d2 = pg_fetch_object($r);
    
    $r1 = pg_query($con, "select * from c_po_item where po_id = '".$_GET["poid"]."'");
    $n1 = pg_num_rows($r1);
    if($n1 > 0) $d1 = pg_fetch_object($r1);

    pg_free_result($r);
	if($_GET['print']=='' || !isset($_GET['print'])){

    title("Rincian Item Pengadaan");
	}
	if($_GET['print']!=''){
	echo title("Rincian Item Penerimaan","left","rincian_penerimaan");
	}
    $supplier = getFromTable(
               "select a.nama from rs00028 a, c_po b ".
               "where  a.id=b.supp_id::text and b.supp_id::text='".$d2->supp_id."' ");
    $tanggal_po = getFromTable(
               "select to_char(po_tanggal,'DD Mon YYYY') from c_po ".
               "where po_id='".$_GET["poid"]."' ");
    $tanggung_jwb = getFromTable(
               "select po_personal from c_po ".
               "where po_id='".$_GET["poid"]."' ");
if($GLOBALS['print']){
$bg='FFFFFF';
}else{
$bg='B0C4DE';
}
    $f = new Form("");
	echo "<br>";
	echo "<table class=design10a>";
	echo "<tr>";
		echo "<td bgcolor='$bg' $font class=design10a><b> NO. PO </td>";
		echo "<td bgcolor='$bg' $font class=design10><b>: ".$_GET["poid"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='$bg' $font class=design10a><b> NAMA SUPPLIER</td>";
		echo "<td bgcolor='$bg' $font class=design10><b>: $supplier </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='$bg' $font class=design10a><b> TANGGAL PO </td>";
		echo "<td bgcolor='$bg' $font class=design10><b>: $tanggal_po </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='$bg' $font class=design10a><b> PENANGGUNG JAWAB</td>";
		echo "<td bgcolor='$bg' $font class=design10><b>: $tanggung_jwb </td>";
	echo "</tr>";
echo "</table>";
	echo "<br />";

    $f->execute();
    if($_GET['print']=='' || !isset($_GET['print'])){

?>
<table id="list-pasien" width="100%" >
    <thead>
        <tr>
            <td align="CENTER" class="TBL_HEAD" width="" rowspan="2" <?=$font ?>>NAMA OBAT</td>
            <td align="CENTER" class="TBL_HEAD" width="90" rowspan="2" <?=$font ?>>BATCH ID</td>
            <td align="CENTER" class="TBL_HEAD" width="90" rowspan="2" <?=$font ?>>EXPIRE DATE</td>
            <td align="CENTER" class="TBL_HEAD" width="40" colspan="2" <?=$font ?>>QTY</td>
            <td align="CENTER" class="TBL_HEAD" width="100" rowspan="2" <?=$font ?>>STATUS </td>
            <td align="CENTER" class="TBL_HEAD" width="100" rowspan="2" <?=$font ?>>EDIT </td>
        </tr>
        <tr>
            <td align="CENTER" class="TBL_HEAD" width="" <?=$font ?>>JML</td>
			<td align="CENTER" class="TBL_HEAD" width="" <?=$font ?>>SAT</td>
    </thead>
    <tbody>
<?php
    $rowsData = pg_query($con,"select a.obat,a.batch,to_char(b.expire,'yyyy-mm-dd')as expire, b.bonus, b.item_qty as qty,
case when b.qty_terima is null then b.item_qty else b.qty_terima end as item_qty,b.harga_beli, 
 b.diskon1, b.diskon2, case when b.po_status=0 then 'Belum Diproses' else 'Sudah Diproses' end as po_status, b.item_id 
,d.tdesc as satuan_1, e.tdesc as satuan_2
from c_po_item b
JOIN rs00015 a ON a.id::text = b.item_id
LEFT JOIN rs00001 d ON b.satuan1 = d.tc AND d.tt='SAT'
LEFT JOIN rs00001 e ON b.satuan2 = e.tc AND e.tt='SAT'
where (b.po_status = 0 or b.po_status = 2) and b.po_id='".$_GET["poid"]."' and a.id::text=b.item_id"); 
if(!empty($rowsData)){
	 $i=0;
	 $qty=0;
	 while($row=pg_fetch_array($rowsData)){
		 $i++;
		 if (!empty($row['item_qty'])) {
			 $qty += $row['item_qty'];
		 } else {
			 $qty = 0;
		 }
		 ?>
	<tr>
		<td <?=$font ?>><?php echo $row['obat']?></td>
		<td style="text-align: right;" <?=$font ?>><?php if (!empty($row['batch'])) {echo $row['batch'];} else {echo "&nbsp;";}?></td>
		<td style="text-align: right;" <?=$font ?>><?php if (!empty($row['expire'])) {echo $row['expire'];} else {echo "&nbsp;";}?></td>
		<td style="text-align: right;" <?=$font ?>><?php echo $row['item_qty'];?>&nbsp;</td>
		<td style="text-align: right;" <?=$font ?>><?php echo $row['satuan_1'];?>&nbsp;</td>
		<td style="text-align: right;" <?=$font ?>><?php echo $row['po_status'];?>&nbsp;</td>
		<td style="text-align: center;" <?=$font ?>>
		<?php 
			if($row['po_status'] != 'Sudah Diproses'){
		?>
		<a href="<?php echo $SC.'?p='.$PID.'&edit=edit&poid='.$_GET["poid"].'&e='.$row['item_id'].'&o='.$row['obat'].'&q='.$row['qty'].'&b='.$row['bonus'] ?>"> [ Accept ]</a>
		<?php
		}
		?>
		</td>
	</tr>
<?php
		 }
	}
	?>
		<tr>
		<td align="CENTER" class="TBL_HEAD" colspan='3' <?=$font ?>>JUMLAH TAGIHAN</td>
		<td align="CENTER" class="TBL_HEAD" style="text-align: right;" <?=$font ?>><?php echo number_format($qty, '0','','.');?>&nbsp;</td>
		<td align="CENTER" class="TBL_HEAD" style="text-align: right;" <?=$font ?>>&nbsp;</td>
		<td align="CENTER" class="TBL_HEAD" style="text-align: right;" <?=$font ?>>&nbsp;</td>
		<td align="CENTER" class="TBL_HEAD" style="text-align: right;" colspan="2" <?=$font ?>>&nbsp;</td>
    </tbody> 
</table>    
<br/><br/>
<?php 
		}
	if($_GET['print']=='' || !isset($_GET['print'])){

echo title("Rincian Item Penerimaan","left","rincian_penerimaan");
}
	if($_GET['print']!='' || !isset($_GET['print'])){
	if($GLOBALS['print']){
	//	$border = "style='border:1px solid #333;'";
	}
?>
<table id="list-pasien" width="100%" <?=$border ?>>
    <thead>
        <tr>
		<?php 	if(!$GLOBALS['print']){
			?>
            <td align="CENTER" class="TBL_HEAD" width="" rowspan="2" <?=$font ?>>TGL. TERIMA</td>
			<?php
			}?>
            <td align="CENTER" class="TBL_HEAD" width="" rowspan="2" <?=$font ?>>NAMA OBAT</td>
            <td align="CENTER" class="TBL_HEAD" width="" rowspan="2" <?=$font ?>>BATCH ID</td>
            <td align="CENTER" class="TBL_HEAD" width="" rowspan="2" <?=$font ?>>EXPIRE DATE</td>
            <td align="CENTER" class="TBL_HEAD" width="40" colspan="2" <?=$font ?>>QTY</td>
            <td align="CENTER" class="TBL_HEAD" width="60" rowspan="2" <?=$font ?>>HARGA SATUAN</td>
			<?php // if(!$GLOBALS['print']) { ?>
            <td align="CENTER" class="TBL_HEAD" width="100" rowspan="2" <?=$font ?>>JUMLAH HARGA</td>
			<?php //}?>
            <td align="CENTER" class="TBL_HEAD" width="100" colspan="2" <?=$font ?>>DISKON </td>
            <!--td align="CENTER" class="TBL_HEAD" width="100" colspan="2">DISKON 2</td-->
            <td align="CENTER" class="TBL_HEAD" width="80" rowspan="2" <?=$font ?>>PPN (%)</td>
			<?php if(!$GLOBALS['print']) { ?>
            <td align="CENTER" class="TBL_HEAD" width="80" rowspan="2" <?=$font ?>>MATERAI</td>
            <?php }?>
			<td align="CENTER" class="TBL_HEAD" width="100" rowspan="2" <?=$font ?>>TOTAL </td>
			<?php if(!$GLOBALS['print']) { ?>
            <td align="CENTER" class="TBL_HEAD" width="100" rowspan="2" <?=$font ?>>STATUS </td>
			<?php }?>
			<?php if(!$GLOBALS['print']) { ?>
            <td align="CENTER" class="TBL_HEAD" width="100" rowspan="2" <?=$font ?>>NO FAKTUR </td>
			<?php }?>
			<?php if(!$GLOBALS['print']) { ?>
            <td align="CENTER" class="TBL_HEAD" width="100" rowspan="2" <?=$font ?>>JATUH TEMPO </td>
			<?php }?>
			<?php 
	if($_GET['print']=='' || !isset($_GET['print'])){
?>
            <td align="CENTER" class="TBL_HEAD" width="100" rowspan="2" <?=$font ?>>ACTION </td>
			<td class="TBL_HEAD" width='7%' rowspan="2" ><center>CETAK <input type="checkbox" id="check_all_resep"></center></td>
			<?php }?>
        </tr>
        <tr>
            <td align="CENTER" class="TBL_HEAD" width="" <?=$font ?>>JML</td>
			<td align="CENTER" class="TBL_HEAD" width="" <?=$font ?>>SAT</td>
			<td align="CENTER" class="TBL_HEAD" width="" <?=$font ?>>(%)</td>
            <td align="CENTER" class="TBL_HEAD" width="" <?=$font ?>>(Rp.)</td>
            <!--td align="CENTER" class="TBL_HEAD" width="">(%)</td>
            <td align="CENTER" class="TBL_HEAD" width="">(Rp.)</td-->
        </tr>
    </thead>
    <tbody>
<?php
if($GLOBALS['print']){
	$tgl_terima = date('Y-m-d');
	$sql_add = " and b.tanggal_terima='$tgl_terima '";
}
    $rowsDataTerima = pg_query($con,"select a.obat,a.batch,to_char(b.expire,'yyyy-mm-dd')as expire,b.bonus, case when b.qty_terima is null then b.item_qty else b.qty_terima end as item_qty,b.item_qty as qty,b.harga_beli, b.ppn, b.diskon1, b.diskon2, b.materai, case when b.po_status=0 then 'Belum Diproses' else 'Sudah Diproses' end as po_status, b.item_id 
,d.tdesc as satuan1, e.tdesc as satuan_2, b.jumlah2 , to_char(b.tanggal_terima,'yyyy-mm-dd')as tanggal_terima, b.no_faktur, b.jatuh_tempo
from c_po_item_terima b
JOIN rs00015 a ON a.id::text = b.item_id
LEFT JOIN rs00001 d ON b.satuan1 = d.tc AND d.tt='SAT'
LEFT JOIN rs00001 e ON b.satuan2 = e.tc AND e.tt='SAT'
where (b.po_status = 0 or b.po_status = 2) and b.po_id='".$_GET["poid"]."' and a.id::text=b.item_id $sql_add order by a.obat asc"); 
$qty		= 0;
$satuan		= '-';
$hargaBeli	= 0;
$jumlah		= 0;
$disc1		= 0;
$disc2		= 0;
$ppn		= 0;
$materai	= 0;
$total		= 0;
$totalHarga	= 0;
$totalDisc1	= 0;
$totalDisc2	= 0;
$totalPPN		= 0;
$totalMaterai	= 0;
$grandTotal	= 0;

if(!empty($rowsDataTerima)){
	 $i=0;
	 while($rowTerima=pg_fetch_array($rowsDataTerima)){
		 $i++;
		 if (!empty($rowTerima['item_qty'])) {
			 $qty =  $rowTerima['item_qty'];
		 } else {
			 $qty = 0;
		 }
		 
		 if (!empty($rowTerima['satuan1'])) {
			 $satuan=  $rowTerima['satuan1'];
		 } else {
			 $satuan = '-';
		 }
		 
		 if (!empty($rowTerima['harga_beli'])) {
			 $hargaBeli =  $rowTerima['harga_beli']*$rowTerima['jumlah2'];
		 } else {
			 $hargaBeli = 0;
		 }
			 
		 if ($hargaBeli > 0) {
			 $jumlah =  $rowTerima['item_qty']* $hargaBeli ;
		 } else {
			 $jumlah = 0;
		 }
			 
		 if (!empty($rowTerima['diskon1'])) {
			 $disc1 =  $rowTerima['diskon1'];
			 $disc1Rupiah =  $qty*($disc1*$hargaBeli)/100;
		 } else {
			 $disc1 = 0;
			 $disc1Rupiah = 0;
		 }
		 
		 if (!empty($rowTerima['diskon2'])) {
			 $disc2 =  $rowTerima['diskon2'];
			 $disc2Rupiah =  $qty*($disc2*($hargaBeli-$disc1Rupiah))/100;
		 } else {
			 $disc2 = 0;
			 $disc2Rupiah = 0;
		 }
			 
		 if (!empty($rowTerima['ppn'])) {
			 $ppn =  ($rowTerima['ppn']/100)* (($qty*$hargaBeli)-$disc1Rupiah)-$disc2Rupiah;
		 } else {
			 $ppn = 0;
		 }
		 if (!empty($rowTerima['materai'])) {
			 $materai =  $rowTerima['materai'];
		 } else {
			 $materai = 0;
		 }
		 
		 if ($hargaBeli > 0) {
			 $total =  ($jumlah-($disc1Rupiah+$disc2Rupiah))+$ppn+$materai;
		 } else {
			 $total = 0;
		 }
			 
		 $totalHarga = $totalHarga+$jumlah;
		 $totalDisc1 = $totalDisc1+$qty*($disc1*$hargaBeli)/100;
		 $totalDisc2 = $totalDisc2+$qty*($disc2*$hargaBeli)/100;
		 $totalPPN = $totalPPN+$ppn;
		 $totalMaterai = $totalMaterai+$materai;
		 $grandTotal = $grandTotal+$total;
?>
	<tr>
	<?php
		if(!$GLOBALS['print']){   ?>
		<td <?=$font ?>><?php echo $rowTerima['tanggal_terima']?></td>
		<?php }?>
		<td align='left' <?=$font ?>><?php echo $rowTerima['obat']?></td>
		<td align='right' <?=$font ?>><?php if (!empty($rowTerima['batch'])) {echo $rowTerima['batch'];} else {echo "&nbsp;";}?></td>
		<td align='right' <?=$font ?>><?php if (!empty($rowTerima['expire'])) {echo $rowTerima['expire'];} else {echo "&nbsp;";}?></td>
		<td align='right' <?=$font ?>><?php echo $qty;?>&nbsp;</td>
		<td align='right' <?=$font ?>><?php echo $satuan;?>&nbsp;</td>
		<td align='right' <?=$font ?>><?php echo number_format($hargaBeli, '0','','.');?>&nbsp;</td>
		<?php//if(!$GLOBALS['print']) { ?>
		<td align='right' <?=$font ?>><?php echo number_format($jumlah, '0','','.');?>&nbsp;</td>
		<?php //}?>
		<td align='right' <?=$font ?>><?php echo $disc1;?>&nbsp;</td>
		<td align='right' <?=$font ?>><?php if($hargaBeli > 0){ echo number_format($disc1Rupiah, '0','.','.'); }else{ echo '0'; }?>&nbsp;</td>
		<!--td style="text-align: right;"><?php echo $disc2;?>&nbsp;</td>
		<td style="text-align: right;"><?php if($hargaBeli > 0){ echo number_format($disc2Rupiah, '0','.','.'); }else{ echo '0'; }?>&nbsp;</td-->
		<td align='right' <?=$font ?>><?php echo number_format($ppn, '0','','.');?>&nbsp;</td>
		<?php if(!$GLOBALS['print']) { ?>
		<td align='right' <?=$font ?>><?php echo number_format($materai, '0','','.');?>&nbsp;</td>
		<?php }?>
		<td align='right' <?=$font ?>><?php echo number_format($total, '0','','.');?>&nbsp;</td>
		<?php if(!$GLOBALS['print']) { ?>
		<td align='right' <?=$font ?>><?php echo $rowTerima['po_status'];?>&nbsp;</td>
		<?php }?>
		<?php if(!$GLOBALS['print']) { ?>
		<td align='right' <?=$font ?>><?php echo $rowTerima['no_faktur'];?>&nbsp;</td>
		<?php }?>
		<?php if(!$GLOBALS['print']) { ?>
		<td align='right' <?=$font ?>><?php echo $rowTerima['jatuh_tempo'];?>&nbsp;</td>
		<?php }?>
		<?php
	if($_GET['print']=='' || !isset($_GET['print'])){
?>
		<td align='center' <?=$font ?>><a href="<?php echo $SC.'?p='.$PID.'&edit=edit&&poid='. $_GET["poid"].'&e='.$rowTerima['item_id'].'&o='.$rowTerima['obat'].'&q='.$rowTerima['qty'].'&b='.$rowTerima['bonus'] ?>"> [ edit ]</a></td>
	    <td class="" align="center"><input type="checkbox" class="check_resep" name="cetak_<?php echo $i ?>" id="cetak_<?php echo $i ?>" value="<?php echo $i ?>"></td>
	<?php }?>	
	</tr>
<?php
		 }
	}
	echo '<input type="hidden" name="max_i_resep" id="max_i_resep" value="' . $i . '">';
	echo '<input type="hidden" name="max_i" id="max_i" value="' . $i . '">';

?>
		<tr>
		<?php
		if($GLOBALS['print']){
		?>
		<td align="CENTER" class="TBL_HEAD" colspan='5' <?=$font ?>>TOTAL TAGIHAN</td>
		<?php } else{
		?>
		<td align="CENTER" class="TBL_HEAD" colspan='6' <?=$font ?>>TOTAL TAGIHAN</td>
		<?php
		}?>
		<td align="CENTER" class="TBL_HEAD"  <?=$font ?>>&nbsp;</td>
		<?php// if(!$GLOBALS['print']) { ?>
		<td align="RIGHT" class="TBL_HEAD" <?=$font ?>><?php echo number_format($totalHarga, '0','','.');?>&nbsp;</td>
		<?php// }?>
		<td align="RIGHT" class="TBL_HEAD" <?=$font ?>>&nbsp;</td>
		<td align="RIGHT" class="TBL_HEAD" <?=$font ?>><?php echo number_format($totalDisc1, '0','','.');?>&nbsp;</td>
		<td align="RIGHT" class="TBL_HEAD" <?=$font ?>><?php echo number_format($totalPPN, '0','','.');?>&nbsp;</td>
		<?php if(!$GLOBALS['print']) { ?>
		<td align="RIGHT" class="TBL_HEAD" <?=$font ?>><?php echo number_format($totalMaterai, '0','','.');?>&nbsp;</td>
		<?php }?>
		<td align="RIGHT" class="TBL_HEAD" <?=$font ?>><?php echo number_format($grandTotal, '0','','.');?>&nbsp;</td>
		<?php if(!$GLOBALS['print']) { ?>
		<td align="RIGHT" class="TBL_HEAD" colspan="5" <?=$font ?>>&nbsp;</td>
		<?php }?>
    </tbody> 
</table>    
<?php if(!$GLOBALS['print']) { ?>
<table  width='100%%'>
    <tr>
        <td class="TBL_BODY" align="center" width='33%'><b></b>&nbsp;&nbsp;</td>
        <td class="TBL_BODY" align="center" width='33%'><b></b>&nbsp;&nbsp;</a></td>
        <td class="TBL_BODY" align="center" width='33%'><b>Cetak Bukti Terima</b>&nbsp;&nbsp;<a href="javascript: cetakTransaksi()" ><img src="images/cetak.gif" border="0"></a></td>
    </tr>	
</table>
<? }
}

if($GLOBALS['print']) { 
		echo "<br/><br/><br/><br/>";
		echo "<div align='right' style='padding-right:10px;font-size:11px;'> ( ".$_SESSION['nama_usr']." ) </div>";
}
?>
<script>
    $('#check_all_return').click(function(){
        if($('#check_all_return').is(':checked')){
            $('.check_return').attr("checked",true);
        }else{
            $('.check_return').attr("checked",false);
        }        
    })
    $('#check_all_obat').click(function(){
        if($('#check_all_obat').is(':checked')){
            $('.check_obat').attr("checked",true);
        }else{
            $('.check_obat').attr("checked",false);
        }        
    })
    $('#check_all_resep').click(function(){
        if($('#check_all_resep').is(':checked')){
            $('.check_resep').attr("checked",true);
        }else{
            $('.check_resep').attr("checked",false);
        }        
    })
		
    function cetakResep() { 
        maxIResep = $('#max_i_resep').val();
        selectedToPrint = '&max_resep='+maxIResep;
        for(iResep=0;iResep<=maxIResep;iResep++){
            if($('#cetak_resep_'+iResep).is(':checked') == true){
                obatResepSelected = $('#cetak_resep_'+iResep).val();
                selectedToPrint = selectedToPrint+'&obat_id_'+iResep+'='+obatResepSelected;
            }
        }
        window.open('includes/cetak.rincian_obat_resep.php?rg=<?php echo $_GET['rg'] ?>'+selectedToPrint, 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');
    }
    function cetakReturn() {
        maxIReturn = $('#max_i_return').val();
        selectedToPrint = '&max_return='+maxIReturn;
        for(iReturn=0;iReturn<=maxIReturn;iReturn++){
            if($('#cetak_return_'+iReturn).is(':checked') == true){
                obatReturnSelected = $('#cetak_return_'+iReturn).val();
                selectedToPrint = selectedToPrint+'&obat_id_'+iReturn+'='+obatReturnSelected;
            }
        }
        window.open('includes/cetak.rincian_obat_return.php?rg=<?php echo $_GET['rg'] ?>'+selectedToPrint, 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');
    }
    
    function cetakTransaksicopyresep() {
        maxI = $('#max_i').val();
        selectedToPrint = '&max_cetak='+maxI;
        for(i=0;i<=maxI;i++){
            if($('#cetak_'+i).is(':checked') == true){
                obatSelected = $('#cetak_'+i).val();
                selectedToPrint = selectedToPrint+'&cetak_'+i+'='+obatSelected;
            }
        }
        window.open('includes/cetak.rincian_obat_selectedcopyresep.php?rg=<?php echo $_GET['rg'] ?>'+selectedToPrint, 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');

    }

    function cetakTransaksi() {
        maxI = $('#max_i').val();
        selectedToPrint = '&max_cetak='+maxI;
        for(i=0;i<=maxI;i++){
            if($('#cetak_'+i).is(':checked') == true){
                obatSelected = $('#cetak_'+i).val();
                selectedToPrint = selectedToPrint+'&cetak_'+i+'='+obatSelected;
            }
        }
        window.open('includes/cetak.rincian_obat_terima.php?poid=<?php echo $_GET['poid'] ?>'+selectedToPrint, 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');
    }
</script>