<? // 30/12/2003

//if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "igd" || $_SESSION[uid] == "rm" || $_SESSION[uid] == "root" || $_SESSION[uid] == "laborat" || $_SESSION[uid] == "radiologi") {
if ($_SESSION[uid]) {

$PID = "115";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if(isset($_GET["e"])) {
    $r = pg_query($con, "select * from rs00002 where mr_no = '".$_GET["e"]."'");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    
    title("Edit Identitas Pasien");
    
    if($n > 0) {
        $f = new Form("actions/110.update.php", "POST");
        $f->subtitle("Identitas");
        $f->hidden("mr_no","$d->mr_no");
        $f->text("mr_no","MR No",12,8,$d->mr_no,"DISABLED");
    } else {
        $f = new Form("actions/110.insert.php");
        $f->subtitle("Identitas");
        $f->hidden("mr_no","new");
        $f->text("mr_no","MR No",12,12,"<OTOMATIS>","DISABLED");
    }    
    $f->PgConn = $con;
    $f->text("f_nama","Nama",40,50,$d->nama);
    $f->text("f_nama_keluarga","Nama Keluarga",40,50,$d->nama_keluarga);
    $f->selectArray("f_jenis_kelamin", "Jenis Kelamin",Array("L" => "Laki-laki", "P" => "Perempuan"),$d->jenis_kelamin);
    $f->text("f_tmp_lahir","Tempat Lahir",40,40,$d->tmp_lahir);
    $f->selectDate("f_tgl_lahir", "Tanggal Lahir", pgsql2phpdate($d->tgl_lahir));
    $f->text("f_umur", "(Umur)", 5,3,$d->umur);
    $f->selectSQL("f_agama_id", "Agama","select tc, tdesc from rs00001 where tt = 'AGM' and tc != '000'",$d->agama_id);
    
    $f->subtitle("Alamat Tetap");
    $f->text("f_alm_tetap","Alamat",50,50,$d->alm_tetap);
    $f->text("f_kota_tetap","Kota",50,50,$d->kota_tetap);
    $f->text("f_pos_tetap","Kode Pos",5,5,$d->pos_tetap);
    $f->text("f_tlp_tetap","Telepon",15,15,$d->tlp_tetap);

    $f->hidden("f_alm_sementara",$d->alm_sementara);
    $f->hidden("f_kota_sementara",$d->kota_sementara);
    $f->hidden("f_pos_sementara",$d->pos_sementara);
    $f->hidden("f_tlp_sementara",$d->tlp_sementara);

    $f->hidden("f_keluarga_dekat",$d->keluarga_dekat);
    $f->hidden("f_alm_keluarga",$d->alm_keluarga);
    $f->hidden("f_kota_keluarga",$d->kota_keluarga);
    $f->hidden("f_pos_keluarga",$d->pos_keluarga);
    $f->hidden("f_tlp_keluarga",$d->tlp_keluarga);

    $f->submit(" Simpan ");
    $f->execute();
} else {
	if (!$GLOBALS['print']){
    	title_print("<img src='icon/informasi-2.gif' align='absmiddle' > DATA PASIEN PER REGISTRASI");
    } else {
    	title("<img src='icon/informasi.gif' align='absmiddle' > DATA PASIEN PER REGISTRASI");
    }
    
    $f = new Form("index.php", "GET");
    $f->hidden("p",$PID);
    
    if(!$GLOBALS['print']){
    	
    	if (!isset($_GET['tanggal1D'])) {
			$tanggal1D = date("d", time());
			$tanggal1M = date("m", time());
			$tanggal1Y = date("Y", time());
			$tanggal2D = date("d", time());
			$tanggal2M = date("m", time());
			$tanggal2Y = date("Y", time());

		    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y));
		    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y));
		    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
		    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");
		
		} else {
		    $tgl_sakjane = $_GET[tanggal2D] ;
		    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
		    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
		    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "");
		    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "");
		}
	    $f->submit(" Tampilkan ");
	    $f->execute();
    	
    } else {
    	
    	if (!isset($_GET['tanggal1D'])) {
			$tanggal1D = date("d", time());
			$tanggal1M = date("m", time());
			$tanggal1Y = date("Y", time());
			$tanggal2D = date("d", time());
			$tanggal2M = date("m", time());
			$tanggal2Y = date("Y", time());

		    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y));
		    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y));
		    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "");
		    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "");
		
		} else {
		    $tgl_sakjane = $_GET[tanggal2D] ;		
		    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
		    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
		    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
		    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
		}
		    //$f->submit(" Tampilkan ");
		    $f->execute();    	
    }
    
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<INPUT TYPE=HIDDEN NAME=tanggal1D VALUE=".$_GET[tanggal1D].">";
    echo "<INPUT TYPE=HIDDEN NAME=tanggal1M VALUE=".$_GET[tanggal1M].">";
    echo "<INPUT TYPE=HIDDEN NAME=tanggal1Y VALUE=".$_GET[tanggal1Y].">";
    echo "<INPUT TYPE=HIDDEN NAME=tanggal2D VALUE=".$_GET[tanggal2D].">";
    echo "<INPUT TYPE=HIDDEN NAME=tanggal2M VALUE=".$_GET[tanggal2M].">";
    echo "<INPUT TYPE=HIDDEN NAME=tanggal2Y VALUE=".$_GET[tanggal2Y].">";
	echo "<BR>";
    //echo "<TD class=SUB_MENU>NOMOR MR / NAMA: <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    //echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
    echo "</TR></FORM></TABLE></DIV>";

    if (!empty($_GET[tanggal1D]) or isset($ts_check_in1)) {
       $tmbh = "and (a.tanggal_reg between '$ts_check_in1' and '$ts_check_in2') ";
    }
	if ($_SESSION[uid] == "igd") {
	    $SQLWHERE = "AND (c.tc = 100 OR c.tc=0)";		//$SQLWHERE = "AND (b.poli = 10 OR b.poli=0)";
	} elseif ($_SESSION[uid] == "daftar") {
	    $SQLWHERE = "AND c.tc<>100 AND c.tc<>0";		//$SQLWHERE = "AND b.poli<>10 AND b.poli<>0";
	} elseif ($_SESSION[uid] == "laborat") {
		$SQLWHERE = "AND c.tc=203";
	} elseif ($_SESSION[uid] == "radiologi") {
		$SQLWHERE = "AND c.tc=204";
	} 
    $t = new PgTable($con, "100%");
    $t->SQL = "select b.mr_no, a.id, b.nama, ".
              "b.pangkat_gol,b.nrp_nip,b.kesatuan, ".
              "b.alm_tetap || ' ' || b.kota_tetap as alamat, c.tdesc as poli FROM rs00006 a ".
              "left join rs00002 b on b.mr_no = a.mr_no  ".
              "left join rs00001 c on c.tc = a.poli ".
              "where ((upper(b.nama) LIKE '%".strtoupper($_GET["search"])."%') ".
              "OR (a.mr_no LIKE '%".$_GET["search"]."%')) ".
              "$tmbh $SQLWHERE";

        if (!isset($_GET[sort])) {
           $_GET[sort] = "a.id";
           $_GET[order] = "asc";
	}

    $t->ColHeader = array("NO.MR","NO.REG","NAMA","PANGKAT","NRP/NIP","KESATUAN","ALAMAT","RAWATAN");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[1] = "CENTER";
        
    if(!$GLOBALS['print']){
		$t->RowsPerPage = 20;
    }else{
    	$t->RowsPerPage = 30;
    	$t->DisableNavButton = true;
    	$t->DisableScrollBar = true;
    	//$t->DisableStatusBar = true;
    }
    $t->execute();

}

} // end of ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "igd" || $_SESSION[uid] == "rm" || $_SESSION[uid] == "root")
?>

