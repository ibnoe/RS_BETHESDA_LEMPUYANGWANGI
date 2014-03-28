<? 
$PID = "pembagian_jm";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
  
if($_GET["tc"] == "view") {

    title_print("Rincian Pembagian Pendapatan Jasa Medis");
	title_excel("pembagian_jm&p=".$_GET["p"]."&tc=view&f=".$_GET["f"]."&t1=".$_GET["t1"]."&t2=".$_GET["t2"]."&c=".$_GET["c"]."&z=".$_GET["z"]."");
    $tp = getFromTable(
               "select a.jabatan_medis_fungsional from rs00018 a, rs00017 b ".
             //  "where  b.jabatan_medis_fungsional_id=a.id and b.nama = '".$_GET["f"]."' ");
               "where  b.jabatan_medis_fungsional_id=a.id and b.nama like % dr %"); // '".$_GET["f"]."' ");
    $pasien = getFromTable(
               "select tdesc from rs00001 ".
               "where tdesc like '%".$_GET["c"]."%' and tt='JEP'");
	$poli = getFromTable(
               "select tdesc from rs00001 ".
               "where tdesc like '%".$_GET["z"]."%' and tt='LYN'");

    $r = pg_query($con,
        "select to_char(to_date('".$_GET["t1"]."','YYYY-MM-DD'),'DD-MON-YYYY') as tgl");
    $d = pg_fetch_object($r);
    pg_free_result($r);
    $bulan = $d->tgl;

    $r1 = pg_query($con,
        "select to_char(to_date('".$_GET["t2"]."','YYYY-MM-DD'),'DD-MON-YYYY') as tgl1");
    $d1 = pg_fetch_object($r1);
    pg_free_result($r1);
    $bulan1 = $d1->tgl1;

    $f = new Form("");
	echo "<br>";
echo "<table>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> PERIODE </td>";
		echo "<td bgcolor='B0C4DE'><b>: $bulan s/d $bulan1 </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> NAMA DOKTER</td>";
		echo "<td bgcolor='B0C4DE'><b>: ".$_GET["f"]." </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> JABATAN MEDIS FUNGSIONAL </td>";
		echo "<td bgcolor='B0C4DE'><b>: $tp </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> TIPE PASIEN</td>";
		echo "<td bgcolor='B0C4DE'><b>: $pasien </td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td bgcolor='B0C4DE'><b> POLI </td>";
		echo "<td bgcolor='B0C4DE'><b>: $poli </td>";
	echo "</tr>";
echo "</table>";

    $f->execute();
    
    if (!$GLOBALS['print']){
    	echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    } else {
    	"";
    }

    echo "<br>";
    $t = new PgTable($con, "100%");    
	$r3 = pg_query($con,"select sum(jum_tagihan) as jum from rsv_pembagian_jm
	where (tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') and tipe_pasien like '%".$_GET["c"]."%'	and nama = '".$_GET["f"]."' and nm_poli like '%".$_GET["z"]."%'");

    $d3 = pg_fetch_object($r3);
    pg_free_result($r3);
	
	$r4 = pg_query($con,"select sum(jm_dokter) as jum from rsv_pembagian_jm
	where (tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') and tipe_pasien like '%".$_GET["c"]."%'	and nama = '".$_GET["f"]."' and nm_poli like '%".$_GET["z"]."%'");

    $d4 = pg_fetch_object($r4);
    pg_free_result($r4);
	
	$r5 = pg_query($con,"select sum(jum_tagihan-jm_dokter) as jum from rsv_pembagian_jm
	where (tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') and tipe_pasien like '%".$_GET["c"]."%'	and nama = '".$_GET["f"]."' and nm_poli like '%".$_GET["z"]."%'");

    $d5 = pg_fetch_object($r5);
    pg_free_result($r5);
  
   	$SQL = "select to_char(tanggal_trans,'dd Mon yyyy') as tanggal, layanan,  jum_tagihan as jml_tagihan, prosen, jm_dokter as jml_dokter, jum_tagihan::int-jm_dokter  as jml_rs
	from rsv_pembagian_jm
	where (tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') and tipe_pasien like '%".$_GET["c"]."%'	and nama = '".$_GET["f"]."' and nm_poli like '%".$_GET["z"]."%'
	group by tanggal_trans, layanan, jum_tagihan,prosen,jm_dokter";

	//echo $SQL;
			@$r1 = pg_query($con,$SQL);
			@$n1 = pg_num_rows($r1);
			
   			$max_row= 99999 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;} 
?>
<TABLE ALIGN="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
			<tr class="TBL_HEAD">     	
				<td class="TBL_HEAD" width="4%" align="center">NO</td>
				<td class="TBL_HEAD"align="center">TANGGAL</td>
				<td class="TBL_HEAD"align="center">LAYANAN/JENIS TINDAKAN</td>
				<td width="10%" align="center" class="TBL_HEAD"align="center">JUMLAH HARGA LAYANAN</td>
				<td width="10%" align="center" class="TBL_HEAD"align="center">%</td>
				<td width="10%" align="center" class="TBL_HEAD">JM DOKTER</td>
				<td width="10%" align="center" class="TBL_HEAD">JM RS</td>
			</tr>
			
	
		<?	
			$jml_tagihan= 0;
			$jml_dokter= 0;
			$jml_rs= 0;
			$row1=0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while (@$row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
					$no=$i 	
					?>		
				 	<tr valign="top" class="<?=$class_nya?>" >  
			        	<td class="TBL_BODY" align="center"><?=$no ?> </td>
			        	<td class="TBL_BODY" align="center"><?=$row1["tanggal"] ?> </td>
						<td align="left" class="TBL_BODY"><?=$row1["layanan"] ?></td>
						<td align="right" class="TBL_BODY" valign="middle"><?=number_format($row1["jml_tagihan"],2,",",".") ?></td>
						<td align="center" class="TBL_BODY"><?=$row1["prosen"] ?></td>
						<td align="right" class="TBL_BODY" valign="middle"><?=number_format($row1["jml_dokter"],2,",",".") ?></td>
						<td align="right" class="TBL_BODY" valign="middle"><?=number_format($row1["jml_rs"],2,",",".") ?></td>
					</tr>	
					<?
					$jml_tagihan=$jml_tagihan+$row1["jml_tagihan"] ;
					$jml_dokter=$jml_dokter+$row1["jml_dokter"] ;
					$jml_rs=$jml_rs+$row1["jml_rs"] ;
					?>
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
			
					<tr class="TBL_HEAD">  
			        	<td class="TBL_HEAD" align="center" colspan="3" height="25" valign="middle"> TOTAL </td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><?=number_format($jml_tagihan,2,",",".") ?></td>
						<td class="TBL_HEAD" align="center" height="25" valign="middle"> &nbsp; </td>
						<td class="TBL_HEAD" align="right" valign="middle"><?=number_format($jml_dokter,2,",",".") ?></td>
						<td class="TBL_HEAD" align="right" valign="middle"><?=number_format($jml_rs,2,",",".") ?></td>
					</tr>	
</table>
<?
    
} else {
   if (!$GLOBALS['print']){
		title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > Laporan Pembagian Jasa Medis");
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Pembagian Jasa Medis");
    }
	
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
    			  "select c.tc as tc, c.tdesc as tdesc ".
    			  "from rs00008 a, rs00006 b, rs00001 c ".
    			  "where a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP'", $_GET["mPASIEN"],"");
		//$f->selectSQL("mSUMBER", "Sumber Pendapatan","select '' as kel_sumb_pendapatan_id, '' as jasa_medis union ".
    	//		  "select kel_sumb_pendapatan_id as kel_sumb_pendapatan_id, jasa_medis as jasa_medis ".
    	//		  "from rsv_pembagian_jm ".
    	//		  "group by kel_sumb_pendapatan_id,jasa_medis ", $_GET["mSUMBER"],"");
		$f->selectSQL("mPOLI", "Poli","select '' as tc, '' as tdesc union ".
							"SELECT c.tc,c.tdesc FROM rs00001 c, rs00006 d WHERE CAST (c.tc AS NUMERIC)=d.poli and c.tt = 'LYN' and c.tc not in ('000','201','202','206','207','208')
							 order by tdesc ",$_GET["mPOLI"], "102");
		//trendy
		//$f->text("mDokter2","Dokter", 10, 10, "");
		
							 
		$f->selectSQL("mDokter2", "Dokter","select id,nama from rs00017 where nama like '%dr%' ",$_GET["mDokter2"], "" ); //where nama like '%dr%'",$_GET["mDokter2"], "" );
							 
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
		$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
    			  "select c.tc as tc, c.tdesc as tdesc ".
    			  "from rs00008 a, rs00006 b, rs00001 c ".
    			  "where a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP'", $_GET["mPASIEN"],"disabled");
		//$f->selectSQL("mSUMBER", "Sumber Pendapatan","select '' as kel_sumb_pendapatan_id, '' as jasa_medis union ".
    	//		  "select kel_sumb_pendapatan_id as kel_sumb_pendapatan_id, jasa_medis as jasa_medis ".
    	//		  "from rsv_pembagian_jm ".
    	//		  "group by kel_sumb_pendapatan_id,jasa_medis ", $_GET["mSUMBER"],"disabled");
		$f->selectSQL("mPOLI", "Poli","select '' as tc, '' as tdesc union ".
							"SELECT c.tc,c.tdesc FROM rs00001 c, rs00006 d WHERE c.tc_poli=d.poli and c.tt = 'LYN' and c.tc not in ('000','201','202','206','207','208')
							 order by tdesc ",$_GET["mPOLI"], "disabled");
		$f->selectSQL("mDokter2", "Dokter","select id,nama from rs00017 where nama like '%dr%'",$_GET["mDokter2"], "disabled" );
							 
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
	//trendy
	if (!empty($_GET[mDokter2])) {
       $SQL_b = " and id = '".$_GET["mDokter2"]."' ";
       $SQL_b2 = " and nama = '".$_GET["mDokter2"]."' ";

    } else {
       $SQL_b = " ";
    }
	
    if (strlen($_GET["search"]) > 0) {
			$r1 = pg_query($con, "sum(jum_tagihan) as jml_tagihan,sum(jm_dokter) as jml_dokter,(sum(jum_tagihan)-sum(jm_dokter)) as jml_rs ".
              "from rsv_pembagian_jm ".
			  "where upper(poli::text) LIKE '%".strtoupper($_GET["search"])."%' or upper(tipe) LIKE '%".strtoupper($_GET["search"])."%' or (tanggal_trans between '$ts_check_in1' and '$ts_check_in2')".
              "group by tanggal_trans,nama");

    } else {
        $r1 = pg_query($con,
	      "select sum(jum_tagihan) as jml_tagihan,sum(jm_dokter) as jml_dokter,(sum(jum_tagihan)-sum(jm_dokter)) as jml_rs from rsv_pembagian_jm 
		   where (tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and tipe like '%".$_GET[mPASIEN]."%' and poli::text like '%".$_GET[mPOLI]."%'  group by tanggal_trans,nama");

    }
	
   $SQL = "select to_char(tanggal_trans,'dd MON YYYY') as tanggal,nm_poli,tipe_pasien,nama,sum(jum_tagihan) as jml_tagihan,sum(jm_dokter) as jml_dokter,(sum(jum_tagihan)-sum(jm_dokter)) as jml_rs from rsv_pembagian_jm where (tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and tipe like '%".$_GET[mPASIEN]."%' and poli::text like '%".$_GET[mPOLI]."%' and id::text = '".$_GET[mDokter2]."' group by tanggal_trans,nm_poli,tipe_pasien,nama";
	
	$potongan=getFromTable("select sum(potongan) from rsv_total_pasien where (tgl_entry between '$ts_check_in1' and '$ts_check_in2')");
	
			@$r1 = pg_query($con,$SQL);
			@$n1 = pg_num_rows($r1);
	
	$SQL1 = "select to_char(tanggal_trans,'dd MON YYYY') as tanggal,poli,tipe,nama 
	from rsv_pembagian_jm 
	where (tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and tipe like '%".$_GET[mPASIEN]."%' 
	and poli::text like '%".$_GET[mPOLI]."%' and kel_sumb_pendapatan_id like '%".$_GET[mSUMBER]."%' 
	group by tanggal_trans,poli,tipe,nama";
	
	//$t->SQL1 = "$SQL1";
	
			@$r2 = pg_query($con,$SQL1);
			@$n2 = pg_num_rows($r2);
			
   			$max_row= 99999 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;} 
?>
<TABLE ALIGN="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
			<tr class="TBL_HEAD">     	
				<td class="TBL_HEAD" width="4%" align="center">NO</td>
				<td class="TBL_HEAD"align="center">TANGGAL</td>
				<td class="TBL_HEAD"align="center">NAMA DOKTER</td>
				<td class="TBL_HEAD"align="center">POLI</td>
				<td class="TBL_HEAD"align="center">TIPE PASIEN</td>
				<td width="10%" align="center" class="TBL_HEAD">JUMLAH PENDAPATAN JM</td>
				<td width="10%" align="center" class="TBL_HEAD">JUMLAH JM DOKTER</td>
				<td width="10%" align="center" class="TBL_HEAD">JUMLAH JM RS</td>
				<td width="5%" align="center" class="TBL_HEAD">VIEW DETAIL</td>
			</tr>
			
	
		<?	
			$jml_tagihan= 0;
			$jml_dokter= 0;
			$jml_rs= 0;
			$row1=0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while (@$row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
					$no=$i 	
					?>		
				 	<tr valign="top" class="<?=$class_nya?>" >  
			        	<td class="TBL_BODY" align="center"><?=$no ?> </td>
			        	<td class="TBL_BODY" align="center"><?=$row1["tanggal"] ?> </td>
						<td align="left" class="TBL_BODY"><?=$row1["nama"] ?></td>
						<td align="left" class="TBL_BODY"><?=$row1["nm_poli"] ?></td>
						<td align="left" class="TBL_BODY"><?=$row1["tipe_pasien"] ?></td>
						<td align="right" class="TBL_BODY" valign="middle"><?=number_format($row1["jml_tagihan"],2,",",".") ?></td>
						<td align="right" class="TBL_BODY" valign="middle"><?=number_format($row1["jml_dokter"],2,",",".") ?></td>
						<td align="right" class="TBL_BODY" valign="middle"><?=number_format($row1["jml_rs"],2,",",".") ?></td>
						<td align="center" class="TBL_BODY" valign="middle"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view&f=".$row1['nama']."&t1=$ts_check_in1"."&t2=$ts_check_in2&c=".$row1['tipe_pasien']."&z=".$row1['nm_poli'] ."'>".icon("view","View")."</A>";?></td>
					</tr>	
					<?
					$jml_tagihan=$jml_tagihan+$row1["jml_tagihan"] ;
					$jml_dokter=$jml_dokter+$row1["jml_dokter"] ;
					$jml_rs=$jml_rs+$row1["jml_rs"] ;
					?>
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
			
					<tr class="TBL_HEAD">  
			        	<td class="TBL_HEAD" align="center" colspan="5" height="25" valign="middle"> TOTAL </td>
			        	<td class="TBL_HEAD" align="right" valign="middle"><?=number_format($jml_tagihan,2,",",".") ?></td>
						<td class="TBL_HEAD" align="right" valign="middle"><?=number_format($jml_dokter,2,",",".") ?></td>
						<td class="TBL_HEAD" align="right" valign="middle"><?=number_format($jml_rs,2,",",".") ?></td>
						<td class="TBL_HEAD" align="right" valign="middle">&nbsp;</td>
					</tr>	
</table>

<?}
?>
