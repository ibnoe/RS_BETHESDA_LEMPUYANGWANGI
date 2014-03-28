<? // Nugraha, 17/02/2004
   // Pur, 27/02/2004
   // Pur, 27/03/2004 : new libs table
   // sfdn, 21-04-2004
   // sfdm, 08-06-2004
   // heri, 03-07-2007

$PID = "430";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");


if ($_GET["v"]){
	
	if(!$GLOBALS['print']){
		title_print("<img src='icon/medical-record-2.gif' align='absmiddle' >PELAPORAN REKAM MEDIS");
		echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
	}else {
		title_print("<img src='icon/medical-record.gif' align='absmiddle' > PELAPORAN REKAM MEDIS");
	}
	
	if ($_GET["v"] == '104'){
		include ('rm_anak.php');		
	}elseif ($_GET["v"] == '112'){
		include ('rm_jantung.php');
	}elseif ($_GET["v"] == '107'){
		include ('rm_bedah.php');
	}elseif ($_GET["v"] == '106'){
		include ('rm_tht.php');
	}elseif ($_GET["v"] == '102'){
		include ('rm_mata.php');
	}elseif ($_GET["v"] == '101'){
		include ('rm_umum.php');
	}elseif ($_GET["v"] == '103'){
		include ('rm_peny_dalam.php');
	}elseif ($_GET["v"] == '109'){
		include ('rm_kulit_kelamin.php');
	}elseif ($_GET["v"] == '105'){
		include ('rm_gigi.php');
	}elseif ($_GET["v"] == '113'){
		include ('rm_paru.php');
	}elseif ($_GET["v"] == '116'){
		include ('rm_psikiatri.php');
	}elseif ($_GET["v"] == '100'){
		include ('rm_igd.php');
	}elseif ($_GET["v"] == '114'){
		include ('rm_kebidanan.php&list=obstetri');
	}elseif ($_GET["v"] == '115'){
		include ('rm_kebidanan.php&list=genekologi');
	}elseif ($_GET["v"] == '110'){
		include ('rm_akupunktur.php');
	}elseif ($_GET["v"] == '108'){
		include ('rm_syaraf.php');
	}elseif ($_GET["v"] == '111'){
		include ('rm_gizi.php');
	}
	

}elseif ($_GET["mLAPOR"] == "002") {
    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));

	//$judul	= getFromTable("select tdesc from rs00001 where tc='".$_GET["mLAPOR"]."' and tt='LMR'");
	$prd1	= getFromTable("select to_char(to_date('$ts_check_in1','YYYY-MM-DD'),'DD MON YYYY')");
	$prd2	= getFromTable("select to_char(to_date('$ts_check_in2','YYYY-MM-DD'),'DD MON YYYY')");

	if(!$GLOBALS['print']){
		title_print("<img src='icon/medical-record-2.gif' align='absmiddle' >PELAPORAN REKAM MEDIS");
		//echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
	}else {
		title_print("<img src='icon/medical-record.gif' align='absmiddle' > PELAPORAN REKAM MEDIS");
	}
	$f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

	include(xxx);
	/*
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
	
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
	*/
	    //$f->selectDate("f_tanggal1", "Periode Laporan dari", pgsql2phpdate(now));
	    //$f->selectDate("f_tanggal2", " s/d", pgsql2phpdate(now));
	if(!$GLOBALS['print']){
		$ext = "";
	}else {
		$ext = "disabled";
	}
    $f->selectSQL("mLAPOR", "Jenis Laporan",
				    "select '' as tc, '' as tdesc union " .
				    "select tc, tdesc ".
				    "from rs00001 ".
				    "where tt = 'LMR' and tc!='000' ".
				    "order by tc", $_GET["mLAPOR"],$ext);

    //$f->submit(" Laporan ", "'actions/430.lap.".$_GET["mPEG"].".php'");
    $f->submit(" Laporan ",$ext);
    $f->execute();
    //echo "<br>";
 /*   
    $SQL = "select distinct b.tdesc, ".
			"(select count(no_reg) from rsv0040b where poli = a.poli ".
			"	and tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ) as pasien, ".
			"(select count(no_reg) from rsv0040b where is_baru='Y' and poli = a.poli ".
			"	and tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ) as baru, ". 
			"(select count(no_reg) from rsv0040b where is_baru='T' and poli = a.poli ".
			"	and tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ) as lama ".//, a.poli as dummy
			"from rs00006 a ".
			"join rs00001 b on a.poli = b.tc and b.tt = 'LYN' ".
			"where a.poli = b.tc and b.tt = 'LYN' order by b.tdesc ";

    $r = pg_query($con, $SQL);
    while ($d = pg_fetch_object($r)) {
       $t_pasien = $t_pasien + $d->pasien;
       $t_lama = $t_lama + $d->lama;
       $t_baru = $t_baru + $d->baru;

    }


    //$f = new Form("");
   subtitle("Periode : $prd1 s/d $prd2");
    //$f->execute();
    $t = new PgTable($con, "100%");
    $t->SQL = $SQL;
	$t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
	$t->ColAlign[1] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "CENTER";
	//$t->ColAlign[4] = "CENTER";
    $t->ColHeader = Array("P O L I", "PASIEN MASUK", "B A R U", "L A M A");	
    //$t->ColHeader = Array("P O L I", "PASIEN MASUK", "B A R U", "L A M A","V i e w");	
    $t->ColFooter[1] =  number_format($t_pasien,0);
    $t->ColFooter[2] =  number_format($t_baru,0);
    $t->ColFooter[3] =  number_format($t_lama,0);
	if(!$GLOBALS['print']){
    	//$t->ColFormatHtml[4] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&v=<#4#>'>".icon("view","View")."</A>";	
    }else {
    	//$t->ColFormatHtml[4] = icon("view","View");	
    	$t->DisableNavButton = true;
		$t->DisableScrollBar = true;
    }
    $t->execute();
*/

 			$SQL = "select  a.comment as layanan, 
				(select count(id) from rs00006 where poli = a.tc and tipe = '001' and jenis_kedatangan_id = '001' and rawat_inap != 'I' 
					and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as b_tni_au,
				(select count(id) from rs00006 where poli = a.tc and tipe = '008' and jenis_kedatangan_id = '001' and rawat_inap != 'I'  
					and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as b_pns_au,
				(select count(id) from rs00006 where poli = a.tc and tipe in ('002', '009') and jenis_kedatangan_id = '001' and rawat_inap != 'I'  
					and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as b_ad,
				(select count(id) from rs00006 where poli = a.tc and tipe in ('004','010') and jenis_kedatangan_id = '001' and rawat_inap != 'I'  
					and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as b_al,
				(select count(id) from rs00006 where poli = a.tc and tipe in ('005','011','012') and jenis_kedatangan_id = '001' and rawat_inap != 'I'  
					and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as b_askes,
				(select count(id) from rs00006 where poli = a.tc and tipe = '006' and jenis_kedatangan_id = '001' and rawat_inap != 'I' 
					and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as b_yanmas,
				(select count(id) from rs00006 where poli = a.tc and jenis_kedatangan_id = '001' and rawat_inap != 'I'  
					and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as jml_pasien_baru,
					
				(select count(id) from rs00006 where poli = a.tc and tipe = '001' and jenis_kedatangan_id = '003' and rawat_inap != 'I' 
					and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as l_tni_au,
				(select count(id) from rs00006 where poli = a.tc and tipe = '008' and jenis_kedatangan_id = '003' and rawat_inap != 'I'  
					and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as l_pns_au,
				(select count(id) from rs00006 where poli = a.tc and tipe in ('002', '009') and jenis_kedatangan_id = '003' and rawat_inap != 'I'  
					and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as l_ad,
				(select count(id) from rs00006 where poli = a.tc and tipe in ('004','010') and jenis_kedatangan_id = '003' and rawat_inap != 'I'  
					and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as l_al,
				(select count(id) from rs00006 where poli = a.tc and tipe in ('005','011','012') and jenis_kedatangan_id = '003' and rawat_inap != 'I'  
					and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as l_askes,
				(select count(id) from rs00006 where poli = a.tc and tipe = '006' and jenis_kedatangan_id = '003' and rawat_inap != 'I' 
					and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as l_yanmas,
				(select count(id) from rs00006 where poli = a.tc and jenis_kedatangan_id = '003' and rawat_inap != 'I'  
					and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as jml_pasien_lama,
				
				(select count(id) from rs00006 where poli = a.tc and rawat_inap != 'I' 
					and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as jml_kunjungan				 
				
				from rs00001 a 
				where a.tt = 'LYN' and a.tc not in ('000','201','202','206','207','208') 
				group by a.comment,a.tc
				order by a.tc ";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}
			//nav_db2($mulai,$n3,$max_row,"index2.php?p=$PID","") ;	
		
		?>			
			
			<TABLE CLASS=TBL_BORDER WIDTH='100%' BORDER=0 CELLSPACING=1 CELLPADDING=2>
			<tr class="TBL_HEAD">     	
				<td class="TBL_HEAD" width="3%" align="center" rowspan="3">NO</td>
				<td class="TBL_HEAD" width="15%" align="center" rowspan="3">JENIS PELAYANAN<BR>RAWAT JALAN</td>
				<td class="TBL_HEAD" align="center" colspan="7">KUNJUNGAN BARU</td>
				<td class="TBL_HEAD" align="center" colspan="7">KUNJUNGAN ULANG</td>
				<td class="TBL_HEAD" align="center" rowspan="3">JML KUNJ.</td>
				
			</tr>
			<tr class="TBL_HEAD">
				<td class="TBL_HEAD" align="center" colspan="2">TNI AU</td>
				<td class="TBL_HEAD" align="center" colspan="2">ANGK.LAIN</td>
				<td class="TBL_HEAD" align="center" rowspan="2">ASKES</td>
				<td class="TBL_HEAD" align="center" rowspan="2">YANMAS</td>
				<td class="TBL_HEAD" align="center" rowspan="2">&nbsp; JML &nbsp;</td>
				<td class="TBL_HEAD" align="center" colspan="2">TNI AU</td>
				<td class="TBL_HEAD" align="center" colspan="2">ANGK.LAIN</td>
				<td class="TBL_HEAD" align="center" rowspan="2">ASKES</td>
				<td class="TBL_HEAD" align="center" rowspan="2">YANMAS</td>
				<td class="TBL_HEAD" align="center" rowspan="2">&nbsp; JML &nbsp; </td>				
			</tr>
			<tr class="TBL_HEAD">
				<td class="TBL_HEAD" align="center">M</td>
				<td class="TBL_HEAD" align="center">S</td>
				<td class="TBL_HEAD" align="center">AD</td>
				<td class="TBL_HEAD" align="center">AL</td>
				<td class="TBL_HEAD" align="center">M</td>
				<td class="TBL_HEAD" align="center">S</td>
				<td class="TBL_HEAD" align="center">AD</td>
				<td class="TBL_HEAD" align="center">AL</td>				
			</tr>	
			
		<?	
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while ($row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
					$no=$i 	
					?>		
				 	<tr valign="top" class="<?=$class_nya?>" >  
			        	<td class="TBL_BODY" align="center"><?=$no ?> </font></td>
			        	<td class="TBL_BODY" align="left"><?=$row1["layanan"] ?> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["b_tni_au"] ?> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["b_pns_au"] ?> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["b_ad"] ?> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["b_al"] ?> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["b_askes"] ?> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["b_yanmas"] ?> </font></td>
						<td class="TBL_BODY" align="center"><b><?=$row1["jml_pasien_baru"] ?></b> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["l_tni_au"] ?> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["l_pns_au"] ?> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["l_ad"] ?> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["l_al"] ?> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["l_askes"] ?> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["l_yanmas"] ?> </font></td>
						<td class="TBL_BODY" align="center"><b><?=$row1["jml_pasien_lama"] ?></b> </font></td>
						<td class="TBL_BODY" align="center"><b><?=$row1["jml_kunjungan"] ?></b> </font></td>
						
					</tr>	
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			//footer total
			$SQL2 = "select  distinct
						(select count(id) from rs00006 where tipe = '001' and jenis_kedatangan_id = '001' and rawat_inap != 'I' 
							and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as b_tni_au,
						(select count(id) from rs00006 where tipe = '008' and jenis_kedatangan_id = '001' and rawat_inap != 'I'  
							and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as b_pns_au,
						(select count(id) from rs00006 where tipe in ('002', '009') and jenis_kedatangan_id = '001' and rawat_inap != 'I'  
							and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as b_ad,
						(select count(id) from rs00006 where tipe in ('004','010') and jenis_kedatangan_id = '001' and rawat_inap != 'I'  
							and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as b_al,
						(select count(id) from rs00006 where tipe in ('005','011','012') and jenis_kedatangan_id = '001' and rawat_inap != 'I'  
							and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as b_askes,
						(select count(id) from rs00006 where tipe = '006' and jenis_kedatangan_id = '001' and rawat_inap != 'I' 
							and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as b_yanmas,
						(select count(id) from rs00006 where jenis_kedatangan_id = '001' and rawat_inap != 'I'  
							and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as jml_pasien_baru,
							
						(select count(id) from rs00006 where tipe = '001' and jenis_kedatangan_id = '003' and rawat_inap != 'I' 
							and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as l_tni_au,
						(select count(id) from rs00006 where tipe = '008' and jenis_kedatangan_id = '003' and rawat_inap != 'I'  
							and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as l_pns_au,
						(select count(id) from rs00006 where tipe in ('002', '009') and jenis_kedatangan_id = '003' and rawat_inap != 'I'  
							and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as l_ad,
						(select count(id) from rs00006 where tipe in ('004','010') and jenis_kedatangan_id = '003' and rawat_inap != 'I'  
							and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as l_al,
						(select count(id) from rs00006 where tipe in ('005','011','012') and jenis_kedatangan_id = '003' and rawat_inap != 'I'  
							and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as l_askes,
						(select count(id) from rs00006 where tipe = '006' and jenis_kedatangan_id = '003' and rawat_inap != 'I' 
							and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as l_yanmas,
						(select count(id) from rs00006 where jenis_kedatangan_id = '003' and rawat_inap != 'I'  
							and (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2')) as jml_pasien_lama,						
						(select count(id) from rs00006 where (tanggal_reg >= '$ts_check_in1' and tanggal_reg <= '$ts_check_in2') and rawat_inap != 'I' ) as jml_kunjungan
							
					from rs00001 a 
					where a.tt = 'LYN' and a.tc not in ('000','201','202','206','207','208') 
					group by a.comment,a.tc ";
					$r2 = pg_query($con,$SQL2);
					$n2 =pg_numrows($r2);
					$row2 = pg_fetch_array($r2);
						//echo $SQL2;
			
			?>
			
					<tr valign="top" class="TBL_HEAD" >  
			        	<td align="center" colspan="2" height="25" valign="middle"> TOTAL </font></td>
			        	<td align="center" valign="middle"><?=$row2["b_tni_au"] ?> </font></td>
						<td align="center" valign="middle"><?=$row2["b_pns_au"] ?> </font></td>
						<td align="center" valign="middle"><?=$row2["b_ad"] ?> </font></td>
						<td align="center" valign="middle"><?=$row2["b_al"] ?> </font></td>
						<td align="center" valign="middle"><?=$row2["b_askes"] ?> </font></td>
						<td align="center" valign="middle"><?=$row2["b_yanmas"] ?> </font></td>
						<td align="center" valign="middle"><?=$row2["jml_pasien_baru"] ?> </font></td>
						<td align="center" valign="middle"><?=$row2["l_tni_au"] ?> </font></td>
						<td align="center" valign="middle"><?=$row2["l_pns_au"] ?> </font></td>
						<td align="center" valign="middle"><?=$row2["l_ad"] ?> </font></td>
						<td align="center" valign="middle"><?=$row2["l_al"] ?> </font></td>
						<td align="center" valign="middle"><?=$row2["l_askes"] ?> </font></td>
						<td align="center" valign="middle"><?=$row2["l_yanmas"] ?> </font></td>
						<td align="center" valign="middle"><?=$row2["jml_pasien_lama"] ?> </font></td>
						<td align="center" valign="middle"><?=$row2["jml_kunjungan"] ?></font></td>
						
					</tr>	
			
		</table>
		  <?		  
		echo "</td></tr></table>"; 

} else {
	if(!$GLOBALS['print']){
		title_print("<img src='icon/medical-record-2.gif' align='absmiddle' > PELAPORAN REKAM MEDIS");
	}else {
		title_print("<img src='icon/medical-record.gif' align='absmiddle' > PELAPORAN REKAM MEDIS");
	}
	
   // echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

	include(xxx);
	/*
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
	
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
	*/
	    //$f->selectDate("f_tanggal1", "Periode Laporan dari", pgsql2phpdate(now));
	    //$f->selectDate("f_tanggal2", " s/d", pgsql2phpdate(now));
	if(!$GLOBALS['print']){
		$ext = "";
	}else {
		$ext = "disabled";
	}
    $f->selectSQL("mLAPOR", "Jenis Laporan",
				    "select '' as tc, '' as tdesc union " .
				    "select tc, tdesc ".
				    "from rs00001 ".
				    "where tt = 'LMR' and tc!='000' ".
				    "order by tc", $_GET["mLAPOR"],$ext);

    //$f->submit(" Laporan ", "'actions/430.lap.".$_GET["mPEG"].".php'");
    $f->submit(" Laporan ",$ext);
    $f->execute();
	$f->hidden("mLAPOR",$_GET["mLAPOR"]);
	$f->hidden("ts_check_in1",'$ts_check_in1');
	$f->hidden("ts_check_in2",'$ts_check_in2');
	
    $t = new PgTable($con, "100%");
    $t->SQL =   "select nama, qty1,qty2, qty3,qty4, qty5, qty6, qty7,qty8,qty9, ".
                "qty10,qty11,qty12,qty13,qty14 ".
                "from rs00036 ".
                "where rs00001_tc = '" . $_GET["mPEG"]."'";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColHeader = Array("PELAYANAN", "PASIEN AWAL", "PASIEN MASUK", "KELUAR HIDUP",
                          "MATI < 48 JAM", "MATI >=48 JAM", "LAMA DIRAWAT","PASIEN AKHIR",
                          "HARI PERAWATAN","KLS. UTAMA","KLS. I","KLS. II","KLS. IIIA",
                          "KLS. IIIB","TANPA KELAS");
    if($GLOBALS['print']){
    	$t->DisableNavButton = true;
		$t->DisableScrollBar = true;
    }
    $t->execute();
}

?>
