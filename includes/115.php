<? // 30/12/2003

//if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "igd" || $_SESSION[uid] == "rm" || $_SESSION[uid] == "root" || $_SESSION[uid] == "laborat" || $_SESSION[uid] == "radiologi") {
// Agung Sunandar 0:51 27/06/2012 Menambahkan filter shift
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
}

 else {
	if (!$GLOBALS['print']){
    	title_print("<img src='icon/informasi-2.gif' align='absmiddle' > DATA PASIEN PER REGISTRASI");
        title_excel("115&tanggal1D=".$_GET["tanggal1D"]."&tanggal1M=".$_GET["tanggal1M"]."&tanggal1Y=".$_GET["tanggal1Y"]."&tanggal2D=".$_GET["tanggal2D"]."&tanggal2M=".$_GET["tanggal2M"]."&tanggal2Y=".$_GET["tanggal2Y"]."&mRAWAT=".$_GET["tanggalmRAWAT"]."&mPASIEN=".$_GET["tanggalmPASIEN"]);
        }
   

 else {
    	title("<img src='icon/informasi.gif' align='absmiddle' > DATA PASIEN PER REGISTRASI");
    	//title_excel("110&tblstart=".$_GET['tblstart']);
    	
		}
    
    $f = new Form("index2.php", "GET");
    $f->PgConn = $con;
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
		
		$f->selectSQL("mDOKTER", "Dokter",
	        "select '' as nm_dok, '' as nama union ".
	        "select nama as nm_dok, nama ".
	        "from rs00017 ".
	        "WHERE pangkat LIKE '%DOKTER%' Order By nama Asc ;", $_GET["mDOKTER"],
        $ext);
		   
		$f->selectSQL("mRAWAT", "RAWATAN","select '' as tc, '' as tdesc union 
								 SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','201','202','206','207','208')
								 order by tdesc ",$_GET["mRAWAT"], "");
		$f->selectSQL("mPASIEN","Tipe Pasien","select '' as tc, '' as tdesc union 
											   select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000' order by tdesc ASC ",
											   $_GET["mPASIEN"],"");
		// Agung Sunandar 0:51 27/06/2012 Menambahkan filter shift
		/* $f->selectArray("shift", "Pasien",  Array("P" => "Shift Pagi", "S" => "Shift Siang" , "M1" => "Shift Malam"  ), $_GET["shift"],"disabled"); */
		$f->selectArray("shift", "Pasien",  Array("P" => "Shift Pagi (07.00-14.00)", "S" => "Shift Siang (14.01-21.00)" , "M1" => "Shift Malam (21.01-23.59)" , "M2" => "Shift Malam (00.00-06.59)" ), $_GET["shift"]," ");
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
		    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$tanggal1M,$tanggal1D,$tanggal1Y)), "disabled");
		    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$tanggal2M,$tanggal2D,$tanggal2Y)), "disabled");
		
		} else {
		    $tgl_sakjane = $_GET[tanggal2D] ;		
		    $ts_check_in1 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"]));
		    $ts_check_in2 = date("Y-m-d", mktime(0,0,0,$_GET["tanggal2M"],$tgl_sakjane,$_GET["tanggal2Y"]));
		    $f->selectDate("tanggal1", "Dari Tanggal", getdate(mktime(0,0,0,$_GET["tanggal1M"],$_GET["tanggal1D"],$_GET["tanggal1Y"])), "disabled");
		    $f->selectDate("tanggal2", "s/d", getdate(mktime(0,0,0,$_GET["tanggal2M"],$_GET["tanggal2D"],$_GET["tanggal2Y"])), "disabled");
		}
		
		$f->selectSQL("mDOKTER", "Dokter",
	        "select '' as nm_dok, '' as nama union ".
	        "select nama as nm_dok, nama ".
	        "from rs00017 ".
	        "WHERE pangkat LIKE '%DOKTER%' Order By nama Asc ;", $_GET["mDOKTER"],
        $ext);
		    //$f->PgConn = $con;
		$f->selectSQL("mRAWAT", "RAWATAN","select '' as tc, '-' as tdesc union 
								 SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' and tc not in ('000','201','202','206','207','208')
								 order by tdesc ",$_GET["mRAWAT"], "disabled");
		
		$f->selectSQL("mPASIEN","Tipe Pasien","select '' as tc, '' as tdesc union 
												   select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000' ",
												   $_GET["mPASIEN"],"disabled");
		// Agung Sunandar 0:51 27/06/2012 Menambahkan filter shift
		/* $f->selectArray("shift", "Pasien",  Array("P" => "Shift Pagi", "S" => "Shift Siang" , "M1" => "Shift Malam"  ), $_GET["shift"],"disabled"); */
		$f->selectArray("shift", "Pasien",  Array("P" => "Shift Pagi (07.00-14.00)", "S" => "Shift Siang (14.01-21.00)" , "M1" => "Shift Malam (21.01-23.59)" , "M2" => "Shift Malam (00.00-06.59)" ), $_GET["shift"]," ");
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
    echo "</TR></FORM></TABLE></DIV>";
	
	if ($_GET["shift"]=="P"){
	$jam1="07:00:00";
	$jam2="14:00:00";
	}elseif($_GET["shift"]=="S"){
	$jam1="14:01:00";
	$jam2="21:00:00";
	}elseif($_GET["shift"]=="M1"){
	$jam1="21:01:00";
	$jam2="23:59:00";
	}elseif($_GET["shift"]=="M2"){
	$jam1="00:00:00";
	$jam2="06:59:00";
	}else{
	$jam1="00:00:00";
	$jam2="23:59:59";
	}

    $t = new PgTable($con, "100%");
    $t->SQL = "select b.mr_no, b.mr_rs, a.id, upper(b.nama)as nama, ".
              "b.jenis_kelamin,b.umur,d.tdesc, ".
              "b.alm_tetap || ' ' || b.kota_tetap as alamat, c.tdesc as poli, a.diagnosa_sementara as dokter FROM rs00006 a ".
              "left join rs00002 b on b.mr_no = a.mr_no  ".
              "left join rs00001 c on c.tc_poli = a.poli and c.tt='LYN' ".
              "left join rs00001 x ON a.poli = x.tc_poli and x.tt='LYN' ".
              "left join rs00001 d on a.tipe = d.tc and d.tt='JEP' ".
              "where ((upper(b.nama) LIKE '%".strtoupper($_GET["search"])."%') ".
              "OR (a.mr_no LIKE '%".$_GET["search"]."%')) ".
              "and a.diagnosa_sementara like '%".$_GET["mDOKTER"]."%' and a.tipe like '%".$_GET["mPASIEN"]."%' and a.poli::text like '%".$_GET["mRAWAT"]."%' and (a.tanggal_reg between '$ts_check_in1' and '$ts_check_in2')
			   and (a.waktu_reg between '$jam1' and '$jam2') ";

        if (!isset($_GET[sort])) {
           $_GET[sort] = "a.tanggal_reg";
           $_GET[order] = "asc";
	}

    $t->ColHeader = array("NO.MR","MR LAMA","NO.REG","NAMA","SEKS","UMUR (Tahun)","TIPE PASIEN","ALAMAT","RAWATAN","DOKTER");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[4] = "CENTER";
    $t->ColAlign[5] = "CENTER";
    $t->ColAlign[6] = "CENTER";
    $t->ColAlign[1] = "CENTER";
        
    if(!$GLOBALS['print']){
		$t->RowsPerPage = 50;
    }else{
    	$t->RowsPerPage = 100;
    	$t->DisableNavButton = true;
    	$t->DisableScrollBar = true;
    	//$t->DisableStatusBar = true;
    }
    $t->execute();

}

} // end of ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "igd" || $_SESSION[uid] == "rm" || $_SESSION[uid] == "root")
?>

