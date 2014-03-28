<?php
session_start();
require_once("../lib/dbconn.php");



// cek nilai post is_racikan, jika nilainya = 1, nilai trans_type = 'RCK'
if( (int)$_POST["is_racikan"] == 1){
	$transType = 'RCK';
}else{
    $transType = 'OB1';
}

// Ambil nilai jasa sesuai dengan kategori obatnya
  
$noReg		= $_GET["rg"];
$obatId		= $_POST["obat_id"];
$qty		= floatval($_POST["qty"]);
$harga		= floatval($_POST["harga"]);
$jasa		= floatval($_POST['jasa']);
$tagihan	= ($qty*$harga)+$jasa;
$penjami	= floatval($_POST["penjamin"]);

if(!empty($_GET["del"])){
    $sqlStok = pg_query($con, "SELECT qty_ri FROM rs00016a WHERE obat_id = ".$_POST["obat_id"]);
    $rowStok = pg_fetch_array($sqlStok);
    $stok    = $rowStok['qty_ri'];
    pg_query($con, "DELETE FROM rs00008 WHERE id = ".$_POST["rs00008_id"] );
    pg_query($con, "UPDATE rs00016a SET qty_ri = ".($stok+$_POST["qty"])."  WHERE obat_id = ".$_POST["obat_id"]);
    
    // ---- update juga rs00005 untuk kasir
    updateTagihanUntukKasir($con,$_GET["rg"]);}

