<?php
session_start();
require_once("../lib/dbconn.php");


$transType = 'BHP';
// Ambil nilai jasa sesuai dengan kategori obatnya
  
$noReg		= $_GET["rg"];
$fieldStock     = 'qty_029';
$obatId		= $_POST["obat_id"];
$qty		= floatval($_POST["qty"]);
$harga		= floatval($_POST["harga"]);
$jasa		= 0;
$tagihan	= floatval($_POST['jumlah']);

$penjamin       = $_POST['penjamin'];

if(!empty($_GET["delreturn"])){
    pg_query($con, "DELETE FROM rs00008_return WHERE id = ".$_POST["rs00008_return_id"] );
}
if(!empty($_GET["del"])){
    $sqlStok = pg_query($con, "SELECT ".$fieldStock." FROM rs00016a WHERE obat_id = ".$_POST["obat_id"]);
    $rowStok = pg_fetch_array($sqlStok);
    $stok    = $rowStok[$fieldStock];
    pg_query($con, "DELETE FROM rs00008 WHERE id = ".$_POST["rs00008_id"] );
    pg_query($con, "UPDATE rs00016a SET ".$fieldStock." = ".($stok+$_POST["qty"])."  WHERE obat_id = ".$_POST["obat_id"]);
    
    // ---- update juga rs00005 untuk kasir
    updateTagihanUntukKasir($con,$_GET["rg"]);
}

