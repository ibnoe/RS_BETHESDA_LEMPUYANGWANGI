<?php

$PID = "lap_kunjungan_pasien";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
echo "<br>";
title("LAPORAN KUNJUNGAN PASIEN");
$ext = "OnChange = 'Form1.submit();'";
echo "<br>";
$f = new Form($SC, "GET", "NAME=Form1");
$f->PgConn = $con;
$f->hidden("p",
        $PID);

include(xxx2);

$f->selectArray("unit", "U n i t", Array("" => "", "IGD" => "IGD", "RWJ" => "Rawat Jalan", "RWI" => "Rawat Inap"), $_GET[unit], "onChange='document.Form1.submit();'; ");
if ($_GET["unit"] == "RWJ") {
    $f->selectSQL("mRAWAT", "Poli Daftar",
            "select '' as tc, '' as tdesc union 
            SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','100','201','202','206','207','208')
            order by tdesc ASC ",
            $_GET["mRAWAT"],
            "");
} elseif ($_GET["unit"] == "RWI") {
    $f->selectSQL("mINAP", "Bangsal ",
            "select d.bangsal, d.bangsal as bangsal
            from rs00010 as a 
            join rs00012 as b on a.bangsal_id = b.id 
            join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' 
            join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' 
            join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR'
            group by d.bangsal
            order by d.bangsal ", $_GET["mINAP"], "");
} 
$f->selectSQL("mPASIEN", "Tipe Pasien",
        "select '' as tc, '' as tdesc union select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000' ORDER BY tdesc ASC", $_GET[mPASIEN], "");
$f->submit("TAMPILKAN");
$f->execute();
//die;
$t = new PgTable($con, "100%");

$fltUnit = "";
if($_GET['unit'] == 'IGD'){
    $fltUnit = " AND x.tdesc = 'IGD' ";
}
if($_GET['unit'] == 'RWJ'){
    $fltUnit = " AND x.tdesc <> 'IGD' ";
}
if($_GET['unit'] == 'RWI'){
    $fltUnit = " AND a.rawat_inap='I' ";
}
$t->SQL  =  "select a.mr_no,c.nama,a.id, to_char(a.tanggal_reg,'DD-MM-YYYY') as tgl_reg_str, d.tdesc as pasien, x.tdesc 
            from rs00006 a 
            left join rs00008 b ON a.id = b.no_reg 
            left join rs00002 c ON a.mr_no = c.mr_no 
            left join rs00001 d ON a.tipe = d.tc and d.tt='JEP' 
            left join rs00001 x ON a.poli = x.tc_poli and x.tt='LYN' 
            where (a.tanggal_reg between '$ts_check_in1' and '$ts_check_in2') 
            and (x.tc like '%".$_GET["mRAWAT"]."%') and a.tipe like '%".$_GET[mPASIEN]."%' ".$fltUnit."  
            group by a.mr_no,c.nama,a.id,a.tanggal_reg,d.tdesc, a.rawat_inap, x.tdesc ";

echo "<BR>";
$t->setlocale("id_ID");
$t->ColHeader = array("NO.MR", "NAMA PASIEN", "NO.REG", "TGL. REGISTRASI", "TIPE PASIEN", "RAWATAN");
$t->ShowRowNumber = true;
$t->ColAlign[0] = "CENTER";
$t->ColAlign[2] = "CENTER";
$t->ColAlign[3] = "CENTER";
$t->RowsPerPage = $ROWS_PER_PAGE;
$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=details_pasien&v=<#2#>&t1=$ts_check_in1&t2=$ts_check_in2'><#2#></A>";
$t->execute();
?>