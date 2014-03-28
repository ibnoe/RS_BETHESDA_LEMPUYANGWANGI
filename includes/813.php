<? // Nugraha, 17/02/2004
   // Pur, 27/02/2004
   // Pur, 27/03/2004 : new libs table
   // sfdn, 20-04-2004
   // sfdn, 30-04-2004
   // sfdn, 19-05-2004
   
$PID = "813";
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
        $f = new Form("actions/813.insert.php");
        title("Insert Sub-Unsur Kegiatan");
        echo "<BR>";
        $f->text("id","ID",12,12,"<OTOMATIS>","DISABLED");
    } else {
        $r2 = pg_query($con,
            "select * ".
            "from rs00023 ".
            "where id_kegiatan='".$_GET["e"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
        $f = new Form("actions/813.update.php");
        title("Edit Sub-Unsur Kegiatan");
        echo "<BR>";
        $f->hidden("id_kegiatan",$_GET["e"]);
        $f->text("id","ID",4,4,$_GET["e"],"DISABLED");
    }
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    $f->PgConn = $con;
    /*
    $f->text("f_jenjang","Jenjang Jabatan",40,50,$d2->jabatan);
    */

    $f->selectSQL("f_unsur_id", "Unsur Kegiatan",
                  "select tc, tdesc from rs00001 where tt='UKP' and tc<>'000'",
                  $d2->unsur_id);

    /*
    $f->text("f_ruang","Gol.Ruang",40,50,$d2->ruang);


    $f->selectSQL("f_gol_ruang_id", "Gol. Ruang",
                  "select tc, tdesc from rs00001 where tt='GRP' and tc<>'000'",
                  $d2->tc);

    */

    $f->text("f_nama_sub_unsur","Nama Sub-Unsur Kegiatan",40,50,$d2->nama_sub_unsur);

    $f->submit(" Simpan ");
    $f->execute();
    echo "<br>";
    if(strlen($_GET["err"]) > 0) {
        errmsg("Terjadi Kesalahan", stripslashes($_GET["err"]));
    }
} else {

    title("Tabel Master: Sub-Unsur Kegiatan Penilaian");
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Unsur/Sub-Unsur '></TD>";
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL =
        "select b.tdesc as kegiatan, a.nama_sub_unsur, a.id_kegiatan as dummy ".
        "from rs00023 a,rs00001 b ".
        "where a.unsur_id=b.tc and b.tt='UKP' and ".
        "(upper(b.tdesc) LIKE '%".strtoupper($_GET["search"])."%' ".
        "OR upper(a.nama_sub_unsur) LIKE '%".strtoupper($_GET["search"])."%')";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = 14;
    /*$t->ColAlign[0] = "CENTER";*/
    $t->ColAlign[2] = "CENTER";
    $t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#2#>'>".icon("edit","Edit")."</A>";
    $t->ColHeader = array("UNSUR KEGIATAN", "SUB-UNSUR KEGIATAN", "E d i t");

    $t->execute();

    echo "<BR><DIV ALIGN=RIGHT><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>&#171; Tambah Data Sub-Unsur Kegiatan &#187;</A></DIV>";
}

?>
