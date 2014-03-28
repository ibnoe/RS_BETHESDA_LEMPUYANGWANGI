<? // 30/12/2003
   // sfdn, 20-04-2004
   // sfdn, 30-04-2004

$PID = "263";
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
        $f = new Form("actions/263.update.php", "POST");
        title("Edit Data Periode Menu Makanan");
        $f->hidden("id","$d->id");
		$f->hidden("f_tt","PRM");
        $f->text("id","Kode Data",6,6,$d->id,"DISABLED");
    } else {
        $f = new Form("actions/263.insert.php");
        title("Data Periode Menu Makanan Baru");
        $f->hidden("id","new");
		$f->hidden("f_tt","PRM");
		$f->text("id","Kode Data",12,12,"<OTOMATIS>","DISABLED");		
    }
    $f->PgConn = $con;
    $f->text("f_day1","Tanggal awal",4,4,$d->day1);
	$f->text("f_day2","S/D",4,4,$d->day2);
    $f->submit(" Simpan ");
    $f->execute();
} else {
    // search box
    title("Periode/Tanggal Menu Makanan");

    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = 
			"select day1, day2, id as dummy ".
			"FROM rs00043 ".
			"where tt='PRM'";
			
    $t->ColHeader = array("TANGGAL 1", "TANGGAL AKHIR", "E d i t");
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
         "HREF='index2.php?p=$PID&e=new'>&#171; Periode Baru &#187;</A></DIV>";
}
?>
