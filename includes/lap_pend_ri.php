<?

$PID = "lap_pend_ri";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

if($_GET["tc"] == "view") {
    title("Rincian Pendapatan Rawat Inap");

    if ($_GET["e"] == "Y") {
        $unit = "Rawat Jalan";
    } elseif  ($_GET["e"] == "N"){
        $unit = "IGD";
    } elseif ($_GET["e"] == "I"){
        $unit = "Rawat Inap";
    } else {
        $unit = "Semua";
    }

    $pasien = getFromTable(
               "select tdesc from rs00001 ".
               "where tc = '".$_GET["u"]."' and tt='JEP'");

    $r = pg_query($con, "select tanggal(to_date(".$_GET["f"].",'YYYYMMDD'),3) as tgl");
    $d = pg_fetch_object($r);
    pg_free_result($r);

    $bulan = $d->tgl;
    $f = new Form("");
    $f->subtitle("Tanggal    : $bulan");
    $f->subtitle("U n i t    : $unit");
    $f->subtitle("Tipe Pasien : $pasien");
    $f->execute();

    echo "<br>";
    $t = new PgTable($con, "100%");
    $r2 = pg_query($con,
              "select sum(a.qty * a.harga) as jum ".
              "from rs00008 a ".
              "     left join rs00006 b ON a.no_reg = b.id ".
              "where b.rawat_inap='".$_GET["e"]."' and ".
              "     to_char(a.tanggal_trans,'YYYYMMDD') ='".$_GET["f"]. "' and ".
              "     a.trans_type='OB1' and b.tipe = '".$_GET["u"]."'");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    $t->SQL = "select c.mr_no,c.nama,a.no_reg, ".
              "     e.obat, a.qty, a.harga, sum(a.qty * a.harga) as tagih ".
              "from rs00008 a  ".
              "     left join rs00006 b ON a.no_reg = b.id ".
              "     left join rs00002 c ON b.mr_no = c.mr_no ".
              "     left join rs00001 d ON (b.tipe = d.tc and d.tt = 'JEP') ".
              "     left join rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
              "where ".
              " to_char(a.tanggal_trans,'YYYYMMDD') ='".$_GET["f"]. "' and ".
              "     b.rawat_inap ='".$_GET["e"]."' and ".
              "     a.trans_type = 'OB1' ".
              "group by c.mr_no, c.nama, a.no_reg, e.obat, a.qty, a.harga";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[2] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatMoney[4] = "%!+#2n";
    $t->ColFormatMoney[5] = "%!+#2n";
    $t->ColFormatMoney[6] = "%!+#2n";
    $t->ColHeader = array("MR.NO","NAMA","NO.REG","NAMA O B A T","QTY","HARGA","Rp.");
    $t->ColFooter[6] =  number_format($d2->jum,2);
    //$t->ShowSQLExecTime = true;
    //$t->ShowSQL = true;

    $t->execute();

} else {
    if (!$GLOBALS['print']){
    	title("<img src='icon/keuangan-2.gif' align='absmiddle' > Laporan Pendapatan Rawat Inap");
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Pendapatan Rawat Inap");
    }
    //title("LAPORAN PENDAPATAN RAWAT INAP");
	title_excel("lap_pend_ri&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."&mPASIEN=".$_GET["mPASIEN"]."");
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
        $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"]+1,$_GET["tanggal2Y"]));
    	$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
		$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
    	}

    	$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
        						 "select tc, tdesc from rs00001 ".
        						 "where tt='JEP' and tc != '000' ", $_GET["mPASIEN"],$ext);
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
        $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"]+1,$_GET["tanggal2Y"]));
    	$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
		$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
    	}

    	$f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
        						 "select tc, tdesc from rs00001 ".
        						 "where tt='JEP' and tc != '000' ", $_GET["mPASIEN"],"disabled");
		//$f->submit ("OK");
    	$f->execute();
	}	
    	
    	echo "<br>";

