<?php // Nugraha, Sat Apr 24 14:56:40 WIT 2004
      // sfdn, 09-05-2004

$PID = "lap_internal_transfer";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

$tgl_sekarang = date("d-m-Y", time());
$tglhariini = date("Y-m-d", time());

if(!$GLOBALS['print']){
title("<img src='icon/apotik-2.gif' align='absmiddle' > LAPORAN INTERNAL TRANSFER");
}else{
title("LAPORAN INTERNAL TRANSFER");
}
//echo "<br>"; 

	//================ Tambah Tanggal, AGung SUnandar 13:20 07/08/2012
	
	$wkthariini = date("H:i:s", time());
	
	//echo $wkthariini;
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
		//$f->selectArray("status","Status",Array(""=>"Semua Status","0"=>"Belum di Konfirmasi","1"=>"Sudah di Konformasi"),$_GET[status],"");
    	$f->hidden("act",$_GET['act']);
		$f->selectSQL("depo", "DEPO ",
						"select '' as tc, '-' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'GDP' and tc not in ('000','003') order by tdesc "
						,$_GET["depo"],$ext);
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
	
	   $ts_check_in1 = date("Y-m-d");
	    $ts_check_in2 = date("Y-m-d");
	 //   $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
	  //  $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");
		echo "<br /> Tanggal : ".$ts_check_in1;
		if($ts_check_in1!=$ts_check_in2){
		echo " s/d : ".$ts_check_in2;
		}		
	    } else {
		    
	    $tgl_sakjane = $_GET[tanggal2D] + 1;	
	    //   $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
	  //  $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
	/*	if($_GET["status"]==""){
			$status="Semua Status";
		}else if($_GET["status"]=="0"){
			$status="Belum di Konfirmasi";
		}else{
			$status="Sudah di Konformasi";
		} */
		if(!$_GET['tanggal1D']){
			$tanggal1 = date('Y-m-d');
			$tanggal2 = date('Y-m-d');
		}else{
			$ts_check_in1 = date("d M Y", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
			$ts_check_in2 = date("d M Y", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
			$tanggal1 =$ts_check_in1;
			$tanggal2 =$ts_check_in2;
		}
		echo "<br /> Tanggal : ".$tanggal1;
		if($tanggal1!=$tanggal2){
		echo " s/d : ".$tanggal2;
		}		
	//	echo " Status : ".$status;
		$border='border=1';
	    }
	  //  $f->selectArray("status","Status",Array(""=>"Semua Status","0"=>"Belum di Konfirmasi","1"=>"Sudah di Konformasi"),$_GET[status],"disabled");   
    	$f->execute();
	}
	title_print("");
	title_excel("$PID&tanggal1D=".$_GET[tanggal1D]."&tanggal1M=".$_GET[tanggal1M]."&tanggal1Y=".$_GET[tanggal1Y]."&tanggal2D=".$_GET[tanggal2D]."&tanggal2M=".$_GET[tanggal2M]."&tanggal2Y=".$_GET[tanggal2Y]."&search=".$_GET[search]."&depo=".$_GET[depo]."&tblstart=".$_GET[tblstart]."");
   // echo "<br>";
	//======================== Akhir tanggal
	echo "<div class='wrapper'>";
	if(!$GLOBALS['print']){
		echo "<br /><br />";
		echo "<DIV ALIGN=RIGHT>";
        echo "<TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID >";
        echo "<INPUT TYPE=HIDDEN NAME=tanggal1D VALUE=$_GET[tanggal1D] >";
        echo "<INPUT TYPE=HIDDEN NAME=tanggal2D VALUE=$_GET[tanggal2D] >";
        echo "<INPUT TYPE=HIDDEN NAME=tanggal1M VALUE=$_GET[tanggal1M] >";
        echo "<INPUT TYPE=HIDDEN NAME=tanggal2M VALUE=$_GET[tanggal2M] >";
        echo "<INPUT TYPE=HIDDEN NAME=tanggal1Y VALUE=$_GET[tanggal1Y] >";
        echo "<INPUT TYPE=HIDDEN NAME=tanggal2Y VALUE=$_GET[tanggal2Y] >";
        echo "<TD >Pencarian Nama Obat: <INPUT TYPE=TEXT NAME=search VALUE='" . $_GET["search"] . "'></TD>";
        echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

        echo "</TR></FORM></TABLE>";
        echo "</DIV>";
		echo "<br />";
	}
	$ext = "onchange='javascript:Form2.submit()'";        
    $t = new PgTable($con, "100%",$border);
	if(!$_GET['tanggal1D']){
		$tanggal1 = date('Y-m-d');
		$tanggal2 = date('Y-m-d');
	}else{
		$tanggal1 =$ts_check_in1;
		$tanggal2 =$ts_check_in2;
	}
	if($_GET['search']){
		$wh = " where (a.waktu_ver between '$tanggal1' and '$tanggal2') and d.obat like '%".strtoupper($_GET[search])."%' ";
	}else{
		$wh = " where (a.waktu_ver between '$tanggal1' and '$tanggal2') ";
	}
		$wh .=" and b.poli_asal like '%".$_GET['depo']."%'";
   	$t->SQL = "select to_char(a.waktu_ver,'dd MM yyyy'),c.tdesc as poli , d.obat ,a.jumlah_stok_awal, a.jumlah ,(a.jumlah_stok_awal-a.jumlah) as sisa, a.nm_user , a.verifikator
			   from internal_transfer_d a 
		       left join internal_transfer_m b on a.kode_transaksi=b.kode_transaksi
		       left join rs00001 c on b.poli_asal=c.tc and tt='GDP'
		       left join rs00015 d on a.item_id=d.id::text
			   $wh 
			   ";
	$r1 = pg_query($con,"select to_char(a.waktu_ver,'dd MM yyyy'),c.tdesc as poli , d.obat , a.jumlah , a.nm_user , a.verifikator
			   from internal_transfer_d a 
		       left join internal_transfer_m b on a.kode_transaksi=b.kode_transaksi
		       left join rs00001 c on b.poli_asal=c.tc and tt='GDP'
		       left join rs00015 d on a.item_id=d.id::text
			   $wh 
			   ");
	$n = pg_numrows($r1);
        if (!isset($_GET[sort])) {
           $_GET[sort] = "d.obat,c.tdesc";
           $_GET[order] = "asc";
	}

    
	$t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[1] = "LEFT";
    $t->ColAlign[5] = "CENTER";
    $t->ColAlign[4] = "CENTER";
    $t->ColAlign[3] = "CENTER";
	if (!$GLOBALS['print']){
		$t->ColHeader = array("TANGGAL VERIFIKASI","DEPO","NAMA OBAT","STOK GUDANG","JUMLAH TRANSFER","SISA STOK GUDANG","USER ENTRY","VERIFIKATOR");
		$t->RowsPerPage = 20;
	//	$t->ColFormatHtml[4] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&act=$_GET[act]&action=view&f=<#4#>&e=<#1#>&g=<#2#>&stat=<#3#>'>".icon("view","View")."</A>";
		$t->DisableNavButton = false;
		$t->DisableStatusBar = false;
		$t->DisableScrollBar = false;
		}else{
			$t->ColHeader = array("TANGGAL VERIFIKASI","DEPO","NAMA OBAT","STOK GUDANG","JUMLAH TRANSFER","SISA <br/>STOK GUDANG","USER ENTRY","VERIFIKATOR");
			$t->RowsPerPage = $n;
			$t->DisableNavButton = true;
			$t->DisableStatusBar = true;
			$t->DisableScrollBar = true;
			}
			
    $t->execute();
echo "</div>";	

?>
