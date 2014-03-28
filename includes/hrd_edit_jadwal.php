<? // tokit, 2004 09 08

//if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "igd" || $_SESSION[uid] == "root" || $_SESSION[uid] == "laborat" || $_SESSION[uid] == "radiologi") {

$PID = "hrd_edit_jadwal";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (strlen($_GET["registered"]) == 0) $_GET["registered"] = "Y";
title("<img src='icon/daftar-2.gif' align='absmiddle' > EDIT JADWAL ABSENSI");
        echo "<table width='100%' cellspacing=0 cellpadding=2><td CLASS='PAGE_TITLE'></td>\n";
        echo "<td width=1 align=right><a href=\"index2.php?p=hrd_tambah_jadwal\"><img border=0 src=\"icon/log_message.png\" title=\"tambah\" ></a></td>\n";
        echo "&nbsp";
        echo "<td width=1 align=right><a href=\"index2.php?p=hrd_hapus_jadwal\"><img border=0 src=\"icon/log_message.png\" title=\"hapus\" ></a></td>\n";
        echo "</table>\n";
echo "<br>";

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
        // data reg
        $r = pg_query($con, "select * from rs00006 where id = '".$_GET["e"]."'");
        $d = pg_fetch_object($r);
        pg_free_result($r);

        // data mr
        $rr = pg_query($con, "select * from rs00002 where mr_no = '$d->mr_no'");
        $dd = pg_fetch_object($rr);
        pg_free_result($rr);
        
        echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID&registered=Y&q=search&search=a'>".icon("back","Kembali")."</a></DIV>";

        $f = new Form("actions/140.update.php", "POST", "NAME=Form1");
        $f->hidden("p",$PID);
        $f->hidden("mr_no",$d->mr_no);
        $f->hidden("xpoli",$d->poli);

        $f->subtitle("Registrasi");
        $f->text("no_reg","No. Registrasi",12,12, $d->id,"readonly");
        $f->selectDate("tgl_reg", "Tgl Register", pgsql2phpDate($d->tanggal_reg),"DISABLED");
        $f->text("nama","Nama",40,50, $dd->nama,"readonly");
        $f->text("alamat","Alamat",40,50, "$dd->alm_tetap $dd->kota_tetap","readonly");

        if ($_SESSION[uid] == "igd") {

        $f->selectArray("f_rawat_inap", "Rawatan",Array("N" => "IGD"),"N", "OnChange=\"setPoli(this.value);\"");
        $f->PgConn = $con;
		$f->selectSQL("f_poli", "Poli","select '' as tc, '' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc NOT IN 
						 ('000','100','201','202','206','207','208') order by tdesc ","", "DISABLED");

        } elseif ($_SESSION[gr] == "daftar" || $_SESSION[gr] == "DAFTARRI") {

        $f->selectArray("f_rawat_inap", "Rawatan",Array("Y" => "Rawat Jalan"),"Y", "OnChange=\"setPoli(this.value);\"");
        $f->PgConn = $con;
		$f->selectSQL("f_poli", "Poli","select '' as tc, '' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc NOT IN 
						 ('000','100','201','202','206','207','208') order by tdesc ","", "");
        } else {

        $f->selectArray("f_rawat_inap", "Rawatan",Array("Y" => "Rawat Jalan", "N" => "IGD"),
                        "N", "OnChange=\"setPoli(this.value);\"");
        $f->PgConn = $con;
		$f->selectSQL("f_poli", "Poli","select '' as tc, '' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc NOT IN 
						 ('000','100','201','202','206','207','208') order by tdesc ","", "DISABLED");
        }

		$f->selectArray("f_rujukan", "Jenis Kedatangan",Array("N" => "Non Rujukan", "Y" => "Rujukan"),
                        $d->rujukan, "OnChange=\"setRujukan(this.value);\"");
        $f->selectSQL("f_rujukan_rs_id", "Rumah Sakit Perujuk","select '' as tc, '' as tdesc union ".
                      "select tc, tdesc from rs00001 where tt = 'RUJ' and tc != '000'","", "DISABLED");
        $f->text("f_rujukan_dokter","Dokter Perujuk",50,50,"","DISABLED");
        $f->selectSQL("f_id_penanggung", "Penanggung","select tc, tdesc from rs00001 where tt = 'PEN' and tc != '000'",
                      $d->id_penanggung);
        $f->selectSQL("f_id_penjamin", "Penjamin","select tc, tdesc from rs00001 where tt = 'PJN' and tc != '000'",
                      $d->id_penjamin);
        $f->selectSQL("f_tipe", "Tipe Pasien","select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000'",
                      $d->tipe);
        $f->textarea("f_diagnosa_sementara", "Diagnosa Sementara", 4, 50, $d->diagnosa_sementara);

        $f->submit(" SIMPAN ");
       
	 $f->execute();
        
        if ($_GET[err] == 1) {
           echo "<br><font color=red>ERROR: Poli belum dipilih.</font>";
        }
        ?>

        <SCRIPT language="JavaScript">
            document.Form1.f_rujukan_rs_id.selectedIndex = -1;

            function setRujukan( v )
            {
                document.Form1.f_rujukan_rs_id.disabled = v == "N";
                document.Form1.f_rujukan_dokter.disabled = v == "N";
                document.Form1.f_rujukan_dokter.value = v == "N" ? "" : document.Form1.f_rujukan_dokter.value;
                document.Form1.f_rujukan_rs_id.selectedIndex = document.Form1.f_rujukan_rs_id.selectedIndex == -1 && v == "Y" ? 0 : v == "Y" ? document.Form1.f_rujukan_rs_id.selectedIndex : -1;
            }
        </SCRIPT>
        <SCRIPT language="JavaScript">
            document.Form1.f_poli.selectedIndex = -1;
            function setPoli( v )
            {
                document.Form1.f_poli.disabled = v == "N";
                document.Form1.f_poli.selectedIndex = document.Form1.f_poli.selectedIndex == -1 && v == "Y" ? 0 : v == "Y" ? document.Form1.f_poli.selectedIndex : -1;
            }
        </SCRIPT>
		
        <?php
} else {
    
    if ($_GET["registered"] == "Y" && $_GET["q"] == "search" && strlen($_GET["search"]) > 0) {
        $t = new PgTable($con, "100%");
     $t->SQL =
            "select a.nip,a.nama, e.shift, ".
                "case when b.tempat_bangsal = '' and b.tempat_poli != '' then c.tdesc ".
		"     when b.tempat_bangsal != '' and b.tempat_poli = '' then d.bangsal ".
                "     when b.tempat = 'I' then 'IGD' ".
                "     when b.tempat = 'K' then 'Kantor' ".
                "else 'Non-Medis' end ,  ".
                "e.jm_mulai,e.jm_selesai,  ".
                "a.id as href ".
	        "from rs00017 a , hrd_absen b ".
	        //"left outer join hrd_absen d ON d.tanggal = '$tglhariini' ".
	        "left outer join hrd_shift e ON b.shift = e.code ".
	        "left outer join rs00012 d ON b.tempat_bangsal = d.hierarchy ".
	        "left outer join rs00001 c ON b.tempat_poli = c.tc and c.tt='LYN' ".
                //"where a.jabatan_medis_fungsional_id='".$_GET["mJAB"]."' ".
                "where (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%') ".
                "and  a.id = b.id_pegawai     ";

		if (!isset($_GET[sort])) {
        	$_GET[sort] = "a.nip";
           	$_GET[order] = "asc";
		}
        $t->ColHeader = array("NRP/NIP","NAMA", "SHIFT","TEMPAT","MULAI","SELESAI", "EDIT");
        $t->ShowRowNumber = true;
        $t->ColAlign[0] = "CENTER";
        $t->ColAlign[2] = "CENTER";
        $t->ColAlign[4] = "CENTER";
        $t->ColAlign[6] = "CENTER";
        $t->RowsPerPage = 10;
	//$t->RowsPerPage = $ROWS_PER_PAGE;
        //$t->DisableStatusBar = true;
        // sfdn, 27-12-2006 -> hanya pembetulan baris
	$t->ColFormatHtml[6] = "<A CLASS=TBL_HREF "."HREF='$SC?p=$PID&e=<#6#>'>".icon("edit","Edit")."</A>";
        /*
	$SQLSTR = "select a.mr_no, b.id, upper(a.nama)as nama,a.jenis_kelamin,a.umur,a.kesatuan, c.tdesc as poli, ".
			 "d.tdesc, b.id as href FROM  rs00006 b ".
              "left join rs00002 a on a.mr_no = b.mr_no  ".
			  "left join rs00001 c on b.poli = c.tc_poli ".
			  "left join rs00001 d on b.tipe = d.tc AND d.tt = 'JEP' ".
              "where upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' ".
              "OR a.mr_no LIKE '%".$_GET["search"]."%' ".
              "OR upper(a.alm_tetap) like '%".strtoupper($_GET["search"])."%' ".
              "OR upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' ".
              "OR upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%'" ;
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
    
    $t = new PgTable($con, "100%");
    $t->SQL = "$SQLSTR $SQLWHERE";
    $t->ColHeader = array("NO.MR","NO.REG","NAMA","SEKS","UMUR (Tahun)","PEKERJAAN","RAWATAN","TIPE PASIEN","EDIT");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
	$t->ColAlign[1] = "CENTER";
        $t->ColAlign[3] = "CENTER";
        $t->ColAlign[4] = "CENTER";
	$t->ColAlign[7] = "CENTER";
	$t->ColAlign[8] = "CENTER";
	$t->RowsPerPage = 12;
    //$t->DisableStatusBar = true;
    $t->ColFormatHtml[8] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#8#>'>".icon("edit","Edit")."</A>";*/
    $t->execute();
    }
}


//} // end of $_SESSION[uid] == daftar || igd
?>
