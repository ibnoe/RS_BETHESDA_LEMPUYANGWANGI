<?php
session_start();
require_once("lib/dbconn.php");
$PID = 'lap_pendapatan_rwj_rwi';
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/form.php");
?>

<link rel="stylesheet" type='text/css' href="jquery-ui.custom.css"/>
<script type="text/javascript" src="plugin/jquery-1.8.2.js"></script>
<script type="text/javascript" src="plugin/jquery-ui.js"></script>
<script language="javascript">
$(document).ready(function(){
$("input:text[name='dokter']").autocomplete({
		type:'GET',
		source:function(request,response){
		$.ajax({
			url:'./lib/getPegawai.php',
			data: {term : request.term},
			dataType : 'json',
			success : function(data){
				response(data);
			},
		});
	},
		selectFirst: true,
		select: function( event, ui ) {
			$("input:hidden[name='id_dokter']").val(ui.item.id);
		},
	});
});	
</script>	

<?php

//--start
if (!$GLOBALS['print']){
    title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > <b>LAPORAN PENDAPATAN</b>");
    title_excel("lap_pendapatan_rwj_rwi&tanggal1D=".$_GET['tanggal1D']."&tanggal1M=".$_GET['tanggal1M']."&tanggal1Y=".$_GET['tanggal1Y']."&tanggal2D=".$_GET['tanggal2D']."&tanggal2M=".$_GET['tanggal2M']."&tanggal2Y=".$_GET['tanggal2Y']."&rawat_inap=".$_GET['rawat_inap']."&mRAWAT=".$_GET['mRAWAT']."&dokter=".$_GET['dokter']."&id_dokter=".$_GET['id_dokter']."&mPASIEN=".$_GET['mPASIEN']."");
} else {
    title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Pendapatan");
    title_excel("lap_pendapatan_rwj_rwi&tanggal1D=".$_GET['tanggal1D']."&tanggal1M=".$_GET['tanggal1M']."&tanggal1Y=".$_GET['tanggal1Y']."&tanggal2D=".$_GET['tanggal2D']."&tanggal2M=".$_GET['tanggal2M']."&tanggal2Y=".$_GET['tanggal2Y']."&rawat_inap=".$_GET['rawat_inap']."&mRAWAT=".$_GET['mRAWAT']."&dokter=".$_GET['dokter']."&id_dokter=".$_GET['id_dokter']."&mPASIEN=".$_GET['mPASIEN']."");
}

//form filter
$ext = "OnChange = 'Form1.submit();'";
$f = new Form($SC, "GET", "NAME=Form1");
$f->PgConn = $con;
$f->hidden("p", $PID);

