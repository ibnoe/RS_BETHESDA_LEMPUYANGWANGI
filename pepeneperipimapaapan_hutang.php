<?php
$SC = $_SERVER["SCRIPT_NAME"];

if ($_GET["httpHeader"]=="byr"){
//echo "<pre>";
//var_dump($_SERVER);
//die;
pg_query($con,"UPDATE c_po_item_terima SET status_bayar=1,tanggal_bayar='".$_POST["tanggal_bayar"]."',
ket_bayar='".$_POST["ket_bayar"]."' WHERE po_id='".$_POST["po_id"]."' and no_faktur = '".$_POST["no_faktur"]."'");


header("Location: $SC?p=$PID&edit=edit1&poid=".$_POST["po_id"]);
}
ELSE{}


$r = pg_query($con, "select * from c_po where po_id = '".$_GET["poid"]."'");
    $n = pg_num_rows($r);
    if($n > 0) $d2 = pg_fetch_object($r);

    pg_free_result($r);
    title_print("Rincian Item");
    $supplier = getFromTable(
               "select a.nama from rs00028 a, c_po b ".
               "where  a.id=b.supp_id::text and b.supp_id::text='".$d2->supp_id."' ");
    $tanggal_po = getFromTable(
               "select to_char(po_tanggal,'DD Mon YYYY') from c_po ".
               "where po_id='".$_GET["poid"]."' ");
    $tanggung_jwb = getFromTable(
               "select po_personal from c_po ".
               "where po_id='".$_GET["poid"]."' ");

    $f = new Form("");
	echo "<br>";
echo "<table class=design10a>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE' class=design10a><b> NO. PO </td>";
		echo "<td bgcolor='B0C4DE' class=design10><b>: ".$_GET["poid"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE' class=design10a><b> NAMA SUPPLIER</td>";
		echo "<td bgcolor='B0C4DE' class=design10><b>: $supplier </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE' class=design10a><b> TANGGAL PO </td>";
		echo "<td bgcolor='B0C4DE' class=design10><b>: $tanggal_po </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE' class=design10a><b> PENANGGUNG JAWAB</td>";
		echo "<td bgcolor='B0C4DE' class=design10><b>: $tanggung_jwb </td>";
	echo "</tr>";
echo "</table>";

    //$f->execute();
 echo "<br>";    
 ?>
<table id="list-pasien" width="500" >
    <thead>
        <tr>
            <td align="CENTER" class="TBL_HEAD" width="40" rowspan="0" <?=$font ?>>NO FAKTUR</td>
            <td align="CENTER" class="TBL_HEAD" width="90" rowspan="0" <?=$font ?>>JATUH TEMPO</td>
            <td align="CENTER" class="TBL_HEAD" width="90" rowspan="0" <?=$font ?>>STATUS</td>
            <td align="CENTER" class="TBL_HEAD" width="20" colspan="0" <?=$font ?>>PEMBAYARAN</td>
        </tr>
    </thead>
    <tbody>
	<?PHP
   $f->execute;
	$rowsData = pg_query($con,"select no_faktur,jatuh_tempo,CASE WHEN status_bayar='1' THEN 'LUNAS' WHEN status_bayar='2' THEN 'CICILAN' ELSE 'HUTANG' END AS status
from c_po_item_terima WHERE po_id='".$_GET["poid"]."' GROUP BY no_faktur,jatuh_tempo,status_bayar"); 
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
		<td style="text-align: center;" <?=$font ?>><?php if (!empty($row['no_faktur'])) {echo $row['no_faktur'];} else {echo "&nbsp;";}?></td>
		<td style="text-align: center;" <?=$font ?>><?php if (!empty($row['jatuh_tempo'])) {echo $row['jatuh_tempo'];} else {echo "&nbsp;";}?></td>
		<td style="text-align: center;" <?=$font ?>><?php echo $row['status'];?>&nbsp;</td>
		<td style="text-align: center;" <?=$font ?>>
		<?php
		if ($row['status']=='LUNAS'){}else{
		?>
		<a href="<?php echo $SC.'?p='.$PID.'&edit=edit1&poid='.$_GET["poid"].'&no_faktur='.$row['no_faktur'].'&byr=a' ?>"> [ Accept ]</a>
		<?
		echo "&nbsp;";}
		?>
		</td>
	</tr>
	<?php
		 }
	}
	?>
    </tbody> 
</table>    
<br/><br/>
	<?
	if ($_GET["byr"]=='a'){
	echo "<form action=$SC?p=$PID&edit=edit1&poid=".$_GET[poid]."&httpHeader=byr method=POST name=formx onSubmit='return checkinput()' >";
	echo "<input type=hidden name=p value=$PID>";
	echo "<input type=hidden name=httpHeader value=byr>";
	echo "<input type=hidden name=no_faktur value='".$_GET["no_faktur"]."'>";
	echo "<input type=hidden name=po_id value='".$_GET["poid"]."'>";
	echo "<table border=0 class=design10a>";
	echo "<TR ><TD class=design10a>Tanggal Bayar </TD><TD CLASS=FORM>:</TD>\n";
	echo "<TD class=design10 VALIGN='ABSMIDDLE'><INPUT TYPE=TEXT NAME=tanggal_bayar id=tanggal_bayar SIZE=10 MAXLENGTH=12 VALUE='".$d2->jatuh_tempo."'>\n";
	echo "<A HREF=\"#\" onClick=\"cal.select(document.forms['formx'].tanggal_bayar,'tanggalan','yyyy-MM-dd'); return false;\" NAME=\"tanggalan\" ID=\"tanggalan\" ><INPUT TYPE='IMAGE' SRC='icon/calendar.gif' TITLE='Pilih' ></A></TD>\n";
	echo "</TR>\n\n";
	echo "<tr><td class=design10a>Keterangan</td><td class=FORM>:</td>";
	echo "    <td colspan=2 class=design10><input type=TEXT name=ket_bayar id=ket_bayar size=30 maxlength=30 value='".$d2->no_faktur."'></td></tr>";
	echo "    <td class=FORM colspan=2><input type=SUBMIT value='BAYAR'></td></tr>";
	echo "<tr><td class=FORM>&nbsp;</td><td class=FORM>&nbsp;</td>";
	echo "</tr></table>";
	echo "</form>";
	
	$cek_po=getFromTable("select no_faktur from c_po where po_id='".$_GET["poid"]."'");
	}else{}
	?>
