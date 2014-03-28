<? 
$PID = "hrd_tambah_jadwal";
$SC = $_SERVER["SCRIPT_NAME"];

unset($_SESSION["IBU"]["id"]);
unset($_SESSION["IBU"]["nama"]);
		
require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
     
if (strlen($_GET["registered"]) == 0) $_GET["registered"] = "Y";
   title(" <img src='icon/tambah.png' align='absmiddle' > INPUT JADWAL KERJA ");

        echo "<table width='100%' cellspacing=0 cellpadding=2><td CLASS='PAGE_TITLE'></td>\n";
        echo "<td width=1 align=right><a href=\"index2.php?p=hrd_izin_sakit\"><img border=0 src=\"icon/edit_small.png\" title=\"edit\" ></a></td>\n";
        echo "&nbsp";
        echo "<td width=1 align=right><a href=\"index2.php?p=hrd_hapus_jadwal\"><img border=0 src=\"icon/hapus_small.png\" title=\"hapus\" ></a></td>\n";
        echo "</table>\n";


$f = new Form($SC, "GET", "NAME=Form2");
$f->hidden("p",$PID);
$f->hidden("q","search");

if ($_GET["registered"] == "Y" && $_GET["q"] != "reg") {
//$f->calendar("tgal","Tanggal",15,15,$_GET["tgl"],"Form2","icon/calendar.gif","Pilih Tanggal","");
$f->text("search","Nama / NIP",40,40,$_GET["search"]);
$f->submit("Tampilkan");
}
$f->execute();

$psn = "<font color='red'>{$_GET['psn']}</font>";
$psn2 = "<font color='red'>{$_GET['psn2']}</font>";

if ($_GET["q"] == "reg") {
        $r = @pg_query($con, "select * from rs00017 where id = '".$_GET["id"]."'");
        $n = @pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        @pg_free_result($r);
		
        $f = new Form("actions/hrd_tambah_jadwal.insert.php", "POST", "NAME=Form1");
        $f->hidden("p",$PID);
        $f->subtitle("Tambah Jadwal");
        $f->text("id_pegawai","ID",12,12,$_GET["id"],"Disabled");
        $f->hidden("f_id_pegawai",$_GET["id"]);
	$f->selectArray("f_tempat", "Tempat",Array("B" => "Bangsal", "I" => "IGD", "P" => "Poliklinik", "K" => "Kantor", "N" => "Non-Medis"), "K", "OnChange=\"settempat(this.value);\"");
	$f->PgConn = $con;
	$f->selectSQL2("f_tempat_bangsal", "Bangsal",
                       "select '' as id, '-' as bangsal union " .
                       "select hierarchy, bangsal ".
                       "from rs00012 ".
                       "where substr(hierarchy,4,12) = '000000000000' ".
                       "and is_group = 'Y' ".
                       "order by bangsal ","", "DISABLED",$psn2);
        $f->selectSQL2("f_tempat_poli", "Poliklinik/layanan","select '' as tc, '-' as tdesc union ".
                       "SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','100','201','202','206','207','208')
                	order by tdesc ","", "DISABLED",$psn);
	$f->calendar1("f_tanggal","Tanggal Jadwal",15,15,date("Y-m-d", time()),"Form1","icon/calendar.gif","Pilih Tanggal",$ext);
        $f->selectSQL("f_shift", "Shift","select code, shift from hrd_shift ","");
        $f->hidden("f_status","");
        $f->submit(" Tambah ");
	$f->execute();
        
        ?>
        <SCRIPT language="JavaScript">
            document.Form1.f_tempat_bangsal.selectedIndex = -1;
            document.Form1.f_tempat_poli.selectedIndex = -1;
            function settempat( v )
            {
                document.Form1.f_tempat_bangsal.disabled = v == "I" || v == "P" || v == "K" || v == "N";
                document.Form1.f_tempat_poli.disabled = v == "I" || v == "B" || v == "K" || v == "N";
                document.Form1.f_tempat_bangsal.selectedIndex = document.Form1.f_tempat_bangsal.selectedIndex == -1 && v == "B" ? 0 : v == "B" ? document.Form1.f_tempat_bangsal.selectedIndex : -1;
                document.Form1.f_tempat_poli.selectedIndex = document.Form1.f_tempat_poli.selectedIndex == -1 && v == "P" ? 0 : v == "P" ? document.Form1.f_tempat_poli.selectedIndex : -1;
            }
        </SCRIPT>		
        <?php
} else {

	if ($_GET["registered"] == "Y" && $_GET["q"] == "search" && strlen($_GET["search"]) > 0) {
    	$t = new PgTable($con, "100%");
        $t->SQL =
                "select a.nip,a.nama, e.tdesc as agama,to_char(tanggal_lahir,'DD MON YYYY') as lahir, ".
                "a.pangkat,a.jabatan,  ".
                "a.id as href ".
	        "from rs00017 a ".
	        "left outer join rs00027 d ON a.rs00027_id = d.id ".
	        "left outer join rs00001 e ON a.agama_id = e.tc and e.tt='AGM' ".
	        "left outer join rs00001 b ON a.jjd_id = b.tc and b.tt='JJD' ".
	        "left outer join rs00001 c ON a.gol_ruang_id = c.tc and c.tt='GRP' ".
                //"left outer join hrd_absen f ON a.id = f.id_pegawai  ".
                "where ".
                //" f.tanggal = ".$_GET["tgl"]." and ".
                "(upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%') and a.tgl_keluar is null ";
                	
		if (!isset($_GET[sort])) {
        	$_GET[sort] = "a.nip";
           	$_GET[order] = "asc";
		}
        $t->ColHeader = array("NRP/NIP","NAMA", "AGAMA","TANGGAL LAHIR","PANGKAT","JABATAN", "TAMBAH");
        $t->ShowRowNumber = true;
        $t->ColAlign[0] = "CENTER";
        $t->ColAlign[2] = "CENTER";
        $t->ColAlign[4] = "CENTER";
        $t->ColAlign[6] = "CENTER";
        $t->RowsPerPage = 10;
	
	$t->ColFormatHtml[6] = "<nobr>
	<A CLASS=TBL_HREF "."HREF='$SC?p=$PID&q=reg&id=<#6#>'>".icon("ok","Jadwal")."</A>
        </nobr>";
        $t->execute();
    }
}

//session_destroy();
//} // end of $_SESSION[uid] == daftar || igd
?>
