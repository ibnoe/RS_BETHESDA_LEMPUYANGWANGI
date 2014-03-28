<?php
/***Me->-20130208***/

$PID="848";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");


if (empty($_GET[sure])) {
if(isset($_GET["e"])) {
    $r = pg_query($con, "select * from margin_apotik where margin_id = '".$_GET["e"]."'");
    $n = pg_num_rows($r);
    if($n > 0) $d = pg_fetch_object($r);
    pg_free_result($r);
    
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=$PID'>".icon("back","Kembali")."</a></DIV>";
    if($n > 0) {
    title("<B><font color='orange'>Edit Margin Apotik</B>");        
        $f = new Form("actions/848.update.php", "POST");
        //$f->subtitle("<font color='red'><b>*</b> : 1/100 = 0.01</font>");
        $f->hidden("margin_id",$d->margin_id);
        $f->text("margin_id","<B><font color='green'>KODE",3,3,$d->margin_id,"DISABLED");
    } else {
    title("<B><font color='green'>&nbsp;Margin Apotik</B>");    
        $f = new Form("actions/848.insert.php");
        //$f->subtitle("<font color='red'><b>*</b> : 1/100 = 0.01</font>");
        $f->hidden("margin_id","new");
        $f->text("margin_id","<B><font color='green'>KODE",12,12,"&lt;OTOMATIS&gt;","DISABLED");
     }
    
    $f->PgConn = $con;
    $f->selectSQL("f_kategori_id", "<B><font color='green'>Kategori Inventory","select '' as tc, '-- pilih kategori --' as tdesc union ".
                  "select tc, tdesc from rs00001 where tt = 'GOB' and tc != '000'",
                  $d->kategori_id);
    
    $f->text2column_A("f_pm_car_drs","<B><font color='green'>1. Persen Margin RAJAL OBAT LUAR DAN TABLET",4,4,$d->pm_car_drs);
    $f->text2column_B("f_tuslah_car_drs","<B><font color='green'>1. Tuslah RAJAL OBAT LUAR DAN TABLET",6,8,$d->tuslah_car_drs);
    
    $f->text2column_A("f_pm_car_rsrj","<B><font color='green'>2. Persen Margin RAJAL INJEKSI DAN ALKES",4,4,$d->pm_car_rsrj);
    $f->text2column_B("f_tuslah_car_rsrj","<B><font color='green'>2. Tuslah RAJAL INJEKSI DAN ALKES",6,8,$d->tuslah_car_rsrj);

    $f->text2column_A("f_pm_car_rsri","<B><font color='green'>3. Persen Margin RAJAL TAGIHAN",4,4,$d->pm_car_rsri);
    $f->text2column_B("f_tuslah_car_rsri","<B><font color='green'>3. Tuslah RAJAL TAGIHAN",6,8,$d->tuslah_car_rsri);

    $f->text2column_A("f_pm_inhealth_drs","<B><font color='green'>4. Persen Margin HV",4,4,$d->pm_inhealth_drs);
    $f->text2column_B("f_tuslah_inhealth_drs","<B><font color='green'>4. Tuslah HV",6,8,$d->tuslah_inhealth_drs);

    $f->text2column_A("f_pm_inhealth_rs","<B><font color='green'>5. Persen Margin BON KARYAWAN",4,4,$d->pm_inhealth_rs);
    $f->text2column_B("f_tuslah_inhealth_rs","<B><font color='green'>5. Tuslah BON KARYAWAN",6,8,$d->tuslah_inhealth_rs);

    $f->text2column_A("f_pm_jam_ri","<B><font color='green'>6. Persen Margin RAJAL KARYAWAN",4,4,$d->pm_jam_ri);
    $f->text2column_B("f_tuslah_jam_ri","<B><font color='green'>6. Tuslah RAJAL KARYAWAN",6,8,$d->tuslah_jam_ri);

    $f->text2column_A("f_pm_jam_rj","<B><font color='green'>7. Persen Margin ROS",4,4,$d->pm_jam_rj);
    $f->text2column_B("f_tuslah_jam_rj","<B><font color='green'>7. Tuslah ROS",6,8,$d->tuslah_jam_rj);

    $f->text2column_A("f_pm_kry_kelinti","<B><font color='green'>8. Persen Margin RANAP UMUM KELAS III",4,4,$d->pm_kry_kelinti);
    $f->text2column_B("f_tuslah_kry_kelinti","<B><font color='green'>8. Tuslah RANAP UMUM KELAS III",6,8,$d->tuslah_kry_kelinti);

    $f->text2column_A("f_pm_kry_kelbesar","<B><font color='green'>9. Persen Margin RANAP UMUM KELAS II - VIP",4,4,$d->pm_kry_kelbesar);
    $f->text2column_B("f_tuslah_kry_kelbesar","<B><font color='green'>9. Tuslah RANAP UMUM KELAS II - VIP",6,8,$d->tuslah_kry_kelbesar);

    $f->text2column_A("f_pm_kry_kelgratisri","<B><font color='green'>10. Persen Margin RANAP IBU KELAS III (KHUSUS)",4,4,$d->pm_kry_kelgratisri);
    $f->text2column_B("f_tuslah_kry_kelgratisri","<B><font color='green'>10. Tuslah RANAP IBU KELAS III (KHUSUS)",6,8,$d->tuslah_kry_kelgratisri);

    $f->text2column_A("f_pm_kry_kelrespoli","<B><font color='green'>11. Persen Margin RANAP IBU KELAS III - VIP",4,4,$d->pm_kry_kelrespoli);
    $f->text2column_B("f_tuslah_kry_kelrespoli","<B><font color='green'>11. Tuslah RANAP IBU KELAS III - VIP",6,8,$d->tuslah_kry_kelrespoli);

    $f->text2column_A("f_pm_kry_kel","<B><font color='green'>12. Persen Margin RANAP BAYI KELAS III (KHUSUS)",4,4,$d->pm_kry_kel);
    $f->text2column_B("f_tuslah_kry_kel","<B><font color='green'>12. Tuslah RANAP BAYI KELAS III (KHUSUS)",6,8,$d->tuslah_kry_kel);
    
    $f->text2column_A("f_pm_kry_kelgratisrj","<B><font color='green'>13. Persen Margin RANAP BAYI KELAS III - VIP",4,4,$d->pm_kry_kelgratisrj);
    $f->text2column_B("f_tuslah_kry_kelgratisrj","<B><font color='green'>13. Tuslah RANAP BAYI KELAS III - VIP",6,8,$d->tuslah_kry_kelgratisrj);
    
    $f->text2column_A("f_pm_umum_ri","<B><font color='green'>14. Persen Margin RANAP KARYAWAN",4,4,$d->pm_umum_ri);
    $f->text2column_B("f_tuslah_umum_ri","<B><font color='green'>14. Tuslah RANAP KARYAWAN",6,8,$d->tuslah_umum_ri);

    $f->text2column_A("f_pm_umum_rj","<B><font color='green'>15. Persen Margin KELUARGA INTI",4,4,$d->pm_umum_rj);
    $f->text2column_B("f_tuslah_umum_rj","<B><font color='green'>15. Tuslah KELUARGA INTI",6,8,$d->tuslah_umum_rj);

    $f->text2column_A("f_pm_umum_ikutrekening","<B><font color='green'>16. Persen Margin RANAP IBU TAGIHAN KELAS III (KHUSUS)",4,4,$d->pm_umum_ikutrekening);
    $f->text2column_B("f_tuslah_umum_ikutrekening","<B><font color='green'>16. Tuslah RANAP IBU TAGIHAN KELAS III (KHUSUS)",6,8,$d->tuslah_umum_ikutrekening);

    $f->text2column_A("f_pm_gratis_rj","<B><font color='green'>17. Persen Margin RANAP IBU TAGIHAN KELAS III - VIP",4,4,$d->pm_gratis_rj);
    $f->text2column_B("f_tuslah_gratis_rj","<B><font color='green'>17. Tuslah RANAP IBU TAGIHAN KELAS III - VIP",6,8,$d->tuslah_gratis_rj);
   
    $f->text2column_A("f_pm_gratis_ri","<B><font color='green'>18. Persen Margin RANAP UMUM TAGIHAN KELAS II - I",4,4,$d->pm_gratis_ri);
    $f->text2column_B("f_tuslah_gratis_ri","<B><font color='green'>18. Tuslah RANAP UMUM TAGIHAN KELAS II - I",6,8,$d->tuslah_gratis_ri);

    $f->text2column_A("f_pm_pen_bebas","<B><font color='green'>19. Persen Margin RANAP UMUM TAGIHAN KELAS III",4,4,$d->pm_pen_bebas);
    $f->text2column_B("f_tuslah_pen_bebas","<B><font color='green'>19. Tuslah RANAP UMUM TAGIHAN KELAS III",6,8,$d->tuslah_pen_bebas);
   
    $f->text2column_A("f_pm_nempil","<B><font color='green'>20. Persen Margin ASURANSI",4,4,$d->pm_nempil);
    $f->text2column_B("f_tuslah_nempil","<B><font color='green'>20. Tuslah RAJAL ASURANSI",6,8,$d->tuslah_nempil);

    $f->text2column_A("f_pm_nempil_apt","<B><font color='green'>21. Persen Margin RAJAL RESEP LUAR",4,4,$d->pm_nempil_apt);
    $f->text2column_B("f_tuslah_nempil_apt","<B><font color='green'>21. Tuslah RAJAL RESEP LUAR",6,8,$d->tuslah_nempil_apt);

    $f->submit(" Simpan ");
    $f->execute();
	
} else {
    title("<img src='icon/informasi-2.gif' align='absmiddle' >  Margin Settings");
    echo "<DIV ALIGN=RIGHT><TABLE BORDER=0><FORM ACTION=$SC NAME=Form2><TR>";
    echo "<INPUT TYPE=HIDDEN NAME=p VALUE=$PID>";
    echo "<TD>Pencarian : <INPUT TYPE=TEXT NAME=search VALUE='".$_GET["search"]."'></TD>";
    echo "<TD><input onchange=\"Form2.submit();\" src=\"icon/ico_find.gif\" title=\"Cari\" type=\"image\"> </TD>";

    echo "</TR></FORM></TABLE></DIV>";
    $t = new PgTable($con, "100%");
    $t->SQL = 
        "select b.tdesc, a.margin_id as dummy ".
        "from margin_apotik a ".
        "left join rs00001 b on a.kategori_id = b.tc and tt='GOB'".
        "where margin_id >='0' and (upper(b.tdesc) LIKE '%".strtoupper($_GET["search"])."%'".
        "OR upper(a.kategori_id) LIKE '%".strtoupper($_GET["search"])."%')";

    $t->setlocale("id_ID");
    $t->ShowRowNumber = true;
    $t->ColAlign[1] = "LEFT";
    $t->RowsPerPage = $ROWS_PER_PAGE;
    $t->ColFormatHtml[1] = "<A CLASS=TBL_HREF HREF='$SC?p=$PID&e=<#1#>'>".icon("edit","Edit")."&nbsp;"."<A CLASS=TBL_HREF HREF='actions/848.delete.php?p=$PID".
            "&e=<#1#>".
            "'>".icon("delete","Hapus")."</A>";
    $t->ColHeader = array("Kategori","View");
    $t->execute();
    
    echo "<BR><DIV ALIGN=LEFT><img src=\"icon/user.gif\" align=absmiddle ><A CLASS=SUB_MENU ".
         "HREF='index2.php?p=$PID&e=new'>Tambah Margin Apotik </A></DIV>";
}
}else{
	
	$data = getFromTable("select kategori_id from margin_settings where margin_id='".$_GET[e]."'");

    echo "<div align=center>";
    echo "<form action='actions/848.delete.php' method='get'>";
    echo "<font color=red size=3>PERINGATAN !</font><br>";
    echo "<font class=SUB_MENU>Relasi Apotek <font color=navy>'".$data."'</font> akan Dihapus.</font><br><br>";
    echo "<input type=hidden name=p value=$PID>";
    echo "<input type=hidden name=e value=".$_GET[e].">";
    
    echo "<input type=submit name=sure value='YA'>&nbsp;";
    echo "<input type=submit name=sure value='TIDAK'>";
    echo "</form>";
    echo "</div>";
}

?>

