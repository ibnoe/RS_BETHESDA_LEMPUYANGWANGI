<?	
  
$PID = "penyalahgunaan_napza";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");	
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - PENANGANAN PENYALAHGUNAAN NAPZA");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs);  
	  
		
	if(!$GLOBALS['print']){
	 	title_print("");
		title_excel("penyalahgunaan_napza");
        edit_laporan("input_napza");
	}else {		
		
	}
	
	 $SQL = "select * from rl100021 order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}  
 
?>
<table align="center" CLASS=TBL_BORDER WIDTH='75%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr>
        <td class="TBL_HEAD" align="center" rowspan="2">No</td>
        <td class="TBL_HEAD" align="center" rowspan="2">Jenis NAPZA</td>
        <td class="TBL_HEAD" align="center" colspan="2">Jenis Pelayanan</td>
        <td class="TBL_HEAD" align="center" rowspan="2">Aftercare</td>
    </tr>
    <tr>
        <td class="TBL_HEAD" align="center">Kuratif</td>
        <td class="TBL_HEAD" align="center">Rehabilitatif</td>
    </tr>
	<?	$tot1= 0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while ($row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
					$no=$i 	
					?>		
    <tr>
      <td class="TBL_BODY" align="center"><?=$row1["urutan"] ?></td>
      <td class="TBL_BODY" align="left"><?=$row1["jenis"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["kuratif"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["rehab"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["aftercare"] ?></td>
    </tr>
	 <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
</table>
<p>&nbsp;</p>
