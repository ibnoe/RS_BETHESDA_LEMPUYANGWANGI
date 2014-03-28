<?php
//  apep-- juli 28, 2007 

$PID = "p_kebidanan";
$SC = $_SERVER["SCRIPT_NAME"];
session_start();

if (!empty($_SESSION[uid])) {

require_once("startup.php");
require_once("lib/visit_setting.php");

$_GET["mPOLI_obs"]=$setting_poli["kebidanan_obstetri"];
$_GET["mPOLI_gin"]=$setting_poli["kebidanan_ginekologi"];



$tab_disabled = array("obstetri"=>true, "ginekologi"=>true,"obstetri_kon"=>true, "ginekologi_kon"=>true);
if ($_GET["act"] == "detail" ) {
	$tab_disabled = array("obstetri"=>false, "ginekologi"=>false,"obstetri_kon"=>false, "ginekologi_kon"=>false);
	$tab_disabled = array("obstetri"=>true, "ginekologi"=>true,"obstetri_kon"=>true, "ginekologi_kon"=>true);
	
	$tab_disabled[$_GET["tab"]] = true;
	$tab_disabled[$_POST["tab"]] = true;
}
$T = new TabBar();

if(!$GLOBALS['print']){
	$T->addTab("index2.php?p=p_kebidanan&tab=obstetri", " OBSTETRI "	, $tab_disabled["obstetri"]);
	$T->addTab("index2.php?p=p_kebidanan&tab=obstetri_kon", " PASIEN KONSUL OBSTETRI "	, $tab_disabled["obstetri_kon"]);
	$T->addTab("index2.php?p=p_kebidanan&tab=ginekologi", " GINEKOLOGI ", $tab_disabled["ginekologi"]);
	$T->addTab("index2.php?p=p_kebidanan&tab=ginekologi_kon", " PASIEN KONSUL GINEKOLOGI ", $tab_disabled["ginekologi_kon"]);
}else {
	$T->addTab("", " OBSTETRI "	, $tab_disabled["obstetri"]);
	$T->addTab("", " PASIEN KONSUL OBSTETRI "	, $tab_disabled["obstetri_kon"]);
	$T->addTab("", " GINEKOLOGI ", $tab_disabled["ginekologi"]);
	$T->addTab("", " PASIEN KONSUL GINEKOLOGI ", $tab_disabled["ginekologi_kon"]);
}
	//--fungsi column color-------------- Agung Sunandar 22:58 26/06/2012
function color( $dstr, $r ) {
	    if($_GET['list4']=="tab"){
	    	if ($dstr[8] == 'BELUM ADA TAGIHAN' ){
	    		return "<font color=red><b>{$dstr[$r]}</b></font>";
	    	}else{
	    		return "<font color=blue><b>{$dstr[$r]}</b></font>";
	    	}
	    }elseif($_GET['list3']=="tab"){
	    	if ($dstr[7] == 'BELUM ADA TAGIHAN' ){
	    		return "<font color=red><b>{$dstr[$r]}</b></font>";
	    	}else{
	    		return "<font color=blue><b>{$dstr[$r]}</b></font>";
	    	}
	    }elseif($_GET['list2']=="tab"){
	    	if ($dstr[8] == 'BELUM ADA TAGIHAN' ){
	    		return "<font color=red><b>{$dstr[$r]}</b></font>";
	    	}else{
	    		return "<font color=blue><b>{$dstr[$r]}</b></font>";
	    	}
	    }else{
	    	if ($dstr[7] == 'BELUM ADA TAGIHAN' ){
	    		return "<font color=red><b>{$dstr[$r]}</b></font>";
	    	}else{
	    		return "<font color=blue><b>{$dstr[$r]}</b></font>";
	    	}
	    }
}
		//-------------------------------   
if ($_GET["tab"] == "ginekologi") {
	if(!$GLOBALS['print']){
		title_print("<img src='icon/rawat-jalan-2.gif' align='absmiddle'> KLINIK KEBIDANAN DAN PENYAKIT KANDUNGAN (GINEKOLOGI)");
		title_excel("p_ginekologi&tblstart=".$_GET['tblstart']);
		$T->show(2);
	}	
	
    	
    	//if ($_GET['act'] ==  "detail") {
		$ext = "OnChange = 'Form1.submit();'";
		    $f = new Form($SC, "GET", "NAME=Form1");
		    $f->PgConn = $con;
		    $f->hidden("p", $PID);
		    $f->hidden("tab", "ginekologi");
		    $f->hidden("list", "pemeriksaan");
		    $f->hidden("poli",$_GET["mPOLI_gin"]);
		    
		   		
		   		echo "<div align='right' valign='middle'>";	
				$f = new Form($SC, "GET","NAME=Form2");
			    $f->hidden("p", $PID);
			    $f->hidden("tab", "ginekologi");
			    if (!$GLOBALS['print']) {
			    	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
				}
			    $f->execute();
		    	if ($msg) errmsg("Error:", $msg);
		    	echo "</div>";
				//---------------------
				echo "<br>";
			
			$SQLSTR = 	"select distinct a.mr_no,a.id,upper(a.nama)as nama,tanggal(a.tanggal_reg,0)||' '||to_char(waktu_reg,'hh:mi:ss') as tgl,a.alm_tetap,a.kesatuan,a.tdesc,a.statusbayar
				from rsv_pasien4 a 
				left join c_visit b on b.no_reg = a.id
				WHERE a.poli='".$_GET["mPOLI_gin"]."'";
		// 24-12-2006 --> tambahan 'where is_bayar = 'N'
		//status_akhir,rawatan di query sementara di tutup
        
		$tglhariini = date("Y-m-d", time());
    if (strlen($_GET["mPOLI_gin"]) > 0 ) {
		$SQLWHERE =
			"AND a.TANGGAL_REG = '$tglhariini' AND".
			"	(UPPER(a.NAMA) LIKE '%".strtoupper($_GET["search"])."%') ";
	}
	if ($_GET["search"]) {
		$SQLWHERE =
			"and (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' or a.id like '%".$_GET['search']."%' or a.mr_no like '%".$_GET["search"]."%' ".
					" or upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%' or a.nrp_nip like '%".$_GET['search']."%' ".
					" or upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' ) ";
	}
	if (!isset($_GET[sort])) {

           $_GET[sort] = "a.id";
           $_GET[order] = "asc";
	}
	$rstr=pg_query($con, "$SQLSTR $SQLWHERE ");
   // $n = pg_num_rows($rstr);		    	
	$dstr = pg_fetch_array($rstr); 
	   	$t = new PgTable($con, "100%");
	    $t->SQL = "$SQLSTR $SQLWHERE ";
	    $t->setlocale("id_ID");
	    $t->ShowRowNumber = true;
	    $t->ColAlign = array("CENTER","CENTER","LEFT","LEFT","LEFT","CENTER","LEFT","LEFT","LEFT","LEFT","LEFT");	
	    $t->RowsPerPage = $ROWS_PER_PAGE;
	    $t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_ginekologi&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI"]}&list=layanan&sub2=nonpaket'><#2#>";
	    //(awal)$t->ColFormatHtml[8] = "<A HREF='$SC?p=$PID&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI"]}&act=periksa'><INPUT NAME='submitButton' TYPE=SUBMIT VALUE='Periksa' >";
	   	//$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","TANGGAL  REG","WAKTU REG","NAMA PASIEN","PANGKAT","NRP/NIP","KESATUAN","LOKET","TIPE PASIEN","STATUS");
	   	$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","NAMA PASIEN","WAKTU REGISTRASI","ALAMAT","PEKERJAAN","TIPE PASIEN","STATUS");
	    $t->ColColor[7] = "color";
	    //$t->ColRowSpan[2] = 2;
	    $t->execute();
	    echo"<br><div class=NOTE>Catatan : Daftar pasien di urut berdasarkan no antrian</div><br>";	
				
    	//}
}elseif ($_GET["tab"] == "ginekologi_kon") {
	if(!$GLOBALS['print']){
		title_print("<img src='icon/rawat-jalan-2.gif' align='absmiddle'> KLINIK KEBIDANAN DAN PENYAKIT KANDUNGAN (GINEKOLOGI)");
		title_excel("p_ginekologi&tblstart=".$_GET['tblstart']);
		$T->show(3);
	}	
	
    	
    	//if ($_GET['act'] ==  "detail") {
		$ext = "OnChange = 'Form1.submit();'";
		    $f = new Form($SC, "GET", "NAME=Form1");
		    $f->PgConn = $con;
		    $f->hidden("p", $PID);
		    $f->hidden("tab", "ginekologi_kon");
		    $f->hidden("list", "pemeriksaan");
		    $f->hidden("poli",$_GET["mPOLI_gin"]);
		    
		   		
		   		echo "<div align='right' valign='middle'>";	
				$f = new Form($SC, "GET","NAME=Form2");
			    $f->hidden("p", $PID);
			    $f->hidden("tab", "ginekologi_kon");
			    if (!$GLOBALS['print']) {
			    	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
				}
			    $f->execute();
		    	if ($msg) errmsg("Error:", $msg);
		    	echo "</div>";
				//---------------------
				echo "<br>";
			
			$SQLSTR = 	"select distinct a.mr_no,a.id,upper(a.nama)as nama,tanggal(b.tanggal_konsul,0)||' '||to_char(b.waktu_konsul,'hh:mi:ss') as tgl,a.alm_tetap,a.kesatuan,a.tdesc,CASE WHEN a.rawat_inap='I' THEN 'RAWAT INAP'
                             WHEN a.rawat_inap='N' THEN 'INSTALASI GAWAT DARURAT'
			     ELSE c.tdesc end as rawatan,a.statusbayar
				from rsv_pasien4 a 
				left join c_visit b on b.no_reg = a.id
				left join c_visit_operasi d on d.no_reg = a.id
				left join rs00001 c on c.tc_poli = b.id_poli and c.tt='LYN' or c.tc_poli = d.id_poli and c.tt='LYN'
				WHERE b.id_konsul='".$_GET["mPOLI_gin"]."'";
		// 24-12-2006 --> tambahan 'where is_bayar = 'N'
		//status_akhir,rawatan di query sementara di tutup
          //29-04-211 -->   status pasien ditampilkan perhari 'AND a.TANGGAL_REG = '$tglhariini' '        

		$tglhariini = date("Y-m-d", time());
    if (strlen($_GET["mPOLI_gin"]) > 0 ) {
		$SQLWHERE =
			"AND b.TANGGAL_KONSUL = '$tglhariini' AND".
			"	(UPPER(a.NAMA) LIKE '%".strtoupper($_GET["search"])."%') ";
	}
	if ($_GET["search"]) {
		$SQLWHERE =
			"and (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' or a.id like '%".$_GET['search']."%' or a.mr_no like '%".$_GET["search"]."%' ".
					" or upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%' or a.nrp_nip like '%".$_GET['search']."%' ".
					" or upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' ) ";
	}
	if (!isset($_GET[sort])) {

           $_GET[sort] = "a.id";
           $_GET[order] = "asc";
	}
	$rstr=pg_query($con, "$SQLSTR $SQLWHERE ");
   // $n = pg_num_rows($rstr);		    	
	$dstr = pg_fetch_array($rstr); 
	   	$t = new PgTable($con, "100%");
	    $t->SQL = "$SQLSTR $SQLWHERE ";
	    $t->setlocale("id_ID");
	    $t->ShowRowNumber = true;
	    $t->ColAlign = array("CENTER","CENTER","LEFT","LEFT","LEFT","CENTER","LEFT","LEFT","LEFT","LEFT","LEFT");	
	    $t->RowsPerPage = $ROWS_PER_PAGE;
	    $t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_ginekologi&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI_gin"]}&list=layanan&sub2=nonpaket'><#2#>";
	    //(awal)$t->ColFormatHtml[8] = "<A HREF='$SC?p=$PID&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI"]}&act=periksa'><INPUT NAME='submitButton' TYPE=SUBMIT VALUE='Periksa' >";
	   	//$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","TANGGAL  REG","WAKTU REG","NAMA PASIEN","PANGKAT","NRP/NIP","KESATUAN","LOKET","TIPE PASIEN","STATUS");
	   	$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","NAMA PASIEN","WAKTU KONSULTASI","ALAMAT","PEKERJAAN","TIPE PASIEN","UNIT ASAL","STATUS");
	    $t->ColColor[8] = "color";
	    //$t->ColRowSpan[2] = 2;
	    $t->execute();
	    echo"<br><div class=NOTE>Catatan : Daftar pasien di urut berdasarkan no antrian</div><br>";	
}elseif ($_GET["tab"] == "obstetri_kon") {
	if(!$GLOBALS['print']){
		title_print("<img src='icon/rawat-jalan-2.gif' align='absmiddle'> KLINIK KEBIDANAN(OBSTETRI)");
		title_excel("p_obsteteri&tblstart=".$_GET['tblstart']);
		$T->show(1);
	}	
	
    	
    	//if ($_GET['act'] ==  "detail") {
		$ext = "OnChange = 'Form1.submit();'";
		    $f = new Form($SC, "GET", "NAME=Form1");
		    $f->PgConn = $con;
		    $f->hidden("p", $PID);
		    $f->hidden("tab", "obstetri_kon");
		    $f->hidden("list", "pemeriksaan");
		    $f->hidden("poli",$_GET["mPOLI_obs"]);
		    
		   		
		   		echo "<div align='right' valign='middle'>";	
				$f = new Form($SC, "GET","NAME=Form2");
			    $f->hidden("p", $PID);
			    $f->hidden("tab", "obstetri_kon");
			    if (!$GLOBALS['print']) {
			    	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
				}
			    $f->execute();
		    	if ($msg) errmsg("Error:", $msg);
		    	echo "</div>";
				//---------------------
				echo "<br>";
			
			$SQLSTR = 	"select distinct a.mr_no,a.id,upper(a.nama)as nama,tanggal(b.tanggal_konsul,0)||' '||to_char(b.waktu_konsul,'hh:mi:ss') as tgl,a.alm_tetap,a.kesatuan,a.tdesc,CASE WHEN a.rawat_inap='I' THEN 'RAWAT INAP'
                             WHEN a.rawat_inap='N' THEN 'INSTALASI GAWAT DARURAT'
			     ELSE c.tdesc end as rawatan,a.statusbayar
				from rsv_pasien4 a 
				left join c_visit b on b.no_reg = a.id
				left join c_visit_operasi d on d.no_reg = a.id
				left join rs00001 c on c.tc_poli = b.id_poli and c.tt='LYN' or c.tc_poli = d.id_poli and c.tt='LYN'
				WHERE (b.id_konsul='".$_GET["mPOLI_obs"]."' or d.id_konsul='".$_GET["mPOLI"]."')";
		// 24-12-2006 --> tambahan 'where is_bayar = 'N'
		//status_akhir,rawatan di query sementara di tutup
          //29-04-211 -->   status pasien ditampilkan perhari 'AND a.TANGGAL_REG = '$tglhariini' '        

		$tglhariini = date("Y-m-d", time());
    if (strlen($_GET["mPOLI_obs"]) > 0 ) {
		$SQLWHERE =
			"AND b.TANGGAL_KONSUL = '$tglhariini' AND".
			"	(UPPER(a.NAMA) LIKE '%".strtoupper($_GET["search"])."%') ";
	}
	if ($_GET["search"]) {
		$SQLWHERE =
			"and (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' or a.id like '%".$_GET['search']."%' or a.mr_no like '%".$_GET["search"]."%' ".
					" or upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%' or a.nrp_nip like '%".$_GET['search']."%' ".
					" or upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' ) ";
	}
	if (!isset($_GET[sort])) {

           $_GET[sort] = "a.id";
           $_GET[order] = "asc";
	}
	$rstr=pg_query($con, "$SQLSTR $SQLWHERE ");
   // $n = pg_num_rows($rstr);		    	
	$dstr = pg_fetch_array($rstr); 
	   	$t = new PgTable($con, "100%");
	    $t->SQL = "$SQLSTR $SQLWHERE ";
	    $t->setlocale("id_ID");
	    $t->ShowRowNumber = true;
	    $t->ColAlign = array("CENTER","CENTER","LEFT","LEFT","LEFT","CENTER","LEFT","LEFT","LEFT","LEFT","LEFT");	
	    $t->RowsPerPage = $ROWS_PER_PAGE;
	    $t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_obsteteri&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI_obs"]}&list=layanan&sub2=nonpaket'><#2#>";
	    //(awal)$t->ColFormatHtml[8] = "<A HREF='$SC?p=$PID&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI"]}&act=periksa'><INPUT NAME='submitButton' TYPE=SUBMIT VALUE='Periksa' >";
	   	//$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","TANGGAL  REG","WAKTU REG","NAMA PASIEN","PANGKAT","NRP/NIP","KESATUAN","LOKET","TIPE PASIEN","STATUS");
	   	$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","NAMA PASIEN","WAKTU KONSUL","ALAMAT","PEKERJAAN","TIPE PASIEN","UNIT ASAL","STATUS");
	    $t->ColColor[8] = "color";
	    //$t->ColRowSpan[2] = 2;
	    $t->execute();
	    echo"<br><div class=NOTE>Catatan : Daftar pasien di urut berdasarkan no antrian</div><br>";	
}else {
    if(!$GLOBALS['print']){
		
    	title_print("<img src='icon/rawat-jalan-2.gif' align='absmiddle'> <b>KLINIK KEBIDANAN (OBSTETRI)</B>");
    	title_excel("p_obstetri&tblstart=".$_GET['tblstart']);
		$T->show(0);
    }
		//if ($_GET['act'] ==  "detail"){
		
		$ext = "OnChange = 'Form1.submit();'";
		    $f = new Form($SC, "GET", "NAME=Form1");
		    $f->PgConn = $con;
		    $f->hidden("p", $PID);
		    $f->hidden("tab", "obsteteri");
		    $f->hidden("list", "pemeriksaan");
		    $f->hidden("poli",$_GET["mPOLI_obs"]);
		    
		   		
		   		echo "<div align='right' valign='middle'>";	
				$f = new Form($SC, "GET","NAME=Form2");
			    $f->hidden("p", $PID);
			    $f->hidden("tab", "obsteteri");
			    if (!$GLOBALS['print']) {
			    	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
				}
			    $f->execute();
		    	if ($msg) errmsg("Error:", $msg);
		    	echo "</div>";
				//---------------------
				echo "<br>";
			
			$SQLSTR = "select distinct a.mr_no,a.id,upper(a.nama)as nama,tanggal(a.tanggal_reg,0)||' '||to_char(waktu_reg,'hh:mi:ss') as tgl,a.alm_tetap,a.kesatuan,a.tdesc,a.statusbayar
				from rsv_pasien4 a 
				left join c_visit b on b.no_reg = a.id
				WHERE a.poli='" . $_GET["mPOLI_obs"] . "'";
		// 24-12-2006 --> tambahan 'where is_bayar = 'N'
		//status_akhir,rawatan di query sementara di tutup
        
		$tglhariini = date("Y-m-d", time());
    if (strlen($_GET["mPOLI_obs"]) > 0 ) {
		$SQLWHERE =
			"AND a.TANGGAL_REG = '$tglhariini' AND".
			"	(UPPER(a.NAMA) LIKE '%".strtoupper($_GET["search"])."%') ";
	}
	if ($_GET["search"]) {
		$SQLWHERE =
			"and (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' or a.id like '%".$_GET['search']."%' or a.mr_no like '%".$_GET["search"]."%' ".
					" or upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%' or a.nrp_nip like '%".$_GET['search']."%' ".
					" or upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' ) ";
	}
	if (!isset($_GET[sort])) {

           $_GET[sort] = "a.id";
           $_GET[order] = "asc";
	}
	
	$rstr=pg_query($con, "$SQLSTR $SQLWHERE ");
   // $n = pg_num_rows($rstr);		    	
	$dstr = pg_fetch_array($rstr); 
	   	$t = new PgTable($con, "100%");
	   	$t->SQL = "$SQLSTR $SQLWHERE ";
	    $t->setlocale("id_ID");
	    $t->ShowRowNumber = true;
	    $t->ColAlign = array("CENTER","CENTER","LEFT","LEFT","LEFT","CENTER","LEFT","LEFT","LEFT","LEFT","LEFT");	
	    $t->RowsPerPage = $ROWS_PER_PAGE;
	    $t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_obsteteri&tab=obsteteri&act=detail&list=layanan&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI_obs"]}&sub2=nonpaket'><#2#>";
	    //(awal)$t->ColFormatHtml[8] = "<A HREF='$SC?p=$PID&rg=<#1#>&mr=<#0#>&poli={$_GET["mPOLI"]}&act=periksa'><INPUT NAME='submitButton' TYPE=SUBMIT VALUE='Periksa' >";
	   	//$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","TANGGAL  REG","WAKTU REG","NAMA PASIEN","PANGKAT","NRP/NIP","KESATUAN","LOKET","TIPE PASIEN","STATUS");
	   	$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","NAMA PASIEN","WAKTU REGISTRASI","ALAMAT","PEKERJAAN","TIPE PASIEN","STATUS");
	    $t->ColColor[7] = "color";
	    //$t->ColRowSpan[2] = 2;
	    $t->execute();
			    echo"<br><div class=NOTE><B>Catatan : Daftar pasien diurut berdasarkan no antrian</B></div><br>";
	
		//}
		
}
}
?>
