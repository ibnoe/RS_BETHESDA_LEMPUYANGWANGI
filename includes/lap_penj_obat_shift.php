<?php
/*--------------
 * 2013-04-16
 * Wildan S ST. code
--------------*/

session_start();
$PID = "lap_penj_obat_shift";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/form.php");

$tgl_sekarang = date("d M Y", time());

//--start
if (!$GLOBALS['print']){
    title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > <b>LAPORAN PENJUALAN OBAT PER SHIFT</b>");
    title_excel("lap_penj_obat_shift&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."".
        "&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."".
        "&mUNIT=".$_GET["mUNIT"]."&mPASIEN=".$_GET["mPASIEN"]."&mKATEGORY=".$_GET["mKATEGORY"]."&mDOKTER=".$_GET["mDOKTER"]."&mSPESIALIS=".$_GET["mSPESIALIS"]."");
} else {
    title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Penjualan Obat Per Shift");
    title_excel("lap_penj_obat_shift&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."".
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
        "WHERE pangkat LIKE '%DOKTER%' order by nama ;", $_GET["mDOKTER"],
        $ext);
	
	//jabatan_medis_fungsional_id
	$f->selectSQL("mSPESIALIS", "Spesialis",
        "select '' as id, '' as jabatan_medis_fungsional union ".
        "select id, jabatan_medis_fungsional ".
        "from rs00018  ".
        "where id in ('200', '188', '196', '207', '194', '201', '190', '189', '205', '192', '195', '197', '187', '235') order by jabatan_medis_fungsional ASC", $_GET["mSPESIALIS"],
        $ext);
	
    $f->selectArray("shift", "Shift",  Array(""=>"","P" => "Shift Pagi (07.00-14.00)", "S" => "Shift Siang (14.01-21.00)" , "M1" => "Shift Malam (21.01-23.59)", "M2" => "Shift Malam (00.00-06.59)"  ), $_GET["shift"]," ");
    
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
	}
	elseif($_GET["shift"]=="M2"){
	$jam1="00:00:00";
	$jam2="06:59:00";
	} 
	else{
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
//-- add param end

?>

<SCRIPT language="JavaScript" src="plugin/jquery-1.8.2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="plugin/jquery-ui.js"></SCRIPT>
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.theme.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.datepicker.css">

<?php
//start apotik klinik
//-------------------------------------------------------------------------------------------------------------------
$f1 = new Form("");
$f1->titleme("1. Penjualan Apotik Klinik");
$f1->execute();

//-- jumlah rows semua obat klinik
$totalObatKliniks = pg_query($con, "select ".
          "		sum((a.tagihan)+(a.referensi::numeric)) as total_tag, ".
          "		sum(a.dibayar_penjamin) as total_penjamin, ".
          "		sum((a.tagihan)+(a.referensi::numeric)) - sum(dibayar_penjamin) as total_bayar, ".
          "		sum(CASE WHEN e.kategori_id::numeric=020 THEN (a.qty * 100)
		            ELSE a.referensi::numeric
		       	END) as total_tuslah ".
          "from rs00008 a ".
          "     left join rs00006 b ON a.no_reg = b.id ".
          "     left join rs00002 c ON b.mr_no = c.mr_no ".
          "     left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') ".
          "     left join rs00016 f ON a.item_id = f.obat_id::character varying ".
          "     left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
		  "     left join rs00017 ee ON ee.nama = b.diagnosa_sementara AND ee.pangkat like '%DOKTER%' ".
          "     left join rs00018 ff ON ff.id = ee.jabatan_medis_fungsional_id ".
          "where ".
          "     (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
          "     (a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) ".
          "     ".$addParam." and (a.waktu_entry between '$jam1' and '$jam2') ");

$totalObatKlinik = pg_fetch_object($totalObatKliniks);
pg_free_result($totalObatKliniks);
//--

