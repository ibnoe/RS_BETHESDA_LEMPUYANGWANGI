<? // Nugraha, 17/02/2004
   // Pur, 27/02/2004
   // Pur, 09/03/2004: new libs table
   // sfdn, 30-04-2004
   
$PID = "803";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

$r = pg_query($con,"select * from rs00010");
$d = pg_fetch_object($r);
pg_free_result($r);


if(strlen($_GET["e"]) > 0) {
    if($_GET["e"] == "new") {
        $f = new Form("actions/803.insert.php");
        title("Bangsal Baru");
        echo "<BR>";
        $f->text("id","ID",12,12,"<OTOMATIS>","DISABLED");
        $f->text("f_bangsal","BANGSAL",50,50,"");
    } else {
        $r2 = pg_query($con,
            "select * ".
            "from rs00010 ".
            "where id='".$_GET["e"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
        $f = new Form("actions/803.update.php");
        title("Edit Bangsal");
        echo "<BR>";
        $f->hidden("id",$_GET["e"]);
        $f->text("id","ID",3,3,$_GET["e"],"DISABLED");
        $f->text("f_bangsal","BANGSAL",50,50,$d2->bangsal);
    }
    
    $f->submit(" Simpan ");
    $f->execute();
    echo "<br>";
    if(strlen($_GET["err"]) > 0) {
        errmsg("Terjadi Kesalahan", stripslashes($_GET["err"]));
    }
} else {

    title("Tabel Master: BANGSAL");
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
    echo "</TR></FORM></TABLE></DIV>";
    
    $t = new PgTable($con, "100%");
    $t->SQL = 
        "select bangsal, id as dummy from rs00010 ".
        "where ".
        "(id LIKE '%".strtoupper($_GET["search"])."%' ".
        "OR upper(bangsal) LIKE '%".strtoupper($_GET["search"])."%')";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[1] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[1] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#1#>'>".icon("edit","Edit")."</A>";
    $t->ColHeader = array("BANGSAL", "E d i t");
    
    $t->execute();

    echo "<BR><DIV ALIGN=RIGHT><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>&#171; Tambah Data Bangsal &#187;</A></DIV>";
}
?>
