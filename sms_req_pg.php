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
// Mengambil pesan yg di dapat dari kannel dan merubah jadi kapital
$msg = $_GET['text'];
            $tok = strtok($msg, " ");
            $i=1;
            while ($tok !== false) {
                $token[$i]=$tok;
                //echo "word $i:".$tok."<br/>";
                $tok = strtok(" ");
                $i++;
            }
			
 // Cari data berdasarkan request dari sms
  if ($i == 3 && $token[1] == 'jadwal')
  {
      // cari jadwal berdasarkan query yang di minta
     $query2 = "select a.nama, 
                case when b.tempat_bangsal = '' and b.tempat_poli != '' then c.tdesc 
		     when b.tempat_bangsal != '' and b.tempat_poli = '' then d.bangsal 
                     when b.tempat = 'I' then 'IGD' 
                     when b.tempat = 'K' then 'Kantor' 
                else 'Non-Medis' end as praktek 
	        from rs00017 as a , hrd_absen as b 
	        left outer join hrd_status f ON b.status = f.code 
	        left outer join hrd_shift e ON b.shift = e.code 
	        left outer join rs00012 d ON b.tempat_bangsal = d.hierarchy 
	        left outer join rs00001 c ON b.tempat_poli = c.tc and c.tt='LYN' 
                where  
                  a.id = b.id_pegawai 
		and a.nama = '$token[2]'";
     $hasil2 = pg_query($query2);
 
     // cek bila data nilai tidak ditemukan
     if (pg_num_rows($hasil2) == 0) 
		{
		$reply = "Data dengan nama dokter " .$token[2]. " tidak ada";
		}	
	 else
     {
        // bila nilai ditemukan
        $data2 = pg_fetch_array($hasil2);
        $reply2 = $data2['nama'];
		$reply3 = $data2['praktek'];
        $reply = " ".$reply2." berada di ".$reply3;
     }
  }
  else 
  $reply = "Maaf perintah salah, silakan ketik jadwal nama dokter";
  echo $reply;
 
 ?>