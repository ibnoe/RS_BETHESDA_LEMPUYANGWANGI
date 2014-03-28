<?php // Nugraha, Mon Apr 19 09:56:02 WIT 2004
      // sfdn, 30-04-2004

session_start();


// tokit: biar ngga usah pilih2 kategeori terus :D
if (!empty($_SESSION[mLINEN])) {
   $_GET[mLINEN] = $_SESSION[mLINEN];
   unset($_SESSION[mLINEN]);
}

unset($_SESSION["SELECT_LINEN"]);

if (isset($_GET["e"])) {
    $_SESSION["SELECT_LINEN"] = $_GET["e"];
    $_SESSION[mLINEN] = $_GET[mLINEN];
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

title("Pilih Kategori Barang");
echo "<br>";

// tambahan 1
$ext = "OnChange = 'Form1.submit();'";
// akhir tambahan 1 sfdn

$f = new Form("linen_pilih.php", "GET", "NAME=Form1");
$f->PgConn = $con;

// tambahan 2
    $f->selectSQL("mLINEN", "Bangsal ",
        "select '' as hierarchy, '' as bangsal union " .
    "select hierarchy, bangsal ".
    "from rs00012 ".
    "where substr(hierarchy,4,6) = '000000' ".
    "and is_group = 'Y' ".
    "order by bangsal", $_GET[mLINEN],
        $ext);
    $f->execute();
// akhir tambahan 2 sfdn

// search box
echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION='linen_pilih.php'><TR>";
// tambahan 3
echo "<TD><INPUT TYPE=HIDDEN NAME=mLINEN VALUE='".$_GET["mLINEN"]."'></TD>";
// akhir tambahan 3 sfdn

echo "<TD class=SUB_MENU >NAMA BARANG: <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
echo "<TD><INPUT TYPE=SUBMIT VALUE=' CARI '></TD>";
echo "</TR></FORM></TABLE></DIV>";

$r=pg_query($con,"select hierarchy, bangsal from rs00012 where hierarchy= '".$_GET["mLINEN"]."' and is_group = 'Y' order by bangsal");
$n = pg_num_rows($r);
 if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);
echo $d->bangsal;
$t = new PgTable($con, "100%");
$t->SQL =   "select id,no_seri,jenis_linen from linen where kelas_linen like '".$d->bangsal."%'";
$t->setlocale("id_ID");
$t->RowsPerPage = 20;
$t->ColFormatHtml[0] =
    "<A HREF='linen_pilih.php?e=<#0#>'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";
$t->ColHeader = Array("&nbsp;", "Serial", "Jenis");
$t->ColAlign  = Array("CENTER", "LEFT",       "LEFT" );
$t->execute();

?>
</TD></TR></TABLE>
</BODY>
</HTML>
