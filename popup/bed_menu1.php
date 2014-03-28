<?php // Agung Sunandar; lagi di bukittinggi ;)

session_start();

unset($_SESSION["SELECT_PASIEN"]);



if (isset($_GET["e"])) {
		 $_SESSION["SELECT_PASIEN"] = $_GET["e"];
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
    <TITLE>Pilih Pasien</TITLE>
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
	$f = new Form("$SC", "GET", "name='Form2'");
    $f->PgConn = $con;
	$f->hidden("p",$PID);
	$f->hidden("new","new");
	$f->selectSQL("pasien", "Poli Asal","select '-' as tc, '' as tdesc union
						  select distinct(a.poli::text) as tc, b.tdesc AS tdesc
						  from rs00006 a, rs00001 b
						  where b.tc != '111' and b.tt='LYN' and a.poli::text=b.tc and a.rawat_inap='Y'
						  ", $_GET["pasien"],"onChange=\"Form2.submit();\"");
	$f->search("search","Pencarian",20,20,$_GET["search"],"../icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
	$f->execute();
   
	$SQL =   "select distinct a.id, a.mr_no,a.id,upper(a.nama)as nama,c.tdesc
				from rsv_pasien4 a 
				left join c_visit b on b.no_reg = a.id
				left join rs00001 c on c.tc_poli = b.id_poli and c.tt='LYN'
				WHERE b.id_konsul='111' and b.id_poli::text like '%".$_GET["pasien"]."%' and ((UPPER(a.NAMA) LIKE '%".strtoupper($_GET["search"])."%') or a.id like '%".$_GET["pasien"]."%' or a.mr_no like '%".$_GET["pasien"]."%')
				 group by a.id, a.mr_no,a.id,a.nama,c.tdesc ";
	
    $t = new PgTable($con, "100%");
    $t->SQL = $SQL;
    $t->ColHeader = array("PILIH", "No. MR","No. Reg","Nama Pasien","Poli Asal");
    $t->ColAlign = array("center","center","center","left","left");
    $t->ColFormatHtml[0] =  "<A HREF='bed_menu.php?e=<#0#>&n=<#2#>&poli=".$_GET["pasien"]."'><IMG BORDER=0 SRC='../images/icon-ok.png'></A>"; 
    $t->ShowRowNumber = false;
    $t->ColAlign[0] = "CENTER";
    $t->execute();

?>
</TD></TR></TABLE>
</BODY>
</HTML>
