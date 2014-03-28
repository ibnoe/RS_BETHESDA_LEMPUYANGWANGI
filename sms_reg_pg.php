<?php
//koneksi ke postgresql dan db nya
$db_host = "localhost";
$db_port = 5432;
$db_user = "postgres";
$db_pass = "1234";
$db_name = "onemedic";

$con = pg_connect("host=$db_host port=$db_port dbname=$db_name user=$db_user password=$db_pass");

if (!$con) {
  echo "Oops ada pesan kesalahan.\n";
  exit;
}
// Mengambil pesan yg di dapat dari kannel dan merubah menjadi array
$msg = $_GET['text'];
            $tok = strtok($msg, " ");
            $i=1;
            while ($tok !== false) {
                $token[$i]=$tok;
               // echo "word $i:".$tok."<br/>";
                $tok = strtok(" ");
                $i++;
            }
$phone= $_GET['phone'];	
	
			
 // Cari data berdasarkan request dari sms dengan keyword REG
  if ($token[1] == 'reg')
  {
      // cari jadwal berdasarkan query yang di minta
     $query2 = "SELECT mr_no,nama from rs00002 where mr_no = '$token[2]'";
	 $hasil2 = pg_query($query2);
     $data2 = pg_fetch_array($hasil2);
     $nama = $data2['nama'];
	 	 
	 // cek bila data pasien tidak ditemukan
     if (pg_num_rows($hasil2) == 0) 
		{
		$reply = "Data pasien dengan nomor MR " .$token[2]. " tidak ada, hanya untuk pasien lama";
		}	
	 else
     {
        // bila MR sudah terdaftar
		$poli = $token[3];
		$query1 = "INSERT INTO sms_reg(nama,nohp,rawatan,mr_no) VALUES ('$nama',$phone,'$poli','$token[2]')";     
		$hasil4 = pg_query($query1);
		$reply =  "Anda telah terdaftar dengan nama:  " .$nama. " di poli " .$poli;
		
     }
  }
  else 
  $reply = "Maaf perintah salah, silakan ketik reg NOMORMR poli";
  echo $reply;
 
 ?>