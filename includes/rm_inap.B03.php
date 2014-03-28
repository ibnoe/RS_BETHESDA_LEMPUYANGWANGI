<?
//heri 23 juli 2007

$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["grafik_bayi"];

	if ($_GET['act'] ==  "detail"){
		
			$sql = "select a.*,b.nama,c.tdesc as rujukan 
					from c_visit_ri a 
					left join rs00017 b on CAST(a.vis_1 AS INTEGER) = b.id 
					left join rs00001 c on a.id_rujukan=CAST (c.tc AS NUMERIC) and c.tt='LYN' 
					where a.no_reg='{$_GET['id']}' and a.id_ri='B03' ";
			$r = pg_query($con,$sql);
			$n = pg_num_rows($r);
		    if($n > 0) $d = pg_fetch_array($r);
		    pg_free_result($r);
		
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
			echo "<tr><td class='TBL_BODY' valign=top width='34%'>";
			$f = new ReadOnlyForm();
		    $f->text("Nama","<b>". $d2->nama."</b>");
		    $f->text("Umur",$d2->umur);
			$f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='33%'>";
			$f = new ReadOnlyForm();
			$f->text("No.RM bayi","<b>". $d2->mr_no."</b>");			
			$f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='33%'>";
		    $f = new ReadOnlyForm();
		    $f->text("Jenis Kelamin Bayi ",$d2->jenis_kelamin);
		    $f->execute();
		    echo "</td></tr>"; 

		    echo "<tr><td class='TBL_BODY' valign=top width='34%'>";
			$f = new ReadOnlyForm();
		    $f->text("Nama Ibu","<b>". $d2->nama."</b>");
		    $f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='33%'>";
			$f = new ReadOnlyForm();
			$f->text("No.RM Ibu","<b>". $d2->mr_no."</b>");			
			$f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='33%'>";
		    $f = new ReadOnlyForm();
		    $f->text("Nama ayah ",$d2->jenis_kelamin);
		    $f->execute();
		    echo "</td></tr>"; 	    
								    
				echo "\n<script language='JavaScript'>\n";
			    echo "function grafik() {\n";
			    echo "sWin = window.open('grafik/grafik_bayi.php?id={$_GET['id']}&p={$_GET["mPOLI"]}', 'xWin', 'top=0,left=0,width=700,height=600,menubar=no,scrollbars=yes');\n";
			    echo "sWin.focus();\n";
			    echo "}\n";
			    echo "</script>\n";
			        
        echo "<tr><td class='tbl_body' colspan='3' align='center' height='30'><A HREF='javascript:grafik()'>Lihat Grafik Bayi</a></td></tr>";
        echo "<tr><td class='tbl_body' colspan='3'>";
  				
        	 $sql2 = "SELECT vis_2, vis_3, vis_4, vis_5, vis_6 ". 
					   	"FROM c_visit_ri ".
					  	"WHERE no_reg = '".$_GET["id"]."' AND id_ri = '{$_GET["mPOLI"]}' ";
					
			$r2=pg_query($con,$sql2);
			$n2 = pg_num_rows($r2);
			$max_row= 30 ;
			$mulai = 1 ;
			
			
		?>
					<TABLE CLASS=TBL_BORDER WIDTH='100%' BORDER=0 CELLSPACING=1 CELLPADDING=2>  
						
						<tr class="TBL_HEAD" >
							<td align="center">Tanggal</td>
							<td align="center">Jam</td>
							<td align="center">Berat Badan</td>
							<td align="center">Suhu</td>
														
						</tr>
								
				<?	
					$i= 1 ;
					$j= 1 ;
					$last_id=1;			
					while ($row1 = pg_fetch_array($r2)){
						if (($j<=$max_row) AND ($i >= $mulai)){
							$class_nya = "TBL_BODY" ;
								
				?>		
						 	<tr valign="top" class="TBL_BODY" align="center" >  
					        	<td class="TBL_BODY"><?=$row1["vis_2"] ?> </font></td>
								<td class="TBL_BODY"><?=$row1["vis_3"] ?> </font></td>
								<td class="TBL_BODY"><?=$row1["vis_4"] ?> </font></td>
								<td class="TBL_BODY"><?=$row1["vis_5"] ?> </font></td>
							
								
							</tr>	
						<?;$j++;					
						}
						$i++;
							
					} ?>
					<tr><td colspan="16" CLASS=TBL_BODY height="25" >
						
						<? $rc = pg_query($con, "SELECT COUNT(*) AS CNT FROM c_visit_ri where no_reg='{$_GET['id']}' and id_ri='B03' ");
					            $dc = pg_fetch_object($rc);
					            pg_free_result($rc);
					            $rowCount = $dc->cnt; 
					            $x= 0;
					            if ($rowCount > 0){    
		            	   			echo "<b>". ($i-1)." to ".($j-1)." from ".$rowCount ."</b>";
					            }else{
					            	echo "<b>".$x ." to ".$x." from ".$rowCount."</b>";
					            }
					            	        
		                 ?>
		                 
		            	</TD>            	
					</tr> 	
				</table>
				<?	
		 echo "</td></tr><tr><td class='tbl_body' colspan='3'>";
  				
        	 $sql5 = "SELECT vis_2,vis_6, vis_7, vis_8, vis_9, vis_10,vis_11 ".
        	 			"FROM c_visit_ri ".
					  	"WHERE no_reg = '".$_GET["id"]."' AND id_ri = 'B03' ";
			
			$r5=pg_query($con,$sql5);
			$n5 = pg_num_rows($r5);
			$max_row= 10 ;
			$mulai = 1 ;
			
			
		?><br>
			<TABLE CLASS=TBL_BORDER WIDTH='100%' BORDER=0 CELLSPACING=1 CELLPADDING=2>  
				
				<tr class="TBL_HEAD" >
					<td align="center">Tanggal</td>
					<td align="center">Makanan/Minuman</td>
					<td align="center">Defakasi</td>							
					<td align="center">Miksi</td>							
					<td align="center">Tidur</td>							
					<td align="center">Tangis</td>							
					<td align="center">Nafas</td>							
				</tr>
										
						<?	
							$i= 1 ;
							$j= 1 ;
							$last_id=1;			
							while ($d5 = pg_fetch_array($r5)){
								if (($j<=$max_row) AND ($i >= $mulai)){
									$class_nya = "TBL_BODY" ;
										
						?>		
								 	<tr valign="top" class="TBL_BODY" align="center" >  
							        	<td class="TBL_BODY"><?=$d5["vis_2"] ?> </font></td>
							        	<td class="TBL_BODY"><?=$d5["vis_6"] ?> </font></td>
										<td class="TBL_BODY"><?=$d5["vis_7"] ?> </font></td>
										<td class="TBL_BODY"><?=$d5["vis_8"] ?> </font></td>
										<td class="TBL_BODY"><?=$d5["vis_9"] ?> </font></td>
										<td class="TBL_BODY"><?=$d5["vis_10"] ?> </font></td>
										<td class="TBL_BODY"><?=$d5["vis_11"] ?> </font></td>
										
									</tr>	
								<?;$j++;					
								}
								$i++;
									
							} ?>
				<tr><td colspan="16" CLASS=TBL_BODY height="25" >
				
				<? $rc = pg_query($con, "SELECT COUNT(*) AS CNT FROM c_visit_ri where no_reg='{$_GET['id']}' and id_ri='B03' ");
			            $dc = pg_fetch_object($rc);
			            pg_free_result($rc);
			            $rowCount = $dc->cnt; 
			            $x= 0;
			            if ($rowCount > 0){    
            	   			echo "<b>". ($i-1)." to ".($j-1)." from ".$rowCount ."</b>";
			            }else{
			            	echo "<b>".$x ." to ".$x." from ".$rowCount."</b>";
			            }
			            	        
                 ?>
                 
	            	</TD>            	
				</tr> 	
			</table>
<?	
		echo "</td></tr><tr><td class='tbl_body' colspan='3'>";
  				
        	 $sql3 = "SELECT vis_2,vis_12, vis_13,vis_14, vis_15, vis_16, vis_17, vis_18,vis_19, vis_20 ". 
					   	"FROM c_visit_ri ".
					  	"WHERE no_reg = '".$_GET["id"]."' AND id_ri = 'B03' ";
					
			$r8=pg_query($con,$sql3);
			$d8 = pg_fetch_array($r8);			
					  	
			$r3=pg_query($con,$sql3);
			$n3 = pg_num_rows($r3);
			$max_row= 25 ;
			$mulai = 1 ;
			
			
		?><br>
			<TABLE CLASS=TBL_BORDER WIDTH='100%' BORDER=0 CELLSPACING=1 CELLPADDING=2>  
				
				<tr class="TBL_HEAD" >
					<td align="center" rowspan="2">Tanggal</td>
					<td align="center" colspan="4">Catatan (Obat-obatan)</td>							
					<td align="center" rowspan="2">Tindakan Khusus</td>							
					
				</tr>
				<tr class="TBL_HEAD" >
					<td align="center"><?=$d8["vis_17"]?>&nbsp;</td>							
					<td align="center"><?=$d8["vis_18"]?>&nbsp;</td>							
					<td align="center"><?=$d8["vis_19"]?>&nbsp;</td>							
					<td align="center"><?=$d8["vis_20"]?>&nbsp;</td>							
					
				</tr>						
						<?	
							$i= 1 ;
							$j= 1 ;
							$last_id=1;			
							while ($row1 = pg_fetch_array($r3)){
								if (($j<=$max_row) AND ($i >= $mulai)){
									$class_nya = "TBL_BODY" ;
										
						?>		
								 	<tr valign="top" class="TBL_BODY" align="center" >  
							        	<td class="TBL_BODY"><?=$row1["vis_2"] ?> </font></td>
										<td class="TBL_BODY"><?=$row1["vis_12"] ?> </font></td>
										<td class="TBL_BODY"><?=$row1["vis_13"] ?> </font></td>
										<td class="TBL_BODY"><?=$row1["vis_14"] ?> </font></td>
										<td class="TBL_BODY"><?=$row1["vis_15"] ?> </font></td>
										<td class="TBL_BODY" align="left"><?=$row1["vis_16"] ?> </font></td>
										
									</tr>	
								<?;$j++;					
								}
								$i++;
									
							} ?>
					<tr><td colspan="16" CLASS=TBL_BODY height="25" >
				
				<? $rc = pg_query($con, "SELECT COUNT(*) AS CNT FROM c_visit_ri where no_reg='{$_GET['id']}' and id_ri='B03' ");
			            $dc = pg_fetch_object($rc);
			            pg_free_result($rc);
			            $rowCount = $dc->cnt; 
			            $x= 0;
			            if ($rowCount > 0){    
            	   			echo "<b>". ($i-1)." to ".($j-1)." from ".$rowCount ."</b>";
			            }else{
			            	echo "<b>".$x ." to ".$x." from ".$rowCount."</b>";
			            }
			            	        
                 ?>
                 
		            	</TD>            	
					</tr> 	
				</table>
<?	
        
        
		echo "</td></tr></table>";
		
	}else {
		
		include("rm_inap2.php");	
		
	}
}
?>
