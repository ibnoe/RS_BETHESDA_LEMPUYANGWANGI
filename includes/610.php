<?  // tokit, 2004-09-30

$PID = "610";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if(strlen($_GET["e"]) > 0) {
    if($_GET["e"] == "new") {
        $f = new Form("actions/610.insert.php");
        title("Insert Data Angka Kredit");
        echo "<BR>";
        $f->text("id","ID",12,12,"<OTOMATIS>","DISABLED");
        $f->hidden("u",$_GET["u"]);
        $f->hidden("s",$_GET["s"]);
        $f->hidden("b",$_GET["b"]);
        $f->hidden("r",$_GET["r"]);
        $f->hidden("v",$_GET["v"]);
        $krd = 0;
    } else {

        $nama = getFromTable("select nama from rs00017 where id = '".$_GET[e]."'");
        $jjd_id = getFromTable("select jjd_id from rs00017 where id = '".$_GET[e]."'");
        title("Data Perolehan Angka Kredit: ".$nama);

        echo "<BR>";
        echo "<font class=BESAR>KEGIATAN</font>";
        $t = new PgTable($con, "100%");
        $t->SQL =
            "select a.tanggal, f.tdesc as unsur,b.nama_rincian_kegiatan, c.kredit ".
                //", a.id as dummy ".
	        "from rs00030 a ".
	            "left join rs00025 b ON b.id = a.rs00025_id ".
	            "left join rs00026 c on c.id_rincian = '".$_GET[mRINCIAN]."' ".
	            "left join rs00024 d on d.id_bidang = b.id_bidang ".
                    "left join rs00023 e on e.id_kegiatan = d.id_kegiatan ".
                    "left join rs00001 f on f.tc = e.unsur_id and f.tt = 'UKP' ".

		"where a.rs00017_id='".$_GET[e]."' ";


        $t->setlocale("id_ID");
        $t->ColAlign[0] = "CENTER";
        //$t->ColAlign[7] = "CENTER";
        $t->ShowRowNumber = true;
        $t->RowsPerPage = $ROWS_PER_PAGE;

        /*
        $t->ColFormatHtml[7] =
            "<A CLASS=TBL_HREF HREF='".
            "actions/610.insert.php?p=$PID".
            "&u=".$_GET[mUNSUR].
            "&s=".$_GET[mSUBUNSUR].
            "&b=".$_GET[mBIDANG].
            "&r=".$_GET[mRINCIAN].

            "&mPEG=" . $_GET["mPEG"] .
            "&mJAB=" . $_GET["mJAB"] .
            "&e=<#7#>".
            "'>".icon("ok","Angka Kredit")."</A>".
	    "</nobr>";
	*/
        $t->ColHeader = Array( "TANGGAL","UNSUR KEG.","RINCIAN KEGIATAN","KREDIT");
        $t->execute();

        echo "<BR>";
        echo "<font class=BESAR>ANGKA KREDIT</font>";
        $t = new PgTable($con, "100%");
        $t->SQL =
            "select sum(a.akkm_utama) as utama, sum(a.akkm_penunjang) as penunjang  ".
                //", a.id as dummy ".
	        "from rs00029 a ".
	            //"left join rs00025 b ON b.id = a.rs00025_id ".
	            //"left join rs00026 c on c.id_rincian = '".$_GET[mRINCIAN]."'  ".
		"where a.rs00017_id='".$_GET[e]."' ";

        $t->setlocale("id_ID");
        $t->ColAlign[0] = "CENTER";
        $t->ColAlign[1] = "CENTER";
        $t->ShowRowNumber = true;
        $t->RowsPerPage = $ROWS_PER_PAGE;

        /*
        $t->ColFormatHtml[7] =
            "<A CLASS=TBL_HREF HREF='".
            "actions/610.insert.php?p=$PID".
            "&u=".$_GET[mUNSUR].
            "&s=".$_GET[mSUBUNSUR].
            "&b=".$_GET[mBIDANG].
            "&r=".$_GET[mRINCIAN].

            "&mPEG=" . $_GET["mPEG"] .
            "&mJAB=" . $_GET["mJAB"] .
            "&e=<#7#>".
            "'>".icon("ok","Angka Kredit")."</A>".
	    "</nobr>";
	*/
        $t->ColHeader = Array( "AK UTAMA","AK PENUNJANG");
        $t->execute();

    }

/*
        $r2 = pg_query($con,
            "select * ".
            "from rs00026 ".
            "where id='".$_GET["e"]."'");
        $d2 = pg_fetch_object($r2);
        pg_free_result($r2);

        $f = new Form("actions/610.update.php");
        title("Input Data Angka Kredit");
        echo "<BR>";
        $f->hidden("id",$_GET["e"]);
        $f->hidden("u",$_GET["u"]);
        $f->hidden("s",$_GET["s"]);
        $f->hidden("b",$_GET["b"]);
        $f->hidden("r",$_GET["r"]);
        $f->hidden("v",$_GET["v"]);
        $krd=$d2->kredit;
        //$f->text("id","ID",4,4,$_GET["e"],"DISABLED");
    }
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID&mUNSUR=".$_GET["u"].
                                        "&mSUBUNSUR=".$_GET["s"].
                                        "&mBIDANG=".$_GET["b"].
                                        "&mRINCIAN=".$_GET["r"].
                                        "&mJENJANG=".$_GET["v"].
                                        "'>".icon("back","Kembali")."</a></DIV>";
    // table unsur kegiatan
    $r3 = pg_query($con,
        "select tdesc ".
        "from rs00001 ".
        "where tc='".$_GET["u"]."'");
    $d3 = pg_fetch_object($r3);
    pg_free_result($r3);

    // table sub-unsur kegiatan
    $r4 = pg_query($con,
        "select * ".
        "from rs00023 ".
        "where id_kegiatan='".$_GET["s"]."'");
    $d4 = pg_fetch_object($r4);
    pg_free_result($r4);

    // table Bidang kegiatan
    $r5 = pg_query($con,
        "select * ".
        "from rs00024 ".
        "where id_bidang='".$_GET["b"]."'");
    $d5 = pg_fetch_object($r5);
    pg_free_result($r5);

    // table rincian kegiatan
    $r6 = pg_query($con,
        "select * ".
        "from rs00025 ".
        "where id_rincian='".$_GET["r"]."'");
    $d6 = pg_fetch_object($r6);
    pg_free_result($r6);

    // table Jenjang Jabatan
    $r7 = pg_query($con,
        "select * ".
        "from rs00001 ".
        "where tc='".$_GET["v"]."' and tt='JJD'");
    $d7 = pg_fetch_object($r7);
    pg_free_result($r7);

    // table Jenjang Pangkat
    $r8 = pg_query($con,
        "select * ".
        "from rs00027 ".
        "where jjd_id=$d7->tc");
    $d8 = pg_fetch_object($r8);
    pg_free_result($r8);

    $f->PgConn = $con;
    $f->selectSQL("", "Unsur Kegiatan",
                  "select tc, tdesc ".
                  "from rs00001 where tt='UKP' and tc!='000' ",
                  $d3->tdesc);
    $f->selectSQL("", "Sub-Unsur Kegiatan",
                  "select id_kegiatan, nama_sub_unsur ".
                  "from rs00023",
                  $d4->id_kegiatan);
    $f->selectSQL("", "Bidang Kegiatan",
                  "select id_bidang, nama_bidang_kegiatan ".
                  "from rs00024",
                  $d5->id_bidang);

    $f->selectSQL("f_id_rincian", "Rincian Bidang Kegiatan",
                  "select id_rincian, nama_rincian_kegiatan ".
                  "from rs00025",
                  $d6->id_rincian);
    $f->selectSQL("f_jjd_id", "Jenjang Jabatan Dokter",
                  "select tc, tdesc ".
                  "from rs00001 where tc='".$_GET["v"]."' and tt='JJD' and tc!='000' ",
                  $d2->jjd_id);

    $f->selectSQL("f_rs00027_id", "Jenjang Pangkat Dokter",
                  "select id, nama_jenjang_pangkat ".
                  "from rs00027 where jjd_id ='".$_GET["v"]."'",
                  $d2->f_rs00027_id);

    $f->text("","Prasyarat(Angka)",4,5,$d6->prasyarat);
    $f->selectSQL("", "Prasyarat(Satuan)",
                  "select '-' as tc, '-' as tdesc union ".
                  "select tc,tdesc from rs00001 ".
                  "where tt='SAT' and tc in ('020','021','022','050')",
                  $d6->satuan_id);

    $f->text("f_kredit","Angka Kredit",4,5,$krd);
    $f->submit(" Simpan ");
    $f->execute();
    echo "<br>";
    if(strlen($_GET["err"]) > 0) {
        errmsg("Terjadi Kesalahan", stripslashes($_GET["err"]));
    }
*/

} else {

    title("Input Angka Kredit");
    $ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);
    $f->selectSQL("mUNSUR", "Unsur Kegiatan",
        "select '' as tc, '' as  tdesc union ".
        "select tc, tdesc ".
        "from rs00001 ".
        "where tt='UKP' and tc!='000'", $_GET["mUNSUR"],
        $ext);
    $f->selectSQL("mSUBUNSUR", "Sub-Unsur Kegiatan",
        "select '' as id_kegiatan, '' as nama_sub_unsur union ".
        "select id_kegiatan, nama_sub_unsur ".
        "from rs00023 ".
        "where unsur_id = '" . $_GET["mUNSUR"] . "' ".
        "order by nama_sub_unsur", $_GET["mSUBUNSUR"],
        $ext);
    $f->selectSQL("mBIDANG", "Bidang Kegiatan Penilaian",
         "select '' as id_bidang, '' as nama_bidang_kegiatan union ".
         "select id_bidang, nama_bidang_kegiatan ".
         "from rs00024 a, rs00023 b ".
         "where b.id_kegiatan = '" .$_GET["mSUBUNSUR"]."' and ".
            "b.id_kegiatan = a.id_kegiatan and ".
            "b.unsur_id = '".$_GET["mUNSUR"]."' ".
        "order by nama_bidang_kegiatan", $_GET["mBIDANG"],
        $ext);
    $f->selectSQL("mRINCIAN", "Rincian Kegiatan Penilaian",
         "select '' as id_rincian, '' as nama_rincian_kegiatan union ".
         "select a.id_rincian, a.nama_rincian_kegiatan ".
         "from rs00025 a, rs00024 b ".
         "where b.id_bidang = '" .$_GET["mBIDANG"]."' and ".
            "b.id_bidang = a.id_bidang  ".
        "order by nama_rincian_kegiatan", $_GET["mRINCIAN"],
        $ext);
	

        // ********** include from 809 (master pegawai)
        $f->selectSQL("mPEG", "Unit Medis",
            "select '' as tc, '' as tdesc union " .
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'PEG' and tc!='000' ".
            "order by tdesc", $_GET["mPEG"],
            $ext);
        $f->selectSQL("mJAB", "Sub Unit Medis",
            "select '' as id, '' as jabatan_medis_fungsional union " .
            "select id, jabatan_medis_fungsional ".
            "from rs00018 ".
            "where unit_medis_fungsional_id = '" . $_GET["mPEG"] . "' ".
            "order by jabatan_medis_fungsional", $_GET["mJAB"],
            $ext);

        // *********** end of include


    $f->execute();
    echo "<br>";

    $headerakkm = "STD.AKKM UTAMA";
    if ($_GET["mUNSUR"] == "002") {
        $headerakkm == "STD.AKKM PENUNJANG";
    }


        $t = new PgTable($con, "100%");
        $t->SQL =
            "select a.nip,a.nama, e.tdesc as agama,to_char(tanggal_lahir,'DD MON YYYY') as lahir, ".
                "b.tdesc as jabatan, c.tdesc as golongan, d.nama_jenjang_pangkat ".
                ", a.id as dummy ".
	        "from rs00017 a ".
	            "left outer join rs00027 d ON a.rs00027_id = d.id  ".
	            "left outer join rs00001 e ON a.agama_id = e.tc and e.tt='AGM' ".
	            "left outer join rs00001 b ON d.jjd_id = b.tc and b.tt='JJD' ".
	            "left outer join rs00001 c ON d.gol_ruang_id = c.tc and c.tt='GRP' ".
		"where a.jabatan_medis_fungsional_id='".$_GET["mJAB"]."' ";
		    
            //"where a.jabatan_medis_fungsional_id='".$_GET["mJAB"]."' ".
            //    "and (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%')";

        $t->setlocale("id_ID");
        $t->ColAlign[0] = "CENTER";
        $t->ColAlign[7] = "CENTER";
        $t->ShowRowNumber = true;
        $t->RowsPerPage = $ROWS_PER_PAGE;
        $t->ColFormatHtml[7] =
            "<A CLASS=TBL_HREF HREF='".
            "actions/610.insert.php?p=$PID".
            "&u=".$_GET[mUNSUR].
            "&s=".$_GET[mSUBUNSUR].
            "&b=".$_GET[mBIDANG].
            "&r=".$_GET[mRINCIAN].

            "&mPEG=" . $_GET["mPEG"] .
            "&mJAB=" . $_GET["mJAB"] .
            "&e=<#7#>".
            "'>".icon("ok","Angka Kredit")."</A>".
	    
	    
	    "</nobr>";
        $t->ColHeader = Array( "NIP","NAMA", "AGAMA","TANGGAL LAHIR",
                              "JENJANG JABATAN","GOL.","JENJANG PANGKAT","");
        $t->execute();



