<? // 30/12/2003
   // sfdn, 20-04-2004
   // sfdn, 30-04-2004

$PID = "input_cara_pembayaran";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (empty($_GET[sure])) {
if(isset($_GET["e"])) {
    if ($_GET["e"] != "new") {
        $r = pg_query($con, "select * from rl100023 where id = '".$_GET["e"]."'");


    
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }

    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";



    if($n > 0) {
        $f = new Form("actions/cara.update.php", "POST");
        title("Edit Laporan");
        $f->subtitle("Update Cara Pembayaran");
        $f->hidden("id","$d->id");
        $f->text("id","NO",6,6,$d->id,"DISABLED");
    } else {
        $f = new Form("actions/cara.insert.php");
        title("Edit Laporan");
        $f->subtitle("Tambah Data Cara Pembayaran");
        $f->hidden("id","new");
        $f->text("id","NO",12,12,"<OTOMATIS>","DISABLED");
    }
    $f->PgConn = $con;
    $f->text("f_urutan","No Urut",40,50,$d->urutan);

    $f->text("f_cara","Cara Pembayaran",40,50,$d->cara);
    $f->text("f_a","Jumlah Pasien Rawat Inap Keluar",20,20,$d->a);
	$f->text("f_b","Jumlah Lama Dirawat Pasien Rawat Inap",20,20,$d->b);
	$f->text("f_c","Jumlah Pasien Rawat Jalan",20,20,$d->c);
	$f->text("f_d","Jumlah Pemeriksaan Laboratorium",20,20,$d->d);
	$f->text("f_e","Jumlah Pemeriksaan Radiologi",20,20,$d->e);
	$f->text("f_f","Lain-Lain",20,20,$d->f);
	$f->text("f_g","Total Pendapatan Seharusnya",20,20,$d->g);
	$f->text("f_h","Total Pendapatan Diterima",20,20,$d->h);
	
     $f->submit(" Simpan ");
    $f->execute();
} else {
    // search box
    title("<img src='icon/icon-view.png' align='absmiddle' >  Edit Laporan Cara Pembayaran");
lihat_laporan("cara_pembayaran");
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
//    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
  //  echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
     echo "<TD >Pencarian:<INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

 
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = "select urutan, cara, a, b, c, d, e, f, g, h, id as href FROM rl100023 order by id";            
              
    $t->ColHeader = array("No Urut", "Cara Pembayaran", "Jumlah Pasien Rawat Inap Keluar", "Jumlah Lama Dirawat Pasien Rawat Inap", "Jumlah Pasien Rawat Jalan" , "Jumlah Pemeriksaan Laboratorium", "Jumlah Pemeriksaan Radiologi", "Lain-Lain", "Total Pendapatan Seharusnya", "Total Pendapatan Diterima" ,"edit");
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
        $t->ColAlign[10] = "CENTER";

    $t->ColFormatHtml[10] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#10#>'>".icon("edit","Edit")."</A>".
    						"<A CLASS=TBL_HREF HREF='".
            "actions/cara.delete.php?p=$PID".
            "&e=<#10#>".
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
	$data = getFromTable("select cara from rl100023 where no='".$_GET[e]."'");

    echo "<div align=center>";
    echo "<form action='actions/cara.delete.php' method='get'>";
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
