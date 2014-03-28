<?php
//**************** Laporan Khusus OGB *************
//************ Metri, 2013-04-04; 01:45 ***********

$PID = "lap_khusus_ogb";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/form.php");

//--start
if (!$GLOBALS['print']){
    laporan_ogb("<img src='icon/keuangan-2.gif' align='absmiddle' > <b>LAPORAN KHUSUS OGB</b>");
    title_excel("lap_penj_obat&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."".
        "&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."".
        "&mUNIT=".$_GET["mUNIT"]."&mPASIEN=".$_GET["mPASIEN"]."&mKATEGORY=".$_GET["mKATEGORY"]."");
} else {
    title("<font size='1em'>LAPORAN KHUSUS<br /> Laporan OGB");
    title_excel("lap_penj_obat&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."".
        "&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."".
        "&mUNIT=".$_GET["mUNIT"]."&mPASIEN=".$_GET["mPASIEN"]."&mKATEGORY=".$_GET["mKATEGORY"]."");
}


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

    $f->selectSQL("jenis_id", "Jenis Obat",
        "select '' as tc, '' as tdesc union ".
        "select tc, tdesc ".
        "from rs00001  ".
        "where tt='GNR' and tc != '000' order by tdesc", $_GET["jenis_id"],
        $ext);

    $f->selectSQL("tipe_id", "Tipe Obat",
        "select '' as tc, '' as tdesc union ".
        "select tc, tdesc ".
        "from rs00001  ".
        "where tt='FRM' and tc != '000' order by tdesc", $_GET["tipe_id"],
        $ext);
    
    $f->selectArray("mUNIT", "Rawatan",
        Array(""=>"", "Y" => "Rawat Jalan", "I" => "Rawat Inap", "N" => "IGD"), $_GET["mUNIT"],
        $ext);


    $f->selectSQL("mPASIEN", "Tipe Pasien",
        "select '' as tc, '' as tdesc union ".
        "select tc, tdesc ".
        "from rs00001  ".
        "where tt='JEP' and tc != '000' order by tdesc", $_GET["mPASIEN"],
        $ext);
 
    $f->selectSQL("mKATEGORY", "Kategori Obat",
        "select '' as tc, '' as tdesc union ".
        "select tc, tdesc ".
        "from rs00001  ".
        "where tt='GOB' and (tc='007' or tc='008' or tc='009' or tc='010' or tc='011' or tc='012' or tc='014') order by tdesc", $_GET["mKATEGORY"],
        $ext);
    
    $f->submit ("TAMPILKAN");
    $f->execute();
} else {

 $f = new Form("");
	echo "<table>";
	echo "<tr>";
		echo "<td bgcolor='WHITE'><font size='0.88em'><b> Nama Apotik </td>";
		echo "<td bgcolor='WHITE'><font size='0.88em'><b>: INSTALASI FARMASI RUMAH SAKIT SARILA HUSADA </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='WHITE'><font size='0.9em'><b> Periode </td>";
		echo "<td bgcolor='WHITE'><font size='0.9em'><b>: $ts_check_in1 s/d $ts_check_in2 </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='WHITE'><font size='0.9em'><b> Rawatan</td>";
		echo "<td bgcolor='WHITE'><font size='0.9em'><b>: $unit </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='WHITE'><font size='0.9em'><b> Kategori Obat </td>";
		echo "<td bgcolor='WHITE'><font size='0.9em'><b>: $kategory </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='WHITE'><font size='0.9em'><b> Tipe Pasien</td>";
		echo "<td bgcolor='WHITE'><font size='0.9em'><b>: $pasien </td>";
	echo "</tr>";
	echo "</table>";

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
if($_GET['jenis_id'] != ''){
    $addParam = $addParam." AND e.jenis_id = '".$_GET['jenis_id']."' ";
}
if($_GET['tipe_id'] != ''){
    $addParam = $addParam." AND e.tipe_id = '".$_GET['tipe_id']."' ";
}

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
          "select sum(a.qty) as jum, ".
          "sum((a.referensi::numeric)) as tuslah, ".
          "sum(a.harga) as jual ".
          "from rs00008 a ".
          "     left join rs00006 b ON a.no_reg = b.id ".
          "     left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') ".
          "     left join rs00016 f ON a.item_id = f.obat_id::character varying ".
          "     left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
          "where (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
          "     (a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) ".
          "     ".$addParam."  ");

$d2 = pg_fetch_object($r2);
pg_free_result($r2);

$t->SQL = "select e.obat, a.nmr_transaksi, a.no_reg, tanggal(a.tanggal_trans,0) as tgl,tanggal(a.tanggal_entry,0)||' '||to_char(waktu_entry,'hh:mi:ss') as tgle, a.qty, c.mr_no, c.nama,c.alm_tetap, ".
	  "case when b.rawat_inap='I' then 'Rawat Inap' ".
	  "    when b.rawat_inap='Y' then 'Rawat Jalan' else 'IGD' end as rawat,".
          "       b.diagnosa_sementara ".
          "from rs00008 a  ".
          "     left join rs00006 b ON a.no_reg = b.id ".
          "     left join rs00002 c ON b.mr_no = c.mr_no ".
          "     left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') ".
          "     left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
          "where ".
          "     (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
          "     (a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) ".
          "     ".$addParam."  ".
          "group by tgl, a.nmr_transaksi, c.mr_no, c.nama, a.no_reg, e.obat, a.qty, e.kategori_id,tgle,c.alm_tetap,b.diagnosa_sementara,rawat";

$t->setlocale("id_ID");
$t->ShowRowNumber = true;
$t->ColAlign[0] = "LEFT";
$t->ColAlign[1] = "CENTER";
$t->ColAlign[6] = "LEFT";
$t->RowsPerPage = 300;
$t->ColHeader = array("<font size='2em'>NAMA OBAT","<font size='2em'>NO.RESEP","<font size='2em'>NO.REG","<font size='2em'>TANGGAL RESEP","<font size='2em'>TANGGAL PENYERAHAN","<font size='2em'>QTY","<font size='2em'>NO.MR","<font size='2em'>NAMA PASIEN","<font size='2em'>ALAMAT PASIEN","<font size='2em'>RAWATAN","<font size='2em'>NAMA DOKTER");

if(!$GLOBALS['print']){
    $t->RowsPerPage = 300;
    $t->ColFooter[4] =  "Total Qty";
    $t->ColFooter[5] =  number_format($d2->jum,1);
}else{
    $t->RowsPerPage = 300;
    $t->ColFooter[4] =  "Total";
    $t->ColFooter[5] =  number_format($d2->jum,1);
    $t->DisableNavButton = true;
    $t->DisableScrollBar = true;
}
$t->execute();
//--end
?>
