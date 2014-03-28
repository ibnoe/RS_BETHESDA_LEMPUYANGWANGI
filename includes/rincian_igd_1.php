<? // Agung Sunandar , Menampilkan lap. Buku Besar Klinik


$PID = "rincian_igd_1";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");


    // search box
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/akuntansi-bukubesar.png' align='absmiddle' > RINCIAN PENERIMAAN IGD");
		title_excel("rincian_igd_1&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."");
    } else {
    	title("<img src='icon/akuntansi-bukubesar.png' align='absmiddle' > RINCIAN PENERIMAAN IGD");
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

    	$f->execute();
	}

    echo "<br>";

    echo "<br>";


//---------------agung 04/2011---------------

/* $sql = "select a.poli, b.tdesc from rsv_layanan a
        left join rs00001 b on a.poli::text=b.tc and b.tt='LYN'
        where a.poli='".$_GET["mRAWAT"]."'
        group by a.poli, b.tdesc
        order by a.poli, b.tdesc ";

        @$r1 = pg_query($con,$sql);
        @$n1 = pg_num_rows($r1);

        $max_row1= 200 ;
        $mulai1 = $HTTP_GET_VARS["rec"] ;
        if (!$mulai1){$mulai1=1;} */

?>
<table width="100%">
	<tr>
		<td align="center" class="TBL_JUDUL">RSUD dr. ACHMAD MOCHTAR BUKITTINGGI</td>
	</tr>
	<tr>
		<td align="center" class="TBL_JUDUL">RINCIAN PENERIMAAN IGD</td>
	</tr>
</table>

