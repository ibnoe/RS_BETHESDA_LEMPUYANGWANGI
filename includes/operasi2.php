<?php
/**
 * Gema Perbangsa
 * 19 September 2013
 */ 

require_once 'lib/functions.php';
title('Tindakan Operasi');
$sql = "SELECT a.item_id,a.mr_no,a.no_reg,a.tdesc, a.nama, a.layanan, a.harga, a.diskon, a.dibayar_penjamin,a.tagihan, 
CASE WHEN a.status = 1 THEN 'SUDAH DIINPUT' ELSE 'BELUM DIINPUT' END, a.id, a.referensi, a.persen, a.no_kwitansi,
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
WHERE a.id =".$_GET['input'];
//echo $sql;
$result = pg_fetch_array(pg_query($sql));

$sql_info_pasien = "SELECT DISTINCT a.id, tanggal(a.tanggal_reg,0) AS tanggal_reg, a.waktu_reg, 
            a.mr_no, e.nama, to_char(e.tgl_lahir, 'DD MONTH YYYY') AS tgl_lahir, 
            e.tmp_lahir, CASE WHEN e.jenis_kelamin = 'L' THEN 'Laki - Laki' ELSE 'Wanita' END AS jenis_kelamin, 
            f.tdesc AS agama, 
            e.alm_tetap, e.kota_tetap, e.pos_tetap, e.tlp_tetap, 
            a.id_penanggung, b.tdesc AS penanggung, a.id_penjamin, 
            c.tdesc AS penjamin, a.no_jaminan, a.rujukan, a.rujukan_rs_id, 
            d.tdesc AS rujukan_rs, a.rujukan_dokter, a.rawat_inap, 
            a.status, a.tipe, g.tdesc AS tipe_desc, a.diagnosa_sementara, 
            to_char(a.tanggal_reg, 'DD MONTH YYYY') AS tanggal_reg_str, 
                CASE
                    WHEN a.rawat_inap = 'I' THEN 'Rawat Inap'  
                    WHEN a.rawat_inap = 'Y' THEN 'Rawat Jalan'
                    ELSE 'IGD' 
                END AS rawatan,
                age(a.tanggal_reg , e.tgl_lahir ) AS umur, 
		case when a.rujukan = 'Y' then 'Rujukan' 
		     when a.rujukan ='U' then 'Unit Lain'  else 'Non-Rujukan'
               end as datang,  
           i.tdesc as  poli 
        FROM rs00006 a 
           LEFT JOIN rs00001 b ON a.id_penanggung = b.tc AND b.tt = 'PEN'
           LEFT JOIN rs00001 c ON a.id_penjamin = c.tc AND c.tt = 'PJN' 
           LEFT JOIN rs00002 e ON a.mr_no = e.mr_no 
           LEFT JOIN rs00001 f ON e.agama_id = f.tc AND f.tt = 'AGM' 
           LEFT JOIN rs00001 g ON a.tipe = g.tc AND g.tt = 'JEP' 
           LEFT JOIN rs00001 d ON a.id_penjamin = d.tc AND d.tt = 'RUJ'
           LEFT JOIN rs00001 h ON a.jenis_kedatangan_id = h.tc and h.tt = 'JDP'
		 left join rs00001 i on i.tc_poli = a.poli 
		WHERE a.id = '".$result['no_reg']."'";

$result_info = pg_fetch_array(pg_query($sql_info_pasien));	

	
?>
<link rel="stylesheet" type='text/css' href="jquery-ui.custom.css"/>
<script type="text/javascript" src="plugin/jquery-1.8.2.js"></script>
<script type="text/javascript" src="plugin/jquery-ui.js"></script>
<script language="javascript">
 $(document).ready(function(){
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
	
	$("input:text[name='diskonNominalOp']").keyup(function(){
		hitungOperasi();
		hitungDiskon(getFloat($("input:text[name='totalOp']").val()), 'diskonNominalOp', 'diskonPersenOp', 'persen');
	});
	
	$("input:text[name='diskonPersenOp']").keyup(function(){
		hitungOperasi();
		hitungDiskon(getFloat($("input:text[name='totalOp']").val()), 'diskonPersenOp', 'diskonNominalOp', 'nominal');
	});
	
	$("#formOperasi").submits(function(){
		if($("input:hidden[name='idItemOp']").val()==''){
			alert('Layanan Operasi Tidak Boleh Kosong !!! ');
			return false;
		}
		if($("input:hidden[name='idOp1']").val()==''){
			alert('Operator operasi Tidak Boleh Kosong !!! ');
			return false;
		}
		return false;
		});
});
	
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
		})
	.fail(function(msg){
			alert('gagal');
		});
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
	
	function hitungDiskon(val,source, target, tipe){
		if(tipe=='persen'){
			$('input:text[name="'+target+'"]').val(val*(getFloat($('input:text[name="'+source+'"]').val())/100));
		}
		else if(tipe=='nominal'){
			$('input:text[name="'+target+'"]').val((getFloat($('input:text[name="'+source+'"]').val())/100*val));
		}
	}
