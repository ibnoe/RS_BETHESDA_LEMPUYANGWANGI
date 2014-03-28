<?php
//  hery-- may 28, 2007 

$PID = "rm_paru";
$SC = $_SERVER["SCRIPT_NAME"];
session_start();

if (!empty($_SESSION[uid])) {

require_once("startup.php");
require_once("lib/visit_setting.php");

//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_poli["paru"];

if ($_GET['act'] ==  "detail"){
		if (!$GLOBALS['print']){
			title_print("<img src='icon/medical-record-2.gif' align='absmiddle' > REKAM MEDIS KLINIK PARU DAN ALERGI");
			echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
		}else{
			title_print("<img src='icon/medical-record.gif' align='absmiddle' > REKAM MEDIS KLINIK PARU DAN ALERGI");
		}
		    //echo "<br>";
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
		    echo "</td></tr>"; 
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
				</td>
			</tr>		    
		<?
		echo "</table>"; 
		
				include ("rm_tindakan.php");
		
		echo "</DIV>";
		
}else {
	if ($_GET["v"]){ // v from 430.php
    	subtitle2("KLINIK PARU / ALERGI","left");		
    			    	
    }else {
		if (!$GLOBALS['print']){
			title_print("<img src='icon/medical-record-2.gif' align='absmiddle' > REKAM MEDIS KLINIK PARU / ALERGI");
			$ext = "OnChange = 'Form1.submit();'";
    	}else {
    		title_print("<img src='icon/medical-record.gif' align='absmiddle' > REKAM MEDIS KLINIK PARU / ALERGI");
    		$ext = "disabled";
    	}
		
		    //echo "<br>";
		    echo "<table border='0' width='100%'><tr><td width='50%' align='left'>";
		    $f = new Form($SC, "GET", "NAME=Form1");
		    $f->PgConn = $con;
		    $f->hidden("p", $PID);
		  
		    $f->selectArray2("mBULAN","B u l a n",Array("1"=>"Januari","2"=>"Februari","3"=>"Maret","4"=>"April",
		         "5"=>"Mei","6"=>"Juni","7"=>"Juli","8"=>"Agustus","9"=>"September","10"=>"Oktober",
				 "11"=>"November","12"=>"Desember"),$_GET["mBULAN"],$ext);       
			
		    $f->selectSQL2("mTAHUN", "T a h u n",
		        "select distinct to_char(tanggal_reg,'yyyy'), to_char(tanggal_reg,'yyyy') from rs00006 "
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
					"where a.id=b.no_reg and b.id_poli='{$_GET["mPOLI"]}' ";
										
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
				$SQLWHERE = "and substr(b.tanggal_reg,1,10)= '$tglhariini' ";
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
			    	$t->ColFormatHtml[8] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&act=detail&id=<#0#>&mr=<#1#>'>".icon("view","View")."</A>";
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

?>