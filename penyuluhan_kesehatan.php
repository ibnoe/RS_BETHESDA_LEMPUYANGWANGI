<?	
  
 $PID = "penyuluhan_kesehatan";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");	 
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - KEGIATAN PENYULUHAN KESEHATAN");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs);  
		
	if(!$GLOBALS['print']){
		title_print("");
		title_excel("penyuluhan_kesehatan");
		edit_laporan("input_penyuluhan_kesehatan");
	}else {
		
	}
$SQL = "select * from rl100015 order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}  	

?>
<table align="center" CLASS=TBL_BORDER WIDTH='75%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr>
	<td class="TBL_HEAD" align="center">No</td>
    <td class="TBL_HEAD" align="center">Topik Penyuluhan</td>
    <td class="TBL_HEAD" align="center">Pemasangan Poster (Ya/Tidak)</td>
    <td class="TBL_HEAD" align="center">Pemutaran Kaset (kali)</td>
    <td class="TBL_HEAD" align="center">Ceramah (kali)</td>
    <td class="TBL_HEAD" align="center">Demonstrasi (kali)</td>
    <td class="TBL_HEAD" align="center">Pameran (kali)</td>
    <td class="TBL_HEAD" align="center">Pelatihan (kali)</td>
    <td class="TBL_HEAD" align="center">Lain-Lain (kali)</td>
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
    <tr>
      <td class="TBL_BODY" align="center"><?=$no ?></td>
      <td class="TBL_BODY" align="left"><?=$row1["topik"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["poster"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["kaset"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["ceramah"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["demo"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["pameran"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["pelatihan"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["lain"] ?></td>
	   </tr>
	   <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
</table>
<p>&nbsp;</p>