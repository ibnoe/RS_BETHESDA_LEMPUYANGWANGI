<? // Nugraha, 29/03/2004
   // sfdn, 22-04-2004
   // sfdn, 24-12-2006
	// sfdn, 27-12-2006
//session_start();
//if ($_SESSION[uid] == "daftar" || $_SESSION[uid] == "daftarri"  || $_SESSION[uid] == "igd" || $_SESSION[uid] == "root") {

$PID = "order_menu";
$SC = $_SERVER["SCRIPT_NAME"];

unset($_SESSION["IBU"]["id"]);
unset($_SESSION["IBU"]["nama"]);
		
require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

$noid= $_GET['id'];
$pagi=$_GET['pagi'];
$siang=$_GET['siang'];
$malam=$_GET['malam'];
$snack1=$_GET['snack1'];
$snack2=$_GET['snack2'];


pg_query($con, "UPDATE  menu_pasien SET pagi= '$pagi',siang='$siang',malam='$malam',snack_1='$snack1',snack_2='$snack2' where id=".$noid."");    
header("Location: ../index2.php?p=$PID");
exit;
?>  



