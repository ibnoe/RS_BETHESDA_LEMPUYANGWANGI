<?php
session_start();
require_once("../lib/dbconn.php");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=laporan_pendapatan_rawat_jalan.xls");
echo "$headers\n$data";
header("Content-type: Application/vnd.ms-excel");
$tglDari    = date('Y-m-d');
$tglSampai  = date('Y-m-d');

if($_GET['tgl_dari'] != ''){
    $tglDari    = $_GET['tgl_dari'];
}

if($_GET['tgl_sampai'] != ''){
    $tglSampai    = $_GET['tgl_sampai'];
}

$rowsTipePasien = pg_query($con, "SELECT tc AS tipe_pasien_id, tdesc AS tipe_pasien_nama FROM rs00001 WHERE tt = 'JEP' ORDER BY tdesc ASC");
$rowsUnit = pg_query($con, "SELECT DISTINCT rs00001.tc AS poli_id, rs00001.tdesc AS poli_nama 
                            FROM rs00006
                            JOIN rs00001 ON rs00001.tc::text = rs00006.poli::text 
                            WHERE rs00001.tt = 'LYN' ORDER BY rs00001.tdesc ASC");
$rowsShiftKerja	= pg_query($con, "SELECT tc_tipe as shift_id, tdesc AS nama_shift FROM rs00001 WHERE tt = 'SHI' AND tc!='000' ORDER BY tdesc ASC");


$addParam = '';
if($_GET['tipe_pasien_id'] != ''){
    $addParam = $addParam." AND rs00001.tc = '".$_GET['tipe_pasien_id']."' ";
}
if($_GET['unit_id'] > 0){
    $addParam = $addParam." AND rs00006.poli = '".$_GET['unit_id']."' ";
}
$shiftId=$_GET['shiftkerja'];
if($_GET['shiftkerja'] != ''){    
    if ($_GET["shiftkerja"]=="P"){
	$jam1="07:00:00";
	$jam2="14:00:00";
	$addParam = $addParam." AND (rs00006.waktu_reg::text between '$jam1' and '$jam2') ";
	}elseif($_GET["shiftkerja"]=="S"){
	$jam1="14:01:00";
	$jam2="21:00:00";
	$addParam = $addParam." AND (rs00006.waktu_reg::text between '$jam1' and '$jam2') ";
	}elseif($_GET["shiftkerja"]=="M1"){
	$jam1="21:01:00";
	$jam2="23:59:00";
	$addParam = $addParam." AND (rs00006.waktu_reg::text between '$jam1' and '$jam2') ";
	}elseif($_GET["shiftkerja"]=="M2"){
	$jam1="00:00:00";
	$jam2="06:59:00";
	$addParam = $addParam." AND (rs00006.waktu_reg::text between '$jam1' and '$jam2') ";
	}else{
	$jam1="00:00:00";
	$jam2="23:59:59";
	$addParam = $addParam." AND (rs00006.waktu_reg between '$jam1' and '$jam2') ";
	}
}
$rowsPasien = pg_query($con, "SELECT DISTINCT rs00006.id AS no_reg, 
                                     rs00006.tanggal_reg, 
                                     rs00002.mr_no, 
                                     rs00002.nama, 
                                     A.tdesc AS poli,
                                     (SELECT sum(e.tagihan) AS sum FROM rs00008 e WHERE e.no_reg::text = rs00006.id::text AND e.trans_type::text = 'BHP'::text ) AS bhp,
                                     (SELECT sum(f.tagihan) AS sum FROM rs00008 f WHERE f.no_reg::text = rs00006.id::text AND (f.trans_type::text = 'OB1'::text OR f.trans_type::text = 'RCK'::text) ) AS obat,
                                     (SELECT sum(g.jumlah) AS sum FROM rs00005 g WHERE g.reg::text = rs00006.id::text AND (g.kasir::text = 'BYR'::text OR g.kasir::text = 'BYD'::text) ) AS bayar_tunai,
                                     (SELECT sum(h.dibayar_penjamin) AS sum FROM rs00005 h WHERE h.reg::text = rs00006.id::text AND (h.kasir::text = 'BYR'::text OR h.kasir::text = 'BYD'::text) ) AS bayar_askes,
                                     (SELECT sum(i.jumlah) AS sum FROM rs00005 i WHERE i.reg::text = rs00006.id::text AND i.kasir::text = 'POT'::text) AS bayar_potongan
                            FROM rs00006
                            JOIN rs00002 ON rs00002.mr_no = rs00006.mr_no
                            JOIN rs00001 ON rs00006.tipe = rs00001.tc AND rs00001.tt = 'JEP' 
                            JOIN rs00001 A ON A.tc = rs00006.poli::text AND A.tt = 'LYN'
                            WHERE rs00006.status_akhir_pasien <> '012' AND (rs00006.tanggal_reg BETWEEN '".$tglDari."' AND '".$tglSampai."'  ".$addParam." )
                            ORDER BY rs00006.id ASC    
                            ");

$resultSumberPendapatan = pg_query($con,"select tdesc from rs00001 where tt='SBP' and tc !='000' AND tdesc not in('VISITE','ASISTERN','ALAT','OPERASI','Lain Lain') order by tc");
while ($rowSumberPendapatan = pg_fetch_array($resultSumberPendapatan)) {
    $arrSumberPendapatan[] = $rowSumberPendapatan;
}
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name=ProgId content=Excel.Sheet>
<meta name=Generator content="Microsoft Excel 14">
<link rel=File-List
href="PENJAMIN%20LAPORAN%20PENDAPATAN%20RAWAT%20JALAN_files/filelist.xml">
<style id="PENJAMIN LAPORAN PENDAPATAN RAWAT JALAN_32053_Styles">
<!--table
	{mso-displayed-decimal-separator:"\.";
	mso-displayed-thousand-separator:"\,";}
.xl1532053
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl6332053
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:middle;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl6432053
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	border:.5pt solid windowtext;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl6532053
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:general;
	vertical-align:middle;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl6632053
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:middle;
	border-top:.5pt solid windowtext;
	border-right:.5pt solid windowtext;
	border-bottom:none;
	border-left:.5pt solid windowtext;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl6732053
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:middle;
	border-top:none;
	border-right:.5pt solid windowtext;
	border-bottom:.5pt solid windowtext;
	border-left:.5pt solid windowtext;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl6832053
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:11.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl6932053
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:11.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:left;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl7032053
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:middle;
	border-top:.5pt solid windowtext;
	border-right:.5pt solid windowtext;
	border-bottom:none;
	border-left:.5pt solid windowtext;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:normal;}
.xl7132053
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:middle;
	border-top:none;
	border-right:.5pt solid windowtext;
	border-bottom:.5pt solid windowtext;
	border-left:.5pt solid windowtext;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:normal;}
