<?php
require_once("../lib/dbconn.php");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=laporan_pendapatan_rawat_jalan.xls");
echo "$headers\n$data";
header("Content-type: Application/vnd.ms-excel");


$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
$ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));

if (empty($_GET[mPOLI]) == " ")
{
$SQL = "select tgl_entry, reg, mr_no, nama, nm_poli, obat, bayar_tunai, bayar_potongan, bayar_askes
		from rsv_lap_rj 
		where (tgl_entry between '$ts_check_in1' and '$ts_check_in2') and tipe_pasien like '%".$_GET["mPASIEN"]."%' order by reg desc";
} else {
$SQL = "select tgl_entry, reg, mr_no, nama, nm_poli, obat, bayar_tunai, bayar_potongan, bayar_askes
		from rsv_lap_rj 
		where (tgl_entry between '$ts_check_in1' and '$ts_check_in2') and tipe_pasien like '%".$_GET["mPASIEN"]."%' and id_poli::text = '".$_GET["mPOLI"]."'  order by reg desc";
}

$result = pg_query($con,$SQL);

$sqlSumberPendapatan	= "select tdesc from rs00001 where tt='SBP' and tc !='000' order by tc";
$resultSumberPendapatan = pg_query($con,$sqlSumberPendapatan);
while ($rowSumberPendapatan = pg_fetch_array($resultSumberPendapatan)){ 
		$arrSumberPendapatan[] = $rowSumberPendapatan;	
}

?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name=ProgId content=Excel.Sheet>
<meta name=Generator content="Microsoft Excel 12">
<link rel=File-List
href="laporan%20pendapatan%20rawat%20jalan_files/filelist.xml">
<style id="laporan pendapatan rawat jalan_16798_Styles">
<!--table
	{mso-displayed-decimal-separator:"\.";
	mso-displayed-thousand-separator:"\,";}
.xl1516798
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
.xl6316798
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
.xl6416798
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
	vertical-align:middle;
	border:.5pt solid windowtext;
	background:#D8D8D8;
	mso-pattern:black none;
	white-space:nowrap;}
.xl6516798
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
	border:.5pt solid windowtext;
	background:#D8D8D8;
	mso-pattern:black none;
	white-space:nowrap;}
.xl6616798
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
	mso-number-format:"\[$-421\]dd\\ mmmm\\ yyyy\;\@";
	text-align:general;
	vertical-align:bottom;
	border:.5pt solid windowtext;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl6716798
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
	mso-number-format:0;
	text-align:general;
	vertical-align:bottom;
	border:.5pt solid windowtext;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl6816798
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:12.0pt;
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
.xl6916798
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:12.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:0;
	text-align:general;
	vertical-align:bottom;
	border:.5pt solid windowtext;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl7016798
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:12.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:bottom;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:.5pt solid windowtext;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl7116798
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:12.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:bottom;
	border-top:.5pt solid windowtext;
	border-right:none;
	border-bottom:.5pt solid windowtext;
	border-left:none;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl7216798
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:12.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:bottom;
	border-top:.5pt solid windowtext;
	border-right:.5pt solid windowtext;
	border-bottom:.5pt solid windowtext;
	border-left:none;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl7316798
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:12.0pt;
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
.xl7416798
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:14.0pt;
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
-->
</style>
</head>

<body>
<!--[if !excel]>&nbsp;&nbsp;<![endif]-->
<!--The following information was generated by Microsoft Office Excel's Publish
as Web Page wizard.-->
<!--If the same item is republished from Excel, all information between the DIV
tags will be replaced.-->
<!----------------------------->
<!--START OF OUTPUT FROM EXCEL PUBLISH AS WEB PAGE WIZARD -->
<!----------------------------->

<div id="laporan pendapatan rawat jalan_16798" align=center
x:publishsource="Excel">

