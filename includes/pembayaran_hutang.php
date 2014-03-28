<?php 

$PID = "pembayaran_hutang";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/visit_setting.php");


 if($_GET["edit"] == "edit1") {

	$r = pg_query($con, "select * from piutang_po where po_id = '".$_GET["poid"]."'");
    $n = pg_num_rows($r);
    if($n > 0) $d2 = pg_fetch_object($r);

    pg_free_result($r);
    title("<img src='icon/rawat-inap-2.gif' align='absmiddle' > Pembayaran Hutang");
    $supplier = getFromTable(
               "select a.nama from rs00028 a, c_po b ".
               "where  a.nama::text='".$_GET["g"]."' ");
    $tanggal_po = getFromTable(
               "select to_char(po_tanggal,'DD Mon YYYY') from c_po ".
               "where po_id='".$_GET["poid"]."' ");
	$tanggung_jwb = getFromTable(
               "select po_personal from c_po ".
               "where po_id='".$_GET["poid"]."' ");
	$jumlah_hutang=getFromTable("select (jumlah_hutang-jumlah_bayar) from piutang_po where po_id='".$_GET["poid"]."'");
    $f = new Form("");
	echo "<br>";
echo "<table>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> NO. PO </td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["poid"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> NAMA SUPPLIER</td>";
		echo "<td bgcolor='B0C4DE'><b>: $supplier </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> TANGGAL PO </td>";
		echo "<td bgcolor='B0C4DE'><b>: $tanggal_po </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> PENANGGUNG JAWAB</td>";
		echo "<td bgcolor='B0C4DE'><b>: $tanggung_jwb </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> JUMLAH HUTANG</td>";
		echo "<td bgcolor='B0C4DE'><b>: $jumlah_hutang </td>";
	echo "</tr>";
echo "</table>";

 echo "<br>";    

   

	echo "<form action=actions/pembayaran_hutang.insert.php method=POST onSubmit='return validasi()' name=formx>";
	echo "<input type=hidden name=p value=$PID>";
	echo "<input type=hidden name=po_id value='".$_GET["poid"]."'>";
	echo "<table border=0>";
	echo "<TR ><TD CLASS=FORM>Jumlah  Bayar</TD><TD CLASS=FORM>:</TD>\n";
	echo "<TD CLASS=FORM VALIGN='ABSMIDDLE'><INPUT TYPE=TEXT NAME=jumlah_bayar SIZE=10 MAXLENGTH=12 VALUE='0'>\n";
	echo "<tr><td class=FORM>&nbsp;</td><td class=FORM>&nbsp;</td>";
	echo "    <td class=FORM colspan=2><input type=SUBMIT value='Submit'></td></tr>";
	echo "</tr></table>";
	echo "</form>";
	
$jumlah_hutang=getFromTable("select (jumlah_hutang-jumlah_bayar) from piutang_po where po_id='".$_GET["poid"]."'");
//echo $jumlah_hutang;
	?>
<SCRIPT language=JavaScript>

function validasi(){
var num = <?=$jumlah_hutang?>;
if (document.formx.jumlah_bayar.value > num){
alert("Warning!\n Nominal yang anda masukan terlalu besar!")
document.formx.jumlah_bayar.focus();
return false;
} else if (document.formx.jumlah_bayar.value == "0") {
alert("Warning!\n Anda Harus memasukan nilai")
document.formx.jumlah_bayar.focus();
return false;
}
}
</SCRIPT>
<?
}
else {
   if (!$GLOBALS['print']){
		title_print("<img src='icon/rawat-inap-2.gif' align='absmiddle' > Pembayaran Hutang");
    } else {
    	title("<img src='icon/rawat-inap-2.gif' align='absmiddle' > Pembayaran Hutang");
    }
	
	$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);


	$SQL = "select a.po_id,to_char(a.tanggal_po,'dd MON YYYY') as tanggal,b.nama,c.jatuh_tempo ,a.jumlah_hutang,a.jumlah_bayar,a.sisa_hutang,case when c.status_bayar=0 then 'Belum Bayar' else 'Belum Lunas' end as status_bayar
	from piutang_po a,rs00028 b,c_po c 
	where c.supp_id::text=b.id and a.po_id=c.po_id and c.status_bayar=0 or c.supp_id::text=b.id and a.po_id=c.po_id and c.status_bayar=1
	group by a.po_id,a.tanggal_po,b.nama,a.jumlah_hutang,a.jumlah_bayar,a.sisa_hutang,c.status_bayar,c.jatuh_tempo  
	order by c.jatuh_tempo ";
	
			@$r1 = pg_query($con,$SQL);
			@$n1 = pg_num_rows($r1);
	
	
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;} 
?>
<TABLE ALIGN="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
			<tr class="TBL_HEAD">     	
				<td class="TBL_HEAD" width="4%" align="center">NO</td>
				<td class="TBL_HEAD"align="center">PO ID</td>
				<td class="TBL_HEAD"align="center">TANGGAL PO</td>
				<td class="TBL_HEAD"align="center">NAMA SUPPLIER</td>	
				<td class="TBL_HEAD"align="center">JATUH TEMPO</td>
				<td class="TBL_HEAD"align="center">JUMLAH HUTANG</td>
				<td class="TBL_HEAD"align="center">JUMLAH BAYAR</td>
				<td class="TBL_HEAD"align="center">SISA HUTANG</td>
				<td class="TBL_HEAD"align="center">STATUS</td>
				<td width="5%" align="center" class="TBL_HEAD">EDIT BAYAR</td>
			</tr>
			
	
		<?	
			$jml_tagihan= 0;
			$jml_dokter= 0;
			$jml_rs= 0;
			$row1=0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while (@$row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
					$no=$i 	
					?>		
				 	<tr valign="top" class="<?=$class_nya?>" >  
			        	<td class="TBL_BODY" align="center"><?=$no ?> </td>
			        	<td class="TBL_BODY" align="center"><?=$row1["po_id"] ?> </td>
						<td align="center" class="TBL_BODY"><?=$row1["tanggal"] ?></td>
						<td align="left" class="TBL_BODY"><?=$row1["nama"] ?></td>
						<td align="center" class="TBL_BODY"><?=$row1["jatuh_tempo"] ?></td>
						<td align="right" class="TBL_BODY" valign="middle"><?=number_format($row1["jumlah_hutang"],2,",",".") ?></td>
						<td align="right" class="TBL_BODY" valign="middle"><?=number_format($row1["jumlah_bayar"],2,",",".") ?></td>
						<td align="right" class="TBL_BODY" valign="middle"><?=number_format($row1["sisa_hutang"],2,",",".") ?></td>
						<td align="center" class="TBL_BODY"><?=$row1["status_bayar"] ?></td>
						<td align="center" class="TBL_BODY" valign="middle"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&edit=edit1&poid=".$row1['po_id']."&g=".$row1['nama']."'>".
                        icon("edit","Edit")."</A>";?></td>
					</tr>	

					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>

</table>
<?}
?>
