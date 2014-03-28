<? // 30/12/2003
   // sfdn, 20-04-2004
   // sfdn, 30-04-2004

$PID = "input_rl2b";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (empty($_GET[sure])) {
if(isset($_GET["e"])) {
    if ($_GET["e"] != "new") {
        $r = pg_query($con, "select * from rl200002b where id = '".$_GET["e"]."'");


    
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }

    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";



    if($n > 0) {
        $f = new Form("actions/rl2b.update.php", "POST");
        title("Edit Laporan");
        $f->subtitle("Update Laporan RL2b");
        $f->hidden("id","$d->id");
        $f->text("id","NO",6,6,$d->id,"DISABLED");
    } else {
        $f = new Form("actions/rl2b.insert.php");
        title("Edit Laporan");
        $f->subtitle("Tambah Data Laporan RL2b");
        $f->hidden("id","new");
        $f->text("id","NO",12,12,"<OTOMATIS>","DISABLED");
    }
    $f->PgConn = $con;
    $f->text("f_no_dtd","NO DTD",40,50,$d->no_dtd);
	$f->text("f_no_daftar","NO DAFTAR TERPERINCI",40,50,$d->no_daftar);
	$f->text("f_golongan","GOLONGAN SEBAB-SEBAB SAKIT",100,100,$d->golongan);
    $f->text("f_a","5",20,20,$d->a);
	$f->text("f_b","6",20,20,$d->b);
	$f->text("f_c","7",20,20,$d->c);
	$f->text("f_d","8",20,20,$d->d);
	$f->text("f_e","9",20,20,$d->e);
	$f->text("f_f","10",20,20,$d->f);
	$f->text("f_g","11",20,20,$d->g);
	$f->text("f_h","12",20,20,$d->h);
	$f->text("f_i","13",20,20,$d->i);
	$f->text("f_j","14",20,20,$d->j);
	$f->text("f_k","15",20,20,$d->k);
	$f->text("f_l","16",20,20,$d->l);
	
	
     $f->submit(" Simpan ");
    $f->execute();
} else {
    // search box
    title("<img src='icon/icon-view.png' align='absmiddle' >  Edit Laporan RL2b");
lihat_laporan("laporan_rl2b");
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
//    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
  //  echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
     echo "<TD >Pencarian:<INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

 
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = "select no_dtd, no_daftar, golongan, a, b, c, d, e, f, g, h,i,j,k,l, id as href FROM rl200002b order by no_dtd";            
              
    $t->ColHeader = array("NO DTD", "NO DAFTAR TERPERINCI", "GOLONGAN SEBAB-SEBAB SAKIT", "5", "6" , "7", "8", "9", "10", "11" ,"12","13","14","15","16","edit");
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
        $t->ColAlign[15] = "CENTER";

    $t->ColFormatHtml[15] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#15#>'>".icon("edit","Edit")."</A>".
    						"<A CLASS=TBL_HREF HREF='".
            "actions/rl2b.delete.php?p=$PID".
            "&e=<#15#>".
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
	$data = getFromTable("select golongan from rl200002b where no='".$_GET[e]."'");

    echo "<div align=center>";
    echo "<form action='actions/rl2b.delete.php' method='get'>";
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
