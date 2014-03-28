<?php // Nugraha, Sun Apr 18 23:09:04 WIT 2004

session_start();

unset($_SESSION["SELECT_ICD9"]);

if (isset($_GET["e"])) {
    $_SESSION["SELECT_ICD9"] = $_GET["e"];
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
    <TITLE>Pilih ICD 9</TITLE>
    <LINK rel='StyleSheet' type='text/css' href='../default.css'>
</HEAD>
<BODY>
<TABLE border="0" bgcolor="#FFFFFF" width="100%" cellpadding="8"><TR><TD>
<?php

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");

title("Pilih ICD 9");

echo "<br>";
$f = new Form("icd_9.php", "GET", "NAME=Form1");
$f->PgConn = $con;

// search box
echo "<DIV ALIGN=LEFT><TABLE BORDER=0><FORM ACTION='icd_9.php'><TR>";
echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
echo "</TR></FORM></TABLE></DIV>";

$t = new PgTable($con, "100%");
$t->SQL =   "select kode as id, kode, nama from icd_9 where ".
            "(upper(kode) LIKE '%".strtoupper($_GET["search"])."%' OR ".
            "upper(nama) LIKE '%".strtoupper($_GET["search"])."%')";
$t->setlocale("id_ID");    
$t->ShowRowNumber = true;
$t->RowsPerPage = 20;
$t->DisableStatusBar = false;
$t->DisableScrollBar = false;
$t->ColFormatHtml[0] =
    "<A HREF='icd_9.php?e=<#0#>'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";
$t->ColHeader = Array("&nbsp;", "KODE", "DESKRIPSI");
$t->ColAlign  = Array("CENTER", "LEFT", "LEFT");
$t->execute();

?>
</TD></TR></TABLE>
</BODY>
</HTML>
