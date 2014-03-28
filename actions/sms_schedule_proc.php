<?php	 // ary, 02 Sept 2010 -> Membuat modul untuk sms broadcast based on Group

session_start();
$PID = "sms_sentitems_broadcast";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

//$tgl_awal = $HTTP_POST_VARS['tgl_awal'];
$dest = $HTTP_POST_VARS['groupid'];
$pesan = $HTTP_POST_VARS['pesan'];
$tablename = 'sms_log';

//Ngambil query untuk menentukan group yang akan dikirim
$r = pg_query($con, "SELECT p.name as nama,p.number as nohp,pg.name as group FROM pbk p,pbk_groups pg
            where p.groupid = pg.id and p.groupid = '$dest'");
$i=0;			
while($d = pg_fetch_array($r))
 {
    $nohp = $d[1];
    $group = $d['group'];
	
$sql = "INSERT into $tablename (destinationnumber,coding,textdecoded)
        values('$nohp','Default_No_Compression','$pesan')";
pg_query($sql);

$words = explode (' ',$HTTP_POST_VARS['pesan']);
$pesan = join('+', $words);
@fopen("http://localhost:13013/cgi-bin/sendsms?user=tester&password=foobar&to=$nohp&text=$pesan","r");
//echo $nohp," ",$pesan;

//apabila data ada dan bisa dieksekusi langsung masuk ke pengiriman

if (!$sql) {
     echo $tr->ErrMsg;
  exit;
} else {
    $_SESSION["dialog"]["title"] = "SMS broadcast telah dikirim kepada $group'";
    $_SESSION["dialog"]["button"][0]["capt"] = " Ok ";
    $_SESSION["dialog"]["button"][0]["href"] = "index2.php?p=$PID";
    header("Location: ../index2.php?p=dialog");
}
}
?>