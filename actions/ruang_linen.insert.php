<? // Nugraha, 29/03/2004
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




pg_query("select nextval('ruang_linen_seq')");
$r = pg_query($con, "select currval('ruang_linen_seq') as no_id");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);

$id_1 = $d->no_id;

$nama= $_POST['nama'];


//echo $_GET['jenis_linen'];

$sql = "INSERT INTO ruang_linen(id,nama_ruang) VALUES($d->no_id,'$nama')";
     $result = pg_query($con, $sql);
     header("Location: ../index2.php?p=$PID");
exit;
?> 



