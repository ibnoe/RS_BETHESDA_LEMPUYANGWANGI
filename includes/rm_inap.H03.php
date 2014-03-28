<?
//heri 23 juli 2007

$PID = "rm_inap";

if (strlen($_GET["sub"]) > 0 && empty($_GET[sure])) {
//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_ri["pemakaian_alat_keperawatan"];
	
	if ($_GET['act'] ==  "show"){		
				$tglnya= $_GET["tgl"];
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
		 	echo "</td></tr></table>";
		 	
			
					$SQL3 = "select a.*,to_char(a.tanggal_reg,'dd MM yyyy') as tanggal_reg, b.nama ".
							"from c_visit_ri a ".
							"left join rsv_pasien2 b on a.no_reg=b.id ".
							"where a.id_ri='{$_GET["mPOLI"]}' and a.no_reg='{$_GET["id"]}' and a.vis_1='$tglnya' ".
							"order by a.tanggal_reg asc ";
					$r3 = pg_query($con,$SQL3);
					$n3 = pg_num_rows($r3);
				    if($n3 > 0) $d3 = pg_fetch_array($r3);
				    pg_free_result($r3);
				    
			echo "<table class='TBL_BORDER' border='0' width='100%' cellspacing=1 cellpadding=2>";
			echo "<tr valign='middle'><td class='TBL_BODY' align='center' colspan='2' height='30'><b> T A N G G A L : $tglnya</b> </td></tr>";
			echo "<tr><td class='TBL_BODY' valign=top width='50%'>";
				$f = new ReadOnlyForm();
				$f->title1("I. A L A T");
			    $f->text("1. Infus Set ",$d3["vis_2"]);
			    $f->text("2. Tranfusi Set ",$d3["vis_3"]);
			    $f->text("3. abocath ",$d3["vis_4"]);
			    $f->text("4. Cairan Infus ","");
			    $f->text("&nbsp; &nbsp; a. NaCl 0,9% ",$d3["vis_5"]);
			    $f->text("&nbsp; &nbsp; b. Dex 10% ",$d3["vis_6"]);
			    $f->text("&nbsp; &nbsp; c. R.L.",$d3["vis_7"]);
			    $f->text("&nbsp; &nbsp; d. 2A",$d3["vis_8"]);
			    $f->text("&nbsp; &nbsp; e. Tranfusi ",$d3["vis_9"]);
			    $f->text("5. Cateter ",$d3["vis_10"]);
			    $f->text("6. N G T ",$d3["vis_11"]);
			    $f->text("7. Shorten ",$d3["vis_12"]);
			    $f->text("8. O2 Set ",$d3["vis_13"]);
			    $f->text("9. Slymzuiger ",$d3["vis_14"]);
			    $f->text("10. Spuit 2,5;5;10;20 ",$d3["vis_15"]);
			    $f->text("11. Alkohol ",$d3["vis_16"]);
			    $f->text("12. Bethadin ",$d3["vis_17"]);
			    $f->text("13. Kaps ",$d3["vis_18"]);
			    $f->text("14. Khasa Steril ",$d3["vis_19"]);
			    $f->text("15. Verband ",$d3["vis_20"]);
			    $f->text("16. Elastis Verband ",$d3["vis_21"]);
			    $f->text("17. Elektoda + Monitor ",$d3["vis_22"]);
			    $f->text("18. Ventilator ",$d3["vis_23"]);
			    $f->text("19. E K G ",$d3["vis_24"]);
			    $f->execute();    

			echo "</td><td valign=top  class='tbl_body' align=left width='50%'>";   
				 $f = new ReadOnlyForm();
				$f->title1("II. T I N D A K A N");
			    $f->text("1. Pasang Infus ",$d3["vis_25"]);
			    $f->text("2. Pasang Tranfusi ",$d3["vis_26"]);
			    $f->text("3. Pasang Cateter ",$d3["vis_27"]);
			    $f->text("4. Pasang NGT ",$d3["vis_28"]);
			    $f->text("5. Pasang Shorteen ",$d3["vis_29"]);
			    $f->text("6. Pasang O2 ",$d3["vis_30"]);
			    $f->text("7. Slymzuiger ",$d3["vis_31"]);
			    $f->text("8. Huknah ",$d3["vis_32"]);
			    $f->text("9. Ganti Balutan ",$d3["vis_33"]);
			    $f->text("10. Injectie ","");
			    $f->text("&nbsp; &nbsp; a. Intra Cutan ",$d3["vis_34"]);
			    $f->text("&nbsp; &nbsp; b. Sub Cutan ",$d3["vis_35"]);
			    $f->text("&nbsp; &nbsp; c. Intra Muscular ",$d3["vis_36"]);
			    $f->text("&nbsp; &nbsp; d. Intra Venous ",$d3["vis_37"]);
			    $f->text("11. Vena Punctie ",$d3["vis_38"]);
			    $f->text("12. Lumba Punctie ",$d3["vis_39"]);
			    $f->text("13. Pleura Punctie ",$d3["vis_40"]);
			    $f->text("14. Asites Punctie ",$d3["vis_41"]);
			    $f->text("15. BM ",$d3["vis_42"]);
			    $f->text("16. EKG ",$d3["vis_43"]);
			    $f->text("17. Vena Sectie ",$d3["vis_45"]);
			    $f->text("18. Pasang NGT ",$d3["vis_28"]);
			    $f->text("19. Pasang ventilator ",$d3["vis_44"]);
			    $f->text("20. Bilas lambung ",$d3["vis_46"]);
			    $f->text("21. Spuling ",$d3["vis_47"]);
			    $f->execute();    
			echo "</td></tr></table>";
			  
	}elseif ($_GET['act'] ==  "detail"){
		
			
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
		 	echo "</td></tr></table>";
		 	
								    
				$SQL = "select vis_1,vis_2,vis_3,vis_26,no_reg,vis_25 ".
					"from c_visit_ri ".
					"where id_ri='{$_GET["mPOLI"]}' and no_reg='{$_GET["id"]}' ";
																
			
			echo "<DIV ><br>";
				$t = new PgTable($con, "100%");
			    $t->SQL = "$SQL" ;
			    $t->ShowRowNumber = true;
			    $t->ColHidden[5] = true;
				$t->ColHeader = array("TANGGAL","INFUS SET","TRANFUSI SET","PASANG INFUS","PASANG TRANFUSI");
				$t->ColAlign = array("CENTER","left","left","left","left");		
				if (!$GLOBALS['print']){
					$t->ColFormatHtml[0] = "<A CLASS=SUB_MENU1 HREF='$SC?p=$PID&sub={$_GET["sub"]}&act=show&id=<#4#>&tgl=<#0#>'><#0#></A>";
			   		$t->RowsPerPage = $ROWS_PER_PAGE;			
			   	}else {
			   		$t->RowsPerPage = 20;
			    	$t->DisableNavButton = true;
			    	$t->DisableScrollBar = true;
			    	$t->DisableSort = true;
			   	}
				$t->execute();  	
		
			echo "</div>";
			
			
				
			echo "</DIV>";
		
	}else {
		
		include("rm_inap2.php");	
		
	}
}
?>
