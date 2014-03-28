<?	  
$PID = "kegiatan_rujukan";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - KEGIATAN RUJUKAN");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs); 	  
		
	if(!$GLOBALS['print']){
	 	title_print("");
		title_excel("kegiatan_rujukan");
        edit_laporan("input_rujukan");
	}else {
	
			
	}
	
	$SQL = "select * from rl100024 order by id";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}    
 
?>
<table align="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr>
        <td class="TBL_HEAD" align="center" rowspan="3">No</td>
        <td class="TBL_HEAD" align="center" rowspan="3">Jenis Spesialisasi</td>
        <td class="TBL_HEAD" align="center" colspan="4">24.1. Pengiriman Dokter Ahli Ke Sarana Kesehatan Lain</td>
        <td class="TBL_HEAD" align="center" colspan="3">24.2. Kunjungan Dr Ahli yg Diterima</td>
        <td class="TBL_HEAD" align="center" colspan="9">24.3. Rujukan Pasien</td>
    </tr>
    <tr>
        <td class="TBL_HEAD" align="center" colspan="2">Rumah Sakit</td>
        <td class="TBL_HEAD" align="center" colspan="2">Puskesmas</td>
        <td class="TBL_HEAD" align="center" rowspan="2">Total Kali</td>
        <td class="TBL_HEAD" align="center" rowspan="2">Kunj Dr Ahli Asing</td>
        <td class="TBL_HEAD" align="center" rowspan="2">Total Pasien Yg Dilayani</td>
        <td class="TBL_HEAD" align="center" colspan="6">Rujukan Dari Bawah</td>
        <td class="TBL_HEAD" align="center" colspan="3">Dirujuk Keatas</td>
    </tr>
    <tr>
        <td class="TBL_HEAD" align="center">Total Kali</td>
        <td class="TBL_HEAD" align="center">Total Rumah Sakit</td>
        <td class="TBL_HEAD" align="center">Total Kali</td>
        <td class="TBL_HEAD" align="center">Total Pus-kesmas</td>
        <td class="TBL_HEAD" align="center">Diterima Dari Puskesmas</td>
        <td class="TBL_HEAD" align="center">Diterima Dari Fasilitas Kes. Lain *)</td>
        <td class="TBL_HEAD" align="center">Diterima Dari RS Lain</td>
        <td class="TBL_HEAD" align="center">Dikembalikan ke Puskesmas</td>
        <td class="TBL_HEAD" align="center">Dikembalikan ke Fasilitas Kes.Lain *)</td>
        <td class="TBL_HEAD" align="center">Dikembalikan Ke RS Asal</td>
        <td class="TBL_HEAD" align="center">Pasien Rujukan</td>
        <td class="TBL_HEAD" align="center">Pasien Datang Sendiri</td>
        <td class="TBL_HEAD" align="center">Diterima Kembali</td>
    </tr>
    <tr>
        <td class="TBL_HEAD" align="center">1</td>
        <td class="TBL_HEAD" align="center">2</td>
        <td class="TBL_HEAD" align="center">3</td>
        <td class="TBL_HEAD" align="center">4</td>
        <td class="TBL_HEAD" align="center">5</td>
        <td class="TBL_HEAD" align="center">6</td>
        <td class="TBL_HEAD" align="center">7</td>
        <td class="TBL_HEAD" align="center">8</td>
        <td class="TBL_HEAD" align="center">9</td>
        <td class="TBL_HEAD" align="center">10</td>
        <td class="TBL_HEAD" align="center">11</td>
        <td class="TBL_HEAD" align="center">12</td>
        <td class="TBL_HEAD" align="center">13</td>
        <td class="TBL_HEAD" align="center">14</td>
        <td class="TBL_HEAD" align="center">15</td>
        <td class="TBL_HEAD" align="center">16</td>
        <td class="TBL_HEAD" align="center">17</td>
        <td class="TBL_HEAD" align="center">18</td>
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
      <td class="TBL_BODY" align="center"><?=$row1["l"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["m"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["n"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["o"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["p"] ?></td>
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
					$tot13=$tot13+$row1["m"] ;
					$tot14=$tot14+$row1["n"] ;
					$tot15=$tot15+$row1["o"] ;
					$tot16=$tot16+$row1["p"] ;
					
					
					
					?>
   <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
    <tr>
      <td class="TBL_HEAD" align="center">99</td>
      <td class="TBL_HEAD" align="center"><div align="justify">TOTAL</div></td>
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
      <td class="TBL_HEAD" align="center"><?=$tot11 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot12 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot13 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot14 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot15 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot16 ?></td>
    </tr>
</table>
<p>&nbsp;</p>
