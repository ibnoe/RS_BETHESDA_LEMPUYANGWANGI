<?php
//  hery-- may 28, 2007 

$PID = "rm_kebidanan";
$SC = $_SERVER["SCRIPT_NAME"];
session_start();

if (!empty($_SESSION[uid])) {

require_once("startup.php");
require_once("lib/visit_setting.php");

$_GET["mPOLI_obs"]=$setting_poli["kebidanan_obstetri"];
$_GET["mPOLI_gin"]=$setting_poli["kebidanan_ginekologi"];

if ($_GET["list"] == "obstetri"){
	if(!$GLOBALS['print']){
		title_print("<img src='icon/medical-record-2.gif' align='absmiddle'> REKAM MEDIS KLINIK KEBIDANAN DAN PENYAKIT KANDUNGAN (OBSTETRI)");
	}else {
		title_print("<img src='icon/medical-record.gif' align='absmiddle'> REKAM MEDIS KLINIK KEBIDANAN DAN PENYAKIT KANDUNGAN (OBSTETRI)");
	}
}else {
	if(!$GLOBALS['print']){
		title_print("<img src='icon/medical-record-2.gif' align='absmiddle'> REKAM MEDIS KLINIK KEBIDANAN DAN PENYAKIT KANDUNGAN (GINEKOLOGI)");
	}else {
		title_print("<img src='icon/medical-record.gif' align='absmiddle'> REKAM MEDIS KLINIK KEBIDANAN DAN PENYAKIT KANDUNGAN (GINEKOLOGI)");
	}
}

$tab_disabled = array("obstetri"=>true, "ginekologi"=>true);
if ($_GET["act"] == "detail" ) {
	$tab_disabled = array("obstetri"=>false, "ginekologi"=>false);
	$tab_disabled = array("obstetri"=>true, "ginekologi"=>true);
	
	$tab_disabled[$_GET["list"]] = true;
	$tab_disabled[$_POST["list"]] = true;
}

$T = new TabBar();
if(!$GLOBALS['print']){
	$T->addTab("index2.php?p=rm_kebidanan&list=obstetri", " OBSTETRI "	, $tab_disabled["obstetri"]);
	$T->addTab("index2.php?p=rm_kebidanan&list=ginekologi", " GINEKOLOGI ", $tab_disabled["ginekologi"]);
}else {
	$T->addTab("", " OBSTETRI "	, $tab_disabled["obstetri"]);
	$T->addTab("", " GINEKOLOGI ", $tab_disabled["ginekologi"]);
}

	if (!$GLOBALS['print']){
		echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
	}
if ($_GET["list"] == "ginekologi") {
		$T->show(1);
    	
    	if ($_GET['act'] ==  "detail"){
				
				//echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
			    echo "<br>";
				$sql = "select a.*,b.nama, to_char(a.tanggal_reg,'DD Month YYYY')as tgl_periksa 
			    		from c_visit a
						left join rs00017 b on a.id_dokter = b.id 
						left join rsv0002 c on a.no_reg=c.id 
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
			echo "</td></tr></table>";   
				 		    
				echo "<table class='TBL_BORDER' border='0' width='100%' cellspacing=1 cellpadding=0>";
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
				echo "</td></tr></table>"; 
				
							include ("rm_tindakan.php");
				
				echo "</DIV>";
			/*	if(!$GLOBALS['print']){
					//echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
					echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
				}
			*/	
		}else {
			if ($_GET["v"]){ // v from 430.php
		    	subtitle2("KLINIK KEBIDANAN & PENYAKIT KANDUNGAN (GENEKOLOGI)","left");		
		    			    	
		    }else {
		    	
				 if(!$GLOBALS['print']){				
		    		$ext = "OnChange = 'Form1.submit();'";
				 }else{
				 	$ext = "disabled";
				 }
			    echo "<br>";
			   	echo "<table border='0' width='100%'><tr><td width='50%' align='left'>";
				    $f = new Form($SC, "GET", "NAME=Form1");
				    $f->PgConn = $con;
				    $f->hidden("p", $PID);
				    $f->hidden("list", "ginekologi");				  
				    $f->selectArray2("mBULAN","B u l a n",Array("1"=>"Januari","2"=>"Februari","3"=>"Maret","4"=>"April",
				         "5"=>"Mei","6"=>"Juni","7"=>"Juli","8"=>"Agustus","9"=>"September","10"=>"Oktober",
						 "11"=>"November","12"=>"Desember"),$_GET["mBULAN"],$ext);       
					
				    $f->selectSQL2("mTAHUN", "T a h u n",
				        "select distinct to_char(tanggal_reg,'yyyy'), to_char(tanggal_reg,'yyyy') from rs00006"
				        , $_GET["mTAHUN"],$ext);
								      
					$f->execute();
				    
					$start_tgl = mktime(0,0,0,$_GET[mBULAN],1,$_GET[mTAHUN]);
				    $max_tgl = date("t", $start_tgl);
				    $end_tgl = mktime(0,0,0,$_GET[mBULAN],$max_tgl,$_GET[mTAHUN]);
				    $start_tgl = date("Y-m-d", $start_tgl);
				    $end_tgl = date("Y-m-d", $end_tgl);
					
						echo "</td><td width='50%' align='right' valign='middle'>";
							$f = new Form($SC, "GET","NAME=Form2");
						    $f->hidden("p", $PID);
						    $f->hidden("list", "ginekologi");
						    if (!$GLOBALS['print']){
						    	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
							}else { 
							   	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","disabled");
							}
						    $f->execute();
					    	if ($msg) errmsg("Error:", $msg);
						echo "</td></tr></table>";
		    }
		    
		    		$tglhariini = substr(date("Y-m-d", time()),0,10);
		    		 
					$SQL = "select a.id,a.mr_no,a.nama,a.pangkat_gol,a.nrp_nip,a.kesatuan,to_char(b.tanggal_reg,'dd Mon yyyy') as tanggal_reg,a.alm_tetap, 'dummy' ".
							"from rsv_pasien2 a ".
							"left join c_visit b ON a.id=b.no_reg ".
							"where a.id=b.no_reg and b.id_poli='{$_GET["mPOLI_gin"]}' ";
												
					if ($_GET["v"]){
						$SQLWHERE = "";
					}elseif ($_GET["search"]) {
						$SQLWHERE =
							"and (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' or a.id like '%".$_GET['search']."%' or a.mr_no like '%".$_GET["search"]."%' ".
							" or upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%' or a.nrp_nip like '%".$_GET['search']."%' ".
							" or upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' or upper(a.alm_tetap) like '%".strtoupper($_GET["search"])."%') ";
					}elseif ($_GET["mBULAN"] || $_GET["mTAHUN"]) {
						$SQLWHERE = "and (b.tanggal_reg >=  '$start_tgl' and b.tanggal_reg <= '$end_tgl') ";
					}else {
						$SQLWHERE = "and TO_CHAR(b.tanggal_reg,'dd Mon')= '$tglhariini' ";
					}
								
					echo "<DIV >";
					echo "<br>";
					
						$t = new PgTable($con, "100%");
					    $t->SQL = "$SQL $SQLWHERE" ;
					    $t->setlocale("id_ID");
					   	$t->ShowRowNumber = true;
					   	$t->ColHidden[8] = true;//8+1(rownumber)
					   	//$t->ColRowSpan[0] = 1;
					   	$t->ColHeader = array( "NO.REG","NO.RM", "NAMA","PANGKAT","NRP/NIP","KESATUAN","TGL PERIKSA","","");
						$t->ColAlign = array("center","center","left","left","center","left","center","","center");
						if (!$GLOBALS['print']){
							$t->RowsPerPage = $ROWS_PER_PAGE;
					    	$t->ColFormatHtml[8] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&list=ginekologi&act=detail&id=<#0#>&mr=<#1#>'>".icon("view","View")."</A>";
					    }else {
					    	$t->ColFormatHtml[8] = icon("view2","View");
					    	$t->RowsPerPage = 30;
					    	$t->ColHidden[8] = true;
					    	$t->DisableNavButton = true;
						$t->DisableScrollBar = true;
							
					    }
						$t->execute();
		    	
					echo "</div>";						
								
		}
    	
}else {
    	$T->show(0);
		
		if ($_GET['act'] ==  "detail"){
				//title(" <img src='icon/medical-record-2.gif' align='absmiddle' > Rekam Medis Klinik Kebidanan (Obstetri)");
				
			    echo "<br>";
				$sql = "select a.*,b.nama 
			    		from c_visit a
						left join rs00017 b on a.id_dokter = b.id 
						left join rsv0002 c on a.no_reg=c.id 
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
			echo "</td></tr></table>";  
				
			echo "<table class='TBL_BORDER' border='0' width='100%' cellspacing=1 cellpadding=0>";
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
				echo "</td></tr></table>"; 
				
							include ("rm_tindakan.php");
				
				echo "</DIV>";
				/*if(!$GLOBALS['print']){
					//echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
					echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
				}
				*/
		}else {
			if ($_GET["v"]){ // v from 430.php
		    	subtitle2("KLINIK KEBIDANAN & PENYAKIT KANDUNGAN (OBSTETRI)","left");		
		    			    	
		    }else {
		    	
				if(!$GLOBALS['print']){				
		    		$ext = "OnChange = 'Form1.submit();'";
				 }else{
				 	$ext = "disabled";
				 }
			    echo "<br>";
			   echo "<table border='0' width='100%'><tr><td width='50%' align='left'>";
				    $f = new Form($SC, "GET", "NAME=Form1");
				    $f->PgConn = $con;
				    $f->hidden("p", $PID);
				  	$f->hidden("list", "obstetri");
				    $f->selectArray2("mBULAN","B u l a n",Array("1"=>"Januari","2"=>"Februari","3"=>"Maret","4"=>"April",
				         "5"=>"Mei","6"=>"Juni","7"=>"Juli","8"=>"Agustus","9"=>"September","10"=>"Oktober",
						 "11"=>"November","12"=>"Desember"),$_GET["mBULAN"],$ext);       
					
				    $f->selectSQL2("mTAHUN", "T a h u n",
				        "select distinct TO_CHAR(tanggal_reg,'YYYY'), TO_CHAR(tanggal_reg,'YYYY') from rs00006 "
				        , $_GET["mTAHUN"],$ext);
				
				      
					$f->execute();
				    
					$start_tgl = mktime(0,0,0,$_GET[mBULAN],1,$_GET[mTAHUN]);
				    $max_tgl = date("t", $start_tgl);
				    $end_tgl = mktime(0,0,0,$_GET[mBULAN],$max_tgl,$_GET[mTAHUN]);
				    $start_tgl = date("Y-m-d", $start_tgl);
				    $end_tgl = date("Y-m-d", $end_tgl);
					
						echo "</td><td width='50%' align='right' valign='middle'>";
							$f = new Form($SC, "GET","NAME=Form2");
						    $f->hidden("p", $PID);
						    $f->hidden("list", "obstetri");
				    		if (!$GLOBALS['print']){
						    	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
							}else { 
							   	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","disabled");
							}
						    $f->execute();
					    	if ($msg) errmsg("Error:", $msg);
						echo "</td></tr></table>";
						
		    }
		    
		    		$tglhariini = substr(date("Y-m-d", time()),0,10);
		    		 
					$SQL = "select a.id,a.mr_no,a.nama,a.pangkat_gol,a.nrp_nip,a.kesatuan,to_char(b.tanggal_reg,'dd Mon yyyy') as tanggal_reg,a.alm_tetap, 'dummy' ".
							"from rsv_pasien2 a ".
							"left join c_visit b ON a.id=b.no_reg ".
							"where a.id=b.no_reg and b.id_poli='{$_GET["mPOLI_obs"]}' ";
												
					if ($_GET["v"]){
						$SQLWHERE = "";
					}elseif ($_GET["search"]) {
						$SQLWHERE =
							"and (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' or a.id like '%".$_GET['search']."%' or a.mr_no like '%".$_GET["search"]."%' ".
							" or upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%' or a.nrp_nip like '%".$_GET['search']."%' ".
							" or upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' or upper(a.alm_tetap) like '%".strtoupper($_GET["search"])."%') ";
					}elseif ($_GET["mBULAN"] || $_GET["mTAHUN"]) {
						$SQLWHERE = "and (b.tanggal_reg >=  '$start_tgl' and b.tanggal_reg <= '$end_tgl') ";
					}else {
						$SQLWHERE = "and TO_CHAR(b.tanggal_reg,'dd Mon')= '$tglhariini' ";
					}
								
					echo "<DIV >";
					echo "<br>";
					
						$t = new PgTable($con, "100%");
					    $t->SQL = "$SQL $SQLWHERE" ;
					    $t->setlocale("id_ID");
					   	$t->ShowRowNumber = true;
					   	$t->ColHidden[8] = true;//8+1(rownumber)
					   	//$t->ColRowSpan[0] = 1;
					   	$t->ColHeader = array( "NO.REG","NO.RM", "NAMA","PANGKAT","NRP/NIP","KESATUAN","TGL PERIKSA","","");
						$t->ColAlign = array("center","center","left","left","center","left","center","","center");
						if (!$GLOBALS['print']){
							$t->RowsPerPage = $ROWS_PER_PAGE;
					    	$t->ColFormatHtml[8] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&list=obstetri&act=detail&id=<#0#>&mr=<#1#>'>".icon("view","View")."</A>";
					    }else {
					    	$t->ColFormatHtml[8] = icon("view2","View");
					    	$t->RowsPerPage = 30;
					    	$t->ColHidden[8] = true;
					    	$t->DisableNavButton = true;
						$t->DisableScrollBar = true;
							
					    }
						$t->execute();
		    	
					echo "</div>";
					
		}
		
}
}
?>