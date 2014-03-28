<? // 30/12/2003
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 29-04-2004
   // sfdn, 30-04-2004
   // sfdn, 09-05-2004
   // sfdn, 18-05-2004
   // sfdn, 02-06-2004
   // tokit aja, 15-09-2004
   // sfdn, 17-12-2006
   // sfdn, 24-12-2006
   // sfdn, 25-12-2006
   // sfdn, 26-12-2006

if ($_SESSION[uid] == "kasir2" || $_SESSION[uid] == "igd"|| $_SESSION[uid] == "kasir1"|| $_SESSION[uid] == "root") {  

$PID = "750";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

    if ($_SESSION[uid] == "kasir2") {
       $what = "RAWAT INAP";
       $sqlayanan = "NOT LIKE '%IGD%'";	
    } elseif ($_SESSION[uid] == "kasir1") {
       $what = "RAWAT JALAN";
       $sqlayanan = "NOT LIKE '%IGD%'";
    } else {
       $what = "IGD";
       $sqlayanan = "LIKE '%IGD%'";
    }

if (isset($_GET["v"])) {
    $r = pg_query($con, 
	    "select a.id,tanggal(tanggal_reg,3) as tgl_reg_str, tanggal_reg as tgl_reg, ".
            "		b.nama,b.pangkat_gol,b.nrp_nip, ".
	    "	case when a.rawat_inap ='I' then 'Rawat Inap' ".
	    "		when a.rawat_inap='Y' then 'Rawat Jalan' else 'IGD' end as rawatan, ".
            "	c.tdesc as pasien, a.id, a.mr_no, a.rawat_inap ".
            "from rs00006 a ".
            "   left join rs00002 b ON a.mr_no = b.mr_no ".
            "   left join rs00001 c ON (a.tipe = c.tc and c.tt='JEP') ".
            "where a.id = '".$_GET["v"]."' ");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    
    $xxtgl_reg = split("-", $d->tgl_reg);
    $xtgl_reg = $xxtgl_reg[2]."-".$xxtgl_reg[1]."-".$xxtgl_reg[0];
    title_print("<img src='icon/keuangan.gif' align='absmiddle' > INFO TRANSAKSI");
    title("Data Pemeriksaan Kesehatan Pasien");
    echo "<br>";
	$akhir = getFromTable("select to_char(CURRENT_TIMESTAMP,'DD-MON-YYYY HH24:MI:SS') as tgl");
	echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='50%'>";
    $f = new ReadOnlyForm();
    $f->text("Nama Pasien",$d->nama);
    $f->text("Pangkat", $d->pangkat_gol);
    $f->text("NRP / NIP", $d->nrp_nip);
    $f->text("Kesatuan / Tipe Pasien", $d->pasien);
    $f->execute();
    
    echo "</td><td valign=top width='50%'>";
    $f = new ReadOnlyForm();
    //$f->text("No. Registrasi", $d->id);
    $f->text("Nomor Registrasi", formatRegNo($d->id). " - " .
                getFromTable("select count(mr_no) from rs00006 where mr_no = '$d->mr_no'"));
    $f->text("Tanggal Registrasi", $xtgl_reg);
    $f->text("U n i t", $d->rawatan);
    $f->text("Data s/d", $akhir);
    $f->execute();
    echo "</td></tr></table><br>";

    //echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID&mPERIODE=".$_GET["x"]."&mUNIT=".$_GET["y"]."&mPASIEN=".$_GET["z"]."'>".icon("back","Kembali")."</a></DIV>";
    //$t->ColFormatHtml[6] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&v=<#6#>'>".icon("view","View")."</A>";

    $f = new Form("");
    $f->subtitle("Data Tindakan / Layanan");
    $f->execute();
    // sfdn, 26-12-2006 --> mengalikan qty*harga sebagai ganti tagihan
    $r2 = pg_query($con, "select sum(a.qty*a.harga) as jum ".
        "from rs00008 a, rs00034 e,rs00034 f ".
        "where a.trans_type='LTM' and to_number(a.item_id,'999999999999')= e.id ".
        "	and a.no_reg = '".$_GET["v"]."' and ".
        "	(a.tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') and ".
	// sfdn, 25-12-2006 --> diubah script di bawah ini
        "	case when length(rtrim(e.hierarchy,'0'))=12 ".
	"		  then substr(rtrim(e.hierarchy,'0'),1,9) = substr(rtrim(f.hierarchy,'0'),1,9) ".
        "	     when length(rtrim(e.hierarchy,'0'))=9 ".
	"		  then substr(rtrim(e.hierarchy,'0'),1,6) = substr(rtrim(f.hierarchy,'0'),1,6) ".
        "	     when length(rtrim(e.hierarchy,'0'))=6 ".
	"		  then substr(rtrim(e.hierarchy,'0'),1,3) = substr(rtrim(f.hierarchy,'0'),1,3) ".
        "	else substr(rtrim(e.hierarchy,'0'),1,12) = substr(rtrim(f.hierarchy,'0'),1,12) ".
	"	end ".
	"and f.is_group='Y' "); //AND f.layanan $sqlayanan ");

	// --- eof 26-12-2006 ---

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);


    // LAYANAN
    $t = new PgTable($con, "100%");
    $t->SQL =
        "select f.layanan as desc2,e.layanan as desc1,a.harga*a.qty as tagihan ".
        "from rs00008 a, rs00034 e,rs00034 f ".
        "where a.trans_type='LTM' and to_number(a.item_id,'999999999999')= e.id ".
        "	and a.no_reg = '".$_GET["v"]."' and ".
        "	(a.tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') and ".
	// sfdn, 25-12-2006 --> diubah script di bawah ini
        "	case when length(rtrim(e.hierarchy,'0'))=12 ".
	"		  then substr(rtrim(e.hierarchy,'0'),1,9) = substr(rtrim(f.hierarchy,'0'),1,9) ".
        "	     when length(rtrim(e.hierarchy,'0'))=9 ".
	"		  then substr(rtrim(e.hierarchy,'0'),1,6) = substr(rtrim(f.hierarchy,'0'),1,6) ".
        "	     when length(rtrim(e.hierarchy,'0'))=6 ".
	"		  then substr(rtrim(e.hierarchy,'0'),1,3) = substr(rtrim(f.hierarchy,'0'),1,3) ".
        "	else substr(rtrim(e.hierarchy,'0'),1,12) = substr(rtrim(f.hierarchy,'0'),1,12) ".
	"	end ".
	// --- eof 25-12-2006 ---
	"and f.is_group='Y' "; //AND f.layanan $sqlayanan ";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    //$t->ColFormatMoney[2] = "%!+#2n";
    $t->ColFormatNumber[2] = 2;
    $t->ColHeader = array("U N I T","NAMA LAYANAN/TINDAKAN","Rp.");
    $t->ColFooter[2] =  number_format($d2->jum,2);
    $t->DisableScrollBar = true;
    $t->DisableStatusBar = true;
    $t->execute();
    echo "<br>";



    // OBAT
	$totalsdhbayar = 0.00;
	$totalblmbayar = 0.00;
	$rec1 = getFromTable (
    		"select count(id) from rs00008 ".
			// sfdn, 24-12-2006 --> OB2 diubah menjadi OB11
			"where trans_type = 'OB1' and no_reg ='".$_GET["v"]."'");
			
	if ($rec1 > 0) {
		$SQL2 =		"select e.obat, a.harga, a.qty,'LUNAS', ".
					"a.qty*a.harga as njum ".
					"from rs00008 a ".
					"	left join  rs00015 e ON to_number(a.item_id,'999999999999') = e.id ".
					"where a.trans_type='OB1' and ".
					"	a.no_reg = '".$_GET["v"]."' and ".
					"	(a.tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') ";
		$totalsdhbayar = getFromTable(
					"select sum(qty*harga) as jumlah ".
					"from rs00008 ".
					"where trans_type='OB1' and ".
					"	no_reg = '".$_GET["v"]."' and ".
					"(tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') ");
			// ---- end of 24-12-2006 ----
		$f = new Form("");
		$f->subtitle("Data Obat");
		$f->execute();
		$t = new PgTable($con, "100%");
		$t->SQL = " $SQL2 ";
		$t->setlocale("id_ID");
		$t->ShowRowNumber = true;
		$t->RowsPerPage = $ROWS_PER_PAGE;
		$t->ColFormatNumber[1] = 0;
		$t->ColFormatNumber[2] = 2;
		$t->ColFormatNumber[3] = 2;
	        $t->ColFormatNumber[4] = 2;
		$t->ColHeader = array("NAMA OBAT","HARGA SATUAN","QTY","STATUS","TOTAL(Rp.)");
		$t->ColFooter[4] =  number_format($totalsdhbayar,2);
		$t->DisableScrollBar = true;
		$t->DisableStatusBar = true;
	   $t->execute();		

    } else {
		$rec = getFromTable (
				"select count(id) from rs00008 ".
				"where trans_type = 'OB1' and is_bayar='N' ".
				"		and no_reg ='".$_GET["v"]."'");
		if ($rec > 0) {
			$SQL1 =	"select b.obat, a.qty, a.harga, sum(a.qty*a.harga) as tagihan ".
					"from rs00008 a ".
					"	left join rs00015 b ".
					"ON to_number(a.item_id,'999999999999') = b.id ".
					"where a.trans_type = 'OB1' and ".
					"	a.no_reg = '".$_GET["v"]."' and ".
					"(a.tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') ".
					"group by b.obat, a.qty, a.harga ";
			$SQL2 ="";
			$totalblmbayar = getFromTable(
					"select sum(qty*harga) as jumlah ".
					"from rs00008 ".
					"where trans_type='OB1' and ".
					"	no_reg = '".$_GET["v"]."' and ".
					"(tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') ");

		$f = new Form("");
		$f->subtitle("Data Obat");
		$f->execute();
		
		$t = new PgTable($con, "100%");
		$t->SQL = " $SQL1 ";
		$t->setlocale("id_ID");
		$t->ShowRowNumber = true;
		$t->RowsPerPage = $ROWS_PER_PAGE;
		//$t->ColFormatMoney[1] = "%!+#2n";
		//$t->ColFormatMoney[2] = "%!+#2n";
		//$t->ColFormatMoney[3] = "%!+#2n";
		$t->ColFormatNumber[1] = 0;
		$t->ColFormatNumber[2] = 2;
		$t->ColFormatNumber[3] = 2;
		$t->ColHeader = array("NAMA OBAT","QTY","HARGA","TOTAL");
		$t->ColFooter[3] =  number_format($totalblmbayar,2,',','.');
		$t->DisableScrollBar = true;
		$t->DisableStatusBar = true;
    		$t->execute();
	    	echo "<br>";

		} 
		
	}

    
    // DIAGNOSA
	$rec1 = getFromTable (
    		"select count(id) from rs00008 ".
			// sfdn, 27-12-2006 --> melakukan testing apakah ada data diagnosa
			"where trans_type = 'ICD' and no_reg ='".$_GET["v"]."'");
	if ($rec1 > 0) {

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
			"   a.no_reg = '".$_GET["v"]."' and ".
			"   (a.tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') )";
		
		$t->setlocale("id_ID");
		$t->ShowRowNumber = true;
		$t->RowsPerPage = $ROWS_PER_PAGE;
		$t->ColHeader = array("KODE ICD","DESKRIPSI ICD","DIAGNOSA");
		$t->DisableScrollBar = true;
		$t->DisableStatusBar = true;
		
		$t->execute();
    
	}

     // ---------------    
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
        //$t->ColFormatMoney[7] = "%!+#2n";
        //$t->ColFormatMoney[8] = "%!+#2n";
        $t->ColFormatNumber[7] = 2;
        $t->ColFormatNumber[8] = 2;
	$t->ColFormatNumber[9] = 2;
	
        $t->ColHeader = array("TGL. MASUK","TGL. KELUAR"," B E D",
                            "NAMA RUANG","BANGSAL   KEPERAWATAN",
                            "KLS.TARIF","JML. HARI","TARIF","Rp");
        $t->DisableScrollBar = true;
        $t->DisableStatusBar = true;
	//$t->ColFooter[7] =  number_format($d1->hari,2);
        $t->ColFooter[8] =  number_format($d1->biaya,2);
	//$t->ColFooter[9] =  number_format($d1->biaya*$d1->hari,2);
        //$t->ShowSQLExecTime = true;
        //$t->ShowSQL = true;

        $t->execute();
    }
} else {
    // search box
    title("<img src='icon/keuangan-2.gif' align='absmiddle' > TRANSAKSI PASIEN");
    $ext = "OnChange = 'Form1.submit();'";
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

include("xxx2");

    $f->selectArray("rawat_inap", "U n i t",
                        Array(""=>"", "N" => "IGD", "Y" => "Rawat Jalan",  "I" => "Rawat Inap"),
                        $_GET[rawat_inap], "");
    $f->selectSQL("mPASIEN","Tipe Pasien","select '' as tc, '' as tdesc union select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000' ",$_GET[mPASIEN],"");

    $f->submit (" OK ");
    $f->execute();


   if (!empty($_GET[mPASIEN])) {
      $add = " and a.tipe = '".$_GET[mPASIEN]."'";
   } else {
      $add = "";
   }

    echo "<BR>";
    $r2 = pg_query($con,
	// sfdn, 24-12-2006 --> hitungan tagihan = b.qty*b.harga
        "select sum(b.qty*b.harga) as jum ".
	// --- end of 24-12-2006 ---
        "from rs00006 a ".
        "   left join rs00008 b ON a.id = b.no_reg and b.is_bayar = 'Y' ".
        "   left join rs00002 c ON a.mr_no = c.mr_no ".
        "where ".
	// sfdn, 25-12-2006 --> ditambahkan filter untuk kategori layanan = $_GET[rawat_inap]
	"	a.rawat_inap = '".$_GET[rawat_inap]."' ".
	// --- end of 25-12-2006
	"	and (a.tanggal_reg between '$ts_check_in1' and '$ts_check_in2') $add ");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);


    $t = new PgTable($con, "100%");
    $t->SQL  =
                "select a.mr_no,c.nama,a.id, ".
                "   to_char(b.tanggal_entry,'DD-MM-YYYY') as tgl_reg_str, ".
                "   d.tdesc as pasien, ".
                "   case when a.rawat_inap='I' then 'RAWAT INAP' ".
                "       when a.rawat_inap='Y' then 'RAWAT JALAN' ".
		// sfdn, 24-12-2006 --> hitungan tagihan = b.qty*b.harga)
                "       else 'IGD' end as rawat, x.tdesc ".
		// --- end 24-12-2006
                "from rs00006 a ".
                "   left join rs00008 b ON a.id = b.no_reg ".
                "   left join rs00002 c ON a.mr_no = c.mr_no ".
                "   left join rs00001 d ON a.tipe = d.tc and d.tt='JEP' ".
                "   left join rs00001 x ON a.poli = x.tc and x.tt='LYN' ".
                "where (upper(c.nama) LIKE '%".strtoupper($_GET["search"])."%' OR ".
                "   no_reg LIKE '%".$_GET["search"]."%' ) and ".
                "   (b.tanggal_entry between '$ts_check_in1' and '$ts_check_in2') ".
                "   and (a.rawat_inap = '".$_GET[rawat_inap]."') $add  ".
                "group by a.mr_no,c.nama,a.id,b.tanggal_entry,d.tdesc, a.rawat_inap, x.tdesc ";
                //"order by a.id";


    if (!isset($_GET[sort])) {
	$_GET[sort] = "id";
	$_GET[order] = "desc";
    
    }
    $t->setlocale("id_ID");
    $t->ColHeader = array("MR. NO","NAMA PASIEN","NO.REG","TGL.REG", "TIPE PASIEN","UNIT","RAWATAN" );
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "CENTER";
    $t->ColFormatNumber[6] = 2;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID".
                           "&v=<#2#>".
                           "&t1=$ts_check_in1".
                           "&t2=$ts_check_in2".
                           "'><#2#></A>";
    //$t->ColFooter[6] =  number_format($d2->jum,2);
    $t->execute();

}
} // --- end of ($_SESSION[uid] ----
?>