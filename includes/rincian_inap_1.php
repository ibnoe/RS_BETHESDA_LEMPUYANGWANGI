<? // Agung Sunandar , Menampilkan lap. Buku Besar Klinik


$PID = "rincian_inap_1";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");


    // search box
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/akuntansi-bukubesar.png' align='absmiddle' > RINCIAN PENERIMAAN RAWAT INAP");
		title_excel("rincian_inap_1&tanggal1D=".$_GET["tanggal1D"]."1&tanggal1M=".$_GET["tanggal1M"]."2&tanggal1Y=".$_GET["tanggal1Y"]."2012&tanggal2D=".$_GET["tanggal2D"]."16&tanggal2M=".$_GET["tanggal2M"]."2&tanggal2Y=".$_GET["tanggal2Y"]."2012&pos=L");
    } else {
    	title("<img src='icon/akuntansi-bukubesar.png' align='absmiddle' > RINCIAN PENERIMAAN RAWAT INAP");
    }
    echo "<br>";
    //$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

    if (!$GLOBALS['print']){
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

	    $tgl_sakjane = $_GET[tanggal2D] + 1;
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");

	    }
		$f->selectArray("pos", "Posisi", Array("L" => "Kiri", "R" => "Kanan"), $_GET["pos"],"");
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

	    $tgl_sakjane = $_GET[tanggal2D] + 1;
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");

	    }
		$f->selectArray("pos", "Posisi", Array("L" => "Kiri", "R" => "Kanan"), $_GET["pos"],"disabled");
    	$f->execute();
	}

    echo "<br>";

    echo "<br>";


//---------------agung 04/2011---------------


?>
<table width="100%">
	<tr>
		<td align="center" class="TBL_JUDUL">RSUD dr. ACHMAD MOCHTAR BUKITTINGGI</td>
	</tr>
	<tr>
		<td align="center" class="TBL_JUDUL">RINCIAN PENERIMAAN RAWAT INAP</td>
	</tr>
	<tr>
	<?if ($_GET["pos"]=="L") {?>
	<td align="left" class="TBL_JUDUL">FORM 1</td>
	<?}elseif ($_GET["pos"]=="R") {?>
	<td align="left" class="TBL_JUDUL">FORM 2</td>
	<?}?>
	</tr>
</table>

