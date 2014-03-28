<?	
  
 $PID = "kesehatan_jiwa";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - KESEHATAN JIWA");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs); 	  
		
	if(!$GLOBALS['print']){
		title_print("");
		title_excel("kesehatan_jiwa");
		edit_laporan("input_kesehatan_jiwa");	
	}else {
		
	}
	
$SQL = "select * from rl100006 group by jenis_layanan, kunjungan, id order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}     

?>
<table align="center" CLASS=TBL_BORDER WIDTH='75%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr>
        <td class="TBL_HEAD" align="center">No</td>
        <td class="TBL_HEAD" align="center">Jenis Pelayanan</td>
        <td class="TBL_HEAD" align="center">Kunjungan</td>
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
      <td class="TBL_BODY" align="center"><?=$no ?></td>
      <td class="TBL_BODY" align="left"><?=$row1["jenis_layanan"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["kunjungan"] ?></td>
    </tr>
	<?
	$tot1=$tot1+$row1["kunjungan"] ;
	?>
	<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
    <tr>
      <td colspan="2" align="center" class="TBL_HEAD">TOTAL</td>
      <td class="TBL_HEAD" align="center"><?=$tot1 ?></td>
    </tr>
</table>
<p>&nbsp;</p>