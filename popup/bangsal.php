<?php // Nugraha, Sun May  9 14:08:15 WIT 2004
	  // sfdn, 31-05-2004

session_start();

unset($_SESSION["SELECT_BANGSAL"]);



if (isset($_GET["e"])) {
	if ($_GET["n"] == 'TERSEDIA'){
	    $_SESSION["SELECT_BANGSAL"] = $_GET["e"];
	    ?>
	    <SCRIPT language="JavaScript">
	        window.opener.location = window.opener.location;
	        window.close();
	    </SCRIPT>
	    <?php
	    exit;
	}else{ 
	?>
	<SCRIPT language="JavaScript"> alert ("RUANGAN TERISI ") </SCRIPT>
	<?
	}

}

?>
<HTML>
<HEAD>
    <TITLE>Pilih Bangsal</TITLE>
    <LINK rel='StyleSheet' type='text/css' href='../default.css'>
</HEAD>
<BODY>
<TABLE border="0" bgcolor="#FFFFFF" width="100%" cellpadding="8"><TR><TD>
<?php

require_once("../startup.php");

title("Pilih Bangsal");
echo "<br>";

function getLevel($hcode)
{
    if (strlen($hcode) != 9) return 0;
    if (substr($hcode,  4,  6) == str_repeat("0", 6)) return 1;
    if (substr($hcode,  7,  3) == str_repeat("0", 3)) return 2;
    return 3;
}

$ext = "OnChange = 'Form1.submit();'";
$level = 0;
$f = new Form($SC, "GET", "NAME=Form1");
$f->PgConn = $con;
$f->hidden("p", $PID);
$f->hidden("sub", $sub);
$f->selectSQL("L1", "Bangsal",
    "select '' as hierarchy, '' as bangsal union " .
    "select hierarchy, bangsal ".
    "from rs00012 ".
    "where substr(hierarchy,4,6) = '000000' ".
    "and is_group = 'Y' ".
    "order by bangsal", $_GET["L1"],
    $ext);
if (strlen($_GET["L1"]) > 0) $level = 1;
if (getFromTable(
        "select hierarchy, bangsal ".
        "from rs00012 ".
        "where substr(hierarchy,7,3) = '000' ".
        "and substr(hierarchy,1,3) = '".substr($_GET["L1"],0,3)."' ".
        "and hierarchy != '".$_GET["L1"]."' ".
        "and is_group = 'Y'")
    && strlen($_GET["L1"]) > 0) {
    $f->selectSQL("L2", "Ruangan",
        "select '' as hierarchy, '' as bangsal union " .
        "select hierarchy, a.bangsal || '  ' || b.tdesc as bangsal ".
        "from rs00012 a, rs00001 b ".
        "where substr(hierarchy,7,3) = '000' ".
        "and substr(hierarchy,1,3) = '".substr($_GET["L1"],0,3)."' ".
        "and hierarchy != '".$_GET["L1"]."' ".
	"and a.klasifikasi_tarif_id = b.tc and b.tt='KTR' ".
        "and is_group = 'Y' ".
        "order by hierarchy", $_GET["L2"],
        $ext);
    if (strlen($_GET["L2"]) > 0) $level = 2;
}
$f->execute();

if ($level == 2) {
    /*$SQL =  "select case when b.id is null then ".
            "       '<a href=\"bangsal.php?e='||a.id||'\"><img border=0 src=\"../images/icon-ok.png\"></a>' ".
            "       else '<a href=\"bangsal.php?e='||a.id||'\"><img border=0 src=\"../images/icon-ok.png\"></a>' end as id, ".
	    //"       else '' end as id, ".
            "a.bangsal, ".
            //"case when b.id is null then 'TERSEDIA' else 'TERSEDIA' end as status ".
	    "case when b.id is null then 'TERSEDIA' else 'TERISI PASIEN' end as status ".
            "from rs00012 as a ".
            "    left join rs00010 as b on a.id = b.bangsal_id and b.ts_calc_stop is null ".
            "where a.is_group = 'N' ".
            "    and substr(a.hierarchy,1,6) = '".substr($_GET["L2"],0,6)."'";
      */
		$SQL =  "select  a.id , ".
            	"	a.bangsal, ".
 	    	"	case when b.id is null then 'TERSEDIA' else 'TERISI PASIEN' end as status ".
            	"from rs00012 as a ".
           		"    left join rs00010 as b on a.id = b.bangsal_id and b.ts_calc_stop is null ".
            	"where a.is_group = 'N' ".
            	"    and substr(a.hierarchy,1,6) = '".substr($_GET["L2"],0,6)."' ";
       $test = "xxx";
    $t = new PgTable($con, "100%");
    $t->SQL = $SQL;
    $t->ColHeader = array("PILIH", "BED", "STATUS");
    $t->ColAlign = array("center","left","center");
    $t->ColFormatHtml[0] =  "<A HREF='bangsal.php?e=<#0#>&n=<#2#>&L1=".$_GET["L1"]."&L2=".$_GET["L2"]."'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>"; 
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->execute();
}

?>
</TD></TR></TABLE>
</BODY>
</HTML>
