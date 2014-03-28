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

title("Pilih Layanan");
$ext = "OnChange = 'Form1.submit();'";
$level = 0;

$f = new Form("layanan.php", "GET", "NAME=Form1");
$f->PgConn = $con;

//$SQLSTR .= "select tipe from rs00006 ";
//$zal = tipe
//if ($zal == "006") {
//$SQLWHERE1 .= " AND upper(a.pasien) NOT LIKE('%DINAS%')";}

$f->selectSQL("L1", "Grup Layanan",
    "select '' as hierarchy, '- - - - - - - - - - - - SEMUA - - - - - - - - - - - - ' as layanan union " .
    "select hierarchy, layanan ".
    "from rs00034 ".
    "where substr(hierarchy,4,12) = '000000000000' ".
    "and is_group = 'Y' and status='1' ".
    "order by layanan", $_SESSION["LAYANAN_L1"],
    $ext);
   
if (strlen($_SESSION["LAYANAN_L1"]) > 0) $level = 1;
if (getFromTable(
        "select hierarchy, layanan ".
        "from rs00034 ".
        "where substr(hierarchy,7,9) = '000000000' ".
        "and substr(hierarchy,1,3) = '".substr($_SESSION["LAYANAN_L1"],0,3)."' ".
        "and hierarchy != '".$_SESSION["LAYANAN_L1"]."' ".
        "and is_group = 'Y' and status='1'")
    && strlen($_SESSION["LAYANAN_L1"]) > 0) {
    $f->selectSQL("L2", "",
        "select '' as hierarchy, '' as layanan union " .
        "select hierarchy, layanan ".
        "from rs00034 ".
        "where substr(hierarchy,7,9) = '000000000' ".
        "and substr(hierarchy,1,3) = '".substr($_SESSION["LAYANAN_L1"],0,3)."' ".
        "and hierarchy != '".$_SESSION["LAYANAN_L1"]."' ".
        "and is_group = 'Y' and status='1' ".
        "order by layanan", $_SESSION["LAYANAN_L2"],
        $ext);
    if (strlen($_SESSION["LAYANAN_L2"]) > 0) $level = 2;
    if (getFromTable(
            "select hierarchy, layanan ".
            "from rs00034 ".
            "where substr(hierarchy,10,6) = '000000' ".
            "and substr(hierarchy,1,6) = '".substr($_SESSION["LAYANAN_L2"],0,6)."' ".
            "and hierarchy != '".$_SESSION["LAYANAN_L2"]."' ".
            "and is_group = 'Y' and status='1'")
        && strlen($_SESSION["LAYANAN_L1"]) > 0
        && strlen($_SESSION["LAYANAN_L2"]) > 0) {
        $f->selectSQL("L3", "",
            "select '' as hierarchy, '' as layanan union " .
            "select hierarchy, layanan ".
            "from rs00034 ".
            "where substr(hierarchy,10,6) = '000000' ".
            "and substr(hierarchy,1,6) = '".substr($_SESSION["LAYANAN_L2"],0,6)."' ".
            "and hierarchy != '".$_SESSION["LAYANAN_L2"]."' ".
            "and is_group = 'Y' and status='1' ".
            "order by layanan", $_SESSION["LAYANAN_L3"],
            $ext);
        if (strlen($_SESSION["LAYANAN_L3"]) > 0) $level = 3;
        if (getFromTable(
                "select hierarchy, layanan ".
                "from rs00034 ".
                "where substr(hierarchy,13,3) = '000' ".
                "and substr(hierarchy,1,9) = '".substr($_SESSION["LAYANAN_L3"],0,9)."' ".
                "and hierarchy != '".$_SESSION["LAYANAN_L3"]."' ".
                "and is_group = 'Y' and status='1'")
            && strlen($_SESSION["LAYANAN_L1"]) > 0
            && strlen($_SESSION["LAYANAN_L2"]) > 0
            && strlen($_SESSION["LAYANAN_L3"]) > 0) {
            $f->selectSQL("L4", "",

                "select '' as hierarchy, '' as layanan union " .
                "select hierarchy, layanan ".
                "from rs00034 ".
                "where substr(hierarchy,13,3) = '000' ".
                "and substr(hierarchy,1,9) = '".substr($_SESSION["LAYANAN_L3"],0,9)."' ".
                "and hierarchy != '".$_SESSION["LAYANAN_L3"]."' ".
                "and is_group = 'Y' and status='1' ".
                "order by layanan", $_SESSION["LAYANAN_L4"],
                $ext);
                if (strlen($_SESSION["LAYANAN_L4"]) > 0) $level = 4;
        }
    }
}
$f->execute();
 //}


    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION='layanan.php'><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=L1 VALUE='".$_SESSION["LAYANAN_L1"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=L2 VALUE='".$_SESSION["LAYANAN_L2"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=L3 VALUE='".$_SESSION["LAYANAN_L3"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=L4 VALUE='".$_SESSION["LAYANAN_L4"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=level VALUE='$level'>";
    echo "<TD class=form_title>Cari: <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Nama Layanan'></TD>";
    echo "</TR></FORM></TABLE></DIV>";

$SQL1 = "select a.id, a.layanan, a.harga, b.tdesc as satuan ".
        "from rs00034 as a ".
        "left join rs00001 as b on a.satuan_id = b.tc and b.tt = 'SAT' ".
        "left join rs00001 as c on a.klasifikasi_tarif_id = c.tc and c.tt = 'KTR' ".
	"left join rs00001 as d on a.sumber_pendapatan_id = d.tc and d.tt = 'SBP' ".
        "left join rs00001 as e on a.golongan_tindakan_id = e.tc and e.tt = 'GTD' ".

        "where substr(a.hierarchy,1,".($level*3).") = '".substr($_SESSION["LAYANAN_L$level"],0,($level*3))."' ".
        "and a.hierarchy <> '".$_SESSION["LAYANAN_L$level"]."' ".
        "and substr(a.hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
        str_repeat("0",15-(($level*3)+3))."' ".
        "and is_group = 'N' AND status='1' ".
		"and upper(a.layanan) LIKE '%".strtoupper($_GET["search"])."%' ";
$SQL1Counter =
        "select count(*) ".
        "from rs00034 as a ".
        "where substr(a.hierarchy,1,".($level*3).") = '".substr($_SESSION["LAYANAN_L$level"],0,($level*3))."' ".
        "and a.hierarchy <> '".$_SESSION["LAYANAN_L$level"]."' ".
        "and substr(a.hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
        str_repeat("0",15-(($level*3)+3))."' ".
        "and is_group = 'N' AND status='1' ";
$SQL2 = "select a.layanan, a.id ".
        "from rs00034 as a ".
        "left join rs00001 as b on a.satuan_id = b.tc and b.tt = 'SAT' ".
        "left join rs00001 as c on a.klasifikasi_tarif_id = c.tc and c.tt = 'KTR' ".
        "where substr(a.hierarchy,1,".($level*3).") = '".substr($_SESSION["LAYANAN_L$level"],0,($level*3))."' ".
        "and a.hierarchy <> '".$_SESSION["LAYANAN_L$level"]."' ".
        "and substr(a.hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
        str_repeat("0",15-(($level*3)+3))."'";
$SQL2Counter =
        "select counter(*) ".
        "from rs00034 as a ".
        "where substr(a.hierarchy,1,".($level*3).") = '".substr($_SESSION["LAYANAN_L$level"],0,($level*3))."' ".
        "and a.hierarchy <> '".$_SESSION["LAYANAN_L$level"]."' ".
        "and substr(a.hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
        str_repeat("0",15-(($level*3)+3))."'";

if (!isset($_GET[sort])) {

           $_GET[sort] = "layanan";
           $_GET[order] = "asc";
}
if ($_SESSION["LAYANAN_L1"]){ 

$t = new PgTable($con, "100%");
$t->SQL = $SQL1;
$t->SQLCounter = $SQL1Counter;
$t->setlocale("id_ID");
$t->ShowRowNumber = true;
if ($_SESSION["LAYANAN_L2"] == '005002000000000') {
	$t->ColFormatHtml[0] =
    "<IMG BORDER=0 SRC='../images/22_login.gif'>&nbsp<b>Otomatis</b>";
	$t->ColAlign[0] = "LEFT";
} else {
$t->ColFormatHtml[0] =
    "<A HREF='layanan.php?e=<#0#>'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";
}
$t->ColHeader = Array("&nbsp;", "LAYANAN", "TARIF", "SATUAN");

//$t->ShowSQL = true;
$t->execute();

}else{
	
$SQL = "select a.id, a.layanan, a.harga, b.tdesc as satuan ".
        "from rs00034 as a ".
        "left join rs00001 as b on a.satuan_id = b.tc and b.tt = 'SAT' ".
        "left join rs00001 as c on a.klasifikasi_tarif_id = c.tc and c.tt = 'KTR' ".
		"left join rs00001 as d on a.sumber_pendapatan_id = d.tc and d.tt = 'SBP' ".
        "left join rs00001 as e on a.golongan_tindakan_id = e.tc and e.tt = 'GTD' ".
        "where is_group = 'N' AND status='1' ".
		"and upper(a.layanan) LIKE '%".strtoupper($_GET["search"])."%' ";
			
$t = new PgTable($con, "100%");
$t->SQL = $SQL;
$t->SQLCounter = $SQL1Counter;
$t->setlocale("id_ID");
$t->ShowRowNumber=true;
$t->ColFormatHtml[0] =
    "<A HREF='layanan.php?e=<#0#>'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";
$t->ColHeader = Array("&nbsp;", "LAYANAN", "TARIF", "SATUAN");
$t->execute();
}
?>
</TD></TR></TABLE>
</BODY>
</HTML>
