<? 

   
$PID = "812";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (empty($_GET[sure])) {
if(isset($_GET["e"])) {
    $r = pg_query($con, "select * from rs00021 where id = '".$_GET["e"]."'");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    
  
    if($n > 0) {
    title("Kelompok Sumber Pendapatan (Edit)");
        $f = new Form("actions/812.update.php", "POST");
        $f->hidden("id","$d->id");
        $f->text("id","KODE",3,3,$d->id,"DISABLED");
    } else {
    title("Kelompok Sumber Pendapatan (Baru)");
        $f = new Form("actions/812.insert.php");
        $f->hidden("id","new");
        $f->text("id","KODE",12,12,"&lt;OTOMATIS&gt;","DISABLED");
    }
    $f->PgConn = $con;
    $f->text("f_jasa_medis","Jasa Medis",50,60,$d->jasa_medis);
    $f->selectSQL("f_tipe_pasien_id", "Tipe Pasien",
                  "select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000'",
                  $d->tipe_pasien_id);
    $f->submit(" Simpan ");
    $f->execute();
} else {
	title("<img src='icon/informasi-2.gif' align='absmiddle' >  Tabel Master: KELOMPOK SUMBER PENDAPATAN");
    

    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<TD>Pencarian : <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
//    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";
    
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = 
        "select a.jasa_medis,b.tdesc ,a.id as dummy ".
        "from rs00021 a ".
        "join rs00001 b on a.tipe_pasien_id = b.tc ".
        "where tt = 'JEP'".
        "and ".
        "(id LIKE '%".strtoupper($_GET["search"])."%' ".
        "OR upper(b.tdesc) LIKE '%".strtoupper($_GET["search"])."%'". 
        "OR upper(a.jasa_medis) LIKE '%".strtoupper($_GET["search"])."%')";
	
    $t->setlocale("id_ID");    
    $t->ShowRowNumber = true;    
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColAlign[2] = "CENTER";
    $t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#2#>'>".icon("edit","Edit")."</A>&nbsp;".
    "<A CLASS=TBL_HREF HREF='actions/812.delete.php?p=$PID&e=<#2#>'>".icon("delete","Hapus")."</A>";
    
    $t->ColHeader = array("JASA MEDIS", "TIPE PASIEN", "V i e w");
    $t->execute();
    
    echo "<BR><DIV ALIGN=LEFT><img src=\"icon/keuangan.gif\" align=absmiddle > <A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>KELOMPOK SUMBER PENDAPATAN</A></DIV>";
}
}else{
	
	$data = getFromTable("select jasa_medis from rs00021 where id='".$_GET[e]."'");

    echo "<div align=center>";
    echo "<form action='actions/812.delete.php' method='get'>";
    echo "<font color=red size=3>PERINGATAN !</font><br>";
    echo "<font class=SUB_MENU>Master Sumber Pendapatan <font color=navy>'".$data."'</font> akan Dihapus.</font><br><br>";
    echo "<input type=hidden name=p value=$PID>";
    echo "<input type=hidden name=e value=".$_GET[e].">";
    
    echo "<input type=submit name=sure value='YA'>&nbsp;";
    echo "<input type=submit name=sure value='TIDAK'>";
    echo "</form>";
    echo "</div>";
}
?>
