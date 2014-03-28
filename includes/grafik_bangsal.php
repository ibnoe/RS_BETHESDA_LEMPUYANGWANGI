<?	

$PID = "grafik_bangsal";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/setting.php"); 	
subtitle_print("DATA RAWAT INAP");
//subtitle_print("Triwulan : ".$set_triwulan);
//subtitle_rs("FORMULIR RL1 - PENGUNJUNG RUMAH SAKIT");
//subtitle_rs($set_header[0]." ".$set_header[1]);
//subtitle_rs("No. Kode RS : ".$set_kode_rs);    

	if(!$GLOBALS['print']){
	 //	title_print("");
	//	title_excel("pengunjung_rumah_sakit");		
   	//    edit_laporan("input_pengunjung_rumah_sakit");
	}else {
		
	}
 
    //    $okeh =" name=\"reg\"";
    $f = new Form($SC, "GET", "NAME=Form1");
     title_print("");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

	if(!$GLOBALS['print']){
		if (!isset($_GET['mTAHUN'])) {
                        $mBULAN = date("m", time());
			$mTAHUN = date("Y", time());
                        $f->selectSQL2("mBULAN", "B u l a n",
		        "select distinct TO_CHAR(ts_check_in,'mm'), TO_CHAR(ts_check_in,'mm') from rs00010 "
		        , $mBULAN,$ext);
			$f->selectSQL2("mTAHUN", "T a h u n",
		        "select distinct TO_CHAR(ts_check_in,'yyyy'), TO_CHAR(ts_check_in,'yyyy') from rs00010 "
		        , $mTAHUN,$ext);
				$f->selectSQL("mINAP", "Bangsal ","select d.bangsal, d.bangsal as bangsal
                       from rs00010 as a 
                           join rs00012 as b on a.bangsal_id = b.id 
                           join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' 
                           join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' 
                           join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR'
group by d.bangsal
order by d.bangsal " ,$_GET["mINAP"], "");
$f->selectSQL("mPASIEN","Tipe Pasien","select '' as tc, '' as tdesc union select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000' order by tdesc ",$_GET[mPASIEN],"");
    
    	} else {
                        $f->selectSQL2("mBULAN", "B u l a n",
		        "select distinct TO_CHAR(ts_check_in,'mm'), TO_CHAR(ts_check_in,'mm') from rs00010 "
		        , $mBULAN,$ext);
			$f->selectSQL2("mTAHUN", "T a h u n",
		        "select distinct TO_CHAR(ts_check_in,'yyyy'), TO_CHAR(ts_check_in,'yyyy') from rs00010 "
		        , $_GET["mTAHUN"],$ext);
				$f->selectSQL("mINAP", "Bangsal ","select d.bangsal, d.bangsal as bangsal
                       from rs00010 as a 
                           join rs00012 as b on a.bangsal_id = b.id 
                           join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' 
                           join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' 
                           join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR'
group by d.bangsal
order by d.bangsal " ,$_GET["mINAP"], "");
$f->selectSQL("mPASIEN","Tipe Pasien","select '' as tc, '' as tdesc union select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000' order by tdesc ",$_GET[mPASIEN],"");
    	}
		$f->submit ("TAMPILKAN");
		$f->execute();
	} else {
		if (!isset($_GET['mTAHUN'])) {
                        $mBULAN = date("m", time());
			$mTAHUN = date("Y", time());
                        $f->selectSQL2("mBULAN", "B u l a n",
		        "select distinct TO_CHAR(ts_check_in,'mm'), TO_CHAR(ts_check_in,'mm') from rs00010 "
		        , $mBULAN,$ext);
			$f->selectSQL2("mTAHUN", "T a h u n",
		        "select distinct TO_CHAR(ts_check_in,'yyyy'), TO_CHAR(ts_check_in,'yyyy') from rs00010 "
		        , $mTAHUN,$ext);
    	} else {
                        $f->selectSQL2("mBULAN", "B u l a n",
		        "select distinct TO_CHAR(ts_check_in,'mm'), TO_CHAR(ts_check_in,'mm') from rs00010 "
		        , $mBULAN,$ext);
			$f->selectSQL2("mTAHUN", "T a h u n",
		        "select distinct TO_CHAR(ts_check_in,'yyyy'), TO_CHAR(ts_check_in,'yyyy') from rs00010 "
		        , $_GET["mTAHUN"],$ext);
    	}
		$f->execute();
	}
     		
				//$SQL = "select * from rl100001 order by no";
   
  				//		$r1 = pg_query($con,$SQL);
				
	
 

?> 		
<center>
<table>
<SCRIPT LANGUAGE="Javascript" SRC="FusionCharts/FusionCharts.js"></SCRIPT>

<TD  rowspan="2" colspan="2" align="center"><? include("123Includes/FusionCharts.php");
		//	include("lib/dbconn.php");
			//<TR>
	//<TD colspan="2" align="center"><?

	$strXML = "<graph caption='GRAFIK KUNJUNGAN PASIEN' subCaption='".$mBULAN." ".$mTAHUN."' showBorder='1' showNames='1' formatNumberScale='0' numberSuffix='' decimalPrecision='0' xAxisName='Tanggal' yAxisName='Pasien'>";
        if ($_GET["mTAHUN"] % 4 == 0){
                    if ($mBULAN == '04' or $mBULAN == '06' or $mBULAN == '09' or $mBULAN == '11'){
                        $bulanini = 30;
                    }elseif ($mBULAN == '02'){
                        $bulanini = 29;
                    } else {
                        $bulanini = 31;
                    }
                } else {
                    if ($mBULAN == '04' or $mBULAN == '06' or $mBULAN == '09' or $mBULAN == '11'){
                        $bulanini = 30;
                    }elseif ($mBULAN == '02'){
                        $bulanini = 28;
                    } else {
                        $bulanini = 31;
                    }
                }
        $tgl = 1;
		for ($tgl=1;$tgl<=$bulanini;$tgl++) {
		//$thnini=date("Y", time());
		//$blnini=date("m", time());
		//$strQuery2 = "select * from rs00010 WHERE ts_calc_start ='$thnini-$blnini-$tgl 12:00:00'";
                $sql_satus = getFromTable("select count(b.id) 
                              from rs00010 b 
							  left join rs00006 c on b.no_reg=c.id  
							  left join rs00010 e ON c.id = e.no_reg 
							  join rs00012 as f on e.bangsal_id = f.id 
							  join rs00012 as h on h.hierarchy = substr(f.hierarchy,1,3) || '000000000000'
                              where b.awal = 1 
                              and extract(YEAR from b.ts_check_in) = $mTAHUN 
                             and extract(day from b.ts_check_in) = $tgl and c.tipe like '%".$_GET[mPASIEN]."%' and (h.bangsal like '%".$_GET[mINAP]."%')");
			//$result2 = pg_query($con, "$strQuery2");
			//$row = pg_num_rows($result2);
			$strXML .= "<set name='" . $tgl . "' value='" . $sql_satus . "' />";
		}

	//Finally, close <graph> element
	$strXML .= "</graph>";
	//Create the chart - Pie 3D Chart with data from $strXML
	echo renderChart("FusionCharts/FCF_Column3D.swf", "", $strXML, "1", 800, 250);
	// echo renderChartHTML("FusionCharts/FCF_Line.swf", "Data/Data.xml", "", "myFirst", 500, 250);?> </TD>

<p>&nbsp;</p>
<p>&nbsp;</p>
</table>
</center>