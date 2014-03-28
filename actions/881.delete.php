<?php // Yudha, Wed Jun  2 16:19:25 WIT 2004

session_start();

$PID = "881";

require_once("../lib/dbconn.php");
 

if ($_POST["id"] > 0 ) {
    $SQL = "delete from rs99995 " .
           "where id = ".$_POST["id"];
    pg_query($con, $SQL);
    header("Location: ../index2.php?p=$PID");
    
} 

exit();

?>