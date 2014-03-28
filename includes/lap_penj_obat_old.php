<?php
/*--------------
 * 2013-03-07
 * wildan sawaludin code
--------------*/

$PID = "lap_penj_obat";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/form.php");

//--start
if (!$GLOBALS['print']){
    title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > <b>LAPORAN PENJUALAN FARMASI (OBAT)</b>");
    title_excel("lap_penj_obat&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."".
        "&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."".
        "&mUNIT=".$_GET["mUNIT"]."&mPASIEN=".$_GET["mPASIEN"]."&mKATEGORY=".$_GET["mKATEGORY"]."&mDOKTER=".$_GET["mDOKTER"]."");
} else {
    title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Penjualan Farmasi (Obat)");
    title_excel("lap_penj_obat&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."".
        "&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."".
        "&mUNIT=".$_GET["mUNIT"]."&mPASIEN=".$_GET["mPASIEN"]."&mKATEGORY=".$_GET["mKATEGORY"]."&mDOKTER=".$_GET["mDOKTER"]."");
}


//if (!$GLOBALS['print']) {
//    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
//}

//--------------------------- start for print
$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
$ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));

if ($_GET["mUNIT"] == "Y") {
    $unit = "Rawat Jalan";
} elseif  ($_GET["mUNIT"] == "N"){
    $unit = "IGD";
} elseif ($_GET["mUNIT"] == "I"){
    $unit = "Rawat Inap";
} else {
    $unit = "Semua";
}

if ($_GET["mPASIEN"] != '') {
    $pasien = getFromTable(
               "select tdesc from rs00001 ".
               "where tc = '".$_GET["mPASIEN"]."' and tt='JEP'");
} else {
    $pasien = "Semua";
}

if ($_GET["mKATEGORY"] != '') {
    $kategory = getFromTable(
               "select tdesc from rs00001 ".
               "where tc = '".$_GET["mKATEGORY"]."' and tt='GOB'");
} else {
    $kategory = "Semua";
}
if ($_GET["mDOKTER"] != '') {
    $dokter = getFromTable(
               "select nama from rs00017 ".
               "where nama = '".$_GET["mDOKTER"]."' and pangkat LIKE '%DOKTER%' Order By nama Asc;");
} else {
    $dokter = "Semua";
}

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
    
    $f->selectArray("mUNIT", "Rawatan",
        Array(""=>"", "Y" => "Rawat Jalan", "I" => "Rawat Inap", "N" => "IGD"), $_GET["mUNIT"],
        $ext);


    $f->selectSQL("mPASIEN", "Tipe Pasien",
        "select '' as tc, '' as tdesc union ".
        "select tc, tdesc ".
        "from rs00001  ".
        "where tt='JEP' and tc != '000'", $_GET["mPASIEN"],
        $ext);
 
    $f->selectSQL("mKATEGORY", "Kategori Obat",
        "select '' as tc, '' as tdesc union ".
        "select tc, tdesc ".
        "from rs00001  ".
        "where tt='GOB' and tc != '000'", $_GET["mKATEGORY"],
        $ext);
    
    $f->selectSQL("mDOKTER", "Dokter",
        "select '' as nm_dok, '' as nama union ".
        "select nama as nm_dok, nama ".
        "from rs00017 ".
        "WHERE pangkat LIKE '%DOKTER%' Order By nama Asc ;", $_GET["mDOKTER"],
        $ext);

    $f->selectArray("shift", "Pasien",  Array(""=>"","P" => "Shift Pagi (07.00-14.00)", "S" => "Shift Siang (14.01-21.00)" , "M1" => "Shift Malam (21.01-23.59)" , "M2" => "Shift Malam (00.00-06.59)" ), $_GET["shift"]," ");
    
    $f->submit ("TAMPILKAN");
    $f->execute();
} else {
    $f = new Form("");
    $f->titleme("Dari Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $ts_check_in1");
    $f->titleme("s/d Tanggal  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $ts_check_in2");
    $f->titleme("Rawatan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $unit");
    $f->titleme("Tipe Pasien &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $pasien");
    $f->titleme("Kategori &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $kategory");
    $f->titleme("Dokter &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $dokter");
    $f->execute();
}

echo "<br>";
if (!isset($_GET[sort])) {
       $_GET[sort] = "no_reg";
       $_GET[order] = "asc";
}
// Shift Kerja
if ($_GET["shift"]=="P"){
	$jam1="07:00:00";
	$jam2="14:00:00";
	}elseif($_GET["shift"]=="S"){
	$jam1="14:01:00";
	$jam2="21:00:00";
	}elseif($_GET["shift"]=="M1"){
	$jam1="21:01:00";
	$jam2="23:59:00";
	}elseif($_GET["shift"]=="M2"){
	$jam1="00:00:00";
	$jam2="06:59:00";
	}else{
	$jam1="00:00:00";
	$jam2="23:59:59";
	}


