<? // Nugraha, 17/02/2004
   // Pur, 27/02/2004
   // Pur, 27/03/2004 : new libs table
   // sfdn, 21-04-2004
   // sfdm, 08-06-2004

$PID = "430";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
if ($_GET["mLAPOR"] == "002") {
    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));

	$judul	= getFromTable("select tdesc from rs00001 where tc='".$_GET["mLAPOR"]."' and tt='LMR'");
	$prd1	= getFromTable("select to_char(to_date('$ts_check_in1','YYYY-MM-DD'),'DD MON YYYY')");
	$prd2	= getFromTable("select to_char(to_date('$ts_check_in2','YYYY-MM-DD'),'DD MON YYYY')");

	title($judul);
    echo "<br>";
    
    $SQL =
	pg_query("select a.layanan, ".
    	"	(select count(no_reg) ".
        "		from rsv0040 ".
        "		where substr(hierarchy,1,6)=substr(a.hierarchy,1,6) and ".
	"			tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
    	"	) as pasien, ".
    	"	(select count(no_reg) ".
        "		from rsv0040 ".
        "		where is_baru='Y' and ".
        "		substr(hierarchy,1,6)=substr(a.hierarchy,1,6) and ".
	"			tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
    	"	) as baru, ".
    	"	(select count(no_reg) ".
        "		from rsv0040 ".
        "		where is_baru='T' and ".
        "		substr(hierarchy,1,6)=substr(a.hierarchy,1,6) and ".
	"			tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
    	"	) as lama, a.hierarchy as dummy ".
	"from rs00034 a ".
	"where substr(a.hierarchy,1,3)='002' and ".
	"		substr(a.hierarchy,4,3) NOT IN ('000') and ".
	"	is_group ='Y' and substr(a.hierarchy,1,6) NOT IN ('002087','002000','002086','002084')");
    
    while ($d = pg_fetch_object($SQL)) {
       $t_pasien = $t_pasien + $d->pasien;
       $t_lama = $t_lama + $d->lama;
       $t_baru = $t_baru + $d->baru;

    }


    $f = new Form("");
    $f->subtitle("Periode : $prd1 s/d $prd2");
    $f->execute();
    $t = new PgTable($con, "100%");
    $t->SQL =
		"select a.layanan, ".
    	"	(select count(no_reg) ".
        "		from rsv0040 ".
        "		where substr(hierarchy,1,6)=substr(a.hierarchy,1,6) and ".
		"			tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
    	"	) as pasien, ".
    	"	(select count(no_reg) ".
        "		from rsv0040 ".
        "		where is_baru='Y' and ".
        "		substr(hierarchy,1,6)=substr(a.hierarchy,1,6) and ".
		"			tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
    	"	) as baru, ".
    	"	(select count(no_reg) ".
        "		from rsv0040 ".
        "		where is_baru='T' and ".
        "		substr(hierarchy,1,6)=substr(a.hierarchy,1,6) and ".
		"			tanggal_trans between '$ts_check_in1' and '$ts_check_in2' ".
    	"	) as lama, a.hierarchy as dummy ".
		"from rs00034 a ".
		"where substr(a.hierarchy,1,3)='002' and ".
   		"		substr(a.hierarchy,4,3) NOT IN ('000') and ".
   		"	is_group ='Y' and substr(a.hierarchy,1,6) NOT IN ('002087','002000','002086','002084')";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
	$t->ColAlign[1] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "CENTER";
	$t->ColAlign[4] = "CENTER";
    $t->ColHeader = Array("P O L I", "PASIEN MASUK", "B A R U", "L A M A","V i e w");
	$t->ColFormatHtml[4] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&v=<#4#>'>".icon("view","View")."</A>";	
    $t->ColFooter[1] =  number_format($t_pasien,0);
    $t->ColFooter[2] =  number_format($t_baru,0);
    $t->ColFooter[3] =  number_format($t_lama,0);

    $t->execute();

} else {
	title("PELAPORAN REKAM MEDIS");
    echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
    //$f = new Form($SC, "GET");
    $f->PgConn = $con;
    $f->hidden("p", $PID);


include(xxx);
/*
    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));

    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
*/
    //$f->selectDate("f_tanggal1", "Periode Laporan dari", pgsql2phpdate(now));
    //$f->selectDate("f_tanggal2", " s/d", pgsql2phpdate(now));

    $f->selectSQL("mLAPOR", "Jenis Laporan",
    "select '' as tc, '' as tdesc union " .
    "select tc, tdesc ".
    "from rs00001 ".
    "where tt = 'LMR' and tc!='000' ".
    "order by tc", $_GET["mLAPOR"]);

    //$f->submit(" Laporan ", "'actions/430.lap.".$_GET["mPEG"].".php'");
    $f->submit(" Laporan ");
    $f->execute();
	$f->hidden("mLAPOR",$_GET["mLAPOR"]);
	$f->hidden("ts_check_in1",'$ts_check_in1');
	$f->hidden("ts_check_in2",'$ts_check_in2');
    $t = new PgTable($con, "100%");
    $t->SQL =   "select nama, qty1,qty2, qty3,qty4, qty5, qty6, qty7,qty8,qty9, ".
                "qty10,qty11,qty12,qty13,qty14 ".
                "from rs00036 ".
                "where rs00001_tc = '" . $_GET["mPEG"]."'";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColHeader = Array("PELAYANAN", "PASIEN AWAL", "PASIEN MASUK", "KELUAR HIDUP",
                          "MATI < 48 JAM", "MATI >=48 JAM", "LAMA DIRAWAT","PASIEN AKHIR",
                          "HARI PERAWATAN","KLS. UTAMA","KLS. I","KLS. II","KLS. IIIA",
                          "KLS. IIIB","TANPA KELAS");
    $t->execute();
}

?>
