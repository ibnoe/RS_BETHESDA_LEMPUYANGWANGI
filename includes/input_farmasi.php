<? // 30/12/2003
   // sfdn, 20-04-2004
   // sfdn, 30-04-2004

$PID = "input_farmasi";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (empty($_GET[sure])) {
if(isset($_GET["e"])) {
    if ($_GET["e"] != "new") {
        $r = pg_query($con, "select * from rl100012a where id = '".$_GET["e"]."'");


    
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }

    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";



    if($n > 0) {
        $f = new Form("actions/farmasi.update.php", "POST");
        title("Edit Laporan");
        $f->subtitle("Update Pengadaan Obat");
        $f->hidden("id","$d->id");
        $f->text("id","NO",6,6,$d->id,"DISABLED");
    } else {
        $f = new Form("actions/farmasi.insert.php");
        title("Edit Laporan");
        $f->subtitle("Tambah Data Pengadaan Obat");
        $f->hidden("id","new");
        $f->text("id","No",12,12,"<OTOMATIS>","DISABLED");
    }
    $f->PgConn = $con;
    $f->text("f_gol_obat","Golongan Obat",40,50,$d->gol_obat);
    $f->text("f_jml_obat","Jumlah Obat Sesuai Kebutuhan",30,30,$d->jml_obat);
	$f->text("f_jml_item","Jumlah Item yang tersedia",30,30,$d->jml_item);
	$f->text("f_sedia","Persen Ketersediaan",30,30,$d->sedia);
	$f->text("f_ket","Keterangan",30,30,$d->ket);
	$f->submit(" Simpan ");
    $f->execute();
} else {
    // search box
    title("<img src='icon/icon-view.png' align='absmiddle' >  Edit Laporan Pengadaan Obat");
lihat_laporan("kegiatan_farmasi");
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
//    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
  //  echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
     echo "<TD >Pencarian:<INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

 
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = "select gol_obat, jml_obat, jml_item, sedia, ket,id as href FROM rl100012a order by id";            
              
    $t->ColHeader = array("Golongan Obat", "Jumlah Obat Sesuai Kebutuhan","Jumlah Item yang tersedia","Persen Ketersediaan","Keterangan", "Edit");
    $t->ShowRowNumber = true;
 
    $t->RowsPerPage = $ROWS_PER_PAGE;
   
        $t->ColAlign[5] = "CENTER";

    $t->ColFormatHtml[5] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#5#>'>".icon("edit","Edit")."</A>".
    						"<A CLASS=TBL_HREF HREF='".
            "actions/farmasi.delete.php?p=$PID".
            "&e=<#5#>".
            "'>".icon("delete","Hapus")."</A>".
   

    $t->execute();

    echo "<BR><DIV ALIGN=LEFT><img src=\"icon/user.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>Tambah Data </A></DIV>";
}
}else{
	$data = getFromTable("select gol_obat from rl100012a where id='".$_GET[e]."'");

    echo "<div align=center>";
    echo "<form action='actions/farmasi.delete.php' method='get'>";
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
