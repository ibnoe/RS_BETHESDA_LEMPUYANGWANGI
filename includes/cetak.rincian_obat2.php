<?php
session_start();
require_once("../lib/dbconn.php");
?>

<HTML>
    <HEAD>
        <TITLE>::: Sistem Informasi <?php echo $RS_NAME; ?> :::</TITLE>
        
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

    <?
    $reg            = $_GET["rg"];
    $tgl_sekarang   = date("d M Y", time());

    $rt = pg_query($con,
            "select id as code, nama, alm_tetap,kota_tetap, diagnosa_sementara from rsv_pasien2 where id::text= '$reg'  ");

    $nt = pg_num_rows($rt);
    $dt = pg_fetch_object($rt);

    $rowsPemakaianObat      = pg_query($con, "SELECT id, tanggal_entry, item_id, qty, tagihan, referensi, dibayar_penjamin  
                             FROM rs00008 
                             WHERE trans_type = 'OB1' AND rs00008.no_reg = '".$_GET["rg"]."' ");
    $rowsPemakaianRacikan   = pg_query($con, "SELECT id, tanggal_entry, item_id, qty, tagihan, referensi, dibayar_penjamin  
                             FROM rs00008 
                             WHERE trans_type = 'RCK' AND rs00008.no_reg = '".$_GET["rg"]."' ");
    ?>
    <table align=center >
        <tr>
            <td align="center" colspan="4" style="font-family: Tahoma; font-size: 18px; letter-spacing: 3px;"><b>RINCIAN TRANSAKSI FARMASI RAWAT JALAN</b></u></td>
        </tr>
    </table>
    <table border ="0" align=left cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 14px; letter-spacing: 4px;" width="100%">
        <tr>
            <td>Tanggal</td>
            <td colspan="3">: <? echo $tgl_sekarang; ?></td>
        </tr>
        <tr>
            <td>No.Reg</td>
            <td width="40%">: <? echo $dt->code; ?></td>
            <td align="right">Dokter</td>
            <td>: <? echo $dt->diagnosa_sementara; ?></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td colspan="3">: <? echo $dt->nama; ?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td colspan="3">: <? echo $dt->alm_tetap; ?>, <? echo $dt->kota_tetap; ?></td>
        </tr>
    </table>
    <table width="100%" BORDER="0"  cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 14px; letter-spacing: 2px;">
        <tr>
            <td align="center"  style='border-top:solid 1px #000;border-bottom:solid 1px #000;'>NAMA OBAT</td>
            <td align="center"  style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="15%">JUMLAH</td>
            <td align="center"  style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="12%">TAGIHAN</td>
            <td align="center"  style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="12%">PENJAMIN</td>
            <td align="center"  style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="12%">SELISIH</td>
        </tr>

<?php
if(pg_num_rows($rowsPemakaianObat) > 0){
    echo '<tr><td class="" colspan="8"><span style="font-weight: bold;">Obat Resep</span></td></tr>';
        $iObat          = 0;
        $total          = 0;
        $totalPenjamin  = 0;
        $totalSelisih   = 0;
        while($row=pg_fetch_array($rowsPemakaianObat)){
            $iObat++;
            $total          = $total + $row["tagihan"];
            $totalPenjamin  = $totalPenjamin + $row["dibayar_penjamin"];
            $totalSelisih   = $totalSelisih + ($row["tagihan"]-$row["dibayar_penjamin"]);
            
            $sqlObat = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga 
                                        FROM rs00015 
                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $row["item_id"] );
            $obat = pg_fetch_array($sqlObat);
?>
	<tr>
		<td class="" align="left" height="30" >&nbsp; <?=$obat["obat"]?></td>
		<td class="" align="left" height="30" style="text-align: center;"><?=$row["qty"]?> <? //=$obat["satuan"]?></td>
		<td class="" align="right" height="30" ><?=number_format($row["tagihan"],'0','','.')?></td>
		<td class="" align="right" height="30" ><?=number_format($row["dibayar_penjamin"],'0','','.')?></td>
		<td class="" align="right" height="30" ><?=number_format(($row["tagihan"]-$row["dibayar_penjamin"]),'0','','.')?>&nbsp;</td>
	</tr>
<?php
        }
}
?>
    <?php
if(pg_num_rows($rowsPemakaianRacikan) > 0){   
    echo '<tr><td class="" colspan="8"><span style="font-weight: bold; font-size:18px;"><br/>Obat Racikan</span></td></tr>';
        $iRacikan       = 0;
        while($rowRacikan=pg_fetch_array($rowsPemakaianRacikan)){
            $iRacikan++;
            $total          = $total + $rowRacikan["tagihan"];
            $totalPenjamin  = $totalPenjamin + $rowRacikan["dibayar_penjamin"];
            $totalSelisih   = $totalSelisih + ($rowRacikan["tagihan"]-$rowRacikan["dibayar_penjamin"]);
            
            $sqlObatR = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga 
                                        FROM rs00015 
                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $rowRacikan["item_id"] );
            $obatR = pg_fetch_array($sqlObatR);
?>
	<tr>
		<td class="" align="left" height="30">&nbsp; <?=$obatR["obat"]?></td>
		<td class="" align="left" height="30" style="text-align: center;"><?=$rowRacikan["qty"]?> <? //=$obatR["satuan"]?></td>
		<td class="" align="right" height="30"><?=number_format($rowRacikan["tagihan"],'0','','.')?></td>
		<td class="" align="right" height="30"><?=number_format($rowRacikan["dibayar_penjamin"],'0','','.')?></td>
		<td class="" align="right" height="30"><?=number_format(($rowRacikan["tagihan"]-$rowRacikan["dibayar_penjamin"]),'0','','.')?>&nbsp;</td>
	</tr>
<?php
        }
}
?>        
	<tr>
		<td  style='border-top:solid 1px #000;' colspan="2" align="left"><span style="font-weight: bold;">T O T A L</span></td>
		<td style='border-top:solid 1px #000;' align="right" ><span style="font-weight: bold;"><?= number_format($total,'0','','.')?></span></td>
		<td style='border-top:solid 1px #000;' align="right" ><span style="font-weight: bold;"><?= number_format($totalPenjamin,'0','','.')?></span></td>
		<td style='border-top:solid 1px #000;' align="right" ><span style="font-weight: bold;"><?= number_format($totalSelisih,'0','','.')?></span>&nbsp;</td>
	</tr>
</table>


<table border="0" align="right" width="50%">

    <td align="center" class="TITLE_SIM3"></td>
</tr>
<tr>
    <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
</tr>
<tr>
    <td align="right" class="TITLE_SIM3" style="font-family: Tahoma; font-size: 15px; letter-spacing: 2px;"><? echo $_SESSION["nama_usr"]; ?></td>
</tr>
</table>

<SCRIPT LANGUAGE="JavaScript">
    printWindow();
</script>

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

function bulan($params) {
    switch ($params) {
        case 1:
            $bln = "Jan";
            break;
        case 2:
            $bln = "Peb";
            break;
        case 3:
            $bln = "Mar";
            break;
        case 4:
            $bln = "Apr";
            break;
        case 5:
            $bln = "Mei";
            break;
        case 6:
            $bln = "Jun";
            break;
        case 7:
            $bln = "Jul";
            break;
        case 8:
            $bln = "Agu";
            break;
        case 9:
            $bln = "Sep";
            break;
        case 10:
            $bln = "Okt";
            break;
        case 11:
            $bln = "Nop";
            break;
        case 12:
            $bln = "Des";
            break;
            break;
    }
    return $bln;
}
?>