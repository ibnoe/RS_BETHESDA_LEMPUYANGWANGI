<?php

$PID = "809";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

//echo "<br>";
$is_selected = getFromTable(
    "select count(id) ".
    "from rs00018 ".
    "where unit_medis_fungsional_id = '" . $_GET["mPEG"] . "' ".
    "    and id = '" . $_GET["mJAB"] . "'") > 0;

title("<img src='icon/informasi-2.gif' align='absmiddle' > Master Pegawai");
title_excel("p=809");
if(isset($_GET["e"])) {


    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";


  /*  $r = pg_query($con, "select * from rs99995 where id = '".$_GET["e"]."'");
    $n = pg_num_rows($r);

    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);

 	$x_passwd ="" ;*/
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
		$f->calendar1("f_tmt_pangkat","TMT Pangkat",15,15,date("Y-m-d", time()),"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
        $f->text("f_jabatan","Jabatan",50,50,$_GET["f_jabatan"]);
		$f->calendar1("f_tmt_jabatan","TMT Pangkat",15,15,date("Y-m-d", time()),"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
		$f->selectArray("f_jns_kelamin", "Jenis Kelamin",Array("L" => "Laki-laki", "P" => "Perempuan"),$d2->jns_kelamin);
		$f->text("f_tempat_lahir","Tempat Lahir",20,20,$d2->tempat_lahir);
        $f->selectDate("f_tanggal_lahir", "Tanggal Lahir", getDate(mktime(0,0,0,(int)date("m"),(int)date("d"),((int)date("Y"))-40)));
        
		$f->textarea("f_alamat","Alamat",4,40,$d2->alamat);
		
		$f->selectSQL("f_jjd_id", "Jenjang Jabatan",
                  "select '-' as tc,'-' as tdesc union select tc, tdesc from rs00001 where tt = 'JJD' and tc != '000'",
                  $d2->jjd_id);

        $f->selectSQL("f_gol_ruang_id", "Golongan",
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
        $f->calendar1("f_tmt_cpns","TMT CPNS",15,15,date("Y-m-d", time()),"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
		$f->calendar1("f_tgl_masuk","Tanggal Pengangkatan",15,15,date("Y-m-d", time()),"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
        $f->text("f_jasa_medis","Jasa Medis",20,20,0,$d2->jasa_medis);//jm dikasih default 0 by Me 30Jan2013 
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
		$f->calendar1("f_tmt_pangkat","TMT Pangkat",15,15,$d2->tmt_pangkat,"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
        $f->text("f_jabatan","Jabatan",50,50,$d2->jabatan);
		$f->calendar1("f_tmt_jabatan","TMT Jabatan",15,15,$d2->tmt_jabatan,"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
        $f->selectSQL("", "Unit Medis",
                  "select tc, tdesc ".
                  "from rs00001 ".
                  "where tt='PEG' and tc='".$_GET[mPEG]."'",
                  $d3->tc);

        $f->selectSQL("f_jabatan_medis_fungsional_id", "Ruangan/Posisi Jabatan",
                  "select '-' as tc,'-' as tdesc union ".
                  "select id, jabatan_medis_fungsional ".
                  "from rs00018 where unit_medis_fungsional_id = '" . $_GET["mPEG"] . "'",
                  $d2->jabatan_medis_fungsional_id);

        $f->selectSQL("f_jjd_id", "Jenjang Jabatan",
        	  "select '-' as tc,'-' as tdesc union ".
                  "select tc, tdesc from rs00001 where tt = 'JJD' and tc != '000'",
                  $d2->jjd_id);

        $f->selectSQL("f_gol_ruang_id", "Golongan",
                  "select '-' as tc,'-' as tdesc union ".
                  "select tc, tdesc from rs00001 ".
                  "where tt = 'GRP' and tc != '000' ".
                  "order by tdesc",
                  $d2->gol_ruang_id);
		$f->selectArray("f_jns_kelamin", "Jenis Kelamin",Array("L" => "Laki-laki", "P" => "Perempuan"),$d2->jns_kelamin);
		$f->text("f_tempat_lahir","Tempat Lahir",20,20,$d2->tempat_lahir);
		$f->selectDate("f_tanggal_lahir", "Tanggal Lahir", pgsql2phpDate($d2->tanggal_lahir));
		$f->textarea("f_alamat","Alamat",4,40,$d2->alamat);
        $f->selectSQL("f_agama_id", "Agama",
        "select '-' as tc,'-' as tdesc union ".
                  "select tc, tdesc from rs00001 where tt = 'AGM' and tc != '000'",
                  $d2->agama_id);
		
        $f->text("f_phone","Telephone",20,20,$d2->phone);
		$f->calendar1("f_tmt_cpns","TMT CPNS",15,15,$d2->tmt_cpns,"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
        $f->calendar1("f_tgl_masuk","Tanggal Pengangkatan",15,15,$d2->tgl_masuk,"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
        $f->text("f_jasa_medis","Jasa Medis",20,20,$d2->jasa_medis);
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
        $f->selectSQL("mJAB", "Ruangan/Posisi Jabatan",
            "select '' as id, '' as jabatan_medis_fungsional union " .
            "select id, jabatan_medis_fungsional ".
            "from rs00018 ".
            "where unit_medis_fungsional_id = '" . $_GET["mPEG"] . "' ".
            "order by jabatan_medis_fungsional", $_GET["mJAB"],
            $ext);
        $f->execute();
    if ($is_selected) {
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2 ><TR>";
        echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
        echo "<INPUT TYPE=HIDDEN NAME=mPEG VALUE='".$_GET["mPEG"]."'>";
        echo "<INPUT TYPE=HIDDEN NAME=mJAB VALUE='".$_GET["mJAB"]."'>";
        echo "<TD>PENCARIAN <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
        echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";
        echo "</TR></FORM></TABLE></DIV>";
        $t = new PgTable($con, "100%");
        $t->SQL =
            "select a.nama, e.tdesc as agama,to_char(tanggal_lahir,'DD MON YYYY') as lahir, ".
                "a.pangkat,a.jabatan,a.status,  ".
                "a.id as dummy ".
	        "from rs00017 a ".
	            "left outer join rs00027 d ON a.rs00027_id = d.id ".
	            "left outer join rs00001 e ON a.agama_id = e.tc and e.tt='AGM' ".
	            "left outer join rs00001 b ON a.jjd_id = b.tc and b.tt='JJD' ".
	            "left outer join rs00001 c ON a.gol_ruang_id = c.tc and c.tt='GRP' ".
            "where a.jabatan_medis_fungsional_id='".$_GET["mJAB"]."' ".
                "and (a.status = 'peg' or a.status = 'klr' or a.status is null) and (upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%') 
				group by a.nama, agama, lahir, a.pangkat, a.jabatan, a.status, a.id
				";

        $t->setlocale("id_ID");
        $t->ColAlign[0] = "LEFT";
	$t->ColAlign[6] = "CENTER";
        $t->ShowRowNumber = true;
        $t->RowsPerPage = $ROWS_PER_PAGE;
        $t->ColFormatHtml[6] =
            "<A CLASS=TBL_HREF HREF='".
            "$SC?p=$PID".
            "&z=dua" .
            "&mPEG=" . $_GET["mPEG"] .
            "&mJAB=" . $_GET["mJAB"] .
            "&e=<#6#>".
            "'>".icon("edit","Edit")."</A>&nbsp;&nbsp;&nbsp;&nbsp;".

            "<A CLASS=TBL_HREF HREF='".
            "actions/809.keluar.php?p=$PID".
            "&z=tiga" .
            "&mPEG=" . $_GET["mPEG"] .
            "&mJAB=" . $_GET["mJAB"] .
            "&e=<#6#>".
            "'>".icon("del-left","Keluar")."</A> &nbsp;&nbsp;&nbsp;&nbsp;".

	    "<A CLASS=TBL_HREF HREF='".
            "actions/809.delete.php?p=$PID".
            "&z=empat" .
            "&mPEG=" . $_GET["mPEG"] .
            "&mJAB=" . $_GET["mJAB"] .
            "&e=<#6#>".
            "'>".icon("delete","Hapus")."</A>".

	    "</nobr>";
        $t->ColHeader = Array( "NAMA", "AGAMA", "TANGGAL LAHIR",
                              "PANGKAT","JABATAN","STATUS", "V i e w");
        $t->execute();

        echo " <BR><DIV ALIGN=LEFT><img src=\"icon/user.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
            "HREF='index2.php".
            "?p=$PID".
            "&z=satu" .
            "&mPEG=".$_GET["mPEG"].
            "&mJAB=".$_GET["mJAB"].
            "&e=new'>".
            "  Tambah Data Personil  </A></DIV>";
    }
}
?>
