<? // 30/12/2003
   // sfdn, 20-04-2004
   // sfdn, 30-04-2004

$PID = "input_pembedahan";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (empty($_GET[sure])) {
if(isset($_GET["e"])) {
    if ($_GET["e"] != "new") {
        $r = pg_query($con, "select * from rl100005 where id = '".$_GET["e"]."'");


    
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }

    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";



    if($n > 0) {
        $f = new Form("actions/pembedahan.update.php", "POST");
        title("Edit Laporan");
        $f->subtitle("Update Pembedahan");
        $f->hidden("id","$d->id");
        $f->text("id","NO",6,6,$d->id,"DISABLED");
    } else {
        $f = new Form("actions/pembedahan.insert.php");
        title("Edit Laporan");
        $f->subtitle("Tambah Data Pembedahan");
        $f->hidden("id","new");
        $f->text("id","No",12,12,"<OTOMATIS>","DISABLED");
    }
    $f->PgConn = $con;
    $f->text("f_spesialisasi","Spesialisasi",40,50,$d->spesialisasi);
	
//    $f->text("f_total","Total",50,50,$d->total);
	
	$f->text("f_total","Total",30,30,$d->total);
	$f->text("f_khusus_kamar_bedah","Kamar Bedah Khusus",30,30,$d->khusus_kamar_bedah);
	$f->text("f_khusus_unit_darurat","Unit Darurat Khusus",30,30,$d->khusus_unit_darurat);
	$f->text("f_besar_kamar_bedah","Kamar Bedah Besar",30,30,$d->besar_kamar_bedah);
	$f->text("f_besar_unit_darurat","Unit Darurat Besar",30,30,$d->besar_unit_darurat);
	$f->text("f_sedang_kamar_bedah","Kamar Bedah Sedang",30,30,$d->sedang_kamar_bedah);
	$f->text("f_sedang_unit_darurat","Unit Darurat Sedang",30,30,$d->sedang_unit_darurat);
	$f->text("f_kecil_kamar_bedah","Kamar Bedah Kecil",30,30,$d->kecil_kamar_bedah);
	$f->text("f_kecil_unit_darurat","Unit Darurat Kecil",30,30,$d->kecil_unit_darurat);
	$f->text("f_kecil_poli","Poliklinik",30,30,$d->kecil_poli);
     $f->submit(" Simpan ");
    $f->execute();
} else {
    // search box
    title("<img src='icon/icon-view.png' align='absmiddle' >  Edit Laporan Pembedahan");
lihat_laporan("pembedahan");
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
//    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
  //  echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
     echo "<TD >Pencarian:<INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

 
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = "select spesialisasi, total, khusus_kamar_bedah, khusus_unit_darurat, besar_kamar_bedah, besar_unit_darurat, sedang_kamar_bedah, sedang_unit_darurat, kecil_kamar_bedah, kecil_unit_darurat, kecil_poli, id as href FROM rl100005 order by id";            
              
    $t->ColHeader = array("Spesialisasi", "Total", "Kamar Bedah Khusus", "Unit Darurat Khusus", "Kamar Bedah Besar", "Unit Darurat Besar", "Kamar Bedah Sedang", "Unit Darurat Sedang","Kamar Bedah Kecil", "Unit Darurat Kecil", "Poliklinik","Edit");
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
        $t->ColAlign[11] = "CENTER";

    $t->ColFormatHtml[11] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#11#>'>".icon("edit","Edit")."</A>".
    						"<A CLASS=TBL_HREF HREF='".
            "actions/pembedahan.delete.php?p=$PID".
            "&e=<#11#>".
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
	$data = getFromTable("select spesialisasi from rl100005 where id='".$_GET[e]."'");

    echo "<div align=center>";
    echo "<form action='actions/pembedahan.delete.php' method='get'>";
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