<table border=0 cellpadding=0 cellspacing=0 width=2985 style='border-collapse:
 collapse;table-layout:fixed;width:2250pt'>
 <col width=35 style='mso-width-source:userset;mso-width-alt:1280;width:26pt'>
 <col width=121 style='mso-width-source:userset;mso-width-alt:4425;width:91pt'>
 <col width=82 span=2 style='mso-width-source:userset;mso-width-alt:2998;
 width:62pt'>
 <col width=167 style='mso-width-source:userset;mso-width-alt:6107;width:125pt'>
 <col width=188 style='mso-width-source:userset;mso-width-alt:6875;width:141pt'>
 <col width=110 span=21 style='mso-width-source:userset;mso-width-alt:4022;
 width:83pt'>
 <tr height=25 style='height:18.75pt'>
  <td colspan=27 height=25 class=xl7416798 width=2985 style='height:18.75pt;
  width:2250pt'>LAPORAN PENDAPATAN RAWAT JALAN</td>
 </tr>
 <tr height=25 style='height:18.75pt'>
  <td colspan=27 height=25 class=xl7416798 style='height:18.75pt'>RUMAH SAKIT
  SITI KHODIJAH<span style='mso-spacerun:yes'> </span></td>
 </tr>
 <tr class=xl6816798 height=21 style='height:15.75pt'>
  <td colspan=27 height=21 class=xl7316798 style='height:15.75pt'><!--12 JANUARI
  2012 S.D 23 JANUARI 2012--></td>
 </tr>
 <tr height=10 style='mso-height-source:userset;height:7.5pt'>
  <td height=10 class=xl1516798 style='height:7.5pt'></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
  <td class=xl1516798></td>
 </tr>
 <tr height=20 style='height:15.0pt'>
  <td rowspan=2 height=40 class=xl6416798 style='height:30.0pt'>No</td>
  <td rowspan=2 class=xl6416798>TANGGAL</td>
  <td rowspan=2 class=xl6416798>NO.REG</td>
  <td rowspan=2 class=xl6416798>NO.MR</td>
  <td rowspan=2 class=xl6416798>NAMA</td>
  <td rowspan=2 class=xl6416798>POLI DAFTAR</td>
  <td colspan=16 class=xl6516798 style='border-left:none'>RINCIAN TAGIHAN<span
  style='mso-spacerun:yes'> </span></td>
  <td rowspan=2 class=xl6416798>TOTAL TAGIHAN</td>
  <td colspan=4 class=xl6516798 style='border-left:none'>PEMBAYARAN</td>
 </tr>
 <tr height=20 style='height:15.0pt'>
	<?php
	foreach($arrSumberPendapatan as $key => $val){ 
		echo "<td class=xl6516798 style='border-top:none;border-left:none'>".$val["tdesc"]."</td>";
	}
	?>
  <td class=xl6516798 style='border-top:none;border-left:none'>BHP</td>
  <td class=xl6516798 style='border-top:none;border-left:none'>OBAT</td>
  <td class=xl6516798 style='border-top:none;border-left:none'>PAKET</td>
  <td class=xl6516798 style='border-top:none;border-left:none'>TUNAI</td>
  <td class=xl6516798 style='border-top:none;border-left:none'>POTONGAN</td>
  <td class=xl6516798 style='border-top:none;border-left:none'>PENJAMIN</td>
  <td class=xl6516798 style='border-top:none;border-left:none'>PIUTANG PASIEN</td>
 </tr>