//-- rows semua obat klinik
$rowsObatKliniks = pg_query($con, "select a.tanggal_trans as tgl, a.nmr_transaksi as nmr_transaksi, a.no_reg as no_reg, ".
          "     c.mr_no as mr_no, c.nama as nama, ".
          "		sum((a.tagihan)+(a.referensi::numeric)) as jum_tag, ".
          "		sum(a.dibayar_penjamin) as jum_penjamin, ".
          "		sum((a.tagihan)+(a.referensi::numeric)) - sum(a.dibayar_penjamin) as jum_bayar, ".
          "		sum(CASE WHEN e.kategori_id::numeric=020 THEN (a.qty * 100)
		            ELSE a.referensi::numeric
		       	END) as jum_tuslah ".
          "from rs00008 a ".
          "     left join rs00006 b ON a.no_reg = b.id ".
          "     left join rs00002 c ON b.mr_no = c.mr_no ".
          "     left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') ".
          "     left join rs00016 f ON a.item_id = f.obat_id::character varying ".
          "     left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
		  "     left join rs00017 ee ON ee.nama = b.diagnosa_sementara AND ee.pangkat like '%DOKTER%' ".
          "     left join rs00018 ff ON ff.id = ee.jabatan_medis_fungsional_id ".
          "where ".
          "     (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
          "     (a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) ".
          "     ".$addParam." and (a.waktu_entry between '$jam1' and '$jam2') ".
          "group by a.tanggal_trans, a.nmr_transaksi, a.no_reg, c.mr_no, c.nama ".
          "order by a.tanggal_trans, a.nmr_transaksi");
//--

?>

<table id="list-pasien" width="100%">
    <thead>
        <tr>
            <td align="CENTER" class="TBL_HEAD" width="20">No.</td>
            <td align="CENTER" class="TBL_HEAD" width="70">TANGGAL</td>
            <td align="CENTER" class="TBL_HEAD" width="40">NO.RESEP</td>
            <td align="CENTER" class="TBL_HEAD" width="40">NO.REG</td>
            <td align="CENTER" class="TBL_HEAD" width="40">NO.MR</td>
            <td align="CENTER" class="TBL_HEAD" width="150">NAMA</td>
            <td align="CENTER" class="TBL_HEAD" width="60">JUM. TAGIHAN</td>
            <td align="CENTER" class="TBL_HEAD" width="60">PENJAMIN</td>
            <td align="CENTER" class="TBL_HEAD" width="60">BAYAR TUNAI</td>
            <td align="CENTER" class="TBL_HEAD" width="60">TUSLAH</td>
        </tr>
    </thead>
    <tbody>
        <?php
            if(!empty($rowsObatKliniks)){
                 $i=0;
                 while($rowsObatKlinik=pg_fetch_array($rowsObatKliniks)){
                 	 $i++;
					 
        ?>
        <tr>
            <td align="right"><?php echo $i?></td>
            <td align="left">&nbsp;&nbsp;<?php echo $rowsObatKlinik['tgl'];?></td>
            <td align="left">&nbsp;&nbsp;<?php echo "<b>".$rowsObatKlinik['nmr_transaksi']."</b>";?></td>
            <td align="left">&nbsp;&nbsp;<a href="index2.php?p=lap_transaksi_pasien&no_reg=<?php echo $rowsObatKlinik['no_reg'];?>"><?php echo $rowsObatKlinik['no_reg'];?></a>&nbsp;&nbsp;&nbsp;</td>
            <td align="left">&nbsp;&nbsp;<?php echo $rowsObatKlinik['mr_no'];?></td>
            <td align="left">&nbsp;&nbsp;<?php echo $rowsObatKlinik['nama'];?></td>
            <td align="right"><?php echo number_format($rowsObatKlinik['jum_tag'],2);?>&nbsp;&nbsp;</td>
            <td align="right"><?php echo number_format($rowsObatKlinik['jum_penjamin'],2);?>&nbsp;&nbsp;</td>
            <td align="right"><?php echo number_format($rowsObatKlinik['jum_bayar'],2);?>&nbsp;&nbsp;</td>
            <td align="right"><?php echo number_format($rowsObatKlinik['jum_tuslah'],2);?>&nbsp;&nbsp;</td>
        </tr>
        <?php
                 }
            }
        ?>
        <tr>
	        <td colspan="6" class="TBL_HEAD" align="right">T O T A L</td>
	        <td class="TBL_HEAD" align="right" id="jumlah_tagihan"><?php echo number_format($totalObatKlinik->total_tag,2);?></td>
	        <td class="TBL_HEAD" align="right" id="jumlah_penjamin"><?php echo number_format($totalObatKlinik->total_penjamin,2);?></td>
	        <td class="TBL_HEAD" align="right" id="jumlah_bayar"><?php echo number_format($totalObatKlinik->total_bayar,2);?></td>
	        <td class="TBL_HEAD" align="right" id="jumlah_tuslah"><?php echo number_format($totalObatKlinik->total_tuslah,2);?></td>
	    </tr>
    </tbody>    
