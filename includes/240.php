<? // 30/12/2003
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 29-04-2004
   // sfdn, 30-04-2004

$PID = "240";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (isset($_GET["v"])) {
    $r = pg_query($con, "select * from rsv0002 where id = '".$_GET["v"]."'");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    $f = new Form("");
    title("Data Transaksi Rawat Inap");
    $f->subtitle("Nama: $d->nama");
    $f->execute();
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    $t = new PgTable($con, "100%");
    $t->SQL = "select rs00008.no_reg,rs00006.mr_no,rs00002.nama, ".
             "tanggal(rs00008.tanggal_trans,3) as tgl_trans, ".
        	 "tdesc as unit_layanan, sub_unit_layanan, ".
	         "sum(tagihan) as jumlah,'' ".
             "from rs00008, rs00007, rs00005, rs00003, rs00001, rs00006 ".
             "where to_number(item_id,'999999999999') = rs00007.id ".
             "and rs00007.layanan_id = rs00005.id ".
             "and rs00005.sub_unit_layanan_id = rs00003.id ".
             "and rs00003.unit_layanan_id = rs00001.tc ".
             "and rs00008.no_reg = rs00006.id ".
             "and rs00006.mr_no = rs00002.mr_no ".
             "and tt = 'ULY' ".
             "and trans_type = 'LTM' ".
             "and no_reg = '".$_GET["v"]."'".
             "group  by rs00008.no_reg,rs00002.nama, ".
             "rs00008.tanggal_trans,rs00001.tdesc,rs00003.sub_unit_layanan,rs00006.mr_no";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[1] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatMoney[6] = "%!+#2n";
    $t->ColHeader = array("NO.REG","MR.NO","NAMA PASIEN","TANGGAL","UNIT LAYANAN","SUB UNIT LAYANAN","Rp.","DIBAYAR?");

    $t->execute();


} else {
    // search box
    title("Daftar Pasien Rawat Inap");
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = "select mr_no, nama, tanggal_reg_str, id,".
              "tipe_desc, '','',id as dummy FROM rsv0002 ".
              "where rawat_inap='Y' and ".
              "upper(nama) LIKE '%".strtoupper($_GET["search"])."%' ".
              "order by nama";
    $t->setlocale("id_ID");
    $t->ColHeader = array("MR. NO","NAMA PASIEN","TGL.REG", "NO.REG","TIPE PASIEN", "BANGSAL","SDH.PULANG?","&nbsp;");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "CENTER";
    $t->ColAlign[7] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[7] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&v=<#7#>'>".icon("view","View")."</A>";


    $t->execute();

}
?>
