<? // 30/12/2003
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 29-04-2004
   // sfdn, 30-04-2004
   // sfdn, 11-05-2004
   // sfdn, 01-06-2004

$PID = "450";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if($_GET["tc"] == "view") {

    title("Rincian Pendapatan Jasa Medis");
    echo "<br>";
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
    $smf = getFromTable(
               "select nama from rs00017 ".
               "where nip = '".$_GET["e"]."' ");
    $klp = getFromTable(
               "select description from rs00020 ".
               "where id = '".$_GET["s"]."' ");

    $reg = $_GET["e"];

    $r = pg_query($con,
        "select to_char(to_date('".$_GET["t1"]."','YYYY-MM-DD'),'DD-MON-YYYY') as tgl");
    $d = pg_fetch_object($r);
    pg_free_result($r);
    $bulan = $d->tgl;

    $r1 = pg_query($con,
        "select to_char(to_date('".$_GET["t2"]."','YYYY-MM-DD'),'DD-MON-YYYY') as tgl1");
    $d1 = pg_fetch_object($r1);
    pg_free_result($r1);
    $bulan1 = $d1->tgl1;

    $f = new Form("");
    $f->subtitle("A/N   : $smf");
    $f->subtitle("Kelompok Penerima : $klp");
    $f->subtitle("Tipe Pasien: $tp");
    $f->subtitle("Sumber Jenis Pendapatan: $jm");
    $f->subtitle("Periode : $bulan s/d $bulan1");
    $f->execute();

    echo "<br>";
    $t = new PgTable($con, "100%");
    $r2 = pg_query($con,
        "select sum(b.tagihan) as tagih,sum(.25*b.tagihan) as pemda, ".
        "    sum(.75*b.tagihan) as rs, ".
	    "    sum(((.75*b.tagihan)* ".
		"    (select (x.prosen/sum(x.prosen)) as p1 ".
		"        from rs00020 x, rs00021 y, rs00033 z ".
		"        where   x.pembagian_jasa_medis_id = y.id and ".
		"	    x.pembagian_jasa_medis_id=f.id and ".
		"	    z.pembagian_jasa_medis_id = x.id and ".
		"	    z.trans_group=a.trans_group and x.id=d.id ".
		"	    group by x.id,x.prosen,z.trans_group ".
		"	    ))) as hak ".
        "from rs00033 a, rs00008 b, rs00034 c, rs00020 d, rs00006 e, ".
        "   rs00021 f, rs00017 g, rs00002 h, rs00034 i, rs00001 j ".
        "where a.trans_group = b.trans_group and ".
        "    to_number(b.item_id,'999999999') = c.id and ".
        "    case when length(rtrim(c.hierarchy,'0'))=9 then ".
        "    substr(rtrim(c.hierarchy,'0'),1,6) = substr(rtrim(i.hierarchy,'0'),1,6) ".
        "    when length(rtrim(c.hierarchy,'0'))=8 then ".
        "    substr(rtrim(c.hierarchy,'0'),1,6) = substr(rtrim(i.hierarchy,'0'),1,6) ".
        "    when length(rtrim(c.hierarchy,'0'))=12 then ".
        "    substr(rtrim(c.hierarchy,'0'),1,6) = substr(rtrim(i.hierarchy,'0'),1,6) ".
        "    else substr(rtrim(c.hierarchy,'0'),1,12) = ".
        "    substr(rtrim(i.hierarchy,'0'),1,12) end and ".
        "    i.is_group='Y' and ".
    	"    c.sumber_pendapatan_id='".$_GET["u"]."' and ".
        "    c.sumber_pendapatan_id = j.tc and j.tt='SBP'and ".
        "    b.trans_type='LTM' and b.no_reg = e.id and e.mr_no = h.mr_no and ".
        "    a.pembagian_jasa_medis_id = d.id and ".
		"	 d.pembagian_jasa_medis_id = f.id and ".
        "    (b.tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') and ".
        "    e.tipe ='".$_GET["c"]."' and ".
        "	 a.nip=".$_GET["e"]." and a.nip = g.nip ");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    $t->SQL =
        "select b.no_reg,b.tanggal_trans as tgl_trans_str, h.nama,  ".
	    "    b.tagihan as tagih,.25*b.tagihan as pemda, ".
        "    .75*b.tagihan as rs, ".
	    "    ((.75*b.tagihan)* ".
		"    (select (x.prosen/sum(x.prosen)) as p1 ".
		"        from rs00020 x, rs00021 y, rs00033 z ".
		"        where   x.pembagian_jasa_medis_id = y.id and ".
		"	    x.pembagian_jasa_medis_id=f.id and ".
		"	    z.pembagian_jasa_medis_id = x.id and ".
		"	    z.trans_group=a.trans_group and x.id=d.id ".
		"	    group by x.id,x.prosen,z.trans_group ".
		"	    )) as hak ".
        "from rs00033 a, rs00008 b, rs00034 c, rs00020 d, rs00006 e, rs00021 f, ".
        "    rs00017 g, rs00002 h, rs00034 i, rs00001 j ".
        "where a.trans_group = b.trans_group and ".
        "    to_number(b.item_id,'999999999') = c.id and ".
        "    case when length(rtrim(c.hierarchy,'0'))=9 then ".
        "    substr(rtrim(c.hierarchy,'0'),1,6) = substr(rtrim(i.hierarchy,'0'),1,6) ".
        "    when length(rtrim(c.hierarchy,'0'))=8 then ".
        "    substr(rtrim(c.hierarchy,'0'),1,6) = substr(rtrim(i.hierarchy,'0'),1,6) ".
        "    when length(rtrim(c.hierarchy,'0'))=12 then ".
        "    substr(rtrim(c.hierarchy,'0'),1,6) = substr(rtrim(i.hierarchy,'0'),1,6) ".
        "    else substr(rtrim(c.hierarchy,'0'),1,12) = ".
        "    substr(rtrim(i.hierarchy,'0'),1,12) end and ".
        "    i.is_group='Y' and ".
    	"    c.sumber_pendapatan_id='".$_GET["u"]."' and ".
        "    c.sumber_pendapatan_id = j.tc and j.tt='SBP'and ".
        "    b.trans_type='LTM' and b.no_reg = e.id and e.mr_no = h.mr_no and ".
        "    a.pembagian_jasa_medis_id = d.id and ".
		"	 d.pembagian_jasa_medis_id = f.id and ".
        "    (b.tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') and ".
        "    e.tipe ='".$_GET["c"]."' and ".
        "	 a.nip = '".$_GET["e"]."' and a.nip= g.nip ";

    echo "<br>";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatMoney[3] = "%!+#2n";
    $t->ColFormatMoney[4] = "%!+#2n";
    $t->ColFormatMoney[5] = "%!+#2n";
    $t->ColFormatMoney[6] = "%!+#2n";
    $t->ColHeader = array("NO.REG","TANGGAL TRANSK.","NAMA","PEMBAYARAN","PEMDA","R/S","Rp.");
    $t->ColFooter[3] =  number_format($d2->tagih,2);
    $t->ColFooter[4] =  number_format($d2->pemda,2);
    $t->ColFooter[5] =  number_format($d2->rs,2);
    $t->ColFooter[6] =  number_format($d2->hak,2);
    $t->execute();

} else {
    // search box
    title("DATA PEMBAGIAN J /M");
    $ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

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

    //echo "Xxx: $ts_check_in2"; exit();	
    }



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
    $f->selectSQL("mSMF", "Kelompok Penerima JM",
            "select '' as tc, '' as tdesc union ".
            "select distinct(a.pembagian_jasa_medis_id) as tc,d.description as tdesc ".
            "from rs00033 a, rs00008 b, rs00020 d, rs00021 f, rs00017 g ".
            "where a.trans_group = b.trans_group and ".
                "b.trans_type='LTM' and ".
		        "a.pembagian_jasa_medis_id = d.id and ".
			    "d.pembagian_jasa_medis_id = f.id and ".
			    "a.nip = g.nip "
                ,$_GET["mSMF"],$ext);

    //$f->submit ("OK");
    $f->execute();
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
    echo "<br>";
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
        "    c.sumber_pendapatan_id = j.tc and j.tt='SBP'and j.tc='".$_GET["mUNIT"]."' and ".
        "    b.trans_type='LTM' and b.no_reg = e.id and e.mr_no = h.mr_no and ".
        "    a.pembagian_jasa_medis_id = d.id and ".
	    "    (b.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
        "    a.pembagian_jasa_medis_id = '".$_GET["mSMF"]."' and ".
		"    a.pembagian_jasa_medis_id = d.id and ".
        "    d.pembagian_jasa_medis_id='".$_GET["mSUMBER"]."' and ".
		"	d.pembagian_jasa_medis_id = f.id and ".
		"	a.nip = g.nip and ".
        "   e.tipe = '".$_GET["mPASIEN"]."'");


    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    $t = new PgTable($con, "100%");
    $t->SQL =
        "select a.nip,g.nama, ".
	    "    sum(b.tagihan) as tagih, sum(.25*b.tagihan) as pemda, ".
	    "    sum(.75*b.tagihan) as rs,sum(((.75*b.tagihan)*(d.prosen/100))* ".
		"        (select (x.prosen/sum(x.prosen)) as p1 ".
		"	        from rs00020 x, rs00021 y, rs00033 z ".
		"            where   x.pembagian_jasa_medis_id = y.id and ".
		"	        x.pembagian_jasa_medis_id=f.id and ".
		"	        z.pembagian_jasa_medis_id = x.id and ".
		"	        z.trans_group=a.trans_group and x.id=d.id ".
		"	        group by x.id,x.prosen,z.trans_group ".
		"	        )) as hak ".
        "from rs00033 a, rs00008 b, rs00034 c, rs00020 d, rs00006 e, rs00021 f, rs00017 g, rs00002 h, rs00034 i, rs00001 j ".
        "where a.trans_group = b.trans_group and to_number(b.item_id,'999999999') = c.id and ".
        "    case when length(rtrim(c.hierarchy,'0'))=9 then substr(rtrim(c.hierarchy,'0'),1,6) = substr(rtrim(i.hierarchy,'0'),1,6) ".
        "         when length(rtrim(c.hierarchy,'0'))=8 then substr(rtrim(c.hierarchy,'0'),1,6) = substr(rtrim(i.hierarchy,'0'),1,6) ".
        "         when length(rtrim(c.hierarchy,'0'))=12 then substr(rtrim(c.hierarchy,'0'),1,6) = substr(rtrim(i.hierarchy,'0'),1,6) ".
        "         else substr(rtrim(c.hierarchy,'0'),1,12) = substr(rtrim(i.hierarchy,'0'),1,12) end and ".
        "            i.is_group='Y' and ".
        "    c.sumber_pendapatan_id = j.tc and j.tt='SBP'and j.tc='".$_GET["mUNIT"]."' and ".
        "    b.trans_type='LTM' and b.no_reg = e.id and e.mr_no = h.mr_no and ".
        "    a.pembagian_jasa_medis_id = d.id and ".
	"    (b.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
        "    a.pembagian_jasa_medis_id = '".$_GET["mSMF"]."' and ".
	"    a.pembagian_jasa_medis_id = d.id and ".
        "    d.pembagian_jasa_medis_id='".$_GET["mSUMBER"]."' and ".
	"	 d.pembagian_jasa_medis_id = f.id and ".
	"	 a.nip = g.nip and ".
        "    e.tipe = '".$_GET["mPASIEN"]."' ".
        "group by a.nip, g.nama ";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    /*
    $t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view&e=<#3#>&f=<#0#>&u=".$_GET["mPASIEN"]."'>".
                        icon("view","View")."</A>";
    */

    $t->ColFormatMoney[2] = "%!+#2n";
    $t->ColFormatMoney[3] = "%!+#2n";
    $t->ColFormatMoney[4] = "%!+#2n";
    $t->ColFormatMoney[5] = "%!+#2n";
    $t->ColHeader = array("NIP","NAMA SMF","PEMBAYARAN", $pemda,$rs, "Rp.");
    $t->ColFormatHtml[0] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view&e=<#0#>".
                        "&t1=$ts_check_in1".
                        "&t2=$ts_check_in2".
                        "&u=".$_GET["mUNIT"].
                        "&c=".$_GET["mPASIEN"]."&s=".$_GET["mSMF"].
                        "&v=".$_GET["mSUMBER"]."'><#0#></A>";

    $t->ColFooter[2] =  number_format($d2->tagih,2);
    $t->ColFooter[3] =  number_format($d2->pemda,2);
    $t->ColFooter[4] =  number_format($d2->rs,2);
    $t->ColFooter[5] =  number_format($d2->hak,2);

    $t->execute();

}

?>
