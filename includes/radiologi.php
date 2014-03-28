<?	
  
 $PID = "radiologi";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");	  
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - KEGIATAN RADIOLOGI");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs); 
		
	if(!$GLOBALS['print']){
		title_print("");
		title_excel("radiologi");
		edit_laporan("input_radiodiagnostik");	
	}else {
		
	}
	
 $SQL = "select * from rl100009a order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}  

?>
<table align="center" CLASS=TBL_BORDER WIDTH='75%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
  <tr>
    <td colspan="2" align="center" class="TBL_HEAD">A. RADIODIAGNOSTIK</td>
    <td width="22%" align="center" class="TBL_HEAD">Jumlah</td>
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
    <td width="7%" align="center" class="TBL_BODY"><?=$no ?></td>
    <td width="71%" align="left" class="TBL_BODY"><?=$row1["jenis"] ?></td>
    <td class="TBL_BODY" align="center"> <?=$row1["jumlah"] ?> kali</td>
  </tr>
  <?
	$tot1=$tot1+$row1["jumlah"] ;
	?>
  <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
  <tr>
    <td colspan="2" align="center" class="TBL_HEAD">TOTAL</td>
    <td class="TBL_HEAD" align="center"><?=$tot1 ?> kali</td>
  </tr>
</table>
<p>&nbsp;</p>
<? edit_laporan("input_radiotherapi");	

$SQL = "select * from rl100009b order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}  

?>
<table align="center" class="TBL_BORDER" width='75%' border="1" cellspacing="1" cellpadding="2">
  <tr>
    <td colspan="2" align="center" class="TBL_HEAD">B. RADIOTHERAPI</td>
    <td width="22%" align="center" class="TBL_HEAD">Jumlah</td>
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
    <td width="7%" align="center" class="TBL_BODY"><?=$no ?></td>
    <td width="71%" align="left" class="TBL_BODY"><?=$row1["jenis"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["jumlah"] ?> Org</td>
  </tr>
  <?
	$tot1=$tot1+$row1["jumlah"] ;
	?>
  <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
  <tr>
    <td colspan="2" align="center" class="TBL_HEAD">TOTAL</td>
    <td class="TBL_HEAD" align="center"><?=$tot1 ?> Org</td>
  </tr>
</table>
<p>&nbsp;</p>
<? edit_laporan("input_dokter_nuklir");	
 $SQL = "select * from rl100009c order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}  

?>
<table align="center" class="TBL_BORDER" width='75%' border="1" cellspacing="1" cellpadding="2">
  <tr>
    <td colspan="2" align="center" class="TBL_HEAD">C. KEDOKTERAN NUKLIR </td>
    <td width="22%" align="center" class="TBL_HEAD">Jumlah</td>
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
    <td width="7%" align="center" class="TBL_BODY"><?=$no ?></td>
    <td width="71%" align="left" class="TBL_BODY"><?=$row1["jenis"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["jumlah"] ?> Org</td>
  </tr>
  <?
	$tot1=$tot1+$row1["jumlah"] ;
	?>
  <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
  <tr>
    <td colspan="2" align="center" class="TBL_HEAD">TOTAL</td>
    <td class="TBL_HEAD" align="center"><?=$tot1 ?> Org</td>
  </tr>
</table>
<p>&nbsp;</p>
<? edit_laporan("input_imaging"); 
$SQL = "select * from rl100009d order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}  

?>
<table align="center" class="TBL_BORDER" width='75%' border="1" cellspacing="1" cellpadding="2">
  <tr>
    <td colspan="2" align="center" class="TBL_HEAD">D. IMAGING/PENCITRAAN </td>
    <td width="22%" align="center" class="TBL_HEAD">Jumlah</td>
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
    <td width="7%" align="center" class="TBL_BODY"><?=$no ?></td>
    <td width="71%" align="left" class="TBL_BODY"><?=$row1["jenis"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["jumlah"] ?> Kali</td>
  </tr>
  <?
	$tot1=$tot1+$row1["jumlah"] ;
	?>
 <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
  <tr>
    <td colspan="2" align="center" class="TBL_HEAD">TOTAL</td>
    <td class="TBL_HEAD" align="center"><?=$tot1 ?> Kali</td>
  </tr>
</table>
<p>&nbsp;</p>