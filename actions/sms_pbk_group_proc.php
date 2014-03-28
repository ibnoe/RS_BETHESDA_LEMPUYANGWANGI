<?php // Nugraha, Sat Apr 24 16:39:35 WIT 2004
	 //  Ian, 30 Nov 2007 0:56 WIB
	 // ary, 24 Feb 2010 -> Developing database

session_start();
$PID = "sms_pbk_add";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

$group = $HTTP_POST_VARS['group'];
$tr = new PgTrans;
$tr->PgConn = $con;

$tr->addSQL("insert into pbk_groups (name)
        values('$group')");


if ($tr->execute()) {
    $_SESSION["dialog"]["title"] = "Anda telah berhasil menambah group '$group'";
    //$_SESSION["dialog"]["desc"] = "Klik tombol dibawah ini untuk melakukan transaksi lagi. ==".$tgl_2;
    $_SESSION["dialog"]["button"][0]["capt"] = " Ok ";
    $_SESSION["dialog"]["button"][0]["href"] = "index2.php?p=$PID";
    header("Location: ../index2.php?p=dialog");
    exit;
} else {
    echo $tr->ErrMsg;
}

?>