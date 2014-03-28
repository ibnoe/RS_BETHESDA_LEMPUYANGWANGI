<?	

$PID = "pengunjung_rumah_sakit";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/setting.php"); 	
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - PENGUNJUNG RUMAH SAKIT");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs);    

	if(!$GLOBALS['print']){
	 	title_print("");
		title_excel("pengunjung_rumah_sakit");		
   	    edit_laporan("input_pengunjung_rumah_sakit");
	}else {
		
	}
 
    //    $okeh =" name=\"reg\"";
    
     		
				$SQL = "select * from rl100001 order by no";
   
  						$r1 = pg_query($con,$SQL);
				
	
 

?> 		
		
						 

<table align="center" CLASS=TBL_BORDER WIDTH='39%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
<? while ($n1 = pg_fetch_row($r1)) { ?>
 <tr valign="top" class="TBL_HEAD">
    <td width="11%" align="center" class="TBL_HEAD"><?=$n1[2] ?></td>
    <td width="46%" align="left" class="TBL_HEAD"><?=$n1[0] ?></td>
    <td width="43%" align="center" class="TBL_HEAD"><?=$n1[1] ?>  orang</td>
  </tr>
 
<? } ?>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
