<? // 30/12/2003
   // sfdn, 20-04-2004
   // sfdn, 30-04-2004
   // sfdn, 05-06-2004

$PID = "260";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if(isset($_GET["e"])) {
        $r = pg_query($con, "select a.nama_menu || '  /  ' || b.tdesc as nama ".
				"from rs00043 a, rs00001 b ".
				"where a.id = '".$_GET["m"]."' and ".
				"	a.kode_satuan = b.tc and b.tt='SAT'");
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);

        $r1 = pg_query($con, "select * from rs00043 where id = '".$_GET["l"]."'");
        $n1 = pg_num_rows($r1);
        if($n1 > 0) $d1 = pg_fetch_object($r1);
        pg_free_result($r1);
    if ($_GET["e"] != "new") {
        $f = new Form("actions/260.update.php", "POST");
        title("Edit Komposisi Menu Makanan");
        $f->hidden("id","$d1->id");
		$f->hidden("f_tt","KOM");
		$f->hidden("f_kode_menu",$_GET["m"]);
        $f->text("id","Kode Data",10,10,$d1->id,"DISABLED");
		$qty = $d1->qty;
    } else {
        $f = new Form("actions/260.insert.php");
        title("Komposisi Menu Makanan Baru");
        $f->hidden("id","new");
		$f->hidden("f_tt","KOM");
		$f->hidden("f_kode_menu",$_GET["m"]);
		$f->text("id","Kode Data",10,10,"<OTOMATIS>","DISABLED");		
		$qty = 0.00;
    }
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID&mMENU=".$_GET["m"]."'>".icon("back","Kembali")."</a></DIV>";	
    $f->PgConn = $con;
    $f->text("","Nama Menu Makanan",40,40,$d->nama,"DISABLED");
    $f->selectSQL("f_rs00015_id", "Bahan Menu Makanan",
				  "select '' as tc, '' as tdesc union ".
                  "select to_char(id,'999999999') as tc, obat as tdesc ".
				  "from rs00015 ".
				  "where kategori_stock_id = '003' ",
                  $d1->rs00015_id);
	$f->selectSQL("f_kode_satuan", "Satuan",
				  "select '' as tc, '' as tdesc union ".
                  "select tc, tdesc ".
				  "from rs00001 ".
				  "where tt = 'SAT' and tc IN ('026','027','030','035','025','050','051') ",
                  $d1->kode_satuan);
	$f->text("f_qty","QTY",12,12,$qty,"style='text-align:right'");				  
	$f->submit(" Simpan ");
    $f->execute();
} else {
    // search box
    title("Daftar Komposisi Menu Makanan");
    if (isset($_GET["e"])) {
        $ext = "DISABLED";
    } else {
        $ext = "OnChange = 'Form1.submit();'";
    }
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    $f->selectSQL("mMENU", "Nama Menu",
        "select '' as tc, '' as tdesc union " .
        "select id as tc, nama_menu as tdesc ".
        "from rs00043 ".
        "where tt = 'DFM'  ", $_GET["mMENU"],
        $ext);
	if (strlen($_GET["mMENU"]) > 0 ) {
		$satuan = getFromTable("select b.tdesc ".
                     "from rs00043 a ".
					 "	left join rs00001 b ON a.kode_satuan = b.tc and b.tt='SAT' ".
                     "where a.id = '".$_GET["mMENU"]."'");
		$f->text("","Satuan",20,20,$satuan,"DISABLED");
	}
	$f->execute();
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
	echo "<INPUT TYPE=HIDDEN NAME=mMENU VALUE='".$_GET["mMENU"]."'>";	
    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari/Bahan Makanan '></TD>";
    echo "</TR></FORM></TABLE></DIV>";
    echo "<br>";
    $t = new PgTable($con, "100%");
    $t->SQL = 
			"select b.obat, a.qty,c.tdesc as bahan, a.id as dummy ".
			"FROM rs00043 a ".
			"	left join rs00015 b ON a.rs00015_id = b.id ".
			"	left join rs00001 c ON a.kode_satuan = c.tc and c.tt='SAT' ".
			"where a.tt='KOM' and ".
			"	a.kode_menu = '".$_GET["mMENU"]."' and ".
			"	upper(b.obat) LIKE '%".strtoupper($_GET["search"])."%' ";
	
    $t->ColHeader = array("BAHAN MAKANAN","QTY","SATUAN", "E d i t");
    $t->ShowRowNumber = true;
    $t->ColAlign[3] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=edit&l=<#3#>&m=".$_GET["mMENU"]."'>".icon("edit","Edit")."</A>";
    /*
    $t->ColFormatHtml[4] = "<nobr><A CLASS=TBL_HREF HREF=''>".icon("view","View")."</A> &nbsp; ".
                           "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#4#>'>".icon("edit","Edit")."</A></nobr>";
    */

    $t->execute();

    echo "<BR><DIV ALIGN=RIGHT><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new&m=".$_GET["mMENU"]."'>&#171; Komposisi Menu Makanan Baru &#187;</A></DIV>";
}
?>
