<? // Nugraha, Sat May  8 22:22:11 WIT 2004
   // sfdn, 31-05-2004

echo "<div align=right>";
echo "<form action='index2.php' method='get'>";
echo "<input type=hidden name=p value=p_rawat_inap>";
echo "<input type=hidden name=sub value=1>";
echo "<font class=SUB_MENU>NO MR / NO REG / NAMA : </font><input type=text name=search>&nbsp;";
echo "<input type=submit value=' CARI '>";
echo "</form>";
echo "</div>";


$SQLSTR = "select b.mr_no, a.no_reg, b.nama, ".
          "    to_char(a.ts_check_in,'DD MON YYYY HH24:MI:SS') as tgl_masuk, ".
          //"    f.bangsal, e.bangsal as ruangan, d.bangsal as bed, ".
	  //"    f.bangsal, e.bangsal as ruangan, ".
	  "    b.alm_tetap, e.bangsal as ruangan, ".

          "    a.id ".
          "from rs00010 as a ".
          "    join rs00006 as c on a.no_reg = c.id ".
          "    join rs00002 as b on c.mr_no = b.mr_no ".
          "    join rs00012 as d on a.bangsal_id = d.id ".
          "    join rs00012 as e on e.hierarchy = substr(d.hierarchy,1,6) || '000000000' ".
          "    join rs00012 as f on f.hierarchy = substr(d.hierarchy,1,3) || '000000000000' ".
          "where a.ts_calc_stop is null ";

if ($_GET[search]) {
    $SQLSTR .= " and (upper(b.nama) like '%".strtoupper($_GET[search])."%' or a.no_reg like '%".$_GET[search]."%' or b.mr_no like '%".$_GET[search]."%') ";

}

$t = new PgTable($con, "100%");
$t->SQL = "$SQLSTR";


if (!isset($_GET[sort])) {

           $_GET[sort] = "id";
           $_GET[order] = "desc";
}

$t->ColHeader = array("NO MR", "NO REG", "NAMA", "TGL/JAM MASUK", "ALAMAT",
                      "RUANGAN",  "&nbsp;");
$t->ShowRowNumber = true;
$t->ColAlign[0] = "CENTER";
$t->ColAlign[1] = "CENTER";

$t->ColAlign[3] = "CENTER";
//$t->ColAlign[5] = "CENTER";
$t->RowsPerPage = $ROWS_PER_PAGE;
$t->ColFormatHtml[6] = "<A CLASS=SUB_MENU1 HREF='$SC?p=$PID&sub=4&rg=<#1#>'>".icon("view","View")."</A>";
$t->execute();


unset($_SESSION["BANGSAL"]["desc"]);
unset($_SESSION["BANGSAL"]["id"]);


?>
