<? // 30/12/2003
   // sfdn, 20-04-2004
   // sfdn, 30-04-2004

$PID = "input_pelayanan_rawat_inap";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");

if (empty($_GET[sure])) {
if(isset($_GET["e"])) {
    if ($_GET["e"] != "new") {
        $r = pg_query($con, "select * from rl100002 where id = '".$_GET["e"]."'");


    
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);
    }

    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";



    if($n > 0) {
        $f = new Form("actions/pelayanan_rawat_inap.update.php", "POST");
        title("Edit Laporan");
        $f->subtitle("Update Pelayanan Rawat Inap");
        $f->hidden("id","$d->id");
        $f->text("id","NO",6,6,$d->id,"DISABLED");
    } else {
        $f = new Form("actions/pelayanan_rawat_inap.insert.php");
        title("Edit Laporan");
        $f->subtitle("Tambah Data Pelayanan Rawat Inap");
        $f->hidden("id","new");
        $f->text("id","NO",12,12,"<OTOMATIS>","DISABLED");
    }
    $f->PgConn = $con;
    $f->text("f_layanan","Jenis Layanan",40,50,$d->layanan);

    $f->text("f_awal_triwulan","Pasien Awal Triwulan",20,20,$d->awal_triwulan);
    $f->text("f_masuk","Pasien Masuk",20,20,$d->masuk);
	$f->text("f_keluar_hidup","Pasien Masuk",20,20,$d->keluar_hidup);
	$f->text("f_kurang_48jam","Pasien Keluar Hidup",20,20,$d->kurang_48jam);
	$f->text("f_lebih_48jam","Pasien Keluar Mati < 48 Jam",20,20,$d->lebih_48jam);
	$f->text("f_jumlah","Pasien Keluar Mati > 48 Jam",20,20,$d->jumlah);
	$f->text("f_lama_dirawat","Jumlah Lama Dirawat",20,20,$d->lama_dirawat);
	$f->text("f_akhir_triwulan","Pasien Akhir Triwulan",20,20,$d->akhir_triwulan);
	$f->text("f_hari_perawatan","Jumlah Hari Perawatan",20,20,$d->hari_perawatan);
	$f->text("f_kelas_utama","Kelas Utama",20,20,$d->kelas_utama);
	$f->text("f_kelas_satu","Kelas Satu",20,20,$d->kelas_satu);
	$f->text("f_kelas_dua","Kelas Dua",20,20,$d->kelas_dua);
	$f->text("f_kelas_tiga","Kelas Tiga",20,20,$d->kelas_tiga);
	$f->text("f_tanpa_kelas","Tanpa Kelas",20,20,$d->tanpa_kelas);
     $f->submit(" Simpan ");
    $f->execute();
} else {
    // search box
    title("<img src='icon/medical-record.gif' align='absmiddle' >  Edit Laporan Pelayanan Rawat Inap");
lihat_laporan("pelayanan_rawat_inap");
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
//    echo "<TD><INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
  //  echo "<TD><INPUT TYPE=SUBMIT VALUE=' Cari '></TD>";
     echo "<TD >Pencarian:<INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

 
    echo "</TR></FORM></TABLE></DIV>";

    $t = new PgTable($con, "100%");
    $t->SQL = "select layanan, awal_triwulan, masuk, keluar_hidup, kurang_48jam, lebih_48jam, jumlah, lama_dirawat, akhir_triwulan, hari_perawatan, kelas_utama, Kelas_satu, kelas_dua, kelas_tiga, tanpa_kelas, id as href FROM rl100002 order by id";            
              
    $t->ColHeader = array("Jenis Pelayanan", "Pasien Awal Triwulan", "Pasien Masuk", "Pasien Keluar Hidup", "Pasien Keluar Mati < 48 Jam" , "Pasien Keluar Mati > 48 Jam", "Jumlah Pasien Keluar Mati", "Jumlah Lama Dirawat", "Pasien Akhir Triwulan", "Jumlah Hari Perawatan" , "Kelas Utama","Kelas I","Kelas II", "Kelas III", "Tanpa Kelas" ,"edit");
    $t->ShowRowNumber = true;
 //   $t->ColAlign[5] = "CENTER";
    /*
    $t->columnSort(1, "nama");
    $t->columnSort(2, "nama", true);
    $t->columnSort(3, "nama_keluarga");
    $t->columnSort(4, "alm_tetap");
    $t->columnSort(5, "kota_tetap");
    */
    $t->RowsPerPage = $ROWS_PER_PAGE;
    /*
    $t->Filter = "upper(nama) LIKE '%".strtoupper($_GET["search"])."%' ".
                 "OR mr_no LIKE '%".$_GET["search"]."%'";
    */
        $t->ColAlign[15] = "CENTER";

    $t->ColFormatHtml[15] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#15#>'>".icon("edit","Edit")."</A>".
    						"<A CLASS=TBL_HREF HREF='".
            "actions/pelayanan_rawat_inap.delete.php?p=$PID".
            "&e=<#15#>".
            "'>".icon("delete","Hapus")."</A>".
            "</nobr>"; 
    /*
    $t->ColFormatHtml[4] = "<nobr><A CLASS=TBL_HREF HREF=''>".icon("view","View")."</A> &nbsp; ".
                           "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#4#>'>".icon("edit","Edit")."</A></nobr>";
    */

    $t->execute();

    echo "<BR><DIV ALIGN=LEFT><img src=\"icon/user.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>Tambah Data </A></DIV>";
}
}else{
	$data = getFromTable("select layanan from rl100002 where no='".$_GET[e]."'");

    echo "<div align=center>";
    echo "<form action='actions/pelayanan_rawat_inap.delete.php' method='get'>";
    echo "<font color=red size=3>PERINGATAN !</font><br>";
    echo "<font class=SUB_MENU>Data Laporan <font color=navy>'".$data."'</font> akan Dihapus.</font><br><br>";
    echo "<input type=hidden name=p value=$PID>";
    echo "<input type=hidden name=e value=".$_GET[e].">";
    
    echo "<input type=submit name=sure value='YA'>&nbsp;";
    echo "<input type=submit name=sure value='TIDAK'>";
    echo "</form>";
    echo "</div>";
}
?>
