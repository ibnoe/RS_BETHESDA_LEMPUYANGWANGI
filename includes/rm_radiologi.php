<?php
//  hery-- may 28, 2007 

$PID = "rm_radiologi";
$SC = $_SERVER["SCRIPT_NAME"];
session_start();

if (!empty($_SESSION[uid])) {

require_once("startup.php");
require_once("lib/visit_setting.php");

//echo "<hr noshade size=1>";
$_GET["mPOLI"]=$setting_poli["radiologi"];

if ($_GET['act'] ==  "detail"){
		if (!$GLOBALS['print']){
			title_print("<img src='icon/medical-record-2.gif' align='absmiddle' > REKAM MEDIS KLINIK RADIOLOGI");
			echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
		}else{
			title_print("<img src='icon/medical-record.gif' align='absmiddle' > REKAM MEDIS KLINIK RADIOLOGI");
		}
		    //echo "<br>";
			$sql = "select a.*,(b.nama)as priksa,(d.nama)as kirim 
		    		from c_visit a
					left join rs00017 b on a.id_dokter = b.id 
					left join rsv0002 c on a.no_reg=c.id 
					left join rs00017 d on a.id_dokter=d.id 
					where a.no_reg='{$_GET['id']}' ";
			$r = @pg_query($con,$sql);
			$n = @pg_num_rows($r);
		    if($n > 0) $d = @pg_fetch_array($r);
		    @pg_free_result($r);
		
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
				$f->text("Dokter Pemeriksa",$d["priksa"]);
				$f->text("Dokter Pengirim",$d["kirim"]);
				$f->execute();
				echo "</td></tr></table>"; 
				
							include ("rm_tindakan.php");
								
		echo "</DIV>";
}else {
	if ($_GET["v"]){ // v from 430.php
    	subtitle2("KLINIK RADIOLOGI","left");		
    		    	
    }else {
    	
		if (!$GLOBALS['print']){
			title_print("<img src='icon/medical-record-2.gif' align='absmiddle' > REKAM MEDIS KLINIK RADIOLOGI");
			$ext = "OnChange = 'Form1.submit();'";
    	}else {
    		title_print("<img src='icon/medical-record.gif' align='absmiddle' > REKAM MEDIS KLINIK RADIOLOGI");
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
		        "select distinct TO_CHAR(tanggal_reg,'yyyy'), TO_CHAR(tanggal_reg,'yyyy') from rs00006 "
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
