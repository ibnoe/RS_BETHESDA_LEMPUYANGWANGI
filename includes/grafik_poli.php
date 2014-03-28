<?	

$PID = "grafik_poli";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/setting.php"); 	
subtitle_print("DATA RAWAT JALAN");
//session_start();
//subtitle_print("Triwulan : ".$set_triwulan);
//subtitle_rs("FORMULIR RL1 - PENGUNJUNG RUMAH SAKIT");
//subtitle_rs($set_header[0]." ".$set_header[1]);
//subtitle_rs("No. Kode RS : ".$set_kode_rs);    

	/*if(!$GLOBALS['print']){
	 //	title_print("");
	//	title_excel("pengunjung_rumah_sakit");		
   	//    edit_laporan("input_pengunjung_rumah_sakit");
	}else {	
	}*/
    //    $okeh =" name=\"reg\"";     		
			//	$SQL = "select * from rl100001 order by no";
  						//$r1 = pg_query($con,$SQL);
    $f = new Form($SC, "GET", "NAME=Form1");
     title_print("");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

	if(!$GLOBALS['print']){
		if (!isset($_GET['mTAHUN'])) {
                        $mBULAN = date("m", time());
			$mTAHUN = date("Y", time());
                        $f->selectSQL2("mBULAN", "B u l a n",
		        "select distinct TO_CHAR(tanggal_reg,'mm'), TO_CHAR(tanggal_reg,'mm') from rs00006 "
		        , $mBULAN,$ext);
			$f->selectSQL2("mTAHUN", "T a h u n",
		        "select distinct TO_CHAR(tanggal_reg,'yyyy'), TO_CHAR(tanggal_reg,'yyyy') from rs00006 "
		        , $mTAHUN,$ext);
                        $f->selectSQL("mRAWAT", "RAWATAN","select '' as tc, '' as tdesc union 
                                             SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','201','202','206','207','208')
                                             order by tdesc ",$_GET["mRAWAT"], "");
						$f->selectSQL("mPASIEN","Tipe Pasien","select '' as tc, '' as tdesc union select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000' order by tdesc ",$_GET[mPASIEN],"");
    
    	} else {
                        $f->selectSQL2("mBULAN", "B u l a n",
		        "select distinct TO_CHAR(tanggal_reg,'mm'), TO_CHAR(tanggal_reg,'mm') from rs00006 "
		        , $mBULAN,$ext);
			$f->selectSQL2("mTAHUN", "T a h u n",
		        "select distinct TO_CHAR(tanggal_reg,'yyyy'), TO_CHAR(tanggal_reg,'yyyy') from rs00006 "
		        , $_GET["mTAHUN"],$ext);
                        $f->selectSQL("mRAWAT", "RAWATAN","select '' as tc, '' as tdesc union 
                                             SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','201','202','206','207','208')
                                             order by tdesc ",$_GET["mRAWAT"], "");
											 
					$f->selectSQL("mPASIEN","Tipe Pasien","select '' as tc, '' as tdesc union select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000' order by tdesc ",$_GET[mPASIEN],"");
    						 
    	}
		$f->submit ("TAMPILKAN");
		$f->execute();
	} else {
		if (!isset($_GET['mTAHUN'])) {
                        $mBULAN = date("m", time());
			$mTAHUN = date("Y", time());
                        $f->selectSQL2("mBULAN", "B u l a n",
		        "select distinct TO_CHAR(tanggal_reg,'mm'), TO_CHAR(tanggal_reg,'mm') from rs00006 "
		        , $mBULAN,$ext);
			$f->selectSQL2("mTAHUN", "T a h u n",
		        "select distinct TO_CHAR(tanggal_reg,'yyyy'), TO_CHAR(tanggal_reg,'yyyy') from rs00006 "
		        , $mTAHUN,$ext);
                        $f->selectSQL("mRAWAT", "RAWATAN","select '' as tc, '' as tdesc union 
                                             SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','201','202','206','207','208')
                                             order by tdesc ",$_GET["mRAWAT"], "");
    	} else {
                        $f->selectSQL2("mBULAN", "B u l a n",
		        "select distinct TO_CHAR(tanggal_reg,'mm'), TO_CHAR(tanggal_reg,'mm') from rs00006 "
		        , $mBULAN,$ext);
			$f->selectSQL2("mTAHUN", "T a h u n",
		        "select distinct TO_CHAR(tanggal_reg,'yyyy'), TO_CHAR(tanggal_reg,'yyyy') from rs00006 "
		        , $_GET["mTAHUN"],$ext);
                        $f->selectSQL("mRAWAT", "RAWATAN","select '' as tc, '' as tdesc union 
                                             SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','201','202','206','207','208')
                                             order by tdesc ",$_GET["mRAWAT"], "");
    	}
		$f->execute();
	}
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
	//if ($result) {
		for ($tgl=1;$tgl<=$bulanini;$tgl++) {
			//Now create a second query to get details for this factory
		$thnini=date("Y", time());
		$blnini=date("m", time());
		//$strQuery2 = "select * from rs00006 WHERE tanggal_reg ='$thnini-$blnini-$tgl'";
                $strQuery3 = getfromtable("select count(a.id) from rs00006 a where ".
                            "a.poli::text like '%".$_GET["mRAWAT"]."%' and a.tipe like '%".$_GET["mPASIEN"]."%' and a.tanggal_reg ='".$_GET["mTAHUN"]."-".$_GET["mBULAN"]."-$tgl' ");
			//$result2 = pg_query($con, "$strQuery2");
			//$row = pg_num_rows($result2);
			$strXML .= "<set name='" . $tgl . "' value='" . $strQuery3 . "' />";
		}
	//Finally, close <graph> element
	$strXML .= "</graph>";

	//Create the chart - Pie 3D Chart with data from $strXML
	echo renderChart("FusionCharts/FCF_Column3D.swf", "", $strXML, "2", 800, 250);
	// echo renderChartHTML("FusionCharts/FCF_Line.swf", "Data/Data.xml", "", "myFirst", 500, 250);?> </TD>
			 
<p>&nbsp;</p>
<p>&nbsp;</p>
</table>
</center>