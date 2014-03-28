<?php // efrizal

session_start();
unset($_SESSION["SELECT_KARCIS"]);
if (isset($_GET["e"])) {
    $_SESSION["SELECT_KARCIS"] = $_GET["e"];
    ?>
    <SCRIPT language="JavaScript">
        window.opener.location = window.opener.location;
        window.close();
    </SCRIPT>
    <?php
    exit;
}
if (isset($_GET["mJMK"])) $_SESSION["mJMK"] = $_GET["mJMK"];
if (isset($_GET["tag"]))  $_SESSION["tag"]  = $_GET["tag"];
?>
<HTML>
<HEAD>
    <TITLE>Pilih Karcis</TITLE>
    <LINK rel='StyleSheet' type='text/css' href='../default.css'>
</HEAD>
<BODY>
<TABLE border="0" bgcolor="#FFFFFF" width="100%" cellpadding="8"><TR><TD>
<?php
require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");

title("Pilih Karcis");
echo "<br>";
$f = new Form("karcis.php", "GET", "NAME=Form1");
$f->PgConn = $con;
$f->selectSQL("mJMK", "Jenis Karcis",
    "select '' as tc, '' as tdesc union " .
    "select tc, tdesc ".
    "from rs00001 ".
    "where tt = 'JMK' and tc != '000' ".
    "order by tdesc", $_SESSION["mJMK"],
    "OnChange = 'Form1.submit();'");
    
$f->execute();
$is_selected = getFromTable(
    "select count(id) ".
    "from master_karcis ".
    "where jmk = '" . $_SESSION["mJMK"] . "'") > 0;

if ($is_selected) {
    // search box
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION='karcis.php'><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=mJMK VALUE='".$_SESSION["mJMK"]."'>";
    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari Nama '></TD>";
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL =   
			"select a.id as id, a.code, a.harga ".
                        "from master_karcis a ".
			//"	left join rs00018 b ON a.jabatan_medis_fungsional_id = b.id ".
			"where ".
                        //"b.unit_medis_fungsional_id = '".$_SESSION["mUMF"]."'  and ".
  			"a.jmk = '".$_GET["mJMK"]."' and ".
			"upper(a.code) LIKE '%".strtoupper($_GET["search"])."%' ".
			"group by a.id, a.code, a.harga ".
                        "order by a.id";
				
				
    $t->setlocale("id_ID");    
    //$t->ShowRowNumber = true;
    $t->RowsPerPage = 1000;
    $t->DisableStatusBar = true;
    $t->ColFormatHtml[0] =
        "<A HREF='karcis.php?e=<#0#>'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";
    $t->ColHeader = Array("&nbsp;", "NAMA LAYANAN",    "HARGA" );
    $t->ColAlign  = Array("CENTER", "CENTER", "LEFT");
    //$t->showsql=true;
    $t->execute();
}

?>
</TD></TR></TABLE>
</BODY>
</HTML>
