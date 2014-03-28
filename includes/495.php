<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 01-06-2004

$PID = "495";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if($_GET["tc"] == "view") {
    title("Rincian Retur Obat");

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
    $tgl_year = substr($_GET[f],0,4);
    $tgl_mnth = substr($_GET[f],4,2);
    $tgl_day = substr($_GET[f],6,2);
    
    $f = new Form("");
    $f->subtitle("Tanggal    : $tgl_day-$tgl_mnth-$tgl_year");
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
              "     a.trans_type='RET' and b.tipe = '".$_GET["u"]."'");

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
              "     a.trans_type = 'RET' ".
              "group by c.mr_no, c.nama, a.no_reg, e.obat, a.qty, a.harga";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[2] = "CENTER";
    $t->RowsPerPage = 30;
    $t->ColFormatMoney[4] = "%!+#2n";
    $t->ColFormatMoney[5] = "%!+#2n";
    $t->ColFormatMoney[6] = "%!+#2n";
    $t->ColHeader = array("MR.NO","NAMA","NO.REG","NAMA O B A T","QTY","HARGA","Rp.");
    $t->ColFooter[6] =  number_format($d2->jum,2);
    //$t->ShowSQLExecTime = true;
    //$t->ShowSQL = true;

    $t->execute();

} else {
    //------------------------------------------------------- mulai
    title("LAPORAN PIUTANG");
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

	$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
        $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
    	$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
	$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
    }

    /*
    $f->selectSQL("mUNIT", "U N I T",
        "select '' as tc, '' as tdesc union ".
        "select distinct(b.rawat_inap) as tc, case when b.rawat_inap='Y' then 'RAWAT JALAN' when b.rawat_inap='I' then 'RAWAT INAP' else 'IGD' end as tdesc ".
        "from rs00008 a, rs00006 b ".
        "where a.trans_type = 'OB1' and a.no_reg = b.id ", $_GET["mUNIT"],
        $ext);


    $f->selectArray("mUNIT", "U N I T",
        Array(""=>"", "Y" => "Rawat Jalan", "I" => "Rawat Inap", "N" => "IGD"), $_GET["mUNIT"],
        $ext);

    $f->selectSQL("mPASIEN", "Tipe Pasien",
        "select '' as tc, '' as tdesc union ".
        "select tc, tdesc ".
        "from rs00001  ".
        "where tt='JEP' and tc != '000'", $_GET["mPASIEN"],
        $ext);
        */

    $f->submit ("OK");
    $f->execute();
    echo "<br>";
    
    if (!empty($_GET[mUNIT])) {
       $SQL_a = " and b.rawat_inap = '".$_GET["mUNIT"]."' ";
    } else {
       $SQL_a = " and b.rawat_inap = '".$_GET["mUNIT"]."' ";
    }

    if (!empty($_GET[mPASIEN])) {
       $SQL_b = " and b.tipe = '".$_GET["mPASIEN"]."' ";
    } else {
       $SQL_b = " and b.tipe = '".$_GET["mPASIEN"]."' ";
    }




        $SQL = "select a.id as no_reg, b.nama, b.alm_tetap, c.tagihan ".
              "from rs00006 a ".
              "     left join rs00002 b ON a.mr_no = b.mr_no ".
              "     left join rsvlunas c on c.no_reg = a.id ".
              "where c.ket is null ".
              "     and (a.tanggal_reg between '$ts_check_in1' and '$ts_check_in2') ".
              "  ";



        $d = pg_query($SQL);
        while ($d2 = pg_fetch_object($d)) {
           $jumlah = $jumlah + $d2->tagihan;
        }


	if (!isset($_GET[sort])) {
           $_GET[sort] = "id";
           $_GET[order] = "asc";
	}


    $t = new PgTable($con, "100%");
    $t->SQL = "$SQL";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[3] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view&e=".$_GET["mUNIT"]."&f=<#3#>&u=".$_GET["mPASIEN"]."'>".
                        icon("view","View")."</A>";
    $t->ColFormatMoney[2] = "%!+#2n";
    $t->ColHeader = array("NO.REG","NAMA","ALAMAT", "TAGIHAN");
    $t->ColFooter[3] =  number_format($jumlah,2);
    $t->execute();

}

?>
