<? // Nugraha, Sat May  8 22:22:11 WIT 2004
   // sfdn, 18-05-2004
   // sfdn, 01-06-2004
   // sfdn, 06-06-2004

$PID = "405";
$SC = $_SERVER["SCRIPT_NAME"];

/*
$t->SQL = "select a.no_reg, b.nama, ".
          "    to_char(a.ts_check_in,'DD/MM/YYYY HH24:MI:SS') as tgl_masuk, ".
          "    f.bangsal, e.bangsal as ruangan, d.bangsal as bed ".
          "from rs00010 as a ".
          "    join rs00006 as c on a.no_reg = c.id ".
          "    join rs00002 as b on c.mr_no = b.mr_no ".
          "    join rs00012 as d on a.bangsal_id = d.id ".
          "    join rs00012 as e on e.hierarchy = substr(d.hierarchy,1,6) || '000000000' ".
          "    join rs00012 as f on f.hierarchy = substr(d.hierarchy,1,3) || '000000000000' ".
          "where a.ts_calc_stop is null";
*/

$f = new Form($SC, "GET", "NAME=Form1");
$f->PgConn = $con;

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
	    
    $tgl_sakjane = $_GET[tanggal2D] + 1;	

    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");

    //echo "Xxx: $ts_check_in2"; exit();	
	}

$f->selectSQL("mBANGSAL", "Bangsal Keperawatan",
        "select '' as tc, '' as tdesc union " .
	"select '999999999999999' as tc, 'Semua' as tdesc union ".
	"select a.hierarchy as tc, a.bangsal as tdesc ".
        "from rs00012 as a ".
        "   join rs00012 as b on substr(a.hierarchy,1,3) = ".
        "          substr(b.hierarchy,1,3) and b.is_group = 'N' ".
        "   join rs00012 as c on (substr(a.hierarchy,1,3) = ".
        "           substr(c.hierarchy,1,3)) and c.is_group = 'Y' ".
        "           and substr(c.hierarchy,4,3) NOT IN ('000') ".
        "where substr(a.hierarchy,4,6) = '000000' ".
        "group by a.bangsal, a.hierarchy ", $_GET["mBANGSAL"]);

$f->hidden("p",$PID);
$f->submit(" TAMPILKAN ", "HREF='index2.php?p=$PID'");
$f->execute();
if ($_GET["mBANGSAL"] == "999999999999999" OR strlen($_GET["mBANGSAL"]) <=0) {
	$SQL2 = " ";
} elseif (strlen($_GET["mBANGSAL"]) > 0 ) {
	$SQL2 = " and f.hierarchy = '".$_GET["mBANGSAL"]."'   ";
}
$SQL1 = 
	"select distinct(a.no_reg) as noreg, b.nama, ".
          "    to_char(a.ts_check_in,'DD-MON-YYYY' ) as tgl_masuk, ".
          "    f.bangsal, e.bangsal as ruangan, g.tdesc as kelas, d.bangsal as bed ".
          "from rs00010 as a ".
          "    join rs00006 as c on a.no_reg = c.id ".
          "    join rs00002 as b on c.mr_no = b.mr_no ".
          "    join rs00012 as d on a.bangsal_id = d.id ".
          "    join rs00012 as e on e.hierarchy = substr(d.hierarchy,1,6) || '000000000' ".
          "    join rs00012 as f on f.hierarchy = substr(d.hierarchy,1,3) || '000000000000' ".
	  "	   join rs00001 as g ON e.klasifikasi_tarif_id = g.tc and g.tt='KTR' ".
          "where a.ts_check_in between '$ts_check_in1' and ".
          "       '$ts_check_in2' ";
$SQL3 = "group by a.no_reg, b.nama, f.bangsal, e.bangsal, d.bangsal, a.ts_check_in, g.tdesc ";
$SQL = "$SQL1 $SQL2 $SQL3";
$t = new PgTable($con, "100%");
$t->SQL = $SQL;

$t->ColHeader = array("NO REG", "NAMA", "TANGGAL MASUK", "BANGSAL KEPERAWATAN",
                      "NAMA BANGSAL", "KELAS","BED", "&nbsp;");
$t->ShowRowNumber = true;
$t->ColAlign[0] = "CENTER";
$t->ColAlign[2] = "CENTER";
$t->RowsPerPage = $ROWS_PER_PAGE;
$t->ColFormatHtml[0] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID".
                       "&sub=4".
                       "&rg=<#0#>'><#0#></A>";
$t->execute();

?>
