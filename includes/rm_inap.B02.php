<?
//heri 23 juli 2007

$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["grafik_ibu"];

	if ($_GET['act'] ==  "detail"){
		
			$sql = "select a.vis_1,b.nama,c.tdesc as rujukan 
					from c_visit_ri a 
					left join rs00017 b on CAST(a.id_dokter AS INTEGER) = b.id 
					left join rs00001 c on a.id_rujukan=CAST(c.tc AS NUMERIC) and c.tt='LYN' 
					where a.no_reg='{$_GET['id']}' and a.id_ri='B02'";
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
			echo "<tr><td class='TBL_BODY' valign=top width='32%'>";
			$f = new ReadOnlyForm();
		    $f->text("Nama","<b>". $d2->nama."</b>");
		    $f->text("Umur",$d2->umur);
			$f->text("Diagnosis",$d["vis_1"]);
		    $f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='23%'>";
			$f = new ReadOnlyForm();
			$f->text("No.RM","<b>". $d2->mr_no."</b>");
			$f->text("No.Reg",$d2->id);
			$f->text("Seks",$d2->jenis_kelamin);
			$f->execute();
		    echo "</td><td valign=top  class='tbl_body' align=left width='43%'>";
		    $f = new ReadOnlyForm();
		    $f->text("Pangkat / NRP", $d2->pangkat_gol." ".$d2->nrp_nip );
		    $f->text("Kesatuan",$d2->kesatuan);
		    $f->text("Ruang ",$bangsal);
		    $f->execute();
		    echo "</td></tr>"; 			    
								    
				echo "\n<script language='JavaScript'>\n";
			    echo "function grafik() {\n";
			    echo "sWin = window.open('grafik/grafik_ibu.php?id={$_GET['id']}&p={$_GET["mPOLI"]}', 'xWin', 'top=0,left=0,width=700,height=600,menubar=no,scrollbars=yes');\n";
			    echo "sWin.focus();\n";
			    echo "}\n";
			    echo "</script>\n";
			        
        echo "<tr><td class='tbl_body' colspan='3' align='center' height='30'><A HREF='javascript:grafik()'>Lihat Grafik Ibu</a></td></tr>";
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
					<td align="center">Pernafasan</td>
					<td align="center">Nadi</td>
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
									<td class="TBL_BODY"><?=$row1["vis_6"] ?> </font></td>
									
								</tr>	
							<?;$j++;					
							}
							$i++;
								
						} ?>
				<tr><td colspan="16" CLASS=TBL_BODY height="25" >
				
				<? $rc = pg_query($con, "SELECT COUNT(*) AS CNT FROM c_visit_ri where no_reg='{$_GET['id']}' and id_ri='{$_GET["mPOLI"]}' ");
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
			
				$sql6 = "SELECT vis_2, vis_7, vis_8, vis_9, vis_10, vis_11,vis_12, vis_13, vis_14, vis_15, vis_16 ". 
					   	"FROM c_visit_ri ".
					  	"WHERE no_reg = '".$_GET["id"]."' AND id_ri = '{$_GET["mPOLI"]}' ";
					  	
				$sql7 = "SELECT vis_39, vis_40, vis_41, vis_42, vis_43,vis_44, vis_45, vis_46, vis_47, vis_48 ". 
					   	"FROM c_visit_ri ".
					  	"WHERE no_reg = '".$_GET["id"]."' AND id_ri = '{$_GET["mPOLI"]}' ";
			$r7=pg_query($con,$sql7);
			$d7 = pg_fetch_array($r7);			
			
			$r6=pg_query($con,$sql6);
			$n2 = pg_num_rows($r6);
			$max_row= 25 ;
			$mulai = 1 ;
			
			?>
			<br>
			<TABLE CLASS=TBL_BORDER WIDTH='100%' BORDER=0 CELLSPACING=1 CELLPADDING=2>  
				
				<tr class="TBL_HEAD" >
					<td align="center" rowspan="2">Tanggal</td>
					<td align="center" colspan="5">Medikasi</td>
					<td align="center" colspan="5">Dosis</td>					
				</tr>
				<tr class="TBL_HEAD" >
					<td align="center"><?=$d7["vis_39"]?>&nbsp;</td>
					<td align="center"><?=$d7["vis_40"]?>&nbsp;</td>
					<td align="center"><?=$d7["vis_41"]?>&nbsp;</td>
					<td align="center"><?=$d7["vis_42"]?>&nbsp;</td>
					<td align="center"><?=$d7["vis_43"]?>&nbsp;</td>
					<td align="center"><?=$d7["vis_44"]?>&nbsp;</td>
					<td align="center"><?=$d7["vis_45"]?>&nbsp;</td>
					<td align="center"><?=$d7["vis_46"]?>&nbsp;</td>
					<td align="center"><?=$d7["vis_47"]?>&nbsp;</td>
					<td align="center"><?=$d7["vis_48"]?>&nbsp;</td>
					
				</tr>		
					<?	
						$i= 1 ;
						$j= 1 ;
						$last_id=1;			
						while ($row1 = pg_fetch_array($r6)){
							if (($j<=$max_row) AND ($i >= $mulai)){
								$class_nya = "TBL_BODY" ;
									
					?>		
							 	<tr valign="top" class="TBL_BODY" align="center" >  
						        	<td class="TBL_BODY"><?=$row1["vis_2"] ?> </font></td>
									<td class="TBL_BODY"><?=$row1["vis_7"] ?> </font></td>
									<td class="TBL_BODY"><?=$row1["vis_8"] ?> </font></td>
									<td class="TBL_BODY"><?=$row1["vis_9"] ?> </font></td>
									<td class="TBL_BODY"><?=$row1["vis_10"] ?> </font></td>
									<td class="TBL_BODY"><?=$row1["vis_11"] ?> </font></td>
									<td class="TBL_BODY"><?=$row1["vis_12"] ?> </font></td>
									<td class="TBL_BODY"><?=$row1["vis_13"] ?> </font></td>
									<td class="TBL_BODY"><?=$row1["vis_14"] ?> </font></td>
									<td class="TBL_BODY"><?=$row1["vis_15"] ?> </font></td>
									<td class="TBL_BODY"><?=$row1["vis_16"] ?> </font></td>
									
								</tr>	
							<?;$j++;					
							}
							$i++;
								
						} ?>
				<tr><td colspan="16" CLASS=TBL_BODY height="25" >
				
				<? $rc = pg_query($con, "SELECT COUNT(*) AS CNT FROM c_visit_ri where no_reg='{$_GET['id']}' and id_ri='{$_GET["mPOLI"]}' ");
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
  				
        	 $sql3 = "SELECT vis_2,vis_17, vis_18, vis_19, vis_20, vis_21,vis_22, vis_23, vis_24, vis_25, vis_26,vis_27 ". 
					   	"FROM c_visit_ri ".
					  	"WHERE no_reg = '".$_GET["id"]."' AND id_ri = '{$_GET["mPOLI"]}' ";
					
			$r3=pg_query($con,$sql3);
			$n3 = pg_num_rows($r3);
			$max_row= 10 ;
			$mulai = 1 ;
			
			
		?><br>
			<TABLE CLASS=TBL_BORDER WIDTH='100%' BORDER=0 CELLSPACING=1 CELLPADDING=2>  
				
				<tr class="TBL_HEAD" >
					<td align="center">Tanggal</td>
					<td align="center">Keadaan<br> Umum</td>
					<td align="center">Perut</td>
					<td align="center">Buah Dada/<br>Laktasi</td>
					<td align="center">Luka<br> Pembedahan</td>							
					<td align="center">Fundus Uteri</td>							
					<td align="center">Kontraksi</td>							
					<td align="center">Perinium</td>							
					<td align="center">Lochia</td>							
					<td align="center">Flatus</td>							
					<td align="center">Miksi</td>							
					<td align="center">Defakasi</td>							
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
										<td class="TBL_BODY"><?=$row1["vis_17"] ?> </font></td>
										<td class="TBL_BODY"><?=$row1["vis_18"] ?> </font></td>
										<td class="TBL_BODY"><?=$row1["vis_19"] ?> </font></td>
										<td class="TBL_BODY"><?=$row1["vis_20"] ?> </font></td>
										<td class="TBL_BODY"><?=$row1["vis_21"] ?> </font></td>
										<td class="TBL_BODY"><?=$row1["vis_22"] ?> </font></td>
										<td class="TBL_BODY"><?=$row1["vis_23"] ?> </font></td>
										<td class="TBL_BODY"><?=$row1["vis_24"] ?> </font></td>
										<td class="TBL_BODY"><?=$row1["vis_25"] ?> </font></td>
										<td class="TBL_BODY"><?=$row1["vis_26"] ?> </font></td>
										<td class="TBL_BODY"><?=$row1["vis_27"] ?> </font></td>
										
									</tr>	
								<?;$j++;					
								}
								$i++;
									
							} ?>
					<tr><td colspan="16" CLASS=TBL_BODY height="25" >
				
				<? $rc = pg_query($con, "SELECT COUNT(*) AS CNT FROM c_visit_ri where no_reg='{$_GET['id']}' and id_ri='{$_GET["mPOLI"]}' ");
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
  				
        	 $sql4 = "SELECT vis_2,vis_28, vis_29, vis_30, vis_31, vis_32,vis_33, vis_34, vis_35, vis_36, vis_37 ".
        	 			", vis_49, vis_50, vis_51, vis_52, vis_53 ". 
					   	"FROM c_visit_ri ".
					  	"WHERE no_reg = '".$_GET["id"]."' AND id_ri = '{$_GET["mPOLI"]}' ";
					
			$r4=pg_query($con,$sql4);
			$d4 = pg_fetch_object($r4);			
			
			$r5=pg_query($con,$sql4);
			$n5 = pg_num_rows($r5);
			$max_row= 10 ;
			$mulai = 1 ;
			
			
		?><br>
			<TABLE CLASS=TBL_BORDER WIDTH='100%' BORDER=0 CELLSPACING=1 CELLPADDING=2>  
				
				<tr class="TBL_HEAD" >
					<td align="center">Tanggal</td>
					<td align="center">Darah</td>
					<?
					if ($d4->vis_49 != "''" || $d4->vis_49 != ""){
						echo "<td align='center'>$d4->vis_49</td>";
					}
					if ($d4->vis_50 != "''" || $d4->vis_50 != ""){
						echo "<td align='center'>$d4->vis_50</td>";
					}
					if ($d4->vis_51 != "''" || $d4->vis_51 != ""){
						echo "<td align='center'>$d4->vis_51</td>";
					}
					if ($d4->vis_52 != "''" || $d4->vis_52 != ""){
						echo "<td align='center'>$d4->vis_52</td>";
					}
					if ($d4->vis_53 != "''" || $d4->vis_53 != ""){
						echo "<td align='center'>$d4->vis_53</td>";
					}
					?>
					
					<td align="center">Muntah</td>							
					<td align="center">Tensi Sys</td>							
					<td align="center">Tensi Dyas</td>							
					<td align="center">Berat Badan</td>							
					<td align="center">Tindakan Khusus</td>							
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
							        	<td class="TBL_BODY"><?=$d5["vis_38"] ?> </font></td>
										<?
										if ($d4->vis_49 != "''" || $d4->vis_49 != ""){
											echo "<td class='TBL_BODY'>{$d5["vis_28"]}</td>";
										}
										if ($d4->vis_50 != "''" || $d4->vis_50 != ""){
											echo "<td class='TBL_BODY'>{$d5["vis_29"]}</td>";
										}
										if ($d4->vis_51 != "''" || $d4->vis_51 != ""){
											echo "<td class='TBL_BODY'>{$d5["vis_30"]}</td>";
										}
										if ($d4->vis_52 != "''" || $d4->vis_52 != ""){
											echo "<td class='TBL_BODY'>{$d5["vis_31"]}</td>";
										}
										if ($d4->vis_53 != "''" || $d4->vis_53 != ""){
											echo "<td class='TBL_BODY'>{$d5["vis_32"]}</td>";
										}
										?>
										<td class="TBL_BODY"><?=$d5["vis_33"] ?> </font></td>
										<td class="TBL_BODY"><?=$d5["vis_34"] ?> </font></td>
										<td class="TBL_BODY"><?=$d5["vis_35"] ?> </font></td>
										<td class="TBL_BODY"><?=$d5["vis_36"] ?> </font></td>
										<td class="TBL_BODY" align="left"><?=$d5["vis_37"] ?> </font></td>
										
									</tr>	
								<?;$j++;					
								}
								$i++;
									
							} ?>
				<tr><td colspan="16" CLASS=TBL_BODY height="25" >
				
				<? $rc = pg_query($con, "SELECT COUNT(*) AS CNT FROM c_visit_ri where no_reg='{$_GET['id']}' and id_ri='{$_GET["mPOLI"]}' ");
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
