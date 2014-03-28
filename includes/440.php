<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 09-05-2004
   // sfdn, 11-05-2004
   // sfdn, 14-05-2004


$PID = "440";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if($_GET["tc"] == "view") {

    title("Rincian Pendapatan Jasa Medis");
    $tp = getFromTable(
               "select jasa_medis from rs00021 ".
               "where id = '".$_GET["f"]."'");
    $pasien = getFromTable(
               "select tdesc from rs00001 ".
               "where tc = '".$_GET["c"]."' and tt='JEP'");

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
    $f->subtitle("Sumber Pendapatan  : $tp");
    $f->subtitle("Periode : $bulan s/d $bulan1");
    $f->subtitle("Tipe Pasien : $pasien");
    $f->execute();

    echo "<br>";
    $t = new PgTable($con, "100%");
    //if ($_GET["c"] == '001') {
    $r2 = pg_query($con,
        "select sum(a.tagihan*.75) as tagih ".
        "from rs00008 a, rs00006 b, rs00002 c, rs00001 d, rs00034 g, rs00034 h ".
        "where a.trans_type='LTM' and to_number(a.item_id,'999999999') = g.id and ".
            "case when length(rtrim(g.hierarchy,'0'))=9 then ".
                       "substr(rtrim(g.hierarchy,'0'),1,6) = ".
                       "substr(rtrim(h.hierarchy,'0'),1,6) ".
                  "when length(rtrim(g.hierarchy,'0'))=8 then ".
                       "substr(rtrim(g.hierarchy,'0'),1,6) = ".
                       "substr(rtrim(h.hierarchy,'0'),1,6) ".
                  "when length(rtrim(g.hierarchy,'0'))=12 then ".
                       "substr(rtrim(g.hierarchy,'0'),1,6) = ".
                       "substr(rtrim(h.hierarchy,'0'),1,6) ".
                  "else substr(rtrim(g.hierarchy,'0'),1,12) = ".
                       "substr(rtrim(h.hierarchy,'0'),1,12) end and ".
                  "h.is_group='Y'and ".
            "a.no_reg = b.id and b.tipe = d.tc and d.tt='JEP' and ".
            "b.mr_no = c.mr_no  and ".
            "(a.tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') and ".
            "g.rs00021_id= '".$_GET["f"]."' and ".
            "b.tipe ='".$_GET["c"]."'");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    $t->SQL =
        "select a.no_reg, TO_CHAR(a.tanggal_trans,'dd-mm-yyyy')  as tgl_trans_str, ".
            "c.nama, h.layanan as desc1, ".
            "g.layanan as desc2, (.75*a.tagihan) as tagih ".
        "from rs00008 a, rs00006 b, rs00002 c, rs00001 d, rs00034 g, rs00034 h ".
        "where a.trans_type='LTM' and to_number(a.item_id,'999999999') = g.id and ".
            "case when length(rtrim(g.hierarchy,'0'))=9 then ".
                     "substr(rtrim(g.hierarchy,'0'),1,6) = ".
                     "substr(rtrim(h.hierarchy,'0'),1,6) ".
                  "when length(rtrim(g.hierarchy,'0'))=8 then ".
                     "substr(rtrim(g.hierarchy,'0'),1,6) = ".
                     "substr(rtrim(h.hierarchy,'0'),1,6) ".
                  "when length(rtrim(g.hierarchy,'0'))=12 then ".
                     "substr(rtrim(g.hierarchy,'0'),1,6) = ".
                     "substr(rtrim(h.hierarchy,'0'),1,6) ".
                 "else substr(rtrim(g.hierarchy,'0'),1,12) = ".
                     "substr(rtrim(h.hierarchy,'0'),1,12) end and ".
                 "h.is_group='Y'and ".
            "a.no_reg = b.id and b.tipe = d.tc and d.tt='JEP' and ".
            "b.mr_no = c.mr_no  and ".
            "(a.tanggal_trans between '".$_GET["t1"]."' and '".$_GET["t2"]."') and ".
            "g.rs00021_id= '".$_GET["f"]."' and ".
            "b.tipe = '".$_GET["c"]."'";

       $t->ColHeader = array("NO.REG.", "TGL TRANSK.","NAMA PASIEN","UNIT",
                       "LAYANAN/TINDAKAN","PEMBAYARAN");

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatMoney[5] = "%!+#2n";
    $t->ColFooter[5] =  number_format($d2->tagih,2);
    $t->execute();
    //} else {
    // untuk jenis pasien ASKES



    //}
} else {
    // search box
    title("LAPORAN PENDAPATAN JASA MEDIS");
    $ext = "OnChange = 'Form1.submit();'";
    echo "<br>";
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

    }





    $f->selectSQL("mPASIEN", "Tipe Pasien",
        "select '' as tc, '' as tdesc union ".
        "select distinct(b.tipe) as tc, c.tdesc as tdesc ".
        "from rs00008 a, rs00006 b, rs00001 c ".
        "where a.trans_type = 'LTM' and a.no_reg = b.id and b.tipe = c.tc and c.tt='JEP'", $_GET["mPASIEN"],
        $ext);
    $f->execute();
    echo "<br>";
    if ($_GET["mPASIEN"] == '001') {
    $r2 = pg_query($con,
    "select ".
        "sum(case when ((d.rs00021_id ='011' OR d.rs00021_id ='012' OR d.rs00021_id ='013' OR ".
        "               d.rs00021_id ='014' OR d.rs00021_id ='015' OR d.rs00021_id ='016' OR ".
        "               d.rs00021_id ='017' OR d.rs00021_id ='018' OR d.rs00021_id ='019' OR ".
        "               d.rs00021_id ='020' OR d.rs00021_id ='021' OR d.rs00021_id ='022') ".
        "               and e.tipe= '001') then (.75*a.tagihan) ".
        "end) as tagih, ".
        "sum(case when (d.rs00021_id ='011' and e.tipe= '001') then ((a.tagihan*.75)*.55) ".
        "     when (d.rs00021_id ='012' and e.tipe= '001') then ((a.tagihan*.75)*.375) ".
        "     when (d.rs00021_id ='013' and e.tipe= '001') then ((a.tagihan*.75)*.375) ".
        "     when (d.rs00021_id ='014' and e.tipe= '001') then ((a.tagihan*.75)*.425) ".
        "     when (d.rs00021_id ='015' and e.tipe= '001') then ((a.tagihan*.75)*.425) ".
        "     when (d.rs00021_id ='016' and e.tipe= '001') then 0 ".
        "     when (d.rs00021_id ='017' and e.tipe= '001') then 0 ".
        "     when (d.rs00021_id ='018' and e.tipe= '001') then 0 ".
        "     when (d.rs00021_id ='019' and e.tipe= '001') then 0 ".
        "     when (d.rs00021_id ='020' and e.tipe= '001') then 0 ".
        "     when (d.rs00021_id ='021' and e.tipe= '001') then ((a.tagihan*.75)*.20) ".
        "     when (d.rs00021_id ='022' and e.tipe= '001') then ((a.tagihan*.75)*.2) ".
        "end) as medis, ".
        "sum(case when (d.rs00021_id ='011' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='012' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='013' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='014' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='015' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='016' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='017' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='018' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='019' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='020' and e.tipe= '001') then 0 ".
        "     when (d.rs00021_id ='021' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='022' and e.tipe= '001') then ((a.tagihan*.75)*.05)  ".
        "end) as direktur, ".
        "sum(case when (d.rs00021_id ='011' and e.tipe= '001') then ((a.tagihan*.75)*.225) ".
        "     when (d.rs00021_id ='012' and e.tipe= '001') then ((a.tagihan*.75)*.4) ".
        "     when (d.rs00021_id ='013' and e.tipe= '001') then ((a.tagihan*.75)*.4) ".
        "     when (d.rs00021_id ='014' and e.tipe= '001') then ((a.tagihan*.75)*.35) ".
        "     when (d.rs00021_id ='015' and e.tipe= '001') then ((a.tagihan*.75)*.35) ".
        "     when (d.rs00021_id ='016' and e.tipe= '001') then ((a.tagihan*.75)*.775) ".
        "     when (d.rs00021_id ='017' and e.tipe= '001') then ((a.tagihan*.75)*.775) ".
        "     when (d.rs00021_id ='018' and e.tipe= '001') then ((a.tagihan*.75)*.775) ".
        "     when (d.rs00021_id ='019' and e.tipe= '001') then ((a.tagihan*.75)*.775) ".
        "     when (d.rs00021_id ='020' and e.tipe= '001') then ((a.tagihan*.75)*.8) ".
        "     when (d.rs00021_id ='021' and e.tipe= '001') then ((a.tagihan*.75)*.075) ".
        "     when (d.rs00021_id ='022' and e.tipe= '001') then ((a.tagihan*.75)*.15)  ".
        "end) as paramedis, ".
        "sum(case when (d.rs00021_id ='011' and e.tipe= '001') then ((a.tagihan*.75)*.025)  ".
        "     when (d.rs00021_id ='012' and e.tipe= '001') then ((a.tagihan*.75)*.025) ".
        "     when (d.rs00021_id ='013' and e.tipe= '001') then ((a.tagihan*.75)*.025) ".
        "     when (d.rs00021_id ='014' and e.tipe= '001') then ((a.tagihan*.75)*.025) ".
        "     when (d.rs00021_id ='015' and e.tipe= '001') then ((a.tagihan*.75)*.025) ".
        "     when (d.rs00021_id ='016' and e.tipe= '001') then ((a.tagihan*.75)*.025) ".
        "     when (d.rs00021_id ='017' and e.tipe= '001') then ((a.tagihan*.75)*.025) ".
        "     when (d.rs00021_id ='018' and e.tipe= '001') then ((a.tagihan*.75)*.025) ".
        "     when (d.rs00021_id ='019' and e.tipe= '001') then ((a.tagihan*.75)*.025) ".
        "     when (d.rs00021_id ='020' and e.tipe= '001') then 0 ".
        "     when (d.rs00021_id ='021' and e.tipe= '001') then ((a.tagihan*.75)*.6) ".
        "     when (d.rs00021_id ='022' and e.tipe= '001') then ((a.tagihan*.75)*.025)  ".
        "end) as farmasi, ".
        "sum(case when (d.rs00021_id ='011' and e.tipe= '001') then ((a.tagihan*.75)*.1) ".
        "     when (d.rs00021_id ='012' and e.tipe= '001') then ((a.tagihan*.75)*.1) ".
        "     when (d.rs00021_id ='013' and e.tipe= '001') then ((a.tagihan*.75)*.1) ".
        "     when (d.rs00021_id ='014' and e.tipe= '001') then ((a.tagihan*.75)*.1) ".
        "     when (d.rs00021_id ='015' and e.tipe= '001') then ((a.tagihan*.75)*.1) ".
        "     when (d.rs00021_id ='016' and e.tipe= '001') then ((a.tagihan*.75)*.1) ".
        "     when (d.rs00021_id ='017' and e.tipe= '001') then ((a.tagihan*.75)*.1) ".
        "     when (d.rs00021_id ='018' and e.tipe= '001') then ((a.tagihan*.75)*.1) ".
        "     when (d.rs00021_id ='019' and e.tipe= '001') then ((a.tagihan*.75)*.1) ".
        "     when (d.rs00021_id ='020' and e.tipe= '001') then ((a.tagihan*.75)*.15) ".
        "     when (d.rs00021_id ='021' and e.tipe= '001') then ((a.tagihan*.75)*.075) ".
        "     when (d.rs00021_id ='022' and e.tipe= '001') then ((a.tagihan*.75)*.6)  ".
        "end) as adm, ".
        "sum(case when (d.rs00021_id ='011' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='012' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='013' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='014' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='015' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='016' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='017' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='018' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='019' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='020' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='021' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
        "     when (d.rs00021_id ='022' and e.tipe= '001') then ((a.tagihan*.75)*.075)  ".
        "end) as bu ".
    "from rs00008 a, rs00034 b, rs00021 c, rs00034 d, rs00006 e, rs00002 f ".
    "where a.trans_type='LTM' and a.no_reg = e.id and e.mr_no = f.mr_no and ".
        "	to_number(a.item_id,'999999999')= b.id and ".
        "	b.rs00021_id = c.id and  ".
        "case when length(rtrim(b.hierarchy,'0'))=9 ".
        "	    then substr(rtrim(b.hierarchy,'0'),1,6) = substr(rtrim(d.hierarchy,'0'),1,6) ".
        "   when length(rtrim(b.hierarchy,'0'))=8 ".
        "	    then substr(rtrim(b.hierarchy,'0'),1,6) = substr(rtrim(d.hierarchy,'0'),1,6) ".
        "   when length(rtrim(b.hierarchy,'0'))=12 ".
        "	    then substr(rtrim(b.hierarchy,'0'),1,6) = substr(rtrim(d.hierarchy,'0'),1,6) ".
        "   else substr(rtrim(b.hierarchy,'0'),1,12) = substr(rtrim(d.hierarchy,'0'),1,12) end ".
        "       and d.is_group='Y' and ".
        "	(a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
        "   e.tipe ='".$_GET["mPASIEN"]."'");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    $t = new PgTable($con, "100%");
    $t->SQL =
        "select c.jasa_medis, ".
            "sum(case when ((d.rs00021_id ='011' OR d.rs00021_id ='012' OR d.rs00021_id ='013' OR ".
            "               d.rs00021_id ='014' OR d.rs00021_id ='015' OR d.rs00021_id ='016' OR ".
            "               d.rs00021_id ='017' OR d.rs00021_id ='018' OR d.rs00021_id ='019' OR ".
            "               d.rs00021_id ='020' OR d.rs00021_id ='021' OR d.rs00021_id ='022') ".
            "               and e.tipe= '001') then (.75*a.tagihan) ".
            "end) as tagih, ".
            "sum(case when (d.rs00021_id ='011' and e.tipe= '001') then ((a.tagihan*.75)*.55) ".
            "     when (d.rs00021_id ='012' and e.tipe= '001') then ((a.tagihan*.75)*.375) ".
            "     when (d.rs00021_id ='013' and e.tipe= '001') then ((a.tagihan*.75)*.375) ".
            "     when (d.rs00021_id ='014' and e.tipe= '001') then ((a.tagihan*.75)*.425) ".
            "     when (d.rs00021_id ='015' and e.tipe= '001') then ((a.tagihan*.75)*.425) ".
            "     when (d.rs00021_id ='016' and e.tipe= '001') then 0 ".
            "     when (d.rs00021_id ='017' and e.tipe= '001') then 0 ".
            "     when (d.rs00021_id ='018' and e.tipe= '001') then 0 ".
            "     when (d.rs00021_id ='019' and e.tipe= '001') then 0 ".
            "     when (d.rs00021_id ='020' and e.tipe= '001') then 0 ".
            "     when (d.rs00021_id ='021' and e.tipe= '001') then ((a.tagihan*.75)*.20) ".
            "     when (d.rs00021_id ='022' and e.tipe= '001') then ((a.tagihan*.75)*.2) ".
            "end) as medis, ".
            "sum(case when (d.rs00021_id ='011' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='012' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='013' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='014' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='015' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='016' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='017' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='018' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='019' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='020' and e.tipe= '001') then 0 ".
            "     when (d.rs00021_id ='021' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='022' and e.tipe= '001') then ((a.tagihan*.75)*.05)  ".
            "end) as direktur, ".
            "sum(case when (d.rs00021_id ='011' and e.tipe= '001') then ((a.tagihan*.75)*.225) ".
            "     when (d.rs00021_id ='012' and e.tipe= '001') then ((a.tagihan*.75)*.4) ".
            "     when (d.rs00021_id ='013' and e.tipe= '001') then ((a.tagihan*.75)*.4) ".
            "     when (d.rs00021_id ='014' and e.tipe= '001') then ((a.tagihan*.75)*.35) ".
            "     when (d.rs00021_id ='015' and e.tipe= '001') then ((a.tagihan*.75)*.35) ".
            "     when (d.rs00021_id ='016' and e.tipe= '001') then ((a.tagihan*.75)*.775) ".
            "     when (d.rs00021_id ='017' and e.tipe= '001') then ((a.tagihan*.75)*.775) ".
            "     when (d.rs00021_id ='018' and e.tipe= '001') then ((a.tagihan*.75)*.775) ".
            "     when (d.rs00021_id ='019' and e.tipe= '001') then ((a.tagihan*.75)*.775) ".
            "     when (d.rs00021_id ='020' and e.tipe= '001') then ((a.tagihan*.75)*.8) ".
            "     when (d.rs00021_id ='021' and e.tipe= '001') then ((a.tagihan*.75)*.075) ".
            "     when (d.rs00021_id ='022' and e.tipe= '001') then ((a.tagihan*.75)*.15)  ".
            "end) as paramedis, ".
            "sum(case when (d.rs00021_id ='011' and e.tipe= '001') then ((a.tagihan*.75)*.025)  ".
            "     when (d.rs00021_id ='012' and e.tipe= '001') then ((a.tagihan*.75)*.025) ".
            "     when (d.rs00021_id ='013' and e.tipe= '001') then ((a.tagihan*.75)*.025) ".
            "     when (d.rs00021_id ='014' and e.tipe= '001') then ((a.tagihan*.75)*.025) ".
            "     when (d.rs00021_id ='015' and e.tipe= '001') then ((a.tagihan*.75)*.025) ".
            "     when (d.rs00021_id ='016' and e.tipe= '001') then ((a.tagihan*.75)*.025) ".
            "     when (d.rs00021_id ='017' and e.tipe= '001') then ((a.tagihan*.75)*.025) ".
            "     when (d.rs00021_id ='018' and e.tipe= '001') then ((a.tagihan*.75)*.025) ".
            "     when (d.rs00021_id ='019' and e.tipe= '001') then ((a.tagihan*.75)*.025) ".
            "     when (d.rs00021_id ='020' and e.tipe= '001') then 0 ".
            "     when (d.rs00021_id ='021' and e.tipe= '001') then ((a.tagihan*.75)*.6) ".
            "     when (d.rs00021_id ='022' and e.tipe= '001') then ((a.tagihan*.75)*.025)  ".
            "end) as farmasi, ".
            "sum(case when (d.rs00021_id ='011' and e.tipe= '001') then ((a.tagihan*.75)*.1) ".
            "     when (d.rs00021_id ='012' and e.tipe= '001') then ((a.tagihan*.75)*.1) ".
            "     when (d.rs00021_id ='013' and e.tipe= '001') then ((a.tagihan*.75)*.1) ".
            "     when (d.rs00021_id ='014' and e.tipe= '001') then ((a.tagihan*.75)*.1) ".
            "     when (d.rs00021_id ='015' and e.tipe= '001') then ((a.tagihan*.75)*.1) ".
            "     when (d.rs00021_id ='016' and e.tipe= '001') then ((a.tagihan*.75)*.1) ".
            "     when (d.rs00021_id ='017' and e.tipe= '001') then ((a.tagihan*.75)*.1) ".
            "     when (d.rs00021_id ='018' and e.tipe= '001') then ((a.tagihan*.75)*.1) ".
            "     when (d.rs00021_id ='019' and e.tipe= '001') then ((a.tagihan*.75)*.1) ".
            "     when (d.rs00021_id ='020' and e.tipe= '001') then ((a.tagihan*.75)*.15) ".
            "     when (d.rs00021_id ='021' and e.tipe= '001') then ((a.tagihan*.75)*.075) ".
            "     when (d.rs00021_id ='022' and e.tipe= '001') then ((a.tagihan*.75)*.6)  ".
            "end) as adm, ".
            "sum(case when (d.rs00021_id ='011' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='012' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='013' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='014' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='015' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='016' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='017' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='018' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='019' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='020' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='021' and e.tipe= '001') then ((a.tagihan*.75)*.05) ".
            "     when (d.rs00021_id ='022' and e.tipe= '001') then ((a.tagihan*.75)*.075)  ".
            "end) as bu, ".
            "b.rs00021_id as dummy ".
        "from rs00008 a, rs00034 b, rs00021 c, rs00034 d, rs00006 e, rs00002 f ".
        "where a.trans_type = 'LTM' and a.no_reg = e.id and e.mr_no = f.mr_no and ".
            "	to_number(a.item_id,'999999999')= b.id and ".
            "	b.rs00021_id = c.id and c.tipe_pasien_id = '001' and  ".
            "case when length(rtrim(b.hierarchy,'0'))=9 ".
            "	    then substr(rtrim(b.hierarchy,'0'),1,6) = substr(rtrim(d.hierarchy,'0'),1,6) ".
            "   when length(rtrim(b.hierarchy,'0'))=8 ".
            "	    then substr(rtrim(b.hierarchy,'0'),1,6) = substr(rtrim(d.hierarchy,'0'),1,6) ".
            "   when length(rtrim(b.hierarchy,'0'))=12 ".
            "	    then substr(rtrim(b.hierarchy,'0'),1,6) = substr(rtrim(d.hierarchy,'0'),1,6) ".
            "   else substr(rtrim(b.hierarchy,'0'),1,12) = substr(rtrim(d.hierarchy,'0'),1,12) end ".
            "       and d.is_group='Y' and ".
            "  (a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
            "e.tipe ='".$_GET["mPASIEN"]."' ".
        "group by c.jasa_medis, b.rs00021_id ".
        "order by b.rs00021_id ";
	
        $t->setlocale("id_ID");
        $t->ShowRowNumber = true;
        $t->RowsPerPage = $ROWS_PER_PAGE;
        $t->ColAlign[8] = "CENTER";
        $t->ColFormatMoney[1] = "%!+#2n";
        $t->ColFormatMoney[2] = "%!+#2n";
        $t->ColFormatMoney[3] = "%!+#2n";
        $t->ColFormatMoney[4] = "%!+#2n";
        $t->ColFormatMoney[5] = "%!+#2n";
        $t->ColFormatMoney[6] = "%!+#2n";
        $t->ColFormatMoney[7] = "%!+#2n";
        $t->ColHeader = array("SUMBER DANA","PENDAPTAN (75%)","MEDIS","DIREKTUR","PARA MEDIS","FARMASI","ADM.","B/U","View");
        $t->ColFormatHtml[8] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view&f=<#8#>".
                            "&t1=$ts_check_in1".
                            "&t2=$ts_check_in2".
                            "&c=".$_GET["mPASIEN"]."'>".
                            icon("view","View")."</A>";
        $t->ColFooter[1] =  number_format($d2->tagih,2);
        $t->ColFooter[2] =  number_format($d2->medis,2);
        $t->ColFooter[3] =  number_format($d2->direktur,2);
        $t->ColFooter[4] =  number_format($d2->paramedis,2);
        $t->ColFooter[5] =  number_format($d2->farmasi,2);
        $t->ColFooter[6] =  number_format($d2->adm,2);
        $t->ColFooter[7] =  number_format($d2->bu,2);
        $t->execute();

    } elseif ($_GET["mPASIEN"] == '004') {
    // untuk laporan pasien jenis ASKES
    $r2 = pg_query($con,
    "select ".
            "sum(case when ((d.rs00021_id ='001' OR d.rs00021_id ='003' OR d.rs00021_id ='004' OR ".
            "               d.rs00021_id ='005' OR d.rs00021_id ='006' OR d.rs00021_id ='007' OR ".
            "               d.rs00021_id ='008' OR d.rs00021_id ='009' OR d.rs00021_id ='010') ".
            "               and (e.tipe= '004' OR e.tipe='002')) then (.85*a.tagihan) ".
            "end) as tagih, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then ((a.tagihan*.85)*.45) ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.4125) ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.36) ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.25) ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "end) as spesialis, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.04) ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.5) ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.4) ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.25) ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "end) as umum, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then 0 ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.5) ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "end) as gigi, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then 0 ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.5) ".
            "end) as usg, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then 0 ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.45) ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "end) as rontgen, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then 0 ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.1375) ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "end) as anestesi, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then 0 ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.3) ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "end) as paragigi, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then 0 ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.3) ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.3) ".
            "end) as paramedisro, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then 0 ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.30) ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "end) as paraok, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then ((a.tagihan*.85)*.30) ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.025) ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.40) ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.30) ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.40) ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.30) ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "end) as paralain, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then ((a.tagihan*.85)*.15) ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.15) ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.15) ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.15) ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.15) ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.15) ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.15) ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.15) ".
            "end) as tpdanadm, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.075) ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.05) ".
            "end) as kebersamaan, ".
            "b.rs00021_id as dummy ".
        "from rs00008 a, rs00034 b, rs00021 c, rs00034 d, rs00006 e, rs00002 f ".
        "where a.trans_type='LTM' and a.no_reg = e.id and e.mr_no = f.mr_no and ".
            "	to_number(a.item_id,'999999999')= b.id and ".
            "	b.rs00021_id = c.id and  ".
            "case when length(rtrim(b.hierarchy,'0'))=9 ".
            "	    then substr(rtrim(b.hierarchy,'0'),1,6) = substr(rtrim(d.hierarchy,'0'),1,6) ".
            "   when length(rtrim(b.hierarchy,'0'))=8 ".
            "	    then substr(rtrim(b.hierarchy,'0'),1,6) = substr(rtrim(d.hierarchy,'0'),1,6) ".
            "   when length(rtrim(b.hierarchy,'0'))=12 ".
            "	    then substr(rtrim(b.hierarchy,'0'),1,6) = substr(rtrim(d.hierarchy,'0'),1,6) ".
            "   else substr(rtrim(b.hierarchy,'0'),1,12) = substr(rtrim(d.hierarchy,'0'),1,12) end ".
            "       and d.is_group='Y' and ".
            "(a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
            "e.tipe ='".$_GET["mPASIEN"]."' ".
            "group by b.rs00021_id");

    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    $t = new PgTable($con, "100%");
    $t->SQL =
        "select c.jasa_medis, ".
            "sum(case when ((d.rs00021_id ='001' OR d.rs00021_id ='003' OR d.rs00021_id ='004' OR ".
            "               d.rs00021_id ='005' OR d.rs00021_id ='006' OR d.rs00021_id ='007' OR ".
            "               d.rs00021_id ='008' OR d.rs00021_id ='009' OR d.rs00021_id ='010') ".
            "               and (e.tipe= '004' OR e.tipe='002')) then (.85*a.tagihan) ".
            "end) as tagih, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then ((a.tagihan*.85)*.45) ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.4125) ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.36) ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.25) ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "end) as spesialis, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.04) ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.5) ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.4) ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.25) ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "end) as umum, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then 0 ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.5) ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "end) as gigi, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then 0 ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.5) ".
            "end) as usg, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then 0 ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.45) ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "end) as rontgen, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then 0 ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.1375) ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "end) as anestesi, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then 0 ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.3) ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "end) as paragigi, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then 0 ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.3) ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.3) ".
            "end) as paramedisro, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then 0 ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.30) ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "end) as paraok, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then ((a.tagihan*.85)*.30) ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.025) ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.40) ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.30) ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.40) ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.30) ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then 0 ".
            "end) as paralain, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then ((a.tagihan*.85)*.15) ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.15) ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.15) ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.15) ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.15) ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.15) ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.15) ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.15) ".
            "end) as tpdanadm, ".
            "sum(case when d.rs00021_id ='001' and (e.tipe= '004' OR e.tipe='002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='003' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='004' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.075) ".
            "     when d.rs00021_id ='005' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='006' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='007' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='008' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='009' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.05) ".
            "     when d.rs00021_id ='010' and (e.tipe= '004' OR e.tipe = '002') then ((a.tagihan*.85)*.05) ".
            "end) as kebersamaan, ".
            "b.rs00021_id as dummy ".
        "from rs00008 a, rs00034 b, rs00021 c, rs00034 d, rs00006 e, rs00002 f ".
        "where a.trans_type='LTM' and a.no_reg = e.id and e.mr_no = f.mr_no and ".
            "	to_number(a.item_id,'999999999')= b.id and ".
            "	b.rs00021_id = c.id and  ".
            "case when length(rtrim(b.hierarchy,'0'))=9 ".
            "       then substr(rtrim(b.hierarchy,'0'),1,6) = substr(rtrim(d.hierarchy,'0'),1,6) ".
            "   when length(rtrim(b.hierarchy,'0'))=8 ".
            "	    then substr(rtrim(b.hierarchy,'0'),1,6) = substr(rtrim(d.hierarchy,'0'),1,6) ".
            "   when length(rtrim(b.hierarchy,'0'))=12 ".
            "	    then substr(rtrim(b.hierarchy,'0'),1,6) = substr(rtrim(d.hierarchy,'0'),1,6) ".
            "   else substr(rtrim(b.hierarchy,'0'),1,12) = substr(rtrim(d.hierarchy,'0'),1,12) end ".
            "       and d.is_group='Y' and ".
            "(a.tanggal_trans between '$ts_check_in1' and '$ts_check_in2') and ".
            "e.tipe ='".$_GET["mPASIEN"]."' ".
        "group by c.jasa_medis, b.rs00021_id ".
        "order by b.rs00021_id ";
        $t->setlocale("id_ID");
        $t->ShowRowNumber = true;
        $t->RowsPerPage = $ROWS_PER_PAGE;
        $t->ColAlign[7] = "CENTER";
        $t->ColFormatMoney[1] = "%!+#2n";
        $t->ColFormatMoney[2] = "%!+#2n";
        $t->ColFormatMoney[3] = "%!+#2n";
        $t->ColFormatMoney[4] = "%!+#2n";
        $t->ColFormatMoney[5] = "%!+#2n";
        $t->ColFormatMoney[6] = "%!+#2n";
        $t->ColFormatMoney[7] = "%!+#2n";
        $t->ColFormatMoney[8] = "%!+#2n";
        $t->ColFormatMoney[9] = "%!+#2n";
        $t->ColFormatMoney[10] = "%!+#2n";
        $t->ColFormatMoney[11] = "%!+#2n";
        $t->ColFormatMoney[12] = "%!+#2n";
        $t->ColFormatMoney[13] = "%!+#2n";
        $t->ColHeader = array("SUMBER DANA","PENDAPTAN (85%)","DR. SPEC.","DR. UMUM","DR. GIGI",
                              "DR.USG","DR. RONT","DR. ANEST","PM. GIGI","PM. RO","PM. OK","PM. LAIN",
                              "TP & ADM","KEBERSM.","View");
        $t->ColFormatHtml[14] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view&f=<#14#>".
                            "&t1=$ts_check_in1".
                            "&t2=$ts_check_in2".
                            "&c=".$_GET["mPASIEN"]."'>".
                            icon("view","View")."</A>";
        $t->ColFooter[1] =  number_format($d2->tagih,2);
        $t->ColFooter[2] =  number_format($d2->spesialis,2);
        $t->ColFooter[3] =  number_format($d2->umum,2);
        $t->ColFooter[4] =  number_format($d2->gigi,2);
        $t->ColFooter[5] =  number_format($d2->usg,2);
        $t->ColFooter[6] =  number_format($d2->rontgen,2);
        $t->ColFooter[7] =  number_format($d2->anestesi,2);
        $t->ColFooter[8] =  number_format($d2->pmgigi,2);
        $t->ColFooter[9] =  number_format($d2->pmro,2);
        $t->ColFooter[10] =  number_format($d2->pmok,2);
        $t->ColFooter[11] =  number_format($d2->pmlain,2);
        $t->ColFooter[12] =  number_format($d2->tpdanadm,2);
        $t->ColFooter[13] =  number_format($d2->kebersamaan,2);
        $t->execute();
    }
}

?>
