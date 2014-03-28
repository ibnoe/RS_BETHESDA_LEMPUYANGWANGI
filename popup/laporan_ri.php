<?php //hery 23 july 2007

session_start();

unset($_SESSION["SELECT_LAP"]);
if (isset($_GET["e"])) {
    $_SESSION["SELECT_LAP"] = $_GET["e"];
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
    <TITLE>Pilih Laporan</TITLE>
    <LINK rel='StyleSheet' type='text/css' href='../default.css'>
</HEAD>
<BODY>
<TABLE border="0" bgcolor="#FFFFFF" width="100%" cellpadding="8"><TR><TD>
<?php
require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/class.PgTable.php");
require_once("../lib/functions.php");

//title("Pilih Laporan");
echo "<br>";
    $SQL1="select tc,tdesc from rs00001 where tt='LRI' and tc not in ('000','A02','A03','B02','B03','F01','F02','F04','G01','G02','G03','I02','I03','K03','K04') order by tc asc ";
    $SQL2 ="select tc,tdesc from rs00001 where tt='LRI' and tc in ('A01','B01','C03','D04','F03','E05','G01','G02','H01','H02','H03','I01','J10','K01','K02','K03','K04') order by tc asc ";
    $SQL3 ="select tc,tdesc from rs00001 where tt='LRI' and tc not in ('000') order by tc asc ";
    $SQL4 ="select tc,tdesc from rs00001 where tt='LRI' and tc in ('A01','B01','C03','E05','F03','H01','H02','H03','I01','J10','K01','K02','K03') order by tc asc ";
    $SQL5 ="select tc,tdesc from rs00001 where tt='LRI' and tc not in ('000') order by tc asc ";
    $SQL6 ="select tc,tdesc from rs00001 where tt='LRI' and tc not in ('000') order by tc asc ";
    $SQL7 ="select tc,tdesc from rs00001 where tt='LRI' and tc not in ('000') order by tc asc ";
    $SQL8 ="select tc,tdesc from rs00001 where tt='LRI' and tc not in ('000') order by tc asc ";
    $t = new PgTable($con, "100%");
    if ($_SESSION[gr] == "PARKIT"){
    	$t->SQL = $SQL1;
    }elseif ($_SESSION[gr] == "GELATIK"){
    	$t->SQL = $SQL2;
    }elseif ($_SESSION[gr] == "PERWIRA"){
    	$t->SQL = $SQL3;
    }elseif ($_SESSION[gr] == "MERAK"){
    	$t->SQL = $SQL4;
    }elseif ($_SESSION[gr] == "CENDRA"){
    	$t->SQL = $SQL5;
    }elseif ($_SESSION[gr] == "KUTILANG"){
    	$t->SQL = $SQL6;
    }elseif ($_SESSION[gr] == "MERPATI"){
    	$t->SQL = $SQL7;
    }elseif ($_SESSION[gr] == "ICU") {
    	$t->SQL = $SQL8;
    }else{
    	$t->SQL = "select tc,tdesc from rs00001 where tt='LRI' and tc in ('E05','H02') order by tc asc ";
    }
    $t->setlocale("id_ID");    
    //$t->ShowRowNumber = true;
    $t->RowsPerPage = 30;
    $t->DisableStatusBar = true;
    $t->ColFormatHtml[0] =
        "<A HREF='laporan_ri.php?e=<#0#>'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";
    $t->ColHeader = Array("&nbsp;",  "LAPORAN RAWAT INAP" );
    $t->ColAlign  = Array("CENTER",  "LEFT");
    //$t->showsql=true;
    $t->execute();


?>
</TD></TR></TABLE>
</BODY>
</HTML>
