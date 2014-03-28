<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 01-06-2004
   // sfdn, 24-12-2006	

$PID = "490";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

// 24-12-2006
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
// ---- end ----
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
    if (!$GLOBALS['print']){
    	title("<img src='icon/keuangan-2.gif' align='absmiddle' > Laporan Pendapatan Total");
    } else {
    	title("<img src='icon/keuangan.gif' align='absmiddle' > Laporan Pendapatan Total");
    }
    //title("LAPORAN PENDAPATAN TOTAL");
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

	if(!$GLOBALS['print']){
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
		$f->submit ("OK");
		$f->execute();	
	} else {
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
	    	$f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
			$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
    	}
		//$f->submit ("OK");
		$f->execute();	
	}
    
    echo "<br>";

    $q = pg_query($con, 
    			  "select a.tdesc as layanan ".
    			  "  , sum(b.jumlah) as jml_duit ".
    			  "  from rs00001 a ".
    			  "  left join rs00005 b on b.layanan = a.tc and (b.tgl_entry between '$ts_check_in1' and '$ts_check_in2') ".
    			  "  WHERE  a.tt = 'LYN'   ".
    			  "  group by a.tc,a.tdesc  order by a.tdesc  asc ");

	$nIGD = getFromTable(
               "select sum(b.jumlah) as jml_duit ".
	"from rs00001 a ".
	"  	left join rs00005 b on b.layanan = a.tc ".
	"  	and (b.tgl_entry between '$ts_check_in1' and '$ts_check_in2') ".
 	"  WHERE  a.tt = 'LYN' and a.tc ='100'  ".
	"  group by a.tc,a.tdesc  order by a.tdesc  asc ");
	// end of 24-12-2006



    $spasi1 = "<img src='images/spacer.gif' width='20' height='1'>";
    $spasi2 = "<img src='images/spacer.gif' width='50' height='1'>";
    $spasi3 = "";

    echo "<table cellpadding=0 cellspacing=0 border=0>";
    echo "<tr>";
    echo "<td colspan=4><b>A. PENDAPATAN NON-OBAT</b></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td colspan=4>".$spasi1."<b>1. RAWAT JALAN</b></td>";
    echo "</tr>";

   $non_rj = 0;
    while ($d2 = pg_fetch_object($q)) {
       if ($d2->layanan == "INSTALASI GAWAT DARURAT") {
       $totale_igd = $d2->jml_duit;
       //$non_rj = $non_rj + $d2->jml_duit;
       } else {
       $non_rj = $non_rj + $d2->jml_duit;
       if ($non_rj < 1) {
         $totale = 0;
       } else {
         $totale = $non_rj;
       }

       echo "<tr>";
       echo "<td>$spasi2- $d2->layanan</td>";
       echo "<td>&nbsp;:&nbsp;</td>";
       echo "<td align=right>".number_format($d2->jml_duit,2,",",".")."</td>";
       echo "<td width=100>&nbsp;</td>";
       echo "</tr>";
       //$non_rj = 0;
       }
    }

    pg_free_result($q);

    echo "<tr>";
    echo "<td></td>";
    echo "<td>&nbsp;</td>";
    echo "<td align=right>&nbsp;:&nbsp;</td>";

    echo "<td align=right>$spasi3<b>".number_format($non_rj,2,",",".")."</b></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td>".$spasi1."<b>2. IGD</b></td>";
    echo "<td>&nbsp;</td>";
    echo "<td align=right>&nbsp;:&nbsp;</td>";
    echo "<td align=right colspan=2><b>".number_format($nIGD,2,",",".")."</b></td>";
    echo "</tr>";

    //$sql = pg_query("select sum(jumlah) as jumlah from rs00005 where kasir = 'RIN' and is_obat = 'N' ".
    //                "and (tgl_entry between '$ts_check_in1' and '$ts_check_in2') ");

	// sfdn, 26-12-2006