</table>
<?php
//end apotik klinik
//-------------------------------------------------------------------------------------------------------------------
?>


<?php
echo "<br>";
//start apotik umum
//-------------------------------------------------------------------------------------------------------------------
$f2 = new Form("");
$f2->titleme("2. Penjualan Apotik Umum");
$f2->execute();

//-- jumlah rows semua obat umum
$totalObatUmums = pg_query($con,
          "select ".
          "		sum(c.tagihan) as total_tag, ".
          "		sum(c.dibayar_penjamin) as total_penjamin, ".
          "		sum(c.tagihan) - 0 as total_bayar, ".
          "		sum(CASE WHEN b.kategori_id::numeric=020 THEN (c.qty * 100)
		            ELSE c.referensi::numeric
		       	END) as total_tuslah ".
          "from apotik_umum a ".
          "     left join rs00015 b ON a.obat_id = b.id ".
          "     left join rs00008 c ON a.id::character varying = c.id_transaksi ".
          "     left join rs00016 d ON a.obat_id = d.obat_id ".
          "where (a.tanggal_entry between '$ts_check_in1' and '$ts_check_in2') and ".
          "     (c.trans_type::text = 'OB2'::text) ".
          "     ".$addParam." and (a.waktu_entry between '$jam1' and '$jam2') ");
$totalObatUmum = pg_fetch_object($totalObatUmums);
pg_free_result($totalObatUmums);
//--

//-- rows semua obat umum
$rowsObatUmums = pg_query($con, "select a.tanggal_entry as tgl, ".
		  "		a.no_reg as no_reg, a.nama as nama, ".
		  "		sum(c.tagihan) as jum_tag, ".
		  "		sum(c.dibayar_penjamin) as jum_penjamin, ".
		  "		sum(c.tagihan) - 0 as jum_bayar, ".
		  "		sum(CASE WHEN b.kategori_id::numeric=020 THEN (c.qty * 100)
		            ELSE c.referensi::numeric
		       	END) as jum_tuslah ".
          "from apotik_umum a  ".
          "     left join rs00015 b ON a.obat_id = b.id ".
          "     left join rs00008 c ON a.id::character varying = c.id_transaksi ".
          "     left join rs00016 d ON a.obat_id = d.obat_id ".
          "where ".
          "     (a.tanggal_entry between '$ts_check_in1' and '$ts_check_in2') and ".
          "     (c.trans_type::text = 'OB2'::text) and c.item_id != '' ".
          "     ".$addParam." and (a.waktu_entry between '$jam1' and '$jam2') ".
          "group by a.tanggal_entry, a.no_reg, a.nama ".
          "order by a.tanggal_entry, a.no_reg");
//--

?>

