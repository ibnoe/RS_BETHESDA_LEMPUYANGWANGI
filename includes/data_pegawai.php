<?

$PID = "data_pegawai";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("lib/setting.php");
require_once("startup.php");

//echo "<br>";
$is_selected = getFromTable(
    "select count(id) ".
    "from rs00018 ".
    "where unit_medis_fungsional_id = '" . $_GET["mPEG"] . "' ".
    "    and id = '" . $_GET["mJAB"] . "'") > 0;

title("<img src='icon/informasi-2.gif' align='absmiddle' > INFORMASI DOKTER / PEGAWAI");
title_excel("p=data_pegawai");
title_print("");


if(isset($_GET["e"])) {


    //echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";

	switch ($_GET["z"]) {
		case "satu":

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
        $f->text("f_nip","N I P",30,30,$_GET["f_nip"]);
        $f->text("f_pangkat","Pangkat",50,50,$_GET["f_pangkat"]);
        $f->text("f_jabatan","Jabatan",50,50,$_GET["f_jabatan"]);
		$f->selectArray("f_jns_kelamin", "Jenis Kelamin",Array("L" => "Laki-laki", "P" => "Perempuan"),$d2->jns_kelamin);
		$f->text("f_tempat_lahir","Tempat Lahir",20,20,$d2->tempat_lahir);
        $f->selectDate("f_tanggal_lahir", "Tanggal Lahir", getDate(mktime(0,0,0,(int)date("m"),(int)date("d"),((int)date("Y"))-40)));
        
		$f->textarea("f_alamat","Alamat",4,40,$d2->alamat);
		
		$f->selectSQL("f_jjd_id", "Jenjang Jabatan",
                  "select '-' as tc,'-' as tdesc union select tc, tdesc from rs00001 where tt = 'JJD' and tc != '000'",
                  $d2->jjd_id);

        $f->selectSQL("f_gol_ruang_id", "Gol.Ruang",
                  "select '-' as tc,'-' as tdesc union ".
                  "select tc, tdesc from rs00001 ".
                  "where tt = 'GRP' and tc != '000' ".
                  "order by tdesc",
                  $d2->gol_ruang_id);
		
		
		
        $f->selectSQL("f_agama_id", "Agama",
        	   "select '-' as tc,'-' as tdesc union ".
                  "select tc, tdesc from rs00001 where tt = 'AGM' and tc != '000'",
                  $d->agama_id);
        $f->text("f_phone","Telephone",20,20,$_GET["f_phone"]);
        //$f->selectDate("f_tgl_masuk", "Tanggal Pengangkatan", getDate(mktime(0,0,0,(int)date("m"),(int)date("d"),((int)date("Y")))));
        $f->calendar1("f_tgl_masuk","Tanggal Pengangkatan",15,15,date("Y-m-d", time()),"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
        $f->hidden("f_status", 'peg');
        $f->submit(" Simpan ", "onClick='Form2.method=\"POST\";Form2.action=\"actions/809.insert.php\";'");
        $f->execute();
		    break;
		case "dua":

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
        $f->text("f_nip","NRP/NIP",30,30,$d2->nip);
        $f->text("f_pangkat","Pangkat",50,50,$d2->pangkat);
        $f->text("f_jabatan","Jabatan",50,50,$d2->jabatan);
        $f->selectSQL("", "Unit Medis",
                  "select tc, tdesc ".
                  "from rs00001 ".
                  "where tt='PEG' and tc='".$_GET[mPEG]."'",
                  $d3->tc);

        $f->selectSQL("f_jabatan_medis_fungsional_id", "Pendidikan",
                  "select '-' as tc,'-' as tdesc union ".
                  "select id, jabatan_medis_fungsional ".
                  "from rs00018 where unit_medis_fungsional_id = '" . $_GET["mPEG"] . "'",
                  $d2->jabatan_medis_fungsional_id);

        $f->selectSQL("f_jjd_id", "Jenjang Jabatan",
        	  "select '-' as tc,'-' as tdesc union ".
                  "select tc, tdesc from rs00001 where tt = 'JJD' and tc != '000'",
                  $d2->jjd_id);

        $f->selectSQL("f_gol_ruang_id", "Gol.Ruang",
                  "select '-' as tc,'-' as tdesc union ".
                  "select tc, tdesc from rs00001 ".
                  "where tt = 'GRP' and tc != '000' ".
                  "order by tdesc",
                  $d2->gol_ruang_id);
/*
        $f->selectSQL("f_rs00027_id", "Jenjang Jabatan dan Gol.",
                  "select '' as id, '' as jab union ".
                  "select to_char(a.id,'999999999'), rpad(ltrim(b.tdesc),20,' ') || '      ' || c.tdesc as jab ".
                  "from rs00027 a, rs00001 b, rs00001 c ".
	              "where (a.jjd_id = b.tc and b.tt='JJD') and ".
                    "(a.gol_ruang_id = c.tc and c.tt='GRP')",
                    $d2->rs00027_id);
*/
	//$f->selectDate("f_tanggal_lahir", "Tanggal Lahir", getDate(mktime(0,0,0,(int)date("m"),(int)date("d"),((int)date("Y"))-30)));
		$f->selectArray("f_jns_kelamin", "Jenis Kelamin",Array("L" => "Laki-laki", "P" => "Perempuan"),$d2->jns_kelamin);
		$f->text("f_tempat_lahir","Tempat Lahir",20,20,$d2->tempat_lahir);
		$f->selectDate("f_tanggal_lahir", "Tanggal Lahir", pgsql2phpDate($d2->tanggal_lahir));
		$f->textarea("f_alamat","Alamat",4,40,$d2->alamat);
        $f->selectSQL("f_agama_id", "Agama",
        "select '-' as tc,'-' as tdesc union ".
                  "select tc, tdesc from rs00001 where tt = 'AGM' and tc != '000'",
                  $d2->agama_id);
		
        $f->text("f_phone","Telephone",20,20,$d2->phone);
        //$f->selectDate("f_tgl_masuk", "Tanggal Pengangkatan", pgsql2phpDate($d2->tgl_masuk));
        $f->calendar1("f_tgl_masuk","Tanggal Pengangkatan",15,15,$d2->tgl_masuk,"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
        $f->hidden("f_status", 'peg');
        $f->submit(" Simpan ", "onClick='Form2.method=\"POST\";Form2.action=\"actions/809.update.php\";'");
        $f->execute();
		    break;
		case "tiga":
                        $data = getFromTable("select nama from rs00017 where id='".$_GET[e]."'");
                        echo "<div align=center>";
                        echo "<form action='actions/809.keluar.php' method='get'>";
                        echo "<font color=red size=3>PERINGATAN !</font><br>";
                        echo "<font class=SUB_MENU>Pegawai dengan nama <font color=navy>'".$data."'</font> akan keluar.</font><br><br>";
                        echo "<input type=hidden name=p value=$PID>";
                        echo "<input type=hidden name=e value=".$_GET[e].">";
                        echo "<input type=hidden name=mPEG value=".$_GET[mPEG].">";
                        echo "<input type=hidden name=z value=".$_GET[z].">";
                        echo "<input type=hidden name=mJAB value=".$_GET[mJAB].">";
                        echo "<input type=submit name=sure value='::YA::'>&nbsp;";
                        echo "<input type=submit name=sure value='::TIDAK::'>";
                        echo "</form>";
                        echo "</div>";
		    break;
		case "empat":
			$data = getFromTable("select nama from rs00017 where id='".$_GET[e]."'");
                        echo "<div align=center>";
                        echo "<form action='actions/809.delete.php' method='get'>";
                        echo "<font color=red size=3>PERINGATAN !</font><br>";
                        echo "<font class=SUB_MENU>Master Pegawai <font color=navy>'".$data."'</font> akan Dihapus.</font><br><br>";
                        echo "<input type=hidden name=p value=$PID>";
                        echo "<input type=hidden name=e value=".$_GET[e].">";
                        echo "<input type=hidden name=mPEG value=".$_GET[mPEG].">";
                        echo "<input type=hidden name=z value=".$_GET[z].">";
                        echo "<input type=hidden name=mJAB value=".$_GET[mJAB].">";
                        echo "<input type=submit name=sure value='::YA::'>&nbsp;";
                        echo "<input type=submit name=sure value='::TIDAK::'>";
                        echo "</form>";
                        echo "</div>";
		    break;
	}

} else {
         if (isset($_GET["e"])) {
            $ext = "DISABLED";
        } else {
            $ext = "OnChange = 'Form1.submit();'";
        }
        echo "<br>";
        if (!$GLOBALS['print']){
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
        $f->selectSQL("mJAB", "Pendidikan",
            "select '' as id, '' as jabatan_medis_fungsional union " .
            "select id, jabatan_medis_fungsional ".
            "from rs00018 ".
            "where unit_medis_fungsional_id = '" . $_GET["mPEG"] . "' ".
            "order by jabatan_medis_fungsional", $_GET["mJAB"],
            $ext);
		
        $f->execute();
		}else{
		$f = new Form($SC, "GET", "NAME=Form1");
        $f->PgConn = $con;
        $f->hidden("p", $PID);
        $f->selectSQL("mPEG", "Unit Medis",
            "select '' as tc, '' as tdesc union " .
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'PEG' and tc!='000' ".
            "order by tdesc", $_GET["mPEG"],
            "disabled");
        $f->selectSQL("mJAB", "Pendidikan",
            "select '' as id, '' as jabatan_medis_fungsional union " .
            "select id, jabatan_medis_fungsional ".
            "from rs00018 ".
            "where unit_medis_fungsional_id = '" . $_GET["mPEG"] . "' ".
            "order by jabatan_medis_fungsional", $_GET["mJAB"],
            "disabled");
		
		$f->execute();
		}
		$bln=date('M Y');
		echo "<table width='100%'><tr><td align='right'>";
		$f = new Form($SC, "GET","NAME=Form4");
	    $f->hidden("p", $PID);
		$f->hidden("mPEG", $_GET["mPEG"]);
		$f->hidden("mJAB", $_GET["mJAB"]);
	    if (!$GLOBALS['print']){
	    	$f->search("search","Pencarian Nama atau Alamat",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form4.submit();'");
		}
	    $f->execute();
		echo "</td></tr></table> <br><br>";
    if ($is_selected) {
		if (!$GLOBALS['print']){
		echo "<DIV ALIGN=CENTER><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2 ><TR>";
        echo "<TD ALIGN=CENTER CLASS=TBL_JUDUL>INFORMASI DOKTER / PEGAWAI<BR>".$set_header[0]."</TD>";
        echo "</TR>";
        echo "<TR><TD ALIGN=CENTER>".$set_header[2]."</TD></TR>";
        echo "<TR><TD ALIGN=CENTER >".$set_header[3]."</TD></TR>";
        echo "<TR><TD ALIGN=CENTER CLASS=TBL_JUDUL> </TD>";
        echo "</TR><tr>";
		
		echo "</tr></FORM></TABLE></DIV>";
		}
        $t = new PgTable($con, "100%");
        $t->SQL =
            "select a.nama, a.alamat, a.phone
				from rs00017 a 
	            left outer join rs00027 d ON a.rs00027_id = d.id 
	            left outer join rs00018 f ON f.id = a.jabatan_medis_fungsional_id 
	            left outer join rs00001 e ON e.tc = a.agama_id  and e.tt='AGM' 
	            left outer join rs00001 b ON b.tc = a.jjd_id   and b.tt='JJD' 
	            left outer join rs00001 c ON a.gol_ruang_id = c.tc and c.tt='GRP' 
				where a.jabatan_medis_fungsional_id = '".$_GET["mJAB"]."' and (upper(a.nama) like '%".strtoupper($_GET["search"])."%' or upper(a.alamat) like '%".strtoupper($_GET["search"])."%') ".
                "and (a.status = 'peg' or a.status is null)  
				group by a.nama, a.alamat, a.phone	";

        $t->setlocale("id_ID");
        //$t->ColAlign[0] = "CENTER";

        $t->ShowRowNumber = true;
        $t->RowsPerPage = 500;
        $t->ColHeader = Array( "NAMA", "ALAMAT","TELEPON");
        $t->execute();

    }
}
?>
