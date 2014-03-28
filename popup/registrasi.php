<?php // agung

session_start();
unset($_SESSION["SELECT_KARCIS1"]);
if (isset($_GET["e"])) {
    $_SESSION["SELECT_KARCIS1"] = $_GET["e"];
    ?>
    <SCRIPT language="JavaScript">
        window.opener.location = window.opener.location;
        window.close();
    </SCRIPT>
    <?php
    exit;
}
if (isset($_GET["mPOLI"])) $_SESSION["mPOLI"] = $_GET["mPOLI"];
if (isset($_GET["tag"]))  $_SESSION["tag"]  = $_GET["tag"];
?>
<HTML>
<HEAD>
    <TITLE>Pilih Pasien Registrasi</TITLE>
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
$f = new Form("registrasi.php", "GET", "NAME=Form1");
$f->PgConn = $con;
$f->selectSQL("mPOLI", "Poli Tujuan",
    "select '' as tc, '' as tdesc union " .
    "select tc, tdesc ".
    "from rs00001 ".
    "where tt = 'LYN' and tc not in ('000','201','202','206','207','208') ".
    "order by tdesc", $_SESSION["mPOLI"],
    "OnChange = 'Form1.submit();'");
    
$f->execute();
$is_selected = getFromTable(
    "select count(id) ".
    "from master_karcis ".
    "where jmk = '" . $_SESSION["mJMK"] . "'") > 0;

if ($is_selected) {
    $tglhariini = date("Y-m-d", time());
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION='registrasi.php'><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=mPOLI VALUE='".$_SESSION["mPOLI"]."'>";
    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari Nama '></TD>";
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = " select a.id,a.id,b.mr_no,b.nama,to_char(a.tanggal_reg,'DD MON YYYY')as tanggal_reg,c.tdesc
				from rs00006 a
				left join rs00002 b on a.mr_no=b.mr_no
				left join rs00001 c on a.poli::text=c.tc and c.tt='LYN'
				where a.tanggal_reg='$tglhariini' and a.poli::text like '".$_GET["mPOLI"]."' and a.id like '%".$_GET["search"]."%' 
				group by a.id,b.mr_no,b.nama,a.tanggal_reg,c.tdesc ";
				
				
    $t->setlocale("id_ID");    
    //$t->ShowRowNumber = true;
    $t->RowsPerPage = 1000;
    $t->DisableStatusBar = true;
    $t->ColFormatHtml[0] =
        "<A HREF='registrasi.php?e=<#0#>'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";
    $t->ColHeader = Array("&nbsp;", "NO. REG", "NO.MR", "NAMA", "TGL. REG", "POLI" );
    $t->ColAlign  = Array("CENTER", "CENTER", "LEFT");
    //$t->showsql=true;
    $t->execute();
}

?>
</TD></TR></TABLE>
</BODY>
</HTML>
