<? // Nugraha, 29/03/2004
   // sfdn, 22-04-2004
   // sfdn, 24-12-2006
	// sfdn, 27-12-2006

if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "igd" || $_SESSION[uid] == "root") {

$PID = "120";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
     
if (strlen($_GET["registered"]) == 0) $_GET["registered"] = "Y";
   title(" <img src='icon/informasi-2.gif' align='absmiddle' > PENDAFTARAN PASIEN ");

echo "<br>";

$f = new Form($SC, "GET", "NAME=Form2");
$f->hidden("p",$PID);
$f->selectArray("registered", "Pasien",
    Array("Y" => "Data Sudah Ada", "N" => "Data Belum Ada"),
    $_GET["registered"],"onChange=\"Form2.submit();\"");
$f->hidden("q","search");
if ($_GET["registered"] == "Y" && $_GET["q"] != "reg") {
    $f->text("search","Pencarian",40,40,$_GET["search"]);
    $f->submit(" Cari ");
}
$f->execute();

if ($_GET["q"] == "reg") {
    if ($_GET["mr_no"] != "new") {
        $r = pg_query($con, "select * from rs00002 where mr_no = '".$_GET["mr_no"]."'");
        $n = pg_num_rows($r);
        if($n > 0) $d = pg_fetch_object($r);
        pg_free_result($r);

		
        $f = new Form("actions/120.insert.php", "POST", "NAME=Form1");
        $f->hidden("p",$PID);
        $f->hidden("f_mr_no",$d->mr_no);
		
        $f->subtitle("Pasien");
        $f->text("mr_no","No.MR",12,12,$d->mr_no,"DISABLED");
        $f->text("f_nama","Nama",50,50,$d->nama,"DISABLED");
        $f->text("f_nama_keluarga","Nama Keluarga",50,50,"$d->nama_keluarga","DISABLED");
        $f->text("f_tmp_lahir","Tempat Lahir",50,50,"$d->tmp_lahir","DISABLED");
        $f->text("f_tgl_lahir","Tanggal Lahir",50,50,date("d M Y", pgsql2mktime($d->tgl_lahir)),"DISABLED");
        $f->text("f_umur","Umur",5,3,$d->umur,"DISABLED");
        $f->text("f_alm_tetap","Alamat Tetap",50,150,"$d->alm_tetap, $d->kota_tetap, $d->pos_tetap","DISABLED");
        $f->text("f_tlp_tetap","Telepon",15,15,$d->tlp_tetap,"DISABLED");
        $f->text("f_no_ktp","Nomor KTP/SIM/KTA",50,50,"$d->no_ktp","DISABLED"); 
		$f->text("f_pangkat_gol","Pangkat/Golongan ",50,50,"$d->pangkat_gol","DISABLED");
		$f->text("f_nrp_nip","NRP/NIP ",50,50,"$d->nrp_nip","DISABLED");
		$f->text("f_kesatuan","Kesatuan/Instansi ",50,50,"$d->kesatuan","DISABLED"); 
	
        $f->subtitle("Registrasi");
        $f->text("no_reg","No. Registrasi",12,12,"<OTOMATIS>","DISABLED");
        
        if ($_SESSION[uid] == "igd") {

        $f->selectArray("f_rawat_inap", "Rawatan",Array("N" => "IGD"),"N", "OnChange=\"setPoli(this.value);\"");
        $f->PgConn = $con;
		$f->selectSQL("f_poli", "Poli","select '' as tc, '-' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' order by tdesc ","", "DISABLED");

        } elseif ($_SESSION[uid] == "daftar") {

        $f->selectArray("f_rawat_inap", "Rawatan",Array("Y" => "Rawat Jalan"),"Y", "OnChange=\"setPoli(this.value);\"");
        $f->PgConn = $con;
        $f->selectSQL("f_poli", "Poli","select '' as tc, '-' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' order by tdesc ","", "");
        } else {

        $f->selectArray("f_rawat_inap", "Rawatan",Array("Y" => "Rawat Jalan", "N" => "IGD"),
                        "N", "OnChange=\"setPoli(this.value);\"");
        $f->PgConn = $con;
		$f->selectSQL("f_poli", "Poli","select '' as tc, '-' as tdesc union ".
						"SELECT tc,tdesc FROM rs00001 WHERE tt = 'LYN' order by tdesc ","", "DISABLED");
        }

        $f->selectArray("f_rujukan", "Jenis Kedatangan",Array("N" => "Non Rujukan", "Y" => "Rujukan"),
                        "N", "OnChange=\"setRujukan(this.value);\"");
		$f->selectSQL("f_rujukan_rs_id", "Rumah Sakit Perujuk","select '' as tc, '' as tdesc union ".
                      "select tc, tdesc from rs00001 where tt = 'RUJ' and tc != '000'","", "DISABLED");
        $f->text("f_rujukan_dokter","Dokter Perujuk",50,50,"","DISABLED");        
        $f->selectSQL("f_id_penanggung", "Penanggung","select tc, tdesc from rs00001 where tt = 'PEN' and tc != '000'",
                      "001","OnChange=\"setNmPenanggung(this.value);\"");                      
        $f->text("f_nm_penanggung","Nama Penanggung",50,50,"","DISABLED");
        $f->text("f_hub_penanggung","Hubungan Dengan Pasien",50,50,"","DISABLED");                      
        $f->selectSQL("f_id_penjamin", "Penjamin","select tc, tdesc from rs00001 where tt = 'PJN' and tc != '000'","999");
        $f->selectSQL("f_tipe", "Tipe Pasien","select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000'","$d->tipe_pasien","disabled");
        $f->textarea("f_diagnosa_sementara", "Diagnosa Sementara", 4, 50, "");
        $f->hidden("f_status_akhir_pasien","-");
        $f->submit(" Registrasi ");
        $f->execute();
        
        if ($_GET[err] == 1) {
           echo "<br><font color=red>ERROR: Poli belum dipilih.</font>";
        }
        ?>

        <SCRIPT language="JavaScript">
            document.Form1.f_rujukan_rs_id.selectedIndex = -1;

            function setRujukan( v )
            {
                document.Form1.f_rujukan_rs_id.disabled = v == "N";
                document.Form1.f_rujukan_dokter.disabled = v == "N";
                document.Form1.f_rujukan_dokter.value = v == "N" ? "" : document.Form1.f_rujukan_dokter.value;
                document.Form1.f_rujukan_rs_id.selectedIndex = document.Form1.f_rujukan_rs_id.selectedIndex == -1 && v == "Y" ? 0 : v == "Y" ? document.Form1.f_rujukan_rs_id.selectedIndex : -1;
            }
        </SCRIPT>
        <SCRIPT language="JavaScript">
            document.Form1.f_poli.selectedIndex = -1;
            function setPoli( v )
            {
                document.Form1.f_poli.disabled = v == "N";
                document.Form1.f_poli.selectedIndex = document.Form1.f_poli.selectedIndex == -1 && v == "Y" ? 0 : v == "Y" ? document.Form1.f_poli.selectedIndex : -1;
            }
        </SCRIPT>
        
        <SCRIPT language="JavaScript">

            function setNmPenanggung( v )
            {
                  document.Form1.f_nm_penanggung.disabled = v == "001";
                  document.Form1.f_hub_penanggung.disabled = v == "001";

            }
        </SCRIPT>		
        <?php
    }
} else {
    if ($_GET["registered"] == "N") {
        $f = new Form("actions/110.insert.php", "POST", "NAME=Form1");
        $f->subtitle("Identitas");
        $f->hidden("mr_no","new");
        $f->hidden("p",$PID);
        $f->text("mr_no","No.MR",12,12,"<OTOMATIS>","DISABLED");
        //$f->text("no_reg","No. Registrasi",12,12,"<OTOMATIS>","DISABLED");
        $f->PgConn = $con;
        $f->text("f_nama","Nama",40,50,"");
        $f->text("f_nama_keluarga","Nama Keluarga",40,50,"");
        $f->selectArray("f_jenis_kelamin", "Jenis Kelamin",Array("L" => "Laki-laki", "P" => "Perempuan"),"");
        $f->text("f_tmp_lahir","Tempat Lahir",40,40,"");
        $f->selectDate("f_tgl_lahir", "Tanggal Lahir", getdate());
        //$f->text("f_umur", "(Umur)", 5,3,"","disabled");
        $f->selectSQL("f_agama_id", "Agama","select tc, tdesc from rs00001 where tt = 'AGM' and tc != '000'",$d->agama_id);
        $f->text("f_no_ktp","Nomor KTP/SIM/KTA",50,50,"","");
        $f->text("f_pangkat_gol","Pangkat/Golongan ",50,50,"","");
        $f->text("f_nrp_nip","NRP/NIP ",50,50,"","");
        $f->text("f_kesatuan","Kesatuan/Instansi ",50,50,"","");
		$f->selectSQL("f_status_nikah", "Status Pernikahan",
					  "select tc, tdesc from rs00001 where tt = 'SNP' and tc != '000'","002");
 		$f->selectSQL("f_gol_darah", "Golongan Darah","select '' as tc, '' as tdesc union ".
 					  "select tc, tdesc from rs00001 where tt = 'GOL' and tc != '000'","");
    	$f->selectSQL("f_resus_faktor", "Resus Faktor","select '' as tc, '' as tdesc union ".
    				  "select tc, tdesc from rs00001 where tt = 'REF' and tc != '000'","");                                 
		$f->text("f_nama_ibu","Nama Ibu",50,50,"","");
		$f->text("f_nama_ayah","Nama Ayah ",50,50,"",""); 
		$f->text("f_pekerjaan","Pekerjaan OrangTua",50,50,"","");
     
        $f->subtitle("Alamat Tetap");
        $f->text("f_alm_tetap","Alamat",50,50,"");
        // sfdn, 24-12-2006
		$f->text("f_kota_tetap","Kota",50,50,"Bandung");
        $f->text("f_pos_tetap","Kode Pos",5,5,"");
        $f->text("f_tlp_tetap","Telepon",15,15,"");
        
        $f->subtitle("Keluarga Dekat");
        $f->text("f_keluarga_dekat","Nama",50,50,"");
        $f->text("f_alm_keluarga","Alamat",50,50,"");
        $f->text("f_kota_keluarga","Kota",50,50,"");
        $f->text("f_pos_keluarga","Kode Pos",5,5,"");
        $f->text("f_tlp_keluarga","Telepon",15,15,"");
        

        $f->hidden("f_alm_sementara","");
        $f->hidden("f_kota_sementara","");
        $f->hidden("f_pos_sementara","");
        $f->hidden("f_tlp_sementara","");

		$f->subtitle("KARTU BEROBAT");
		// $f->hidden("f_tgl_reg",date("Y-d-m"));
        $f->selectSQL("f_tipe_pasien", "Tipe Pasien",
        			  "select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000' order by tc asc","001");                      
        $f->selectArray("cek_printer", "CETAK KARTU BEROBAT ? ",Array("Y" => "CETAK", "N" => "TIDAK DI CETAK "),"N");
        $f->submit(" Registrasi ");
        $f->execute();
    }

    if ($_GET["registered"] == "Y" && $_GET["q"] == "search" && strlen($_GET["search"]) > 0) {
        $t = new PgTable($con, "100%");
 
 	$t->SQL = "select a.mr_no,a.nama,a.pangkat_gol,a.nrp_nip,a.kesatuan,a.nama_keluarga,a.alm_tetap,a.kota_tetap, ".
 			  "case when (select x.statusbayar from rsv0012 x where x.mr_no = a.mr_no AND x.id = (select max(d.id) from rs00006 d where d.mr_no = a.mr_no)) ".
			  "	 <> 'LUNAS' ".
			  "	then 'MASIH DIRAWAT' else 'BOLEH BEROBAT' end as akhir, ".
			  " a.mr_no as href ".
			  "FROM rs00002 a ".
			  "where upper(a.nama) LIKE '%".strtoupper($_GET["search"])."%' ".
              "OR a.mr_no LIKE '%".$_GET["search"]."%' ".
              "OR upper(a.alm_tetap) like '%".strtoupper($_GET["search"])."%' ".
              "OR upper(a.kesatuan) like '%".strtoupper($_GET["search"])."%' ".
              "OR upper(a.pangkat_gol) like '%".strtoupper($_GET["search"])."%'" ;
        
	$t->ColHeader = array("NO.MR","NAMA","PANGKAT","NRP/NIP","KESATUAN","NAMA KELUARGA","ALAMAT","KOTA","STATUS","&nbsp;");
	$t->ShowRowNumber = true;
    $t->ColAlign[0] = "CENTER";
    $t->ColAlign[9] = "CENTER";
    $t->RowsPerPage = 30;
    $t->DisableStatusBar = true;
	// sfdn, 27-12-2006 -> hanya pembetulan baris
	$t->ColFormatHtml[9] = "<nobr>
						    <A CLASS=TBL_HREF "."HREF='$SC?p=110&e=<#9#>'>".icon("edit","Edit")."</A> &nbsp; ".
                		   "<A CLASS=TBL_HREF "."HREF='$SC?p=$PID&q=reg&mr_no=<#9#>'>".icon("ok","Registrasi")."</A>
                		   </nobr>";
	// --- eof 27-12-2006
	$t->execute();
    }
}


} // end of $_SESSION[uid] == daftar || igd
?>
