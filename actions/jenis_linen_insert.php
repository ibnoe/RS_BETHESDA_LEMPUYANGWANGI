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
require_once("../lib/class.PgTrans.php");


$nama=$_GET['enis_l'];

pg_query("select nextval('jenislinen_seq')");




$r = pg_query($con, "select currval('jenislinen_seq') as no_id");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);

$id_1 = $d->no_id;
$sql2 = "INSERT INTO jenislinen(nama_jenis,id) VALUES('$nama',$id_1)";
     $result = pg_query($con, $sql2);
     if (!$result) {
         die("Error in SQL query: " . pg_last_error());
     }
     header("Location: ../index2.php?p=$PID");
exit;
?> 



