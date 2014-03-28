<script language="javascript">
function hitungTanggalLahir(){
	var tahun = parseInt(document.Form1.f_tgl_lahirY.value);
	var bulan = parseInt(document.Form1.f_tgl_lahirM.value)-1;
	var tanggal = parseInt(document.Form1.f_tgl_lahirD.value);
	var tanggalan = new Date();
	var today = new Date();
	var jml_hari = 0;
	if(isNaN(parseInt(document.Form1.f_umur_tanggal.value))){
		jml_hari = 0;
		}
	else{
		jml_hari = parseInt(document.Form1.f_umur_tanggal.value);
		}
	var tgl_lahir = parseInt(today.getDate()) - jml_hari;	
	
	var jml_bulan = 0;	
	if(tgl_lahir<1){
		jml_bulan++;
		tanggalan = new Date(tahun, bulan, 0);
		tgl_lahir = tanggalan.getDate()+tgl_lahir;
		}
	if(isNaN(parseInt(document.Form1.f_umur_bulan.value))){
		jml_bulan = 0;
		}
	else{		
		jml_bulan = parseInt(document.Form1.f_umur_bulan.value) + jml_bulan;
		}
		var selisih_bulan = parseInt(today.getMonth()+1) - jml_bulan;
		jml_tahun = 0;
	if(selisih_bulan<1){
		jml_tahun++;
		jml_bulan = 12+selisih_bulan;
		}
	if(isNaN(parseInt(document.Form1.f_umur.value))){
		jml_tahun = 0;
		}
	else{
		jml_tahun = parseInt(document.Form1.f_umur.value)+jml_tahun;
		}
	document.Form1.f_tgl_lahirD.value = tgl_lahir;
	document.Form1.f_tgl_lahirM.value = jml_bulan;
	document.Form1.f_tgl_lahirY.value = parseInt(today.getFullYear()) - jml_tahun;
	}
function hitungUmur(){
	var today = new Date();
	var tahun = parseInt(document.Form1.f_tgl_lahirY.value);
	var bulan = parseInt(document.Form1.f_tgl_lahirM.value)-1;
	var tanggal = parseInt(document.Form1.f_tgl_lahirD.value);
	var lahir = new Date(tahun,bulan,tanggal);
	var selisih = Date.parse(today.toGMTString()) - Date.parse(lahir.toGMTString());
	var lastDay = new Date(tahun, bulan+1, 0);
	var umur_tahun = parseInt((selisih/(1000*60*60*24*365)));
	var umur_bulan = Math.round((selisih/(1000*60*60*24*365/12)))%12;
	var umur_hari  = parseInt((selisih/(1000*60*60*24)))%30;
	document.Form1.f_umur.value = umur_tahun;
	document.Form1.f_umur_bulan.value = umur_bulan;
	document.Form1.f_umur_tanggal.value = umur_hari;
}
</script>
<? // 30/12/2003

//if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "ugd" || $_SESSION[uid] == "rm" || $_SESSION[uid] == "root") {

$PID = "110";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

