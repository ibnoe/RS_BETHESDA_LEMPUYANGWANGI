<? // Agung Sunandar , Menampilkan lap. Buku Besar Klinik


$PID = "rekap_kunjungan";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");


    // search box
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/akuntansi-bukubesar.png' align='absmiddle' > REKAP PENERIMAAN PENGUNJUNG");
    } else {
    	title("<img src='icon/akuntansi-bukubesar.png' align='absmiddle' > REKAP PENERIMAAN PENGUNJUNG");
    }
    echo "<br>";
    //$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
	title_excel("rekap_kunjungan&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."&mRAWAT=".$_GET["mRAWAT"]."&mPASIEN=".$_GET["mPASIEN"]."");
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
                                                        END) AS  tdesc
                                                  from rs00006 a  where a.rawat_inap != 'Y'
                                                  union
                                                  select distinct(a.poli::text) as tc, b.tdesc AS tdesc
                                                  from rs00006 a, rs00001 b
                                                  where b.tt='LYN' and a.poli::text=b.tc and a.rawat_inap='Y' order by tdesc
                                                  ", $_GET["mRAWAT"],"");
				$f->selectSQL("mPASIEN","Tipe Pasien","select '' as tc, '' as tdesc union select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000'  order by tdesc ",$_GET[mPASIEN],"");
    								  

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
                                                        END) AS  tdesc
                                                  from pengunjung a  where a.rawat_inap != 'Y'
                                                  union
                                                  select distinct(a.poli::text) as tc, b.tdesc AS tdesc
                                                  from pengunjung a, rs00001 b
                                                  where b.tt='LYN' and a.poli::text=b.tc and a.rawat_inap='Y' order by tdesc
                                                  ", $_GET["mRAWAT"],"disabled");
		$f->selectSQL("mPASIEN","Tipe Pasien","select '' as tc, '' as tdesc union select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000'  order by tdesc ",$_GET[mPASIEN],"");
    
    	$f->execute();
	}

    echo "<br>";

    echo "<br>";


