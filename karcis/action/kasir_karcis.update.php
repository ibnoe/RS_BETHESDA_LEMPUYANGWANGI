<?php // efrizal, 07/01/2011
$PID = "kasir_karcis";

require_once("../lib/dbconn.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

            $ri = pg_query($con,"select harga from master_karcis where id = '".$_POST[idk]."' ");
            $di = pg_fetch_object($ri);
            pg_free_result($ri);
pg_query("update kasir_karcis set poli='".$_POST[idk]."', harga=".$di->harga.", nama='".$_POST[nama]."', alamat='".$_POST[alamat]."' ".
	 	 "where id='".$_POST[idp]."'") or die("error atuh");

header("Location: ../index2.php?p=$PID");
exit;

?>
