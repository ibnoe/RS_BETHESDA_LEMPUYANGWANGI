<? // sfdn, 30-04-2004
   
$PID = "hrd_status";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

title("<img src='icon/informasi-2.gif' align='absmiddle' >  Tabel Master: Status Absensi");
if(strlen($_GET["e"]) > 0) {
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    if($_GET["e"] == "new") {
     
            $r8 = pg_query($con,"select max(code) as code from hrd_status");
            $d8 = pg_fetch_object($r8);
            pg_free_result($r8);
            $_GET["code"] = str_pad(((int) $d8->code) + 1, 3, "0", STR_PAD_LEFT);
        
        $f = new Form("actions/hrd_status.insert.php");
        title("Status absensi baru");
        echo "<BR>";
        $f->text("code","Kode",3,3,$_GET["code"],"DISABLED");
        $f->hidden("f_code",$_GET["code"]);
        $f->text("f_status","Status Absen ",30,30,"");
        
    } else {
        $r2 = pg_query($con,
            "select * ".
            "from hrd_status ".
            "where code='".$_GET["e"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
        $f = new Form("actions/hrd_status.update.php");
        $f->subtitle("Edit status Absensi");
        echo "<BR>";
        $f->hidden("code",$_GET["e"]);
        $f->textinfo("f_code","Kode",3,3,$_GET["e"],"","DISABLED");
        $f->text("f_status","Status Absensi",30,30,$d2->status);    
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
//   echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
//    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
     echo "<TD >Pencarian:<INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

    echo "</TR></FORM></TABLE></DIV>";
    
    $t = new PgTable($con, "100%");
    $t->SQL = 
        "select code, status,  code as dummy ".
        "from hrd_status ".
        "where ".
        "(upper(code) LIKE '%".strtoupper($_GET["search"])."%' ".
        "OR upper(status) LIKE '%".strtoupper($_GET["search"])."%' ".
        ")";
    if (!isset($_GET[sort])) {
        	$_GET[sort] = "code";
           	$_GET[order] = "asc";
		}
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#2#>'>".icon("edit","Edit")."</A>";
            //"<A CLASS=TBL_HREF HREF='actions/hrd_status.delete.php?p=$PID&code=<#2#>'>".icon("delete","Hapus")."</A>";;
    $t->ColHeader = array("KODE", "NAMA STATUS", "EDIT");
    
    $t->execute();
    //echo"<br><div class=NOTE>Catatan : Urutun untuk kode status absensi tidak boleh diubah </div><br>";
     //echo "<BR><DIV ALIGN=LEFT><img src=\"icon/user.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
       //  "HREF='index2.php?p=$PID&e=new'>Status Absen Baru </A></DIV>";
}
?>
