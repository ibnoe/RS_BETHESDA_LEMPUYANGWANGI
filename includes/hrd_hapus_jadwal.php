<? // tokit, 2004 09 08

//if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "igd" || $_SESSION[uid] == "root"|| $_SESSION[uid] == "laborat" || $_SESSION[uid] == "radiologi") {

$PID = "hrd_hapus_jadwal";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (strlen($_GET["registered"]) == 0) $_GET["registered"] = "Y";
title("<img src='icon/hapus.png' align='absmiddle' > HAPUS JADWAL ABSEN");
        echo "<table width='100%' cellspacing=0 cellpadding=2><td CLASS='PAGE_TITLE'></td>\n";
        echo "<td width=1 align=right><a href=\"index2.php?p=hrd_tambah_jadwal\"><img border=0 src=\"icon/tambah_small.png\" title=\"tambah\" ></a></td>\n";
        echo "&nbsp";
        echo "<td width=1 align=right><a href=\"index2.php?p=hrd_izin_sakit\"><img border=0 src=\"icon/edit_small.png\" title=\"edit\" ></a></td>\n";
        echo "</table>\n";

$f = new Form($SC, "GET", "NAME=Form2");
$f->hidden("p",$PID);
$f->hidden("registered", "Y");
$f->hidden("q","search");
if (empty($_GET[e])) { 
$f->text("search","Pencarian",40,40,$_GET["search"]);
$f->submit(" Cari ");
}
$f->execute();

