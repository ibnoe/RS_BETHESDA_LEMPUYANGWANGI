<?	
  
$PID = "latihan_kursus";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");	  
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - LATIHAN/KURSUS/PENATARAN");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs); 
		
	if(!$GLOBALS['print']){
	 	title_print("");
		title_excel("latihan_kursus");	
        edit_laporan("input_kursus");
	}else {
		
		
		}
		
$SQL = "select * from rl100019 order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}       		
 
?>
<table align="center" CLASS=TBL_BORDER WIDTH='75%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr>
        <td class="TBL_HEAD" align="center" rowspan="2">No</td>
        <td class="TBL_HEAD" align="center" rowspan="2">Kategori Pelatihan</td>
        <td class="TBL_HEAD" align="center" colspan="3">Rumah Sakit Sendiri</td>
        <td class="TBL_HEAD" align="center" colspan="3">Rumah Sakit / Instansi Lain</td>
    </tr>
    <tr>
        <td class="TBL_HEAD" align="center">Dokter</td>
        <td class="TBL_HEAD" align="center">Tenaga Kes. Lainnya</td>
        <td class="TBL_HEAD" align="center">Non Kes. Lainnya</td>
        <td class="TBL_HEAD" align="center">Dokter</td>
        <td class="TBL_HEAD" align="center">Tenaga Kes Lainnya.</td>
        <td class="TBL_HEAD" align="center">Non Kes. Lainnya</td>
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
      <td class="TBL_BODY" align="left"><?=$row1["kategori"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["dokter_sendiri"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["kes_sendiri"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["non_kes_sendiri"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["dokter_lain"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["kes_lain"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["non_kes_lain"] ?></td>
    </tr>
	
	<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
</table>
<p>&nbsp;</p>
