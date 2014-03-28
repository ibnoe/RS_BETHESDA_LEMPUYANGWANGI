<?php
/*--------------
 * 2013-03-07
 * wildan sawaludin code
--------------*/

session_start();
$PID = "lap_pemakaian_obat";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/form.php");

//--start
if (!$GLOBALS['print']){
    title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > <b>LAPORAN PEMAKAIAN OBAT PER DOKTER</b>");
    title_excel("lap_pemakaian_obat&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."".
        "&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."".
        "&mUNIT=".$_GET["mUNIT"]."&mPASIEN=".$_GET["mPASIEN"]."&mKATEGORY=".$_GET["mKATEGORY"]."&mDOKTER=".$_GET["mDOKTER"]."&mSPESIALIS=".$_GET["mSPESIALIS"]."");
} else {
    title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Pemakaian Obat Per Dokter");
    title_excel("lap_pemakaian_obat&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."".
        "&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."".
        "&mUNIT=".$_GET["mUNIT"]."&mPASIEN=".$_GET["mPASIEN"]."&mKATEGORY=".$_GET["mKATEGORY"]."&mDOKTER=".$_GET["mDOKTER"]."&mSPESIALIS=".$_GET["mSPESIALIS"]."");
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
 
    $f->selectSQL("mKATEGORY", "Kategori Obat",
        "select '' as tc, '' as tdesc union ".
        "select tc, tdesc ".
        "from rs00001  ".
        "where tt='GOB' and tc != '000' order by tdesc", $_GET["mKATEGORY"],
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
	

    $f->selectArray("shift", "Shift",  Array(""=>"","P" => "Shift Pagi (07.00-14.00)", "S" => "Shift Siang (14.01-21.00)" , "M1" => "Shift Malam (21.01-23.59)" , "M2" => "Shift Malam (00.00-06.59)" ), $_GET["shift"]," ");
    
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
	$f->titleme("Spesialis &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: $spesialis");
    $f->execute();
}

echo "<br>";
if (!isset($_GET[sort])) {
       $_GET[sort] = "dokter";
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
if($_GET['mSPESIALIS'] != ''){
    $addParam = $addParam." AND ff.id = '".$_GET['mSPESIALIS']."' ";
}
//-- rs00017(nama dokter) + rs00018(jabatan_medis_fungsional_id) + rs00006 (diagnosa_sementara)
//-- add param end

?>

<SCRIPT language="JavaScript" src="plugin/jquery-1.8.2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="plugin/jquery-ui.js"></SCRIPT>
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.theme.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.datepicker.css">
<?php

//-- jumlah tuslah obat grand total
$rowsObatTotal = pg_query($con,
		  "select sum(a.qty) as qty ".
          "from rs00008 a ".
          "     left join rs00006 b ON a.no_reg = b.id ".
          "     left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') ".
          "     left join rs00016 f ON a.item_id = f.obat_id::character varying ".
          "     left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
          "     left join rs00017 ee ON ee.nama = b.diagnosa_sementara ".
          "     left join rs00018 ff ON ff.id = ee.jabatan_medis_fungsional_id ".
          "where (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
          "     (a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) ".
          "     ".$addParam." and (b.waktu_reg between '$jam1' and '$jam2') ");

$rowsObatGrandTotal = pg_fetch_object($rowsObatTotal);
pg_free_result($rowsObatTotal);
//--

//-- rows semua obat
$rowsObat = pg_query($con, "select b.diagnosa_sementara as dokter, ".
          "     e.obat as obat, sum(a.qty) as qty ".
          "from rs00008 a ".
          "     left join rs00006 b ON a.no_reg = b.id ".
          "     left join rs00002 c ON b.mr_no = c.mr_no ".
          "     left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') ".
          "     left join rs00016 f ON a.item_id = f.obat_id::character varying ".
          "     left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
		  "     left join rs00017 ee ON ee.nama = b.diagnosa_sementara ".
          "     left join rs00018 ff ON ff.id = ee.jabatan_medis_fungsional_id ".
          "where ".
          "     (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
          "     (a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) ".
          "     ".$addParam." and (b.waktu_reg between '$jam1' and '$jam2') ".
          "group by dokter, e.obat ".
          "order by dokter");
//--

?>

<table id="list-pasien" width="100%">
    <thead>
        <tr>
            <td style="font-size: 11px;" align="CENTER" class="TBL_HEAD" width="20">No.</td>
            <td style="font-size: 11px;" align="CENTER" class="TBL_HEAD" width="130">DOKTER</td>
            <td style="font-size: 11px;" align="CENTER" class="TBL_HEAD" width="150">NAMA OBAT</td>
            <td style="font-size: 11px;" align="CENTER" class="TBL_HEAD" width="40">QTY</td>
        </tr>
    </thead>
    <tbody>
        <?php
            if(!empty($rowsObat)){
                 $i=1;
                 while($row=pg_fetch_array($rowsObat)){
					
                 	$newNamaDokter = $row['dokter'];
                 	
                 	if ($oldNamaDokter == $row['dokter'] && $oldNamaDokter != '') {
						$ii='';
					 } else {
					 	$ii=$i++;
					 }
                 	
                 	if ($oldNamaDokter == $row['dokter'] && $oldNamaDokter != '') {
						$newNamaDokter = '';
					}
        ?>
        <tr>
            <td style="font-size: 11px;" align="right"><?php echo $ii?></td>
            <td style="font-size: 11px;" align="left">&nbsp;&nbsp;&nbsp;<?php echo $newNamaDokter;?></td>
            <td style="font-size: 11px;" align="left">&nbsp;&nbsp;&nbsp;<?php echo $row['obat'];?></td>
            <td style="font-size: 11px;" align="right"><?php echo $row['qty'];?>&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <?php
        	$oldNamaDokter	= $row['dokter'];
                 }
            }
        ?>
        <tr>
	        <td style="font-size: 11px;" colspan="3" class="TBL_HEAD" align="right">T O T A L</td>
	        <td style="font-size: 11px;" class="TBL_HEAD" align="right" id="jumlah_total"><?php echo number_format($rowsObatGrandTotal->qty,2);?></td>
	    </tr>
    </tbody>    
</table>

<script language="JavaScript" type="text/JavaScript">
$(document).ready(function() { 
	//jquery code here
});
</script>

<?php
function tanggal($tanggal) {
    $arrTanggal = explode('-', $tanggal);

    $hari = $arrTanggal[2];
    $bulan = $arrTanggal[1];
    $tahun = $arrTanggal[0];

    $result = $hari . ' ' . bulan($bulan) . ' ' . $tahun;

    return $result;
}

function tanggalShort($tanggal) {
    $arrTanggal = explode('-', $tanggal);

    $hari = $arrTanggal[2];
    $bulan = $arrTanggal[1];
    $tahun = $arrTanggal[0];

    $result = $hari . ' ' . substr(bulan($bulan),0,3) . ' ' . $tahun;

    return $result;
}

function bulan($params) {
    switch ($params) {
        case 1:
            $bln = "Januari";
            break;
        case 2:
            $bln = "Pebruari";
            break;
        case 3:
            $bln = "Maret";
            break;
        case 4:
            $bln = "April";
            break;
        case 5:
            $bln = "Mei";
            break;
        case 6:
            $bln = "Juni";
            break;
        case 7:
            $bln = "Juli";
            break;
        case 8:
            $bln = "Agustus";
            break;
        case 9:
            $bln = "September";
            break;
        case 10:
            $bln = "Oktober";
            break;
        case 11:
            $bln = "Nopember";
            break;
        case 12:
            $bln = "Desember";
            break;
            break;
    }
    return $bln;
}
?>