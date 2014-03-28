<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 09-05-2004
   // sfdn, 11-05-2004
   // sfdn, 12-05-2004

session_start();
$PID = "post_pend_jm";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/querybuilder.php");
require_once("lib/class.PgTrans.php");


if($_GET["tc"] == "posting") {

    $tr = new PgTrans;
    $tr->PgConn = $con;
     // insert ke table master pendapatan JM (rs000037)
     $tr->addSQL(
        "insert into rs00037 (" .
            "rs00008_id,no_reg,tanggal_trans,tagihan,sumber_pendapatan_id, ".
            "tipe_pasien,trans_group,item_id ) ".
        "select ".
            "a.id as var1, a.no_reg as var2, a.tanggal_trans as var3, a.tagihan as var4, ".
            "b.sumber_pendapatan_id as var5, ".
            "d.tipe as var6, a.trans_group as var7, a.item_id as var8 ".
        "from rs00008 a, rs00034 b, rs00001 c, rs00006 d ".
        "where ".
            "a.trans_type='LTM' and  a.is_posted='N' and ".
            "to_char(a.tanggal_trans,'YYYYMM')='".$_GET["t"]."' and ".
	        "to_number(a.item_id,'999999999') = b.id and ".
	        "a.no_reg = d.id and d.tipe = '".$_GET["c"]."' and ".
	        "b.sumber_pendapatan_id = c.tc and c.tt='SBP' and ".
            "c.tc = '".$_GET["u"]."'");

    // update ke table rs00008.is_posted='Y'
        $tr->addSQL("update rs00008 set is_posted = 'Y' ".
            "where id = '".$_GET["e"]."'");

    //$tr->showSQL();
    if ($tr->execute()) {
       // header("Location: ../index2.php?p=435");
    echo "<div align=right><a href='".
        "$SC?p=$PID'>".icon("back", "Kembali")."</a></div>";

        exit;
    } else {
        echo $tr->ErrMsg;
    }

/*
$SQL = "insert into rs00010 (id, no_reg, bangsal_id, ts_check_in, ts_calc_start) ".
       "values (nextval('rs00010_seq'),'".$_POST["rg"]."','".$_SESSION["BANGSAL"]["id"].
       "','$ts_check_in'::timestamp,'$ts_calc_start'::timestamp)";

$SQL = "insert into rs00037 (" .
            "rs00008_id,no_reg,tanggal_trans,tagihan,sumber_pendapatan_id, ".
            "tipe_pasien,trans_group,item_id ) ".
        "select ".
            "a.id as var1, a.no_reg as var2, a.tanggal_trans as var3, a.tagihan as var4, ".
            "b.sumber_pendapatan_id as var5, ".
            "d.tipe as var6, a.trans_group as var7, a.item_id as var8 ".
        "from rs00008 a, rs00034 b, rs00001 c, rs00006 d ".
        "where ".
            "a.trans_type='LTM' and  a.is_posted='N' and ".
            "to_char(a.tanggal_trans,'YYYYMM')='".$_GET["t"]."' and ".
	        "to_number(a.item_id,'999999999') = b.id and ".
	        "a.no_reg = d.id and d.tipe = '".$_GET["c"]."' and ".
	        "b.sumber_pendapatan_id = c.tc and c.tt='SBP' and ".
            "c.tc = '".$_GET["u"]."'";

        pg_query($con, $SQL);


        //header("Location: ../index2.php?p=$PID");

    echo "<div align=right><a href='".
        "$SC?p=$PID'>".icon("back", "Kembali")."</a></div>";

        exit;
*/
} else {
    // search box
    if (!$GLOBALS['print']){
    	title_print("<img src='icon/medical-record-2.gif' align='absmiddle' > Posting Pendapatan Jasa Medis");
    } else {
    	title("<img src='icon/medical-record.gif' align='absmiddle' > Posting Pendapatan Jasa Medis");
    }
    //title("POSTING PENDAPATAN JASA MEDIS");
	title_excel("post_pend_jm&mPERIODE=".$_GET["mPERIODE"]."&mUNIT=".$_GET["mUNIT"]."&mPASIEN=".$_GET["mPASIEN"]."");
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    if (!$GLOBALS['print']) {
	    $f->selectSQL("mPERIODE", "Periode","select '' as tc, '' as tdesc union ".
	        "select distinct(to_char(tanggal_trans,'YYYYMM')) as tc, to_char(tanggal_trans,'MONTH YYYY') as tdesc ".
	        "from rs00008 where trans_type = 'LTM' ", $_GET["mPERIODE"],$ext);
	
	    $f->selectSQL("mUNIT", "Jenis Sumber Pendapatan","select '' as tc, '' as tdesc union ".
	        "select tc, tdesc from rs00001 where tc!='000' and tt = 'SBP' ", $_GET["mUNIT"],$ext);
	
	    $f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
	        "select distinct(b.tipe) as tc, c.tdesc as tdesc from rs00008 a, rs00006 b, rs00001 c ".
	        "where a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP'", $_GET["mPASIEN"],$ext);	
	    $f->submit ("TAMPILKAN");
	    $f->execute();
    } else {
	    $f->selectSQL("mPERIODE", "Periode","select '' as tc, '' as tdesc union ".
	        "select distinct(to_char(tanggal_trans,'YYYYMM')) as tc, to_char(tanggal_trans,'MONTH YYYY') as tdesc ".
	        "from rs00008 where trans_type = 'LTM' ", $_GET["mPERIODE"],"disabled");	
	        
	    $f->selectSQL("mUNIT", "Jenis Sumber Pendapatan","select '' as tc, '' as tdesc union ".
	        "select tc, tdesc from rs00001 where tc!='000' and tt = 'SBP' ", $_GET["mUNIT"],"disabled");	
	        
	    $f->selectSQL("mPASIEN", "Tipe Pasien","select '' as tc, '' as tdesc union ".
	        "select distinct(b.tipe) as tc, c.tdesc as tdesc from rs00008 a, rs00006 b, rs00001 c ".
	        "where a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP'", $_GET["mPASIEN"],"disabled");	
	    $f->execute();
    }
    
    
    echo "<br>";
    
    //title_print("");
    $rs = "R/S";
    $pemda = "PEMDA";

    if ($_GET["mUNIT"] == "003" and $_GET["mPASIEN"] == "001") {
        $rs = "R/S 75%";
        $pemda = "PEMDA 25%";
    } elseif ($_GET["mUNIT"] == "003" and $_GET["mPASIEN"] == "004" ) {
        $rs = "R/S 100%";
        $pemda = "PEMDA 0%";

    } elseif (($_GET["mUNIT"] == "001" OR $_GET["mUNIT"] == "002") and $_GET["mPASIEN"] == "001" ) {
        $rs = "R/S 5%";
        $pemda = "PEMDA 95%";
    } elseif (($_GET["mUNIT"] == "001" OR $_GET["mUNIT"] == "002") and $_GET["mPASIEN"] == "004" ) {
        $rs = "R/S 100%";
        $pemda = "PEMDA 0%";

    } elseif (($_GET["mUNIT"] == "004" and $_GET["mPASIEN"] == "001") OR ($_GET["mUNIT"] == "004" and $_GET["mPASIEN"] == "004")) {
        $rs = "R/S 50%";
        $pemda = "PEMDA 50%";
    }

    if (strlen($_GET["search"]) > 0) {
        $r2 = pg_query($con, "select sum(jum) as jum,rawatan ".
              "from rsv0010 ".
	          "where upper(rawatan) LIKE '%".strtoupper($_GET["search"])."%' ".
              "group by rawatan");

    } else {
        $r2 = pg_query($con,
            "select sum(tagihan) as jum ".
            "from rs00008 a, rs00034 b , rs00006 c ".
            "where a.trans_type='LTM' and ".
            "to_number(a.item_id,'999999999') = b.id and ".
            "a.no_reg = c.id and ".
            "to_char(a.tanggal_trans,'YYYYMM') = '".$_GET["mPERIODE"]."' and ".
            "b.sumber_pendapatan_id like '%".$_GET["mUNIT"]."%' and ".
            "c.tipe ='".$_GET["mPASIEN"]."' and a.is_posted='N'");
    }
    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);
    $t = new PgTable($con, "100%");
    $t->SQL =
       /* "select a.no_reg,b.nama, TO_CHAR(a.tanggal_trans,'dd-mm-yyyy')  as tanggal_trans, f.layanan as desc1, ".
        "e.layanan as desc2,a.tagihan, a.id ".
	    "from rs00008 a, rs00002 b,rs00001 c,rs00006 d,rs00034 e, rs00034 f ".
	    "where (a.trans_type='LTM' and a.is_posted = 'N' and ".
        "to_char(a.tanggal_trans,'YYYYMM') = '".$_GET["mPERIODE"]."') and ".
	    "(a.no_reg = d.id and d.mr_no = b.mr_no and d.tipe ='".$_GET["mPASIEN"]."') and ".
	    "(to_number(a.item_id,'999999999')= e.id and ".
        "e.sumber_pendapatan_id= c.tc and c.tt='SBP' and c.tc='".$_GET["mUNIT"]."' and ".
        "case when length(rtrim(e.hierarchy,'0'))=9 ".
        "then substr(rtrim(e.hierarchy,'0'),1,6) = substr(rtrim(f.hierarchy,'0'),1,6) ".
        "when length(rtrim(e.hierarchy,'0'))=8 ".
        "then substr(rtrim(e.hierarchy,'0'),1,6) = substr(rtrim(f.hierarchy,'0'),1,6) ".
        "when length(rtrim(e.hierarchy,'0'))=12 ".
        "then substr(rtrim(e.hierarchy,'0'),1,6) = substr(rtrim(f.hierarchy,'0'),1,6) ".
        "else substr(rtrim(e.hierarchy,'0'),1,12) = ".
        "substr(rtrim(f.hierarchy,'0'),1,12) end and ".
        "f.is_group='Y')"; */
        
        "select a.no_reg,b.nama, b.pangkat_gol, b.nrp_nip, ".
        "b.kesatuan, e.layanan as desc2, a.tagihan ".
		"from rs00008 a, rs00002 b,rs00001 c,rs00006 d,rs00034 e, rs00034 f ".
		"where (a.trans_type='LTM' and a.is_posted = 'N' and ".
		"to_char(a.tanggal_trans,'YYYYMM') = '".$_GET["mPERIODE"]."') and ".
		"(a.no_reg = d.id and d.mr_no = b.mr_no and d.tipe ='".$_GET["mPASIEN"]."') and ".
		"(to_number(a.item_id,'999999999')= e.id and ".
		"e.sumber_pendapatan_id= c.tc and c.tt='SBP' and c.tc like '%".$_GET["mUNIT"]."%' and ".
		"to_number(a.item_id,'999999999') = f.id )";

    //$t->ColFormatHtml[6] = "<A CLASS=TBL_HREF HREF='$SC?p=435.insert.php'>post?</A>";

    /*
    $t->ColFormatHtml[6] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=posting&e=<#6#>".
                    "&t=".$_GET["mPERIODE"]."&u=".$_GET["mUNIT"].
                    "&c=".$_GET["mPASIEN"]."'>post?</A>";
    */
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    if(!$GLOBALS['print']){
		$t->RowsPerPage = 20;
	} 
    else{
    	$t->RowsPerPage = 20;
    	$t->DisableNavButton = true;
    	$t->DisableScrollBar = true;
    }
    //$t->RowsPerPage = $ROWS_PER_PAGE;
    //$t->ColFormatMoney[5] = "%!+#2n";
    $t->ColHeader = array("NO.REG","NAMA PASIEN","PANGKAT","NRP/NIP","KESATUAN","LAYANAN/TINDAKAN","TAGIHAN");
    $t->ColFooter[6] =  number_format($d2->jum,2);
    $t->execute();

}

?>