if ($_GET["e"]) {

    if (empty($_GET[sure])) {

    echo "<div align=center>";
    echo "<form action='index2.php' method='get'>";
    echo "<font color=red size=3>PERINGATAN !</font><br>";
    echo "<font class=SUB_MENU>Data Jadwal Absen <font color=navy>'".$_GET[uye]."'</font> pada tanggal <font color=navy>'".$_GET[uya]."'</font> akan Dihapus.</font><br><br>";
    echo "<input type=hidden name=p value=$PID>";
    echo "<input type=hidden name=e value=".$_GET[e].">";
    echo "<input type=submit name=sure value='::YA::'>";
    echo "<input type=submit name=sure value='::TIDAK::'>";
    echo "</form>";
    echo "</div>";
	
    } elseif ($_GET[sure] == "::YA::") {
    
    $id = $_GET[e];
    pg_query("delete from hrd_absen where id = '$id'");
    
    echo "<script language=javascript>\n";
    echo "<!--\n";
    echo "window.location=\"index2.php?p=$PID&registered=Y&q=search&search=00\";\n";
    echo "-->\n";
    echo "</script>\n";
    
    } else {

    echo "<script language=javascript>\n";
    echo "<!--\n";
    echo "window.location=\"index2.php?p=$PID&registered=Y&q=search&search=00\";\n";
    echo "-->\n";
    echo "</script>\n";    
    
    }

} else {
    
    if ($_GET["registered"] == "Y" && $_GET["q"] == "search" && strlen($_GET["search"]) > 0) {
$tglhariini = date("Y-m-d", time());
         $t = new PgTable($con, "100%");
     $t->SQL =
            "select a.nip,a.nama, e.shift, ".
                "case when b.tempat_bangsal = '' and b.tempat_poli != '' then c.tdesc ".
		"     when b.tempat_bangsal != '' and b.tempat_poli = '' then d.bangsal ".
                "     when b.tempat = 'I' then 'IGD' ".
                "     when b.tempat = 'K' then 'Kantor' ".
                "else 'Non-Medis' end , to_char(b.tanggal,'DD-MM-YYYY') as tgl,   ".
                "e.jm_mulai, e.jm_selesai,  ".
                "b.id as href ".
	        "from rs00017 a , hrd_absen b ".
	        "left outer join hrd_shift e ON b.shift = e.code ".
	        "left outer join rs00012 d ON b.tempat_bangsal = d.hierarchy ".
	        "left outer join rs00001 c ON b.tempat_poli = c.tc and c.tt='LYN' ".
                //"where a.jabatan_medis_fungsional_id='".$_GET["mJAB"]."' ".
                "where (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%') ".
                "and  a.id = b.id_pegawai and   b.tanggal > '$tglhariini'  ";

		if (!isset($_GET[sort])) {
        	$_GET[sort] = "a.nip";
           	$_GET[order] = "asc";
		}
        $t->ColHeader = array("NRP/NIP","NAMA", "SHIFT","TEMPAT","TANGGAL","MULAI","SELESAI", "HAPUS");
        $t->ShowRowNumber = true;
        $t->ColAlign[0] = "CENTER";
        $t->ColAlign[2] = "CENTER";
        $t->ColAlign[4] = "CENTER";
        $t->ColAlign[7] = "CENTER";
        $t->RowsPerPage = 10;
	//$t->RowsPerPage = $ROWS_PER_PAGE;
        //$t->DisableStatusBar = true;
        // sfdn, 27-12-2006 -> hanya pembetulan baris
	$t->ColFormatHtml[7] = "<A CLASS=TBL_HREF "."HREF='$SC?p=$PID&e=<#7#>&uye=<#1#>&uya=<#4#>'>".icon("delete","Hapus")."</A>";
	/*$SQLSTR = "select a.mr_no, b.id, upper(a.nama)as nama,a.jenis_kelamin,a.umur,a.kesatuan, c.tdesc as poli, ".
			  "d.tdesc, b.id as href FROM  rs00006 b ".
              "left join rs00002 a on a.mr_no = b.mr_no  ".
			  "left join rs00001 c on b.poli = c.tc_poli ".
			  "left join rs00001 d on b.tipe = d.tc AND d.tt = 'JEP' ".
              "where upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' ".
              "OR b.id LIKE '%".$_GET["search"]."%' ".

              "OR a.mr_no LIKE '%".$_GET["search"]."%' ".
              "OR upper(a.alm_tetap) like '%".strtoupper($_GET["search"])."%' ".
              "OR upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' ".
              "OR upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%' " ;   
             
	// tambah urutan
    if (!isset($_GET[sort])) {
           $_GET[sort] = "mr_no";
           $_GET[order] = "asc";
	}   
	
	if ($_SESSION[uid] == "igd") {
	    $SQLWHERE = "AND (c.tc_poli = 100 OR c.tc_poli=0)";		//$SQLWHERE = "AND (b.poli = 10 OR b.poli=0)";
	//} elseif ($_SESSION[uid] == "daftar") {
	//    $SQLWHERE = "AND c.tc<>100 AND c.tc<>0";		//$SQLWHERE = "AND b.poli<>10 AND b.poli<>0";
	} elseif ($_SESSION[uid] == "laborat") {
		$SQLWHERE = "AND c.tc=203";
	} elseif ($_SESSION[uid] == "radiologi") {
		$SQLWHERE = "AND c.tc=204";
	}

 // echo $SQLSTR.$SQLWHERE ; 

        $t = new PgTable($con, "100%");
        $t->SQL = "$SQLSTR $SQLWHERE";
        
        $t->ColHeader = array("NO.MR","NO.REG","NAMA","JNS KELAMIN","UMUR (Tahun)","PEKERJAAN","RAWATAN","TIPE PASIEN","HAPUS");
        $t->ShowRowNumber = true;
        $t->ColAlign[0] = "CENTER";
		$t->ColAlign[1] = "CENTER";
                $t->ColAlign[3] = "CENTER";
                $t->ColAlign[4] = "CENTER";
		$t->ColAlign[8] = "CENTER";	
        $t->RowsPerPage = 12;
        //$t->DisableStatusBar = true;
        $t->ColFormatHtml[8] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#8#>'>".icon("delete","Hapus")."</A>";*/
        $t->execute();
    }
}


//} // end of $_SESSION[uid] == daftar || igd
?>
