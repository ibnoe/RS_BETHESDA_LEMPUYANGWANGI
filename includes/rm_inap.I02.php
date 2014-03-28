<?
//heri 29 august 2007
//udah 

$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["pengawasan_pasien_anak"];

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
		$f->text("No.Reg",$d2->id);
		$f->text("Seks",$d2->jenis_kelamin);
		$f->execute();
		echo "</td><td valign=top  class='tbl_body' align=left width='43%'>";
		$f = new ReadOnlyForm();
		$f->text("Pangkat / NRP", $d2->pangkat_gol." ".$d2->nrp_nip );
		$f->text("Kesatuan",$d2->kesatuan);
		$f->text("Ruang ",$bangsal);
		$f->execute();
		echo "</td></tr><tr><td class='TBL_BODY' colspan=3>";
		echo "<TABLE CLASS=TBL_BORDER WIDTH='100%' BORDER=0 CELLSPACING=1 CELLPADDING=2>";
			  echo "<tr><td class='TBL_HEAD' align='center'><b>P<br>E<br>R<br>A<br>T<br>U<br>R<br>A<br>N </b>";
			  echo "</td><td class='TBL_BODY' valign='top'>";
			   ?>
			  <TABLE CLASS=TBL_BORDER WIDTH='100%' BORDER=0 CELLSPACING=1 CELLPADDING=2>
			    <tr class="TBL_HEAD">     	
					<td class="TBL_HEAD" align="center" >Makanan</td>
					<td class="TBL_HEAD" align="center" >Pengobatan</td>
					<td class="TBL_HEAD" align="center" >Pemeriksaan Laboratorium<br>MANTOUX</td>
					<td class="TBL_HEAD" align="center" >Perhatian<br>Khusus</td>
					<td class="TBL_HEAD" align="center" >Consul</td>
				</tr>
				<?
				$SQL4 = "select distinct vis_1,vis_2,vis_3,vis_4,vis_5 from c_visit_ri where no_reg='{$_GET['id']}' and id_ri='{$_GET["mPOLI"]}' ";
					$i= 1 ;
					$j= 1 ;
					$mulai = 1;
					$max_row = 30;
					$r4 = pg_query($con,$SQL4);
					while ($row4 = pg_fetch_array($r4)){
						if (($j<=$max_row) AND ($i >= $mulai)){
							$class_nya = "TBL_BODY" ;
								
							?>		
						 	<tr valign="middle" class="<?=$class_nya?>" >  
					        	<td class="TBL_BODY" align="center" height="100"><?=$row4["vis_1"] ?>&nbsp; </font></td>
								<td class="TBL_BODY" align="center"><?=$row4["vis_2"] ?>&nbsp; </font></td>
								<td class="TBL_BODY" align="center"><?=$row4["vis_3"] ?>&nbsp; </font></td>
								<td class="TBL_BODY" align="center"><?=$row4["vis_4"] ?>&nbsp; </font></td>
								<td class="TBL_BODY" align="center"><?=$row4["vis_5"] ?>&nbsp; </font></td>
							</tr>	
							<?;$j++;
							
						}
						$i++;
								
					}
				echo "</table>" ;
			  echo "</td></tr></table>";
		  echo "</td></tr><tr><td colspan=3>";
			  
			$SQL3 = "select to_char(tanggal_reg,'dd mm yyyy')as tgl,vis_6,vis_7,vis_8,vis_9,vis_10,vis_11,vis_12,vis_13,vis_14,vis_15,vis_16,vis_17 ".
					"from c_visit_ri where no_reg='{$_GET['id']}' and id_ri='{$_GET["mPOLI"]}' ";	
					
			$r3 = pg_query($con,$SQL3);
			$n3 = pg_num_rows($r3);
   			$max_row= 30 ;
			$mulai = $HTTP_GET_VARS["rec"] ;	
			if (!$mulai){$mulai=1;}
			nav_db2($mulai,$n3,$max_row,"index2.php?p=$PID&sub={$_GET["mPOLI"]}&act=detail&id={$_GET['id']}&mr={$_GET['mr']}","") ;	
		?>			
		
			<TABLE CLASS=TBL_BORDER WIDTH='100%' BORDER=0 CELLSPACING=1 CELLPADDING=2>
			<tr class="TBL_HEAD">     	
				<td class="TBL_HEAD" align="center" rowspan="2">Tanggal</td>
				<td class="TBL_HEAD" align="center" rowspan="2">Jam</td>
				<td class="TBL_HEAD" align="center" rowspan="2">Suhu</td>
				<td class="TBL_HEAD" align="center" rowspan="2">Nadi</td>
				<td class="TBL_HEAD" align="center" rowspan="2">Perna<br>fasan</td>
				<td class="TBL_HEAD" align="center" rowspan="2">Muntah</td>
				<td class="TBL_HEAD" align="center" colspan="4">FAECES</td>
				<td class="TBL_HEAD" align="center" rowspan="2">Makanan</td>
				<td class="TBL_HEAD" align="center" rowspan="2">Obat-<br>obatan</td>
				<td class="TBL_HEAD" align="center" rowspan="2">Observasi</td>
			</tr>
			<tr class="TBL_HEAD">
				<td class="TBL_HEAD" align="center">Cons</td>
				<td class="TBL_HEAD" align="center">Ingus</td>	
				<td class="TBL_HEAD" align="center">Darah</td>	
				<td class="TBL_HEAD" align="center">Cacing</td>
			</tr>	
			
		<?	
			$i= 1 ;
			$j= 1 ;
			$last_id=1;
			while ($row1 = pg_fetch_array($r3)){
				if (($j<=$max_row) AND ($i >= $mulai)){
					$class_nya = "TBL_BODY" ;
						
					?>		
				 	<tr valign="top" class="<?=$class_nya?>" >  
			        	<td class="TBL_BODY" align="center"><?=$row1["tgl"] ?> </font></td>
			        	<td class="TBL_BODY" align="center"><?=$row1["vis_17"] ?> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["vis_6"] ?> </font></td>
						<td class="TBL_BODY" align="center"><?=$row1["vis_7"] ?> </font></td>
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
				if ($last_id < $row1->no_reg){$last_id=$row1->no_reg;}			
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
			    if (!$GLOBALS['print']) {
		            	echo "<span style='padding-left: 75%;'>$first_nya | $prev_nya || $next_nya | $last_nya </span>";
		         } 
                 ?>
            	</TD>
			</tr> 	
			</table>
		  <?
		  
		echo "</td></tr></table>"; 
		
				//include(rm_tindakan);
		
		echo "</DIV>";
		
	}else {
		
		include("rm_inap2.php");	
		
	}
}
?>
