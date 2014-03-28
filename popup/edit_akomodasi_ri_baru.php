<?php

session_start();
require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
?>
<html>
<head>
    <title>Edit Akomodasi Rawat Inap</title>
       <link rel='stylesheet' type='text/css' href='../default.css'>    
    <script language="javascript" src="../plugin/jquery-1.8.2.js"></script>
    <link rel="stylesheet" type='text/css' href="../plugin/jquery-ui.custom.css"/>
	<script type="text/javascript" src="../plugin/jquery-ui.js"></script>
    <?php if(isset($_GET['e'])){?>
    <script language="JavaScript">
        window.opener.location.reload();
        window.close();
    </script>
    <?php }?>
    <script language="javascript">
		$(function() {
		$("#persen").keyup( function(){
           hitung("persen");
        });
        
        $("input:text[name='bangsal']").autocomplete({
		type:'GET',
		source:function(request,response){
		$.ajax({
			url:'../lib/get_info_ruangan.php',
			data: {term : request.term},
			dataType : 'json',
			success : function(data){
					response(data);
				},
			});
		},
		selectFirst: true,
		select: function( event, ui ) {
			$("input:hidden[name='id_bangsal']").val(ui.item.id);
			$("input:text[name='harga']").val(ui.item.harga);
			hitung('nominal');
				},
		});
        
        $("#diskon").keyup( function(){
           hitung("nominal");
        }); 
        
       $("#dibayar_penjamin").keyup(function(){
		   hitung(null);
		});       
		
	   $("#qty").keyup(function(){
		   hitung(null);
		});
    });
		function cekInput(){
			if(parseFloat($("#tagihan").val())<0){
				alert('Tagihan tidak boleh minus!!!');
				return false;
				}				
			}
		function hitung(tipe_diskon){
			var qty = parseFloat($("#qty").val());
			var harga = parseFloat($("#harga").val());
			var dibayar_penjamin = parseFloat($("#dibayar_penjamin").val());
			
			var totalHarga = qty*harga;
			var tagihan = qty*harga;
			$("#tagihan").val(tagihan);
			var diskon = 0;
			if(tipe_diskon=='persen'){
				diskon = parseFloat($("#persen").val())/100*tagihan;
				$("#diskon").val(diskon);
			}
			else if(tipe_diskon=='nominal'){
				diskon = parseFloat($('#diskon').val());
				$("#persen").val(diskon/tagihan*100);
			}
			else{
				diskon = parseFloat($("#persen").val())/100*tagihan;
				$("#diskon").val(diskon);
				$("#persen").val(diskon/tagihan*100);
				}
			diskon = parseFloat($('#diskon').val());			
			$("#tagihan").val(tagihan-diskon);
		}
    </script>
</head>
<body>
<?php
title("Edit Akomodasi Rawat Inap");
 $row = pg_fetch_array(pg_query("SELECT id, bangsal_id, tanggal(tanggal_trans,3) AS tgl_trans, tanggal_entry,bangsal, ruangan,bed, 
		klasifikasi_tarif,no_reg,nama, qty, diskon, persen,harga, tagihan, bangsal_id, 
        dibayar_penjamin FROM rsv_akomodasi_inap_kasir WHERE id='".$_GET['id']."'"));
?>
<br/>
<form action="../actions/update_akomodasi_rawat_inap.php" method="post" onsubmit="cekInput()">
<input type="hidden" name="id" value="<?=$_GET['id']?>"/>
<input type="hidden" name="reg" value="<?=$row['no_reg']?>"/>
<input type="hidden" name="tanggal_entry" value="<?=$row['tanggal_entry']?>"/>
<input type="hidden" name="bangsal_id" value="<?=$row['bangsal_id']?>"/>
<input type="hidden" name="qty_05" value="<?= ($row['qty']>=1) ? (int) $row['qty'] : $row['qty'];?>"/>
<table width="100%">
	<tr>
		<input type="hidden" name="id_bangsal" value="<?php echo $row['bangsal_id'];?>" />
		<td><b>Bangsal/Bed</b></td><td>:</td><td><input type="text" name="bangsal" value="<?=$row['ruangan'].' / '.$row['bed'].' / '.$row['klasifikasi_tarif']?>" size="35" /></td>
	</tr>
	<tr>
		<td><b>Qty</b></td><td>:</td><td><input style="text-align:right;" id="qty" name="qty" type="text" value="<?=$row['qty']?>" size="35"/></td>
	</tr>
	<tr>
		<td><b>Harga</b></td><td>:</td><td><input id ="harga" name="harga" style="text-align:right;" type="text" value="<?=number_format($row['harga'],2,'.','')?>" readonly size="35" /></td>
	</tr>
	<tr>
		<td><b>Tagihan</b></td><td>:</td><td><input style="text-align:right;" type="text" id="tagihan" name="tagihan" value="<?=number_format($row['tagihan'],2,'.','')?>" readonly size="35"  /></td>
	</tr>
	<tr>
		<td><b>Dibayar Penjamin</b></td><td>:</td><td><input style="text-align:right;" type="text" id="dibayar_penjamin" name="dibayar_penjamin" value="<?=number_format($row['dibayar_penjamin'],2,'.',',')?>" size="35" /></td>
	</tr>
	<tr>
		<td><b>Diskon (%)</b></td><td>:</td><td><input name="persen" id="persen" style="text-align:right;" type="text" value="<?=number_format($row['persen'],2,'.',',')?>" size="35" /></td>
	</tr>
	<tr>
		<td><b>Diskon (Rp.)</b></td><td>:</td><td><input id="diskon" name="diskon" style="text-align:right;" type="text" value="<?=number_format($row['diskon'],2,'.',',')?>" size="35" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align="left"><input type="reset" value="Reset"/><input type="submit" value="Simpan"/></td>
	</tr>
</table>
</form>
</body>
</html>
