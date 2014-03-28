<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 09-05-2004
   // sfdn, 11-05-2004
   // sfdn, 14-05-2004
   // sfdn, 29-05-2004
	//hery, 03-07-2007

$PID = "470";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/visit_setting.php");


if ($_GET["v"]){
	
	if(!$GLOBALS['print']){
		title_print("<img src='icon/medical-record-2.gif' align='absmiddle' >REKAPITULASI PELAYANAN RAWAT JALAN");
		echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
	}else {
		title_print("<img src='icon/medical-record.gif' align='absmiddle' > REKAPITULASI PELAYANAN RAWAT JALAN");
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
	

}elseif($_GET["tc"] == "view") {
    if(!$GLOBALS['print']){
		title_print("<img src='icon/medical-record-2.gif' align='absmiddle' >Rincian Laporan Rawat Jalan");
		echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
	}else {
		title_print("<img src='icon/medical-record.gif' align='absmiddle' > Rincian Laporan Rawat Jalan");
	}
    if ($_GET["x"] == "001") {
        $tp = getFromTable(
               "select month_str || '  ' || to_char(tahun,'9999') as prd from rs00035 ".
               "where id = '".$_GET["y"]."'");
        $bulan = "Bulan : $tp";
    } else {
        $tp = $_GET["y"];
        $tahun = $_GET["z"];
        $bulan = "Kuartal  $tp $tahun";

    }
    $unit = getFromTable(
               "select tdesc from rs00001 ".
               "where tt='LYN' and  tc = '".$_GET["f"]."'");
    $f = new Form("");
    $f->rotext("B u l a n",$tp);
    $f->rotext("U n i t ","<A CLASS=TBL_HREF HREF='$SC?p=$PID&v={$_GET["f"]}' >". $unit."</A>");
    $f->execute();

    echo "<br>";
    $t = new PgTable($con, "100%");
    $t->SQL =
        "	select a.no_reg,to_char(a.tanggal_reg,'DD-MON-YYYY') as tgl, c.nama,e.tdesc as pasien ".
		"	from c_visit a ".
		"	left join rs00006 b ON a.no_reg = b.id ".
		"	left join rs00002 c ON b.mr_no = c.mr_no ".
		"	left join rs00001 e ON b.tipe = e.tc ".
		"	where e.tt='JEP' and a.id_poli = '".$_GET["f"]."'";

   	$t->ColHeader = array("NO.REG.", "TGL PERIKSA","NAMA PASIEN","TIPE PASIEN");
	$t->ColAlign = array("center","center","left","left");
	if($GLOBALS['print']){
		//$t->ColFormatHtml[4] = icon("view","View");	
    	$t->DisableNavButton = true;
		$t->DisableScrollBar = true;
    }
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->execute();

} else {
    
    if (!$GLOBALS['print']){
		title_print("<img src='icon/medical-record-2.gif' align='absmiddle' > LAPORAN BULANAN PELAYANAN RAWAT JALAN");
		$ext = "OnChange = 'Form1.submit();'";
	}else {
		title_print(" LAPORAN BULANAN PELAYANAN RAWAT JALAN","center");
		$ext = "disabled";
	}
    if (!$GLOBALS['print']){
	    $f = new Form($SC, "GET", "NAME=Form1");
	    $f->PgConn = $con;
	    $f->hidden("p", $PID);
	    
	    $f->selectArray("mBULAN","Bulan",Array("0"=>" ","1"=>"Januari","2"=>"Februari","3"=>"Maret","4"=>"April",
	         "5"=>"Mei","6"=>"Juni","7"=>"Juli","8"=>"Agustus","9"=>"September","10"=>"Oktober",
			 "11"=>"November","12"=>"Desember"),$_GET["mBULAN"],$ext);       
		
		    $f->selectSQL("mTAHUN", "T a h u n",
		        "select distinct substr(tanggal_reg,1,4), substr(tanggal_reg,1,4) from rs00006 "
		        , $_GET["mTAHUN"],$ext);
	    $f->execute();
    }else {
    	echo "<table width='100%' cellspacing=0 cellpadding=2><tr><td CLASS='FORM_TITLE2' align='center' >\n";
		switch ($_GET['mBULAN']){
			case 1 :  echo "<font size=1> Bulan Januari  ".$_GET['mTAHUN']."</font>";
				break;
			case 2 :  echo "<font size=3> Bulan : Februari  ".$_GET['mTAHUN']."</font>";
				break;
			case 3 :  echo "<font size=3> Bulan : Maret  ".$_GET['mTAHUN']."</font>";
				break;
			case 4 : echo "<font size=3> Bulan : April  ".$_GET['mTAHUN']."</font>";
				break;
			case 5 : echo "<font size=3> Bulan : Mei  ".$_GET['mTAHUN']."</font>";
				break;
			case 6 : echo "<font size=3> Bulan : Juni  ".$_GET['mTAHUN']."</font>";
				break;
			case 7 : echo "<font size=3> Bulan : Juli  ".$_GET['mTAHUN']."</font>";
				break;
			case 8 : echo "<font size=3> Bulan : Agustus  ".$_GET['mTAHUN']."</font>";
				break;	
			case 9 : echo "<font size=3> Bulan : September  ".$_GET['mTAHUN']."</font>";
				break;
			case 10 : echo "<font size=3> Bulan : Oktober  ".$_GET['mTAHUN']."</font>";
				break;
			case 11 : echo "<font size=3> Bulan : November  ".$_GET['mTAHUN']."</font>";
				break;
			case 12 : echo "<font size=3> Bulan : Desember  ".$_GET['mTAHUN']."</font>";
				break;
			default:
				break;			
		}
		echo "</td></tr></table>\n";	 
    	
    }
	    
    echo "<br>";
    //if ($_GET["mPERIODE"]<>'' && $_GET["mBULAN"]<>'' && $_GET["mTAHUN"]<>'') {
    
    $_GET["mPERIODE"] = "001";
    $start_tgl = mktime(0,0,0,$_GET[mBULAN],1,$_GET[mTAHUN]);
    $max_tgl = date("t", $start_tgl);
    $end_tgl = mktime(0,0,0,$_GET[mBULAN],$max_tgl,$_GET[mTAHUN]);
    $start_tgl = date("Y-m-d", $start_tgl);
    $end_tgl = date("Y-m-d", $end_tgl);
    
    if ($_GET["mPERIODE"]<>''&& $_GET["mBULAN"]<>'' && $_GET["mTAHUN"]<>'') {
    
    //if ($_GET["mPERIODE"] == "001") {
        $prd_id = getFromTable(
               "select id from rs00035 ".
               "where month_no = '".$_GET["mBULAN"]."' and ".
               "    tahun ='".$_GET["mTAHUN"]."'");
        
	/*
	$r2 = pg_query($con,
            "select sum(jml_pasien_masuk) as masuk, sum(jml_rujukan_dari_bawah) as bawah, ".
            "   sum(jml_dirujuk_keatas) as atas,sum(jml_jpsbk) as jpsbk ".
            "from rs00042 ".
            "where time_id = $prd_id ");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
	*/

       $q = pg_query("select a.tdesc, count(b.no_reg) as jml_masuk, count(c.status_akhir_pasien) as rujuk_bawah, 
					count(d.status_akhir_pasien) as rujuk_atas  
					from rs00001 a 
					left join c_visit b on b.id_poli = a.tc  and (b.tanggal_reg >=  '$start_tgl' and b.tanggal_reg <= '$end_tgl') 
					left join rs00006 c on c.poli = a.tc and c.status_akhir_pasien = '008' and (c.tanggal_reg >=  '$start_tgl' and c.tanggal_reg <= '$end_tgl') 
					left join rs00006 d on d.poli = a.tc and d.status_akhir_pasien = '009' and (d.tanggal_reg >=  '$start_tgl' and d.tanggal_reg <= '$end_tgl') 
					where a.tt = 'LYN' and a.tc like '1%'   
					group by a.tc,a.tdesc order by a.tc");
        	
		$d2 = pg_fetch_object($q);
	
		if ($d2) {
			do  {
			   
			   $masuk = $masuk + $d2->jml_masuk;
			   $bawah = $bawah + $d2->rujuk_bawah;
			   $atas = $atas + $d2->rujuk_atas;
			   
			} while ($d2 = pg_fetch_object($q));
		}
	        
	
	//pg_free_result($r2);
	
	
	
       
    $t = new PgTable($con, "100%");
    //if ($_GET["mPERIODE"] == "001") {
        
   //////////////////
    /* echo   $t->SQL =
           	"select a.comment, count(b.no_reg) as jml_masuk, count(c.status_akhir_pasien) as rujuk_bawah, 
           	count(d.status_akhir_pasien) as rujuk_atas,a.tc as dummy 
			from rs00001 a 
			left join c_visit b on b.id_poli = a.tc and (b.tanggal_reg >= '$start_tgl' and b.tanggal_reg <= '$end_tgl') 
			left join rs00006 c on c.poli = a.tc and c.status_akhir_pasien = '008' 
				and (c.tanggal_reg >= '$start_tgl' and c.tanggal_reg <= '$end_tgl') 
			left join rs00006 d on d.poli = a.tc and d.status_akhir_pasien = '009' 
				and (d.tanggal_reg >= '$start_tgl' and d.tanggal_reg <= '$end_tgl') 
			where a.tt = 'LYN' and a.tc like '1%'  
			group by a.comment,a.tc order by a.tc ";
	*/		
   $SQL = "select  a.comment as layanan, 
				(select count(id) from rs00006 where poli = a.tc and tipe = '001' and jenis_kedatangan_id = '001' and rawat_inap != 'I' 
					and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as b_tni_au,
				(select count(id) from rs00006 where poli = a.tc and tipe = '008' and jenis_kedatangan_id = '001' and rawat_inap != 'I'  
					and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as b_pns_au,
				(select count(id) from rs00006 where poli = a.tc and tipe in ('002', '009') and jenis_kedatangan_id = '001' and rawat_inap != 'I'  
					and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as b_ad,
				(select count(id) from rs00006 where poli = a.tc and tipe in ('004','010') and jenis_kedatangan_id = '001' and rawat_inap != 'I'  
					and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as b_al,
				(select count(id) from rs00006 where poli = a.tc and tipe in ('005','011','012') and jenis_kedatangan_id = '001' and rawat_inap != 'I'  
					and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as b_askes,
				(select count(id) from rs00006 where poli = a.tc and tipe = '006' and jenis_kedatangan_id = '001' and rawat_inap != 'I' 
					and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as b_yanmas,
				(select count(id) from rs00006 where poli = a.tc and jenis_kedatangan_id = '001' and rawat_inap != 'I'  
					and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as jml_pasien_baru,
					
				(select count(id) from rs00006 where poli = a.tc and tipe = '001' and jenis_kedatangan_id = '003' and rawat_inap != 'I' 
					and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as l_tni_au,
				(select count(id) from rs00006 where poli = a.tc and tipe = '008' and jenis_kedatangan_id = '003' and rawat_inap != 'I'  
					and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as l_pns_au,
				(select count(id) from rs00006 where poli = a.tc and tipe in ('002', '009') and jenis_kedatangan_id = '003' and rawat_inap != 'I'  
					and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as l_ad,
				(select count(id) from rs00006 where poli = a.tc and tipe in ('004','010') and jenis_kedatangan_id = '003' and rawat_inap != 'I'  
					and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as l_al,
				(select count(id) from rs00006 where poli = a.tc and tipe in ('005','011','012') and jenis_kedatangan_id = '003' and rawat_inap != 'I'  
					and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as l_askes,
				(select count(id) from rs00006 where poli = a.tc and tipe = '006' and jenis_kedatangan_id = '003' and rawat_inap != 'I' 
					and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as l_yanmas,
				(select count(id) from rs00006 where poli = a.tc and jenis_kedatangan_id = '003' and rawat_inap != 'I'  
					and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as jml_pasien_lama,
				
				(select count(id) from rs00006 where poli = a.tc and rawat_inap != 'I' 
					and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as jml_kunjungan				 
				
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
						<td class="TBL_BODY" align="center"> <?=$row1["b_tni_au"] ?></font></td>
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
							and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as b_tni_au,
						(select count(id) from rs00006 where tipe = '008' and jenis_kedatangan_id = '001' and rawat_inap != 'I'  
							and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as b_pns_au,
						(select count(id) from rs00006 where tipe in ('002', '009') and jenis_kedatangan_id = '001' and rawat_inap != 'I'  
							and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as b_ad,
						(select count(id) from rs00006 where tipe in ('004','010') and jenis_kedatangan_id = '001' and rawat_inap != 'I'  
							and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as b_al,
						(select count(id) from rs00006 where tipe in ('005','011','012') and jenis_kedatangan_id = '001' and rawat_inap != 'I'  
							and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as b_askes,
						(select count(id) from rs00006 where tipe = '006' and jenis_kedatangan_id = '001' and rawat_inap != 'I' 
							and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as b_yanmas,
						(select count(id) from rs00006 where jenis_kedatangan_id = '001' and rawat_inap != 'I'  
							and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as jml_pasien_baru,
							
						(select count(id) from rs00006 where tipe = '001' and jenis_kedatangan_id = '003' and rawat_inap != 'I' 
							and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as l_tni_au,
						(select count(id) from rs00006 where tipe = '008' and jenis_kedatangan_id = '003' and rawat_inap != 'I'  
							and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as l_pns_au,
						(select count(id) from rs00006 where tipe in ('002', '009') and jenis_kedatangan_id = '003' and rawat_inap != 'I'  
							and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as l_ad,
						(select count(id) from rs00006 where tipe in ('004','010') and jenis_kedatangan_id = '003' and rawat_inap != 'I'  
							and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as l_al,
						(select count(id) from rs00006 where tipe in ('005','011','012') and jenis_kedatangan_id = '003' and rawat_inap != 'I'  
							and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as l_askes,
						(select count(id) from rs00006 where tipe = '006' and jenis_kedatangan_id = '003' and rawat_inap != 'I' 
							and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as l_yanmas,
						(select count(id) from rs00006 where jenis_kedatangan_id = '003' and rawat_inap != 'I'  
							and (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl')) as jml_pasien_lama,						
						(select count(id) from rs00006 where (tanggal_reg >= '$start_tgl' and tanggal_reg <= '$end_tgl') and rawat_inap != 'I' ) as jml_kunjungan
							
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
		    <p>
		      <?		  
		echo "</td></tr></table>"; 
    }
}

?>
</p>
		    <p>&nbsp;                        </p>
