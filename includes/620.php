<?  // tokit, 2004-09-30

$PID = "620";
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
        $gol_ruang_id = (int) getFromTable("select gol_ruang_id from rs00017 where id = '".$_GET[e]."'");
        $rs00025_id = getFromTable("select rs00025_id from rs00030 where rs00017_id = '".$_GET[e]."'");
        $rs00027_id = getFromTable("select rs00027_id from rs00030 where rs00017_id = '".$_GET[e]."'");

        $rincian_id = getFromTable("select id from rs00025 where id = $rs00025_id");

        //$total_kredit = pg_query("select sum() from rs00030 where rs00017_id = '".$_GET[e]."'");
        title("Data Perolehan Angka Kredit: ".$nama);

        echo "<BR>";
        echo "<font class=BESAR>KEGIATAN</font>";
        $t = new PgTable($con, "100%");
        $t->SQL =
            "select a.tanggal, f.tdesc as unsur, b.nama_rincian_kegiatan, c.kredit ".
                //", a.id as dummy ".
	        "from rs00030 a ".
	            "left join rs00025 b ON b.id = a.rs00025_id ".
	            "left join rs00017 h on h.nip = '".$_GET[e]."' ".
	            "left join rs00027 g on g.jjd_id = h.jjd_id and g.gol_ruang_id = h.gol_ruang_id ".
                    "left join rs00026 c on c.id_rincian = lpad(a.rs00025_id,8,'0') and c.jjd_id = '$jjd_id' and c.rincian_kegiatan_id = '$gol_ruang_id'  ".
	            "left join rs00024 d on d.id_bidang = b.id_bidang ".
                    "left join rs00023 e on e.id_kegiatan = d.id_kegiatan ".
                    "left join rs00001 f on f.tc = e.unsur_id and f.tt = 'UKP' ".

		"where a.rs00017_id='".$_GET[e]."' ";

/*
                select * from rs00026
left join rs00027 a on rs00026.jjd_id = '003' and a.gol_ruang_id = '007' 
where id_rincian = lpad(61,8,'0') and (rs00026.jjd_id = '003' and a.gol_ruang_id = '007' )

*/
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

        /*
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

        $t->ColHeader = Array( "AK UTAMA","AK PENUNJANG");
        $t->execute();
        */
    }


} else {

    title("Data Perolehan Angka Kredit");
    $ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);


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
            "index2.php?p=$PID".
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
