<?php // Nugraha, Sun May  9 15:04:53 WIT 2004
      // sfdn, 01-06-2004

session_start();

$PID = "370";

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/functions.php");
require_once("../lib/class.PgTrans.php");

$flag=$_POST["flag"];
// by yudha 
if (!$_SESSION["BANGSAL"]["id"]) {
	 $_SESSION["BANGSAL"]["id"] = $_POST["kode_bangsal"];
	}
//**

$ts_check_in = date("Y-m-d", mktime(0,0,0,$_POST["tanggalM"],$_POST["tanggalD"],$_POST["tanggalY"])).
    " ".$_POST["jam"].":00";
$jam = (int) substr($_POST["jam"],0,2);

if ($jam >= 12) {
    $ts_calc_start = date("Y-m-d", mktime(0,0,0,$_POST["tanggalM"],$_POST["tanggalD"],$_POST["tanggalY"])).
        " 12:00:00";
} else {
    $ts_calc_start = date("Y-m-d", mktime(0,0,0,$_POST["tanggalM"],$_POST["tanggalD"]-1,$_POST["tanggalY"])).
        " 12:00:00";
}

$SQL = "insert into rs00010 (id, no_reg, bangsal_id, ts_check_in, ts_calc_start, awal) ".
       "values (nextval('rs00010_seq'),'".$_POST["rg"]."',".$_SESSION["BANGSAL"]["id"].
       ",'$ts_check_in'::timestamp,'$ts_check_in'::timestamp, 1)";

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
$tglmasuk= $ts_check_in;

if ($reg_count > 1 ) $baru = "T";

$SQL1 = "insert into rs00008 (id,trans_type, is_inout, qty,  ".
        "		is_baru,no_reg, tanggal_trans,datang_id,trans_group) ".
        "values (nextval('rs00008_seq'),'RIN','I',1, ".
        "'$baru','$noreg','$tglmasuk','$asal', nextval('rs00008_seq_group'))";

// untuk mencatat/mengubah data unit pendaftaran (mis. RJ, IGD), dikarenakan si pasien LANGSUNG dipindahkan ke
// RAWAT INAP, sehingga status loket pendaftarannya harus diubah ke tipe RAWAT INAP, atau rs00006.rawat_inap='Y'
// hal ini dilakukan jika, nilai rs00006.rawat_inap <> dgn. status terakhir, misal, pasien didaftar lewat loket
// IGD, kemudian direkomendasikan untuk dirawat di RAWAT INAP, maka nilai rs00006.rawat_inap harus diubah ke status
// rawat inap
$SQL2 ="update rs00006 set rawat_inap='I', rujukan = '$asal' where id ='".$_POST["rg"]."'";
// akhir tambahan

// ubah loket jadi RIN
// $SQL3 = "update rs00005 set kasir='RIN' where reg='".$_POST["rg"]."' and kasir='IGD' ";

//$SQL3 = "update rs00005 set kasir='RIN' where reg='".$_POST["rg"]."' ";

$SQL3 = "update rs00005 set kasir='RIN' where reg='".$_POST["rg"]."' and (kasir = 'RJL' OR kasir = 'IGD') ";

$SQL4 = "update rs00006 set status='A' where id='".$_POST["rg"]."' ";

$SQL5 = "update rs00006 set flag='$flag' where id='".$_POST["rg"]."' ";
 
unset($_SESSION["BANGSAL"]);
pg_query($con, $SQL);
pg_query($con, $SQL1);
pg_query($con, $SQL2);
pg_query($con, $SQL3);
pg_query($con, $SQL4);
pg_query($con, $SQL5);

 

header("Location: ../index2.php?p=$PID");
exit;
?>


