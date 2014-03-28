<?php // Nugraha, Mon Apr  5 21:58:16 WIT 2004
	// Ian, 01-12-2007 Custom Jenis Pemeriksaan Lab
session_start();

for ($n = 1; $n < 5; $n++) if (isset($_GET["L$n"])) $_SESSION["LAB_L$n"] = $_GET["L$n"];
unset($_SESSION["SELECT_LAB"]);
if (isset($_GET["e"])) {
    $_SESSION["SELECT_LAB"] = $_GET["e"];
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
    <TITLE>Pilih Jenis Pemeriksaan</TITLE>
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

$ext = "OnChange = 'Form1.submit();'";
$level = 0;

$f = new Form("laboratorium.php", "GET", "NAME=Form1");
$f->PgConn = $con;

/*$f->selectSQL("L1", "Grup Parameter",
    "select '' as hierarchy, '- - - - - - - - - - - - SEMUA - - - - - - - - - - - - ' as parameter union " .
    "select hierarchy, parameter ".
    "from c_pemeriksaan_lab ".
    "where substr(hierarchy,4,12) = '000000000000' ".
    "and is_group = 'Y' ".
    "order by parameter", $_SESSION["LAB_L1"],
    $ext);
   
if (strlen($_SESSION["LAB_L1"]) > 0) $level = 1;
if (getFromTable(
        "select hierarchy, parameter ".
        "from c_pemeriksaan_lab ".
        "where substr(hierarchy,7,9) = '000000000' ".
        "and substr(hierarchy,1,3) = '".substr($_SESSION["LAB_L1"],0,3)."' ".
        "and hierarchy != '".$_SESSION["LAB_L1"]."' ".
        "and is_group = 'Y'")
    && strlen($_SESSION["LAB_L1"]) > 0) {
    $f->selectSQL("L2", "",
        "select '' as hierarchy, '' as parameter union " .
        "select hierarchy, parameter ".
        "from c_pemeriksaan_lab ".
        "where substr(hierarchy,7,9) = '000000000' ".
        "and substr(hierarchy,1,3) = '".substr($_SESSION["LAB_L1"],0,3)."' ".
        "and hierarchy != '".$_SESSION["LAB_L1"]."' ".
        "and is_group = 'Y' ".
        "order by parameter", $_SESSION["LAB_L2"],
        $ext);
    if (strlen($_SESSION["LAB_L2"]) > 0) $level = 2;
    if (getFromTable(
            "select hierarchy, parameter ".
            "from c_pemeriksaan_lab ".
            "where substr(hierarchy,10,6) = '000000' ".
            "and substr(hierarchy,1,6) = '".substr($_SESSION["LAB_L2"],0,6)."' ".
            "and hierarchy != '".$_SESSION["LAB_L2"]."' ".
            "and is_group = 'Y'")
        && strlen($_SESSION["LAB_L1"]) > 0
        && strlen($_SESSION["LAB_L2"]) > 0) {
        $f->selectSQL("L3", "",
            "select '' as hierarchy, '' as parameter union " .
            "select hierarchy, parameter ".
            "from c_pemeriksaan_lab ".
            "where substr(hierarchy,10,6) = '000000' ".
            "and substr(hierarchy,1,6) = '".substr($_SESSION["LAB_L2"],0,6)."' ".
            "and hierarchy != '".$_SESSION["LAB_L2"]."' ".
            "and is_group = 'Y' ".
            "order by parameter", $_SESSION["LAB_L3"],
            $ext);
        if (strlen($_SESSION["LAB_L3"]) > 0) $level = 3;
        if (getFromTable(
                "select hierarchy, parameter ".
                "from c_pemeriksaan_lab ".
                "where substr(hierarchy,13,3) = '000' ".
                "and substr(hierarchy,1,9) = '".substr($_SESSION["LAB_L3"],0,9)."' ".
                "and hierarchy != '".$_SESSION["LAB_L3"]."' ".
                "and is_group = 'Y'")
            && strlen($_SESSION["LAB_L1"]) > 0
            && strlen($_SESSION["LAB_L2"]) > 0
            && strlen($_SESSION["LAB_L3"]) > 0) {
            $f->selectSQL("L4", "",

                "select '' as hierarchy, '' as parameter union " .
                "select hierarchy, parameter ".
                "from c_pemeriksaan_lab ".
                "where substr(hierarchy,13,3) = '000' ".
                "and substr(hierarchy,1,9) = '".substr($_SESSION["LAB_L3"],0,9)."' ".
                "and hierarchy != '".$_SESSION["LAB_L3"]."' ".
                "and is_group = 'Y' ".
                "order by parameter", $_SESSION["LAB_L4"],
                $ext);
                if (strlen($_SESSION["LAB_L4"]) > 0) $level = 4;
        }
    }
}
$f->execute();*/



    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION='laboratorium.php'><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=L1 VALUE='".$_SESSION["LAB_L1"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=L2 VALUE='".$_SESSION["LAB_L2"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=L3 VALUE='".$_SESSION["LAB_L3"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=L4 VALUE='".$_SESSION["LAB_L4"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=level VALUE='$level'>";
    echo "<TD class=form>Jenis Pemeriksaan <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' CARI '></TD>";
    echo "</TR></FORM></TABLE></DIV>";

/*$SQL1 = "select a.id, a.parameter,a.satuan,a.rentang_normal ".
        "from c_pemeriksaan_lab as a ".
        "where substr(a.hierarchy,1,".($level*3).") = '".substr($_SESSION["LAB_L$level"],0,($level*3))."' ".
        "and a.hierarchy <> '".$_SESSION["LAB_L$level"]."' ".
        "and substr(a.hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
        str_repeat("0",15-(($level*3)+3))."' ".
        "and is_group = 'N' ".
		"and upper(a.parameter) LIKE '%".strtoupper($_GET["search"])."%' ";
$SQL1Counter =
        "select count(*) ".
        "from c_pemeriksaan_lab as a ".
        "where substr(a.hierarchy,1,".($level*3).") = '".substr($_SESSION["LAB_L$level"],0,($level*3))."' ".
        "and a.hierarchy <> '".$_SESSION["LAB_L$level"]."' ".
        "and substr(a.hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
        str_repeat("0",15-(($level*3)+3))."' ".
        "and is_group = 'N'";
$SQL2 = "select a.parameter, a.id ".
        "from c_pemeriksaan_lab as a ".
        "where substr(a.hierarchy,1,".($level*3).") = '".substr($_SESSION["LAB_L$level"],0,($level*3))."' ".
        "and a.hierarchy <> '".$_SESSION["LAB_L$level"]."' ".
        "and substr(a.hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
        str_repeat("0",15-(($level*3)+3))."'";
$SQL2Counter =
        "select counter(*) ".
        "from c_pemeriksaan_lab as a ".
        "where substr(a.hierarchy,1,".($level*3).") = '".substr($_SESSION["LAB_L$level"],0,($level*3))."' ".
        "and a.hierarchy <> '".$_SESSION["LAB_L$level"]."' ".
        "and substr(a.hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
        str_repeat("0",15-(($level*3)+3))."'";

if (!isset($_GET[sort])) {

           $_GET[sort] = "parameter";
           $_GET[order] = "asc";
}*/
$SQL1= "select  id, parameter,  hierarchy  ".
    "from c_pemeriksaan_lab ".
    "where substr(hierarchy,4,12) = '000000000000' ".
    "and is_group = 'Y' and upper(parameter) LIKE '%".strtoupper($_GET["search"])."%' ".
    "order by parameter";
//if ($_SESSION["LAB_L1"]){ 

$t = new PgTable($con, "100%");
$t->SQL = $SQL1;
$t->SQLCounter = $SQL1Counter;
$t->setlocale("id_ID");
$t->RowsPerPage = 10;
$t->ColFormatHtml[0] =
    "<A HREF='laboratorium.php?e=<#0#>'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";
$t->ColHeader = Array("&nbsp;", "JENIS PAKET", " &nbsp;");
$t->ColFormatHtml[2] =
    "&nbsp;";
//$t->ShowSQL = true;
$t->execute();
/*}else{
	
$SQL = "select a.id, a.parameter,a.satuan,a.rentang_normal ".
        "from c_pemeriksaan_lab as a ".
        "where is_group = 'N' ".
		"and upper(a.parameter) LIKE '%".strtoupper($_GET["search"])."%' ";
			
$t = new PgTable($con, "100%");
$t->SQL = $SQL;
//$t->SQLCounter = $SQL1Counter;
$t->setlocale("id_ID");
//$t->ShowRowNumber=true;
$t->RowsPerPage = 10;
$t->ColFormatHtml[0] =
    "<A HREF='laboratorium.php?e=<#0#>&s=".$_GET["L1"]."'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";
$t->ColHeader = Array("&nbsp;", "JENIS PEMERIKSAAN", "SATUAN", "RENTANG NORMAL");
$t->execute();
}*/
?>
</TD></TR></TABLE>
</BODY>
</HTML>
