<?php
/*--------------
 * 2013-03-07
 * wildan sawaludin code
--------------*/

$PID = "lap_penj_formalin";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/form.php");

//--start
if (!$GLOBALS['print']){
    title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > <b>LAPORAN PENJUALAN FORMALIN</b>");
    title_excel("lap_penj_obat&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."".
        "&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."".
        "&mUNIT=".$_GET["mUNIT"]."&mPASIEN=".$_GET["mPASIEN"]."&mKATEGORY=".$_GET["mKATEGORY"]."&mOBAT=".$_GET["mOBAT"]."&mDOKTER=".$_GET["mDOKTER"]."&mSPESIALIS=".$_GET["mSPESIALIS"]."");
} else {
    title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Penjualan Formalin");
    title_excel("lap_penj_obat&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."".
        "&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."".
        "&mUNIT=".$_GET["mUNIT"]."&mPASIEN=".$_GET["mPASIEN"]."&mKATEGORY=".$_GET["mKATEGORY"]."&mOBAT=".$_GET["mOBAT"]."&mDOKTER=".$_GET["mDOKTER"]."&mSPESIALIS=".$_GET["mSPESIALIS"]."");
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



if ($_GET["mDOKTER"] != '') {
    $dokter = getFromTable(
               "select nama from rs00017 ".
               "where nama = '".$_GET["mDOKTER"]."' and pangkat LIKE '%DOKTER%' Order By nama Asc;");
} else {
    $dokter = "Semua";
}

if ($_GET["mSPESIALIS"] != '') {
    $spesialis = getFromTable(
               "select nama jabatan_medis_fungsional rs00018 ".
               "where id = '".$_GET["mSPESIALIS"]."' and id in ('200', '188', '196', '207', '194', '201', '190', '189', '205', '192', '195', '197', '187', '235') Order By jabatan_medis_fungsional Asc;");
} else {
    $spesialis = "Semua";
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
        "where tt='JEP' and tc != '000' order by tdesc", $_GET["mPASIEN"],
        $ext);

        
    $f->selectSQL("mDOKTER", "Dokter",
        "select '' as nm_dok, '' as nama union ".
        "select nama as nm_dok, nama ".
        "from rs00017 ".
        "WHERE pangkat LIKE '%DOKTER%' order by nama Asc ;", $_GET["mDOKTER"],
        $ext);
		
	//jabatan_medis_fungsional_id
	$f->selectSQL("mSPESIALIS", "Spesialis",
        "select '' as id, '' as jabatan_medis_fungsional union ".
        "select id, jabatan_medis_fungsional ".
        "from rs00018  ".
        "where id in ('200', '188', '196', '207', '194', '201', '190', '189', '205', '192', '195', '197', '187', '235') order by jabatan_medis_fungsional ASC", $_GET["mSPESIALIS"],
        $ext);
    
    $f->submit ("TAMPILKAN");
    $f->execute();
} else {
    $f = new Form("");
    $f->titleme("Dari Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $ts_check_in1");
    $f->titleme("s/d Tanggal  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $ts_check_in2");
    $f->titleme("Rawatan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $unit");
    $f->titleme("Tipe Pasien &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $pasien");
    $f->titleme("Kategori Obat &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $kategory");
    $f->titleme("Obat &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $obat");
    $f->titleme("Dokter &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $dokter");
    $f->titleme("Spesialis &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $spesialis");
	$f->execute();
}

echo "<br>";
if (!isset($_GET[sort])) {
       $_GET[sort] = "a.tanggal_trans";
       $_GET[order] = "asc";
}

//-- add param start
$addParam = '';
if($_GET['mUNIT'] != ''){
    $addParam = $addParam." AND b.rawat_inap = '".$_GET['mUNIT']."' ";
}
if($_GET['mPASIEN'] != ''){
    $addParam = $addParam." AND d.tc = '".$_GET['mPASIEN']."' ";
}

if($_GET['mDOKTER'] != ''){
    $addParam = $addParam." AND b.diagnosa_sementara = '".$_GET['mDOKTER']."' ";
}
if($_GET['mSPESIALIS'] != ''){
    $addParam = $addParam." AND ff.id = '".$_GET['mSPESIALIS']."' ";
}
//-- add param end

$t = new PgTable($con, "100%");
$t->SQL = "select tanggal(a.tanggal_trans,0)||' '||to_char(waktu_entry,'hh:mi:ss') as tgl, a.nmr_transaksi, a.no_reg, c.nama, c.alm_tetap,b.diagnosa_sementara, e.obat, a.qty,to_char(gg.harga,'999,999,999.99'),to_char(a.qty*gg.harga,'999,999,999.99') as total   ".
          "from rs00008 a  ".
          "     left join rs00006 b ON a.no_reg = b.id ".
          "     left join rs00002 c ON b.mr_no = c.mr_no ".
          "     left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') ".
          "     left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
          "     left join rs00016 gg ON to_number(a.item_id,'999999999999') = gg.obat_id ".
		  "     left join rs00017 ee ON ee.nama = b.diagnosa_sementara AND ee.pangkat like '%DOKTER%' ".
          "     left join rs00018 ff ON ff.id = ee.jabatan_medis_fungsional_id ".
          "where ".
          "     (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
          "     (a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) ".
          "     ".$addParam."  AND e.obat like '%FORMALIN%' ".
          "group by a.tanggal_trans, tgl, a.nmr_transaksi, a.no_reg, c.mr_no, c.nama, c.alm_tetap, e.obat, a.qty, b.diagnosa_sementara,gg.harga ";

		  $r2 = pg_query($con,
		  "select SUM(a.qty*gg.harga) as harga  ".
          "from rs00008 a  ".
          "     left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
          "     left join rs00016 gg ON to_number(a.item_id,'999999999999') = gg.obat_id ".
		  "where ".
          "     (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
          "     (a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) ".
          "     ".$addParam."  AND e.obat like '%FORMALIN%' ");
          //"group by a.tanggal_trans, tgl, a.nmr_transaksi, a.no_reg, c.mr_no, c.nama, c.alm_tetap, e.obat, a.qty, b.diagnosa_sementara ");

$d2 = pg_fetch_object($r2);
pg_free_result($r2);

$t->setlocale("id_ID");
$t->ShowRowNumber = true;
$t->ColAlign[0] = "CENTER";
$t->ColAlign[1] = "CENTER";
$t->RowsPerPage = 300;
//$t->ColFormatMoney[4] = "%!+#2n";
//$t->ColFormatMoney[5] = "%!+#2n";
//$t->ColFormatMoney[6] = "%!+#2n";
$t->ColHeader = array("TANGGAL","NO.RESEP","NO.REG","NAMA PASIEN","ALAMAT","NAMA DOKTER","NAMA OBAT","QTY","HARGA","TOTAL HARGA");

//$t->ShowSQLExecTime = true;
//$t->ShowSQL = true;
if(!$GLOBALS['print']){
    $t->RowsPerPage = 300;
	$t->ColFormatNumber[7] = 2;
	$t->ColFormatNumber[8] = 0;
	$t->ColFormatNumber[9] = 0;
	$t->ColFooter[10] =  number_format($d2->harga,2);
	
}else{
    $t->RowsPerPage = 300;
    $t->DisableNavButton = true;
    $t->DisableScrollBar = true;
	$t->ColFooter[2] =  number_format($d2->harga,2);
    //$t->DisableStatusBar = true;
}
$t->execute();
//--end

?>