/*
	$nTagihan = getFromTable("select (sum(jmlbayar)-sum(obat)) from rsvmondok ".
			"where  ".
                   	" (tgl_pulang between '$ts_check_in1' and '$ts_check_in2') ");
*/
	// --- eof 26-12-2006 ---


	$nTagihan = getFromTable("select sum (jumlah) as jumlah from rs00005 ".
			"where is_bayar = 'Y' and layanan = 99996  ".
                   	" AND (tgl_entry between '$ts_check_in1' and '$ts_check_in2') ");

 
	
    echo "<tr>";
    echo "<td>".$spasi1."<b>3. RAWAT INAP</b></td>";
    echo "<td>&nbsp;</td>";
    echo "<td align=right>&nbsp;:&nbsp;</td>";
    echo "<td align=right colspan=2><b>".number_format($nTagihan,2,",",".")."</b></td>";
    echo "</tr>";

	// sfdn, 26-12-2006 --> jumlahnya diubah menjadi $non_rj+$nIGD+$nTagihan
    $tot_nonobat = $non_rj+$nIGD+$nTagihan;
	// end of 26-12-2006

    echo "<tr>";
    echo "<td></td>";
    echo "<td>&nbsp;</td>";
    echo "<td align=right>Sub total A&nbsp;:&nbsp;</td>";

    echo "<td align=right colspan=2><font color=blue><b>".number_format($tot_nonobat,2,",",".")."</b></font></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td colspan=4><br><br><b>B. PENDAPATAN OBAT</b></td>";
    echo "</tr>";

    // sfdn, 24-12-2006 --> untuk meyakinkan bahwa data yang diambil is_obat='Y' and is_bayar='Y'
    $sql = pg_query("select sum(jumlah) as jumlah from rs00005 ".
		"where kasir = 'RJL' and is_obat = 'Y' ".//and is_bayar ='Y' ".
                    "and (tgl_entry between '$ts_check_in1' and '$ts_check_in2') ");
    $d4 = pg_fetch_object($sql);


    echo "<tr>";
    echo "<td>".$spasi1."<b>1. RAWAT JALAN</b></td>";
    echo "<td>&nbsp;</td>";
    echo "<td align=right>&nbsp;:&nbsp;</td>";
    echo "<td align=right colspan=2><b>".number_format($d4->jumlah,2,",",".")."</b></td>";
    echo "</tr>";

    $sql = pg_query("select sum(jumlah) as jumlah from rs00005 ".
		"where kasir = 'IGD' and is_obat = 'Y' ".//and is_bayar = 'Y' ".
                    "and (tgl_entry between '$ts_check_in1' and '$ts_check_in2') ");
    $d5 = pg_fetch_object($sql);

    // end of 24-12-2006
    echo "<tr>";
    echo "<td>".$spasi1."<b>2. IGD</b></td>";
    echo "<td>&nbsp;</td>";
    echo "<td align=right>&nbsp;:&nbsp;</td>";
    echo "<td align=right colspan=2><b>".number_format($d5->jumlah,2,",",".")."</b></td>";
    echo "</tr>";

    $sql = pg_query("select sum(jumlah) as jumlah from rs00005 where kasir = 'RIN' and is_obat = 'Y' ".
                    "and (tgl_entry between '$ts_check_in1' and '$ts_check_in2') ");
    $d6 = pg_fetch_object($sql);

    echo "<tr>";
    echo "<td>".$spasi1."<b>3. RAWAT INAP</b></td>";
    echo "<td>&nbsp;</td>";
    echo "<td align=right>&nbsp;:&nbsp;</td>";
    echo "<td align=right colspan=2><b>".number_format($d6->jumlah,2,",",".")."</b></td>";
    echo "</tr>";

    $tot_obat = $d4->jumlah+$d5->jumlah+$d6->jumlah;
    echo "<tr>";
    echo "<td></td>";
    echo "<td>&nbsp;</td>";
    echo "<td align=right>Sub total B&nbsp;:&nbsp;</td>";
    echo "<td align=right colspan=2><font color=blue><b>".number_format($tot_obat,2,",",".")."</b></font></td>";
    echo "</tr>";

    echo "<tr><td>&nbsp;</td></tr>";
    $total_pendapatan = $tot_obat+$tot_nonobat;
    echo "<tr>";
    echo "<td></td>";
    echo "<td>&nbsp;</td>";
    echo "<td align=right>Total A dan B&nbsp;:&nbsp;</td>";
    echo "<td align=right colspan=2><font color=red><b>".number_format($total_pendapatan,2,",",".")."</b></font></td>";
    echo "</tr>";
    echo "</table>";

}
title_print("");;
?>