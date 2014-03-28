<?php // Nugraha, Sun Apr 18 23:09:04 WIT 2004

session_start();

unset($_SESSION["no_asuransi"]);

if (isset($_GET["e"])) {
    $_SESSION["no_asuransi"] = $_GET["e"];
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
    <TITLE>Eligibility CIU Policy Number</TITLE>
    <LINK rel='StyleSheet' type='text/css' href='../default.css'>
</HEAD>
<BODY>
<TABLE border="0" bgcolor="#FFFFFF" width="100%" cellpadding="8"><TR><TD>
<?php

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");

title("Input Nomor Polis Asuransi CIU");

echo "<br>";
$f = new Form("ciu2.php", "GET", "NAME=Form1");
$f->PgConn = $con;

// search box
echo "<DIV ALIGN=LEFT><TABLE BORDER=0><FORM ACTION='ciu2.php'><TR>";
echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
echo "</TR></FORM></TABLE></DIV>";

$t = new PgTable($con, "100%");
$t->SQL =   "select no, no_asuransi, nama, alamat, plafon_sisa ".
            "from ciu where no_asuransi LIKE '%".strtoupper($_GET["search"])."%' order by no";
            
$t->setlocale("id_ID");    
//$t->ShowRowNumber = true;
$t->RowsPerPage = 1;
//$t->DisableStatusBar = false;
//$t->DisableScrollBar = false;
$t->ColFormatHtml[0] =
    "<A HREF='ciu2.php?e=<#1#>'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";
$t->ColHeader = Array("&nbsp;", "No Polis CIU",   "Pemegang Polis", "Alamat", "Plafon");
$t->ColAlign  = Array("CENTER", "CENTER", "LEFT",       "LEFT");
$t->execute();

?>
</TD></TR></TABLE>
</BODY>
</HTML>
