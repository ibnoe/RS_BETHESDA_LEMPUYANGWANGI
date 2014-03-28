<?
	// 24-12-2006

session_start();
$PID = "413";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE     = 14;
$RS_NAME           = "RUMAH SAKIT XXXXXXXXXXXXX XXXXXXXXXXXXXXXXX";


require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");

?>

<HTML>

<HEAD>
<TITLE>::: Sistem Informasi <?php echo $RS_NAME; ?> :::</TITLE>
<LINK rel='styleSheet' type='text/css' href='../cetak.css'>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>


</HEAD>

<BODY TOPMARGIN=5 LEFTMARGIN=5 MARGINWIDTH=0 MARGINHEIGHT=0>

<?
title($RS_NAME);
subtitle("Jl. xxxxxxxxxxxxxxx, Tlp. 00000000000, Fax. 000000000000");
echo "<hr>";
echo "<br>";

/*
if($_GET["tc"] == "view") {
    title("Rincian Pendapatan Rawat Inap");

    if ($_GET["e"] == "Y") {
        $unit = "Rawat Jalan";
    } elseif  ($_GET["e"] == "N"){
        $unit = "IGD";
    } elseif ($_GET["e"] == "I"){
        $unit = "Rawat Inap";
    } else {
        $unit = "Semua";
    }

    $pasien = getFromTable(
               "select tdesc from rs00001 ".
               "where tc = '".$_GET["u"]."' and tt='JEP'");

    $r = pg_query($con, "select tanggal(to_date(".$_GET["f"].",'YYYYMMDD'),3) as tgl");
    $d = pg_fetch_object($r);
    pg_free_result($r);

    $bulan = $d->tgl;
    $f = new Form("");
    $f->subtitle("Tanggal    : $bulan");
    $f->subtitle("U n i t    : $unit");
    $f->subtitle("Tipe Pasien : $pasien");
    $f->execute();

    echo "<br>";
    $t = new PgTable($con, "100%");
    $r2 = pg_query($con,
              "select sum(a.qty * a.harga) as jum ".
              "from rs00008 a ".
              "     left join rs00006 b ON a.no_reg = b.id ".
              "where b.rawat_inap='".$_GET["e"]."' and ".
              "     to_char(a.tanggal_trans,'YYYYMMDD') ='".$_GET["f"]. "' and ".
              "     a.trans_type='OB1' and b.tipe = '".$_GET["u"]."'");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    $t->SQL = "select c.mr_no,c.nama,a.no_reg, ".
              "     e.obat, a.qty, a.harga, sum(a.qty * a.harga) as tagih ".
              "from rs00008 a  ".
              "     left join rs00006 b ON a.no_reg = b.id ".
              "     left join rs00002 c ON b.mr_no = c.mr_no ".
              "     left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') ".
              "     left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
              "where ".
              " to_char(a.tanggal_trans,'YYYYMMDD') ='".$_GET["f"]. "' and ".
              "     b.rawat_inap ='".$_GET["e"]."' and ".
              "     a.trans_type = 'OB1' ".
              "group by c.mr_no, c.nama, a.no_reg, e.obat, a.qty, a.harga";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[2] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatMoney[4] = "%!+#2n";
    $t->ColFormatMoney[5] = "%!+#2n";
    $t->ColFormatMoney[6] = "%!+#2n";
    $t->ColHeader = array("MR.NO","NAMA","NO.REG","NAMA O B A T","QTY","HARGA","Rp.");
    $t->ColFooter[6] =  number_format($d2->jum,2);
    //$t->ShowSQLExecTime = true;
    //$t->ShowSQL = true;

    $t->execute();

} else {
  
  
*/

    title("LAPORAN PENDAPATAN RAWAT JALAN");
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

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

	$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
        $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
    	$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
	$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");

}


/*
    $f->selectSQL("mUNIT", "U N I T",
        "select '' as tc, '' as tdesc union ".
        "select distinct(b.rawat_inap) as tc, case when b.rawat_inap='Y' then 'RAWAT JALAN' when b.rawat_inap='I' then 'RAWAT INAP' else 'IGD' end as tdesc ".
        "from rs00008 a, rs00006 b ".
        "where a.trans_type = 'OB1' and a.no_reg = b.id ", $_GET["mUNIT"],
        $ext);


    $f->selectSQL("mPASIEN", "Tipe Pasien",
        "select '' as tc, '' as tdesc union ".
        "select tc, tdesc ".
        "from rs00001 ".
        "where tt='JEP' and tc != '000' ", $_GET["mPASIEN"],
        $ext);
*/

    // $f->submit ("OK");
    $f->execute();
    echo "<br>";

