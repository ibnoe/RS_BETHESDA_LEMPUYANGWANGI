<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 09-05-2004


$PID = "akun_inv_kos";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if($_GET["v"] == "view") {

    $flg = "PENGADAAN BARANG";
    if ($_GET["u"] == "OB5") {
        $flg = "PENERIMAAN BARANG";
    }
    
    title("<img src='icon/daftar-2.gif' align='absmiddle' >  DATA $flg FARMALKES");
    $supplier = getFromTable(
               "select nama from rs00028 ".
               "where id = '".$_GET["e"]."'");
    $tgl_str = getFromTable(
               "select tanggal(tanggal_trans,3) as tanggal from rs00008 ".
               "where referensi = '".$_GET["t"]."' and trans_type ='".$_GET["u"]."'");
    $bukti = $_GET["t"];
    echo "<br>";
    $f = new Form("");
    $f->subtitle("Jenis Transaksi    : $flg");
    $f->subtitle("Supplier    : $supplier");
    $f->subtitle("No. Bukti: $bukti");
    $f->subtitle("Tanggal: $tgl_str");
    $f->execute();

    echo "<br>";
    $t = new PgTable($con, "100%");
    $r2 = pg_query($con, "select sum(qty*harga) as jum ".
              "from rs00008  ".
	          "where referensi = '".$_GET["t"]."' ".
              "and trans_type ='".$_GET["u"]. "'");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);
    $t->SQL = "select b.obat,d.tdesc as satuan,a.qty,a.harga,(a.qty*a.harga) as jum ".
              "from rs00008 a, rs00015 b, rs00001 d ".
              "where to_number(a.item_id, '999999999999') = b.id and ".
              "a.referensi='".$_GET["t"]."' and ".
              "a.trans_type ='".$_GET["u"]."'and ".
              "b.satuan_id = d.tc and d.tt='SAT'";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[1] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    //$t->ColFormatMoney[2] = "%!+#2n";
    //$t->ColFormatMoney[3] = "%!+#2n";
    //$t->ColFormatMoney[4] = "%!+#2n";

    $t->ColFormatNumber[2] = 0;
    $t->ColFormatNumber[3] = 2;
    $t->ColFormatNumber[4] = 2;

    $t->ColHeader = array("NAMA OBAT","SATUAN","QTY","HARGA","TOTAL");
    $t->ColFooter[4] =  number_format($d2->jum,2,',','.');
    $t->execute();

} else {
    // detail
    
    title("<img src='icon/daftar-2.gif' align='absmiddle' >  Inventory Cost");
    echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    $f->selectSQL("mOBT", "Aktivitas",
        "select '' as tc, '' as tdesc union ".
        "select 'OB4' as tc, 'PENGADAAN [FARMALKES]' as tdesc union ".
        "select 'OB5' as tc, 'PENERIMAAN [FARMALKES]' as tdesc union ".
        "select 'OB6' as tc, 'PENGADAAN [INST.GIZI]' as tdesc union ".
        "select 'OB7' as tc, 'PENERIMAAN [INST.GIZI]' as tdesc", $_GET["mOBT"],
        $ext);

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




    $f->submit ("OK");
    $f->execute();
    echo "<BR>";
    $r2 = pg_query($con, "select sum(a.qty*a.harga) as jum ".
            "from rs00008 a, rs00015 b ".
            "where a.trans_type='".$_GET["mOBT"]."' and ".
            "to_number(a.item_id, '999999999999') = b.id and ".
            "(a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2')");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    $t = new PgTable($con, "100%");
    $t->SQL = "select b.nama,a.referensi,to_char(a.tanggal_trans,'dd MON YYYY') as tanggal_trans_str, ".
              "sum(a.qty*a.harga) as jum, no_reg as dummy ".
              "from rs00008 a, rs00028 b ".
              "where a.trans_type='".$_GET["mOBT"]."' and ".
              "a.no_reg = b.id and ".
              "(a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') ".
              "group by a.trans_type,b.nama, a.tanggal_trans, a.referensi, a.no_reg";
              //tanggal asli tanggal(a.tanggal_trans,3)

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColAlign[3] = "RIGHT";
    $t->ColAlign[1] = "RIGHT";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[4] = "CENTER";
    //$t->ColFormatMoney[3] = "%!+#2n";
    $t->ColFormatNumber[3] = 2;
    $t->ColHeader = array("NAMA SUPPLIER","NO.BUKTI","TANGGAL", "NILAI PESANAN","V i e w");
    $t->ColFormatHtml[4] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&v=view&e=<#4#>&t=<#1#>&u=".$_GET["mOBT"]."'>".
                        icon("view","View")."</A>";

    $t->ColFooter[3] =  number_format($d2->jum,2,',','.');
    $t->execute();

}

?>