<table id="list-pasien" width="100%">
    <thead>
        <tr>
            <td align="CENTER" class="TBL_HEAD" width="20">No.</td>
            <td align="CENTER" class="TBL_HEAD" width="70">TANGGAL</td>
            <td align="CENTER" class="TBL_HEAD" width="40">NO.REG</td>
            <td align="CENTER" class="TBL_HEAD" width="150">NAMA</td>
            <td align="CENTER" class="TBL_HEAD" width="60">JUM. TAGIHAN</td>
            <td align="CENTER" class="TBL_HEAD" width="60">PENJAMIN</td>
            <td align="CENTER" class="TBL_HEAD" width="60">BAYAR TUNAI</td>
            <td align="CENTER" class="TBL_HEAD" width="60">TUSLAH</td>
        </tr>
    </thead>
    <tbody>
        <?php
            if(!empty($rowsObatUmums)){
                 $j=0;
                 while($rowsObatUmum=pg_fetch_array($rowsObatUmums)){
                 	 $j++;
					 
					 if ($rowsObatUmum['nama'] != "") {
						 $nama = $rowsObatUmum['nama'];
					 } else {
						 $nama = '-';
					 }
					
        ?>
        <tr>
            <td align="right"><?php echo $j?></td>
            <td align="left">&nbsp;&nbsp;<?php echo $rowsObatUmum['tgl'];?></td>
            <td align="left">&nbsp;&nbsp;<a href="index2.php?p=apotik_umum&no_reg=<?php echo $rowsObatUmum['no_reg'];?>"><?php echo $rowsObatUmum['no_reg'];?></a></td>
            <td align="left">&nbsp;&nbsp;<?php echo $nama;?></td>
            <td align="right"><?php echo number_format($rowsObatUmum['jum_tag'],2);?>&nbsp;&nbsp;</td>
            <td align="right"><?php echo number_format($rowsObatUmum['jum_penjamin'],2);?>&nbsp;&nbsp;</td>
            <td align="right"><?php echo number_format($rowsObatUmum['jum_bayar'],2);?>&nbsp;&nbsp;</td>
            <td align="right"><?php echo number_format($rowsObatUmum['jum_tuslah'],2);?>&nbsp;&nbsp;</td>
        </tr>
        <?php
                 }
            }
        ?>
        <tr>
	        <td colspan="4" class="TBL_HEAD" align="right">T O T A L</td>
	        <td class="TBL_HEAD" align="right" id="jumlah_tagihan"><?php echo number_format($totalObatUmum->total_tag,2);?></td>
	        <td class="TBL_HEAD" align="right" id="jumlah_penjamin"><?php echo number_format($totalObatUmum->total_penjamin,2);?></td>
	        <td class="TBL_HEAD" align="right" id="jumlah_bayar"><?php echo number_format($totalObatUmum->total_bayar,2);?></td>
	        <td class="TBL_HEAD" align="right" id="jumlah_tuslah"><?php echo number_format($totalObatUmum->total_tuslah,2);?></td>
	    </tr>
    </tbody>    
</table>
<?php
//end apotik umum
//-------------------------------------------------------------------------------------------------------------------
?>


<?php
//start return obat
//-------------------------------------------------------------------------------------------------------------------
echo "<br>";
$f3 = new Form("");
$f3->titleme("3. Return Obat");
$f3->execute();

//-- jumlah rows semua return obat
$totalObatReturns = pg_query($con,
		  "select ".
		  "		sum((a.qty_return * a.harga)+(a.referensi::numeric)) as total_tag ".
          "from rs00008_return a ".
          "     left join rs00006 b ON a.no_reg = b.id ".
          "     left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') ".
          "     left join rs00016 f ON a.item_id = f.obat_id::character varying ".
          "     left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
		  "     left join rs00017 ee ON ee.nama = b.diagnosa_sementara ".
          "     left join rs00018 ff ON ff.id = ee.jabatan_medis_fungsional_id ".
          "where (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
          "     (a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) ".
          "     ".$addParam." and (a.waktu_entry between '$jam1' and '$jam2') ");

$totalObatReturn = pg_fetch_object($totalObatReturns);
pg_free_result($totalObatReturns);
//--

//-- rows semua obat return
$rowsObatReturns = pg_query($con, "select a.tanggal_trans as tgl, a.no_reg as no_reg, ".
		  "		c.mr_no as mr_no, c.nama as nama, e.obat as obat, sum(a.qty_return) as qty_return, ".
		  "		f.harga::integer as harga_pokok, sum((a.qty_return * a.harga)+(a.referensi::numeric)) as jum_tag ".
          "from rs00008_return a ".
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
          "     ".$addParam." and (a.waktu_entry between '$jam1' and '$jam2') ".
          "group by a.tanggal_trans, a.no_reg, c.mr_no, c.nama, e.obat, f.harga ".
          "order by a.tanggal_trans, no_reg");
//--

?>

