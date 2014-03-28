<?php // Nugraha, Thu Apr 22 19:51:19 WIT 2004
      // sfdn, 10-05-2004
      // sfdn, 02-06-2004
	  // tokit, 07-07-2004

if ($_SESSION[uid] == "kasir1" || $_SESSION[uid] == "root") {

$PID = "337";
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
                     " ") == 0) {
		     //"and status = 'A'") == 0) {
        $reg = 0;
        $msg = "Nomor registrasi tidak ditemukan. Masukkan kembali nomor registrasi.";
    }
}

title("KASIR ==> Data Tagihan Pasien");
echo "<br>";
if ($reg > 0) {

    $t = new Form("");
    $t->subtitle("Data Medis Terakhir");
    $t->execute();

    $r = pg_query($con,
        "SELECT a.id, to_char(a.tanggal_reg,'DD MONTH YYYY') AS tanggal_reg, a.waktu_reg, ".
        "    a.mr_no, e.nama, to_char(e.tgl_lahir, 'DD MONTH YYYY') AS tgl_lahir, ".
        "    e.tmp_lahir, e.jenis_kelamin, f.tdesc AS agama, ".
        "    e.alm_tetap, e.kota_tetap, e.pos_tetap, e.tlp_tetap, ".
        "    a.id_penanggung, b.tdesc AS penanggung, a.id_penjamin, ".
        "    c.tdesc AS penjamin, a.no_jaminan, a.rujukan, a.rujukan_rs_id, ".
        "    d.tdesc AS rujukan_rs, a.rujukan_dokter, a.rawat_inap, ".
        "    a.status, a.tipe, g.tdesc AS tipe_desc, a.diagnosa_sementara, ".
        "    to_char(a.tanggal_reg, 'DD MONTH YYYY') AS tanggal_reg_str, ".
        "        CASE ".
        "            WHEN a.rawat_inap = 'I' THEN 'Rawat Inap' ".		
        "            WHEN a.rawat_inap = 'Y' THEN 'Rawat Jalan' ".
        "            ELSE 'IGD' ".
        "        END AS rawat, ".
        "        age(a.tanggal_reg , e.tgl_lahir ) AS umur, ".
		"	case when a.rujukan = 'Y' then 'Rujukan' else 'Non-Rujukan' end as datang ".
        "FROM rs00006 a ".
        "   LEFT JOIN rs00001 b ON a.id_penanggung = b.tc AND b.tt = 'PEN'".
        "   LEFT JOIN rs00001 c ON a.id_penjamin = c.tc AND c.tt = 'PJN' ".
        "   LEFT JOIN rs00002 e ON a.mr_no = e.mr_no ".
        "   LEFT JOIN rs00001 f ON e.agama_id = f.tc AND f.tt = 'AGM' ".
        "   LEFT JOIN rs00001 g ON a.tipe = g.tc AND g.tt = 'JEP' ".
        "   LEFT JOIN rs00001 d ON a.id_penjamin = d.tc AND d.tt = 'RUJ' ".
        "   LEFT JOIN rs00001 h ON a.jenis_kedatangan_id = h.tc AND h.tt = 'JDP' ".
        "WHERE a.id = lpad($reg,10,'0') ");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";
    $f = new ReadOnlyForm();
    $f->text("Nama", $d->nama);
    $f->text("Nomor Registrasi", formatRegNo($d->id). " - " . getFromTable("select count(mr_no) from rs00006 where mr_no = '$d->mr_no'"));
    $f->text("Nomor MR", $d->mr_no);
    $f->text("Pasien Dari", $d->rawat );
    $f->text("RS Perujuk", $d->rujukan_rs);
    $f->text("Dokter Perujuk", $d->rujukan_dokter);
    $f->text("Jenis Kedatangan", $d->datang);

    $f->execute();
    echo "</td><td align=center valign=top width='33%'>";
    $f = new ReadOnlyForm();
    $f->text("Tanggal Reg.", $d->tanggal_reg);
    $f->text("Waktu Registrasi", substr($d->waktu_reg,0,8));
    $f->text("Penanggung", $d->penanggung);
    $f->text("Penjamin", $d->penjamin);
    $f->text("No. Jaminan", $d->no_jaminan);
    $f->text("Tipe Pasien", $d->tipe_desc);
    $f->execute();
    echo "</td><td valign=top width='33%'>";
    $f = new ReadOnlyForm();
    echo "<table border=0 width='100%'>";
    echo "<tr><td class=TBL_BODY>Diagnosa Sementara:</td></tr>";
    echo "<tr><td align=justify class=TBL_BODY>$d->diagnosa_sementara</td></tr>";
    echo "</table>";
    $f->execute();
    echo "</td></tr></table>";

    echo "<form name=Form3>";
    echo "<input name=b1 type=button value='Identitas' onClick='window.location=\"$SC?p=$PID&t1=$ts_check_in1&rg=".$_GET["rg"]."&sub=1\";'".($_GET["sub"] == "1" || $_GET["sub"] == "" ? " DISABLED" : "").">&nbsp;";
    echo "<input name=b2 type=button value='Tindakan Medis' onClick='window.location=\"$SC?p=$PID&t1=$ts_check_in1&rg=".$_GET["rg"]."&sub=2\";'".($_GET["sub"] == "2" ? " DISABLED" : "").">&nbsp;";
    echo "<input name=b2 type=button value='Rincian Tagihan' onClick='window.location=\"$SC?p=$PID&t1=$ts_check_in1&rg=".$_GET["rg"]."&sub=3\";'".($_GET["sub"] == "3" ? " DISABLED" : "").">&nbsp;";
    echo "<input name=b2 type=button value='Pembayaran' onClick='window.location=\"$SC?p=$PID&t1=$ts_check_in1&rg=".$_GET["rg"]."&sub=4\";'".($_GET["sub"] == "4" ? " DISABLED" : "").">&nbsp;";
    echo "</form>";

    $sub = isset($_GET["sub"]) ? $_GET["sub"] : "1";
    if (file_exists("includes/$PID.$sub.php")) include_once("includes/$PID.$sub.php");

} else {
    $f = new Form($SC, "GET", "NAME=Form1");
	$f->PgConn = $con;
	$f->hidden("p",$PID);
 
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
	
    echo "<br>";
    $f->selectSQL("mLUNAS", "Lunas/Blm.Lunas",
		"select '' as tc, '' as tdesc union " .
		"select 'XXX' as tc, 'LUNAS' as tdesc union ".
		"select 'YYY' as tc, 'BLM.LUNAS' as tdesc union ".
		"select 'ZZZ' as tc, 'SEMUA DATA' as tdesc  ", $_GET["mLUNAS"]);
    $f->submit(" OK ", "HREF='index2.php".
                    "?p=$PID'");

    $f->execute();
	$SQLSTR =  
		"select id, nama,mr_no,tgl_reg, rawat, pasien, statusbayar, tagih, ".
        "   bayar,sisa ".
        "from rsv0012  ";
	$SQLWHERE =	
        "where(tanggal_reg between '$ts_check_in1' and '$ts_check_in2') ";
	if ($_GET["mLUNAS"] == "XXX") {
		$SQLWHERE1 = " and (sisa <= 0 or sisa is null)";
	} elseif ($_GET["mLUNAS"] == "YYY"){
		$SQLWHERE1 = " and sisa > 0";
	} elseif ($_GET["mLUNAS"] == "ZZZ") {
		$SQLWHERE1 = "";
	}


    $r2 = pg_query($con,
        "select  sum(tagih) as tagih, ".
        "   sum(bayar)  as bayar, ".
        "   sum(sisa) as sisa ".
        "from rsv0012 ".
        "where tanggal_reg between '$ts_check_in1' and '$ts_check_in2'");
    echo "<br>";
    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);

    $t = new PgTable($con, "100%");
    $t->SQL = "$SQLSTR $SQLWHERE $SQLWHERE1 ";
    $t->ColHeader = array("NO.REG", "N A M A", "MR.NO", "TGL. REGISTRASI", "U N I T",
                          "TIPE PASIEN","BAYAR?","TAGIHAN","BAYAR","SISA");
    $t->ShowRowNumber = true;
    $t->setlocale("id_ID");
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "CENTER";
    $t->RowsPerPage = 100;
    $t->ColFormatMoney[7] = "%!+#2n";
    $t->ColFormatMoney[8] = "%!+#2n";
    $t->ColFormatMoney[9] = "%!+#2n";
    $t->ColFormatHtml[0] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tc=view".
                            "&rg=<#0#>".
                            "&t1=$ts_check_in1".
                            "&t2=$ts_check_in2".
                            "'><#0#></A>";

    $t->ColFooter[7] =  number_format($d2->tagih,2);
    $t->ColFooter[8] =  number_format($d2->bayar,2);
    $t->ColFooter[9] =  number_format($d2->sisa,2);
    //$t->ShowSQL = true;
    $t->execute();

}

} // end of $_SESSION[uid] == kasir1 || root
?>
