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

$noreg= $_GET['no_reg'];
$nomr=$_GET['no_mr'];
$id_bangsal=$_GET['id_bangsal'];
$tgl=$_GET['tanggal_menu'];
$pagi=$_GET['pagi'];
$siang=$_GET['siang'];
$malam=$_GET['malam'];
$snack1=$_GET['snack1'];
$snack2=$_GET['snack2'];
$pasien=$_GET['pasien'];

pg_query("select nextval('menu_pasien_seq')");

$r = pg_query($con, "select currval('menu_pasien_seq') as no_id");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);

pg_query($con, "INSERT INTO menu_pasien(id,no_reg,no_mr,id_bangsal,pagi,siang,malam,snack_1,snack_2,tgl,jns_pasien) VALUES ($d->no_id,$noreg,'$nomr',$id_bangsal,'$pagi','$siang','$malam','$snack1','$snack2','$tgl','$pasien')");    
header("Location: ../index2.php?p=$PID");
exit;
?>  



