<? // 30/12/2003
   // sfdn, 21-04-2004
   // sfdn, 22-04-2004

$PID = "510";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");


if(isset($_GET["e"])) {
    if ($_GET["e"] != "new") {
        $r = pg_query($con, "select * from rsv0031 where id = '".$_GET["e"]."'");
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }

    if($n > 0) {
        $f = new Form("actions/510.update.php", "POST");
        title("Edit Data Supplier");
        $f->subtitle("Data Pengadaan");
        $f->hidden("id","$d->id");
        $f->text("id","Kode suppl.",6,6,$d->id,"DISABLED");
        $f->text("tgl","Tanggal Pesan",20,20,$d->tanggal_pesan,"DISABLED");
        $f->text("f_no_referensi","No. Pesanan",15,15,$d->no_referensi);
        $f->text("supplier","Nama Supplier",30,30,$d->nama,"DISABLE");

    } else {
        $f = new Form("actions/510.insert.php");
        $f->PgConn = $con;
        title("Pengadaan barang FARMALKES Baru");
        $f->subtitle("Data Pengadaan");
        $f->hidden("id","new");
        $f->text("id","Kode",12,12,"<OTOMATIS>","DISABLED");
        $f->selectDate("f_tanggal", "Tanggal Pengadaan", pgsql2phpdate($d->tanggal));
        $f->text("f_no_referensi","No. Bukti",15,15,$d->no_pesanan);
        $f->selectSQL("f_rs00028_id", "Supplier",
                  "select id,nama from rs00028 order by nama",$d->rs00028_id,$exit);

    }
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";

    $f->submit(" Simpan ");
    $f->execute();

} elseif (isset($_GET["v"])) {
    title("Pengadaan barang Farmalkes");
    $r = pg_query($con, "select * from rsv0031 where id = '".$_GET["v"]."'");

    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    echo "<table border=0 width='100%'><tr><td>";
    $f = new ReadOnlyForm();
    $f->text("No. Bukti", $d->no_referensi);
    $f->text("Tanggal", $d->tanggal_pesan);
    $f->text("Nama Supplier", $d->nama);
    $f->execute();
    echo "</td><td align=right>";
    $f = new ReadOnlyForm();
    $f->text("Status dikirim", $d->kirim);
    $f->text("Status diterima", $d->terima);
    $f->text("Lengkap?", $d->lengkap);

    $f->execute();
    echo "</td></tr></table>";

    $t = new PgTable($con, "100%");
    $t->SQL = "select obat, satuan, qty, terima, lengkap, id as dummy ".
              "FROM rsv0032 ".
              "where rs00031_id = '".$_GET["v"]."'";
    $t->ColHeader = array("NAMA BARANG", "SATUAN", "QTY", "TERIMA?", "LENGKAP?", "&nbsp;");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[5] = "CENTER";
    $t->RowsPerPage = 100;
    $t->DisableStatusBar = true;
    $t->ColFormatHtml[4] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#4#>'>".icon("edit","Edit")."</A>";

    $t->execute();

    echo "<BR><DIV ALIGN=RIGHT><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>&#171; Tambah data Pesanan &#187;</A></DIV>";

}

else {
    // search box
    title("Data Master Pengadaan");
    title("Detil pengadaan tlh.dibuat table & view ->rs00032 & rsv0032");
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = "select no_referensi, tanggal_pesan, nama, ".
              "kirim,terima,lengkap, id as dummy FROM rsv0031 ".
              "where upper(nama) LIKE '%".strtoupper($_GET["search"])."%' ".
              "OR upper(no_referensi) LIKE '%".$_GET["search"]."%'".
              "OR upper(tanggal) LIKE '%".strtoupper($_GET["search"])."%'";
    $t->ColHeader = array("NO.BUKTI","TANGGAL","NAMA SUPPLIER", "TERKIRIM?", "DITERIMA?", "LENGKAP?", "&nbsp;");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[5] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[6] = "<nobr><A CLASS=TBL_HREF HREF='$SC?p=819&v=<#6#>'>".icon("view","View")."</A> &nbsp; ".
                           "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#6#>'>".icon("edit","Edit")."</A></nobr>";


    $t->execute();

    echo "<BR><DIV ALIGN=RIGHT><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>&#171; Data Pengadaan Baru &#187;</A></DIV>";
}
?>
