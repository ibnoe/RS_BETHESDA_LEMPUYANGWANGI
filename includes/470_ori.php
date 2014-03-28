<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 09-05-2004
   // sfdn, 11-05-2004
   // sfdn, 14-05-2004
   // sfdn, 29-05-2004


$PID = "470";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if($_GET["tc"] == "view") {
    title("Rincian Laporan Rawat Jalan");
    if ($_GET["x"] == "001") {
        $tp = getFromTable(
               "select month_str || '  ' || to_char(tahun,'9999') as prd from rs00035 ".
               "where id = '".$_GET["y"]."'");
        $bulan = "Bulan : $tp";
    } else {
        $tp = $_GET["y"];
        $tahun = $_GET["z"];
        $bulan = "Kuartal  $tp $tahun";

    }
    $unit = getFromTable(
               "select layanan from rs00034 ".
               "where id = '".$_GET["f"]."'");
    $f = new Form("");
    $f->subtitle($bulan);
    $f->subtitle("U n i t : $unit");
    $f->execute();

    echo "<br>";
    $t = new PgTable($con, "100%");
    $t->SQL =
        "select a.no_reg,to_char(a.tanggal_trans,'DD-MON-YYYY') as tgl, ".
        "   c.nama,e.tdesc as pasien ".
	    "from rs00008 a ".
	    "   left join rs00006 b ON a.no_reg = b.id ".
	    "   left join rs00002 c ON b.mr_no = c.mr_no ".
	    "   left join rs00001 e ON b.tipe = e.tc ".
	    "   left join rs00034 d ON to_number(a.item_id,'999999999')= d.id ".
        "where a.trans_type = 'LTM' and ".
	    "   e.tt='JEP' and d.id = '".$_GET["f"]."'";

       $t->ColHeader = array("NO.REG.", "TGL TRANSK.","NAMA PASIEN","TIPE PASIEN");

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->execute();

} else {
    // search box
    title("REKAPITULASI PELAYANAN RAWAT JALAN");
    $ext = "OnChange = 'Form1.submit();'";
    echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    /*
    $f->selectSQL("mPERIODE", "Bulan/Kuartal",
        "select '' as tc, '' as tdesc union ".
        "select '001' as tc, 'PER BULAN' as tdesc union ".
        "select '002' as tc, 'PER KUARTAL' as tdes "
        , $_GET["mPERIODE"],
        $ext);
    
    if ($_GET["mPERIODE"] == "001") {
        $f->selectSQL("mBULAN", "B u l a n",
            "select month_no as tc, month_str as tdesc ".
            "from rs00035 "
            , $_GET["mBULAN"],
            $ext);
*/

    $f->selectArray("mBULAN","Bulan",Array("1"=>"Januari","2"=>"Februari","3"=>"Maret","4"=>"April",
         "5"=>"Mei","6"=>"Juni","7"=>"Juli","8"=>"Agustus","9"=>"September","10"=>"Oktober",
	 "11"=>"November","12"=>"Desember"),$_GET["mBULAN"],$ext);       

/*
    } elseif ($_GET["mPERIODE"] == "002") {
        $f->selectSQL("mBULAN", "Kuartal ke",
            "select '' as tc, '' as tdesc union ".
            "select distinct(rtrim(ltrim(to_char(q,'99')))) as tc, to_char(q,'99') as tdesc ".
            "from rs00035 "
            , $_GET["mBULAN"],
            $ext);
    }
*/
    $f->selectSQL("mTAHUN", "T a h u n",
        "select '' as tc, '' as tdesc union ".
        "select distinct(ltrim(to_char(tahun,'9999'))) as tc, to_char(tahun,'9999') as tdesc  ".
        "from rs00035 "
        , $_GET["mTAHUN"],
        $ext);
    $f->execute();
    
    echo "<br>";
    //if ($_GET["mPERIODE"]<>'' && $_GET["mBULAN"]<>'' && $_GET["mTAHUN"]<>'') {
    
    $_GET["mPERIODE"] = "001";
    $start_tgl = mktime(0,0,0,$_GET[mBULAN],1,$_GET[mTAHUN]);
    $max_tgl = date("t", $start_tgl);
    $end_tgl = mktime(0,0,0,$_GET[mBULAN],$max_tgl,$_GET[mTAHUN]);
    $start_tgl = date("Y-m-d", $start_tgl);
    $end_tgl = date("Y-m-d", $end_tgl);
    
    if ($_GET["mPERIODE"]<>''&& $_GET["mBULAN"]<>'' && $_GET["mTAHUN"]<>'') {
    
    if ($_GET["mPERIODE"] == "001") {
        $prd_id = getFromTable(
               "select id from rs00035 ".
               "where month_no = '".$_GET["mBULAN"]."' and ".
               "    tahun ='".$_GET["mTAHUN"]."'");
        
	/*
	$r2 = pg_query($con,
            "select sum(jml_pasien_masuk) as masuk, sum(jml_rujukan_dari_bawah) as bawah, ".
            "   sum(jml_dirujuk_keatas) as atas,sum(jml_jpsbk) as jpsbk ".
            "from rs00042 ".
            "where time_id = $prd_id ");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
	*/

       $q =
            pg_query("select a.layanan, count(b.id) as jml_masuk, count(c.status_akhir_pasien) as rujuk_bawah, ".
	    "   count(d.status_akhir_pasien) as rujuk_atas ".//, count(e.tipe) as jpsbk ".
	    "from rs00034 a ".
	    "   left join rs00006 b on b.poli = a.id  and (b.tanggal_reg >=  '$start_tgl' and b.tanggal_reg <= '$end_tgl') ".
	    "   left join rs00006 c on c.poli = a.id and c.status_akhir_pasien = '008' and (c.tanggal_reg >=  '$start_tgl' and c.tanggal_reg <= '$end_tgl') ".
	    "   left join rs00006 d on d.poli = a.id and d.status_akhir_pasien = '009' and (d.tanggal_reg >=  '$start_tgl' and d.tanggal_reg <= '$end_tgl') ".
	    //"   left join rs00006 e on e.poli = a.id and e.tipe = '002' and (e.tanggal_reg between  '$start_tgl' and  '$end_tgl') ".
	    "where a.id in (11071,11072,11073,11074,11075,11076,11095,11096,11097,11098,8,7,10) ".
	    "   ".
	    "group by a.layanan order by a.layanan");
        
	//echo $q;
	
	$d2 = pg_fetch_object($q);
	
	
	if ($d2) {
	do  {
	   
	   $masuk = $masuk + $d2->jml_masuk;
	   $bawah = $bawah + $d2->rujuk_bawah;
	   $atas = $atas + $d2->rujuk_atas;
	   
	} while ($d2 = pg_fetch_object($q));
	}
        
	
	
	//pg_free_result($r2);
	
	
	
    } elseif ($_GET["mPERIODE"] == "002") {
        $prd_id = $_GET["mBULAN"];
        $r2 = pg_query($con,
            "select sum(jml_pasien_masuk) as masuk, sum(jml_rujukan_dari_bawah) as bawah, ".
            "   sum(jml_dirujuk_keatas) as atas,sum(jml_jpsbk) as jpsbk ".
            "from rs00042 ".
            "where time_id IN (select id from rs00035 where q='".$_GET["mBULAN"].
            "' and tahun='".$_GET["mTAHUN"]."')");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
    }
    
    $t = new PgTable($con, "100%");
    if ($_GET["mPERIODE"] == "001") {
        
    /*
       $t->SQL =
            "select b.layanan, a.jml_pasien_masuk, a.jml_rujukan_dari_bawah, ".
            "   a.jml_dirujuk_keatas,a.jml_jpsbk, a.rs00034_id as dummy ".
            "from rs00042 a ".
            "left join rs00034 b ON a.rs00034_id = b.id ".
            "where a.time_id = $prd_id";
    */
       $t->SQL =
            "select a.layanan, count(b.id) as jml_masuk, count(c.status_akhir_pasien) as rujuk_bawah, ".
	    "   count(d.status_akhir_pasien) as rujuk_atas ".//, count(e.tipe) as jpsbk ".
	    "from rs00034 a ".
	    "   left join rs00006 b on b.poli = a.id  and (b.tanggal_reg >=  '$start_tgl' and b.tanggal_reg <= '$end_tgl') ".
	    "   left join rs00006 c on c.poli = a.id and c.status_akhir_pasien = '008' and (c.tanggal_reg >=  '$start_tgl' and c.tanggal_reg <= '$end_tgl') ".
	    "   left join rs00006 d on d.poli = a.id and d.status_akhir_pasien = '009' and (d.tanggal_reg >=  '$start_tgl' and d.tanggal_reg <= '$end_tgl') ".
	    //"   left join rs00006 e on e.poli = a.id and e.tipe = '002' and (e.tanggal_reg between  '$start_tgl' and  '$end_tgl') ".
	    "where a.id in (11071,11072,11073,11074,11075,11076,11095,11096,11097,11098,8,7,10) ".
	    "   ".
	    "group by a.layanan order by a.layanan";
    
    } elseif ($_GET["mPERIODE"] == "002") {
        $t->SQL =
            "select b.layanan, sum(a.jml_pasien_masuk) as masuk, ".
            "   sum(a.jml_rujukan_dari_bawah) as bawah,  ".
	        "   sum(a.jml_dirujuk_keatas) as atas,	sum(a.jml_jpsbk) as jpsbk, ".
            "   a.rs00034_id as dummy ".
            "from rs00042 a ".
	        "left join rs00034 b ON a.rs00034_id = b.id ".
            "where a.time_id IN (select id from rs00035 where q='".$_GET["mBULAN"].
            "' and tahun='".$_GET["mTAHUN"]."')".
            "group by a.rs00034_id, b.layanan";
    }
    if ($_GET["mPERIODE"] == "001" OR $_GET["mPERIODE"] == "002") {
        $t->setlocale("id_ID");
        $t->ShowRowNumber = true;
        $t->RowsPerPage = $ROWS_PER_PAGE;
        $t->ColAlign[5] = "CENTER";
	$t->ColAlign[1] = "CENTER";
	$t->ColAlign[2] = "CENTER";
	$t->ColAlign[3] = "CENTER";
	
        $t->ColFormatMoney[1] = "%!+#2n";
        $t->ColFormatMoney[2] = "%!+#2n";
        $t->ColFormatMoney[3] = "%!+#2n";
        $t->ColFormatMoney[4] = "%!+#2n";
        $t->ColHeader = array("INSTALASI RAWAT JALAN","JML.PASIEN MASUK","RJK.DARI BAWAH",
                              "DIRUJUK KE ATAS","PASIEN JPSBK","View");
        $t->ColFormatHtml[5] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID".
                            "&tc=view&f=<#5#>".
                            "&x=".$_GET["mPERIODE"].
                            "&y=$prd_id".
                            "&z=".$_GET["mTAHUN"]."'>".
                            icon("view","View")."</A>";
        $t->ColFooter[1] =  number_format($masuk,2);
        $t->ColFooter[2] =  number_format($bawah,2);
        $t->ColFooter[3] =  number_format($atas,2);
        //$t->ColFooter[4] =  number_format($jpsbk,2);
        $t->execute();
       }
    }
    }

?>
