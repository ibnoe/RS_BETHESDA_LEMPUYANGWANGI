<?php

session_start();
require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
?>
<html>
<head>
    <title>Edit Layanan Paket</title>
    <link rel='stylesheet' type='text/css' href='../default.css'>    
    <script language="javascript" src="../plugin/jquery-1.8.2.js"></script>
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
		
		function getFloat(value){
			var val = parseFloat(value);
		if(isNaN(val)){return 0;}
			return val;
		}
		
		function hitung(tipe_diskon){
			var qty = getFloat($("#qty").val());
			var harga = getFloat($("#harga").val());
			var dibayar_penjamin = getFloat($("#dibayar_penjamin").val());
			$("#dibayar_penjamin").val(dibayar_penjamin);
			var totalHarga = qty*harga;
			var tagihan = qty*harga;
			$("#tagihan").val(tagihan);
			var diskon = 0;
			if(tipe_diskon=='persen'){
				diskon = getFloat($("#persen").val()/100*tagihan);
				$("#diskon").val(diskon);
			}
			else if(tipe_diskon=='nominal'){
				diskon = getFloat($('#diskon').val());
				$("#persen").val(getFloat(diskon/tagihan*100));
			}
			else{
				diskon = getFloat($("#persen").val()/100*tagihan);
				$("#diskon").val(diskon);
				$("#persen").val(getFloat(diskon/tagihan*100));
				}
			diskon = getFloat($('#diskon').val());			
			$("#tagihan").val(tagihan-diskon);
		}
    </script>
</head>
<body>
<?php
title("Edit Layanan Paket");
 $row = pg_fetch_array(pg_query("SELECT b.description AS layanan,a.id, a.item_id, a.qty, a.harga,a.tagihan, a.diskon, a.persen, 
 a.dibayar_penjamin,a.no_reg
FROM rs00008 a LEFT JOIN rs99996 b ON a.item_id::numeric = b.id
WHERE a.id = ".$_GET['id']." AND referensi = 'P'"));
?>
<br/>
<form action="../actions/update_layanan_paket.php" method="post" onsubmit="cekInput()">
<input type="hidden" name="id" value="<?=$_GET['id']?>"/>
<table width="100%">
	<tr>
		<td><b>Paket</b></td><td>:</td><td><input type="text" name="layanan" value="<?=$row['layanan']?>" readonly size="35" /></td>
	</tr>
	<tr>
		<td><b>Qty</b></td><td>:</td><td><input style="text-align:right;" id="qty" name="qty" type="text" value="<?=$row['qty']?>" size="35"/></td>
	</tr>
	<tr>
		<td><b>Harga</b></td><td>:</td><td><input id ="harga" name="harga" style="text-align:right;" type="text" value="<?=$row['harga']?>" readonly size="35" /></td>
	</tr>
	<tr>
		<td><b>Tagihan</b></td><td>:</td><td><input style="text-align:right;" type="text" id="tagihan" name="tagihan" value="<?=$row['tagihan']?>" readonly size="35"  /></td>
	</tr>
	<tr>
		<td><b>Dibayar Penjamin</b></td><td>:</td><td><input style="text-align:right;" type="text" id="dibayar_penjamin" name="dibayar_penjamin" value="<?=$row['dibayar_penjamin']?>" size="35" /></td>
	</tr>
	<tr>
		<td><b>Diskon (%)</b></td><td>:</td><td><input name="persen" id="persen" style="text-align:right;" type="text" value="<?=$row['persen']?>" size="35" /></td>
	</tr>
	<tr>
		<td><b>Diskon (Rp.)</b></td><td>:</td><td><input id="diskon" name="diskon" style="text-align:right;" type="text" value="<?=$row['diskon']?>" size="35" /></td>
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
