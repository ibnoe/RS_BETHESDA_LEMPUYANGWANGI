<? // Nugraha, 17/02/2004
   // Pur, 27/02/2004
   // Pur, 27/03/2004 : new libs table
   // sfdn, 21-04-2004

$PID = "420";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if ($_GET["tc"] =="view") {
    title("underconstruction !!!!");
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID&mPERIODE=".$_GET["t"].
                                             "'>".icon("back","Kembali")."</a></DIV>";
} else {
    title("Perolehan Angka Kredit Kumulatif Minimal(AKKM)");
    echo "<br>";
    $ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    $f->selectSQL("mPERIODE", "Periode",
        "select '' as tc, '' as tdesc union ".
        "select distinct(to_char(tanggal_trans,'YYYYMM')) as tc, to_char(tanggal_trans,'MONTH YYYY') as tdesc ".
        "from rs00008 ".
        "where trans_type = 'LTM' ", $_GET["mPERIODE"],
        $ext);
    $f->execute();
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<INPUT TYPE=HIDDEN NAME=mUNSUR VALUE='".$_GET["mPERIODE"]."'>";
    //echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    //echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
    echo "</TR></FORM></TABLE></DIV>";
    $t = new PgTable($con, "100%");
    $t->SQL =
        "select a.nip,d.nama,sum(i.kredit) as bobot,l.standard_akkm ".
        "from rs00033 a ".
        "   left outer join rs00008 b ON (a.trans_group = b.trans_group ) ".
	    "   left outer join rs00034 c ON to_number(b.item_id,'999999999')= c.id ".
	    "   left outer join rs00017 d ON a.nip = d.nip ".
	    "   left outer join rs00027 e ON d.rs00027_id = e.id ".
	    "   left outer join rs00001 f ON e.jjd_id = f.tc ".
	    "   left outer join rs00001 g ON e.gol_ruang_id = g.tc ".
	    "   left outer join rs00025 h ON c.id_rincian = h.id_rincian ".
	    "   left outer join rs00026 i ON c.id_rincian = i.id_rincian ".
	    "   left outer join rs00024 j ON h.id_bidang = j.id_bidang ".
	    "   left outer join rs00023 k ON j.id_kegiatan = k.id_kegiatan ".
	    "   left outer join rs00038 l ON k.unsur_id = l.unsur_id ".
        "where b.trans_type='LTM' and c.sumber_pendapatan_id='003' ".
	    "   and g.tt='GRP' and f.tt='JJD' ".
	    "   and (i.jjd_id = e.jjd_id and i.rs00027_id = e.id) ".
	    "   and (d.rs00027_id = l.rs00027_id) ".
        "   and (to_char(b.tanggal_trans,'YYYYMM')='".$_GET["mPERIODE"]."')" .
        "group by a.nip,d.nama,k.unsur_id,l.standard_akkm ".


    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = 14;
    $t->ColAlign[0] = "CENTER";
    $t->ColFormatHtml[0] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID".
                            "&tc=view".
                            "&t=".$_GET["mPERIODE"].
                            "&e=<#0#>'><#0#></A>";
    $t->ColHeader = array("N I P","N A M A", "PEROLEHAN AKKM", "STANDARD AKKM", "&nbsp;");
    $t->execute();
}

?>
