<? // Nugraha, 17/02/2004
   // Pur, 27/02/2004
   // Pur, 27/03/2004 : new libs table
   // sfdn, 21-04-2004
   // sfdn, 23-04-2004
   // sfdn, 14-05-2004

$PID = "526";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (isset($_GET[obat])) {
title("<img src='icon/daftar-2.gif' align='absmiddle' >  RINCIAN RETUR OBAT");
echo "<br>";

$q = pg_query(
    "select a.obat, c.tdesc as satuan, d.harga, sum(b.gudang) as gudang, sum(b.rj) as rj, sum(b.ri) as ri, a.id as dummy ".
    "from rs00015 a ".
    "     left join rs00016 d on a.id = d.id ".
    "     left join rs00016b b on a.id = b.id ".
    "     left join rs00001 c on a.satuan_id = c.tc and c.tt='SAT'  ".
    "where ".
    "a.kategori_id ='".$_GET["mOBT"]."' and a.id = ".$_GET[obat]." ".
    "group by a.obat, c.tdesc, d.harga, a.id");

$qr = pg_fetch_object($q);

    $f = new ReadOnlyForm();
    //$f->title("Data Obat");
    $f->text("Obat ID",$_GET[obat]);
    $f->text("Nama Obat",$qr->obat);
    $f->text("Satuan",$qr->satuan);
    $f->text("Harga",number_format($qr->harga,2,',','.'));
    $f->text("Retur Gudang",$qr->gudang);
    $f->text("Retur Apotek R/J",$qr->rj);
    $f->text("Retur Apotek R/I",$qr->ri);
    $f->execute();

    echo "<br>";


$q = pg_query("select sum(gudang) as gudang, sum(rj) as rj, sum(ri) as ri from rs00016b where id=".$_GET[obat]);
$qr = pg_fetch_object($q);
$t = new PgTable($con, "100%");

$t->SQL = "select to_char(tanggal, 'DD MON YYYY'),gudang,rj,ri from rs00016b where id=".$_GET[obat];

$t->setlocale("id_ID");
$t->ShowRowNumber = true;
$t->RowsPerPage = 50;
$t->ColAlign[0] = "CENTER";
$t->ColAlign[1] = "RIGHT";
$t->ColAlign[2] = "RIGHT";
$t->ColAlign[3] = "RIGHT";
$t->ColFormatNumber[1] = 0;
$t->ColFormatNumber[2] = 0;
$t->ColFormatNumber[3] = 0;
$t->ColHeader = array("TANGGAL", "RETUR GUDANG", "RETUR APOTEK R/J", "RETUR APOTEK R/I");
$t->ColFooter[1] = number_format($qr->gudang,0,',','.');
$t->ColFooter[2] = number_format($qr->rj,0,',','.');
$t->ColFooter[3] = number_format($qr->ri,0,',','.');

$t->execute();



} else {
title("<img src='icon/daftar-2.gif' align='absmiddle' >  LAPORAN RETUR OBAT");
title_excel("526");
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
    $f->selectSQL("mOBT", "Kategori",
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
echo "<TD><font class=SUB_MENU>NAMA OBAT:</font> <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
echo "<TD><INPUT TYPE=SUBMIT VALUE=' CARI '></TD>";
echo "</TR></FORM></TABLE></DIV>";

$t = new PgTable($con, "100%");

$t->SQL =
    "select a.obat, c.tdesc as satuan, d.harga, sum(b.gudang) as gudang, sum(b.rj) as rj, sum(b.ri) as ri, a.id as dummy ".
    "from rs00015 a ".
    "     left join rs00016 d on a.id = d.id ".
    "     left join rs00016b b on a.id = b.id ".
    "     left join rs00001 c on a.satuan_id = c.tc and c.tt='SAT'  ".
    "where ".
    "a.kategori_id ='".$_GET["mOBT"]."' and ".
    "upper(a.obat) LIKE '%".strtoupper($_GET["search"])."%' ".
    "group by a.obat, c.tdesc, d.harga, a.id";

$t->setlocale("id_ID");
$t->ShowRowNumber = true;
$t->RowsPerPage = 50;
$t->ColAlign[3] = "RIGHT";
$t->ColAlign[4] = "RIGHT";
$t->ColAlign[5] = "RIGHT";
$t->ColAlign[6] = "CENTER";
$t->ColFormatNumber[2] = 2;
$t->ColFormatNumber[3] = 0;
$t->ColFormatNumber[4] = 0;
$t->ColFormatNumber[5] = 0;
//$t->ColFormatNumber[6] = 0;
//$t->ColFormatNumber[7] = 0;


//$t->ColFormatMoney[2] = "%!+#2n";

//$t->ColHeader = array("NAMA OBAT", "SATUAN", "HARGA", "AWAL","TERIMA","KELUAR","AKHIR");
$t->ColHeader = array("NAMA OBAT", "SATUAN", "HARGA", "RETUR<br>GUDANG","RETUR<br>APOTEK R/J","RETUR<br>APOTEK R/I","RINCIAN");
$t->ColFormatHtml[6] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&mOBT=".$_GET[mOBT]."&obat=<#6#>'>".icon("view","View")."</A>";

$t->execute();
    }
?>