.xl7232053
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:middle;
	border:.5pt solid windowtext;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:normal;}
-->
</style>
</head>

<body>
<!--[if !excel]>&nbsp;&nbsp;<![endif]-->
<!--The following information was generated by Microsoft Excel's Publish as Web
Page wizard.-->
<!--If the same item is republished from Excel, all information between the DIV
tags will be replaced.-->
<!----------------------------->
<!--START OF OUTPUT FROM EXCEL PUBLISH AS WEB PAGE WIZARD -->
<!----------------------------->

<div id="PENJAMIN LAPORAN PENDAPATAN RAWAT JALAN_32053" align=center x:publishsource="Excel">

<table border=0 cellpadding=0 cellspacing=0 width=1304 style='border-collapse:
 collapse;table-layout:fixed;width:978pt'>
 <col width=64 span=3 style='width:48pt'>
 <col width=74 style='mso-width-source:userset;mso-width-alt:2706;width:56pt'>
 <col width=64 span=2 style='width:48pt'>
 <col width=79 style='mso-width-source:userset;mso-width-alt:2889;width:59pt'>
 <col width=87 style='mso-width-source:userset;mso-width-alt:3181;width:65pt'>
 <col width=110 style='mso-width-source:userset;mso-width-alt:4022;width:83pt'>
 <col width=64 span=2 style='width:48pt'>
 <col width=99 style='mso-width-source:userset;mso-width-alt:3620;width:74pt'>
 <col width=65 style='mso-width-source:userset;mso-width-alt:2377;width:49pt'>
 <col width=65 style='mso-width-source:userset;mso-width-alt:2377;width:49pt'>
 <col width=64 span=3 style='width:48pt'>
 <col width=75 span=2 style='mso-width-source:userset;mso-width-alt:2742; width:56pt'>
 <tr height=20 style='height:15.0pt'>
  <td colspan=23 height=20 class=xl6832053 width=1304 style='height:15.0pt; width:978pt'>LAPORAN PENDATAN RAWAT JALAN</td>
 </tr>
