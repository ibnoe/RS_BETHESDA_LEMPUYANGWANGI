<? // Nugraha, 17/02/2004
   // Pur, 27/02/2004
   // Pur, 27/03/2004 : new libs table
   // sfdn, 20-04-2004
   // sfdn, 30-04-2004
   // sfdn, 19-05-2004


$PID = "817";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

$r = pg_query($con,"select * from rsv0027");
$d = pg_fetch_object($r);
pg_free_result($r);


if(strlen($_GET["e"]) > 0) {
    if($_GET["e"] == "new") {
        $f = new Form("actions/817.insert.php");
        title("Insert Data Standard AKKM");
        echo "<BR>";
        $f->text("id","ID",12,12,"<OTOMATIS>","DISABLED");
        $f->hidden("j",$_GET["j"]);
        $f->hidden("u",$_GET["u"]);

    } else {
        $r2 = pg_query($con,
            "select * ".
            "from rs00038 ".
            "where id='".$_GET["e"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);

        $rx = pg_query($con,
            "select * ".
            "from rs00027 ".
            "where id='$d2->rs00027_id'");
        $dx = pg_fetch_object($rx);
        pg_free_result($rx);

        $f = new Form("actions/817.update.php");
        title("Edit Data Standard AKKM");
        echo "<BR>";
        $f->hidden("id",$_GET["e"]);
        $f->hidden("j",$_GET["j"]);
        $f->hidden("u",$_GET["u"]);
        $f->text("id","ID",10,4,$_GET["e"],"DISABLED");
    }
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID".
                    "&mUNSUR=".$_GET["u"]."&mJENJANG=".$_GET["j"]."'>".icon("back","Kembali")."</a></DIV>";
    $f->PgConn = $con;

    // unsur kegiatan
    $f->selectSQL("f_unsur_id", "Unsur Kegiatan",
                  "select tc, tdesc ".
                  "from rs00001 ".
                  "where tc='".$_GET["u"]."' and ".
                        "tt='UKP' and tc<>'000'",
                  $d2->unsur_id);

    $f->selectSQL("f_jjd_id", "Jenjang Jabatan",
                  "select tc, tdesc ".
                  "from rs00001 ".
                  "where tc='".$_GET["j"]."' and ".
                         "tt='JJD' and tc<>'000'",
                  $d2->jjd_id);

    $f->selectSQL("f_rs00027_id", "Jenjang Pangkat",
                  "select id, nama_jenjang_pangkat ".
                  "from rs00027 ".
                  "where jjd_id='".$_GET["j"]."'",
                  $d2->rs00027_id);

    $f->selectSQL("", "Gol. Ruang",
                  "select '' as tc, '' as  tdesc union ".
                  "select tc,tdesc ".
                  "from rs00001 a, rs00027 b  ".
                  "where b.jjd_id = '".$_GET["j"]."' and ".
                         "b.gol_ruang_id = a.tc and a.tt='GRP'"
                         ,$dx->gol_ruang_id);

    $f->text("f_standard_akkm","Angka Kredit Minimal ",10,5,$d2->standard_akkm);
    $f->submit(" Simpan ");
    $f->execute();
    echo "<br>";
    if(strlen($_GET["err"]) > 0) {
        errmsg("Terjadi Kesalahan", stripslashes($_GET["err"]));
    }
} else {

    title("Tabel Master: Standard Angka Kredit Kumulatif Minimal(AKKM)");
    echo "<br>";
    $ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    $f->selectSQL("mUNSUR", "Unsur Kegiatan",
        "select '' as tc, '' as  tdesc union ".
        "select tc, tdesc ".
        "from rs00001 ".
        "where tt='UKP' and tc!='000'", $_GET["mUNSUR"],
        $ext);
    $f->selectSQL("mJENJANG", "Jenjang Jabatan",
        "select '' as tc, '' as tdesc union ".
        "select tc, tdesc ".
        "from rs00001 ".
        "where tt='JJD' and tc!='000'", $_GET["mJENJANG"],
        $ext);
    $f->execute();
/*
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Jenjang '></TD>";
    echo "</TR></FORM></TABLE></DIV>";
*/
    echo "<br>";
    $t = new PgTable($con, "100%");
    $t->SQL =
        "select b.nama_jenjang_pangkat, c.tdesc, a.standard_akkm, a.id as dummy ".
        "from rs00038 a, rs00027 b, rs00001 c ".
        "where a.unsur_id='".$_GET["mUNSUR"]."' and ".
            "a.jjd_id = '".$_GET["mJENJANG"]."' and a.rs00027_id = b.id and  ".
            "b.gol_ruang_id = c.tc and c.tt='GRP'";
/*
        "SELECT b.tdesc AS jabatan, c.tdesc AS ruang, a.nama_jenjang_pangkat, ".
	    "a.angka_kredit_kumulatif_minimal, a.angka_kredit_kumulatif_minimal1, ".
	    "(a.angka_kredit_kumulatif_minimal+a.angka_kredit_kumulatif_minimal1) as angka, ".
        "a.id ".
        "FROM rs00027 a, rs00001 b, rs00001 c ".
	    "where (a.jjd_id = b.tc and b.tt='JJD') and ".
		    "(a.gol_ruang_id = c.tc and c.tt='GRP') and ".
            "(upper(b.tdesc) LIKE '%".strtoupper($_GET["search"])."%')";
*/
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = 14;
    $t->ColAlign[1] = "CENTER";
    $t->ColAlign[3] = "CENTER";
    $t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID".
                            "&e=<#3#>".
                            "&j=".$_GET["mJENJANG"].
                            "&u=".$_GET["mUNSUR"].
                            "'>".icon("edit","Edit")."</A>";
    $t->ColHeader = array("NAMA JENJANG PANGKAT", "GOL.RUANG", "AKKM STANDARD", "E d i t");
    $t->execute();

    echo "<BR><DIV ALIGN=RIGHT><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new&j=".$_GET["mJENJANG"]."&u=".$_GET["mUNSUR"]."'>&#171; Tambah Data Standard AKKM &#187;</A></DIV>";
}

?>
