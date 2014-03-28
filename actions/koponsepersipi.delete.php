<?php // Nugraha, Wed Jun  2 16:19:25 WIT 2004

session_start();

$PID = "koponsepersipi";

require_once("../lib/dbconn.php");

    $SQL = "delete from rs00016d " .
           "where kode_trans = '".$_GET["kode_trans"]."' ";
    pg_query($con, $SQL);
   
    header("Location: ../index2.php?p=$PID&e=".$_GET["e"]."&o=".$_GET["o"]."&f=kon ");

exit();

?>