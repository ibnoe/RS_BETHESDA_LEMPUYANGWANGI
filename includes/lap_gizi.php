<? // Nugraha, 29/03/2004
   // sfdn, 22-04-2004
   // sfdn, 24-12-2006
	// sfdn, 27-12-2006
//session_start();
//if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "daftarri"  || $_SESSION[uid] == "igd" || $_SESSION[uid] == "root") {

$PID = "lap_gizi";
$SC = $_SERVER["SCRIPT_NAME"];

unset($_SESSION["IBU"]["id"]);
unset($_SESSION["IBU"]["nama"]);
		
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (!$GLOBALS['print']){
	title_print("<img src='icon/rawat-jalan-2.gif' align='absmiddle' >  Laporan Konsultasi Gizi");
	title_excel("p=lap_gizi");
}else{
title("<img src='icon/rawat-jalan-2.gif' align='absmiddle' >  Laporan Konsultasi Gizi");
}
	$f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

    
	if (!$GLOBALS['print']){
	    if (!isset($_GET['tanggal1D'])) {
			$tanggal1D = date("d", time());
			$tanggal1M = date("m", time());
			$tanggal1Y = date("Y", time());
			$tanggal2D = date("d", time());
			$tanggal2M = date("m", time());
			$tanggal2Y = date("Y", time());
	
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");
			
	    } else {
		    
	    $tgl_sakjane = $_GET[tanggal2D] + 1;	
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
	
	    }
		$f->selectArray("pasien1", "Jenis Pasien Klinik",
				Array("I" => "Rawat Inap", "J" => "Konsul Rawat Jalan"),
				$_GET["pasien1"],"onChange=\"Form1.submit();\"");
		if ($_GET["pasien1"]=="J"){
	    $f->selectSQL("mRAWAT", "Poli Asal","select '' as tc, '' as tdesc union
                                                  select distinct(a.poli::text) as tc, b.tdesc AS tdesc
                                                  from rs00006 a, rs00001 b
                                                  where b.tt='LYN' and a.poli::text=b.tc and a.rawat_inap='Y'
                                                  ", $_GET["mRAWAT"],"");
		}
    	$f->submit ("TAMPILKAN");
    	$f->execute();
	} else { 
		if (!isset($_GET['tanggal1D'])) {
			$tanggal1D = date("d", time());
			$tanggal1M = date("m", time());
			$tanggal1Y = date("Y", time());
			$tanggal2D = date("d", time());
			$tanggal2M = date("m", time());
			$tanggal2Y = date("Y", time());
	
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");
			
	    } else {
		    
	    $tgl_sakjane = $_GET[tanggal2D] + 1;	
	    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
	    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
	
	    }
		$f->selectArray("pasien1", "Jenis Pasien Klinik",
				Array("I" => "Rawat Inap", "J" => "Konsul Rawat Jalan"),
				$_GET["pasien1"],"onChange=\"Form1.submit();\"disabled");
		if ($_GET["pasien1"]=="J"){
	  $f->selectSQL("mRAWAT", "Poli Asal","select '' as tc, '' as tdesc union ".
    			  "select distinct(a.poli::text) as tc, b.tdesc AS tdesc
                           from rs00006 a, rs00001 b
                           where b.tt='LYN' and a.poli::text=b.tc and a.rawat_inap='Y' ", $_GET["mRAWAT"],"disabled");
		}
    	$f->execute();
	}

    echo "<br>";
	$POLI=getFromTable("SELECT tdesc FROM RS00001 WHERE tc='".$_GET["mRAWAT"]."' and tt='LYN' ");
if ($_GET["pasien1"]=="I"){
$KET="RAWAT INAP" ; 
}ELSEIF ($_GET["pasien1"]=="J"){
$KET="RAWAT JALAN <br> DARI POLI ". $POLI;
}

//$date1=date($ts_check_in1,"dd-mm-yyyy");
	?>
	<TABLE WIDTH="100%" BORDER="0">
		<TR>
			<TD CLASS="TBL_JUDUL" ALIGN="CENTER">RSUD Dr. ACHMAD MUCHTAR BUKITTINGGI</TD>
		</TR>
		<TR>
			<TD CLASS="TBL_JUDUL" ALIGN="CENTER">LAPORAN KONSULTASI GIZI DARI <?= $KET; ?> </TD>
		</TR>
		<tr>
		<td ALIGN="right"><? $f = new Form($SC, "GET", "NAME=Form2");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
	$f->hidden("tanggal1D", $_GET["tanggal1D"]);
	$f->hidden("tanggal1M", $_GET["tanggal1M"]);
	$f->hidden("tanggal1Y", $_GET["tanggal1Y"]);
	$f->hidden("tanggal2D", $_GET["tanggal2D"]);
	$f->hidden("tanggal2M", $_GET["tanggal2M"]);
	$f->hidden("tanggal2Y", $_GET["tanggal2Y"]);
	$f->hidden("pasien1", $_GET["pasien1"]);
	$f->hidden("mRAWAT", $_GET["mRAWAT"]);
	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
	$f->execute(); ?></td>
		</tr>
	</TABLE>
	<br>
	<?
	
	
	if ($_GET["pasien1"]=="I"){
	$t = new PgTable($con, "100%");

	$t->SQL = "select to_char(d.tgl,'DD MON YYYY') as tgl, d.no_mr as no_mr ,e.nama as nama, 
			c.bangsal || ' / ' || b.bangsal || ' / ' || a.bangsal as bangsal, 'Pagi: '|| d.pagi ||' <br>Siang: '|| d.siang ||' <br>Malam '|| 
			d.malam ||' <br>Snack: '|| d.snack_1 ||', '|| d.snack_2 as diet
			from menu_pasien d , rs00002 e, rs00012 as a 
			join rs00012 as b on b.hierarchy = substr(a.hierarchy,1,6) || '000000000' 
			join rs00012 as c on c.hierarchy = substr(a.hierarchy,1,3) || '000000000000' 
			where a.id = d.id_bangsal and d.no_mr=e.mr_no and jns_pasien='I' and (d.tgl between '$ts_check_in1' and '$ts_check_in2' )
			and (d.no_mr like '%".$_GET["search"]."%' or upper(e.nama) like '%".strtoupper($_GET["search"])."%' or upper(c.bangsal) like '%".strtoupper($_GET["search"])."%'
			or upper(b.bangsal) like '%".strtoupper($_GET["search"])."%' or upper(a.bangsal) like '%".strtoupper($_GET["search"])."%' or upper(d.pagi) like '%".strtoupper($_GET["search"])."%'
			or upper(d.siang) like '%".strtoupper($_GET["search"])."%' or upper(d.malam) like '%".strtoupper($_GET["search"])."%' )
			group by tgl,d.no_mr ,e.nama ,diet,c.bangsal ,b.bangsal,a.bangsal";
		if (!isset($_GET[sort])) {
           $_GET[sort] = "tgl";
           $_GET[order] = "asc";
	}
  	$t->ColHeader = array("TANGGAL","NO. MR","NAMA PASIEN","RUANGAN","DIET");
    $t->ShowRowNumber = true;
   	$t->ColAlign[0] = "CENTER";
	$t->ColAlign[1] = "CENTER";
    $t->ColAlign[2] = "CENTER";

	$t->ColAlign[11] = "CENTER";
	$t->execute();
 }else{
 $t = new PgTable($con, "100%");

	$t->SQL = "select to_char(d.tgl,'DD MON YYYY') as tgl, d.no_mr as no_mr ,e.nama as nama, 
			c.tdesc as poli, d.pagi as pagi 
			from menu_pasien d , rs00002 e, rs00001 c  
			where c.tc = d.id_bangsal::text and c.tt='LYN' and d.no_mr=e.mr_no and d.jns_pasien='J' 
			and d.id_bangsal::text like '%".$_GET["mRAWAT"]."%' and (d.tgl between '$ts_check_in1' and '$ts_check_in2' ) 
			and (d.no_mr like '%".$_GET["search"]."%' or upper(e.nama) like '%".strtoupper($_GET["search"])."%' or 
			upper(d.pagi) like '%".strtoupper($_GET["search"])."%' )
			group by tgl,d.no_mr,e.nama ,c.tdesc,d.pagi ";
		if (!isset($_GET[sort])) {
           $_GET[sort] = "tgl";
           $_GET[order] = "asc";
	}
  	$t->ColHeader = array("TANGGAL","NO. MR","NAMA PASIEN","POLI ASAL","CATATAN DIET");
    $t->ShowRowNumber = true;
   	$t->ColAlign= ARRAY("CENTER","CENTER","LEFT","LEFT","LEFT");
	
	$t->execute();
 }
 


?> 
