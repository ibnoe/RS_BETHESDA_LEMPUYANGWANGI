<?php
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

   $f->execute;

	echo "<form action=actions/360_2_.update.php method=POST name=formx onSubmit='return checkinput()' >";
	echo "<input type=hidden name=p value=$PID>";
	echo "<input type=hidden name=httpHeader value=1>";
	echo "<input type=hidden name=po_id value='".$_GET["poid"]."'>";
	echo "<table border=0 class=design10a>";
	echo "<tr><td class=design10a>No. Faktur</td><td class=FORM>:</td>";
	echo "    <td colspan=2 class=design10><input type=TEXT name=no_faktur id=no_faktur size=30 maxlength=30 value='".$d2->no_faktur."'></td></tr>";
	echo "<TR ><TD class=design10a>Tanggal Jatuh Tempo </TD><TD CLASS=FORM>:</TD>\n";
	echo "<TD class=design10 VALIGN='ABSMIDDLE'><INPUT TYPE=TEXT NAME=jatuh_tempo id=jatuh_tempo SIZE=10 MAXLENGTH=12 VALUE='".$d2->jatuh_tempo."'>\n";
	echo "<A HREF=\"#\" onClick=\"cal.select(document.forms['formx'].jatuh_tempo,'tanggalan','yyyy-MM-dd'); return false;\" NAME=\"tanggalan\" ID=\"tanggalan\" ><INPUT TYPE='IMAGE' SRC='icon/calendar.gif' TITLE='Pilih' ></A></TD>\n";
	echo "</TR>\n\n";
	echo "<tr><td class=FORM>&nbsp;</td><td class=FORM>&nbsp;</td>";
	echo "    <td class=FORM colspan=2><input type=SUBMIT value='Submit'></td></tr>";
	echo "</tr></table>";
	echo "</form>";
	
	$cek_po=getFromTable("select no_faktur from c_po where po_id='".$_GET["poid"]."'");
?>
