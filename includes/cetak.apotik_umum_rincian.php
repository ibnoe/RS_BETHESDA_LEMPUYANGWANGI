<?php
session_start();
require_once("../lib/dbconn.php");
require_once("../lib/setting.php");
require_once("../lib/terbilang.php");

$tgl_sekarang   = date("d-m-Y H:i:s", time());
$tgl_now        = date("d-m-Y", time());
$sql = pg_query($con, "SELECT * FROM apotik_umum WHERE no_reg = '".$_GET['no_reg']."' ");

$row  = pg_fetch_array($sql);
?>    
<HTML>
    <HEAD>
        <!--<TITLE>Apotik Umum</TITLE>-->
        <TITLE></TITLE>
        <!--<LINK rel='styleSheet' type='text/css' href='../cetak.css'>
        <LINK rel='styleSheet' type='text/css' href='../invoice.css'>-->
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


<?php
	//start sql nama relasi
	$rr = pg_query($con, "SELECT tc AS nama_relasi_id, tdesc AS nama_relasi
            FROM rs00008
            JOIN rs00001 ON rs00001.tc::text = rs00008.item_id::text AND rs00001.tt::text = 'RAP'
            WHERE trans_type = 'OBM' AND rs00008.no_reg = '".$_GET["no_reg"]."'");

    $nr = pg_num_rows($rr);
    $dr = pg_fetch_object($rr);
	//end sql nama relasi
?>

<table width="50%" border="0" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 11px; letter-spacing: 2px;">
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td align="center" colspan="4" style="font-family: Tahoma; font-size: 11px; letter-spacing: 3px;"><b>RINCIAN TRANSAKSI OBAT APOTIK UMUM<br/> <?=$set_header[0]?></b></td>
    </tr>
</table>

<br/>
<table width="50%" border="0" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 11px; letter-spacing: 2px;">
    <tr>
        <td valign="top">No.Reg</td>
        <td colspan="2">: <? echo $row['no_reg']; ?></td>
        <td align="right"><? echo $tgl_sekarang; ?></td>
    </tr>
    <tr>
        <td valign="top">Nama Relasi</td>
        <td colspan="3">: <? echo "<b>".$dr->nama_relasi."</b>"; ?></td>
    </tr>
    <tr>
        <td valign="top">Nama</td>
        <td colspan="3">: <? echo $row['nama']?$row['nama']:"-"; ?></td>
    </tr>
    <tr>
        <td valign="top">Alamat</td>
        <td colspan="3">: <? echo $row['alamat']?$row['alamat']:"-"; ?></td>
    </tr>
    <tr>
        <td valign="top">Dokter</td>
        <td colspan="3">: <? echo $row['dokter']?$row['dokter']:"-"; ?></td>
    </tr>
    <tr>
        <td valign="top">Tempat Praktek</td>
        <td colspan="3">: <? echo $row['praktek']?$row['praktek']:"-"; ?></td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
</table>

<table width="50%" border="0" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 11px; letter-spacing: 2px;">
    <tr>
        <td width="1%" align="left" style='border-top:solid 1px #000;border-bottom:solid 1px #000;'>No.</td>
        <td width="37%" align="left" style='border-top:solid 1px #000;border-bottom:solid 1px #000;'>Nama Obat</td>
        <td width="3%" align="right" style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="10%">Jml</td>
        <td width="3%" align="right" style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="12%">Harga</td>
    </tr>

<?
//echo ' <table width="50%" BORDER="0"  cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 1px; letter-spacing: 2px;">';
//echo "<tr>";
//echo "<td><img src=\"images/spacer.gif\" width=50 height=1></td>";
///echo "<td><img src=\"images/spacer.gif\" width=400 height=1></td>";
//echo "</tr>";
//echo "<tr>";
//echo "<th width=50 style='border-top:solid 1px #000;border-bottom:solid 1px #000;'>NO</th>";
//echo "<th width=300 style='border-top:solid 1px #000;border-bottom:solid 1px #000;'>URAIAN</th>";
//echo "<th width=100 style='border-top:solid 1px #000;border-bottom:solid 1px #000;'>BANYAKNYA</th>";
//echo "<th width=100 style='border-top:solid 1px #000;border-bottom:solid 1px #000;'>JUMLAH</th>";
//echo "</tr>";


if ($row['id'] > 0){
    $sqlPembelian = pg_query($con, "SELECT id, obat_id, obat_nama, banyaknya, harga, jumlah FROM apotik_umum  WHERE no_reg = '".$_GET['no_reg']."' ");
    $i=0;
    while($rowPembelian=pg_fetch_array($sqlPembelian)){
        $i++;
        $total = $total + $rowPembelian['jumlah'];
        //echo "<tr>";
        //echo "<td align=right>".$i.". &nbsp;</td>";
        //echo "<td align=left>&nbsp;".$rowPembelian['obat_nama']."</td>";
        //echo "<td align=right>" . $rowPembelian['banyaknya'] . "&nbsp;</td>";
        //echo "<td align=right>" . number_format($rowPembelian['jumlah'], 2) . "&nbsp;</td>";
        //echo "</tr>";
        ?>
        <tr>
		    <td valign="top" class="" align="left" height="15" ><?=$i?>.</td>
			<td class="" align="left" height="15" ><?php echo $rowPembelian["obat_nama"]?></td>
			<td class="" align="right" height="15" style="text-align: right;"><?php echo $rowPembelian["banyaknya"]?></td>
			<td class="" align="right" height="15" ><?php echo number_format($rowPembelian["jumlah"],'0','','.')?></td>
		</tr>
        <?php
    }
        //echo "<tr>";
        //echo "<td align=right style='border-top:solid 1px #000'>&nbsp;</td>";
        //echo "<td align=right colspan=2 style='border-top:solid 1px #000'><b>TOTAL</b>&nbsp;</td>";
        //echo "<td align=right style='border-top:solid 1px #000'><b>" . number_format($total, 2) . "</b>&nbsp;</td>";
        //echo "</tr>";
        ?>
        <tr>
			<td style='border-top:solid 1px #000;' colspan="3" align="right"><span style="font-weight: bold; font-size: 11px;">Total =</span></td>
			<td style='border-top:solid 1px #000;' align="right" ><span style="font-weight: bold;"><?php echo number_format($total,'0','','.')?></span></td>
		</tr>
        <?php
}
//echo "</table>";
?>
</table>
<?php
?>

<table width="50%" border="0" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 1px; letter-spacing: 2px;">
    <tr>
        <td colspan="4" width="50%" align="left" style='border-top:solid 0px #000;border-bottom:solid 0px #000;'><i><?php  echo terbilang($total); ?> rupiah</i></td>
    </tr>
</table>

<table width="50%" border="0" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 11px; letter-spacing: 2px;">
    <tr>    
        <td width="50%" colspan="4" align="left" class="TITLE_SIM3">&nbsp;</td>
    </tr>
    <tr>
        <td width="35%" colspan="2" align="center" class="TITLE_SIM3">&nbsp;</td>
        <td width="15%" colspan="2" align="center" class="TITLE_SIM3" style="font-family: Tahoma; font-size: 11px; letter-spacing: 2px;"><?php echo $client_city.", ".$tgl_now."<br>".$_SESSION["nama_usr"]; ?></td>
    </tr>
    <tr>    
        <td width="50%" colspan="4" align="left" class="TITLE_SIM3">&nbsp;</td>
    </tr>
    <tr>    
        <td width="50%" colspan="4" align="left" class="TITLE_SIM3">&nbsp;</td>
    </tr>
    <tr>
        <td width="35%" colspan="2" align="center" class="TITLE_SIM3">&nbsp;</td>
        <td width="15%" colspan="2" align="center" class="TITLE_SIM3" style="font-family: Tahoma; font-size: 11px; letter-spacing: 2px;"><?php echo ".........................."; ?></td>
    </tr>
    <tr>    
        <td width="50%" colspan="4" align="left" class="TITLE_SIM3">&nbsp;</td>
    </tr>
    <tr>
        <td width="50%" colspan="4" align="left" class="TITLE_SIM3" style="font-family: Tahoma; font-size: 11px; letter-spacing: 2px;">** Terima Kasih ** <br /> Dokumen dicetak komputer, tidak perlu stempel</td>
    </tr>
</table>

<SCRIPT LANGUAGE="JavaScript">
    <!-- Begin
    printWindow();
    //  End -->
</script>
</body>
</html>
