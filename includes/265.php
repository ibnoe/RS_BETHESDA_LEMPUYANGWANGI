<? // 30/12/2003
   // sfdn, 20-04-2004
   // sfdn, 30-04-2004
   // sfdn, 05-06-2004

$PID = "265";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if(isset($_GET["e"])) {
		$ext = "OnChange = 'Form1.submit();'";
		$t = new Form($SC, "GET", "NAME=Form1");
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
		$buton = "Simpan";
    if ($_GET["e"] == "edit") {
        $f = new Form("actions/265.update.php", "POST");
        title("Edit Penyajian Menu Makanan");
        $f->hidden("id","$d1->id");
		$f->hidden("f_tt","PMU");
		$f->text("id","Kode Data",10,10,$d1->id,"DISABLED");
		$f->hidden("e",$_GET["e"]);
		$qty 	= $d1->qty;
		$s		= $d1->kode_periode;
		$k		= $d1->klasifikasi_id;
		$w		= $d1->kode_waktu_penyajian;
		$f->hidden("s",$_GET["s"]);
		$f->hidden("k",$_GET["k"]);
		$f->hidden("w",$_GET["w"]);
    } elseif ($_GET["e"] == "new") {
        $f = new Form("actions/265.insert.php");
        title("Penyajian Menu Makanan Baru");
        $f->hidden("id","new");
		$f->hidden("f_tt","PMU");
		$f->text("id","Kode Data",10,10,"<OTOMATIS>","DISABLED");		
		$f->hidden("e",$_GET["e"]);
		$qty = 0.00;
		$s		= $_GET["s"];
		$k		= $_GET["k"];
		$w		= $_GET["w"];
		$f->hidden("s",$_GET["s"]);
		$f->hidden("k",$_GET["k"]);
		$f->hidden("w",$_GET["w"]);
    } elseif ($_GET["e"] == "del") {
        $f = new Form("actions/265.delete.php", "POST");
        title("Hapus data Penyajian Menu Makanan");
        $f->hidden("id",$_GET["l"]);
		$f->hidden("f_tt","PMU");
		$f->text("id","Kode Data",10,10,$d1->id,"DISABLED");
		$f->hidden("e",$_GET["e"]);
		$qty 	= $d1->qty;
		$s		= $d1->kode_periode;
		$k		= $d1->klasifikasi_id;
		$w		= $d1->kode_waktu_penyajian;
		$f->hidden("s",$_GET["s"]);
		$f->hidden("k",$_GET["k"]);
		$f->hidden("w",$_GET["w"]);
		$buton = "Hapus";
    }
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID".
								"&mSAJI=".$_GET["s"].
								"&mKELAS=".$_GET["k"].
								"&mWAKTU=".$_GET["w"].
								"'>".icon("back","Kembali")."</a></DIV>";	
    $f->PgConn = $con;
	$f->selectSQL("f_kode_periode", "Tgl. Penyajian",
				  "select '' as tc, '' as tdesc union ".
                  "select id as tc, lpad(day1,2,'0') || '  s/d  ' || lpad(day2,2,'0') as tdesc ".
				  "from rs00043 ".
				  "where tt = 'PRM' ",
                  $s);
				  
	$f->selectSQL("f_klasifikasi_id", "K e l a s",
				  "select '' as tc, '' as tdesc union ".
                  "select tc, tdesc ".
				  "from rs00001 ".
				  "where tt = 'KTR' and tc IN ('002','003','019','020','021','022') ",
                  $k);
	$f->selectSQL("f_is_menu", "Menu/Non-Menu",
				  "select '' as tc, '' as tdesc union ".
                  "select 'Y' as tc, 'MENU' as tdesc union ".
				  "select 'N' as tc, 'NON-MENU' as tdesc ",
                  $d1->is_menu);
	if ($d1->is_menu == "Y") {
		$f->selectSQL("f_kode_menu", "Nama Menu",
				  "select '' as tc, '' as tdesc union ".
                  "select id as tc, nama_menu as tdesc ".
				  "from rs00043 ".
				  "where tt = 'DFM' ",
                  $d1->kode_menu);
	} else {
		$f->selectSQL("f_rs00015_id", "Nama Menu",
				  "select '' as tc, '' as tdesc union ".
                  "select to_char(a.id,'999999999'), a.obat as tdesc ".
				  "from rs00015 a ".
				  "		left join rs00043 b ON b.rs00015_id = a.id ".
				  "where a.kategori_stock_id = '003' ",
                  $d1->rs00015_id);
	}
	
	$f->selectSQL("f_kode_satuan", "Satuan",
				  "select '' as tc, '' as tdesc union ".
                  "select tc, tdesc ".
				  "from rs00001 ".
				  "where tt = 'SAT' and tc IN ('026','027','030','035','025','050','049','051') ",
                  $d1->kode_satuan);
	$f->selectSQL("f_kode_waktu_penyajian", "Waktu Penyajian",
				  "select '' as tc, '' as tdesc union ".
                  "select tc, tdesc ".
				  "from rs00001 ".
				  "where tt = 'WPM' and tc NOT IN ('000') ",
                  $w);
	$f->text("f_qty","QTY",5,5,$qty,"style='text-align:right'");
	$f->submit($buton);
	$f->execute();

} else {
    // search box
    title("Daftar Penyajian Menu Makanan");
	echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    $f->selectSQL("mSAJI", "Tgl. Penyajian",
        "select '' as tc, '' as tdesc union " .
		"select '9999999999' as tc, 'Semua' as tdesc union ".
        "select id as tc, lpad(day1,2,'0') || '  s/d  ' || lpad(day2,2,'0') as tdesc ".
        "from rs00043 ".
		"where tt='PRM' ", $_GET["mSAJI"]);
    $f->selectSQL("mKELAS", "K e l a s",
        "select '' as tc, '' as tdesc union " .
		"select '999' as tc, 'Semua' as tdesc union ".
        "select tc , tdesc ".
        "from rs00001 ".
		"where tt='KTR' and tc IN ('002','003','019','020','021','022')", $_GET["mKELAS"]);
    $f->selectSQL("mWAKTU", "Waktu Penyajian",
        "select '' as tc, '' as tdesc union " .
		"select '999' as tc, 'Semua' as tdesc union ".
        "select tc , tdesc ".
        "from rs00001 ".
		"where tt='WPM' and tc NOT IN ('000')", $_GET["mWAKTU"]);
	$f->submit(" OK ");
	$f->execute();
	echo "<br>";
	$SQL0 = 
			"select lpad(b.day1,2,'0') || '  s/d  ' || lpad(b.day2,2,'0') as periode, ".
			"	c.tdesc as klasifikasi, ".
			"	case when a.is_menu='N' then (select obat from rs00015 x where x.id = a.rs00015_id) ".
			"		else (select nama_menu from rs00043 x where x.id = a.kode_menu) end as bahan, ".
			"	a.qty, d.tdesc as satuan, e.tdesc as waktu, a.id as dummy ".
			"from rs00043 a ".
  			"	left join rs00043 b ON b.id = a.kode_periode ".
  			"	left join rs00001 c ON a.klasifikasi_id = c.tc and c.tt='KTR' ".
  			"	left join rs00001 d ON a.kode_satuan = d.tc and d.tt='SAT' ".
			"	left join rs00001 e ON a.kode_waktu_penyajian = e.tc and e.tt='WPM' ".
			"where a.tt='PMU' ";

	if ($_GET["mSAJI"] == "9999999999") {
		$SQL1 = " ";
	} elseif (strlen($_GET["mSAJI"]) > 0 ) {
		$SQL1 = " and a.kode_periode = '".$_GET["mSAJI"]."' ";
	}	
	if ($_GET["mKELAS"] == "999") {
		$SQL2 = " ";
	} elseif (strlen($_GET["mKELAS"]) > 0 ) {
		$SQL2 = " and a.klasifikasi_id = '".$_GET["mKELAS"]."' ";
	}	
	if ($_GET["mWAKTU"] == "999") {
		$SQL3 = " ";
	} elseif (strlen($_GET["mWAKTU"]) > 0 ) {
		$SQL3 = " and a.kode_waktu_penyajian = '".$_GET["mWAKTU"]."' ";
	}	
	$SQL = "$SQL0 $SQL1 $SQL2 $SQL3";
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
	echo "<INPUT TYPE=HIDDEN NAME=mSAJI VALUE='".$_GET["mSAJI"]."'>";	
	echo "<INPUT TYPE=HIDDEN NAME=mKELAS VALUE='".$_GET["mKELAS"]."'>";	
	echo "<INPUT TYPE=HIDDEN NAME=mWAKTU VALUE='".$_GET["mWAKTU"]."'>";	
    //echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    //echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
    echo "</TR></FORM></TABLE></DIV>";
    echo "<br>";
	if (strlen($_GET["mSAJI"]) > 0 AND strlen($_GET["mKELAS"]) > 0 AND strlen($_GET["mWAKTU"]) > 0) {
		$t = new PgTable($con, "100%");
		$t->SQL = $SQL;
		$t->ColHeader = array("TGL.PENYAJIAN","KELAS","MENU MAKANAN","QTY","SATUAN", "WAKTU","E d i t | Hapus");
		$t->ShowRowNumber = true;
		$t->ColAlign[6] = "CENTER";
		$t->RowsPerPage = $ROWS_PER_PAGE;
		/*
		$t->ColFormatHtml[6] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID".
								"&e=edit&l=<#6#>".
								"&s=".$_GET["mSAJI"].
								"&k=".$_GET["mKELAS"].
								"&w=".$_GET["mWAKTU"].
								"'>".icon("edit","Edit")."</A>";
		*/
		$t->ColFormatHtml[6] = "<nobr><A CLASS=TBL_HREF HREF='$SC?p=$PID".
								"&e=edit&l=<#6#>".
								"&s=".$_GET["mSAJI"].
								"&k=".$_GET["mKELAS"].
								"&w=".$_GET["mWAKTU"].
								"'>".icon("edit","Edit")."</A> &nbsp; ".
							   "<A CLASS=TBL_HREF HREF='$SC?p=$PID".
							    "&e=del&l=<#6#>".							   
								"&s=".$_GET["mSAJI"].
								"&k=".$_GET["mKELAS"].
								"&w=".$_GET["mWAKTU"].
								"'>".icon("del-left","Hapus")."</A></nobr>";
		$t->execute();
	
		echo "<BR><DIV ALIGN=RIGHT><A CLASS=SUB_MENU ".
			 "HREF='index2.php?p=$PID".
								"&s=".$_GET["mSAJI"].
								"&k=".$_GET["mKELAS"].
								"&w=".$_GET["mWAKTU"].
			 					"&e=new'>&#171; Data Penyajian Menu Makanan Baru &#187;</A></DIV>";
	
	}
}
?>
