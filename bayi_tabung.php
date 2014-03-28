<?	  
$PID = "bayi_tabung";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - KEGIATAN BAYI TABUNG");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs);  	  
		
	if(!$GLOBALS['print']){
	 	title_print("");
		title_excel("bayi_tabung");
        edit_laporan("input_bayi_tabung");
	}else {
	
			
	}
	
$SQL = "select * from rl100022 order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;} 	
 
?>
<table align="center" CLASS=TBL_BORDER WIDTH='75%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
        <td class="TBL_HEAD" align="center" rowspan="2">No</td>
        <td class="TBL_HEAD" align="center" rowspan="2">Metode</td>
        <td class="TBL_HEAD" align="center" colspan="3">Realisasi</td>
    </tr>
    <tr>
        <td class="TBL_HEAD" align="center">Siklus Pengobatan</td>
        <td class="TBL_HEAD" align="center">% Kehamilan</td>
        <td class="TBL_HEAD" align="center">% Take Home Baby (THB)</td>
    </tr>
	<?	
			$totsiklus= 0;
			$totplus= 0;
			$totpersen= 0;
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
      <td class="TBL_BODY" align="left"><?=$row1["metode"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["siklus_obat"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["persen_hamil"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["hamil_plus"] ?></td>
    </tr>
	<?
					$totsiklus=$totsiklus+$row1["siklus_obat"] ;
					$totplus=$totplus+$row1["persen_hamil"] ;
					$totpersen=$totpersen+$row1["hamil_plus"] ;
					?>
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
    <tr>
      <td colspan="2" align="center" class="TBL_HEAD">TOTAL</td>
      <td class="TBL_HEAD" align="center"><?=$totsiklus ?></td>
      <td class="TBL_HEAD" align="center"><?=$totplus ?></td>
      <td class="TBL_HEAD" align="center"><?=$totpersen ?></td>
    </tr>
</table>
<p>&nbsp;</p>