<? // sfdn, 30-04-2004
   
$PID = "808";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

title("<img src='icon/informasi-2.gif' align='absmiddle' >  Tabel Master: ICD");
if(strlen($_GET["e"]) > 0) {
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    if($_GET["e"] == "new") {
        $f = new Form("actions/808.insert.php");
        title("Data ICD Baru");
        echo "<BR>";
        $f->text("f_diagnosis_code","Kode ICD",12,12,"","");
        $f->text("f_description","Nama ICD ",50,50,"");
 
        
    }elseif($_GET["e"] == "new1") {
        $f = new Form("actions/808.insert.php");
        title("Data ICD Baru");
        echo "<BR>";
        $f->text("f_diagnosis_code","Kode Kategori ICD",12,12,"","");
        $f->text("f_description","Nama Kategori ICD ",50,50,"");
		$f->hidden("f_sub_level","3");
        echo "<table><tr><td> Contoh untuk Kode </td><td>: </td><td> A99.- </td></tr></table>";
		//echo "<tr><td> </td><td>: </td><td> A900.- </td></tr>";
    } else {
        $r2 = pg_query($con,
            "select * ".
            "from rs00019 ".
            "where diagnosis_code='".$_GET["e"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
        $f = new Form("actions/808.update.php");
        $f->subtitle("Edit Data ICD");
        echo "<BR>";
        $f->hidden("diagnosis_code",$_GET["e"]);
        $f->text("f_diagnosis_code","Kode ICD",6,6,$_GET["e"],"DISABLED");
        $f->text("f_description","Nama ICD",50,50,$d2->description);
        

         
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
        "select diagnosis_code, description, category,diagnosis_code as dummy ".
        "from rsv0005 ".
        "where ".
        "(upper(diagnosis_code) LIKE '%".strtoupper($_GET["search"])."%' ".
        "OR upper(description) LIKE '%".strtoupper($_GET["search"])."%' ".
        "OR upper(category) LIKE '%".strtoupper($_GET["search"])."%')";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[3] = "CENTER";
    $t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#3#>'>".icon("edit","Edit")."</A>";
    $t->ColHeader = array("KODE DIAGNOSA", "DIAGNOSA", "KATEGORI","E d i t");
    
    $t->execute();
    echo "<BR><DIV ALIGN=LEFT><img src=\"icon/user.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new1'>Kategori ICD Baru </A></DIV>";
	echo "<BR><DIV ALIGN=LEFT><img src=\"icon/user.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>Diagnosa ICD Baru </A></DIV>";
}
?>