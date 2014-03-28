<? // Nugraha, 29/03/2004
   // sfdn, 22-04-2004
   // sfdn, 24-12-2006
	// sfdn, 27-12-2006
//session_start();
//if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "daftarri"  || $_SESSION[uid] == "igd" || $_SESSION[uid] == "root") {

$PID = "120";
$SC = $_SERVER["SCRIPT_NAME"];

unset($_SESSION["IBU"]["id"]);
unset($_SESSION["IBU"]["nama"]);
		
//require_once("lib/dbconn.php");
//require_once("lib/form.php");
//require_once("lib/class.PgTable.php");
//require_once("lib/functions.php");
$db_host = "10.1.9.4";
$db_port = 5432;
$db_user = "postgres";
$db_pass = "1234";
$db_name = "rsud";

$default_page = "login/index.php";

$con = pg_connect("host=$db_host port=$db_port dbname=$db_name user=$db_user password=$db_pass");


$no_menu= $_GET['id_menu'];
$nama_menu=$_GET['nama_menu'];
$bahan=$_GET['bahan'];
 //echo $id."____".$nama;
//pg_query($con, $SQL1);

//$SQL2="INSERT INTO jenis_linen (id, jenis) VALUES('$id' ,'$nama')";

$sql = "INSERT INTO resep_menu(no_menu,nama_menu,bahan) VALUES('$no_menu','$nama_menu','$bahan')";
     $result = pg_query($con, $sql);
     if (!$result) {
         die("Error in SQL query: " . pg_last_error());
     }
   echo "<script type='text/javascript'> 
   var stay=alert('data berhasil di input')
if (!stay)
window.location='http://10.1.9.4/rumahsakit/index2.php?p=standard_menu'
</script>"
;
?> 



