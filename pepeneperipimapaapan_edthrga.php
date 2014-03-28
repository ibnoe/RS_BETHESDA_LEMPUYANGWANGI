<?php
	
	$r = pg_query($con, "select a.*,b.tdesc as sat1,c.harga,d.ppn from c_po_item a, rs00001 b,rs00016 c,c_po d where a.satuan1=b.tc and b.tt='SAT' and a.po_id = '".$_GET["poid"]."' and a.item_id= '".$_GET["e"]."' and c.obat_id= '".$_GET["e"]."' and a.bonus=$_GET[b] and a.po_id=d.po_id");
    $n = pg_num_rows($r);
    $d = pg_fetch_object($r);
    
    $r1 = pg_query($con, "select a.*,b.tdesc as sat2 from rs00015 a, rs00001 b where a.satuan_id=b.tc and tt='SAT' and a.id::text= '".$_GET["e"]."'");
    $n1 = pg_num_rows($r1);
    $d1 = pg_fetch_object($r1);
    
    $r2 = pg_query($con, "select * from c_po where po_id = '".$_GET["poid"]."'");
    $n2 = pg_num_rows($r2);
    if($n2 > 0) $d2 = pg_fetch_object($r2);


    pg_free_result($r);
	
	$r3 = pg_query($con, "select a.kode_trans,  b.tdesc as satuan1, a.jumlah2,a.jumlah1, c.tdesc as satuan2 
		from rs00016d a, rs00001 b, rs00001 c 
		where a.satuan1=b.tc and b.tt='SAT' and a.satuan2=c.tc and c.tt='SAT' and a.kode_trans = '".$d->kode_trans."'");
	$d3 = pg_fetch_object($r3);
	pg_free_result($r3);
		
    title_print("Rincian Item");
    $supplier = getFromTable(
               "select a.nama from rs00028 a, c_po b ".
               "where  a.id=b.supp_id::text and b.supp_id::text='".$d2->supp_id."' ");
    $tanggal_po = getFromTable(
               "select to_char(po_tanggal,'DD Mon YYYY') from c_po ".
               "where po_id='".$_GET["poid"]."' ");
    $jam_po = getFromTable(
               "select po_jam from c_po ".
               "where po_id='".$_GET["poid"]."' ");
    $tanggung_jwb = getFromTable(
               "select po_personal from c_po ".
               "where po_id='".$_GET["poid"]."' ");
	
	$expire = getFromTable(
               "select expire from c_po_item_terima ".
               "where po_id='".$_GET["poid"]."' and item_id='".$_GET["e"]."' ");
	
	$batch = getFromTable(
               "select batch from c_po_item_terima ".
               "where po_id='".$_GET["poid"]."'  and item_id='".$_GET["e"]."'");
	

    $f = new Form("");
echo "<br>";
echo "<table class='design10a'>";
	echo "<tr>";
		echo "<td class='design10a'><b> NO. PO </td>";
		echo "<td class='design10'><b>: ".$_GET["poid"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td class='design10a'><b> NAMA SUPPLIER</td>";
		echo "<td class='design10'><b>: $supplier </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td class='design10a'><b> TANGGAL PO </td>";
		echo "<td class='design10'><b>: $tanggal_po </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td class='design10a'><b> PENANGGUNG JAWAB</td>";
		echo "<td class='design10'><b>: $tanggung_jwb </td>";
	echo "</tr>";
echo "</table>";
$f->execute();
echo "<br>";    

   
if($_GET["edit"]=="edit"){
$no_faktur = getFromTable(
               "select no_faktur from c_po_item_terima where po_id ='".$d->po_id."' ");
	$jatuh_tempo = getFromTable(
               "select jatuh_tempo from c_po_item_terima where po_id ='".$d->po_id."' ");
	echo "<form action=actions/360_2.update.php method=POST name=formx>";
	echo "<input type=hidden name=p value=$PID>";
	echo "<input type=hidden name=httpHeader value=1>";
	echo "<input type=hidden name=item_id value='".$d->item_id."'>";
	echo "<input type=hidden name=po_id value='".$d->po_id."'>";
        echo "<input type=hidden name=item_qty2 value='".$_GET[q]."'>";
        echo "<input type=hidden name=qty_terkecil value='".$d->jumlah2."'>";
	    echo "<input type=hidden name=bonus value='".$_GET[b]."'>";
	echo "<table border=0 class='design10a'>";
	
	
	echo "<tr><td class=design10a>No. Faktur</td><td class=FORM>:</td>";
	echo "    <td colspan=2 class=design10><input type=TEXT name=no_faktur id=no_faktur size=30 maxlength=30 value='".$no_faktur."'></td></tr>";
	echo "<TR ><TD class=design10a>Tanggal Jatuh Tempo </TD><TD CLASS=FORM>:</TD>\n";
	echo "<TD class=design10 VALIGN='ABSMIDDLE'><INPUT TYPE=TEXT NAME=jatuh_tempo id=jatuh_tempo SIZE=10 MAXLENGTH=12 VALUE='".$jatuh_tempo."'>\n";
	echo "<A HREF=\"#\" onClick=\"cal.select(document.forms['formx'].jatuh_tempo,'tanggalan','yyyy-MM-dd'); return false;\" NAME=\"tanggalan\" ID=\"tanggalan\" ><INPUT TYPE='IMAGE' SRC='icon/calendar.gif' TITLE='Pilih' ></A></TD>\n";
	echo "</TR>\n\n";
	echo "<tr><td class=design10a>Jam Terima (format = 24)</td><td class=FORM>:</td>";
	 $jam 	= getFromTable("select to_char(CURRENT_TIMESTAMP,'HH24:MI') as jam");
	echo "    <td colspan=2 class=design10><input type=TEXT name=jam_terima id=jam_terima size=5 maxlength=5 value='".$jam."'></td></tr>";
	
	
	echo "<tr><td class=design10a>Kode Obat</td><td class=FORM>:</td>";
	echo "    <td class=design10 width=1><input style='text-align:center' type=TEXT name=item_id1 size=15 maxlength=10 value='".$d->item_id."' disabled></td>";
	echo "<tr><td class=design10a>Nama Obat</td><td class=FORM>:</td>";
	echo "    <td class=design10 width=1><input style='text-align:left' type=TEXT name=nama_obat1 size=40 maxlength=50 value='".$_GET["o"]."' disabled></td>";
	echo "<tr><td class=design10a>Satuan Jual</td><td class=FORM>:</td>";
	echo "    <td class=design10 width=1><input style='text-align:left' type=TEXT name=sat_jual size=40 maxlength=50 value='".$d1->sat2."' disabled></td>";
	echo "<tr><td class=design10a>Isi per ".$d->sat1."</td><td class=FORM>:</td>";
	echo "    <td class=design10 width=1><input style='text-align:left' type=TEXT name=sat_jual size=15 maxlength=10 value='".$d->jumlah2." ".$d1->sat2."' disabled> </td>";
	if($_GET['b']==1){
		echo "<tr><td class=design10a>Keterangan</td><td class=FORM>:</td>";
		echo "    <td class=design10 width=1><input style='text-align:left' type=TEXT name=sat_jual size=15 maxlength=50 value='BONUS' disabled></td>";
	}
	echo "<tr><td class=design10a>Jumlah Permintaan </td><td class=FORM>:</td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=text name=item_qty size=5 maxlength=10 value='".$_GET[q]."' readonly> ".$d->sat1."</td></tr>";
	echo "<tr><td class=design10a>Jumlah Penerimaan</td><td class=FORM>:</td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=text name=qty_terima id='jum_terima' size=5 maxlength=10 value='' onKeyUp='hitung()' > ".$d->sat1."</td></tr>";
	
	echo "<tr><td class=design10a>Batch Id </td><td class=FORM>:</td>";
	echo "    <td class=design10 colspan=2><input type=TEXT name=batch size=30 maxlength=30 value='$batch'></td></tr>";
	echo "<TR ><TD CLASS=design10a>Tanggal Expire </TD><TD CLASS=FORM>:</TD>\n";
	echo "<TD CLASS=design10 VALIGN='ABSMIDDLE'><INPUT TYPE=TEXT NAME=expire SIZE=10 MAXLENGTH=12 VALUE='".$expire."'>\n";
	echo "<A HREF=\"#\" onClick=\"cal.select(document.forms['formx'].expire,'tanggalan','yyyy-MM-dd'); return false;\" NAME=\"tanggalan\" ID=\"tanggalan\" ><INPUT TYPE='IMAGE' SRC='icon/calendar.gif' TITLE='Pilih' ></A></TD>\n";
	echo "</TR>\n\n";
	
	echo "<tr><td class=design10a>Locator/Rak</td><td class=FORM>:</td>";
	echo "    <td class=design10 width=1><input style='text-align:left' type=TEXT name=locator size=15 maxlength=10 value='".$d1->locator."' ></td>";
	
    echo "<tr><td class=design10a>Harga Beli  per ".$d->sat1."</td><td class=FORM>:</td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=text id='harga_beli'  name=harga_beli_pesan size=15 maxlength=20 value='".$d->harga_beli*$d->jumlah2."' onchange='hitung()'> (Rp.)</td></tr>";
	if($_GET['b']==0){
	echo "<tr><td class=design10a>Termasuk PPN </td><td class=FORM>:</td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=checkbox name=cek_ppn value='1' id='cek_ppn'> <span id='cek_detail1'>Ya</span></td></tr>";
	}
	echo "<tr><td class=design10a>Discount </td><td class=FORM>:</td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=text name=diskon1 size=15 maxlength=20 value='".$d->diskon1."' onchange='hitung()'> (%) <input style='text-align:right' type=text name=jumdis1 value='". $d->diskon1*($d->harga_beli*($d->jumlah2*$_GET[q]))/100 ."' size=15 maxlength=20 readonly></td></tr>";
	echo "<tr><td class=design10a></td><td class=FORM></td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=hidden name=diskon2 size=15 maxlength=20 value='".$d->diskon2."' onchange='hitung()'><input style='text-align:right' type=hidden name=jumdis2 value='". $d->diskon2*($d->harga_beli*($d->jumlah2*$_GET[q]))/100 ."' size=15 maxlength=20 readonly></td></tr>";
	echo "<tr><td class=design10a  ><span class='ppn'>PPN</span> </td><td class=FORM ><span class='ppn'>:</span></td>";
	if($_GET['b']==0){
		$ppn =$d->ppn;
	}else{
		$ppn = 0;
	}
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=text name=ppn size=15 maxlength=20 class='ppn' value='".$ppn."' onchange='hitung()'> <span class='ppn'>(%) </span> <input style='text-align:right' type=text name=jumppn class='ppn' id='jumlah_ppn' value='". $d->ppn*($d->harga_beli*($d->jumlah2*$_GET[q]))/100 ."' size=15 maxlength=20 readonly></td></tr>";
	echo "<tr><td class=design10a>Materai</td><td class=FORM>:</td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=text name=materai size=15 maxlength=20 value='0' onchange='hitung()'> (Rp.)</td></tr>";
	echo "<tr><td class=design10a>Total Harga</td><td class=FORM>:</td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right'  id='harga_jual'  type=text name=tot_harga size=15 maxlength=20 value='' readonly> (Rp.)</td></tr>";
	echo "<tr><td class=design10a colspan=3><hr/></td></tr>";
	echo "<tr><td class=design10a></td><td class=FORM></td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=hidden name=harga_beli size=15 maxlength=20 value='".$d->harga_beli."' readonly> <input style='text-align:right' type=hidden name=jumlah_terkecil size=15 maxlength=20 value='".$d->jumlah2."' readonly> </td></tr>";
	echo "<tr><td class=design10a></td><td class=FORM></td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=hidden name=ppnbtr size=15 maxlength=20 value='".$d->ppn."' onchange='hitung()'>  <input style='text-align:right' type=hidden name=jumppnbtr value='".(($d->ppn*$d->harga_beli)/100)."' size=15 maxlength=20 readonly></td></tr>";
	echo "<tr><td class=design10a></td><td class=FORM></td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=hidden name=resep size=15 maxlength=20 value='20' onchange='hitung()'></td></tr>";
	
	echo "<tr><td class=design10a></td><td class=FORM></td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=hidden name=harga_jual size=15 maxlength=20 value='".($d->harga_beli+(($d->ppn*$d->harga_beli)/100)+((20*$d->harga_beli)/100))."' ></td></tr>";
	
	
        echo "<tr><td class=>&nbsp;</td><td class=FORM>&nbsp;</td>"; 
	echo "    <td class= colspan=2><input type=SUBMIT value='Submit'></td></tr>";
	echo "</tr></table>";
	echo "</form>";
 }else{
	$tot_qty= $d3->jumlah1 * $_GET[q];
 	echo "<form action=actions/360_2_hrg.update.php method=POST name=formx>";
	echo "<input type=hidden name=p value=$PID>";
	echo "<input type=hidden name=httpHeader value=1>";
	echo "<input type=hidden name=item_id value='".$d->item_id."'>";
	echo "<input type=hidden name=o value='".$_GET[o]."'>";
	echo "<input type=hidden name=no_faktur value='".$d->no_faktur."'>";
	echo "<input type=hidden name=po_id value='".$d->po_id."'>";
    echo "<input type=hidden name=item_qty2 value='".$d->qty_terima."'>";
    echo "<input type=hidden name=tot_qty value='".$tot_qty."'>";
    echo "<input type=hidden name=qty_terkecil value='".$d->jumlah2."'>";
	echo "<table border=0 class='design10a'>";
	echo "<tr><td class=design10a>Kode Obat</td><td class=FORM>:</td>";
	echo "    <td class=design10 width=1><input style='text-align:center' type=TEXT name=item_id1 size=15 maxlength=10 value='".$d->item_id."' disabled></td>";
	echo "<tr><td class=design10a>Nama Obat</td><td class=FORM>:</td>";
	echo "    <td class=design10 width=1><input style='text-align:left' type=TEXT name=nama_obat1 size=40 maxlength=50 value='".$_GET["o"]."' disabled></td>";
	echo "<tr><td class=design10a>Satuan Jual</td><td class=FORM>:</td>";
	echo "    <td class=design10 width=1><input style='text-align:left' type=TEXT name=sat_jual size=40 maxlength=50 value='".$d1->sat2."' disabled></td>";
	
	echo "<tr><td class=design10a>Jumlah Permintaan</td><td class=FORM>:</td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=text name=item_qty size=5 maxlength=10 value='".$_GET[q]."' readonly> ".$d->sat1."&nbsp;&nbsp; = &nbsp;".$d3->jumlah1 * $_GET[q]."&nbsp;".$d1->sat2."</td></tr>";
	echo "<tr><td class=design10a>Jumlah Penerimaan</td><td class=FORM>:</td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=text name=qty_terima size=5 maxlength=10 value='".$d->qty_terima."' readonly> ".$d->sat1."&nbsp;&nbsp; = &nbsp;".$d3->jumlah1 * $_GET[q]."&nbsp;".$d1->sat2."</td></tr>";
	
	echo "<tr><td class=design10a>Batch Id</td><td class=FORM>:</td>";
	echo "    <td class=design10 colspan=2><input type=TEXT name=batch size=30 maxlength=30 value='".$batch."' readonly></td></tr>";
	echo "<TR ><TD CLASS=design10a>Tanggal Expire </TD><TD CLASS=FORM>:</TD>\n";
	echo "<TD CLASS=design10 VALIGN='ABSMIDDLE'><INPUT TYPE=TEXT NAME=expire SIZE=10 MAXLENGTH=12 VALUE='".$expire."' readonly>\n";
	echo "</TD>\n";
	echo "</TR>\n\n";
	echo "<tr><td class=design10a>Locator/Rak</td><td class=FORM>:</td>";
	echo "    <td class=design10 width=1><input style='text-align:left' type=TEXT name=locator size=15 maxlength=10 value='".$d1->locator."' readonly></td>";
	echo "<tr><td class=>&nbsp;</td><td class=FORM>&nbsp;</td>";
	//===================================Inputan untuk org keuangan
	
	echo "<tr><td class=design10a>Harga Beli per ".$d->sat1."</td><td class=FORM>:</td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=text name=harga_beli_pesan size=15 maxlength=20 value='' onchange='hitung()'> (Rp.)</td></tr>";
	echo "<tr><td class=design10a>Total Harga</td><td class=FORM>:</td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=text name=tot_harga size=15 maxlength=20 value='' readonly> (Rp.) </td></tr>";
	echo "<tr><td class=design10a>Harga Pokok Penjualan per".$d1->sat2."</td><td class=FORM>:</td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=text name=harga_beli size=15 maxlength=20 value='' readonly> (Rp.)</td></tr>";
	echo "<tr><td class=design10a>Harga Jual per ".$d1->sat2."</td><td class=FORM>:</td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=text name=harga_jual size=15 maxlength=20 value='' > (Rp.)</td></tr>";
	
	echo "<tr><td class=design10a>Discount 1</td><td class=FORM>:</td>";
	echo "	  <td class=design10 colspan=2><input style='text-align:right' type=text name=diskon1 size=15 maxlength=20 value='0' > (Rp.)</td></tr>";
//	echo "<tr><td class=design10a>Discount 2</td><td class=FORM>:</td>";
//	echo "	  <td class=design10 colspan=2><input sLocator/Raktyle='text-align:right' type=text name=diskon2 size=15 maxlength=20 value='0' > (Rp.)</td></tr>";
	
	
	echo "    <td class= colspan=2><input type=SUBMIT value='Simpan'></td></tr>";
	echo "</tr></table>";
	echo "</form>";
	

 }
 
	echo "\n<script language='JavaScript'>\n";
	echo "function hitung() {\n";
	echo "}\n";
	echo "</script>\n";
?>
<script type='text/javascript' src='plugin/jquery-1.4.2.min.js'></script>
<script language="javascript">
$(document).ready(function() {
	$(".ppn").show();
	$("#cek_ppn").click(function(){
			if($("#cek_ppn").is(':checked')){
				$(".ppn").hide();
				$("#harga_jual").val( parseInt($("#harga_jual").val()) - parseInt($("#jumlah_ppn").val()));
		//		$("#cek_ppn2").show();
				//$("#cek_ppn").hide();
			//	$("#cek_detail2").show();
			//	$("#cek_detail1").hide();
		//		$("#cek_ppn2").attr("disabled", false);
			}else{
				$(".ppn").show();
				$("#harga_jual").val(0);
				$("#harga_jual").val( (parseInt($("#harga_beli").val())*parseInt($("#jum_terima").val())) + parseInt($("#jumlah_ppn").val()));
			}
	});
/*	$("#cek_ppn2").click(function(){
	$("#cek_ppn").attr("disabled", true);
	$("#cek_ppn").hide();
			if($("#cek_ppn2").val()==0){
				$("#ppn").show();
				$("#cek_ppn").show();
				$("#cek_ppn2").hide();
				$("#cek_detail2").hide();
				$("#cek_detail1").show();
				$("#cek_ppn").attr("disabled", false);
			}
	}); */
});
	function hitung(){
		
		if(parseInt(document.formx.qty_terima.value) > parseInt(document.formx.item_qty.value)){
			alert("jumlah penerimaan tidak boleh lebih besar dari jumlah pengadaan");
			document.formx.qty_terima.value = 0;
		}
		
		var qtyTerkecil 		= Math.round(document.formx.qty_terkecil.value);
		var jumlahPengadaan 	= Math.round(document.formx.item_qty.value);
		var jumlahPenerimaan 	= Math.round(document.formx.qty_terima.value);
		var hargaSatuanBesar	= Math.round(document.formx.harga_beli_pesan.value);
	//	var diskon1			    = parseFloat(document.formx.diskon1.value.replace('.', ','));
		var diskon1			    = parseFloat(document.formx.diskon1.value);
		//var diskon1				= Math.round(document.formx.diskon1.value);
		var diskon2				= Math.round(document.formx.diskon2.value);
		var materai				= Math.round(document.formx.materai.value);
		
		if($("#cek_ppn").is(':checked')){
		var ppn					= 0;
		}else {
		var ppn					= Math.round(document.formx.ppn.value);
		}
		
		var resep				= Math.round(document.formx.resep.value);
		
		nilaiJumlahAwal			= jumlahPenerimaan*hargaSatuanBesar;
		nilaiDiskon1			= diskon1*(jumlahPenerimaan*hargaSatuanBesar)/100;
		nilaiDiskon2			= diskon2*((jumlahPenerimaan*hargaSatuanBesar)-nilaiDiskon1)/100;
		nilaiPPN				= ppn*((((jumlahPenerimaan)*hargaSatuanBesar)-nilaiDiskon1)-nilaiDiskon2)/100;
		hargaBeliSatuankecil	= hargaSatuanBesar/qtyTerkecil;
		nilaiPPNSatuankecil		= (hargaBeliSatuankecil*ppn)/100;
		hargaJualSatuankecil	= hargaBeliSatuankecil+nilaiPPNSatuankecil+((resep*hargaBeliSatuankecil)/100);
		
		document.formx.jumdis1.value 	= nilaiDiskon1
		document.formx.jumdis2.value 	= nilaiDiskon2;
		document.formx.jumppn.value  	= nilaiPPN;
		document.formx.tot_harga.value  = ((nilaiJumlahAwal-nilaiDiskon1)-nilaiDiskon2)+nilaiPPN+materai;
		document.formx.harga_beli.value = hargaBeliSatuankecil;
		document.formx.ppnbtr.value		= ppn;
		document.formx.jumppnbtr.value	= nilaiPPNSatuankecil;
		document.formx.harga_jual.value	= hargaJualSatuankecil;
		
	}
</script>
