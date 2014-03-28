<SCRIPT language="JavaScript" src="plugin/jquery.js"></SCRIPT>
<SCRIPT language="JavaScript" src="plugin/jquery.price_format.1.7.js"></SCRIPT>
<?

$PID = "lap_pend_rj";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

  

title("<img src='icon/keuangan-2.gif' align='absmiddle' > Laporan Pendapatan Rawat Jalan");
	
	$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);


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

	    $f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
        						 "select tdesc as n, tdesc from rs00001 where tt = 'JEP' ORDER BY tdesc ASC ", $_GET["mPASIEN"],"");
		$f->selectSQL("mPOLI", "Poli","select '' as tc, '' as tdesc union ".
							"SELECT c.tc,c.tdesc FROM rs00001 c, rs00006 d WHERE CAST (c.tc AS NUMERIC)=d.poli and c.tt = 'LYN' and c.tc not in ('000','201','202','206','207','208')
							 order by tdesc ",$_GET["mPOLI"], "102");
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
	    $f->title("Dari Tanggal : ".$ts_check_in1);
		$f->title("s/d          : ".$ts_check_in2);
	    }

	    $f->title("Tipe Pasien  : ".$_GET["mPASIEN"]);
	    $f->title("Poli  : ".$_GET["mPOLI"]);
	    $f->execute();
	}

    echo "<br>";
    if (!empty($_GET[mPASIEN])) {
       $SQL_b = " and b.tdesc = '".$_GET["mPASIEN"]."' ";
       $SQL_b2 = " and y.tdesc = '".$_GET["mPASIEN"]."' ";

    } else {
       $SQL_b = " ";
    }
	
    if (!empty($_GET[mPOLI])) {
       $SQL_b = " and b.tdesc = '".$_GET["mPOLI"]."' ";
       $SQL_b2 = " and y.tdesc = '".$_GET["mPOLI"]."' ";

    } else {
       $SQL_b = " ";
    }
    
	if (empty($_GET[mPOLI]) == " ")
	{
	$SQL = "select tgl_entry, reg, mr_no, nama, nm_poli, obat, bayar_tunai, bayar_potongan, bayar_askes
			from rsv_lap_rj 
			where (tgl_entry between '$ts_check_in1' and '$ts_check_in2') and tipe_pasien like '%".$_GET["mPASIEN"]."%' order by reg desc";
	} else {
	$SQL = "select tgl_entry, reg, mr_no, nama, nm_poli, obat, bayar_tunai, bayar_potongan, bayar_askes
			from rsv_lap_rj 
			where (tgl_entry between '$ts_check_in1' and '$ts_check_in2') and tipe_pasien like '%".$_GET["mPASIEN"]."%' and id_poli::text = '".$_GET["mPOLI"]."'  order by reg desc";
    }

	$result = pg_query($con,$SQL);
	
	$sqlSumberPendapatan	= "select tdesc from rs00001 where tt='SBP' and tc !='000' order by tc";
	$resultSumberPendapatan = pg_query($con,$sqlSumberPendapatan);
	while ($rowSumberPendapatan = pg_fetch_array($resultSumberPendapatan)){ 
			$arrSumberPendapatan[] = $rowSumberPendapatan;	
	}

	$thisUrl = apache_getenv("REQUEST_URI");
	$excelUrl = "includes/lap_pend_rj_xls.php?tanggal1D=".$_GET['tanggal1D']."&tanggal1M=".$_GET['tanggal1M']."&tanggal1Y=".$_GET['tanggal1Y']."&tanggal2D=".$_GET['tanggal2D']."&tanggal2M=".$_GET['tanggal2M']."&tanggal2Y=".$_GET['tanggal2Y']."&mPASIEN=".$_GET['mPASIEN']."&mPOLI=".$_GET['mPOLI'];

?>

