<?php // Nugraha, Mon Apr 19 09:56:02 WIT 2004
      // sfdn, 30-04-2004

session_start();


// tokit: biar ngga usah pilih2 kategeori terus :D
if (!empty($_SESSION[mpar])) {
   $_GET[mpar] = $_SESSION[mpar];
   unset($_SESSION[mpar]);
}

unset($_SESSION["SELECT_OBAT"]);

if (isset($_GET["e"])) {
    $_SESSION["SELECT_OBAT"] = $_GET["e"];
    $_SESSION[mpar] = $_GET[mpar];
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
    <TITLE>Pilih Obat</TITLE>
    <LINK rel='StyleSheet' type='text/css' href='../default.css'>
</HEAD>
<BODY>
<TABLE border="0" bgcolor="#FFFFFF" width="100%" cellpadding="8"><TR><TD>
<?php

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");

// akhir tambahan 2 sfdn

// search box
echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION='par.php'><TR>";
// tambahan 3

// akhir tambahan 3 sfdn

echo "<TD class=SUB_MENU >NAMA BARANG: <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
echo "<TD><INPUT TYPE=SUBMIT VALUE=' CARI '></TD>";
echo "</TR></FORM></TABLE></DIV>";



$t = new PgTable($con, "100%");
$t->SQL =   "select id, no_seri, jenis_linen, kelas_linen from linen ";
$t->setlocale("id_ID");

$t->RowsPerPage = 20;

$t->ColFormatHtml[0] =
    "<A HREF='par.php?e=<#1#>'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";
$t->ColHeader = Array("&nbsp;", "No Seri", "Jenis Linen", "Bangsal");
$t->ColAlign  = Array("CENTER", "LEFT",       "LEFT"  , "LEFT");
$t->execute();

?>
</TD></TR></TABLE>
</BODY>
</HTML>
