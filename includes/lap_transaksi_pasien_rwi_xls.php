<?php
session_start();
require_once("../lib/dbconn.php");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=laporan_transakasi_rawat_jalan.xls");
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
$rowsDokter = pg_query($con, "SELECT DISTINCT rs00017.nama AS dokter
                              FROM rs00008 JOIN rs00017 ON rs00008.no_kwitansi = rs00017.id AND rs00017.pangkat LIKE '%DOKTER%' 
                              WHERE trans_type = 'LTM' ORDER BY rs00017.nama ASC");


$addParam = '';
if($_GET['tipe_pasien_id'] != ''){
    $addParam = $addParam." AND rs00001.tc = '".$_GET['tipe_pasien_id']."' ";
}
if($_GET['unit_id'] > 0){
    $addParam = $addParam." AND rs00006.poli = '".$_GET['unit_id']."' ";
}
if($_GET['dokter'] != ''){
    $addParam = $addParam." AND rs00017.nama = '".$_GET['dokter']."' ";
}

$rowsPasien = pg_query($con, "SELECT DISTINCT rs00006.id AS no_reg, 
                                     rs00006.tanggal_reg, 
                                     rs00002.mr_no, 
                                     rs00002.nama, 
                                     A.tdesc AS poli,
                                     (SELECT sum(e.tagihan) AS sum FROM rs00008 e WHERE e.no_reg::text = rs00006.id::text AND e.trans_type::text = 'BHP'::text ) AS bhp,
                                     (SELECT sum(f.tagihan) AS sum FROM rs00008 f WHERE f.no_reg::text = rs00006.id::text AND (f.trans_type::text = 'OB1'::text OR f.trans_type::text = 'RCK'::text) ) AS obat,
                                     (SELECT sum(g.jumlah) AS sum FROM rs00005 g WHERE g.reg::text = rs00006.id::text AND (g.kasir::text = 'BYR'::text OR g.kasir::text = 'BYD'::text) ) AS bayar_tunai,
                                     (SELECT sum(h.jumlah) AS sum FROM rs00005 h WHERE h.reg::text = rs00006.id::text AND h.kasir::text = 'ASK'::text) AS bayar_askes,
                                     (SELECT sum(i.jumlah) AS sum FROM rs00005 i WHERE i.reg::text = rs00006.id::text AND i.kasir::text = 'POT'::text) AS bayar_potongan
                            FROM rs00006
                            JOIN rs00002 ON rs00002.mr_no = rs00006.mr_no
                            JOIN rs00001 ON rs00006.tipe = rs00001.tc AND rs00001.tt = 'JEP' 
                            JOIN rs00001 A ON A.tc = rs00006.poli::text AND A.tt = 'LYN'
                            JOIN rs00008 ON rs00008.no_reg = rs00006.id::text
                            LEFT JOIN rs00017 ON rs00017.id::text = rs00008.no_kwitansi::text
                            WHERE rs00006.status_akhir_pasien <> '012' AND (rs00006.tanggal_reg BETWEEN '".$tglDari."' AND '".$tglSampai."'  ".$addParam." )
                            ORDER BY rs00006.id ASC    
                            ");

$resultSumberPendapatan = pg_query($con,"select tdesc from rs00001 where tt='SBP' and tc !='000' AND tdesc != 'VISITE' AND tdesc != 'ASISTERN'  AND tdesc != 'KONSULTASI' order by tc");
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

<div id="PENJAMIN Laporan Pendaftaran Pasien Rawat Jalan_32053" align=center x:publishsource="Excel">

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
  <td colspan=11 height=20 class=xl6832053 width=1304 style='height:15.0pt; width:978pt'>Laporan Pembayaran Pasien Rawat Jalan</td>
 </tr>
<?php
    if($_GET['tipe_pasien_id'] > 0){
        $rowsTipe = pg_query($con, "SELECT tdesc AS tipe_pasien_nama FROM rs00001 WHERE tt = 'JEP' AND tc = '".$_GET['tipe_pasien_id']."' ORDER BY tdesc ASC");
        $rowTipe  = pg_fetch_array($rowsTipe);
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
  <td colspan=11 height=20 class=xl6832053 width=1304 style='height:15.0pt; width:978pt'><?php echo $rowUnit['poli_nama'];?></td>
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
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td colspan=6 height=24 class=xl6932053 style='height:15.0pt'>RUMAH
  SAKIT &nbsp;: RS SARILA
  HUSADA SRAGEN</td>
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
 </tr>
<?php
$addParam = '';
if($_GET['tipe_pasien_id'] != ''){
    $addParam = $addParam." AND rs00001.tc = '".$_GET['tipe_pasien_id']."' ";
}
if($_GET['unit_id'] != ''){
    $addParam = $addParam." AND rs00006.poli = '".$_GET['unit_id']."' ";
}

$rowsPasien = pg_query($con, "SELECT rs00006.id AS no_reg, 
                                        rs00006.tanggal_reg, 
                                        rs00006.waktu_reg::time(0), 
                                        rs00006.status_akhir_pasien, 
                                        rs00002.mr_no, 
                                        rs00002.nama, 
                                        rs00001.tdesc AS tipe_pasien, 
                                        A.tdesc AS poli,
                                        SUM(rs00008.tagihan) AS tagihan,
                                        SUM(rs00008.dibayar_penjamin) AS penjamin,
                                        (SELECT sum(x.jumlah) AS sum FROM rs00005 x WHERE (kasir = 'BYR' OR kasir = 'BYD') AND x.reg::text = rs00006.id::text) AS bayar 
                            FROM rs00006
                            JOIN rs00002 ON rs00002.mr_no = rs00006.mr_no
                            JOIN rs00001 ON rs00006.tipe = rs00001.tc AND rs00001.tt = 'JEP' 
                            JOIN rs00001 A ON A.tc = rs00006.poli::text AND A.tt = 'LYN'
                            JOIN rs00008 ON rs00008.no_reg = rs00006.id::text
                            WHERE (rs00006.tanggal_reg BETWEEN '".$tglDari."' AND '".$tglSampai."'  ".$addParam." )
                            GROUP BY rs00006.id, rs00006.tanggal_reg, rs00002.mr_no, rs00002.nama, rs00001.tdesc, A.tdesc    
                            ORDER BY rs00006.id ASC    
                            ");
?>
 <tr class=xl6332053 height=40 style='mso-height-source:userset;height:30.0pt'>
            <td align="CENTER" class=xl6932053 style='border:.5pt solid black;' width="30">No.</td>
            <td align="CENTER" class=xl6932053 style='border:.5pt solid black;' width="90">Tgl. Registrasi</td>
            <td align="CENTER" class=xl6932053 style='border:.5pt solid black;' width="90">No. Registrasi</td>
            <td align="CENTER" class=xl6932053 style='border:.5pt solid black;' width="90">No. MR</td>
            <td align="CENTER" class=xl6932053 style='border:.5pt solid black;' width="170">Nama Pasien</td>
            <td align="CENTER" class=xl6932053 style='border:.5pt solid black;' width="">Tipe pasien</td>
            <td align="CENTER" class=xl6932053 style='border:.5pt solid black;' width="170">Unit</td>
            <td align="CENTER" class=xl6932053 style='border:.5pt solid black;'>Tagihan</td>
            <td align="CENTER" class=xl6932053 style='border:.5pt solid black;'>Bayar</td>
            <td align="CENTER" class=xl6932053 style='border:.5pt solid black;'>Penjamin</td>
            <td align="CENTER" class=xl6932053 style='border:.5pt solid black;' width="70">Status</td>
 </tr>
<?php
if($_GET['status'] == ''){        
            if(!empty($rowsPasien)){
                 $i=0;
                 $cekStatus = 'LUNAS';
                 while($row=pg_fetch_array($rowsPasien)){
                     $i++;
//                        $selisih = (int)$row['tagihan']-(int)($row['penjamin']+(int)$row['bayar']);
                        $selisih = (int)$row['tagihan']-(int)($row['penjamin']+(int)$row['bayar']);
                        if((int)$selisih <= 1){
                            $selisihTxt =  'LUNAS';
                        }else{
                            $selisihTxt =  'BELUM LUNAS';
                        }
                        
                        if($row['tagihan'] == 0){
                            $selisihTxt = 'BELUM DIINPUT'; 
                        }
                        
                        if($row['status_akhir_pasien'] == '012'){
                            $selisihTxt = 'RAWAT INAP'; 
                        }
        ?><tr>
            <td class=xl6332053 style='border:.5pt solid black; width:56pt'><?php echo $i?></td>
            <td class=xl6332053 style='border:.5pt solid black; width:56pt' align="right"><?php echo tanggalShort($row['tanggal_reg'])?>&nbsp;<br/><?php echo $row['waktu_reg']?>&nbsp;</td>
            <td class=xl6332053 style='border:.5pt solid black; width:56pt'><?php echo $row['no_reg']?></td>
            <td class=xl6332053 style='border:.5pt solid black; width:56pt'><?php echo $row['mr_no']?></td>
            <td class=xl6332053 style='border:.5pt solid black; width:56pt'><?php echo $row['nama']?></td>
            <td class=xl6332053 style='border:.5pt solid black; width:56pt'><?php echo $row['tipe_pasien']?></td>
            <td class=xl6332053 style='border:.5pt solid black; width:56pt'><?php echo $row['poli']?></td>
            <td class=xl6332053 style='border:.5pt solid black; width:56pt' align="right"><?php echo number_format($row['tagihan'],'0','',',')?></td>
            <td class=xl6332053 style='border:.5pt solid black; width:56pt' align="right"><?php echo number_format($row['bayar'],'0','',',')?></td>
            <td class=xl6332053 style='border:.5pt solid black; width:56pt' align="right"><?php echo number_format($row['penjamin'],'0','',',')?></td>
            <td class=xl6332053 style='border:.5pt solid black; width:56pt' align="left"> &nbsp;<?php echo $selisihTxt?></td>
        </tr>
<?php
                 }
            }
}else{
            if(!empty($rowsPasien)){
                 $n=0;
                 $cekStatus = 'LUNAS';
                 while($row=pg_fetch_array($rowsPasien)){
                     
                        $selisih = (int)$row['tagihan']-(int)($row['penjamin']+(int)$row['bayar']);
                        if((int)$selisih <= 1){
                            $selisihTxt =  'LUNAS';
                        }else{
                            $selisihTxt =  'BELUM LUNAS';
                        }    
                        
                        if($row['tagihan'] == 0){
                            $selisihTxt = 'BELUM DIINPUT'; 
                        }
                        
                        if($row['status_akhir_pasien'] == '012'){
                            $selisihTxt = 'RAWAT INAP'; 
                        }
                        
                        if($_GET['status'] == $selisihTxt){
                            $n++;
?>
        <tr>
            <td style="border-bottom: solid 1px #000;"><?php echo $n?></td>
            <td style="border-bottom: solid 1px #000;" align="right"><?php echo tanggalShort($row['tanggal_reg'])?>&nbsp;<br/><?php echo $row['waktu_reg']?>&nbsp;</td>
            <td style="border-bottom: solid 1px #000;"><a href="index2.php?p=lap_transaksi_pasien&no_reg=<?php echo $row['no_reg']?>"><?php echo $row['no_reg']?></a></td>
            <td style="border-bottom: solid 1px #000;"><?php echo $row['mr_no']?></td>
            <td style="border-bottom: solid 1px #000;"><?php echo $row['nama']?></td>
            <td style="border-bottom: solid 1px #000;"><?php echo $row['tipe_pasien']?></td>
            <td style="border-bottom: solid 1px #000;"><?php echo $row['poli']?></td>
            <td style="border-bottom: solid 1px #000;" align="right"><?php echo number_format($row['tagihan'],'0','',',')?></td>
            <td style="border-bottom: solid 1px #000;" align="right"><?php echo number_format($row['bayar'],'0','',',')?></td>
            <td style="border-bottom: solid 1px #000;" align="right"><?php echo number_format($row['penjamin'],'0','',',')?></td>
            <td style="border-bottom: solid 1px #000;" align="left"> &nbsp;<?php echo $selisihTxt?></td>
        </tr>
<?php        
                        }
                 }
            }
}
?>
 
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