if(!empty($_POST["obat_id"]) && ($_GET["del"] == false)){
// ---------------------- Start Insert/Update Pemakaian Obat  ------------------
   // select stok 
   $sqlStok = pg_query($con, "SELECT qty_ri FROM rs00016a WHERE obat_id = ".$_POST["obat_id"]);
   $rowStok = pg_fetch_array($sqlStok);
   $stok    = $rowStok['qty_ri'];
   
   if($_POST["rs00008_id"] > 0){
        pg_query($con, "UPDATE  rs00008 SET trans_type = '".$transType."', waktu_entry = CURRENT_TIME, item_id = '".$_POST["obat_id"]."', referensi = '".$_POST["jasa"]."', 
                        qty = ".$_POST["qty"].", harga = ".$_POST["harga"].", tagihan = ".(($_POST["qty"]*$_POST["harga"])+$_POST["jasa"]).", 
                            dibayar_penjamin = ".$_POST["penjamin"]." WHERE id = ".$_POST["rs00008_id"] );
        $stok = $stok+$_POST["qty_awal"];
        pg_query($con, "UPDATE rs00016a SET qty_ri = ".($stok-$_POST["qty"])."  WHERE obat_id = ".$_POST["obat_id"]);
        
        // ---- update juga rs00005 untuk kasir
        updateTagihanUntukKasir($con,$_GET["rg"]);
   }else{
        pg_query($con, "INSERT INTO rs00008 (id, trans_type, trans_form, trans_group, tanggal_trans, 
                    tanggal_entry, waktu_entry, no_reg, item_id, referensi,qty, harga, tagihan, dibayar_penjamin,user_id) 
                    values(
                    nextval('rs00008_seq'), '".$transType."', '320RJ_SWD', nextval('rs00008_seq_group'), CURRENT_DATE, CURRENT_DATE, CURRENT_TIME, 
                    '".$noReg."', '".$obatId."', '".$jasa."',
                    ".$qty.", ".$harga.", ".$tagihan.", ".$penjami.",'".$_SESSION["uid"]."')" );
   
        pg_query($con, "UPDATE rs00016a SET qty_ri = ".($stok-$qty)."  WHERE obat_id = ".$obatId);
        
        // ---- update juga rs00005 untuk kasir
        updateTagihanUntukKasir($con,$noReg);
   }
// ---------------------- End Insert/Update Pemakaian Obat  --------------------

}

$rowsPemakaianObat      = pg_query($con, "SELECT id, tanggal_entry, item_id, qty, tagihan, referensi, dibayar_penjamin, trans_type  
                             FROM rs00008 
                             WHERE trans_type = 'OB1' AND rs00008.no_reg = '".$_GET["rg"]."' ");
$rowsPemakaianRacikan   = pg_query($con, "SELECT id, tanggal_entry, item_id, qty, tagihan, referensi, dibayar_penjamin, trans_type  
                             FROM rs00008 
                             WHERE trans_type = 'RCK' AND rs00008.no_reg = '".$_GET["rg"]."' ");

?>
<!-- ---------------------- Start Buat tabel hasil input obat -------------------->
<table width='100%'>
	<tr>
		<td class="TBL_HEAD" width='3%'><center>No.</center></td>
		<td class="TBL_HEAD" width='10%' ><center>Tanggal</center></td>
		<td class="TBL_HEAD"><center>Obat</center></td>
		<td class="TBL_HEAD" width='8%'><center>Jumlah</center></td>
		<td class="TBL_HEAD" width='10%'><center>Tagihan</center></td>
		<td class="TBL_HEAD" width='10%'><center>Pejamin</center></td>
		<td class="TBL_HEAD" width='10%'><center>Selisih</center></td>
		<td class="TBL_HEAD" width='12%'>&nbsp;</td>
	</tr>	
        <tr>
		<td class="TBL_BODY" colspan="8"><span style="font-weight: bold;">Obat Resep</span></td>
	</tr>
<?php
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
		<td class="TBL_BODY" align="right"><?=$iObat?></td>
		<td class="TBL_BODY" align="right"><?=tanggal($row["tanggal_entry"])?></td>
		<td class="TBL_BODY" align="left">
                    <input type="hidden" id="obat_id_<?php echo $row["id"]?>" value="<?=$obat["id"]?>" />
                    <input type="hidden" id="harga_<?php echo $row["id"]?>" value="<?=$obat["harga"]?>" />
                    <input type="hidden" id="jasa_<?php echo $row["id"]?>" value="<?=$row["referensi"]?>" />
                    <input type="hidden" id="tipe_<?php echo $row["id"]?>" value="<?=$row["trans_type"]?>" />
                    <span id="obat_nama_<?php echo $row["id"]?>"><?=$obat["obat"]?></span>
                </td>
		<td class="TBL_BODY" align="right">
                    <span id="qty_<?php echo $row["id"]?>"><?=$row["qty"]?></span>
                    <span id="satuan_<?php echo $row["id"]?>"><?=$obat["satuan"]?></span>
                </td>
		<td class="TBL_BODY" align="right"><?=number_format($row["tagihan"])?></td>
		<td class="TBL_BODY" align="right"><span id="penjamin_<?php echo $row["id"]?>"><?=number_format($row["dibayar_penjamin"])?></span></td>
		<td class="TBL_BODY" align="right"><span id="selisih_<?php echo $row["id"]?>"><?=number_format($row["tagihan"]-$row["dibayar_penjamin"])?></span></td>
                <td class="TBL_BODY" align="center"><a href="#" onClick="edit_data_obat('<?php echo $row["id"]?>')">edit</a> &nbsp; | &nbsp;
                <a href="#" onClick="delete_data_obat('<?php echo $row["id"]?>')">delete</a></td>
	</tr>
<?php
        }
?>
            <tr>
		<td class="TBL_BODY" colspan="8"><span style="font-weight: bold;">Obat Racikan</span></td>
	</tr>
    <?php
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
		<td class="TBL_BODY" align="right"><?=$iRacikan?></td>
		<td class="TBL_BODY" align="right"><?=tanggal($rowRacikan["tanggal_entry"])?></td>
		<td class="TBL_BODY" align="left">
			<input type="hidden" id="obat_id_<?php echo $rowRacikan["id"]?>" value="<?=$obatR["id"]?>" />
			<input type="hidden" id="harga_<?php echo $rowRacikan["id"]?>" value="<?=$obatR["harga"]?>" />
			<input type="hidden" id="jasa_<?php echo $rowRacikan["id"]?>" value="<?=$rowRacikan["referensi"]?>" />
			<input type="hidden" id="tipe_<?php echo $row["id"]?>" value="<?=$row["trans_type"]?>" />
			<span id="obat_nama_<?php echo $rowRacikan["id"]?>"><?=$obatR["obat"]?></span>
        </td>
		<td class="TBL_BODY" align="right">
                    <span id="qty_<?php echo $rowRacikan["id"]?>"><?=$rowRacikan["qty"]?></span>
                    <span id="satuan_<?php echo $rowRacikan["id"]?>"><?=$obatR["satuan"]?></span>
                </td>
		<td class="TBL_BODY" align="right"><?=number_format($rowRacikan["tagihan"])?></td>
		<td class="TBL_BODY" align="right"><span id="penjamin_<?php echo $rowRacikan["id"]?>"><?=number_format($rowRacikan["dibayar_penjamin"])?></span></td>
		<td class="TBL_BODY" align="right"><span id="selisih_<?php echo $rowRacikan["id"]?>"><?=number_format($rowRacikan["tagihan"]-$rowRacikan["dibayar_penjamin"])?></span></td>
                <td class="TBL_BODY" align="center"><a href="#" onClick="edit_data_obat('<?php echo $rowRacikan["id"]?>')">edit</a> &nbsp; | &nbsp;
                <a href="#" onClick="delete_data_obat('<?php echo $rowRacikan["id"]?>')">delete</a></td>
	</tr>
<?php
        }
?>        
	<tr>
		<td class="TBL_HEAD" colspan="4" align="right">T O T A L </td>
		<td class="TBL_HEAD" align="right" ><?=number_format($total)?>&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" ><?=number_format($totalPenjamin)?>&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" ><?=number_format($totalSelisih)?>&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" >&nbsp;&nbsp;</td>
	</tr>
</table>
<table>
	<tr>
	<td class="TBL_BODY" colspan="5" align="right">Cetak Resep</td>
	<td class="TBL_BODY" align="center" width='15%'><a href="javascript: cetakkwitansi1(<? echo (int) $_GET["rg"];?>)" ><img src="images/cetak.gif" border="0"></a></td>
	</tr>	
</table>
<!-- ---------------------- End Buat tabel hasil input obat -------------------- -->

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

function updateTagihanUntukKasir($con, $rg){
            // ---- insert juga rs00005 untuk kasir
        $sqlTotalBiayaObat      = pg_query($con, "SELECT SUM(tagihan) as jumlah_tagihan, SUM(dibayar_penjamin) as jumlah_penjamin  
                            FROM rs00008 
                            WHERE no_reg = '".$rg."' ");
        $totalBiayaObat = pg_fetch_array($sqlTotalBiayaObat);

        if($totalBiayaObat['jumlah_tagihan'] > 0){
            $totalBiayaObatVal = $totalBiayaObat['jumlah_tagihan'];
        }else{
            $totalBiayaObatVal = 0;
        }
        
        if($totalBiayaObat['jumlah_penjamin'] > 0){
            $totalBiayaObatValPenjamin = $totalBiayaObat['jumlah_penjamin'];
        }else{
            $totalBiayaObatValPenjamin = 0;
        }
        
        // cek dulu di tabel rs00005 klo datanya udah ada di update, klo g ada di insert aja cuy
        $sqlCek = pg_query($con, "SELECT jumlah FROM  rs00005 WHERE reg = '".$rg."' AND kasir = 'RJL' AND layanan = '320RJ_SWD' ");
        
        if(pg_num_rows($sqlCek) > 0){
            pg_query($con, "UPDATE  rs00005  SET  jumlah = ".((int)$totalBiayaObatVal-(int)$totalBiayaObatValPenjamin)." 
                WHERE reg = '".$rg."' AND kasir = 'RJL' AND layanan = '320RJ_SWD' ");
        }else{
            pg_query($con, "INSERT INTO rs00005 VALUES( nextval('kasir_seq'), '".$rg."', ".
        "CURRENT_DATE, 'RJL', 'Y', 'N', '320RJ_SWD', ".((int)$totalBiayaObatVal-(int)$totalBiayaObatValPenjamin).", 'N')");
        }
}
?>