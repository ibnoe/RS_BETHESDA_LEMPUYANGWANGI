<? // Nugraha, 17/02/2004
   // Pur, 27/02/2004
   // Pur, 27/03/2004 : new libs table
   // sfdn, 21-04-2004
   // sfdn, 08-06-2004
   // sfdn, 12-06-2004
   // tokit aja, 09-09-2004

$PID = "480";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 50;
$RS_NAME = "RUMAH SAKIT XXXXXXXXXXXXXX XXXXXXXXXXXXXXXX";
require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");

?>

<HTML>

<HEAD>
<TITLE>::: Sistem Informasi <?php echo $RS_NAME; ?> :::</TITLE>
<LINK rel='styleSheet' type='text/css' href='../cetak.css'>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>
</HEAD>

<BODY TOPMARGIN=5 LEFTMARGIN=5 MARGINWIDTH=0 MARGINHEIGHT=0>


<?
if ($_GET["mLAPOR"] == "002") {
    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));

	$judul	= getFromTable("select tdesc from rs00001 where tc='".$_GET["mLAPOR"]."' and tt='LMR'");
	$prd1	= getFromTable("select to_char(to_date('$ts_check_in1','YYYY-MM-DD'),'DD MON YYYY')");
	$prd2	= getFromTable("select to_char(to_date('$ts_check_in2','YYYY-MM-DD'),'DD MON YYYY')");

    title($judul);
    echo "<br>";
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
    $t->execute();

} else {
    title("LAPORAN KARCIS RAWAT JALAN");
    echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
    //$f = new Form($SC, "GET");
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
	    
    //$tgl_sakjane = $_GET[tanggal2D] + 1;

    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");

    //echo "Xxx: $ts_check_in2"; exit();	
	}

        $f->selectSQL("mPASIEN", "Tipe Pasien",
        "select '' as tc, '' as tdesc union ".
        "select tc, tdesc ".
        "from rs00001 ".
        "where tt='JEP' and tc != '000' ", $_GET["mPASIEN"],
        $ext);
        
        if ($_GET[mPASIEN] == "") {
           $tipe_pasien = "";
        } else {
           $tipe_pasien = " and rs00006.tipe = '".$_GET[mPASIEN]."'";
        }

//------  query begin here

$SQL =  "select a.layanan, ".
        "   (select count(id) from rs00006 b where b.poli = a.id ".
        "         and (b.tanggal_reg between '$ts_check_in1' and '$ts_check_in2') and b.tipe like '%".$_GET[mPASIEN]."%') as pasien ".
        "   , (select sum(b.tagihan) from rs00008 b ".
        "         left join rs00034 c on c.id = b.item_id  ".
        "         left join rs00006 d on d.id = b.no_reg   ".
        "      where b.trans_type = 'LTM' and substr(c.hierarchy,7,3) = '001' ".
        "         and (d.tanggal_reg between '$ts_check_in1' and '$ts_check_in2') ".
        "         and d.tipe like '%".$_GET[mPASIEN]."%' and substr(c.hierarchy,1,6)= substr(a.hierarchy,1,6) ) as sarana ".
        "   , (select sum(b.tagihan) from rs00008 b ".
        "         left join rs00034 c on c.id = b.item_id  ".
        "         left join rs00006 d on d.id = b.no_reg   ".
        "      where b.trans_type = 'LTM' and substr(c.hierarchy,7,3) = '002' ".
        "         and (substr(c.hierarchy,4,3) IN ('082','083','016','017')) ".
        "         and (d.tanggal_reg between '$ts_check_in1' and '$ts_check_in2') ".
        "         and d.tipe like '%".$_GET[mPASIEN]."%' and substr(c.hierarchy,1,6)= substr(a.hierarchy,1,6) ) as bpumum ".
        "   , (select sum(b.tagihan) from rs00008 b ".
        "         left join rs00034 c on c.id = b.item_id  ".
        "         left join rs00006 d on d.id = b.no_reg   ".
        "      where b.trans_type = 'LTM' and substr(c.hierarchy,7,3) = '002' ".
        "         and (substr(c.hierarchy,4,3) IN ('005','003','004','002','001','006','008','009','086')) ".
        "         and (d.tanggal_reg between '$ts_check_in1' and '$ts_check_in2') ".
        "         and d.tipe like '%".$_GET[mPASIEN]."%' and substr(c.hierarchy,1,6)= substr(a.hierarchy,1,6) ) as bpspesialis ".
        "   , (select sum(b.tagihan) from rs00008 b ".
        "         left join rs00034 c on c.id = b.item_id  ".
        "         left join rs00006 d on d.id = b.no_reg   ".
        "      where b.trans_type = 'LTM' and substr(c.hierarchy,7,3) = '003' ".
        "         and (substr(c.hierarchy,4,3) IN ('082','083')) ".
        "         and (d.tanggal_reg between '$ts_check_in1' and '$ts_check_in2') ".
        "         and d.tipe like '%".$_GET[mPASIEN]."%' and substr(c.hierarchy,1,6)= substr(a.hierarchy,1,6) ) as obatpaket ".
        "   , (select sum(b.tagihan) from rs00008 b ".
        "         left join rs00034 c on c.id = b.item_id  ".
        "         left join rs00006 d on d.id = b.no_reg   ".
        "      where b.trans_type = 'LTM' and substr(c.hierarchy,7,3) = '003' ".
        "         and (substr(c.hierarchy,4,3) IN ('005','003','004','002','001','006','008','009','086')) ".
        "         and (d.tanggal_reg between '$ts_check_in1' and '$ts_check_in2') ".
        "         and d.tipe like '%".$_GET[mPASIEN]."%' and substr(c.hierarchy,1,6)= substr(a.hierarchy,1,6) ) as obatlanjut ".

        "from rs00034 a ".
        "where a.id in (12653, 13207, 13208, 11095, 13209, 13210, 12651, 8, 11074, 11075, 11076, 11097, 11072, 11071, 11096, 11073, 7, 13111) ".
        "   ";


    $f->submit(" Laporan ");
    $f->execute();



    echo "<TABLE CLASS=TBL_BORDER WIDTH='100%' BORDER=0 CELLSPACING=0 CELLPADDING=1><tr><td>";
    echo "<TABLE WIDTH='100%' BORDER=0 CELLSPACING=1 CELLPADDING=2>";
