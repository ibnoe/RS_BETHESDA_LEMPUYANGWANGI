<? // Nugraha, 23/02/2004
   // Pur, 09/03/2004: new libs table
   // sfdn, 14-05-2004
   // sfdn, 20-05-2004
   // sfdn, 07-06-2004   
   
$PID = "809";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

title("Tabel Master: PEGAWAI");


$is_selected = getFromTable(
    "select count(id) ".
    "from rs00018 ".
    "where unit_medis_fungsional_id = '" . $_GET["mPEG"] . "' ".
    "    and id = '" . $_GET["mJAB"] . "'") > 0;

    echo "<br>";

if (isset($_GET["e"])) {
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
                  "select '' as id, '' as jab union ".
                  "select to_char(a.id,'999999999'), rpad(ltrim(b.tdesc),20,' ') || '      ' || c.tdesc as jab ".
                  "from rs00027 a, rs00001 b, rs00001 c ".
	              "where (a.jjd_id = b.tc and b.tt='JJD') and ".
                    "(a.gol_ruang_id = c.tc and c.tt='GRP')",
                    $d2->rs00027_id);
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

} else {
    //if ($is_selected) {
        // search box
        if (isset($_GET["e"])) {
            $ext = "DISABLED";
        } else {
            $ext = "OnChange = 'Form1.submit();'";
        }
        echo "<br>";
        $f = new Form($SC, "GET", "NAME=Form1");
        $f->PgConn = $con;
        $f->hidden("p", $PID);
	   $f->selectSQL("mLEVEL1", "UNIT LAYANAN",
			"select '' as tc, '' as tdesc union " .
			"select hierarchy as tc, layanan as tdesc ".
			"from rs00034 ".
			"where length(rtrim(hierarchy,'0'))=3 "
			, $_GET["mLEVEL1"],$ext);	
			
		$f->selectSQL("mLEVEL2", "SUB-UNIT LAYANAN",
			"select '' as tc, '' as tdesc union " .
			"select hierarchy as tc, layanan as tdesc ".
			"from rs00034 ".
			"where length(rtrim(hierarchy,'0'))!=3 ".
			"	and substr(hierarchy,1,3) = '".$_GET["mLEVEL1"]."' "		
			, $_GET["mLEVEL2"], $ext);			
			
        $f->execute();
    if ($is_selected) {
        echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
        echo "<INPUT TYPE=HIDDEN NAME=mLEVEL1 VALUE='".$_GET["mLEVEL1"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=mLEVEL2 VALUE='".$_GET["mLEVEL2"]."'>";
        echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
        echo "<TD><INPUT TYPE=SUBMIT VALUE=' Nama Pegawai '></TD>";
        echo "</TR></FORM></TABLE></DIV>";
        $t = new PgTable($con, "100%");
	$SQL =
		"select distinct(a.layanan), ".
		"   case when (select x.sumber_pendapatan_id ".
		"			  from rs00034 x ".
		"			  where x.layanan = a.layanan and ".
		"					x.sumber_pendapatan_id ='001') is null ".
		"		then 0 ".
		"		else ".
		"			 (select x.harga ".
		"			  from rs00034 x ".
		"			  where x.layanan = a.layanan and ".
		"					x.sumber_pendapatan_id = '001') ".
		"		end as bahan, ".
		"  case when (select x.sumber_pendapatan_id  ".
		"			  from rs00034 x  ".
		"			  where x.layanan = a.layanan and ".
		"					x.sumber_pendapatan_id ='002') is null ".
		"		then 0 ".
		"		else ".
		"			 (select x.harga ".
		"			  from rs00034 x ".
		"			  where x.layanan = a.layanan and ".
		"					x.sumber_pendapatan_id = '002') ".
		"		end as sarana, ".
		"  case when (select x.klasifikasi_tarif_id  ".
		"			  from rs00034 x ".
		"			  where x.layanan = a.layanan and ".
		"					x.klasifikasi_tarif_id ='002') is null ".
		"		then 0 ".
		"		else ".
		"			 (select x.harga ".
		"			  from rs00034 x ".
		"			  where x.layanan = a.layanan and ".
		"					x.klasifikasi_tarif_id = '002') ".
		"		end as kelas1, ".
		"  case when (select x.klasifikasi_tarif_id  ".
		"			  from rs00034 x  ".
		"			  where x.layanan = a.layanan and ".
		"					x.klasifikasi_tarif_id ='003') is null ".
		"		then 0 ".
		"		else ".
		"			 (select x.harga ".
		"			  from rs00034 x ".
		"			  where x.layanan = a.layanan and ".
		"					x.klasifikasi_tarif_id = '003') ".
		"		end as kelas2, ".
		"  case when (select x.klasifikasi_tarif_id  ".
		"			  from rs00034 x ".
		"			  where x.layanan = a.layanan and ".
		"					x.klasifikasi_tarif_id ='019') is null ".
		"		then 0 ".
		"		else ".
		"			 (select x.harga ".
		"			  from rs00034 x ".
		"			  where x.layanan = a.layanan and ".
		"					x.klasifikasi_tarif_id = '019') ".
		"		end as kelas3a, ".
		"  case when (select x.klasifikasi_tarif_id ".
		"			  from rs00034 x  ".
		"			  where x.layanan = a.layanan and ".
		"					x.klasifikasi_tarif_id ='022') is null ".
		"		then 0 ".
		"		else ".
		"			 (select x.harga ".
		"			  from rs00034 x ".
		"			  where x.layanan = a.layanan and ".
		"					x.klasifikasi_tarif_id = '022') ".
		"		end as utama, ".
		"  case when (select x.klasifikasi_tarif_id  ".
		"			  from rs00034 x ".
		"			  where x.layanan = a.layanan and ".
		"					x.klasifikasi_tarif_id ='021') is null  ".
		"		then 0 ".
		"		else ".
		"			 (select x.harga ".
		"			  from rs00034 x ".
		"			  where x.layanan = a.layanan and ".
		"					x.klasifikasi_tarif_id = '021') ".
		"		end as teladan ".
		"from rs00034 a ".
		"where is_group = 'N' ".
		"   and substr(a.hierarchy,1,6)='003099' ".
		"   and length(rtrim(a.hierarchy,'0'))!=3  "; 
		
		
        $t->SQL = "$SQL";
        $t->setlocale("id_ID");
        $t->ColAlign[0] = "CENTER";
        $t->ColAlign[7] = "CENTER";
        $t->ShowRowNumber = true;
        $t->RowsPerPage = $ROWS_PER_PAGE;
}

?>
