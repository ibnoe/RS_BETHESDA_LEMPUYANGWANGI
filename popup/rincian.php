<?php // Nugraha, Mon Apr  5 21:58:16 WIT 2004
session_start();

for ($n = 1; $n < 5; $n++) if (isset($_GET["L$n"])) $_SESSION["LAYANAN_L$n"] = $_GET["L$n"];

unset($_SESSION["SELECT_LAYANAN"]);

if (isset($_GET["e"])) {
    $_SESSION["SELECT_LAYANAN"] = $_GET["e"];
    ?>
    <SCRIPT language="JavaScript">
        window.opener.location = window.opener.location;
        window.close();
    </SCRIPT>
    <?php
    exit;
}

?>
<HTML>
<HEAD>
    <TITLE>Pilih Layanan</TITLE>
    <LINK rel='StyleSheet' type='text/css' href='../default.css'>
</HEAD>
<BODY>
<TABLE border="0" bgcolor="#FFFFFF" width="100%" cellpadding="8"><TR><TD>
<?php

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");

function getLevel($hcode)
{
    if (strlen($hcode) != 15) return 0;
    if (substr($hcode,  4, 12) == str_repeat("0", 12)) return 1;
    if (substr($hcode,  7,  9) == str_repeat("0",  9)) return 2;
    if (substr($hcode, 10,  6) == str_repeat("0",  6)) return 3;
    if (substr($hcode, 13,  3) == str_repeat("0",  3)) return 4;
    return 5;
}

title("Kategori Rincian Kegiatan");

$ext = "OnChange = 'Form1.submit();'";
$level = 0;

$f = new Form("rincian.php", "GET", "NAME=Form1");
$f->PgConn = $con;
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
$t->ColFormatHtml[0] =
    "<A HREF='../index.php?p=860&e=<#0#>'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";
$t->ColHeader = Array("&nbsp;", "NAMA RINCIAN KEGIATAN");
//$t->ShowSQL = true;
$t->execute();

?>
</TD></TR></TABLE>
</BODY>
</HTML>
