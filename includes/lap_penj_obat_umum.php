<?php
/*--------------
 * 2013-03-07
 * wildan sawaludin code
--------------*/

$PID = "lap_penj_obat_umum";
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
    $f->selectSQL("mKATEGORY", "Kategori Obat",
        "select '' as tc, '' as tdesc union ".
        "select tc, tdesc ".
        "from rs00001  ".
        "where tt='GOB' and tc != '000' order by tdesc", $_GET["mKATEGORY"],
        $ext);
    
    $f->selectSQL("mDOKTER", "Dokter",
        "select '' as nm_dok, '' as nama union ".
        "select dokter as nm_dok,dokter ".
        "from apotik_umum ".
        "order by nm_dok ;", $_GET["mDOKTER"],
        $ext);
	
    $f->submit ("TAMPILKAN");
    $f->execute();
	//--lap sebelum tgl 6 april 2013
	echo '<div align="right">';
		echo '<a href="'.$SC.'?p=lap_penj_obat_umum_lama">';
			echo 'Laporan Sebelum Tanggal 06 April 2013';
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
//-- add param start
$addParam = '';

if($_GET['mKATEGORY'] != ''){
    $addParam = $addParam." AND b.kategori_id = '".$_GET['mKATEGORY']."' ";
}
//-- add param end



