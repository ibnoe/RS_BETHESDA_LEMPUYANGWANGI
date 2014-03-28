<?	  
$PID = "laporan_rl2b";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/setting.php"); 
subtitle_print("DATA KEADAAN MORBIDITAS PASIEN RAWAT JALAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL2b");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs); 	  
		
	if(!$GLOBALS['print']){
	 	title_print("");
		title_excel("laporan_rl2b");
        edit_laporan("input_rl2b");
	}else {
	
			
	}
	
	$SQL = "select * from rl200002b order by no_dtd";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}    
 
?>
<table align="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr>
        <td class="TBL_HEAD" align="center" rowspan="2">NO URUT </td>
        <td class="TBL_HEAD" align="center" rowspan="2">NO DTD </td>
        <td rowspan="2" align="center" class="TBL_HEAD">NO DAFTAR TERPERINCI </td>
        <td rowspan="2" align="center" class="TBL_HEAD">GOLONGAN SEBAB-SEBAB SAKIT </td>
        <td colspan="8" align="center" class="TBL_HEAD">PASIEN KELUAR (HIDUP &amp; MATI) MENURUT GOLONGAN UMUR </td>
        <td colspan="2" align="center" class="TBL_HEAD">PASIEN KELUAR (Hidup &amp; Mati) MENURUT SEX </td>
        <td rowspan="2" align="center" class="TBL_HEAD">JUMLAH PASIEN KELUAR (13+14) </td>
        <td rowspan="2" align="center" class="TBL_HEAD">JUMLAH PASIEN KELUAR MATI </td>
    </tr>
    
    <tr>
        <td class="TBL_HEAD" align="center">0-28 HR </td>
        <td class="TBL_HEAD" align="center">28 HR - &lt; 1 TH </td>
        <td class="TBL_HEAD" align="center">1-4 TH </td>
        <td class="TBL_HEAD" align="center">5-14 TH </td>
        <td class="TBL_HEAD" align="center">5-24 TH </td>
        <td class="TBL_HEAD" align="center">25-44 TH </td>
        <td class="TBL_HEAD" align="center">45-64 TH </td>
        <td align="center" class="TBL_HEAD">65+ TH </td>
        <td align="center" class="TBL_HEAD">LK</td>
        <td align="center" class="TBL_HEAD">PR</td>
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
        <td align="center" class="TBL_HEAD">15</td>
        <td align="center" class="TBL_HEAD">16</td>
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
			$tot12= 0;
					
			
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
      <td class="TBL_BODY" align="center"><?=$row1["no_dtd"] ?></td>
      <td align="center" class="TBL_BODY"><?=$row1["no_daftar"] ?></td>
      <td align="left" class="TBL_BODY"><?=$row1["golongan"] ?></td>
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
      <td align="center" class="TBL_BODY"><?=$row1["l"] ?></td>
    </tr>
	  <?
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
					$tot12=$tot12+$row1["l"] ;
					
					
					
					
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
      <td align="center" class="TBL_HEAD">&nbsp;</td>
      <td align="center" class="TBL_HEAD">&nbsp;</td>
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
