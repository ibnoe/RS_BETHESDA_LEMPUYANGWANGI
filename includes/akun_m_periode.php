<? 

// Wildan ST. 18 Feb 2014 
   
$PID = "akun_m_periode";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");



if (empty($_GET[sure])) {
if(isset($_GET["e"])) {
    $r = pg_query($con, "select * from triwulan where kode = '".$_GET["e"]."'");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    
  
    if($n > 0) {
    title("<img src='icon/akuntansi-settingperiode.png' align='absmiddle' >  Setting Periode (Edit)");
        $f = new Form("actions/akun_m_periode.update.php", "POST" , "NAME=Form1");
        $f->hidden("kode","$d->kode");
        $f->text("kode","KODE",3,3,$d->kode,"DISABLED");
    } else {
    title("<img src='icon/akuntansi-settingperiode.png' align='absmiddle' >  Setting Periode (Baru)");
        $f = new Form("actions/akun_m_periode.insert.php", "POST" ,"NAME=Form1");
        $f->hidden("kode","new");
        $f->text("kode","KODE",12,12,"&lt;OTOMATIS&gt;","DISABLED");
    }
    $f->PgConn = $con;
    $f->text("f_ket_triwulan","Nama Periode",50,50,$d->ket_triwulan);
    //$f->text("f_bln_awal","Nama Periode",30,30,$d->ket_triwulan);
	$f->calendar1("f_bln_awal","Tanggal Awal",15,15,$d->bln_awal,"Form1","icon/calendar.gif","Pilih Tanggal",$ext);
	$f->calendar1("f_bln_akhir","Tanggal Akhir",15,15,$d->bln_akhir,"Form1","icon/calendar.gif","Pilih Tanggal",$ext);
	$f->text("f_keterangan","Keterangan",50,50,$d->keterangan);
	$f->submit("Simpan");
    $f->execute();
} else {
	title("<img src='icon/akuntansi-settingperiode.png' align='absmiddle' >  Setting Periode ");
    

    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<TD>Pencarian : <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
//    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";
    
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = 
        "select ket_triwulan,to_char(bln_awal,'dd Mon yyyy'),to_char(bln_akhir,'dd Mon yyyy'),keterangan,kode ".
        "from triwulan ".
        "where (kode LIKE '%".strtoupper($_GET["search"])."%' ".
        "OR upper(ket_triwulan) LIKE '%".strtoupper($_GET["search"])."%'". 
        "OR upper(keterangan) LIKE '%".strtoupper($_GET["search"])."%')";
	
    $t->setlocale("id_ID");    
    $t->ShowRowNumber = true;    
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColAlign[4] = "CENTER";
	$t->ColAlign[1] = "CENTER";
	$t->ColAlign[2] = "CENTER";
    $t->ColFormatHtml[4] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#4#>'>".icon("edit","Edit")."</A>&nbsp;".
    "<A CLASS=TBL_HREF HREF='actions/akun_m_periode.delete.php?p=$PID&e=<#4#>'>".icon("delete","Hapus")."</A>";
    
    $t->ColHeader = array("NAMA PERIODE", "TANGGAL AWAL","TANGGAL AKHIR", "KETERANGAN","V i e w");
    $t->execute();
    
    echo "<BR><DIV ALIGN=LEFT><img src=\"icon/clock1.gif\" align=absmiddle > <A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>TAMBAH PERIODE</A></DIV>";
}
}else{
	
	$data = getFromTable("select ket_triwulan from triwulan where kode='".$_GET["e"]."'");

    echo "<div align=center>";
    echo "<form action='actions/akun_m_periode.delete.php' method='get'>";
    echo "<font color=red size=3>PERINGATAN !</font><br>";
    echo "<font class=SUB_MENU>Setting Periode Akuntansi <font color=navy>'".$data."'</font> akan Dihapus.</font><br><br>";
    echo "<input type=hidden name=p value=$PID>";
    echo "<input type=hidden name=e value=".$_GET["e"].">";
    echo "<input type=submit name=sure value='YA'>&nbsp;";
    echo "<input type=submit name=sure value='TIDAK'>";
    echo "</form>";
    echo "</div>";
}

?>