$sql = "select distinct a.item_id, b.tdesc as ket
        from rs00008 a
        join rs00001 b on b.tc=a.item_id and b.tt='RAP'
        where a.trans_type in ('OBM') AND (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2')
        and a.item_id like '%".$_GET["mOBM"]."%'
        ";
@$r1 = pg_query($con, $sql);
@$n1 = pg_num_rows($r1);
?>

    <?php
    while (@$row = pg_fetch_array($r1)) {
	$i=0;
	
	if ($oldNo == $row['item_id'] && $oldNo != '') {
						$ii='';
						$jj='';
					 } else {
					 	$ii=$i++;
						$jj=$j++;
					 }
    ?>
    <tr>
        <td align="CENTER" class="TBL_HEAD" width="20" colspan='12'>Nama Relasi : <?php echo $row[ket];?></td>
    </tr>

    <?php
    $sql2 = "select distinct nmr_transaksi
            from rs00008 a
            where a.trans_type in ('OBM') AND (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2')
            and a.item_id = '".$row[item_id]."'
            ";
        @$r2 = pg_query($con, $sql2);
        @$n2 = pg_num_rows($r2);
		
		while (@$row2 = pg_fetch_array($r2)) {
$rowsObatTotal = pg_query($con,
          //"select sum((c.referensi::numeric)) as tuslah, ".
          //"sum((c.qty * c.harga)+(c.referensi::numeric)) as jum ".
          "select ".
          "		sum(CASE WHEN b.kategori_id::numeric=020 THEN (c.qty * 100)
		            ELSE c.referensi::numeric
		       	END) as tuslah, ".
          "sum(c.tagihan) as jum ".
          "from apotik_umum a ".
          "     left join rs00015 b ON a.obat_id = b.id ".
          "     left join rs00008 c ON a.id::character varying = c.id_transaksi ".
          "     left join rs00016 d ON a.obat_id = d.obat_id ".
          "where (a.tanggal_entry between '$ts_check_in1' and '$ts_check_in2') and ".
          "     (c.trans_type::text = 'OB2'::text) ".
          "     AND a.dokter = '".$_GET['mDOKTER']."' ".$addParam."");

$rowsObatGrandTotal = pg_fetch_object($rowsObatTotal);
pg_free_result($rowsObatTotal);

$rowsObat = pg_query($con, "select a.tanggal_entry as tgl, ".
		  "		a.no_reg as no_reg, a.nama as nama, b.obat as obat, a.banyaknya as banyaknya, d.harga::integer as harga_pokok, ".
          "     a.harga as harga_jual, ".
          //"		c.referensi::numeric as harga_tuslah, ".
          "		a.jumlah as harga_total ".
          "from apotik_umum a  ".
          "     left join rs00015 b ON a.obat_id = b.id ".
          "     left join rs00008 c ON a.id::character varying = c.id_transaksi ".
          "     left join rs00016 d ON a.obat_id = d.obat_id ".
          "where ".
          "     (a.tanggal_entry between '$ts_check_in1' and '$ts_check_in2') ".
          "     and (c.trans_type::text = 'OB2'::text) and c.item_id != '' ".
          "     AND a.dokter = '".$_GET['mDOKTER']."' ".$addParam." ".
          "group by tgl, a.no_reg, a.waktu_entry, a.nama, b.obat, a.banyaknya, d.harga, a.harga, ".
          //"c.referensi, ".
          "a.jumlah ".
          "order by a.no_reg, a.waktu_entry");
 
         
//-- jumlah row tuslah obat grand total
$rowsTotalTuslahObats = pg_query($con,
          //"select sum((c.referensi::numeric)) as tuslah, ".
          //"sum((c.qty * c.harga)+(c.referensi::numeric)) as jum ".
          "select ".
          "		sum(CASE WHEN b.kategori_id::numeric=020 THEN (c.qty * 100)
		            ELSE c.referensi::numeric
		       	END) as tuslah, ".
          "sum(c.tagihan) as jum ".
          "from apotik_umum a ".
          "     left join rs00015 b ON a.obat_id = b.id ".
          "     left join rs00008 c ON a.id::character varying = c.id_transaksi ".
          "     left join rs00016 d ON a.obat_id = d.obat_id ".
          "where (a.tanggal_entry between '$ts_check_in1' and '$ts_check_in2') and ".
          "     (c.trans_type::text = 'OB2'::text) and c.item_id != '' ".
          "     ".$addParam." group by a.no_reg ".
          "order by a.no_reg");
          
while($hargaTuslahObat = pg_fetch_array($rowsTotalTuslahObats)) {
	$hargaTuslahObats[]	.= $hargaTuslahObat['tuslah'];
	$hargaJumObats[]	.= $hargaTuslahObat['jum'];
}
//--

?>
 
<table id="list-pasien" width="100%">
    <thead>
        <tr>
            <td align="CENTER" class="TBL_HEAD" width="20">No.</td>
            <td align="CENTER" class="TBL_HEAD" width="70">TANGGAL TRANSAKSI</td>
            <td align="CENTER" class="TBL_HEAD" width="40">NO.REG</td>
            <td align="CENTER" class="TBL_HEAD" width="150">NAMA</td>
            <td align="CENTER" class="TBL_HEAD" width="150">NAMA OBAT</td>
            <td align="CENTER" class="TBL_HEAD" width="40">QTY</td>
            <td align="CENTER" class="TBL_HEAD" width="60">HARGA POKOK</td>
            <td align="CENTER" class="TBL_HEAD" width="60">HARGA JUAL</td>
            <td align="CENTER" class="TBL_HEAD" width="60">TUSLAH</td>
            <td align="CENTER" class="TBL_HEAD" width="60">TOTAL (Rp.)</td>
        </tr>
    </thead>
    <tbody>
        <?php
            if(!empty($rowsObat)){
                 $i=1;
				 $j=0;
                 while($row=pg_fetch_array($rowsObat)){
                 	
					 $newTgl		= $row['tgl'];
					 $newNoReg		= $row['no_reg'];
					 $newNama		= $row['nama'];
					 $hargaTuslah	= 0;
					 $hargaTotal	= 0;
					 
					 if ($oldNoReg == $row['no_reg'] && $oldNoReg != '') {
						$ii='';
						$jj='';
					 } else {
					 	$ii=$i++;
						$jj=$j++;
					 }
					 
					 //if ($oldTgl == $row['tgl'] && $oldTgl != '') {
						//$newTgl = '';
						//$newTgl *ikut $newNoReg
					 //}
					 
					 if ($oldNoReg == $row['no_reg'] && $oldNoReg != '') {
						$newNoReg = '';
						$newTgl = '';
					 }
					 
					 if ($oldNama == $row['nama'] && $oldNama != '') {
						$newNama = '';
					 }
					 
					 if ($ii == '' || $newNoReg == '') {
						$hargaTuslah = '';
						$hargaTotal  = '';
					 } else {
					 	$hargaTuslah = $hargaTuslahObats[$jj];
						$hargaTotal  = $hargaJumObats[$jj];
					 }
					 
					 if ($row['nama'] == "") {
						 $newNama = '-';
					 }
					 
        ?>
        <tr>
            <td align="right"><?php echo $ii?></td>
            <td align="right"><?php echo tanggalShort($newTgl);?>&nbsp;&nbsp;&nbsp;</td>
            <td align="left"><a href="index2.php?p=apotik_umum&no_reg=<?php echo $newNoReg;?>"><?php echo $newNoReg;?></a>&nbsp;&nbsp;&nbsp;</td>
            <td align="left">&nbsp;&nbsp;&nbsp;<?php echo $newNama;?></td>
            <td align="left">&nbsp;&nbsp;&nbsp;<?php echo $row['obat'];?></td>
            <td align="right"><?php echo $row['banyaknya'];?>&nbsp;&nbsp;&nbsp;</td>
            <td align="right"><?php echo number_format($row['harga_pokok'],2);?>&nbsp;&nbsp;&nbsp;</td>
            <td align="right"><?php echo number_format($row['harga_jual'],2);?>&nbsp;&nbsp;&nbsp;</td>
            <td align="right"><?php echo number_format($hargaTuslah,2);?>&nbsp;&nbsp;&nbsp;</td>
            <td align="right"><?php echo number_format($hargaTotal,2);?>&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <?php
        	$oldTgl		= $row['tgl'];
        	$oldNoReg	= $row['no_reg'];
			$oldNama	= $row['nama'];
                 }
            }
			}
        ?>
        <tr>
	        <td colspan="8" class="TBL_HEAD" align="right">T O T A L</td>
	        <td class="TBL_HEAD" align="right" id="jumlah_tuslah"><?php echo number_format($rowsObatGrandTotal->tuslah,2);?></td>
	        <td class="TBL_HEAD" align="right" id="jumlah_total"><?php echo number_format($rowsObatGrandTotal->jum,2);?></td>
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