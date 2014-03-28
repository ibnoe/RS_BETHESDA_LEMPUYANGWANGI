<?php

session_start();
require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
?>
<html>
<head>
    <title>Edit Layanan Non Paket</title>
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
		$("input:text[name='tanggal_trans']").datepicker({dateFormat:'yy-mm-dd'});
		$("input:text[name='dokter']").autocomplete({
		type:'GET',
		source:function(request,response){
		$.ajax({
			url:'../lib/getPegawai.php',
			data: {term : request.term},
			dataType : 'json',
			success : function(data){
					response(data);
				},
			});
		},
		selectFirst: true,
		select: function( event, ui ) {
			$("input:hidden[name='idDokter']").val(ui.item.id);
				},
		});
		
		$("input:text[name='layanan']").autocomplete({
		type:'GET',
		source:function(request,response){
		$.ajax({
			url:'../lib/getLayanan.php',
			data: {term : request.term},
			dataType : 'json',
			success : function(data){
					response(data);
				},
			});
		},
		selectFirst: true,
		select: function( event, ui ) {
			$("input:hidden[name='item_id']").val(ui.item.item_id);
			$("input:text[name='harga']").val(ui.item.harga);
			hitung();
				},
		});
		
		$("#persen").keyup( function(){
		  hitung("persen");
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

	$("input:radio[name='patokan_diskon']").click(function(){
		   hitung('persen');
	});

    });
		function cekInput(){
			if(parseFloat($("#tagihan").val())<0){
				alert('Tagihan tidak boleh minus!!!');
				return false;
				}				
			}
		
		function getFloat(value){
			var val = parseFloat(value);
		if(isNaN(val)){return 0;}
			return val;
		}
		
		function hitung(tipe_diskon){
			var qty = getFloat($("#qty").val());
			var harga = getFloat($("#harga").val());
			var dibayar_penjamin = getFloat($("#dibayar_penjamin").val());
			
			var totalHarga = qty*harga;
			var tagihan = qty*harga;
			$("#tagihan").val(tagihan);
			var diskon = 0;

			if($("input:radio[name='patokan_diskon']:checked").val()!=''){
			    var patokan_diskon = getFloat($("input:text[name='"+$("input[name='patokan_diskon']:checked").val()+"']").val())*qty;
			
			}
			else{
			    var patokan_diskon = totalHarga;
			}
			if(tipe_diskon=='persen'){
				diskon = getFloat($("#persen").val()/100*patokan_diskon);
				$("#diskon").val(diskon);
			}
			else if(tipe_diskon=='nominal'){
				diskon = getFloat($('#diskon').val());
				$("#persen").val(getFloat(diskon/patokan_diskon*100));
			}
			else{
				diskon = getFloat($("#persen").val()/100*patokan_diskon);
				$("#diskon").val(diskon);
				$("#persen").val(getFloat(diskon/patokan_diskon*100));
			}
			diskon = getFloat($('#diskon').val());			
			$("#tagihan").val(tagihan-diskon);
		}
    </script>