if(isset($_GET["e"])) {
    $r = pg_query($con, "select * from rs00002 where mr_no = '".$_GET["e"]."'");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID&registered=Y&q=search&search=a'>".icon("back","Kembali")."</a></DIV>";
    
    title("Edit Identitas Pasien");
    
    if($n > 0) {
        $f = new Form("actions/110.update.php", "POST","NAME=Form1");
        $f->subtitle1("Identitas");
        $f->subtitle("<font color='red'><b>*</b> : Harus Di Isi</font>");
		$f->hidden("uid","$_SESSION[uid]");
        $f->hidden("nama_usr","$_SESSION[nama_usr]");
        $f->hidden("mr_no","$d->mr_no");
        $f->text("mr_no","No.MR",12,8,$d->mr_no,"DISABLED");
    } else {
        $f = new Form("actions/110.insert.php","POST","NAME=Form1");
        $f->subtitle1("Identitas");
		$f->hidden("uid","$_SESSION[uid]");
        $f->hidden("nama_usr","$_SESSION[nama_usr]");
        $f->hidden("mr_no","new");
        $f->text("mr_no","No.MR",12,12,"<OTOMATIS>","DISABLED");
    }    
    $f->PgConn = $con;
    $f->text("f_nama","<font color='red'>Nama</font>",40,50,$d->nama,"required");
    $f->text("f_mr_rs","MR Lama",40,50,$d->mr_rs);
    $f->selectArray("f_jenis_kelamin", "<font color='red'>Jenis Kelamin *</font>",Array("" => "-", "L" => "Laki-laki", "P" => "Perempuan"),$d->jenis_kelamin);
    $f->text("f_tmp_lahir","<font color='red'>Tempat Lahir *</font>",40,40,$d->tmp_lahir,"required");
    //$f->selectDate("f_tgl_lahir", "Tanggal Lahir", pgsql2phpdate($d->tgl_lahir));
    $f->selectDate_reg("f_tgl_lahir", "Tanggal Lahir", getdate(), 'onChange="hitungUmur()"');
    //$f->calendar("f_tgl_lahir","Tanggal Lahir",20,20,$d->tgl_lahir,"FORM1","icon/calendar.gif","Pilih Tanggal",$ext);
    //$f->text("f_umur", "(Umur)", 5,3,$d->umur);
    $name['tahun']='f_umur';$size['tahun']='3';$ext['tahun']='style="text-align:right;" onkeyup="hitungTanggalLahir();" required';$def_val['tahun']=$d->umur;
	$name['bulan']='f_umur_bulan';$size['bulan']='3';$ext['bulan']='style="text-align:right;" onkeyup="hitungTanggalLahir();" required';$def_val['bulan']=$d->umur_bulan;
	$name['hari']='f_umur_tanggal';$size['tanggal']='3';$ext['hari']='style="text-align:right;" onkeyup="hitungTanggalLahir();" required';$def_val['hari']=$d->umur_tanggal;
	$f->textUmurTahunBulanHari($name,"<font color='red'>Umur *</font>",$size,$max_length,$def_val,$ext);
    $f->selectSQL("f_agama_id", "Agama", "select '' as tc, '-' as tdesc union ".
                       "select tc, tdesc from rs00001 where tt = 'AGM' and tc != '000' order by tc",$d->agama_id);    
    $f->text("f_no_ktp","<font color='red'>Nomor KTP/SIM/KTA *</font>",50,50,$d->no_ktp,"required"); 
	$f->text("f_pangkat_gol","Pangkat/Golongan ",50,50,$d->pangkat_gol);
	$f->text("f_nrp_nip","NRP/NIP ",50,50,$d->nrp_nip);
	$f->text("f_kesatuan","Kesatuan/Instansi/Pekerjaan ",50,50,$d->kesatuan);
	$f->selectSQL("f_status_nikah", "Status Pernikahan","select '' as tc, '-' as tdesc union ".
        			  "select tc, tdesc from rs00001 where tt = 'SNP' and tc != '000' order by tc",$d->status_nikah);
    $f->selectSQL("f_gol_darah", "Golongan Darah","select '' as tc, '-' as tdesc union ".
        			  "select tc, tdesc from rs00001 where tt = 'GOL' and tc != '000' order by tc",$d->gol_darah);
    $f->selectSQL("f_resus_faktor", "Resus Faktor","select '' as tc, '-' as tdesc union ".
                        "select tc, tdesc from rs00001 where tt = 'REF' and tc != '000' order by tc",$d->resus_faktor);                                              
 	$f->text("f_nama_ayah","Nama Ayah ",50,50,$d->nama_ayah);
    $f->text("f_nama_ibu","Nama Ibu",50,50,$d->nama_ibu);
    $f->text("f_pekerjaan","Pekerjaan Orangtua ",50,50,$d->pekerjaan);
    
	$f->subtitle1("Alamat Tetap");
	$f->text("f_alm_tetap","<font color='red'>Alamat *</font>",50,50,$d->alm_tetap,"required");
	$f->text("f_kota_tetap","<font color='red'>Kota *</font>",50,50,$d->kota_tetap, "required");
	$f->text("f_pos_tetap","Kode Pos",5,5,$d->pos_tetap);
	$f->text("f_tlp_tetap","Telepon",15,15,$d->tlp_tetap);
 
    $f->subtitle1("Keluarga Dekat");
    $f->text("f_keluarga_dekat","Nama",50,50,$d->keluarga_dekat);
    $f->text("f_alm_keluarga","Alamat",50,50,$d->alm_keluarga);
    $f->text("f_kota_keluarga","Kota",50,50,$d->kota_keluarga);
    $f->text("f_pos_keluarga","Kode Pos",5,5,$d->pos_keluarga);
    $f->text("f_tlp_keluarga","<font color='red'>Telepon *</font>",15,15,$d->tlp_keluarga,"required");   

    $f->hidden("f_alm_sementara",$d->alm_sementara);
    $f->hidden("f_kota_sementara",$d->kota_sementara);
    $f->hidden("f_pos_sementara",$d->pos_sementara);
    $f->hidden("f_tlp_sementara",$d->tlp_sementara);
	
    $f->selectSQL("f_tipe_pasien", "<font color='red'>Tipe Pasien *</font>","select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000'",
    			  "$d->tipe_pasien");                      
    $f->submit(" Simpan ");
    $f->execute();
    echo "<br/>";
    echo "\n<script language='JavaScript'>\n";
    echo "function cetakKartu(tag) {\n";
    echo "    sWin = window.open('includes/cetak.120_rm.php?rg=' + tag, 'xWin',".
        " 'top=0,left=0,width=500,height=300,menubar=no,scrollbars=yes');\n";
    echo "    sWin.focus();\n";
    echo "}\n";
    echo "</script>\n";
    echo "<a href='#' onClick=cetakKartu('".(string)$d->mr_no."') ><img src='images/cetak.gif' border=0> CEtak Kartu Pasien</a>";
} else {
    
	if (!$GLOBALS['print']){
    	title_print("<img src='icon/informasi-2.gif' align='absmiddle' > DATA PASIEN");
        title_excel("110&tblstart=".$_GET['tblstart']);
        
    } else {
    	title("<img src='icon/informasi.gif' align='absmiddle' > DATA PASIEN");
    }
    
    //-- add param start
	$addParam = '';
	//if($_GET['no_mr'] != ''){
	//    $addParam = $addParam." AND a.mr_no LIKE '%".$_GET["no_mr"]."%' ";
	//}
	if($_GET['nama'] != ''){
	    $addParam = $addParam." AND upper(a.nama) LIKE '%".strtoupper($_GET["nama"])."%' ";
	}
	if($_GET['nama_a'] != ''){
	    $addParam = $addParam." AND upper(a.nama_ayah) LIKE '%".strtoupper($_GET["nama_a"])."%' ";
	}
	if($_GET['nama_i'] != ''){
	    $addParam = $addParam." AND upper(a.nama_ibu) LIKE '%".strtoupper($_GET["nama_i"])."%' ";
	}
	if($_GET['alamat'] != ''){
	    $addParam = $addParam." AND upper(a.alm_tetap) like '%".strtoupper($_GET["alamat"])."%' ";
	}
	//-- add param end
	
    // search box
    //echo "<img src='icon/informasi-2.gif' align='absmiddle' >";
    //echo "<font class=FORM_TITLE>DATA PASIEN</font>";
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    if(!$GLOBALS['print']){
    	//echo "<TD class=FORM>Pencarian : <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    	//echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
    	echo "<TD class=FORM>No. MR : <INPUT TYPE=TEXT NAME=no_mr VALUE='".$_GET["no_mr"]."'></TD>";
    	echo "<TD class=FORM>Nama Pasien : <INPUT TYPE=TEXT NAME=nama VALUE='".$_GET["nama"]."'></TD>";
		echo "<TD class=FORM>Nama Ayah : <INPUT TYPE=TEXT NAME=nama_a VALUE='".$_GET["nama_a"]."'></TD>";
		echo "<TD class=FORM>Nama Ibu : <INPUT TYPE=TEXT NAME=nama_i VALUE='".$_GET["nama_i"]."'></TD>";
		echo "<TD class=FORM>Alamat : <INPUT TYPE=TEXT NAME=alamat VALUE='".$_GET["alamat"]."'></TD>";
    	echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
    }else{
    	//echo "<TD class=FORM>Pencarian : <INPUT disabled TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    	//echo "<TD><INPUT disabled TYPE=SUBMIT VALUE=' Cari '></TD>";
    	echo "<TD class=FORM>No. MR : <INPUT disabled TYPE=TEXT NAME=no_mr VALUE='".$_GET["no_mr"]."'></TD>";
		echo "<TD class=FORM>Nama Pasien : <INPUT disabled TYPE=TEXT NAME=nama VALUE='".$_GET["nama"]."'></TD>";
		echo "<TD class=FORM>Nama Ayah : <INPUT TYPE=TEXT NAME=nama_a VALUE='".$_GET["nama_a"]."'></TD>";
		echo "<TD class=FORM>Nama Ibu : <INPUT TYPE=TEXT NAME=nama_i VALUE='".$_GET["nama_i"]."'></TD>";
		echo "<TD class=FORM>Alamat : <INPUT disabled TYPE=TEXT NAME=alamat VALUE='".$_GET["alamat"]."'></TD>";
    	echo "<TD><INPUT disabled TYPE=SUBMIT VALUE=' Cari '></TD>";
    }
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
  //  $t->SQL = "select a.mr_no,upper(a.nama)as nama,a.jenis_kelamin,a.tmp_lahir,a.tgl_lahir,a.kesatuan, ".
   //           "a.alm_tetap as alamat, a.tlp_tetap,a.mr_no as href ".
    //          "FROM rs00002 a ".
     //         "left join rs00001 b on a.tipe_pasien = b.tc and b.tt = 'JEP'".
      //        "where upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' ".
       //       "OR a.mr_no LIKE '%".$_GET["search"]."%' ".
        //      "OR upper(a.alm_tetap) like '%".strtoupper($_GET["search"])."%' ".
         //     "OR upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' ";
          //   "OR upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%'" ;
/*
$t->SQL = "select a.mr_no,upper(a.nama)as nama,a.mr_rs,a.jenis_kelamin,a.tmp_lahir,a.umur,a.kesatuan,b.tdesc, ".
              " a.alm_tetap as alamat,a.tlp_tetap, a.mr_no as href, a.mr_no as href2 ".
              "FROM rs00002 a ".
              "left join rs00001 b on a.tipe_pasien = b.tc and b.tt = 'JEP'".
              "where upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' ".
              "OR a.mr_no LIKE '%".$_GET["search"]."%' ".
              "OR upper(a.alm_tetap) like '%".strtoupper($_GET["search"])."%' ".
              "OR upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' ".
              "OR upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%'";
*/
$t->SQL = "select a.mr_no,upper(a.nama)as nama,a.mr_rs,a.jenis_kelamin,to_char(a.tgl_lahir,'DD Mon YYYY') as tgl_lahir,a.umur,a.kesatuan,b.tdesc, ".
              " a.alm_tetap as alamat,a.tlp_tetap,a.nama_ayah,a.nama_ibu, a.mr_no as href, a.mr_no as href2 ".
              "FROM rs00002 a ".
              "left join rs00001 b on a.tipe_pasien = b.tc and b.tt = 'JEP' ".
              "where a.mr_no LIKE '%".$_GET["no_mr"]."%' ".
              "".$addParam."";

        if (!isset($_GET[sort])) {
           $_GET[sort] = "mr_no";
           $_GET[order] = "desc";
		}

 //   $t->ColHeader = array("NO.MR","NAMA","JNS KELAMIN","TEMPAT LAHIR","TANGGAL LAHIR","PEKERJAAN","ALAMAT","TELP","EDIT","&nbsp;");
//    $t->ColHeader = array("NO.MR","NAMA","PEKERJAAN","TIPE PASIEN","NAMA KELUARGA","ALAMAT","EDIT","&nbsp;");
// $t->ColHeader = array("NO.MR","NAMA","JNS KELAMIN","TEMPAT LAHIR","TGL LAHIR","PEKERJAAN","TIPE PASIEN","ALAMAT","EDIT","&nbsp;");
	$t->ColHeader = array("NO.MR","NAMA","MR LAMA","SEX","TANGGAL LAHIR","UMUR (Th)","PEKERJAAN","TIPE PASIEN","ALAMAT","TELEPON","AYAH","IBU","EDIT","CETAK MR","&nbsp;");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[2] = "CENTER";
    $t->ColAlign[3] = "CENTER";
    $t->ColAlign[4] = "LEFT";
    $t->ColAlign[5] = "CENTER";
    $t->ColAlign[12] = "CENTER";
	$t->ColAlign[13] = "CENTER";    
    if(!$GLOBALS['print']){
		$t->RowsPerPage = 50;
    	$t->ColFormatHtml[12] = "<nobr><A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#12#>'>".icon("edit","Edit")."</A></nobr>";
       $t->ColFormatHtml[13] = "<nobr><A CLASS=TBL_HREF HREF='includes/cetak.120.php?rg=<#13#>'>".icon("ok","Cetak")."</A></nobr>";
    }else{
    	$t->RowsPerPage = 50;
    	$t->ColFormatHtml[12] = icon("edit","Edit");
    	$t->DisableNavButton = true;
    	$t->DisableScrollBar = true;
    	//$t->DisableStatusBar = true;
    }
    $t->execute();

}

//} // end of $_SESSION[uid]

?>

