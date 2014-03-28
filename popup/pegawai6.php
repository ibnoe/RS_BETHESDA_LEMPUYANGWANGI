<?php // Nugraha, Tue Apr 20 15:41:59 WIT 2004
     // sfdn, 05-06-2004

session_start();
unset($_SESSION["SELECT_EMP6"]);

if (isset($_GET["e6"])) {
    $_SESSION["SELECT_EMP6"] = $_GET["e6"];
    
    ?>
    <SCRIPT language="JavaScript">
        window.opener.location = window.opener.location;
        window.close();
    </SCRIPT>
    <?php
    exit;
}
if (isset($_GET["mUMF"])) $_SESSION["mUMF"] = $_GET["mUMF"];
if (isset($_GET["tag"]))  $_SESSION["tag"]  = $_GET["tag"];
?>
<HTML>
<HEAD>
    <TITLE>Pilih Pegawai</TITLE>
    <LINK rel='StyleSheet' type='text/css' href='../default.css'>
</HEAD>
<BODY>
<TABLE border="0" bgcolor="#FFFFFF" width="100%" cellpadding="8"><TR><TD>
<?php
require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");

title("Pilih SMF");
echo "<br>";
$f = new Form("pegawai6.php", "GET", "NAME=Form1");
$f->PgConn = $con;
$f->selectSQL("mUMF", "Unit Medis Fungsional",
    "select '' as tc, '' as tdesc union " .
    "select tc, tdesc ".
    "from rs00001 ".
    "where tt = 'PEG' and tc != '000' ".
    "order by tdesc", $_SESSION["mUMF"],
    "OnChange = 'Form1.submit();'");
    
$f->selectSQL("mJMF","Jabatan Medis Fungsional",
    "select '' as tc, '' as tdesc union ".
    "select id as tc, jabatan_medis_fungsional as tdesc ".
    "from rs00018 ".
    "where unit_medis_fungsional_id = '".$_SESSION["mUMF"]."' "
    ,$_GET["mJMF"],
    "OnChange = 'Form1.submit();'");
$f->execute();
$is_selected = getFromTable(
    "select count(id) ".
    "from rs00018 ".
    "where unit_medis_fungsional_id = '" . $_SESSION["mUMF"] . "'") > 0;

if ($is_selected) {
    // search box
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION='pegawai.php'><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=mUMF VALUE='".$_SESSION["mUMF"]."'>";
    echo "<INPUT TYPE=HIDDEN NAME=mJMF VALUE='".$_GET["mJMF"]."'>";
    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari Nama '></TD>";
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL =   
			"select a.id as id, a.nip, a.nama ".
            "from rs00017 a ".
			"	left join rs00018 b ON a.jabatan_medis_fungsional_id = b.id ".
			"where b.unit_medis_fungsional_id = '".$_SESSION["mUMF"]."'  and ".
  			"a.jabatan_medis_fungsional_id = '".$_GET["mJMF"]."' and ".
			"upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' ".
			"group by a.id, a.nip, a.nama ".		
            "order by a.nama";
				
				
    $t->setlocale("id_ID");    
    //$t->ShowRowNumber = true;
    $t->RowsPerPage = 1000;
    $t->DisableStatusBar = true;
    $t->ColFormatHtml[0] =
        "<A HREF='pegawai6.php?e6=<#0#>'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";
    $t->ColHeader = Array("&nbsp;", "NIP",    "NAMA" );
    $t->ColAlign  = Array("CENTER", "CENTER", "LEFT");
    //$t->showsql=true;
    $t->execute();
}

?>
</TD></TR></TABLE>
</BODY>
</HTML>
