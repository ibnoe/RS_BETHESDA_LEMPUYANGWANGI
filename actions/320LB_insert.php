<?php
session_start();
require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");
// cek nilai post is_racikan, jika nilainya = 1, nilai trans_type = 'RCK'
if( (int)$_POST["is_racikan"] == 1){
	$transType = 'RCK';
}else{
    $transType = 'OB1';
}

// Ambil nilai jasa sesuai dengan kategori obatnya
  
$noReg		= $_GET["rg"];
$poli		= 203;
$pemId		= $_POST["id"];
$Hasil		= $_POST["hasil"];
$Keterangan	= $_POST["keterangan"];
$Is_inap	= 'Y';
if(!empty($_GET["del"])){
    pg_query($con, "DELETE FROM c_catatan WHERE id = ".$_POST["id"] );
}

if(!empty($_POST["id"]) && ($_GET["del"] == false)){
// ---------------------- Start Insert/Update Hasil Pemeriksaan  ------------------
   // select status inap 
   $sqlIs = pg_query($con, "SELECT rawat_inap FROM rs00006 WHERE id ='$noReg'");
   $rowIs = pg_fetch_array($sqlIs);
   $Is    = $rowIs['rawat_inap'];
   
   if($_POST["id"] > 0){
	pg_query($con, "INSERT INTO c_catatan (id,no_reg,id_poli,tanggal_entry,waktu_entry,item_id,is_inap,hasil,keterangan) 
                    values(nextval('c_catatan_seq'),'".$noReg."','".$poli."',CURRENT_DATE,CURRENT_TIME, '".$pemId."','".$Is."','".$Hasil."','".$Keterangan."')" );

	}
// ---------------------- End Insert/Update Hasil Pemeriksaan  --------------------

}

$rowsPemeriksaanRawatJalan      = pg_query($con, "select a.id,a.no_reg,a.tanggal_entry,a.waktu_entry,a.item_id, b.jenis,b.parameter,a.hasil,b.satuan,b.rentang_normal,a.keterangan 
		  from c_catatan a
		  left join c_pemeriksaan_lab b on a.item_id = b.id
		  where a.no_reg ='".$_GET['rg']."' and a.id_poli = '203' and is_inap!='I' order by id");

$rowsPemeriksaanRawatInap   = pg_query($con, "select a.id,a.no_reg,a.tanggal_entry,a.waktu_entry,a.item_id, b.jenis,b.parameter,a.hasil,b.satuan,b.rentang_normal,a.keterangan 
		  from c_catatan a
		  left join c_pemeriksaan_lab b on a.item_id = b.id
		  where a.no_reg ='".$_GET['rg']."' and a.id_poli = '203' and is_inap='I' order by id");

?>
<!-- ---------------------- Start Buat tabel hasil input hasil pemeriksaan -------------------->
<table width='100%'>
	<tr>
		<td class="TBL_HEAD" width='3%'><center>No.</center></td>
		<td class="TBL_HEAD" width='16%' ><center>Tanggal</center></td>
		<td class="TBL_HEAD"><center>Nama Pemeriksaan</center></td>
		<td class="TBL_HEAD" width='8%'><center>Hasil</center></td>
		<td class="TBL_HEAD" width='18%'><center>Rentang Normal</center></td>
		<td class="TBL_HEAD" width='8%'><center>Satuan</center></td>
		<td class="TBL_HEAD" width='15%'><center>Keterangan</center></td>
		<td class="TBL_HEAD" width='10%'><center>Cetak <input type="checkbox" id="check_all_obat"></center></center></td>
		<td class="TBL_HEAD" width='10%'>&nbsp;</td>
	</tr>	
        <tr>
		<td class="TBL_BODY" colspan="9"><span style="font-weight: bold;">Hasil Pemeriksaan Rawat Jalan</span></td>
	</tr>
<?php
        $iData          = 0;
        $iObat          = 0;
        while($row=pg_fetch_array($rowsPemeriksaanRawatJalan)){
            $iData++;
            $iObat++;
            
            $sqlObat = pg_query($con, "SELECT DISTINCT c_pemeriksaan_lab.id,c_pemeriksaan_lab.parameter,c_pemeriksaan_lab.satuan,c_pemeriksaan_lab.rentang_normal
    FROM c_pemeriksaan_lab 
    WHERE is_group='N' AND id=". $row["item_id"] );
            $obat = pg_fetch_array($sqlObat);
            $arrWaktuEntry = explode('.', $row["waktu_entry"]);
            $arrJamEntry = explode(':', $arrWaktuEntry[0]);
?>
	<tr>
	<td class="TBL_BODY" align="right"><?=$iObat?></td>
	<td class="TBL_BODY" align="right"><?=tanggal($row["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry[0].':'.$arrJamEntry[1]?></td>
	<td class="TBL_BODY" align="left">
             <input type="hidden" id="id<?php echo $row["id"]?>" value="<?=$obat["id"]?>" />
             <span id="parameter_<?php echo $row["id"]?>"><?=$obat["parameter"]?></span>
        </td>
	<td class="TBL_BODY" align="left">
        <span id="hasil_<?php echo $row["id"]?>"><?=$row["hasil"]?></span>
        </td>
	<td class="TBL_BODY" align="left"><span id="rentang_normal_<?php echo $row["id"]?>"><?=$obat["rentang_normal"]?></span></td>
	<td class="TBL_BODY" align="left">
	<span id="satuan_<?php echo $row["id"]?>"><?=$obat["satuan"]?></td>
	<td class="TBL_BODY" align="left">
        <span id="keterangan_<?php echo $row["id"]?>"><?=$row["keterangan"]?></span>
        </td>
                <td class="TBL_BODY" align="center"><input type="checkbox" class="check_obat" name="cetak_<?php echo $iData ?>" id="cetak_<?php echo $iData ?>" value="<?php echo $row["id"]?>"></td>
                <td class="TBL_BODY" align="center">
                    <a href="index2.php?p=p_laboratorium&list=pemeriksaan&rg=<?=$_GET["rg"]?>&poli=203&mr=<?=$_GET["mr"]?>&editlab=edit&id=<?=$row["id"]?>&item_id=<?=$row["item_id"]?>">edit</a> &nbsp; | &nbsp;
                <a href="index2.php?p=p_laboratorium&list=pemeriksaan&rg=<?=$_GET["rg"]?>&poli=203&mr=<?=$_GET["mr"]?>&deletelab=edit&id=<?=$row["id"]?>&item_id=<?=$row["item_id"]?>">delete</a></td>
	</tr>
<?php
        }
?>
            <tr>
		<td class="TBL_BODY" colspan="9"><span style="font-weight: bold;">Hasil Pemeriksaan Rawat Inap</span></td>
	</tr>
    <?php
        $iRacikan         = 0;
        while($rowRacikan = pg_fetch_array($rowsPemeriksaanRawatInap)){
            $iRacikan++;
            $iData++;
            
            $sqlObatR = pg_query($con, "SELECT DISTINCT c_pemeriksaan_lab.id,c_pemeriksaan_lab.parameter,c_pemeriksaan_lab.satuan,c_pemeriksaan_lab.rentang_normal
FROM c_pemeriksaan_lab WHERE is_group='N' AND id=". $rowRacikan["item_id"] );
            $obatR = pg_fetch_array($sqlObatR);
            $arrWaktuEntry2 = explode('.', $rowRacikan["waktu_entry"]);
            $arrJamEntry2 = explode(':', $arrWaktuEntry2[0]);
      
?>
	<tr>
            
		<td class="TBL_BODY" align="right"><?=$iRacikan?></td>
		<td class="TBL_BODY" align="right"><?=tanggal($rowRacikan["tanggal_entry"]). ' &nbsp; ' .$arrJamEntry2[0].':'.$arrJamEntry2[1]?></td>
		<td class="TBL_BODY" align="left">
			<input type="hidden" id="id_<?php echo $rowRacikan["id"]?>" value="<?=$obatR["id"]?>" />
			<span id="parameter_<?php echo $rowRacikan["id"]?>"><?=$obatR["parameter"]?></span></td>
		<td class="TBL_BODY" align="left">
        <span id="hasil_<?php echo $rowRacikan["id"]?>"><?=$rowRacikan["hasil"]?></span>
        </td>
	<td class="TBL_BODY" align="left"><span id="rentang_normal_<?php echo $rowRacikan["id"]?>"><?=$obatR["rentang_normal"]?></span></td>
	<td class="TBL_BODY" align="left">
	<span id="satuan_<?php echo $rowRacikan["id"]?>"><?=$obatR["satuan"]?></td>
	<td class="TBL_BODY" align="left">
        <span id="keterangan_<?php echo $rowRacikan["id"]?>"><?=$rowRacikan["keterangan"]?></span>
        </td>
                <td class="TBL_BODY" align="center"><input type="checkbox" class="check_obat" name="cetak_<?php echo $iData ?>" id="cetak_<?php echo $iData ?>" value="<?php echo $rowRacikan["id"]?>"></td>
                <td class="TBL_BODY" align="center">
                    <a href="index2.php?p=p_laboratorium&list=pemeriksaan&rg=<?=$_GET["rg"]?>&poli=203&mr=<?=$_GET["mr"]?>&editlab=edit&id=<?=$rowRacikan["id"]?>&item_id=<?=$rowRacikan["item_id"]?>">edit</a> &nbsp; | &nbsp;
                <a href="index2.php?p=p_laboratorium&list=pemeriksaan&rg=<?=$_GET["rg"]?>&poli=203&mr=<?=$_GET["mr"]?>&deletelab=edit&id=<?=$rowRacikan["id"]?>&item_id=<?=$rowRacikan["item_id"]?>">delete</a></td>
	</tr>
<?php
        }
        echo '<input type="hidden" name="max_i" id="max_i" value="'.$iData.'">';
?>        
	
</table>
<table  width='100%%'>
    <tr>
        <td class="TBL_BODY" align="center" width='33%'><b>Cetak Rawat Jalan</b>&nbsp;&nbsp;<a href="javascript: cetakkwitansi2(<? echo (int) $_GET["rg"];?>)" ><img src="images/cetak.gif" border="0"></a></td>
	<td class="TBL_BODY" align="center" width='33%'><b>Cetak Hasil Selected</b>&nbsp;&nbsp;<a href="javascript: cetakTransaksiSelected(<? echo (int) $_GET["rg"];?>)" ><img src="images/cetak.gif" border="0"></a></td>
        <td class="TBL_BODY" align="center" width='33%'><b>Cetak Rawat Inap</b>&nbsp;&nbsp;<a href="javascript: cetakTransaksi(<? echo (int) $_GET["rg"];?>)" ><img src="images/cetak.gif" border="0"></a></td>
    </tr>	
</table>

<!-- ---------------------- End Buat tabel hasil input obat -------------------- -->

<script>
   
    $('#check_all_obat').click(function(){
        if($('#check_all_obat').is(':checked')){
            $('.check_obat').attr("checked",true);
        }else{
            $('.check_obat').attr("checked",false);
        }        
    })
    
    function cetakTransaksi() {
        maxI = $('#max_i').val();
        selectedToPrint = '&max_cetak='+maxI;
        for(i=0;i<=maxI;i++){
            if($('#cetak_'+i).is(':checked') == true){
                obatSelected = $('#cetak_'+i).val();
                selectedToPrint = selectedToPrint+'&cetak_'+i+'='+obatSelected;
            }
        }
        window.open('includes/cetak.pemeriksaan_rawatinap.php?rg=<?php echo $_GET['rg']?>'+selectedToPrint, 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');
    }
	
	function cetakTransaksiSelected() {
        maxI = $('#max_i').val();
        selectedToPrint = '&max_cetak='+maxI;
        for(i=0;i<=maxI;i++){
            if($('#cetak_'+i).is(':checked') == true){
                obatSelected = $('#cetak_'+i).val();
                selectedToPrint = selectedToPrint+'&cetak_'+i+'='+obatSelected;
            }
        }
        window.open('includes/cetak.pemeriksaan_selected.php?rg=<?php echo $_GET['rg']?>'+selectedToPrint, 'xWin','top=0,left=0,width=750,height=550,menubar=no,location=no,scrollbars=yes');

    }

</script>
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
