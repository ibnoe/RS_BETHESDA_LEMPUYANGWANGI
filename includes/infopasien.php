<?php // Nugraha, Thu Apr 22 19:51:19 WIT 2004
      // sfdn, 10-05-2004
      // sfdn, 02-06-2004
	  // sfdn, 06-06-2004
	  // hery, 03-07-2007 print

$PID = "infopasien";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

if (strlen($_GET["nm"]) > 0) {
//if ($reg > 0) {
	if (!$GLOBALS['print']){
		title_print("<img src='icon/informasi-2.gif' align='absmiddle' > INFORMASI PASIEN");
		echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back-big","Kembali")."</a></DIV>";
		$ext = "";
	}else {
		title_print("<img src='icon/informasi.gif' align='absmiddle' > INFORMASI PASIEN");
		$ext = "disabled";
	}
    
    //echo "<br>";

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
    $t->ColHeader = array("MR.NO","NAMA PASIEN","NO.REG","TANGGAL REG.","U N I T","TIPE PASIEN","STATUS AKHIR","V i e w");
    if (!$GLOBALS['print']){
		$t->RowsPerPage = $ROWS_PER_PAGE;
   		$t->ColFormatHtml[7] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&rg=<#2#>'>".
                        icon("view-big","View")."</A>";
    }else {
    	$t->ColFormatHtml[7] = icon("view-big2","View");
    	$t->RowsPerPage = 30;
    	$t->DisableNavButton = true;
		$t->DisableScrollBar = true;
		//$t->DisableStatusBar = true;
    }
    $t->execute();
} elseif (strlen($_GET["rg"]) > 0) {
    if (!$GLOBALS['print']){
		title_print("<img src='icon/informasi-2.gif' align='absmiddle' > INFORMASI PASIEN");
		echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back-big","Kembali")."</a></DIV>";	
	}else {
		title_print("<img src='icon/informasi.gif' align='absmiddle' > INFORMASI PASIEN");
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

    /*
    $r = pg_query($con,
        "SELECT a.id, to_char(a.tanggal_reg,'DD MONTH YYYY') AS tanggal_reg, a.waktu_reg, ".
        "    a.mr_no, e.nama, to_char(e.tgl_lahir, 'DD MONTH YYYY') AS tgl_lahir, ".
        "    e.tmp_lahir, e.jenis_kelamin, f.tdesc AS agama, ".
        "    e.alm_tetap, e.kota_tetap, e.pos_tetap, e.tlp_tetap, ".
        "    a.id_penanggung, b.tdesc AS penanggung, a.id_penjamin, ".
        "    c.tdesc AS penjamin, a.no_jaminan, a.rujukan, a.rujukan_rs_id, ".
        "    d.tdesc AS rujukan_rs, a.rujukan_dokter, a.rawat_inap, ".
        "    a.status, a.tipe, g.tdesc AS tipe_desc, a.diagnosa_sementara, ".
        "    to_char(a.tanggal_reg, 'DD MONTH YYYY') AS tanggal_reg_str, ".
        "        CASE ".
        "            WHEN a.rawat_inap = 'I' THEN 'Rawat Inap'  ".
        "            WHEN a.rawat_inap = 'Y' THEN 'Rawat Jalan' ".
        "            ELSE 'IGD' ".
        "        END AS rawatan, ".
        "        age(a.tanggal_reg , e.tgl_lahir ) AS umur, ".
		"	case when a.rujukan = 'N' then 'Non-Rujukan' ".
		"		 when a.rujukan = 'U' then 'Unit Lain' else 'Rujukan' end as datang ".
        "FROM rs00006 a ".
        "   LEFT JOIN rs00001 b ON a.id_penanggung = b.tc AND b.tt = 'PEN'".
        "   LEFT JOIN rs00001 c ON a.id_penjamin = c.tc AND c.tt = 'PJN' ".
        "   LEFT JOIN rs00002 e ON a.mr_no = e.mr_no ".
        "   LEFT JOIN rs00001 f ON e.agama_id = f.tc AND f.tt = 'AGM' ".
        "   LEFT JOIN rs00001 g ON a.tipe = g.tc AND g.tt = 'JEP' ".
        "   LEFT JOIN rs00001 d ON a.id_penjamin = d.tc AND d.tt = 'RUJ' ".
        "   LEFT JOIN rs00001 h ON a.jenis_kedatangan_id = h.tc AND h.tt = 'JDP' ".
        "WHERE a.id = lpad($reg,10,'0')");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    $r = new Form("");
    $r->subtitle("Data Medis Terakhir");
    $r->execute();

    $akhir = getFromTable("select to_char(CURRENT_TIMESTAMP,'DD MON YYYY HH24:MI:SS') as akhir");
    echo "<table border=0 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='33%'>";
    $f = new ReadOnlyForm();
    $f->text("Nama", $d->nama);
    $f->text("Nomor Registrasi", formatRegNo($d->id). " - " . getFromTable("select count(mr_no) from rs00006 where mr_no = '$d->mr_no'"));
    $f->text("Nomor MR", $d->mr_no);
    $f->text("Pasien Dari", $d->rawatan );
    $f->text("RS Perujuk", $d->rujukan_rs);
    $f->text("Dokter Perujuk", $d->rujukan_dokter);
    $f->text("Jenis Kedatangan", $d->datang);
    $f->execute();
    echo "</td><td align=center valign=top width='33%'>";
    $f = new ReadOnlyForm();
    $f->text("Tanggal Reg.", $d->tanggal_reg);
    $f->text("Waktu Registrasi", substr($d->waktu_reg,0,8));
    $f->text("Penanggung", $d->penanggung);
    $f->text("Penjamin", $d->penjamin);
    $f->text("No. Jaminan", $d->no_jaminan);
    $f->text("Tipe Pasien", $d->tipe_desc);
	$f->text("Sampai Tgl.", $akhir);	
    $f->execute();
    echo "</td><td valign=top width='33%'>";
    $f = new ReadOnlyForm();
    echo "<table border=0 width='100%'>";
    echo "<tr><td class=TBL_BODY>Diagnosa Sementara:</td></tr>";
    echo "<tr><td align=justify class=TBL_BODY>$d->diagnosa_sementara</td></tr>";
    echo "</table>";
    $f->execute();
    echo "</td></tr></table>";

    */
    
    include("335.inc.php");

    echo "<form name=Form3>";
    if (!$GLOBALS['print']){    
	    echo "<input name=b1 type=button value='Identitas' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=1\";'".($_GET["sub"] == "1" || $_GET["sub"] == "" ? " DISABLED" : "").">&nbsp;";
	    // di tutup dulu gan. sama efrizal 20101111
		//echo "<input name=b2 type=button value='Tindakan Medis' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=2\";'".($_GET["sub"] == "2" ? " DISABLED" : "").">&nbsp;";
	    //echo "<input name=b2 type=button value='Rincian Tagihan' onClick='window.location=\"$SC?p=$PID&rg=".$_GET["rg"]."&sub=3\";'".($_GET["sub"] == "3" ? " DISABLED" : "").">&nbsp;";
    }
    echo "</form>";
    $sub = isset($_GET["sub"]) ? $_GET["sub"] : "1";
    if (file_exists("includes/$PID.$sub.php")) include_once("includes/$PID.$sub.php");

} else {
    title("Informasi Pasien ");
    echo "<br>";

    echo "<DIV class=BOX>";
    $f = new Form($SC, "GET");
    $f->hidden("p", $PID);
    $f->text("nm","NAMA / NO.RM/Registrasi",20,20,$_GET["nm"]);
    $f->submit(" CARI ");
    $f->execute();
    if ($msg) errmsg("Error:", $msg);
    echo "</DIV>";
}


?>
<!--
<br><br>
<div align="right">
<a href="javascript: cetakaja(<?// echo (int) $_GET[rg];?>)" ><img src="images/cetak.gif" border="0"></a>
</div>
-->