<?php
    if($_GET['tipe_pasien_id'] > 0){
        $rowsTipe = pg_query($con, "SELECT tdesc AS tipe_pasien_nama FROM rs00001 WHERE tt = 'JEP' AND tc = '".$_GET['tipe_pasien_id']."' ORDER BY tdesc ASC");
        $rowTipe  = pg_fetch_array($rowsTipe);
?>     
 <tr height=20 style='height:15.0pt'>
  <td colspan=22 height=20 class=xl6832053 width=1304 style='height:15.0pt; width:978pt'><?php echo $rowTipe['tipe_pasien_nama'];?></td>
 </tr>
<?php
    }
?>     
<?php
    if($_GET['unit_id'] > 0){
        $rowsUnit = pg_query($con, "SELECT DISTINCT rs00001.tdesc AS poli_nama 
                            FROM rs00006
                            JOIN rs00001 ON rs00001.tc::text = rs00006.poli::text 
                            WHERE rs00001.tt = 'LYN' AND rs00001.tc = '".$_GET['unit_id']."' ORDER BY rs00001.tdesc ASC");
        $rowUnit  = pg_fetch_array($rowsUnit);
?>     
 <tr height=20 style='height:15.0pt'>
  <td colspan=23 height=20 class=xl6832053 width=1304 style='height:15.0pt; width:978pt'><?php echo $rowUnit['poli_nama'];?></td>
 </tr>
<?php
    }
?>     
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl1532053 style='height:15.0pt'></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td colspan=6 height=24 class=xl6932053 style='height:15.0pt'>RUMAH
  SAKIT &nbsp;: RUMAH SAKIT HOSANA MEDICA</td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td colspan=6 height=20 class=xl6932053 style='height:15.0pt'>
  PELAYANAN &nbsp;&nbsp;&nbsp;&nbsp; : <?php echo tanggal($tglDari) .' s.d '. tanggal($tglSampai)  ?></td>

  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
  <td class=xl1532053></td>
 </tr>

 <tr class=xl6332053 height=40 style='mso-height-source:userset;height:30.0pt'>
  <td rowspan=2 height=60 class=xl6632053 style='border-bottom:.5pt solid black; height:45.0pt'>NO</td>
  <td rowspan=2 class=xl6632053 width=80 style='border-bottom:.5pt solid black'>TANGGAL</td>
  <td rowspan=2 class=xl6632053 width=280 style='border-bottom:.5pt solid black'>NAMA</td>
  <td rowspan=2 class=xl7032053 width=50 style='border-bottom:.5pt solid black; width:56pt'>NO REGISTER</td>
  <td rowspan=2 class=xl6632053 width=200 style='border-bottom:.5pt solid black'>DOKTER</td>
            <?php
            foreach ($arrSumberPendapatan as $key => $val) {
                    echo "<td rowspan=2 class=xl6632053 style='border-bottom:.5pt solid black'>".$val["tdesc"]."</td>";
            }
            ?>
  <td rowspan=2 class=xl6632053 style='border-bottom:.5pt solid black'>BHP</td>
  <td rowspan=2 class=xl6632053 style='border-bottom:.5pt solid black'>OBAT</td>
  <td rowspan=2 class=xl7232053 width=64 style='width:48pt'>TOTAL TAGIHAN</td>
  <td rowspan=2 class=xl7232053 width=64 style='width:48pt'>TUNAI</td>
  <!--td rowspan=2 class=xl6632053 style='border-bottom:.5pt solid black'>POTONGAN</td-->
  <td rowspan=2 class=xl6632053 style='border-bottom:.5pt solid black'>PENJAMIN</td>
 </tr>
 <tr height=20 style='height:15.0pt'>
 </tr>

     <?php
            if(!empty($rowsPasien)){
                 $i=0;
				 $total[] = 0;
                 while($row=pg_fetch_array($rowsPasien)){
                     if($row["bayar_tunai"] > 0 || $row["bayar_potongan"] > 0  || $row["bayar_askes"] > 0 ){
                     $i++;
				     $totalJMLSumberPendapatan = 0;

                     $sqlDokter     = pg_query($con,"SELECT rs00017.nama 
                                                     FROM rs00008 JOIN rs00017 ON rs00008.no_kwitansi = rs00017.id AND rs00017.pangkat LIKE '%DOKTER%' 
                                                     WHERE trans_type = 'LTM' AND no_reg = '".$row['no_reg']."'");
        ?>
        <tr>
            <td class=xl6432053 style='border:.5pt solid black'><?php echo $i?></td>
            <td class=xl6432053 style='border:.5pt solid black'><?php echo tanggalShort($row['tanggal_reg'])?>&nbsp; </td>
            <td class=xl6432053 style='border:.5pt solid black'><?php echo $row['nama']?></td>
            <td class=xl6432053 style='border:.5pt solid black'> <?php echo $row['no_reg']?></td>
            <td style='border:.5pt solid black'><?php 
                                    while($rowDokter=pg_fetch_array($sqlDokter)){
                                        echo $rowDokter['nama'].'<br/>';
                                    }
                                ?></td>
        
            <?php
    $j = 0;
	foreach ($arrSumberPendapatan as $key => $val) {
        $j++;
        $sqlJMLSumberPendapatan = "SELECT sum(a.tagihan) as jumlah 
                                FROM rs00008 a
                                LEFT JOIN rs00034 b on b.id = a.item_id::numeric
                                LEFT JOIN rs00001 c on c.tc = b.sumber_pendapatan_id and c.tt='SBP'  
                                WHERE a.no_reg='" . $row["no_reg"] . "' AND (a.trans_type='LTM') and upper(c.tdesc) like '%" . strtoupper($val["tdesc"]) . "%'";

        $resultJMLSumberPendapatan = pg_query($con,
                $sqlJMLSumberPendapatan);
		
        while ($rowJMLSumberPendapatan = pg_fetch_array($resultJMLSumberPendapatan)) {
            $jumlah = (int) $rowJMLSumberPendapatan["jumlah"];
            $totalJMLSumberPendapatan = $totalJMLSumberPendapatan + $jumlah;
			$total[$j] = $total[$j] + $jumlah;
            echo '<td class=xl6432053 style=border:.5pt solid black id="val_' . $i . '_' . $j . '">';
            echo $jumlah;
            echo '</td>';
        }
    }
    $totalPembayaran = $row["bayar_tunai"] + $row["bayar_potongan"] + $row["bayar_askes"];
    ?>
            <td class=xl6432053 style='border:.5pt solid black'><?php echo (int)$row["bhp"]?>  </td>
            <td class=xl6432053 style='border:.5pt solid black' id="val_obat_<?php echo $i ?>"><?php echo $row["obat"] ?> </td>
            <td class=xl6432053 style='border:.5pt solid black' id="val_tagihan_<?php echo $i ?>"><?php echo $totalJMLSumberPendapatan + $row["obat"] ?> </td>
            <td class=xl6432053 style='border:.5pt solid black' id="val_tunai_<?php echo $i ?>"><?php echo  $row["bayar_tunai"] ?> </td>
            <!--td class=xl6432053 style='border:.5pt solid black' id="val_potongan_<?php echo $i ?>"><?php echo  $row["bayar_potongan"] ?> </td-->
            <td class=xl6432053 style='border:.5pt solid black' id="val_askes_<?php echo $i ?>"><?php echo  $row["bayar_askes"] ?> </td>
            </tr>
        <?php
				 $totalBayarBHP = $totalBayarBHP + $row["bhp"];
				 $totalBayarObat = $totalBayarObat + $row["obat"];
                 $totalBayarPend = $totalBayarPend + ($totalJMLSumberPendapatan+ $row["obat"]);
                 $totalBayarTunai = $totalBayarTunai + $row["bayar_tunai"];
                 $totalBayarPotong = $totalBayarPotong + $row["bayar_potongan"];
                 $totalBayarAskes = $totalBayarAskes + $row["bayar_askes"];
                     }
				
				 }
            }
        ?>
  <tr class=xl6332053 height=20 style='mso-height-source:userset;height:20.0pt'>
  <td colspan=5 class=xl6632053 width=200 style='border-bottom:.5pt solid black'>TOTAL</td>
  <td class=xl6632053 style='border-bottom:.5pt solid black'><?php echo $total[1]; ?></td>
  <td class=xl6632053 style='border-bottom:.5pt solid black'><?php echo $total[2]; ?></td>
  <td class=xl6632053 style='border-bottom:.5pt solid black'><?php echo $total[3]; ?></td>
  <td class=xl6632053 style='border-bottom:.5pt solid black'><?php echo $total[4]; ?></td>
  <td class=xl6632053 style='border-bottom:.5pt solid black'><?php echo $total[5]; ?></td>
  <td class=xl6632053 style='border-bottom:.5pt solid black'><?php echo $total[6]; ?></td>
  <td class=xl6632053 style='border-bottom:.5pt solid black'><?php echo $total[7]; ?></td>
  <td class=xl6632053 style='border-bottom:.5pt solid black'><?php echo $total[8]; ?></td>
  <td class=xl6632053 style='border-bottom:.5pt solid black'><?php echo $total[9]; ?></td>
  <td class=xl6632053 style='border-bottom:.5pt solid black'><?php echo $totalBayarBHP; ?></td>
  <td class=xl6632053 style='border-bottom:.5pt solid black'><?php echo $totalBayarObat; ?></td>
  <td class=xl6632053 style='border-bottom:.5pt solid black'><?php echo $totalBayarPend; ?></td>
  <td class=xl6632053 style='border-bottom:.5pt solid black'><?php echo number_format($totalBayarTunai,0,".",","); ?></td>
  <!--td class=xl6632053 style='border-bottom:.5pt solid black'><?php echo number_format($totalBayarPotong,0,".",","); ?></td-->
  <td class=xl6632053 style='border-bottom:.5pt solid black'><?php echo number_format($totalBayarAskes,0,".",","); ?></td>
 </tr>
 
    <tr>
 
 <![if supportMisalignedColumns]>
 <tr height=0 style='display:none'>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=74 style='width:56pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=79 style='width:59pt'></td>
  <td width=87 style='width:65pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=99 style='width:74pt'></td>
  <td width=65 style='width:49pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=75 style='width:56pt'></td>
  <td width=75 style='width:56pt'></td>
 </tr>
 <![endif]>
</table>

</div>


<!----------------------------->
<!--END OF OUTPUT FROM EXCEL PUBLISH AS WEB PAGE WIZARD-->
<!----------------------------->
</body>

</html>


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