&nbsp;&nbsp;&nbsp; <a href="<?php echo $excelUrl?>"><img border="0" src="icon/Excel-22.gif" width="24"><b>[ Download To Excel ]</b></a><br/><br/>
<table width="100%" border="1">
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
		foreach($arrSumberPendapatan as $key => $val){ 
			if($val["tdesc"] == 'FISIOTERAPHI/NEBULIZER'){
				echo '<td class="TBL_HEAD" align="center" width="7%">FISIOTERAPHI<br/>NEBULIZER</td>';
			}else{
				echo '<td class="TBL_HEAD" align="center" width="7%">'.$val["tdesc"].'</td>';	
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
	
	<?	
		$i=0;
		while ($row = pg_fetch_array($result)){
			$i++;
	?>		
		<tr valign="top"> 
			<td class="TBL_BODY" align="center"><?=$i ?> </td>
			<td class="TBL_BODY" align="left"><?=$row["tgl_entry"] ?> </td>
			<td class="TBL_BODY" align="left"><?=$row["reg"] ?> </td>
			<td class="TBL_BODY" align="left"><?=$row["mr_no"] ?> </td>
			<td class="TBL_BODY" align="left"><?=$row["nama"] ?> </td>
			<td class="TBL_BODY" align="left"><?=$row["nm_poli"] ?> </td>
			<?php
			$j=0;
			$totalJMLSumberPendapatan=0;
			foreach($arrSumberPendapatan as $key => $val){ 
				$j++;
				$sqlJMLSumberPendapatan = "SELECT sum(a.tagihan) as jumlah 
										FROM rs00008 a
										LEFT JOIN rs00034 b on b.id = a.item_id::numeric
										LEFT JOIN rs00001 c on c.tc = b.sumber_pendapatan_id and c.tt='SBP'  
										WHERE a.no_reg='".$row["reg"]."' AND (a.trans_type='LTM') and upper(c.tdesc) like '%".strtoupper($val["tdesc"])."%'";
				
				$resultJMLSumberPendapatan = pg_query($con,$sqlJMLSumberPendapatan);
				
				while ($rowJMLSumberPendapatan = pg_fetch_array($resultJMLSumberPendapatan)){
					$jumlah = $rowJMLSumberPendapatan["jumlah"];
					$totalJMLSumberPendapatan = $totalJMLSumberPendapatan + $jumlah;
					echo '<td class="TBL_BODY" align="right" id="val_'.$i.'_'.$j.'">';
					echo number_format($jumlah, 0, " ", ".");
					echo '</td>';
				}
				
			}
			$totalPembayaran = $row["bayar_tunai"]+$row["bayar_potongan"]+$row["bayar_askes"];
			?>
			<td class="TBL_BODY" align="right"><?php echo number_format(0, 0, " ", ".") ?> </td>
			<td class="TBL_BODY" align="right" id="val_obat_<?php echo $i?>"><?php echo number_format($row["obat"], 0, " ", ".") ?> </td>
			<td class="TBL_BODY" align="right"><?php echo number_format(0, 0, " ", ".") ?> </td>
			<td class="TBL_BODY" align="right" id="val_tagihan_<?php echo $i?>"><?php echo number_format($totalJMLSumberPendapatan+$row["obat"], 0, " ", ".") ?> </td>
			<td class="TBL_BODY" align="right" id="val_tunai_<?php echo $i?>"><?php echo number_format($row["bayar_tunai"], 0, " ", ".") ?> </td>
			<td class="TBL_BODY" align="right" id="val_potongan_<?php echo $i?>"><?php echo number_format($row["bayar_potongan"], 0, " ", ".") ?> </td>
			<td class="TBL_BODY" align="right" id="val_askes_<?php echo $i?>"><?php echo number_format($row["bayar_askes"], 0, " ", ".") ?> </td>
			<td class="TBL_BODY" align="right" id="val_sisa_<?php echo $i?>"><?php echo number_format(($totalJMLSumberPendapatan+$row["obat"])-$totalPembayaran, 0, " ", ".") ?> </td>
		</tr>
	<?	
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
<script>
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
for(i=1;i<=<?php echo $i?>;i++){
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