</script>
<form id="formOperasi" action="actions/operasi_simpan.php" method="post">
<input type="hidden" name="rg" value="<?php echo $result_info['id'];?>"/>
<input type="hidden" name="status" value="insert"/>
<input type="hidden" name="idrs08" value="<?php echo $result['id'];?>" />
<table width="75%" border="1" cellspacing="1">
	<tr>
		<td>No.Reg</td><td align="center">:</td><td><?php echo $result_info['id'];?></td><td>Alamat</td><td align="center">:</td><td><?php echo $result_info['alm_tetap'];?></td>
	</tr>
	<tr>
		<td>No.MR</td><td align="center">:</td><td><?php echo $result_info['mr_no'];?></td><td>Telepon</td><td align="center">:</td><td><?php echo $result_info['tlp_tetap'];?></td>
	</tr>
	<tr>
		<td>Nama</td><td align="center">:</td><td><?php echo $result_info['nama'];?></td><td>Tanggal Registrasi</td><td align="center">:</td><td><?php echo $result_info['tanggal_reg'];?></td>
	</tr>
	<tr>
		<td>Pasien</td><td align="center">:</td><td><?php echo $result_info['rawatan'];?></td><td>Tipe Pasien</td><td align="center">:</td><td><?php echo $result_info['tipe_desc'];?></td>
	</tr>
	<tr>
		<td>Bangsal</td><td align="center">:</td><td><?php echo getFromTable("select c.bangsal || ' / ' || e.tdesc ".
                       "from rs00010 as a ".
                       "    join rs00012 as b on a.bangsal_id = b.id ".
                       "    join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' ".
                       "    join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR' ".
                       "where a.id = '".getFromTable("select max(id) from rs00010 where no_reg = '".$result_info['id']."'")."'");;?></td><td>Umur</td><td align="center">:</td><td><?php echo $result_info['umur'];?></td>
	</tr>
	<tr>
		<td>Kedatangan</td><td align="center">:</td><td><?php echo $result_info['datang'];?></td><td>Jenis Kelamin</td><td align="center">:</td><td><?php echo $result_info['jenis_kelamin'];?></td>
	</tr>
</table>
<table cellpadding="5" cellspacing="0">
	<tr>
		<th colspan="3">TINDAKAN OPERASI / ANESTESI</th>
	</tr>
	<tr>
		<input type="hidden" name="id08Op" value="<?php echo $result['id'];?>" />
		<td align="center" class="TBL_HEAD">JENIS TINDAKAN<br/>OPERASI / ANESTESI</td><td align="center" class="TBL_HEAD">CITO</td>
		<td align="center" class="TBL_HEAD">HARGA</td><td align="center" class="TBL_HEAD">TAGIHAN</td>
		<td align="center" class="TBL_HEAD">DIBAYAR<br>PENJAMIN</td>
		<td align="center" class="TBL_HEAD">DISKON (%)</td><td align="center"class="TBL_HEAD">DISKON (Rp.)</td>
		<td align="center" class="TBL_HEAD">TOTAL</td>
	</tr>
	<tr>
		<input type="hidden" name="idItemOp" value="<?php echo $result['item_id'];?>" />
		<td class="TBL_BODY"><input type="text" name="jenisOperasi" id="autoCompOp" value="<?php echo $result['layanan'];?>" readonly /></td>
		<td class="TBL_BODY"><input id="citoOp" type="checkbox" name="citoOp" onchange="hitungOperasi()" <?php if ($result['referensi'] > 0) {echo 'checked';}?> disabled /></td>
		<td class="TBL_BODY"><input type="text" name="hargaOperasi" value="<?php echo $result['harga'];?>" style="text-align:right;" readonly /></td>
		<td class="TBL_BODY"><input type="text" name="tagihanOperasi" value="<?php echo $result['tagihan'];?>" style="text-align:right;" readonly /></td>
		<td class="TBL_BODY"><input type="text" name="dibayarPenjaminOperasi" value="<?php echo (double) $result['layanan'];?>" style="text-align:right;" readonly /></td>
		<td class="TBL_BODY"><input type="text" name="diskonPersenOp" value="<?php echo (double) $result['persen'];?>" style="text-align:right;" readonly /></td>
		<td class="TBL_BODY"><input type="text" name="diskonNominalOp" value="<?php echo (double) $result['diskon'];?>" style="text-align:right;" readonly /></td>
		<td class="TBL_BODY"><input type="text" name="totalOp" value="0" style="text-align:right;" <?php echo (double) $result['tagihan'];?> readonly /></td>
	</tr>
