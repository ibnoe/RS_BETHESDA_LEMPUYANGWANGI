<?
$PID = "lap_retur";
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
    $supplier = getFromTable(
               "select a.nama from rs00028 a ".
               "where  a.nama::text='".$_GET["g"]."' ");
    $tanggal_po = getFromTable(
               "select to_char(tgl_retur,'DD Mon YYYY') from rs00016b ".
               "where retur_id='".$_GET["po_id"]."' ");
	$tanggal_po2 = getFromTable(
               "select tgl_retur from rs00016b ".
               "where retur_id='".$_GET["po_id"]."' ");
	$tanggung_jwb = getFromTable(
               "select retur_personal from rs00016b ".
               "where retur_id='".$_GET["po_id"]."' ");
	$fak = getFromTable(
               "select no_faktur from rs00016b ".
               "where retur_id='".$_GET["po_id"]."' ");

    $f = new Form("");
	echo "<br>";
echo "<table>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> NO. PO </td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["po_id"]." </td>";
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
	      "select sum(harga_beli * qty_terima) as jml_tagihan from c_po_item where po_id='".$_GET["po_id"]."'");
	$d2 = pg_fetch_object($r2);
    pg_free_result($r2);
	
    echo "<br>";
    $t = new PgTable($con, "100%");   
   	$t->SQL = "select a.obat,a.batch,b.item_qty,ket
	from rs00016c b, rs00015 a 
	where b.retur_id='".$_GET["po_id"]."' and a.id::text=b.item_id ";

    $t->ColHeader = array("NAMA OBAT", "BATCH ID","QTY","KETERANGAN");
    $t->ColAlign = array("LEFT","LEFT","CENTER","RIGHT","LEFT","CENTER");
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
	//$t->ColFooter [5]=  number_format($d2->jml_tagihan,2,',','.');
    $t->execute();
}else{
	title_print("<img src='icon/rawat-jalan-2_asli.gif' align='absmiddle' > LAPORAN RETUR BARANG");
	title_excel("lap_retur&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."");

    $ext = "OnChange = 'Form1.submit();'";
	

    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
	if (!$GLOBALS['print']) {
		include(xxx2);
		$f->submit ("TAMPILKAN");
		$f->execute();
	}else{
		include(xxx2);
		$f->execute();
	}

    echo "<BR>";
	$tgl1=$ts_check_in1;
	$tgl2=$ts_check_in2;
    $t = new PgTable($con, "100%");
    $t->SQL =
            "select a.retur_id,to_char(a.tgl_retur,'DD Mon YYYY'),b.nama,a.retur_personal,a.retur_id from rs00016b a, rs00028 b 
            where a.tgl_retur between '$tgl1' and '$tgl2' and a.suplier_id = b.id ";

    $t->setlocale("id_ID");
    $t->ColHeader = array("NO. RETUR","TANGGAL", "NAMA SUPPLIER" , "PENANGGUNG JAWAB","VIEW <BR> DETAIL");
    $t->ShowRowNumber = true;
    $t->ColAlign = array("left","center","left","left","center","center");
    $t->RowsPerPage = $ROWS_PER_PAGE;
    //$t->ColFormatHtml[5] = "<A CLASS=TBL_HREF HREF='index2.php?p=print_po&po_id=<#5#>&e=lap'>".icon("print","Print Kwitansi")."</A>";
    $t->ColFormatHtml[4] = "<A CLASS=TBL_HREF HREF='index2.php?p=$PID&edit=view&po_id=<#4#>&supp=<#2#>'>".icon("view","Print Kwitansi")."</A>";
    $t->execute();
}

?>
