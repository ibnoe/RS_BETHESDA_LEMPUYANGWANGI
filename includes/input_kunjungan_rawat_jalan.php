<? // 30/12/2003
   // sfdn, 20-04-2004
   // sfdn, 30-04-2004

$PID = "input_kunjungan_rawat_jalan";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (empty($_GET[sure])) {
if(isset($_GET["e"])) {
    if ($_GET["e"] != "new") {
        $r = pg_query($con, "select * from rl100003 where no = '".$_GET["e"]."' ");


    
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }

    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";



    if($n > 0) {
        $f = new Form("actions/kunjungan_rawat_jalan.update.php", "POST");
        title("Edit Laporan");
        $f->subtitle("Update Kunjungan Rawat Jalan");
        $f->hidden("no","$d->no");
        $f->text("no","NO",6,6,$d->no,"DISABLED");
    } else {
        $f = new Form("actions/kunjungan_rawat_jalan.insert.php");
        title("Edit Laporan");
        $f->subtitle("Tambah Data Kunjungan Rawat Jalan");
        $f->hidden("no","new");
        $f->text("no","No",12,12,"<OTOMATIS>","DISABLED");
    }
    $f->PgConn = $con;
    $f->text("f_jenis_layanan","Jenis Layanan",40,50,$d->jenis_layanan);

    $f->text("f_kunjungan_baru","Kunjungan Baru",50,50,$d->kunjungan_baru);
    $f->text("f_kunjungan_ulang","Kunjungan Ulang",30,30,$d->kunjungan_ulang);
     $f->submit(" Simpan ");
    $f->execute();
} else {
    // search box
    title("<img src='icon/icon-view.png' align='absmiddle' >  Edit Laporan Kunjungan Rawat Jalan");
lihat_laporan("kunjungan_rawat_jalan");
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
//    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
  //  echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
     echo "<TD >Pencarian:<INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

 
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = "select jenis_layanan, kunjungan_baru, kunjungan_ulang, no as href FROM rl100003 group by no,
  jenis_layanan ,
  kunjungan_baru ,
  kunjungan_ulang order by no";            
              
    $t->ColHeader = array("Jenis Pelayanan Rawat Inap", "Kunjungan Baru", "Kunjungan Ulang", "Edit");
    $t->ShowRowNumber = true;
 //   $t->ColAlign[5] = "CENTER";
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
        $t->ColAlign[3] = "CENTER";

    $t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#3#>'>".icon("edit","Edit")."</A>".
    						"<A CLASS=TBL_HREF HREF='".
            "actions/kunjungan_rawat_jalan.delete.php?p=$PID".
            "&e=<#3#>".
            "'>".icon("delete","Hapus")."</A>".
            "</nobr>"; 
    /*
    $t->ColFormatHtml[4] = "<nobr><A CLASS=TBL_HREF HREF=''>".icon("view","View")."</A> &nbsp; ".
                           "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#4#>'>".icon("edit","Edit")."</A></nobr>";
    */

    $t->execute();

    echo "<BR><DIV ALIGN=LEFT><img src=\"icon/user.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>Tambah Data </A></DIV>";
}
}else{
	$data = getFromTable("select jenis_layanan from rl100003 where no='".$_GET[e]."'");

    echo "<div align=center>";
    echo "<form action='actions/kunjungan_rawat_jalan.delete.php' method='get'>";
    echo "<font color=red size=3>PERINGATAN !</font><br>";
    echo "<font class=SUB_MENU>Data Laporan <font color=navy>'".$data."'</font> akan Dihapus.</font><br><br>";
    echo "<input type=hidden name=p value=$PID>";
    echo "<input type=hidden name=e value=".$_GET[e].">";
    
    echo "<input type=submit name=sure value='YA'>&nbsp;";
    echo "<input type=submit name=sure value='TIDAK'>";
    echo "</form>";
    echo "</div>";
}
?>
