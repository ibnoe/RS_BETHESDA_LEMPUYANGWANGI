<? // Agung Sunandar , Menampilkan lap. Buku Besar Klinik


$PID = "rekap_rujukan";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");


    // search box
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/akuntansi-bukubesar.png' align='absmiddle' > REKAP PENERIMAAN PASIEN RUJUKAN");
    } else {
    	title("<img src='icon/akuntansi-bukubesar.png' align='absmiddle' > REKAP PENERIMAAN PASIEN RUJUKAN");
    }
    echo "<br>";
    //$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
	title_excel("rekap_rujukan&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."&rawat_inap=".$_GET["rawat_inap"]."&mRAWAT=".$_GET["mRAWAT"]."");
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

	    }/* 
            $f->selectSQL("mRAWAT", "Jenis Rawat","select distinct(a.rawat_inap),
                                                        (CASE
                                                            WHEN a.rawat_inap = 'N'::bpchar THEN 'IGD'::text
                                                            WHEN a.rawat_inap = 'I'::bpchar THEN 'RAWAT INAP'::text
                                                        END) AS  tdesc
                                                  from rs00006 a  where a.rawat_inap != 'Y'
                                                  union
                                                  select distinct(a.poli::text) as tc, b.tdesc AS tdesc
                                                  from rs00006 a, rs00001 b
                                                  where b.tt='LYN' and a.poli::text=b.tc and a.rawat_inap='Y'
                                                  ", $_GET["mRAWAT"],""); */
		   $f->selectArray("rawat_inap", "U n i t",Array(""=>"", "N" => "IGD", "Y" => "Rawat Jalan",  "I" => "Rawat Inap"),
                     $_GET[rawat_inap], "onChange='document.Form1.submit();'; ");
	$_GET["rawat_inap"] = strlen($_GET["rawat_inap"]) == "" ? "" : $_GET["rawat_inap"];
	if ($_GET["rawat_inap"]=="Y"){
	$f->selectSQL("mRAWAT", "Poli","select '' as tc, '' as tdesc union 
                                             SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','100','201','202','206','207','208')
                                             order by tdesc ",$_GET["mRAWAT"], "");
	}elseif ($_GET["rawat_inap"]=="I"){
	$f->selectSQL("mINAP", "Bangsal ","select d.bangsal, d.bangsal as bangsal
                       from rs00010 as a 
                           join rs00012 as b on a.bangsal_id = b.id 
                           join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' 
                           join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' 
                           join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR'
group by d.bangsal
order by d.bangsal " ,$_GET["mINAP"], "");
}else{}

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
            /* $f->selectSQL("mRAWAT", "Jenis Rawat","select distinct(a.rawat_inap),
                                                        (CASE
                                                            WHEN a.rawat_inap = 'N'::bpchar THEN 'IGD'::text
                                                            WHEN a.rawat_inap = 'I'::bpchar THEN 'RAWAT INAP'::text
                                                        END) AS  tdesc
                                                  from pengunjung a  where a.rawat_inap != 'Y'
                                                  union
                                                  select distinct(a.poli::text) as tc, b.tdesc AS tdesc
                                                  from pengunjung a, rs00001 b
                                                  where b.tt='LYN' and a.poli::text=b.tc and a.rawat_inap='Y'
                                                  ", $_GET["mRAWAT"],"disabled"); */
	   $f->selectArray("rawat_inap", "U n i t",Array(""=>"", "N" => "IGD", "Y" => "Rawat Jalan",  "I" => "Rawat Inap"),
                     $_GET[rawat_inap], "onChange='document.Form1.submit();'; ");
	$_GET["rawat_inap"] = strlen($_GET["rawat_inap"]) == "" ? "" : $_GET["rawat_inap"];
	if ($_GET["rawat_inap"]=="Y"){
	$f->selectSQL("mRAWAT", "Poli","select '' as tc, '' as tdesc union 
                                             SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','100','201','202','206','207','208')
                                             order by tdesc ",$_GET["mRAWAT"], "");
	}elseif ($_GET["rawat_inap"]=="I"){
	$f->selectSQL("mINAP", "Bangsal ","select d.bangsal, d.bangsal as bangsal
                       from rs00010 as a 
                           join rs00012 as b on a.bangsal_id = b.id 
                           join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' 
                           join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' 
                           join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR'
group by d.bangsal
order by d.bangsal " ,$_GET["mINAP"], "");
}else{}										  
    	$f->execute();
	}

    echo "<br>";

    echo "<br>";


//---------------agung 04/2011---------------
if ($_GET["mRAWAT"]=="I" or $_GET["mRAWAT"]=="N"){
 $sql = "select a.tc, a.tdesc,
				(select count(b.mr_no) from pengunjung b
                    where b.rujukan = 'Y' and b.tipe=a.tc and  b.rawat_inap='".$_GET["mRAWAT"]."' and (b.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as rujukan,
				(select count(b1.mr_no) from pengunjung b1
                    where b1.rujukan = 'N' and b1.tipe=a.tc and  b1.rawat_inap='".$_GET["mRAWAT"]."' and (b1.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as non_rujukan
				from rs00001 a where a.tt='JEP' and tc !='000' group by a.tc, a.tdesc
        ";
}else{
    $sql = "select a.tc, a.tdesc,
				(select count(b.mr_no) from pengunjung b
                    where b.rujukan = 'Y' and b.tipe=a.tc and  b.poli='".$_GET["mRAWAT"]."' and (b.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as rujukan,
				(select count(b1.mr_no) from pengunjung b1
                    where b1.rujukan = 'N' and b1.tipe=a.tc and  b1.poli='".$_GET["mRAWAT"]."' and (b1.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')) as non_rujukan
				from rs00001 a where a.tt='JEP' and tc !='000' group by a.tc, a.tdesc
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
		<td align="center" class="TBL_JUDUL">REKAP PENERIMAAN PASIEN RUJUKAN <?= $rawat?></td>
	</tr>
	<tr>
		<td align="center" class="TBL_JUDUL"><?= $ket ?><?= $poli?></td>
	</tr>
       
</table>
<br>
<br>
<table width="100%" BORDER="0" CLASS="TBL_BORDER">
  <tr>
    <td class="TBL_HEAD" ><div align="center">NO. </div></td>
    <td class="TBL_HEAD" ><div align="center">TIPE PASIEN</div></td>
    <td class="TBL_HEAD" ><div align="center">RUJUKAN</div></td>
	<td class="TBL_HEAD" ><div align="center">NON RUJUKAN</div></td>
  </tr>

  <?
    $row1=0;
	$jumlah=0;
	$jumlah1=0;
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
                        <td class="TBL_BODY" align="center"><?=$row1["rujukan"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["non_rujukan"] ?> </td>
                </tr>
				<?
                $jumlah=$jumlah+$row1["rujukan"] ;
				$jumlah1=$jumlah1+$row1["non_rujukan"] ;
                ?>
                <?;$j++;
        }
        $i++;
}
?>
				<tr valign="top" class="TBL_HEAD">
                <td class="TBL_HEAD" align="center" colspan="2" height="25" valign="middle"><b> TOTAL PENUNJUNG</b></td>
                <td class="TBL_HEAD" align="center" valign="middle"><b><?= $jumlah ?></b></td>
				<td class="TBL_HEAD" align="center" valign="middle"><b><?= $jumlah1 ?></b></td>
                </tr>
</table>
