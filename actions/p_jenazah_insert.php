<? // Nugraha, 29/03/2004
   // sfdn, 22-04-2004
   // sfdn, 24-12-2006
	// sfdn, 27-12-2006
//session_start();
//if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "daftarri"  || $_SESSION[uid] == "igd" || $_SESSION[uid] == "root") {

$PID = "120";
//$SC = $_SERVER["SCRIPT_NAME"];

unset($_SESSION["IBU"]["id"]);
unset($_SESSION["IBU"]["nama"]);
		
//require_once("lib/dbconn.php");
//require_once("lib/form.php");
//require_once("lib/class.PgTable.php");
//require_once("lib/functions.php");
$db_host = "localhost";
$db_port = 5432;
$db_user = "postgres";
$db_pass = "1234";
$db_name = "rsud";

//$default_page = "login/index.php";

$con = pg_connect("host=$db_host port=$db_port dbname=$db_name user=$db_user password=$db_pass");


$no_reg= $_GET['no_reg'];
$petugas=$_GET['petugas'];
$nama_jen=$_GET['nama_jen'];
$jenis_kel=$_GET['jenis_kel'];
$umur=$_GET['umur'];
$agama=$_GET['agama'];
$alamat=$_GET['alamat'];
$tgl_msk=$_GET['tgl_msk'];
$jam_msk=$_GET['jam_msk'];
$tgl_keluar=$_GET['tgl_keluar'];
$jam_keluar=$_GET['jam_keluar'];
$diagnosa=$_GET['diagnosa'];
 //echo $id."____".$nama;
//pg_query($con, $SQL1);

//$SQL2="INSERT INTO jenis_linen (id, jenis) VALUES('$id' ,'$nama')";

$sql = "INSERT INTO jenazah(no_reg,petugas,nama,jenis_kel,umur,agama,alamat,tgl_msk,jam_msk,tgl_keluar,jam_keluar,diagnosa) VALUES('$no_reg','$petugas','$nama_jen','$jenis_kel','$umur','$agama','$alamat','$tgl_msk','$jam_msk','$tgl_keluar','$jam_msk','$diagnosa')";
     $result = pg_query($con, $sql);
     if (!$result) {
         die("Error in SQL query: " . pg_last_error());
     }
   /*echo "<script type='text/javascript'> 
   var stay=alert('data berhasil di input')
if (!stay)
window.location='http://localhost/rumahsakit_br/index2.php?p=jenazah2'
</script>"
;*/

?> 
<html>
<div align="center"><h1>RUMAH SAKIT UMUM DAERAH XYZ</h1>
					<h2>KOTA ABC</h2>
					jl. Pasteur No.38 ~ Telp. 202020 - 202021 - 202022 ~ ABC<br>
					____________________________________________________________<br><br>
					<b><font size="2">Keterangan Meninggal</b><br></font>
					<br>No. : 123455674</div>
					
					<table>
					<tr><td> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
					<td>Bagian/Divisi</td><td>:<td><td>Instalasi Jenazah</td></tr>
					
					<tr><td></td>
					<td>Ruangan</td><td>:<td><td>VIP 001</td></tr>
					
					<tr><td></td>
					<td>No Rekam Medik</td><td>:<td><td>MR 0025</td></tr>
					
					<tr><td></td>
					<td>Dokter</td><td>:<td><td><?php echo $petugas;?></td>
					<td>NIP</td><td>:</td><td>125125</td></tr>
					
					<tr><td></td>
					<td>Pada tanggal </td><td><td><td><?php echo $tgl_keluar;?> menerangkan bahwa :</td></tr>
					
					<tr><td></td>
					<td>Nama</td><td>:<td><td><?php echo $nama_jen;?></td></tr>
					
					<tr><td></td>
					<td>Jenis Kelamin</td><td>:<td><td><?php echo $jenis_kel;?></td></tr>
					
					<tr><td> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
					<td>Umur</td><td>:<td><td><?php echo $umur;?></td></tr>
					
					<tr><td> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
					<td>Alamat</td><td>:<td><td><?php echo $alamat;?></td></tr>
					
					<tr><td> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
					<td>Agama</td><td>:<td><td><?php echo $agama;?></td></tr>
					
					<tr><td> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
					<td>Tanggal masuk </td><td>:<td><td><?php echo $tgl_msk;?></td>
					<td>Jam masuk </td><td>:<td><td><?php echo $jam_msk;?></td>
					</tr>
					
					<tr><td></td>
					<td>Tanggal meninggal </td><td>:<td><td><?php echo $tgl_keluar;?></td>
					<td>Jam meninggal </td><td>:<td><td><?php echo $jam_keluar;?></td>
					</tr>
					
					<tr><td> </td>
					<td>Diagnosa</td><td>:<td><td><?php echo $diagnosa;?></td></tr>
					
					<tr><td><br><br> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Petugas kamar Jenazah
					</td>
					<td> </td><td><td><td></td>
					<td> </td><td><td><td><br><br>Dokter yang memeriksa</td>
					</tr>
					
					<tr><td><br><br> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; (nama jelas)
					</td>
					<td> </td><td><td><td></td>
					<td> </td><td><td><td><br><br>(nama jelas)</td>
					</tr>
					</table>
					<script type='text/javascript'>self.print();
					var stay=alert('data berhasil di input')
if (!stay)
window.location='http://10.1.9.4/rumahsakit/index2.php?p=p_jenazah2'</script>

					
					
</html>


