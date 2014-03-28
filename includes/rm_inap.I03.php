<?
//heri 23 juli 2007
//udah
$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["catatan_obstetri"];

	if ($_GET['act'] ==  "detail"){
		
			 	// ambil bangsal
			    $id_max = getFromTable("select max(id) from rs00010 where no_reg = '".$_GET["id"]."'");
			    if (!empty($id_max)) {
			    $bangsal = getFromTable("select c.bangsal || ' / ' || e.tdesc ".
			                       "from rs00010 as a ".
			                       "    join rs00012 as b on a.bangsal_id = b.id ".
			                       "    join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' ".
			                       //"    join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' ".
			                       "    join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR' ".
			                       "where a.id = '$id_max'");
			    }
			    //echo $bangsal;
						$sql2 = "select a.id,a.mr_no,a.nama,a.umur,a.tgl_lahir,a.tmp_lahir,a.tanggal_reg,a.status_akhir, ".
								"a.pangkat_gol,a.nrp_nip,a.kesatuan, a.jenis_kelamin ".
								"from rsv_pasien2 a  ".
								"where a.id= '{$_GET['id']}'";			
						
						$r2 = pg_query($con,$sql2);
						$n2 = pg_num_rows($r2);
					    if($n2 > 0) $d2 = pg_fetch_object($r2);
					    pg_free_result($r2);
		    			    
			echo "<DIV>";
			//echo "<br>";

			echo "<table class='TBL_BORDER' border='0' width='100%' cellspacing=1 cellpadding=0>";
			echo "<tr><td class='TBL_BODY' valign=top width='32%'>";
			$f = new ReadOnlyForm();
		    $f->text("Nama","<b>". $d2->nama."</b>");
		    $f->text("Umur",$d2->umur);
			$f->text("Tgl Masuk",$d2->tanggal_reg);
		    $f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='23%'>";
			$f = new ReadOnlyForm();
			$f->text("No.RM","<b>". $d2->mr_no."</b>");
			$f->text("No.Reg", $d2->id);
			$f->text("Seks",$d2->jenis_kelamin);
			$f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='43%'>";
		    $f = new ReadOnlyForm();
		    $f->text("Pangkat / NRP", $d2->pangkat_gol." ".$d2->nrp_nip );
		    $f->text("Kesatuan",$d2->kesatuan);
		    $f->text("Ruang ",$bangsal);
		    $f->execute();
		    //echo "</td></tr></table>"; 
		 		    
		echo "</td></tr><tr><td colspan=2 class='tbl_body'>";
			
			$SQL3 = "select to_char(a.tanggal_reg,'dd mm YYYY  HH:MM')as tgl_periksa,a.vis_3,a.vis_4,a.vis_5,a.vis_6,a.vis_7,a.vis_9,a.vis_10,b.nama as dokter,c.nama as perawat ".
					"from c_visit_ri a ".
					"LEFT JOIN RS00017 b ON CAST(a.vis_2 AS INTEGER) = b.id ".
					"LEFT JOIN RS00017 c ON CAST(a.vis_8 AS INTEGER)= c.id ".
					"where a.no_reg='{$_GET['id']}' and a.id_ri='I03' ";	
					
			$r3 = pg_query($con,$SQL3);
			$n3 = pg_num_rows($r3);
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}
			nav_db2($mulai,$n3,$max_row,"index2.php?p=$PID&sub={$_GET["mPOLI"]}&act=detail&id={$_GET['id']}&mr={$_GET['mr']}","") ;		
					
			$r4 = pg_query($con,$SQL3);
			$d4 = pg_fetch_array($r4);
			
			$f = new ReadOnlyForm();
			$f->text("Dokter/Bidan",$d4["dokter"]);
			//$f->text($visit_ri_catatan_obstetri["vis_1"],$d4["vis_1"]);
			$f->execute();
		echo "</td><td align='center' class='tbl_body'>G P A "; 
		echo "</td></tr><tr><td colspan='3' class='tbl_body'>";
		
		?>			
		
			<TABLE CLASS=TBL_BORDER WIDTH='100%' BORDER=0 CELLSPACING=1 CELLPADDING=2>
			<tr class="TBL_HEAD">     	
				<td class="TBL_HEAD" width="15%" align="center" rowspan="2">Tanggal & Jam</td>
				<td class="TBL_HEAD" align="center" colspan="4">HIS</td>
				<td class="TBL_HEAD" align="center" rowspan="2">Frekuensi D.I.A</td>
				<td class="TBL_HEAD" align="center" rowspan="2">Bidan/Perawat</td>
			</tr>
			<tr class="TBL_HEAD">
				<td class="TBL_HEAD" align="center">Frekuensi</td>
				<td class="TBL_HEAD" align="center">Lamanya</td>	
				<td class="TBL_HEAD" align="center">Kekuatan</td>	
				<td class="TBL_HEAD" align="center">Relaxasi</td>
			</tr>	
			
		<?	
			$i= 1 ;
			$j= 1 ;
			$last_id=1;
			while ($row1 = pg_fetch_array($r3)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
					if (bcmod($i,2)== 0){$class_nya ="TBL_BODY";}			
					?>		
				 	<tr valign="top" class="<?=$class_nya?>" >  
			        	<td class="TBL_BODY" align="center"><?=$row1["vis_9"]." ".$row1["vis_10"] ?> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["vis_3"] ?> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["vis_4"] ?> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["vis_5"] ?> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["vis_6"] ?> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["vis_7"] ?> </font></td>
						<td class="TBL_BODY"><?=$row1["perawat"] ?> </font></td>
					</tr>	
					<?;$j++;
					
				}
				$i++;
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}			
			} 
			echo "<tr><td colspan='7' CLASS='TBL_BODY' height='25' >";
				
				 $rc = pg_query($con, "SELECT COUNT(*) AS CNT FROM c_visit_ri where no_reg='{$_GET['id']}' and id_ri='{$_GET["mPOLI"]}' ");
			            $dc = pg_fetch_object($rc);
			            pg_free_result($rc);
			            $rowCount = $dc->cnt; 
			            $x= 0;
		            if ($rowCount > 0){    
        	   			echo "<b>". ($i-1)." to ".($j-1)." from ".$rowCount ."</b>";
		            }else{
		            	echo "<b>".$x ." to ".$x." from ".$rowCount."</b>";
		            }
			    	if (!$GLOBALS['print']) {
		            	echo "<span style='padding-left: 75%;'>$first_nya | $prev_nya || $next_nya | $last_nya </span>";
		         	} 
                 ?>
            	</TD>
			</tr> 	
			</table>
			<?	
		echo "</td></tr></table>";	
			
		echo "</DIV>";
		
	}else {
		
		include("rm_inap2.php");	
		
	}
}
?>
