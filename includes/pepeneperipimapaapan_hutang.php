<?php
$PID = "pepeneperipimapaapan_hutang";
$SC = $_SERVER["SCRIPT_NAME"];

//require_once("startup.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/visit_setting.php");
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


if (!$GLOBALS['print']){
    title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > <b>PEMBAYARAN HUTANG (OBAT)</b>");
    title_excel("pepeneperipimapaapan_hutang&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."".
        "&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."".
        "&supplier=".$_GET["supplier"]);
} else {
    title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Penjualan Farmasi (Obat)");
    title_excel("pepeneperipimapaapan_hutang&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."".
        "&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."".
        "&supplier=".$_GET["supplier"]);
}


    pg_free_result($r);
if ($_GET["byr"]!='a'){
    $ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
	if (!$GLOBALS['print']) {
		include(xxx2);
		$f->selectSQL("supplier","SUPPLIER","SELECT '' AS id, '' AS nama UNION SELECT id, nama FROM rs00028 ORDER BY nama ASC", $_GET['supplier'],null);
		$f->submit ("TAMPILKAN");
		$f->execute();
	}
	else{
		include(xxx2);
		$supplier = getFromTable("select a.nama from rs00028 a, c_po b ".
               "where  a.id=b.supp_id::text and b.supp_id::text='".$_GET['supplier']."' ");
		echo "<DIV ALIGN=LEFT>";
        echo "<TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
        echo "<TR>";
        echo "<TD >TANGGAL </td><td> : </td><td>".$ts_check_in1." s/d ".$ts_check_in2."</TD>";
        
		echo "</DIV>";
	}

    echo "<BR>";
	$tgl1=$ts_check_in1;
	$tgl2=$ts_check_in2;
	if (!$GLOBALS['print']) {
    //$f->execute();
	echo "<br /><br />";
		echo "<DIV ALIGN=RIGHT>";
        echo "<TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID >";
        echo "<TD >Pencarian PO ID / Supplier : <INPUT TYPE=TEXT NAME=search VALUE='" . $_GET["search"] . "'></TD>";
        echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

        echo "</TR></FORM></TABLE>";
		echo "</DIV>";
	echo "<br/>";
	}
	if($_GET['search']){
		$sql_add = "WHERE (upper(a.no_faktur) like '%".strtoupper($_GET[search])."%' or upper(c.nama) like '%".strtoupper($_GET[search])."%' )";
	}else if($_GET['tanggal1D']){
    $sql_add = " WHERE a.jatuh_tempo between '$tgl1' and '$tgl2'";
	}else if($_GET['supplier']){
		$sql_add = " WHERE b.supp_id=".$_GET['supplier'];
	}else if ($_GET['tanggal1D'] && $_GET['supplier'])
	{
	$sql_add = " WHERE a.jatuh_tempo between '$tgl1' and '$tgl2' AND supp_id=".$_GET['supplier'];
	}else{
	$sql_add = " ";
	}

 ?>
<table id="list-pasien" width="100%" >
    <thead>
        <tr>
            <td align="CENTER" class="TBL_HEAD" width="20" rowspan="0" <?=$font ?>>NO FAKTUR</td>
            <td align="CENTER" class="TBL_HEAD" width="140" rowspan="0" <?=$font ?>>NAMA PBF <br>(NAMA SUPPLIER)</td>
            <td align="CENTER" class="TBL_HEAD" width="90" rowspan="0" <?=$font ?>>TANGGAL TERIMA</td>
            <td align="CENTER" class="TBL_HEAD" width="90" rowspan="0" <?=$font ?>>JATUH TEMPO</td>
            <td align="CENTER" class="TBL_HEAD" width="190" rowspan="0" <?=$font ?>>NOMINAL</td>
            <td align="CENTER" class="TBL_HEAD" width="90" rowspan="0" <?=$font ?>>MATERAI</td>
            <td align="CENTER" class="TBL_HEAD" width="90" rowspan="0" <?=$font ?>>STATUS</td>
            <td align="CENTER" class="TBL_HEAD" width="190" colspan="0" <?=$font ?>>NO SPMU</td>
            <td align="CENTER" class="TBL_HEAD" width="90" colspan="0" <?=$font ?>>TANGGAL BAYAR</td>
			<?php if (!$GLOBALS['print']) { ?>
            <td align="CENTER" class="TBL_HEAD" width="10" colspan="0" <?=$font ?>>PEMBAYARAN</td>
			<?php } ?>
        </tr>
    </thead>
    <tbody>
	<?PHP
   $f->execute;
	$SQL = "select c.nama, a.no_faktur, a.jatuh_tempo, a.tanggal_terima, to_char(sum(((a.harga_beli-(a.diskon1/100)*a.harga_beli)+a.ppn_in) * a.total_jumlah),'999,999,999.99') as total,sum(a.materai) as materai,
CASE WHEN a.status_bayar='1' THEN 'LUNAS' WHEN a.status_bayar='2' THEN 'CICILAN' ELSE 'BELUM LUNAS' END AS status,ket_bayar,tanggal_bayar
from c_po_item_terima a
JOIN c_po b ON a.po_id=b.po_id
JOIN rs00028 c ON b.supp_id::text=c.id $sql_add
GROUP BY a.no_faktur,a.jatuh_tempo,a.status_bayar,c.nama,ket_bayar,tanggal_bayar,a.tanggal_terima
order by a.jatuh_tempo asc";
	$rowsData = pg_query($con,$SQL); 
//from c_po_item_terima WHERE po_id='".$_GET["poid"]."' GROUP BY no_faktur,jatuh_tempo,status_bayar"); 
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
		<td style="text-align: left;" <?=$font ?>><?php if (!empty($row['no_faktur'])) {echo $row['no_faktur'];} else {echo "&nbsp;";}?></td>
		<td style="text-align: left;" <?=$font ?>><?php if (!empty($row['nama'])) {echo $row['nama'];} else {echo "&nbsp;";}?></td>
		<td style="text-align: center;" <?=$font ?>><?php if (!empty($row['jatuh_tempo'])) {echo $row['jatuh_tempo'];} else {echo "&nbsp;";}?></td>
		<td style="text-align: center;" <?=$font ?>><?php if (!empty($row['tanggal_terima'])) {echo $row['tanggal_terima'];} else {echo "&nbsp;";}?></td>
		<td style="text-align: right;" <?=$font ?>><?php if (!empty($row['total'])) {echo $row['total'];} else {echo "&nbsp;";}?></td>
		<td style="text-align: right;" <?=$font ?>><?php if (!empty($row['materai'])) {echo $row['materai'];} else {echo "&nbsp;";}?></td>
		<td style="text-align: center;" <?=$font ?>><?php echo $row['status'];?>&nbsp;</td>
		<td style="text-align: center;" <?=$font ?>><?php if (!empty($row['ket_bayar'])) {echo $row['ket_bayar'];} else {echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";}?></td>
		<td style="text-align: center;" <?=$font ?>><?php if (!empty($row['tanggal_bayar'])) {echo $row['jatuh_tempo'];} else {echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";}?></td>
		<?php if (!$GLOBALS['print']) { ?>
		<td style="text-align: center;" <?=$font ?>>
		<?php
		if ($row['status']=='LUNAS'){}else{
		?>
		<a href="<?php echo $SC.'?p='.$PID.'&edit=edit1&no_faktur='.$row['no_faktur'].'&byr=a' ?>"> [ BAYAR ]</a>
		<?
		echo "&nbsp;";}
		?>
		</td>
		<?php } ?>
	</tr>
	<?php
		 }
	}
	?>
    </tbody> 
</table>    
<br/><br/>
	<?
	}
	else {
	$po_id = getFromTable("select po_id from c_po_item_terima where no_faktur='".$_GET["no_faktur"]."' ");
	$r = pg_query($con, "select * from c_po where po_id = '".$po_id."'");
    $n = pg_num_rows($r);
    if($n > 0) $d2 = pg_fetch_object($r);
	//if ($_GET["byr"]=='a'){
	$supplier = getFromTable("select a.nama from rs00028 a, c_po b ".
               "where  a.id=b.supp_id::text and b.supp_id::text='".$d2->supp_id."' ");
    $tanggal_po = getFromTable(
               "select to_char(po_tanggal,'DD Mon YYYY') from c_po ".
               "where po_id='".$po_id."' ");
    $tanggung_jwb = getFromTable(
               "select po_personal from c_po ".
               "where po_id='".$po_id."' ");

    $f = new Form("");
	echo "<br>";
echo "<table class=design10a>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE' class=design10a><b> NO. PO </td>";
		echo "<td bgcolor='B0C4DE' class=design10><b>: ".$po_id." </td>";
	echo "</tr>";
	echo "<br>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE' class=design10a><b> NO. FAKTUR </td>";
		echo "<td bgcolor='B0C4DE' class=design10><b>: ".$_GET["no_faktur"]." </td>";
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
 echo "<br>";    
	echo "<form action=$SC?p=$PID&edit=edit1&poid=".$po_id."&httpHeader=byr method=POST name=formx onSubmit='return checkinput()' >";
	echo "<input type=hidden name=p value=$PID>";
	echo "<input type=hidden name=httpHeader value=byr>";
	echo "<input type=hidden name=no_faktur value='".$_GET["no_faktur"]."'>";
	echo "<input type=hidden name=po_id value='".$po_id."'>";
	echo "<table border=0 class=design10a>";
	echo "<TR ><TD class=design10a>Tanggal Bayar </TD><TD CLASS=FORM>:</TD>\n";
	echo "<TD class=design10 VALIGN='ABSMIDDLE'><INPUT TYPE=TEXT NAME=tanggal_bayar id=tanggal_bayar SIZE=10 MAXLENGTH=12 VALUE='".$d2->jatuh_tempo."'>\n";
	echo "<A HREF=\"#\" onClick=\"cal.select(document.forms['formx'].tanggal_bayar,'tanggalan','yyyy-MM-dd'); return false;\" NAME=\"tanggalan\" ID=\"tanggalan\" ><INPUT TYPE='IMAGE' SRC='icon/calendar.gif' TITLE='Pilih' ></A></TD>\n";
	echo "</TR>\n\n";
	echo "<tr><td class=design10a>Keterangan</td><td class=FORM>:</td>";
	echo "    <td colspan=2 class=design10><input type=TEXT name=ket_bayar id=ket_bayar size=30 maxlength=30 value=''></td></tr>";
	echo "    <td class=FORM colspan=2><input type=SUBMIT value='BAYAR'></td></tr>";
	echo "<tr><td class=FORM>&nbsp;</td><td class=FORM>&nbsp;</td>";
	echo "</tr></table>";
	echo "</form>";
	
	$cek_po=getFromTable("select no_faktur from c_po where po_id='".$po_id."'");
	}//else{}
	?>