/*    
    if (!empty($_GET[mUNIT])) {
       $SQL_a = " and b.rawat_inap = '".$_GET["mUNIT"]."' ";
    } else {
       $SQL_a = " and b.rawat_inap = '".$_GET["mUNIT"]."' ";
    }

*/

    if (!empty($_GET[mPASIEN])) {
       $SQL_b = " and b.tipe = '".$_GET["mPASIEN"]."' ";
       $SQL_b2 = " and y.tipe = '".$_GET["mPASIEN"]."' ";
       
    } else {
       $SQL_b = " ";
    }

    if (strlen($_GET["search"]) > 0) {
        $r2 = pg_query($con, "select sum(jum) as jum,rawatan ".
              "from rsv0010 ".
              "where upper(rawatan) LIKE '%".strtoupper($_GET["search"])."%' ".
              "group by rawatan");

    } else {
        $r2 = pg_query($con,
	      "select sum(sarana) as sarana, sum(bahan) as bahan, sum(periksa) as periksa ".    	
	      "   , sum(obat_paket) as obat_paket, sum(obat_lanjut) as obat_lanjut, sum(jmlbayar) as jmlbayar ".
	      "from rsvrj ". 

		"   left join rs00001 a on a.tdesc = rsvrj.pasien and a.tt = 'JEP' ".
		"where (tgl_masuk between '$ts_check_in1' and '$ts_check_in2') ".
		"   and a.tc like '%".$_GET[mPASIEN]."%'".

	      " ");
	     
    
    }

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);


