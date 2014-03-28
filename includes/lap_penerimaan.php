<?
$PID = "lap_penerimaan";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/class.BaseTable.php");
require_once("lib/functions.php");


if ($_GET["edit"]=='view'){
    title_print("Rincian Item Barang");
	
	$_GET["po_id"]=getFromTable("select po_id from c_po_item_terima where no_faktur='".$_GET["no_faktur"]."'");
    
	$supplier = getFromTable(
               "select a.nama from rs00028 a, c_po b ".
               "where  a.nama::text='".$_GET["g"]."' ");
    $tanggal_po = getFromTable(
               "select to_char(po_tanggal,'DD Mon YYYY') from c_po ".
               "where po_id='".$_GET["po_id"]."' ");
	$tanggal_po2 = getFromTable(
               "select po_tanggal from c_po ".
               "where po_id='".$_GET["po_id"]."' ");
	$tanggung_jwb = getFromTable(
               "select po_personal from c_po ".
               "where po_id='".$_GET["po_id"]."' ");
	$fak = getFromTable(
               "select no_faktur from c_po ".
               "where po_id='".$_GET["po_id"]."' ");

    $f = new Form("");
	echo "<br>";
echo "<table>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> NO. PO </td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["po_id"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> NO. FAKTUR </td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["no_faktur"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> NAMA SUPPLIER</td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["supp"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> TANGGAL PO </td>";
		echo "<td bgcolor='B0C4DE'><b>: $tanggal_po </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> PENANGGUNG JAWAB</td>";
		echo "<td bgcolor='B0C4DE'><b>: $tanggung_jwb </td>";
	echo "</tr>";
echo "</table>";

    $f->execute();
    
    if (!$GLOBALS['print']){
    	//echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    } else {
    	"";
    }

        $r2 = pg_query($con,
	      "select sum((((harga_beli)-(diskon1/100)*(harga_beli))+ppn_in) * total_jumlah) as jml_tagihan from c_po_item_terima where no_faktur='".$_GET["no_faktur"]."'");
	$d2 = pg_fetch_object($r2);
    pg_free_result($r2);
	
    echo "<br>";
    $t = new PgTable($con, "100%"); 
   	$t->SQL = "select a.obat,b.batch,to_char(b.expire,'dd Mon yyyy')as expire,b.total_jumlah,to_char(b.harga_beli,'999,999,999.99'),diskon1,to_char((diskon1/100)*(b.harga_beli*b.total_jumlah),'999,999,999.99') as diskon2,ppn,to_char((ppn_in* b.total_jumlah),'999,999,999.99'),to_char(((((b.harga_beli)-(diskon1/100)*b.harga_beli)+ppn_in) * b.total_jumlah),'999,999,999.99') 
	from c_po_item_terima b, rs00015 a 
	where b.no_faktur='".$_GET["no_faktur"]."' and a.id::text=b.item_id ";

    $t->ColHeader = array("NAMA OBAT", "BATCH ID","EXPIRE DATE","QTY","HARGA","DISKON (%)","DISKON (Rp.)","PPN","PPN (Rp.)","JUMLAH HARGA");
    $t->ColAlign = array("LEFT","LEFT","CENTER","RIGHT","RIGHT","RIGHT","RIGHT","RIGHT","RIGHT","RIGHT","RIGHT");
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
	$t->ColFooter [7]=  number_format($d2->jml_tagihan,2,',','.');
    $t->execute();
}else{
	title_print("<img src='icon/rawat-jalan-2_asli.gif' align='absmiddle' > LAPORAN PENERIMAAN BARANG");
	title_excel("lap_penerimaan&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."&supplier=".$_GET["supp"]."&faktur_po=".$_GET["no_faktur"]."");

    $ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
	if (!$GLOBALS['print']) {
		include(xxx2);
		$f->selectSQL("supplier","SUPPLIER","SELECT '' AS id, '' AS nama UNION SELECT id, nama FROM rs00028 ORDER BY nama ASC", $_GET['supplier'],null);
		$f->text("faktur_po", " No. Faktur", null, 50, $_GET['faktur_po']);
		$f->submit ("TAMPILKAN");
		$f->execute();
	}else{
		include(xxx2);
		$f->selectSQL("supplier","SUPPLIER","SELECT '' AS id, '' AS nama UNION SELECT id, nama FROM rs00028 ORDER BY nama ASC", $_GET['supplier'],null);
		$f->text("faktur_po", " No. Faktur", null, 50, $_GET['faktur_po']);
		$f->execute();
	}

    echo "<BR>";
	$tgl1=$ts_check_in1;
	$tgl2=$ts_check_in2;
    $t = new PgTable($con, "100%");
     if($_GET['supplier']){
		$cond = " AND supp_id=".$_GET['supplier'];
	}else if($_GET['faktur_po']){
		$cond = " AND (po_id LIKE '%".$_GET['faktur_po']."%' OR no_faktur LIKE '%".$_GET['faktur_po']."%')";
	}else if($_GET['faktur_po'] && $_GET['supplier']){
	$cond = " AND supp_id=".$_GET['supplier']." AND (po_id LIKE '%".$_GET['faktur_po']."%' OR no_faktur LIKE '%".$_GET['faktur_po']."%')";
	}else{}
	
    $t->SQL ="	select no_faktur,to_char(po_tanggal,'DD Mon YYYY'),nama, po_personal,
				to_char((select sum(((harga_beli-(diskon1/100)*harga_beli)+ppn_in) * total_jumlah) as jml_tagihan from c_po_item_terima WHERE po_id = rsv_penerimaan.po_id),'999,999,999.99') AS total , 
				no_faktur, id from rsv_penerimaan where 
				po_tanggal between '$tgl1' and '$tgl2'".$cond;
	$t->setlocale("id_ID");
    $t->ColHeader = array("NO. FAKTUR","TANGGAL", "NAMA SUPPLIER" , "PENANGGUNG JAWAB","TOTAL","VIEW <BR> DETAIL","PRINT <BR> KWITANSI");
    $t->ShowRowNumber = true;
    $t->ColAlign = array("left","left","center","left","right","center","center");
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[6] = "<A CLASS=TBL_HREF HREF='index2.php?p=print_po&no_faktur=<#5#>&jenis=<#6#>&e=lap'>".icon("print","Print Kwitansi")."</A>";
    $t->ColFormatHtml[5] = "<A CLASS=TBL_HREF HREF='index2.php?p=$PID&edit=view&no_faktur=<#5#>&supp=<#2#>'>".icon("view","Print Kwitansi")."</A>";
    $t->execute();
}

?>
