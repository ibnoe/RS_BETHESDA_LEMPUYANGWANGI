<?php // Nugraha, Wed Jun  2 16:19:25 WIT 2004

session_start();

$PID = "998";

require_once("../lib/dbconn.php");

if ($_GET["id"] > 0 && $_GET["return"] > 0) {
    $SQL = "delete from rs99997 " .
           "where id = ".$_GET["id"];
    pg_query($con, $SQL);
    header("Location: ../index2.php?p=$PID&id=".$_GET["return"]);
    
} elseif ($_GET["id"] > 0) {

    if (empty($_GET[sure])) {
	header("Location: ../index2.php?p=$PID&id=".$_GET[id]."&sure=false");
	exit();
    } elseif ($_GET[sure] == "::YA::") {
    

    $SQL = "delete from rs99997 " .
           "where preset_id = ".$_GET["id"];
    pg_query($con, $SQL);
    $SQL = "delete from rs99996 " .
           "where id = ".$_GET["id"];
    pg_query($con, $SQL);
    header("Location: ../index2.php?p=$PID");

    } else {
    
    header("Location: ../index2.php?p=$PID");
    
    }

}

exit();

?>