<?	  
$PID = "cara_pembayaran";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");	
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - CARA PEMBAYARAN");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs);    
		
	if(!$GLOBALS['print']){
	 	title_print("");
		title_excel("cara_pembayaran");	
        edit_laporan("input_cara_pembayaran");
	}else {
				
	}
	
	$SQL = "select * from rl100023 order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}    
 
?>
<table align="center" CLASS=TBL_BORDER WIDTH='75%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
   <tr>
    <td class="TBL_HEAD" align="center" rowspan="2">No</td>
    <td class="TBL_HEAD" align="center" rowspan="2">CARA PEMBAYARAN</td>
    <td class="TBL_HEAD" align="center" colspan="2">Pasien Rawat Inap</td>
    <td class="TBL_HEAD" align="center" rowspan="2">Jumlah Pasien Rawat Jalan</td>
    <td class="TBL_HEAD" align="center" colspan="3">Jumlah Pemeriksaan Pelayanan Langsung</td>
    <td class="TBL_HEAD" align="center" colspan="2">Total Pendapatan (Rp.)</td>
  </tr>
  <tr>
    <td class="TBL_HEAD" align="center">Jumlah Pasien Keluar</td>
    <td class="TBL_HEAD" align="center">Jumlah Lama Dirawat</td>
    <td class="TBL_HEAD" align="center">Laboratorium</td>
    <td class="TBL_HEAD" align="center">Radiologi</td>
    <td class="TBL_HEAD" align="center">Lain-Lain</td>
    <td class="TBL_HEAD" align="center">Seharusnya</td>
    <td class="TBL_HEAD" align="center">Diterima</td>
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
			
			
			
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while ($row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
					$no=$i 	
					?>		
  <tr>
    <td class="TBL_BODY" align="center"><?=$row1["urutan"] ?></td>
    <td class="TBL_BODY" align="left"><?=$row1["cara"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["a"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["b"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["c"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["d"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["e"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["f"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["g"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["h"] ?></td>
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
					
					
					
					?>
   <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
  <tr>
    <td colspan="2" align="center" class="TBL_HEAD"><div align="justify">TOTAL</div></td>
    <td class="TBL_HEAD" align="center"><?=$tot1 ?></td>
    <td class="TBL_HEAD" align="center"><?=$tot2 ?></td>
    <td class="TBL_HEAD" align="center"><?=$tot3 ?></td>
    <td class="TBL_HEAD" align="center"><?=$tot4 ?></td>
    <td class="TBL_HEAD" align="center"><?=$tot5 ?></td>
    <td class="TBL_HEAD" align="center"><?=$tot6 ?></td>
    <td class="TBL_HEAD" align="center"><?=$tot7 ?></td>
    <td class="TBL_HEAD" align="center"><?=$tot8 ?></td>
  </tr>
</table>
<p>&nbsp;</p>
