 <?	
  
 $PID = "pembedahan";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - KEGIATAN PEMBEDAHAN");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs); 		  
		
	if(!$GLOBALS['print']){
		title_print("");
		title_excel("pembedahan");	
		edit_laporan("input_pembedahan");
	}else {
		
	}
	
$SQL = "select * from rl100005 order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}   

?>
<table align="center" CLASS=TBL_BORDER WIDTH='75%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr>
        <td class="TBL_HEAD" align="center" rowspan="2">No</td>
        <td class="TBL_HEAD" align="center" rowspan="2">Spesialisasi</td>
        <td class="TBL_HEAD" align="center" rowspan="2">Total</td>
        <td class="TBL_HEAD" align="center" colspan="2">Khusus</td>
        <td class="TBL_HEAD" align="center" colspan="2">Besar</td>
        <td class="TBL_HEAD" align="center" colspan="2">Sedang</td>
        <td class="TBL_HEAD" align="center" colspan="3">Kecil</td>
    </tr>
    <tr>
        <td class="TBL_HEAD" align="center">Kamar Bedah</td>
        <td class="TBL_HEAD" align="center">Unit Darurat</td>
        <td class="TBL_HEAD" align="center">Kamar Bedah</td>
        <td class="TBL_HEAD" align="center">Unit Darurat</td>
        <td class="TBL_HEAD" align="center">Kamar Bedah</td>
        <td class="TBL_HEAD" align="center">Unit Darurat</td>
        <td class="TBL_HEAD" align="center">Kamar Bedah</td>
        <td class="TBL_HEAD" align="center">Unit Darurat</td>
        <td class="TBL_HEAD" align="center">Poliklinik</td>
    </tr>
		<?	$tot1= 0;
			$tot2= 0;
			$tot3= 0;
			$tot4= 0;
			$tot5= 0;
			$tot6= 0;
			$tot7= 0;
			$tot8= 0;
			$tot9= 0;
			$tot10= 0;
			
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
      <td class="TBL_BODY" align="left"><?=$row1["spesialisasi"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["total"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["khusus_kamar_bedah"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["khusus_unit_darurat"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["besar_kamar_bedah"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["besar_unit_darurat"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["sedang_kamar_bedah"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["sedang_unit_darurat"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["kecil_kamar_bedah"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["kecil_unit_darurat"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["kecil_poli"] ?></td>
    </tr>
	<?
	                $tot1=$tot1+$row1["total"] ;
					$tot2=$tot2+$row1["khusus_kamar_bedah"] ;
					$tot3=$tot3+$row1["khusus_unit_darurat"] ;
					$tot4=$tot4+$row1["besar_kamar_bedah"] ;
					$tot5=$tot5+$row1["besar_unit_darurat"] ;
					$tot6=$tot6+$row1["sedang_kamar_bedah"] ;
					$tot7=$tot7+$row1["sedang_unit_darurat"] ;
					$tot8=$tot8+$row1["kecil_kamar_bedah"] ;
					$tot9=$tot9+$row1["kecil_unit_darurat"] ;
					$tot10=$tot10+$row1["kecil_poli"] ;
					
	?>				
	<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
    <tr>
      <td colspan="2" align="center" class="TBL_HEAD" valign="middle">TOTAL</div></td>
      <td class="TBL_HEAD" align="center"><?=$tot1 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot2 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot3 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot4 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot5 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot6 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot7 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot8 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot9 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot10 ?></td>
    </tr>
</table>
<p>&nbsp;</p>