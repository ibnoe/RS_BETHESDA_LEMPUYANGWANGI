<?php
// sikasep Wildan :)
session_start();
 
//$PID = "post_pend_coa";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/visit_setting.php");

$tglDari    = date('Y-m-d');
$tglSampai  = date('Y-m-d');

if($_GET['tgl_dari'] != ''){
    $tglDari    = $_GET['tgl_dari'];
}

if($_GET['tgl_sampai'] != ''){
    $tglSampai    = $_GET['tgl_sampai'];
}

$rowsTipePasien = pg_query($con, "SELECT tc AS tipe_pasien_id, tdesc AS tipe_pasien_nama FROM rs00001 WHERE tt = 'JEP' and tc != '000' ORDER BY tdesc ASC");

$rowsUnit = pg_query($con, "SELECT DISTINCT rs00001.tc AS poli_id, rs00001.tdesc AS poli_nama 
                            FROM rs00006
                            JOIN rs00001 ON rs00001.tc::text = rs00006.poli::text 
                            WHERE rs00001.tt = 'LYN' AND rs00001.tc = '100' ORDER BY rs00001.tdesc ASC");
?>

<SCRIPT language="JavaScript" src="plugin/jquery-1.8.2.js"></SCRIPT>
<SCRIPT language="JavaScript" src="plugin/jquery-ui.js"></SCRIPT>
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.theme.css">
<LINK rel="stylesheet" type="text/css" href="plugin/jquery.ui.datepicker.css">
<h2>POSTING JURNAL</h2>
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
                    <option value="RAWAT INAP" <?php if($_GET['unit_id'] == 'RAWAT INAP'){ echo 'selected="selected"'; } ?>>RAWAT INAP</option>
                    <option value="RAWAT JALAN" <?php if($_GET['unit_id'] == 'RAWAT JALAN'){ echo 'selected="selected"'; } ?>>RAWAT JALAN</option>
                    
					</select>                    
                </td>
            </tr>
			
            <tr>
                <td>Status</td>
                <td>
                    <select name="status" id="status">
                        <option value=""></option>
                        <option value="LUNAS" <?php if($_GET['status'] == 'LUNAS'){ echo 'selected="selected"'; } ?>>LUNAS</option>
                        <option value="BELUM LUNAS" <?php if($_GET['status'] == 'BELUM LUNAS'){ echo 'selected="selected"'; } ?>>BELUM LUNAS</option>
                        <option value="BELUM DIINPUT" <?php if($_GET['status'] == 'BELUM DIINPUT'){ echo 'selected="selected"'; } ?>>BELUM DIINPUT</option>
						<option value="BELUM DIEKSEKUSI" <?php if($_GET['status'] == 'BELUM DIEKSEKUSI'){ echo 'selected="selected"'; } ?>>BELUM DIEKSEKUSI</option>
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

	echo "<DIV ALIGN=RIGHT>";
	echo "<TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
	echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID >";
	echo "<TD >Pencarian : <INPUT TYPE=TEXT NAME=search VALUE='" . $_GET["search"] . "'></TD>";
	echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

	echo "</TR></FORM></TABLE>";
	echo "</DIV>";

$addParam = '';
if($_GET['tipe_pasien_id'] != ''){
    $addParam = $addParam." AND rs00001.tc = '".$_GET['tipe_pasien_id']."' ";
}



if($_GET['tgl_dari'] != ''){
    $addParam = $addParam." and rs00006.tanggal_reg BETWEEN '".$tglDari."' AND '".$tglSampai."' ";
}
if($_GET['unit_id'] != '' AND $_GET['unit_id']!='RAWAT INAP' AND $_GET['unit_id']!='RAWAT JALAN'){
    $addParam = $addParam." AND rs00006.poli = '".$_GET['unit_id']."' ";
}else if($_GET['unit_id'] == 'RAWAT INAP'){
    $addParam = $addParam." AND rs00006.status_akhir_pasien = '012' ";
}else if($_GET['unit_id'] == 'RAWAT JALAN'){
    $addParam = $addParam." AND rs00006.status_akhir_pasien != '012' AND rs00006.poli !='100' ";
}

if($_GET['search'] != ''){	
	$addParam = $addParam." AND (upper(rs00002.NAMA) LIKE '%" . strtoupper($_GET["search"]) . "%' or rs00006.id like '%" . $_GET['search'] . "%' or rs00002.mr_no like '%" . $_GET["search"] . "%' ) ";
}

$rowsPasien = pg_query($con, "SELECT rs00006.id AS no_reg, 
                                        rs00006.tanggal_reg, 
                                        rs00006.waktu_reg::time(0), 
                                        rs00006.status_akhir_pasien, 
                                        rs00002.mr_no, 
                                        rs00002.nama, 
                                        rs00001.tdesc AS tipe_pasien, 
                                        A.tdesc AS poli,
                                        SUM(rs00008.tagihan) AS tagihan,
                                        SUM(rs00008.dibayar_penjamin) AS penjamin,
                                        (SELECT sum(x.jumlah) AS sum FROM rs00005 x WHERE (kasir = 'BYI' OR kasir = 'BYR' OR kasir = 'BYD') AND x.reg::text = rs00006.id::text) AS bayar,
										rs00006.id AS rg, rs00008.is_coa as coa, rs00008.tgl_coa as tgl_coa
                            FROM rs00006
                            JOIN rs00002 ON rs00002.mr_no = rs00006.mr_no
                            JOIN rs00001 ON rs00006.tipe = rs00001.tc AND rs00001.tt = 'JEP' 
                            JOIN rs00001 A ON A.tc = rs00006.poli::text AND A.tt = 'LYN'
                            JOIN rs00008 ON rs00008.no_reg = rs00006.id::text
                            WHERE ((rs00008.is_coa='0' or rs00008.dibayar_penjamin <= '0' or rs00008.dibayar_penjamin IS NULL  ) and rs00008.is_coa!='1' ".$addParam." )
                            GROUP BY rs00006.id, rs00006.tanggal_reg, rs00002.mr_no, rs00002.nama, rs00001.tdesc, A.tdesc,rs00008.is_coa,rs00008.tgl_coa    
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
            <td align="CENTER" class="TBL_HEAD" width="">Tipe pasien</td>
            <td align="CENTER" class="TBL_HEAD" width="170">Unit Asal</td>
            <td align="CENTER" class="TBL_HEAD" width="170">Unit Akhir</td>
            <td align="CENTER" class="TBL_HEAD" width="80">Tagihan</td>
            <td align="CENTER" class="TBL_HEAD" width="80">Bayar</td>
            <td align="CENTER" class="TBL_HEAD" width="80">Penjamin</td>
            <td align="CENTER" class="TBL_HEAD" width="80">Status <br> Bayar</td>
            <td align="CENTER" class="TBL_HEAD" width="80">Status <br> Posting</td>
            <td align="CENTER" class="TBL_HEAD" width="80">View</td>
        </tr>
    </thead>
    <tbody>
<?php
if($_GET['status'] == ''){        
            if(!empty($rowsPasien)){
                 $i=0;
                 $cekStatus = 'LUNAS';
                 while($row=pg_fetch_array($rowsPasien)){
                     $i++;
                        //$selisih = (int)$row['tagihan']-(int)($row['penjamin']+(int)$row['bayar']);
                        //if((int)$selisih <= 1){
                        if((int)$row['tagihan'] == (int)$row['bayar'] && (int)$row['penjamin'] == '0'){
                            $selisihTxt =  'LUNAS';
                        }else{
                            $selisihTxt =  'BELUM LUNAS';
                        }
						
						//COA
						if($row['coa'] == '0' && $row['tgl_coa'] ==''){
                            $posting =  'BELUM POSTING';
                        }else if($row['coa'] == '1' && $row['tgl_coa'] !='' && (int)$row['tagihan'] != (int)$row['bayar']){
                            $posting =  'SUDAH POSTING BELUM LUNAS';
                        }else{
                            $posting =  'SUDAH POSTING';
                        }
						
                        
                        if($row['tagihan'] == 0){
                            $selisihTxt = 'BELUM DIINPUT'; 
                        }
                        
                        if($row['status_akhir_pasien'] == '012'){
                            $uniteds = 'RAWAT INAP'; 
                        } else {
							$uniteds = $row['poli'];
						}
        ?>
        <tr>
            <td style="border-bottom: solid 1px #000;"><?php echo $i?></td>
            <td style="border-bottom: solid 1px #000;" align="right"><?php echo tanggalShort($row['tanggal_reg'])?>&nbsp;<br/><?php echo $row['waktu_reg']?>&nbsp;</td>
            <td style="border-bottom: solid 1px #000;"><a href="index2.php?p=lap_transaksi_pasien&no_reg=<?php echo $row['no_reg']?>"><?php echo $row['no_reg']?></a></td>
            <td style="border-bottom: solid 1px #000;"><?php echo $row['mr_no']?></td>
            <td style="border-bottom: solid 1px #000;"><?php echo $row['nama']?></td>
            <td style="border-bottom: solid 1px #000;"><?php echo $row['tipe_pasien']?></td>
            <td style="border-bottom: solid 1px #000;"><?php echo $row['poli']?></td>
            <td style="border-bottom: solid 1px #000;"><?php echo $uniteds?></td>
            <td style="border-bottom: solid 1px #000;" align="right"><?php echo number_format($row['tagihan'],'0','','.')?></td>
            <td style="border-bottom: solid 1px #000;" align="right"><?php echo number_format($row['bayar'],'0','','.')?></td>
            <td style="border-bottom: solid 1px #000;" align="right"><?php echo number_format($row['penjamin'],'0','','.')?></td>
            <td style="border-bottom: solid 1px #000;" align="left"> &nbsp;<?php echo $selisihTxt?></td>
            <td style="border-bottom: solid 1px #000;" align="left"> &nbsp;<?php echo $posting?></td>
			<td style="border-bottom: solid 1px #000;" align="right"><?php echo "<A CLASS=TBL_HREF HREF='$SC?p=post_pend&edit=view&unit=".$uniteds."&status=".$selisihTxt."&rg=".$row["rg"]."&pjm=".$row["penjamin"]."'>".icon("view","Proses Posting")."</A>";?></td>
        </tr>
<?php
                 }
            }
			
			
}else{
            if(!empty($rowsPasien)){
                 $n=0;
                 $cekStatus = 'LUNAS';
                 while($row=pg_fetch_array($rowsPasien)){
						if((int)$row['tagihan'] >0 &&(int)$row['penjamin']==0 && (int)$row['bayar']==0){
							$selisihTxt =  'BELUM DIEKSEKUSI';
						} else{
							//$selisih = (int)$row['tagihan']-(int)($row['penjamin']+(int)$row['bayar']);
							//if((int)$selisih <= 1){
							if((int)$row['tagihan'] == (int)$row['bayar'] && (int)$row['penjamin'] == '0'){
								$selisihTxt =  'LUNAS';
							}else{
								$selisihTxt =  'BELUM LUNAS';
							}    
							
							//COA
							if($row['coa'] == '0' && $row['tgl_coa'] ==''){
								$posting =  'BELUM POSTING';
							}else if($row['coa'] == '1' && $row['tgl_coa'] !='' && (int)$row['tagihan'] != (int)$row['bayar']){
								$posting =  'SUDAH POSTING BELUM LUNAS';
							}else{
								$posting =  'SUDAH POSTING';
							}

							
							if($row['tagihan'] == 0){
								$selisihTxt = 'BELUM DIINPUT'; 
							}
							
							if($row['status_akhir_pasien'] == '012'){
								$uniteds = 'RAWAT INAP'; 
							} else {
								$uniteds = $row['poli'];
							}
                        }
						
                        if($_GET['status'] == $selisihTxt){
                            $n++;
?>
        <tr>
            <td style="border-bottom: solid 1px #000;"><?php echo $n?></td>
            <td style="border-bottom: solid 1px #000;" align="right"><?php echo tanggalShort($row['tanggal_reg'])?>&nbsp;<br/><?php echo $row['waktu_reg']?>&nbsp;</td>
            <td style="border-bottom: solid 1px #000;"><a href="index2.php?p=lap_transaksi_pasien&no_reg=<?php echo $row['no_reg']?>"><?php echo $row['no_reg']?></a></td>
            <td style="border-bottom: solid 1px #000;"><?php echo $row['mr_no']?></td>
            <td style="border-bottom: solid 1px #000;"><?php echo $row['nama']?></td>
            <td style="border-bottom: solid 1px #000;"><?php echo $row['tipe_pasien']?></td>
            <td style="border-bottom: solid 1px #000;"><?php echo $row['poli']?></td>
			<td style="border-bottom: solid 1px #000;"><?php echo $uniteds?></td>
            <td style="border-bottom: solid 1px #000;" align="right"><?php echo number_format($row['tagihan'],'0','','.')?></td>
            <td style="border-bottom: solid 1px #000;" align="right"><?php echo number_format($row['bayar'],'0','','.')?></td>
            <td style="border-bottom: solid 1px #000;" align="right"><?php echo number_format($row['penjamin'],'0','','.')?></td>
            <td style="border-bottom: solid 1px #000;" align="left"> &nbsp;<?php echo $selisihTxt?></td>
            <td style="border-bottom: solid 1px #000;" align="left"> &nbsp;<?php echo $posting?></td>
			<td style="border-bottom: solid 1px #000;" align="right"><?php echo "<A CLASS=TBL_HREF HREF='$SC?p=post_pend&edit=view&unit=".$uniteds."&status=".$selisihTxt."&rg=".$row["rg"]."&pjm=".$row["penjamin"]."'>".icon("view","Proses Posting")."</A>";?></td>
        </tr>
<?php        
                        }
                 }
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
		var status          = $("#status").val();
		window.location = 'index2.php?p=post_pend_coa&tgl_dari='+tglDari+'&tgl_sampai='+tglSampai+'&tipe_pasien_id='+tipePasienId+'&unit_id='+unitId+'&status='+status;
	
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
