<script language='JavaScript'>
	function selectLaporan() {
	   sWin = window.open('popup/laporan_ri.php', 'xWin', 'top=0,left=0,width=500,height=650,menubar=no,scrollbars=yes')
	   sWin.focus()
	}
</script>
<?
// app, 08-09-2007
$PID = "p_layanan_rawat_inap";
$SC = $_SERVER["SCRIPT_NAME"];
session_start();

require_once("startup.php");
require_once("lib/visit_setting.php");

set_time_limit(60);

function color( $dstr, $r ) {
	    //if ($dstr[7] == '-') {
	    	if ($dstr[4] == 'Sudah Keluar' ){
	    		return "<font color=RED>{$dstr[$r]}</font>";
	    	}elseif ($dstr[4] == 'Masih Dirawat'){
	    		return "<font color=BLUE>{$dstr[$r]}</font>";
	    	}
	    //}else return $dstr[$i];
}

if (!empty($_SESSION[uid])) {

require_once("startup.php");
require_once("lib/visit_setting.php");
if(!$GLOBALS['print']){
		title_print("<img src='icon/rawat-inap-2.gif' align='absmiddle' >  LAYANAN RAWAT INAP");
                
		}
if(!$GLOBALS['print']){
    echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=p_layanan_rawat_inap1&rg={$_GET["rg"]}&mr={$_GET["mr"]}&rg1={$_GET["rg1"]}'>".icon("back","Kembali")."</a></DIV>";
    echo"<br>";
}
$laporan = getFromTable("select tdesc from rs00001 where tt='LRI' and tc = '".$_SESSION[SELECT_LAP]."'");
	$F = new Form($SC."?p=".$PID,"POST","name='frmTst'");
	$F->textAndButton("btn_laporan","Hasil Pemeriksaan",50,50,$laporan,$ext,"...","OnClick='selectLaporan();';");
	$F->execute();	
$ri=$_SESSION[SELECT_LAP];
		echo "<div align='right' valign='middle'>";	
		echo "<table width='100%' border=0 cellpadding=2 cellspacing=2><tr><td>";
		echo "<div align='right' valign='middle'>";	
		$f = new Form($SC, "GET","NAME=Form2");
                echo "<DIV ALIGN=RIGHT><A HREF='$SC?p=p_layanan_rawat_inap1>".icon("back","Kembali")."</a></DIV>";
	    $f->hidden("p", $PID);
	    $f->hidden("ri",$ri);
	    
	    if (!$GLOBALS['print']){
	    	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","OnChange='Form2.submit();'");
                
		}else { 
		   	$f->search("search","Pencarian",20,20,$_GET["search"],"icon/ico_find.gif","Cari","disabled");
		}
            
	    $f->execute();
    	if ($msg) errmsg("Error:", $msg);
    	echo "</div>";
		//---------------------
		echo "<br>";
		if ($_SESSION[gr]=="PARKIT"){
			$bangsal = "PARKIT";
		}elseif($_SESSION[gr]=="PERWIRA"){
			$bangsal = "PERWIRA";
		}elseif ($_SESSION[gr]=="MERAK"){
			$bangsal = "MERAK";
		}elseif ($_SESSION[gr]=="KUTILANG"){
			$bangsal = "KUTILANG";
		}elseif ($_SESSION[gr]=="MERPATI"){
			$bangsal = "MERPATI";	
		}elseif($_SESSION[gr]=="CENDRA"){
			$bangsal = "CENDRAWASIH";
		}elseif($_SESSION[gr]=="GELATIK"){
			$bangsal = "GELATIK";
		}elseif($_SESSION[gr]=="ICU"){
			$bangsal = "ICU";
                }elseif($_SESSION[gr]=="VVIP"){
 			$bangsal = "MATO";	
		}else{
			$bangsal = "";
		}
                        $SQLSTR = "select b.mr_no, a.no_reg, upper(b.nama)as nama, ".
                                  "    f.bangsal || ' / ' || e.bangsal|| ' / ' || i.tdesc || ' / ' || d.bangsal as bangsal, 
                                        case when c.status = 'P' then 'Sudah Keluar' else 'Masih Dirawat' end as status ".
                                  " from rs00010 as a 
                                   ".
                                  "    join rs00006 as c on a.no_reg = c.id ".
                                  "    join rs00002 as b on c.mr_no = b.mr_no ".
                                  "    join rs00012 as d on a.bangsal_id = d.id ".
                                  "    join rs00012 as e on e.hierarchy = substr(d.hierarchy,1,6) || '000000000' ".
                                  "    join rs00012 as f on f.hierarchy = substr(d.hierarchy,1,3) || '000000000000' ".
				  "    join rs00001 i on i.tc = e.klasifikasi_tarif_id and i.tt='KTR' ".
                                   "	 left join rs00001 g on g.tc = b.tipe_pasien and g.tt='JEP' ".
                                  " where a.ts_calc_stop is null
                                    "; 
//			$SQLTR = "select f.mr_no,f.id,upper(f.nama)as nama,
//					 d.bangsal || ' / ' || c.bangsal|| ' / ' || b.bangsal as bangsal,
//					 case when f.status = 'P' then 'Sudah Keluar' else 'Masih Dirawat' end as status
//					 from rs00010 a
//					 left join rsv_pasien2 f on a.no_reg=f.id
//					 join rs00012 as b on a.bangsal_id = b.id 
//					 join rs00012 as c on c.hierarchy = substr(b.hierarchy,1,6) || '000000000' 
//					 join rs00012 as d on d.hierarchy = substr(b.hierarchy,1,3) || '000000000000' 
//					 join rs00001 as e on c.klasifikasi_tarif_id = e.tc and e.tt = 'KTR' and d.bangsal like '%$bangsal%' 
//                                          ";
//			
//			if (strlen($_GET["mPOLI"]) > 0 ) {
//		$SQLWHERE =
//			//"AND TANGGAL_REG = '$tglhariini' AND".
//			"and (UPPER(NAMA) LIKE '%".strtoupper($_GET["search"])."%')  ";
//	}
//	if ($_GET["search"]) {
//		$SQLWHERE =
//			"and (upper(nama) LIKE '%".strtoupper($_GET["search"])."%' or f.id like '%".$_GET['search']."%' or mr_no like '%".$_GET["search"]."%' ".
//					" or upper(pangkat_gol) like '%".strtoupper($_GET["search"])."%' or nrp_nip like '%".$_GET['search']."%' ".
//					" or upper(kesatuan) like '%".strtoupper($_GET["search"])."%' )   ";
//	}
//	if (!isset($_GET[sort])) {
//
//           $_GET[sort] = "f.status,f.id";
//           $_GET[order] = "desc";
//	}
	//echo "$SQLTR,$SQLWHERE";
			$rstr=pg_query($con, "$SQLSTR ");	    	
			$dstr = pg_fetch_array($rstr);
			$t = new PgTable($con,"100%");
			$t->SQL = " $SQLSTR and a.no_reg='".$_GET["rg1"]."' and b.mr_no='".$_GET["mr"]."' group by c.status,b.mr_no,i.tdesc,a.no_reg, b.nama,a.ts_check_in,g.tdesc,b.alm_tetap,f.bangsal,e.bangsal,d.bangsal ";
			$t->ShowRowNumber = true;
			$t->RowsPerPage = 10;
			$t->ColHeader = array("NO.RM","NO REGISTRASI","NAMA PASIEN","BANGSAL","STATUS");
			$t->ColAlign = array("center","center","left","left","center");
			$t->ColColor[4] = "color";
			
			if ($ri == $setting_ri["riwayat_penyakit_pemeriksaan_fisik"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_riwayat_penyakit&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>&list=layanan&sub2=nonpaket'><#2#></A>";
			}elseif($ri == $setting_ri["catatan_riwayat_kebidanan"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_catatan_kebidanan&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["catatan_bayi"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_catatan_bayi&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["resume_dewasa_anak"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_resume_dewasa_anak&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["resume_kebidanan"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_resume_keb&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["resume_bayi"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_resume_bayi&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["ringkasan_masuk_keluar"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_ringkasan_masuk_keluar&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["dokumen_surat_pengantar"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_dokumen_surat_pengantar&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["catatan_harian_penyakit"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_catatan_harian&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["catatan_perkembangan_bayi"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_catatan_perkembangan_bayi&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["laporan_pembedahan"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_laporan_pembedahan&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["asuhan_keperawatan"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_asuhan_keperawatan&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["proses_keperawatan"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_proses_keperawatan&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["catatan_obstetri"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_catatan_obstetrik&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["lembar_konsultasi"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_lembar_konsultasi&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["hasil_laboratorium"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_hasil_laboratorium&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["hasil_radiologi"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_hasil_radiologi&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["hasil_EKG"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_hasil_ekg&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["hasil_USG"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_hasil_usg&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["pengawasan_pasien_anak"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_pengawasan_anak&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["pengawasan_pasien_dewasa"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_pengawasan_dewasa&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["laporan_pemakaian_alat"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_pemakaian_alat_pembedahan&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["grafik_suhu"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_grafik_suhu&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["grafik_ibu"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_grafik_ibu&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["grafik_bayi"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_grafik_bayi&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}elseif($ri == $setting_ri["pemakaian_alat_keperawatan"]){
			$t->ColFormatHtml[2] = "<A CLASS=SUB_MENU1 HREF='$SC?p=p_pemakaian_alat_keperawatan&rg=<#1#>&mr=<#0#>&ri=$ri&rg1=<#1#>'><#2#></A>";
			}
			$t->execute();
		
		echo "</td></tr></table>";

}

?>