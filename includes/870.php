<?php // Nugraha, Mon Apr  5 21:58:16 WIT 2004

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/class.PgTrans.php");

$PID = "870";
$SC = $_SERVER["SCRIPT_NAME"];
if ($_GET["e"] == "edit") {
    $tr = new PgTrans;
    $tr->PgConn = $con;
    echo "<DIV ALIGN=RIGHT><A HREF='../index2.php?p=860".
                                             "&L1=".$_GET["L1"].
                                             "&L2=".$_GET["L2"].
                                             "&mITEM=".$_GET["L3"].
                                             "'>".icon("back","Kembali")."</a></DIV>";

    $tr->addSQL("update rs00034 set id_rincian = lpad(".$_GET["f"].",8,'0') ".
            " where id = '".$_GET["L3"]."'");
    $tr->execute();

    //header("Location:../index2.php?p=860".
    //                          "&L1=".$_GET["L1"].
    //                          "&L2=".$_GET["L2"].
    //                          "&mITEM=".$_GET["L3"]);

    //exit;

} else {

    $ext = "OnChange = 'Form1.submit();'";
    $level = 0;
    $t = new Form($SC, "GET", "NAME=Form2");
    // group layanan
    $r2 = pg_query($con,
        "select * ".
        "from rs00034 ".
        "where hierarchy='".$_GET["L1"]."'");
    $d2 = pg_fetch_object($r2);
    pg_free_result($r2);
    // sub-group layanan
    $r3 = pg_query($con,
        "select * ".
        "from rs00034 ".
        "where hierarchy='".$_GET["L2"]."'");
    $d3 = pg_fetch_object($r3);
    pg_free_result($r3);
    // Nama layanan
    $r4 = pg_query($con,
        "select * ".
        "from rs00034 ".
        "where id='".$_GET["L3"]."'");
    $d4 = pg_fetch_object($r4);
    pg_free_result($r4);
    title("Data Layanan");
    $t->text("x1","Group Layanan/Tindakan",50,50,$d2->layanan);
    $t->text("x2","Sub-Group Layanan/Tindakan",50,50,$d3->layanan);
    $t->text("x3","Nama Layanan/Tindakan",50,50,$d4->layanan);
    $t->execute();
    echo "<br>";
    title("Kategori Rincian Kegiatan AKKM");
    echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->hidden("p", $PID);
    $f->hidden("L1",$_GET["L1"]);
    $f->hidden("L2",$_GET["L2"]);
    $f->hidden("L3",$_GET["L3"]);
    $f->PgConn = $con;

    echo "<div class=BOX>";
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

    $SQL1 = "select id_rincian,nama_rincian_kegiatan ".
            "from rs00025  ".
            "where id_bidang='".$_GET["mBIDANG"]."'";


    $t = new PgTable($con, "100%");
    $t->SQL = $SQL1;
    $t->setlocale("id_ID");
    $t->RowsPerPage = 10;
    $t->ColAlign[0] = "CENTER";
    $t->ColFormatHtml[0] =
    $t->ColFormatHtml[3] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&f=<#0#>".
                            "&e=edit".
                            "&L1=".$_GET["L1"].
                            "&L2=".$_GET["L2"].
                            "&L3=".$_GET["L3"]."'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";

    $t->ColHeader = Array("&nbsp;", "NAMA RINCIAN KEGIATAN");
    //$t->ShowSQL = true;
    $t->execute();
}

?>
