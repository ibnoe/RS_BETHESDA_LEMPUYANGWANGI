<?php // Nugraha, Sun May  9 00:28:59 WIT 2004
      // sfdn, 30-05-2004
	  // hery, 03-0702007 

function getLevel($hcode)
{
    if (strlen($hcode) != 9) return 0;
    if (substr($hcode,  4,  6) == str_repeat("0", 6)) return 1;
    if (substr($hcode,  7,  3) == str_repeat("0", 3)) return 2;
    return 3;
}
if (!$GLOBALS['print']){
    $ext = "OnChange = 'Form1.submit();'";
}else {
	$ext = "disabled";
}

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
        "select a.hierarchy as hierarchy, a.bangsal || '  ' || b.tdesc as bangsal ".
        "from rs00012 a, rs00001 b ".
        "where substr(a.hierarchy,7,3) = '000' ".
        "and substr(a.hierarchy,1,3) = '".substr($_GET["L1"],0,3)."' ".
        "and hierarchy != '".$_GET["L1"]."' ".
        "and a.klasifikasi_tarif_id = b.tc and b.tt='KTR' ".
        "and is_group = 'Y' ".
        "order by bangsal", $_GET["L2"],
        $ext);
    if (strlen($_GET["L2"]) > 0) $level = 2;
}
$f->execute();

if ($level == 2) {
    $SQL =  "select a.bangsal, b.no_reg, d.nama, ".
            "    to_char(ts_check_in,'DD/MM/YYYY HH24:MI:SS') as tanggal_masuk ".
            "from rs00012 as a ".
            "    left join rs00010 as b on a.id = b.bangsal_id and b.ts_calc_stop is null ".
            "    left join rs00006 as c on b.no_reg = c.id ".
            "    left join rs00002 as d on c.mr_no = d.mr_no ".
            "where a.is_group = 'N' ".
            "    and substr(a.hierarchy,1,6) = '".substr($_GET["L2"],0,6)."'";
    $t = new PgTable($con, "100%");
    $t->SQL = $SQL;
    $t->ColHeader = array("BANGSAL", "REGISTRASI", "NAMA", "TGL MASUK", "&nbsp;");
    $t->ShowRowNumber = true;
    $t->ColAlign[1] = "CENTER";
    $t->ColAlign[3] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    if ($GLOBALS['print']){
    	$t->DisableNavButton = true;
    	$t->DisableScrollBar = true;
    }
    $t->execute();
} elseif ($level == 1) {
    $SQL =  "select a.bangsal, to_char(a.harga,'999,999,999,999.99') as harga, count(b.id) as jumlah_bed, count(c.id) as jumlah_pasien, ".
            "    count(b.id) - count(c.id) as bed_tersedia ".
            "from rs00012 as a ".
            "    join rs00012 as b on substr(a.hierarchy,1,6) = substr(b.hierarchy,1,6) ".
            "        and b.is_group = 'N' ".
            "    left join rs00010 as c on b.id = c.bangsal_id ".
            "        and c.ts_calc_stop is null ".
            "where substr(a.hierarchy,1,3) = '".substr($_GET["L1"],0,3)."' ".
            "    and substr(a.hierarchy,7,3) = '000' ".
            "    and substr(a.hierarchy,4,6) != '000000' ".
            "group by a.bangsal,a.harga ";
    $t = new PgTable($con, "100%");
    $t->SQL = $SQL;
    $t->ColHeader = array("BANGSAL", "HARGA","JUMLAH BED", "JUMLAH PASIEN", "BED TERSEDIA");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColAlign[1] = "RIGHT";
    $t->ColAlign[2] = "RIGHT";
    $t->ColAlign[3] = "RIGHT";
    if ($GLOBALS['print']){
    	$t->DisableNavButton = true;
    	$t->DisableScrollBar = true;
    }
    $t->execute();
} elseif ($level == 0) {
    $SQL =  "select a.bangsal,count(distinct c.id) as jumlah_ruangan, count(b.id)/count(distinct c.id) as jumlah_bed, ".
            "count(d.bangsal_id)/count(distinct c.id) as jumlah_pasien, (count(b.id)-count(d.bangsal_id))/count(distinct c.id) as bed_tersedia ".
            "from rs00012 as a ".
            "    join rs00012 as b on substr(a.hierarchy,1,3) = substr(b.hierarchy,1,3) ".
            "        and b.is_group = 'N' ".
            "    join rs00012 as c on substr(a.hierarchy,1,3) = substr(c.hierarchy,1,3) ".
            "        and c.is_group = 'Y' and substr(c.hierarchy,4,6) != '000000' ".
            "    left join rs00010 as d on b.id = d.bangsal_id ".
            "        and d.ts_calc_stop is null ".
      //      "where substr(a.hierarchy,4,6) = '000000' ".
   //   "where substr(a.hierarchy,4,6) = '000000' ".
     //  "    and substr(a.hierarchy,7,3) != '000' ".
            "    where substr(a.hierarchy,4,6) = '000000' ".
            "group by a.bangsal";
    $t = new PgTable($con, "100%");
    $t->SQL = $SQL;
    $t->ColHeader = array("BANGSAL", "JUMLAH RUANGAN", "JUMLAH BED", "JUMLAH PASIEN", "BED TERSEDIA");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColAlign[1] = "RIGHT";
    $t->ColAlign[2] = "RIGHT";
    $t->ColAlign[3] = "RIGHT";
    $t->ColAlign[4] = "RIGHT";
    if ($GLOBALS['print']){
    	$t->DisableNavButton = true;
    	$t->DisableScrollBar = true;
    }
    $t->execute();
}

?>
