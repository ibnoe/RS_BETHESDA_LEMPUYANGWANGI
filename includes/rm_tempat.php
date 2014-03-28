<? // 30/12/2003

//if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "ugd" || $_SESSION[uid] == "rm" || $_SESSION[uid] == "root") {

$PID = "rm_tempat";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

if(isset($_GET["e"])) {
        $r = @pg_query($con, "select * from rs00002 where mr_no = '".$_GET["e"]."'");
        $n = @pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        @pg_free_result($r);


        $f = new Form("actions/rm_tempat.update.php", "POST", "NAME=Form1");
        $f->hidden("p",$PID);
        $f->subtitle("Tempat Penyimpanan RM");
        //$f->text("id_pegawai","ID",12,12,$_GET["id"],"Disabled");
        $f->hidden("f_id",$_GET["e"]);
        $f->textarea("f_tempat", "Tempat RM", 4, 50, $d->tempat_rm);
	$f->PgConn = $con;
        $f->submit(" Proses ");
	$f->execute();
} else {
    
    	title("<img src='icon/informasi.gif' align='absmiddle' > DATA PASIEN");
    
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    if(!$GLOBALS['print']){
    	echo "<TD class=FORM>Pencarian : <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    	echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
    }else{
    	echo "<TD class=FORM>Pencarian : <INPUT disabled TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    	echo "<TD><INPUT disabled TYPE=SUBMIT VALUE=' Cari '></TD>";
    }
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");

 $t->SQL = "select a.mr_no,upper(a.nama)as nama,a.mr_rs, ".
              " a.tempat_rm, a.mr_no as href ".
              "FROM rs00002 a ".
              "left join rs00001 b on a.tipe_pasien = b.tc and b.tt = 'JEP'".
              "where upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' ".
              "OR a.mr_no LIKE '%".$_GET["search"]."%' ".
              "OR upper(a.alm_tetap) like '%".strtoupper($_GET["search"])."%' ".
              "OR upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' ".
              "OR upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%'";

        if (!isset($_GET[sort])) {
           $_GET[sort] = "mr_no";
           $_GET[order] = "asc";
	}
 $t->ColHeader = array("NO.MR","NAMA","MR LAMA","TEMPAT RM","EDIT","&nbsp;");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[4] = "CENTER";
    $t->ColFormatHtml[4] = "<nobr><A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#4#>'>".icon("edit","Edit")."</A></nobr>";
    $t->execute();
}

?>

