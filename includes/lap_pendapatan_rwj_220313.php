<?php
session_start();
require_once("lib/dbconn.php");

$tglDari    = date('Y-m-d');
$tglSampai  = date('Y-m-d');

if($_GET['tgl_dari'] != ''){
    $tglDari    = $_GET['tgl_dari'];
}

if($_GET['tgl_sampai'] != ''){
    $tglSampai    = $_GET['tgl_sampai'];
}

$rowsTipePasien = pg_query($con, "SELECT tc AS tipe_pasien_id, tdesc AS tipe_pasien_nama FROM rs00001 WHERE tt = 'JEP' ORDER BY tdesc ASC");
$rowsUnit = pg_query($con, "SELECT DISTINCT rs00001.tc AS poli_id, rs00001.tdesc AS poli_nama 
                            FROM rs00006
                            JOIN rs00001 ON rs00001.tc::text = rs00006.poli::text 
                            WHERE rs00001.tt = 'LYN' ORDER BY rs00001.tdesc ASC");
$rowsShiftKerja	= pg_query($con, "SELECT tc_tipe as shift_id, tdesc AS nama_shift FROM rs00001 WHERE tt = 'SHI' AND tc!='000' ORDER BY tdesc ASC");
?>
<SCRIPT language="JavaScript" src="plugin/jquery-1.8.2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="plugin/jquery-ui.js"></SCRIPT>
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.theme.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.datepicker.css">
<h2>Laporan Pendapatan Rawat Jalan</h2>
<form action="#" method="post" id="table_form" class="">
	<input type="hidden" name="tgl_dari" id="tgl_dari" value="<?php echo $tglDari?>"  /> &nbsp; 
	<input type="hidden" name="tgl_sampai" id="tgl_sampai" value="<?php echo $tglSampai?>"  />
        <table>
            <tr>
                <td>
                    Mulai Tgl</td>
                <td>
                    <input type="text" name="tgl_dari_show" id="tgl_dari_show" size="25" value="<?php echo tanggal($tglDari)?>"  /> &nbsp;&nbsp;
                    Sampai Tgl : <input type="text" name="tgl_sampai_show" id="tgl_sampai_show" size="25" value="<?php echo tanggal($tglSampai)?>"  />  
                </td>
            </tr>
            <tr>
                <td>Tipe Pasien</td>
                <td>
                    <select name="tipe_pasien_id" id="tipe_pasien_id">
                        <option value=""></option>
                        <?php 
                            while($rowTipePasien=pg_fetch_array($rowsTipePasien)){
                                echo '<option value="'.$rowTipePasien["tipe_pasien_id"].'"';
                                        if($_GET['tipe_pasien_id'] == $rowTipePasien['tipe_pasien_id']){
                                            echo 'selected="selected"';
                                        }
                                echo '">'.$rowTipePasien["tipe_pasien_nama"].'</option>';
                            }
                        ?>
                        <option value=""></option>
                    </select>                    
                </td>
            </tr>
            <tr>
                <td>Unit</td>
                <td>
                    <select name="unit_id" id="unit_id">
                        <option value=""></option>
                        <?php 
                            while($rowUnit=pg_fetch_array($rowsUnit)){
                                echo '<option value="'.$rowUnit["poli_id"].'"';
                                        if($_GET['unit_id'] == $rowUnit['poli_id']){
                                            echo 'selected="selected"';
                                        }
                                echo '">'.$rowUnit["poli_nama"].'</option>';
                            }
                        ?>
                        <option value=""></option>
                    </select>                    
                </td>
            </tr>
	    <tr>
                <td>Shift</td>
                <td>
                    <select name="shiftkerja" id="shiftkerja">
                        <option value=""></option>
			<?php
			 while($rowShiftKerja=pg_fetch_array($rowsShiftKerja)){
                                echo '<option value="'.$rowShiftKerja["shift_id"].'"';
                                        if($_GET['shiftkerja'] == $rowShiftKerja['shift_id']){
                                            echo 'selected="selected"';
                                        }
                                echo '">'.$rowShiftKerja["nama_shift"].'</option>';
                            }
			?>
			<option value=""></option>
                    </select>                    
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><button id="btn_filter" type="button">Tampilkan</button></td>
            </tr>
        </table>
</form>   
<?php
$addParam = '';
if($_GET['tipe_pasien_id'] != ''){
    $addParam = $addParam." AND rs00001.tc = '".$_GET['tipe_pasien_id']."' ";
}
if($_GET['unit_id'] != ''){
    $addParam = $addParam." AND rs00006.poli = '".$_GET['unit_id']."' ";
}
$shiftId=$_GET['shiftkerja'];
//var_dump($shiftId);
if($_GET['shiftkerja'] != ''){    
    if ($_GET["shiftkerja"]=="P"){
	$jam1="07:00:00";
	$jam2="14:00:00";
	$addParam = $addParam." AND (rs00006.waktu_reg::text between '$jam1' and '$jam2') ";
	}elseif($_GET["shiftkerja"]=="S"){
	$jam1="14:01:00";
	$jam2="21:00:00";
	$addParam = $addParam." AND (rs00006.waktu_reg::text between '$jam1' and '$jam2') ";
	}elseif($_GET["shiftkerja"]=="M1"){
	$jam1="21:01:00";
	$jam2="23:59:00";
	$addParam = $addParam." AND (rs00006.waktu_reg::text between '$jam1' and '$jam2') ";
	}elseif($_GET["shiftkerja"]=="M2"){
	$jam1="00:00:00";
	$jam2="06:59:00";
	$addParam = $addParam." AND (rs00006.waktu_reg::text between '$jam1' and '$jam2') ";
	}else{
	$jam1="00:00:00";
	$jam2="23:59:59";
	$addParam = $addParam." AND (rs00006.waktu_reg between '$jam1' and '$jam2') ";
	}
}

$rowsPasien = pg_query($con, "SELECT rs00006.id AS no_reg, 
                                     rs00006.tanggal_reg, 
                                     rs00002.mr_no, 
                                     rs00002.nama, 
                                     A.tdesc AS poli,
                                     (SELECT sum(e.tagihan) AS sum FROM rs00008 e WHERE e.no_reg::text = rs00006.id::text AND e.trans_type::text = 'BHP'::text ) AS bhp,
                                     (SELECT sum(f.tagihan) AS sum FROM rs00008 f WHERE f.no_reg::text = rs00006.id::text AND (f.trans_type::text = 'OB1'::text OR f.trans_type::text = 'RCK'::text) ) AS obat,
                                     (SELECT sum(g.jumlah) AS sum FROM rs00005 g WHERE g.reg::text = rs00006.id::text AND (g.kasir::text = 'BYR'::text OR g.kasir::text = 'BYD'::text) ) AS bayar_tunai,
                                     (SELECT sum(h.jumlah) AS sum FROM rs00005 h WHERE h.reg::text = rs00006.id::text AND h.kasir::text = 'ASK'::text) AS bayar_askes,
                                     (SELECT sum(i.jumlah) AS sum FROM rs00005 i WHERE i.reg::text = rs00006.id::text AND i.kasir::text = 'POT'::text) AS bayar_potongan
                            FROM rs00006
                            JOIN rs00002 ON rs00002.mr_no = rs00006.mr_no
                            JOIN rs00001 ON rs00006.tipe = rs00001.tc AND rs00001.tt = 'JEP' 
                            JOIN rs00001 A ON A.tc = rs00006.poli::text AND A.tt = 'LYN'
                            WHERE rs00006.status_akhir_pasien <> '012' AND (rs00006.tanggal_reg BETWEEN '".$tglDari."' AND '".$tglSampai."'  ".$addParam." )
                            ORDER BY rs00006.id ASC    
                            ");

$resultSumberPendapatan = pg_query($con,"select tdesc from rs00001 where tt='SBP' and tc !='000' order by tc");
while ($rowSumberPendapatan = pg_fetch_array($resultSumberPendapatan)) {
    $arrSumberPendapatan[] = $rowSumberPendapatan;
}
?>
<table id="list-pasien" width="100%">
        <tr>
            <td rowspan="2" class="TBL_HEAD" align="center" width="3%">NO.</td>
            <td rowspan="2" class="TBL_HEAD" align="center" width="6%">TANGGAL</td>
            <td rowspan="2" class="TBL_HEAD" align="center" width="6%">NO. REG</td>
            <td rowspan="2" class="TBL_HEAD" align="center" width="6%">NO. MR</td>
            <td rowspan="2" class="TBL_HEAD" align="center" width="15%">NAMA</td>
            <td rowspan="2" class="TBL_HEAD" align="center" width="15%">POLI DAFTAR</td>
            <td colspan="16" class="TBL_HEAD" align="center">RINCIAN TAGIHAN</td>
            <td rowspan="2" class="TBL_HEAD" align="center" width="7%">TOTAL TAGIHAN</td>
            <td colspan="4" class="TBL_HEAD" align="center">PEMBAYARAN</td>
        </tr>
        <tr>
            <?php
            foreach ($arrSumberPendapatan as $key => $val) {
                if ($val["tdesc"] == 'FISIOTERAPHI/NEBULIZER') {
                    echo '<td class="TBL_HEAD" align="center" width="7%">FISIOTERAPHI<br/>NEBULIZER</td>';
                } else {
                    echo '<td class="TBL_HEAD" align="center" width="7%">' . $val["tdesc"] . '</td>';
                }
            }
            ?>
            <td class="TBL_HEAD" align="center" width="7%">BHP</td>
            <td class="TBL_HEAD" align="center" width="7%">OBAT</td>
            <td class="TBL_HEAD" align="center" width="7%">PAKET</td>
            <td class="TBL_HEAD" align="center" width="7%">TUNAI</td>
            <td class="TBL_HEAD" align="center" width="7%">POTONGAN</td>
            <td class="TBL_HEAD" align="center" width="7%">PENJAMIN</td>
            <td class="TBL_HEAD" align="center" width="7%">PIUTANG PASIEN</td>
        </tr>
        <?php
            if(!empty($rowsPasien)){
                 $i=0;
                 while($row=pg_fetch_array($rowsPasien)){
                     if($row["bayar_tunai"] > 0 || $row["bayar_potongan"] > 0  || $row["bayar_askes"] > 0 ){
                     $i++;
        ?>
        <tr>
            <td><?php echo $i?></td>
            <td align="right"><?php echo tanggalShort($row['tanggal_reg'])?>&nbsp; </td>
            <td><a href="index2.php?p=lap_transaksi_pasien&no_reg=<?php echo $row['no_reg']?>"><?php echo $row['no_reg']?></a></td>
            <td><?php echo $row['mr_no']?></td>
            <td><?php echo $row['nama']?></td>
            <td><?php echo $row['poli']?></td>
        
            <?php
    $j = 0;
    $totalJMLSumberPendapatan = 0;
    foreach ($arrSumberPendapatan as $key => $val) {
        $j++;
        $sqlJMLSumberPendapatan = "SELECT sum(a.tagihan) as jumlah 
                                FROM rs00008 a
                                LEFT JOIN rs00034 b on b.id = a.item_id::numeric
                                LEFT JOIN rs00001 c on c.tc = b.sumber_pendapatan_id and c.tt='SBP'  
                                WHERE a.no_reg='" . $row["no_reg"] . "' AND (a.trans_type='LTM') and upper(c.tdesc) like '%" . strtoupper($val["tdesc"]) . "%'";

        $resultJMLSumberPendapatan = pg_query($con,
                $sqlJMLSumberPendapatan);

        while ($rowJMLSumberPendapatan = pg_fetch_array($resultJMLSumberPendapatan)) {
            $jumlah = $rowJMLSumberPendapatan["jumlah"];
            $totalJMLSumberPendapatan = $totalJMLSumberPendapatan + $jumlah;
            echo '<td class="TBL_BODY" align="right" id="val_' . $i . '_' . $j . '">';
            echo number_format($jumlah,
                    0,
                    " ",
                    ".");
            echo '</td>';
        }
    }
    $totalPembayaran = $row["bayar_tunai"] + $row["bayar_potongan"] + $row["bayar_askes"];
    ?>
            <td class="TBL_BODY" align="right"><?php echo number_format($row["bhp"],
            0,
            " ",
            ".") ?>  </td>
            <td class="TBL_BODY" align="right" id="val_obat_<?php echo $i ?>"><?php echo number_format($row["obat"],
            0,
            " ",
            ".") ?> </td>
            <td class="TBL_BODY" align="right"><?php echo number_format(0,
            0,
            " ",
            ".") ?> </td>
            <td class="TBL_BODY" align="right" id="val_tagihan_<?php echo $i ?>"><?php echo number_format($totalJMLSumberPendapatan + $row["obat"],
            0,
            " ",
            ".") ?> </td>
            <td class="TBL_BODY" align="right" id="val_tunai_<?php echo $i ?>"><?php echo number_format($row["bayar_tunai"],
            0,
            " ",
            ".") ?> </td>
            <td class="TBL_BODY" align="right" id="val_potongan_<?php echo $i ?>"><?php echo number_format($row["bayar_potongan"],
            0,
            " ",
            ".") ?> </td>
            <td class="TBL_BODY" align="right" id="val_askes_<?php echo $i ?>"><?php echo number_format($row["bayar_askes"],
            0,
            " ",
            ".") ?> </td>
            <td class="TBL_BODY" align="right" id="val_sisa_<?php echo $i ?>"><?php echo number_format(($totalJMLSumberPendapatan + $row["obat"]) - $totalPembayaran,
            0,
            " ",
            ".") ?> </td>
            </tr>
        <?php
                     }
                 }
            }
        ?>
    <tr>
        <td colspan="6" class="TBL_HEAD" align="right">J U M L A H</td>
        <td class="TBL_HEAD" align="right" id="jumlah_visite"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_alat"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_radiologi"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_tindakan"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_konsultasi"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_lab"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_ambulance"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_esg"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_oksigen"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_fisio"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_admin"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_lain"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_periksa"></td>
        <td class="TBL_HEAD" align="right" id="">0</td>
        <td class="TBL_HEAD" align="right" id="jumlah_obat"></td>
        <td class="TBL_HEAD" align="right" id="">0</td>
        <td class="TBL_HEAD" align="right" id="jumlah_tagihan"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_tunai"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_potongan"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_askes"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_sisa"></td>
    </tr>
</table>
<script language="JavaScript" type="text/JavaScript">

$(document).ready(function() { 

	$("#tgl_dari_show").datepicker({
		showOn: 'button', 
		buttonImage: 'images/calendar.gif', 
		dateFormat: 'd MM yy',
		buttonImageOnly: true,
		altField: '#tgl_dari', altFormat: 'yy-mm-dd'
	});

	$("#tgl_sampai_show").datepicker({
		showOn: 'button', 
		buttonImage: 'images/calendar.gif', 
		dateFormat: 'd MM yy',
		buttonImageOnly: true,
		altField: '#tgl_sampai', altFormat: 'yy-mm-dd'
	});

	$("#btn_filter").click(function(){
		var tglDari         = $("#tgl_dari").val();
		var tglSampai       = $("#tgl_sampai").val();
		var tipePasienId    = $("#tipe_pasien_id").val();
		var unitId          = $("#unit_id").val();
		var shiftId         = $("#shiftkerja").val();
		window.location = 'index2.php?p=lap_pendapatan_rwj&tgl_dari='+tglDari+'&tgl_sampai='+tglSampai+'&tipe_pasien_id='+tipePasienId+'&unit_id='+unitId+'&shiftkerja='+shiftId;
	
	});

	$("#btn_print").click(function(){
		$("#laporan-transaksi").printElement();

	});
	$("#btn_download").click(function(){
		var tglDari		= $("#tgl_dari").val();
		var tglSampai	= $("#tgl_sampai").val();
		window.location = '<? //=base_url()?>index.php/laporan/transaksi/index/'+tglDari+'/'+tglSampai+'/false/true';
	});
});

   totalVisite		= 0;
    totalAlat		= 0;
    totalRadiologi	= 0;
    totalTindakan	= 0;
    totalKonsultasi	= 0;
    totalLab		= 0;
    totalAmbulance	= 0;
    totalESG		= 0;
    totalOksigen	= 0;
    totalFisio		= 0;
    totalAdmin		= 0;
    totalLain		= 0;
    totalPeriksa	= 0;
    totalObat		= 0;
    totalTagihan	= 0;
    totalTunai		= 0;
    totalPotongan	= 0;
    totalAskes		= 0;
    totalSisa		= 0;
    for(i=1;i<=<?php echo $i ?>;i++){
        visiteTmp = $('#val_'+i+'_1').text();
        visite = parseInt(visiteTmp.replace('.',''));
        totalVisite = totalVisite+visite;

        alatTmp = $('#val_'+i+'_2').text();
        alat = parseInt(alatTmp.replace('.',''));
        totalAlat = totalAlat+alat;

        radiologiTmp = $('#val_'+i+'_3').text();
        radiologi = parseInt(radiologiTmp.replace('.',''));
        totalRadiologi = totalRadiologi+radiologi;

        tindakanTmp = $('#val_'+i+'_4').text();
        tindakan = parseInt(tindakanTmp.replace('.',''));
        totalTindakan = totalTindakan+tindakan;

        konsultasiTmp = $('#val_'+i+'_5').text();
        konsultasi = parseInt(konsultasiTmp.replace('.',''));
        totalKonsultasi = totalKonsultasi+konsultasi;

        labTmp = $('#val_'+i+'_6').text();
        lab = parseInt(labTmp.replace('.',''));
        totalLab = totalLab+lab;

        ambulanceTmp = $('#val_'+i+'_7').text();
        ambulance = parseInt(ambulanceTmp.replace('.',''));
        totalAmbulance = totalAmbulance+ambulance;

        esgTmp = $('#val_'+i+'_8').text();
        esg = parseInt(esgTmp.replace('.',''));
        totalESG = totalESG+esg;

        oksigenTmp = $('#val_'+i+'_9').text();
        oksigen = parseInt(oksigenTmp.replace('.',''));
        totalOksigen = totalOksigen+oksigen;

        fisioTmp = $('#val_'+i+'_10').text();
        fisio = parseInt(fisioTmp.replace('.',''));
        totalFisio = totalFisio+fisio;

        adminTmp = $('#val_'+i+'_11').text();
        admin = parseInt(adminTmp.replace('.',''));
        totalAdmin = totalAdmin+admin;

        lainTmp = $('#val_'+i+'_12').text();
        lain = parseInt(lainTmp.replace('.',''));
        totalLain = totalLain+lain;

        periksaTmp = $('#val_'+i+'_13').text();
        periksa = parseInt(periksaTmp.replace('.',''));
        totalPeriksa = totalPeriksa+periksa;

        obatTmp = $('#val_obat_'+i).text();
        obat = parseInt(obatTmp.replace('.',''));
        totalObat = totalObat+obat;

        tagihanTmp = $('#val_tagihan_'+i).text();
        tagihan = parseInt(tagihanTmp.replace('.',''));
        totalTagihan = totalTagihan+tagihan;

        tunaiTmp = $('#val_tunai_'+i).text();
        tunai = parseInt(tunaiTmp.replace('.',''));
        totalTunai = totalTunai+tunai;

        potonganTmp = $('#val_potongan_'+i).text();
        potongan = parseInt(potonganTmp.replace('.',''));
        totalPotongan = totalPotongan+potongan;

        askesTmp = $('#val_askes_'+i).text();
        askes = parseInt(askesTmp.replace('.',''));
        totalAskes = totalAskes+askes;

        sisaTmp = $('#val_sisa_'+i).text();
        sisa = parseInt(sisaTmp.replace('.',''));
        totalSisa = totalSisa+sisa;
    }

    $('#jumlah_visite').text(totalVisite);
    $('#jumlah_alat').text(totalAlat);
    $('#jumlah_radiologi').text(totalRadiologi);
    $('#jumlah_tindakan').text(totalTindakan);
    $('#jumlah_konsultasi').text(totalKonsultasi);
    $('#jumlah_lab').text(totalLab);
    $('#jumlah_ambulance').text(totalAmbulance);
    $('#jumlah_esg').text(totalESG);
    $('#jumlah_oksigen').text(totalOksigen);
    $('#jumlah_fisio').text(totalFisio);
    $('#jumlah_admin').text(totalAdmin);
    $('#jumlah_lain').text(totalLain);
    $('#jumlah_periksa').text(totalPeriksa);
    $('#jumlah_obat').text(totalObat);
    $('#jumlah_tagihan').text(totalTagihan);
    $('#jumlah_tunai').text(totalTunai);
    $('#jumlah_potongan').text(totalPotongan);
    $('#jumlah_askes').text(totalAskes);
    $('#jumlah_sisa').text(totalSisa);

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

function tanggalShort($tanggal) {
    $arrTanggal = explode('-', $tanggal);

    $hari = $arrTanggal[2];
    $bulan = $arrTanggal[1];
    $tahun = $arrTanggal[0];

    $result = $hari . ' ' . substr(bulan($bulan),0,3) . ' ' . $tahun;

    return $result;
}

function bulan($params) {
    switch ($params) {
        case 1:
            $bln = "Januari";
            break;
        case 2:
            $bln = "Pebruari";
            break;
        case 3:
            $bln = "Maret";
            break;
        case 4:
            $bln = "April";
            break;
        case 5:
            $bln = "Mei";
            break;
        case 6:
            $bln = "Juni";
            break;
        case 7:
            $bln = "Juli";
            break;
        case 8:
            $bln = "Agustus";
            break;
        case 9:
            $bln = "September";
            break;
        case 10:
            $bln = "Oktober";
            break;
        case 11:
            $bln = "Nopember";
            break;
        case 12:
            $bln = "Desember";
            break;
            break;
    }
    return $bln;
}
?>
