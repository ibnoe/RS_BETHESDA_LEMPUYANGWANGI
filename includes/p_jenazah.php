<?php 	// Nugraha, Sun Apr 18 18:58:42 WIT 2004
      	// sfdn, 22-04-2004: hanya merubah beberapa title
      	// sfdn, 23-04-2004: tambah harga obat
      	// sfdn, 30-04-2004
      	// sfdn, 09-05-2004
      	// sfdn, 18-05-2004: age
      	// sfdn, 02-06-2004
      	// Nugraha, Sun Jun  6 18:14:41 WIT 2004 : Paket Transaksi
      	// sfdn, 24-12-2006 --> layanan hanya diberikan kpd. pasien yang blm. lunas
		// rs00006.is_bayar = 'N'
		// sfdn, 27-12-2006
		// App,02-06-2007 --> Developer

session_start();
$PID = "p_jenazah";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");
require_once("lib/visit_setting.php");
title("<img src='icon/rawat-jalan-2.gif' align='absmiddle' >  INSTALASI JENAZAH");

//--fungsi column color-------------- 
$f = new Form("actions/p_psikologi.insert.php", "POST", "NAME=Form2");
					$f->hidden("act","new");
					$f->hidden("f_no_reg",$d->id);
					$f->hidden("list","pemeriksaan");
				    $f->hidden("mr",$_GET["mr"]);
				    $f->hidden("f_id_poli",$_GET["poli"]);
				    $f->hidden("f_user_id",$_SESSION[uid]);
				//$f->calendar("tanggal_reg","Tanggal Registrasi",15,15,$d2[1],"Form2","icon/calendar.gif","Pilih Tanggal",$ext);
					
				    echo "<table border=1 width='100%' cellspacing=0 cellpadding=0><tr><td valign=top width='100%'>";
					echo"<div align=left class=FORM_SUBTITLE1><U>KETERANGAN JENAZAH</U></div>";
					//unset($_SESSION["SELECT_EMP"]);
					if (isset($_SESSION["SELECT_EMP"])) {
    					$_SESSION["DOKTER"]["id"] = $_SESSION["SELECT_EMP"];
    					$_SESSION["DOKTER"]["nama"] =
        				getFromTable(
			            "select nama from rs00017 where id = '".$_SESSION["DOKTER"]["id"]."'");
			            
			            $f->textAndButton3("f_id_dokter","Dokter Pemeriksa",2,10,$_SESSION["DOKTER"]["id"],$ext,"nm2",30,70,$_SESSION["DOKTER"]["nama"],$ext,"...",$ext,"OnClick='selectPegawai();';");	
			            
    					unset($_SESSION["SELECT_EMP"]);
					}elseif ($d2["id_dokter"] != '') {
							$f->textAndButton3("f_id_dokter","Dokter Pemeriksa",2,10,$d2["id_dokter"],$ext,"nm2",30,70,$d2["nama"],$ext,"...",$ext,"OnClick='selectPegawai();';");
					}else{
						$f->text("no_reg","No Reg Jenazah",13, $visit_jantung["vis_".$i."W"],ucfirst($d2[2+$i]),$ext);
						$f->textAndButton3("f_id_dokter","Petugas Kamar Jenazah",2,10,0,$ext,"nm2",30,70,$d2["nama"],$ext,"...",$ext,"OnClick='selectPegawai();';");
					echo "&nbsp;  Asal Jenazah  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp;  &nbsp; &nbsp;&nbsp; &nbsp;  &nbsp; :&nbsp;<select><option>Rumah Sakit</option><option>Luar</option></select>";}
					
					
					$max = count($visit_jantung) ; 
					$i = 1;
					
					$f->text("nama_jen".$i,"Nama Jenazah",50, $visit_jantung["vis_".$i."W"],ucfirst($d2[2+$i]),$ext);
					$f->text("jenis_kel".$i,"Jenis Kelamin" ,50, $visit_jantung["vis_".$i."W"],ucfirst($d2[2+$i]),$ext);
						$f->text("umur".$i,"Umur" ,50, $visit_jantung["vis_".$i."W"],ucfirst($d2[2+$i]),$ext);
						$f->text("agama".$i,"Agama" ,50, $visit_jantung["vis_".$i."W"],ucfirst($d2[2+$i]),$ext);
						$f->textarea("alamat".$i,"Alamat" ,1, $visit_jantung["vis_".$i."W"],ucfirst($d2[2+$i]),$ext);
						$f->text("tgl_msk".$i,"Tanggal Masuk" ,50, $visit_jantung["vis_".$i."W"],ucfirst($d2[2+$i]),$ext);
						$f->text("jam_msk".$i,"Jam Masuk" ,50, $visit_jantung["vis_".$i."W"],ucfirst($d2[2+$i]),$ext);
						$f->text("tgl_keluar".$i,"Tanggal Keluar" ,50, $visit_jantung["vis_".$i."W"],ucfirst($d2[2+$i]),$ext);
						$f->text("jam_keluar".$i,"Jam Keluar" ,50, $visit_jantung["vis_".$i."W"],ucfirst($d2[2+$i]),$ext);
						$f->textarea("diagnosa".$i,"Diagnosa" ,1, $visit_jantung["vis_".$i."W"],ucfirst($d2[2+$i]),$ext);
					//$f->PgConn=$con;
					//$f->selectSQL("f_status_akhir","Status Akhir Pasien", "select '' as tc, '' as tdesc union select tc , tdesc from rs00001 where tt='SAP' and tc not in ('000')", ($d2["status_akhir"]),$ext);
					$f->submitAndCancel("Simpan",$ext,"Batal","window.history.back()",$ext);
					$f->execute();
					echo"</div>";
			
    	
  
    
    //pemeriksaan
    
    
   		

  
?>
