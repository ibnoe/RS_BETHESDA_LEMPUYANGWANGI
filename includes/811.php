<? // Nugraha, 22/02/2004
   // Pur, 09/03/2004: new libs table
   // sfdn, 30-04-2004
   // sfdn, 13-05-2004
   // sfdn, 01-06-2004

$PID = "811_2";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (empty($_GET[sure])) {
if(isset($_GET["e"])) {
    $r = pg_query($con, "select * from rs00020_2 where id = '".$_GET["e"]."'");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    
  
    if($n > 0) {
    title("Sub Pembagian Sumber Pendapatan (Edit)");
    echo "<BR>";
        $f = new Form("actions/811.update.php", "POST");
        $f->hidden("id",$d->id);
        $f->text("id","KODE",3,3,$d->id,"DISABLED");
    } else {
    title("Sub Pembagian Jasa Medis (Baru)");    
    echo "<BR>";
        $f = new Form("actions/811.insert.php");
        $f->hidden("id","new");
        $f->text("id","KODE",12,12,"&lt;OTOMATIS&gt;","DISABLED");
    }    
    $f->PgConn = $con;
    $f->selectSQL("f_pembagian_jasa_medis_id", "Jasa Medis",
                  "SELECT id, tdesc || ': ' || jasa_medis as jasa_medis ".
                  "FROM rs00021, rs00001 ".
                  "WHERE rs00021.tipe_pasien_id = rs00001.tc ".
                  "AND tt = 'JEP' and tc!='000' ",
                  $d->pembagian_jasa_medis_id);
    $f->text("f_description","Description",50,100,$d->description);
    $f->text("f_prosen","Prosentase",5,5,$d->prosen);
    $f->selectArray("f_is_person",
                    "PEGAWAI",
                     Array("Y" => "Ya", "N" => "Tidak"),
                     $d->is_person);

    $f->selectSQL("f_tipe_pasien", "Tipe Pasien",
                  " select '' as tc, '' as tdesc union ".
                  "SELECT tc, tdesc ".
                  "FROM rs00001 ".
                  "WHERE tc!='000' ".
                  "AND tt = 'JEP' order by tdesc",
                  $d->tipe_pasien);

    $f->submit(" Simpan ");
    $f->execute();
} else {
    title("<img src='icon/informasi-2.gif' align='absmiddle' >  Tabel Master: % PEMBAGIAN SUMBER PENDAPATAN");
    
    echo "<BR>";
    // search box
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<TD>Pencarian : <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
  //  echo "<TD><INPUT TYPE=SUBMIT VALUE=' Tipe Pasien '></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = 
        "select a.description, b.jasa_medis, c.tdesc,case when a.is_person='Y' then 'Personal' else 'Bukan' end as person, a.prosen || '  ' || '%', ".
        "a.id as dummy ".
        "from rs00020_2 a ".
        "join rs00021 b on a.pembagian_jasa_medis_id = b.id ".
        "join rs00001 c on b.tipe_pasien_id = c.tc ".
        "where c.tt='JEP' and ".
        "(upper(description) LIKE '%".strtoupper($_GET["search"])."%' ".
        "OR jasa_medis LIKE '%".strtoupper($_GET["search"])."%' ".
        "OR upper(tdesc) LIKE '%".strtoupper($_GET["search"])."%')";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[4] = "RIGHT";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[5] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[5] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#5#>'>".icon("edit","Edit")."</A>&nbsp;".
                           "<A CLASS=TBL_HREF HREF='actions/811.delete.php?p=$PID&e=<#5#>'>".icon("delete","Hapus")."</A>";

    $t->ColHeader = array("PENERIMA J/M", "SUMBER PENDAPATAN", "TIPE PASIEN","&nbsp;", "PROSENTASE", "V i e w");
    
    $t->execute();
    
    echo "<BR><DIV ALIGN=LEFT><img src=\"icon/keuangan.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>Sub Jasa Pelayanan Medis Askes Baru </A></DIV>";
}
}else{
	$data = getFromTable("select description from rs00020_2 where id='".$_GET[e]."'");

    echo "<div align=center>";
    echo "<form action='actions/811.delete.php' method='get'>";
    echo "<font color=red size=3>PERINGATAN !</font><br>";
    echo "<font class=SUB_MENU> Master % Pembagian Sumber Pendapatan <font color=navy>'".$data."'</font> akan Dihapus.</font><br><br>";
    echo "<input type=hidden name=p value=$PID>";
    echo "<input type=hidden name=e value=".$_GET[e].">";
    
    echo "<input type=submit name=sure value='YA'>&nbsp;";
    echo "<input type=submit name=sure value='TIDAK'>";
    echo "</form>";
    echo "</div>";
}
?>
