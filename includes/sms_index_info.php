<? // Nugraha, 17/02/2004
   // Pur, 08/03/2004: new libs table
   // sfdn, 22-04-2004
   // sfdn, 23-04-2004
   // sfdn, 01-05-2004
   // sfdn, 01-06-2004
   // sfdn, 24-12-2006	

$PID = "sms_index";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("startup.php");

// query untuk membaca SMS yang belum diproses
$query = "SELECT * FROM inbox WHERE Processed = 'false'";
$hasil = pg_query($query);
while ($data = pg_fetch_array($hasil))
{
  // membaca ID SMS
  $id = $data['ID'];
 
  // membaca no pengirim
  $noPengirim = $data['SenderNumber'];
 
  // membaca pesan SMS dan mengubahnya menjadi kapital
  $msg = strtoupper($data['TextDecoded']);
 
  // proses parsing 
 
  // memecah pesan berdasarkan karakter <spasi>
  $pecah = explode(" ", $msg);
 
  // jika kata terdepan dari SMS adalah 'RUANG' maka cari Bangsal untuk nama pasien tertentu
  if ($pecah[0] == "RUANG")
  {
     // baca NIM dari pesan SMS
     $nama = $pecah[1];
 
     // cari RUANG RAWAT INAP berdasarkan nama pasien
     $query2 = "select f.bangsal || ' / ' || e.bangsal|| ' / ' || d.bangsal as bangsal 
          from rs00010 as a 
              join rs00006 as c on a.no_reg = c.id 
              join rs00002 as b on c.mr_no = b.mr_no 
              join rs00012 as d on a.bangsal_id = d.id 
              join rs00012 as e on e.hierarchy = substr(d.hierarchy,1,6) || '000000000' 
              join rs00012 as f on f.hierarchy = substr(d.hierarchy,1,3) || '000000000000' 
	   	 left join rs00001 g on g.tc = b.tipe_pasien and g.tt='JEP' 
          where a.ts_calc_stop is null and nama = '$nama'";
     $hasil2 = pg_query($query2);
 
     // cek bila data tidak ditemukan
     if (pg_num_rows($hasil2) == 0) $reply = "Nama pasien tidak ditemukan";
     else
     {
        // bila data ditemukan
        $data2 = pg_fetch_array($hasil2);
        $bangsal = $data2['bangsal'];
        $reply = "Pasien dengan nama: ".$nama. "berada pada bangsal: ".$bangsal;
     }
  }
  else $reply = "Maaf perintah salah";
 
  // membuat SMS balasan
 
  $query3 = "INSERT INTO outbox(DestinationNumber, TextDecoded) VALUES ('$noPengirim', '$reply')";
  $hasil3 = pg_query($query3);
 
  // ubah nilai 'processed' menjadi 'true' untuk setiap SMS yang telah diproses
 
  $query3 = "UPDATE inbox SET Processed = 'true' WHERE ID = '$id'";
  $hasil3 = pg_query($query3);
}


?>