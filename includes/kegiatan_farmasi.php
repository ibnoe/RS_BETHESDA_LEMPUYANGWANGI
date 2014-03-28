<?	
  
 $PID = "kegiatan_farmasi";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");	 
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - KEGIATAN FARMASI RUMAH SAKIT");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs);  
		
	if(!$GLOBALS['print']){
		title_print("");
		title_excel("kegiatan_farmasi");
		edit_laporan("input_farmasi");
	}else {
		
	}
	
$SQL = "select * from rl100012a order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}   
?>
<DIV ALIGN=LEFT CLASS=SUBTITLEPRINT><B>A. PENGADAAN OBAT</B></DIV>
<p>&nbsp;</p>
<table align="center" CLASS=TBL_BORDER WIDTH='75%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr>
        <td class="TBL_HEAD" align="center" rowspan="2">NO</td>
        <td class="TBL_HEAD" align="center" rowspan="2">GOLONGAN OBAT</td>
        <td class="TBL_HEAD" align="center" rowspan="2">JUMLAH ITEM OBAT SESUAI KEBUTUHAN</td>
        <td class="TBL_HEAD" align="center" colspan="2">YANG TERSEDIA DI RUMAH SAKIT</td>
        <td class="TBL_HEAD" align="center" rowspan="2">KETERANGAN</td>
    </tr>
    <tr>
        <td class="TBL_HEAD" align="center">JUMLAH ITEM</td>
        <td class="TBL_HEAD" align="center">% KETERSEDIAAN</td>
    </tr>
<?	
						
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
      <td class="TBL_BODY" align="center"><?=$row1["gol_obat"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["jml_obat"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["jml_item"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["sedia"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["ket"] ?></td>
    </tr>
	<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
</table>
<p>&nbsp;</p>
<DIV ALIGN=LEFT CLASS=SUBTITLEPRINT><B>B. PENULISAN DAN PELAYANAN RESEP (R/)</B></DIV>
<p>&nbsp;</p>
<? edit_laporan("input_resep");
$SQL = "select * from rl100012b order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}   ?>
<table align="center" class="TBL_BORDER" width='75%' border="1" cellspacing="1" cellpadding="2">
  <tr>
    <td rowspan="2" align="center" class="TBL_HEAD">NO</td>
    <td rowspan="2" align="center" class="TBL_HEAD">GOLONGAN OBAT</td>
    <td rowspan="2" align="center" class="TBL_HEAD">RAWAT JALAN </td>
    <td rowspan="2" align="center" class="TBL_HEAD">UGD</td>
    <td rowspan="2" align="center" class="TBL_HEAD">RAWAT INAP </td>
    <td colspan="2" align="center" class="TBL_HEAD">TOTAL</td>
    <td colspan="2" align="center" class="TBL_HEAD">JUMLAH R/ YANG DILAYANI RS </td>
  </tr>
  <tr>
    <td align="center" class="TBL_HEAD">R/</td>
    <td align="center" class="TBL_HEAD">%</td>
    <td align="center" class="TBL_HEAD">R/</td>
    <td align="center" class="TBL_HEAD">%</td>
  </tr>
  <tr>
    <td class="TBL_HEAD" align="center">1</td>
    <td class="TBL_HEAD" align="center">2</td>
    <td class="TBL_HEAD" align="center">3</td>
    <td class="TBL_HEAD" align="center">4</td>
    <td class="TBL_HEAD" align="center">5</td>
    <td align="center" class="TBL_HEAD">6=(3+4+5)</td>
    <td align="center" class="TBL_HEAD">7</td>
    <td class="TBL_HEAD" align="center">8</td>
    <td class="TBL_HEAD" align="center">9=8/6</td>
  </tr>
<?	        $tot1=0;
			$tot2=0;
			$tot3=0;
			$tot4=0;
			$tot5=0;
			$tot6=0;
						
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
    <td class="TBL_BODY" align="center"><?=$row1["gol_obat"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["rawat_jalan"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["ugd"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["rawat_inap"] ?></td>
    <td align="center" class="TBL_BODY"><?=$row1["rawat_jalan"]+$row1["ugd"]+$row1["rawat_inap"] ?></td>
    <td align="center" class="TBL_BODY"><?=$row1["persen_total"] ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["jumlah_resep"] ?></td>
    <td class="TBL_BODY" align="center"><?=($row1["jumlah_resep"])/($row1["rawat_jalan"]+$row1["ugd"]+$row1["rawat_inap"]) ?></td>
  </tr>
  <? $tot1=$tot1+$row1["rawat_jalan"] ;
  	   $tot2=$tot2+$row1["ugd"] ;
	   $tot3=$tot3+$row1["rawat_inap"] ;
	   $tot4=$tot4+$row1["rawat_jalan"]+$row1["ugd"]+$row1["rawat_inap"] ;
	   $tot5=$tot5+$row1["jumlah_resep"] ;
	   $tot6=$tot6+(($row1["jumlah_resep"])/($row1["rawat_jalan"]+$row1["ugd"]+$row1["rawat_inap"])) ;
	?>
 <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
  <tr>
    <td class="TBL_HEAD" align="center">99</td>
    <td class="TBL_HEAD" align="center">TOTAL</td>
    <td class="TBL_HEAD" align="center"><?=$tot1 ?></td>
    <td class="TBL_HEAD" align="center"><?=$tot2 ?></td>
    <td class="TBL_HEAD" align="center"><?=$tot3 ?></td>
    <td align="center" class="TBL_HEAD"><?=$tot4 ?></td>
    <td align="center" class="TBL_HEAD">100%</td>
    <td class="TBL_HEAD" align="center"><?=$tot5 ?></td>
    <td class="TBL_HEAD" align="center"><?=$tot6 ?></td>
  </tr>
</table>
<p>&nbsp;</p>
