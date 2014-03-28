<?php
session_start();
require_once("../lib/dbconn.php");
require_once("../lib/terbilang.php");
require_once("../lib/functions.php");
require_once("../lib/setting.php");

if ($_GET["kas"] == "rj") {
    $jenisKwitansi =  "KWITANSI TAGIHAN RAWAT JALAN";
} elseif ($_GET["kas"] == "ri") {
    $jenisKwitansi = "KWITANSI TAGIHAN RAWAT INAP";
} else {
    $jenisKwitansi = "KWITANSI TAGIHAN IGD";
}

$tgl_sekarang = date("d M Y", time());

$sqlPasien  = pg_query($con, "SELECT rs00006.tanggal_reg, rs00006.waktu_reg, rs00002.nama, rs00002.alm_tetap, kota_tetap, rs00001.tdesc, i.tdesc as poli FROM rs00006
                              JOIN rs00002  ON rs00006.mr_no = rs00002.mr_no
                              JOIN rs00001  ON rs00006.tipe = rs00001.tc AND rs00001.tt = 'JEP' 
                              left join rs00001 i on i.tc_poli = rs00006.poli
                              WHERE id = '".$_GET['rg']."'");
$pasien     = pg_fetch_object($sqlPasien);

if ($_GET["kas"] == "rj") {
    $poli = $dt->poli;
} elseif ($_GET["kas"] == "ri") {
    $poli = $pasien->bangsal . " / " . $pasien->ruangan . " / " . $pasien->bed . " / " . $pasien->klasifikasi_tarif;
} else {
    $poli = "IGD";
}
// Ambil nilai nomor kwitansi
if ($_GET[kas] == "rj") {
    $ksr1 = "BYR";
	$kwitansi = 'RJ - '.$_GET[rg];
} elseif ($_GET[kas] == "ri") {
    $ksr1 = "BYI";
	$kwitansi = 'RI - '.$_GET[rg];
} elseif ($_GET[kas] == "igd") {
    $ksr1 = "BYD";
	$kwitansi = 'RJ - '.$_GET[rg];
}

// Ambil nama pembayar
$cekpembayar = getFromTable("select max(bayar) as jumlah from rs00005 where reg = '$_GET[rg]' ");
if ($cekpembayar == '') {
    $pembayar = $pasien->nama;
} else {
    $pembayar = $cekpembayar;
}
// Akhir ambil nama pembayar

include ("tagihan");
?>    
<HTML>
    <HEAD>
        <!--<TITLE>Kwitansi Penjamin</TITLE>-->
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 14px; letter-spacing: 2px;">
	<tr valign="middle" >
		<td rowspan="2" align="center"><!--<img width="70px" height="70px" src="../images/logo_kotakab_sragen.png" align="left"/>-->
		<font color=white>
			<div style="font-family: Tahoma; font-size: 12px; color: #000; padding-left: 8px; padding-right: 8px;">&nbsp</div>
		    <div style="font-family: Tahoma; font-size: 14px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold">
<?=$set_header[0]?>
</div>
			<div style="font-family: Tahoma; font-size: 14px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?=$set_header[2]?></div>
			<div style="font-family: Tahoma; font-size: 14px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?=$set_header[3]?></div>
		</font>
	</tr>			
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 1px; letter-spacing: 2px;">
    <tr>
        <td align="left" style='border-top:solid 0px #000;border-bottom:solid 2px #000;'>&nbsp;</td>
    </tr>
    <tr>
        <td align="left" style='border-top:solid 2px #000;border-bottom:solid 0px #000;'>&nbsp;</td>
    </tr>
</table>
<!--END KOP KWITANSI -->
		  
<table align=center >
    <tr>
        <td align="center" colspan="4" style="font-family: Tahoma; font-size: 18px; letter-spacing: 4px;"><b><?php echo $jenisKwitansi ?></b></u></td>
    </tr>
</table>
<table border ="0" align=left cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 12px; letter-spacing: 4px;" width="100%">
    <tr>
        <td style="font-family: Tahoma; font-size: 12px;">No. Kwitansi</td>
        <td style="font-family: Tahoma; font-size: 12px;">: <? echo $kwitansi; ?></td>
        <td style="font-family: Tahoma; font-size: 12px;"></td>
        <td style="font-family: Tahoma; font-size: 12px;" align="right"><? echo date('Y-m-d H:i:s', strtotime($pasien->tanggal_reg.' '.$pasien->waktu_reg)); ?></td>
    </tr>
    <tr>
        <td style="font-family: Tahoma; font-size: 12px;">Nama Pasien</td>
        <td style="font-family: Tahoma; font-size: 12px;" width="40%">: <? echo $pasien->nama; ?></td>
        <td style="font-family: Tahoma; font-size: 12px;" align="right" colspan="2"><?php echo $pasien->poli?></td>
    </tr>
    <tr>
        <td style="font-family: Tahoma; font-size: 12px;">Alamat</td>
        <td style="font-family: Tahoma; font-size: 12px;" colspan="3">: <? echo $pasien->alm_tetap . " " . $pasien->kota_tetap; ?></td>
    </tr>
    <tr>
        <td style="font-family: Tahoma; font-size: 12px;">Penjamin</td>
        <td style="font-family: Tahoma; font-size: 12px;" colspan="3">: <? echo $pasien->tdesc; ?></td>
    </tr>
</table>
<?php
$pembayar = getFromTable("select max(bayar) as jumlah from rs00005 " .
        "where kasir in ('BYR','BYI','BYD') and " .
        "to_number(reg,'999999999999') = '".$_GET['rg']."' ");
if ($pembayar == '') {
    $pembayar1 = $pasien->tdesc;
} else {
    $pembayar1 = $pembayar;
}
?>    
<br/>
<br/>
<br/>
<br/>
<br/>
<table border ="0" align=left cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 12px; letter-spacing: 4px;" width="100%">
    <tr>
        <td style="font-family: Tahoma; font-size: 12px;" width="250"><b>SUDAH TERIMA DARI</b></td>
        <td style="font-family: Tahoma; font-size: 12px;"><b><? echo $pembayar1; ?></b></td>
    </tr>
    <tr>
        <td style="font-family: Tahoma; font-size: 12px;"><b>UANG SEJUMLAH</b></td>
        <td style="font-family: Tahoma; font-size: 12px;"><b>: Rp. <?= number_format($totalPenjamin, 0) ?></b></td>
    </tr>
</table>
<br/>
<br/>
<br/>
        <?
        
        echo ' <table width="100%" BORDER="0"  cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 12px; letter-spacing: 2px;">';
        echo "<tr>";
        echo "<td><img src=\"images/spacer.gif\" width=50 height=1></td>";
        echo "<td><img src=\"images/spacer.gif\" width=400 height=1></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<th width=50 style='border-top:solid 1px #000;border-bottom:solid 1px #000;'>NO</th>";
        echo "<th width=300 style='border-top:solid 1px #000;border-bottom:solid 1px #000;'>URAIAN</th>";
        echo "<th width=100 style='border-top:solid 1px #000;border-bottom:solid 1px #000;'>JUMLAH</th>";
        echo "</tr>";
    
        $nomor = 1;
        
        if ($admin > 0){
            echo "<tr>";
            echo "<td $cls align=center><font size=1 face=Tahoma>$nomor</td>";
            $nomor = $nomor + 1;
            echo "<td $cls align=left><font size=1 face=Tahoma>ADMINISTRASI</td>";
            echo "<td $cls align=right><font size=1 face=Tahoma>" . number_format($adminPenjamin, 0) . "</td>";
            echo "</tr>";
            }
                
            echo "<tr>";
            echo "<td $cls align=center><font size=1 face=Tahoma>$nomor</td>";
            $nomor = $nomor + 1;
            echo "<td $cls align=left><font size=1 face=Tahoma>LAYANAN</td>";
            echo "<td $cls align=right><font size=1 face=Tahoma>&nbsp;</td>";
            echo "</tr>";
            
		if ($tindakan > 0){
		echo "<tr>";
		echo "<td $cls align=center><font size=1 face=Tahoma>&nbsp;</td>";
		echo "<td $cls align=left><font size=1 face=Tahoma>- TINDAKAN MEDIS</td>";
		echo "<td $cls align=right><font size=1 face=Tahoma>" . number_format($tindakanPenjamin, 0) . "</td>";
		echo "</tr>";
		}
                
		if ($visite > 0){
		echo "<tr>";
		echo "<td $cls align=center><font size=1 face=Tahoma>&nbsp;</td>";
		echo "<td $cls align=left><font size=1 face=Tahoma>- VISITE</td>";
		echo "<td $cls align=right><font size=1 face=Tahoma>" . number_format($visitePenjamin, 0) . "</td>";
		echo "</tr>";
		}
                
		if ($layananDokter > 0){
		echo "<tr>";
		echo "<td $cls align=center><font size=1 face=Tahoma>&nbsp;</td>";
		echo "<td $cls align=left><font size=1 face=Tahoma>- PEMERIKSAAN DOKTER</td>";
		echo "<td $cls align=right><font size=1 face=Tahoma>" . number_format($layananDokterPenjamin, 0) . "</td>";
		echo "</tr>";
		}
                
		if ($konsul > 0){
		echo "<tr>";
		echo "<td $cls align=center><font size=1 face=Tahoma>&nbsp;</td>";
		echo "<td $cls align=left><font size=1 face=Tahoma>- KONSUL</td>";
		echo "<td $cls align=right><font size=1 face=Tahoma>" . number_format($konsulPenjamin, 0) . "</td>";
		echo "</tr>";
		}
                
		if ($alat > 0){
		echo "<tr>";
		echo "<td $cls align=center><font size=1 face=Tahoma>&nbsp;</td>";
		echo "<td $cls align=left><font size=1 face=Tahoma>- ALAT</td>";
		echo "<td $cls align=right><font size=1 face=Tahoma>" . number_format($$alatPenjamin, 0) . "</td>";
		echo "</tr>";
		}
                
		if ($bhp > 0){
		echo "<tr>";
		echo "<td $cls align=center><font size=1 face=Tahoma>$nomor</td>";
		$nomor = $nomor + 1;
		echo "<td $cls align=left><font size=1 face=Tahoma>BHP DI RUANGAN</td>";
		echo "<td $cls align=right><font size=1 face=Tahoma>" . number_format($bhpPenjamin, 0) . "</td>";
		echo "</tr>";
		}
                
		if ($obat > 0){
		echo "<tr>";
		echo "<td $cls align=center><font size=1 face=Tahoma>$nomor</td>";
		$nomor = $nomor + 1;
		echo "<td $cls align=left><font size=1 face=Tahoma>OBAT / FARMASI</td>";
		echo "<td $cls align=right><font size=1 face=Tahoma>" . number_format($obatPenjamin, 0) . "</td>";
		echo "</tr>";
		}
                
		if ($laborat > 0){
		echo "<tr>";
		echo "<td $cls align=center><font size=1 face=Tahoma>$nomor</td>";
		$nomor = $nomor + 1;
		echo "<td $cls align=left><font size=1 face=Tahoma>LABORATORIUM</td>";
		echo "<td $cls align=right><font size=1 face=Tahoma>" . number_format($laboratPenjamin, 0) . "</td>";
		echo "</tr>";
		}
                
		if ($radiologi > 0){
		echo "<tr>";
		echo "<td $cls align=center><font size=1 face=Tahoma>$nomor</td>";
		$nomor = $nomor + 1;
		echo "<td $cls align=left><font size=1 face=Tahoma>RADIOLOGI</td>";
		echo "<td $cls align=right><font size=1 face=Tahoma>" . number_format($radiologiPenjamin, 0) . "</td>";
		echo "</tr>";
		}
                
		if ($usg > 0){
		echo "<tr>";
		echo "<td $cls align=center><font size=1 face=Tahoma>$nomor</td>";
		$nomor = $nomor + 1;
		echo "<td $cls align=left><font size=1 face=Tahoma>USG / ECG</td>";
		echo "<td $cls align=right><font size=1 face=Tahoma>" . number_format($usgPenjamin, 0) . "</td>";
		echo "</tr>";
		}
                
		if ($oksigen > 0){
		echo "<tr>";
		echo "<td $cls align=center><font size=1 face=Tahoma>$nomor</td>";
		$nomor = $nomor + 1;
		echo "<td $cls align=left><font size=1 face=Tahoma>OKSIGEN / NO2</td>";
		echo "<td $cls align=right><font size=1 face=Tahoma>" . number_format($oksigenPenjamin, 0) . "</td>";
		echo "</tr>";
		}
                
		if ($fisio > 0){
		echo "<tr>";
		echo "<td $cls align=center><font size=1 face=Tahoma>$nomor</td>";
		$nomor = $nomor + 1;
		echo "<td $cls align=left><font size=1 face=Tahoma>FISIOTERAPHI</td>";
		echo "<td $cls align=right><font size=1 face=Tahoma>" . number_format($fisioPenjamin, 0) . "</td>";
		echo "</tr>";
		}
                
		if ($ambulan > 0){
		echo "<tr>";
		echo "<td $cls align=center><font size=1 face=Tahoma>$nomor</td>";
		$nomor = $nomor + 1;
		echo "<td $cls align=left><font size=1 face=Tahoma>AMBULANCE</td>";
		echo "<td $cls align=right><font size=1 face=Tahoma>" . number_format($ambulanPenjamin, 0) . "</td>";
		echo "</tr>";
		}
		
		if ($lain > 0){
		echo "<tr>";
		echo "<td $cls align=center><font size=1 face=Tahoma>$nomor</td>";
		$nomor = $nomor + 1;
		echo "<td $cls align=left><font size=1 face=Tahoma>LAIN - LAIN</td>";
		echo "<td $cls align=right><font size=1 face=Tahoma>" . number_format($lainPenjamin, 0) . "</td>";
		echo "</tr>";
		}
		
		
		// if ($_SESSION[uid] == "kasir2" || $_SESSION[uid] == "root") {
		if ($_GET["kas"] == "ri" || $_GET["kas"] == "root") {
			echo "<tr>";
			echo "<td $cls align=center><font size=1 face=Tahoma>$nomor</td>";
			$nomor = $nomor + 1;
			echo "<td $cls align=left><font size=1 face=Tahoma>AKOMODASI RAWAT INAP</td>";
			echo "<td $cls align=right><font size=1 face=Tahoma>" . number_format($akomodasiPenjamin, 0) . "</td>";
			echo "</tr>";
		}
		
		//--pembulatan
		$totalPembulatan = pembulatan($totalPenjamin);
		$pembulatan = $totalPembulatan - $totalPenjamin;
		//--

		if($totalPenjamin > 0){
                echo "<tr>";
				echo "<td align=center style='border-top:solid 1px #000;'><font size=1 face=Tahoma>&nbsp;</td>";
				echo "<td align=right style='border-top:solid 1px #000;'><font size=1 face=Tahoma><b>PEMBAYARAN </b></td>";
				echo "<td align=right style='border-top:solid 1px #000;'><font size=1 face=Tahoma><b>" . number_format($totalPenjamin, 0) . "</b></td>";
				echo "</tr>";
				
				echo "<tr>";
				echo "<td align=center style='border-top:solid 1px #000;'><font size=1 face=Tahoma>&nbsp;</td>";
				echo "<td align=right style='border-top:solid 1px #000;'><font size=1 face=Tahoma><b>PEMBULATAN </b></td>";
				echo "<td align=right style='border-top:solid 1px #000;'><font size=1 face=Tahoma><b>" . number_format($pembulatan, 0) . "</b></td>";
				echo "</tr>";
				
				echo "<tr>";
				echo "<td align=center style='border-top:solid 1px #000;'><font size=1 face=Tahoma>&nbsp;</td>";
				echo "<td align=right style='border-top:solid 1px #000;'><font size=1 face=Tahoma><b>TOTAL PEMBAYARAN</b></td>";
				echo "<td align=right style='border-top:solid 1px #000;'><font size=1 face=Tahoma><b>" . number_format($totalPembulatan, 0) . "</b></td>";
				echo "</tr>";
        }

		echo "</table>";
        ?>
        <br/>
         <table width="100%" BORDER="0"  cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 12px; letter-spacing: 2px;">
            <tr>
                <td align="left" style="font-family: Tahoma; font-size: 18px; letter-spacing: 4px;"><i><? echo terbilang($totalPembulatan); ?> rupiah</i></td>
            </tr>
        </table>
        <br/>
         <table width="100%" BORDER="0" CLASS="" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 12px; letter-spacing: 2px;">
            <tr>
                <td style="font-family: Tahoma; font-size: 14px;" align="right"><b><font size="2"  face="Tahoma"><? echo 'Boyolali, '.$tgl_sekarang; ?></b></td>
            </tr>
            <tr>
                <td style="font-family: Tahoma; font-size: 14px;" align="right"><font size="2"  face="Tahoma">&nbsp;</td>
            </tr>
            <tr>
                <td style="font-family: Tahoma; font-size: 14px;" align="right"><font size="2"  face="Tahoma">&nbsp;</td>
            </tr>
            <tr>
                <td style="font-family: Tahoma; font-size: 14px;" align="right"><u><b><font size="2"  face="Tahoma"><? echo $_SESSION["nama_usr"]; ?></b></u></td>
            </tr>
        </table>

        <SCRIPT LANGUAGE="JavaScript">
            <!-- Begin
            printWindow();
            //  End -->
        </script>
    </body>
</html>
