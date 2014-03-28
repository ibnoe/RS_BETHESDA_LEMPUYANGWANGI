<?php 
$PID = "akun_periode";

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/functions.php");
require_once("../lib/class.PgTrans.php");

for ($bulan=1;$bulan<=12;$bulan++) {

               if ($_POST["f_tahun"] % 4 == 0){
                    if ($bulan == 4 or $bulan == 6 or $bulan == 9 or $bulan == 11){
                        $tgl = 30;
                    }elseif ($bulan == 2){
                        $tgl = 29;
                    } else {
                        $tgl = 31;
                    }
                } else {
                    if ($bulan == 4 or $bulan == 6 or $bulan == 9 or $bulan == 11){
                        $tgl = 30;
                    }elseif ($bulan == 2){
                        $tgl = 28;
                    } else {
                        $tgl = 31;
                    }
                }
$ca=$_POST["f_max"]+$bulan;
$cu = str_pad(((int) $bulan), 2, "0", STR_PAD_LEFT);
$tanggal=$_POST["f_tahun"]."-".$cu."-".$tgl;
$user_id = $_POST["usr"];
pg_query($con, "insert into master_periode (id, bulan, user_id) values ($ca,'$tanggal'::date,'$user_id')");
}
header("Location: ../index2.php?p=$PID");
exit;
?>