<?	
$i=0;
while ($row = pg_fetch_array($result)){
	$i++;
?>		
 <tr height=20 style='height:15.0pt'>
  <td height=20 class=xl6316798 align=right style='height:15.0pt;border-top: none'><?=$i?></td>
  <td class=xl6616798 align=right style='border-top:none;border-left:none'><?=$row["tgl_entry"] ?></td>
  <td class=xl6316798 align=right style='border-top:none;border-left:none'><?=$row["reg"] ?></td>
  <td class=xl6316798 align=right style='border-top:none;border-left:none'><?=$row["mr_no"] ?></td>
  <td class=xl6316798 style='border-top:none;border-left:none'><?=$row["nama"] ?></td>
  <td class=xl6316798 style='border-top:none;border-left:none'><?=$row["nm_poli"] ?></td>

	<?php
	$j=0;
	$totalJMLSumberPendapatan=0;
	foreach($arrSumberPendapatan as $key => $val){ 
		$j++;
		$sqlJMLSumberPendapatan = "SELECT sum(a.tagihan) as jumlah 
								FROM rs00008 a
								LEFT JOIN rs00034 b on b.id = a.item_id::numeric
								LEFT JOIN rs00001 c on c.tc = b.sumber_pendapatan_id and c.tt='SBP'  
								WHERE a.no_reg='".$row["reg"]."' AND (a.trans_type='LTM') and upper(c.tdesc) like '%".strtoupper($val["tdesc"])."%'";
		
		$resultJMLSumberPendapatan = pg_query($con,$sqlJMLSumberPendapatan);
		
		while ($rowJMLSumberPendapatan = pg_fetch_array($resultJMLSumberPendapatan)){
			$jumlah = $rowJMLSumberPendapatan["jumlah"];
			$totalJMLSumberPendapatan = $totalJMLSumberPendapatan + $jumlah;
			echo "<td class=xl6716798 align=right style='border-top:none;border-left:none'>".$jumlah."</td>";
		}
		
	}
	$totalPembayaran = $row["bayar_tunai"]+$row["bayar_potongan"]+$row["bayar_askes"];
	?>

  <td class=xl6716798 align=right style='border-top:none;border-left:none'>0</td>
  <td class=xl6716798 align=right style='border-top:none;border-left:none'><?=$row["obat"] ?></td>
  <td class=xl6716798 align=right style='border-top:none;border-left:none'>0</td>
  <td class=xl6716798 align=right style='border-top:none;border-left:none'><?php echo ($totalJMLSumberPendapatan+$row["obat"]) ?></td>
  <td class=xl6716798 align=right style='border-top:none;border-left:none'><?php echo $row["bayar_tunai"]?></td>
  <td class=xl6716798 align=right style='border-top:none;border-left:none'><?php echo $row["bayar_potongan"]?></td>
  <td class=xl6716798 align=right style='border-top:none;border-left:none'><?php echo $row["bayar_askes"]?></td>
  <td class=xl6716798 align=right style='border-top:none;border-left:none'><?php echo ($totalJMLSumberPendapatan+$row["obat"])-$totalPembayaran ?></td>
 </tr>
<?	
}
?>		

 <tr height=21 style='height:15.75pt'>
  <td colspan=6 height=21 class=xl7016798 style='border-right:.5pt solid black;
  height:15.75pt'>JUMLAH</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(G7:G<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(H7:H<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(I7:I<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(J7:J<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(K7:K<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(L7:L<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(M7:M<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(N7:N<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(O7:O<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(P7:P<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(Q7:Q<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(R7:R<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(S7:S<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(T7:T<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(U7:U<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(V7:V<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(W7:W<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(X7:X<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(Y7:Y<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(Z7:Z<?php echo $i+6?>)</td>
  <td class=xl6916798 align=right style='border-top:none;border-left:none'>=SUM(AA7:AA<?php echo $i+6?>)</td>
 </tr>
 <![if supportMisalignedColumns]>
 <tr height=0 style='display:none'>
  <td width=35 style='width:26pt'></td>
  <td width=121 style='width:91pt'></td>
  <td width=82 style='width:62pt'></td>
  <td width=82 style='width:62pt'></td>
  <td width=167 style='width:125pt'></td>
  <td width=188 style='width:141pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
  <td width=110 style='width:83pt'></td>
 </tr>
 <![endif]>
</table>

</div>


<!----------------------------->
<!--END OF OUTPUT FROM EXCEL PUBLISH AS WEB PAGE WIZARD-->
<!----------------------------->
</body>

</html>
