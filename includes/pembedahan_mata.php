<?	
  
$PID = "pembedahan_mata";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - PEMBEDAHAN MATA");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs);  	  
		
	if(!$GLOBALS['print']){
	 	title_print("");
		title_excel("pembedahan_mata");
        edit_laporan("input_katarak");	
	}else {
				
	}
	
	 $SQL = "select * from rl100020a order by id";
   
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
        <td class="TBL_HEAD" align="center" colspan="3">Kualitas Hasil (BCVS)</td>
        <td class="TBL_HEAD" align="center">Kuantitas</td>
    </tr>
    <tr>
        <td class="TBL_HEAD" align="center">Baik &gt;6/12</td>
        <td class="TBL_HEAD" align="center">Sedang 6/18-6/12</td>
        <td class="TBL_HEAD" align="center">Buruk &lt;6/18</td>
        <td class="TBL_HEAD" align="center">Jumlah</td>
    </tr>
    <tr>
      <td class="TBL_HEAD" align="center">1</td>
      <td class="TBL_HEAD" align="center"><div align="justify">Katarak/Refraktif</div></td>
      <td class="TBL_HEAD" align="center">&nbsp;</td>
      <td class="TBL_HEAD" align="center">&nbsp;</td>
      <td class="TBL_HEAD" align="center">&nbsp;</td>
      <td class="TBL_HEAD" align="center">&nbsp;</td>
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
      <td class="TBL_BODY" align="center">&nbsp;</td>
      <td class="TBL_BODY" align="left"><?=$row1["kegiatan"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["baik"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["sedang"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["buruk"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["kuantitas"] ?></td>
    </tr>
	
  <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
</table>

<p>&nbsp;</p>
<? edit_laporan("input_glaukoma");	

$SQL = "select * from rl100020b order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}  

?>
<table align="center" CLASS=TBL_BORDER WIDTH='75%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
  <tr>
    <td class="TBL_HEAD" align="center" rowspan="3">No</td>
    <td class="TBL_HEAD" align="center" rowspan="3">Jenis Kegiatan</td>
    <td class="TBL_HEAD" align="center" colspan="3">Kualitas</td>
    <td class="TBL_HEAD" align="center">Kuantitas</td>
  </tr>
  <tr>
    <td class="TBL_HEAD" align="center" colspan="3">TIO</td>
    <td class="TBL_HEAD" align="center" rowspan="2">Jumlah</td>
  </tr>
  <tr>
    <td class="TBL_HEAD" align="center">Turun Obat (-)</td>
    <td class="TBL_HEAD" align="center">Turun Obat (+)</td>
    <td class="TBL_HEAD" align="center">Tetap / Meningkat</td>
  </tr>
  <tr>
    <td class="TBL_HEAD" align="center">2</td>
    <td class="TBL_HEAD" align="center"><div align="justify">Glaukoma</div></td>
    <td class="TBL_HEAD" align="center">&nbsp;</td>
    <td class="TBL_HEAD" align="center">&nbsp;</td>
    <td class="TBL_HEAD" align="center">&nbsp;</td>
    <td class="TBL_HEAD" align="center">&nbsp;</td>
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
    <td class="TBL_BODY" align="center">&nbsp;</td>
    <td class="TBL_BODY" align="left"><?=$row1["kegiatan"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["turun_obat_min"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["turun_obat_plus"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["tetap"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["kuantitas"] ?></td>
  </tr>
   <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
</table>
<p>&nbsp;</p>
<? edit_laporan("input_retina");	
 $SQL = "select * from rl100020c order by id";
   
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
    <td class="TBL_HEAD" align="center" colspan="2">Kualitas</td>
    <td class="TBL_HEAD" align="center">Kuantitas</td>
  </tr>
  <tr>
    <td class="TBL_HEAD" align="center">Perbaikan Anatromik</td>
    <td class="TBL_HEAD" align="center">Perbaikan Visus</td>
    <td class="TBL_HEAD" align="center">Jumlah</td>
  </tr>
  <tr>
    <td class="TBL_HEAD" align="center">3</td>
    <td class="TBL_HEAD" align="center"><div align="justify">Retina</div></td>
    <td class="TBL_HEAD" align="center">&nbsp;</td>
    <td class="TBL_HEAD" align="center">&nbsp;</td>
    <td class="TBL_HEAD" align="center">&nbsp;</td>
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
    <td class="TBL_BODY" align="center">&nbsp;</td>
    <td class="TBL_BODY" align="left"><?=$row1["kegiatan"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["anatromik"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["visus"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["kuantitas"] ?></td>
  </tr>
   <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
</table>
<p>&nbsp;</p>
<? edit_laporan("input_kornea"); 
$SQL = "select * from rl100020d order by id";
   
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
    <td class="TBL_HEAD" align="center" colspan="2">Kualitas</td>
    <td class="TBL_HEAD" align="center">Kuantitas</td>
  </tr>
  <tr>
    <td class="TBL_HEAD" align="center">Perbaikan -</td>
    <td class="TBL_HEAD" align="center">Perbaikan +</td>
    <td class="TBL_HEAD" align="center">Jumlah</td>
  </tr>
  <tr>
    <td class="TBL_HEAD" align="center">4</td>
    <td class="TBL_HEAD" align="center"><div align="justify">Kornea/Infeksi</div></td>
    <td class="TBL_HEAD" align="center">&nbsp;</td>
    <td class="TBL_HEAD" align="center">&nbsp;</td>
    <td class="TBL_HEAD" align="center">&nbsp;</td>
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
    <td class="TBL_BODY" align="center">&nbsp;</td>
    <td class="TBL_BODY" align="left"><?=$row1["kegiatan"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["baik_min"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["baik_plus"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["kuantitas"] ?></td>
  </tr>
   <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
</table>
<p>&nbsp;</p>
