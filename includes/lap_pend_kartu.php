<? // Agung S. 31-10-2011


$PID = "lap_pend_kartu";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");


    // search box
    if (!$GLOBALS['print']){
    	title("<img src='icon/medical-record-2.gif' align='absmiddle' > Laporan Pendapatan Kartu Pasien");
		title_excel("lap_pend_kartu&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."&mRAWAT=".$_GET["mRAWAT"]."");
    } else {
    	title("<img src='icon/medical-record.gif' align='absmiddle' > Laporan Pendapatan Kartu Pasien");
    }
    //title("LAPORAN PENDAPATAN JASA MEDIS");
    //$ext = "OnChange = 'Form1.submit();'";
    echo "<br>";
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
	    $f->selectSQL("mRAWAT", "Jenis Rawat","select '-' as tc, '' as tdesc
                                                  union
                                                  select distinct(a.rawat_inap),
                                                        (CASE
                                                            WHEN a.rawat_inap = 'N'::bpchar THEN 'IGD'::text
                                                            WHEN a.rawat_inap = 'I'::bpchar THEN 'RAWAT INAP'::text
                                                        END) AS  tdesc
                                                  from rs00006 a  where a.rawat_inap != 'Y'
                                                  union
                                                  select distinct(a.poli::text) as tc, b.tdesc AS tdesc
                                                  from rs00006 a, rs00001 b
                                                  where b.tt='LYN' and a.poli::text=b.tc and a.rawat_inap='Y'
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
	  $f->selectSQL("mRAWAT", "Jenis Rawat","select '-' as tc, '' as tdesc union ".
//    			  "select distinct(a.rawat_inap) as tc,(CASE
//                                WHEN a.rawat_inap = 'N'::bpchar THEN 'IGD'::text
//                                WHEN a.rawat_inap = 'I'::bpchar THEN 'RAWAT INAP'::text
//                                END) AS  tdesc ".
//    			  "from rs00006 a where a.rawat_inap != 'Y' union ".
    			  "select distinct(a.poli::text) as tc, b.tdesc AS tdesc
                           from rs00006 a, rs00001 b
                           where b.tt='LYN' and a.poli::text=b.tc and a.rawat_inap='Y' ", $_GET["mRAWAT"],"disabled");
      
    	$f->execute();
	}

    echo "<br>";
 if ($_GET["mRAWAT"]=='N'){
 $sql=" select tanggal(tanggal_reg,3) as tanggal_reg,mr_no,nama,alamat, jenis_kelamin, nm_poli, harga
        from rsv_kartu
        where is_baru='Y' and rawat_inap='".$_GET["mRAWAT"]."' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')
		GROUP BY tanggal_reg,mr_no,nama,alamat, jenis_kelamin, nm_poli, harga
        order by tanggal_reg, mr_no ";
 }elseif ($_GET["mRAWAT"]=='-'){
 $sql=" select tanggal(tanggal_reg,3) as tanggal_reg,mr_no,nama,alamat, jenis_kelamin, nm_poli, harga
        from rsv_kartu
        where is_baru='Y' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')
		GROUP BY tanggal_reg,mr_no,nama,alamat, jenis_kelamin, nm_poli, harga
        order by tanggal_reg, mr_no ";
 }else{
     $sql=" select tanggal(tanggal_reg,3) as tanggal_reg,mr_no,nama,alamat, jenis_kelamin, nm_poli, harga
        from rsv_kartu
        where is_baru='Y' and poli='".$_GET["mRAWAT"]."' and (tanggal_reg between '$ts_check_in1' and '$ts_check_in2')
		GROUP BY tanggal_reg,mr_no,nama,alamat, jenis_kelamin, nm_poli, harga
        order by tanggal_reg, mr_no  ";
 }

 if ($_GET["mRAWAT"]=='-'){
 $rawat='SEMUA POLI';
 }elseif ($_GET["mRAWAT"]=='N'){
 $rawat='IGD';
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
title_print("");
//$tgl_awal=t($ts_check_in1,'dd Mon yyyy');
//echo $ts_check_in1;
?>
<table width="100%" align="center">
	<tr>
		<td align="center" class="TBL_JUDUL">PENDAPATAN KARTU PASIEN BARU <?= $rawat?></td>
	</tr>
	<tr>
		<td align="center" class="TBL_JUDUL"><?= $ket ?><?= $poli?></td>
	</tr>
       
</table>
<br>
  <table CLASS=TBL_BORDER width="100%" border="0">

    <tr>
      <td class="TBL_HEAD" width="4%"><div align="center">No. </div></td>
      <td class="TBL_HEAD" width="10%"><div align="center">Tanggal Reg. </div></td>
      <td class="TBL_HEAD" width="6%"><div align="center">No. MR</div></td>
      <td class="TBL_HEAD" ><div align="center">Nama Pasien</div></td>
      <td class="TBL_HEAD" width="8%"><div align="center">Jenis Kelamin</div></td>
      <td class="TBL_HEAD" ><div align="center">Alamat</div></td>
      <td class="TBL_HEAD" ><div align="center">Poli</div></td>
      <td class="TBL_HEAD" ><div align="center">Kartu (Rp)</div></td>
    </tr>
    <?
    $jumlah= 0;
    $row1=0;
    $i= 1 ;
    $j= 1 ;
    $last_id=1;
    while (@$row1 = pg_fetch_array($r1)){
        if (($j<=$max_row) AND ($i >= $mulai)){
                $no=$i
                ?>
                <tr valign="top" class="<? ?>" >
                        <td class="TBL_BODY" align="center"><?=$no ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["tanggal_reg"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["mr_no"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["nama"] ?> </td>
                        <td class="TBL_BODY" align="center"><?=$row1["jenis_kelamin"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["alamat"] ?> </td>
                        <td class="TBL_BODY" align="left"><?=$row1["nm_poli"] ?> </td>
                        <td class="TBL_BODY" align="right"><?=number_format($row1["harga"] ,2,",",".")?> </td>
                </tr>

                <?
                $jumlah=$jumlah+$row1["harga"] ;
                ?>
                <?;$j++;
        }
        $i++;
}
?>

                <tr valign="top" class="TBL_HEAD">
                <td class="TBL_HEAD" align="center" colspan="7" height="25" valign="middle"><b> TOTAL </b></td>
                <td class="TBL_HEAD" align="right" valign="middle"><b><?=number_format($jumlah,2,",",".") ?></b></td>
                </tr>


  </table>