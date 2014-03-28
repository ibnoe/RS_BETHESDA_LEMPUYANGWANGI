<? // 30/12/2003
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 29-04-2004
   // sfdn, 30-04-2004
   // sfdn, 09-05-2004
   // sfdn, 01-06-2004

$PID = "230";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (isset($_GET["v"])) {
    $r = pg_query($con,
            "select a.id,to_char(tanggal_reg,'DD MON YYYY') as tgl_reg_str, ".
            "   b.nama, ".
            "   case when a.rawat_inap ='I' then 'Rawat Inap' ".
            "        when a.rawat_inap='Y' then 'Rawat Jalan' else 'IGD' end as rawatan, ".
            "   c.tdesc as pasien, a.rawat_inap ".
            "from rs00006 a, rs00002 b, rs00001 c ".
            "where a.id = '".$_GET["v"]."' and ".
            "   a.mr_no = b.mr_no and ".
            "   a.tipe = c.tc and c.tt='JEP'");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);

    $jd = "Data Pasien Masuk";
    if ($_GET["i"] == "O") $jd = "Data Pasien Kaluar";
    title($jd);
    echo "<br>";

    $f = new ReadOnlyForm();
    $f->text("Nama Pasien",$d->nama);
    $f->text("No. Registrasi", $d->id);
    $f->text("Tanggal Registrasi", $d->tgl_reg_str);
    $f->text("Unit Pelayanan", $d->rawatan);
    $f->text("Tipe Pasien", $d->pasien);
    $f->execute();

    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID".
            "&tanggal1D=".$_GET["tanggal1D"].
            "&tanggal1M=".$_GET["tanggal1M"].
            "&tanggal1Y=".$_GET["tanggal1Y"].
            "&tanggal2D=".$_GET["tanggal2D"].
            "&tanggal2M=".$_GET["tanggal2M"].
            "&tanggal2Y=".$_GET["tanggal2Y"].
			"&mUNIT=".$_GET["mUNIT"].
			"&mTIPE=".$_GET["mTIPE"].
			"&mDATANG=".$_GET["mDATANG"].
			"&mLAMA=".$_GET["mLAMA"].
			"&mSTATUSOUT=".$_GET["mSTATUSOUT"].								
            "&mINOUT=".$_GET["i"].
            "'>".icon("back","Kembali")."</a></DIV>";

    //$t->ColFormatHtml[6] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&v=<#6#>'>".icon("view","View")."</A>";
    $f = new Form("");
    $f->subtitle("Data Tindakan / Layanan");
    $f->execute();

    $r2 = pg_query($con, "select sum(tagihan) as jum ".
              "from rs00008 ".
              "where no_reg = '".$_GET["v"]."' and ".
              "trans_type='LTM'");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    $t = new PgTable($con, "100%");
    $t->SQL =
        "select f.layanan as desc2,e.layanan as desc1,tagihan ".
        "from rs00008 a, rs00034 e,rs00034 f ".
        "where a.trans_type='LTM' and to_number(a.item_id,'999999999999')= e.id ".
        "and a.no_reg = '".$_GET["v"]."' and ".
        "case when length(rtrim(e.hierarchy,'0'))=9 then substr(rtrim(e.hierarchy,'0'),1,6) = substr(rtrim(f.hierarchy,'0'),1,6) ".
        "when length(rtrim(e.hierarchy,'0'))=8 then substr(rtrim(e.hierarchy,'0'),1,6) = substr(rtrim(f.hierarchy,'0'),1,6) ".
        "when length(rtrim(e.hierarchy,'0'))=12 then substr(rtrim(e.hierarchy,'0'),1,6) = substr(rtrim(f.hierarchy,'0'),1,6) ".
        "else substr(rtrim(e.hierarchy,'0'),1,12) = substr(rtrim(f.hierarchy,'0'),1,12) end and ".
        "f.is_group='Y'";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatMoney[2] = "%!+#2n";
    $t->ColHeader = array("U N I T","NAMA LAYANAN/TINDAKAN","Rp.");
    $t->ColFooter[2] =  number_format($d2->jum,2);
    $t->DisableScrollBar = true;
    $t->DisableStatusBar = true;
    //$t->ShowSQLExecTime = true;
    //$t->ShowSQL = true;

    $t->execute();
    echo "<br>";
    $f = new Form("");
    $f->subtitle("Data Obat");
    $f->execute();

    $r1 = pg_query($con,
                "select sum(qty*harga) as jum ".
                "from rs00008 ".
                "where no_reg = '".$_GET["v"]."' and ".
                "   trans_type='OB2'");

    $d1 = pg_fetch_object($r1);
    pg_free_result($r1);

    $t = new PgTable($con, "100%");
    $t->SQL =
        "select e.obat, a.qty,a.harga,a.tagihan ".
        "from rs00008 a, rs00015 e ".
        "where a.trans_type='OB2' and ".
        "   to_number(a.item_id, '999999999999') = e.id and ".
        "   a.no_reg = '".$_GET["v"]."'";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatMoney[1] = "%!+#2n";
    $t->ColFormatMoney[2] = "%!+#2n";
    $t->ColFormatMoney[3] = "%!+#2n";
    $t->ColHeader = array("NAMA OBAT","QTY","HARGA","Rp.");
    $t->ColFooter[3] =  number_format($d1->jum,2);
    $t->DisableScrollBar = true;
    $t->DisableStatusBar = true;
    //$t->ShowSQLExecTime = true;
    //$t->ShowSQL = true;

    $t->execute();
    echo "<br>";

    $f = new Form("");
    $f->subtitle("Data Diagnosa");
    $f->execute();

    $t = new PgTable($con, "100%");
    $t->SQL =
        "select a.item_id,e.description, ".
        "   (select description from rs00019 x, rs00008 y ".
        "       where x.diagnosis_code=y.item_id ".
        "       and y.trans_type='ICD' and y.no_reg = a.no_reg) as diagnosa ".
        "from rs00008 a, rs00009 e ".
        "where (a.trans_type='DIA' and ".
        "   a.id = e.trans_id and ".
        "   a.no_reg = '".$_GET["v"]."')";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColHeader = array("KODE ICD","DESKRIPSI ICD","DIAGNOSA");
    $t->DisableScrollBar = true;
    $t->DisableStatusBar = true;
    //$t->ShowSQLExecTime = true;
    //$t->ShowSQL = true;

    $t->execute();

    // informasi bangsal bagi Pasien Rawat Inap
    echo "<br>";

    if ($d->rawat_inap == "I") {
        $f = new Form("");
        $f->subtitle("Data Pemondokan");
        $f->execute();

        $t = new PgTable($con, "100%");

        $r1 = pg_query($con,
            "select sum(extract(day from case when d.ts_calc_stop is null ".
            "       then current_timestamp else d.ts_calc_stop ".
            "           end - d.ts_calc_start)*f.harga) as biaya ".
            "from rs00006 a ".
            "   left join rs00010 c ON a.id = c.no_reg and c.id = ".
            "       (select min(id) from rs00010 where no_reg = a.id) ".
            "   left join rs00010 d ON a.id = d.no_reg and d.id = ".
            "       (select max(id) from rs00010 where no_reg = a.id) ".
            "   left join rs00012 e ON d.bangsal_id = e.id ".
            "   left join rs00012 f ON substr(e.hierarchy,1,6)||'000000000' = f.hierarchy ".
            "   left join rs00012 g ON substr(e.hierarchy,1,3)||'000000000000' = g.hierarchy ".
            "   left join rs00001 h ON f.klasifikasi_tarif_id = h.tc and h.tt='KTR' ".
            "where a.id = '".$_GET["v"]."'");

        $d1 = pg_fetch_object($r1);
        pg_free_result($r1);

        $t->SQL =
            "select to_char(c.ts_check_in, 'DD-MON-YYYY HH24:MI:SS'), ".
            "   to_char(d.ts_calc_stop,'DD-MON-YYYY HH24:MI:SS'), e.bangsal, ".
            "   f.bangsal, g.bangsal, h.tdesc, ".
            "   extract(day from case when d.ts_calc_stop is null ".
            "       then current_timestamp else d.ts_calc_stop ".
            "           end - d.ts_calc_start) as hari, f.harga, ".
            "   to_number((extract(day from case when d.ts_calc_stop is null ".
            "       then current_timestamp else d.ts_calc_stop ".
            "           end - d.ts_calc_start)*f.harga),'999999999') as biaya ".
            "from rs00006 a ".
            "   left join rs00010 c ON a.id = c.no_reg and c.id = ".
            "       (select min(id) from rs00010 where no_reg = a.id) ".
            "   left join rs00010 d ON a.id = d.no_reg and d.id = ".
            "       (select max(id) from rs00010 where no_reg = a.id) ".
            "   left join rs00012 e ON d.bangsal_id = e.id ".
            "   left join rs00012 f ON substr(e.hierarchy,1,6)||'000000000' = f.hierarchy ".
            "   left join rs00012 g ON substr(e.hierarchy,1,3)||'000000000000' = g.hierarchy ".
            "   left join rs00001 h ON f.klasifikasi_tarif_id = h.tc and h.tt='KTR' ".
            "where a.id = '".$_GET["v"]."'";

        $t->setlocale("id_ID");
        $t->ShowRowNumber = true;
        $t->RowsPerPage = $ROWS_PER_PAGE;
        $t->ColAlign[6] = "RIGHT";
        $t->ColAlign[7] = "CENTER";
        $t->ColFormatMoney[7] = "%!+#2n";
        $t->ColFormatMoney[8] = "%!+#2n";
        $t->ColHeader = array("TGL. MASUK","TGL. KELUAR"," B E D",
                            "NAMA RUANG","BANGSAL  KEPERAWATAN",
                            "KLS.TARIF","JML. HARI","TARIF","Rp");
        $t->DisableScrollBar = true;
        $t->DisableStatusBar = true;
        $t->ColFooter[8] =  number_format($d1->biaya,2);
        //$t->ShowSQLExecTime = true;
        //$t->ShowSQL = true;

        $t->execute();
    }

} else {
    // search box
    title("Info Pasien Masuk/Keluar");
    //$ext = "OnChange = 'Form1.submit();'";
    echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

/*
    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));

    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
*/

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

    $f->selectSQL("mINOUT", "MASUK/KELUAR",
        "select '' as tc, '' as tdesc union ".
        "select 'I' as tc, 'MASUK' union ".
        "select 'O' as tc, 'KELUAR'", $_GET["mINOUT"]);

    $f->selectSQL("mUNIT", "U N I T",
        "select '' as tc, '' as tdesc union ".
		"select 'XX' as tc, 'Semua' as tdesc union ".
        "select 'RIN' as tc, 'RAWAT INAP' union ".
		"select 'RJN' as tc, 'RAWAT JALAN' union ".
		"select 'IGD' as tc, 'IGD' ", $_GET["mUNIT"]);
		
    $f->selectSQL("mTIPE", "TIPE PASIEN",
        "select '' as tc, '' as tdesc union ".
		"select '999' as tc , 'Semua' as tdesc union ".
        "select tc, tdesc ".
        "from rs00001 ".
		"where tt='JEP' and tc NOT IN ('000')", $_GET["mTIPE"]);

    $f->selectSQL("mDATANG", "JENIS KEDATANGAN",
        "select '' as tc, '' as tdesc union ".
		"select 'Z' as tc , 'Semua' as tdesc union ".
		"select 'N' as tc, 'Non-Rujukan' as tdesc union ".
		"select 'Y' as tc, 'Rujukan' as tdesc union ".
		"select 'U' as tc, 'Unit Lain' as tdesc", $_GET["mDATANG"]);
		
    $f->selectSQL("mLAMA", "LAMA/BARU",
        "select '' as tc, '' as tdesc union ".
		"select 'Z' as tc , 'Semua' as tdesc union ".
		"select 'T' as tc , 'LAMA' as tdesc union ".
		"select 'Y' as tc , 'BARU' as tdesc ", $_GET["mLAMA"]);

    $f->selectSQL("mSTATUSOUT", "STATUS KELUAR PASIEN",
        "select '' as tc, '' as tdesc union ".
		"select '999' as tc , 'Semua' as tdesc union ".
		"select tc , tdesc  ".
		"from rs00001 ".
		"where tt='SAP' and tc IN ('001','002','003','004','005','007','009','010')", $_GET["mSTATUSOUT"]);
		
    $f->submit ("OK");
    $f->execute();
    echo "<br>";
	if ($_GET["mUNIT"] == "XX" OR strlen($_GET["mUNIT"]) <=0) {
		$SQL1 = " ";
	} elseif (strlen($_GET["mUNIT"]) > 0) {
		$SQL1 = " and rtrim(a.trans_type) = '".$_GET["mUNIT"]."' ";
	}
	if ($_GET["mTIPE"] == "999" OR strlen($_GET["mTIPE"]) <= 0) {
		$SQL2 = " ";
	} elseif (strlen($_GET["mTIPE"]) > 0) {
		$SQL2 = " and e.tipe = '".$_GET["mTIPE"]."' ";
	}
	if ($_GET["mDATANG"] == "Z" OR strlen($_GET["mDATANG"]) <=0) {
		$SQL3 = " ";
	} elseif (strlen($_GET["mDATANG"]) > 0) {
		$SQL3 = " and a.datang_id = '".$_GET["mDATANG"]."' ";
	}
	if ($_GET["mLAMA"] == "Z" or strlen($_GET["mLAMA"]) <=0) {
		$SQL4 = " ";
	} elseif (strlen($_GET["mLAMA"]) > 0) {
		$SQL4 = " and a.is_baru = '".$_GET["mLAMA"]."' ";
	}
	if ($_GET["mSTATUSOUT"] == "999" OR strlen($_GET["mSTATUSOUT"]) <= 0) {
		$SQL5 = " ";
	} elseif (strlen($_GET["mUNIT"]) > 0) {
		$SQL5 = " and e.status_akhir_pasien = '".$_GET["mSTATUSOUT"]."' ";
	}

	$SQLIN		= 
	        "select distinct(a.no_reg), to_char(tanggal_entry,'DD MON YYYY') as tgl_reg, b.nama, f.layanan, b.mr_no, ".
            "	case when a.trans_type ='RJN' then 'RAWAT JALAN' ".
            "		when a.trans_type ='RIN' then 'RAWAT INAP' ".
            "		else 'IGD' end as rawat, ".
            "	c.tdesc as pasien,  ".
			"	case when a.datang_id='N' then 'Non-Rujukan' ".
			"		when a.datang_id ='U' then 'Unit Lain' ". 
			"		else 'Rujukan' end as rujuk, ".
            "	case when a.is_baru='T' then 'LAMA' else '-' end as lama, ".
			"	case when a.is_baru='T' then '-' else 'BARU' end as baru ".
            "from rs00008 a ".
            "	left join rs00006 e ON a.no_reg = e.id ".
            "	left join rs00002 b ON e.mr_no = b.mr_no ".
            "	left join rs00001 c ON (e.tipe = c.tc and c.tt='JEP') ".
			"	left join rs00001 d ON e.status_akhir_pasien = d.tc and d.tt='JDP' ".
			"	left join rs00034 f ON e.poli = f.id ".
            "where (a.tanggal_entry between '$ts_check_in1' and ".
            "       '$ts_check_in2') ";
	$SQLOUT	= 
			"select distinct(a.no_reg), to_char(tanggal_entry,'DD MON YYYY') as tgl_reg, b.nama, b.mr_no, ".
            "	case when a.trans_type ='RJN' then 'RAWAT JALAN' ".
            "		when a.trans_type ='RIN' then 'RAWAT INAP' ".
            "		else 'IGD' end as rawat, ".
            "	c.tdesc as pasien,  ".
			"	case when a.datang_id = 'N' then 'Non-Rujukan' ".
			"		 when a.datang_id = 'U' then 'Unit Lain' else 'Rujukan' end as rujuk, ".
            "	case when a.is_baru='T' then 'LAMA' else 'BARU' end as lama, ".
            "   d.tdesc as keluar, ".
			"	case when rtrim(a.trans_type) ='RIN' then a.qty else 0 end as lamadirawat, ".
            "   to_char(a.tanggal_trans,'DD MON YYYY') as tglkeluar ".
            "from rs00008 a ".
            "	left join rs00006 e ON a.no_reg = e.id ".
            "	left join rs00002 b ON e.mr_no = b.mr_no ".
            "	left join rs00001 c ON (e.tipe = c.tc and c.tt='JEP') ".
            "   left join rs00001 d ON (e.status_akhir_pasien = d.tc and d.tt='SAP') ".
            "where (a.tanggal_trans between '$ts_check_in1' and ".
            "       '$ts_check_in2') ";
	$SQLINOUT = " and a.is_inout = '".$_GET["mINOUT"]."' ";
			
    $t = new PgTable($con, "100%");
    if ($_GET["mINOUT"] == "I") {
        $t->SQL  = "$SQLIN $SQLINOUT $SQL1 $SQL2 $SQL3 $SQL4 ";
        $t->setlocale("id_ID");
        $t->ColHeader = array("NO.REG","TGL.REG","NAMA PASIEN","POLI", "MR.NO","LOKET",
                            "TIPE PASIEN","KEDATANGAN","&nbsp;","&nbsp;" );
        $t->ShowRowNumber = true;
        $t->ColAlign[0] = "CENTER";
        $t->ColAlign[1] = "CENTER";
        $t->ColAlign[4] = "CENTER";
        $t->RowsPerPage = $ROWS_PER_PAGE;
        $t->ColFormatHtml[0] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID".
                                "&v=<#0#>".
                                "&tanggal1D=".$_GET["tanggal1D"].
                                "&tanggal1M=".$_GET["tanggal1M"].
                                "&tanggal1Y=".$_GET["tanggal1Y"].
                                "&tanggal2D=".$_GET["tanggal2D"].
                                "&tanggal2M=".$_GET["tanggal2M"].
                                "&tanggal2Y=".$_GET["tanggal2Y"].
								"&mUNIT=".$_GET["mUNIT"].
								"&mTIPE=".$_GET["mTIPE"].
								"&mDATANG=".$_GET["mDATANG"].
								"&mLAMA=".$_GET["mLAMA"].
								"&mSTATUSOUT=".$_GET["mSTATUSOUT"].								
                                "&i=".$_GET["mINOUT"]."'><#0#></A>";
    } else {
        $t->SQL  = "$SQLOUT $SQLINOUT $SQL1 $SQL2 $SQL3 $SQL4 $SQL5 ";
        $t->setlocale("id_ID");
        $t->ColHeader = array("NO.REG","TGL.REG","NAMA PASIEN","MR.NO","U N I T",
                            "TIPE PASIEN","KEDATANGAN","LAMA / BARU",
                            "STATUS KELUAR","LAMA DIRAWAT","TGL. KELUAR");
        $t->ShowRowNumber = true;
        $t->ColAlign[0] = "CENTER";
        $t->ColAlign[1] = "CENTER";
        $t->ColAlign[3] = "CENTER";
        $t->ColAlign[11] = "CENTER";
        $t->RowsPerPage = $ROWS_PER_PAGE;
        $t->ColFormatHtml[0] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID".
                                "&v=<#0#>".
                                "&tanggal1D=".$_GET["tanggal1D"].
                                "&tanggal1M=".$_GET["tanggal1M"].
                                "&tanggal1Y=".$_GET["tanggal1Y"].
                                "&tanggal2D=".$_GET["tanggal2D"].
                                "&tanggal2M=".$_GET["tanggal2M"].
                                "&tanggal2Y=".$_GET["tanggal2Y"].
								"&mUNIT=".$_GET["mUNIT"].
								"&mTIPE=".$_GET["mTIPE"].
								"&mDATANG=".$_GET["mDATANG"].
								"&mLAMA=".$_GET["mLAMA"].
								"&mSTATUSOUT=".$_GET["mSTATUSOUT"].								
                                "&i=".$_GET["mINOUT"]."'><#0#></A>";
    }
	//$t->ShowSQL = true;
	//$t->Showsql = true;
    $t->execute();

}
?>