<table id="list-pasien" width="100%">
    <thead>
        <tr>
            <td align="CENTER" class="TBL_HEAD" width="20">No.</td>
            <td align="CENTER" class="TBL_HEAD" width="70">TANGGAL</td>
            <td align="CENTER" class="TBL_HEAD" width="40">NO.REG</td>
            <td align="CENTER" class="TBL_HEAD" width="40">NO.MR</td>
            <td align="CENTER" class="TBL_HEAD" width="150">NAMA PASIEN</td>
            <td align="CENTER" class="TBL_HEAD" width="150">NAMA OBAT</td>
            <td align="CENTER" class="TBL_HEAD" width="150">QTY RETURN</td>
            <td align="CENTER" class="TBL_HEAD" width="150">HARGA POKOK</td>
            <td align="CENTER" class="TBL_HEAD" width="60">TOTAL RETURN</td>
        </tr>
    </thead>
    <tbody>
        <?php
            if(!empty($rowsObatReturns)){
                 $k=0;
                 while($rowsObatReturn=pg_fetch_array($rowsObatReturns)){
                 	 $k++;
        ?>
        <tr>
            <td align="right"><?php echo $k?></td>
            <td align="left">&nbsp;&nbsp;<?php echo $rowsObatReturn['tgl'];?></td>
            <td align="left">&nbsp;&nbsp;<a href="index2.php?p=lap_transaksi_pasien&no_reg=<?php echo $rowsObatReturn['no_reg'];?>"><?php echo $rowsObatReturn['no_reg'];?></a>&nbsp;&nbsp;&nbsp;</td>
            <td align="left">&nbsp;&nbsp;<?php echo $rowsObatReturn['mr_no'];?></td>
            <td align="left">&nbsp;&nbsp;<?php echo $rowsObatReturn['nama'];?></td>
            <td align="left">&nbsp;&nbsp;<?php echo $rowsObatReturn['obat'];?></td>
            <td align="left">&nbsp;&nbsp;<?php echo $rowsObatReturn['qty_return'];?></td>
            <td align="right"><?php echo number_format($rowsObatReturn['harga_pokok'],2);?>&nbsp;&nbsp;</td>
            <td align="right"><?php echo number_format($rowsObatReturn['jum_tag'],2);?>&nbsp;&nbsp;</td>
        </tr>
        <?php
                 }
            }
        ?>
        <tr>
	        <td colspan="8" class="TBL_HEAD" align="right">T O T A L</td>
	        <td class="TBL_HEAD" align="right" id="jumlah_tagihan"><?php echo number_format($totalObatReturn->total_tag,2);?></td>
	    </tr>
    </tbody>    
</table>
<?php
//end obat return
//-------------------------------------------------------------------------------------------------------------------
?>

<?php
$totPenjObat = ($totalObatKlinik->total_bayar + $totalObatUmum->total_bayar) - $totalObatReturn->total_tag;
if($GLOBALS['print']) {
?>

	<br />
	<br />
	<br />
	<br />
	<br />
	<table id="list-pasien" width="100%">
		<tr>
			<td align="left" "><font size="3"  face="Times New Roman"><b><? echo "Total Penjualan Obat (Klinik & Umum) - Return Obat = ".number_format($totPenjObat,2); ?></b></td>
		</tr>
	</table>
	<br />
	<table id="list-pasien" width="100%">
		<tr>
			<td align="center"><font size="3"  face="Times New Roman"><? echo "Sragen, ".$tgl_sekarang; ?></td>
			<td align="center"><font size="3"  face="Times New Roman"><? echo "Sragen, ".$tgl_sekarang; ?></td>
		</tr>
		<tr>
			<td align="center"><font size="3"  face="Times New Roman"><? echo "Petugas Kasir R. Jalan / R. Inap"; ?></td>
			<td align="center"><font size="3"  face="Times New Roman"><? echo "Petugas Farmasi R. Jalan / R. Inap"; ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align="center"><u><font size="2"  face="Times New Roman"><? echo "(ttd, nama terang)"; ?></u></td>
			<td align="center"><u><font size="2"  face="Times New Roman"><? echo "(ttd, nama terang)"; ?></u></td>
		</tr>
	</table>
	<br />
	<table id="list-pasien" width="100%">
		<tr>
			<td align="left" "><font size="3"  face="Times New Roman"><i><? echo "*Coret yang tidak perlu"; ?></i></td>
		</tr>
		<tr>
			<td align="left" "><font size="3"  face="Times New Roman"><i><? echo "*Dokumen dicetak 2 : 1. Kasir R. Jalan / R. Inap, 2. Farmasi R. Jalan / R. Inap"; ?></i></td>
		</tr>
	</table>
	
<?php
}
?>

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
