<?	
  
$PID = "kebidanan";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - KEGIATAN KEBIDANAN & PERINATOLOGI");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs);      
		
	if(!$GLOBALS['print']){
		title_print("");
		title_excel("kebidanan");
		edit_laporan("input_kebidanan");	
	}else {
		
		
	}
	
 $SQL = "select * from rl100004 order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}  

?>

<table align="center" CLASS=TBL_BORDER WIDTH='75%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr>
        <td class="TBL_HEAD" align="center" rowspan="2">No</td>
        <td class="TBL_HEAD" align="center" rowspan="2">Jenis Kegiatan</td>
        <td class="TBL_HEAD" align="center" colspan="2">Rujukan</td>
        <td class="TBL_HEAD" align="center" colspan="2">Non Rujukan</td>
        <td class="TBL_HEAD" align="center" rowspan="2">Dirujuk Keatas</td>
    </tr>
    <tr>
        <td class="TBL_HEAD" align="center">Jml</td>
        <td class="TBL_HEAD" align="center">Mati</td>
        <td class="TBL_HEAD" align="center">Jml</td>
        <td class="TBL_HEAD" align="center">Mati</td>
    </tr>
	<?	
			$totbaru= 0;
			$totulang= 0;
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
      <td class="TBL_BODY" align="left"><?=$row1["jenis_kegiatan"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["ruj_jml"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["ruj_mati"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["non_ruj_jml"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["non_ruj_mati"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["dirujuk_keatas"] ?></td>
    </tr>
		<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
</table>
	<p>&nbsp;</p>