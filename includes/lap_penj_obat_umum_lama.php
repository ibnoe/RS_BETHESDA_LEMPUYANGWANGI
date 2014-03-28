<?php
/*--------------
 * 2013-03-07
 * wildan sawaludin code
--------------*/

$PID = "lap_penj_obat_umum_lama";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/form.php");

//--start
if (!$GLOBALS['print']){
    title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > <b>LAPORAN PENJUALAN APOTIK UMUM</b>");
    title_excel("lap_penj_obat_umum&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."".
        "&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."");
} else {
    title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Penjualan Apotik Umum");
    title_excel("lap_penj_obat_umum&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."".
        "&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."");
}

//if (!$GLOBALS['print']) {
//    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
//}

//--------------------------- start for print
$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
$ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));

//--------------------------- start for print

if(!$GLOBALS['print']){
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
        $f->selectDate("tanggal2", "s/d Tanggal", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");

    } else {

        $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
        $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
        $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
        $f->selectDate("tanggal2", "s/d Tanggal", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
        
    }
    
    $f->submit ("TAMPILKAN");
    $f->execute();
	
	//--lap sebelum tgl 6 april 2013
	echo '<div align="right">';
		echo '<a href="'.$SC.'?p=lap_penj_obat_umum">';
			echo 'Kembali Ke Laporan Setelah Tanggal 06 April 2013';
		echo '</a>';
	echo '</div>';
	//--
	
} else {
    $f = new Form("");
    $f->titleme("Dari Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $ts_check_in1");
    $f->titleme("s/d Tanggal  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $ts_check_in2");
}

echo "<br>";
if (!isset($_GET[sort])) {
       $_GET[sort] = "no_reg";
       $_GET[order] = "asc";
}

//-- add param start
$addParam = '';

//-- add param end

$t = new PgTable($con, "100%");
$r2 = pg_query($con,
          //"select sum(a.jumlah) as jum ".
          "select sum((a.banyaknya * a.harga)) as jum ".
          "from apotik_umum a ".
          "     left join rs00015 b ON a.obat_id = b.id ".
          "     left join rs00016 d ON a.obat_id = d.obat_id ".
          "where (a.tanggal_entry between '$ts_check_in1' and '$ts_check_in2') ".
          "     ".$addParam."");

$d2 = pg_fetch_object($r2);
pg_free_result($r2);

$t->SQL = "select tanggal(a.tanggal_entry,0)||' '||to_char(a.waktu_entry,'hh:mi:ss') as tgl, ".
		  "		a.no_reg, a.nama, b.obat, a.banyaknya, d.harga::integer, ".
          "     a.harga, a.jumlah ".
          "from apotik_umum a  ".
          "     left join rs00015 b ON a.obat_id = b.id ".
          "     left join rs00016 d ON a.obat_id = d.obat_id ".
          "where ".
          "     (a.tanggal_entry between '$ts_check_in1' and '$ts_check_in2') ".
          "     ".$addParam." ".
          "group by tgl, a.no_reg, a.nama, b.obat, a.banyaknya, d.harga, a.harga, a.jumlah";

$t->setlocale("id_ID");
$t->ShowRowNumber = true;
$t->ColAlign[0] = "CENTER";
$t->ColAlign[1] = "CENTER";
$t->ColAlign[2] = "LEFT";
$t->ColAlign[3] = "LEFT";
$t->ColAlign[4] = "CENTER";
$t->ColAlign[5] = "RIGHT";
$t->ColAlign[6] = "RIGHT";
$t->ColAlign[7] = "RIGHT";
//$t->RowsPerPage = 30;
//$t->ColFormatMoney[3] = "%!+#2n";
//$t->ColFormatMoney[4] = "%!+#2n";
//$t->ColFormatMoney[6] = "%!+#2n";
$t->ColHeader = array("TANGGAL TRANSAKSI","NO.REG","NAMA","NAMA OBAT","QTY","HARGA POKOK","HARGA JUAL","TOTAL (Rp.)");

//$t->ShowSQLExecTime = true;
//$t->ShowSQL = true;
if(!$GLOBALS['print']){
    $t->RowsPerPage = 300;
    $t->ColFooter[6] =  "Total";
    $t->ColFooter[7] =  number_format($d2->jum,2);
}else{
    $t->RowsPerPage = 300;
    $t->ColFooter[6] =  "Total";
    $t->ColFooter[7] =  number_format($d2->jum,2);
    //$t->ColFormatHtml[7] = icon("edit","Edit");
    $t->DisableNavButton = true;
    $t->DisableScrollBar = true;
    //$t->DisableStatusBar = true;
}
$t->execute();
//--end

?>