echo "<TR>";
echo "    <TD CLASS=TBL_HEAD ALIGN=CENTER>NO</TD>";
echo "    <TD CLASS=TBL_HEAD ALIGN=CENTER>UNIT LAYANAN</TD>";
echo "    <TD CLASS=TBL_HEAD ALIGN=CENTER>PASIEN</TD>";
echo "    <TD CLASS=TBL_HEAD ALIGN=CENTER>JASA SARANA</TD>";
echo "    <TD CLASS=TBL_HEAD ALIGN=CENTER>B/P<br>UMUM</TD>";
echo "    <TD CLASS=TBL_HEAD ALIGN=CENTER>B/P SPESIALIS</TD>";
echo "    <TD CLASS=TBL_HEAD ALIGN=CENTER>OBAT R/J PAKET</TD>";
echo "    <TD CLASS=TBL_HEAD ALIGN=CENTER>OBAT R/J LANJUT</TD>";
echo "    <TD CLASS=TBL_HEAD ALIGN=CENTER>TOTAL</TD>";
echo "</TR>";

$q = pg_query($SQL);
$i = 0;

while ($r = pg_fetch_object($q)) {
$i++;
$total = $r->sarana + $r->bpumum + $r->bpspesialis + $r->obatpaket + $r->obatlanjut;
$tpasien = $tpasien + $r->pasien;
$tsarana = $tsarana + $r->sarana;
$tbpumum = $tbpumum + $r->bpumum;
$tbpspesialis = $tbpspesialis + $r->bpspesialis;
$tobatpaket = $tobatpaket + $r->obatpaket;
$tobatlanjut = $tobatlanjut + $r->obatlanjut;
$ttotal = $ttotal + $total;

echo "<TR>";
echo "    <TD CLASS=TBL_BODY ALIGN=RIGHT>$i</TD>";
echo "    <TD CLASS=TBL_BODY ALIGN=LEFT>$r->layanan</TD>";
echo "    <TD CLASS=TBL_BODY ALIGN=CENTER>$r->pasien</TD>";
echo "    <TD CLASS=TBL_BODY ALIGN=RIGHT>".number_format($r->sarana,2,",",".")."</TD>";
echo "    <TD CLASS=TBL_BODY ALIGN=RIGHT>".number_format($r->bpumum,2,",",".")."</TD>";
echo "    <TD CLASS=TBL_BODY ALIGN=RIGHT>".number_format($r->bpspesialis,2,",",".")."</TD>";
echo "    <TD CLASS=TBL_BODY ALIGN=RIGHT>".number_format($r->obatpaket,2,",",".")."</TD>";
echo "    <TD CLASS=TBL_BODY ALIGN=RIGHT>".number_format($r->obatlanjut,2,",",".")."</TD>";
echo "    <TD CLASS=TBL_BODY ALIGN=RIGHT>".number_format($total,2,",",".")."</TD>";
echo "</TR>";
}
echo "<TR>";
echo "    <TD CLASS=TBL_HEAD ALIGN=RIGHT></TD>";
echo "    <TD CLASS=TBL_HEAD ALIGN=RIGHT></TD>";
echo "    <TD CLASS=TBL_HEAD ALIGN=CENTER>".number_format($tpasien,0,",",".")."</TD>";
echo "    <TD CLASS=TBL_HEAD ALIGN=RIGHT>".number_format($tsarana,2,",",".")."</TD>";
echo "    <TD CLASS=TBL_HEAD ALIGN=RIGHT>".number_format($tbpumum,2,",",".")."</TD>";
echo "    <TD CLASS=TBL_HEAD ALIGN=RIGHT>".number_format($tbpspesialis,2,",",".")."</TD>";
echo "    <TD CLASS=TBL_HEAD ALIGN=RIGHT>".number_format($tobatpaket,2,",",".")."</TD>";
echo "    <TD CLASS=TBL_HEAD ALIGN=RIGHT>".number_format($tobatlanjut,2,",",".")."</TD>";
echo "    <TD CLASS=TBL_HEAD ALIGN=RIGHT>".number_format($ttotal,2,",",".")."</TD>";
echo "</TR>";
echo "</TABLE>";
    echo "</td></tr></TABLE>";


}

?>
</body>
</html>


