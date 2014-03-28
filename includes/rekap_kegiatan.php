<? // Agung Sunandar , Menampilkan lap. Buku Besar Klinik


$PID = "rekap_kegiatan";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");


    // search box
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/akuntansi-bukubesar.png' align='absmiddle' > REKAP PENERIMAAN-KELUAR PASIEN");
    } else {
    	title("<img src='icon/akuntansi-bukubesar.png' align='absmiddle' > REKAP PENERIMAAN-KELUAR PASIEN");
    }
    echo "<br>";
    //$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
	title_excel("rekap_kegiatan&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1D"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."2&tanggal2Y=".$_GET["tanggal2Y"]."&mRAWAT=".$_GET["mRAWAT"]."");
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
            $f->selectSQL("mRAWAT", "Jenis Rawat","select distinct(a.rawat_inap),
                                                        (CASE
                                                            WHEN a.rawat_inap = 'N'::bpchar THEN 'IGD'::text
                                                            WHEN a.rawat_inap = 'I'::bpchar THEN 'RAWAT INAP'::text
															else 'RAWAT JALAN'
                                                        END) AS  tdesc
                                                  from pengunjung a  group by a.rawat_inap
                                                  ", $_GET["mRAWAT"],"");

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
            $f->selectSQL("mRAWAT", "Jenis Rawat","select distinct(a.rawat_inap),
                                                        (CASE
                                                            WHEN a.rawat_inap = 'N'::bpchar THEN 'IGD'::text
                                                            WHEN a.rawat_inap = 'I'::bpchar THEN 'RAWAT INAP'::text
															else 'RAWAT JALAN'
                                                        END) AS  tdesc
                                                  from pengunjung a  group by a.rawat_inap
                                                  ", $_GET["mRAWAT"],"disabled");
    	$f->execute();
	}

    echo "<br>";

    echo "<br>";


//---------------agung 04/2011---------------
if ($_GET["mRAWAT"]=="I" or $_GET["mRAWAT"]=="N"){
 $sql = 		"select a.rawat_inap as tc, 
						(CASE
							WHEN a.rawat_inap = 'N'::bpchar THEN 'IGD'::text
							WHEN a.rawat_inap = 'I'::bpchar THEN 'RAWAT INAP'::text
						END) AS  tdesc,
				(select count(b.mr_no) from pengunjung b
                    where b.rujukan = 'N' and b.rawat_inap='".$_GET["mRAWAT"]."' and (b.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as umum,
				(select count(b1.mr_no) from pengunjung b1
                    where b1.rujukan = 'Y' and b1.rawat_inap='".$_GET["mRAWAT"]."' and b1.rujukan_rs_id='003' and (b1.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as rs,
				(select count(b2.mr_no) from pengunjung b2
                    where b2.rujukan = 'Y' and b2.rawat_inap='".$_GET["mRAWAT"]."' and b2.rujukan_rs_id='002' and (b2.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as dr,
				(select count(b3.mr_no) from pengunjung b3
				where b3.rujukan = 'Y' and b3.rawat_inap='".$_GET["mRAWAT"]."' and b3.rujukan_rs_id='001' and (b3.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as puskesmas,
				(select count(b3_.mr_no) from pengunjung b3_
				where b3_.rujukan = 'Y' and b3_.rawat_inap='".$_GET["mRAWAT"]."' and b3_.rujukan_rs_id not in ('001','002','003') and (b3_.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as lainnya,
				(select count(b4.mr_no) from pengunjung b4
				where b4.rawat_inap='".$_GET["mRAWAT"]."' and b4.status_akhir_pasien='012' and (b4.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as dirawat,
				(select count(b5.mr_no) from pengunjung b5
				where b5.rawat_inap='".$_GET["mRAWAT"]."' and b5.status_akhir_pasien='013' and (b5.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as dirujuk,	
				(select count(b6.mr_no) from pengunjung b6
				where b6.rawat_inap='".$_GET["mRAWAT"]."' and b6.status_akhir_pasien='011' and (b6.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as dirujuk_unit,	
				(select count(b7.mr_no) from pengunjung b7
				where b7.rawat_inap='".$_GET["mRAWAT"]."' and b7.status_akhir_pasien='003' and (b7.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as pulang,	
				(select count(b8.mr_no) from pengunjung b8
				where b8.rawat_inap='".$_GET["mRAWAT"]."' and b8.status_akhir_pasien='006' and (b8.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as meninggal,
				(select count(b9.mr_no) from pengunjung b9
				where b9.rawat_inap='".$_GET["mRAWAT"]."' and b9.status_akhir_pasien in ('014','015','016','017') and (b9.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as lain,
				(select count(b10.mr_no) from pengunjung b10
				where b10.rawat_inap='".$_GET["mRAWAT"]."' and b10.status_akhir_pasien not in ('006','003','011','012','013','014','015','016','017') and (b10.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as tdk_diisi
				
				from pengunjung a where a.rawat_inap = '".$_GET["mRAWAT"]."' group by tc, tdesc
        ";

}else{
    $sql = "select a.tc ,a.tdesc,
				(select count(b.mr_no) from pengunjung b
                    where b.rujukan = 'N' and b.rawat_inap='".$_GET["mRAWAT"]."' and poli::text=a.tc and (b.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as umum,
				(select count(b1.mr_no) from pengunjung b1
                    where b1.rujukan = 'Y' and b1.rawat_inap='".$_GET["mRAWAT"]."' and poli::text=a.tc and b1.rujukan_rs_id='003' and (b1.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as rs,
				(select count(b2.mr_no) from pengunjung b2
                    where b2.rujukan = 'Y' and b2.rawat_inap='".$_GET["mRAWAT"]."' and poli::text=a.tc and b2.rujukan_rs_id='002' and (b2.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as dr,
				(select count(b3.mr_no) from pengunjung b3
				where b3.rujukan = 'Y' and b3.rawat_inap='".$_GET["mRAWAT"]."' and poli::text=a.tc and b3.rujukan_rs_id='001' and (b3.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as puskesmas,
				(select count(b3_.mr_no) from pengunjung b3_
				where b3_.rujukan = 'Y' and b3_.rawat_inap='".$_GET["mRAWAT"]."' and poli::text=a.tc and b3_.rujukan_rs_id not in ('001','002','003') and (b3_.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as lainnya,
				(select count(b4.mr_no) from pengunjung b4
				where b4.rawat_inap='".$_GET["mRAWAT"]."' and poli::text=a.tc and b4.status_akhir_pasien='012' and (b4.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as dirawat,
				(select count(b5.mr_no) from pengunjung b5
				where b5.rawat_inap='".$_GET["mRAWAT"]."' and poli::text=a.tc and b5.status_akhir_pasien='013' and (b5.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as dirujuk,	
				(select count(b6.mr_no) from pengunjung b6
				where b6.rawat_inap='".$_GET["mRAWAT"]."' and poli::text=a.tc and b6.status_akhir_pasien='011' and (b6.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as dirujuk_unit,	
				(select count(b7.mr_no) from pengunjung b7
				where b7.rawat_inap='".$_GET["mRAWAT"]."' and poli::text=a.tc and b7.status_akhir_pasien='003' and (b7.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as pulang,	
				(select count(b8.mr_no) from pengunjung b8
				where b8.rawat_inap='".$_GET["mRAWAT"]."' and poli::text=a.tc and b8.status_akhir_pasien='006' and (b8.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as meninggal,
				(select count(b9.mr_no) from pengunjung b9
				where b9.rawat_inap='".$_GET["mRAWAT"]."' and poli::text=a.tc and b9.status_akhir_pasien in ('014','015','016','017') and (b9.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as lain,
				(select count(b10.mr_no) from pengunjung b10
				where b10.rawat_inap='".$_GET["mRAWAT"]."' and poli::text=a.tc and b10.status_akhir_pasien not in ('006','003','011','012','013','014','015','016','017') and (b10.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as tdk_diisi
				
				from rs00001 a where a.tc not in ('000','201','202','206','207','208') and a.tt='LYN' group by tc, tdesc
        ";
}
//echo $sql;
if ($_GET["mRAWAT"]=='-'){
 $rawat='SEMUA POLI';
 }elseif ($_GET["mRAWAT"]=='N'){
 $rawat='IGD';
 }elseif ($_GET["mRAWAT"]=='I'){
 $rawat='RAWAT INAP';
 }else{
 $rawat='RAWAT JALAN';
 //$poli=getFromTable("select tdesc from rs00001 where tt='LYN' and tc='".$_GET["mRAWAT"]."' ");

 //$ket = 'PADA ';
 }
        @$r1 = pg_query($con,$sql);
        @$n1 = pg_num_rows($r1);

	$max_row= 9999999999 ;
	$mulai = $HTTP_GET_VARS["rec"] ;
	if (!$mulai){$mulai=1;}

?>
<table width="100%" align="center">
	<tr>
		<td align="center" class="TBL_JUDUL">REKAP PENERIMAAN-KELUAR PASIEN <?= $rawat?></td>
	</tr>
	<tr>
		<td align="center" class="TBL_JUDUL"><?= $ket ?><?= $poli?></td>
	</tr>
       
</table>
<br>
<br>
<table width="100%" BORDER="0" CLASS="TBL_BORDER">
  <tr>
    <td class="TBL_HEAD" rowspan="2"><div align="center" >NO. </div></td>
    <td class="TBL_HEAD" rowspan="2"><div align="center" >POLI TUJUAN</div></td>
    <td class="TBL_HEAD" rowspan="2"><div align="center" >NON RUJUKAN</div></td>
	<td class="TBL_HEAD" colspan="3"><div align="center" >RUJUKAN</div></td>
	<td class="TBL_HEAD" rowspan="2"><div align="center" >TIDAK DIISI</div></td>
	<td class="TBL_HEAD" colspan="7"><div align="center" >STATUS AKHIR</div></td>
</tr>
<tr>
    <td class="TBL_HEAD" ><div align="center" >RS</div></td>
    <td class="TBL_HEAD" ><div align="center" >DOKTER</div></td>
    <td class="TBL_HEAD" ><div align="center" >INSTANSI&nbsp;LAIN</div></td>
	<td class="TBL_HEAD" ><div align="center" >DIRAWAT</div></td>
	<td class="TBL_HEAD" ><div align="center" >DIRUJUK RS&nbsp;LAIN</div></td>
	<td class="TBL_HEAD" ><div align="center" >DIRUJUK UNIT&nbsp;LAIN</div></td>
	<td class="TBL_HEAD" ><div align="center" >PULANG/SEMBUH</div></td>
	<td class="TBL_HEAD" ><div align="center" >MENINGGAL</div></td>
	<td class="TBL_HEAD" ><div align="center" >LAIN-LAIN</div></td>
	<td class="TBL_HEAD" ><div align="center" >TIDAK DIISI</div></td>
  </tr>

  <?
    $row1=0;
	$jumlah=0;
	$jumlah1=0;
	$jumlah2=0;
	$jumlah3=0;
	$jumlah4=0;
	$jumlah5=0;
	$jumlah6=0;
	$jumlah7=0;
	$jumlah8=0;
	$jumlah9=0;
	$jumlah10=0;
	$jumlah11=0;
    $i= 1 ;
    $j= 1 ;
    $last_id=1;
    while (@$row1 = pg_fetch_array($r1)){
        if (($j<=$max_row) AND ($i >= $mulai)){
                $no=$i
                ?>
                <tr valign="top" class="<? ?>" >
                        <td class="TBL_BODY" align="center"><?=$no ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["tdesc"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["umum"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["rs"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row1["dr"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row1["puskesmas"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row1["lainnya"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row1["dirawat"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["dirujuk"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row1["dirujuk_unit"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row1["pulang"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row1["meninggal"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row1["lain"] ?> </td>
						<td class="TBL_BODY" align="center"><?=$row1["tdk_diisi"] ?> </td>
                </tr>
				<?
                $jumlah=$jumlah+$row1["umum"] ;
				$jumlah1=$jumlah1+$row1["rs"] ;
				$jumlah2=$jumlah2+$row1["dr"];
				$jumlah3=$jumlah3+$row1["puskesmas"];
				$jumlah4=$jumlah4+$row1["dirawat"];
				$jumlah5=$jumlah5+$row1["dirujuk"];
				$jumlah6=$jumlah6+$row1["dirujuk_unit"];
				$jumlah7=$jumlah7+$row1["pulang"];
				$jumlah8=$jumlah8+$row1["meninggal"];
				$jumlah9=$jumlah9+$row1["lain"];
				$jumlah10=$jumlah10+$row1["tdk_diisi"];
				$jumlah11=$jumlah11+$row1["lainnya"];
                ?>
                <?;$j++;
        }
        $i++;
}
?>
				<tr valign="top" class="TBL_HEAD">
                <td class="TBL_HEAD" align="center" colspan="2" height="25" valign="middle"><b>SUB TOTAL </b></td>
                <td class="TBL_HEAD" align="center" valign="middle"><b><?= $jumlah ?></b></td>
				<td class="TBL_HEAD" align="center" valign="middle"><b><?= $jumlah1 ?></b></td>
				<td class="TBL_HEAD" align="center" valign="middle"><b><?= $jumlah2 ?></b></td>
				<td class="TBL_HEAD" align="center" valign="middle"><b><?= $jumlah3 ?></b></td>
				<td class="TBL_HEAD" align="center" valign="middle"><b><?= $jumlah11 ?></b></td>
				<td class="TBL_HEAD" align="center" valign="middle"><b><?= $jumlah4 ?></b></td>
				<td class="TBL_HEAD" align="center" valign="middle"><b><?= $jumlah5 ?></b></td>
				<td class="TBL_HEAD" align="center" valign="middle"><b><?= $jumlah6 ?></b></td>
				<td class="TBL_HEAD" align="center" valign="middle"><b><?= $jumlah7 ?></b></td>
				<td class="TBL_HEAD" align="center" valign="middle"><b><?= $jumlah8 ?></b></td>
				<td class="TBL_HEAD" align="center" valign="middle"><b><?= $jumlah9 ?></b></td>
				<td class="TBL_HEAD" align="center" valign="middle"><b><?= $jumlah10 ?></b></td>
                </tr>
				<?$tot1= $jumlah + $jumlah1 + $jumlah2 + $jumlah3 + $jumlah11; $tot2= $jumlah4 + $jumlah5 + $jumlah6 + $jumlah7 + $jumlah8 + $jumlah9 + $jumlah10;?>
				<tr valign="top" class="TBL_HEAD">
                <td class="TBL_HEAD" align="center" colspan="2" height="25" valign="middle"><b>TOTAL </b></td>
                <td class="TBL_HEAD" align="center" colspan="5" valign="middle"><b><?= $tot1 ?></b></td>
				<td class="TBL_HEAD" align="center" colspan="7" valign="middle"><b><?= $tot2 ?></b></td>
                </tr>
</table>
