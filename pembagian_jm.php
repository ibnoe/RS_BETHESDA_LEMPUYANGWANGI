<? 
$PID = "pembagian_jm";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

if($_GET["tc"] == "view") {

    title_print("Rincian Pendapatan Jasa Medis");
	$tp = getFromTable(
               "select a.jabatan_medis_fungsional from rs00018 a, rs00017 b ".
               "where  b.jabatan_medis_fungsional_id=a.id and b.id = '".$_GET["dok"]."' ");
			   
    $nama = getFromTable(
               "select nama from rsv_jasa_medis ".
               "where  id_dokter = '".$_GET["dok"]."' group by nama");
    $pasien = getFromTable(
               "select tipe_p from rsv_jasa_medis ".
               "where  tipe = '".$_GET["tipe"]."' group by tipe_p");
	$poli = getFromTable(
               "select tdesc from rsv_jasa_medis ".
               "where tc = '".$_GET["poli"]."' group by tdesc");

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
		echo "<td bgcolor='B0C4DE'><b>: $nama </td>";
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
    echo "<br>";
	title("Rincian Pendapatan Layanan");
	//echo "RINCIAN PENDAPATAN LAYANAN";
	echo "<br>";
	title_excel("pembagian_jm&tc=view&dok=".$_GET["dok"]."&t1=".$_GET["t1"]."&t2=".$_GET["t2"]."&tipe=".$_GET["tipe"]."&inap=".$_GET["inap"]."&poli=".$_GET["poli"]."");
/*     if (!$GLOBALS['print']){
    	echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    } else {
    	"";
    } */

$sql="select tanggal(a.tanggal_entry,0) as tanggal, a.no_reg,c.mr_no,c.nama, a.layanan, a.harga_atas, a.harga_bawah, a.tagihan
from rsv_jasa_medis a
left join rs00006 b on a.no_reg=b.id
left join rs00002 c on c.mr_no=b.mr_no 
where (a.tanggal_entry between '".$_GET["t1"]."' and '".$_GET["t2"]."') and a.tipe='".$_GET["tipe"]."' and a.is_inap='".$_GET["inap"]."' and id_dokter='".$_GET["dok"]."'";

@$r1 = pg_query($con,$sql);
			@$n1 = pg_num_rows($r1);
			
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;} 
?>
<TABLE ALIGN="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
			<tr class="TBL_HEAD">     	
				<td class="TBL_HEAD" width="4%" align="center">NO</td>
				<td class="TBL_HEAD"align="center">TANGGAL</td>
				<td class="TBL_HEAD"align="center">NO.REG</td>
				<td class="TBL_HEAD"align="center">NO.MR</td>
				<td class="TBL_HEAD"align="center">NAMA PASIEN</td>
				<td class="TBL_HEAD"align="center">LAYANAN</td>
				<td width="10%" align="center" class="TBL_HEAD">JASA SARANA</td>
				<td width="10%" align="center" class="TBL_HEAD">JASA PELAYANAN</td>
				<td width="10%" align="center" class="TBL_HEAD">TOTAL</td>
			</tr>
			
	
		<?	
			$jml_js= 0;
			$jml_jp= 0;
			$jml= 0;
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
						<td align="left" class="TBL_BODY"><?=$row1["no_reg"] ?></td>
						<td align="left" class="TBL_BODY"><?=$row1["mr_no"] ?></td>
						<td align="left" class="TBL_BODY"><?=$row1["nama"] ?></td>
						<td align="left" class="TBL_BODY"><?=$row1["layanan"] ?></td>
						<td align="right" class="TBL_BODY" valign="middle"><?=number_format($row1["harga_atas"],2,",",".") ?></td>
						<td align="right" class="TBL_BODY" valign="middle"><?=number_format($row1["harga_bawah"],2,",",".") ?></td>
						<td align="right" class="TBL_BODY" valign="middle"><?=number_format($row1["tagihan"],2,",",".") ?></td>
						
					</tr>	
					<?
					$jml_js=$jml_js+$row1["harga_atas"] ;
					$jml_jp=$jml_jp+$row1["harga_bawah"] ;
					$jml=$jml+$row1["tagihan"] ;
					?>
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
			
					<tr class="TBL_HEAD">  
			        	<td align="center" colspan="6" height="25" valign="middle"> TOTAL </td>
			        	<td align="right" valign="middle"><?=number_format($jml_js,2,",",".") ?></td>
						<td align="right" valign="middle"><?=number_format($jml_jp,2,",",".") ?></td>
						<td align="right" valign="middle"><?=number_format($jml,2,",",".") ?></td>
					</tr>	
