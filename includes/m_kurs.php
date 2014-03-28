<? // Nugraha, 23/02/2004
   // Pur, 09/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 30-04-2004
   // sfdn, 09-05-2004
   // sfdn, 18-05-2004
   // sfdn, 05-06-2004
   
$PID = "m_kurs";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if(strlen($_GET["e"]) > 0) {
    echo "<div align=right><a href='".
        "$SC?p=$PID&mOBT=".$_GET["o"].
        "'>".icon("back", "Kembali")."</a></div>";
	
    if($_GET["e"] == "new") {
        $f = new Form("actions/807.insert.php");
        title("Data Baru");
        echo "<BR>";
        $f->text("id","ID",12,12,"<OTOMATIS>","DISABLED");
	$harga=0;
    } else {
        $r2 = pg_query($con,
            "select * ".
            "from rs00015 ".
            "where id='".$_GET["e"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
	
        $r3 = pg_query($con,
            "select harga ".
            "from rs00016 ".
            "where obat_id = '".$_GET["e"]."' and id = (select max(id) from rs00016 where obat_id = '".$_GET["e"]."')");
        $d3 = pg_fetch_object($r3);
        pg_free_result($r3);
        $f = new Form("actions/807.update.php?search=".$_GET[search]."&sort=".$_GET[sort]."&order=".$_GET[order]."&tblstart=".$_GET[tblstart]);
        title("Editing Data");
        echo "<BR>";


        $f->hidden("id",$_GET["e"]);
        $f->text("id","ID",10,4,$_GET["e"],"DISABLED");
	$harga=$d3->harga;
    }
	
    
    	$f->PgConn = $con;
	$f->hidden("f_kategori_stock_id",$_GET["s"]);
        $f->hidden("f_kategori_id",$_GET["o"]);
	$f->hidden("mOBT",$_GET["o"]);
    
	$jd1 = "Obat";
	if ($_GET["o"] == "005" ){
		$jd1 = "Nama Bahan Makanan";
	} elseif ($_GET["o"] == "004" ) {
		$jd1 = "Nama Barang Habis Pakai";
	}
    $f->text("f_obat",$jd1,40,50,$d2->obat);
	if ($_GET["o"] == "001" OR $_GET["o"] == "002" OR $_GET["o"] == "003" ) {
    	$f->text("f_generik","Generik",40,50,$d2->generik);
	}
	if ($_GET["o"] == "001" OR $_GET["o"] == "002" OR $_GET["o"] == "003" OR $_GET["o"] == "004" OR $_GET["o"] == "005")    {
		$SQL = 
				"select '' as tc, '' as tdesc union ".
				"select tc, tdesc from rs00001 where tt = 'SAT' and tc!='000'";
	} elseif ($_GET["o"] == "003" ) {
		$SQL = 
				"select '' as tc, '' as tdesc union ".
				"select tc, tdesc from rs00001 where tt = 'SAT' and tc IN ('026','027','030','035','025','050')";
	}
  	
	$f->selectSQL("f_satuan_id", "Satuan",$SQL,
                  $d2->satuan_id);
		  
	
    $f->text("harga","Harga",12,12,$harga,"style='text-align:right'");
    $f->submit(" Simpan ");
    $f->execute();
    echo "<br>";
    if(strlen($_GET["err"]) > 0) {
        errmsg("Terjadi Kesalahan", stripslashes($_GET["err"]));
    }

} else {
    title("<img src='icon/informasi-2.gif' align='absmiddle' >  MASTER KURS");
    if (isset($_GET["e"])) {
        $ext = "DISABLED";
    } else {
        $ext = "OnChange = 'Form1.submit();'";
    }
    echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

    $q = pg_query("select kategori_stock_id from rs00015 where kategori_id = '".$_GET['mOBT']."'");
    $qr = pg_fetch_object($q);
    $f->hidden("stock_id", $qr->kategori_stock_id);

  /*  $f->selectSQL("mOBT", "Kategori Akun",
        //"select '' as tc, '' as tdesc union " .
        "select tc, tdesc ".
        "from rs00001 ".
        "where tt = 'GOB' and tc != '000' ".
        "order by tc", $_GET["mOBT"],
        $ext);*/
    $f->execute();
	$jdl = "Data Kurs Baru";
	if ($_GET["mOBT"] == "005") {
		$jdl = "Data Bahan Inst.Gizi";
	} elseif ($_GET["mOBT"] == "004") {
		$jdl = "Data BHP";
	}

    // search box
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<INPUT TYPE=HIDDEN NAME=mOBT VALUE='".$_GET["mOBT"]."'>";
//    echo "<TD><font class=SUB_MENU>NAMA BARANG:</font> <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
 //   echo "<TD><INPUT TYPE=SUBMIT VALUE=' CARI '></TD>";
      echo "<TD WIDTH=1>Pencarian : <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL =
        "select a.obat, c.tdesc as satuan, tanggal(b.tanggal_entry,3) as tanggal_entry_str, ".
        "b.harga, a.generik,a.id as dummy ".
        "from rs00015 a, rs00016 b, rs00001 c ".
        "where ".
        "a.kategori_id = '".$_GET["mOBT"]."' and ".
        "a.id = b.obat_id and a.satuan_id = c.tc and c.tt='SAT' and".

//        "a.id = b.obat_id and a.satuan_id = c.tc and c.tt='SAT' and ".
        "(upper(obat) LIKE '%".strtoupper($_GET["search"])."%')";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "RIGHT";
    $t->ColAlign[5] = "CENTER";
    //$t->ColFormatMoney[3] = "%!+#2n";
    $t->ColFormatNumber[3] = 2;
    $t->ColFormatHtml[5] = "<nobr><A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#5#>&o=".$_GET["mOBT"].
	"&search=".$_GET[search]."&sort=".$_GET[sort]."&order=".$_GET[order]."&tblstart=".$_GET[tblstart]."'>".icon("edit","Edit")."</A>";

/******* tokit: jangan diberi fasilitas delete, ini berhubungan dengan inventori. Bahaya!!!	

	"&nbsp;<A CLASS=TBL_HREF HREF='807.delete.php?p=$PID&e=<#5#>&o=".$_GET["mOBT"].
	"&search=".$_GET[search]."&sort=".$_GET[sort]."&order=".$_GET[order]."&tblstart=".$_GET[tblstart]."'>".icon("delete","Hapus")."</A></nobr>";

********/

    $t->ColHeader = array("KODE", "NAMA MATA UANG", "TANGGAL", "RASIO","KETERANGAN","E d i t");

    $t->execute();
 
    echo "<BR><DIV ALIGN=LEFT><img src=\"icon/inventory.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new&o=".$_GET["mOBT"]."&s=".$qr->kategori_stock_id."'> Tambah $jdl </A></DIV>";

}

?>
