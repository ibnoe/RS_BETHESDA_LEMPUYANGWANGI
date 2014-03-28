<? // Nugraha, 22/02/2004
   // Pur, 09/03/2004: new libs table
   // sfdn, 30-04-2004

$PID = "810";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (empty($_GET[sure])) {
if(isset($_GET["e"])) {
    $r = pg_query($con, "select * from rs00018 where id = '".$_GET["e"]."'");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";

    if($n > 0) {
    title("Edit Jabatan Medis Fungsional");        
        $f = new Form("actions/810.update.php", "POST");
        $f->hidden("id",$d->id);
        $f->text("id","KODE",3,3,$d->id,"DISABLED");
    } else {
    title("Jabatan Medis Fungsional Baru");    
        $f = new Form("actions/810.insert.php");
        $f->hidden("id","new");
        $f->text("id","KODE",12,12,"&lt;OTOMATIS&gt;","DISABLED");
    }    
    $f->PgConn = $con;
    $f->selectSQL("f_unit_medis_fungsional_id", "Unit Medis Fungsional",
                  "select tc, tdesc from rs00001 where tt = 'PEG' and tc != '000'",
                  $d->unit_medis_fungsional_id);
    $f->text("f_jabatan_medis_fungsional","Jabatan Medis Fungsional",50,50,$d->jabatan_medis_fungsional);
    $f->submit(" Simpan ");
    $f->execute();
} else {
    title("<img src='icon/informasi-2.gif' align='absmiddle' >  Tabel Master : JABATAN MEDIS FUNGSIONAL");
    
    // search box
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<TD>Pencarian : <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
  //  echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = 
        "select b.tdesc, a.jabatan_medis_fungsional, a.id as dummy ".
        "from rs00018 a ".
        "join rs00001 b on a.unit_medis_fungsional_id = b.tc ".
        "where tt = 'PEG'".
        "and ".
        "(upper(b.tdesc) LIKE '%".strtoupper($_GET["search"])."%'".
        "OR upper(a.jabatan_medis_fungsional) LIKE '%".strtoupper($_GET["search"])."%')";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[2] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#2#>'>".icon("edit","Edit")."&nbsp;"."<A CLASS=TBL_HREF HREF='actions/810.delete.php?p=$PID".
            "&e=<#2#>".
            "'>".icon("delete","Hapus")."</A>";
    $t->ColHeader = array("UNIT MEDIS FUNGSIONAL", "JABATAN MEDIS FUNGSIONAL", "V i e w");
    
    $t->execute();
    
    echo "<BR><DIV ALIGN=LEFT><img src=\"icon/user.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>Jabatan Medis Fungsional Baru </A></DIV>";
}
}else{
	
	$data = getFromTable("select jabatan_medis_fungsional from rs00018 where id='".$_GET[e]."'");

    echo "<div align=center>";
    echo "<form action='actions/810.delete.php' method='get'>";
    echo "<font color=red size=3>PERINGATAN !</font><br>";
    echo "<font class=SUB_MENU>Master Jabatan Medis Fungsional <font color=navy>'".$data."'</font> akan Dihapus.</font><br><br>";
    echo "<input type=hidden name=p value=$PID>";
    echo "<input type=hidden name=e value=".$_GET[e].">";
    
    echo "<input type=submit name=sure value='YA'>&nbsp;";
    echo "<input type=submit name=sure value='TIDAK'>";
    echo "</form>";
    echo "</div>";
}
?>
