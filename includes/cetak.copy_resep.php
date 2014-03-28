<?php
session_start();
require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
require_once("../lib/setting.php");
require_once("../lib/terbilang.php");
?>

<HTML>
    <HEAD>
        <TITLE>::: Sistem Informasi <?php echo $RS_NAME; ?> :::</TITLE>
        <SCRIPT language="JavaScript" src="../plugin/jquery-1.8.2.js"></SCRIPT>
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
			    <div style="font-family: Tahoma; font-size: 14px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?php echo $set_header[0]?></div>
				<div style="font-family: Tahoma; font-size: 14px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?php echo $set_header[2]?></div>
				<div style="font-family: Tahoma; font-size: 14px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?php echo $set_header[3]?></div>
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

    <?
    $reg            = $_GET["rg"];
    $tgl_sekarang   = date("d M Y", time());

    $rt = pg_query($con,
            "select id as code, nama, alm_tetap,kota_tetap, diagnosa_sementara from rsv_pasien2 where id::text= '$reg'  ");

    $nt = pg_num_rows($rt);
    $dt = pg_fetch_object($rt);

    $rowsPemakaianObat      = pg_query($con, "SELECT id, tanggal_entry, waktu_entry, item_id, qty, tagihan, referensi, dibayar_penjamin  
                             FROM rs00008 
                             WHERE trans_type = 'OB1' AND rs00008.no_reg = '".$_GET["rg"]."' ");
    $rowsPemakaianRacikan   = pg_query($con, "SELECT id, tanggal_entry, waktu_entry, item_id, qty, tagihan, referensi, dibayar_penjamin  
                             FROM rs00008 
                             WHERE trans_type = 'RCK' AND rs00008.no_reg = '".$_GET["rg"]."' ");
    $rowsPemakaianBHP       = pg_query($con, "SELECT id, tanggal_entry, waktu_entry, item_id, qty, tagihan, referensi, dibayar_penjamin  
                             FROM rs00008 
                             WHERE trans_type = 'BHP' AND rs00008.no_reg = '".$_GET["rg"]."' ");
    ?>
    <table align=center >
        <tr>
            <td align="center" colspan="4" style="font-family: Tahoma; font-size: 16px; letter-spacing: 3px;"><b>KWITANSI COPY RESEP</b></u></td>
        </tr>
    </table>
    <table border ="0" align=left cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 13px; letter-spacing: 4px;" width="100%">
        <tr>
            <td>Tanggal</td>
            <td colspan="3">: <? echo $tgl_sekarang; ?></td>
        </tr>
        <tr>
            <td>No.Reg</td>
            <td width="80%">: <? echo $dt->code; ?></td>
            <td align="right"></td>
            <td></td>
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
&nbsp;
<br/>
    <table width="100%" BORDER="0"  cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 12px; letter-spacing: 2px;">
        <tr>
            <td align="center" style='border-top:solid 1px #000;border-bottom:solid 1px #000;'>WAKTU ENTRY</td>
            <td align="center" style='border-top:solid 1px #000;border-bottom:solid 1px #000;'>NAMA OBAT</td>
            <td align="center" style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="3%">JML</td>
            <td align="center" style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="12%">TAGIHAN</td>
            <td align="center" style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="12%">PENJAMIN</td>
            <td align="center" style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="12%">SELISIH</td>
        </tr>

<?php
if(pg_num_rows($rowsPemakaianObat) > 0){
    echo '<tr id="list_obat_to_print" ><td class="" colspan="8"><span style="font-weight: bold;">Obat Resep</span></td></tr>';
        $iData          = 0;
        $iObat          = 0;
        $total          = 0;
        $totalPenjamin  = 0;
        $totalSelisih   = 0;
        while($row=pg_fetch_array($rowsPemakaianObat)){
            
            $iData++;
            
            $sqlObat = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga 
                                        FROM rs00015 
                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $row["item_id"] );
            $obat = pg_fetch_array($sqlObat);
                $iObat++;
                $total          = $total + $row["tagihan"];
                $totalPenjamin  = $totalPenjamin + $row["dibayar_penjamin"];
                $totalSelisih   = $totalSelisih + ($row["tagihan"]-$row["dibayar_penjamin"]);
                $arrWaktuEntry = explode('.', $row["waktu_entry"]);
                $arrJamEntry = explode(':', $arrWaktuEntry[0]);
?>
	<tr>
            <td class="" align="left" height="17" >&nbsp; <?=tanggal($row["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry[0].':'.$arrJamEntry[1]?></td>
            <td class="" align="left" height="17" >&nbsp; <?=$obat["obat"]?></td>
            <td class="" align="left" height="17" style="text-align: center;"><?=$row["qty"]?> <? //=$obat["satuan"]?></td>
            <td class="" align="right" height="17" ><?=number_format($row["tagihan"],'0','','.')?></td>
            <td class="" align="right" height="17" ><?=number_format($row["dibayar_penjamin"],'0','','.')?></td>
            <td class="" align="right" height="17" ><?=number_format(($row["tagihan"]-$row["dibayar_penjamin"]),'0','','.')?>&nbsp;</td>
	</tr>
<?php
        }
}
?>
    <?php
if(pg_num_rows($rowsPemakaianRacikan) > 0){   
    echo '<tr id="list_racikan_to_print"><td class="" colspan="8"><span style="font-weight: bold;"><br/>Obat Racikan</span></td></tr>';
        $iRacikan       = 0;
        while($rowRacikan=pg_fetch_array($rowsPemakaianRacikan)){
            
            
            $iData++;
            
            $sqlObatR = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga 
                                        FROM rs00015 
                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $rowRacikan["item_id"] );
            $obatR = pg_fetch_array($sqlObatR);
                $iRacikan++;
                $total          = $total + $rowRacikan["tagihan"];
                $totalPenjamin  = $totalPenjamin + $rowRacikan["dibayar_penjamin"];
                $totalSelisih   = $totalSelisih + ($rowRacikan["tagihan"]-$rowRacikan["dibayar_penjamin"]);
                $arrWaktuEntry2 = explode('.', $rowRacikan["waktu_entry"]);
                $arrJamEntry2 = explode(':', $arrWaktuEntry2[0]);                    
?>
	<tr>
            <td class="" align="left" height="17">&nbsp; <?=tanggal($rowRacikan["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry2[0].':'.$arrJamEntry2[1]?></td>
            <td class="" align="left" height="17">&nbsp; <?=$obatR["obat"]?></td>
            <td class="" align="left" height="17" style="text-align: center;"><?=$rowRacikan["qty"]?> <? //=$obatR["satuan"]?></td>
            <td class="" align="right" height="17"><?=number_format($rowRacikan["tagihan"],'0','','.')?></td>
            <td class="" align="right" height="17"><?=number_format($rowRacikan["dibayar_penjamin"],'0','','.')?></td>
            <td class="" align="right" height="17"><?=number_format(($rowRacikan["tagihan"]-$rowRacikan["dibayar_penjamin"]),'0','','.')?>&nbsp;</td>
	</tr>
<?php
        }
}
?>        
        
    <?php
if(pg_num_rows($rowsPemakaianBHP) > 0){   
    echo '<tr id="list_bhp_to_print"><td class="" colspan="8"><span style="font-weight: bold;"><br/>BHP</span></td></tr>';
        $iBHP       = 0;
        while($rowBHP=pg_fetch_array($rowsPemakaianBHP)){
            $iData++;
            
            $sqlObatBHP = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga 
                                        FROM rs00015 
                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $rowBHP["item_id"] );
            $obatBHP = pg_fetch_array($sqlObatBHP);
                $iBHP++;
                $total          = $total + $rowBHP["tagihan"];
                $totalPenjamin  = $totalPenjamin + $rowBHP["dibayar_penjamin"];
                $totalSelisih   = $totalSelisih + ($rowBHP["tagihan"]-$rowBHP["dibayar_penjamin"]);
                $arrWaktuEntry2 = explode('.', $rowBHP["waktu_entry"]);
                $arrJamEntry2 = explode(':', $arrWaktuEntry2[0]);                    
?>
	<tr>
            <td class="" align="left" height="17">&nbsp; <?=tanggal($rowBHP["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry2[0].':'.$arrJamEntry2[1]?></td>
            <td class="" align="left" height="17">&nbsp; <?=$obatBHP["obat"]?></td>
            <td class="" align="left" height="17" style="text-align: center;"><?=$rowBHP["qty"]?> <? //=$obatR["satuan"]?></td>
            <td class="" align="right" height="17"><?=number_format($rowBHP["tagihan"],'0','','.')?></td>
            <td class="" align="right" height="17"><?=number_format($rowBHP["dibayar_penjamin"],'0','','.')?></td>
            <td class="" align="right" height="17"><?=number_format(($rowBHP["tagihan"]-$rowBHP["dibayar_penjamin"]),'0','','.')?>&nbsp;</td>
	</tr>
<?php
        }
}
?>        
	<tr>
            <td style='border-top:solid 1px #000;' colspan="3" align="left"><span style="font-weight: bold;">TOTAL TRANSAKSI</span></td>
            <td style='border-top:solid 1px #000;' align="right" ><span style="font-weight: bold;"><?= number_format($total,'0','','.')?></span></td>
            <td style='border-top:solid 1px #000;' align="right" ><span style="font-weight: bold;"><?= number_format($totalPenjamin,'0','','.')?></span></td>
            <td style='border-top:solid 1px #000;' align="right" ><span style="font-weight: bold;"><?= number_format($totalSelisih,'0','','.')?></span>&nbsp;</td>
	</tr>
<?php
$rowsReturn = pg_query($con, "SELECT id, tanggal_entry, waktu_entry, item_id, qty_return, tagihan, referensi, dibayar_penjamin, trans_type  
                             FROM rs00008_return 
                             WHERE trans_type = 'OB1' AND rs00008_return.no_reg = '".$_GET["rg"]."' ");
if(pg_num_rows($rowsReturn) > 0){   
    echo '<tr><td class="" colspan="6"><span style="font-weight: bold;"><br/>Obat Return</span></td></tr>';
     while($row=pg_fetch_array($rowsReturn)){
          $sqlObat = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga 
                                        FROM rs00015 
                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $row["item_id"] );
            $obat = pg_fetch_array($sqlObat);
            $arrWaktuEntry = explode('.', $row["waktu_entry"]);
            $arrJamEntry = explode(':', $arrWaktuEntry[0]);
            
            $totalTagihanReturn = $totalTagihanReturn + $row["tagihan"];
            $totalPenjaminReturn = $totalPenjaminReturn + $row['dibayar_penjamin'];
?>
        <tr>
            <td class="" align="left" height="17">&nbsp; <?=tanggal($row["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry2[0].':'.$arrJamEntry2[1]?></td>
            <td class="" align="left" height="17">&nbsp; <?=$obat["obat"]?></td>
            <td class="" align="left" height="17" style="text-align: center;"><?=$row["qty_return"]?> </td>
            <td class="" align="right" height="17"><?=number_format($row["tagihan"],'0','','.')?></td>
            <td class="" align="right" height="17"><?=number_format($row["dibayar_penjamin"],'0','','.')?></td>
            <td class="" align="right" height="17"><?=number_format(($row["tagihan"]-$row["dibayar_penjamin"]),'0','','.')?>&nbsp;</td>
	</tr>
<?php        
     }
}
?>     
<?php
$rowsReturnBHP = pg_query($con, "SELECT id, tanggal_entry, waktu_entry, item_id, qty_return, tagihan, referensi, dibayar_penjamin, trans_type  
                             FROM rs00008_return 
                             WHERE trans_type = 'BHP' AND rs00008_return.no_reg = '".$_GET["rg"]."' ");
if(pg_num_rows($rowsReturnBHP) > 0){   
    echo '<tr><td class="" colspan="6"><span style="font-weight: bold;"><br/>Obat Return</span></td></tr>';
     while($rowReturnBHP=pg_fetch_array($rowsReturnBHP)){
          $sqlReturnBHP = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga 
                                        FROM rs00015 
                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $rowReturnBHP["item_id"] );
            $returnBHP = pg_fetch_array($sqlReturnBHP);
            $arrWaktuEntry = explode('.', $rowReturnBHP["waktu_entry"]);
            $arrJamEntry = explode(':', $arrWaktuEntry[0]);
            
            $totalTagihanReturn = $totalTagihanReturn + $rowReturnBHP["tagihan"];
            $totalPenjaminReturn = $totalPenjaminReturn + $rowReturnBHP['dibayar_penjamin'];
?>
        <tr>
            <td class="" align="left" height="17">&nbsp; <?=tanggal($rowReturnBHP["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry2[0].':'.$arrJamEntry2[1]?></td>
            <td class="" align="left" height="17">&nbsp; <?=$returnBHP["obat"]?></td>
            <td class="" align="left" height="17" style="text-align: center;"><?=$rowReturnBHP["qty_return"]?> </td>
            <td class="" align="right" height="17"><?=number_format($rowReturnBHP["tagihan"],'0','','.')?></td>
            <td class="" align="right" height="17"><?=number_format($rowReturnBHP["dibayar_penjamin"],'0','','.')?></td>
            <td class="" align="right" height="17"><?=number_format(($rowReturnBHP["tagihan"]-$rowReturnBHP["dibayar_penjamin"]),'0','','.')?>&nbsp;</td>
	</tr>
<?php        
     }
}
?>     
        <tr>
            <td style='border-top:solid 1px #000;' colspan="3" align="left"><span style="font-weight: bold;">TOTAL RETURN</span></td>
            <td style='border-top:solid 1px #000;' align="right" ><span style="font-weight: bold;"><?= number_format($totalTagihanReturn,'0','','.')?></span></td>
            <td style='border-top:solid 1px #000;' align="right" ><span style="font-weight: bold;"><?= number_format($totalPenjaminReturn,'0','','.')?></span></td>
            <td style='border-top:solid 1px #000;' align="right" ><span style="font-weight: bold;"><?= number_format(($totalTagihanReturn-$totalPenjaminReturn),'0','','.')?></span>&nbsp;</td>
	</tr>
        <tr>
            <td colspan="6" align="left">&nbsp;</td>
	</tr>
        <tr>
            <td style='border-top:solid 0px #000;' colspan="3" align="left"><span style="font-weight: bold;">GRAND TOTAL</span></td>
            <td style='border-top:solid 0px #000;' align="right" ><span style="font-weight: bold;"><?= number_format($total-$totalTagihanReturn,'0','','.')?></span></td>
            <td style='border-top:solid 0px #000;' align="right" ><span style="font-weight: bold;"><?= number_format($totalPenjamin-$totalPenjaminReturn,'0','','.')?></span></td>
            <td style='border-top:solid 0px #000;' align="right" ><span style="font-weight: bold;"><?= number_format(($totalSelisih-($totalTagihanReturn-$totalPenjaminReturn)),'0','','.')?></span>&nbsp;</td>
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
if($iObat == 0){
    echo '<script>';
    echo '$("#list_obat_to_print").remove();';
    echo '</script>';
}
if($iRacikan == 0){
    echo '<script>';
    echo '$("#list_racikan_to_print").remove();';
    echo '</script>';
}
    
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