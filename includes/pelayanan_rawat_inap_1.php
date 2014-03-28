
 <?	
  
 $PID = "pelayanan_rawat_inap";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");	 
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - PELAYANAN RAWAT INAP");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs);
		
	if(!$GLOBALS['print']){
        title_print(""); 
		title_excel("pelayanan_rawat_inap");
		edit_laporan("input_pelayanan_rawat_inap");	
		
	}else {
		
	}

$SQL = "select hierarchy, bangsal, id ".
		"from rs00012 ".
		"where substr(hierarchy,4,6) = '000000' ".
		"and is_group = 'Y' order by bangsal";
   
			$r1 = pg_query($con,$SQL);
			$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}    
	
 

?>

<table CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
  <tr>
    <td class="TBL_HEAD" align="center" rowspan="2">No.</td>
    <td class="TBL_HEAD" align="center" rowspan="2">Jenis Pelayanan</td>
    <td class="TBL_HEAD" align="center" rowspan="2">Pasien Awal Triwulan</td>
    <td class="TBL_HEAD" align="center" rowspan="2">Pasien Masuk</td>
    <td class="TBL_HEAD" align="center" rowspan="2">Pasien Keluar Hidup</td>
    <td class="TBL_HEAD" align="center" colspan="3">Pasien Keluar Mati</td>
    <td class="TBL_HEAD" align="center" rowspan="2">Jumlah Lama Dirawat</td>
    <td class="TBL_HEAD" align="center" rowspan="2">Pasien Akhir Triwulan</td>
    <td class="TBL_HEAD" align="center" rowspan="2">Jumlah Hari Perawatan</td>
    <td class="TBL_HEAD" align="center" colspan="5">Jumlah Hari Perawatan per Kelas</td>
  </tr>
  <tr>
    <td class="TBL_HEAD" align="center">&lt;48 jam</td>
    <td class="TBL_HEAD" align="center">&gt;=48 jam</td>
    <td class="TBL_HEAD" align="center">Jumlah</td>
    <td class="TBL_HEAD" align="center">Kelas Utama</td>
    <td class="TBL_HEAD" align="center">Kelas I</td>
    <td class="TBL_HEAD" align="center">Kelas II</td>
    <td class="TBL_HEAD" align="center">Kelas III</td>
    <td class="TBL_HEAD" align="center">Tanpa Kelas</td>
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
    <td class="TBL_BODY" align="left"><?=$row1["bangsal"] ?></td>
    <td class="TBL_BODY" align="center">0</td>
    <td class="TBL_BODY" align="center">0</td>
    <td class="TBL_BODY" align="center">0</td>
    <td class="TBL_BODY" align="center">0</td>
    <td class="TBL_BODY" align="center">0</td>
    <td class="TBL_BODY" align="center">0</td>
    <td class="TBL_BODY" align="center">0</td>
    <td class="TBL_BODY" align="center">0</td>
    <td class="TBL_BODY" align="center">0</td>
    <td class="TBL_BODY" align="center">0</td>
    <td class="TBL_BODY" align="center">0</td>
    <td class="TBL_BODY" align="center">0</td>
    <td class="TBL_BODY" align="center">0</td>
    <td class="TBL_BODY" align="center">0</td>
  </tr>
  <?
					$tot1=$tot1+$row1["awal_triwulan"] ;
					$tot2=$tot2+$row1["masuk"] ;
					$tot3=$tot3+$row1["keluar_hidup"] ;
					$tot4=$tot4+$row1["kurang_48jam"] ;
					$tot5=$tot5+$row1["lebih_48jam"] ;
					$tot6=$tot6+$row1["jumlah"] ;
					$tot7=$tot7+$row1["lama_dirawat"] ;
					$tot8=$tot8+$row1["akhir_triwulan"] ;
					$tot9=$tot9+$row1["hari_perawatan"] ;
					$tot10=$tot10+$row1["kelas_utama"] ;
					$tot11=$tot11+$row1["kelas_satu"] ;
					$tot12=$tot12+$row1["kelas_dua"] ;
					$tot13=$tot13+$row1["kelas_tiga"] ;
					$tot14=$tot14+$row1["tanpa_kelas"] ;
					
					
					?>
   <?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
  <tr>
    <td class="TBL_HEAD" align="center">&nbsp;</td>
    <td class="TBL_HEAD" align="center">TOTAL</td>
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
  </tr>
</table>
<p>&nbsp;</p>	