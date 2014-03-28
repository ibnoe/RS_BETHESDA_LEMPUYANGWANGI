<?	
  
$PID = "transfusi_darah";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");	  
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - TRANSFUSI DARAH");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs); 
		
	if(!$GLOBALS['print']){
	 	title_print("");
		title_excel("transfusi_darah");
       edit_laporan("input_darah");
	}else {
		
		
		
	}
	
$SQL = "select * from rl100018 order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}  	    

?>
<table align="center" CLASS=TBL_BORDER WIDTH='50%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
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
    <td class="TBL_BODY" align="center"><?=$row1["no"] ?></td>
    <td class="TBL_BODY" align="left"><?=$row1["jenis"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["jumlah"] ?> <?=$row1["satuan"] ?></td>
  </tr>
  <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
</table>

<p>&nbsp;</p>
