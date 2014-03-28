<?php // efrizal, 07/01/2011
$PID = "kasir_karcis";

require_once("../lib/dbconn.php");
require_once("../lib/form.php");
require_once("../lib/functions.php");
require_once("../lib/querybuilder.php");
require_once("../lib/class.PgTrans.php");

            $ri = pg_query($con,"select max(id) as id from kasir_karcis ");
            $di = pg_fetch_object($ri);
            pg_free_result($ri);
            $idd = $di->id +1;
            $ro = pg_query($con,"select * from master_karcis where id = '".$_POST[idk]."' ");
            $do = pg_fetch_object($ro);
            pg_free_result($ro);
            $harga = $do->harga;
$SQL = "insert into kasir_karcis (id, nama, alamat, tanggal_reg, poli, harga) ".
       "values (".$idd.",'".$_POST[nama]."','".$_POST[alamat]."',CURRENT_TIMESTAMP::timestamp,'".$_POST[idk]."',".$harga.")";
pg_query($con, $SQL);

header("Location: ../index2.php?p=$PID");
exit;

?>


