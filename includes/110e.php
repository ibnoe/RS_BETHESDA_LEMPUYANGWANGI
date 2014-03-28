<? // 30/12/2003

if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "igd" || $_SESSION[uid] == "rm" || $_SESSION[uid] == "root") {

$PID = "120";
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
        $f = new Form("actions/110e.update.php", "POST");
        $f->subtitle1("Identitas");
        $f->hidden("mr_no","$d->mr_no");
        $f->text("mr_no","No.MR",12,8,$d->mr_no,"DISABLED");
    } else {
        $f = new Form("actions/110.insert.php");
        $f->subtitle1("Identitas");
        $f->hidden("mr_no","new");
        $f->text("mr_no","No.MR",12,12,"<OTOMATIS>","DISABLED");
    }    
    $f->PgConn = $con;
    $f->text("f_nama","Nama",40,50,$d->nama);
    $f->text("f_nama_keluarga","Nama Keluarga",40,50,$d->nama_keluarga);
    $f->selectArray("f_jenis_kelamin", "Jenis Kelamin",Array("L" => "Laki-laki", "P" => "Perempuan"),$d->jenis_kelamin);
    $f->text("f_tmp_lahir","Tempat Lahir",40,40,$d->tmp_lahir);
    $f->selectDate("f_tgl_lahir", "Tanggal Lahir", pgsql2phpdate($d->tgl_lahir));
    $f->text("f_umur", "(Umur)", 5,3,$d->umur,"disabled");
    $f->selectSQL("f_agama_id", "Agama","select tc, tdesc from rs00001 where tt = 'AGM' and tc != '000'",$d->agama_id);    
    $f->text("f_no_ktp","Nomor KTP/SIM/KTA",50,50,$d->no_ktp); 
	$f->text("f_pangkat_gol","Pangkat/Golongan ",50,50,$d->pangkat_gol);
	$f->text("f_nrp_nip","NRP/NIP ",50,50,$d->nrp_nip);
	$f->text("f_kesatuan","Kesatuan/Instansi/Pekerjaan ",50,50,$d->kesatuan);
	$f->selectSQL("f_status_nikah", "Status Pernikahan","select '' as tc, '-' as tdesc union ".
        			  "select tc, tdesc from rs00001 where tt = 'SNP' and tc != '000'",$d->status_nikah);
    $f->selectSQL("f_gol_darah", "Golongan Darah","select '' as tc, '-' as tdesc union ".
        			  "select tc, tdesc from rs00001 where tt = 'GOL' and tc != '000'",$d->gol_darah);
    $f->selectSQL("f_resus_faktor", "Resus Faktor","select '' as tc, '-' as tdesc union ".
                        "select tc, tdesc from rs00001 where tt = 'REF' and tc != '000'",$d->resus_faktor);                                              
 	$f->text("f_nama_ayah","Nama Ayah ",50,50,$d->nama_ayah);
    $f->text("f_nama_ibu","Nama Ibu",50,50,$d->nama_ibu);
    $f->text("f_pekerjaan","Pekerjaan Orangtua ",50,50,$d->pekerjaan);
    
	$f->subtitle1("Alamat Tetap");
	$f->text("f_alm_tetap","Alamat",50,50,$d->alm_tetap);
	$f->text("f_kota_tetap","Kota",50,50,$d->kota_tetap);
	$f->text("f_pos_tetap","Kode Pos",5,5,$d->pos_tetap);
	$f->text("f_tlp_tetap","Telepon",15,15,$d->tlp_tetap);
 
    $f->subtitle1("Keluarga Dekat");
    $f->text("f_keluarga_dekat","Nama",50,50,$d->keluarga_dekat);
    $f->text("f_alm_keluarga","Alamat",50,50,$d->alm_keluarga);
    $f->text("f_kota_keluarga","Kota",50,50,$d->kota_keluarga);
    $f->text("f_pos_keluarga","Kode Pos",5,5,$d->pos_keluarga);
    $f->text("f_tlp_keluarga","Telepon",15,15,$d->tlp_keluarga);   

    $f->hidden("f_alm_sementara",$d->alm_sementara);
    $f->hidden("f_kota_sementara",$d->kota_sementara);
    $f->hidden("f_pos_sementara",$d->pos_sementara);
    $f->hidden("f_tlp_sementara",$d->tlp_sementara);
	
    $f->selectSQL("f_tipe_pasien", "Tipe Pasien","select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000'",
    			  "$d->tipe_pasien");                      
    $f->submit(" Simpan ");
    $f->execute();
} else {
    // search box
    echo "<img src='icon/informasi-2.gif' align='absmiddle' >";
    echo "<font class=FORM_TITLE>DATA PASIEN</font>";
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<TD class=SUB_MENU>NOMOR MR / NAMA: <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = "select a.mr_no, a.nama, a.nama_keluarga, a.alm_tetap as alamat, a.mr_no as href FROM rs00002 a ".
              "where upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' ".
              "OR a.mr_no LIKE '%".$_GET["search"]."%' ";

        if (!isset($_GET[sort])) {
           $_GET[sort] = "mr_no";
           $_GET[order] = "asc";
	}

    $t->ColHeader = array("MR NO", "NAMA", "NAMA KELUARGA", "ALAMAT", "EDIT", "&nbsp;");
    $t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[4] = "CENTER";
    $t->RowsPerPage = 20;
    $t->ColFormatHtml[4] = "<nobr><A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#4#>'>".icon("edit","Edit")."</A></nobr>";
    $t->execute();

?>
<br><br>
<div align="right">
<a href="javascript: cetakaja(<? echo (int) $_GET[rg];?>)" ><img src="images/cetak.gif" border="0"></a>
</div>
<?
}

} // end of $_SESSION[uid] == daftar || igd || rm || root

echo "\n<script language='JavaScript'>\n";
echo "function cetakaja(tag) {\n";
echo "sWin = window.open('includes/cetak.110.php?rg=' + tag, 'xWin',".
     "'top=0,left=0,width=750,height=550,menubar=no,scrollbars=yes');\n";
echo "sWin.focus();\n";
echo "}\n";
echo "</script>\n";
?>