/*    
    if (!empty($_GET[mUNIT])) {
       $SQL_a = " and b.rawat_inap = '".$_GET["mUNIT"]."' ";
    } else {
       $SQL_a = " and b.rawat_inap = '".$_GET["mUNIT"]."' ";
    }

*/

    if (!empty($_GET[mPASIEN])) {
       $SQL_b = " and b.tipe = '".$_GET["mPASIEN"]."' ";
       $SQL_b2 = " and a.tipe = '".$_GET["mPASIEN"]."' ";
       
    } else {
       $SQL_b = " ";
    }

    if (strlen($_GET["search"]) > 0) {
        $r2 = pg_query($con, "select sum(jum) as jum,rawatan ".
              "from rsv0010 ".
              "where upper(rawatan) LIKE '%".strtoupper($_GET["search"])."%' ".
              "group by rawatan");

    } else {
        $r2 = pg_query($con,
	      "select sum(sarana) as sarana, sum(bahan) as bahan, sum(farmasi) as farmasi, sum(periksa) as periksa ".    	
	      "   , sum(obat) as obat, sum(jmlbayar) as jmlbayar, sum(harga) as harga ".	//, sum(jmlhari) as jmlhari ".
	      "from rsvmondok_new ". 

		"   left join rs00001 a on a.tdesc = rsvmondok_new.pasien and a.tt = 'JEP' ".
		"where (tgl_pulang between '$ts_check_in1' and '$ts_check_in2') ".
		"   and a.tc like '%".$_GET[mPASIEN]."%'".

	      " ");
	     
    
    }

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);


