<? // 30/12/2003
   // sfdn, 20-04-2004
   // sfdn, 30-04-2004

$PID = "input_kegiatan_kb";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (empty($_GET[sure])) {
if(isset($_GET["e"])) {
    if ($_GET["e"] != "new") {
        $r = pg_query($con, "select * from rl100014 where id = '".$_GET["e"]."'");


    
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }

    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";



    if($n > 0) {
        $f = new Form("actions/kegiatan_kb.update.php", "POST");
        title("Edit Laporan");
        $f->subtitle("Update Keluarga Berencana");
        $f->hidden("id","$d->id");
        $f->text("id","NO",6,6,$d->id,"DISABLED");
    } else {
        $f = new Form("actions/kegiatan_kb.insert.php");
        title("Edit Laporan");
        $f->subtitle("Tambah Data Keluarga Berencana");
        $f->hidden("id","new");
        $f->text("id","No",12,12,"<OTOMATIS>","DISABLED");
    }
    $f->PgConn = $con;
    $f->text("f_metoda","Metoda",40,50,$d->metoda);	
   $f->text("f_bukan_rujuk","Bukan Rujukan",30,30,$d->bukan_rujuk);
	$f->text("f_ruj_rawat_inap","Rujukan Rawat Inap",30,30,$d->ruj_rawat_inap);
	$f->text("f_ruj_rawat_jalan","Rujukan Rawat Jalan",30,30,$d->ruj_rawat_jalan);
	$f->text("f_kunjungan_ulang","Kunjungan Ulang",30,30,$d->kunjungan_ulang);
	$f->text("f_jumlah","Jumlah",30,30,$d->jumlah);
	$f->text("f_dirujuk_keatas","Dirujuk Keatas",30,30,$d->dirujuk_keatas);	
     $f->submit(" Simpan ");
    $f->execute();
} else {
    // search box
    title("<img src='icon/icon-view.png' align='absmiddle' >  Edit Laporan Kegiatan KB");
lihat_laporan("kegiatan_kb");
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
//    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
  //  echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
     echo "<TD >Pencarian:<INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

 
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = "select metoda, bukan_rujuk, ruj_rawat_inap, ruj_rawat_jalan, kunjungan_ulang, jumlah, dirujuk_keatas , id as href FROM rl100014 order by id";            
              
    $t->ColHeader = array("Metoda", "Bukan Rujukan", "Rujukan Rawat Inap", "Rujukan Rawat Jalan", "Kunjungan Ulang", "Jumlah", "Dirujuk Keatas","Edit");
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
        $t->ColAlign[7] = "CENTER";

    $t->ColFormatHtml[7] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#7#>'>".icon("edit","Edit")."</A>".
    						"<A CLASS=TBL_HREF HREF='".
            "actions/kegiatan_kb.delete.php?p=$PID".
            "&e=<#7#>".
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
	$data = getFromTable("select metoda from rl100014 where id='".$_GET[e]."'");

    echo "<div align=center>";
    echo "<form action='actions/kegiatan_kb.delete.php' method='get'>";
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
