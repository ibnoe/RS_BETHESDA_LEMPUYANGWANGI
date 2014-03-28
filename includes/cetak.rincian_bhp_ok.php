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
            "select nama, alm_tetap, tipe_desc, diagnosa_sementara from rsv_pasien2 where id = '".(string)$_GET['rg']."'");
    $nt = pg_num_rows($rt);
    $dt = pg_fetch_object($rt);
    
    $addParam = '';
    for($i=1;$i<=50;$i++){
        if(!empty($_GET['bhp_'.$i])){
            $addParam = $addParam.' OR id = '.$_GET['bhp_'.$i];
        }
    }
    if($addParam != ''){
        $addParam = 'AND ('.substr($addParam,3).')';
    }
    
    $rowsPemakaianBHP      = pg_query($con, "SELECT id, tanggal_entry, item_id, qty, tagihan, referensi, dibayar_penjamin  
                             FROM rs00008 
                             WHERE trans_type = 'BHP' AND rs00008.no_reg = '".$_GET["rg"]."' ". $addParam);
    ?>
    <table align=center >
        <tr>
            <td align="center" colspan="4" style="font-family: Tahoma; font-size: 20px; letter-spacing: 3px;"><b>RINCIAN BHP </b></u></td>
        </tr>
    </table>
    <table border ="0" align=left cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 16px; letter-spacing: 4px;" width="100%">
        <tr>
            <td>Tanggal</td>
            <td>: <? echo $tgl_sekarang; ?></td>
            <td>Tipe Pasien</td>
            <td>: <?php echo $dt->tipe_desc?></td>
        </tr>
        <tr>
            <td>No.Reg</td>
            <td width="25%">: <? echo $_GET['rg']; ?></td>
            <td align="">Poli</td>
            <td>: Ruang Operasi </td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>: <? echo $dt->nama; ?></td>
            <td align="">Dokter </td>
            <td>: <? echo $dt->diagnosa_sementara; ?></td>            
        </tr>
        <tr>
            <td>Alamat</td>
            <td colspan="3">: <? echo $dt->alm_tetap; ?>, <? echo $dt->kota_tetap; ?></td>
        </tr>
    </table>
    <table width="100%" BORDER="0"  cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 15px; letter-spacing: 2px;">
        <tr>
            <td align="center"  style='border-top:solid 1px #000;border-bottom:solid 1px #000;'>NAMA OBAT</td>
            <td align="center"  style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="15%">JUMLAH</td>
            <td align="center"  style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="12%">TAGIHAN</td>
            <td align="center"  style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="12%">PENJAMIN</td>
            <td align="center"  style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="12%">SELISIH</td>
        </tr>

<?php
if($_GET['print_selected'] == 'true'){
    if(pg_num_rows($rowsPemakaianBHP) > 0){
        echo '<tr><td class="" colspan="8"><span style="font-weight: bold;">Barang Habis Pakai</span></td></tr>';
        $iObat          = 0;
        $total          = 0;
        $totalPenjamin  = 0;
        $totalSelisih   = 0;   
       while($row=pg_fetch_array($rowsPemakaianBHP)){
           if($_GET['bhp_'.$row['id']] == 'on'){
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
		<td class="" align="right" height="30" ><?=number_format($row["tagihan"])?></td>
		<td class="" align="right" height="30" ><?=number_format($row["dibayar_penjamin"])?></td>
		<td class="" align="right" height="30" ><?=number_format($row["tagihan"]-$row["dibayar_penjamin"])?>&nbsp;</td>
	</tr>
<?php
            }
       }
    }
}else{
if(pg_num_rows($rowsPemakaianBHP) > 0){
    echo '<tr><td class="" colspan="8"><span style="font-weight: bold;">Barang Habis Pakai</span></td></tr>';
        $iObat          = 0;
        $total          = 0;
        $totalPenjamin  = 0;
        $totalSelisih   = 0;
        while($row=pg_fetch_array($rowsPemakaianBHP)){
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
		<td class="" align="right" height="30" ><?=number_format($row["tagihan"])?></td>
		<td class="" align="right" height="30" ><?=number_format($row["dibayar_penjamin"])?></td>
		<td class="" align="right" height="30" ><?=number_format($row["tagihan"]-$row["dibayar_penjamin"])?>&nbsp;</td>
	</tr>
<?php
        }
}
}
?>
	<tr>
		<td  style='border-top:solid 1px #000;' colspan="2" align="left"><span style="font-weight: bold;">T O T A L</span></td>
		<td style='border-top:solid 1px #000;' align="right" ><span style="font-weight: bold;"><?= number_format($total)?></span></td>
		<td style='border-top:solid 1px #000;' align="right" ><span style="font-weight: bold;"><?= number_format($totalPenjamin)?></span></td>
		<td style='border-top:solid 1px #000;' align="right" ><span style="font-weight: bold;"><?= number_format($totalSelisih)?></span>&nbsp;</td>
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