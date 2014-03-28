<? // sfdn, 30-04-2004
   
$PID = "hrd_kalendar";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

title("<img src='icon/rawat-inap-2.gif' align='absmiddle' >  Tabel Master: Master Kalendar");
if(strlen($_GET["e"]) > 0) {
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    if($_GET["e"] == "new") {
     
            $r8 = pg_query($con,"select max(code) as code from hrd_kalendar");
            $d8 = pg_fetch_object($r8);
            pg_free_result($r8);
            $_GET["code"] = str_pad(((int) $d8->code) + 1, 6, "0", STR_PAD_LEFT);
        
        $f = new Form("actions/hrd_kalendar.insert.php", "POST", "NAME=Form1");
        title("Event Baru");
        echo "<BR>";
        //$f->text("code","Kode",6,6,$_GET["code"],"DISABLED");
        $f->hidden("f_code",$_GET["code"]);
        $f->calendar("f_tanggal","Tanggal",15,15,date("d-m-Y", time()),"Form1","icon/calendar.gif","Pilih Tanggal",$ext);
        $f->text("f_event","Nama Event ",50,50,"");
        $f->selectArray("f_libur", "Libur",Array("Y" => "Ya", "T" => "Tidak"), "T" );
    }

    $f->submit(" Simpan ");
    $f->execute();
    echo "<br>";
    if(strlen($_GET["err"]) > 0) {
        errmsg("Terjadi Kesalahan", stripslashes($_GET["err"]));
    }
} else {
    echo "<BR>";
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
     echo "<TD >Pencarian:<INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

    echo "</TR></FORM></TABLE></DIV>";
    
    $t = new PgTable($con, "100%");
    $t->SQL = 
        "select tanggal, event, ".
        "case when libur = 'Y' then 'Libur' ".
        "     else 'Tidak Libur' end , code as dummy ".
        "from hrd_kalendar ".
        "where ".
        "((tanggal) LIKE '%".($_GET["search"])."%' ".
        "OR upper(event) LIKE '%".strtoupper($_GET["search"])."%' ".
        ")";
    //$t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[3] = "CENTER";
    $t->ColFormatHtml[3] = //"<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#4#>'>".icon("edit","Edit")."</A>".
            "<A CLASS=TBL_HREF HREF='actions/hrd_kalendar.delete.php?p=$PID&code=<#3#>'>".icon("delete","Hapus")."</A>";;
    $t->ColHeader = array("TANGGAL", "NAMA EVENT", "LIBUR","HAPUS");
    
    $t->execute();
     echo "<BR><DIV ALIGN=LEFT><img src=\"icon/user.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>Tambah Event Baru </A></DIV>";
}
?>
