<?php
/**
 * Gema Perbangsa
 * 19 September 2013
 */ 

require_once 'lib/functions.php';
title('Tindakan Operasi & Anestesi');

if(empty($_GET['rg'])){
	
}
else{
?>
<link rel="stylesheet" type='text/css' href="jquery-ui.custom.css"/>
<script type="text/javascript" src="plugin/jquery-1.8.2.js"></script>
<script type="text/javascript" src="plugin/jquery-ui.js"></script>
<script language="javascript">
 $(document).ready(function(){
	$("#rincian_operasi").load('./includes/rincian_op.php?rg=<?php echo $_GET['rg'];?>');
    $("#autoCompOp").autocomplete({
	type:'GET',
	source:function(request,response){
	$.ajax({
	url:'./includes/getOperasi.php',
	data: {term : request.term, tbl:'operasi'},
	dataType : 'json',
	success : function(data){
		response(data);		
			},
		});
	},
	selectFirst: true,
	select: function( event, ui ) {
		$("input:hidden[name='idItemOp']").val(ui.item.id);
		$("input:text[name='hargaOperasi']").val(ui.item.harga);
		hitungOperasi();
		},
	});
	
    $("#autoCompAn").autocomplete({
	type:'GET',
	source:function(request,response){
	$.ajax({
	url:'./includes/getOperasi.php',
	data: {term : request.term, tbl:'anestesi'},
	dataType : 'json',
	success : function(data){
		response(data);
			},
		});
	},
	selectFirst: true,
	select: function( event, ui ) {
		$("input:hidden[name='idItemAn']").val(ui.item.id);
		$("input:text[name='hargaAnestesi']").val(ui.item.harga);
		hitungAnestesi();
		},
	});
	
	$("input:text[name='dokterOperasi1']").autocomplete({
	type:'GET',
	source:function(request,response){
	$.ajax({
	url:'./includes/getOperasi.php',
	data: {term : request.term, tbl:'dokter'},
	dataType : 'json',
	success : function(data){
		response(data);
			},
		});
	},
	selectFirst: true,
	select: function( event, ui ) {
		$("input:hidden[name='idOp1']").val(ui.item.id);
		$("input:text[name='dokterOperasi1']").val(ui.item.harga);
		},
	});
	
	$("input:text[name='dokterOperasi2']").autocomplete({
	type:'GET',
	source:function(request,response){
	$.ajax({
	url:'./includes/getOperasi.php',
	data: {term : request.term, tbl:'dokter'},
	dataType : 'json',
	success : function(data){
		response(data);
			},
		});
	},
	selectFirst: true,
	select: function( event, ui ) {
		$("input:hidden[name='idOp2']").val(ui.item.id);
		$("input:text[name='dokterOperasi2']").val(ui.item.harga);
		},
	});
	
	$("input:text[name='dokterAnestesi1']").autocomplete({
	type:'GET',
	source:function(request,response){
	$.ajax({
	url:'./includes/getOperasi.php',
	data: {term : request.term, tbl:'dokter'},
	dataType : 'json',
	success : function(data){
		response(data);
			},
		});
	},
	selectFirst: true,
	select: function( event, ui ) {
		$("input:hidden[name='idAn1']").val(ui.item.id);
		$("input:text[name='dokterAnestesi1']").val(ui.item.harga);
		},
	});
	
	$("input:text[name='dokterAnestesi2']").autocomplete({
	type:'GET',
	source:function(request,response){
	$.ajax({
	url:'./includes/getOperasi.php',
	data: {term : request.term, tbl:'dokter'},
	dataType : 'json',
	success : function(data){
		response(data);
			},
		});
	},
	selectFirst: true,
	select: function( event, ui ) {
		$("input:hidden[name='idAn2']").val(ui.item.id);
		$("input:text[name='dokterAnestesi2']").val(ui.item.harga);
		},
	});
	
	$("input:text[name='asistenOperasi1']").autocomplete({
	type:'GET',
	source:function(request,response){
	$.ajax({
	url:'./includes/getOperasi.php',
	data: {term : request.term, tbl:'asisten'},
	dataType : 'json',
	success : function(data){
		response(data);
			},
		});
	},
	selectFirst: true,
	select: function( event, ui ) {
		$("input:hidden[name='idAsistenOp1']").val(ui.item.id);
		},
	});
	
	$("input:text[name='asistenOperasi2']").autocomplete({
	type:'GET',
	source:function(request,response){
	$.ajax({
	url:'./includes/getOperasi.php',
	data: {term : request.term, tbl:'asisten'},
	dataType : 'json',
	success : function(data){
		response(data);
			},
		});
	},
	selectFirst: true,
	select: function( event, ui ) {
		$("input:hidden[name='idAsistenOp2']").val(ui.item.id);
		},
	});
	
		$("input:text[name='asistenOperasi3']").autocomplete({
	type:'GET',
	source:function(request,response){
	$.ajax({
	url:'./includes/getOperasi.php',
	data: {term : request.term, tbl:'asisten'},
	dataType : 'json',
	success : function(data){
		response(data);
			},
		});
	},
	selectFirst: true,
	select: function( event, ui ) {
		$("input:hidden[name='idAsistenOp3']").val(ui.item.id);
		},
	});
	
	$("input:text[name='asistenAnestesi1']").autocomplete({
	type:'GET',
	source:function(request,response){
	$.ajax({
	url:'./includes/getOperasi.php',
	data: {term : request.term, tbl:'asisten'},
	dataType : 'json',
	success : function(data){
		response(data);
			},
		});
	},
	selectFirst: true,
	select: function( event, ui ) {
		$("input:hidden[name='idAsistenAn1']").val(ui.item.id);
		},
	});
	
	$("input:text[name='asistenAnestesi2']").autocomplete({
	type:'GET',
	source:function(request,response){
	$.ajax({
	url:'./includes/getOperasi.php',
	data: {term : request.term, tbl:'asisten'},
	dataType : 'json',
	success : function(data){
		response(data);
			},
		});
	},
	selectFirst: true,
	select: function( event, ui ) {
		$("input:hidden[name='idAsistenAn2']").val(ui.item.id);
		},
	});
	
	$("input:text[name='asistenAnestesi3']").autocomplete({
	type:'GET',
	source:function(request,response){
	$.ajax({
	url:'./includes/getOperasi.php',
	data: {term : request.term, tbl:'asisten'},
	dataType : 'json',
	success : function(data){
		response(data);
			},
		});
	},
	selectFirst: true,
	select: function( event, ui ) {
		$("input:hidden[name='idAsistenAn3']").val(ui.item.id);
		},
	});
	
	$("input:text[name='diskonNominalOp']").keyup(function(){
		hitungOperasi();
		hitungDiskon(getFloat($("input:text[name='totalOp']").val()), 'diskonNominalOp', 'diskonPersenOp', 'persen');
	});
	
	$("input:text[name='diskonPersenOp']").keyup(function(){
		hitungOperasi();
		hitungDiskon(getFloat($("input:text[name='totalOp']").val()), 'diskonPersenOp', 'diskonNominalOp', 'nominal');
	})
	
	$("input:text[name='diskonNominalAnestesi']").keyup(function(){
		hitungAnestesi();
		hitungDiskon(getFloat($("input:text[name='totalAn']").val()), 'diskonNominalAnestesi', 'diskonPersenAnestesi', 'persen');
	});
	
	$("input:text[name='diskonPersenAnestesi']").keyup(function(){
		hitungOperasi();
		hitungDiskon(getFloat($("input:text[name='totalAn']").val()), 'diskonPersenAnestesi', 'diskonNominalAnestesi', 'nominal');
	})
	
	$("#formOperasi").submit(function(){
		if($("input:hidden[name='idItemOp']").val()==''){
			alert('Layanan Operasi Tidak Boleh Kosong !!! ');
			return false;
		}
		if($("input:hidden[name='idOp1']").val()==''){
			alert('Operator operasi Tidak Boleh Kosong !!! ');
			return false;
		}
		if($("input:hidden[name='idItemAn']").val()==''){
			alert('Layanan Anestesi Tidak Boleh Kosong !!! ');
			return false;
		}
		if($("input:hidden[name='idAn1']").val()==''){
			alert('Dokter Anestesi Tidak Boleh Kosong !!! ');
			return false;
		}
		if($("input:hidden[name='status']").val()=='insert'){
			 var act = './actions/operasi.insert.php';
		}
		else if($("input:hidden[name='status']").val()=='update'){
			 var act = './actions/operasi.update.php';
		}
		
		if($("input:text[name='dokterOperasi2']").val()==''){
			$("input:hidden[name='idOp2']").val(null);		
		}
		
		if($("input:text[name='dokterAnestesi2']").val()==''){
			$("input:hidden[name='idAn2']").val(null);		
		}
		
		if($("input:text[name='asistenOperasi1']").val()==''){
			$("input:hidden[name='idAsistenOp1']").val(null);		
		}
		
		if($("input:text[name='asistenOperasi2']").val()==''){
			$("input:hidden[name='idAsistenOp2']").val(null);		
		}
		
		if($("input:text[name='asistenOperasi3']").val()==''){
			$("input:hidden[name='idAsistenOp3']").val(null);		
		}
		
		if($("input:text[name='dokterAnestesi2']").val()==''){
			$("input:hidden[name='idAn2']").val(null);		
		}
		
		if($("input:text[name='asistenAnestesi1']").val()==''){
			$("input:hidden[name='idAsistenAn1']").val(null);		
		}
		
		if($("input:text[name='asistenAnestesi2']").val()==''){
			$("input:hidden[name='idAsistenAn2']").val(null);		
		}
		
		if($("input:text[name='asistenAnestesi3']").val()==''){
			$("input:hidden[name='idAsistenAn3']").val(null);		
		}
		
		$.post(act, $("#formOperasi").serialize())
		.done(function(msg){
			resetForm();
			$("#rincian_operasi").load('./includes/rincian_op.php?rg=<?php echo $_GET['rg'];?>');
			})
		.fail(function(msg){
			alert('gagal');
			});
		return false;
		});
});

function hapus(trans_group, e){
	if(confirm("Hapus Layanan ?")){
		$.post('./actions/operasi.delete.php', {data: trans_group})
		.done(function(msg){
			$("#rincian_operasi").load('./includes/rincian_op.php?rg=<?php echo $_GET['rg'];?>');
			})
		.fail(function(msg){
			alert('gagal');
			});
		}
	e.preventDefault();
	}
	
function getOperasi(trans_group){
		$.post('./includes/getOperasi.php', {term: trans_group, tbl: 'getData'}, 'json')
		.done(function(msg){
		var mm =  jQuery.parseJSON (msg);
		
		$("input:hidden[name='id08Op']").val(mm[0].id[0]);		
		$("#autoCompOp").val(mm[0].layanan[0]);
		$("input:hidden[name='status']").val('update');		
		if(parseFloat(mm[0].referensi[0])){
			$("#citoOp").prop('checked', true);
		}
		$("input:hidden[name='idItemOp']").val(mm[0].item_id[0]);
		$("input:text[name='hargaOperasi']").val(mm[0].harga[0]);
		$("input:text[name='dibayarPenjaminOperasi']").val(mm[0].dibayar_penjamin[0]);
		$("input:text[name='diskonPersenOp']").val(mm[0].diskon[0]/mm[0].harga[0]*100);
		$("input:text[name='diskonNominalOp']").val(mm[0].diskon[0]);
		$("input:text[name='totalOp']").val(mm[0].tagihan[0]);
		
		$("input:hidden[name='idOp1']").val(mm[0].id_dokter1[0]);
		$("input:text[name='dokterOperasi1']").val(mm[0].dokter1[0]);
		$("input:text[name='diskonPersenOperasi1']").val(0);
		$("input:text[name='diskonNominalOperasi1']").val(mm[0].diskon_dokter1[0]);
		
		$("input:hidden[name='idOp2']").val(mm[0].id_dokter2[0]);
		$("input:text[name='dokterOperasi2']").val(mm[0].dokter2[0]);
		$("input:text[name='diskonPersenOperasi2']").val(0);
		$("input:text[name='diskonNominalOperasi2']").val(mm[0].diskon_dokter2[0]);
		
		$("input:hidden[name='idAsistenOp1']").val(mm[0].id_asisten1[0]);
		$("input:text[name='asistenOperasi1']").val(mm[0].asisten1[0]);
		$("input:text[name='diskonPersenAsistenOperasi1']").val(0);
		$("input:text[name='diskonNominalAsistenOperasi1']").val(mm[0].diskon_asisten1[0]);
		
		$("input:hidden[name='idAsistenOp2']").val(mm[0].id_asisten2[0]);
		$("input:text[name='asistenOperasi2']").val(mm[0].asisten2[0]);
		$("input:text[name='diskonPersenAsistenOperasi2']").val(0);
		$("input:text[name='diskonNominalAsistenOperasi2']").val(mm[0].diskon_asisten2[0]);
		
		$("input:hidden[name='idAsistenOp3']").val(mm[0].id_asisten3[0]);
		$("input:text[name='asistenOperasi3']").val(mm[0].asisten3[0]);
		$("input:text[name='diskonPersenAsistenOperasi3']").val(0);
		$("input:text[name='diskonNominalAsistenOperasi3']").val(mm[0].diskon_asisten3[0]);
		
		/** Anestesi **/
		if(parseFloat(mm[0].referensi[1])){
			$("#citoAn").prop('checked', true);
		}
		
		$("input:hidden[name='id08An']").val(mm[0].id[1]);
		$("#autoCompAn").val(mm[0].layanan[1]);
		$("input:hidden[name='idItemAn']").val(mm[0].item_id[1]);
		$("input:text[name='hargaAnestesi']").val(mm[0].harga[1]);
		$("input:text[name='dibayarPenjaminAnestesi']").val(mm[0].dibayar_penjamin[1]);
		$("input:text[name='diskonPersenAnestesi']").val(mm[0].diskon[1]/mm[0].harga[1]*100);
		$("input:text[name='diskonNominalAnestesi']").val(mm[0].diskon[1]);
		$("input:text[name='totalAn']").val(mm[0].tagihan[1]);
		
		$("input:hidden[name='idAn1']").val(mm[0].id_dokter1[1]);
		$("input:text[name='dokterAnestesi1']").val(mm[0].dokter1[1]);
		$("input:text[name='diskonPersenAnestesi1']").val(1);
		$("input:text[name='diskonNominalAnestesi1']").val(mm[0].diskon_dokter1[1]);
		
		$("input:hidden[name='idAn2']").val(mm[0].id_dokter2[1]);
		$("input:text[name='dokterAnestesi2']").val(mm[0].dokter2[1]);
		$("input:text[name='diskonPersenAnestesi2']").val(0);
		$("input:text[name='diskonNominalAnestesi2']").val(mm[0].diskon_dokter2[1]);
		
		$("input:hidden[name='idAsistenAn1']").val(mm[0].id_asisten1[1]);
		$("input:text[name='asistenAnestesi1']").val(mm[0].asisten1[1]);
		$("input:text[name='diskonPersenAsistenAnestesi1']").val(0);
		$("input:text[name='diskonNominalAsistenAnestesi1']").val(mm[0].diskon_asisten1[1]);
		
		$("input:hidden[name='idAsistenAn2']").val(mm[0].id_asisten2[1]);
		$("input:text[name='asistenAnestesi2']").val(mm[0].asisten2[1]);
		$("input:text[name='diskonPersenAsistenAnestesi2']").val(1);
		$("input:text[name='diskonNominalAsistenAnestesii2']").val(mm[0].diskon_asisten2[1]);
		
		$("input:hidden[name='idAsistenAn3']").val(mm[0].id_asisten3[1]);
		$("input:text[name='asistenAnestesi3']").val(mm[0].asisten3[1]);
		$("input:text[name='diskonPersenAsistenAnestesi3']").val(0);
		$("input:text[name='diskonNominalAsistenAnestesi3']").val(mm[0].diskon_asisten3[1]);
		})
	.fail(function(msg){
			alert('gagal');
		});
	}	
	
	function statusInsert(){
		$("input:hidden[name='status']").val('insert');
		}
	
	function resetForm(){
		$("#autoCompOp").val(null);
		$("input:hidden[name='id08Op']").val(null);
		
		$("input:hidden[name='status']").val('insert');		
		$("input:hidden[name='idItemOp']").val(null);
		$("input:text[name='hargaOperasi']").val(0);
		$("input:text[name='dibayarPenjaminOperasi']").val(0);
		$("input:text[name='diskonPersenOp']").val(0);
		$("input:text[name='diskonNominalOp']").val(0);
		$("input:text[name='totalOp']").val(0);
		
		$("input:hidden[name='idOp1']").val(null);
		$("input:text[name='dokterOperasi1']").val(null);
		$("input:text[name='diskonPersenOperasi1']").val(0);
		$("input:text[name='diskonNominalOperasi1']").val(0);
		
		$("input:hidden[name='idOp2']").val(null);
		$("input:text[name='dokterOperasi2']").val(null);
		$("input:text[name='diskonPersenOperasi2']").val(0);
		$("input:text[name='diskonNominalOperasi2']").val(0);
		
		$("input:hidden[name='idAsistenOp1']").val(0);
		$("input:text[name='asistenOperasi1']").val(null);
		$("input:text[name='diskonPersenAsistenOperasi1']").val(0);
		$("input:text[name='diskonNominalAsistenOperasi1']").val(0);
		
		$("input:hidden[name='idAsistenOp2']").val(null);
		$("input:text[name='asistenOperasi2']").val(null);
		$("input:text[name='diskonPersenAsistenOperasi2']").val(0);
		$("input:text[name='diskonNominalAsistenOperasi2']").val(0);
		
		$("input:hidden[name='idAsistenOp3']").val(null);
		$("input:text[name='asistenOperasi3']").val(null);
		$("input:text[name='diskonPersenAsistenOperasi3']").val(0);
		$("input:text[name='diskonNominalAsistenOperasi3']").val(0);
		
		/** Anestesi **/
		$("#autoCompAn").val(null);
		$("input:hidden[name='id08An']").val(null);
		$("input:hidden[name='idItemAn']").val(null);
		$("input:text[name='hargaAnestesi']").val(0);
		$("input:text[name='dibayarPenjaminAnestesi']").val(0);
		$("input:text[name='diskonPersenAnestesi']").val(0);
		$("input:text[name='diskonNominalAnestesi']").val(0);
		$("input:text[name='totalAn']").val(0);
		
		$("input:hidden[name='idAn1']").val(null);
		$("input:text[name='dokterAnestesi1']").val(null);
		$("input:text[name='diskonPersenAnestesi1']").val(0);
		$("input:text[name='diskonNominalAnestesi1']").val(0);
		
		$("input:hidden[name='idAn2']").val(null);
		$("input:text[name='dokterAnestesi2']").val(null);
		$("input:text[name='diskonPersenAnestesi2']").val(0);
		$("input:text[name='diskonNominalAnestesi2']").val(0);
		
		$("input:hidden[name='idAsistenAn1']").val(null);
		$("input:text[name='asistenAnestesi1']").val(null);
		$("input:text[name='diskonPersenAsistenAnestesi1']").val(0);
		$("input:text[name='diskonNominalAsistenAnestesi1']").val(0);
		
		$("input:hidden[name='idAsistenAn2']").val(null);
		$("input:text[name='asistenAnestesi2']").val(null);
		$("input:text[name='diskonPersenAsistenAnestesi2']").val(0);
		$("input:text[name='diskonNominalAsistenAnestesii2']").val(0);
		
		$("input:hidden[name='idAsistenAn3']").val(null);
		$("input:text[name='asistenAnestesi3']").val(null);
		$("input:text[name='diskonPersenAsistenAnestesi3']").val(0);
		$("input:text[name='diskonNominalAsistenAnestesi3']").val(0);
		}
	
	function getFloat(value){
		var val = parseFloat(value);
		if(isNaN(val)){return 0;}
		return val;
	}	
	
	function hitungOperasi(){
			if($('#citoOp').attr('checked')=='checked'){
			   $('input:text[name="totalOp"]').val(
				getFloat($('input:text[name="hargaOperasi"]').val())*1.25-getFloat($('input:text[name="diskonNominalOp"]').val())
			   );
			}
			else{
			   $('input:text[name="totalOp"]').val(
				getFloat($('input:text[name="hargaOperasi"]').val())-getFloat($('input:text[name="diskonNominalOp"]').val())
			   );
			   }			
	}

	function hitungAnestesi(){
			if($('#citoAn').attr('checked')=='checked'){
			   $('input:text[name="totalAn"]').val(
				getFloat($('input:text[name="hargaAnestesi"]').val())*1.25-getFloat($('input:text[name="diskonNominalAnestesi"]').val())
			   );
			}
			else{
			   $('input:text[name="totalAn"]').val(
				getFloat($('input:text[name="hargaAnestesi"]').val())-getFloat($('input:text[name="diskonNominalAnestesi"]').val())
			   );
			   }		
	}
	
	function hitungDiskon(val,source, target, tipe){
		if(tipe=='persen'){
			$('input:text[name="'+target+'"]').val(val*(getFloat($('input:text[name="'+source+'"]').val())/100));
		}
		else if(tipe=='nominal'){
			$('input:text[name="'+target+'"]').val((getFloat($('input:text[name="'+source+'"]').val())/100*val));
		}
	}
</script>
<form id="formOperasi">
<input type="hidden" name="rg" value="<?php echo $_GET['rg']?>"/>
<input type="hidden" name="status" value="insert"/>
<table cellpadding="5" cellspacing="0" width="100%">
	<tr>
		<th>Tindakan Operasi</th>
	</tr>
	<tr>
		<input type="hidden" name="id08Op"/>
		<td align="center" class="TBL_HEAD">Jenis<br/>Tindakan Operasi</td><td align="center" class="TBL_HEAD">CITO</td>
		<td align="center" class="TBL_HEAD">Harga</td><td align="center" class="TBL_HEAD">Dibayar Penjamin</td>
		<td align="center" class="TBL_HEAD">Diskon (%)</td><td align="center"class="TBL_HEAD">Diskon (Rp.)</td>
		<td align="center" class="TBL_HEAD">Total</td>
	</tr>
	<tr>
		<input type="hidden" name="idItemOp"/>
		<td class="TBL_BODY"><input type="text" name="jenisOperasi" id="autoCompOp"/></td>
		<td class="TBL_BODY"><input id="citoOp" type="checkbox" name="citoOp" onchange="hitungOperasi()"/></td>
		<td class="TBL_BODY"><input type="text" name="hargaOperasi" value="0" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="dibayarPenjaminOperasi" value="0" style="text-align:right;"/></td>
		<td class="TBL_BODY"><input type="text" name="diskonPersenOp" value="0" style="text-align:right;"/></td>
		<td class="TBL_BODY"><input type="text" name="diskonNominalOp" value="0" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="totalOp" value="0" style="text-align:right;" readonly /></td>
	</tr>
</table>
<table cellpadding="5" cellspacing="0" width="45%">	
	<tr>
		<th>Dokter Operasi</th>
	</tr>
	<tr>
		<td align="center" class="TBL_HEAD">Dokter Operator 1</td>
		<!--
		</td><td align="center" class="TBL_HEAD">Diskon (%)</td><td align="center" class="TBL_HEAD">Diskon (Rp.)</td>
		-->
	</tr>
	<tr>
		<input type="hidden" name="idOp1" />
		<td class="TBL_BODY"><input type="text" name="dokterOperasi1" /></td>
		<!--
		<td class="TBL_BODY"><input type="text" name="diskonPersenOperasi1" value="0" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="diskonNominalOperasi1" value="0" style="text-align:right;" /></td>
		-->
	</tr>
	<tr>
		<input type="hidden" name="idOp2" />
		<td align="center" class="TBL_HEAD">Dokter Operator 2</td>
		<!--
		<td align="center" class="TBL_HEAD">Diskon (%)</td>
		<td align="center" class="TBL_HEAD">Diskon (Rp.)</td>
		-->
	</tr>
	<tr>
		<td class="TBL_BODY"><input type="text" name="dokterOperasi2" /></td>
		<!--
		<td class="TBL_BODY"><input type="text" name="diskonPersenOperasi2" value="0" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="diskonNominalOperasi2" value="0" style="text-align:right;" /></td>
		-->
	</tr>
</table>
<table cellpadding="5" cellspacing="0" width="45%">	
	<tr>
		<th>Asisten Operasi</th>
	</tr>
	<tr>
		<td align="center" class="TBL_HEAD">Asisten 1</td></td>
		<!--
		<td align="center" class="TBL_HEAD">Diskon (%)</td><td align="center" class="TBL_HEAD">Diskon (Rp.)</td>
		-->
	</tr>
	<tr>
		<input type="hidden" name="idAsistenOp1" />
		<td class="TBL_BODY"><input type="text" name="asistenOperasi1" /></td>
		<!--
		<td class="TBL_BODY"><input type="text" name="diskonPersenAsistenOperasi1" value="0" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="diskonNominalAsistenOperasi1" value="0" style="text-align:right;" /></td>
		-->
	</tr>
	<tr>
		<input type="hidden" name="idAsistenOp2" />
		<td align="center" class="TBL_HEAD">Asisten 2</td>
		<!--
		<td align="center" class="TBL_HEAD">Diskon (%)</td><td align="center" class="TBL_HEAD">Diskon (Rp.)</td>
		-->
	</tr>
	<tr>
		<td class="TBL_BODY"><input type="text" name="asistenOperasi2" /></td>
		<!--
		<td class="TBL_BODY"><input type="text" name="diskonPersenAsistenOperasi2" value="0" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="diskonNominalAsistenOperasi2" value="0" style="text-align:right;" /></td>
		-->
	</tr>
	<tr>
		<input type="hidden" name="idAsistenOp3" />
		<td align="center" class="TBL_HEAD">Asisten 3</td>
		<!--
		<td align="center" class="TBL_HEAD">Diskon (%)</td><td align="center" class="TBL_HEAD">Diskon (Rp.)</td>
		-->
	</tr>
	<tr>
		<td class="TBL_BODY"><input type="text" name="asistenOperasi3" /></td>
		<!--
		<td class="TBL_BODY"><input type="text" name="diskonPersenAsistenOperasi3" value="0" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="diskonNominalAsistenOperasi3" value="0" style="text-align:right;" /></td>
		-->
	</tr>
</table>
<hr></hr>
<table cellpadding="5" cellspacing="0" width="100%">
	<tr>
		<th>Tindakan Anestesi</th>
	</tr>
	<tr>
		<input type="hidden" name="id08An"/>
		<td align="center" class="TBL_HEAD">Jenis<br/>Tindakan Anestesi</td><td align="center" class="TBL_HEAD">CITO</td>
		<td align="center" class="TBL_HEAD">Harga</td><td align="center" class="TBL_HEAD">Dibayar Penjamin</td>
		<td align="center" class="TBL_HEAD">Diskon (%)</td><td align="center"class="TBL_HEAD">Diskon (Rp.)</td>
		<td align="center" class="TBL_HEAD">Total</td>
	</tr>
	<tr>
		<input type="hidden" name="idItemAn" />
		<td class="TBL_BODY"><input type="text" id="autoCompAn" name="jenisAnestesi"/></td>
		<td class="TBL_BODY"><input id="citoAn" type="checkbox" name="citoAn" onchange="hitungAnestesi()"/></td>
		<td class="TBL_BODY"><input type="text" name="hargaAnestesi" value="0" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="dibayarPenjaminAnestesi" value="0" style="text-align:right;"/></td>
		<td class="TBL_BODY"><input type="text" name="diskonPersenAnestesi" value="0" style="text-align:right;"/></td>
		<td class="TBL_BODY"><input type="text" name="diskonNominalAnestesi" value="0" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="totalAn" value="0" style="text-align:right;" readonly /></td>
	</tr>
</table>
<table cellpadding="5" cellspacing="0">	
	<tr>
		<th>Dokter Anestesi</th>
	</tr>
	<tr>
		<td align="center" class="TBL_HEAD">Dokter Anestesi 1</td></td>
		<!--
		<td align="center" class="TBL_HEAD">Diskon (%)</td><td align="center" class="TBL_HEAD">Diskon (Rp.)</td>
		-->
	</tr>
	<tr>
		<input type="hidden" name="idAn1" />
		<td class="TBL_BODY"><input type="text" name="dokterAnestesi1" /></td>
		<!--
		<td class="TBL_BODY"><input type="text" name="diskonPersenAnestesi1" value="0" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="diskonNominalAnestesi1" value="0" style="text-align:right;" /></td>
		-->
	</tr>
	<tr>
		<input type="hidden" name="idAn2" />
		<td align="center" class="TBL_HEAD">DokterAnestesi 2</td>
		<!--
		<td align="center" class="TBL_HEAD">Diskon (%)</td><td align="center" class="TBL_HEAD">Diskon (Rp.)</td>
		-->
	</tr>
	<tr>
		<td class="TBL_BODY"><input type="text" name="dokterAnestesi2" /></td>
		<!--
		<td class="TBL_BODY"><input type="text" name="diskonPersenAnestesi2" value="0" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="diskonNominalAnestesi2" value="0" style="text-align:right;" /></td>
		-->
	</tr>
</table>
<table cellpadding="5" cellspacing="0" width="45%" >	
	<tr>
		<th>Asisten Anestesi</th>
	</tr>
	<tr>
		<td align="center" class="TBL_HEAD">Asisten 1</td></td>
		<!--
		<td align="center" class="TBL_HEAD">Diskon (%)</td><td align="center" class="TBL_HEAD">Diskon (Rp.)</td>
		-->
	</tr>
	<tr>
		<input type="hidden" name="idAsistenAn1" />
		<td class="TBL_BODY"><input type="text" name="asistenAnestesi1" /></td>
		<!--
		<td class="TBL_BODY"><input type="text" name="diskonPersenAsistenAnestesi1" value="0" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="diskonNominalAsistenAnestesi1" value="0" style="text-align:right;" /></td>
		-->
	</tr>
	<tr>
		<td align="center" class="TBL_HEAD">Asisten 2</td>
		<!--
		<td align="center" class="TBL_HEAD">Diskon (%)</td><td align="center" class="TBL_HEAD">Diskon (Rp.)</td>
		-->
	</tr>
	<tr>
		<input type="hidden" name="idAsistenAn2" />
		<td class="TBL_BODY"><input type="text" name="asistenAnestesi2" /></td>
		<!--
		<td class="TBL_BODY"><input type="text" name="diskonPersenAsistenAnestesi2" value="0" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="diskonNominalAsistenAnestesi2" value="0" style="text-align:right;" /></td>
		-->
	</tr>
	<tr>
		<input type="hidden" name="idOp2" />
		<td align="center" class="TBL_HEAD">Asisten 3</td>
		<!--
		<td align="center" class="TBL_HEAD">Diskon (%)</td><td align="center" class="TBL_HEAD">Diskon (Rp.)</td>
		-->
	</tr>
	<tr>
		<input type="hidden" name="idAsistenAn3" />
		<td class="TBL_BODY"><input type="text" name="asistenAnestesi3" /></td>
		<!--
		<td class="TBL_BODY"><input type="text" name="diskonPersenAsistenAnestesi3" value="0" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="diskonNominalAsistenAnestesi3" value="0" style="text-align:right;" /></td>
		-->
	</tr>
</table>
<input type="submit" value="SIMPAN"/><input type="reset" onclick="statusInsert()" value="RESET"/>
</form>
<div id="rincian_operasi">

</div>
<?php
}
?>


