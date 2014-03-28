<? // Agung SUnandar 0:34 07/07/2012 Membetulkan stock obak
$PID = "520";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
//require_once("lib/dbconn.php");
require_once("lib/form.php");
//require_once("lib/class.PgTable.php");
//require_once("lib/functions.php");

if (!$GLOBALS['print']){
title_print("<img src='icon/daftar-2.gif' align='absmiddle' >  LAPORAN STOK BARANG");
//title_excel("520");
title_excel("520&mOBT=".$_GET["mOBT"]."");
}else{
title("<img src='icon/daftar-2.gif' align='absmiddle' >  LAPORAN STOK BARANG");
title_excel("520&mOBT=".$_GET["mOBT"]."");
}
echo "<br>";
    if (isset($_GET["e"])) {
        $ext = "DISABLED";
    } else {
        $ext = "OnChange = 'Form1.submit();'";
    }
    echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
	if (!$GLOBALS['print']){
    $f->selectSQL("mOBT", "Kategori Inventory",
        "select '' as tc, '' as tdesc union " .
        "select tc, tdesc ".
        "from rs00001 ".
        "where tt = 'GOB' and tc != '000' ".
        "order by tdesc", $_GET["mOBT"],
        $ext);
	}else{
	
	echo "KATEGORI OBAT :  ";
	$kat=getFromTable("select tdesc from rs00001 where tt = 'GOB' and tc='".$_GET["mOBT"]."'");
	//echo $_GET["mOBT"];
	echo $kat;
	}
    $f->execute();
    $f->hidden ("mOBT", $_GET["mOBT"]);
	
//	if ($_GET["mOBT"]!=""){

	if($_GET['mOBT'] && ($_GET['search']=='') && ($_GET['mOBT'] != '000')){
	    $kategori = " a.kategori_id = '" . $_GET["mOBT"] . "' and ";
	}
	else if(($_GET['search']!='') && ($_GET['mOBT'] != '000') && ($_GET['mOBT'] != '')){
		$_GET['mOBT'] = '';
	}

	
 $syntax =
    "select a.obat,c.tdesc as satuan,
CASE WHEN z.harga::text IS NULL THEN b.harga_beli
ELSE z.harga END as harga
,x.qty_ri,CASE WHEN max(z.harga)::text IS NULL THEN b.harga_beli*x.qty_ri
ELSE max(z.harga)*x.qty_ri END as harga
from rs00015 a, rs00016 b, rs00001 c, rs00016a x,buku_besar z ".
    "where a.id = b.obat_id and ".
    "a.id = x.obat_id and ".
	"a.id::text = z.item_id::text and z.trans_form = 'c_po_item_terima' and trans_type='OBT' and ".
	"a.status = '1' and ".
    //"a.kategori_id ='".$_GET["mOBT"]."' and ".
    "$kategori ".
	"a.satuan_id = c.tc and c.tt='SAT' and ".
    "upper(obat) LIKE '%".strtoupper($_GET["search"])."%'
	group by a.obat,c.tdesc,z.harga,x.qty_ri,b.harga_beli ";
	
/*
 else{

echo "select tdesc from rs00001 where tt = 'GOB' and tc='".$_GET["mOBT"]."'";

echo $syntax2 =
    "select a.obat,c.tdesc as satuan,b.harga,x.qty_ri ".
    "from rs00015 a, rs00016 b, rs00001 c, rs00016a x ".
    "where a.id = b.obat_id and ".
    "a.id = x.obat_id and ".
    "a.status = '1' and ".
    "a.satuan_id = c.tc and c.tt='SAT' and ".
    "upper(obat) LIKE '%".strtoupper($_GET["search"])."%'";
 /*
 }
*/

echo "<BR>";
echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
echo "<INPUT TYPE=HIDDEN NAME=mOBT VALUE='".$_GET["mOBT"]."'>";
if (!$GLOBALS['print']){
echo "<TD><font class=SUB_MENU>NAMA BARANG:</font> <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
echo "<TD><INPUT TYPE=SUBMIT VALUE=' CARI '></TD>";
}
echo "</TR></FORM></TABLE></DIV>";

//if (!isset($_GET[sort])) {
//       $_GET[sort] = "a.obat";
//       $_GET[order] = "asc";
//}

$t = new PgTable($con, "100%");

//ini_set('display_errors',0);
$r2 = pg_query($con,
    "select sum(z.harga) as harga,sum(x.qty_ri),sum(z.harga*x.qty_ri) as total ".
    "from rs00015 a, rs00016 b, rs00001 c, rs00016a x,buku_besar z ".
    "where a.id = b.obat_id and ".
    "a.id = x.obat_id and ".
    "a.id::text = z.item_id::text and z.trans_form = 'c_po_item_terima' and trans_type='OBT' and ".
	"a.status = '1' and ".
    //"a.kategori_id ='".$_GET["mOBT"]."' and ".
    "$kategori ".
	"a.satuan_id = c.tc and c.tt='SAT' and ".
    "upper(obat) LIKE '%".strtoupper($_GET["search"])."%'"
	//"order by a.obat ASC"
	);

$d2 = pg_fetch_object($r2);
pg_free_result($r2);

/*
 $t->SQL =
    "select a.obat,c.tdesc as satuan,b.harga,x.qty_ri ".
    "from rs00015 a, rs00016 b, rs00001 c, rs00016a x ".
    "where a.id = b.obat_id and ".
    "a.id = x.obat_id and ".
    "a.kategori_id ='".$_GET["mOBT"]."' and ".
    "a.satuan_id = c.tc and c.tt='SAT' and ".
    "upper(obat) LIKE '%".strtoupper($_GET["search"])."%'
 ";
*/
$t->SQL =$syntax;

$t->setlocale("id_ID");
$t->ShowRowNumber = true;
//$t->RowsPerPage = 1000;
if(!$GLOBALS['print']){
	$t->RowsPerPage = 25; 
}else{
	$t->RowsPerPage = pg_num_rows(pg_query($syntax));	
}
$t->ColAlign[3] = "RIGHT";
$t->ColAlign[1] = "LEFT";
$t->ColAlign[4] = "RIGHT";
$t->ColAlign[5] = "RIGHT";
$t->ColFormatNumber[2] = 2;
$t->ColFormatNumber[3] = 0;
$t->ColFormatNumber[4] = 0;
$t->ColFormatNumber[5] = 0;

$t->ColHeader = array("NAMA OBAT", "SATUAN", "HARGA","APOTEK FARMASI","TOTAL HARGA");

$t->ColFooter[1] =  "Total";
$t->ColFooter[2] =  number_format($d2->harga,2);
$t->ColFooter[3] =  number_format($d2->qty_ri,2);
$t->ColFooter[4] =  number_format($d2->total,2);
	
$t->execute();

?>
