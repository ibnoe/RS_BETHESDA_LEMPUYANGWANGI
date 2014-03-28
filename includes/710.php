<? // 30/12/2003

$PID = "710";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if(isset($_GET["e"])) {
    $r = pg_query($con, "select * from rs00002 where mr_no = '".$_GET["e"]."'");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);

    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";

    title("Edit Identitas Pasien");

    if($n > 0) {
        $f = new Form("actions/110.update.php", "POST");
        $f->subtitle("Identitas");
        $f->hidden("mr_no","$d->mr_no");
        $f->text("mr_no","MR No",12,8,$d->mr_no,"DISABLED");
    } else {
        $f = new Form("actions/110.insert.php");
        $f->subtitle("Identitas");
        $f->hidden("mr_no","new");
        $f->text("mr_no","MR No",12,12,"<OTOMATIS>","DISABLED");
    }
    $f->PgConn = $con;
    $f->text("f_nama","Nama",40,50,$d->nama);
    $f->text("f_nama_keluarga","Nama Keluarga",40,50,$d->nama_keluarga);
    $f->selectArray("f_jenis_kelamin", "Jenis Kelamin",
                    Array("L" => "Laki-laki", "P" => "Perempuan"),
                    $d->jenis_kelamin);
    $f->text("f_tmp_lahir","Tempat Lahir",40,40,$d->tmp_lahir);
    $f->selectDate("f_tgl_lahir", "Tanggal Lahir", pgsql2phpdate($d->tgl_lahir));
    $f->selectSQL("f_agama_id", "Agama",
                  "select tc, tdesc from rs00001 where tt = 'AGM' and tc != '000'",
                  $d->agama_id);

    $f->subtitle("Alamat Tetap");
    $f->text("f_alm_tetap","Alamat",50,50,$d->alm_tetap);
    $f->text("f_kota_tetap","Kota",50,50,$d->kota_tetap);
    $f->text("f_pos_tetap","Kode Pos",5,5,$d->pos_tetap);
    $f->text("f_tlp_tetap","Telepon",15,15,$d->tlp_tetap);

    $f->subtitle("Alamat Sementara");
    $f->text("f_alm_sementara","Alamat",50,50,$d->alm_sementara);
    $f->text("f_kota_sementara","Kota",50,50,$d->kota_sementara);
    $f->text("f_pos_sementara","Kode Pos",5,5,$d->pos_sementara);
    $f->text("f_tlp_sementara","Telepon",15,15,$d->tlp_sementara);

    $f->subtitle("Keluarga Dekat");
    $f->text("f_keluarga_dekat","Nama",50,50,$d->keluarga_dekat);
    $f->text("f_alm_keluarga","Alamat",50,50,$d->alm_keluarga);
    $f->text("f_kota_keluarga","Kota",50,50,$d->kota_keluarga);
    $f->text("f_pos_keluarga","Kode Pos",5,5,$d->pos_keluarga);
    $f->text("f_tlp_keluarga","Telepon",15,15,$d->tlp_keluarga);

    $f->submit(" Simpan ");
    $f->execute();
} else {
    // search box
    title("Info Pasien");
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = "select mr_no, nama, id,tanggal_reg_str, tipe_desc, rawatan FROM rsv0002 ".
              "where upper(nama) LIKE '%".strtoupper($_GET["search"])."%' ".
              "OR mr_no LIKE '%".$_GET["search"]."%'";
    $t->ColHeader = array("MR NO", "NAMA", "NO.REG","TGL.REGISTRASI", "TIPE PASIEN", "UNIT LAYANAN");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[5] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    /*
    $t->ColFormatHtml[4] = "<nobr><A CLASS=TBL_HREF HREF=''>".icon("view","View")."</A> &nbsp; ".
                          "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#4#>'>".icon("edit","Edit")."</A></nobr>";
    */

    $t->execute();
    /*
    echo "<BR><DIV ALIGN=RIGHT><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>&#171; Pasien Baru &#187;</A></DIV>";
    */

}
?>
