<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 09-05-2004
   // sfdn, 11-05-2004


$PID = "lap_labarugi1";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

if($_GET["tc"] == "view") {
/*
*/
} else {
    // search box
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > LAPORAN LABA RUGI");
		title_excel("lap_labarugi");
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > LAPORAN LABA RUGI");
    }
    echo "<br>";
    //$ext = "OnChange = 'Form1.submit();'";
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
	    $f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
    			  "select distinct(b.tipe) as tc, c.tdesc as tdesc ".
    			  "from rs00008 a, rs00006 b, rs00001 c ".
    			  "where a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP'", $_GET["mPASIEN"],$ext);
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
	    $f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
    			  "select distinct(b.tipe) as tc, c.tdesc as tdesc ".
    			  "from rs00008 a, rs00006 b, rs00001 c ".
    			  "where a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP'", $_GET["mPASIEN"],"disabled");
    	$f->execute();
	}
	
    echo "<br>";
    
    // DEBIT
    $r2 = pg_query($con,"select sum(x.jumlah) as debit from rs00006 a
						left join rs00002 b ON a.mr_no = b.mr_no
						left join rs00001 c ON c.tc = a.tipe and c.tt = 'JEP'
						left join rs00005 x ON x.reg = a.id
			where (a.tanggal_reg between '$ts_check_in1' and '$ts_check_in2') and b.tipe_pasien = '".$_GET["mPASIEN"]."' "); 

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);
    
    // KREDIT
    $r3 = pg_query($con,"select sum(x.jumlah) as kredit from rs00006 a
						left join rs00002 b ON a.mr_no = b.mr_no
						left join rs00001 c ON c.tc = a.tipe and c.tt = 'JEP'
						left join rs00005 x ON x.reg = a.id
			where (a.tanggal_reg between '$ts_check_in1' and '$ts_check_in2') and b.tipe_pasien = '".$_GET["mPASIEN"]."' 
			--and x.jumlah like '%-%' 
			"); 

    $d3 = pg_fetch_object($r3);
    pg_free_result($r3);
    
    if (!empty($_GET[mPASIEN])) {
    
    $t = new PgTable($con, "100%");
    $SQL = "select a.id as no_reg ,b.nama ,c.tdesc as pasien, b.pangkat_gol,b.nrp_nip ,
			sum(x.harga * x.qty) as tagihan, 
			(select sum(jumlah) from rs00005 where reg = a.id) as pembatalan from rs00006 a
			left join rs00002 b ON a.mr_no = b.mr_no
			left join rs00001 c ON c.tc = a.tipe and c.tt = 'JEP'
			left join rs00008 x ON x.no_reg = a.id
			where (a.tanggal_reg between '$ts_check_in1' and '$ts_check_in2') and b.tipe_pasien = '".$_GET["mPASIEN"]."'
			group by a.id,b.nama,c.tdesc,b.pangkat_gol,b.nrp_nip,b.tipe_pasien";
	
    $t->setlocale("id_ID");
    $t->SQL = "$SQL";
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColAlign[0] = "CENTER";
        
        if(!$GLOBALS['print']) {
			$t->RowsPerPage = 20;
	    	$t->ShowRowNumber = true;
	    	$t->ColHeader = array("NO.REG","NAMA PASIEN","TIPE PASIEN", "PANGKAT","NRP/NIP","DEBIT","KREDIT");
	    } else {
	    	$t->RowsPerPage = 20;
	    	$t->ShowRowNumber = true;
	    	$t->ColHeader = array("NO.REG","NAMA PASIEN","TIPE PASIEN", "PANGKAT","NRP/NIP","DEBIT","KREDIT");
	    	$t->DisableNavButton = true;
	    	$t->DisableScrollBar = true;
	    }
                            
    $t->ColFooter[5] =  number_format($d2->debit,2);
    $t->ColFooter[6] =  number_format($d3->kredit,2);
    $t->execute();

    }  

}

?>
