<?php // Nugraha, Mon Apr 19 09:56:02 WIT 2004
      // sfdn, 30-04-2004

session_start();


// tokit: biar ngga usah pilih2 kategeori terus :D
if (!empty($_SESSION[mOBT])) {
   $_GET[mOBT] = $_SESSION[mOBT];
   unset($_SESSION[mOBT]);
}

unset($_SESSION["SELECT_KONVERSI"]);

if (isset($_GET["e"])) {
    $_SESSION["SELECT_KONVERSI"] = $_GET["e"];
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

title("Pilih Konversi");
echo "<br>";
$dt_obt=pg_query($con,"select * from rsv0004 where id = ".$_GET[obt_id]."");

$d3 = pg_fetch_object($dt_obt);
pg_free_result($dt_obt);


// data untuk rincian table konversi
$f = new Form("", "POST");
$f->hidden("id", $_GET["id"]);
$f->text("description","Kode Obat",20,40,$d3->id,"disabled");
$f->text("harga","Nama Obat",50,40,$d3->obat,"disabled");
$f->execute();


$cek=getFromTable("select count(kode_trans) from rs00016d where kode_obat='".$_GET["obt_id"]."'");

if($cek > 0){
echo "<br><DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION='konversi.php'><TR>";
echo "<TD><INPUT TYPE=HIDDEN NAME=obt_id VALUE='".$_GET["obt_id"]."'></TD>";
echo "<TD class=SUB_MENU >SATUAN: <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
echo "<TD><INPUT TYPE=SUBMIT VALUE=' CARI '></TD>";
echo "</TR></FORM></TABLE></DIV>";



$t = new PgTable($con, "100%");
$t->SQL =   "select a.kode_trans, a.jumlah2||' '|| c.tdesc as satuan ,  a.jumlah1||' '|| b.tdesc as satuan
			from rs00016d a, rs00001 b, rs00001 c 
			where a.kode_obat = '".$_GET["obt_id"]."' and 
			a.satuan1=b.tc and b.tt='SAT' and a.satuan2=c.tc and c.tt='SAT' 
			AND (upper(b.tdesc) LIKE '%".strtoupper($_GET["search"])."%' or upper(c.tdesc) LIKE '%".strtoupper($_GET["search"])."%')
			
			";
$t->setlocale("id_ID");
//$t->ShowRowNumber = true;
$t->RowsPerPage = 20;
//$t->DisableStatusBar = true;

$t->ColFormatHtml[0] =
    "<A HREF='konversi.php?e=<#0#>&nomor_bukti=".$_GET["nomor_bukti"]."&mOBT=".$_GET[mOBT]."'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>";
$t->ColHeader = Array("&nbsp;", "SATUAN KONVERSI", "HASIL KONVERSI");
$t->ColAlign  = Array("CENTER", "LEFT", "LEFT"  , "LEFT", "LEFT");
$t->execute();
}else{
echo "<blink><font size='5'>Konversi tidak tersedia!</font></blink>";
}
?>
</TD></TR></TABLE>
</BODY>
</HTML>
