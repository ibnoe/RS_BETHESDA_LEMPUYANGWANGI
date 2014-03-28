<? // sfdn, 30-04-2004
   
$PID = "master_karcis";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

title("<img src='icon/informasi-2.gif' align='absmiddle' >  MASTER KARCIS");
if(strlen($_GET["e"]) > 0) {
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    if($_GET["e"] == "new") {
     
            $r8 = pg_query($con,"select max(id) as id from master_karcis");
            $d8 = pg_fetch_object($r8);
            pg_free_result($r8);
            $_GET["id"] = str_pad(((int) $d8->id) + 1, 3, "0", STR_PAD_LEFT);
        
        $f = new Form("actions/master_karcis.insert.php");
        title("Data baru");
        echo "<BR>";
        $f->PgConn = $con;
        $f->text("id","Kode",3,3,$_GET["id"],"DISABLED");
        $f->hidden("f_id",$_GET["id"]);
        $f->text("f_code","Nama Layanan ",30,30,"");
        $f->selectSQL("f_jmk", "Jenis Master Karcis","select tc, tdesc from rs00001 where tt = 'JMK' and tc != '000'","");
        $f->text("f_harga","Harga ",15,15,"0");
        
    } else {
        $r2 = pg_query($con,
            "select * ".
            "from master_karcis ".
            "where id='".$_GET["e"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
        $f = new Form("actions/master_karcis.update.php");
        $f->subtitle("Edit Master Karcis");
        echo "<BR>";
        $f->PgConn = $con;
        $f->hidden("f_id",$_GET["e"]);
        $f->text("id","Kode",3,3,$_GET["e"],"","DISABLED");
        $f->text("f_code","Nama Layanan",30,30,$d2->code);
        $f->selectSQL("f_jmk", "Jenis Master Karcis","select tc, tdesc from rs00001 where tt = 'JMK' and tc != '000'", $d2->jmk);
        $f->text("f_harga","Harga ",15,15,$d2->harga);
         
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
        "select a.code, b.tdesc, a.harga, a.id as dummy ".
        "from master_karcis a ".
        "left join rs00001 b ON b.tt = 'JMK' and b.tc = a.jmk ".
        "where ".
        "(upper(code) LIKE '%".strtoupper($_GET["search"])."%' ".
        ")";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[3] = "CENTER";
    $t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#3#>'>".icon("edit","Edit")."</A>".
            "<A CLASS=TBL_HREF HREF='actions/master_karcis.delete.php?p=$PID&id=<#3#>'>".icon("delete","Hapus")."</A>";;
    $t->ColHeader = array("LAYANAN","KARCIS", "HARGA","");
    
    $t->execute();
     echo "<BR><DIV ALIGN=LEFT><img src=\"icon/user.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>Karcis Baru </A></DIV>";
}
?>
