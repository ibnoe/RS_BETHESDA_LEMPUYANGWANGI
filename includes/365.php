<? // Nugraha, 17/02/2004
   // Pur, 27/02/2004
   // Pur, 27/03/2004 : new libs table
   // sfdn, 21-04-2004
   // sfdn, 23-04-2004
   // sfdn, 09-05-2004
   // sfdn, 14-05-2004

$PID = "365";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if($_GET["tc"] == "view") {
    title("Rincian Pengeluaran Barang");
    $r = pg_query($con, "select b.obat,a.harga,c.tdesc as satuan,d.tdesc as kategori ".
                    "from rs00008 a, rs00015 b, rs00001 c, rs00001 d ".
                    "where a.item_id = '".$_GET["v"]."' ".
                    "and to_number(a.item_id, '999999999999') = b.id ".
                    "and b.satuan_id = c.tc and c.tt='SAT' ".
                    "and b.kategori_id = d.tc and d.tt='GOB'");

    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);

    $f = new Form("");
    $f->subtitle("Nama Barang: $d->obat");
    $f->subtitle("Satuan: $d->satuan");
    $f->subtitle("Harga: $d->harga");
    $f->subtitle("Kategori : $d->kategori");
    $f->execute();

    echo "<br>";
    $t = new PgTable($con, "100%");
    $r2 = pg_query($con, "select sum(qty) as jum,harga,sum(qty*harga) as nil ".
              "from rs00008 ".
	          "where item_id='".$_GET["v"]."' ".
              "and trans_type='OB1' ".
              "group by harga");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    $t->SQL = "select e.nama, no_reg, ".
              "a.tanggal_trans as tgl_trans_str, ".
              "qty as jum, a.harga, (qty*harga) as nilai ".
              "from rs00008 a, rs00015 b ,rs00001 c, rs00006 d, rs00002 e ".
              "where (a.tanggal_trans between '".$_GET[ts_check_in1]."' and '".$_GET[ts_check_in2]."') and ".
	      "a.trans_type='OB1' and ".
              "to_number(a.item_id::text, '999999999999'::text) = b.id ".
              "and b.satuan_id = c.tc and c.tt='SAT' ".
              "and a.no_reg = d.id and d.mr_no = e.mr_no ".
              "and item_id ='".$_GET["v"]."'";

    $t->setlocale("id_ID");

    $t->ShowRowNumber = true;
    $t->ColAlign[2] = "CENTER";
    $t->RowsPerPage = 20;
    $t->ColFormatMoney[4] = "%!+#2n";
    $t->ColFormatMoney[5] = "%!+#2n";
    $t->ColHeader = array("NAMA PASIEN","NO.REG","TANGGAL","QTY","HARGA","Rp.");
    $t->ColFooter[3] =  number_format($d2->jum,2);
    $t->ColFooter[4] =  number_format($d2->harga,2);
    $t->ColFooter[5] =  number_format($d2->nil,2);
    $t->execute();

} else {

    title("Laporan Pengeluaran Barang Inventory");
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
//        $ext);
        "");

    if (!isset($_GET['tanggal1D'])) {
	$tanggal1D = date("d", time());
	$tanggal1M = date("m", time());
	$tanggal1Y = date("Y", time());
	$tanggal2D = date("d", time());
	$tanggal2M = date("m", time());
	$tanggal2Y = date("Y", time());

	$ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
        $ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
	$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
    	$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");
		
    } else {
	    
        $tgl_sakjane = $_GET[tanggal2D] + 1;	
    
	$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
        $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
        $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
        $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");

    }

    $f->submit(" OK ");
    $f->execute();






    // search box
    echo "<BR>";
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<INPUT TYPE=HIDDEN NAME=mOBT VALUE='".$_GET["mOBT"]."'>";
    echo "<TD><font class=SUB_MENU>NAMA BARANG:</font> <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' CARI '></TD>";
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = "select b.obat, c.tdesc, sum(qty) as jum, a.harga, ".
            "sum(qty*harga) as nilai, a.item_id as dummy ".
            "from rs00008 a, rs00015 b, rs00001 c ".
            "where (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
	    "a.trans_type='OB1' and ".
            "to_number(a.item_id, '999999999999') = b.id and ".
            "b.kategori_id = '".$_GET["mOBT"]."' ".
            "and b.satuan_id = c.tc and c.tt='SAT' ".
            "and upper(obat) LIKE '%".strtoupper($_GET["search"])."%' ".
            "group by a.item_id,b.obat,c.tdesc,b.kategori_id,a.harga";


    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = 20;
    $t->ColAlign[1] = "CENTER";
    $t->ColAlign[5] = "CENTER";
    $t->ColFormatMoney[2] = "%!+#2n";
    $t->ColFormatMoney[3] = "%!+#2n";
    $t->ColFormatMoney[4] = "%!+#2n";
    $t->ColHeader = array("NAMA BARANG", "SATUAN", "QTY","HARGA", "Rp.","V i e w");
    $t->ColFormatHtml[5] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view&v=<#5#>&ts_check_in1=$ts_check_in1&ts_check_in2=$ts_check_in2'>".icon("view","View")."</A>";
    $t->execute();
}

?>
