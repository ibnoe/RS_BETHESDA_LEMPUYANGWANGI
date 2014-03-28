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
							
$rowsStatus = pg_query($con, "select rs00034.id AS poli_id,rs00034.layanan AS poli_nama from rs00034 where rs00034.sumber_pendapatan_id in ('006', '003') order by id ASC");
?>
<SCRIPT language="JavaScript" src="plugin/jquery-1.8.2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="plugin/jquery-ui.js"></SCRIPT>
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.theme.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.datepicker.css">
<h2>Laporan Per Pemeriksaan (Lab dan Radiologi)</h2>
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
                <td>Layanan</td>
                <td>
                    <select name="status_id" id="status_id">
                        <option value=""></option>
                        <?php 
                            while($rowStatus=pg_fetch_array($rowsStatus)){
                                echo '<option value="'.$rowStatus["poli_id"].'"';
                                        if($_GET['unit_id'] == $rowStatus['poli_id']){
                                            echo 'selected="selected"';
                                        }
                                echo '">'.$rowStatus["poli_nama"].'</option>';
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
if($_GET['status_id'] != ''){
    $addParam = $addParam." AND rs00008.item_id = '".$_GET['status_id']."' ";
}



$rowsPasien = pg_query($con, "SELECT rs00006.id AS no_reg, rs00006.tanggal_reg, rs00002.mr_no, rs00002.nama, rs00002.alm_tetap as alamat, rs00001.tdesc AS tipe_pasien, A.tdesc AS poli 
FROM rs00006 
JOIN rs00002 ON rs00002.mr_no = rs00006.mr_no 
JOIN rs00001 ON rs00006.tipe = rs00001.tc AND rs00001.tt = 'JEP' 
JOIN rs00001 A ON A.tc = rs00006.poli::text AND A.tt = 'LYN'
JOIN rs00008 	ON rs00008.no_reg = rs00006.id
JOIN rs00034 	ON rs00008.item_id::text = rs00034.id::text
                            WHERE (rs00006.tanggal_reg BETWEEN '".$tglDari."' AND '".$tglSampai."'  ".$addParam." )
                            ORDER BY rs00006.id ASC    
                            ");
?>
<table id="list-pasien" width="100%">
    <thead>
        <tr>
            <td align="CENTER" class="TBL_HEAD" width="30">No.</td>
            <td align="CENTER" class="TBL_HEAD" width="90">Tgl. Registrasi</td>
            <td align="CENTER" class="TBL_HEAD" width="90">No. Registrasi</td>
            <td align="CENTER" class="TBL_HEAD" width="90">No. MR</td>
            <td align="CENTER" class="TBL_HEAD" width="170">Nama Pasien</td>
            <td align="CENTER" class="TBL_HEAD" width="">Alamat</td>
            <td align="CENTER" class="TBL_HEAD" width="">Tipe pasien</td>
            <td align="CENTER" class="TBL_HEAD" width="170">Unit</td>
        </tr>
    </thead>
    <tbody>
        <?php
            if(!empty($rowsPasien)){
                 $i=0;
                 while($row=pg_fetch_array($rowsPasien)){
                     $i++;
        ?>
        <tr>
            <td><?php echo $i?></td>
            <td align="right"><?php echo tanggalShort($row['tanggal_reg'])?>&nbsp; </td>
            <td><?php echo $row['no_reg']?></td>
            <td><?php echo $row['mr_no']?></td>
            <td><?php echo $row['nama']?></td>
            <td><?php echo $row['alamat']?></td>
            <td><?php echo $row['tipe_pasien']?></td>
            <td><?php echo $row['poli']?></td>
        </tr>
        <?php
                 }
            }
        ?>
    </tbody>    
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
		window.location = 'index2.php?p=lap_rujukan&tgl_dari='+tglDari+'&tgl_sampai='+tglSampai+'&tipe_pasien_id='+tipePasienId+'&unit_id='+unitId;
	
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