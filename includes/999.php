 <?php
// Agung Sunandar
	
/* By YGR  */
$jns_kasir = array(
	"rj"=>"RAWAT JALAN", 
	"ri"=>"RAWAT INAP",
	"igd"=>"IGD",
) ;
$kasirnya = $_GET["kas"] ;

if ($_GET["mJNS"]=="205"){
    $poli="p_fisioterapi";
}  elseif ($_GET["mJNS"]=="203") {
    $poli="p_laboratorium";
}   elseif ($_GET["mJNS"]=="209") {
    $poli="p_operasi";
}   elseif ($_GET["mJNS"]=="204") {
    $poli="p_radiologi";
}
// if ($_SESSION[uid] == "kasir2" || $_SESSION[uid] == "igd"|| $_SESSION[uid] == "kasir1"|| $_SESSION[uid] == "root") {
$PID = "999";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");


 
echo "</br>";
title("<img src='icon/kasir-2.gif' align='absmiddle' > KASIR PENUNJANG ".$jns_kasir[$kasirnya]."");
echo "</br>";
             
$reg = $_GET["rg"];
$id_reg =  getfromtable("select to_number(id,'9999999999') as id FROM rs00006 where to_number(id,'9999999999') = $reg");
if ($reg > 0) {
    if ($id_reg == 0)
	{
        $reg = 0;
        $msg = "Nomor registrasi tidak ditemukan. Masukkan kembali nomor registrasi.";
    }
}


if ($reg > 0) {
 
     echo "<DIV ALIGN=RIGHT OnClick='window.location=\"$SC?p=$PID&t1=$ts_check_in1&kas=$kasirnya\"'>".icon("back","Kembali")."</a></DIV>";

    include("335.inc.php");

    echo "<form name=Form3>";
    echo "<input name=b2 type=button value='Pembayaran'       onClick='window.location=\"$SC?p=$PID&t1=$ts_check_in1&kas=$kasirnya&rg=".$_GET["rg"]."&sub=4\";'".($_GET["sub"] == "4" ? " DISABLED" : "").">&nbsp;";
    echo "</form>";
    
    $sub = isset($_GET["sub"]) ? $_GET["sub"] : "4";
    if (file_exists("includes/$PID.$sub.php")) include_once("includes/$PID.$sub.php");

} else {
     if (!$GLOBALS['print']){
	$f = new Form($SC, "GET", "NAME=Form1");
        $f->PgConn = $con;
        $f->hidden("p", $PID);
        $f->selectArray("kas", "JENIS KUNJUNGAN",Array(""=>"Semuanya","rj" => "RAWAT JALAN", "ri" => "RAWAT INAP", "igd" => "IGD"),$_GET[kas]);
        $f->selectSQL("mJNS", "PENUNJANG",
            "select '' as tc, '' as tdesc union " .
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'LYN' and tc in ('203','204','205','209') ".
            "order by tdesc", $_GET["mJNS"],
            $ext);
        $f->submit ("TAMPILKAN");
        $f->execute();
    }else{
        $f = new Form($SC, "GET", "NAME=Form1");
        $f->PgConn = $con;
        $f->hidden("p", $PID);
        $f->selectArray("kas", "JENIS KUNJUNGAN",Array(""=>"Semuanya","rj" => "RAWAT JALAN", "ri" => "RAWAT INAP", "igd" => "IGD"),$_GET[kas],"disabled");
        $f->selectSQL("mJNS", "PENUNJANG",
            "select '' as tc, '' as tdesc union " .
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'LYN' and tc::text in ('203','204','205','209') ".
            "order by tdesc", $_GET["mJNS"],
            $ext,"disabled");
        $f->submit ("TAMPILKAN");
        $f->execute();
        }
                
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p",$PID);
    $f->hidden("kas",$kasirnya);
    
    echo "<DIV ALIGN=RIGHT>";
    echo "<TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID >";
    echo "<INPUT TYPE=HIDDEN NAME=kas VALUE='$kasirnya' >";        
    echo "<TD >Pencarian : <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";
 
    echo "</TR></FORM></TABLE>";
    echo "</DIV>";

    $what = $jns_kasir[$kasirnya] ;
    $SQLSTR  = "SELECT a.no_reg, d.nama, d.mr_no, tanggal(c.tanggal_reg,0) as tanggal_reg, e.tdesc as nm_poli,sum(a.tagihan) as tagihan,f.sisa
                   FROM rs00008 a
                   LEFT JOIN rs00006 c ON a.no_reg::text = c.id::text
                   LEFT JOIN rs00001 e ON e.tc::text = c.poli::text and e.tt='LYN'
                   LEFT JOIN rs00002 d ON c.mr_no::text = d.mr_no::text
                   LEFT JOIN rsv0012 f ON f.id::text = a.no_reg::text
              WHERE f.rawat like '%$what%' and a.trans_form like '%$poli%' and a.trans_type::text = 'LTM'::text and a.trans_form in ('p_fisioterapi','p_laboratorium','p_radiologi','p_operasi')
              GROUP BY a.trans_form, a.no_reg,c.tanggal_reg, d.nama, d.mr_no, c.rawat_inap, c.poli,e.tdesc,f.sisa
              ORDER BY a.no_reg  " ;
   

	echo "<br>";

    $t = new PgTable($con, "100%");
    $t->SQL = "$SQLSTR ";
    $t->ColHeader = array("NO.REG", "N A M A", "NO. MR", "TGL. REGISTRASI", "ASAL PASIEN","TAGIHAN","SISA");
    $t->ShowRowNumber = true;
    $t->setlocale("id_ID");
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "CENTER";
    $t->ColAlign[4] = "CENTER";
    $t->ColAlign[5] = "CENTER";
    $t->ColAlign[6] = "CENTER";
    $t->ColAlign[7] = "CENTER";
    $t->ColAlign[8] = "CENTER";
    $t->RowsPerPage = 20;
    $t->ColFormatHtml[1] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID"."&rg=<#0#>&sub=4"."&kas=$kasirnya&mJNS=$_GET[mJNS]><#1#></A>";
    $t->execute();
}
?>
