<?
$PID = "hrd_laporan_pegawai";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/class.BaseTable.php");
require_once("lib/functions.php");
    // search box
    //title("<img src='icon/informasi-2.gif' align='absmiddle' > LAPORAN PEGAWAI");
	//title_excel("pengunjung_rumah_sakit_1&mTRIWULAN=".$_GET["mTRIWULAN"]."&mTAHUN=".$_GET["mTAHUN"]."");
	title_excel("hrd_laporan_pegawai&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&search=".$_GET["search"]."");
    if (!$GLOBALS['print']){
		title_print("<img src='icon/informasi-2.gif' align='absmiddle' > LAPORAN REKAP DATA PEGAWAI");
		//echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
	}else{
		title_print("<img src='icon/informasi-2.gif' align='absmiddle' > LAPORAN REKAP DATA PEGAWAI ");
	}
    $ext = "OnChange = 'Form1.submit();'";
    //echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

    if (!isset($_GET['tanggal1D'])) {
	$tanggal1D = date("d", time());
	$tanggal1M = date("m", time());
	$tanggal1Y = date("Y", time());

    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,0,0,0));
    if (!$GLOBALS['print']){
	    $f->selectDate("tanggal1", "Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
    }else{
    	    $f->selectDate("tanggal1", "Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "disabled");
    }
} else {
    //$tgl_sakjane = $_GET[tanggal2D]; // + 1;
    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
    if (!$GLOBALS['print']){
	    $f->selectDate("tanggal1", "Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
    }else {
    	    $f->selectDate("tanggal1", "Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
    }
}
$f->text("search","Search Nama",50,50,$_GET["search"]);

    //$f->selectArray("rawat_inap", "U n i t",Array(""=>"", "N" => "IGD", "Y" => "Rawat Jalan",  "I" => "Rawat Inap"),
    //                 $_GET[rawat_inap], "");
    //$f->selectSQL("mPASIEN","Tipe Pasien","select '' as tc, '' as tdesc union select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000' ",$_GET[mPASIEN],"");
    $f->submit ("TAMPILKAN");
    $f->execute();
    
    echo "<BR>";
    $t = new PgTable($con, "100%");
        $t->SQL =
                "select a.nip,a.nama, e.tdesc as agama,to_char(tanggal_lahir,'DD MON YYYY') as lahir, ".
                "a.pangkat,a.jabatan  ".
                //"a.id as href ".
	        "from rs00017 a ".
	        "left outer join rs00027 d ON a.rs00027_id = d.id ".
	        "left outer join rs00001 e ON a.agama_id = e.tc and e.tt='AGM' ".
	        "left outer join rs00001 b ON a.jjd_id = b.tc and b.tt='JJD' ".
	        "left outer join rs00001 c ON a.gol_ruang_id = c.tc and c.tt='GRP' ".
                //"left outer join hrd_absen f ON a.id = f.id_pegawai  ".
                "where ".
                //" f.tanggal = ".$_GET["tgl"]." and ".
                "(upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%') ".
                "and (a.tgl_keluar is null or ('$ts_check_in1' between a.tgl_masuk and a.tgl_keluar))".
                "  group by a.nip,a.nama, e.tdesc , tanggal_lahir,a.pangkat,a.jabatan ";
		if (!isset($_GET[sort])) {
        	$_GET[sort] = "a.nama";
           	$_GET[order] = "asc";
		}
        $t->ColHeader = array("NRP/NIP","NAMA", "AGAMA","TANGGAL LAHIR","PANGKAT","JABATAN");
        $t->ShowRowNumber = true;
        $t->ColAlign[0] = "CENTER";
        $t->ColAlign[2] = "CENTER";
        $t->ColAlign[4] = "CENTER";
        $t->RowsPerPage = 10;

        $t->execute();


//} // --- end of ($_SESSION[uid] ----
?>