</table>
<?php 
$jasmed = getJasmedOp($result); 
?>
<table width="65%" cellspacing="0" cellpadding="5">		
	<tr>
		<th>DOKTER OPERASI / ANESTESI</th>
	</tr>
	<tr>
		<td align="center" class="TBL_HEAD">POTONGAN</td><td align="center" class="TBL_HEAD">RS (10%)</td><td align="center" class="TBL_HEAD">RS (2.5%)</td>
		<td align="center" class="TBL_HEAD">NON MEDIS</td><td align="center" class="TBL_HEAD">ZAKAT</td><td align="center" class="TBL_HEAD">TERIMA</td>
	</tr>
	<tr>
		<td align="right" class="TBL_BODY"><?php echo number_format($jasmed['potongan'],2);?></td><td align="right" class="TBL_BODY"><?php echo number_format($jasmed['rs_10'],2);?></td>
		<td align="right" class="TBL_BODY"><?php echo number_format($jasmed['rs_025'],2);?></td><td align="right" class="TBL_BODY"><?php echo number_format($jasmed['non_medis'],2);?></td>
		<td align="right" class="TBL_BODY"><?php echo number_format($jasmed['zakat'],2);?></td><td align="center" class="TBL_BODY"><b><?php echo number_format($jasmed['terima_dokter'],2);?></b></td>
	</tr>
</table>
<table cellpadding="5" cellspacing="0" width="65%">
	<tr>
		<td align="center" class="TBL_HEAD">Dokter Operator 1</td></td><td align="center" class="TBL_HEAD">Diskon (%)</td><td align="center" class="TBL_HEAD">Diskon (Rp.)</td><td align="center" class="TBL_HEAD">Terima</td>
	</tr>
	<tr>
		<input type="hidden" name="idOp1" value="<?php echo $result['id_dokter1'];?>"/>
		<td class="TBL_BODY"><input type="text" name="dokterOperasi1" value="<?php echo $result['dokter1'];?>" /></td>		
		<td class="TBL_BODY"><input type="text" name="diskonPersenOperasi1" value="<?php echo (double)$result['persen_dokter1'];?>" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="diskonNominalOperasi1" value="<?php echo (double)$result['diskon_dokter1'];?>" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="totalTerimaOperasi1" value="<?php echo (double)$result['terima_dokter1'];?>" style="text-align:right;" /></td>
	</tr>
	<tr>
		<input type="hidden" name="idOp2" value="<?php echo $result['id_dokter2'];?>" />
		<td align="center" class="TBL_HEAD">Dokter Operator 2</td>
		<td align="center" class="TBL_HEAD">Diskon (%)</td>
		<td align="center" class="TBL_HEAD">Diskon (Rp.)</td>
		<td align="center" class="TBL_HEAD">Terima (Rp.)</td>
	</tr>
	<tr>
		<td class="TBL_BODY"><input type="text" name="dokterOperasi2" value="<?php echo $result['dokter2'];?>" /></td>
		<td class="TBL_BODY"><input type="text" name="diskonPersenOperasi2" value="<?php echo (double)$result['persen_dokter2'];?>" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="diskonNominalOperasi2" value="<?php echo (double)$result['persen_dokter2'];?>" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="totalTerimaOperasi2" value="<?php echo (double)$result['persen_dokter2'];?>" style="text-align:right;" /></td>
	</tr>
