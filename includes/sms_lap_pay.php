<?php 
# @Agung S. 29/05/2012 menambahkan laporan Payment
/* @Ary 29/05/2012 menambahkan Requirement : 
	1. Cek jumlah pasien sebulan yang ter register dan tidak melakukan retur
	2. Pengecekan data di lakukan satu bulan sebelumnya
*/

$PID = "sms_lap_pay";
$SC = $_SERVER["SCRIPT_NAME"];

require_once("lib/dbconn.php");
require_once("lib/form.php");
require_once("lib/class.PgTable.php");
require_once("lib/functions.php");
require_once("startup.php");


$f = new Form($SC, "GET", "NAME=Form1");
$f->PgConn = $con;
$f->hidden("p", $PID);
$today=time();
//deklarasi tanggal hari ini
$newto=$today-(3600*24*30);
//tanggal hari ini dikurang satu bulan
//deklarasi waktunya
$tgl1 = date('Y-m-d',time());
$tgl2 = date('Y-m-d',$newto);

$daftar=getFromTable("select count(reg) from rsv_bayar where (tanggal_reg between '$tgl2' and '$tgl1') and klinik_id=".$_SESSION[id_klinik]."");
$pay=getFromTable("select pay_user from rs_id where id_klinik = ".$_SESSION[id_klinik]." ");
// Untuk Tanggal
$r = pg_query($con, "select tanggal('$tgl1'::date,0) as tanggal1, tanggal('$tgl2'::date,0) as tanggal2 ");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);
echo "Transaksi pasien Dari tanggal ".$d->tanggal2." s.d Tanggal ".$d->tanggal1."<br>Jumlah pasien: ".$daftar."<br>Harga ".number_format($pay,2,",",".")."<br>Jumlah Pendapatan ".number_format($pay * $daftar,2,",",".");
?>