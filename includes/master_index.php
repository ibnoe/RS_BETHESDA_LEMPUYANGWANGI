<? // Agung Sunandar; lagi di bukittinggi ;)
   
$PID = "master_index";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

$r = pg_query($con,"select * from index_gaji where tt='".$_GET["tt"]."' and tc='000'");
$d = pg_fetch_object($r);
pg_free_result($r);


title("<img src='icon/informasi-2.gif' align='absmiddle' >  Tabel Master Index: ".$d->tdesc);
echo "<BR>";
if (empty($_GET[sure])) {
if(strlen($_GET["e"]) > 0 || strlen($_GET["tc"]) > 0) {
    if($_GET["e"] == "new") {
        if (strlen($_GET["tc"]) == 0) {
            $r8 = pg_query($con,"select max(tc) as tc from index_gaji where tt='".$_GET["tt"]."'");
            $d8 = pg_fetch_object($r8);
            pg_free_result($r8);
            $_GET["tc"] = str_pad(((int) $d8->tc) + 1, 3, "0", STR_PAD_LEFT);
        }
        $f = new Form("actions/master_index.insert.php");
        $f->hidden("tt",$_GET["tt"]);
        $f->text("tc","Kode",3,3,$_GET["tc"]);
    } else {
        $r2 = pg_query($con,
            "select * ".
            "from index_gaji ".
            "where tt='".$_GET["tt"]."' and tc='".$_GET["e"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
        $f = new Form("actions/master_index.update.php");
        $f->hidden("tt",$_GET["tt"]);
        $f->hidden("tc",$_GET["e"]);
        $f->text("tc","KODE",3,3,$_GET["e"],"DISABLED");
    }
    if(strlen($_GET["tdesc"]) > 0) {
        $f->text("tdesc","Nama ".$d->tdesc,50,200,$_GET["tdesc"]);
		$f->text("index","Index",50,200,$_GET["index"]);
    } else {
        $f->text("tdesc","Nama ".$d->tdesc,50,200,$d2->tdesc);
		$f->text("index","Index",50,200,$d2->index);
    }
    $f->submit(" Simpan ");
    $f->execute();
    echo "<br>";
    if(strlen($_GET["err"]) > 0) {
        errmsg("Terjadi Kesalahan", stripslashes($_GET["err"]));
    }
} else {
    // search box
    echo "<TABLE BORDER=0 WIDTH='100%'><FORM NAME=frm1 ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<TD><SELECT NAME=tt onChange='frm1.search.value=\"\";frm1.submit();'>";
    $r1 = pg_query($con, "select tt, tdesc from index_gaji where tc = '000' order by tdesc");
    while($d1 = pg_fetch_object($r1)) {
        if ($d1->tt == $_GET["tt"]) {
            echo "<option selected value='$d1->tt'>$d1->tdesc</option>";
        } else {
            echo "<option value='$d1->tt'>$d1->tdesc</option>";
        }
    }
    pg_free_result($r1);
    echo "</SELECT></TD>";
    echo "<TD WIDTH=1>Pencarian : <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

//    echo "<TD WIDTH=1><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
    echo "</TR></FORM></TABLE>";

    $t = new PgTable($con, "100%");
    $t->SQL = "select tdesc,index, tc as dummy from index_gaji where tt='".$_GET["tt"]."' and tc!='000'".
              "and (upper(tdesc) LIKE '%".strtoupper($_GET["search"])."%'".
              "OR upper(tc) LIKE '%".strtoupper($_GET["search"])."%') group by tdesc,index, tc order by index ";
    $t->setlocale("id_ID");    
    $t->ShowRowNumber = true;    
    $t->ColAlign[2] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&tt=$d->tt&e=<#2#>'>".
                        icon("edit","Edit")."</A>&nbsp;&nbsp;".
   
   "<A CLASS=TBL_HREF HREF='actions/master_index.delete.php?p=$PID&tt=$d->tt&tc=<#2#>'>".icon("delete","Hapus")."</A>"; 
   
    $t->ColHeader = array($d->tdesc, "I n d e x", "V i e w");
    $t->execute();

    echo "<BR><DIV ALIGN=LEFT><img src=\"icon/back.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&tt=".$_GET["tt"]."&e=new'>  Tambah Data $d->tdesc  </A></DIV>";
    
}
 }else{
	
	$data = getFromTable("select tdesc from index_gaji where tt='".$_GET[tt]."' and tc='".$_GET[tc]."'");

    echo "<div align=center>";
    echo "<form action='actions/master_index.delete.php' method='get'>";
    echo "<font color=red size=3>PERINGATAN !</font><br>";
    echo "<font class=SUB_MENU> $d->tdesc <font color=navy>'".$data."'</font> akan Dihapus.</font><br><br>";
    echo "<input type=hidden name=p value=$PID>";
    echo "<input type=hidden name=tt value=".$_GET[tt].">";
    echo "<input type=hidden name=tc value=".$_GET[tc].">";
    
    echo "<input type=submit name=sure value='YA'>&nbsp;";
    echo "<input type=submit name=sure value='TIDAK'>";
    echo "</form>";
    echo "</div>";
}   

?>
