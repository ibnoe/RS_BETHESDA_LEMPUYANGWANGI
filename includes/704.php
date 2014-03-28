<? // Nugraha, 23/02/2004
   // Pur, 09/03/2004: new libs table
   

$PID = "704";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

title("Sensus Pasien per Unit Layanan");

if (isset($_GET["e"])) {
    $ext = "DISABLED";
} else {
    $ext = "OnChange = 'Form1.submit();'";
}
echo "<br>";
$f = new Form($SC, "GET", "NAME=Form1");
$f->PgConn = $con;
$f->hidden("p", $PID);
$f->selectSQL("mPEG", "Unit Layanan",
    "select '' as tc, '' as tdesc union " .
    "select tc, tdesc ".
    "from rs00001 ".
    "where tt = 'ULY' and tc!='000' and tc!='001' and tc!='006'".
    "order by tdesc", $_GET["mPEG"],
    $ext);
$f->selectSQL("mJAB", "Sub-Unit Layanan",
    "select '' as id, '' as sub_unit_layanan union " .
    "select id, sub_unit_layanan ".
    "from rs00003 ".
    "where unit_layanan_id = '" . $_GET["mPEG"] . "' ".
    "order by sub_unit_layanan", $_GET["mJAB"],
    $ext);
$f->execute();
/*
$is_selected = getFromTable(
    "select count(id) ".
    "from rs00018 ".
    "where unit_medis_fungsional_id = '" . $_GET["mPEG"] . "' ".
    "    and id = '" . $_GET["mJAB"] . "'") > 0;
*/

    echo "<br>";

if (isset($_GET["e"])) {
    echo "<div align=right><a href='".
        "$SC?p=$PID&mPEG=".$_GET["mPEG"]."&mJAB=".$_GET["mJAB"].
        "'>".icon("back", "Kembali")."</a></div>";
    echo "<div class=BOX>";

    if ($_GET["e"] == "new") {

        title("Tambah Data Pegawai");
        echo "<br>";
        $f = new Form("$SC", "GET", "name='Form2'");
        $f->PgConn = $con;
        $f->hidden("p", $PID);
        $f->hidden("mPEG", $_GET["mPEG"]);
        $f->hidden("mJAB", $_GET["mJAB"]);
        $f->hidden("e", "new");
        $f->hidden("f_jabatan_medis_fungsional_id", $_GET["mJAB"]);
        $f->text("f_id","Kode",12,12,"&lt;OTOMATIS&gt;","DISABLED");
        $f->text("f_nama","N a m a",50,50,$_GET["f_nama"]);
        $f->text("f_nip","N I P",12,12,$_GET["f_nip"]);
        $f->text("f_golongan","Golongan",10,10,$_GET["f_golongan"]);
        $f->selectDate("f_tanggal_lahir", "Tanggal Lahir", getDate(mktime(0,0,0,(int)date("m"),(int)date("d"),((int)date("Y"))-30)));
        $f->selectSQL("f_agama_id", "Agama",
                  "select tc, tdesc from rs00001 where tt = 'AGM' and tc != '000'",
                  $d->agama_id);

        $f->submit(" Simpan ", "onClick='Form2.method=\"POST\";Form2.action=\"actions/809.insert.php\";'");
        $f->execute();
    } else {

        title("Edit Data Pegawai");
        echo "<br>";
        $f = new Form("$SC", "GET", "name='Form2'");
        $f->PgConn = $con;
        $f->hidden("p", $PID);
        $f->hidden("mPEG", $_GET["mPEG"]);
        $f->hidden("mJAB", $_GET["mJAB"]);

        $r2 = pg_query($con,
            "select * ".
            "from rsv0006 ".
            "where id = '".$_GET["e"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);

        $f->hidden("id", $d2->id);
        $f->hidden("f_jabatan_medis_fungsional_id", $_GET["mJAB"]);
        $f->text("id","Kode",12,12,$d2->id,"DISABLED");
        $f->text("f_nama","N a m a",50,50,$d2->nama);
        $f->text("f_nip","N I P",12,12,$d2->nip);
        /*$f->text("f_golongan","Golongan",10,10,$d2->golongan);*/
        $f->selectSQL("f_jjd_id", "Jenjang Jabatan",
                  "select '-' as tc,'-' as tdesc union select tc, tdesc from rs00001 where tt = 'JJD' and tc != '000'",
                  $d2->jjd_id);
        $f->selectSQL("f_gol_ruang_id", "Gol.Ruang",
                  "select '-' as tc,'-' as tdesc union select tc, tdesc from rs00001 where tt = 'GRP' and tc != '000'",
                  $d2->gol_ruang_id);

        $f->selectDate("f_tanggal_lahir", "Tanggal Lahir", pgsql2phpdate($d2->tanggal_lahir));
        $f->selectSQL("f_agama_id", "Agama",
                  "select tc, tdesc from rs00001 where tt = 'AGM' and tc != '000'",
                  $d2->agama_id);


        $f->submit(" Simpan ", "onClick='Form2.method=\"POST\";Form2.action=\"actions/809.update.php\";'");
        $f->execute();
    }
    echo "</div>";
    echo "<br>";
    if (isset($_GET["err"])) errmsg("Terjadi Kesalahan",stripslashes($_GET["err"]));

} else {
    if ($is_selected) {
        // search box

        echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
        echo "<INPUT TYPE=HIDDEN NAME=mPEG VALUE='".$_GET["mPEG"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=mJAB VALUE='".$_GET["mJAB"]."'>";
        echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
        echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
        echo "</TR></FORM></TABLE></DIV>";

        $t = new PgTable($con, "100%");
        $t->SQL =   "select nama, nip, ruang, jabatan,agama, tanggal_lahir, id as dummy ".
                    "from rsv0006 ".
                    "where jabatan_medis_fungsional_id = '" . $_GET["mJAB"] . "' ".
                    "and ".
                    "(upper(nama) LIKE '%".strtoupper($_GET["search"])."%' OR ".
                    "upper(golongan) LIKE '%".strtoupper($_GET["search"])."%' OR ".
                    "upper(agama) LIKE '%".strtoupper($_GET["search"])."%' )".
                    "order by nip";
        $t->setlocale("id_ID");
        $t->ColAlign[2] = "CENTER";    
        $t->ShowRowNumber = true;
        $t->RowsPerPage = $ROWS_PER_PAGE;
        $t->ColFormatHtml[6] =
            "<A CLASS=TBL_HREF HREF='".
            "$SC?p=$PID".
            "&mPEG=" . $_GET["mPEG"] .
            "&mJAB=" . $_GET["mJAB"] .
            "&e=<#6#>".
            "'>".icon("edit","Edit")."</A></nobr>";
        $t->ColHeader = Array("NAMA", "NIP", "GOLONGAN", "JENJANG JABATAN","AGAMA", "TANGGAL LAHIR", "&nbsp;");
        $t->execute();

        echo "<BR><DIV ALIGN=RIGHT><A CLASS=SUB_MENU ".
            "HREF='index2.php".
            "?p=$PID".
            "&mPEG=".$_GET["mPEG"].
            "&mJAB=".$_GET["mJAB"].
            "&e=new'>".
            "&#171; Tambah Data Pegawai &#187;</A></DIV>";
    } else {
        echo "<BR><DIV ALIGN=RIGHT CLASS=SUB_MENU_DISABLED>".
            "&#171; Tambah Data Pegawai &#187;</DIV>";
    }
}

?>
