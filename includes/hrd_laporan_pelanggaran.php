<?
$PID = "hrd_laporan_pelanggaran";
$SC = $_SERVER["SCRIPT_NAME"];
$ROWS_PER_PAGE = 20;

require_once("startup.php");
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/class.BaseTable.php");
require_once("lib/functions.php");
    // search box
    //title("<img src='icon/informasi-2.gif' align='absmiddle' > LAPORAN PELANGGARAN PEGAWAI");
	title_excel("hrd_laporan_pelanggaran&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal12M"]."&tanggal2Y=".$_GET["tanggal2Y"]."");
    if (!$GLOBALS['print']){
		title_print("<img src='icon/informasi-2.gif' align='absmiddle' > LAPORAN PELANGGARAN PEGAWAI");
		//echo "<DIV ALIGN=RIGHT OnClick='window.history.back()'>".icon("back","Kembali")."</a></DIV>";
	}else{
		title_print("<img src='icon/informasi-2.gif' align='absmiddle' > LAPORAN PELANGGARAN PEGAWAI ");
	}
    $ext = "OnChange = 'Form1.submit();'";
    //echo "<br>";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

	include(xxx2);

    //$f->selectArray("rawat_inap", "U n i t",Array(""=>"", "N" => "IGD", "Y" => "Rawat Jalan",  "I" => "Rawat Inap"),
    //                 $_GET[rawat_inap], "");
    //$f->selectSQL("mPASIEN","Tipe Pasien","select '' as tc, '' as tdesc union select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000' ",$_GET[mPASIEN],"");
    $f->submit ("TAMPILKAN");
    $f->execute();

    echo "<BR>";

    $t = new PgTable($con, "100%");
    $t->SQL =
            "select a.nip,a.nama, e.shift, ".
                "case when b.tempat_bangsal = '' and b.tempat_poli != '' then c.tdesc ".
		"     when b.tempat_bangsal != '' and b.tempat_poli = '' then d.bangsal ".
                "     when b.tempat = 'I' then 'IGD' ".
                "     when b.tempat = 'K' then 'Kantor' ".
                "else 'Non-Medis' end ,  ".
                "to_char(b.tanggal,'DD-MM-YYYY') as tgl, e.jm_mulai, e.jm_selesai, ".
	        "case when b.status = '' then g.status ".
                "else f.status end , ".
                "case when b.status = '002' then TO_CHAR(b.waktu_absen-e.jm_mulai,'HH24:MI:SS')  ".
                "else '' end ".
                "from rs00017 as a , hrd_absen as b ".
	        "left outer join hrd_status f ON b.status = f.code ".
	        "left outer join hrd_shift e ON b.shift = e.code ".
	        "left outer join rs00012 d ON b.tempat_bangsal = d.hierarchy ".
	        "left outer join rs00001 c ON b.tempat_poli = c.tc and c.tt='LYN' ".
                "left outer join hrd_status g ON g.code = '003' ".
                "where  ".
                "  a.id = b.id_pegawai and b.tanggal between '$ts_check_in1' and '$ts_check_in2' ".
                "  and (b.status = '002' or b.status = '003' or b.status = '') ".
                " group by a.nip,a.nama, e.shift, b.tempat_bangsal, b.tempat_poli,c.tdesc, b.tempat, b.tanggal, e.jm_mulai, e.jm_selesai, b.status, g.status, f.status,b.waktu_absen,d.bangsal";

    if (!isset($_GET[sort])) {
	$_GET[sort] = "a.nama";
	$_GET[order] = "asc";
    }
    
    //$t->setlocale("id_ID");
    $t->ColHeader = array("NRP/NIP","NAMA", "SHIFT","TEMPAT","TANGGAL","MULAI","SELESAI", "KET", "TELAT" );
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "CENTER";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    //$t->ColFormatHtml[2] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&v=<#2#>&t1=$ts_check_in1&t2=$ts_check_in2'><#2#></A>";
    $t->execute();


//} // --- end of ($_SESSION[uid] ----
?>
