<?php // efrizal
session_start();

for ($n = 1; $n < 5; $n++) if (isset($_GET["L$n"])) $_SESSION["AKUN_L$n"] = $_GET["L$n"];

unset($_SESSION["SELECT_AKUN"]);

if (isset($_GET["e"])) {
    $_SESSION["SELECT_AKUN"] = $_GET["e"];
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
    <TITLE>Pilih Akun</TITLE>
    <LINK rel='StyleSheet' type='text/css' href='../default.css'>
</HEAD>
<BODY>
<TABLE border="0" bgcolor="#FFFFFF" width="100%" cellpadding="8"><TR><TD>
<?php

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");

title("Pilih Akun");
$ext = "OnChange = 'Form1.submit();'";
$level = 0;

$f = new Form("Akun1.php", "GET", "NAME=Form1");
$f->PgConn = $con;

/*
$f->selectSQL("L1", "Grup Akun",
    "select '' as hierarchy, '- - - - - - - - - - - - SEMUA - - - - - - - - - - - - ' as nama union " .
    "select hierarchy, nama ".
    "from akun_master ".
    "where substr(hierarchy,4,12) = '000000000000' ".
    "and is_group = 'Y' ".
    "order by nama", $_SESSION["AKUN_L1"],
    $ext);
   
if (strlen($_SESSION["AKUN_L1"]) > 0) $level = 1;
if (getFromTable(
        "select hierarchy, nama ".
        "from akun_master ".
        "where substr(hierarchy,7,9) = '000000000' ".
        "and substr(hierarchy,1,3) = '".substr($_SESSION["AKUN_L1"],0,3)."' ".
        "and hierarchy != '".$_SESSION["AKUN_L1"]."' ".
        "and is_group = 'Y'")
    && strlen($_SESSION["AKUN_L1"]) > 0) {
    $f->selectSQL("L2", "",
        "select '' as hierarchy, '' as nama union " .
        "select hierarchy, nama ".
        "from akun_master ".
        "where substr(hierarchy,7,9) = '000000000' ".
        "and substr(hierarchy,1,3) = '".substr($_SESSION["AKUN_L1"],0,3)."' ".
        "and hierarchy != '".$_SESSION["AKUN_L1"]."' ".
        "and is_group = 'Y' ".
        "order by nama", $_SESSION["AKUN_L2"],
        $ext);
    if (strlen($_SESSION["AKUN_L2"]) > 0) $level = 2;
    if (getFromTable(
            "select hierarchy, nama ".
            "from akun_master ".
            "where substr(hierarchy,10,6) = '000000' ".
            "and substr(hierarchy,1,6) = '".substr($_SESSION["AKUN_L2"],0,6)."' ".
            "and hierarchy != '".$_SESSION["AKUN_L2"]."' ".
            "and is_group = 'Y'")
        && strlen($_SESSION["AKUN_L1"]) > 0
        && strlen($_SESSION["AKUN_L2"]) > 0) {
        $f->selectSQL("L3", "",
            "select '' as hierarchy, '' as nama union " .
            "select hierarchy, nama ".
            "from akun_master ".
            "where substr(hierarchy,10,6) = '000000' ".
            "and substr(hierarchy,1,6) = '".substr($_SESSION["AKUN_L2"],0,6)."' ".
            "and hierarchy != '".$_SESSION["AKUN_L2"]."' ".
            "and is_group = 'Y' ".
            "order by nama", $_SESSION["AKUN_L3"],
            $ext);
        if (strlen($_SESSION["AKUN_L3"]) > 0) $level = 3;
        if (getFromTable(
                "select hierarchy, nama ".
                "from akun_master ".
                "where substr(hierarchy,13,3) = '000' ".
                "and substr(hierarchy,1,9) = '".substr($_SESSION["AKUN_L3"],0,9)."' ".
                "and hierarchy != '".$_SESSION["AKUN_L3"]."' ".
                "and is_group = 'Y'")
            && strlen($_SESSION["AKUN_L1"]) > 0
            && strlen($_SESSION["AKUN_L2"]) > 0
            && strlen($_SESSION["AKUN_L3"]) > 0) {
            $f->selectSQL("L4", "",

                "select '' as hierarchy, '' as nama union " .
                "select hierarchy, nama ".
                "from akun_master ".
                "where substr(hierarchy,13,3) = '000' ".
                "and substr(hierarchy,1,9) = '".substr($_SESSION["AKUN_L3"],0,9)."' ".
                "and hierarchy != '".$_SESSION["AKUN_L3"]."' ".
                "and is_group = 'Y' ".
                "order by nama", $_SESSION["AKUN_L4"],
                $ext);
                if (strlen($_SESSION["AKUN_L4"]) > 0) $level = 4;
        }
    }
}
$f->execute();
 //}


    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION='akun1.php'><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=L1 VALUE='".$_SESSION["AKUN_L1"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=L2 VALUE='".$_SESSION["AKUN_L2"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=L3 VALUE='".$_SESSION["AKUN_L3"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=L4 VALUE='".$_SESSION["AKUN_L4"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=level VALUE='$level'>";
    echo "<TD class=form_title>Cari: <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Nama Akun'></TD>";
    echo "</TR></FORM></TABLE></DIV>";

$SQL1 = "select a.id, a.nama, a.kode ".
        "from akun_master as a ".
        "where substr(a.hierarchy,1,".($level*3).") = '".substr($_SESSION["AKUN_L$level"],0,($level*3))."' ".
        "and a.hierarchy <> '".$_SESSION["AKUN_L$level"]."' ".
        "and substr(a.hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
        str_repeat("0",15-(($level*3)+3))."' ".
        "and is_group = 'N' ".
		"and upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' ";
$SQL1Counter =
        "select count(*) ".
        "from akun_master as a ".
        "where substr(a.hierarchy,1,".($level*3).") = '".substr($_SESSION["AKUN_L$level"],0,($level*3))."' ".
        "and a.hierarchy <> '".$_SESSION["AKUN_L$level"]."' ".
        "and substr(a.hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
        str_repeat("0",15-(($level*3)+3))."' ".
        "and is_group = 'N'";
$SQL2 = "select a.nama, a.id ".
        "from akun_master as a ".
        "where substr(a.hierarchy,1,".($level*3).") = '".substr($_SESSION["AKUN_L$level"],0,($level*3))."' ".
        "and a.hierarchy <> '".$_SESSION["AKUN_L$level"]."' ".
        "and substr(a.hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
        str_repeat("0",15-(($level*3)+3))."'";
$SQL2Counter =
        "select counter(*) ".
        "from akun_master as a ".
        "where substr(a.hierarchy,1,".($level*3).") = '".substr($_SESSION["AKUN_L$level"],0,($level*3))."' ".
        "and a.hierarchy <> '".$_SESSION["AKUN_L$level"]."' ".
        "and substr(a.hierarchy,".(($level*3)+4).",".(15-(($level*3)+3)).") = '".
        str_repeat("0",15-(($level*3)+3))."'";

if (!isset($_GET[sort])) {

           $_GET[sort] = "nama";
           $_GET[order] = "asc";
}
if ($_SESSION["AKUN_L1"]){ 

$t = new PgTable($con, "100%");
$t->SQL = $SQL1;
$t->SQLCounter = $SQL1Counter;
$t->setlocale("id_ID");
$t->RowsPerPage = 10;
$t->ColFormatHtml[0] =
    "<A HREF='akun1.php?e=<#2#>'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";
$t->ColHeader = Array("&nbsp;", "AKUN", "KODE");
//$t->ShowSQL = true;
$t->execute();

}else{
*/	
$SQL = "select a.kode, a.nama ".
        "from akun_master as a ".
        "where is_group = 'N' order by kode";
			
$t = new PgTable($con, "100%");
$t->SQL = $SQL;
//$t->SQLCounter = $SQL1Counter;
$t->setlocale("id_ID");
//$t->ShowRowNumber=true;
$t->RowsPerPage = 20;
$t->ColFormatHtml[0] =
    "<A HREF='akun1.php?e=<#0#>'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";
$t->ColHeader = Array("&nbsp;", "AKUN", "KODE");
$t->execute();
/*}*/
?>
</TD></TR></TABLE>
</BODY>
</HTML>
