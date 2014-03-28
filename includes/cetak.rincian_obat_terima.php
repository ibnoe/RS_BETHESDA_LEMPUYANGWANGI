<?php
session_start();
require_once("../lib/dbconn.php");
require_once("../lib/setting.php");
?>

<HTML>
    <HEAD>
        <!--<TITLE>::: Sistem Informasi <?php echo $RS_NAME; ?> :::</TITLE>-->
        <TITLE></TITLE>
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

    <?
    $reg            = $_GET["rg"];
    $tgl_sekarang   = date("d-m-Y H:i:s", time());
    $tgl_now        = date("d-m-Y", time());
    $noUrut = 0;

  //  $rt = pg_query($con,
		$r = pg_query($con, "select * from c_po where po_id = '".$_GET["poid"]."'");

    $n = pg_num_rows($r);
    $d = pg_fetch_object($r);
    $supplier = getFromTable(
               "select a.nama from rs00028 a, c_po b ".
               "where  a.id=b.supp_id::text and b.supp_id::text='".$d->supp_id."' ");
    $tanggal_po = getFromTable(
               "select to_char(po_tanggal,'DD Mon YYYY') from c_po ".
               "where po_id='".$_GET["poid"]."' ");

    $rowsPemakaianObat      = pg_query($con, "SELECT id, tanggal_entry, waktu_entry, item_id, qty, tagihan, referensi, dibayar_penjamin  
                             FROM rs00008 
                             WHERE trans_type = 'OB1' AND rs00008.no_reg = '".$_GET["rg"]."' order by id ");
    $rowsPemakaianRacikan   = pg_query($con, "SELECT id, tanggal_entry, waktu_entry, item_id, qty, tagihan, referensi, dibayar_penjamin  
                             FROM rs00008 
                             WHERE trans_type = 'RCK' AND rs00008.no_reg = '".$_GET["rg"]."' order by id ");
	 $rowsDataTerima = pg_query($con,"select a.obat,a.batch,to_char(b.expire,'yyyy-mm-dd')as expire,b.bonus, case when b.qty_terima is null then b.item_qty else b.qty_terima end as item_qty,b.item_qty as qty,b.harga_beli, b.ppn, b.diskon1, b.diskon2, b.materai, case when b.po_status=0 then 'Belum Diproses' else 'Sudah Diproses' end as po_status, b.item_id 
,d.tdesc as satuan1, e.tdesc as satuan_2, b.jumlah2 , to_char(b.tanggal_terima,'yyyy-mm-dd')as tanggal_terima
from c_po_item_terima b
JOIN rs00015 a ON a.id::text = b.item_id
LEFT JOIN rs00001 d ON b.satuan1 = d.tc AND d.tt='SAT'
LEFT JOIN rs00001 e ON b.satuan2 = e.tc AND e.tt='SAT'
where (b.po_status = 0 or b.po_status = 2) and b.po_id='".$_GET["poid"]."' and a.id::text=b.item_id order by a.obat asc");
					 
	//start sql nama relasi
/*	$rr = pg_query($con, "SELECT tc AS nama_relasi_id, tdesc AS nama_relasi
            FROM rs00008
            JOIN rs00001 ON rs00001.tc::text = rs00008.item_id::text AND rs00001.tt::text = 'RAP'
            WHERE trans_type = 'OBM' AND rs00008.no_reg = '".$_GET["rg"]."'");

    $nr = pg_num_rows($rr);
    $dr = pg_fetch_object($rr);
	//end sql nama relasi
	
	//start sql nama dokter
	$rr1 = pg_query($con, "SELECT b.id, b.nama
			FROM rs00008 a
			JOIN rs00017 b ON b.id = a.no_kwitansi
			WHERE a.no_reg = '".$_GET["rg"]."' and a.trans_type::text = 'OBM'::text ");
	
	$nr1 = pg_num_rows($rr1);
	$dr1 = pg_fetch_object($rr1);
	//---------------------------------
	$rr2 = pg_query($con, "SELECT b.id, b.nama
			FROM rs00006 a
			JOIN rs00017 b ON b.nama = a.diagnosa_sementara
			WHERE a.id = '".$_GET["rg"]."'");
	
	$nr2 = pg_num_rows($rr2);
	$dr2 = pg_fetch_object($rr2);
		
	if($nr1 > 0){
			$nama_dokter = $dr1->nama;
	} else if($nr1 == 0){
			$nama_dokter = $dt->diagnosa_sementara;
	} else {
			$nama_dokter = $dr2->nama;
	}
	//end sql nama dokter
	
	//start sql nomor resep
	$rr3 = pg_query($con, "SELECT a.nmr_transaksi
			FROM rs00008 a
			WHERE a.no_reg = '".$_GET["rg"]."' and a.trans_type::text = 'OBM'::text ");
	
	$nr3 = pg_num_rows($rr3);
	$dr3 = pg_fetch_object($rr3);
	$nomorResep = $dr3->nmr_transaksi; 
	//end sql nomor resep
	*/
   ?>
<!--
<table align=center >
    <tr>
        <td align="center" colspan="4" style="font-family: Tahoma; font-size: 18px; letter-spacing: 3px;"><b>RINCIAN TRANSAKSI FARMASI RAWAT INAP</b></u></td>
    </tr>
</table>
-->

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 14px; letter-spacing: 2px;">
		<tr valign="middle" >
			<td rowspan="2" align="center"><!--<img width="70px" height="70px" src="../images/logo_kotakab_sragen.png" align="left"/>-->
			<font color=white>
				<div style="font-family: Tahoma; font-size: 12px; color: #000; padding-left: 8px; padding-right: 8px;">&nbsp</div>
			    <div style="font-family: Tahoma; font-size: 12px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?=$set_header[0]?></div>
				<div style="font-family: Tahoma; font-size: 12px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?=$set_header[2]?></div>
				<div style="font-family: Tahoma; font-size: 12px; color: #000; padding-left: 8px; padding-right: 8px; font-weight: bold"><?=$set_header[3]?></div>
			</font>
		</tr>			
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 11px; letter-spacing: 2px;">
    <tr>
        <td colspan="6" style="border-top:1px solid;">&nbsp;</td>
    </tr>
    <tr>
        <td align="left" colspan="6" style="font-family: Tahoma; font-size: 14px; letter-spacing: 3px;"><b>RINCIAN ITEM PENERIMAAN</b></td>
    </tr>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 11px; letter-spacing: 2px;">
    <tr>
        <td valign="top">&nbsp;</td>
        <td colspan="5">&nbsp;</td>
    </tr>  <tr>
        <td valign="top" width="25%">NO. PO</td>
        <td colspan="5">: <? echo $d->po_id; ?></td>
    </tr>
    <tr>
        <td valign="top">NAMA SUPPLIER</td>
        <td colspan="5">: <? echo $supplier; ?></td>
    </tr>
    <tr>
        <td valign="top">TANGGAL PO</td>
        <td colspan="5">: <? echo $tanggal_po; ?></td>
    </tr>
    <tr>
        <td valign="top">PENANGGUNG JAWAB</td>
        <td colspan="5">: <? echo $d->po_personal; ?></td>
    </tr>
    <tr>
        <td valign="top">NO REGISTRASI AKUNTANSI</td>
        <td colspan="5">: <? echo $d->reg_akun; ?></td>
    </tr>
    <tr>
        <td colspan="5">&nbsp;</td>
    </tr>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 1px; letter-spacing: 2px;">
    <tr>
        <td colspan="6" width="45%" align="left" style='border-top:solid 1px #000;border-bottom:solid 1px #000;'>&nbsp;</td>
    </tr>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 11px; letter-spacing: 2px;">
    <tr>
        <!--<td width="24%" align="left" style='border-top:solid 1px #000;border-bottom:solid 1px #000;'>Waktu Entry</td>-->
	<td width="1%" align="left" style='border-top:solid 1px #000;border-bottom:solid 1px #000;'>No.</td>
        <td width="10%" align="center" style='border-top:solid 1px #000;border-bottom:solid 1px #000;'>Nama Obat</td>
        <td width="3%" align="center" style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="10%">Batch</td>
        <td width="3%" align="center" style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="10%">Expire Date</td>
        <td width="3%" align="center" style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="10%">Jml</td>
        <td width="5%" align="center" style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="10%">Sat</td>
        <td width="8%" align="center" style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="12%">Harga Satuan</td>
        <td width="8%" align="center" style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="12%">Jumlah Harga</td>
        <td width="3%" align="center" style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="12%">Diskon</td>
        <td width="3%" align="center" style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="12%">PPN</td>
        <td width="3%" align="center" style='border-top:solid 1px #000;border-bottom:solid 1px #000;' width="12%">Total</td>
    </tr>
       

<?php
if(pg_num_rows($rowsDataTerima) > 0){
   // echo '<tr id="list_obat_to_print" ><td class="" colspan="6"><span style="font-weight: bold;">Obat Resep</span></td></tr>';
        $iData          = 0;
        $iObat          = 0;
        $total          = 0;
        $totalPenjamin  = 0;
        $totalSelisih   = 0;
        while($row=pg_fetch_array($rowsDataTerima)){

	/*if ($rowsme!=0 && $rowsme == 23) {//Page 1
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
}else if($rowsme>23 && $rowsme ==54) {//Page 2
	//No Space
}else if($rowsme>54  && $rowsme ==88){//Page 3
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
}else if($rowsme>88 && $rowsme ==122){//Page 4
	//No Space
}else if($rowsme>122 && $rowsme ==156){//Page 5
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
}else if($rowsme>156 && $rowsme ==190){//Page 6
	//No Space
}*/

            $noUrut++;
            $iData++;
//            $total          = $total + $row["tagihan"];
//            $totalPenjamin  = $totalPenjamin + $row["dibayar_penjamin"];
//            $totalSelisih   = $totalSelisih + ($row["tagihan"]-$row["dibayar_penjamin"]);
            
            $sqlObat = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga 
                                        FROM rs00015 
                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $row["item_id"] );
            $obat = pg_fetch_array($sqlObat);
            if($_GET['cetak_'.$iData] != ''){
                $iObat++;
                 if (!empty($row['item_qty'])) {
			 $qty =  $row['item_qty'];
		 } else {
			 $qty = 0;
		 }
		 
		 if (!empty($row['satuan1'])) {
			 $satuan=  $row['satuan1'];
		 } else {
			 $satuan = '-';
		 }
		 
		 if (!empty($row['harga_beli'])) {
			 $hargaBeli =  $row['harga_beli']*$row['jumlah2'];
		 } else {
			 $hargaBeli = 0;
		 }
			 
		 if ($hargaBeli > 0) {
			 $jumlah =  $row['item_qty']*$hargaBeli;
		 } else {
			 $jumlah = 0;
		 }
			 
		 if (!empty($row['diskon1'])) {
			 $disc1 =  $row['diskon1'];
			 $disc1Rupiah =  $qty*($disc1*$hargaBeli)/100;
		 } else {
			 $disc1 = 0;
			 $disc1Rupiah = 0;
		 }
		 
		 if (!empty($row['diskon2'])) {
			 $disc2 =  $row['diskon2'];
			 $disc2Rupiah =  $qty*($disc2*($hargaBeli-$disc1Rupiah))/100;
		 } else {
			 $disc2 = 0;
			 $disc2Rupiah = 0;
		 }
			 
		 if (!empty($row['ppn'])) {
			 $ppn =  ($row['ppn']/100)* (($qty*$hargaBeli)-$disc1Rupiah)-$disc2Rupiah;
		 } else {
			 $ppn = 0;
		 }
		 if (!empty($row['materai'])) {
			 $materai =  $row['materai'];
		 } else {
			 $materai = 0;
		 }
		 
		 if ($hargaBeli > 0) {
			 $total =  ($jumlah-($disc1Rupiah+$disc2Rupiah))+$ppn;
		 } else {
			 $total = 0;
		 }
			 
		 $totalHarga = $totalHarga+$jumlah;
		 $totalDisc1 = $totalDisc1+$qty*($disc1*$hargaBeli)/100;
		 $totalDisc2 = $totalDisc2+$qty*($disc2*$hargaBeli)/100;
		 $totalPPN = $totalPPN+$ppn;
		 $totalMaterai = $totalMaterai+$materai;
		 $grandTotal = $grandTotal+$total;
?>
    <tr>
        <!--<td class="" align="left" height="30" ><?=tanggal($row["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry[0].':'.$arrJamEntry[1]?></td>-->	<td valign="top" class="" align="left" height="15" ><?=$iObat?>.</td>
        <!--td class="" align="left" height="15" ><?=$obat["obat"]?><?if((strlen($obat["obat"]))>=32){$rowsme=$rowsme+2;}else{$rowsme=$rowsme+1;};?></td-->
	<td class="" align="left" height="15" ><?=$obat["obat"]?></td>
        <td align='center'><?php if (!empty($row['batch'])) {echo $row['batch'];} else {echo "&nbsp;";}?></td>
		<td align='center'><?php if (!empty($row['expire'])) {echo $row['expire'];} else {echo "&nbsp;";}?></td>
		<td class="" align="center" height="15" style="text-align: center;"><?=$row["item_qty"]?> <? //=$obat["satuan"]?></td>
        <td class="" align="left" height="15" style="text-align: left;"><?=$row["satuan1"]?> <? //=$obat["satuan"]?></td>
        <td class="" align="right" height="15" ><?=number_format($hargaBeli,'0','','.')?></td>
        <td class="" align="right" height="15" ><?=number_format($jumlah,'0','','.')?></td>
		<td align='right' ><?php if($hargaBeli > 0){ echo number_format($disc1Rupiah, '0','.','.'); }else{ echo '0'; }?>&nbsp;</td>
		<td align='right' ><?php echo number_format($ppn, '0','','.');?>&nbsp;</td>
		<td align='right' ><?php echo number_format($total, '0','','.');?>&nbsp;</td>
    </tr>
    
<?php
            }
        }
}
?>
    <?php
if(pg_num_rows($rowsPemakaianRacikan) > 0){   
   /* echo '<tr id="list_racikan_to_print"><td class="" colspan="6"><span style="font-weight: bold;"><br/>Obat Racikan</span></td></tr>';
        $iRacikan       = 0;
        while($rowRacikan=pg_fetch_array($rowsPemakaianRacikan)){
            
            $noUrut++;
            $iData++;
//            $total          = $total + $rowRacikan["tagihan"];
//            $totalPenjamin  = $totalPenjamin + $rowRacikan["dibayar_penjamin"];
//            $totalSelisih   = $totalSelisih + ($rowRacikan["tagihan"]-$rowRacikan["dibayar_penjamin"]);
            
            $sqlObatR = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga 
                                        FROM rs00015 
                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $rowRacikan["item_id"] );
            $obatR = pg_fetch_array($sqlObatR);
            if($_GET['cetak_'.$iData] != ''){
                $iRacikan++;
                $total          = $total + $rowRacikan["tagihan"];
                $totalPenjamin  = $totalPenjamin + $rowRacikan["dibayar_penjamin"];
                $totalSelisih   = $totalSelisih + ($rowRacikan["tagihan"]-$rowRacikan["dibayar_penjamin"]);
                $arrWaktuEntry2 = explode('.', $rowRacikan["waktu_entry"]);
                $arrJamEntry2 = explode(':', $arrWaktuEntry2[0]);                    
?>
    <tr>
        <!--<td class="" align="left" height="15" ><?=tanggal($rowRacikan["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry2[0].':'.$arrJamEntry2[1]?></td>-->
	<td valign="top" class="" align="left" height="15" ><?=$noUrut?>.</td>
        <td class="" align="left" height="15"><?=$obatR["obat"]?></td>
        <td class="" align="right" height="15" style="text-align: right;"><?=$rowRacikan["item_qty"]?> <? //=$obatR["satuan"]?></td>
        <td class="" align="right" height="15"><?=number_format($rowRacikan["tagihan"],'0','','.')?></td>
        <td class="" align="right" height="15"><?=number_format($rowRacikan["dibayar_penjamin"],'0','','.')?></td>
        <td class="" align="right" height="15"><?=number_format(($rowRacikan["tagihan"]-$rowRacikan["dibayar_penjamin"]),'0','','.')?>&nbsp;</td>
    </tr>
 
<?php
            }
        }*/
}
?>        
    <tr>
        <td  style='border-top:solid 1px #000;' colspan="7" align="right"><span style="font-weight: bold; font-size: 11px;">Total </span></td>
        <td  style='border-top:solid 1px #000;' align="RIGHT" class="" ><?php echo number_format($totalHarga, '0','','.');?></td>
		<td  style='border-top:solid 1px #000;' align="RIGHT" class="" ><?php echo number_format($totalDisc1, '0','','.');?>&nbsp;</td>
		<td  style='border-top:solid 1px #000;' align="RIGHT" class="" ><?php echo number_format($totalPPN, '0','','.');?>&nbsp;</td>
		<td  style='border-top:solid 1px #000;' align="RIGHT" class="" ><?php echo number_format($grandTotal, '0','','.');?>&nbsp;</td>
		</tr>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 1px; letter-spacing: 2px;">
    <tr>
        <td colspan="6" width="45%" align="left" style='border-top:solid 1px #000;border-bottom:solid 1px #000;'>&nbsp;</td>
    </tr>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Tahoma; font-size: 11px; letter-spacing: 2px;">
    <tr>    
        <td width="100%" colspan="6" align="left" class="TITLE_SIM3">&nbsp;</td>
    </tr>
    <tr>
        <td width="35%" colspan="4" align="center" class="TITLE_SIM3">&nbsp;</td>
        <td width="15%" colspan="2" align="center" class="TITLE_SIM3" style="font-family: Tahoma; font-size: 11px; letter-spacing: 2px;"><? echo "Yogyakarta, ".$tgl_now."<br>".$_SESSION["nama_usr"]; ?></td>
    </tr>
    <tr>    
        <td width="100%" colspan="6" align="left" class="TITLE_SIM3">&nbsp;</td>
    </tr>
    <tr>    
        <td width="100%" colspan="6" align="left" class="TITLE_SIM3">&nbsp;</td>
    </tr>
    <tr>
        <td width="35%" colspan="4" align="center" class="TITLE_SIM3">&nbsp;</td>
        <td width="15%" colspan="2" align="center" class="TITLE_SIM3" style="font-family: Tahoma; font-size: 11px; letter-spacing: 2px;"><? echo ".........................."; ?></td>
    </tr>
    <tr>    
        <td width="100%" colspan="6" align="left" class="TITLE_SIM3">&nbsp;</td>
    </tr>
    <!--tr>
        <td width="100%" colspan="6" align="left" class="TITLE_SIM3" style="font-family: Tahoma; font-size: 11px; letter-spacing: 2px;">** Terima Kasih ** <br /> Dokumen dicetak komputer, tidak perlu stempel</td>
    </tr-->
</table>

<!--
<table border="0" align="right" width="100%">
<tr>
    <td align="center" class="TITLE_SIM3"></td>
</tr>
<tr>
    <td align="center" class="TITLE_SIM3"><b>&nbsp;</b></td>
</tr>
<tr>
    <td align="right" class="TITLE_SIM3" style="font-family: Tahoma; font-size: 15px; letter-spacing: 2px;"><? echo $_SESSION["nama_usr"]; ?></td>
</tr>
</table>
-->
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
