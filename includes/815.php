<? // Nugraha, 17/02/2004
   // Pur, 27/02/2004
   // Pur, 27/03/2004 : new libs table
   // sfdn, 20-04-2004
   // sfdn, 30-04-2004
   // sfdn, 20-05-2004

$PID = "815";
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
        $f = new Form("actions/815.insert.php");
        title("Insert Rincian Kegiatan Penilaian");
        echo "<BR>";
        $f->text("id","ID",12,12,"<OTOMATIS>","DISABLED");
        $f->hidden("u",$_GET["u"]);
        $f->hidden("s",$_GET["s"]);
        $f->hidden("b",$_GET["b"]);
    } else {
        // table rincian
	$x = (int) $_GET[e];
//echo $x;
        $r2 = pg_query($con,
            "select * ".
            "from rs00025 ".
            "where id=".$x);
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);

        $f = new Form("actions/815.update.php");
        title("Edit Rincian Kegiatan Penilaian");
        echo "<BR>";
        $f->hidden("id",$_GET["e"]);
        $f->hidden("u",$_GET["u"]);
        $f->hidden("s",$_GET["s"]);
        $f->hidden("b",$_GET["b"]);
        $f->text("id","ID",10,4,$_GET["e"],"DISABLED");

    }
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID&mUNSUR=".$_GET["u"].
                                             "&mSUBUNSUR=".$_GET["s"].
                                             "&mBIDANG=".$_GET["b"].
                                             "'>".icon("back","Kembali")."</a></DIV>";
        // table unsur kegiatan
        $r3 = pg_query($con,
            "select tdesc ".
            "from rs00001 ".
            "where tc='".$_GET["u"]."'");
        $d3 = pg_fetch_object($r3);
        pg_free_result($r3);

        // table sub-unsur kegiatan
        $r4 = pg_query($con,
            "select * ".
            "from rs00023 ".
            "where id_kegiatan='".$_GET["s"]."'");
        $d4 = pg_fetch_object($r4);
        pg_free_result($r4);

        // table Bidang kegiatan
        $r5 = pg_query($con,
            "select * ".
            "from rs00024 ".
            "where id_bidang='".$_GET["b"]."'");
        $d5 = pg_fetch_object($r5);
        pg_free_result($r5);

    $f->PgConn = $con;
    $f->selectSQL("", "Unsur Kegiatan",
                  "select tc, tdesc ".
                  "from rs00001 where tt='UKP' and tc = '".$_GET[u]."'",
                  $d3->tdesc);
    $f->selectSQL("", "Sub-Unsur Kegiatan",
                  "select id_kegiatan, nama_sub_unsur ".
                  "from rs00023 where id_kegiatan = '".$_GET[s]."'",
                  $d4->id_kegiatan);
    $f->selectSQL("f_id_bidang", "Bidang Kegiatan",
                  "select id_bidang, nama_bidang_kegiatan ".
                  "from rs00024 where id_bidang = '".$_GET[b]."'",
                  $d5->id_bidang);

    if (empty($d2->prasyarat)) {
       $d2->prasyarat = 0;
    }
    $f->text("f_prasyarat","Prasyarat(Angka)",4,5,$d2->prasyarat);
    $f->selectSQL("f_satuan_id", "Prasyarat(Satuan)",
                  "select tc,tdesc from rs00001 ".
                  "where tt='SAT' and tc in ('020','021','022','050')",
                  $d2->satuan_id);
    $f->textarea("f_nama_rincian_kegiatan", "Nama Rincian Kegiatan Penilaian", 4, 50, $d2->nama_rincian_kegiatan);
    $f->submit(" Simpan ");
    $f->execute();
    echo "<br>";
    if(strlen($_GET["err"]) > 0) {
        errmsg("Terjadi Kesalahan", stripslashes($_GET["err"]));
    }
} else {

    title("Tabel Master: Rincian Kegiatan Penilaian");
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
    $f->selectSQL("mBIDANG", "Bidang Kegiatan Penilaian",
         "select '' as id_bidang, '' as nama_bidang_kegiatan union ".
         "select id_bidang, nama_bidang_kegiatan ".
         "from rs00024 a, rs00023 b ".
         "where b.id_kegiatan = '" .$_GET["mSUBUNSUR"]."' and ".
            "b.id_kegiatan = a.id_kegiatan and ".
            "b.unsur_id = '".$_GET["mUNSUR"]."' ".
        "order by nama_bidang_kegiatan", $_GET["mBIDANG"],
        $ext);
    $f->execute();
    echo "<br>";
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<INPUT TYPE=HIDDEN NAME=mUNSUR VALUE='".$_GET["mUNSUR"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=mSUBUNSUR VALUE='".$_GET["mSUBUNSUR"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=mBIDANG VALUE='".$_GET["mBIDANG"]."'>";
    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Nama Rincian '></TD>";
    echo "</TR></FORM></TABLE></DIV>";
    echo "<br>";
    $t = new PgTable($con, "100%");
    $t->SQL =
        "SELECT a.nama_rincian_kegiatan, a.prasyarat, d.tdesc AS satuan,a.id_rincian ".
        "FROM rs00025 a, rs00023 b, rs00024 c, rs00001 d, rs00001 e ".
        "where (a.id_bidang = '".$_GET["mBIDANG"]."' and a.id_bidang = c.id_bidang) and ".
            "(c.id_kegiatan ='".$_GET["mSUBUNSUR"]."' and c.id_kegiatan = b.id_kegiatan) and ".
            "(b.unsur_id ='".$_GET["mUNSUR"]."' and b.unsur_id = e.tc and e.tt='UKP') and ".
	        "a.satuan_id = d.tc and d.tt='SAT' and ".
            "upper(a.nama_rincian_kegiatan) LIKE '%".strtoupper($_GET["search"])."%'";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = 14;
    /*$t->ColAlign[0] = "CENTER";*/
    $t->ColAlign[3] = "CENTER";
    $t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#3#>".
                           "&u=".$_GET["mUNSUR"]."&s=".$_GET["mSUBUNSUR"].
                           "&b=".$_GET["mBIDANG"]."'>".icon("edit","Edit")."</A>";
    $t->ColHeader = array("NAMA RINCIAN KEGIATAN", "PRASYARAT", "SATUAN","E d i t");

    $t->execute();
    //if ($is_selected) {

        echo "<BR><DIV ALIGN=RIGHT><A CLASS=SUB_MENU ".
             "HREF='index2.php?p=$PID&e=new".
                          "&u=".$_GET["mUNSUR"].
                          "&s=".$_GET["mSUBUNSUR"].
                          "&b=".$_GET["mBIDANG"]."'>&#171; ".
                          "Tambah Data Rincian Kegiatan &#187;</A></DIV>";
    //} else {
    //    echo "<BR><DIV ALIGN=RIGHT CLASS=SUB_MENU_DISABLED>".
    //        "&#171; Tambah Data Rincian Kegiatan &#187;</DIV>";
    //}

}

?>
