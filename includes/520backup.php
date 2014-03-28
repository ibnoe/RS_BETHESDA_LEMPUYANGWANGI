<? // Nugraha, 17/02/2004
   // Pur, 27/02/2004
   // Pur, 27/03/2004 : new libs table
   // sfdn, 21-04-2004
   // sfdn, 23-04-2004
   // sfdn, 14-05-2004

$PID = "520";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
//require_once("lib/dbconn.php");
require_once("lib/form.php");
//require_once("lib/class.PgTable.php");
//require_once("lib/functions.php");


title("<img src='icon/daftar-2.gif' align='absmiddle' >  LAPORAN STOK BARANG");
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
    $f->selectSQL("mOBT", "Kategori Inventory",
        "select '' as tc, '' as tdesc union " .
        "select tc, tdesc ".
        "from rs00001 ".
        "where tt = 'GOB' and tc != '000' ".
        "order by tc", $_GET["mOBT"],
        $ext);
    $f->execute();
    $f->hidden ("mOBT", $_GET["mOBT"]);
echo "<BR>";
echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
echo "<INPUT TYPE=HIDDEN NAME=mOBT VALUE='".$_GET["mOBT"]."'>";
echo "<TD><font class=SUB_MENU>NAMA BARANG:</font> <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
echo "<TD><INPUT TYPE=SUBMIT VALUE=' CARI '></TD>";
echo "</TR></FORM></TABLE></DIV>";

$t = new PgTable($con, "100%");
/*
$t->SQL =
    "select obat, satuan,harga, awal,terima,keluar,akhir from rsv004x ".
    "where ".
    "kategori = '".$_GET["mOBT"]."' ".
    "and (upper(obat) LIKE '%".strtoupper($_GET["search"])."%' ".
    "OR upper(satuan) LIKE '%".strtoupper($_GET["search"])."%')";
*/

$t->SQL =
    "select a.obat,c.tdesc as satuan,b.harga,b.qty_awal,x.qty_rj,x.qty_ri,b.qty_keluar, ".
    "(b.qty_awal+x.qty_ri+x.qty_rj) as sisa ".
    "from rs00015 a, rs00016 b, rs00001 c, rs00016a x ".
    "where a.id = b.obat_id and ".
    "a.id = x.obat_id and ".
    "a.kategori_id ='".$_GET["mOBT"]."' and ".
    "a.satuan_id = c.tc and c.tt='SAT' and ".
    "upper(obat) LIKE '%".strtoupper($_GET["search"])."%' ";

$t->setlocale("id_ID");
$t->ShowRowNumber = true;
$t->RowsPerPage = 50;
$t->ColAlign[3] = "RIGHT";
$t->ColAlign[1] = "CENTER";
$t->ColAlign[4] = "RIGHT";
$t->ColAlign[5] = "RIGHT";
$t->ColAlign[6] = "RIGHT";
$t->ColFormatNumber[2] = 2;
$t->ColFormatNumber[3] = 0;
$t->ColFormatNumber[4] = 0;
$t->ColFormatNumber[5] = 0;
$t->ColFormatNumber[6] = 0;
$t->ColFormatNumber[7] = 0;


//$t->ColFormatMoney[2] = "%!+#2n";

//$t->ColHeader = array("NAMA OBAT", "SATUAN", "HARGA", "AWAL","TERIMA","KELUAR","AKHIR");
$t->ColHeader = array("NAMA OBAT", "SATUAN", "HARGA", "GUDANG","APOTEK R/J","APOTEK R/I","KELUAR","SISA");
$t->execute();

?>
