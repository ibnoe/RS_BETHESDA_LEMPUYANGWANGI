<? // 30/12/2003
   // sfdn, 20-04-2004
   // sfdn, 30-04-2004

$PID = "818";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (empty($_GET[sure])) {
if(isset($_GET["e"])) {
    if ($_GET["e"] != "new") {
        $r = pg_query($con, "select * from rs00028 where id = '".$_GET["e"]."'");

    /*$r = pg_query($con, "select * from rs00028");*/
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }

    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";



    if($n > 0) {
        $f = new Form("actions/818.update.php", "POST");
        title("Edit Data Supplier");
        $f->subtitle("Identitas Supplier");
        $f->hidden("id","$d->id");
        $f->text("id","Kode suppl.",6,6,$d->id,"DISABLED");
    } else {
        $f = new Form("actions/818.insert.php");
        title("Data Supplier Baru");
        $f->subtitle("Identitas Supplier");
        $f->hidden("id","new");
        $f->text("id","Kode suppl.",12,12,"<OTOMATIS>","DISABLED");
    }
    $f->PgConn = $con;
    $f->text("f_nama","Nama",40,50,$d->nama);

    /*
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
    */

    $f->text("f_alamat_jln1","Alamat",50,50,$d->alamat_jln1);
    $f->text("f_alamat_kota","Kota",30,30,$d->alamat_kota);
    $f->text("f_kode_pos","Kode Pos",5,5,$d->kode_pos);
    $f->text("f_telepon","Telepon",15,15,$d->telepon);
    $f->text("f_contact_person","C/P",50,50,$d->contact_person);
    $f->text("f_npwp","N P W P",50,50,$d->npwp);

    /*
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
    */

    $f->submit(" Simpan ");
    $f->execute();
} else {
    // search box
    title("<img src='icon/informasi-2.gif' align='absmiddle' >  DATA SUPPLIER");

    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
     echo "<TD >Pencarian:<INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

 
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = "select nama, alamat_jln1, alamat_kota, contact_person, id as href FROM rs00028 ".
              "where upper(nama) LIKE '%".strtoupper($_GET["search"])."%' ".
              "OR alamat_jln1 LIKE '%".$_GET["search"]."%'".
              "OR upper(contact_person) LIKE '%".strtoupper($_GET["search"])."%' order by nama";
    $t->ColHeader = array("NAMA SUPPLIER", "ALAMAT", "KOTA", "C/P", "V i e w");
    $t->ShowRowNumber = true;
    $t->ColAlign[5] = "CENTER";
    /*
    $t->columnSort(1, "nama");
    $t->columnSort(2, "nama", true);
    $t->columnSort(3, "nama_keluarga");
    $t->columnSort(4, "alm_tetap");
    $t->columnSort(5, "kota_tetap");
    */
    $t->RowsPerPage = $ROWS_PER_PAGE;
    /*
    $t->Filter = "upper(nama) LIKE '%".strtoupper($_GET["search"])."%' ".
                 "OR mr_no LIKE '%".$_GET["search"]."%'";
    */
        $t->ColAlign[4] = "CENTER";

    $t->ColFormatHtml[4] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#4#>'>".icon("edit","Edit")."</A>".
    						"<A CLASS=TBL_HREF HREF='".
            "actions/818.delete.php?p=$PID".
            "&e=<#4#>".
            "'>".icon("delete","Hapus")."</A>".
            "</nobr>"; 
    /*
    $t->ColFormatHtml[4] = "<nobr><A CLASS=TBL_HREF HREF=''>".icon("view","View")."</A> &nbsp; ".
                           "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#4#>'>".icon("edit","Edit")."</A></nobr>";
    */

    $t->execute();

    echo "<BR><DIV ALIGN=LEFT><img src=\"icon/user.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>Supplier Baru </A></DIV>";
}
}else{
	$data = getFromTable("select nama from rs00028 where id='".$_GET[e]."'");

    echo "<div align=center>";
    echo "<form action='actions/818.delete.php' method='get'>";
    echo "<font color=red size=3>PERINGATAN !</font><br>";
    echo "<font class=SUB_MENU>Master Supplier <font color=navy>'".$data."'</font> akan Dihapus.</font><br><br>";
    echo "<input type=hidden name=p value=$PID>";
    echo "<input type=hidden name=e value=".$_GET[e].">";
    
    echo "<input type=submit name=sure value='YA'>&nbsp;";
    echo "<input type=submit name=sure value='TIDAK'>";
    echo "</form>";
    echo "</div>";
}
?>