<br>
<br>
<TABLE BORDER="0" CLASS="TBL_BORDER">

              <tr>
                
				<?if ($_GET["pos"]=="L") {?>
				<td class="TBL_HEAD" rowspan="2"><div align="center">NO</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">TGL. LUNAS</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">TGL. KWITANSI </div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">&nbsp;&nbsp;&nbsp;&nbsp;NAMA&nbsp;&nbsp;&nbsp;&nbsp;PASIEN&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">NO. MR </div></td>
				
                <td class="TBL_HEAD" colspan="8"><div align="center">STATUS PASIEN</div></td>
                <td class="TBL_HEAD" colspan="2"><div align="center">TANGGAL</div></td>
                <td class="TBL_HEAD" colspan="12"><div align="center">KELAS BANGSAL</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">AKOMODASI</div></td>
				<td class="TBL_HEAD" rowspan="2"><div align="center">VISITE</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">KONSUL</div></td>
				<td class="TBL_HEAD" colspan="2"><div align="center">OPERASI</div></td>
				<td class="TBL_HEAD" colspan="2"><div align="center">PERSALINAN</div></td>
				<td class="TBL_HEAD" rowspan="2"><div align="center">TINDAKAN RUANG</div></td>
				<?}elseif ($_GET["pos"]=="R") {?>
				<td class="TBL_HEAD" rowspan="2"><div align="center">NO</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">TGL. LUNAS</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">TGL. KWITANSI </div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">&nbsp;&nbsp;&nbsp;&nbsp;NAMA&nbsp;&nbsp;&nbsp;&nbsp;PASIEN&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">NO. MR </div></td>
				
				<td class="TBL_HEAD" colspan="12"><div align="center">ALAT/PEMERIKSAAN ELEKTROMEDIK</div></td>
				<td class="TBL_HEAD" rowspan="2"><div align="center">FISIOTERAPI</div></td>
                <td class="TBL_HEAD" colspan="6"><div align="center">PENUNJANG</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">OKSIGEN</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">GALON</div></td>
				<td class="TBL_HEAD" rowspan="2"><div align="center">LAIN-LAIN</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">TOTAL</div></td>
				<?}?>
              </tr>

            <tr>
			<?if ($_GET["pos"]=="L") {?>
                <td class="TBL_HEAD" ><div align="center">UMUM</div></td>
                <td class="TBL_HEAD"><div align="center">JAMKESMAS</div></td>
                <td class="TBL_HEAD"><div align="center">JAMSOSTEK</div></td>
                <td class="TBL_HEAD"><div align="center">JEMINAN PEMDA</div></td>
				<td class="TBL_HEAD" ><div align="center">AKSES SOS</div></td>
                <td class="TBL_HEAD"><div align="center">JAMKESDA</div></td>
                <td class="TBL_HEAD"><div align="center">MPK</div></td>
                <td class="TBL_HEAD"><div align="center">JAMPERSAL</div></td>
				
				<td class="TBL_HEAD"><div align="center">MASUK</div></td>
                <td class="TBL_HEAD"><div align="center">KELUAR</div></td>
				
				<td class="TBL_HEAD"><div align="center">VVIP</div></td>
                <td class="TBL_HEAD"><div align="center">VIP</div></td>
                <td class="TBL_HEAD"><div align="center">VIP A</div></td>
                <td class="TBL_HEAD"><div align="center">VIP B</div></td>
                <td class="TBL_HEAD"><div align="center">VIP C</div></td>
                <td class="TBL_HEAD"><div align="center">VIP D</div></td>
				<td class="TBL_HEAD"><div align="center">VIP UTAMA</div></td>
                <td class="TBL_HEAD"><div align="center">KELAS I</div></td>
				<td class="TBL_HEAD"><div align="center">KELAS II</div></td>
				<td class="TBL_HEAD"><div align="center">KELAS III</div></td>
				<td class="TBL_HEAD"><div align="center">KELAS UMUM</div></td>
                <td class="TBL_HEAD"><div align="center">JUMLAH RAWAT</div></td>
				
				<td class="TBL_HEAD"><div align="center">PAKET</div></td>
                <td class="TBL_HEAD"><div align="center">NON PAKET</div></td>
				
				<td class="TBL_HEAD"><div align="center">PER-VAGINAL</div></td>
                <td class="TBL_HEAD"><div align="center">PER-ABNORMAL</div></td>
			<?}elseif ($_GET["pos"]=="R") {?>
				<td class="TBL_HEAD" ><div align="center">EKG</div></td>
                <td class="TBL_HEAD"><div align="center">USG</div></td>
                <td class="TBL_HEAD"><div align="center">MONITOR</div></td>
                <td class="TBL_HEAD"><div align="center">ENDOSCOPY</div></td>
				<td class="TBL_HEAD" ><div align="center">INFUSION PUMP</div></td>
                <td class="TBL_HEAD"><div align="center">INCUBATOR</div></td>
                <td class="TBL_HEAD"><div align="center">TREADMIL</div></td>
                <td class="TBL_HEAD"><div align="center">ECHO</div></td>
				<td class="TBL_HEAD"><div align="center">EEG</div></td>
                <td class="TBL_HEAD"><div align="center">TCD</div></td>
				<td class="TBL_HEAD"><div align="center">NEBULIZER</div></td>
                <td class="TBL_HEAD"><div align="center">JUMLAH</div></td>
				
                <td class="TBL_HEAD"><div align="center">RADIOLOGI</div></td>
                <td class="TBL_HEAD"><div align="center">LAB. KLINIK</div></td>
                <td class="TBL_HEAD"><div align="center">LAB.&nbsp;PA</div></td>
                <td class="TBL_HEAD"><div align="center">REHABILITASI MEDIK</div></td>
                <td class="TBL_HEAD"><div align="center">INSTALASI</div></td>
                <td class="TBL_HEAD"><div align="center">JUMLAH</div></td>
			<?}?>
            </tr>
	<?
	$sql5a = "select to_char(kwitansi,'dd/mm/yyyy') as kwitansi1,kwitansi from rsv_layanan_inap3 group by kwitansi order by kwitansi asc";
	@$r5a = pg_query($con,$sql5a);
	@$n5a = pg_num_rows($r5a);

	$max_row5a= 9999999 ;
	$mulai5a = $HTTP_GET_VARS["rec"] ;
	if (!$mulai5a){$mulai5a=1;}
	
	$i5a= 1 ;
	$j5a= 1 ;
	$last_id5a=1;
	while (@$row5a = pg_fetch_array($r5a)){
		if (($j5a<=$max_row5a) AND ($i5a >= $mulai5a)){
		  $no5a=$i5a;
	
	$sql5 = "select *, (ekg + usg + monitor + endoscopy + pump + incubator + treadmil + echo + eeg + tcd + nebulizer) as jumlah_1, 
			(radiologi + lab_klinik + lab_pa + rehab + instalasi) as jumlah_penunjang, (tindakan + persalinan_abnormal + persalinan_vaginam + operasi_non_paket + operasi_paket + konsul + akomodasi + visite + ekg + usg + monitor + endoscopy + pump + incubator + treadmil + echo + eeg + tcd + nebulizer + radiologi 
			+ lab_klinik + lab_pa + rehab + instalasi + fisio + oksigen + galon + lain_lain) as total
			from rsv_layanan_inap3
			order by tgl_keluar asc";
	@$r5 = pg_query($con,$sql5);
	@$n5 = pg_num_rows($r5);

	$max_row5= 9999999 ;
	$mulai5 = $HTTP_GET_VARS["rec"] ;
	if (!$mulai5){$mulai5=1;}
		
					$row5=0;
					$i5= 1 ;
					$j5= 1 ;
					$last_id5=1;
					while (@$row5 = pg_fetch_array($r5)){
						  if (($j5<=$max_row5) AND ($i5 >= $mulai5)){
							 $no5=$i5;
							 if ($row5["kwitansi"]==$row5a["kwitansi"]){
	?>
	<tr>
		
		<?if ($_GET["pos"]=="L") {?>
		<td class="TBL_BODY" align="center"><?=$no5 ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["kwitansi"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["kwitansi"] ?></td>
		<td class="TBL_BODY" align="left"><?=$row5["nama"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["mr_no"] ?></td>
		
		<td class="TBL_BODY" align="center"><?=$row5["umum"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["jamkesmas"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["jamsostek"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["jamperda"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["askes"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["jamkesda"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["mpk"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["jampersal"] ?></td>
		
		<td class="TBL_BODY" align="right"><?=$row5["tanggal_reg"] ?></td>
		<td class="TBL_BODY" align="right"><?=$row5["tgl_keluar"] ?></td>
		
		<td class="TBL_BODY" align="center"><?=$row5["vvip"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["vip"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["vipa"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["vipb"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["vipc"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["vipd"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["vip_utama"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["kls1"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["kls2"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["kls3"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["kelas_umum"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["jml_hari"] ?></td>
		
		<td class="TBL_BODY" align="right"><?=number_format($row5["akomodasi"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["visite"],2,",",".") ?></td>

		<td class="TBL_BODY" align="right"><?=number_format($row5["konsul"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["operasi_paket"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["operasi_non_paket"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["persalinan_vaginam"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["persalinan_abnormal"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["tindakan"],2,",",".") ?></td>
		<?}elseif ($_GET["pos"]=="R") {?>
		<td class="TBL_BODY" align="center"><?=$no5 ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["kwitansi"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["kwitansi"] ?></td>
		<td class="TBL_BODY" align="left"><?=$row5["nama"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["mr_no"] ?></td>
		
		<td class="TBL_BODY" align="right"><?=number_format($row5["ekg"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["usg"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["monitor"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["endoscopy"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["pump"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["incubator"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["treadmil"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["echo"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["eeg"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["tcd"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["nebulizer"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["jumlah_1"],2,",",".") ?></td>
		
		<td class="TBL_BODY" align="right"><?=number_format($row5["fisio"],2,",",".") ?></td>
		
		<td class="TBL_BODY" align="right"><?=number_format($row5["radiologi"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["lab_klinik"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["lab_pa"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["rehab"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["instalasi"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["jumlah_penunjang"],2,",",".") ?></td>
		
		<td class="TBL_BODY" align="right"><?=number_format($row5["oksigen"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["galon"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["lain_lain"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["total"],2,",",".") ?></td>
		<?}?>
		
	</tr>
	<?
	$akomodasi=$akomodasi + $row5["akomodasi"];
	$visite=$visite + $row5["visite"];
	$konsul=$konsul + $row5["konsul"];
	$operasi_paket = $operasi_paket + $row5["operasi_paket"];
	$operasi_non_paket = $operasi_non_paket + $row5["operasi_non_paket"];
	$persalinan_vaginam = $persalinan_vaginam + $row5["persalinan_vaginam"];
	$persalinan_abnormal = $persalinan_abnormal + $row5["persalinan_abnormal"];
	$tindakan = $tindakan + $row5["tindakan"];
	
	$ekg = $ekg + $row5["ekg"];
	$usg =$usg + $row5["usg"];
	$monitor=$monitor + $row5["monitor"];
	$endoscopy =$endoscopy + $row5["endoscopy"];
	$pump = $pump + $row5["pump"];
	$incubator = $incubator + $row5["incubator"];
	$treadmil = $treadmil + $row5["treadmil"];
	$echo = $echo + $row5["echo"];
	$eeg = $eeg + $row5["eeg"];
	$tcd = $tcd + $row5["tcd"];
	$nebulizer = $nebulizer + $row5["nebulizer"];
	
	$jumlah_1 = $jumlah_1 + $row5["jumlah_1"];
	
	$fisio = $fisio + $row5["fisio"];
	
	$penunjang_1=$penunjang_1 + $row5["radiologi"];
	$penunjang_2=$penunjang_2 + $row5["lab_klinik"];
	$penunjang_3=$penunjang_3 + $row5["lab_pa"];
	$penunjang_4=$penunjang_4 + $row5["rehab"];
	$penunjang_5=$penunjang_5 + $row5["instalasi"];
	$penunjang_total=$penunjang_total + $row5["jumlah_penunjang"];
	
	$oksigen = $oksigen + $row5["oksigen"];
	$galon = $galon + $row5["galon"];
	$lain_lain = $lain_lain + $row5["lain_lain"];
	
	$total = $total + $row5["total"];
	
		;$j5++;}

    $i5++;}
	}
	
	
	$akomodasi_ = getFromTable("select sum(akomodasi) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$visite_ = getFromTable("select sum(visite) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$konsul_ = getFromTable("select sum(konsul) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$operasi_paket_ = getFromTable("select sum(operasi_paket) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$operasi_non_paket_ = getFromTable("select sum(operasi_non_paket) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$persalinan_vaginam_ = getFromTable("select sum(persalinan_vaginam) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$persalinan_abnormal_ = getFromTable("select sum(persalinan_abnormal) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$tindakan_ = getFromTable("select sum(tindakan) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	
	$ekg_ = getFromTable("select sum(ekg) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$usg_ = getFromTable("select sum(usg) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$monitor_ = getFromTable("select sum(monitor) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$endoscopy_ = getFromTable("select sum(endoscopy) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$pump_ = getFromTable("select sum(pump) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$incubator_ = getFromTable("select sum(incubator) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$treadmil_ = getFromTable("select sum(treadmil) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$echo_ = getFromTable("select sum(echo) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$eeg_ = getFromTable("select sum(eeg) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$tcd_ = getFromTable("select sum(tcd) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$nebulizer_ = getFromTable("select sum(nebulizer) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$jumlah_1_ = getFromTable("select sum(ekg + usg + monitor + endoscopy + pump + incubator + treadmil + echo + eeg + tcd + nebulizer) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	
	$fisio_ = getFromTable("select sum(fisio) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	
	$radiologi_ = getFromTable("select sum(radiologi) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$lab_klinik_ = getFromTable("select sum(lab_klinik) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$lab_pa_ = getFromTable("select sum(lab_pa) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$rehab_ = getFromTable("select sum(rehab) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$instalasi_ = getFromTable("select sum(instalasi) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$jumlah_penunjang_ = getFromTable("select sum(jumlah_penunjang) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	
	$oksigen_ = getFromTable("select sum(oksigen) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$galon_ = getFromTable("select sum(galon) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	$lain_lain_ = getFromTable("select sum(lain_lain) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	
	$total_ = getFromTable("select sum(tindakan + persalinan_abnormal + persalinan_vaginam + operasi_non_paket + operasi_paket + konsul + akomodasi + visite + ekg + usg + monitor + endoscopy + pump + incubator + treadmil + echo + eeg + tcd + nebulizer + radiologi 
							+ lab_klinik + lab_pa + rehab + instalasi + fisio + oksigen + galon + lain_lain) from rsv_layanan_inap3 where kwitansi='".$row5a["kwitansi"]."'");
	?>	
	<tr>
	<?if ($_GET["pos"]=="L") {?>
		<td colspan="27" class="TBL_FOOT" align="center">TOTAL TANGGAL <?= $row5a["kwitansi1"] ?> </td>
		<td class="TBL_FOOT" align="right"><?=number_format($akomodasi_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($visite_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($konsul_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($operasi_paket_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($operasi_non_paket_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($persalinan_vaginam_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($persalinan_abnormal_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($tindakan_,2,",",".") ?></td>
	<?}elseif ($_GET["pos"]=="R") {?>
		<td colspan="5" class="TBL_FOOT" align="center">TOTAL TANGGAL <?= $row5a["kwitansi1"] ?> </td>
		<td class="TBL_FOOT" align="right"><?=number_format($ekg_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($usg_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($monitor_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($endoscopy_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($pump_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($incubator_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($treadmil_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($echo_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($eeg_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($tcd_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($nebulizer_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($jumlah_1_,2,",",".") ?></td>
		
		<td class="TBL_FOOT" align="right"><?=number_format($fisio_,2,",",".") ?></td>
		
		<td class="TBL_FOOT" align="right"><?=number_format($radiologi_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($lab_klinik_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($lab_pa_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($rehab_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($instalasi_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($jumlah_penunjang_,2,",",".") ?></td>
		
		<td class="TBL_FOOT" align="right"><?=number_format($oksigen_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($galon_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($lain_lain_,2,",",".") ?></td>
		
		<td class="TBL_FOOT" align="right"><?=number_format($total_,2,",",".") ?></td>
	<?}?>
	</tr>	
	<?
		;$j5a++;}

	$i5a++;
	}
	?>
	
	<tr>
	<?if ($_GET["pos"]=="L") {?>
		<td colspan="27" class="TBL_FOOT" align="center">TOTAL</td>
		<td class="TBL_FOOT" align="right"><?=number_format($akomodasi,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($visite,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($konsul,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($operasi_paket,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($operasi_non_paket,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($persalinan_vaginam,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($persalinan_abnormal,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($tindakan,2,",",".") ?></td>
		<?}elseif ($_GET["pos"]=="R") {?>
		<td colspan="5" class="TBL_FOOT" align="center">TOTAL TANGGAL <?= $row5a["kwitansi1"] ?> </td>
		<td class="TBL_FOOT" align="right"><?=number_format($ekg,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($usg,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($monitor,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($endoscopy,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($pump,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($incubator,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($treadmil,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($echo,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($eeg,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($tcd,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($nebulizer,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($jumlah_1,2,",",".") ?></td>
		
		<td class="TBL_FOOT" align="right"><?=number_format($fisio,2,",",".") ?></td>
		
		<td class="TBL_FOOT" align="right"><?=number_format($radiologi,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($lab_klinik,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($lab_pa,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($rehab,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($instalasi,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($jumlah_penunjang,2,",",".") ?></td>
		
		<td class="TBL_FOOT" align="right"><?=number_format($oksigen,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($galon,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($lain_lain,2,",",".") ?></td>
		
		<td class="TBL_FOOT" align="right"><?=number_format($total,2,",",".") ?></td>
		<?}?>
	</tr>
</TABLE>