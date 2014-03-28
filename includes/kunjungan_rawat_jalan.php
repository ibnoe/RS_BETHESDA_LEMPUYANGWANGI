  <?	
  
 $PID = "kunjungan_rawat_jalan";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/visit_setting.php");	
require_once("lib/setting.php"); 
subtitle_print("DATA KEGIATAN RUMAH SAKIT");
subtitle_print("Triwulan : ".$set_triwulan);
subtitle_rs("FORMULIR RL1 - KUNJUNGAN RAWAT JALAN");
subtitle_rs($set_header[0]." ".$set_header[1]);
subtitle_rs("No. Kode RS : ".$set_kode_rs);  
	
	if(!$GLOBALS['print']){
		title_print("");
		title_excel("kunjungan_rawat_jalan");	
		edit_laporan("input_kunjungan_rawat_jalan");
	}else {
		
	}
	

   
	
 $SQL = "select * from rl100003 group by no,
  jenis_layanan ,
  kunjungan_baru ,
  kunjungan_ulang order by no";
   
  						$r1 = pg_query($con,$SQL);
						$n1 = pg_num_rows($r1);
					    
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;} 

?>
<TABLE ALIGN="center" CLASS=TBL_BORDER WIDTH='75%' BORDER=1 CELLSPACING=1 CELLPADDING=2>
			<tr class="TBL_HEAD">     	
				<td class="TBL_HEAD" width="6%" align="center">NO</td>
				<td class="TBL_HEAD" width="34%" align="center">JENIS PELAYANAN RAWAT JALAN</td>
				<td width="31%" align="center" class="TBL_HEAD">KUNJUNGAN BARU</td>
				<td width="29%" align="center" class="TBL_HEAD">KUNJUNGAN ULANG</td>
			</tr>
			
	
		<?	
			$totbaru= 0;
			$totulang= 0;
			$i= 1 ;
			$j= 1 ;
			$last_id=1;			
			while ($row1 = pg_fetch_array($r1)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
					$no=$i 	
					?>		
				 	<tr valign="top" class="<?=$class_nya?>" >  
			        	<td class="TBL_BODY" align="center"><?=$no ?> </td>
			        	<td class="TBL_BODY" align="left"><?=$row1["jenis_layanan"] ?> </td>
						<td align="center" class="TBL_BODY"><?=$row1["kunjungan_baru"] ?></td>
						<td align="center" class="TBL_BODY"><?=$row1["kunjungan_ulang"] ?></td>
					</tr>	
					<?
					$totbaru=$totbaru+$row1["kunjungan_baru"] ;
					$totulang=$totulang+$row1["kunjungan_ulang"] ;
					?>
					<?;$j++;					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}		
			} 
			?>
			
					<tr valign="top" class="TBL_HEAD" >  
			        	<td align="center" colspan="2" height="25" valign="middle"> TOTAL </td>
			        	<td align="center" valign="middle"><?=$totbaru ?></td>
						<td align="center" valign="middle"><?=$totulang ?></td>
					</tr>	
			</table>
	<p>&nbsp;</p>	