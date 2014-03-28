<?php // Nugraha, Thu Apr 22 19:51:19 WIT 2004
      // sfdn, 10-05-2004
      // sfdn, 02-06-2004
	  // tokit, 07-07-2004
	// sfdn, 17-12-2006

if ($_SESSION[uid] == "kasir2" || $_SESSION[uid] == "igd"|| $_SESSION[uid] == "kasir1"|| $_SESSION[uid] == "root") {

$PID = "335";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

$reg = $_GET["rg"];
if ($reg > 0) {
    if (getFromTable("select to_number(id,'9999999999') as id ".
                     "from rs00006 ".
                     "where to_number(id,'9999999999') = $reg ".
                     //"and status = 'A'") == 0) 
		     " ") == 0) {
        $reg = 0;
        $msg = "Nomor registrasi tidak ditemukan. Masukkan kembali nomor registrasi.";
    }
}

if ($_SESSION[uid] == "kasir1") {
  title("KASIR RAWAT JALAN");
} elseif ($_SESSION[uid] == "kasir2") {
  title("KASIR RAWAT INAP");
} else {
  title("KASIR IGD");
}

echo "<br>";
if ($reg > 0) {


    include("335.inc.php");

    echo "<form name=Form3>";
    echo "<input name=b1 type=button value='Identitas' onClick='window.location=\"$SC?p=$PID&t1=$ts_check_in1&rg=".$_GET["rg"]."&sub=1\";'".($_GET["sub"] == "1" || $_GET["sub"] == "" ? " DISABLED" : "").">&nbsp;";
    echo "<input name=b2 type=button value='Tindakan Medis' onClick='window.location=\"$SC?p=$PID&t1=$ts_check_in1&rg=".$_GET["rg"]."&sub=2\";'".($_GET["sub"] == "2" ? " DISABLED" : "").">&nbsp;";
    echo "<input name=b2 type=button value='Rincian Tagihan' onClick='window.location=\"$SC?p=$PID&t1=$ts_check_in1&rg=".$_GET["rg"]."&sub=3\";'".($_GET["sub"] == "3" ? " DISABLED" : "").">&nbsp;";
    echo "<input name=b2 type=button value='Pembayaran' onClick='window.location=\"$SC?p=$PID&t1=$ts_check_in1&rg=".$_GET["rg"]."&sub=4\";'".($_GET["sub"] == "4" ? " DISABLED" : "").">&nbsp;";
    echo "</form>";

    $sub = isset($_GET["sub"]) ? $_GET["sub"] : "4";
    if (file_exists("includes/$PID.$sub.php")) include_once("includes/$PID.$sub.php");

} else {



    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p",$PID);
		
		/*
		if (!isset($_GET['tanggal1D'])) {
		
			$tanggal1D = date("d", time());
			$tanggal1M = date("m", time());
			$tanggal1Y = date("Y", time());
			$tanggal2D = date("d", time());
			$tanggal2M = date("m", time());
			$tanggal2Y = date("Y", time());
		
			$ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
			$ts_check_in2 = date("Y-m-d", mktime(0,0,0,0,0,0));
			$f->selectDate("tanggal1", "Tgl Registrasi", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
			//$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");
		
		} else {
		
			$ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
			$ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"]));
			$f->selectDate("tanggal1", "Tgl Registrasi", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
			//$f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
		
		}

		echo "<br>";
		$f->selectSQL("mLUNAS", "Lunas/Blm.Lunas",
				"select '' as tc, '' as tdesc union " .
				"select 'XXX' as tc, 'LUNAS' as tdesc union ".
				"select 'YYY' as tc, 'BELUM LUNAS' as tdesc union ".
				"select 'ZZZ' as tc, 'SEMUA DATA' as tdesc  ", $_GET["mLUNAS"]);
		
		
		
		$f->submit(" TAMPILKAN ", "HREF='index2.php".
				"?p=$PID'");
		
		$f->execute();
		
		*/

        echo "<DIV ALIGN=RIGHT>";
	echo "<TABLE BORDER=0><FORM ACTION=$SC><TR>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
        echo "<TD class=SUB_MENU>NO.MR / NO.REG / NAMA : <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
        echo "<TD><INPUT TYPE=SUBMIT VALUE=' CARI '></TD>";
        echo "</TR></FORM></TABLE>";
	echo "</DIV>";


    $SQLSTR =
	"select id, nama, mr_no, tgl_reg, rawat, pasien, statusbayar, tagih, ".
        "   bayar,sisa ".
        "from rsv0012  ";

		/*
		$SQLWHERE =
			"where (tanggal_reg between '$ts_check_in1' and '$ts_check_in2') AND rawat = 'RAWAT INAP' ";
			if ($_GET["mLUNAS"] == "XXX") {
				$SQLWHERE1 = " and (sisa <= 0 or sisa is null)";
			} elseif ($_GET["mLUNAS"] == "YYY"){
				$SQLWHERE1 = " and sisa > 0";
			} elseif ($_GET["mLUNAS"] == "ZZZ") {
				$SQLWHERE1 = "";
			}
		*/

    if ($_SESSION[uid] == "kasir2") {
       $what = "RAWAT INAP";
    } elseif ($_SESSION[uid] == "kasir1") {
       $what = "RAWAT JALAN";
    } else {
       $what = "IGD";
    }

    $SQLWHERE = "where rawat = '$what' ";

		// sfdn, 17-12-2006
		/*
			if ($_GET["mLUNAS"] == "XXX") {
				$SQLWHERE1 = " and (sisa <= 0 or sisa is null)";
			} elseif ($_GET["mLUNAS"] == "YYY"){
				$SQLWHERE1 = " and sisa > 0";
			} elseif ($_GET["mLUNAS"] == "ZZZ") {
				$SQLWHERE1 = "";
			}
		*/


/* sfdn, 17-12-2006 */
$SQLWHERE1 = "";
$SQLWHERE2 = " and (upper(nama) LIKE '%".strtoupper($_GET[search])."%' or ".
                    " mr_no like '%".$_GET[search]."%' or id like '%".$_GET[search]."%') ";

$SQLWHERE3 = " and statusbayar like '%BELUM LUNAS%'  ";

    if (!isset($_GET[sort])) {
           $_GET[sort] = "id";
           $_GET[order] = "desc";
    }

    echo "<br>";

    $t = new PgTable($con, "100%");
    $t->SQL = "$SQLSTR $SQLWHERE $SQLWHERE1 $SQLWHERE2 $SQLWHERE3 ";
    $t->ColHeader = array("NO.REG", "N A M A", "MR.NO", "TGL. REGISTRASI", "UNIT","TIPE PASIEN","LUNAS","TAGIHAN","BAYAR","SISA");
    $t->ShowRowNumber = true;
    $t->setlocale("id_ID");
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "CENTER";
    $t->RowsPerPage = 30;
    $t->ColFormatMoney[7] = "%!+#2n";
    $t->ColFormatMoney[8] = "%!+#2n";
    $t->ColFormatMoney[9] = "%!+#2n";
    $t->ColFormatHtml[0] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID".
                            "&rg=<#0#>&sub=4".
                            "&t1=$ts_check_in1".
                            "&t2=$ts_check_in2".
                            "'><#0#></A>";

		// sfdn, 17-12-2006
		/*
		if (isset($_GET[search])) {
		$SQLWHERE2 = " and (upper(nama) LIKE '%".strtoupper($_GET[search])."%' or ".
				" mr_no like '%".$_GET[search]."%' or id like '%".$_GET[search]."%') ";
		} else {
		$SQLWHERE2 = " and tanggal_reg = CURRENT_DATE ";
		}
		
		
		if (isset($_GET[tanggal1D])) {
		$SQLWHERE2 = " and tanggal_reg = '$ts_check_in1' ";
		}
		$r2 = pg_query($con,
			"select  sum(tagih) as tagih, ".
			"   sum(bayar)  as bayar, ".
			"   sum(sisa) as sisa ".
			"from rsv0012 ".
			"where tanggal_reg between '$ts_check_in1' and '$ts_check_in2' AND rawat = 'RAWAT INAP'");
		$d2 = pg_fetch_object($r2);
		pg_free_result($r2);
		$t->ColFormatHtml[0] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view".
                            "&rg=<#0#>".
                            "&t1=$ts_check_in1".
                            "&t2=$ts_check_in2".
                            "'><#0#></A>";
		*/
    //$t->ColFooter[7] =  number_format($d2->tagih,2);
    //$t->ColFooter[8] =  number_format($d2->bayar,2);
    //$t->ColFooter[9] =  number_format($d2->sisa,2);
    //$t->ShowSQL = true;

    $t->execute();
}


} // end of $_SESSION[uid] == kasir2 || root
?>
