<?php // Wildan ST., 07-05-2013  19:44:19 WIT

$PID = "persetujuan_ri";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

if (strlen($_GET["nm"]) > 0) {
	if (!$GLOBALS['print']){
		title_print("<img src='icon/informasi-2.gif' align='absmiddle' > DATA PASIEN");
		echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
		$ext = "";
	}else {
		title_print("<img src='icon/informasi.gif' align='absmiddle' > DATA PASIEN");
		$ext = "disabled";
	}
    
    // search box
    echo "<DIV class=BOX>";
    $f = new Form($SC, "GET");
    $f->hidden("p", $PID);
    $f->text("nm","NAMA / NO.RM/Registrasi",20,20,$_GET["nm"],$ext);
    $f->submit(" CARI ",$ext);
    $f->execute();

    $t = new PgTable($con, "100%");
    $t->SQL =
        "select b.mr_no, b.nama,a.id,  to_char(a.tanggal_reg,'DD MON YYYY') as tgl_str, ".
        "	case when a.rawat_inap='I' then 'Rawat Inap' ".
	"		when a.rawat_inap='Y' then 'Rawat Jalan' else 'IGD' end as rawat, ".
        "	c.tdesc as pasien, ". 
	"	case when a.status_akhir_pasien='-' then 'MASIH DIRAWAT' ".
	"		else d.tdesc end as akhir, ltrim(a.id,'0') as dummy ".
        "from rs00006 a ".
	"	left join rs00002 b ON a.mr_no= b.mr_no ".
	"	left join rs00001 c ON a.tipe = c.tc and c.tt= 'JEP' ".
	"	left join rs00001 d ON a.status_akhir_pasien = d.tc and d.tt='SAP' ".
        "where (upper(b.nama) LIKE '%".strtoupper($_GET["nm"])."%' or b.mr_no like '%".$_GET[nm]."%' or ".
	"a.id like '%".$_GET[nm]."%')";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[7] = "CENTER";
    $t->ColHeader = array("MR.NO","NAMA PASIEN","NO.REG","TANGGAL REG.","U N I T","TIPE PASIEN","STATUS AKHIR","CETAK");
    if (!$GLOBALS['print']){
		$t->RowsPerPage = $ROWS_PER_PAGE;
   		$t->ColFormatHtml[7] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&rg=<#2#>'>".
                        icon("ok","Cetak")."</A>";
    }else {
    	$t->ColFormatHtml[7] = icon("ok","Cetak");
    	$t->RowsPerPage = 30;
    	$t->DisableNavButton = true;
		$t->DisableScrollBar = true;
		//$t->DisableStatusBar = true;
    }
    $t->execute();
} elseif (strlen($_GET["rg"]) > 0) {
    if (!$GLOBALS['print']){
		title_print("<img src='icon/informasi-2.gif' align='absmiddle' > DATA PASIEN");
		echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";	
	}else {
		title_print("<img src='icon/informasi.gif' align='absmiddle' > DATA PASIEN");
title_excel("infopasien");
	}
    echo "<br>";
    $reg = (int) $_GET["rg"];
    if ($reg > 0) {
        if (getFromTable("select to_number(id,'9999999999') as id ".
                     "from rs00006 ".
                     "where to_number(id,'9999999999') = $reg ".
		     " ") == 0) {
                     //"and status = 'A'") == 0) {
        $reg = 0;
        $msg = "Nomor registrasi tidak ditemukan. Masukkan kembali nomor registrasi.";
        }
    }
    
    include("335.inc.php");

    echo "<form name=Form3>";
    if (!$GLOBALS['print']){    
	    //echo "<input name=b1 type=button value='Identitas' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=1\";'".($_GET["sub"] == "1" || $_GET["sub"] == "" ? " DISABLED" : "").">&nbsp;";
		?>
		<!-- button cetak lembar persetujuan -->
		<br>
		<script language="javascript">
			function cetakpersetujuanri(){
				sWin=window.open('includes/cetak.persetujuan_ri.php?rg=<? echo $reg?>', '_blank','top=0,left=0,width=1000,height=750,menubar=no,scrollbars=yes');
				sWin.focus();
			}
		</script>
		<input type='button' value='Cetak Persetujuan Rawat Inap' onclick="cetakpersetujuanri()"/>
		<?php
	}
    echo "</form>";
    $sub = isset($_GET["sub"]) ? $_GET["sub"] : "1";
    if (file_exists("includes/$PID.$sub.php")) include_once("includes/$PID.$sub.php");

} else {
	echo "<br>";
    title("Cari Pasien ");
    echo "<br>";

    echo "<DIV class=BOX>";
    $f = new Form($SC, "GET");
    $f->hidden("p", $PID);
    $f->text("nm","NAMA / No.RM / No.Reg",40,40,$_GET["nm"]);
    $f->submit(" CARI ");
    $f->execute();
    if ($msg) errmsg("Error:", $msg);
    echo "</DIV>";
}
?>
