<?php // Nugraha, Mon Apr 19 09:56:02 WIT 2004
      // sfdn, 30-04-2004

session_start();


// tokit: biar ngga usah pilih2 kategeori terus :D
if (!empty($_SESSION[mOBT])) {
   $_GET[mOBT] = $_SESSION[mOBT];
   unset($_SESSION[mOBT]);
}

unset($_SESSION["SELECT_OBAT2"]);

if (isset($_GET["e"])) {
    $_SESSION["SELECT_OBAT2"] = $_GET["e"];
    $_SESSION[mOBT] = $_GET[mOBT];
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

title("Pilih Kategori Barang (Stok Apotek RJ)");
echo "<br>";

// tambahan 1
$ext = "OnChange = 'Form1.submit();'";
// akhir tambahan 1 sfdn

$f = new Form("obat_apotek_swd.php", "GET", "NAME=Form1");
$f->PgConn = $con;

// tambahan 2
    $f->selectSQL("mOBT", "Kategori ",
        "select '' as tc, '' as tdesc union " .
        "select tc, tdesc ".
        "from rs00001 ".
        "where tt = 'GOB' and tc not in ('000','008') ".
        "order by tc", $_GET[mOBT],
        $ext);
    $f->execute();
// akhir tambahan 2 sfdn

// search box
echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION='obat_apotek_swd.php'><TR>";
// tambahan 3
echo "<TD><INPUT TYPE=HIDDEN NAME=mOBT VALUE='".$_GET["mOBT"]."'></TD>";
// akhir tambahan 3 sfdn

echo "<TD class=SUB_MENU >NAMA BARANG: <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
echo "<TD><INPUT TYPE=SUBMIT VALUE=' CARI '></TD>";
echo "</TR></FORM></TABLE></DIV>";


$t = new PgTable($con, "100%");
$t->SQL =   "select a.id, a.id, a.obat, a.satuan, x.qty_ri   ".            
			"from rsv0004 a, rs00016 b, rs00016a x ".
			"where a.id = b.obat_id and ".
			"a.id = x.obat_id and ".
			"a.kategori_id ='".$_GET["mOBT"]."' AND ".
            "upper(obat) LIKE '%".strtoupper($_GET["search"])."%'
			group by a.id, a.obat, a.satuan,  x.qty_ri  ";
$t->setlocale("id_ID");
//$t->ShowRowNumber = true;
$t->RowsPerPage = 20;
//$t->DisableStatusBar = true;

$t->ColFormatHtml[0] =
    "<A HREF='obat_racikanswd.php?e=<#0#>&nomor_bukti=".$_GET["nomor_bukti"]."&mOBT=".$_GET[mOBT]."'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";
$t->ColHeader = Array("&nbsp;", "ID<br>OBAT","KETERANGAN", "SATUAN", "STOK<br>APOTEK");
$t->ColAlign  = Array("CENTER", "LEFT",    "LEFT",       "LEFT"  , "LEFT", "LEFT",    "LEFT",       "LEFT"  , "LEFT");
$t->execute();
//}
?>
</TD></TR></TABLE>
</BODY>
</HTML>
