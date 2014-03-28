<? // Nugraha, 17/02/2004
   // Pur, 27/02/2004

$PID = "806";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

$r = pg_query($con,"select * from rs00014");
$d = pg_fetch_object($r);
pg_free_result($r);


if(strlen($_GET["e"]) > 0) {
    if($_GET["e"] == "new") {
        $f = new Form("actions/806.insert.php");
        title("Sub Kategori Obat Baru");
        echo "<BR>";
        $f->text("id","ID",12,12,"<OTOMATIS>","DISABLED");
    } else {
        $r2 = pg_query($con,
            "select * ".
            "from rs00014 ".
            "where id='".$_GET["e"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
        $f = new Form("actions/806.update.php");
        title("Edit Sub Kategori Obat");
        echo "<BR>";
        $f->hidden("id",$_GET["e"]);
        $f->text("id","ID",4,4,$_GET["e"],"DISABLED");
    }
    $f->PgConn = $con;
    $f->text("f_sub_kategori","Sub Kategori Obat",40,50,$d2->sub_kategori);
    $f->selectSQL("f_kategori_id", "Kategori",
                  "select id, kategori from rs00013",
                  $d2->kategori_id);
    $f->submit(" Simpan ");
    $f->execute();
    echo "<br>";
    if(strlen($_GET["err"]) > 0) {
        errmsg("Terjadi Kesalahan", stripslashes($_GET["err"]));
    }
} else {
    title("Tabel Master: Sub Kategori Obat");
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
    echo "</TR></FORM></TABLE></DIV>";
$t = new PgTable($con, "100%");
    $t->SQL = 
        "select rs00014.id, rs00014.sub_kategori, rs00013.kategori, rs00014.id as dummy from rs00013, rs00014 where rs00014.kategori_id = rs00013.id ".
        "and ".
        "(rs00014.id LIKE '%".strtoupper($_GET["search"])."%' ".
        "OR rs00014.sub_kategori LIKE '%".strtoupper($_GET["search"])."%')";
    $t->setlocale("id_ID");    
    $t->ShowRowNumber = false;    
    $t->RowsPerPage = 14;
    $t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#3#>'>".icon("edit","Edit")."</A>";
    $t->ColHeader = array("ID", "SUB KATEGORI OBAT", "KATEGORI OBAT", "&nbsp;");
    $t->execute();
    echo "<BR><DIV ALIGN=RIGHT><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>&#171; Kategori Obat Baru &#187;</A></DIV>";
    
}
    
?>