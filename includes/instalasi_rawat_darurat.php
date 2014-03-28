<?	
  
 $PID = "instalasi_rawat_darurat";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");	  
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - INSTALASI RAWAT DARURAT");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs); 	
		
	if(!$GLOBALS['print']){
		title_print("");
		title_excel("instalasi_rawat_darurat");	
		edit_laporan("input_ird");	
	}else {
		
	}
	
$SQL = "select * from rl100007 group by  jenis_layanan, pasien_ruj, pasien_non_ruj, lanjut_dirawat, lanjut_dirujuk, lanjut_pulang, mati,id order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}       

?>
<table align="center" CLASS=TBL_BORDER WIDTH='75%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr>
        <td class="TBL_HEAD" align="center" rowspan="2">No</td>
        <td class="TBL_HEAD" align="center" rowspan="2">Jenis Pelayanan</td>
        <td class="TBL_HEAD" align="center" colspan="2">Total Pasien</td>
        <td class="TBL_HEAD" align="center" colspan="3">Tindak Lanjut Pelayanan</td>
        <td class="TBL_HEAD" align="center" rowspan="2">Mati sebelum dirawat</td>
    </tr>
	
    <tr>
        <td class="TBL_HEAD" align="center">Rujukan</td>
        <td class="TBL_HEAD" align="center">Non Rujukan</td>
        <td class="TBL_HEAD" align="center">Dirawat</td>
        <td class="TBL_HEAD" align="center">Dirujuk</td>
        <td class="TBL_HEAD" align="center">Pulang</td>
    </tr>
	<?	    $tot1= 0;
	        $tot2= 0;
			$tot3= 0;
			$tot4= 0;
			$tot5= 0;
			$tot6= 0;
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
      <td class="TBL_BODY" align="center"><?=$row1["pasien_ruj"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["pasien_non_ruj"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["lanjut_dirawat"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["lanjut_dirujuk"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["lanjut_pulang"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["mati"] ?></td>
    </tr>
	<?
	$tot1=$tot1+$row1["pasien_ruj"] ;
	$tot2=$tot2+$row1["pasien_non_ruj"] ;
	$tot3=$tot3+$row1["lanjut_dirawat"] ;
	$tot4=$tot4+$row1["lanjut_dirujuk"] ;
	$tot5=$tot5+$row1["lanjut_pulang"] ;
	$tot6=$tot6+$row1["mati"] ;
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
      <td class="TBL_HEAD" align="center"><?=$tot2 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot3 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot4 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot5 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot6 ?></td>
    </tr>
</table>
<p>&nbsp;</p>