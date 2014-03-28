<?	  
$PID = "laporan_rl5";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/setting.php"); 
subtitle_print("DATA PERALATAN MEDIS RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL5");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs); 	  
		
	if(!$GLOBALS['print']){
	 	title_print("");
		title_excel("laporan_rl5");
        edit_laporan("input_rl5");
	}else {
	
			
	}
	
	$SQL = "select * from rl500005 order by nam";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}    
 
?>
<table align="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr>
        <td class="TBL_HEAD" align="center" rowspan="3">NO URUT </td>
        <td class="TBL_HEAD" align="center" rowspan="3">NAMA/JENIS PERALATAN MEDIS</td>
        <td rowspan="3" align="center" class="TBL_HEAD">JUMLAH</td>
        <td colspan="3" align="center" class="TBL_HEAD">UMUR </td>
        <td rowspan="3" align="center" class="TBL_HEAD">KAPASITAS</td>
        <td colspan="3" align="center" class="TBL_HEAD">KONDISI</td>
		<td colspan="2" align="center" class="TBL_HEAD"> IJIN OPERASIONAL</td>
		<td colspan="2" align="center" class="TBL_HEAD"> SERTIFIKAT KALIBRASI</td>
    </tr>
    
    <tr>
        <td class="TBL_HEAD" align="center" rowspan="2" >0 - &lt; 5 TH</td>
        <td class="TBL_HEAD" align="center" rowspan="2">5 - &lt; 10 TH </td>
        <td class="TBL_HEAD" align="center" rowspan="2"> &gt;= 10 TH</td>
        <td class="TBL_HEAD" align="center" rowspan="2"> BAIK </td>
        <td class="TBL_HEAD" align="center" colspan="2">RUSAK</td>
        <td class="TBL_HEAD" align="center" rowspan="2" >ADA</td>
        <td class="TBL_HEAD" align="center" rowspan="2">TIDAK </td>
        <td class="TBL_HEAD" align="center" rowspan="2" >ADA</td>
        <td class="TBL_HEAD" align="center" rowspan="2">TIDAK </td>
    </tr>
	<tr>
        <td class="TBL_HEAD" align="center" >RINGAN</td>
        <td class="TBL_HEAD" align="center">BERAT </td>
     </tr>
	 <tr>
        <td class="TBL_HEAD" align="center">1</td>
        <td class="TBL_HEAD" align="center">2</td>
        <td align="center" class="TBL_HEAD">3</td>
        <td align="center" class="TBL_HEAD">4</td>
        <td class="TBL_HEAD" align="center">5</td>
        <td class="TBL_HEAD" align="center">6</td>
        <td class="TBL_HEAD" align="center">7</td>
        <td class="TBL_HEAD" align="center">8</td>
        <td class="TBL_HEAD" align="center">9</td>
        <td class="TBL_HEAD" align="center">10</td>
        <td class="TBL_HEAD" align="center">11</td>
        <td align="center" class="TBL_HEAD">12</td>
        <td align="center" class="TBL_HEAD">13</td>
        <td align="center" class="TBL_HEAD">14</td>
    </tr>
	 <?	
			$tot1= 0;
			$tot2= 0;
			$tot3= 0;
			$tot4= 0;
			$tot5= 0;
			$tot6= 0;
			$tot7= 0;
			$tot8= 0;
			$tot9= 0;
			$tot10= 0;
			$tot11= 0;
			$tot_jum=0;
					
			
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
      <td class="TBL_BODY" align="center"><?=$row1["nam"] ?></td>
      <td align="center" class="TBL_BODY"><?=$row1["jumlah"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["a"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["b"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["c"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["d"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["e"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["f"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["g"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["h"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["i"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["j"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["k"] ?></td>
          </tr>
	  <?
					$tot_jum=$tot_jum+$row1["jumlah"];
					$tot1=$tot1+$row1["a"] ;
					$tot2=$tot2+$row1["b"] ;
					$tot3=$tot3+$row1["c"] ;
					$tot4=$tot4+$row1["d"] ;
					$tot5=$tot5+$row1["e"] ;
					$tot6=$tot6+$row1["f"] ;
					$tot7=$tot7+$row1["g"] ;
					$tot8=$tot8+$row1["h"] ;
					$tot9=$tot9+$row1["i"] ;
					$tot10=$tot10+$row1["j"] ;
					$tot11=$tot11+$row1["k"] ;
					
					
					
					
					
					?>
   <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
    <tr>
      <td class="TBL_HEAD" align="center">99</td>
      <td class="TBL_HEAD" align="center"><div align="justify">JUMLAH</div></td>
      <td class="TBL_HEAD" align="center"><?=$tot_jum ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot1 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot2 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot3 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot4 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot5 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot6 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot7 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot8 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot9 ?></td>
      <td align="center" class="TBL_HEAD"><?=$tot10 ?></td>      
	  <td align="center" class="TBL_HEAD"><?=$tot11 ?></td>   
	  <td align="center" class="TBL_HEAD"><?=$tot12 ?></td>       
    </tr>
</table>
<p>&nbsp;</p>
