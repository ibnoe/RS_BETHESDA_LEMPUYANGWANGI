<?	
  
$PID = "pemantauan_tenaga_kes_asing";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");	 
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - PEMANTAUAN DOKTER & TENAGA KESEHATAN ASING LAINNYA");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs);  
		
	if(!$GLOBALS['print']){
	 	title_print("");
		title_excel("pemantauan_tenaga_kes_asing");
        edit_laporan("input_tenaga_asing");
	}else {
		
		
	}
	
$SQL = "select * from rl100017 order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}  	  

?>
<table align="center" CLASS=TBL_BORDER WIDTH='50%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
   <tr>
    <td class="TBL_HEAD" align="center">No</td>
    <td class="TBL_HEAD" align="center">Jenis Keahlian</td>
    <td class="TBL_HEAD" align="center">Asal Negara</td>
    <td class="TBL_HEAD" align="center">Status Kepegawaian</td>
    <td class="TBL_HEAD" align="center">Lama Domisili</td>
    <td class="TBL_HEAD" align="center">Jenis Pelayanan</td>
    <td class="TBL_HEAD" align="center">Jumlah</td>
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
      <td class="TBL_BODY" align="left"><?=$row1["keahlian"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["negara"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["status_pegawai"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["lama_domisili"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["pelayanan"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["jumlah"] ?></td>
	 </tr> 
	 <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
</table>
<p>&nbsp;</p>