/*********************** komplitnya ini *********************

        $SQL = "select y.id, y.mr_no, to_char(y.tanggal_reg, 'DD MON YYYY') as tgl_masuk ".
	      "   , c.nama, c.umur, c.alm_tetap, b.layanan as poli ".

	      "   , (case when (select sum(harga) from rsvsarana  where no_reg = y.id) > 0 then (select sum(harga) from rsvsarana  where no_reg = y.id) else 0 end) as sarana ". 	 
	      "   , (case when (select sum(harga) from rsvbahan  where no_reg = y.id) > 0 then (select sum(harga) from rsvbahan  where no_reg = y.id) else 0 end) as bahan ". 	 
	      "   , (case when (select sum(harga) from rsvperiksa  where no_reg = y.id) > 0 then (select sum(harga) from rsvperiksa  where no_reg = y.id) else 0 end) as periksa ". 
	      "   , (select harga from rs00008 where no_reg = y.id and item_id = rs00034.id and rs00034.layanan = 'OBAT PAKET') as obat_paket  ".	 
	      "   , j.jumlah as obat_lanjut ".
	     
	      "   , ((case when (select sum(harga) from rsvsarana  where no_reg = y.id) > 0 then (select sum(harga) from rsvsarana  where no_reg = y.id) else 0 end)  ". 	 
	      "     	+ (case when (select sum(harga) from rsvbahan  where no_reg = y.id) > 0 then (select sum(harga) from rsvbahan  where no_reg = y.id) else 0 end)  ". 	 
	      "   	+ (case when (select sum(harga) from rsvperiksa  where no_reg = y.id) > 0 then (select sum(harga) from rsvperiksa  where no_reg = y.id) else 0 end) ". 
	      "   	+ (case when (select harga from rs00008 where no_reg = y.id and item_id = rs00034.id and rs00034.layanan = 'OBAT PAKET') > 0 then ".
	      "			(select harga from rs00008 where no_reg = y.id and item_id = rs00034.id and rs00034.layanan = 'OBAT PAKET') else 0 end )  ".	 
	      "   	+ (case when j.jumlah>0 then j.jumlah else 0 end) ) as jmlbayar ".
	     
	      "   , a.tdesc as pasien ". 
              "from rs00006 y ".
	      "     left join rs00001 a ON a.tc = y.tipe and a.tt = 'JEP' ".
	      "     left join rs00034 b ON b.id = y.poli ".
	      "     left join rs00002 c on y.mr_no = c.mr_no ".
	      "     left join rs00005 j on y.id = j.reg and  j.is_obat = 'Y' ".
              "where ".
              "     (y.tanggal_reg between '$ts_check_in1' and '$ts_check_in2') ".
	      "     and y.rawat_inap = 'Y' ".
	      "     $SQL_b2 ".
	      " ";


*************************************************************/





    if ($_GET[geser] == "kiri" || empty($_GET[geser])) {
    
    echo "<div align=right><a href='index2.php?p=$PID".
	 "&tanggal1D=".$_GET[tanggal1D]."&tanggal1M=".$_GET[tanggal1M]."&tanggal1Y=".$_GET[tanggal1Y].
	 "&tanggal2D=".$_GET[tanggal2D]."&tanggal2M=".$_GET[tanggal2M]."&tanggal2Y=".$_GET[tanggal2Y].
	 "&mPASIEN=".$_GET[mPASIEN]."&tblstart=".$_GET[tblstart]."&sort=".$_GET[sort]."&geser=kanan".
	 "'><b> GESER KANAN &gt;&gt;</b></a></div>";


	$SQL = "select no_reg, mr_no, to_char(tgl_masuk, 'DD MON YYYY') as tgl_in  ".
		"   , nama, umur, alm_tetap, poli, pasien  ".
		"from rsvrj ".
		"   left join rs00001 a on a.tdesc = rsvrj.pasien and a.tt = 'JEP' ".
		"where (tgl_masuk between '$ts_check_in1' and '$ts_check_in2') ".
		"   and a.tc like '%".$_GET[mPASIEN]."%'";


	 
    } elseif ($_GET[geser] == "kanan") {
    
    echo "<div align=right><a href='index2.php?p=$PID".
	 "&tanggal1D=".$_GET[tanggal1D]."&tanggal1M=".$_GET[tanggal1M]."&tanggal1Y=".$_GET[tanggal1Y].
	 "&tanggal2D=".$_GET[tanggal2D]."&tanggal2M=".$_GET[tanggal2M]."&tanggal2Y=".$_GET[tanggal2Y].
	 "&mPASIEN=".$_GET[mPASIEN]."&tblstart=".$_GET[tblstart]."&sort=".$_GET[sort]."&geser=kiri".
	 "'><b>&lt;&lt; GESER KIRI</b></a></div>";


	$SQL = "select no_reg, mr_no, to_char(tgl_masuk, 'DD MON YYYY') as tgl_in  ".
		"   , nama, sarana, bahan, periksa, obat_paket, obat_lanjut, jmlbayar  ".
		"from rsvrj ".
		"   left join rs00001 a on a.tdesc = rsvrj.pasien and a.tt = 'JEP' ".
		"where (tgl_masuk between '$ts_check_in1' and '$ts_check_in2') ".
		"   and a.tc like '%".$_GET[mPASIEN]."%'";
    
    }	 


    if (empty($_GET[sort])) {
	$_GET[sort] = "tgl_in";
	$_GET[order] = "asc";
    }


    $t = new PgTable($con, "100%");
    $t->SQL = "$SQL";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[2] = "CENTER";
    
    $t->RowsPerPage = 20;
    //$t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view&e=".$_GET["mUNIT"]."&f=<#3#>&u=".$_GET["mPASIEN"]."'>".
    //                    icon("view","View")."</A>";
    
    
    
    if ($_GET[geser] == "kiri" || empty($_GET[geser])) {
    $t->ColHeader = array("NO REG","MR/NO","TGL DATANG","NAMA","UMUR","ALAMAT","POLI","TIPE");
    $t->ColAlign[7] = "CENTER";
    } elseif ($_GET[geser] == "kanan") {
    $t->ColHeader = array("NO REG","MR/NO","TGL MASUK","NAMA",
			"SARANA","BAHAN","BP","OBAT PAKET","OBAT LANJUT","JML BAYAR");
    $t->ColFormatNumber[4] = 2;
    $t->ColFormatNumber[5] = 2;
    $t->ColFormatNumber[6] = 2;
    $t->ColFormatNumber[7] = 2;
    $t->ColFormatNumber[8] = 2;
    $t->ColFormatNumber[9] = 2;
    
    $t->ColFooter[4] =  number_format($d2->sarana,2,',','.');
    $t->ColFooter[5] =  number_format($d2->bahan,2,',','.');
    $t->ColFooter[6] =  number_format($d2->periksa,2,',','.');
    $t->ColFooter[7] =  number_format($d2->obat_paket,2,',','.');
    $t->ColFooter[8] =  number_format($d2->obat_lanjut,2,',','.');
    $t->ColFooter[9] =  number_format($d2->jmlbayar,2,',','.');

    }
    
    
    $t->execute();


echo "\n<script language='JavaScript'>\n";
echo "function cetakaja(tag) {\n";
echo "    sWin = window.open('index2.php?tag=' + tag, 'xWin',".
     " 'width=500,height=400,menubar=no,scrollbars=yes');\n";
echo "    sWin.focus();\n";
echo "}\n";
echo "</script>\n";

?>

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
printWindow();
//  End -->
</script>

</body>
</html>