<?   // Nugraha, 29/03/2004
   // sfdn, 22-04-2004
   // sfdn, 24-12-2006
	// sfdn, 27-12-2006
//session_start();
//if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "daftarri"  || $_SESSION[uid] == "igd" || $_SESSION[uid] == "root") {

$PID = "ruang_linen";
$SC = $_SERVER["SCRIPT_NAME"];

unset($_SESSION["IBU"]["id"]);
unset($_SESSION["IBU"]["nama"]);

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");;






$nama= $_POST['nama'];
$id=$_POST['id'];

//echo $_GET['jenis_linen'];

$sql = "delete from ruang_linen where id=$id";
     $result = pg_query($con, $sql);
     header("Location: ../index2.php?p=$PID");
exit;
?> 



