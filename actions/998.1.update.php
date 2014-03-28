<?php // Nugraha, Sat Jun  5 15:49:44 WIT 2004

session_start();

$PID = "998";

require_once("../lib/dbconn.php");

if (strlen($_POST["description"]) > 0 && strlen($_POST["id"]) > 0) {
    $SQL = "update rs99996 " .
           "set description = '".$_POST["description"]."', harga_paket= '".$_POST["harga"]."' ".
           "where id = '".$_POST["id"]."'";
    pg_query($con, $SQL);
}

header("Location: ../index2.php?p=$PID&id=".$_POST["id"]);
exit;

?>