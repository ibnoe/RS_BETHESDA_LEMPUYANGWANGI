<?	
  
 $PID = "laboratorium";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");	
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - PEMERIKSAAN LABORATORIUM");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs);   
		
	if(!$GLOBALS['print']){
		title_print("");
		title_excel("laboratorium");
		edit_laporan("input_lab");	
	}else {
		
	}
	
$SQL = "select * from rl100011a order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}   

?>
<p>&nbsp;</p>
<DIV ALIGN=LEFT CLASS=SUBTITLEPRINT><B>A. PATOLOGI KLINIK</B></DIV>
<p>&nbsp;</p>
<table align="center" CLASS=TBL_BORDER WIDTH='75%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr>
        <td class="TBL_HEAD" align="center">NO</td>
        <td class="TBL_HEAD" align="center">JENIS PEMERIKSAAN</td>
        <td class="TBL_HEAD" align="center">SEDERHANA</td>
        <td class="TBL_HEAD" align="center">SEDANG</td>
        <td class="TBL_HEAD" align="center">CANGGIH</td>
        <td class="TBL_HEAD" align="center">TOTAL</td>
    </tr>
	<?	
			$totsederhana=0;
			$totsedang=0;
			$totcanggih=0;
			$tottotal=0;
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
      <td class="TBL_BODY" align="left"><?=$row1["jenis"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["sederhana"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["sedang"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["canggih"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["canggih"]+$row1["sedang"]+$row1["sederhana"] ?></td>
    </tr>
	<? $totsederhana=$totsederhana+$row1["sederhana"] ;
  	   $totsedang=$totsedang+$row1["sedang"] ;
	   $totcanggih=$totcanggih+$row1["canggih"] ;
	   $tottotal=$tottotal+$row1["canggih"]+$row1["sedang"]+$row1["sederhana"] ;
	?>
	<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
    <tr>
      <td colspan="2" align="center" class="TBL_HEAD">TOTAL</td>
      <td class="TBL_HEAD" align="center"><?=$totsederhana ?></td>
      <td class="TBL_HEAD" align="center"><?=$totsedang ?></td>
      <td class="TBL_HEAD" align="center"><?=$totcanggih ?></td>
      <td class="TBL_HEAD" align="center"><?=$tottotal ?></td>
    </tr>
</table>
<p>&nbsp;</p>
<? 
edit_laporan("input_lab_anatomi");	
$SQL = "select * from rl100011b order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}   

?>
<DIV ALIGN=LEFT CLASS=SUBTITLEPRINT><B>B. PATOLOGI ANATOMI</B></DIV>
<p>&nbsp;</p>
<table align="center" class="TBL_BORDER" width='75%' border="1" cellspacing="1" cellpadding="2">
  <tr>
    <td class="TBL_HEAD" align="center">NO</td>
    <td class="TBL_HEAD" align="center">JENIS PEMERIKSAAN</td>
    <td class="TBL_HEAD" align="center">SEDERHANA</td>
    <td class="TBL_HEAD" align="center">SEDANG</td>
    <td class="TBL_HEAD" align="center">CANGGIH</td>
    <td class="TBL_HEAD" align="center">TOTAL</td>
  </tr>
   <?	
			$totsederhana=0;
			$totsedang=0;
			$totcanggih=0;
			$tottotal=0;
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
    <td class="TBL_BODY" align="left"><?=$row1["jenis"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["sederhana"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["sedang"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["canggih"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["canggih"]+$row1["sedang"]+$row1["sederhana"] ?></td>
  </tr>
  <? $totsederhana=$totsederhana+$row1["sederhana"] ;
  	   $totsedang=$totsedang+$row1["sedang"] ;
	   $totcanggih=$totcanggih+$row1["canggih"] ;
	   $tottotal=$tottotal+$row1["canggih"]+$row1["sedang"]+$row1["sederhana"] ;
	?>
	<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
  <tr>
    <td colspan="2" align="center" class="TBL_HEAD">TOTAL</td>
    <td class="TBL_HEAD" align="center"><?=$totsederhana ?></td>
    <td class="TBL_HEAD" align="center"><?=$totsedang ?></td>
    <td class="TBL_HEAD" align="center"><?=$totcanggih ?></td>
    <td class="TBL_HEAD" align="center"><?=$tottotal ?></td>
  </tr>
</table>
<p>&nbsp;</p>
<? 
edit_laporan("input_lab_toksikologi");	
$SQL = "select * from rl100011c order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}   

?>
<DIV ALIGN=LEFT CLASS=SUBTITLEPRINT><B>C. TOKSIKOLOGI</B></DIV>
<p>&nbsp;</p>
<table align="center" class="TBL_BORDER" width='75%' border="1" cellspacing="1" cellpadding="2">
  <tr>
    <td class="TBL_HEAD" align="center">NO</td>
    <td class="TBL_HEAD" align="center">JENIS PEMERIKSAAN</td>
    <td class="TBL_HEAD" align="center">SKRINING</td>
    <td class="TBL_HEAD" align="center">KONFIRMASI</td>
    <td class="TBL_HEAD" align="center">TOTAL</td>
  </tr>
  <?	
			$totskrining=0;
			$totkonfirmasi=0;
			$tottotal=0;
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
    <td class="TBL_BODY" align="left"><?=$row1["jenis"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["skrining"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["konfirmasi"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["skrining"]+$row1["konfirmasi"] ?></td>
  </tr>
  <?   $totskrining=$totskrining+$row1["skrining"] ;
	   $totkonfirmasi=$totkonfirmasi+$row1["konfirmasi"] ;
	   $tottotal=$tottotal+$row1["skrining"]+$row1["konfirmasi"] ;
	?>
	<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
  <tr>
    <td colspan="2" align="center" class="TBL_HEAD">TOTAL</td>
    <td class="TBL_HEAD" align="center"><?=$totskrining ?></td>
    <td class="TBL_HEAD" align="center"><?=$totkonfirmasi ?></td>
    <td class="TBL_HEAD" align="center"><?=$tottotal ?></td>
  </tr>
</table>
<p>&nbsp;</p>