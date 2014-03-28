<?	  
$PID = "laporan_rl4";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/setting.php"); 
subtitle_print("DATA KETENAGAAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL4");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs); 	  
		
	if(!$GLOBALS['print']){
	 	title_print("");
		title_excel("laporan_rl4");
        edit_laporan("input_rl4");
	}else {
	
			
	}
	
	$SQL = "select * from rl400004 order by no_kode";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}    
subtitle_rs("A.JUMLAH TENAGA KESEHATAN MENURUT JENIS");
subtitle_rs("1. TENAGA MEDIS");  
?>
<table align="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr>
        <td class="TBL_HEAD" align="center" rowspan="3">NO URUT </td>
        <td class="TBL_HEAD" align="center" rowspan="3">NO KODE </td>
        <td rowspan="3" align="center" class="TBL_HEAD" width="30%">KUALIFIKASI PENDIDIKAN</td>
        
        <td colspan="10" align="center" class="TBL_HEAD">PURNA WAKTU (FULL TIME)</td>
        <td colspan="9" align="center" class="TBL_HEAD">PARUH WAKTU (PART TIME) </td>
        <td rowspan="3" align="center" class="TBL_HEAD">HONORERER</td>
        <td rowspan="3" align="center" class="TBL_HEAD">TOTAL</td>
    </tr>
    
    <tr>
        <td class="TBL_HEAD" rowspan="2" align="center">DEPKES</td>
        <td class="TBL_HEAD" colspan="2" align="center">PEMDA</td>
        <td class="TBL_HEAD" rowspan="2" align="center">DEPDIKNAS </td>
        <td class="TBL_HEAD" rowspan="2" align="center">TNI/POLRI</td>
        <td class="TBL_HEAD" rowspan="2" align="center">DEP LAIN/BUMN</td>
        <td class="TBL_HEAD" rowspan="2" align="center">PTT</td>
        <td class="TBL_HEAD" rowspan="2" align="center">SWASTA</td>
        <td align="center" rowspan="2" class="TBL_HEAD">KONTRAK </td>
        <td align="center" rowspan="2" class="TBL_HEAD">SUBTOTAL</td>
		<td class="TBL_HEAD" rowspan="2" align="center">DEPKES</td>
        <td class="TBL_HEAD" colspan="2" align="center">PEMDA</td>
        <td class="TBL_HEAD" rowspan="2" align="center">DEPDIKNAS </td>
        <td class="TBL_HEAD" rowspan="2" align="center">TNI/POLRI</td>
        <td class="TBL_HEAD" rowspan="2" align="center">DEP LAIN/BUMN</td>
        <td class="TBL_HEAD" rowspan="2" align="center">PTT</td>
        <td class="TBL_HEAD" rowspan="2" align="center">SWASTA</td>
        <td align="center" rowspan="2" class="TBL_HEAD">SUBTOTAL</td>
    </tr>
	<tr>
		<td class="TBL_HEAD" align="center">PROP</td>
		<td class="TBL_HEAD" align="center">KAB KOTA</td>
		<td class="TBL_HEAD" align="center">PROP</td>
		<td class="TBL_HEAD" align="center">KAB KOTA</td>
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
		<td align="center" class="TBL_HEAD">17</td>
		<td align="center" class="TBL_HEAD">18</td>
		<td align="center" class="TBL_HEAD">19</td>
		<td align="center" class="TBL_HEAD">20</td>
		<td align="center" class="TBL_HEAD">21</td>
		<td align="center" class="TBL_HEAD">22</td>
		<td align="center" class="TBL_HEAD">23</td>
		<td align="center" class="TBL_HEAD">24</td>
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
			$tot13= 0;
			$tot14= 0;
			$tot15= 0;
			$tot16= 0;
			$tot17= 0;
			$tot18= 0;
			$tot19= 0;
			$tot20= 0;
			$tot21= 0;
					
			
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while ($row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
					$no=$i 	;
					$subtotal1=$row1["purna_depkes"]+$row1["purna_kab_kota"]+$row1["purna_depdiknas"]+$row1["purna_tni"]+$row1["purna_bumn"]+$row1["purna_ptt"]+$row1["purna_kontrak"];
					$subtotal2=$row1["paruh_depkes"]+$row1["paruh_kab_kota"]+$row1["paruh_depdiknas"]+$row1["paruh_tni"]+$row1["paruh_bumn"]+$row1["paruh_ptt"];
					$total=$row1["honorer"]+$subtotal1+$subtotal2;
					?>		
    <tr>
      <td class="TBL_BODY" align="center"><?=$no ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["no_kode"] ?></td>
      <td align="center" class="TBL_BODY"><?=$row1["kualifikasi"] ?></td>
      <td align="center" class="TBL_BODY"><?=$row1["purna_depkes"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["purna_prop"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["purna_kab_kota"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["purna_depdiknas"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["purna_tni"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["purna_bumn"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["purna_ptt"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["purna_swasta"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["purna_kontrak"] ?></td>
      <td class="TBL_BODY" align="center"><?=$subtotal1?></td>
      <td align="left" class="TBL_BODY"><?=$row1["paruh_depkes"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["paruh_prop"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["paruh_kab_kota"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["paruh_depdiknas"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["paruh_tni"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["paruh_bumn"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["paruh_ptt"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["paruh_swasta"] ?></td>
      <td class="TBL_BODY" align="center"><?=$subtotal2 ?></td>
	  <td class="TBL_BODY" align="center"><?=$row1["honorer"] ?></td>
	  <td class="TBL_BODY" align="center"><?=$total ?></td>
    </tr>
	  <?
					$tot1=$tot1+$row1["purna_depkes"] ;
					$tot2=$tot2+$row1["purna_prop"] ;
					$tot3=$tot3+$row1["purna_kab_kota"] ;
					$tot4=$tot4+$row1["purna_depdiknas"] ;
					$tot5=$tot5+$row1["purna_tni"] ;
					$tot6=$tot6+$row1["purna_bumn"] ;
					$tot7=$tot7+$row1["purna_ptt"] ;
					$tot8=$tot8+$row1["purna_swasta"] ;
					$tot9=$tot9+$row1["purna_kontrak"] ;
					$tot10=$tot10+$subtotal1;
					$tot11=$tot11+$row1["paruh_depkes"] ;
					$tot12=$tot12+$row1["paruh_prop"] ;
					$tot13=$tot13+$row1["paruh_kab_kota"] ;
					$tot14=$tot14+$row1["paruh_depdiknas"] ;
					$tot15=$tot15+$row1["paruh_tni"] ;
					$tot16=$tot16+$row1["paruh_bumn"] ;
					$tot17=$tot17+$row1["paruh_ptt"] ;
					$tot18=$tot18+$row1["paruh_swasta"];
					$tot19=$tot19+$subtotal2;
					$tot20=$tot20+$row1["honorer"];
					$tot21=$tot21+$total;
					
					
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
	  <td align="center" class="TBL_HEAD"><?=$tot13 ?></td> 
	  <td align="center" class="TBL_HEAD"><?=$tot14 ?></td> 
	  <td align="center" class="TBL_HEAD"><?=$tot15 ?></td> 
	  <td align="center" class="TBL_HEAD"><?=$tot16 ?></td> 
	  <td align="center" class="TBL_HEAD"><?=$tot17 ?></td> 
	  <td align="center" class="TBL_HEAD"><?=$tot18 ?></td> 
	  <td align="center" class="TBL_HEAD"><?=$tot19 ?></td> 
	  <td align="center" class="TBL_HEAD"><?=$tot20 ?></td> 
	  <td align="center" class="TBL_HEAD"><?=$tot21 ?></td>       
    </tr>
</table>
<p>&nbsp;</p>