<br>
<br>
<TABLE BORDER="0" CLASS="TBL_BORDER">

              <tr>
                <td class="TBL_HEAD" rowspan="2"><div align="center">NO</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">TGL. LUNAS</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">TGL. KWITANSI </div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">&nbsp;&nbsp;&nbsp;&nbsp;NAMA&nbsp;&nbsp;&nbsp;&nbsp;PASIEN&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">NO. MR </div></td>
                <td class="TBL_HEAD" colspan="4"><div align="center">KARCIS</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">KONSUL</div></td>
                <td class="TBL_HEAD" colspan="6"><div align="center">PENUNJANG</div></td>
                <td class="TBL_HEAD" colspan="12"><div align="center">TINDAKAN MEDIK</div></td>
				<td class="TBL_HEAD" rowspan="2"><div align="center">OBAT</div></td>
                <td class="TBL_HEAD" rowspan="2"><div align="center">TOTAL</div></td>
              </tr>

            <tr>
                <td class="TBL_HEAD" ><div align="center">KARCIS UMUM </div></td>
                <td class="TBL_HEAD"><div align="center">KARCIS SPESIALIS </div></td>
                <td class="TBL_HEAD"><div align="center">STATUS (MR) </div></td>
                <td class="TBL_HEAD"><div align="center">JUMLAH</div></td>
				
				<td class="TBL_HEAD"><div align="center">RADIOLOGI</div></td>
                <td class="TBL_HEAD"><div align="center">LAB. KLINIK</div></td>
                <td class="TBL_HEAD"><div align="center">LAB.&nbsp;PA</div></td>
                <td class="TBL_HEAD"><div align="center">REHABILITASI MEDIK</div></td>
                <td class="TBL_HEAD"><div align="center">INSTALASI</div></td>
                <td class="TBL_HEAD"><div align="center">JUMLAH</div></td>
				
                <td class="TBL_HEAD"><div align="center">JAHIT LUKA</div></td>
                <td class="TBL_HEAD"><div align="center">PASANG INFUS</div></td>
                <td class="TBL_HEAD"><div align="center">PASANG CATETER</div></td>
                <td class="TBL_HEAD"><div align="center">PASANG NGT</div></td>
                <td class="TBL_HEAD"><div align="center">SKIN TEST</div></td>
                <td class="TBL_HEAD"><div align="center">INJEKSI</div></td>
				<td class="TBL_HEAD"><div align="center">NEBULIZER</div></td>
                <td class="TBL_HEAD"><div align="center">PERAWATAN LUKA</div></td>
				<td class="TBL_HEAD"><div align="center">TINDAKAN&nbsp;KECIL DG&nbsp;ANASTESI</div></td>
				<td class="TBL_HEAD"><div align="center">TINDAKAN&nbsp;KECIL TANPA&nbsp;ANASTESI</div></td>
				<td class="TBL_HEAD"><div align="center">TINDAKAN LAIN-LAIN</div></td>
                <td class="TBL_HEAD"><div align="center">JUMLAH</div></td>
                
            </tr>
	
	<?
	$sql5a = "select to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,tgl_keluar from rsv_layanan_igd2 
				where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2')
			   group by tgl_keluar order by tgl_keluar asc";
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
	
	$sql5 = "select poli,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_lunas,to_char(tgl_keluar,'dd/mm/yyyy') as tgl_kwitansi, nama,mr_no,
					karcis_umum, radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,konsul,jahit_luka,pasang_infus,pasang_cateter,
  				    ngt,skin_test,injeksi,nebulizer,perawatan_luka,anestesi,non_anestesi,smf_igd_lain,total_pemeriksaan, 
					(karcis_umum + konsul + jumlah_penunjang + total_pemeriksaan) as total
					from rsv_layanan_igd2
					where (tgl_keluar between '$ts_check_in1' and '$ts_check_in2')
					group by radiologi,lab_klinik,lab_pa,rehab,instalasi,jumlah_penunjang,poli,tgl_keluar, nama,mr_no, karcis_umum, konsul,jahit_luka,pasang_infus,pasang_cateter,
					ngt,skin_test,injeksi,nebulizer,perawatan_luka,anestesi,non_anestesi,smf_igd_lain,total_pemeriksaan
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
							 if ($row5["tgl_lunas"]==$row5a["tgl_lunas"]){
	?>
	<tr>
		<td class="TBL_BODY" align="center"><?=$no5 ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["tgl_lunas"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["tgl_kwitansi"] ?></td>
		<td class="TBL_BODY" align="left"><?=$row5["nama"] ?></td>
		<td class="TBL_BODY" align="center"><?=$row5["mr_no"] ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["karcis_umum"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["karcis_umum"],2,",",".") ?></td>

		<td class="TBL_BODY" align="right"><?=number_format($row5["konsul"],2,",",".") ?></td>

		<td class="TBL_BODY" align="right"><?=number_format($row5["radiologi"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["lab_klinik"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["lab_pa"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["rehab"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["instalasi"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["jumlah_penunjang"],2,",",".") ?></td>

		<td class="TBL_BODY" align="right"><?=number_format($row5["jahit_luka"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["pasang_infus"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["pasang_cateter"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["ngt"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["skin_test"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["injeksi"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["nebulizer"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["perawatan_luka"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["anestesi"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["non_anestesi"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["smf_igd_lain"],2,",",".") ?></td>
		<td class="TBL_BODY" align="right"><?=number_format($row5["total_pemeriksaan"],2,",",".") ?></td>
		
		<td class="TBL_BODY" align="right"><?=number_format(0,2,",",".") ?></td>
		
		<td class="TBL_BODY" align="right"><?=number_format($row5["total"],2,",",".") ?></td>
	</tr>
	<?
	$karcis_1=$karcis_1 + $row5["karcis_umum"];
	$karcis_total=$karcis_total + $row5["karcis_umum"];

	$konsul=$konsul + $row5["konsul"];
	
	$jahit_luka = $jahit_luka + $row5["jahit_luka"];
	$pasang_infus = $pasang_infus + $row5["pasang_infus"];
	$pasang_cateter = $pasang_cateter + $row5["pasang_cateter"];
	$ngt = $ngt + $row5["ngt"];
	$skin_test = $skin_test + $row5["skin_test"];
	$injeksi = $injeksi + $row5["injeksi"];
	$nebulizer = $nebulizer + $row5["nebulizer"];
	$perawatan_luka = $perawatan_luka + $row5["perawatan_luka"];
	$anestesi = $anestesi + $row5["anestesi"];
	$non_anestesi = $non_anestesi + $row5["non_anestesi"];
	$smf_igd_lain = $smf_igd_lain + $row5["smf_igd_lain"];
	$total_pemeriksaan = $total_pemeriksaan + $row5["total_pemeriksaan"];
	
	$penunjang_1=$penunjang_1 + $row5["radiologi"];
	$penunjang_2=$penunjang_2 + $row5["lab_klinik"];
	$penunjang_3=$penunjang_3 + $row5["lab_pa"];
	$penunjang_4=$penunjang_4 + $row5["rehab"];
	$penunjang_5=$penunjang_5 + $row5["instalasi"];
	$penunjang_total=$penunjang_total + $row5["jumlah_penunjang"];
	$total=$total + $row5["total"];
			
			
		;$j5++;}

    $i5++;}
	}
	
	
	$karcis_umum = getFromTable("select sum(karcis_umum) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	
	$konsul_ = getFromTable("select sum(konsul) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	
	$jahit_luka_ = getFromTable("select sum(jahit_luka) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	$pasang_infus_ = getFromTable("select sum(pasang_infus) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	$pasang_cateter_ = getFromTable("select sum(pasang_cateter) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	$ngt_ = getFromTable("select sum(ngt) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	$skin_test_ = getFromTable("select sum(skin_test) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	$injeksi_ = getFromTable("select sum(injeksi) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	$nebulizer_ = getFromTable("select sum(nebulizer) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	$perawatan_luka_ = getFromTable("select sum(perawatan_luka) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	$anestesi_ = getFromTable("select sum(anestesi) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	$non_anestesi_ = getFromTable("select sum(non_anestesi) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	$smf_igd_lain_ = getFromTable("select sum(smf_igd_lain) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	$total_pemeriksaan_ = getFromTable("select sum(total_pemeriksaan) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	
	$penunjang_1_ = getFromTable("select sum(radiologi) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	$penunjang_2_ = getFromTable("select sum(lab_klinik) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	$penunjang_3_ = getFromTable("select sum(lab_pa) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	$penunjang_4_ = getFromTable("select sum(rehab) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	$penunjang_5_ = getFromTable("select sum(instalasi) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	$penunjang_total_ = getFromTable("select sum(jumlah_penunjang) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'");
	$total_ = getFromTable("select sum(karcis_umum + konsul + jumlah_penunjang + total_pemeriksaan) from rsv_layanan_igd2 where tgl_keluar='".$row5a["tgl_keluar"]."'"); 
				  
	?>	
	<tr>
		<td colspan="5" class="TBL_FOOT" align="center">TOTAL TANGGAL <?= $row5a["tgl_keluar"] ?> </td>
		<td class="TBL_FOOT" align="right"><?=number_format($karcis_umum,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($karcis_umum,2,",",".") ?></td>

		<td class="TBL_FOOT" align="right"><?=number_format($konsul_,2,",",".") ?></td>
		
		
		<td class="TBL_FOOT" align="right"><?=number_format($penunjang_1_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($penunjang_2_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($penunjang_3_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($penunjang_4_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($penunjang_5_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($penunjang_total_,2,",",".") ?></td>
		
		<td class="TBL_FOOT" align="right"><?=number_format($jahit_luka_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($pasang_infus_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($pasang_cateter_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($ngt_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($skin_test_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($injeksi_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($nebulizer_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($perawatan_luka_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($anestesi_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($non_anestesi_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($smf_igd_lain_,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($total_pemeriksaan_,2,",",".") ?></td>
		
		
		<td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>

		<td class="TBL_FOOT" align="right"><?=number_format($total_,2,",",".") ?></td>
	</tr>	
	<?
		;$j5a++;}

	$i5a++;
	}
	?>
	
	<tr>
		<td colspan="5" class="TBL_FOOT" align="center">TOTAL</td>
		<td class="TBL_FOOT" align="right"><?=number_format($karcis_umum,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($karcis_umum,2,",",".") ?></td>

		<td class="TBL_FOOT" align="right"><?=number_format($konsul,2,",",".") ?></td>
		
		<td class="TBL_FOOT" align="right"><?=number_format($penunjang_1,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($penunjang_2,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($penunjang_3,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($penunjang_4,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($penunjang_5,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($penunjang_total_,2,",",".") ?></td>
		
		<td class="TBL_FOOT" align="right"><?=number_format($jahit_luka,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($pasang_infus,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($pasang_cateter,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($ngt,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($skin_test,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($injeksi,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($nebulizer,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($perawatan_luka,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($anestesi,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($non_anestesi,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($smf_igd_lain,2,",",".") ?></td>
		<td class="TBL_FOOT" align="right"><?=number_format($total_pemeriksaan,2,",",".") ?></td>
		
		
		<td class="TBL_FOOT" align="right"><?=number_format(0,2,",",".") ?></td>

		<td class="TBL_FOOT" align="right"><?=number_format($total,2,",",".") ?></td>
	</tr>
</TABLE>
