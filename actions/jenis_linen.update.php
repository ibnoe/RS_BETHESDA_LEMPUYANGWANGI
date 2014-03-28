<? // Nugraha, 29/03/2004
   // sfdn, 22-04-2004
   // sfdn, 24-12-2006
	// sfdn, 27-12-2006
//session_start();
//if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "daftarri"  || $_SESSION[uid] == "igd" || $_SESSION[uid] == "root") {

$PID = "jenis_linen";
$SC = $_SERVER["SCRIPT_NAME"];

unset($_SESSION["IBU"]["id"]);
unset($_SESSION["IBU"]["nama"]);

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");;


$id=$_GET['id_linen'];
$nama=$_GET['jenis_l'];


$sql = "UPDATE jenislinen SET nama_jenis='$nama'WHERE id=$id";
    $result = pg_query($con, $sql);
     header("Location: ../index2.php?p=$PID");
exit;
?> 