</table>
<table width="65%" cellspacing="0" cellpadding="5">		
	<tr>
		<th>ASISTEN OPERASI / ANESTESI</th>
	</tr>
	<tr>
		<td align="center" class="TBL_HEAD">POTONGAN</td><td align="center" class="TBL_HEAD">RS (5%)</td><td align="center" class="TBL_HEAD">RS (2.5%)</td>
		<td align="center" class="TBL_HEAD">NON MEDIS</td><td align="center" class="TBL_HEAD">ZAKAT</td><td align="center" class="TBL_HEAD">TERIMA</td>
	</tr>
	<tr>
		<td align="right" class="TBL_BODY"><?php echo number_format($jasmed['asst_potongan'],2);?></td><td align="right" class="TBL_BODY"><?php echo number_format($jasmed['asst_rs_05'],2);?></td>
		<td align="right" class="TBL_BODY"><?php echo number_format($jasmed['asst_rs_025'],2);?></td><td align="right" class="TBL_BODY"><?php echo number_format($jasmed['asst_non_medis'],2);?></td>
		<td align="right" class="TBL_BODY"><?php echo number_format($jasmed['asst_zakat'],2);?></td><td align="center" class="TBL_BODY"><b><?php echo number_format($jasmed['terima_asisten'],2);?></b></td>
	</tr>
</table>
<table cellpadding="5" cellspacing="0" width="65%">
	<tr>
		<td align="center" class="TBL_HEAD">Asisten 1</td></td>
		<td align="center" class="TBL_HEAD">Diskon (%)</td><td align="center" class="TBL_HEAD">Diskon (Rp.)</td>
		<td align="center" class="TBL_HEAD">Terima (Rp.)</td>
	</tr>
	<tr>
		<input type="hidden" name="idAsistenOp1" value="<?php echo $result['id_asisten1'];?>" />
		<td class="TBL_BODY"><input type="text" name="asistenOperasi1" value="<?php echo $result['asisten1'];?>"/></td>
		<td class="TBL_BODY"><input type="text" name="diskonPersenAsistenOperasi1" value="<?php echo (double)$result['persen_asisten1'];?>" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="diskonNominalAsistenOperasi1" value="<?php echo (double)$result['diskon_asisten1'];?>" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="terimaAsistenOperasi1" value="<?php echo (double)$result['terima_asisten1'];?>" style="text-align:right;"/></td>
	</tr>
	<tr>
		<input type="hidden" name="idAsistenOp2" value="<?php echo $result['id_asisten2'];?>" />
		<td align="center" class="TBL_HEAD">Asisten 2</td>
		<td align="center" class="TBL_HEAD">Diskon (%)</td><td align="center" class="TBL_HEAD">Diskon (Rp.)</td>
		<td align="center" class="TBL_HEAD">Terima (Rp.)</td>
	</tr>
	<tr>
		<td class="TBL_BODY"><input type="text" name="asistenOperasi2" value="<?php echo $result['asisten2'];?>" /></td>
		<td class="TBL_BODY"><input type="text" name="diskonPersenAsistenOperasi2" value="<?php echo (double)$result['persen_asisten2'];?>" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="diskonNominalAsistenOperasi2" value="<?php echo (double)$result['diskon_asisten2'];?>" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="terimaAsistenOperasi2" value="<?php echo (double)$result['terima_asisten2'];?>" style="text-align:right;"/></td>
	</tr>
	<tr>
		<input type="hidden" name="idAsistenOp3" value="<?php echo $result['id_asisten3'];?>" />
		<td align="center" class="TBL_HEAD">Asisten 3</td>
		<td align="center" class="TBL_HEAD">Diskon (%)</td><td align="center" class="TBL_HEAD">Diskon (Rp.)</td>
		<td align="center" class="TBL_HEAD">Terima (Rp.)</td>
	</tr>
	<tr>
		<td class="TBL_BODY"><input type="text" name="asistenOperasi3" value="<?php echo $result['asisten3'];?>" /></td>
		<td class="TBL_BODY"><input type="text" name="diskonPersenAsistenOperasi3" value="<?php echo (double)$result['persen_asisten3'];?>" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="diskonNominalAsistenOperasi3" value="<?php echo (double)$result['diskon_asisten3'];?>" style="text-align:right;" /></td>
		<td class="TBL_BODY"><input type="text" name="terimaAsistenOperasi3" value="<?php echo (double)$result['terima_asisten3'];?>" style="text-align:right;"/></td>
	</tr>
</table>
<hr></hr>
</table>
<input type="submit" value="SIMPAN"/><input type="reset" onclick="statusInsert()" value="RESET"/>
</form>


