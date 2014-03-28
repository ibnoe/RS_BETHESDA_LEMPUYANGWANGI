<?php // Nugraha, Sat Apr 24 15:37:16 WIT 2004


session_start();

unset($_SESSION["SELECT_SUPPLIER"]);

if (isset($_GET["e"])) {
    $_SESSION["SELECT_SUPPLIER"] = $_GET["e"];
    setcookie("SELECT_SUPPLIER", $_GET["e"]);
 
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
    <TITLE>Pilih Pemasok</TITLE>
    <LINK rel='StyleSheet' type='text/css' href='../default.css'>
</HEAD>
<BODY>
<TABLE border="0" bgcolor="#FFFFFF" width="100%" cellpadding="8"><TR><TD>
<?php

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");

title("Pilih Pemasok");

echo "<br>";
$f = new Form("supplier.php", "GET", "NAME=Form1");
$f->PgConn = $con;

// search box
echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION='supplier.php'><TR>";
echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
echo "</TR></FORM></TABLE></DIV>";

$t = new PgTable($con, "100%");
$t->SQL =   "select id, nama ".
            "from rs00028 ".
            "where ".
            "upper(nama) LIKE '%".strtoupper($_GET["search"])."%'";
$t->setlocale("id_ID");
$t->ColFormatHtml[0] =
    "<A HREF='supplier.php?e=<#0#>'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";
$t->ColHeader = Array("&nbsp;", "PEMASOK");
$t->ColAlign  = Array("CENTER", "LEFT");
$t->ShowRowNumber = true;
$t->execute();

?>
</TD></TR></TABLE>
</BODY>
</HTML>