<?	

$PID = "grafik_penyakit";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/setting.php"); 	
subtitle_print("GRAFIK BABBER JOHNSON");
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
    
     		/*
				$SQL = "select sum(ts_calc_stop-ts_check_in) from rs000005 a ".
					   "order by no";
				*/	   
				$SQL = "select * from grafikbj ".
					   "order by no";
   
  						$r1 = pg_query($con,$SQL);
				
	
 

?> 		
		
						 

<table align="center" CLASS=TBL_BORDER WIDTH='39%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
<? while ($n1 = pg_fetch_row($r1)) { ?>
 <tr valign="top" class="TBL_HEAD">
    <td width="11%" align="center" class="TBL_BODY"><?=$n1[2] ?></td>
    <td width="46%" align="left" class="TBL_BODY"><?=$n1[0] ?></td>
    <td width="20%" align="center" class="TBL_BODY"><?=$n1[1] ?> </td>
  </tr>
 
<? } ?>
</table>

<TR>
			  <TD colspan="4" align="center"><? include("123Includes/FusionCharts.php");
			  echo renderChartHTML("FusionCharts/FCF_Column3D.swf", "Data/Data3.xml", "", "myFirst", 600, 300);?> 
			  </TD>
			  </TR>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
