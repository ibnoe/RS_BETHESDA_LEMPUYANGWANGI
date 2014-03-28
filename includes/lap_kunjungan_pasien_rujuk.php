<?php
// sikasep Wildan :)
$PID = "lap_kunjungan_pasien_rujuk";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
echo "<br>";
title("LAPORAN RUJUKAN PASIEN");
$ext = "OnChange = 'Form1.submit();'";
echo "<br>";
$f = new Form($SC, "GET", "NAME=Form1");
$f->PgConn = $con;
$f->hidden("p",
        $PID);

include(xxx2);

//$f->selectArray("unit", "Unit Dirujuk", Array("" => "", "RWJ" => "Rawat Jalan", "RWI" => "Rawat Inap"), $_GET[unit], "onChange='document.Form1.submit();'; ");
    $f->selectSQL("mRAWATRUJUK", "Poli Rujukan",
            "select '' as tc, '' as tdesc union 
            SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','100','201','202','206','207','208')
            order by tdesc ASC ",
            $_GET["mRAWATRUJUK"],
            "");
$f->selectSQL("mPASIEN", "Tipe Pasien",
        "select '' as tc, '' as tdesc union select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000' ORDER BY tdesc ASC", $_GET[mPASIEN], "");
$f->submit("TAMPILKAN");
$f->execute();
//die;
$t = new PgTable($con, "100%");
/*
$fltUnit = "";
if($_GET['unit'] == 'RWJ'){
    $fltUnit = " AND x.tdesc <> 'IGD' ";
}
if($_GET['unit'] == 'RWI'){
    $fltUnit = " AND b.rawat_inap='I' ";
}
*/
$t->SQL  =  "SELECT c. mr_no, c.nama, d.tdesc as pasien, a.no_reg, to_char(b.tanggal_reg,'DD-MM-YYYY') as tanggal_reg, 
f.tdesc as poli_asal, to_char(a.tanggal_reg,'DD-MM-YYYY') as tanggal_rujukan,
e.tdesc
FROM c_visit a  
JOIN rs00006 b ON a.no_reg = b.id::text
JOIN rs00002 c ON b.mr_no = c.mr_no
JOIN rs00001 d ON b.tipe = d.tc and d.tt='JEP'
JOIN rs00001 e ON a.id_konsul::text = e.tc_poli::text and e.tt='LYN'
JOIN rs00001 f ON a.id_poli::text = f.tc_poli::text and f.tt='LYN'
where (a.tanggal_reg::date >= '".$ts_check_in1."' and a.tanggal_reg::date <= '".$ts_check_in2."') ".
    " and (e.tc like '%".$_GET["mRAWATRUJUK"]."%') and b.tipe like '%".$_GET[mPASIEN]."%'  ".
    "group by c.mr_no,c.nama,a.no_reg,b.tanggal_reg, a.tanggal_reg,b.rawat_inap, d.tdesc, e.tdesc, f.tdesc ";

echo "<BR>";
$t->setlocale("id_ID");
$t->ColHeader = array("NO.MR", "NAMA PASIEN", "TIPE PASIEN", "NO.REG", "TGL. REG.", "POLI DAFTAR", "TGL.DIRUJUK", "POLI RUJUKAN");
$t->ShowRowNumber = true;
$t->ColAlign[0] = "CENTER";
$t->ColAlign[2] = "LEFT";
$t->ColAlign[3] = "CENTER";
$t->ColAlign[4] = "RIGHT";
$t->RowsPerPage = $ROWS_PER_PAGE;
$t->ColFormatHtml[1] = "<A CLASS=TBL_HREF HREF='$SC?p=details_pasien&v=<#3#>&t1=$ts_check_in1&t2=$ts_check_in2'><#1#></A>";
$t->execute();
?>