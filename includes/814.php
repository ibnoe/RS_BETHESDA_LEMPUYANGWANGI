<? // Nugraha, 17/02/2004
   // Pur, 27/02/2004
   // Pur, 27/03/2004 : new libs table
   // sfdn, 20-04-2004
   // sfdn, 30-04-2004
   // sfdn, 19-05-2004
   

$PID = "814";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

/*
$r = pg_query($con,"select * from rsv0027");
$d = pg_fetch_object($r);
pg_free_result($r);
*/


if(strlen($_GET["e"]) > 0) {
    if($_GET["e"] == "new") {
        $f = new Form("actions/814.insert.php");
        title("Insert Bidang Kegiatan Penilaian");
        echo "<BR>";
        $f->hidden("unsur",$_GET["u"]);
        $f->hidden("sub",$_GET["s"]);
        $f->text("id","ID",12,12,"<OTOMATIS>","DISABLED");
    } else {
        $r2 = pg_query($con,
            "select * ".
            "from rs00024 ".
            "where id='".$_GET["e"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
        $f = new Form("actions/814.update.php");
        title("Edit Bidang Kegiatan Penilaian");
        echo "<BR>";
        $f->hidden("id",$_GET["e"]);
        $f->hidden("unsur",$_GET["u"]);
        $f->hidden("sub",$_GET["s"]);
        $f->text("id","ID",4,4,$_GET["e"],"DISABLED");

        $r = pg_query($con,"select * from rs00023 where id_kegiatan = '" . $d2->id_kegiatan . "'");
        $d = pg_fetch_object($r);
        pg_free_result($r);

    }

    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID&mUNSUR=".$_GET["u"]."&mSUBUNSUR=".$_GET["s"]."'>".icon("back","Kembali")."</a></DIV>";
    $f->PgConn = $con;

    $f->selectSQL("", "Unsur kegiatan",
                  "select tc, tdesc from rs00001 where tt='UKP' ".
                  "and tc = '".$_GET[u]."'",
                  $d->unsur_id,"");

    $f->selectSQL("f_id_kegiatan", "Sub-Unsur Kegiatan",
                  "select id_kegiatan, nama_sub_unsur from rs00023 ".
                  "where id_kegiatan = '".$_GET[s]."' ",
                  $d2->id_kegiatan);

    $f->textarea("f_nama_bidang_kegiatan", "Nama Bidang Kegiatan Penilaian", 4, 50, $d2->nama_bidang_kegiatan);


    /*$f->text("f_nama_bidang_kegiatan","Nama Bidang Kegiatan Penilaian",50,50,$d2->nama_bidang_kegiatan);*/

    $f->submit(" Simpan ");
    $f->execute();
    echo "<br>";
    if(strlen($_GET["err"]) > 0) {
        errmsg("Terjadi Kesalahan", stripslashes($_GET["err"]));
    }
} else {

    title("Tabel Master: Bidang Kegiatan Penilaian");
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
    $f->selectSQL("mSUBUNSUR", "Sub-Unsur Kegiatan",
        "select '' as id_kegiatan, '' as nama_sub_unsur union ".
        "select id_kegiatan, nama_sub_unsur ".
        "from rs00023 ".
        "where unsur_id = '" . $_GET["mUNSUR"] . "' ".
        "order by nama_sub_unsur", $_GET["mSUBUNSUR"],
        $ext);
    $f->execute();
    echo "<br>";
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<INPUT TYPE=HIDDEN NAME=mUNSUR VALUE='".$_GET["mUNSUR"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=mSUBUNSUR VALUE='".$_GET["mSUBUNSUR"]."'>";
    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Bidang Kegiatan '></TD>";
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL =
        "select a.nama_bidang_kegiatan, a.id as dummy ".
        "from rs00024 a,rs00023 b,rs00001 c ".
        "where (a.id_kegiatan=b.id_kegiatan and b.unsur_id = c.tc and c.tt='UKP') and ".
            "(upper(a.nama_bidang_kegiatan) LIKE '%".strtoupper($_GET["search"])."%') and ".
            "a.id_kegiatan='".$_GET["mSUBUNSUR"]."'";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = 14;
    /*$t->ColAlign[0] = "CENTER";*/
    $t->ColAlign[1] = "CENTER";
    $t->ColFormatHtml[1] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#1#>&u=".$_GET["mUNSUR"]."&s=".$_GET["mSUBUNSUR"]."'>".icon("edit","Edit")."</A>";
    $t->ColHeader = array("NAMA BIDANG KEGIATAN", "E d i t");

    $t->execute();

    echo "<BR><DIV ALIGN=RIGHT><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new&u=".$_GET["mUNSUR"]."&s=".$_GET["mSUBUNSUR"]."'>&#171; Tambah Data Bidang Kegiatan &#187;</A></DIV>";
}

?>
