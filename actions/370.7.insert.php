<?php // Nugraha, Sun May  9 15:04:53 WIT 2004
      // sfdn, 01-06-2004

session_start();

$PID = "370";

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/functions.php");
require_once("../lib/class.PgTrans.php");

/* Posting */

$flag= $_POST["flag"];
$reg = $_POST["rg"];

$skrg = time();
$ts_check_in = date("Y-m-d H:i:s", $skrg);
$tgl = date("d", $skrg);
$bln = date("m", $skrg);
$thn = date("Y", $skrg);
$jam = date("H",$skrg);

$posting= "SELECT posting('".$reg."', '".$PID."', '".$_SESSION['uid']."', true)";
pg_query($posting);
$SQL = "update rs00010 set ts_calc_stop=CURRENT_TIMESTAMP where id = (select max(id) from rs00010 where no_reg = '$reg')";
pg_query($con, $SQL);
/* Daftarkan baru */
// by yudha 
if (!$_SESSION["BANGSAL"]["id"]) {
	 $_SESSION["BANGSAL"]["id"] = $_POST["kode_bangsal"];
	}
$ts_check_in = date("Y-m-d", mktime(0,0,0,$_POST["tanggalM"],$_POST["tanggalD"],$_POST["tanggalY"])).
    " ".$_POST["jam"].":00";
$ts_calc_start =  $ts_check_in;
$SQL = "insert into rs00010 (id, no_reg, bangsal_id, ts_check_in, ts_calc_start) ".
       "values (nextval('rs00010_seq'),'".$_POST["rg"]."','".$_SESSION["BANGSAL"]["id"].
       "','$ts_check_in'::timestamp,'$ts_calc_start'::timestamp)";
// tambahan sfdn, 31-05-2004
$r1 = pg_query($con,
    "select tipe, jenis_kedatangan_id as rujukan, id as no_reg, tanggal_reg, rawat_inap ".
    "from rs00006 ".
    "where id = '".$_POST["rg"]."'");
$n1 = pg_num_rows($r1);
if($n1 > 0) $d1 = pg_fetch_object($r1);
pg_free_result($r1);
$reg_count = getFromTable("select count(mr_no) from rs00006 ".
            "where mr_no = (select mr_no from rs00006 where id = '".$_POST["rg"]."') ".
            "   and id <= '".$_POST["rg"]."'");       
$baru    = "Y";
$noreg   = $_POST["rg"];
$bangsal = $_SESSION["BANGSAL"]["id"];
$asal	 = $_POST["asal"];

if ($reg_count > 1 ) $baru = "T";
$SQL2 = "update rs00006 set flag='$flag' where id='".$_POST["rg"]."'"; 
unset($_SESSION["BANGSAL"]);
pg_query($con, $SQL);
//pg_query($con, $SQL1);
pg_query($con, $SQL2);   

header("Location: ../index2.php?p=$PID");
exit;
?>


