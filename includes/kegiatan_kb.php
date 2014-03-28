<?	
  
 $PID = "kegiatan_kb";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - KEGIATAN KELUARGA BERENCANA");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs); 	  
		
	if(!$GLOBALS['print']){
		title_print("");
		title_excel("kegiatan_kb");
		edit_laporan("input_kegiatan_kb");
	}else {
		
	}
	
$SQL = "select * from rl100014 order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;} 

?>
<table align="center" CLASS=TBL_BORDER WIDTH='75%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
  <tr>
    <td class="TBL_HEAD" align="center" rowspan="2">No</td>
    <td class="TBL_HEAD" align="center" rowspan="2">METODA</td>
    <td class="TBL_HEAD" align="center" colspan="4">Peserta KB Baru</td>
    <td class="TBL_HEAD" align="center" rowspan="2">Kunjungan Ulang</td>
    <td class="TBL_HEAD" align="center" colspan="2">Keluhan Efek Samping</td>
  </tr>
  <tr>
    <td class="TBL_HEAD" align="center" >Bukan Rujukan</td>
    <td class="TBL_HEAD" align="center" >Rujukan R. Inap</td>
    <td class="TBL_HEAD" align="center">Rujukan R. Jalan</td>
    <td class="TBL_HEAD" align="center">Total</td>
    <td class="TBL_HEAD" align="center">Jumlah</td>
    <td class="TBL_HEAD" align="center">Dirujuk Keatas</td>
  </tr>
  <?	
			$tot1= 0;
			$tot2= 0;
			$tot3= 0;
			$tot4= 0;
			$tot5= 0;
			$tot6= 0;
			$tot7= 0;
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
    <td class="TBL_BODY" align="left"><?=$row1["metoda"] ?></td>
    <td class="TBL_BODY" align="center" ><?=$row1["bukan_rujuk"] ?></td>
    <td class="TBL_BODY" align="center" ><?=$row1["ruj_rawat_inap"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["ruj_rawat_jalan"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["bukan_rujuk"]+$row1["ruj_rawat_inap"]+$row1["ruj_rawat_jalan"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["kunjungan_ulang"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["jumlah"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["dirujuk_keatas"] ?></td>
  </tr>
  <?
	                $tot1=$tot1+$row1["bukan_rujuk"] ;
					$tot2=$tot2+$row1["ruj_rawat_inap"] ;
					$tot3=$tot3+$row1["ruj_rawat_jalan"] ;
					$tot4=$tot4+$row1["total"] ;
					$tot5=$tot5+$row1["kunjungan_ulang"] ;
					$tot6=$tot6+$row1["jumlah"] ;
					$tot7=$tot7+$row1["dirujuk_keatas"] ;
					
					
	?>				
  <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
  <tr>
    <td colspan="2" align="center" class="TBL_HEAD">TOTAL</td>
    <td class="TBL_HEAD" align="center" ><?=$tot1 ?></td>
    <td class="TBL_HEAD" align="center" ><?=$tot2 ?></td>
    <td class="TBL_HEAD" align="center"><?=$tot3 ?></td>
    <td class="TBL_HEAD" align="center"><?=$tot4 ?></td>
    <td class="TBL_HEAD" align="center"><?=$tot5 ?></td>
    <td class="TBL_HEAD" align="center"><?=$tot6 ?></td>
    <td class="TBL_HEAD" align="center"><?=$tot7 ?></td>
  </tr>
</table>
<p>&nbsp;</p>
