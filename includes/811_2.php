<? 
$PID = "811_2";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (empty($_GET[sure])) {
if(isset($_GET["e"])) {
    $r = pg_query($con, "select * from rs00020 where id = '".$_GET["e"]."'");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    
  
    if($n > 0) {
    title("Sub Pembagian Sumber Pendapatan (Edit)");
    echo "<BR>";
        $f = new Form("actions/811.update.php", "POST");
        $f->hidden("id",$d->id);
		$f->hidden("unit_medis_id",$_GET[unit_medis_id]);
		$f->hidden("f_unit_medis_id", $d->unit_medis_id);
        $f->hidden("f_tipe_medis", $d->tipe_medis);
		$f->hidden("f_tipe_pasien",$d->tipe_pasien);
		
        $f->text("id","KODE",3,3,$d->id,"DISABLED");
		$f->selectArray("f_is_person",
                    "PEGAWAI",
                     Array("Y" => "Ya", "N" => "Tidak"),
                     $d->is_person);
		$f->text("f_prosen","Prosentase",5,5,$d->prosen);
	
		$f->submit(" Simpan ", "onClick='Form1.method=\"POST\";Form1.action=\"actions/811.update.php\";'");
		$f->execute();
    } else {
    title("Sub Pembagian Jasa Medis (Baru)");    
    echo "<BR>";
	 if (isset($_GET["e"])) {
            $ext = "OnChange = 'Form1.submit();'";
        } else {
            $ext = "OnChange = 'Form1.submit();'";
        }
        $f = new Form($SC, "GET", "NAME=Form1");
        $f->hidden("id","new");
		//$f->hidden("e","new");
		$f->hidden("unit_medis_id",$_GET[unit_medis_id]);
        $f->text("id","KODE",12,12,"&lt;OTOMATIS&gt;","DISABLED");
		$f->PgConn = $con;
   
        echo "<br>";
        $f->hidden("p", $PID);
		$f->hidden("e","new");
        $f->selectSQL("f_tipe_pasien","Tipe Pasien",
			"select '' as tc, '' as tdesc union " .
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'JEP' and tc!='000' ".
            "order by tdesc", $_GET["f_tipe_pasien"],$ext);
		$f->selectSQL("f_kel_sumb_pendapatan_id","Kelompok Sumber Pendapatan",
			"select '' as id, '' as jasa_medis union " .
            "select id, jasa_medis ".
            "from rs00021 ".
            "where tipe_pasien_id='" . $_GET["f_tipe_pasien"] . "' ".
            "order by jasa_medis", $_GET["f_kel_sumb_pendapatan_id"],
            $ext);
		$f->selectSQL("f_unit_medis_id", "Unit Medis",
            "select '' as tc, '' as tdesc union " .
            "select tc, tdesc ".
            "from rs00001 ".
            "where tt = 'PEG' and tc!='000' ".
            "order by tdesc", $_GET["f_unit_medis_id"],
            $ext);
        $f->selectSQL("f_tipe_medis", "Jabatan Medis",
            "select '' as id, '' as jabatan_medis_fungsional union " .
            "select id, jabatan_medis_fungsional ".
            "from rs00018 ".
            "where unit_medis_fungsional_id = '" . $_GET["f_unit_medis_id"] . "' ".
            "order by jabatan_medis_fungsional", $_GET["f_tipe_medis"],
            $ext);
		
		$f->selectArray("f_is_person",
                    "PEGAWAI",
                     Array("Y" => "Ya", "N" => "Tidak"),
                     $d->is_person);
		$f->text("f_prosen","Prosentase",5,5,$d->prosen);		
		$f->submit(" Simpan ", "onClick='Form1.method=\"POST\";Form1.action=\"actions/811.insert.php\";'");
		$f->execute();
    }    
    
//echo "test";
} else {
    title("<img src='icon/informasi-2.gif' align='absmiddle' >  Tabel Master: % PEMBAGIAN SUMBER PENDAPATAN");
    
    echo "<BR>";
	
	$ext = "OnChange = 'Form1.submit();'";
    $f = new Form($SC, "GET", "NAME=Form1");
    $f->PgConn = $con;
    $f->hidden("p", $PID);

	if (!$GLOBALS['print']) {
	    if (!isset($_GET['tanggal1D'])) {

	    } else {
		
	    }
		$f->selectSQL("unit_medis_id", "Jabatan Medis",
            "select '' as id, '' as jabatan_medis_fungsional union select id, jabatan_medis_fungsional from rs00018 
			order by jabatan_medis_fungsional", $_GET["unit_medis_id"],"");
	    $f->submit ("TAMPILKAN");
	    $f->execute();

	} else {
		if (!isset($_GET['tanggal1D'])) {

	    } else {
		
	    }
		
		
	}

    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<TD>Pencarian : <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

    echo "</TR></FORM></TABLE></DIV>";
	

	if ($_GET[sort]){
	$sort=" ";
	}else{
	$sort=" order by c.jabatan_medis_fungsional,e.jasa_medis,a.tipe_medis ";
	}
	
	$row=getFromTable("select count(jasa_medis) from rs00021");
    $t = new PgTable($con, "100%");
    $t->SQL = "select e.jasa_medis,c.jabatan_medis_fungsional,d.tdesc,case when a.is_person='Y' then 'Personal' else 'Bukan' end as person,a.prosen || '  ' || '%' as persentasi,a.id as dummy
	  from rs00020 a,rs00001 b, rs00018 c, rs00001 d, rs00021 e
	 where a.unit_medis_id=b.tc and b.tt='PEG' and a.tipe_medis=c.id  
	 and a.tipe_pasien=d.tc and d.tt='JEP' and a.kel_sumb_pendapatan_id=e.id and c.id like '%".$_GET["unit_medis_id"]."%' and 
	 (e.jasa_medis like '%".$_GET["search"]."%' or c.jabatan_medis_fungsional like '%".$_GET["search"]."%')
	$sort ";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "LEFT";
	$t->ColAlign[1] = "LEFT";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "CENTER";
	$t->ColAlign[4] = "CENTER";
	$t->ColAlign[5] = "CENTER";
    $t->RowsPerPage = $row;
    $t->ColFormatHtml[5] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#5#>&unit_medis_id=$_GET[unit_medis_id]'>".icon("edit","Edit")."</A>&nbsp;".
                           "<A CLASS=TBL_HREF HREF='actions/811.delete.php?p=$PID&e=<#5#>&unit_medis_id=$_GET[unit_medis_id]'>".icon("delete","Hapus")."</A>";

    $t->ColHeader = array("SUMBER PENDAPATAN","JABATAN MEDIS", "TIPE PASIEN","KATEGORI", "PROSENTASE", "V i e w");
    
    $t->execute();
    
    echo "<BR><DIV ALIGN=LEFT><img src=\"icon/keuangan.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new&unit_medis_id=$_GET[unit_medis_id]'>Sub Jasa Pelayanan Medis Askes Baru </A></DIV>";
}
}else{

	$data = getFromTable("select description from rs00020 where id='".$_GET[e]."'");

    echo "<div align=center>";
    echo "<form action='actions/811.delete.php' method='get'>";
    echo "<font color=red size=3>PERINGATAN !</font><br>";
    echo "<font class=SUB_MENU> Master % Pembagian Sumber Pendapatan <font color=navy>'".$data."'</font> akan Dihapus.</font><br><br>";
    echo "<input type=hidden name=p value=$PID>";
    echo "<input type=hidden name=e value=".$_GET[e].">";
    echo "<input type=hidden name=unit_medis_id value=".$_GET[unit_medis_id].">";
    echo "<input type=submit name=sure value='YA'>&nbsp;";
    echo "<input type=submit name=sure value='TIDAK'>";
    echo "</form>";
    echo "</div>";
	
}
?>