/*
    $t = new PgTable($con, "100%");
    $t->SQL =
        "select d.nama_jenjang_pangkat, c.prasyarat, b.tdesc,a.kredit, ".
                "(select standard_akkm ".
                    "from rs00038 x, rs00027 y ".
			        "where x.unsur_id = '".$_GET["mUNSUR"]."' and ".
                        "x.jjd_id = '".$_GET["mJENJANG"]."' and ".
                        "x.rs00027_id = a.rs00027_id and x.rs00027_id = y.id) as standard, ".
            "a.id_akkm as dummy ".
        "from rs00026 a, rs00001 b , rs00025 c, rs00027 d ".
        "where a.id_rincian ='".$_GET["mRINCIAN"]. "' and ".
            "a.id_rincian = c.id_rincian and ".
            "c.satuan_id =  b.tc and b.tt='SAT' and ".
            "a.jjd_id = '".$_GET["mJENJANG"]."' and a.rs00027_id = d.id";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->RowsPerPage = 14;
    //$t->ColAlign[0] = "CENTER";
    $t->ColAlign[5] = "CENTER";
    $t->ColFormatHtml[5] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#5#>".
                           "&u=".$_GET["mUNSUR"].
                           "&s=".$_GET["mSUBUNSUR"].
                           "&b=".$_GET["mBIDANG"].
                           "&r=".$_GET["mRINCIAN"].
                           "&v=".$_GET["mJENJANG"].
                           "'>".icon("edit","Edit")."</A>";
    $t->ColHeader = array("JENJANG PANGKAT", "PRASYARAT",
                          "SATUAN","BOBOT",$headerakkm, "E d i t");
    $t->execute();
*/

    
/*    
    echo "<BR><DIV ALIGN=RIGHT><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new".
                          "&u=".$_GET["mUNSUR"].
                          "&s=".$_GET["mSUBUNSUR"].
                          "&b=".$_GET["mBIDANG"].
                          "&r=".$_GET["mRINCIAN"].
                          "&v=".$_GET["mJENJANG"]."'>&#171; ".
                          "Tambah Data AKKM &#187;</A></DIV>";
*/
}

?>
