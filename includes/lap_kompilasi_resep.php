<?php
/*--------------
 * 2013-04-16
 * Wildan S ST. code
--------------*/

session_start();
$PID = "lap_kompilasi_resep";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/form.php");

$tgl_sekarang = date("d M Y", time());

//--start
if (!$GLOBALS['print']){
    title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > <b>LAPORAN PENJUALAN OBAT PER JENIS</b>");
    title_excel("lap_kompilasi_resep&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."".
        "&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."".
        "&mUNIT=".$_GET["mUNIT"]."&mPASIEN=".$_GET["mPASIEN"]."&mKATEGORY=".$_GET["mKATEGORY"]."&mDOKTER=".$_GET["mDOKTER"]."");
} else {
    title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Penjualan Obat Per Jenis");
    title_excel("lap_kompilasi_resep&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."".
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
(a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text)  ".$addParam."
group by a.tanggal_trans order by a.tanggal_trans");

?>
<table id="list-pasien" width="100%">
    <thead>
        <tr>
            <td rowspan='2' align="CENTER" class="TBL_HEAD" width="10">No.</td>
            <td rowspan='2' align="CENTER" class="TBL_HEAD" width="60">TANGGAL</td>
            <td rowspan='2' align="CENTER" class="TBL_HEAD" width="40">LEMBAR <br> RESEP</td>
            <td rowspan='2' align="CENTER" class="TBL_HEAD" width="40">TOTAL RESEP</td>            
            <td COLSPAN='6' align="CENTER" class="TBL_HEAD" width="40">RINCIAN JENIS RESEP</td>
		</TR>
        <tr>
            <td  align="CENTER" class="TBL_HEAD" width="10">GENERIK</td>
            <td  align="CENTER" class="TBL_HEAD" width="60">NON GENERIK</td>
            <td align="CENTER" class="TBL_HEAD" width="40">FORMULARIUM</td>
            <td align="CENTER" class="TBL_HEAD" width="40">NON FORMULARIUM</td>
            <td align="CENTER" class="TBL_HEAD" width="40">ANTIBIOTIK</td>            
            <td align="CENTER" class="TBL_HEAD" width="40">TIDAK TERLAYANI</td>            
		</TR>
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
				(a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text) ".$addParam."
				group by a.nmr_transaksi";
				$rowslembar =pg_query($con,$lembar);
				while($rowslembarA = pg_fetch_array($rowslembar)) {
				$lmbr[] .= $rowslembarA;
				
				}
				
				$jumlah=count($lmbr);	 
				if ($jumlah != '') {
					$newNoJum = $jumlah - $oldJum;
					
				}
				
				
				
				$NotLayan ="select distinct c.id,
				(select count(b.no_reg) from rs00008 b where c.id=b.no_reg and b.trans_type IN ('OB1','RCK','OB2','OBM','BHP')) AS BELI
				from rs00008 a 
				LEFT JOIN rs00006 c ON c.id::text=a.no_reg::text 
				where a.tanggal_trans ='".$row['tanggal_trans']."'and (select count(b.no_reg) from rs00008 b where a.no_reg=b.no_reg and b.trans_type in ('OB1','RCK','OB2','OBM','BHP')) = '0'
				group by c.id";
				$rowsNotLayan =pg_query($con,$NotLayan);
				while($rowsNotLayan1 = pg_fetch_array($rowsNotLayan)) {
				$layan[] .= $rowsNotLayan1;
				
				}
				 $layan=count($layan);	 
				if ($layan != '') {
					$newLayan = $layan - $oldLayan;
					
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
				(a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text)  ".$addParam."");
		$newGrk =getFromTable("	select COUNT(e.obat) as obat 
				from rs00008 a 
				left join rs00006 b ON a.no_reg = b.id 
				left join rs00002 c ON b.mr_no = c.mr_no 
				left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') 
				left join rs00016 f ON a.item_id = f.obat_id::character varying 
				left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id 
				where 
				e.jenis_id='001' AND
				a.tanggal_trans ='".$row['tanggal_trans']."' and 
				(a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text)  ".$addParam."");
		$newNonGrk =getFromTable("	select COUNT(e.obat) as obat 
				from rs00008 a 
				left join rs00006 b ON a.no_reg = b.id 
				left join rs00002 c ON b.mr_no = c.mr_no 
				left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') 
				left join rs00016 f ON a.item_id = f.obat_id::character varying 
				left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id 
				where 
				e.jenis_id='002' AND
				a.tanggal_trans ='".$row['tanggal_trans']."' and 
				(a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text)  ".$addParam."");
		$newFrmlrm =getFromTable("	select COUNT(e.obat) as obat 
				from rs00008 a 
				left join rs00006 b ON a.no_reg = b.id 
				left join rs00002 c ON b.mr_no = c.mr_no 
				left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') 
				left join rs00016 f ON a.item_id = f.obat_id::character varying 
				left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id 
				where 
				e.tipe_id='001' AND
				a.tanggal_trans ='".$row['tanggal_trans']."' and 
				(a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text)  ".$addParam."");
		$newnnFrmlrm =getFromTable("	select COUNT(e.obat) as obat 
				from rs00008 a 
				left join rs00006 b ON a.no_reg = b.id 
				left join rs00002 c ON b.mr_no = c.mr_no 
				left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') 
				left join rs00016 f ON a.item_id = f.obat_id::character varying 
				left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id 
				where 
				e.tipe_id='002' AND
				a.tanggal_trans ='".$row['tanggal_trans']."' and 
				(a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text)  ".$addParam."");
		$newAntbtk =getFromTable("	select COUNT(e.obat) as obat 
				from rs00008 a 
				left join rs00006 b ON a.no_reg = b.id 
				left join rs00002 c ON b.mr_no = c.mr_no 
				left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') 
				left join rs00016 f ON a.item_id = f.obat_id::character varying 
				left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id 
				where 
				e.antibiotik_id='001' AND
				a.tanggal_trans ='".$row['tanggal_trans']."' and 
				(a.trans_type::text = 'OB1'::text OR a.trans_type::text = 'RCK'::text)  ".$addParam."");
		
	
	$i++;
	?>
            <td align="center"><?php echo $newNoJum;?></td>
            <td align="center"><?php echo $resepA;?></td>
            <td align="center"><?php echo $newGrk;?></td>
            <td align="center"><?php echo $newNonGrk;?></td>
            <td align="center"><?php echo $newFrmlrm;?></td>
            <td align="center"><?php echo $newnnFrmlrm;?></td>
            <td align="center"><?php echo $newAntbtk;?></td>
            <td align="center"><?php echo $newLayan;?></td>
           
			<?php
		$oldJum			= count($lmbr);
		$oldGrk			= count($gnrk);
		$oldNonGrk		= count($nngnrk);
		$oldFrmlrm		= count($frmlrm);
		$oldnnFrmlrm	= count($nnfrmlrm);
		$oldAntbtk		= count($oldAntbtk);
		$oldLayan		= count($oldLayan);
		
		$TotalShift1LR=$TotalShift1LR+$newNoJum;
		$TotalGenerik=$TotalGenerik+$newGrk;
		$TotalNonGenerik=$TotalNonGenerik+$newNonGrk;
		$TotalFormularium=$TotalFormularium+$newFrmlrm;
		$TotalNonFormularium=$TotalNonFormularium+$newnnFrmlrm;
		$TotalAntibiotik=$TotalAntibiotik+$newAntbtk;
		$TotalLayan=$TotalLayan+$newLayan;
		
		$TotalShift1R=$TotalShift1R+$resepA;
		$TotalPersenShift1=($TotalShift1LR/($TotalShift1LR+$TotalLayan))*100;
		$TotPerGen=($TotalGenerik/$TotalShift1R)*100;
		$TotPerNonGen=($TotalNonGenerik/$TotalShift1R)*100;
		$TotPerForm=($TotalFormularium/$TotalShift1R)*100;
		$TotPerNonForm=($TotalNonFormularium/$TotalShift1R)*100;
		$TotPerAntibiotik=($TotalAntibiotik/$TotalShift1R)*100;
		$TotPerNonLayan=($TotalLayan/($TotalShift1LR+$TotalLayan))*100;
		} 
	?>
	
	</tr>
	<tr>
	        <td colspan="2" class="TBL_HEAD" align="right">Jumlah </td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo $TotalShift1LR;?></td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo $TotalShift1R;?></td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo $TotalGenerik;?></td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo $TotalNonGenerik;?></td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo $TotalFormularium;?></td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo $TotalNonFormularium;?></td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo $TotalAntibiotik;?></td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo $TotalLayan;?></td>
	</tr>
	<tr>
	        <td colspan="2" class="TBL_HEAD" align="right">Persentase </td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo number_format($TotalPersenShift1);?>%</td>
	        <td class="TBL_HEAD" align="center" id="jum_obat">&nbsp;</td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo number_format($TotPerGen);?>%</td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo number_format($TotPerNonGen);?>%</td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo number_format($TotPerForm);?>%</td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo number_format($TotPerNonForm);?>%</td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo number_format($TotPerAntibiotik);?>%</td>
	        <td class="TBL_HEAD" align="center" id="jum_obat"><?php echo number_format($TotPerNonLayan);?>%</td>
	</tr>
	</tbody>
</table>
<br>
Catatan: Penghitungan persentase pada Generik, Non Generik, Formularium, Non Formularium, Antibiotik adalah
		<li> Catatan Penghitungan: </li>
		<li> Persentase = Generik / Total Resep  * 100 </li>
		<br>
		<li> Catatan Tidak Terlayani: </li>
		<li> Persentase = Tidak Terlayani / (Total Lembar Resep + Total Tanpa Resep)  * 100 </li>
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