//---------------------------------------------------------------------------------------------
if (!$GLOBALS['print']) {
	    if (!isset($_GET['tanggal1D'])) {

		$tanggal1D = date("d", time());
		$tanggal1M = date("m", time());
		$tanggal1Y = date("Y", time());
		$tanggal2D = date("d", time());
		$tanggal2M = date("m", time());
		$tanggal2Y = date("Y", time());

	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
		$ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
		$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");

	    } else {
		$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
		$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
		
	    }
		$f->selectArray("rawat_inap", "<font color='red'>* U n i t</font>",Array(""=>"", "N" => "IGD", "Y" => "Rawat Jalan",  "I" => "Rawat Inap"),
                     $_GET[rawat_inap], "onChange='document.Form1.submit();'; ");
		$_GET["rawat_inap"] = strlen($_GET["rawat_inap"]) == "" ? "" : $_GET["rawat_inap"];
		if ($_GET["rawat_inap"]=="Y"){
		$f->selectSQL("mRAWAT", "Poli","select '' as tc, '' as tdesc union 
												 SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','100','201','202','206','207','208')
												 order by tdesc ",$_GET["mRAWAT"], "");
		}elseif ($_GET["rawat_inap"]=="I"){
		
		$f->selectSQL("mINAP", "Ruangan ","select '' as bangsal1, '' as bangsal2 union select d.bangsal as bangsal1, d.bangsal as bangsal2
						   from rs00010 as a 
							   join rs00012 as b on a.bangsal_id = b.id 
							   join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' 
							   join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' 
							   join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR'
		group by bangsal1
		order by bangsal1 " ,$_GET["mINAP"], "");
			}else{}
		//$f->text("dokter", "Pelaku Tindakan", 25, 100, getFromTable("SELECT nama FROM rs00017 WHERE id = ".$_GET['id_dokter']));
		//$f->hidden("id_dokter", $_GET['id_dokter']);
		$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
    			  "select c.tc as tc, c.tdesc as tdesc ".
    			  "from rs00008 a, rs00006 b, rs00001 c ".
    			  "where a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP' order by tdesc asc", $_GET["mPASIEN"],"");
		
		$f->selectArray("status", "Status",Array("Y" => "LUNAS",  "N" => "BELUM LUNAS"),
                     $_GET[status], "");
		
							 
	    $f->submit ("TAMPILKAN");
	    $f->execute();

	} else {
		if (!isset($_GET['tanggal1D'])) {

		$tanggal1D = date("d", time());
		$tanggal1M = date("m", time());
		$tanggal1Y = date("Y", time());
		$tanggal2D = date("d", time());
		$tanggal2M = date("m", time());
		$tanggal2Y = date("Y", time());

	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
		$ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
		$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");

	    } else {
		$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
		$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
	    }
		$f->selectArray("rawat_inap", "<font color='red'>* U n i t</font>",Array(""=>"", "N" => "IGD", "Y" => "Rawat Jalan",  "I" => "Rawat Inap"),
                     $_GET[rawat_inap], "disabled");
		$_GET["rawat_inap"] = strlen($_GET["rawat_inap"]) == "" ? "" : $_GET["rawat_inap"];
		if ($_GET["rawat_inap"]=="Y"){
		$f->selectSQL("mRAWAT", "Poli","select '' as tc, '' as tdesc union 
												 SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','100','201','202','206','207','208')
												 order by tdesc ",$_GET["mRAWAT"], "disabled");
		}elseif ($_GET["rawat_inap"]=="I"){
		$f->selectSQL("mINAP", "Ruangan ","select '' as bangsal1, '' as bangsal2 union select d.bangsal as bangsal1, d.bangsal as bangsal2
						   from rs00010 as a 
							   join rs00012 as b on a.bangsal_id = b.id 
							   join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' 
							   join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' 
							   join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR'
		group by d.bangsal1
		order by d.bangsal1 " ,$_GET["mINAP"], "disabled");
			}else{}
		
		$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
    			  "select c.tc as tc, c.tdesc as tdesc ".
    			  "from rs00008 a, rs00006 b, rs00001 c ".
    			  "where a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP'", $_GET["mPASIEN"],"disabled");
				  
		$f->selectArray("status", "Status",Array("Y" => "LUNAS",  "N" => "BELUM LUNAS"),
                     $_GET["status"], "");
	}

	
//--- filter
$addParam = '';

//---------------------------------------------------------------------------------------------	

if(($_GET["rawat_inap"] == "Y" or $_GET["rawat_inap"] == "N") and $_GET["tanggal1D"] != ''){
	
	//--filter
	if($_GET['rawat_inap'] == 'N' and $_GET['mRAWAT'] == ''){
		$addParam = $addParam." AND rs00006.poli = '100' "; //igd
	} else if($_GET['rawat_inap'] == 'Y' and $_GET['mRAWAT'] == ''){
		$addParam = $addParam." AND rs00006.poli != '100' "; //rawat jalan
	} else if($_GET['rawat_inap'] == 'Y' and $_GET['mRAWAT'] != ''){ 
		$addParam = $addParam." AND rs00006.poli = '".$_GET['mRAWAT']."' "; //pilih poli
	}
	
	if($_GET['mPASIEN'] != ''){
		$addParam = $addParam." AND rs00001.tc = '".$_GET['mPASIEN']."' ";
	}
	
	if ($_GET["status"] == "Y") {
		$addParam = $addParam." AND ((SELECT sum(g.jumlah) AS sum FROM rs00005 g WHERE g.reg::text = rs00006.id::text AND (g.kasir::text = 'BYR'::text OR g.kasir::text = 'BYD'::text) ) > 0 OR
                                (SELECT sum(h.jumlah) AS sum FROM rs00005 h WHERE h.reg::text = rs00006.id::text AND h.kasir::text = 'ASK'::text) > 0 OR
                                (SELECT sum(i.jumlah) AS sum FROM rs00005 i WHERE i.reg::text = rs00006.id::text AND i.kasir::text = 'POT'::text) > 0 OR
                                (SELECT sum(j.pembulatan) AS sum FROM rs00005 j WHERE j.reg::text = rs00006.id::text AND (j.kasir::text = 'BYR'::text OR j.kasir::text = 'BYD'::text) ) > 0 OR
                                (SELECT sum(k.total_pembulatan) AS sum FROM rs00005 k WHERE k.reg::text = rs00006.id::text AND (k.kasir::text = 'BYR'::text OR k.kasir::text = 'BYD'::text) ) > 0 ) ";
	} else if ($_GET["status"] == "N") {
		$addParam = $addParam." AND ((SELECT sum(g.jumlah) AS sum FROM rs00005 g WHERE g.reg::text = rs00006.id::text AND (g.kasir::text = 'BYR'::text OR g.kasir::text = 'BYD'::text) ) IS NULL OR
                                (SELECT sum(h.jumlah) AS sum FROM rs00005 h WHERE h.reg::text = rs00006.id::text AND h.kasir::text = 'ASK'::text) IS NULL OR
                                (SELECT sum(i.jumlah) AS sum FROM rs00005 i WHERE i.reg::text = rs00006.id::text AND i.kasir::text = 'POT'::text) IS NULL OR
                                (SELECT sum(j.pembulatan) AS sum FROM rs00005 j WHERE j.reg::text = rs00006.id::text AND (j.kasir::text = 'BYR'::text OR j.kasir::text = 'BYD'::text) ) IS NULL OR
                                (SELECT sum(k.total_pembulatan) AS sum FROM rs00005 k WHERE k.reg::text = rs00006.id::text AND (k.kasir::text = 'BYR'::text OR k.kasir::text = 'BYD'::text) ) IS NULL ) ";
	}
	
	//--keur rawat jalan jeung igd
	$rowsPasien = pg_query($con, "SELECT rs00006.id AS no_reg, 
                                     rs00006.tanggal_reg, 
                                     rs00002.mr_no, 
                                     rs00002.nama, 
                                     A.tdesc AS poli,
									 (SELECT sum(l.tagihan) AS sum FROM rs00008 l WHERE l.no_reg::text = rs00006.id::text AND l.trans_type::text = 'POS'::text ) AS kamar,
                                     (SELECT sum(e.tagihan) AS sum FROM rs00008 e WHERE e.no_reg::text = rs00006.id::text AND e.trans_type::text = 'BHP'::text ) AS bhp,
                                     (SELECT sum(f.tagihan) AS sum FROM rs00008 f WHERE f.no_reg::text = rs00006.id::text AND f.trans_type::text = 'OB1'::text ) AS obat,
                                     (SELECT sum(ff.tagihan) AS sum FROM rs00008 ff WHERE ff.no_reg::text = rs00006.id::text AND ff.trans_type::text = 'RCK'::text ) AS racikan,
                                     (SELECT sum(g.jumlah) AS sum FROM rs00005 g WHERE g.reg::text = rs00006.id::text AND (g.kasir::text = 'BYR'::text OR g.kasir::text = 'BYD'::text) ) AS bayar_tunai,
                                     (SELECT sum(h.jumlah) AS sum FROM rs00005 h WHERE h.reg::text = rs00006.id::text AND h.kasir::text = 'ASK'::text) AS bayar_askes,
                                     (SELECT sum(i.jumlah) AS sum FROM rs00005 i WHERE i.reg::text = rs00006.id::text AND i.kasir::text = 'POT'::text) AS bayar_potongan,
                                     (SELECT sum(j.pembulatan) AS sum FROM rs00005 j WHERE j.reg::text = rs00006.id::text AND (j.kasir::text = 'BYR'::text OR j.kasir::text = 'BYD'::text) ) AS bayar_pembulatan,
                                     (SELECT sum(k.total_pembulatan) AS sum FROM rs00005 k WHERE k.reg::text = rs00006.id::text AND (k.kasir::text = 'BYR'::text OR k.kasir::text = 'BYD'::text) ) AS total_bayar_pembulatan
                            FROM rs00006
                            JOIN rs00002 ON rs00002.mr_no = rs00006.mr_no
                            JOIN rs00001 ON rs00006.tipe = rs00001.tc AND rs00001.tt = 'JEP' 
                            JOIN rs00001 A ON A.tc = rs00006.poli::text AND A.tt = 'LYN'
                            WHERE rs00006.status_akhir_pasien != '012' AND (rs00006.tanggal_reg BETWEEN '".$ts_check_in1."' AND '".$ts_check_in2."'  ".$addParam." )
                            ORDER BY rs00006.id ASC    
                            ");
} else if ($_GET["rawat_inap"] == "I" and $_GET["tanggal1D"] != '') {
	//--filter
	if($_GET['mINAP'] != ''){
		$addParam = $addParam." AND i.bangsal like '%".$_GET["mINAP"]."%' ";
	}
	
	if($_GET['mPASIEN'] != ''){
		$addParam = $addParam." AND rs00001.tc = '".$_GET['mPASIEN']."' ";
	}

	//--keur rawat inap
	$rowsPasien = pg_query($con, "SELECT rs00006.id AS no_reg, 
                                     rs00006.tanggal_reg, 
                                     rs00002.mr_no, 
                                     rs00002.nama, 
									 g.bangsal_id, h.bangsal AS poli, i.bangsal,
									 (SELECT sum(l.tagihan) AS sum FROM rs00008 l WHERE l.no_reg::text = rs00006.id::text AND l.trans_type::text = 'POS'::text ) AS kamar,
                                     (SELECT sum(e.tagihan) AS sum FROM rs00008 e WHERE e.no_reg::text = rs00006.id::text AND e.trans_type::text = 'BHP'::text ) AS bhp,
                                     (SELECT sum(f.tagihan) AS sum FROM rs00008 f WHERE f.no_reg::text = rs00006.id::text AND f.trans_type::text = 'OB1'::text ) AS obat,
                                     (SELECT sum(ff.tagihan) AS sum FROM rs00008 ff WHERE ff.no_reg::text = rs00006.id::text AND ff.trans_type::text = 'RCK'::text ) AS racikan,
                                     (SELECT sum(g.jumlah) AS sum FROM rs00005 g WHERE g.reg::text = rs00006.id::text AND g.kasir::text = 'BYI'::text ) AS bayar_tunai,
                                     (SELECT sum(h.jumlah) AS sum FROM rs00005 h WHERE h.reg::text = rs00006.id::text AND h.kasir::text = 'ASK'::text) AS bayar_askes,
                                     (SELECT sum(i.jumlah) AS sum FROM rs00005 i WHERE i.reg::text = rs00006.id::text AND i.kasir::text = 'POT'::text) AS bayar_potongan,
                                     (SELECT sum(j.pembulatan) AS sum FROM rs00005 j WHERE j.reg::text = rs00006.id::text AND j.kasir::text = 'BYI'::text ) AS bayar_pembulatan,
                                     (SELECT sum(k.total_pembulatan) AS sum FROM rs00005 k WHERE k.reg::text = rs00006.id::text AND k.kasir::text = 'BYI'::text ) AS total_bayar_pembulatan
                            FROM rs00006
                            JOIN rs00002 ON rs00002.mr_no = rs00006.mr_no
                            JOIN rs00001 ON rs00006.tipe = rs00001.tc AND rs00001.tt = 'JEP' 
                            JOIN rs00001 A ON A.tc = rs00006.poli::text AND A.tt = 'LYN'
                            LEFT JOIN rs00010 g ON g.id = (( SELECT max(rs00010.id) AS max FROM rs00010 WHERE rs00010.no_reg::text = rs00006.id::text))
							JOIN rs00012 h ON g.bangsal_id = h.id
							JOIN rs00012 i ON i.hierarchy::text = (substr(h.hierarchy::text, 1, 3) || '000000000000'::text)
							JOIN rs00012 j ON j.hierarchy::text = (substr(i.hierarchy::text, 1, 6) || '000000000'::text)
                            WHERE rs00006.status_akhir_pasien = '012' AND (rs00006.tanggal_reg BETWEEN '".$ts_check_in1."' AND '".$ts_check_in2."'  ".$addParam." )
                            ORDER BY rs00006.id ASC    
                            ");
} else if ($_GET["rawat_inap"] == "" and $_GET["tanggal1D"] != '') {
	?>
		<script type="text/javascript">
			alert("Silahkan pilih Unit terlebih dahulu,\n(IGD, Rawat Jalan atau Rawat Inap) yang ingin ditampilkan.");
			history.back();
		</script>
	<?php
}

$resultSumberPendapatan = pg_query($con,"select tdesc from rs00001 where tt='SBP' and tc NOT IN ('000','016') order by tc");
while ($rowSumberPendapatan = pg_fetch_array($resultSumberPendapatan)) {
    $arrSumberPendapatan[] = $rowSumberPendapatan;
}


//--start query dokter
$resultDokter = pg_query($con,"select nama from rs00017 where pangkat LIKE '%DOKTER%'
								and pangkat NOT IN ('DOKTER Fisioterapi','DOKTER GIZI','DOKTER Laboratorium','DOKTER Radiologi')
								and nama NOT IN ('-')
								order By nama Asc");
while ($rowDokter = pg_fetch_array($resultDokter)) {
    $arrowDokter[] = $rowDokter;
}
//--end query dokter


?>

<!--<div align="right">
	<a href="includes/lap_pendapatan_rwj_rwi_xls.php?tgl_dari=<?php //echo $tglDari?>&tgl_sampai=<?php //echo $tglSampai?>&tipe_pasien_id=<?php //echo $_GET['tipe_pasien_id'] ?>&unit_id=<?php //echo $_GET['unit_id']?>&dokter=<?php //echo $_GET['dokter']?>" ><img src="icon/Excel-22.gif" width="24" /> &nbsp;&nbsp; [ Download ]</a> &nbsp;&nbsp;&nbsp;
</div> -->

<table id="list-pasien" width="100%">
        <tr>
            <td rowspan="2" class="TBL_HEAD" align="center" width="3%">NO.</td>
            <td rowspan="2" class="TBL_HEAD" align="center" width="6%">TANGGAL</td>
            <td rowspan="2" class="TBL_HEAD" align="center" width="6%">NO. REG</td>
            <td rowspan="2" class="TBL_HEAD" align="center" width="6%">NO. MR</td>
            <td rowspan="2" class="TBL_HEAD" align="center" width="15%">NAMA</td>
            <td rowspan="2" class="TBL_HEAD" align="center" width="15%">POLI / RUANGAN</td>
			<td rowspan="2" class="TBL_HEAD" align="center" width="7%">STATUS</td>
            <td colspan="21" class="TBL_HEAD" align="center">RINCIAN TAGIHAN</td>
            <td colspan="57" class="TBL_HEAD" align="center">RINCIAN JASA MEDIS</td>
            <td colspan="3" class="TBL_HEAD" align="center">OBAT dan BHP</td>
			<td colspan="1" class="TBL_HEAD" align="center">AKOMODASI</td>
            <td rowspan="2" class="TBL_HEAD" align="center" width="7%">TOTAL TAGIHAN</td>
            <td colspan="6" class="TBL_HEAD" align="center">PEMBAYARAN PASIEN</td>
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
			
			
			//--start query dokter
			foreach ($arrowDokter as $keyDokter => $valDokter) {
                if ($valDokter["nama"] == '-') {
                    echo '<td class="TBL_HEAD" align="center" width="7%">TEST</td>';
                } else {
                    echo '<td class="TBL_HEAD" align="center" width="7%">' . $valDokter["nama"] . '</td>';
                }
            }
			//--end query dokter
			
            ?>
            <td class="TBL_HEAD" align="center" width="7%">TOTAL JTP</td>
            <td class="TBL_HEAD" align="center" width="7%">TOTAL RS</td>
            <td class="TBL_HEAD" align="center" width="7%">TOTAL ALAT</td>
            <td class="TBL_HEAD" align="center" width="7%">TOTAL BAHAN</td>
            <td class="TBL_HEAD" align="center" width="7%">TOTAL ADMINISTRASI</td>
			<td class="TBL_HEAD" align="center" width="7%">TOTAL DISKON (DOKTER)</td>
            <td class="TBL_HEAD" align="center" width="7%">BHP</td>
            <td class="TBL_HEAD" align="center" width="7%">OBAT</td>
            <td class="TBL_HEAD" align="center" width="7%">RACIKAN</td>
            <td class="TBL_HEAD" align="center" width="7%">KAMAR / MAKAN</td>
            <td class="TBL_HEAD" align="center" width="7%">TUNAI</td>
            <td class="TBL_HEAD" align="center" width="7%">POTONGAN</td>
            <td class="TBL_HEAD" align="center" width="7%">PENJAMIN</td>
            <td class="TBL_HEAD" align="center" width="7%">PIUTANG PASIEN</td>
            <td class="TBL_HEAD" align="center" width="7%">PEMBULATAN</td>
            <td class="TBL_HEAD" align="center" width="7%">TOTAL PEMBAYARAN</td>
        </tr>
        <?php
            if(!empty($rowsPasien)){
                $i=0;
                while($row=pg_fetch_array($rowsPasien)){
                    //if($row["bayar_tunai"] > 0 || $row["bayar_potongan"] > 0  || $row["bayar_askes"] > 0 || $row["bayar_pembulatan"] > 0 || $row["total_bayar_pembulatan"] > 0 ){
                    $i++;
					
					
					if($row["bayar_tunai"] > 0 || $row["bayar_potongan"] > 0  || $row["bayar_askes"] > 0 || $row["bayar_pembulatan"] > 0 || $row["total_bayar_pembulatan"] > 0 ){
						$selisihTxt =  'LUNAS';
					} else {
						$selisihTxt =  'BELUM LUNAS';
					}
        ?>
        <tr>
            <td><?php echo $i?></td>
            <td align="right"><?php echo tanggalShort($row['tanggal_reg'])?>&nbsp; </td>
            <td><a href="index2.php?p=lap_transaksi_pasien&no_reg=<?php echo $row['no_reg']?>"><?php echo $row['no_reg']?></a></td>
            <td><?php echo $row['mr_no']?></td>
            <td><?php echo $row['nama']?></td>
            <td><?php echo $row['bangsal']." / ".$row['poli']?></td>
            <td><?php echo $selisihTxt?></td>
        
            <?php
    $j = 0;
    $totalJMLSumberPendapatan = 0;
    foreach ($arrSumberPendapatan as $key => $val) {
        $j++;
		/*
		$sqlJMLSumberPendapatan = "SELECT sum(a.tagihan) as jumlah
						FROM rs00008 a
							LEFT JOIN rs00017 b ON a.no_kwitansi::text = b.id::text
							JOIN rs00001 c ON c.comment::text = a.trans_form::text AND c.tt::text = 'LYN'::text
							JOIN rs00006 d ON d.id::text = a.no_reg::text
							LEFT JOIN rs00034 e ON e.id = a.item_id::integer::numeric
							LEFT JOIN rs00001 f ON d.tipe::text = f.tc::text AND f.tt::text = 'JEP'::text
							LEFT JOIN rs00001 g on g.tc = e.sumber_pendapatan_id and g.tt='SBP' 
						WHERE a.trans_type::text = 'LTM'::text AND a.referensi::text <> 'P'::text AND a.is_bayar::text = 'Y'::text
							AND a.no_reg='" . $row["no_reg"] . "' AND upper(g.tdesc) like '%" . strtoupper($val["tdesc"]) . "%'";
		*/
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
            echo '<td class="TBL_BODY4" align="right" id="val_' . $i . '_' . $j . '">';
            echo number_format($jumlah,
                    0,
                    " ",
                    ".");
            echo '</td>';
        }
    }
	
	
	//--start query dokter
	$a = 0;
    $totalJMLDokter = 0;
    foreach ($arrowDokter as $keyDokter => $valDokter) {
        $a++;
		$sqlJMLDokter = "SELECT sum(e.jasa_dokter) as jasa_dokter
						FROM rs00008 a
							LEFT JOIN rs00017 b ON a.no_kwitansi::text = b.id::text
							JOIN rs00001 c ON c.comment::text = a.trans_form::text AND c.tt::text = 'LYN'::text
							JOIN rs00006 d ON d.id::text = a.no_reg::text
							LEFT JOIN rs00034 e ON e.id = a.item_id::integer::numeric
							LEFT JOIN rs00001 f ON d.tipe::text = f.tc::text AND f.tt::text = 'JEP'::text
							LEFT JOIN rs00001 g on g.tc = e.sumber_pendapatan_id and g.tt='SBP' 
						WHERE a.trans_type::text = 'LTM'::text AND a.referensi::text <> 'P'::text AND a.is_bayar::text = 'Y'::text
							AND a.no_reg='" . $row["no_reg"] . "' AND b.nama like '%" . $valDokter["nama"] . "%'"; 
							
		/*
		$sqlJMLDokter = "SELECT sum(a.tagihan) as jumlah 
                                FROM rs00008 a
                                LEFT JOIN rs00034 b on b.id = a.item_id::numeric
                                LEFT JOIN rs00017 c on c.id::numeric = a.no_kwitansi and c.pangkat LIKE '%DOKTER%'
                                WHERE a.no_reg='" . $row["no_reg"] . "' AND (a.trans_type='LTM') and c.nama like '%" . $valDokter["nama"] . "%'";
		*/
        $resultJMLDokter = pg_query($con,
                $sqlJMLDokter);

        while ($rowJMLDokter = pg_fetch_array($resultJMLDokter)) {
            $jumlahDokter = $rowJMLDokter["jasa_dokter"];
            $totalJMLDokter = $totalJMLDokter + $jumlahDokter;
            echo '<td class="TBL_BODY" align="right" id="valdok_' . $i . '_' . $a . '">';
            echo number_format($jumlahDokter,
                    0,
                    " ",
                    ".");
            echo '</td>';
        }
    }
	//--end query dokter
	
	//--start query asisten
	$b = 0;
    $totalJMLAsisten = 0;
	$sqlJMLAsisten = "SELECT sum(e.jasa_asisten) as jasa_asisten
					FROM rs00008 a
						LEFT JOIN rs00017 b ON a.no_kwitansi::text = b.id::text
						JOIN rs00001 c ON c.comment::text = a.trans_form::text AND c.tt::text = 'LYN'::text
						JOIN rs00006 d ON d.id::text = a.no_reg::text
						LEFT JOIN rs00034 e ON e.id = a.item_id::integer::numeric
						LEFT JOIN rs00001 f ON d.tipe::text = f.tc::text AND f.tt::text = 'JEP'::text
						LEFT JOIN rs00001 g on g.tc = e.sumber_pendapatan_id and g.tt='SBP' 
					WHERE a.trans_type::text = 'LTM'::text AND a.referensi::text <> 'P'::text AND a.is_bayar::text = 'Y'::text
						AND a.no_reg='" . $row["no_reg"] . "'"; 
						
	$resultJMLAsisten = pg_query($con,
			$sqlJMLAsisten);
	
	while ($rowJMLAsisten = pg_fetch_array($resultJMLAsisten)) {
		$b++;
		$jumlahAsisten = $rowJMLAsisten["jasa_asisten"];
		$totalJMLAsisten = $totalJMLAsisten + $jumlahAsisten;
		
		echo '<td class="TBL_BODY" align="right" id="valtotas_' . $i . '_' . $b . '">';
		echo number_format($jumlahAsisten,
				0,
				" ",
				".");
		echo '</td>';
	}
	//--end query asisten
	
	//--start query rs
	$c = 0;
    $totalJMLRs = 0;
	$sqlJMLRs = "SELECT sum(e.jasa_rs) as jasa_rs
					FROM rs00008 a
						LEFT JOIN rs00017 b ON a.no_kwitansi::text = b.id::text
						JOIN rs00001 c ON c.comment::text = a.trans_form::text AND c.tt::text = 'LYN'::text
						JOIN rs00006 d ON d.id::text = a.no_reg::text
						LEFT JOIN rs00034 e ON e.id = a.item_id::integer::numeric
						LEFT JOIN rs00001 f ON d.tipe::text = f.tc::text AND f.tt::text = 'JEP'::text
						LEFT JOIN rs00001 g on g.tc = e.sumber_pendapatan_id and g.tt='SBP' 
					WHERE a.trans_type::text = 'LTM'::text AND a.referensi::text <> 'P'::text AND a.is_bayar::text = 'Y'::text
						AND a.no_reg='" . $row["no_reg"] . "'"; 
						
	$resultJMLRs = pg_query($con,
			$sqlJMLRs);
	
	while ($rowJMLRs = pg_fetch_array($resultJMLRs)) {
		$c++;
		$jumlahRs = $rowJMLRs["jasa_rs"];
		$totalJMLRs = $totalJMLRs + $jumlahRs;
		
		echo '<td class="TBL_BODY" align="right" id="valtotrs_' . $i . '_' . $c . '">';
		echo number_format($jumlahRs,
				0,
				" ",
				".");
		echo '</td>';
	}
	//--end query rs
	
	
	//--start query alat
	$d = 0;
    $totalJMLAlat = 0;
	$sqlJMLAlat = "SELECT sum(e.alat) as alat
					FROM rs00008 a
						LEFT JOIN rs00017 b ON a.no_kwitansi::text = b.id::text
						JOIN rs00001 c ON c.comment::text = a.trans_form::text AND c.tt::text = 'LYN'::text
						JOIN rs00006 d ON d.id::text = a.no_reg::text
						LEFT JOIN rs00034 e ON e.id = a.item_id::integer::numeric
						LEFT JOIN rs00001 f ON d.tipe::text = f.tc::text AND f.tt::text = 'JEP'::text
						LEFT JOIN rs00001 g on g.tc = e.sumber_pendapatan_id and g.tt='SBP' 
					WHERE a.trans_type::text = 'LTM'::text AND a.referensi::text <> 'P'::text AND a.is_bayar::text = 'Y'::text
						AND a.no_reg='" . $row["no_reg"] . "'"; 
						
	$resultJMLAlat = pg_query($con,
			$sqlJMLAlat);
	
	while ($rowJMLAlat = pg_fetch_array($resultJMLAlat)) {
		$d++;
		$jumlahAlat = $rowJMLAlat["alat"];
		$totalJMLAlat = $totalJMLAlat + $jumlahAlat;
		
		echo '<td class="TBL_BODY" align="right" id="valtotal_' . $i . '_' . $d . '">';
		echo number_format($jumlahAlat,
				0,
				" ",
				".");
		echo '</td>';
	}
	//--end query alat
	
	//--start query bahan
	$e = 0;
    $totalJMLBahan = 0;
	$sqlJMLBahan = "SELECT sum(e.bahan) as bahan
					FROM rs00008 a
						LEFT JOIN rs00017 b ON a.no_kwitansi::text = b.id::text
						JOIN rs00001 c ON c.comment::text = a.trans_form::text AND c.tt::text = 'LYN'::text
						JOIN rs00006 d ON d.id::text = a.no_reg::text
						LEFT JOIN rs00034 e ON e.id = a.item_id::integer::numeric
						LEFT JOIN rs00001 f ON d.tipe::text = f.tc::text AND f.tt::text = 'JEP'::text
						LEFT JOIN rs00001 g on g.tc = e.sumber_pendapatan_id and g.tt='SBP' 
					WHERE a.trans_type::text = 'LTM'::text AND a.referensi::text <> 'P'::text AND a.is_bayar::text = 'Y'::text
						AND a.no_reg='" . $row["no_reg"] . "'"; 
						
	$resultJMLBahan = pg_query($con,
			$sqlJMLBahan);
	
	while ($rowJMLBahan = pg_fetch_array($resultJMLBahan)) {
		$e++;
		$jumlahBahan = $rowJMLBahan["bahan"];
		$totalJMLBahan = $totalJMLBahan + $jumlahBahan;
		
		echo '<td class="TBL_BODY" align="right" id="valtotbah_' . $i . '_' . $e . '">';
		echo number_format($jumlahBahan,
				0,
				" ",
				".");
		echo '</td>';
	}
	//--end query bahan
	
	
	//--start query Admin
	$f = 0;
    $totalJMLDll = 0;
	$sqlJMLDll = "SELECT sum(e.dll) as dll
					FROM rs00008 a
						LEFT JOIN rs00017 b ON a.no_kwitansi::text = b.id::text
						JOIN rs00001 c ON c.comment::text = a.trans_form::text AND c.tt::text = 'LYN'::text
						JOIN rs00006 d ON d.id::text = a.no_reg::text
						LEFT JOIN rs00034 e ON e.id = a.item_id::integer::numeric
						LEFT JOIN rs00001 f ON d.tipe::text = f.tc::text AND f.tt::text = 'JEP'::text
						LEFT JOIN rs00001 g on g.tc = e.sumber_pendapatan_id and g.tt='SBP' 
					WHERE a.trans_type::text = 'LTM'::text AND a.referensi::text <> 'P'::text AND a.is_bayar::text = 'Y'::text
						AND a.no_reg='" . $row["no_reg"] . "'"; 
						
	$resultJMLDll = pg_query($con,
			$sqlJMLDll);
	
	while ($rowJMLDll = pg_fetch_array($resultJMLDll)) {
		$f++;
		$jumlahDll = $rowJMLDll["dll"];
		$totalJMLDll = $totalJMLDll + $jumlahDll;
		
		echo '<td class="TBL_BODY" align="right" id="valtotdll_' . $i . '_' . $f . '">';
		echo number_format($jumlahDll,
				0,
				" ",
				".");
		echo '</td>';
	}
	//--end query Admin
	
	//--start query Diskon
	$g = 0;
    $totalJMLDiskon = 0;
	$sqlJMLDiskon = "SELECT sum(a.diskon) as diskon
					FROM rs00008 a
						LEFT JOIN rs00017 b ON a.no_kwitansi::text = b.id::text
						JOIN rs00001 c ON c.comment::text = a.trans_form::text AND c.tt::text = 'LYN'::text
						JOIN rs00006 d ON d.id::text = a.no_reg::text
						LEFT JOIN rs00034 e ON e.id = a.item_id::integer::numeric
						LEFT JOIN rs00001 f ON d.tipe::text = f.tc::text AND f.tt::text = 'JEP'::text
						LEFT JOIN rs00001 g on g.tc = e.sumber_pendapatan_id and g.tt='SBP' 
					WHERE a.trans_type::text = 'LTM'::text AND a.referensi::text <> 'P'::text AND a.is_bayar::text = 'Y'::text
						AND a.no_reg='" . $row["no_reg"] . "'"; 
						
	$resultJMLDiskon = pg_query($con,
			$sqlJMLDiskon);
	
	while ($rowJMLDiskon = pg_fetch_array($resultJMLDiskon)) {
		$g++;
		$jumlahDiskon = $rowJMLDiskon["diskon"];
		$totalJMLDiskon = $totalJMLDiskon + $jumlahDiskon;
		
		echo '<td class="TBL_BODY" align="right" id="valtotdis_' . $i . '_' . $g . '">';
		echo number_format($jumlahDiskon,
				0,
				" ",
				".");
		echo '</td>';
	}
	//--end query Admin
	
    $totalPembayaran = $row["bayar_tunai"] + $row["bayar_potongan"] + $row["bayar_askes"];
    ?>
            <td class="TBL_BODY5" align="right" id="val_bhp_<?php echo $i ?>"><?php echo number_format($row["bhp"],
            0,
            " ",
            ".") ?>  </td>
            <td class="TBL_BODY5" align="right" id="val_obat_<?php echo $i ?>"><?php echo number_format($row["obat"],
            0,
            " ",
            ".") ?> </td>
			<td class="TBL_BODY5" align="right" id="val_racikan_<?php echo $i ?>"><?php echo number_format($row["racikan"],
            0,
            " ",
            ".") ?> </td>
			<td class="TBL_BODY5" align="right" id="val_kamar_<?php echo $i ?>"><?php echo number_format($row["kamar"],
            0,
            " ",
            ".") ?> </td>
			 <td class="TBL_BODY5" align="right" id="val_tagihan_<?php echo $i ?>"><?php echo number_format($totalJMLSumberPendapatan + $row["bhp"] + $row["obat"] + $row["racikan"] + $row["kamar"],
            0,
            " ",
            ".") ?> </td>
            <td class="TBL_BODY5" align="right" id="val_tunai_<?php echo $i ?>"><?php echo number_format($row["bayar_tunai"],
            0,
            " ",
            ".") ?> </td>
            <td class="TBL_BODY5" align="right" id="val_potongan_<?php echo $i ?>"><?php echo number_format($row["bayar_potongan"],
            0,
            " ",
            ".") ?> </td>
            <td class="TBL_BODY5" align="right" id="val_askes_<?php echo $i ?>"><?php echo number_format($row["bayar_askes"],
            0,
            " ",
            ".") ?> </td>
            <td class="TBL_BODY5" align="right" id="val_sisa_<?php echo $i ?>"><?php echo number_format(($totalJMLSumberPendapatan + $row["bhp"] + $row["obat"] + $row["racikan"] + $row["kamar"]) - $totalPembayaran,
            0,
            " ",
            ".") ?> </td>
            <td class="TBL_BODY5" align="right" id="val_pembulatan_<?php echo $i ?>"><?php echo number_format($row["bayar_pembulatan"],
            0,
            " ",
            ".") ?> </td>	    
            <td class="TBL_BODY5" align="right" id="val_total_pembulatan_<?php echo $i ?>"><?php echo number_format(/*$row["total_bayar_pembulatan"]*/$row["bayar_tunai"] + $row["bayar_pembulatan"],
            0,
            " ",
            ".") ?> </td>
            </tr>
        <?php
                     //}
                 }
            }
        ?>
    <tr>
        <td colspan="7" class="TBL_HEAD" align="right">J U M L A H</td>
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
        <td class="TBL_HEAD" align="right" id="jumlah_akomodasi"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_transfusi"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_rujukan"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_sewaok"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_pendaftaran"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_usg"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_ekg"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_rm"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter1"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter2"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter3"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter4"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter5"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter6"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter7"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter8"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter9"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter10"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter11"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter12"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter13"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter14"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter15"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter16"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter17"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter18"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter19"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter20"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter21"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter22"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter23"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter24"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter25"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter26"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter27"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter28"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter29"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter30"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter31"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter32"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter33"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter34"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter35"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter36"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter37"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter38"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter39"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter40"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter41"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter42"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter43"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter44"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter45"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter46"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter47"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter48"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter49"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter50"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_dokter51"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_tot_asisten"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_tot_rs"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_tot_alat"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_tot_bahan"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_tot_dll"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_tot_dis"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_bhp"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_obat"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_racikan"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_kamar"></td>
		<td class="TBL_HEAD" align="right" id="jumlah_tagihan"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_tunai"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_potongan"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_askes"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_sisa"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_pembulatan"></td>
        <td class="TBL_HEAD" align="right" id="jumlah_total_pembulatan"></td>	
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
		window.location = 'index2.php?p=lap_pendapatan_rwj_rwi&tgl_dari='+tglDari+'&tgl_sampai='+tglSampai+'&tipe_pasien_id='+tipePasienId+'&unit_id='+unitId+'&shiftkerja='+shiftId;
	
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
    totalAkomodasi	= 0;
    totalTransfusi	= 0;
    totalRujukan	= 0;
    totalSewaok	= 0;
    totalPendaftaran	= 0;
    totalUSG		= 0;
    totalEKG		= 0;
    totalRM		= 0;
    totalDokter1		= 0;
    totalDokter2		= 0;
    totalDokter3		= 0;
    totalDokter4		= 0;
    totalDokter5		= 0;
    totalDokter6		= 0;
    totalDokter7		= 0;
    totalDokter8		= 0;
    totalDokter9		= 0;
    totalDokter10		= 0;
    totalDokter11		= 0;
    totalDokter12		= 0;
    totalDokter13		= 0;
    totalDokter14		= 0;
    totalDokter15		= 0;
    totalDokter16		= 0;
    totalDokter17		= 0;
    totalDokter18		= 0;
    totalDokter19		= 0;
    totalDokter20		= 0;
    totalDokter21		= 0;
    totalDokter22		= 0;
    totalDokter23		= 0;
    totalDokter24		= 0;
    totalDokter25		= 0;
    totalDokter26		= 0;
    totalDokter27		= 0;
    totalDokter28		= 0;
    totalDokter29		= 0;
    totalDokter30		= 0;
    totalDokter31		= 0;
    totalDokter32		= 0;
    totalDokter33		= 0;
    totalDokter34		= 0;
    totalDokter35		= 0;
    totalDokter36		= 0;
    totalDokter37		= 0;
    totalDokter38		= 0;
    totalDokter39		= 0;
    totalDokter40		= 0;
    totalDokter41		= 0;
    totalDokter42		= 0;
    totalDokter43		= 0;
    totalDokter44		= 0;
    totalDokter45		= 0;
    totalDokter46		= 0;
    totalDokter47		= 0;
    totalDokter48		= 0;
    totalDokter49		= 0;
    totalDokter50		= 0;
    totalDokter51		= 0;
	valtotalAsisten		= 0;
	valtotalRs			= 0;
	valtotalAlat		= 0;
	valtotalBahan		= 0;
	valtotalDll		= 0;
	valtotDis		= 0;
	totalBHP		= 0;
	totalObat		= 0;
	totalRacikan		= 0;
	totalKamar		= 0;
    totalTagihan	= 0;
    totalTunai		= 0;
    totalPotongan	= 0;
    totalAskes		= 0;
    totalSisa		= 0;
    totalPembulatan		= 0;
    totalTotalPembulatan= 0;
    
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
		
		akomodasiTmp = $('#val_'+i+'_14').text();
        akomodasi = parseInt(akomodasiTmp.replace('.',''));
        totalAkomodasi = totalAkomodasi+akomodasi;
		
		transfusiTmp = $('#val_'+i+'_15').text();
        transfusi = parseInt(transfusiTmp.replace('.',''));
        totalTransfusi = totalTransfusi+transfusi;
		
		rujukanTmp = $('#val_'+i+'_16').text();
        rujukan = parseInt(rujukanTmp.replace('.',''));
        totalRujukan = totalRujukan+rujukan;
		
		sewaokTmp = $('#val_'+i+'_17').text();
        sewaok = parseInt(sewaokTmp.replace('.',''));
        totalSewaok = totalSewaok+sewaok;
		
		pendaftaranTmp = $('#val_'+i+'_18').text();
        pendaftaran = parseInt(pendaftaranTmp.replace('.',''));
        totalPendaftaran = totalPendaftaran+pendaftaran;

		USGTmp = $('#val_'+i+'_19').text();
        USG = parseInt(USGTmp.replace('.',''));
        totalUSG = totalUSG+USG;

		EKGTmp = $('#val_'+i+'_20').text();
        EKG = parseInt(EKGTmp.replace('.',''));
        totalEKG = totalEKG+EKG;
		
		RMTmp = $('#val_'+i+'_21').text();
        RM = parseInt(RMTmp.replace('.',''));
        totalRM = totalRM+RM;
		//--dokter
		dokter1Tmp = $('#valdok_'+i+'_1').text();
        Dokter1 = parseInt(dokter1Tmp.replace('.',''));
        totalDokter1 = totalDokter1+Dokter1;
		
		dokter2Tmp = $('#valdok_'+i+'_2').text();
        Dokter2 = parseInt(dokter2Tmp.replace('.',''));
        totalDokter2 = totalDokter2+Dokter2
		
		dokter3Tmp = $('#valdok_'+i+'_3').text();
        Dokter3 = parseInt(dokter3Tmp.replace('.',''));
        totalDokter3 = totalDokter3+Dokter3
		
		dokter4Tmp = $('#valdok_'+i+'_4').text();
        Dokter4 = parseInt(dokter4Tmp.replace('.',''));
        totalDokter4 = totalDokter4+Dokter4
		
		dokter5Tmp = $('#valdok_'+i+'_5').text();
        Dokter5 = parseInt(dokter5Tmp.replace('.',''));
        totalDokter5 = totalDokter5+Dokter5
		
		dokter6Tmp = $('#valdok_'+i+'_6').text();
        Dokter6 = parseInt(dokter6Tmp.replace('.',''));
        totalDokter6 = totalDokter6+Dokter6
		
		dokter7Tmp = $('#valdok_'+i+'_7').text();
        Dokter7 = parseInt(dokter7Tmp.replace('.',''));
        totalDokter7 = totalDokter7+Dokter7
		
		dokter8Tmp = $('#valdok_'+i+'_8').text();
        Dokter8 = parseInt(dokter8Tmp.replace('.',''));
        totalDokter8 = totalDokter8+Dokter8
		
		dokter9Tmp = $('#valdok_'+i+'_9').text();
        Dokter9 = parseInt(dokter9Tmp.replace('.',''));
        totalDokter9 = totalDokter9+Dokter9
		
		dokter10Tmp = $('#valdok_'+i+'_10').text();
        Dokter10 = parseInt(dokter10Tmp.replace('.',''));
        totalDokter10 = totalDokter10+Dokter10
		
		dokter11Tmp = $('#valdok_'+i+'_11').text();
        Dokter11 = parseInt(dokter11Tmp.replace('.',''));
        totalDokter11 = totalDokter11+Dokter11
		
		dokter12Tmp = $('#valdok_'+i+'_12').text();
        Dokter12 = parseInt(dokter12Tmp.replace('.',''));
        totalDokter12 = totalDokter12+Dokter12
		
		dokter13Tmp = $('#valdok_'+i+'_13').text();
        Dokter13 = parseInt(dokter13Tmp.replace('.',''));
        totalDokter13 = totalDokter13+Dokter13
		
		dokter14Tmp = $('#valdok_'+i+'_14').text();
        Dokter14 = parseInt(dokter14Tmp.replace('.',''));
        totalDokter14 = totalDokter14+Dokter14
		
		dokter15Tmp = $('#valdok_'+i+'_15').text();
        Dokter15 = parseInt(dokter15Tmp.replace('.',''));
        totalDokter15 = totalDokter15+Dokter15
		
		dokter16Tmp = $('#valdok_'+i+'_16').text();
        Dokter16 = parseInt(dokter16Tmp.replace('.',''));
        totalDokter16 = totalDokter16+Dokter16
		
		dokter17Tmp = $('#valdok_'+i+'_17').text();
        Dokter17 = parseInt(dokter17Tmp.replace('.',''));
        totalDokter17 = totalDokter17+Dokter17
		
		dokter18Tmp = $('#valdok_'+i+'_18').text();
        Dokter18 = parseInt(dokter18Tmp.replace('.',''));
        totalDokter18 = totalDokter18+Dokter18
		
		dokter19Tmp = $('#valdok_'+i+'_19').text();
        Dokter19 = parseInt(dokter19Tmp.replace('.',''));
        totalDokter19 = totalDokter19+Dokter19
		
		dokter20Tmp = $('#valdok_'+i+'_20').text();
        Dokter20 = parseInt(dokter20Tmp.replace('.',''));
        totalDokter20 = totalDokter20+Dokter20
		
		dokter21Tmp = $('#valdok_'+i+'_21').text();
        Dokter21 = parseInt(dokter21Tmp.replace('.',''));
        totalDokter21 = totalDokter21+Dokter21
		
		dokter22Tmp = $('#valdok_'+i+'_22').text();
        Dokter22 = parseInt(dokter22Tmp.replace('.',''));
        totalDokter22 = totalDokter22+Dokter22
		
		dokter23Tmp = $('#valdok_'+i+'_23').text();
        Dokter23 = parseInt(dokter23Tmp.replace('.',''));
        totalDokter23 = totalDokter23+Dokter23
		
		dokter24Tmp = $('#valdok_'+i+'_24').text();
        Dokter24 = parseInt(dokter24Tmp.replace('.',''));
        totalDokter24 = totalDokter24+Dokter24
		
		dokter25Tmp = $('#valdok_'+i+'_25').text();
        Dokter25 = parseInt(dokter25Tmp.replace('.',''));
        totalDokter25 = totalDokter25+Dokter25
		
		dokter26Tmp = $('#valdok_'+i+'_26').text();
        Dokter26 = parseInt(dokter26Tmp.replace('.',''));
        totalDokter26 = totalDokter26+Dokter26
		
		dokter27Tmp = $('#valdok_'+i+'_27').text();
        Dokter27 = parseInt(dokter27Tmp.replace('.',''));
        totalDokter27 = totalDokter27+Dokter27
		
		dokter28Tmp = $('#valdok_'+i+'_28').text();
        Dokter28 = parseInt(dokter28Tmp.replace('.',''));
        totalDokter28 = totalDokter28+Dokter28
		
		dokter29Tmp = $('#valdok_'+i+'_29').text();
        Dokter29 = parseInt(dokter29Tmp.replace('.',''));
        totalDokter29 = totalDokter29+Dokter29
		
		dokter30Tmp = $('#valdok_'+i+'_30').text();
        Dokter30 = parseInt(dokter30Tmp.replace('.',''));
        totalDokter30 = totalDokter30+Dokter30
		
		dokter31Tmp = $('#valdok_'+i+'_31').text();
        Dokter31 = parseInt(dokter31Tmp.replace('.',''));
        totalDokter31 = totalDokter31+Dokter31
		
		dokter32Tmp = $('#valdok_'+i+'_32').text();
        Dokter32 = parseInt(dokter32Tmp.replace('.',''));
        totalDokter32 = totalDokter32+Dokter32
		
		dokter33Tmp = $('#valdok_'+i+'_33').text();
        Dokter33 = parseInt(dokter33Tmp.replace('.',''));
        totalDokter33 = totalDokter33+Dokter33
		
		dokter34Tmp = $('#valdok_'+i+'_34').text();
        Dokter34 = parseInt(dokter34Tmp.replace('.',''));
        totalDokter34 = totalDokter34+Dokter34
		
		dokter35Tmp = $('#valdok_'+i+'_35').text();
        Dokter35 = parseInt(dokter35Tmp.replace('.',''));
        totalDokter35 = totalDokter35+Dokter35
		
		dokter36Tmp = $('#valdok_'+i+'_36').text();
        Dokter36 = parseInt(dokter36Tmp.replace('.',''));
        totalDokter36 = totalDokter36+Dokter36
		
		dokter37Tmp = $('#valdok_'+i+'_37').text();
        Dokter37 = parseInt(dokter37Tmp.replace('.',''));
        totalDokter37 = totalDokter37+Dokter37
		
		dokter38Tmp = $('#valdok_'+i+'_38').text();
        Dokter38 = parseInt(dokter38Tmp.replace('.',''));
        totalDokter38 = totalDokter38+Dokter38
		
		dokter39Tmp = $('#valdok_'+i+'_39').text();
        Dokter39 = parseInt(dokter39Tmp.replace('.',''));
        totalDokter39 = totalDokter39+Dokter39
		
		dokter40Tmp = $('#valdok_'+i+'_40').text();
        Dokter40 = parseInt(dokter40Tmp.replace('.',''));
        totalDokter40 = totalDokter40+Dokter40
		
		dokter41Tmp = $('#valdok_'+i+'_41').text();
        Dokter41 = parseInt(dokter41Tmp.replace('.',''));
        totalDokter41 = totalDokter41+Dokter41
		
		dokter42Tmp = $('#valdok_'+i+'_42').text();
        Dokter42 = parseInt(dokter42Tmp.replace('.',''));
        totalDokter42 = totalDokter42+Dokter42
		
		dokter43Tmp = $('#valdok_'+i+'_43').text();
        Dokter43 = parseInt(dokter43Tmp.replace('.',''));
        totalDokter43 = totalDokter43+Dokter43
		
		dokter44Tmp = $('#valdok_'+i+'_44').text();
        Dokter44 = parseInt(dokter44Tmp.replace('.',''));
        totalDokter44 = totalDokter44+Dokter44
		
		dokter45Tmp = $('#valdok_'+i+'_45').text();
        Dokter45 = parseInt(dokter45Tmp.replace('.',''));
        totalDokter45 = totalDokter45+Dokter45
		
		dokter46Tmp = $('#valdok_'+i+'_46').text();
        Dokter46 = parseInt(dokter46Tmp.replace('.',''));
        totalDokter46 = totalDokter46+Dokter46
		
		dokter47Tmp = $('#valdok_'+i+'_47').text();
        Dokter47 = parseInt(dokter47Tmp.replace('.',''));
        totalDokter47 = totalDokter47+Dokter47
		
		dokter48Tmp = $('#valdok_'+i+'_48').text();
        Dokter48 = parseInt(dokter48Tmp.replace('.',''));
        totalDokter48 = totalDokter48+Dokter48
		
		dokter49Tmp = $('#valdok_'+i+'_49').text();
        Dokter49 = parseInt(dokter49Tmp.replace('.',''));
        totalDokter49 = totalDokter49+Dokter49
		
		dokter50Tmp = $('#valdok_'+i+'_50').text();
        Dokter50 = parseInt(dokter50Tmp.replace('.',''));
        totalDokter50 = totalDokter50+Dokter50
		
		dokter51Tmp = $('#valdok_'+i+'_51').text();
        Dokter51 = parseInt(dokter51Tmp.replace('.',''));
        totalDokter51 = totalDokter51+Dokter51
		//--
		
		TotAsistenTmp = $('#valtotas_'+i+'_1').text();
        TotAsisten = parseInt(TotAsistenTmp.replace('.',''));
        valtotalAsisten = valtotalAsisten+TotAsisten
		
		TotRsTmp = $('#valtotrs_'+i+'_1').text();
        TotRs = parseInt(TotRsTmp.replace('.',''));
        valtotalRs = valtotalRs+TotRs
		
		TotAlatTmp = $('#valtotal_'+i+'_1').text();
        TotAlat = parseInt(TotAlatTmp.replace('.',''));
        valtotalAlat = valtotalAlat+TotAlat
		
		TotBahTmp = $('#valtotbah_'+i+'_1').text();
        TotBah = parseInt(TotBahTmp.replace('.',''));
        valtotalBahan = valtotalBahan+TotBah
		
		TotDllTmp = $('#valtotdll_'+i+'_1').text();
        TotDll = parseInt(TotDllTmp.replace('.',''));
        valtotalDll = valtotalDll+TotDll
		
		TotDisTmp = $('#valtotdis_'+i+'_1').text();
        TotDis = parseInt(TotDisTmp.replace('.',''));
        valtotDis = valtotDis+TotDis
		
		//--
		
		bhpTmp = $('#val_bhp_'+i).text();
        bhp = parseInt(bhpTmp.replace('.',''));
        totalBHP = totalBHP+bhp;
		
        obatTmp = $('#val_obat_'+i).text();
        obat = parseInt(obatTmp.replace('.',''));
        totalObat = totalObat+obat;
		
		racikanTmp = $('#val_racikan_'+i).text();
        racikan = parseInt(racikanTmp.replace('.',''));
        totalRacikan = totalRacikan+racikan;
		
		kamarTmp = $('#val_kamar_'+i).text();
        kamar = parseInt(kamarTmp.replace('.',''));
        totalKamar = totalKamar+kamar;

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
        
        pembulatanTmp = $('#val_pembulatan_'+i).text();
        pembulatan = parseInt(pembulatanTmp.replace('.',''));
        totalPembulatan = totalPembulatan+pembulatan;
        
        totalPembulatanTmp = $('#val_total_pembulatan_'+i).text();
        totalPembulatan2 = parseInt(totalPembulatanTmp.replace('.',''));
        totalTotalPembulatan = totalTotalPembulatan+totalPembulatan2;
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
	$('#jumlah_akomodasi').text(totalAkomodasi);
	$('#jumlah_transfusi').text(totalTransfusi);
	$('#jumlah_rujukan').text(totalRujukan);
	$('#jumlah_sewaok').text(totalSewaok);
    $('#jumlah_pendaftaran').text(totalPendaftaran);
    $('#jumlah_usg').text(totalUSG);
    $('#jumlah_ekg').text(totalEKG);
    $('#jumlah_rm').text(totalRM);
	
    $('#jumlah_dokter1').text(totalDokter1);
	$('#jumlah_dokter2').text(totalDokter2);
	$('#jumlah_dokter3').text(totalDokter3);
	$('#jumlah_dokter4').text(totalDokter4);
	$('#jumlah_dokter5').text(totalDokter5);
	$('#jumlah_dokter6').text(totalDokter6);
	$('#jumlah_dokter7').text(totalDokter7);
	$('#jumlah_dokter8').text(totalDokter8);
	$('#jumlah_dokter9').text(totalDokter9);
	$('#jumlah_dokter10').text(totalDokter10);
	$('#jumlah_dokter11').text(totalDokter11);
	$('#jumlah_dokter12').text(totalDokter12);
	$('#jumlah_dokter13').text(totalDokter13);
	$('#jumlah_dokter14').text(totalDokter14);
	$('#jumlah_dokter15').text(totalDokter15);
	$('#jumlah_dokter16').text(totalDokter16);
	$('#jumlah_dokter17').text(totalDokter17);
	$('#jumlah_dokter18').text(totalDokter18);
	$('#jumlah_dokter19').text(totalDokter19);
	$('#jumlah_dokter20').text(totalDokter20);
	$('#jumlah_dokter21').text(totalDokter21);
	$('#jumlah_dokter22').text(totalDokter22);
	$('#jumlah_dokter23').text(totalDokter23);
	$('#jumlah_dokter24').text(totalDokter24);
	$('#jumlah_dokter25').text(totalDokter25);
	$('#jumlah_dokter26').text(totalDokter26);
	$('#jumlah_dokter27').text(totalDokter27);
	$('#jumlah_dokter28').text(totalDokter28);
	$('#jumlah_dokter29').text(totalDokter29);
	$('#jumlah_dokter30').text(totalDokter30);
	$('#jumlah_dokter31').text(totalDokter31);
	$('#jumlah_dokter32').text(totalDokter32);
	$('#jumlah_dokter33').text(totalDokter33);
	$('#jumlah_dokter34').text(totalDokter34);
	$('#jumlah_dokter35').text(totalDokter35);
	$('#jumlah_dokter36').text(totalDokter36);
	$('#jumlah_dokter37').text(totalDokter37);
	$('#jumlah_dokter38').text(totalDokter38);
	$('#jumlah_dokter39').text(totalDokter39);
	$('#jumlah_dokter40').text(totalDokter40);
	$('#jumlah_dokter41').text(totalDokter41);
	$('#jumlah_dokter42').text(totalDokter42);
	$('#jumlah_dokter43').text(totalDokter43);
	$('#jumlah_dokter44').text(totalDokter44);
	$('#jumlah_dokter45').text(totalDokter45);
	$('#jumlah_dokter46').text(totalDokter46);
	$('#jumlah_dokter47').text(totalDokter47);
	$('#jumlah_dokter48').text(totalDokter48);
	$('#jumlah_dokter49').text(totalDokter49);
	$('#jumlah_dokter50').text(totalDokter50);
	$('#jumlah_dokter51').text(totalDokter51);
	
	$('#jumlah_tot_asisten').text(valtotalAsisten);
	$('#jumlah_tot_rs').text(valtotalRs);
	$('#jumlah_tot_alat').text(valtotalAlat);
	$('#jumlah_tot_bahan').text(valtotalBahan);
	$('#jumlah_tot_dll').text(valtotalDll);
	$('#jumlah_tot_dis').text(valtotDis);
	
    $('#jumlah_bhp').text(totalBHP);
    $('#jumlah_obat').text(totalObat);
    $('#jumlah_racikan').text(totalRacikan);
    $('#jumlah_kamar').text(totalKamar);
    $('#jumlah_tagihan').text(totalTagihan);
    $('#jumlah_tunai').text(totalTunai);
    $('#jumlah_potongan').text(totalPotongan);
    $('#jumlah_askes').text(totalAskes);
    $('#jumlah_sisa').text(totalSisa);
    $('#jumlah_pembulatan').text(totalPembulatan);
    $('#jumlah_total_pembulatan').text(totalTotalPembulatan);

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
