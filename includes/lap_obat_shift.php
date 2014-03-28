<?php
/*--------------
 * 2013-04-16
 * Wildan S ST. code
--------------*/

session_start();
$PID = "lap_obat_shift";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/form.php");

$tgl_sekarang = date("d M Y", time());

//--start
if (!$GLOBALS['print']){
    title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > <b>LAPORAN PENJUALAN OBAT PER SHIFT</b>");
    title_excel("lap_obat_shift&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."".
        "&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."".
        "&mUNIT=".$_GET["mUNIT"]."&mPASIEN=".$_GET["mPASIEN"]."&mKATEGORY=".$_GET["mKATEGORY"]."&mDOKTER=".$_GET["mDOKTER"]."");
} else {
    title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Penjualan Obat Per Shift");
    title_excel("lap_obat_shift&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."".
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
$addParam = '';
if($_GET['mUNIT'] != ''){
    $addParam = $addParam." AND b.rawat_inap = '".$_GET['mUNIT']."' ";
}
?>

<SCRIPT language="JavaScript" src="plugin/jquery-1.8.2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="plugin/jquery-ui.js"></SCRIPT>
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.theme.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.datepicker.css">

<?php
//start apotik klinik
//-------------------------------------------------------------------------------------------------------------------
$f1 = new Form("");

$tanggal= pg_query($con," select distinct a.tanggal_trans
from rs00008 a 
left join rs00006 b ON a.no_reg = b.id 
where 
(a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and 
(a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) and 
(b.waktu_reg between '00:00:00' and '23:59:59') ".$addParam."
group by a.tanggal_trans order by a.tanggal_trans");

?>
<table id="list-pasien" width="100%">
    <thead>
        <tr>
            <td rowspan='2' align="CENTER" class="TBL_HEAD" width="10">No.</td>
            <td rowspan='2' align="CENTER" class="TBL_HEAD" width="60">TANGGAL</td>
            <td COLSPAN='2' align="CENTER" class="TBL_HEAD" width="40">SHIFT PAGI</td>
            <td COLSPAN='2' align="CENTER" class="TBL_HEAD" width="40">SHIFT SIANG</td>
            <td COLSPAN='2' align="CENTER" class="TBL_HEAD" width="40">SHIFT MALAM</td>
		</TR>
		<TR>
            <td align="CENTER" class="TBL_HEAD" width="40">LEMBAR <br> RESEP</td>
            <td align="CENTER" class="TBL_HEAD" width="40">RESEP</td>            
			<td align="CENTER" class="TBL_HEAD" width="40">LEMBAR <br> RESEP</td>
            <td align="CENTER" class="TBL_HEAD" width="40">RESEP</td>            
			<td align="CENTER" class="TBL_HEAD" width="40">LEMBAR <br> RESEP</td>
            <td align="CENTER" class="TBL_HEAD" width="40">RESEP</td>
        </tr>
    </thead>
    <tbody>
	<TR>
	 <?php
	 /*
	 $a= date("Y");
	 $b= date("m");
	 $c= date("d");
	 
	 $d= date("Y");
	 $e= date("m");
	 $f= date("d");
	 
	 
	 $ab = $a.'-'.$b.'-'.$c;
	 $bc = $d.'-'.$e.'-'.$f;
	 
	 $fg = $c+1;
	 
	 echo $fg;
	 */
                 $i=1;
				 $j=0;
                 while($row=pg_fetch_array($tanggal)){
                 	
					 
					
						$newTgl = $row['tanggal_trans'];
						
				$lembar ="	select distinct nmr_transaksi
				from rs00008 a 
				left join rs00006 b ON a.no_reg = b.id 
				where a.tanggal_trans ='".$row['tanggal_trans']."' and 
				(a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) and 
				(b.waktu_reg between '07:01:00' and '14:00:00') ".$addParam."
				group by a.nmr_transaksi";
				$rowslembar =pg_query($con,$lembar);
				while($rowslembarA = pg_fetch_array($rowslembar)) {
				$ss[] .= $rowslembarA;
				
				}
	
				$jumlah=count($ss);	 
				if ($jumlah != '') {
					$newNoJum = $jumlah - $oldJum;
					
				}
				
				$lembar2 ="	select distinct nmr_transaksi
				from rs00008 a 
				left join rs00006 b ON a.no_reg = b.id 
				where a.tanggal_trans ='".$row['tanggal_trans']."' and 
				(a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) and 
				(b.waktu_reg between '14:01:00' and '21:00:00') ".$addParam."
				group by a.nmr_transaksi";
				$rowslembar2 =pg_query($con,$lembar2);
				while($rowslembarA2 = pg_fetch_array($rowslembar2)) {
				$ss2[] .= $rowslembarA2;
				
				}
	
				$jumlah2=count($ss2);	 
				if ($jumlah2 != '') {
					$newNoJum2 = $jumlah2 - $oldJum2;
					
				}
				
				$lembar3 ="	select distinct nmr_transaksi
				from rs00008 a 
				left join rs00006 b ON a.no_reg = b.id 
				where a.tanggal_trans ='".$row['tanggal_trans']."' and 
				(a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) and 
				(b.waktu_reg between '21:01:00' and '23:59:00') ".$addParam."
				group by a.nmr_transaksi";
				$rowslembar3 =pg_query($con,$lembar3);
				while($rowslembarA3 = pg_fetch_array($rowslembar3)) {
				$ss3[] .= $rowslembarA3;
				
				}
	
				$jumlah3=count($ss3);	 
				if ($jumlah3 != '') {
					$newNoJum3 = $jumlah3 - $oldJum3;
					
				}
				$lembar4 ="	select distinct nmr_transaksi
				from rs00008 a 
				left join rs00006 b ON a.no_reg = b.id 
				where a.tanggal_trans ='".$row['tanggal_trans']."' and 
				(a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) and 
				(b.waktu_reg between '00:00:00' and '07:00:00') ".$addParam."
				group by a.nmr_transaksi";
				$rowslembar4 =pg_query($con,$lembar4);
				while($rowslembarA4 = pg_fetch_array($rowslembar4)) {
				$ss4[] .= $rowslembarA4;
				
				}
	
				$jumlah4=count($ss4);	 
				if ($jumlah4 != '') {
					$newNoJum4 = $jumlah4 - $oldJum4;
					
				}
				 
				
		 ?>
        <tr>
            <td align="center"><?php echo $i?></td>
            <td align="center"><?php echo tanggalShort($newTgl);?>&nbsp;&nbsp;&nbsp;</td>
	<?php
		$resepA =getFromTable("	select COUNT(e.obat) as obat 
				from rs00008 a 
				left join rs00006 b ON a.no_reg = b.id 
				left join rs00002 c ON b.mr_no = c.mr_no 
				left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') 
				left join rs00016 f ON a.item_id = f.obat_id::character varying 
				left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id 
				where 
				a.tanggal_trans ='".$row['tanggal_trans']."' and 
				(a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) and 
				(b.waktu_reg between '07:01:00' and '14:00:00') ".$addParam."");
				
		$resepB =getFromTable("	select COUNT(e.obat) as obat 
				from rs00008 a 
				left join rs00006 b ON a.no_reg = b.id 
				left join rs00002 c ON b.mr_no = c.mr_no 
				left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') 
				left join rs00016 f ON a.item_id = f.obat_id::character varying 
				left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id 
				where 
				a.tanggal_trans ='".$row['tanggal_trans']."' and 
				(a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) and 
				(b.waktu_reg between '14:01:00' and '21:00:00') ".$addParam."");
		
		
		$resepC =getFromTable("	select COUNT(e.obat) as obat 
				from rs00008 a 
				left join rs00006 b ON a.no_reg = b.id 
				left join rs00002 c ON b.mr_no = c.mr_no 
				left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') 
				left join rs00016 f ON a.item_id = f.obat_id::character varying 
				left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id 
				where 
				a.tanggal_trans = '".$row['tanggal_trans']."'  and 
				(a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) and 
				(b.waktu_reg between '21:01:00' and '23:59:00') ".$addParam."");
		$a=date("d", strtotime($row['tanggal_trans']));
		$f=$a+1;
		$b=date("m", strtotime($row['tanggal_trans']));
		$c=date("Y", strtotime($row['tanggal_trans']));
		$resepD =getFromTable("	select COUNT(e.obat) as obat 
				from rs00008 a 
				left join rs00006 b ON a.no_reg = b.id 
				left join rs00002 c ON b.mr_no = c.mr_no 
				left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') 
				left join rs00016 f ON a.item_id = f.obat_id::character varying 
				left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id 
				where 
				a.tanggal_trans ='".$c.'-'.$b.'-'.$f."' and 
				(a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) and 
				(b.waktu_reg between '00:00:00' and '07:00:00') ".$addParam."");
		$resepCD=$resepC+$resepD;
		$newNoJum5=$newNoJum3+$newNoJum4;
	
	$i++;
	?>
            <td align="center"><?php echo $newNoJum;?></td>
            <td align="center"><?php echo $resepA;?></td>
            <td align="center"><?php echo $newNoJum2;?></td>
            <td align="center"><?php echo $resepB;?></td>
            <td align="center"><?php echo $newNoJum5;?></td>
            <td align="center"><?php echo $resepCD;?></td>
			<?php
		$oldJum		= count($ss);
		$oldJum2	= count($ss2);
		$oldJum3	= count($ss3);
		$oldJum4	= count($ss4);
		
		$TotalShift1LR=$TotalShift1LR+$newNoJum;
		$TotalShift2LR=$TotalShift2LR+$newNoJum2;
		$TotalShift3LR=$TotalShift3LR+$newNoJum5;
		$TotalPersenShift1=($TotalShift1LR/($TotalShift1LR+$TotalShift2LR+$TotalShift3LR))*100;
		$TotalPersenShift2=($TotalShift2LR/($TotalShift1LR+$TotalShift2LR+$TotalShift3LR))*100;
		$TotalPersenShift3=($TotalShift3LR/($TotalShift1LR+$TotalShift2LR+$TotalShift3LR))*100;
		
		$TotalShift1R=$TotalShift1R+$resepA;
		$TotalShift2R=$TotalShift2R+$resepB;
		$TotalShift3R=$TotalShift3R+$resepCD;
		$TotalPersenShift1R=($TotalShift1R/($TotalShift1R+$TotalShift2R+$TotalShift3R))*100;
		$TotalPersenShift2R=($TotalShift2R/($TotalShift1R+$TotalShift2R+$TotalShift3R))*100;
		$TotalPersenShift3R=($TotalShift3R/($TotalShift1R+$TotalShift2R+$TotalShift3R))*100;
		
		
		} 
	?>
	
	</tr>
	<tr>
	        <td colspan="2" class="TBL_HEAD" align="right">Jumlah </td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo $TotalShift1LR;?></td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo $TotalShift1R;?></td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo $TotalShift2LR;?></td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo $TotalShift2R;?></td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo $TotalShift3LR;?></td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo $TotalShift3R;?></td>
	</tr>
	<tr>
	        <td colspan="2" class="TBL_HEAD" align="right">Persentase </td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo number_format($TotalPersenShift1);?>%</td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo number_format($TotalPersenShift1R);?>%</td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo number_format($TotalPersenShift2);?>%</td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo number_format($TotalPersenShift2R);?>%</td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo number_format($TotalPersenShift3);?>%</td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo number_format($TotalPersenShift3R);?>%</td>
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
            $bln = "Februari";
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