//-- add param start
$addParam = '';
if($_GET['mUNIT'] != ''){
    $addParam = $addParam." AND b.rawat_inap = '".$_GET['mUNIT']."' ";
}
if($_GET['mPASIEN'] != ''){
    $addParam = $addParam." AND d.tc = '".$_GET['mPASIEN']."' ";
}
if($_GET['mKATEGORY'] != ''){
    $addParam = $addParam." AND e.kategori_id = '".$_GET['mKATEGORY']."' ";
}
if($_GET['mDOKTER'] != ''){
    $addParam = $addParam." AND b.diagnosa_sementara = '".$_GET['mDOKTER']."' ";
}
//-- add param end

//-- jumlah tuslah obat racikan
$tRacikan = new PgTable($con, "100%");
$rRacikan = pg_query($con,
          "select ".
          "		CASE WHEN e.kategori_id::numeric=020 THEN (a.qty * 100)
		            ELSE 0
		       	END ".
          "from rs00008 a ".
          "     left join rs00006 b ON a.no_reg = b.id ".
          "     left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') ".
          "     left join rs00016 f ON a.item_id = f.obat_id::character varying ".
          "     left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
          "where (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
          "     (a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) ".
          "     ".$addParam." and (b.waktu_reg between '$jam1' and '$jam2') ");

while($aRacikan = pg_fetch_array($rRacikan)) {
	$totalTuslahRacikan += $aRacikan['case'];
}
//--

$t = new PgTable($con, "100%");
$r2 = pg_query($con,
          "select sum((a.qty * a.harga)+(a.referensi::numeric)) as jum, ".
          "sum((a.referensi::numeric)) as tuslah, ".
          "sum(a.harga) as jual ".
          "from rs00008 a ".
          "     left join rs00006 b ON a.no_reg = b.id ".
          "     left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') ".
          "     left join rs00016 f ON a.item_id = f.obat_id::character varying ".
          "     left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
          "where (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
          "     (a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) ".
          "     ".$addParam." and (b.waktu_reg between '$jam1' and '$jam2') ");

$d2 = pg_fetch_object($r2);
pg_free_result($r2);

$t->SQL = "select tanggal(a.tanggal_trans,0)||' '||to_char(waktu_entry,'hh:mi:ss') as tgl, a.no_reg, c.mr_no, c.nama, ".
          "     e.obat, a.qty, f.harga::integer, ".
          "		CASE WHEN e.kategori_id::numeric=020 THEN (a.harga - 100)
		            ELSE a.harga::numeric
		       	END, ".
          "		CASE WHEN e.kategori_id::numeric=020 THEN (a.qty * 100)
		            ELSE a.referensi::numeric
		       	END, ".
          "		sum((a.qty * a.harga)+(a.referensi::numeric)) as tagih ".
          "from rs00008 a  ".
          "     left join rs00006 b ON a.no_reg = b.id ".
          "     left join rs00002 c ON b.mr_no = c.mr_no ".
          "     left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') ".
          "     left join rs00016 f ON a.item_id = f.obat_id::character varying ".
          "     left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
          "where ".
          "     (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
          "     (a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) ".
          "     ".$addParam." and (b.waktu_reg between '$jam1' and '$jam2') ".
          "group by tgl,c.mr_no, c.nama, a.no_reg, e.obat, a.qty, a.harga, f.harga, a.referensi, e.kategori_id";

$t->setlocale("id_ID");
$t->ShowRowNumber = true;
$t->ColAlign[0] = "CENTER";
$t->ColAlign[1] = "CENTER";
$t->ColAlign[6] = "RIGHT";
$t->RowsPerPage = 300;
//$t->ColFormatMoney[4] = "%!+#2n";
//$t->ColFormatMoney[5] = "%!+#2n";
//$t->ColFormatMoney[6] = "%!+#2n";
$t->ColHeader = array("TANGGAL TRANSAKSI","NO.REG","NO.MR","NAMA PASIEN","NAMA OBAT","QTY","HARGA POKOK","HARGA JUAL","TUSLAH","TOTAL (Rp.)");

//$t->ShowSQLExecTime = true;
//$t->ShowSQL = true;
if(!$GLOBALS['print']){
    $t->RowsPerPage = 300;
    $t->ColFooter[7] =  "Total";
    //$t->ColFooter[6] =  number_format($d2->jual,2);
    $t->ColFooter[8] =  number_format($d2->tuslah + $totalTuslahRacikan,2);
    $t->ColFooter[9] =  number_format($d2->jum,2);
}else{
    $t->RowsPerPage = 300;
    $t->ColFooter[7] =  "Total";
    //$t->ColFooter[6] =  number_format($d2->jual,2);
    $t->ColFooter[8] =  number_format($d2->tuslah + $totalTuslahRacikan,2);
    $t->ColFooter[9] =  number_format($d2->jum,2);
    //$t->ColFormatHtml[7] = icon("edit","Edit");
    $t->DisableNavButton = true;
    $t->DisableScrollBar = true;
    //$t->DisableStatusBar = true;
}
$t->execute();
//--end

?>
