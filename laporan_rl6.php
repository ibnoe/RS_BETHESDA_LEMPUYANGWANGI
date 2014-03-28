<?	  
$PID = "laporan_rl6";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");
require_once("lib/setting.php"); 
subtitle_print("FORMULIR PELAPORAN INFEKSI NOSOKOMIAL");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL6");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs); 	  
		
	if(!$GLOBALS['print']){
	 	title_print("");
		title_excel("laporan_rl6");
        edit_laporan("input_rl6");
	}else {
	
			
	}
	
	$SQL = "select * from rl600006 order by oid";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}    
 
?>
<table align="center" CLASS=TBL_BORDER WIDTH='100%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
    <tr>
        <td class="TBL_HEAD" align="center" rowspan="3">NO URUT </td>
        <td class="TBL_HEAD" align="center" rowspan="3">SPESIALISASI RUANGAN</td>
        <td rowspan="3" align="center" class="TBL_HEAD">PASIEN KELUAR </td>
        <td colspan="18" align="center" class="TBL_HEAD">JENIS INFEKSI NOSOKOMIAL</td>
        <td rowspan="2" colspan="3" align="center" class="TBL_HEAD">LAIN-LAIN</td>
    </tr>
    
    <tr>
        <td class="TBL_HEAD" align="center" colspan="3">ISK</td>
        <td class="TBL_HEAD" align="center" colspan="3">ILO</td>
        <td class="TBL_HEAD" align="center" colspan="3">Pneumonia</td>
        <td class="TBL_HEAD" align="center" colspan="3">Sepsis</td>
        <td class="TBL_HEAD" align="center" colspan="3">Dekubitus</td>
        <td class="TBL_HEAD" align="center" colspan="3">Phlebitis</td>
    </tr>
    <tr>
        <td class="TBL_HEAD" align="center">IN</td>
        <td class="TBL_HEAD" align="center">Pasien Kateter</td>
        <td align="center" class="TBL_HEAD">%</td>
        <td align="center" class="TBL_HEAD">IN</td>
        <td class="TBL_HEAD" align="center">Pasien Operasi</td>
        <td class="TBL_HEAD" align="center">%</td>
        <td class="TBL_HEAD" align="center">IN</td>
        <td class="TBL_HEAD" align="center">Semua Pasien</td>
        <td class="TBL_HEAD" align="center">%</td>
        <td class="TBL_HEAD" align="center">IN</td>
        <td class="TBL_HEAD" align="center">Semua Pasien</td>
        <td align="center" class="TBL_HEAD">%</td>
        <td align="center" class="TBL_HEAD">IN</td>
        <td align="center" class="TBL_HEAD">Semua Pasien</td>
        <td align="center" class="TBL_HEAD">%</td>
        <td align="center" class="TBL_HEAD">IN</td>
        <td align="center" class="TBL_HEAD">Pasien Infus dan Injeksi</td>
        <td align="center" class="TBL_HEAD">%</td>
        <td align="center" class="TBL_HEAD">IN</td>
        <td align="center" class="TBL_HEAD">Pasien Beresiko</td>
        <td align="center" class="TBL_HEAD">%</td>

    </tr>
    <tr>
        <td class="TBL_HEAD" align="center">1</td>
        <td class="TBL_HEAD" align="center">2</td>
        <td align="center" class="TBL_HEAD">3</td>
        <td align="center" class="TBL_HEAD">4</td>
        <td class="TBL_HEAD" align="center"></td>
        <td class="TBL_HEAD" align="center">5</td>
        <td class="TBL_HEAD" align="center">6</td>
        <td class="TBL_HEAD" align="center"></td>
        <td class="TBL_HEAD" align="center">7</td>
        <td class="TBL_HEAD" align="center">8</td>
        <td class="TBL_HEAD" align="center"></td>
        <td align="center" class="TBL_HEAD">9</td>
        <td align="center" class="TBL_HEAD">10</td>
        <td align="center" class="TBL_HEAD"></td>
        <td align="center" class="TBL_HEAD">11</td>
        <td align="center" class="TBL_HEAD">12</td>
        <td align="center" class="TBL_HEAD"></td>
        <td align="center" class="TBL_HEAD">13</td>
        <td align="center" class="TBL_HEAD">14</td>
        <td align="center" class="TBL_HEAD"></td>
        <td align="center" class="TBL_HEAD">15</td>
        <td align="center" class="TBL_HEAD">16</td>
        <td align="center" class="TBL_HEAD"></td>
        <td align="center" class="TBL_HEAD">17</td>

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
                        $tot22= 0;

				
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
      <td class="TBL_BODY" align="center"><?=$row1["ruangan"] ?></td>
      <td align="center" class="TBL_BODY"><?=$row1["pasien_keluar"] ?></td>
      <td align="left" class="TBL_BODY"><?=$row1["in_isk"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["pasien_isk"] ?></td>
      <td class="TBL_BODY" align="center"><?php if ($row1["pasien_keluar"]==0){
                                            echo "0";}
                                            else
                                        $isk=(integer)((($row1["in_isk"]+$row1["pasien_isk"])/$row1["pasien_keluar"])*100);echo $isk; ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["in_ilo"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["operasi_ilo"] ?></td>
      <td class="TBL_BODY" align="center"><?php if ($row1["pasien_keluar"]==0){
                                            echo "0";}
                                            else $ilo=(integer)((($row1["in_ilo"]+$row1["operasi_ilo"])/$row1["pasien_keluar"])*100);echo $ilo;?></td>
      <td class="TBL_BODY" align="center"><?=$row1["in_pneumonia"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["pasien_pneumonia"] ?></td>
      <td class="TBL_BODY" align="center"><?php if ($row1["pasien_keluar"]==0){
                                            echo "0";}
                                            else $pneumonia=(integer)((($row1["in_pneumonia"]+$row1["pasien_pneumonia"])/$row1["pasien_keluar"])*100); echo $pneumonia;?></td>
      <td class="TBL_BODY" align="center"><?=$row1["in_sepsis"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["pasien_sepsis"] ?></td>
      <td class="TBL_BODY" align="center"><?php if ($row1["pasien_keluar"]==0){
                                            echo "0";}
                                            else $sepsis=(integer)((($row1["in_sepsis"]+$row1["pasien_sepsis"])/$row1["pasien_keluar"])*100);echo $sepsis;?></td>
      <td class="TBL_BODY" align="center"><?=$row1["in_dekubitus"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["pasien_dekubitus"] ?></td>
      <td class="TBL_BODY" align="center"><?php if ($row1["pasien_keluar"]==0){
                                            echo "0";}
                                            else $dekubitus=(integer)((($row1["in_dekubitus"]+$row1["pasien_dekubitus"])/$row1["pasien_keluar"])*100);$dekubitus; ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["in_phlebitis"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["pasien_phlebitis"] ?></td>
      <td class="TBL_BODY" align="center"><?php if ($row1["pasien_keluar"]==0){
                                            echo "0";}
                                            else $phlebitis=(integer)((($row1["in_phlebitis"]+$row1["pasien_phlebitis"])/$row1["pasien_keluar"])*100);echo $phlebitis; ?></td>
    <td class="TBL_BODY" align="center"><?=$row1["in_lain"] ?></td>
      <td class="TBL_BODY" align="center"><?=$row1["pasien_lain"] ?></td>
      <td class="TBL_BODY" align="center"><?php if ($row1["pasien_keluar"]==0){
                                            echo "0";}
                                            else $lain=(integer)((($row1["in_lain"]+$row1["pasien_lain"])/$row1["pasien_keluar"])*100); echo $lain;?></td>
    </tr>
	  <?
					$tot1=$tot1+$row1["pasien_keluar"] ;
					$tot2=$tot2+$row1["in_isk"] ;
					$tot3=$tot3+$row1["pasien_isk"] ;
					$tot4=$tot4+$isk ;
					$tot5=$tot5+$row1["in_ilo"] ;
					$tot6=$tot6+$row1["operasi_ilo"] ;
					$tot7=$tot7+$ilo ;
					$tot8=$tot8+$row1["in_pneumonia"] ;
					$tot9=$tot9+$row1["pasien_pneumonia"] ;
					$tot10=$tot10+$pneumonia ;
					$tot11=$tot11+$row1["in_sepsis"] ;
					$tot12=$tot12+$row1["pasien_sepsis"] ;
					$tot13=$tot13+$sepsis;
                                        $tot14=$tot14+$row1["in_dekubitus"] ;
					$tot15=$tot15+$row1["pasien_dekubitus"] ;
					$tot16=$tot16+$dekubitus;
                                        $tot17=$tot17+$row1["in_phlebitis"] ;
					$tot18=$tot18+$row1["pasien_phlebitis"] ;
					$tot19=$tot19+$phlebitis;
                                        $tot20=$tot20+$row1["in_lain"] ;
					$tot21=$tot21+$row1["pasien_phlebitis"] ;
					$tot22=$tot22+$lain;


					
					
					
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
      <td class="TBL_HEAD" align="center"><?=$tot13 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot14 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot15 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot16 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot17 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot18 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot19 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot20 ?></td>
      <td class="TBL_HEAD" align="center"><?=$tot21?></td>
      <td align="center" class="TBL_HEAD"><?=$tot22 ?></td>
    </tr>
</table>
<p>&nbsp;</p>
