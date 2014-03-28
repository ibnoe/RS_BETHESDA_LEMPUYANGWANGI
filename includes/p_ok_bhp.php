<?
// app, 08-09-2007
$PID = "p_ok_bhp";
$SC = $_SERVER["SCRIPT_NAME"];
session_start();
require_once("startup.php");
title_print("<img src='icon/apotek1-icon.png' align='absmiddle' width=48 >  LAYANAN BHP PASIEN OPERASI");
$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    $f->hidden("poli",$_GET["mPOLI"]);
    echo "<div align='right' valign='middle'>";	
		$f = new Form($SC, "GET","NAME=Form2");
	    $f->hidden("p", $PID);
	    if (!$GLOBALS['print']){
	    	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
		}else { 
		   	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","disabled");
		}
	    $f->execute();
    	if ($msg) errmsg("Error:", $msg);
    	echo "</div><br>";

    $SQLSTR = "select distinct a.mr_no,a.id,upper(a.nama)as nama,a.alm_tetap,a.kesatuan,a.tdesc,c.tdesc,a.statusbayar
                from rsv_pasien4 a 
                left join c_visit b on b.no_reg = a.id
                left join rs00001 c on c.tc_poli = b.id_poli and c.tt='LYN'
                WHERE b.id_konsul = '209' ";


if ($_GET["search"]) {
    $SQLWHERE = "and (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' or a.id like '%".$_GET['search']."%' or a.mr_no like '%".$_GET["search"]."%' ".
                " or upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%' or a.nrp_nip like '%".$_GET['search']."%' ".
                " or upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' ) ";
}
if (!isset($_GET[sort])) {
   $_GET[sort] = "a.id";
   $_GET[order] = "asc";
}


$t = new PgTable($con, "100%");
$ORDER = "";
if(empty($_GET[order])){
$ORDER = "ORDER BY a.ts_check_in desc";
}
$t->SQL = "$SQLSTR $SQLWHERE ";
$t->ColAlign = array("CENTER","CENTER","LEFT","LEFT","LEFT","CENTER","LEFT","LEFT","LEFT","LEFT","LEFT");
$t->ColHeader = array("NO.MR", "NO<br>REGISTRASI","NAMA PASIEN","ALAMAT","PEKERJAAN","TIPE PASIEN","STATUS BAYAR","STATUS PASIEN");
$t->ShowRowNumber = true;
$t->ColAlign[0] = "CENTER";
$t->ColAlign[1] = "CENTER";
$t->ColAlign[7] = "CENTER";
$t->ColAlign[3] = "CENTER";
$t->RowsPerPage = $ROWS_PER_PAGE;
$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=apotik_bhp_ok&rg=<#1#>'><#2#></A>";
$t->execute();
?>