if(!empty($_POST["obat_id"]) && ($_GET["del"] == false)){
// ---------------------- Start Insert/Update Pemakaian Obat  ------------------
   // select stok 
   $sqlStok = pg_query($con, "SELECT ".$fieldStock." FROM rs00016a WHERE obat_id = ".$_POST["obat_id"]);
   $rowStok = pg_fetch_array($sqlStok);
   $stok    = $rowStok[$fieldStock];
   
   if($_POST["rs00008_id"] > 0){
       
        if($_POST['is_return'] == 1){
             pg_query($con, "INSERT INTO rs00008_return (id, trans_type, trans_form, trans_group, tanggal_trans, 
                    tanggal_entry, waktu_entry, no_reg, item_id, referensi,qty_awal, qty_return, harga, tagihan, dibayar_penjamin,user_id) 
                    values(
                    nextval('rs00008_return_seq'), '".$transType."', '-', 0, CURRENT_DATE, CURRENT_DATE, CURRENT_TIME, 
                    '".$noReg."', '".$obatId."', '".$jasa."',
                    ".$_POST["qty_awal"].", ".$qty.", ".$harga.", ".$tagihan.", ".$penjamin.",'".$_SESSION["uid"]."')" );
             
			$qty		= floatval($_POST["qty_awal"]);
			$penjamin	= floatval($_POST["penjamin_awal"]);
			$harga		= floatval($_POST["harga_awal"]);
			$tagihan	= floatval($_POST["jumlah_awal"]);
        }
        
        pg_query($con, "UPDATE  rs00008 SET trans_type = '".$transType."', waktu_entry = CURRENT_TIME, item_id = '".$_POST["obat_id"]."', referensi = '".$_POST["jasa"]."', 
                        qty = ".$qty.", harga = ".$harga.", tagihan = ".$tagihan.", 
                            dibayar_penjamin = ".$penjamin." WHERE id = ".$_POST["rs00008_id"] );
        pg_query($con, "UPDATE rs00016a SET ".$fieldStock." = ".($stok+$_POST["qty"])."  WHERE obat_id = ".$_POST["obat_id"]);
        
        // ---- update juga rs00005 untuk kasir
        updateTagihanUntukKasir($con,$_GET["rg"]);
   }else{
        pg_query($con, "INSERT INTO rs00008 (id, trans_type, trans_form, trans_group, tanggal_trans, 
                    tanggal_entry, waktu_entry, no_reg, item_id, referensi,qty, harga, tagihan, dibayar_penjamin,user_id) 
                    values(
                    nextval('rs00008_seq'), '".$transType."', '-', nextval('rs00008_seq_group'), CURRENT_DATE, CURRENT_DATE, CURRENT_TIME, 
                    '".$noReg."', '".$obatId."', '".$jasa."',
                    ".$qty.", ".$harga.", ".$tagihan.", ".$penjamin.",'".$_SESSION["uid"]."')" );
   
        pg_query($con, "UPDATE rs00016a SET ".$fieldStock." = ".($stok-$qty)."  WHERE obat_id = ".$obatId);
        
        // ---- update juga rs00005 untuk kasir
        updateTagihanUntukKasir($con,$noReg);
   }
// ---------------------- End Insert/Update Pemakaian Obat  --------------------

}

$rowsPemakaianObat      = pg_query($con, "SELECT id, tanggal_entry, waktu_entry, item_id, qty, tagihan::integer,  dibayar_penjamin::integer, trans_type  
                             FROM rs00008 
                             WHERE trans_type = 'BHP' AND rs00008.no_reg = '".$_GET["rg"]."' ");
?>
<!-- ---------------------- Start Buat tabel hasil input obat -------------------->
<table width='100%'>
	<tr>
		<td class="TBL_HEAD" width='3%'><center>No.</center></td>
		<td class="TBL_HEAD" width='12%' ><center>Tanggal</center></td>
		<td class="TBL_HEAD"><center>Obat</center></td>
		<td class="TBL_HEAD" width='8%'><center>Jumlah</center></td>
		<td class="TBL_HEAD" width='8%'><center>Tagihan</center></td>
		<td class="TBL_HEAD" width='8%'><center>Pejamin</center></td>
		<td class="TBL_HEAD" width='8%'><center>Selisih</center></td>
		<td class="TBL_HEAD" width='5%'><center>Cetak</center></td>
		<td class="TBL_HEAD" width='18%'>&nbsp;</td>
	</tr>	
<?php
        $iData          = 0;
        $iObat          = 0;
        $total          = 0;
        $totalPenjamin  = 0;
        $totalSelisih   = 0;
        if(!empty($rowsPemakaianObat)){
        while($row=pg_fetch_array($rowsPemakaianObat)){
            $iData++;
            $iObat++;
            $total          = $total + $row["tagihan"];
            $totalPenjamin  = $totalPenjamin + $row["dibayar_penjamin"];
            $totalSelisih   = $totalSelisih + ($row["tagihan"]-$row["dibayar_penjamin"]);
            
            $sqlObat = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga::integer 
                                        FROM rs00015 
                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $row["item_id"] );
            $obat = pg_fetch_array($sqlObat);
            $arrWaktuEntry = explode('.', $row["waktu_entry"]);
            $arrJamEntry = explode(':', $arrWaktuEntry[0]);
?>
	<tr>
		<td class="TBL_BODY" align="right"><?=$iObat?></td>
		<td class="TBL_BODY" align="right"><?=tanggal($row["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry[0].':'.$arrJamEntry[1]?></td>
		<td class="TBL_BODY" align="left">
                    <input type="hidden" id="obat_id_<?php echo $row["id"]?>" value="<?=$obat["id"]?>" />
                    <input type="hidden" id="harga_<?php echo $row["id"]?>" value="<?=$obat["harga"]?>" />
                    <input type="hidden" id="tagihan_<?php echo $row["id"]?>" value="<?=$row["tagihan"]?>" />
                    <input type="hidden" id="tipe_<?php echo $row["id"]?>" value="<?=$row["trans_type"]?>" />
                    <span id="obat_nama_<?php echo $row["id"]?>"><?=$obat["obat"]?></span>
                </td>
		<td class="TBL_BODY" align="right">
                    <span id="qty_<?php echo $row["id"]?>"><?=$row["qty"]?></span>
                    <span id="satuan_<?php echo $row["id"]?>"><?=$obat["satuan"]?></span>
                </td>
		<td class="TBL_BODY" align="right"><?=number_format($row["tagihan"],'0','','.')?></td>
		<td class="TBL_BODY" align="right"><span id="penjamin_<?php echo $row["id"]?>"><?=number_format($row["dibayar_penjamin"],'0','','.')?></span></td>
		<td class="TBL_BODY" align="right"><span id="selisih_<?php echo $row["id"]?>"><?=number_format($row["tagihan"]-$row["dibayar_penjamin"],'0','','.')?></span></td>
                <td class="TBL_BODY" align="center"><input type="checkbox" name="cetak_<?php echo $iData ?>" id="cetak_<?php echo $iData ?>" value="<?php echo $row["id"]?>"></td>
                <td class="TBL_BODY" align="center">
                    <a href="#" onClick="return_data_obat('<?php echo $row["id"]?>')">Return</a> &nbsp; | &nbsp;
                    <a href="#" onClick="edit_data_obat('<?php echo $row["id"]?>')">edit</a> &nbsp; | &nbsp;
                <a href="#" onClick="delete_data_obat('<?php echo $row["id"]?>')">delete</a></td>
	</tr>
<?php
        }
        }
?>
	<tr>
		<td class="TBL_HEAD" colspan="4" align="right">T O T A L </td>
		<td class="TBL_HEAD" align="right" ><?=number_format($total,'0','','.')?>&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" ><?=number_format($totalPenjamin,'0','','.')?>&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" ><?=number_format($totalSelisih,'0','','.')?>&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" >&nbsp;&nbsp;</td>
		<td class="TBL_HEAD" align="right" >&nbsp;&nbsp;</td>
	</tr>
</table>
<table  width='100%%'>
    <tr>
        <td class="TBL_BODY" align="center" width='33%'><b>Cetak BHP</b>&nbsp;&nbsp;<a href="javascript: cetakBHP('<? echo $_GET["rg"];?>')" ><img src="images/cetak.gif" border="0"></a></td>
        <td class="TBL_BODY" align="center" width='34%'><b>Cetak Return BHP</b>&nbsp;&nbsp;<a href="javascript: cetakBHPreturn('<? echo $_GET["rg"];?>')" ><img src="images/cetak.gif" border="0"></a></td>
    </tr>	
</table>
<?php
$rowsReturn = pg_query($con, "SELECT id, tanggal_entry, waktu_entry, item_id, qty_return, tagihan, dibayar_penjamin, trans_type  
                             FROM rs00008_return 
                             WHERE trans_type = 'BHP' AND rs00008_return.no_reg = '".$_GET["rg"]."' ");
?>
<div align="LEFT" class="FORM_TITLE"><b>Return</b></div>
<table width='100%'>
	<tr>
		<td class="TBL_HEAD" width='3%'><center>No.</center></td>
		<td class="TBL_HEAD" width='12%' ><center>Tanggal</center></td>
		<td class="TBL_HEAD"><center>Obat</center></td>
		<td class="TBL_HEAD" width='7%'><center>Jumlah</center></td>
		<td class="TBL_HEAD" width='7%'><center>Tagihan</center></td>
		<td class="TBL_HEAD" width='7%'><center>Penjamin</center></td>
		<td class="TBL_HEAD" width='7%'><center>Selisih</center></td>
		<td class="TBL_HEAD" width='7%'><center>Cetak</center></td>
		<td class="TBL_HEAD" width='18%'>&nbsp;</td>
	</tr>
<?php
        $iData          = 0;
        while($row=pg_fetch_array($rowsReturn)){
            $iData++;
            
            $sqlObat = pg_query($con, "SELECT DISTINCT rs00015.id, rs00015.obat, rs00001.tdesc AS satuan, rs00016.harga 
                                        FROM rs00015 
                                        INNER JOIN rs00001 ON rs00015.satuan_id = rs00001.tc 
                                        INNER JOIN rs00016 ON rs00015.id = rs00016.obat_id 
                                        WHERE rs00001.tt = 'SAT' AND rs00015.id = ". $row["item_id"] );
            $obat = pg_fetch_array($sqlObat);
            $arrWaktuEntry = explode('.', $row["waktu_entry"]);
            $arrJamEntry = explode(':', $arrWaktuEntry[0]);
?>
	<tr>
            <td class="TBL_BODY" align="right"><?=$iData?></td>
            <td class="TBL_BODY" align="right"><?=tanggal($row["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry[0].':'.$arrJamEntry[1]?></td>
            <td class="TBL_BODY" align="left"><?=$obat["obat"]?></td>
            <td class="TBL_BODY" align="right"><?=$row["qty_return"]?></td>
            <td class="TBL_BODY" align="right"><?=number_format($row["tagihan"],'0','','.') ?></td>
            <td class="TBL_BODY" align="right"><?=number_format($row["dibayar_penjamin"],'0','','.')?></td>
            <td class="TBL_BODY" align="right"><?=number_format($row["tagihan"]-$row["dibayar_penjamin"],'0','','.')?></td>
            <td class="TBL_BODY" align="right"><input type="checkbox" name="cetak_return_<?php echo $iData ?>" id="cetak_return_<?php echo $iData ?>" value="<?php echo $row["id"]?>"></td>
            <td class="TBL_BODY" align="center">
            <a href="#" onClick="delete_data_obat_return('<?php echo $row["id"]?>')">delete</a></td>
	</tr>
<?php
        }
?>        
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
        $sqlCek = pg_query($con, "SELECT jumlah FROM  rs00005 WHERE reg = '".$rg."' AND kasir = 'RJL' AND layanan = '-' ");
        
        if(pg_num_rows($sqlCek) > 0){
            pg_query($con, "UPDATE  rs00005  SET  jumlah = ".((int)$totalBiayaObatVal-(int)$totalBiayaObatValPenjamin)." 
                WHERE reg = '".$rg."' AND kasir = 'RJL' AND layanan = '-' ");
        }else{
            pg_query($con, "INSERT INTO rs00005 VALUES( nextval('kasir_seq'), '".$rg."', ".
        "CURRENT_DATE, 'RJL', 'Y', 'N', '-', ".((int)$totalBiayaObatVal-(int)$totalBiayaObatValPenjamin).", 'N')");
        }
}
?>
<script>
function cetakBHP(reg) {

    checkedPrint = '';
    for(i=1;i<=50;i++){
        if($('#cetak_'+i).is(':checked')){
            bhpId = $('#cetak_'+i).val();
            checkedPrint = checkedPrint+'&bhp_'+i+'='+bhpId;
        }
    }

    sWin = window.open('includes/cetak.rincian_bhp_ok.php?rg='+reg+''+checkedPrint, 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');
    sWin.focus();
}
function cetakBHPreturn(reg) {

    checkedPrint = '';
    for(i=1;i<=50;i++){
        if($('#cetak_return_'+i).is(':checked')){
            bhpId = $('#cetak_return_'+i).val();
            checkedPrint = checkedPrint+'&bhp_'+i+'='+bhpId;
        }
    }

    sWin = window.open('includes/cetak.rincian_bhp_return_ok.php?rg='+reg+''+checkedPrint, 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');
    sWin.focus();
}
</script>