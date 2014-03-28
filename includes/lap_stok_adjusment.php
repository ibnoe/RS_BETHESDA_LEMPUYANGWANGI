<?php // Nugraha, Sat Apr 24 14:56:40 WIT 2004
      // sfdn, 09-05-2004

$PID = "lap_stok_adjusment";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

$tgl_sekarang = date("d-m-Y", time());
$tglhariini = date("Y-m-d", time());

if(!$GLOBALS['print']){
title("<img src='icon/apotik-2.gif' align='absmiddle' > LAPORAN STOK ADJUSMENT");
}else{
title("LAPORAN STOK ADJUSMENT");
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
    	$ext = "OnChange = 'Form1.submit();'";
	   
 	    $f->selectSQL("mGDP", "DEPO",
        "select '' as tc, '' as tdesc union " .
        "select tc, tdesc ".
        "from rs00001 ".
        "where tt = 'GDP' and tc not in ('000','005') ".
        "order by tc", $_GET["mGDP"],
        $ext);
		$f->hidden("act",$_GET['act']);
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
		$f->selectSQL("mGDP", "DEPO",
        "select '' as tc, '' as tdesc union " .
        "select tc, tdesc ".
        "from rs00001 ".
        "where tt = 'GDP' and tc not in ('000') ".
        "order by tc", $_GET["mGDP"],
        'DISABLED');	
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
		$depo = getFromTable("select tdesc from rs00001 where tt='GDP' and tc='".$_GET['mGDP']."'");
		echo "<br /> Tanggal : ".$tanggal1;
		if($tanggal1!=$tanggal2){
		echo " s/d : ".$tanggal2;
		echo "<br/> Depo : ".$depo;
		}		
	//	echo " Status : ".$status;
		$border='border=1';
	    }
	  //  $f->selectArray("status","Status",Array(""=>"Semua Status","0"=>"Belum di Konfirmasi","1"=>"Sudah di Konformasi"),$_GET[status],"disabled");   
    	$f->execute();
	}
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
	$count_print= getFromTable("select count(a.kode_transaksi)
			   from stok_adjusment_item a 
		       left join stok_adjusment b on a.kode_transaksi=b.kode_transaksi
		       left join rs00001 c on b.stok_poli=c.tc and tt='GDP'
		       left join rs00015 d on a.item_id=d.id::text
		       left join rs00016 e on a.item_id=e.obat_id::text
			   $wh and b.stok_poli like '%".$_GET['mGDP']."%'
			   ");
	//$count_print = $count_print-20;
	title_print("","","$PID&mGDP=$_GET[mGDP]&tanggal1D=".$_GET[tanggal1D]."&tanggal1M=".$_GET[tanggal1M]."&tanggal1Y=".$_GET[tanggal1Y]."&tanggal2D=".$_GET[tanggal2D]."&tanggal2M=".$_GET[tanggal2M]."&tanggal2Y=".$_GET[tanggal2Y]."&search=".$_GET[search]);
	title_excel("$PID&pr=print_excel&mGDP=$_GET[mGDP]&tanggal1D=".$_GET[tanggal1D]."&tanggal1M=".$_GET[tanggal1M]."&tanggal1Y=".$_GET[tanggal1Y]."&tanggal2D=".$_GET[tanggal2D]."&tanggal2M=".$_GET[tanggal2M]."&tanggal2Y=".$_GET[tanggal2Y]."&search=".$_GET[search]);
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
        echo "<INPUT TYPE=HIDDEN NAME=act VALUE=$_GET[act] >";
        echo "<INPUT TYPE=HIDDEN NAME=mGDP VALUE=$_GET[mGDP] >";
        echo "<TD >Pencarian Nama Obat: <INPUT TYPE=TEXT NAME=search VALUE='" . $_GET["search"] . "'></TD>";
        echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

        echo "</TR></FORM></TABLE>";
        echo "</DIV>";
		echo "<br />";
	}
	subtitle("Stok Adj. Min");
	$filter_add = " and selisih_stok < 0 ";
	echo "<br />";
	$ext = "onchange='javascript:Form2.submit()'";        
    $t = new PgTable($con, "100%",$border);
	  $r2 = pg_query($con, "select sum(a.stok_real) as stok_real ,sum(a.stok_asal) as stok_asal ,sum(a.selisih_stok) as selisih ,sum(a.stok_real) as stok_real , sum(e.harga_beli) as hna,sum(a.selisih_stok*e.harga_beli) as jml_saldo from stok_adjusment_item a 
		       left join stok_adjusment b on a.kode_transaksi=b.kode_transaksi
			   left join rs00015 d on a.item_id=d.id::text
		       left join rs00016 e on a.item_id=e.obat_id::text $wh and b.stok_poli like '%".$_GET['mGDP']."%' $filter_add");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);
	/*$jumlh_saldo = getFromTable("select sum(a.stok_real*e.harga_beli) as jml_saldo from stok_adjusment_item a 
		       left join stok_adjusment b on a.kode_transaksi=b.kode_transaksi
			   left join rs00015 d on a.item_id=d.id::text
		       left join rs00016 e on a.item_id=e.obat_id::text $wh and b.stok_poli like '%".$_GET['mGDP']."%'"); */
   	if($GLOBALS['print'] && $_GET['mGDP']!=''){
	$t->SQL = "select to_char(a.waktu_ver,'dd MM yyyy') , d.obat , a.stok_asal ,a.selisih_stok, a.stok_real , e.harga_beli,(a.selisih_stok*e.harga_beli) as saldo
			   from stok_adjusment_item a 
		       left join stok_adjusment b on a.kode_transaksi=b.kode_transaksi
		       left join rs00001 c on b.stok_poli=c.tc and tt='GDP'
		       left join rs00015 d on a.item_id=d.id::text
		       left join rs00016 e on a.item_id=e.obat_id::text
			   $wh and b.stok_poli like '%".$_GET['mGDP']."%' $filter_add
			   ";
	}else{
	$t->SQL = "select to_char(a.waktu_ver,'dd MM yyyy'),c.tdesc as poli , d.obat , a.stok_asal ,a.selisih_stok, a.stok_real , e.harga_beli,(a.selisih_stok*e.harga_beli) as saldo
			   from stok_adjusment_item a 
		       left join stok_adjusment b on a.kode_transaksi=b.kode_transaksi
		       left join rs00001 c on b.stok_poli=c.tc and tt='GDP'
		       left join rs00015 d on a.item_id=d.id::text
		       left join rs00016 e on a.item_id=e.obat_id::text
			   $wh and b.stok_poli like '%".$_GET['mGDP']."%' $filter_add
			   ";
	}
        if (!isset($_GET[sort])) {
           $_GET[sort] = "d.obat";
           $_GET[order] = "asc";
	}

    
	$t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[1] = "LEFT";
    $t->ColAlign[5] = "CENTER";
	if($GLOBALS['print'] && $_GET['mGDP']!=''){
	$t->ColFooter[1] =  "TOTAL ";
	$t->ColAlign[5] = "RIGHT";
	if($_GET['pr']){
	$t->ColFooter[2] =  $d2->stok_asal;
	$t->ColFooter[3] =  $d2->selisih;
	$t->ColFooter[4] =  $d2->stok_real;
	//$t->ColFooter[5] =  number_format($d2->hna,0,',','.');
	$t->ColFooter[6] =  $d2->jml_saldo;
	//$t->ColFooter[6] =  number_format($jumlh_saldo,2,',','.');
		
		}else{
	$t->ColFooter[2] =  number_format($d2->stok_asal,0,',','.');
	$t->ColFooter[3] =  number_format($d2->selisih,0,',','.');
	$t->ColFooter[4] =  number_format($d2->stok_real,0,',','.');
	//$t->ColFooter[5] =  number_format($d2->hna,0,',','.');
	$t->ColFooter[6] =  number_format($d2->jml_saldo,2,',','.');
	//$t->ColFooter[6] =  number_format($jumlh_saldo,2,',','.');
	}
	}else{
	$t->ColFooter[2] =  "TOTAL ";
	$t->ColFooter[3] =  number_format($d2->stok_asal,0,',','.');
	$t->ColFooter[4] =  number_format($d2->selisih,0,',','.');
	$t->ColFooter[5] =  number_format($d2->stok_real,0,',','.');
	//$t->ColFooter[6] =  number_format($d2->hna,0,',','.');
	$t->ColFooter[7] =  number_format($d2->jml_saldo,2,',','.');
	}
	if (!$_GET['pr']){
		if($GLOBALS['print'] && $_GET['mGDP']!=''){
		$t->ColHeader = array("TANGGAL VERIFIKASI","NAMA OBAT","STOK ASAL","SELISIH","STOK REAL","HARGA","JUMLAH HARGA");
		}else{
		$t->ColHeader = array("TANGGAL VERIFIKASI","DEPO","NAMA OBAT","STOK ASAL","SELISIH","STOK REAL","HARGA","JUMLAH HARGA");
		}
		$t->RowsPerPage = 10;
	//	$t->ColFormatHtml[4] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&act=$_GET[act]&action=view&f=<#4#>&e=<#1#>&g=<#2#>&stat=<#3#>'>".icon("view","View")."</A>";
		$t->DisableNavButton = true;
		$t->DisableStatusBar = false;
		$t->DisableScrollBar = true;
		}else{
			if($GLOBALS['print'] && $_GET['mGDP']!=''){
				$t->ColHeader = array("TANGGAL VERIFIKASI","NAMA OBAT","STOK ASAL","SELISIH","STOK REAL","HARGA","JUMLAH HARGA");
			}else{
				$t->ColHeader = array("TANGGAL VERIFIKASI","DEPO","NAMA OBAT","STOK ASAL","SELISIH","STOK REAL","HARGA","JUMLAH HARGA");
			}
			$t->RowsPerPage = 99999;
			$t->DisableNavButton = true;
			$t->DisableStatusBar = true;
			$t->DisableScrollBar = true;
			$t->DisableSort = true;
		} 
			
    $t->execute();
	echo "<br />";

	subtitle("Stok Adj. Plus");
	$filter_add = " and selisih_stok > 0 ";
	echo "<br />";
	$ext = "onchange='javascript:Form2.submit()'";        
    $t = new PgTable($con, "100%",$border);
	  $r2 = pg_query($con, "select sum(a.stok_real) as stok_real ,sum(a.stok_asal) as stok_asal ,sum(a.selisih_stok) as selisih ,sum(a.stok_real) as stok_real , sum(e.harga_beli) as hna,sum(a.selisih_stok*e.harga_beli) as jml_saldo from stok_adjusment_item a 
		       left join stok_adjusment b on a.kode_transaksi=b.kode_transaksi
			   left join rs00015 d on a.item_id=d.id::text
		       left join rs00016 e on a.item_id=e.obat_id::text $wh and b.stok_poli like '%".$_GET['mGDP']."%' $filter_add");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);
	/*$jumlh_saldo = getFromTable("select sum(a.stok_real*e.harga_beli) as jml_saldo from stok_adjusment_item a 
		       left join stok_adjusment b on a.kode_transaksi=b.kode_transaksi
			   left join rs00015 d on a.item_id=d.id::text
		       left join rs00016 e on a.item_id=e.obat_id::text $wh and b.stok_poli like '%".$_GET['mGDP']."%'"); */
   	if($GLOBALS['print'] && $_GET['mGDP']!=''){
	$t->SQL = "select to_char(a.waktu_ver,'dd MM yyyy') , d.obat , a.stok_asal ,a.selisih_stok, a.stok_real , e.harga_beli,(a.selisih_stok*e.harga_beli) as saldo
			   from stok_adjusment_item a 
		       left join stok_adjusment b on a.kode_transaksi=b.kode_transaksi
		       left join rs00001 c on b.stok_poli=c.tc and tt='GDP'
		       left join rs00015 d on a.item_id=d.id::text
		       left join rs00016 e on a.item_id=e.obat_id::text
			   $wh and b.stok_poli like '%".$_GET['mGDP']."%' $filter_add
			   ";
	}else{
	$t->SQL = "select to_char(a.waktu_ver,'dd MM yyyy'),c.tdesc as poli , d.obat , a.stok_asal ,a.selisih_stok, a.stok_real , e.harga_beli,(a.selisih_stok*e.harga_beli) as saldo
			   from stok_adjusment_item a 
		       left join stok_adjusment b on a.kode_transaksi=b.kode_transaksi
		       left join rs00001 c on b.stok_poli=c.tc and tt='GDP'
		       left join rs00015 d on a.item_id=d.id::text
		       left join rs00016 e on a.item_id=e.obat_id::text
			   $wh and b.stok_poli like '%".$_GET['mGDP']."%' $filter_add
			   ";
	}
        if (!isset($_GET[sort])) {
           $_GET[sort] = "d.obat";
           $_GET[order] = "asc";
	}

    
	$t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[1] = "LEFT";
    $t->ColAlign[5] = "CENTER";
	if($GLOBALS['print'] && $_GET['mGDP']!=''){
	$t->ColFooter[1] =  "TOTAL ";
	$t->ColAlign[5] = "RIGHT";
		if($_GET['pr']){
	$t->ColFooter[2] =  $d2->stok_asal;
	$t->ColFooter[3] =  $d2->selisih;
	$t->ColFooter[4] =  $d2->stok_real;
	//$t->ColFooter[5] =  number_format($d2->hna,0,',','.');
	$t->ColFooter[6] =  $d2->jml_saldo;
	//$t->ColFooter[6] =  number_format($jumlh_saldo,2,',','.');
		
		}else{
	$t->ColFooter[2] =  number_format($d2->stok_asal,0,',','.');
	$t->ColFooter[3] =  number_format($d2->selisih,0,',','.');
	$t->ColFooter[4] =  number_format($d2->stok_real,0,',','.');
	//$t->ColFooter[5] =  number_format($d2->hna,0,',','.');
	$t->ColFooter[6] =  number_format($d2->jml_saldo,2,',','.');
	//$t->ColFooter[6] =  number_format($jumlh_saldo,2,',','.');
	}
	}else{
	$t->ColFooter[2] =  "TOTAL ";
	$t->ColFooter[3] =  number_format($d2->stok_asal,0,',','.');
	$t->ColFooter[4] =  number_format($d2->selisih,0,',','.');
	$t->ColFooter[5] =  number_format($d2->stok_real,0,',','.');
	//$t->ColFooter[6] =  number_format($d2->hna,0,',','.');
	$t->ColFooter[7] =  number_format($d2->jml_saldo,2,',','.');
	}
	if (!$_GET['pr']){
		if($GLOBALS['print'] && $_GET['mGDP']!=''){
		$t->ColHeader = array("TANGGAL VERIFIKASI","NAMA OBAT","STOK ASAL","SELISIH","STOK REAL","HARGA","JUMLAH HARGA");
		}else{
		$t->ColHeader = array("TANGGAL VERIFIKASI","DEPO","NAMA OBAT","STOK ASAL","SELISIH","STOK REAL","HARGA","JUMLAH HARGA");
		}
		$t->RowsPerPage = 10;
	//	$t->ColFormatHtml[4] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&act=$_GET[act]&action=view&f=<#4#>&e=<#1#>&g=<#2#>&stat=<#3#>'>".icon("view","View")."</A>";
		$t->DisableNavButton = true;
		$t->DisableStatusBar = false;
		$t->DisableScrollBar = true;
		}else{
			if($GLOBALS['print'] && $_GET['mGDP']!=''){
				$t->ColHeader = array("TANGGAL VERIFIKASI","NAMA OBAT","STOK ASAL","SELISIH","STOK REAL","HARGA","JUMLAH HARGA");
			}else{
				$t->ColHeader = array("TANGGAL VERIFIKASI","DEPO","NAMA OBAT","STOK ASAL","SELISIH","STOK REAL","HARGA","JUMLAH HARGA");
			}	
			$t->RowsPerPage = 99999;
			$t->DisableNavButton = true;
			$t->DisableStatusBar = true;
			$t->DisableScrollBar = true;
			$t->DisableSort = true;
			} 
			
    $t->execute();
echo "</div>";	

?>
