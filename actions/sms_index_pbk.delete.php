<?php // Nugraha, 18/02/2004
	  // Pur, 27/02/2004


$PID = "sms_index_pbk";

require_once("../lib/dbconn.php");
$number=$_GET['number'];
pg_query("DELETE FROM pbk  WHERE number='".$number."'");

header("Location: ../index2.php?p=$PID");
exit;
/*if (empty($_GET[sure])) {
	
        header("Location: ../index2.php?p=$PID&n=" . $_GET["n"] ."&sure=false");
	exit();

} elseif ($_GET[sure] == "YA") {

         $SQL = "delete from pbk where ".
              "number = '".$_GET["n"]."' ";

} else {

    header("Location: ../index2.php?p=$PID");
    exit();

}



pg_query($con, $SQL);


header("Location: ../index2.php?p=$PID&sort=".$_GET[sort].
	"&order=".$_GET[order]."&tblstart=".$_GET[tblstart]);
exit;*/

?>
