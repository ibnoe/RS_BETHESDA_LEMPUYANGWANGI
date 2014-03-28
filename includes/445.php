<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 09-05-2004
   // sfdn, 11-05-2004


$PID = "445";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if($_GET["tc"] == "view") {
/*
    title("Rincian Pendapatan Jasa Medis");
    $unit = "Rawat Inap";
    if ($_GET["e"] == "N") {
        $unit = "Rawat Jalan";
    } elseif  ($_GET["e"] == "I"){
        $unit = "IGD";
    }
    $tp = getFromTable(
               "select tdesc from rs00001 ".
               "where tc = '".$_GET["c"]."' and tt='JEP'");
    $jm = getFromTable(
               "select tdesc from rs00001 ".
               "where tc = '".$_GET["u"]."' and tt='SBP'");
    $nm = getFromTable(
               "select b.nama from rs00017 a ".
               "where a.id = '".$_GET["s"]."' ");

    $reg = $_GET["e"];

    $r = pg_query($con, "select to_char(to_date(".$_GET["t"].",'YYYYMM'),'MON YYYY') as tgl");
    $d = pg_fetch_object($r);
    pg_free_result($r);
    $bulan = $d->tgl;

    $f = new Form("");
    $f->subtitle("No.Registrasi  : $reg");
    $f->subtitle("Tipe Pasien: $tp");
    $f->subtitle("Sumber Jenis Pendapatan: $jm");
    $f->subtitle("Bulan : $bulan");
    $f->subtitle("S M F: $nm");
    $f->execute();

    echo "<br>";
    $t = new PgTable($con, "100%");
    $r2 = pg_query($con,
            "select sum(tagihan) as jum,sum(rs) as jumrs, sum(pemda) as jumpemda, ".
            "sum(medis) as jum1, sum(direktur) as jum2, sum(paramedis) as jum3, ".
            "sum(farmasi) as jum4, sum(adm) as jum5, sum(bu) as jum6 ".
            "from rs00037 ".
            "where to_char(tanggal_trans,'YYYYMM') = '".$_GET["t"]."' and ".
            "sumber_pendapatan_id= '".$_GET["u"]."' and ".
            "tipe_pasien ='".$_GET["c"]."' and ".
            "no_reg = '".$_GET["e"]."'");


    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    if ($_GET["u"] <> "004" ) {
        $t->SQL =
            "select h.layanan as desc1,tanggal(a.tanggal_trans,3) as tgl_trans_str, ".
            "a.tagihan, a.rs, a.pemda, a.medis, a.direktur, a.paramedis, a.farmasi, a.adm, a.bu ".
            "from rs00037 a,   rs00034 g, rs00034 h ".
            "where to_number(a.item_id,'999999999') = g.id and ".
            "case when length(rtrim(g.hierarchy,'0'))=9 then substr(rtrim(g.hierarchy,'0'),1,6) = substr(rtrim(h.hierarchy,'0'),1,6) ".
            "when length(rtrim(g.hierarchy,'0'))=8 then substr(rtrim(g.hierarchy,'0'),1,6) = substr(rtrim(h.hierarchy,'0'),1,6) ".
            "when length(rtrim(g.hierarchy,'0'))=12 then substr(rtrim(g.hierarchy,'0'),1,6) = substr(rtrim(h.hierarchy,'0'),1,6) ".
            "else substr(rtrim(g.hierarchy,'0'),1,12) = substr(rtrim(h.hierarchy,'0'),1,12) end and ".
            "h.is_group='Y'and ".
            "to_char(a.tanggal_trans,'YYYYMM') = '".$_GET["t"]."' and ".
            "a.sumber_pendapatan_id= '".$_GET["u"]."' and ".
            "a.tipe_pasien ='".$_GET["c"]."' and ".
            "a.no_reg='".$_GET["e"]."'";
       $t->ColHeader = array("UNIT TINDAKAN / LAYANAN", "TGL TRANSK.","TAGIHAN","R/S","PEMDA",
                       "MEDIS","DIREKTUR","PARA MEDIS","FARMASI","ADM.","B/U");


    } else {
        $t->SQL =
            "select b.obat,tanggal(a.tanggal_trans,3) as tgl_trans_str, ".
            "a.tagihan, a.rs, a.pemda, a.medis, a.direktur, a.paramedis, a.farmasi, a.adm, a.bu ".
            "from rs00037 a, rs00015 b ".
            "where to_number(a.item_id,'999999999') = b.id and ".
            "to_char(a.tanggal_trans,'YYYYMM') = '".$_GET["t"]."' and ".
            "a.sumber_pendapatan_id= '".$_GET["u"]."' and ".
            "a.tipe_pasien ='".$_GET["c"]."' and ".
            "a.no_reg='".$_GET["e"]."'";

       $t->ColHeader = array("NAMA OBAT", "TGL TRANSK.","TAGIHAN","R/S","PEMDA",
                       "MEDIS","DIREKTUR","PARA MEDIS","FARMASI","ADM.","B/U");
    }
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatMoney[2] = "%!+#2n";
    $t->ColFormatMoney[3] = "%!+#2n";
    $t->ColFormatMoney[4] = "%!+#2n";
    $t->ColFormatMoney[5] = "%!+#2n";
    $t->ColFormatMoney[6] = "%!+#2n";
    $t->ColFormatMoney[7] = "%!+#2n";
    $t->ColFormatMoney[8] = "%!+#2n";
    $t->ColFormatMoney[9] = "%!+#2n";
    $t->ColFormatMoney[10] = "%!+#2n";
    $t->ColFooter[2] =  number_format($d2->jum,2);
    $t->ColFooter[3] =  number_format($d2->jumrs,2);
    $t->ColFooter[4] =  number_format($d2->jumpemda,2);
    $t->ColFooter[5] =  number_format($d2->jum1,2);
    $t->ColFooter[6] =  number_format($d2->jum2,2);
    $t->ColFooter[7] =  number_format($d2->jum3,2);
    $t->ColFooter[8] =  number_format($d2->jum4,2);
    $t->ColFooter[9] =  number_format($d2->jum5,2);
    $t->ColFooter[10] =  number_format($d2->jum6,2);
    $t->execute();
*/
} else {
    // search box
    title("TRACING DATA PENDAPATAN JASA MEDIS ");
    echo "<br>";
    $ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);


    include("xxx");

    $f->selectSQL("mUNIT", "Aktivitas Sumber Pendapatan",
        "select '' as tc, '' as tdesc union ".
        "select tc, tdesc ".
        "from rs00001 ".
        "where tc!='000' and tt = 'SBP' ", $_GET["mUNIT"],
        $ext);
    $f->selectSQL("mPASIEN", "Tipe Pasien",
        "select '' as tc, '' as tdesc union ".
        "select distinct(b.tipe) as tc, c.tdesc as tdesc ".
        "from rs00008 a, rs00006 b, rs00001 c ".
        "where a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP'", $_GET["mPASIEN"],
        $ext);
    $f->selectSQL("mSUMBER", "Kelompok Sumber Pendapatan",
        "select '' as tc, '' as tdesc union ".
        "select id as tc, jasa_medis as tdesc ".
        "from rs00021 ".
        "where tipe_pasien_id='".$_GET["mPASIEN"]."' "
        ,$_GET["mSUMBER"],
        $ext);
    $f->selectSQL("mSMF", "S M F",
        "select '' as tc, '' as tdesc union ".
        "select distinct(a.nip) as tc, g.nama as tdesc ".
        "from rs00033 a, rs00008 b, rs00020 d, rs00021 f, rs00017 g ".
        "where a.trans_group = b.trans_group and ".
        "b.trans_type='LTM' and ".
		"a.pembagian_jasa_medis_id = d.id and ".
		"	d.pembagian_jasa_medis_id = f.id and ".
		"	   a.nip = g.nip",$_GET["mSMF"],$ext);

    //$f->submit ("OK");
    $f->execute();
    echo "<br>";
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

    $r2 = pg_query($con,
        "select sum(b.tagihan) as tagih,sum(.25*b.tagihan) as pemda,sum(.75*b.tagihan) as rs, ".
	    "    sum(((.75*b.tagihan)* ".
		"    (select (x.prosen/sum(x.prosen)) as p1 ".
		"        from rs00020 x, rs00021 y, rs00033 z ".
		"        where   x.pembagian_jasa_medis_id = y.id and ".
		"	    x.pembagian_jasa_medis_id=f.id and ".
		"	    z.pembagian_jasa_medis_id = x.id and ".
		"	    z.trans_group=a.trans_group and x.id=d.id ".
		"	    group by x.id,x.prosen,z.trans_group ".
		"	    ))) as hak ".
        "from rs00033 a, rs00008 b, rs00034 c, rs00020 d, rs00006 e, rs00021 f, rs00017 g, rs00002 h, rs00034 i, rs00001 j ".
        "where a.trans_group = b.trans_group and to_number(b.item_id,'999999999') = c.id and ".
        "    case when length(rtrim(c.hierarchy,'0'))=9 then substr(rtrim(c.hierarchy,'0'),1,6) = substr(rtrim(i.hierarchy,'0'),1,6) ".
        "    when length(rtrim(c.hierarchy,'0'))=8 then substr(rtrim(c.hierarchy,'0'),1,6) = substr(rtrim(i.hierarchy,'0'),1,6) ".
        "    when length(rtrim(c.hierarchy,'0'))=12 then substr(rtrim(c.hierarchy,'0'),1,6) = substr(rtrim(i.hierarchy,'0'),1,6) ".
        "    else substr(rtrim(c.hierarchy,'0'),1,12) = substr(rtrim(i.hierarchy,'0'),1,12) end and ".
        "    i.is_group='Y' and ".
    	"    c.sumber_pendapatan_id='".$_GET["mUNIT"]."' and ".
        "    c.sumber_pendapatan_id = j.tc and j.tt='SBP'and ".
        "    b.trans_type='LTM' and b.no_reg = e.id and e.mr_no = h.mr_no and ".
        "    a.pembagian_jasa_medis_id = d.id and ".
        "    d.pembagian_jasa_medis_id='".$_GET["mSUMBER"]."' and ".
		"	 d.pembagian_jasa_medis_id = f.id and ".
        "    (b.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
        "    e.tipe ='".$_GET["mPASIEN"]."' and ".
		"    a.nip = '".$_GET["mSMF"]."' and ".
        "	 a.nip = g.nip ");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    $t = new PgTable($con, "100%");
    $t->SQL =
        "select b.no_reg,b.tanggal_trans as tgl_trans_str, h.nama as nm_pasien, ".
	    "    i.layanan as unit_layanan,c.layanan,b.tagihan,(.25*b.tagihan) as pemda, ".
        "    (.75*b.tagihan) as rs, ".
	    "    ((.75*b.tagihan)* ".
		"        (select (x.prosen/sum(x.prosen)) as p1 ".
		"	        from rs00020 x, rs00021 y, rs00033 z ".
		"        where   x.pembagian_jasa_medis_id = y.id and ".
		"	        x.pembagian_jasa_medis_id=f.id and ".
		"	        z.pembagian_jasa_medis_id = x.id and ".
		"	        z.trans_group=a.trans_group and x.id=d.id ".
		"	        group by x.id,x.prosen,z.trans_group ".
		"	        )) as hak ".
        "from rs00033 a, rs00008 b, rs00034 c, rs00020 d, rs00006 e, rs00021 f, rs00017 g, rs00002 h, rs00034 i, rs00001 j ".
        "where a.trans_group = b.trans_group and (b.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
        "   to_number(b.item_id,'999999999') = c.id and ".
        "    case when length(rtrim(c.hierarchy,'0'))=9 then substr(rtrim(c.hierarchy,'0'),1,6) = substr(rtrim(i.hierarchy,'0'),1,6) ".
        "        when length(rtrim(c.hierarchy,'0'))=8 then substr(rtrim(c.hierarchy,'0'),1,6) = substr(rtrim(i.hierarchy,'0'),1,6) ".
        "        when length(rtrim(c.hierarchy,'0'))=12 then substr(rtrim(c.hierarchy,'0'),1,6) = substr(rtrim(i.hierarchy,'0'),1,6) ".
        "        else substr(rtrim(c.hierarchy,'0'),1,12) = substr(rtrim(i.hierarchy,'0'),1,12) end and ".
        "            i.is_group='Y' and ".
        "    c.sumber_pendapatan_id = j.tc and j.tt='SBP'and ".
        "    b.trans_type='LTM' and b.no_reg = e.id and e.mr_no = h.mr_no and ".
    	"    c.sumber_pendapatan_id='".$_GET["mUNIT"]."' and ".
        "    e.tipe ='".$_GET["mPASIEN"]."' and ".
        "    a.nip = g.nip and a.nip='".$_GET["mSMF"]."' and ".
		"    a.pembagian_jasa_medis_id = d.id and ".
        "    d.pembagian_jasa_medis_id='".$_GET["mSUMBER"]."' and ".
        "	 d.pembagian_jasa_medis_id = f.id ".
        "order by b.trans_group";
/*
        $t->ColFormatHtml[0] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view&e=<#0#>".
                        "&t=".$_GET["mPERIODE"]."&u=".$_GET["mUNIT"].
                        "&c=".$_GET["mPASIEN"]."&s=".$_GET["mSMF"]."'><#0#></A>";
*/
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatMoney[5] = "%!+#2n";
    $t->ColFormatMoney[6] = "%!+#2n";
    $t->ColFormatMoney[7] = "%!+#2n";
    $t->ColFormatMoney[8] = "%!+#2n";
    $t->ColHeader = array("NO.REG","TGL.TRANSK.",
                          "NAMA PASIEN", "U N I T","LAYANAN/TINDAKAN","PEMBAYARAN",
                          $pemda,$rs,"RP.");
    $t->ColFooter[5] =  number_format($d2->tagih,2);
    $t->ColFooter[6] =  number_format($d2->pemda,2);
    $t->ColFooter[7] =  number_format($d2->rs,2);
    $t->ColFooter[8] =  number_format($d2->hak,2);

    $t->execute();

}

?>
