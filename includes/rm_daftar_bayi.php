<? 
//  hery-- Sept 16, 2007 


$PID = "rm_daftar_bayi";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
     
   title(" <img src='icon/informasi-2.gif' align='absmiddle' > REGISTRASI PASIEN BAYI LAHIR");


$tglhariini = substr(date("Y-m-d", time()),0,10);
        $f = new Form("actions/110b.insert.php", "POST", "NAME=Form1");
        $f->subtitle("Identitas");
        $f->hidden("mr_no","new");
       // $f->hidden("is_bayi","Y");
        $f->hidden("p",$PID);
        $f->PgConn = $con;
        
        $f->text("mr_no","MR No",12,12,"<OTOMATIS>","DISABLED");
        $f->text("f_nama","Nama Bayi",40,50,"");
        $f->selectArray("f_jenis_kelamin", "Jenis Kelamin",
                        Array("L" => "Laki-laki", "P" => "Perempuan"),
                        "");
        $f->text("f_tmp_lahir","Tempat Lahir",40,40,"");
        $f->selectDate("f_tgl_lahir", "Tanggal Lahir", getdate());
        
        $f->subtitle("Identitas Orangtua");
        $f->text("f_mr_no_ibu","No.MR Ibu",12,12,"","");
        $f->text("f_nama_ibu","Nama Ibu",50,50,"","");
        $f->text("f_nama_ayah","Nama Ayah ",50,50,"",""); 
		$f->text("f_pekerjaan","Pekerjaan OrangTua",50,50,"",""); 
		$f->selectSQL("f_agama_id", "Agama","select tc, tdesc from rs00001 where tt = 'AGM' and tc != '000'","");
        $f->text("f_no_ktp","Nomor KTP/SIM/KTA",50,50,"",""); 
		$f->text("f_pangkat_gol","Pangkat/Golongan/NRP/NIP ",50,50,"","");
		$f->text("f_kesatuan","Kesatuan/Instansi ",50,50,"","");
        $f->selectArray("f_gol_darah", "Golongan Darah",
                        Array("-" => "-","A" => "A", "B" => "B", "AB" => "AB", "O" => "O"),
                        "");  
        $f->selectArray("f_resus_faktor", "Resus Faktor",
                        Array("0" => "-","1" => "Negatif", "2" => "Positif"),
                        "");                                              
		    
        
		$f->subtitle("KARTU BEROBAT");
		$f->selectSQL("f_tipe_pasien", "Tipe Pasien",
                      "select tc, tdesc from rs00001 where tt = 'JEP' and tc != '000' order by tc asc",
                      "001");
        $f->selectArray("cek_printer", "CETAK KARTU BEROBAT ? ",
                        Array("Y" => "CETAK", "N" => "TIDAK DI CETAK "),
                        "N"); 
		$f->submit(" Registrasi ");
        $f->execute();
    
  //} else {
 

//}
        
?>
