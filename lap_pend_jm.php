<? 
$PID = "lap_pend_jm";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

   if (!$GLOBALS['print']){
		title_print("<img src='icon/keuangan-2.gif' align='absmiddle' > Laporan Pendapatan Jasa Medis");
		title_excel("lap_pend_jm");
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Pendapatan Jasa Medis");
    }
	
	$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

	if (!$GLOBALS['print']) {
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
		$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
		$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
		
	    }
		$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
    			  "select distinct(b.tipe) as tc, c.tdesc as tdesc ".
    			  "from rs00008 a, rs00006 b, rs00001 c ".
    			  "where a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP'", $_GET["mPASIEN"],"006");
		$f->selectSQL("mPOLI", "Poli","select '' as tc, '' as tdesc union ".
							"SELECT c.tc,c.tdesc FROM rs00001 c, rs00006 d WHERE c.tc_poli=d.poli and c.tt = 'LYN' and c.tc not in ('000','201','202','206','207','208')
							 order by tdesc ",$_GET["mPOLI"], "102");
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
		$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
	    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
	    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
		$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
	    }
		
		$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
    			  "select distinct(b.tipe) as tc, c.tdesc as tdesc ".
    			  "from rs00008 a, rs00006 b, rs00001 c ".
    			  "where a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP'", $_GET["mPASIEN"],"disabled");
		$f->selectSQL("mPOLI", "Poli","select '' as tc, '' as tdesc union ".
							"SELECT c.tc,c.tdesc FROM rs00001 c, rs00006 d WHERE c.tc_poli=d.poli and c.tt = 'LYN' and c.tc not in ('000','201','202','206','207','208')
							 order by tdesc ",$_GET["mPOLI"], "disabled");
		$f->execute();
	}


    echo "<br>";

    if (!empty($_GET[mBAYAR])) {
       $SQL_b = " and b.tdesc = '".$_GET["mPASIEN"]."' ";
       $SQL_b2 = " and y.tdesc = '".$_GET["mPOLI"]."' ";

    } else {
       $SQL_b = " ";
    }
    
	if (strlen($_GET["search"]) > 0) {
			$r2 = pg_query($con, "select sum(a.tagihan) as jmlbayar".
              "from rs00008 a,rs00034 d, rs00001 c,rs00006 e, rs00005 f,rs00001 g ".
			  "where a.trans_type='LTM' AND a.item_id::numeric=d.id AND e.id=a.no_reg AND c.tt='JEP' AND f.reg=a.no_reg and f.is_bayar='Y' and e.poli=g.tc_poli and (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2')
				GROUP BY a.tanggal_trans, c.tdesc,a.item_id,d.layanan,g.tdesc order by a.tanggal_trans, g.tdesc, c.tdesc");

    } else {

        $r2 = pg_query($con,
	      "select  sum(a.tagihan) as jmlbayar from rs00008 a,rs00034 d, rs00001 c,rs00006 e, rs00005 f,rs00001 g 
		   where a.trans_type='LTM' AND a.item_id::numeric=d.id AND e.id=a.no_reg AND c.tt='JEP' AND c.tc like '%".$_GET["mPASIEN"]."%' AND f.reg=a.no_reg and f.is_bayar='Y' and e.poli=g.tc_poli and e.poli::text like '%".$_GET["mPOLI"]."%' and (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2')");

    }

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

	$SQL = "SELECT to_char(a.tanggal_trans,'dd MON yyyy'), g.tdesc,c.tdesc, d.layanan, sum(a.tagihan) 
			FROM rs00008 a,rs00034 d, rs00001 c,rs00006 e, rs00005 f,rs00001 g
			WHERE a.trans_type='LTM' AND a.item_id::numeric=d.id AND e.id=a.no_reg AND c.tt='JEP' and c.tc=e.tipe AND c.tc like '%".$_GET["mPASIEN"]."%' AND f.reg=a.no_reg and f.is_bayar='Y' and e.poli=g.tc_poli and e.poli::text like '%".$_GET[mPOLI]."%' and (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2')
			GROUP BY a.tanggal_trans, c.tdesc,a.item_id,d.layanan,g.tdesc order by a.tanggal_trans, g.tdesc, c.tdesc";

    $t = new PgTable($con, "100%");
    $t->SQL = "$SQL";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColHeader = array("TANGGAL TRANSAKSI","POLI","TIPE PASIEN","LAYANAN","JUMLAH");
	$t->ColAlign = array("CENTER","LEFT","LEFT","LEFT","RIGHT");
	$t->ColFooter [4]=  number_format($d2->jmlbayar,2,',','.');
							
    $t->execute();

?>
