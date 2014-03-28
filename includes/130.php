<? // Nugraha, 23/02/2004
   // Pur, 09/03/2004: new libs table
   // sfdn, 14-05-2004
   // sfdn, 20-05-2004
   // sfdn, 07-06-2004
   
   
$PID = "130";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

title("Data Pasien Blm. Dilayani");


$is_selected = getFromTable(
    "select count(id) ".
    "from rs00018 ".
    "where unit_medis_fungsional_id = '" . $_GET["mPEG"] . "' ".
    "    and id = '" . $_GET["mJAB"] . "'") > 0;

    echo "<br>";

//if (isset($_GET["e"])) {
/*
    echo "<div align=right><a href='".
        "$SC?p=$PID&mPEG=".$_GET["mPEG"]."&mJAB=".$_GET["mJAB"].
        "'>".icon("back", "Kembali")."</a></div>";
    echo "<div class=BOX>";

    if ($_GET["e"] == "new") {

        title("Tambah Data Pegawai");
        echo "<br>";
        $f = new Form("$SC", "GET", "name='Form2'");
        $f->PgConn = $con;
        $f->hidden("p", $PID);
        $f->hidden("mPEG", $_GET["mPEG"]);
        $f->hidden("mJAB", $_GET["mJAB"]);
        $f->hidden("e", "new");
        $f->hidden("f_jabatan_medis_fungsional_id", $_GET["mJAB"]);
        $f->text("f_id","Kode",12,12,"&lt;OTOMATIS&gt;","DISABLED");
        $f->text("f_nama","N a m a",50,50,$_GET["f_nama"]);
        $f->text("f_nip","N I P",12,12,$_GET["f_nip"]);
        $f->selectDate("f_tanggal_lahir", "Tanggal Lahir", getDate(mktime(0,0,0,(int)date("m"),(int)date("d"),((int)date("Y"))-30)));
        $f->selectSQL("f_jjd_id", "Jenjang Jabatan",
                  "select '-' as tc,'-' as tdesc union select tc, tdesc from rs00001 where tt = 'JJD' and tc != '000'",
                  $d2->jjd_id);

        $f->selectSQL("f_gol_ruang_id", "Gol.Ruang",
                  "select '-' as tc,'-' as tdesc union select tc, tdesc from rs00001 where tt = 'GRP' and tc != '000'",
                  $d2->gol_ruang_id);

        $f->selectSQL("f_agama_id", "Agama",
                  "select tc, tdesc from rs00001 where tt = 'AGM' and tc != '000'",
                  $d->agama_id);

        $f->submit(" Simpan ", "onClick='Form2.method=\"POST\";Form2.action=\"actions/809.insert.php\";'");
        $f->execute();
    } else {

        title("Edit Data Pegawai");
        echo "<br>";
        $f = new Form("$SC", "GET", "name='Form2'");
        $f->PgConn = $con;
        $f->hidden("p", $PID);
        $f->hidden("mPEG", $_GET["mPEG"]);
        $f->hidden("mJAB", $_GET["mJAB"]);

        $r2 = pg_query($con,
            "select * ".
            "from rs00017 ".
            "where id = '".$_GET["e"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);

        $r3 = pg_query($con,
            "select * ".
            "from rs00001 ".
            "where tc='".$_GET["mPEG"]."' and tt='PEG'");
        $d3 = pg_fetch_object($r3);
        pg_free_result($r3);

        $f->hidden("id", $d2->id);
        $f->hidden("f_jabatan_medis_fungsional_id", $_GET["mJAB"]);
        $f->text("id","Kode",12,12,$d2->id,"DISABLED");
        $f->text("f_nama","N a m a",50,50,$d2->nama);
        $f->text("f_nip","N I P",12,12,$d2->nip);
        $f->selectSQL("", "Unit Medis",
                  "select '-' as tc,'-' as tdesc union ".
                  "select tc, tdesc ".
                  "from rs00001 ".
                  "where tt='PEG' and tc!='000'",
                  $d3->tc);
        $f->selectSQL("f_jabatan_medis_fungsional_id", "Jabatan Medis",
                  "select '-' as id,'-' as jabatan_medis_fungsional union ".
                  "select id, jabatan_medis_fungsional ".
                  "from rs00018 ",
                  $d2->jabatan_medis_fungsional_id);


        $f->selectSQL("f_rs00027_id", "Jenjang Jabatan dan Gol.",
                  "select '-' as tc,'-' as tdesc union ".
                  "select tc, tdesc from rs00001 where tt = 'JJD' and tc != '000'",
                  $d2->rs00027_id);

        $f->selectSQL("f_rs00027_id", "Jenjang Jabatan dan Gol.",
                  "select '' as id, '' as jab union ".
                  "select to_char(a.id,'999999999'), rpad(ltrim(b.tdesc),20,' ') || '      ' || c.tdesc as jab ".
                  "from rs00027 a, rs00001 b, rs00001 c ".
	              "where (a.jjd_id = b.tc and b.tt='JJD') and ".
                    "(a.gol_ruang_id = c.tc and c.tt='GRP')",
                    $d2->rs00027_id);

        $f->selectSQL("f_gol_ruang_id", "Gol.Ruang",
                  "select '-' as tc,'-' as tdesc union select tc, tdesc from rs00001 where tt = 'GRP' and tc != '000'",
                  $d2->gol_ruang_id);

        $f->selectDate("f_tanggal_lahir", "Tanggal Lahir", pgsql2phpdate($d2->tanggal_lahir));
        $f->selectSQL("f_agama_id", "Agama",
                  "select tc, tdesc from rs00001 where tt = 'AGM' and tc != '000'",
                  $d2->agama_id);

        $f->submit(" Simpan ", "onClick='Form2.method=\"POST\";Form2.action=\"actions/809.update.php\";'");
        $f->execute();
    }
    echo "</div>";
    echo "<br>";
    if (isset($_GET["err"])) errmsg("Terjadi Kesalahan",stripslashes($_GET["err"]));
*/
//} else {
    //if ($is_selected) {
        // search box
		/*
        if (isset($_GET["e"])) {
            $ext = "DISABLED";
        } else {
            $ext = "OnChange = 'Form1.submit();'";
        }
        echo "<br>";
        $f = new Form($SC, "GET", "NAME=Form1");
        $f->PgConn = $con;
        $f->hidden("p", $PID);
        $f->selectSQL("mPEG", "Unit Medis",
            "select '' as tc, '' as tdesc union " .
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'PEG' and tc!='000' ".
            "order by tdesc", $_GET["mPEG"],
            $ext);
        $f->selectSQL("mJAB", "Jabatan Medis",
            "select '' as id, '' as jabatan_medis_fungsional union " .
            "select id, jabatan_medis_fungsional ".
            "from rs00018 ".
            "where unit_medis_fungsional_id = '" . $_GET["mPEG"] . "' ".
            "order by jabatan_medis_fungsional", $_GET["mJAB"],
            $ext);
        $f->execute();
    //if ($is_selected) {
	*/
        echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
        //echo "<INPUT TYPE=HIDDEN NAME=mPEG VALUE='".$_GET["mPEG"]."'>";
        //echo "<INPUT TYPE=HIDDEN NAME=mJAB VALUE='".$_GET["mJAB"]."'>";
        echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
        echo "<TD><INPUT TYPE=SUBMIT VALUE=' Nama Pasien '></TD>";
        echo "</TR></FORM></TABLE></DIV>";
		
		
        $t = new PgTable($con, "100%");
        $t->SQL =
      		"select a.id, to_char(tanggal_reg,'DD MON YYYY') as tgl_registrasi,b.nama, a.mr_no,  ".
			"	case when a.rawat_inap = 'Y' then 'RAWAT JALAN'  ".
			"		else 'IGD' end as rawat ".
			"from rs00006 a ".
			"	left join rs00002 b ON a.mr_no = b.mr_no ".
			"where a.is_karcis = 'N' and a.rawat_inap NOT IN ('I') ".
			"	and upper(b.nama) LIKE '%".strtoupper($_GET["search"])."%' ";
			
        if (!isset($_GET[sort])) {

           $_GET[sort] = "id";
           $_GET[order] = "asc";
	}

        $t->setlocale("id_ID");
        $t->ColAlign[0] = "CENTER";
        $t->ColAlign[1] = "CENTER";
		$t->ColAlign[3] = "CENTER";
        $t->ShowRowNumber = true;
        $t->RowsPerPage = $ROWS_PER_PAGE;
        //$t->ColFormatHtml[0] =
            "<A CLASS=TBL_HREF HREF='".
            "$SC?p=$PID".
            "&e=<#0#>".
            "'><#0#></A></nobr>";
        $t->ColHeader = Array( "NO.REG.","TGL.REGISTRASI", "NAMA", "MR.NO","LOKET");
        $t->execute();
//}

?>
