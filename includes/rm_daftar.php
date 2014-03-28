<? 
//  hery-- july 16, 2007 


$PID = "rm_daftar";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
     
   title(" <img src='icon/informasi-2.gif' align='absmiddle' > REGISTRASI PASIEN BAYI LAHIR");

//echo "<br> belum jadi";
$tglhariini = substr(date("Y-m-d", time()),0,10);
        $f = new Form("actions/110.insert.php", "POST", "NAME=Form1");
        $f->subtitle("Identitas");
        $f->hidden("mr_no","new");
        $f->hidden("p",$PID);
        $f->PgConn = $con;
        
        $f->text("mr_no","MR No",12,12,"<OTOMATIS>","DISABLED");
        $f->text("f_nama_ibu","Nama Ibu",50,50,"","");
        $f->text("f_nama_ayah","Nama Ayah ",50,50,"",""); 
		$f->text("f_pekerjaan","Pekerjaan OrangTua",50,50,"",""); 
		
        $f->text("f_nama","Nama",40,50,"");
        //$f->text("f_nama_keluarga","Nama Keluarga",40,50,"");
        $f->selectArray("f_jenis_kelamin", "Jenis Kelamin",
                        Array("L" => "Laki-laki", "P" => "Perempuan"),
                        "");
        $f->text("f_tmp_lahir","Tempat Lahir",40,40,"");
        //$f->selectDate("f_tgl_lahir", "Tanggal Lahir", getdate());
        $f->calendar("f_tgl_lahir","Tanggal Lahir",10,10,date("d-m-Y", time()),"Form1","icon/calendar.gif","Pilih Tanggal","" );
        //$f->text("f_umur", "(Umur)", 5,3,"","disabled");
        $f->selectSQL("f_agama_id", "Agama","select tc, tdesc from rs00001 where tt = 'AGM' and tc != '000'","");
        $f->text("f_no_ktp","Nomor KTP/SIM/KTA",50,50,"",""); 
		$f->text("f_pangkat_gol","Pangkat/Golongan/NRP/NIP ",50,50,"","");
		$f->text("f_kesatuan","Kesatuan/Instansi ",50,50,"","");
        $f->selectArray("f_status_nikah", "Status Pernikahan",
                        Array("1" => "Blm Menikah", "2" => "Menikah", "3" => "Janda", "4" => "Duda"),
                        "");
        $f->selectArray("f_gol_darah", "Golongan Darah",
                        Array("-" => "-","A" => "A", "B" => "B", "AB" => "AB", "O" => "O"),
                        "");  
        $f->selectArray("f_resus_faktor", "Resus Faktor",
                        Array("0" => "-","1" => "Negatif", "2" => "Positif"),
                        "");                                              
		    
        $f->subtitle("Alamat Tetap");
        $f->text("f_alm_tetap","Alamat",50,50,"");
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
		$f->selectSQL("f_tipe_pasien", "Tipe Pasien",
                      "select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000' order by tc asc",
                      "001");
        $f->selectArray("cek_printer", "CETAK KARTU BEROBAT ? ",
                        Array("Y" => "CETAK", "N" => "TIDAK DI CETAK "),
                        "N"); 
		$f->submit(" Registrasi ");
        $f->execute();
    
  
?>