</table>
<?    
} else {
   if (!$GLOBALS['print']){
		title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > Laporan Pembagian Jasa Medis");
		title_excel("pembagian_jm");
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
		$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
    			  "select c.tc as tc, c.tdesc as tdesc ".
    			  "from rs00008 a, rs00006 b, rs00001 c ".
    			  "where a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP'", $_GET["mPASIEN"],"");
/* 		$f->selectSQL("mPOLI", "Poli","select '' as tc, '' as tdesc union ".
							"SELECT c.tc,c.tdesc FROM rs00001 c, rs00006 d WHERE c.tc_poli=d.poli and c.tt = 'LYN' and c.tc not in ('000','201','202','206','207','208')
							 order by tdesc ",$_GET["mPOLI"], "102"); */
							 
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
		$f->selectArray("rawat_inap", "U n i t",Array(""=>"", "N" => "IGD", "Y" => "Rawat Jalan",  "I" => "Rawat Inap"),
                     $_GET[rawat_inap], "disabled");
		$_GET["rawat_inap"] = strlen($_GET["rawat_inap"]) == "" ? "" : $_GET["rawat_inap"];
		if ($_GET["rawat_inap"]=="Y"){
		$f->selectSQL("mRAWAT", "Poli","select '' as tc, '' as tdesc union 
												 SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','100','201','202','206','207','208')
												 order by tdesc ",$_GET["mRAWAT"], "disabled");
		}elseif ($_GET["rawat_inap"]=="I"){
		$f->selectSQL("mINAP", "Bangsal ","select d.bangsal, d.bangsal as bangsal
						   from rs00010 as a 
							   join rs00012 as b on a.bangsal_id = b.id 
							   join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' 
							   join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' 
							   join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR'
		group by d.bangsal
		order by d.bangsal " ,$_GET["mINAP"], "disabled");
			}else{}
		
		$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
    			  "select c.tc as tc, c.tdesc as tdesc ".
    			  "from rs00008 a, rs00006 b, rs00001 c ".
    			  "where a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP'", $_GET["mPASIEN"],"disabled");
/* 		$f->selectSQL("mPOLI", "Poli","select '' as tc, '' as tdesc union ".
							"SELECT c.tc,c.tdesc FROM rs00001 c, rs00006 d WHERE c.tc_poli=d.poli and c.tt = 'LYN' and c.tc not in ('000','201','202','206','207','208')
							 order by tdesc ",$_GET["mPOLI"], "disabled"); */
	}

		if($_GET["rawat_inap"] != "I"){
		$SQL1=" select is_inap,tipe,tipe_p,id_dokter,nama,tdesc,tc, tipe,sum(harga_atas) as js, sum(harga_bawah) as jp, sum(tagihan) as jumlah
				from rsv_jasa_medis
				where (tanggal_entry between '$ts_check_in1' and '$ts_check_in2') and is_inap like '%".$_GET["rawat_inap"]."%' and tc like '%".$_GET["mRAWAT"]."%'
				group by id_dokter,nama,tdesc, tipe,is_inap,tipe,tipe_p,tc 
				order by nama ";
		}else{
		$SQL1=" select is_inap,tipe,tipe_p,id_dokter,nama,tdesc,tc, tipe,sum(harga_atas) as js, sum(harga_bawah) as jp, sum(tagihan) as jumlah
				from rsv_jasa_medis_i
				where (tanggal_entry between '$ts_check_in1' and '$ts_check_in2') and is_inap like '%".$_GET["rawat_inap"]."%' and tc like '%".$_GET["mRAWAT"]."%'
				group by id_dokter,nama,tdesc, tipe,is_inap,tipe,tipe_p,tc 
				order by nama ";
		}
			@$r1 = pg_query($con,$SQL1);
			@$n1 = pg_num_rows($r1);
			
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;} 
?>
<TABLE ALIGN="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
			<tr class="TBL_HEAD">     	
				<td class="TBL_HEAD" width="4%" align="center">NO</td>
				<td class="TBL_HEAD"align="center">NAMA DOKTER</td>
				<? if($_GET["rawat_inap"] != "I"){ ?>
				<td class="TBL_HEAD"align="center">POLI</td>
				<? }else{ ?>
				<td class="TBL_HEAD"align="center">BANGSAL</td>
				<? } ?>
				<td class="TBL_HEAD"align="center">TIPE PASIEN</td>
				<td width="10%" align="center" class="TBL_HEAD">TOTAL JASA SARANA</td>
				<td width="10%" align="center" class="TBL_HEAD">TOTAL JASA PELAYANAN</td>
				<td width="10%" align="center" class="TBL_HEAD">TOTAL PELAYANAN</td>
				<td width="5%" align="center" class="TBL_HEAD">VIEW DETAIL</td>
			</tr>
			
	
		<?	
			$jml_js= 0;
			$jml_jp= 0;
			$jml= 0;
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
						<td align="left" class="TBL_BODY"><?=$row1["nama"] ?></td>
						<td align="left" class="TBL_BODY"><?=$row1["tdesc"] ?></td>
						<td align="left" class="TBL_BODY"><?=$row1["tipe_p"] ?></td>
						<td align="right" class="TBL_BODY" valign="middle"><?=number_format($row1["js"],2,",",".") ?></td>
						<td align="right" class="TBL_BODY" valign="middle"><?=number_format($row1["jp"],2,",",".") ?></td>
						<td align="right" class="TBL_BODY" valign="middle"><?=number_format($row1["jumlah"],2,",",".") ?></td>
						<td align="center" class="TBL_BODY" valign="middle"><?=$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view&dok=".$row1['id_dokter']."&t1=$ts_check_in1"."&t2=$ts_check_in2&tipe=".$row1['tipe']."&inap=".$row1['is_inap'] ."&poli=".$row1['tc'] ."'>".icon("view","View")."</A>";?></td>
					</tr>	
					<?
					$jml_js=$jml_js+$row1["js"] ;
					$jml_jp=$jml_jp+$row1["jp"] ;
					$jml=$jml+$row1["jumlah"] ;
					?>
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
			
					<tr class="TBL_HEAD">  
			        	<td align="center" colspan="4" height="25" valign="middle"> TOTAL </td>
			        	<td align="right" valign="middle"><?=number_format($jml_js,2,",",".") ?></td>
						<td align="right" valign="middle"><?=number_format($jml_jp,2,",",".") ?></td>
						<td align="right" valign="middle"><?=number_format($jml,2,",",".") ?></td>
						<td align="right" valign="middle">&nbsp;</td>
					</tr>	
</table>
<?}
?>
