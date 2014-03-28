<?php // Nugraha, Sat Apr 24 16:39:35 WIT 2004

session_start();
$PID = "paket_linen";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

pg_query("select nextval('par_seq')");

$r = pg_query($con, "select currval('par_seq') as no_id");
$n = pg_num_rows($r);
if($n > 0) $d = pg_fetch_object($r);
pg_free_result($r);

$tr = new PgTrans;
$tr->PgConn = $con;
if (is_array($_SESSION["ob4"]["obat"])) {
    //$tr->addSQL("select nextval('par_seq')");
    foreach ($_SESSION["ob4"]["obat"] as $v) {
	//echo $d->no_id;
      $SQL =   "insert into par (id_par,nama_bangsal,id_linen) values (".
                "$d->no_id, ".$v["bangsal"].",'".$v["id"]."')";
      $tr->addSQL($SQL );
	//  $sql2="UPDATE linen set status='dipakai' where id=".$v["id"]."";

    }
}

if ($tr->execute()) {
    unset($_SESSION["ob4"]);
    $_SESSION["dialog"]["title"] = "trasnsaksi telah diproses";
    $_SESSION["dialog"]["button"][0]["capt"] = " Ok ";
    $_SESSION["dialog"]["button"][0]["href"] = "index2.php?p=$PID";
    header("Location: ../index2.php?p=dialog");
    exit;
} else {
    echo $tr->ErrMsg;
}

?>
