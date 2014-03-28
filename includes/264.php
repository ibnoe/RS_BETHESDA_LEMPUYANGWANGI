<? // 30/12/2003
   // sfdn, 20-04-2004
   // sfdn, 30-04-2004
   // sfdn, 05-06-2004

$PID = "264";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if(isset($_GET["e"])) {
    if ($_GET["e"] != "new") {
        $r = pg_query($con, "select * from rs00043 where id = '".$_GET["e"]."'");

    /*$r = pg_query($con, "select * from rs00028");*/
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    if($n > 0) {
        $f = new Form("actions/264.update.php", "POST");
        title("Edit Daftar Menu Makanan");
        $f->hidden("id","$d->id");
		$f->hidden("f_tt","DFM");
        $f->text("id","Kode Data",10,10,$d->id,"DISABLED");
    } else {
        $f = new Form("actions/264.insert.php");
        title("Data Daftar Menu Makanan Baru");
        $f->hidden("id","new");
		$f->hidden("f_tt","DFM");
		$f->text("id","Kode Data",10,10,"<OTOMATIS>","DISABLED");		
    }
    $f->PgConn = $con;
    $f->text("f_nama_menu","Nama Menu Makanan",30,30,$d->nama_menu);
    $f->selectSQL("f_kode_satuan", "Satuan",
				  "select '' as tc, '' as tdesc union ".
                  "select tc, tdesc ".
				  "from rs00001 where tt = 'SAT' and tc IN ('049','050')",
                  $d->kode_satuan);
	$f->submit(" Simpan ");
    $f->execute();
} else {
    // search box
    title("Daftar Menu Makanan");

    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = 
			"select a.nama_menu, b.tdesc as satuan, a.id as dummy ".
			"FROM rs00043 a ".
			"	left join rs00001 b ON a.kode_satuan = b.tc and b.tt='SAT' ".
			"where a.tt='DFM'";
			
    $t->ColHeader = array("NAMA MENU MAKANAN", "SATUAN", "E d i t");
    $t->ShowRowNumber = true;
    $t->ColAlign[2] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#2#>'>".icon("edit","Edit")."</A>";
    /*
    $t->ColFormatHtml[4] = "<nobr><A CLASS=TBL_HREF HREF=''>".icon("view","View")."</A> &nbsp; ".
                           "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#4#>'>".icon("edit","Edit")."</A></nobr>";
    */

    $t->execute();

    echo "<BR><DIV ALIGN=RIGHT><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>&#171; Menu Makanan Baru &#187;</A></DIV>";
}
?>
