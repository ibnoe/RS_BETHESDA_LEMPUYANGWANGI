<?php
session_start();
require_once("../lib/dbconn.php");
require_once("../lib/setting.php");
require_once("../lib/terbilang.php");

$tgl_sekarang = date("d M Y", time());

$sqlPasien  = pg_query($con, "SELECT DISTINCT rs00002.mr_no, rs00002.nama, rs00002.alm_tetap, kota_tetap, rs00001.tdesc, i.tdesc as poli FROM rs00006
                              JOIN rs00002  ON rs00006.mr_no = rs00002.mr_no
                              JOIN rs00001  ON rs00006.tipe = rs00001.tc AND rs00001.tt = 'JEP' 
                              left join rs00001 i on i.tc_poli = rs00006.poli
                              WHERE id = '".$_GET['no_reg']."'");
$pasien     = pg_fetch_object($sqlPasien);
?>    
<HTML>
    <HEAD>
        <!--<TITLE>Rincian Pelayanan Laboratorium</TITLE>-->
        <TITLE></TITLE>
        <LINK rel='styleSheet' type='text/css' href='../cetak.css'>
        <LINK rel='styleSheet' type='text/css' href='../invoice.css'>
        <SCRIPT LANGUAGE="JavaScript">
            <!-- Begin
            function printWindow() {
                bV = parseInt(navigator.appVersion);
                if (bV >= 4) window.print();
            }
            //  End -->
        </script>
    </HEAD>

<BODY TOPMARGIN=0 LEFTMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0 />

<!--START KOP KWITANSI -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Times New Roman; font-size: 12px; letter-spacing: 2px;">
	<tr valign="middle" >
		<td rowspan="2" align="center"><!--<img width="70px" height="70px" src="../images/logo_kotakab_sragen.png" align="left"/>-->
		<font color=white>
			<div style="font-family: Times New Roman; font-size: 10px; color: #000; padding-left: 8px; padding-right: 8px;">&nbsp</div>
		    <div style="font-family: Times New Roman; font-size: 12px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold">
<?=$set_header[0]?>
</div>
			<div style="font-family: Times New Roman; font-size: 12px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold">Jl. <?=$set_header[2]?></div>
			<div style="font-family: Times New Roman; font-size: 12px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold">Telp. <?=$set_header[3]?></div>
		</font>
	</tr>			
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Times New Roman; font-size: 1px; letter-spacing: 2px;">
    <tr>
        <td align="left" style='border-top:solid 0px #000;border-bottom:solid 2px #000;'>&nbsp;</td>
    </tr>
</table>
	<!--END KOP KWITANSI -->

<table align=center >
    <tr>
        <td align="center" colspan="4" style="font-family: Times New Roman; font-size: 14px; letter-spacing: 0px; font-weight: bold"><b>R&nbsp;I&nbsp;N&nbsp;C&nbsp;I&nbsp;A&nbsp;N&nbsp;&nbsp; P&nbsp;E&nbsp;L&nbsp;A&nbsp;Y&nbsp;A&nbsp;N&nbsp;A&nbsp;N &nbsp;&nbsp;L&nbsp;A&nbsp;B&nbsp;O&nbsp;R&nbsp;A&nbsp;T&nbsp;O&nbsp;R&nbsp;I&nbsp;U&nbsp;M&nbsp;</b></u></td>
    </tr>
</table>
<br/>
<table border ="0" align=left cellpadding="0" cellspacing="0" style="font-family: Times New Roman; font-size: 12px; letter-spacing: 4px;" width="100%">
    <tr>
        <td width="200"><font size="1"  face="Times New Roman">No. Registrasi</font></td>
        <td><font size="1"  face="Times New Roman">: <? echo $_GET['no_reg']; ?></font></td>
        <td><font size="1"  face="Times New Roman"></font></td>
        <td align="right"><font size="1"  face="Times New Roman"></font></td>
    </tr>
        <tr>
        <td width="200"><font size="1"  face="Times New Roman">No. RM</font></td>
        <td><font size="1"  face="Times New Roman">: <? echo $pasien->mr_no; ?></font></td>
        <td><font size="1"  face="Times New Roman"></font></td>
        <td align="right"><font size="1"  face="Times New Roman"></font></td>
    </tr>
    <tr>
        <td><font size="1"  face="Times New Roman">Nama Pasien</font></td>
        <td width="40%"><font size="1"  face="Times New Roman">: <? echo $pasien->nama; ?></font></td>
        <td align="right" colspan="2"><font size="1"  face="Times New Roman"></font></td>
    </tr>
    <tr>
        <td><font size="1"  face="Times New Roman">Alamat</font></td>
        <td colspan="3"><font size="1"  face="Times New Roman">: <? echo $pasien->alm_tetap . " " . $pasien->kota_tetap; ?></font></td>
    </tr>
        <tr>
        <td><font size="1"  face="Times New Roman">Tipe Pasien</font></td>
        <td colspan="3"><font size="1"  face="Times New Roman">: <? echo $pasien->tdesc; ?></font></td>
    </tr>
</table>    
<br/>&nbsp;
        <?
            $maxI = $_GET['max_cetak'];
            $arrSelectedCetak = array();
//            $addParams = ' AND (';
            for($i=0;$i<=$maxI;$i++){
                if($_GET['cetak_'.$i] > 0){
                    $selectedId = $_GET['cetak_'.$i];
                    $arrSelectedCetak[$selectedId] = 'true';
//                    $addParams = $addParams. ' f.id = '.$_GET['cetak_'.$i]. ' OR';
                }
            }
//            $addParams = substr($addParams,0,-2).')';
            
        $sql = pg_query($con,"select distinct  f.trans_form,f.id,f.item_id, a.layanan, f.referensi, 
				f.qty ||' '|| g.tdesc as qty, f.tagihan,  to_char(f.tanggal_trans,'dd-mm-yyyy') as tanggal_trans, f.trans_group,
				f.is_bayar, h.nama 
				from rs00034 a 
				left join rs00008 f on to_number(f.item_id,'999999999999') = a.id and f.trans_type = 'LTM' and f.referensi != 'P'
				left join rs00001 g on a.satuan_id = g.tc and g.tt = 'SAT' 
				left join rs00017 h on f.no_kwitansi::numeric = h.id::numeric
				where f.trans_form = 'p_laboratorium' AND f.no_reg = '".$_GET[no_reg]."'  ".$addParams." 
				order by  tanggal_trans desc");
        
        echo ' <table width="100%"  cellpadding="0" cellspacing="0" style="font-family: Times New Roman; font-size: 14px; letter-spacing: 2px;">';
        echo "<thead>";
        echo "<th style='border-top:solid 1px #000;border-bottom:solid 1px #000;'><font size=1 face=Times New Roman>TANGGAL</font></th>";
        echo "<th style='border-top:solid 1px #000;border-bottom:solid 1px #000;'><font size=1 face=Times New Roman>DESCRIPTION</font></th>";
        echo "<th style='border-top:solid 1px #000;border-bottom:solid 1px #000;'><font size=1 face=Times New Roman>ANALIS</font></th>";
        echo "<th style='border-top:solid 1px #000;border-bottom:solid 1px #000;'><font size=1 face=Times New Roman>JUMLAH</font></th>";
        echo "<th style='border-top:solid 1px #000;border-bottom:solid 1px #000;'><font size=1 face=Times New Roman>TAGIHAN</font></th>";
        echo "</thead>";
    
        $total = 0;
        while ($row = pg_fetch_array($sql)){ 
            if(isset($arrSelectedCetak[$row['id']])){
                $tagihan = $row['tagihan'];
                $total = $total + $tagihan;
                echo "<tr>";
                echo "<td><font size=1 face=Times New Roman> ".$row['tanggal_trans']."<font</td>";
                echo "<td><font size=1 face=Times New Roman> ".$row['layanan']."<font</td>";
                echo "<td><font size=1 face=Times New Roman> ".$row['nama']."<font</td>";
                echo "<td align='right'><font size=1 face=Times New Roman>".$row['qty']."<font</td>";
                echo "<td align='right'><font size=1 face=Times New Roman>".number_format($tagihan,'0', '', '.')."<font</td>";
                echo "</tr>";
            }
        }
            echo "<tr>";
            echo "<td colspan='4' align='right' style='border-top:solid 1px #000;'><font size=1 face=Times New Roman><b>TOTAL</b></font></td>";
            echo "<td align='right' style='border-top:solid 1px #000;'><font size=1 face=Times New Roman><b>".number_format($total, '0', '', '.')."</b></font></td>";
            echo "</tr>";
	echo "</table>";
	
		$tgl_sekarang = date("d M Y",
                time());

        echo "<table>";
        echo "<td valign=top class='TITLE_SIM3'><b><i><font size='1'  face='Times New Roman'>";
        $y = terbilang($total);
        echo strtoupper($y);
        echo "RUPIAH</font></i></b></td>";
        echo "</tr>";
        echo "</table>";
        ?>
<br/>
         <table width="100%" BORDER="0" CLASS="" cellpadding="0" cellspacing="0" style="font-family: Times New Roman; font-size: 10px; letter-spacing: 2px;">
			<tr>
				<td align="right"><b><font size="1"  face="Times New Roman"><? echo $client_city.", ".$tgl_sekarang; ?></b></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
            <tr>
                <td align="right"><u><b><font size="1"  face="Times New Roman"><? echo $_SESSION["nama_usr"]; ?></b></u></td>
            </tr>
        </table>

        <SCRIPT LANGUAGE="JavaScript">
            <!-- Begin
            printWindow();
            //  End -->
        </script>
    </body>
</html>
