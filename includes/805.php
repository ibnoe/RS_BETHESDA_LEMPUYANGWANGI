<? // Nugraha, 17/02/2004
   // Pur, 27/02/2004
   // Pur, 27/03/2004 : new libs table
   // sfdn, 19-05-2004

$PID = "805";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (empty($_GET[sure])) {
if(strlen($_GET["e"]) > 0) {
    if($_GET["e"] == "new") {
        $f = new Form("actions/805.insert.php");
        title("Jenjang Pangkat Baru");
        echo "<BR>";
        $f->text("id","ID",12,12,"<OTOMATIS>","DISABLED");
        $f->hidden("j",$_GET["j"]);
    } else {
        $r2 = pg_query($con,
            "select * ".
            "from rs00027 ".
            "where id='".$_GET["e"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);
        $f = new Form("actions/805.update.php");
        title("Edit Data Jenjang Pangkat");
        echo "<BR>";
        $f->hidden("id",$_GET["e"]);
        $f->hidden("j",$_GET["j"]);
        $f->text("id","ID",3,3,$_GET["e"],"DISABLED");

    }
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID&mJENJANG=".$_GET["j"].
                                        "'>".icon("back","Kembali")."</a></DIV>";
    $f->PgConn = $con;
    $f->selectSQL("f_jjd_id", "Jenjang Jabatan",
                  "select tc, tdesc ".
                  "from rs00001 where tt='JJD' and tc!='000' ",
				  $d2->jjd_id);
				  //"from rs00001 where tt='JJD' and tc='".$_GET[j]."' ",
                  //$_GET[j]);
    $f->selectSQL("f_gol_ruang_id", "Gol.Ruang",
                  "select tc, tdesc ".
                  "from rs00001 where tt='GRP' and tc!='000' order by tdesc",
                  $d2->gol_ruang_id);
    $f->text("f_nama_jenjang_pangkat","Jenjang Pangkat",50,50,$d2->nama_jenjang_pangkat);
    $f->submit(" Simpan ");
    $f->execute();
    echo "<br>";
    if(strlen($_GET["err"]) > 0) {
        errmsg("Terjadi Kesalahan", stripslashes($_GET["err"]));
    }
} else {
	title("<img src='icon/informasi-2.gif' align='absmiddle' >  Tabel Master : JENJANG PANGKAT PEGAWAI");
        echo "<br>";
    $ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

    $f->selectSQL("mJENJANG", "Jenjang Pangkat",
        "select '' as tc, '' as tdesc union ".
        "select tc, tdesc ".
        "from rs00001 ".
        "where tt='JJD' and tc!='000'", $_GET["mJENJANG"],
        $ext);
    $f->execute();
    echo "<br>";
    $t = new PgTable($con, "100%");
    $t->SQL =
        "select distinct a.nama_jenjang_pangkat, c.tdesc as gol, a.id as dummy ".
	    "from rs00027 a, rs00001 b, rs00001 c ".
	    "where (a.jjd_id = b.tc and b.tt='JJD') and ".
		    "(a.gol_ruang_id = c.tc and c.tt='GRP') and ".
            "a.jjd_id='".$_GET["mJENJANG"]."'";
    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[1] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->RowsPerPage = 14;
    $t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID".
                           "&e=<#2#>".
                           "&j=".$_GET["mJENJANG"]."'>".icon("edit","Edit")."&nbsp;"."</A>".
                           "<A CLASS=TBL_HREF HREF='".
            "actions/805.delete.php?p=$PID".
            "&j=".$_GET["mJENJANG"] .
            "&e=<#2#>".
            "'>".icon("delete","Hapus")."</A>".
            "</nobr>";
    $t->ColHeader = array("JENJANG PANGKAT", "GOL.RUANG", "V i e w");
    $t->execute();
    echo "<BR><DIV ALIGN=LEFT><img src=\"icon/user.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new&j=".$_GET["mJENJANG"]."'>  Jenjang Pangkat Baru  </A></DIV>";

}
}else{
	$data = getFromTable("select nama_jenjang_pangkat from rs00027 where id='".$_GET[e]."'");

    echo "<div align=center>";
    echo "<form action='actions/805.delete.php' method='get'>";
    echo "<font color=red size=3>PERINGATAN !</font><br>";
    echo "<font class=SUB_MENU>Master Jenjang Pangkat Pegawai <font color=navy>'".$data."'</font> akan Dihapus.</font><br><br>";
    echo "<input type=hidden name=p value=$PID>";
    echo "<input type=hidden name=e value=".$_GET[e].">";
    echo "<input type=hidden name=j value=".$_GET[mJENJANG].">";
    
    echo "<input type=submit name=sure value='YA'>&nbsp;";
    echo "<input type=submit name=sure value='TIDAK'>";
    echo "</form>";
    echo "</div>";
}

?>