</head>
<body>
<?php
title("Edit Layanan Non Paket");
 $row = pg_fetch_array(pg_query("SELECT a.id, a.item_id, a.qty, a.harga,c.jasa_rs,c.jasa_dokter,c.jasa_asisten,c.alat,c.bahan,c.dll,a.tagihan, a.tanggal_trans, a.diskon, a.persen, 
 a.dibayar_penjamin,a.no_reg, a.no_kwitansi, b.nama AS dokter, b.id AS id_dokter,
c.layanan, lpad(c.id::character varying, 5,'0')
FROM rs00034 c ,rs00008 a LEFT JOIN rs00017 b ON a.no_kwitansi = b.id
WHERE a.id = ".$_GET['id']." AND a.item_id = lpad(c.id::character varying, 5,'0')"));


?>
<br/>
<form action="../actions/update_layanan_non_paket.php" method="post" onsubmit="cekInput()">
<input type="hidden" name="id" value="<?php echo $_GET['id']?>"/>
<table width="100%">
	<tr>
		<td><b>Tanggal</b></td><td>:</td><td><input type="text" name="tanggal_trans" value="<?=$row['tanggal_trans']?>" size="35" /></td><td>&nbsp;</td>
	</tr>
	<tr>
		<input type="hidden" name="item_id" value="<?php echo $row['item_id'];?>"/>
		<td><b>Layanan</b></td><td>:</td><td><input type="text" name="layanan" value="<?php echo $row['layanan']?>" size="35" /></td><td>&nbsp;</td>
	</tr>
	<tr>
		<td><b>Qty</b></td><td>:</td><td><input style="text-align:right;" id="qty" name="qty" type="text" value="<?php echo $row['qty']?>" size="35"/></td><td>&nbsp;</td>
	</tr>
	<tr>
		<input type="hidden" name="idDokter" value="<?php echo $row['no_kwitansi']?>"/>
		<td><b>Dokter</b></td><td>:</td><td><input type="text" name="dokter" value="<?php echo $row['dokter']?>" size="35" /></td><td>&nbsp;</td>
	</tr>
	<tr>
		<td><b>Harga</b></td><td>:</td><td><input id ="harga" name="harga" style="text-align:right;" type="text" value="<?php echo $row['harga']?>" size="35" /></td>
		<td><input type="radio" name="patokan_diskon" value="tagihan" checked>&nbsp;Patokan Diskon</td>
	</tr>
	<tr>
		<td><b>Dibayar Penjamin</b></td><td>:</td><td><input style="text-align:right;" type="text" id="dibayar_penjamin" name="dibayar_penjamin" value="<?php echo $row['dibayar_penjamin']?>" size="35" /></td>
	</tr>
	<tr>
		<td><b>Tagihan</b></td><td>:</td><td><input style="text-align:right;" type="text" id="tagihan" name="tagihan" value="<?php echo $row['tagihan']?>" size="35"  /></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><b>Jasa RS</b></td><td>:</td><td><input disabled style="text-align:right;" name="jasa_rs" type="text" value="<?php echo $row['jasa_rs']?>" size="35" /></td>
		<td><input type="radio" name="patokan_diskon" id="jasa_rs" value="jasa_rs">&nbsp;Patokan Diskon</td>
	</tr>
	<tr>
		<td><b>Jasa Dokter</b></td><td>:</td><td><input disabled style="text-align:right;" name="jasa_dokter" type="text" value="<?php echo $row['jasa_dokter']?>" size="35" /></td>
		<td><input type="radio" name="patokan_diskon" id="jasa_dokter" value="jasa_dokter">&nbsp;Patokan Diskon</td>
	</tr>
	<tr>
		<td><b>Jasa Asisten</b></td><td>:</td><td><input disabled style="text-align:right;" name="jasa_asisten" type="text" value="<?php echo $row['jasa_asisten']?>" size="35" /></td>
		<td><input type="radio" name="patokan_diskon" id="jasa_asisten" value="jasa_asisten">&nbsp;Patokan Diskon</td>
	</tr>
	<tr>
		<td><b>Alat</b></td><td>:</td><td><input disabled style="text-align:right;" name="alat" type="text" value="<?php echo $row['alat']?>" size="35"  /></td>
		<td><input type="radio" name="patokan_diskon" id="alat" value="alat">&nbsp;Patokan Diskon</td>
	</tr>
	<tr>
		<td><b>Bahan</b></td><td>:</td><td><input disabled style="text-align:right;" name="bahan" type="text" value="<?php echo $row['bahan']?>" size="35" /></td>
		<td><input type="radio" name="patokan_diskon" id="bahan" value="bahan">&nbsp;Patokan Diskon</td>
	</tr>
	<tr>
		<td><b>Administrasi</b></td><td>:</td><td><input disabled style="text-align:right;" name="administrasi" type="text" value="<?php echo $row['dll']?>" size="35" /></td>
		<td><input type="radio" name="patokan_diskon" id="administrasi" value="administrasi">&nbsp;Patokan Diskon</td>
	</tr>
	<tr>
		<td><b>Diskon (%)</b></td><td>:</td><td><input name="persen" id="persen" style="text-align:right;" type="text" value="<?php echo $row['persen']?>" size="35" /></td><td>&nbsp;</td>
	</tr>
	<tr>
		<td><b>Diskon (Rp.)</b></td><td>:</td><td><input id="diskon" name="diskon" style="text-align:right;" type="text" value="<?php echo $row['diskon']?>" size="35" /></td><td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align="left"><input type="reset" value="Reset"/><input type="submit" value="Simpan"/></td>
		<td>&nbsp;</td>
	</tr>
</table>
</form>
</body>
</html>