/*********************** komplitnya ini *********************

        $SQL = "select y.no_reg, a.mr_no,  to_char(z.ts_check_in, 'DD MON YYYY') as tgl_masuk, ".
	      "   to_char(z.ts_calc_stop, 'DD MON YYYY') as tgl_pulang, c.nama, c.umur, c.alm_tetap, ".
	      "   e.bangsal, f.tdesc as kelas,  ".

	      "   ((select sum(x.harga) from rsvsarana x where x.no_reg = y.no_reg) + ".
	      "      (select sum(jumlah) from rs00005 where reg=y.no_reg AND is_karcis='N' AND is_obat='N' AND kasir='RIN' AND layanan = 99996)) as sarana, ". 	 
	      "   (select sum(x.harga) from rsvbahan x where x.no_reg = y.no_reg) as bahan, ". 	 
	      "   (select sum(x.harga) from rsvperiksa x where x.no_reg = y.no_reg) as periksa, ". 
	      "   j.jumlah as obat,  ".	 
	      "   ((select sum(x.harga) from rsvsarana x where x.no_reg = y.no_reg) ".
	      "   + (select sum(x.harga) from rsvbahan x where x.no_reg = y.no_reg) ".
	      "   + (select sum(x.harga) from rsvperiksa x where x.no_reg = y.no_reg) ".
	      "   + (select sum(jumlah) from rs00005 where reg=y.no_reg AND is_karcis='N' AND is_obat='N' AND kasir='RIN' AND layanan = 99996) ".
	      "   + (case when j.jumlah > 0 then j.jumlah else 0 end)) as jmlbayar, ".
	      "   extract(day from z.ts_calc_stop - z.ts_calc_start) as jmlhari, k.tdesc as pasien ".


              "from rsv0010a y ".
	      "     left join rs00006 a ON y.no_reg = a.id ".
	      "     left join rs00010 z ON z.id = y.id ".
	      "     left join rs00002 c on a.mr_no = c.mr_no ".
	      "     left join rs00012 d on z.bangsal_id = d.id ".
	      "     left join rs00012 e on e.hierarchy = substr(d.hierarchy,1,6) || '000000000' ".
	      "     left join rs00001 f on e.klasifikasi_tarif_id = f.tc and tt = 'KTR' ".
	      "     left join rs00005 j on z.id = y.id and y.no_reg = j.reg and  j.is_obat = 'Y' ".
	      "     left join rs00001 k on a.tipe = k.tc and k.tt = 'JEP' ".
              "where ".
              "     (z.ts_calc_stop between '$ts_check_in1' and '$ts_check_in2') ".
	      "     $SQL_b2 ".
	      " ";
*************************************************************/





    if ($_GET[geser] == "kiri" || empty($_GET[geser])) {
    
    //echo "<div align=right><a href='index2.php?p=$PID".
	// "&tanggal1D=".$_GET[tanggal1D]."&tanggal1M=".$_GET[tanggal1M]."&tanggal1Y=".$_GET[tanggal1Y].
	// "&tanggal2D=".$_GET[tanggal2D]."&tanggal2M=".$_GET[tanggal2M]."&tanggal2Y=".$_GET[tanggal2Y].
	// "&mPASIEN=".$_GET[mPASIEN]."&tblstart=".$_GET[tblstart]."&sort=".$_GET[sort]."&geser=kanan".
	// "'><b> GESER KANAN &gt;&gt;</b></a></div>";

	$SQL = "select mr_no, no_reg, to_char(tgl_masuk, 'DD MON YYYY') as tgl_in ".
		"   , nama, bangsal||' / '||kelas AS bangsal, pasien,  to_char(sarana,'999,999,999,999.99'), to_char(bahan,'999,999,999,999.99'), to_char(farmasi,'999,999,999,999.99'), to_char(periksa,'999,999,999,999.99'), to_char(obat,'999,999,999,999.99'), to_char(harga,'999,999,999,999.99'), ".
		" to_char((sarana+bahan+farmasi+periksa+obat+biaya),'999,999,999,999.99') AS JML ".
		"from rsvmondok_new ".
		"   left join rs00001 a on a.tdesc = rsvmondok_new.pasien and a.tt = 'JEP' ".
		"where (tgl_pulang between '$ts_check_in1' and '$ts_check_in2') ".
		"   and a.tc like '%".$_GET[mPASIEN]."%' ";


/*
        $SQL = "select y.no_reg, a.mr_no,  to_char(z.ts_check_in, 'DD MON YYYY') as tgl_masuk, ".
	      "   to_char(z.ts_calc_stop, 'DD MON YYYY') as tgl_pulang, c.nama, c.umur, c.alm_tetap, ".
	      "   e.bangsal, f.tdesc as kelas  ".
              "from rsv0010a y ".
	      "     left join rs00006 a ON y.no_reg = a.id ".
	      "     left join rs00010 z ON z.id = y.id ".
	      "     left join rs00002 c on a.mr_no = c.mr_no ".
	      "     left join rs00012 d on z.bangsal_id = d.id ".
	      "     left join rs00012 e on e.hierarchy = substr(d.hierarchy,1,6) || '000000000' ".
	      "     left join rs00001 f on e.klasifikasi_tarif_id = f.tc and tt = 'KTR' ".
	      //"     left join rs00005 j on z.id = y.id and y.no_reg = j.reg and  j.is_obat = 'Y' ".
	      //"     left join rs00001 k on a.tipe = k.tc and k.tt = 'JEP' ".
              "where ".
              "     (z.ts_calc_stop between '$ts_check_in1' and '$ts_check_in2') ".
	      "     $SQL_b2 ".
	      " ";
*/
	 
    } elseif ($_GET[geser] == "kanan") {
    title_print("");
    if(!$GLOBALS['print']){
	    //echo "<div align=right><a href='index2.php?p=$PID".
		// "&tanggal1D=".$_GET[tanggal1D]."&tanggal1M=".$_GET[tanggal1M]."&tanggal1Y=".$_GET[tanggal1Y].
		// "&tanggal2D=".$_GET[tanggal2D]."&tanggal2M=".$_GET[tanggal2M]."&tanggal2Y=".$_GET[tanggal2Y].
		 //"&mPASIEN=".$_GET[mPASIEN]."&tblstart=".$_GET[tblstart]."&sort=".$_GET[sort]."&geser=kiri".
		// "'><b>&lt;&lt; GESER KIRI</b></a></div>";
    } else {
    	//echo "<div align=right><a href='index2.php?p=$PID".
		 //"&tanggal1D=".$_GET[tanggal1D]."&tanggal1M=".$_GET[tanggal1M]."&tanggal1Y=".$_GET[tanggal1Y].
		 //"&tanggal2D=".$_GET[tanggal2D]."&tanggal2M=".$_GET[tanggal2M]."&tanggal2Y=".$_GET[tanggal2Y].
		 //"&mPASIEN=".$_GET[mPASIEN]."&tblstart=".$_GET[tblstart]."&sort=".$_GET[sort]."&geser=kiri".
		 //"'><b>&nbsp;</b></a></div>";
    }

	$SQL = "select mr_no, nama, to_char(sarana,'999,999,999,999.99'), to_char(bahan,'999,999,999,999.99'), to_char(farmasi,'999,999,999,999.99'), to_char(periksa,'999,999,999,999.99'), to_char(obat,'999,999,999,999.99'), to_char(harga,'999,999,999,999.99'), ".
		" to_char((sarana+bahan+farmasi+periksa+obat+biaya),'999,999,999,999.99') AS JML ".
		"from rsvmondok_new ".
		"   left join rs00001 a on a.tdesc = rsvmondok_new.pasien and a.tt = 'JEP' ".
		"where (tgl_pulang between '$ts_check_in1' and '$ts_check_in2') ".
		"   and a.tc like '%".$_GET[mPASIEN]."%' ";
/*
        $SQL = "select y.no_reg, a.mr_no,  to_char(z.ts_check_in, 'DD MON YYYY') as tgl_masuk, ".
	      "   to_char(z.ts_calc_stop, 'DD MON YYYY') as tgl_pulang, c.nama,  ".
	      "   ((select sum(x.harga) from rsvsarana x where x.no_reg = y.no_reg) + ".
	      "      (select sum(jumlah) from rs00005 where reg=y.no_reg AND is_karcis='N' AND is_obat='N' AND kasir='RIN' AND layanan = 99996)) as sarana, ". 	 
	      "   (select sum(x.harga) from rsvbahan x where x.no_reg = y.no_reg) as bahan, ". 	 
	      "   (select sum(x.harga) from rsvperiksa x where x.no_reg = y.no_reg) as periksa, ". 
	      "   j.jumlah as obat,  ".	 
	      "   ((select sum(x.harga) from rsvsarana x where x.no_reg = y.no_reg) ".
	      "   + (select sum(x.harga) from rsvbahan x where x.no_reg = y.no_reg) ".
	      "   + (select sum(x.harga) from rsvperiksa x where x.no_reg = y.no_reg) ".
	      "   + (select sum(jumlah) from rs00005 where reg=y.no_reg AND is_karcis='N' AND is_obat='N' AND kasir='RIN' AND layanan = 99996) ".
	      "   + (case when j.jumlah > 0 then j.jumlah else 0 end)) as jmlbayar, ".
	      "   extract(day from z.ts_calc_stop - z.ts_calc_start) as jmlhari, k.tdesc as pasien ".
              "from rsv0010a y ".
	      "     left join rs00006 a ON y.no_reg = a.id ".
	      "     left join rs00010 z ON z.id = y.id ".
	      "     left join rs00002 c on a.mr_no = c.mr_no ".
	      //"     left join rs00012 d on z.bangsal_id = d.id ".
	      //"     left join rs00012 e on e.hierarchy = substr(d.hierarchy,1,6) || '000000000' ".
	      //"     left join rs00001 f on e.klasifikasi_tarif_id = f.tc and tt = 'KTR' ".
	      "     left join rs00005 j on z.id = y.id and y.no_reg = j.reg and  j.is_obat = 'Y' ".
	      "     left join rs00001 k on a.tipe = k.tc and k.tt = 'JEP' ".
              "where ".
              "     (z.ts_calc_stop between '$ts_check_in1' and '$ts_check_in2') ".
	      "     $SQL_b2 ".
	      " ";
*/
    
    }	 


    if (empty($_GET[sort])) {
	$_GET[sort] = "tgl_masuk";
	$_GET[order] = "desc";
    }


    $t = new PgTable($con, "100%");
    $t->SQL = "$SQL";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[3] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    if(!$GLOBALS['print']){
    $t->RowsPerPage = 20;
    //$t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view&e=".$_GET["mUNIT"]."&f=<#3#>&u=".$_GET["mPASIEN"]."'>".
    //                    icon("view","View")."</A>";
    }
    else{
    	$t->RowsPerPage = 20;
    	$t->DisableNavButton = true;
    	$t->DisableScrollBar = true;
    	//$t->DisableStatusBar = true;
    }
    
    if ($_GET[geser] == "kiri" || empty($_GET[geser])) {
    $t->ColHeader = array("NO.RM","NO.REG","TGL MASUK","NAMA","BANGSAL","TIPE PASIEN","SARANA","BAHAN","FARMASI","PEMERIKSAAN","OBAT","TARIF INAP","JML BAYAR");
    $t->ColAlign = array("CENTER","CENTER","CENTER","LEFT","LEFT","LEFT","RIGHT","RIGHT","RIGHT","RIGHT","RIGHT","RIGHT","RIGHT","RIGHT");		
    $t->ColFormatNumber[6] = 2;
    $t->ColFormatNumber[7] = 2;
    $t->ColFormatNumber[8] = 2;
    $t->ColFormatNumber[9] = 2;
    $t->ColFormatNumber[10] = 2;
    $t->ColFormatNumber[11] = 2;
    $t->ColFormatNumber[12] = 2;
    
    $t->ColFooter[6] =  number_format($d2->sarana,2,',','.');
    $t->ColFooter[7] =  number_format($d2->bahan,2,',','.');
    $t->ColFooter[8] =  number_format($d2->farmasi,2,',','.');
    $t->ColFooter[9] =  number_format($d2->periksa,2,',','.');
    $t->ColFooter[10] =  number_format($d2->obat,2,',','.');
    $t->ColFooter[11] =  number_format($d2->harga,2,',','.');
    $jmltotal = $d2->sarana+$d2->bahan+$d2->farmasi+$d2->periksa+$d2->obat+$d2->harga;
    $t->ColFooter[12] =  number_format($jmltotal,2,',','.');
    } elseif ($_GET[geser] == "kanan") {
    $t->ColHeader = array("NO.RM","NAMA",
			"SARANA","BAHAN","FARMASI","PEMERIKSAAN","OBAT","TARIF INAP","JML BAYAR");	//ganti JML HARI dg TARIF R.INAP
    $t->ColAlign = array("CENTER","LEFT","RIGHT","RIGHT","RIGHT","RIGHT","RIGHT","RIGHT","RIGHT","RIGHT");
    $t->ColAlign[10] = "CENTER";
    $t->ColFormatNumber[2] = 2;
    $t->ColFormatNumber[3] = 2;
    $t->ColFormatNumber[4] = 2;
    $t->ColFormatNumber[5] = 2;
    $t->ColFormatNumber[6] = 2;
    $t->ColFormatNumber[7] = 2;
    $t->ColFormatNumber[8] = 2;
    
    $t->ColFooter[2] =  number_format($d2->sarana,2,',','.');
    $t->ColFooter[3] =  number_format($d2->bahan,2,',','.');
    $t->ColFooter[4] =  number_format($d2->farmasi,2,',','.');
    $t->ColFooter[5] =  number_format($d2->periksa,2,',','.');
    $t->ColFooter[6] =  number_format($d2->obat,2,',','.');
    $t->ColFooter[7] =  number_format($d2->harga,2,',','.');
    $jmltotal = $d2->sarana+$d2->bahan+$d2->farmasi+$d2->periksa+$d2->obat+$d2->harga;
    $t->ColFooter[8] =  number_format($jmltotal,2,',','.');

    }
    
    
    $t->execute();

}

?>