//---------------agung 04/2011---------------
if ($_GET["mRAWAT"]=="I" or $_GET["mRAWAT"]=="N"){
 $sql = "select     (select count(mr_no) from pengunjung 
                    where jenis_kelamin = 'L' and is_baru='Y' and  tipe='".$_GET["mPASIEN"]."' and  rawat_inap='".$_GET["mRAWAT"]."' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as pria_b,
                    (select count(mr_no) from pengunjung 
                    where jenis_kelamin = 'P' and is_baru='Y' and  tipe='".$_GET["mPASIEN"]."' and  rawat_inap='".$_GET["mRAWAT"]."' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as wanita_b,
                    (select count(mr_no) from pengunjung 
                    where jenis_kelamin = 'L' and is_baru='Y' and  tipe='".$_GET["mPASIEN"]."' and  rawat_inap='".$_GET["mRAWAT"]."' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) +
                    (select count(mr_no) from pengunjung 
                    where jenis_kelamin = 'P' and is_baru='Y' and  tipe='".$_GET["mPASIEN"]."' and  rawat_inap='".$_GET["mRAWAT"]."' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as jumlah_b,
                    (select count(mr_no) from pengunjung 
                    where jenis_kelamin = 'L' and is_baru='N' and  tipe='".$_GET["mPASIEN"]."' and  rawat_inap='".$_GET["mRAWAT"]."' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as pria_l,
                    (select count(mr_no) from pengunjung 
                    where jenis_kelamin = 'P' and is_baru='N' and  tipe='".$_GET["mPASIEN"]."' and  rawat_inap='".$_GET["mRAWAT"]."' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as wanita_l,
                    (select count(mr_no) from pengunjung 
                    where jenis_kelamin = 'L' and is_baru='N' and  tipe='".$_GET["mPASIEN"]."' and  rawat_inap='".$_GET["mRAWAT"]."' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) +
                    (select count(mr_no) from pengunjung 
                    where jenis_kelamin = 'P' and is_baru='N' and  tipe='".$_GET["mPASIEN"]."' and  rawat_inap='".$_GET["mRAWAT"]."' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as jumlah_l
        
        ";
}else{
    $sql = "select     (select count(mr_no) from pengunjung 
                    where jenis_kelamin = 'L' and rawat_inap='Y' and is_baru='Y' and  tipe='".$_GET["mPASIEN"]."' and  poli='".$_GET["mRAWAT"]."' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as pria_b,
                    (select count(mr_no) from pengunjung 
                    where jenis_kelamin = 'P' and rawat_inap='Y' and is_baru='Y' and  tipe='".$_GET["mPASIEN"]."' and  poli='".$_GET["mRAWAT"]."' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as wanita_b,
                    (select count(mr_no) from pengunjung 
                    where jenis_kelamin = 'L' and rawat_inap='Y' and is_baru='Y' and  tipe='".$_GET["mPASIEN"]."' and  poli='".$_GET["mRAWAT"]."' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) +
                    (select count(mr_no) from pengunjung 
                    where jenis_kelamin = 'P' and rawat_inap='Y' and is_baru='Y' and  tipe='".$_GET["mPASIEN"]."' and  poli='".$_GET["mRAWAT"]."' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as jumlah_b,
                    (select count(mr_no) from pengunjung 
                    where jenis_kelamin = 'L' and rawat_inap='Y' and is_baru='N' and  tipe='".$_GET["mPASIEN"]."' and  poli='".$_GET["mRAWAT"]."' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as pria_l,
                    (select count(mr_no) from pengunjung 
                    where jenis_kelamin = 'P' and rawat_inap='Y' and is_baru='N' and  tipe='".$_GET["mPASIEN"]."' and  poli='".$_GET["mRAWAT"]."' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as wanita_l,
                    (select count(mr_no) from pengunjung 
                    where jenis_kelamin = 'L' and rawat_inap='Y' and is_baru='N' and  tipe='".$_GET["mPASIEN"]."' and  poli='".$_GET["mRAWAT"]."' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) +
                    (select count(mr_no) from pengunjung 
                    where jenis_kelamin = 'P' and rawat_inap='Y' and is_baru='N' and  tipe='".$_GET["mPASIEN"]."' and  poli='".$_GET["mRAWAT"]."' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as jumlah_l
        ";
}
if ($_GET["mRAWAT"]=='-'){
 $rawat='SEMUA POLI';
 }elseif ($_GET["mRAWAT"]=='N'){
 $rawat='IGD';
 }elseif ($_GET["mRAWAT"]=='I'){
 $rawat='RAWAT INAP';
 }else{
 $rawat='RAWAT JALAN';
 $poli=getFromTable("select tdesc from rs00001 where tt='LYN' and tc='".$_GET["mRAWAT"]."' ");

 $ket = 'PADA ';
 }
        @$r1 = pg_query($con,$sql);
        @$n1 = pg_num_rows($r1);

	$max_row= 9999999999 ;
	$mulai = $HTTP_GET_VARS["rec"] ;
	if (!$mulai){$mulai=1;}

?>
<table width="100%" align="center">
	<tr>
		<td align="center" class="TBL_JUDUL">REKAP PENERIMAAN PASIEN BARU <?= $rawat?></td>
	</tr>
	<tr>
		<td align="center" class="TBL_JUDUL"><?= $ket ?><?= $poli?></td>
	</tr>
       
</table>
<br>
<br>
<table width="100%" BORDER="0" CLASS="TBL_BORDER">
  <tr>
    <td class="TBL_HEAD" colspan="3"><div align="center">PASIEN - BARU </div></td>
    <td class="TBL_HEAD" colspan="3"><div align="center">PASIEN - LAMA </div></td>
    <td class="TBL_HEAD" colspan="3"><div align="center">KUNJUNGAN</div></td>
  </tr>
  <tr>
    <td class="TBL_HEAD" ><div align="center">Pria</div></td>
    <td class="TBL_HEAD" > <div align="center">Wanita</div></td>
    <td class="TBL_HEAD" ><div align="center">Jumlah</div></td>
    <td class="TBL_HEAD" ><div align="center">Pria</div></td>
    <td class="TBL_HEAD" ><div align="center">Wanita</div></td>
    <td class="TBL_HEAD" ><div align="center">Jumlah</div></td>
    <td class="TBL_HEAD" ><div align="center">Pria</div></td>
    <td class="TBL_HEAD" ><div align="center">Wanita</div></td>
    <td class="TBL_HEAD" ><div align="center">Jumlah</div></td>
  </tr>
  <?
    $row1=0;
    $i= 1 ;
    $j= 1 ;
    $last_id=1;
    while (@$row1 = pg_fetch_array($r1)){
        if (($j<=$max_row) AND ($i >= $mulai)){
                $no=$i
                ?>
                <tr valign="top" class="<? ?>" >
                        <td class="TBL_BODY" align="center"><?=$row1["pria_b"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["wanita_b"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["jumlah_b"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["pria_l"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["wanita_l"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["jumlah_l"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["pria_b"] + $row1["pria_l"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["wanita_b"] + $row1["wanita_l"]?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["jumlah_b"] + $row1["jumlah_l"]?> </td>
                </tr>

                <?;$j++;
        }
        $i++;
}
?>
</table>
