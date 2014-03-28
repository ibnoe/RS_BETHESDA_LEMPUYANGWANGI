<?php
//  hery-- june 08, 2007 

$PID = "rm_pasien";
$SC = $_SERVER["SCRIPT_NAME"];
session_start();

if (!empty($_SESSION[uid])) {

require_once("startup.php");
require_once("lib/visit_setting.php");

if(!$GLOBALS['print']){
	title_print("<img src='icon/medical-record-2.gif' align='absmiddle' > RINGKASAN RIWAYAT PASIEN");
}else {
	title_print("<img src='icon/medical-record.gif' align='absmiddle' > RINGKASAN RIWAYAT PASIEN");
}
		
if ($_GET['act'] ==  "show") {
	if (!$GLOBALS['print']){
		echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV><DIV>";
	}
	$tab_disabled = array("identitas"=>true, "riwayat"=>true);
	if ($_GET["act"] == "edit" ) {
		$tab_disabled = array("identitas"=>false, "riwayat"=>false);
		$tab_disabled[$_GET["list"]] = true;
		$tab_disabled[$_POST["list"]] = true;
	}
	
	$T = new TabBar();
	$T->addTab("index2.php?p=rm_pasien&act=show&list=identitas&mr={$_GET["mr"]}", "Identitas Pasien", $tab_disabled["identitas"]);
	$T->addTab("index2.php?p=rm_pasien&act=show&list=riwayat&mr={$_GET["mr"]}"  , "Riwayat Klinik"	, $tab_disabled["riwayat"]);
        $T->addTab("index2.php?p=rm_pasien&act=show&list=riwayat&mr={$_GET["mr"]}"  , "Historical RM"	, $tab_disabled["riwayat"]);
        
	if ($_GET["list"] == "riwayat") {
		$T->show(1);   
				
		if ($_GET['act2'] ==  "detail"){
				//echo "MR=".$_GET["mr"];
				$sql = "select a.*,b.nama as jaga,d.nama as perawat,e.nama as perawat1,f.nama as perawat2,g.nama as perawat3,h.nama as perawat4,i.nama as perawat5,j.nama as perawat6,
				to_char(a.tanggal_reg,'dd Month yyyy') as tgl_periksa  
			    		from c_visit a
						left join rs00017 b on a.id_dokter = b.id
						left join rsv0002 c on a.no_reg=c.id
						left join rs00017 d on a.id_perawat = d.id
						left join rs00017 e on a.id_perawat1 = e.id
						left join rs00017 f on a.id_perawat2 = f.id
						left join rs00017 g on a.id_perawat3 = g.id
						left join rs00017 h on a.id_perawat4 = h.id
						left join rs00017 i on a.id_perawat4 = i.id
						left join rs00017 j on a.id_perawat4 = j.id
						where a.no_reg='{$_GET['id']}' ";
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
			    echo "</td></tr>";
					     
				//echo "<br>";
				
				//echo "<table class='TBL_BORDER' border='0' width='100%' cellspacing=1 cellpadding=0>";
			
				if ($_GET["mPOLI"] == $setting_poli["jantung"]){
						subtitle2("Klinik Jantung","center");
						$max = count($visit_jantung) ; 
						$i = 1;
						echo "<tr><td class='tbl_body' valign=top colspan='3'>";
						$f = new ReadOnlyForm2();
						while ($i<= $max) {		
							if ($visit_jantung["vis_".$i."F"] == "edit") {
							
								$f->text($visit_jantung["vis_".$i],$d[2+$i] );
							}
							if ($visit_jantung["vis_".$i."F"] == "memo") {
							
								$f->text($visit_jantung["vis_".$i],$d[2+$i]);
							}
							if ($visit_jantung["vis_".$i."F"] == "edit4") {
							
								$f->text4($visit_jantung["vis_5"],$d[2+$i],$visit_jantung["vis_6"],$d[2+$i+1],$visit_jantung["vis_7"],$d[2+$i+2],$visit_jantung["vis_8"],$d[2+$i+3],"");
							
							}
							if ($visit_jantung["vis_".$i."F"] == "edit5") {
							
								$f->text4($visit_jantung["vis_9"],$d[2+$i],$visit_jantung["vis_10"],$d[2+$i+1],$visit_jantung["vis_11"],$d[2+$i+2],$visit_jantung["vis_12"],$d[2+$i+3],"");			    	
							}	
							$i++ ; 	
						}
						$f->text("Dokter",$d["nama"]);
						$f->execute();
						
				}elseif ($_GET["mPOLI"] == $setting_poli["anak"]){
						subtitle2("Klinik Anak","center");
						echo "</table><table class='TBL_BORDER' border='0' width='100%' cellspacing=1 cellpadding=0>";
						echo "<tr><td class='TBL_BODY' valign=top width='50%'>";
							$f = new ReadOnlyForm2();			
							$f->text_tmp_tglLahir("Tempat/Tgl Lahir",$d2->tmp_lahir,$d2->tgl_lahir);
							$f->text($visit_anak["vis_1"],$d[3] . " Kg" );
							$f->text($visit_anak["vis_2"],$d[4]);
							$f->execute();
							echo "</td><td class='TBL_BODY' width= 50%>";
							$f = new ReadOnlyForm2();
							$f->text($visit_anak["vis_3"],$d[5]);
							$f->text($visit_anak["vis_4"],$d[6]);
							$f->text($visit_anak["vis_5"],$d[7]);
							$f->execute();
							echo "</td></tr><tr><td class='TBL_BODY' width='50%'>";
							$f = new ReadOnlyForm2();
							$f->text($visit_anak["vis_6"],$d[8]);
							$f->text($visit_anak["vis_7"],$d[9]);
							$f->execute();			
							echo "</td><td class='TBL_BODY' width=50%>";
							$f = new ReadOnlyForm2();			
							$f->text($visit_anak["vis_8"],$d[10]);
							$f->text($visit_anak["vis_9"],$d[11]);
							$f->text($visit_anak["vis_10"],$d[12]);
							$f->execute();

							echo "</td></tr><tr><td class='TBL_BODY' colspan='2'>";
								$t = new PgTable($con, "100%");
							    $t->SQL =  "select a.tanggal_reg,c.umur,a.vis_11,a.vis_12,a.vis_13,a.vis_14,a.vis_15,b.nama 
											from c_visit a
											left join rs00017 b on a.id_dokter = b.id 
											left join rsv0002 c on a.no_reg=c.id 
											where a.id_poli='{$_GET["mPOLI"]}' " ;
							    $t->setlocale("id_ID");
							    $t->ShowRowNumber = true;
							   	//$t->ColHidden[7]= true;
							    $t->ColHeader = array( "Tanggal","Umur", "BB","TB","Laborat mantoux","Diagnosa","Dokter");
							    $t->ColAlign = array("center","center","center","center","center","left","left");
								//$t->ColFormatHtml[7] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&act=detail&id=<#0#>&mr=<#1#>'>".icon("view","View")."</A>";
								if($GLOBALS['print']){
									$t->RowsPerPage = 30;			    
									$t->DisableNavButton = true;
									$t->DisableScrollBar = true;
								}else {
									$t->RowsPerPage = $ROWS_PER_PAGE;			    
								}				
								$t->execute();
				}elseif ($_GET["mPOLI"] == $setting_poli["peny_dalam"]){
							subtitle2("Klinik Penyakit Dalam","center");
							echo "<tr><td class='tbl_body' valign=top colspan='3'>";		    
							$max = count($visit_penyakit_dalam) ; 
							$i = 1;
							$f = new ReadOnlyForm2();
							while ($i<= $max) {		
								if ($visit_penyakit_dalam["vis_".$i."F"] == "edit") {
								
									$f->text($visit_penyakit_dalam["vis_".$i],$d[2+$i] );
								}
								if ($visit_penyakit_dalam["vis_".$i."F"] == "memo") {
								
									$f->text($visit_penyakit_dalam["vis_".$i],$d[2+$i]);
								}
								if ($visit_penyakit_dalam["vis_".$i."F"] == "edit4") {
							
									$f->text4($visit_penyakit_dalam["vis_5"],$d[2+$i],$visit_penyakit_dalam["vis_6"],$d[2+$i+1],$visit_penyakit_dalam["vis_7"],$d[2+$i+2],$visit_penyakit_dalam["vis_8"],$d[2+$i+3],"");
								
								}
								if ($visit_penyakit_dalam["vis_".$i."F"] == "edit5") {
								
									$f->text4($visit_penyakit_dalam["vis_9"],$d[2+$i],$visit_penyakit_dalam["vis_10"],$d[2+$i+1],$visit_penyakit_dalam["vis_11"],$d[2+$i+2],$visit_penyakit_dalam["vis_12"],$d[2+$i+3],"");			    	
								}	
								$i++ ; 	
							}
							$f->text("Dokter",$d["nama"]);
							$f->execute();
				}elseif ($_GET["mPOLI"] == $setting_poli["akupunktur"]){
						subtitle2("Klinik Akupunktur","center");
						$max = count($visit_akupunktur) ; 
						$i = 1;
						echo "<tr><td class='tbl_body' valign=top colspan='3'>";
						$f = new ReadOnlyForm2();
						while ($i<= $max) {		
							if ($visit_akupunktur["vis_".$i."F"] == "edit") {
							
								$f->text($visit_akupunktur["vis_".$i],$d[2+$i] );
							}
							if ($visit_akupunktur["vis_".$i."F"] == "memo") {
							
								$f->text($visit_akupunktur["vis_".$i],$d[2+$i]);
							}if ($visit_akupunktur["vis_".$i."F"] == "memo1") {
							
								$f->text($visit_akupunktur["vis_".$i],$d[2+$i]);
							}if ($visit_akupunktur["vis_".$i."F"] == "memo2") {
							
								$f->text($visit_akupunktur["vis_".$i],$d[2+$i]);
							}
							if ($visit_akupunktur["vis_".$i."F"] == "memo3") {
							
								$f->text($visit_akupunktur["vis_".$i],$d[2+$i]);
							}
							if ($visit_akupunktur["vis_".$i."F"] == "edit4") {
							
								$f->text4($visit_akupunktur["vis_5"],$d[2+$i],$visit_akupunktur["vis_6"],$d[2+$i+1],$visit_akupunktur["vis_7"],$d[2+$i+2],$visit_akupunktur["vis_8"],$d[2+$i+3],"");
							
							}
							if ($visit_akupunktur["vis_".$i."F"] == "edit5") {
							
								$f->text4($visit_akupunktur["vis_9"],$d[2+$i],$visit_akupunktur["vis_10"],$d[2+$i+1],$visit_akupunktur["vis_11"],$d[2+$i+2],$visit_akupunktur["vis_12"],$d[2+$i+3],"");			    	
							}	
							$i++ ; 	
						}	
						$f->text("Dokter",$d["nama"]);
						$f->execute();
				}elseif ($_GET["mPOLI"] == $setting_poli["kulit_kelamin"]){
					
						subtitle2("Klinik Kulit dan Kelamin","center");
						echo "<tr><td class='tbl_body' valign=top colspan='2' align='left' width='60%'>";
					    $f = new ReadOnlyForm();
						$f->text("Tanggal Pemeriksaan","<b>".$d["tgl_periksa"]);
						$f->title1("ANAMNESA","LEFT");
						$f->text($visit_kulit_kelamin["vis_1"],$d[3] );
						$f->text($visit_kulit_kelamin["vis_2"],$d[4]);
						$f->text($visit_kulit_kelamin["vis_3"],$d[5]);
						$f->text($visit_kulit_kelamin["vis_4"],$d[6] );
						$f->text($visit_kulit_kelamin["vis_5"],$d[7] );
						//echo "</td><td valign='top'>";
						$f->title1("PEMERIKSAAN FISIK","LEFT");	
						$f->text($visit_kulit_kelamin["vis_6"],$d[8]);
						$f->text($visit_kulit_kelamin["vis_7"],$d[9]."&nbsp;"."mm Hg");
						$f->text($visit_kulit_kelamin["vis_8"],$d[10]."&nbsp;"."/Menit" );    
						$f->text($visit_kulit_kelamin["vis_9"],$d[11]."&nbsp;"."Celcius");
						$f->text($visit_kulit_kelamin["vis_10"],$d[12]);
						$f->text($visit_kulit_kelamin["vis_11"],$d[13]."&nbsp;"."Cm");
						$f->text($visit_kulit_kelamin["vis_12"],$d[14]);
						$f->text($visit_kulit_kelamin["vis_13"],$d[15] );
						$f->text($visit_kulit_kelamin["vis_14"],$d[16]."&nbsp;"."Kg");
						$f->text($visit_kulit_kelamin["vis_15"],$d[17]);
						$f->title1("DIAGNOSA DAN PENGOBATAN","LEFT");	
						$f->text($visit_kulit_kelamin["vis_18"],$d[20]);
						$f->text($visit_kulit_kelamin["vis_19"],$d[21]);
						$f->text($visit_kulit_kelamin["vis_20"],$d[22]);
						$f->title1("DOKTER PEMERIKSA","LEFT");
						$f->text("Nama",$d["nama"]);
						$f->execute();
						echo "</td><td  class='tbl_body' width='40%' align='left'>";
						echo "<div><IMG  width=35% BORDER=0 SRC='images/orang_02.gif'>\n\n";
						echo "<IMG  width=35% BORDER=0 SRC='images/orang_01.gif'></div>";
						$f = new ReadOnlyForm();
						$f->title1("STATUS DEMATOLOGIKUS","LEFT");			
						$f->text($visit_kulit_kelamin["vis_16"],$d[18]);
						$f->text($visit_kulit_kelamin["vis_17"],$d[19]);
						$f->execute();
						 
					
				}elseif ($_GET["mPOLI"] == $setting_poli["gigi"]){
						
						subtitle2("Klinik Gigi","center");			
						?>	
							<tr valign="top" align="center">
								<td class='TBL_BODY' colspan='3'>
									<table class="FORM" align="center">
										<TR><TD class="FORM" align="center"><b>NOMENKLATUR GIGI (FDI) : <?=$d["vis_1"]?></b></td></TR>
										<tr><td class="from" align="center"><img src="images/no_kotak.gif" ></td></tr>
										<TR><TD class="FORM" align="center"><b>ODONTOGRAM (FDI) : <?=$d["vis_2"]?></b></td></TR>
										<tr><td class="from" align="center"><img src="images/gigi1.gif" ></td></tr>
									</table>
								</td>		
							</tr>
							
						<?		
							echo "<tr><td class='TBL_BODY' colspan='3' align='center'><b>Riwayat Pasien</b>";
								$t = new PgTable($con, "100%");
							    $t->SQL =  "select to_char(a.tanggal_reg,'dd mm yyyy hh:mm')as tanggal_reg,a.vis_3,a.vis_5,a.vis_6,b.nama 
											from c_visit a
											left join rs00017 b on a.id_dokter = b.id 
											left join rsv0002 c on a.no_reg=c.id 
											where a.id_poli='{$_GET["mPOLI"]}' and c.mr_no='{$_GET["mr"]}' " ;
							    $t->setlocale("id_ID");
							    $t->ShowRowNumber = true;
							    $t->ColHeader = array( "Tanggal","Anamnesa/ Pemeriksaan/ Pengobatan","Diagnosis","No Kode<br>Diagnosis","Dokter");
							    $t->ColAlign = array("center","left","left","center","left");
								if($GLOBALS['print']){
									$t->RowsPerPage = 30;			    
									$t->DisableNavButton = true;
									$t->DisableScrollBar = true;
									$t->DisableSort = true;
								}else {
									$t->RowsPerPage = $ROWS_PER_PAGE;			    
								}				
								$t->execute(); 
		
				}elseif ($_GET["mPOLI"] == $setting_poli["mata"]){
						
						subtitle2("Klinik Mata","center");
						echo "<tr><td class='tbl_body' valign=top colspan='3'>";
						$f = new ReadOnlyForm2();
						$f->text("ANAMNESA",$d[3]);
						$f->text("DIKIRIM OLEH","<B>".$d[4]."</B>");
						$f->info("<B><i>Kedudukan Bola Mata</i></B>","");
						$f->text_mata("KEDUDUKAN/GERAK BOLA MATA","OD","OS","<IMG BORDER=0 SRC='images/bg_tbh_04.gif'>","<IMG BORDER=0 SRC='images/bg_tbh_05.gif'>","PALPEBRA",$d[5],$d[6],
										"CONJUNCTIVA",$d[7],$d[8],"CORNEA",$d[9],$d[10],"C.O.A",$d[11],$d[12],"IRIS",$d[13],$d[14],"PUPIL",$d[15],$d[16],"LENSA",$d[17],$d[18],"VITREOUS",$d[19],$d[20],"HUMOR",$d[21],$d[22],"PUNDUSKOPI",$d[23],$d[24],"","","","center");
						
						$f->info("<B><i>Pemeriksaan Lain</i></b>","");
						$f->text_mata("","","","","","VISUS",$d[25],$d[26],"KOREKASI",$d[27],$d[28],"KACAMATA",$d[29],$d[30],"APLANSI",$d[31],$d[32],"TENOMETRI",$d[33],$d[34],"ANEL ",$d[35],$d[36],"","","","","","","","","","","","","","","","CENTER");
						$f->text("BACA",$d[37]);
						$f->text($visit_mata["vis_36"],$d[38]);
						//$f->checkbox3("STEAK",$d[38],"Retinoskopi",$d[38],"Keratomi",$d[39],"Skiasko",$d[40]);
						$f->text("Sistem Lakrimal",$d[42]);
						$f->text("Laboratorium",$d[41]);
						$f->text("Diagnosa",$d[43]);
						$f->text("Dokter","<B>".$d["nama"]."</B>");
						$f->execute();
														
				}elseif ($_GET["mPOLI"] == $setting_poli["gizi"]){
						subtitle2("Klinik Gizi","center");
						echo "<tr><td class='tbl_body' valign=top colspan='3'>";
						$sql = "select a.*,b.nama,to_char(a.tanggal_reg,'dd Month yyyy')as tanggal_reg
								from c_visit a 
								left join rs00017 b on a.id_dokter = b.id 
								left join rsv0002 c on a.no_reg=c.id 
								where a.id_poli='{$_GET["mPOLI"]}' " ;
						$r = pg_query($con,$sql);
						$n = pg_num_rows($r);
						if($n > 0) $d = pg_fetch_array($r);
						pg_free_result($r);
						
						$f = new ReadOnlyForm();
						$f->text("Tanggal Pemeriksaan","<b>".$d["tanggal_reg"]);
						$f->title1("<U>FDI</U>","LEFT");
						$f->text($visit_gizi["vis_1"],$d[3] );
						$f->text($visit_gizi["vis_2"],$d[4]);
						$f->title1("<U>ANAMNESA PASIEN</U>","LEFT");
						$f->text($visit_gizi["vis_3"],$d[5]);
						$f->title1("<U>PEMERIKSAAN</U>","LEFT");
						$f->text($visit_gizi["vis_4"],$d[6]);
						$f->title1("<U>DIAGNOSA KERJA</U>","LEFT");
						$f->text($visit_gizi["vis_5"],$d[7] );
						$f->title1("<U>DOKTER PEMERIKSA</U>","LEFT");
						$f->text("Nama",$d["nama"]);
						$f->execute();
				}elseif ($_GET["mPOLI"] == $setting_poli["tht"]){
							
						subtitle2("Klinik THT","center");
						echo "</table><table class='tbl_border' border='0' width='100%' cellspacing=1 cellpadding=0>";
						echo "<tr><td class='tbl_body' valign=top colspan='2'>";
							$f = new ReadOnlyForm();
							$f->info("<u><B>ANAMNESA PASIEN</B></u>","");
							$f->text("ANAMNESA / TGL",$d["vis_1"]);
							$f->text("ANAMNESA / KELUARGA ",$d["vis_2"]);
							$f->text("ALERGI DIATHESA HAEMORACHI dll",$d["vis_3"]);
							$f->text("HAL-HAL YANG PENTING",$d["vis_4"]);
							$f->execute();
							
							$max = count($visit_jantung) ; 
							$i = 1;
							$f = new ReadOnlyForm2();			
							while ($i<= $max) {
						    	if ($visit_tht["vis_".$i."F"] == "edit") {
					
										$f->text_gambar("<u><B>PEMERIKSAAN FISIK</B></u>","PHARYNX","IMG width=150 BORDER=0 SRC='images/bg_tbh_01.gif'",$visit_tht["vis_5"],$d[2+$i],$visit_tht["vis_6"],$d[2+$i+1],$visit_tht["vis_7"],$d[2+$i+2],$visit_tht["vis_8"],$d[2+$i+3],$visit_tht["vis_9"],$d[2+$i+4],"");
								}
								if ($visit_tht["vis_".$i."F"] == "edit2") {
					
										$f->text_gambar3("EPIPHARYNX","IMG width=150 BORDER=0 SRC='images/bg_tbh_03.gif'",$visit_tht["vis_10"],$d[2+$i],$visit_tht["vis_11"],$d[2+$i+1],$visit_tht["vis_12"],$d[2+$i+2],"");
								}
								if ($visit_tht["vis_".$i."F"] == "edit3") {
					
										$f->text_gambar3("LARYNX","IMG width=150 BORDER=0 SRC='images/bg_tbh_02.gif'",$visit_tht["vis_13"],$d[2+$i],$visit_tht["vis_14"],$d[2+$i+1],$visit_tht["vis_15"],$d[2+$i+2],"");
								}
							
					 		$i++ ; 	
							}
							$f->execute();
						echo "</td></tr><tr valign='top'><td width='50%' CLASS='TBL_BODY' align='left' >";
							$f = new ReadOnlyForm2();
							$f->info("<u><B>LABORATORIUM / PA</B></u>","");
							$f->text("HB",$d["vis_16"],"");
							$f->text("Masa Pendarahan",$d["vis_17"],"");
							$f->text("Masa Pembekuan",$d["vis_18"],"");
							$f->text("Leocosit",$d["vis_19"],"");
							$f->execute();
						echo "</td><td CLASS='TBL_BODY' align='left' >";
							$f = new ReadOnlyForm2();
							$f->info("<u><B>LABORATORIUM / PA</B></u>","");
							$f->text("Diagnosa Kerja",$d["vis_24"],"");
							$f->text("Terapi / Obat-obatan",$d["vis_20"],"");
							$f->text("Rencana Pemeriksaan",$d["vis_21"],"");
							$f->text("Konsul",$d["vis_22"],"");
							$f->text("Tanggal Dirawat",$d["vis_23"],"");
							$f->execute();
						echo "</td></tr><tr valign='top'><td class='tbl_body' valign=top colspan='2' >";
							$f = new ReadOnlyForm2();
							$f->text("Dokter","<b>".$d["nama"]."</b>");
							$f->text("Perawat / Petugas","<b>".$d["vis_25"]."</b>");
							$f->execute();
							
										
				}elseif ($_GET["mPOLI"] == $setting_poli["bedah"]){
					
						subtitle2("Klinik Bedah", "center");
						$max = count($visit_bedah) ; 
						$i = 1;
						echo "<tr><td class='tbl_body' valign=top colspan='3'>";
						$f = new ReadOnlyForm2();
						while ($i<= $max) {		
							if ($visit_bedah["vis_".$i."F"] == "edit") {
							
								$f->text($visit_bedah["vis_".$i],$d[2+$i] );
							}
							if ($visit_bedah["vis_".$i."F"] == "memo") {
							
								$f->text($visit_bedah["vis_".$i],$d[2+$i]);
							}
							if ($visit_bedah["vis_".$i."F"] == "memo1") {
							
								$f->text($visit_bedah["vis_".$i],$d[2+$i]);
							}
							if ($visit_bedah["vis_".$i."F"] == "edit4") {
							
								$f->text4($visit_bedah["vis_5"],$d[2+$i],$visit_bedah["vis_6"],$d[2+$i+1],$visit_bedah["vis_7"],$d[2+$i+2],$visit_bedah["vis_8"],$d[2+$i+3],"");
							
							}
							if ($visit_bedah["vis_".$i."F"] == "edit5") {
							
								$f->text4($visit_bedah["vis_9"],$d[2+$i],$visit_bedah["vis_10"],$d[2+$i+1],$visit_bedah["vis_11"],$d[2+$i+2],$visit_bedah["vis_12"],$d[2+$i+3],"");			    	
							}	
							$i++ ; 	
						}
						$f->text("Dokter",$d["nama"]);
						$f->execute();
					
				}elseif ($_GET["mPOLI"] == $setting_poli["igd"]){
					
						subtitle2("Instalasi Gawat Darurat", "center");
						?>
				    	 <tr valign="top" align="left">
							<td class='TBL_BODY' colspan='3'><br>
								<table class="TBL_BORDER" align="left" border="0" width="100%" cellpadding="2" cellspacing="1">
									<TR valign="top">
										<TD class="TBL_BODY" align="left" width="20%">Dikirim Oleh :<br><?=$d["vis_1"]?></td>
										<TD class="TBL_BODY" align="left" width="20%">Diantar Oleh :<br><?=$d["vis_2"]?></td>
										<TD class="TBL_BODY" align="left" width="20%">Dibawa ke RS dengan : <br><?=$d["vis_1"]?></td>							
										<TD class="TBL_BODY" align="center" width="20%">Kasus Polisi <br><?=$d["vis_10"]?></td>							
										<TD class="TBL_BODY" valign="middle" align="center" width="20%" rowspan="2">Pasien <br><?=$d["vis_11"]?></td>							
									</TR>
									<TR valign="top">
										<TD class="TBL_BODY" align="left">Tgl/Jam Kejadian : <br><?=$d["vis_3"]?>  /  <?=$d["vis_4"]?></td>
										<TD class="TBL_BODY" align="left">Tempat Kejadian : <?=$d["vis_5"]?> </td>
										<TD class="TBL_BODY" align="left" colspan="2">  
														<table><tr><td align="right">Tiba di Rumkit : Tanggal </td><td>: <?=$d["vis_8"]?></td></tr><tr><td align="right">jam </td><td>: <?=$d["vis_9"]?></td></tr></table>
										</td>							
									</TR>
									<tr valign="top">
										<TD class="TBL_BODY" align="left">Dokter jaga : <br> <?=$d["jaga"]?>
										<TD class="TBL_BODY" align="left">Perawat U.darurat : <br><?=$d["perawat"]?> </td>
										<TD class="TBL_BODY" align="left" colspan="2">Tanggal Pemeriksaan : <?=$d["tanggal_periksa"]?> </td>
										<TD class="TBL_BODY" align="left" colspan="2">Jam Pemeriksaan : <?=$d["jam_periksa"]?> </td>															
									</tr>
									<tr valign="top">
										<TD class="TBL_BODY" align="center" colspan="5"><b>Riwayat Kejadian</b> <br>
										<div class="TBL_BODY" align="left" colspan="5" height="40"><p><?=$d["vis_12"]?></p></div></td>																							
									</tr>
									<tr valign="top">
										<TD class="TBL_BODY" align="center" colspan="5"><b>Pemeriksaan Fisik </b><br>								
											<table align="left" width="100%">
												<tr valign="middle">
													<td align="left" width="15%">- Umum Tensi </td>
													<td align="left" width="20%">: &nbsp;<?=$d["vis_13"]?> &nbsp;</td>
													<td align="left" width="20%">-Nadi : &nbsp;<?=$d["vis_14"]?> &nbsp;&nbsp;/menit &nbsp;</td> 
													<td align="left" width="20%">-Suhu : &nbsp;<?=$d["vis_15"]?> &nbsp;&nbsp;/&deg;C &nbsp;</td> 
													<td align="left" width="20%">-Pernafasan : &nbsp;<?=$d["vis_16"]?></td> 
												</tr>
												<tr valign="middle">
													<td align="left" width="15%">- Keadaan Umum </td>
													<td align="left" width="20%" colspan="4">: &nbsp;<?=$d["vis_17"]?> &nbsp;</td>											
												</tr>
												<tr valign="middle">
													<td align="left" width="15%">- Kesadaran </td>
													<td align="left" width="20%" colspan="4">: &nbsp;<?=$d["vis_18"]?> &nbsp;</td>											
												</tr>
												<tr valign="middle">
													<td align="left" width="15%">- Lain-lain </td>
													<td align="left" width="20%" colspan="4">: &nbsp;<?=$d["vis_19"]?> &nbsp;</td>											
												</tr>
											</table>								
										</td>																							
									</tr>
									<tr valign="top">
										<TD class="TBL_BODY" align="left" colspan="3" height="30">- Kepala & Muka : &nbsp;<?=$d["vis_20"]?></td>
										<TD class="TBL_BODY" align="left" colspan="2">- Luka : &nbsp;<?=$d["vis_21"]?></td>
									</tr>
									<tr valign="top">
										<TD class="TBL_BODY" align="left" colspan="2" height="30">- Leher : &nbsp;<?=$d["vis_22"]?></td>
										<TD class="TBL_BODY" align="left" colspan="2">- Tulang Belakang : &nbsp;<?=$d["vis_23"]?></td>
										<TD class="TBL_BODY" align="left" >- Pelvis : &nbsp;<?=$d["vis_24"]?></td>
									</tr>
									<tr valign="top">
										<TD class="TBL_BODY" align="left" colspan="3" height="30">- Dada : &nbsp;<?=$d["vis_25"]?></td>
										<TD class="TBL_BODY" align="left" colspan="2">- Perut : &nbsp;<?=$d["vis_26"]?></td>
									</tr>
									<tr valign="top">
										<TD class="TBL_BODY" align="left" colspan="3" height="30">- Anggota Gerak Bag. Bawah : &nbsp;<?=$d["vis_27"]?></td>
										<TD class="TBL_BODY" align="left">- Kanan : &nbsp;<?=$d["vis_28"]?></td>
										<TD class="TBL_BODY" align="left">- Kiri : &nbsp;<?=$d["vis_29"]?></td>
									</tr>
									<tr valign="top">
										<TD class="TBL_BODY" align="left" colspan="3" height="30">- Anggota Gerak Bag. Atas : &nbsp;<?=$d["vis_30"]?></td>
										<TD class="TBL_BODY" align="left">- Kanan : &nbsp;<?=$d["vis_31"]?></td>
										<TD class="TBL_BODY" align="left">- Kiri : &nbsp;<?=$d["vis_32"]?></td>
									</tr>
									<tr valign="top">
										<TD class="TBL_BODY" align="center" height="30">Glasgow Com Scale </td>
										<TD class="TBL_BODY" align="center">Nilai </td>
										<TD class="TBL_BODY" align="left" colspan="3" rowspan="2">Tindakan : &nbsp;<?=$d["vis_35"]?></td>
									</tr>
									<tr valign="top">
										<TD class="TBL_BODY" align="left" height="30">Buka Mata <br>Respon Motor <br> Respons Verbal </td>
										<TD class="TBL_BODY" align="center" height="30"><?=$d["vis_33"]?> <br><?=$d["vis_34"]?> <br> <?=$d["vis_35"]?> </td>														
								</table>
						<!--	</td>		
						</tr>--> 			
						<?
						
				}elseif ($_GET["mPOLI"] == $setting_poli["kebidanan_ginekologi"]){
					
						subtitle2("Klinik Kebidanan Ginekologi", "center");
							echo "</table><table class='TBL_BORDER' border='0' width='100%' cellspacing=1 cellpadding=0>";
							echo "<tr><td class='TBL_BODY' valign=top>";
							
								echo "<table border=0 width='100%' cellspacing=2 cellpadding=3><tr><td width='50%'>";
								$f = new ReadOnlyForm();
								$f->title1("<u>Pemeriksaan Umum</u>");
								$f->text("Tanggal",$d["tgl_periksa"]);
								$f->text($visit_ginekologi["vis_1"],$d[3]);
								$f->text($visit_ginekologi["vis_3"],$d[5]);
								$f->text($visit_ginekologi["vis_5"],$d[7]);
								$f->text($visit_ginekologi["vis_7"],$d[9]);
								$f->execute();
								echo "</td><td width='50%'>";
								$f = new ReadOnlyForm();
								$f->text($visit_ginekologi["vis_2"],$d[4]);
								$f->text($visit_ginekologi["vis_4"],$d[6]);
								$f->text($visit_ginekologi["vis_6"],$d[8]);
								$f->text($visit_ginekologi["vis_8"],$d[10]);
								$f->execute();
								echo "</td></tr></table><hr noshade color=#999999 size=1>";
								echo "<table border=0 width='95%' align='center' CELLSPACING=2 CELLPADDING=3><tr><td width='40%'>";
								echo "<img src='images/bg_tbh_12.gif'></td><td CLASS=FORM>";
								$f = new ReadOnlyForm2();
								$f->checkbox2("",$visit_ginekologi["vis_9"],$d[11],$visit_ginekologi["vis_10"],$d[12]);
								$f->checkbox2("",$visit_ginekologi["vis_11"],$d[13],$visit_ginekologi["vis_12"],$d[14]);
								$f->checkbox2("",$visit_ginekologi["vis_13"],$d[15],$visit_ginekologi["vis_14"],$d[16]);
								$f->hr();
								$f->execute();
								echo "&nbsp;Pembesaran Kelenjar Getah Bening :";
								$f = new ReadOnlyForm2();
								$f->text4x("Supraclavikula",$d[17],$d[18],$d[19],"cm");
								$f->text4x("Inguil",$d[20],$d[21],$d[22],"cm");
								$f->text4x("Aksila",$d[23],$d[24],$d[25],"cm");
								$f->hr();
								$f->info("Benjolan / Tumor (Lokasi) ","");
								$f->text4x("1.".$d[26],$d[27],$d[28],$d[29],"cm");
								$f->text4x("2.".$d[30],$d[31],$d[32],$d[33],"cm");
								$f->text4x("3.".$d[34],$d[35],$d[36],$d[37],"cm");
								$f->text4x("4.".$d[38],$d[39],$d[40],$d[41],"cm");
								$f->hr();
								$f->text($visit_ginekologi["vis_40"],$d[42]);				
								$f->execute();
								echo "</td></tr></table><hr noshade color=#999999 size=1>";
								echo "<table border='0' width='95%' align='center' cellspacing=2 cellpadding=3><tr><td width='30%'>";
								echo "	<table><tr><td CLASS=FORM><B>Pemeriksaan Ginekologi </B></td></tr>";
								echo "	<tr><td><img src='images/bg_tbh_07.gif'></td></tr>";
								echo "	<tr><td><img src='images/bg_tbh_10.gif'></td></tr>";
								echo "</td></tr></table>";
							echo "</td><td width='50%' class='TBL_BODY'>";
								$f = new ReadOnlyForm2();
								//$f->info("Porsio :","");
								$f->checkbox1("Porsio ",$visit_ginekologi["vis_41"],$d[43]);
								$f->checkbox1("",$visit_ginekologi["vis_42"],$d[44]);
								$f->checkbox1("",$visit_ginekologi["vis_43"],$d[45]);
								$f->checkbox1("",$visit_ginekologi["vis_44"],$d[46]);
								$f->checkbox1("",$visit_ginekologi["vis_45"],$d[47]);
								$f->hr();				
								$f->text4x("Ukuran Tumor           ",$d[48],$d[49],$d[50],"cm");
								$f->text($visit_ginekologi["vis_49"],$d[51]);
								$f->text($visit_ginekologi["vis_50"],$d[52]. " cm");
								$f->execute();
								echo "</td><td  width='20%'><table border='0'><tr><td width='10'>";
								echo "<img src='images/bg_tbh_08.gif'></td><td><img src='images/bg_tbh_09.gif'></td></tr></table>";
								echo "<table border='0' valign='bottom'><tr><td ><img width=110 src='images/bg_tbh_11.gif'></td></tr></table>";
								echo "</td></tr></table><hr noshade color=#999999 size=1>";
								$f = new ReadOnlyForm2();
								$f->text($visit_ginekologi["vis_51"],$d[53]);
								$f->text($visit_ginekologi["vis_52"],$d[54]);
								$f->text($visit_ginekologi["vis_53"],$d[55]);
								$f->text($visit_ginekologi["vis_54"],$d[56]);
								$f->hr();
								$f->text("Dokter ahli Obstetri/Genekologi",$d["nama"]);
								$f->execute();
							
					}elseif ($_GET["mPOLI"] == $setting_poli["kebidanan_obstetri"]){		
							subtitle2("Klinik Kebidanan Obstetri", "center");
							echo "</table><table class='TBL_BORDER' border='0' width='100%' cellspacing=1 cellpadding=0>";
							echo "<tr><td class='TBL_BODY' valign=top >";
								$f = new ReadOnlyForm2();
								$f->text3($visit_obsteteri["vis_1"],$d[3],$visit_obsteteri["vis_2"],$d[4],$visit_obsteteri["vis_3"],$d[5],"");
								//$f->info("Riwayat Sosial","");
								$f->text3($visit_obsteteri["vis_50"],$d[52],$visit_obsteteri["vis_51"],$d[53],$visit_obsteteri["vis_48"],$d[50],"");
								$f->text3($visit_obsteteri["vis_4"],$d[6],$visit_obsteteri["vis_5"],$d[7],$visit_obsteteri["vis_6"],$d[8],"");
								$f->text3($visit_obsteteri["vis_7"],$d[9],$visit_obsteteri["vis_8"],$d[10],$visit_obsteteri["vis_9"],$d[11],"");
								$f->text3($visit_obsteteri["vis_10"],$d[12],$visit_obsteteri["vis_11"],$d[13],$visit_obsteteri["vis_12"],$d[14],"");
								$f->execute();
								$f = new ReadOnlyForm2();
								$f->text2($visit_obsteteri["vis_15"],$d[17],$visit_obsteteri["vis_16"],$d[18],"");
								$f->text2($visit_obsteteri["vis_17"],$d[19],$visit_obsteteri["vis_18"],$d[20]);
								$f->text2($visit_obsteteri["vis_19"],$d[21],$visit_obsteteri["vis_20"],$d[22]);
								$f->hr();
								$f->text($visit_obsteteri["vis_21"],$d[23]);
								$f->hr();
								$f->text2($visit_obsteteri["vis_22"],$d[24],$visit_obsteteri["vis_23"],$d[25]);
								//$f->text($visit_obsteteri["vis_49"],$d[51]);
								//$f->hr();
								$f->info4("<B>Imunisasi(Dasar, Ulangan dan Tanggal/Umur)</B>");
								$f->text2($visit_obsteteri["vis_24"],$d[26],$visit_obsteteri["vis_25"],$d[27]);
								$f->text2($visit_obsteteri["vis_26"],$d[28],$visit_obsteteri["vis_27"],$d[29]);
								$f->text2($visit_obsteteri["vis_28"],$d[30],$visit_obsteteri["vis_29"],$d[31]);
								$f->hr();
								$f->info4("<B>Riwayat Terdahulu(Tanggal/Umur)</B>");
								$f->text2($visit_obsteteri["vis_30"],$d[32],$visit_obsteteri["vis_31"],$d[33]);
								$f->text2($visit_obsteteri["vis_32"],$d[34],$visit_obsteteri["vis_33"],$d[35]);
								$f->text2($visit_obsteteri["vis_34"],$d[36],$visit_obsteteri["vis_35"],$d[37]);
								$f->text2($visit_obsteteri["vis_36"],$d[38],$visit_obsteteri["vis_37"],$d[39]);
								$f->hr();
								$f->info4("<B>Riwayat Obsterik </B>");
								$f->text($visit_obsteteri["vis_38"],$d[40]);
								$f->text($visit_obsteteri["vis_39"],$d[41]);
								$f->text($visit_obsteteri["vis_40"],$d[42]);
								$f->text($visit_obsteteri["vis_41"],$d[43]);
								$f->text($visit_obsteteri["vis_42"],$d[44]);
								$f->text($visit_obsteteri["vis_43"],$d[45]);
								$f->text($visit_obsteteri["vis_44"],$d[46]);
								$f->text($visit_obsteteri["vis_45"],$d[47]);
								$f->text($visit_obsteteri["vis_46"],$d[48]);
								$f->text($visit_obsteteri["vis_47"],$d[49]);
								$f->text($visit_obsteteri["vis_49"],$d[51]);
								$f->hr();
								$f->text("Dokter ahli Obstetri/Genekologi",$d["nama"]);
								$f->execute();
							
				}elseif ($_GET["mPOLI"] == $setting_poli["paru"]){
						
						subtitle2("Klinik Paru / Alergi","center");
							?>
							    <tr valign="top" align="left">
									<td class='TBL_BODY' colspan='3'>
										<table class="FORM" align="left">
											<TR>
												<TD class="FORM" align="left">Keluhan Utama </td>
												<TD class="FORM" align="left">: </td>
												<TD class="FORM" align="left"><?=$d["vis_1"]?> </td>							
											</TR>
											<TR>
												<TD class="FORM" align="left">Riwayat Penyakit Sekarang </td>
												<TD class="FORM" align="left">: </td>
												<TD class="FORM" align="left"><?=$d["vis_2"]?> </td>							
											</TR>
											<TR>
												<TD class="FORM" align="left">Riwayat </td>
												<TD class="FORM" align="left">: </td>
												<TD class="FORM" align="left"><?=$d["vis_3"]?> </td>							
											</TR>
										</table>
									</td>		
								</tr> 
								<tr valign="top" align="left">
									<td class='TBL_BODY' colspan='3'>
										<table class="FORM" border="0" width="100%" >
											<TR><TD class="FORM" colspan="10"><b><u>PEMERIKSAAN JASMANI</u></b></td></TR>
											<TR>
												<TD width="141" class="FORM" >Keadaan Umum : </td>
												<TD class="FORM" width="145"><?=$d["vis_4"]?> </td>							
												<TD width="41" class="FORM" >BB : </td>							
												<TD class="FORM" width="105"><?=$d["vis_5"]?> &nbsp;kg </td>
												<TD width="89" class="FORM" >Tek Darah : </td>							
												<TD class="FORM" width="116"><?=$d["vis_6"]?> &nbsp;/menit</td>
												<TD width="46" class="FORM" >Nadi : </td>							
												<TD class="FORM" width="93"><?=$d["vis_7"]?> </td>
												<TD width="42" class="FORM" >Suhu : </td>							
												<TD class="FORM" width="116"><?=$d["vis_8"]?> </td>												
											</TR>
										</table><br>
									</td>		
								</tr>
								<tr valign="top" align="left">
									<td class='TBL_BODY' colspan='3'>
										<table class="FORM" border="0" width="100%">
											<TR>
												<TD class="FORM" width="41%"><b><u>Gambar</u></b></td>
												<TD class="FORM" colspan="4"><b><u>Laboratorium</u></b></td>							
											</TR>
											<TR>
												<TD class="FORM" rowspan="12"><img src="images/bg_tbh_06.gif"></td>
												<TD width="15%" align="left" class="FORM">Darah Lengkap :</td>							
												<TD width="12%" align="left" class="FORM">HB </td><td width="15%">: <?=$d["vis_9"]?></td>																				
											</TR>
											<tr><td></td><TD class="FORM" align="left">Leukosit </td><td>: <?=$d["vis_10"] ?></td></tr>
											<tr><td></td><TD class="FORM" align="left">L.E.D </td><td>: <?=$d["vis_11"] ?></td></tr>
											<tr><td></td><TD class="FORM" align="left">Diff </td><td>: <?=$d["vis_12"] ?></td></tr>
											<tr><td  class="form" align="left" colspan="4">Liver Fungsi Test : <?=$d["vis_13"] ?></td></tr>
											<tr>
												<td align="left" class="form" colspan="2">Gula darah : <?=$d["vis_14"] ?></td>
												<td align="left" class="form">N : <?=$d["vis_15"] ?></td>
												<td align="left" class="form">P.P : <?=$d["vis_16"] ?></td>							
											</tr>
											<tr>
												<td align="left" class="form">Urine : <?=$d["vis_17"] ?></td>
												<td align="left" class="form">Red : <?=$d["vis_18"] ?></td>						
												<td align="left" class="form">Prot : <?=$d["vis_19"] ?></td>
												<td width="17%" align="left" class="form">Sedimen : <?=$d["vis_20"] ?></td>													
											</tr>
											<? if ($d["vis_21"] == "BTA.Langsung") { $b1 = "<b>"; $img1 = "</b><img src='images/icon-ok.png'>"; }
												elseif ($d["vis_21"] == "Biakan.BTA") { $b2 = "<b>"; $img2 = "</b><img src='images/icon-ok.png'>"; }
												elseif ($d["vis_21"] == "M.O") { $b3 = "<b>"; $img3 = "</b><img src='images/icon-ok.png'>"; }
												elseif ($d["vis_21"] == "Sitologi") { $b4 = "<b>"; $img4 = "</b><img src='images/icon-ok.png'>"; }
											?>
											<tr><td align="left" class="form">Spuntum : </td>
														<td align="left" class="form" colspan="3"><?=$b1?>- BTA langsung <?=$img1 ?></td></tr>						
											<tr><td></td><td align="left" class="form" colspan="3"><?=$b2?>- Biakan BTA <?=$img2 ?></td></tr>					
											<tr><td></td><td align="left" class="form" colspan="3"><?=$b3?>- M.O <?=$img3 ?></td></tr>
											<tr><td></td><td align="left" class="form" colspan="3"><?=$b4?>- Sitologi <?=$img4 ?></td></tr>
											<tr><td align="left" class="form">Lain-lain</td><td align="left" colspan="3">:<?=$d["vis_22"] ?></td></tr>	
										</table>
									</td>		
								</tr>
								<tr valign="top">
									<td align="left" class="tbl_body" colspan="3">
										<table class="FORM" border="0" width="100%">
											<TR><TD width="15%" height="40" class="FORM" ><u>Mantouk Tes </u> </td><td width="44%" align="left" class="FORM">: <?=$d["vis_23"] ?></td></tr>
											<tr><td class="FORM" height="40"><u>Radiologi</u> </td><td class="FORM" align="left">: <?=$d["vis_24"] ?></td></tr>
											<tr><td class="FORM" height="40"><u>Diagnosa Kerja</u> </td><td class="FORM" align="left">: <?=$d["vis_27"] ?></td>
												<td width="20%" height="40" class="FORM"> D/.D./  &nbsp;<?=$d["vis_28"] ?></td>
											</tr>
											<tr><td class="FORM" height="40"><u>Terapi </u> </td><td class="FORM" align="left">: <?=$d["vis_25"] ?></td></tr>
											<tr><td class="FORM" height="40"><u>Saran / Rencana </u> </td><td class="FORM" align="left">: <?=$d["vis_26"] ?></td>
												<td class="FORM" ><u>Dokter yang memeriksa </u> </td><td width="21%" align="left" class="FORM">: <?=$d["nama"] ?></td></tr>
										</table>
								<!--</td>
								</tr>-->		    
							<?
									
							
				}elseif ($_GET["mPOLI"] == $setting_poli["laboratorium"]){
						
						subtitle2("Klinik Laboratorium","center");			
						$max = count($visit_laboratorium) ; 
						$i = 1;
						echo "<tr><td class='TBL_BODY' valign=top colspan='3'>";
						$f = new ReadOnlyForm2();
						while ($i<= $max) {		
							if ($visit_laboratorium["vis_".$i."F"] == "memo") {
							
								$f->text($visit_laboratorium["vis_".$i],$d[2+$i] );
							}
							if ($visit_laboratorium["vis_".$i."F"] == "memo1") {
							
								$f->text($visit_laboratorium["vis_".$i],$d[2+$i]);
							}
							
							$i++ ; 	
						}
						$f->text("Dokter",$d["nama"]);
						$f->execute();
						
						
				}elseif ($_GET["mPOLI"] == $setting_poli["radiologi"]){
						
						subtitle2("Klinik Radiologi","center");		
						$max = count($visit_radiologi) ; 
						$i = 1;
						echo "<tr><td class='tbl_body' valign=top colspan='3'>";
						$f = new ReadOnlyForm2();
						while ($i<= $max) {		
							if ($visit_radiologi["vis_".$i."F"] == "memo") {
							
								$f->text($visit_radiologi["vis_".$i],$d[2+$i] );
							}
							if ($visit_radiologi["vis_".$i."F"] == "memo1") {
							
								$f->text($visit_radiologi["vis_".$i],$d[2+$i]);
							}
							
							$i++ ; 	
						}
						$f->text("Dokter",$d["nama"]);
						$f->execute();
				}elseif ($_GET["mPOLI"] == $setting_poli["saraf"]){
						subtitle2("Klinik Saraf","center");		
						$max = count($visit_saraf) ; 
						$i = 1;
						echo "<tr><td class='tbl_body' valign=top colspan='3'>";
						$f = new ReadOnlyForm2();
						while ($i<= $max) {		
							if ($visit_saraf["vis_".$i."F"] != "") {
							
								$f->text($visit_saraf["vis_".$i],$d[2+$i] );
							}
							
							$i++ ; 	
						}
						$f->text("Dokter",$d["nama"]);
						$f->execute();
						
				}elseif ($_GET["mPOLI"] == $setting_poli["fisioterapi"]){
						
						subtitle2("Klinik Fisioterapi","center");		
						echo "<tr><td class='tbl_body' valign=top colspan='3'>";
						$max = count($visit_fisioterapi) ; 
						$i = 1;
						$f = new ReadOnlyForm2();
						while ($i<= $max) {		
							
							if ($visit_fisioterapi["vis_".$i."F"] == "memo") {
							
								$f->text($visit_fisioterapi["vis_".$i],$d[2+$i]);
							}
							
							$i++ ; 	
						}
						$f->text("Dokter",$d["nama"]);
						$f->execute();
				
				}elseif ($_GET["mPOLI"] == $setting_poli["psikiatri"]){
						
						subtitle2("Klinik Psikiatri","center");		
						echo "<tr><td class='tbl_body' valign=top colspan='3'>";
						$f = new ReadOnlyForm();
						$f->title1("PEMERIKSAAN PASIEN","LEFT");
						$f->text("Tanggal Pemeriksaan","<b>".$d["tgl_periksa"]."</b>");
						$f->text($visit_psikiatri["vis_1"],$d[3] );
						$f->text($visit_psikiatri["vis_2"],$d[4]);
						$f->text($visit_psikiatri["vis_3"],$d[5]);
						$f->title1("DIAGNOSA KERJA","LEFT");	
						$f->text($visit_psikiatri["vis_4"],$d[6] );
						$f->title1("DOKTER PEMERIKSA","LEFT");
						$f->text("Nama",$d["nama"]);
						$f->execute();   	
									
				}elseif ($_GET["mPOLI"] == $setting_poli["umum"]){
						
						subtitle2("Klinik Umum","center");
						echo "<tr><td class='TBL_BODY' colspan='3' align='center'>";
						$t = new PgTable($con, "100%");
					    $t->SQL =  "select to_char(a.tanggal_reg,'dd mm yyyy hh:mm')as tanggal_reg,a.vis_1,a.vis_11,a.vis_12,b.nama 
									from c_visit a
									left join rs00017 b on a.id_dokter = b.id 
									left join rsv0002 c on a.no_reg=c.id 
									where a.id_poli='{$_GET["mPOLI"]}' and c.mr_no='{$_GET["mr"]}' " ;
					    $t->setlocale("id_ID");
					    $t->ShowRowNumber = true;
					    $t->ColHeader = array( "Tanggal","Anamnesa/ Pemeriksaan/ Pengobatan","Diagnosis","No Kode<br>Diagnosis","Dokter");
					    $t->ColAlign = array("center","left","left","center","left");
						if($GLOBALS['print']){
							$t->RowsPerPage = 30;			    
							$t->DisableNavButton = true;
							$t->DisableScrollBar = true;
							$t->DisableSort = true;
						}else {
							$t->RowsPerPage = $ROWS_PER_PAGE;			    
						}				
						$t->execute();
				}
				
					
				echo "</td></tr></table>"; 
				
						include ("rm_tindakan.php");
				
				echo "</DIV>";
				
				//echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
		}else {	 	
			$SQL = "select mr_no,nama,to_char(tgl_lahir,'dd Mon yyyy')as tgl_lahir,umur,alm_tetap,tlp_tetap ".
					"from rs00002 where mr_no='{$_GET["mr"]}' ";
			$r = pg_query($con,$SQL);
				$n = pg_num_rows($r);
			    if($n > 0) $d = pg_fetch_object($r);
			    pg_free_result($r);
				//echo $_GET["mr"];
				$f = new ReadOnlyForm();
				$f->text("Nama","<b>". $d->nama ."</b>");
			    $f->text("NO.MR","<b>". $d->mr_no ."</b>");
			  	$f->execute();
			    		
				echo "<DIV >";
				echo "<br>";
				
					$t = new PgTable($con, "100%");
				    $t->SQL = 	"SELECT a.id,to_char(a.tanggal_reg,'DD Mon YYYY'),to_char(a.waktu_reg,'HH:MM:SS'),d.tdesc , ".
				    			"	to_char(a.tgl_keluar,'DD Mon YYYY'),a.diagnosa_sementara, ".
				    			"	CASE
							            WHEN a.status_akhir_pasien = '-' THEN 
							            CASE
							                WHEN a.periksa = 'N' THEN 'MENUNGGU'
							                ELSE 'SUDAH DIPERIKSA'
							            END
							            ELSE h.tdesc
							        END AS status_akhir,a.mr_no, a.poli ".
								"FROM rs00006 a ".
								"LEFT JOIN rs00034 b ON a.poli=b.id ".
								"LEFT JOIN rs00001 c ON a.status_akhir_pasien = c.tc AND c.tt='SAP' ".
								"LEFT JOIN rs00001 d ON a.poli = d.tc_poli AND d.tt='LYN' ".
								"LEFT JOIN rs00001 h ON a.status_akhir_pasien = h.tc AND h.tt = 'SAP' ".
								"WHERE a.mr_no='{$_GET["mr"]}'" ;
				    $t->setlocale("id_ID");
				    $t->ShowRowNumber = true;
				    //$t->ColHidden[8] = true;
				    $t->ColHidden[8] = true;
				    $t->ColHeader = array( "NO.REG", "TGL.REG","WAKTU REG","KLINIK","TGL.KELUAR","ANAMNESA","STATUS","","DETAIL");
				    $t->ColAlign = array("center","center","center","left","center","left","center","","center");
					if (!$GLOBALS['print']){
						$t->RowsPerPage = $ROWS_PER_PAGE;
						$t->ColFormatHtml[8] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&act=show&list=riwayat&act2=detail&id=<#0#>&mPOLI=<#8#>&mr=<#7#>'>".icon("view","View")."</A>";
					}else {
						$t->RowsPerPage = 20;
						$t->ColFormatHtml[8] = icon("view2","View");
						$t->DisableNavButton = true;
						$t->DisableScrollBar = true;
						//$t->DisableStatusBar = true;
					}
				
					$t->execute();
					
				echo "</div>";
				//echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
			//	echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>"; 
		}
	}else {
		$T->show(0); 
		if ($no_mr != null) {
			$SQL = "SELECT a.*,b.id_dokter FROM rsv_pasien2 a ".
					"LEFT JOIN c_visit b ON a.id=b.no_reg ".
			   		"WHERE a.mr_no='{$_GET["mr"]}'";
					/*$SQL = "SELECT a.*,b.tdesc as agama, c.tdesc as status_nikah,to_char(a.tgl_lahir,'dd Month yyyy')as tgl_lahir,
						CASE 
				            WHEN a.resus_faktor = '1' THEN 'Negatif'
				            WHEN a.resus_faktor = '2' THEN 'Positif'
				            ELSE '-'
				        END AS resus_faktor, 
				        CASE
				            WHEN a.gol_darah = '1' THEN 'A'
				            WHEN a.gol_darah = '2' THEN 'B'
				            WHEN a.gol_darah = '3' THEN 'AB'
				            WHEN a.gol_darah = '4' THEN 'O'
				            ELSE '-'
				        END AS gol_darah, 
				        CASE
				            WHEN a.jenis_kelamin = 'L' THEN 'Laki-laki'
				            ELSE 'Perempuan'
				        END AS jenis_kelamin 
					FROM rs00002 a 
					LEFT JOIN rs00001 b ON a.agama_id = b.tc AND b.tt = 'AGM'
					LEFT JOIN rs00001 c ON a.status_nikah = substr(c.tc, 3) AND c.tt = 'SNP'
					WHERE a.mr_no='{$_GET["mr"]}'";*/
		}else{
			$SQL = "SELECT a.*,b.id_dokter FROM rsv_pasien2 a ".
					"LEFT JOIN c_visit b ON a.id=b.no_reg ".
			   		"WHERE a.mr_no='{$_GET["mr"]}'";
			/*$SQL = "SELECT a.*,b.tdesc as agama, c.tdesc as status_nikah,to_char(a.tgl_lahir,'dd Month yyyy')as tgl_lahir,
						CASE 
				            WHEN a.resus_faktor = '1' THEN 'Negatif'
				            WHEN a.resus_faktor = '2' THEN 'Positif'
				            ELSE '-'
				        END AS resus_faktor, 
				        CASE
				            WHEN a.gol_darah = '1' THEN 'A'
				            WHEN a.gol_darah = '2' THEN 'B'
				            WHEN a.gol_darah = '3' THEN 'AB'
				            WHEN a.gol_darah = '4' THEN 'O'
				            ELSE '-'
				        END AS gol_darah, 
				        CASE
				            WHEN a.jenis_kelamin = 'L' THEN 'Laki-laki'
				            ELSE 'Perempuan'
				        END AS jenis_kelamin 
					FROM rs00002 a 
					LEFT JOIN rs00001 b ON a.agama_id = b.tc AND b.tt = 'AGM'
					LEFT JOIN rs00001 c ON a.status_nikah = substr(c.tc, 3) AND c.tt = 'SNP'
					WHERE a.mr_no='{$_GET["mr"]}'";*/
		}
			$r =  pg_query($con,$SQL);
				$n = pg_num_rows($r);
			    if($n > 0) $d = pg_fetch_object($r);
			    pg_free_result($r);
					$no_mr=$d->mr_no;
		
			echo "<table border='0' width='100%'><tr><td width='50%'>";	
			$f = new ReadOnlyForm2();
			$f->text("Nama","<B>".$d->nama."</B>");		
			$f->text("Pangkat / NRP",$d->pangkat_gol);		
			$f->text("NRP / NIP",$d->nrp_nip);		
			$f->text("Kesatuan",$d->kesatuan);		
			$f->text("Tempat & Tgl Lahir",$d->tmp_lahir." & ".$d->tgl_lahir);
			$f->text("Umur",$d->umur);
			$f->text("Status Perkawinan",$d->status_nikah);
			$f->text("Seks",$d->jenis_kelamin);
			$f->text("Agama",$d->agama);
			$f->text("Pendidikan Terakhir","");
		   	$f->text("Kesatuan",$d->kesatuan);
		   	$f->text("Faktor Resus",$d->resus_faktor);   
		   	$f->execute();
		   	
		   	echo "</td><td width='50%' valign='top'>";
		   	
		   	$f = new ReadOnlyForm2();
		   	$f->text("No.MR","<B>".$d->mr_no."</B>");
		   	$f->text("Gol. Darah",$d->gol_darah);
		   	$f->text("Nama Lengkap Ibu",$d->nama_ibu);
		   	$f->text("Nama Lengkap Ayah",$d->nama_ayah);
		   	$f->text("Pekerjaan Ayah/Suami/Istri/Sendiri",$d->pekerjaan);
		   	$f->text("Penjamin",$d->penjamin);
		   	$f->text("Penanggung",$d->penanggung);
		   	$f->text("Nama Penanggung",$d->nm_penanggung);
		   	$f->text("Hubungan terhadap Pasien",$d->hub_penanggung);
		   	$f->text("Rujukan Dari",$d->rujukan_rs);
		   	$f->text("Dokter yang mengirim",$d->rujukan_dokter);
		   	$f->execute();
		   	echo "</td></tr></table>";
		   	echo "<hr noshade color=#999999 size=1><table border='0' width='100%'> ";
		   	echo "<tr><td class=FORM><u><b>Perubahan Alamat</b></u></td></tr><tr><td width='50%'>";
		   	$f = new ReadOnlyForm2();
		   	$f->info("<b>Alamat I</b>");
		   	$f->text("Alamat Tetap",$d->alm_tetap);
		   	$f->text("Kota ",$d->kota_tetap);
		   	$f->text("Kodepos ",$d->pos_tetap);
		   	$f->info("<b>Alamat II</b>","");
		   	$f->text("Alamat Keluarga",$d->alm_keluarga);
		   	$f->text("Kota ",$d->kota_keluarga);
		   	$f->text("Kodepos ",$d->pos_keluarga);
		   	$f->execute();
		   	echo "</td><td width='50%' valign='top'>";
		   	$f = new ReadOnlyForm2();
		   	$f->info("<b>Alamat III</b>","");
		   	$f->text("Alamat Sementara",$d->alm_sementara);
		   	$f->text("Kota ",$d->kota_sementara);
		   	$f->text("Kodepos ",$d->pos_sementara);
		   	$f->info("<b>Alamat IV</b>","");
		   	$f->text("Alamat Lain-lain","");
		   	$f->text("Kota ","");
		   	$f->text("Kodepos ","");
		   	$f->execute();
		   	echo "</td></tr></table>";
		
	}
}else {
	//echo "<br>";
 	$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
	echo "<table width='100%'><tr><td>";
	    $f = new Form($SC, "GET", "NAME=Form1");
	    //$f = new Form($SC, "GET");
	    $f->PgConn = $con;
	    $f->hidden("p", $PID);
			include(xxx);
		if (!$GLOBALS['print']){
			$f->submit(" Laporan ");
		}
		$f->execute();
		$f->hidden("ts_check_in1",'$ts_check_in1');
		$f->hidden("ts_check_in2",'$ts_check_in2');
				
				echo "</td><td width='50%' align='right' valign='middle'>";
					$f = new Form($SC, "GET","NAME=Form2");
				    $f->hidden("p", $PID);
				    if (!$GLOBALS['print']){
				    	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
					}else { 
					   	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","disabled");
					}
				    $f->execute();
			    	if ($msg) errmsg("Error:", $msg);
				echo "</td></tr></table>";
    
			
			$SQL = "select distinct a.mr_no,to_char(a.tgl_reg,'dd Mon YYYY')as tanggal_reg,a.nama,a.pangkat_gol,a.nrp_nip,a.kesatuan,a.alm_tetap ".
					"from rs00002 a ";		

			if ($_GET["search"]) {
				$SQLWHERE =
					"WHERE (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' or a.mr_no like '%".$_GET["search"]."%' ".
					" or upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%' or a.nrp_nip like '%".$_GET['search']."%' ".
					" or upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' or upper(a.alm_tetap) like '%".strtoupper($_GET["search"])."%') ";
			}else{
				$SQLWHERE ="where a.tgl_reg between '$ts_check_in1' and '$ts_check_in2' ";
			}
					//echo "$SQL $SQLWHERE";			
			echo "<DIV >";
			//echo "<br>";
			
				$t = new PgTable($con, "100%");
			    $t->SQL = "$SQL $SQLWHERE" ;
			    $t->setlocale("id_ID");
			    $t->ShowRowNumber = true;
			    //$t->RowsPerPage = $ROWS_PER_PAGE;
			    $t->ColHeader = array( "NO.MR","TGL.REG", "NAMA","PANGKAT","NRP/NIP","KESATUAN","ALAMAT");
			    $t->ColAlign = array("center","center","left","left","center","left","left");
			    if (!$GLOBALS['print']){
					$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&act=show&list=identitas&mr=<#0#>'><#2#></A>";
					$t->RowsPerPage = $ROWS_PER_PAGE;
			    }else {
			    	$t->RowsPerPage = 35;
			    	$t->DisableNavButton = true;
				$t->DisableScrollBar = true;
				
			    }
				$t->execute();
				
			echo "</div>";
			
}
}
?>